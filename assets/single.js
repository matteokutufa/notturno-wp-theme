/* notturno — single post UI: copy link, details toggle, table of contents.
 * Strings are localized via wp_localize_script (notturnoSingle). */
(function () {
  "use strict";

  var i18n = window.notturnoSingle || {
    showDetails: "Mostra dettagli",
    hideDetails: "Nascondi dettagli",
    copied: "Copiato",
    copyLink: "Copia link"
  };

  document.addEventListener("DOMContentLoaded", function () {
    /* --- copy link --- */
    var copyButton = document.getElementById("share-copy-link");
    if (copyButton) {
      var copyLabel = copyButton.querySelector(".post-share-label");
      copyButton.addEventListener("click", function () {
        var link = copyButton.getAttribute("data-url") || window.location.href;
        var onSuccess = function () {
          copyButton.classList.add("is-copied");
          if (copyLabel) copyLabel.textContent = i18n.copied;
          window.setTimeout(function () {
            copyButton.classList.remove("is-copied");
            if (copyLabel) copyLabel.textContent = i18n.copyLink;
          }, 1600);
        };

        if (navigator.clipboard && navigator.clipboard.writeText) {
          navigator.clipboard.writeText(link).then(onSuccess).catch(function () {});
          return;
        }

        // Legacy fallback (execCommand is deprecated but still the only
        // option on non-secure contexts / very old browsers).
        var input = document.createElement("input");
        input.value = link;
        document.body.appendChild(input);
        input.select();
        try {
          document.execCommand("copy");
          onSuccess();
        } catch (err) {}
        document.body.removeChild(input);
      });
    }

    /* --- details toggle (sidebar meta on narrow screens) --- */
    var metaToggle = document.getElementById("post-meta-toggle");
    var sideExtra = document.getElementById("post-side-extra");
    var isNarrow = window.matchMedia("(max-width: 1080px)");

    var syncMetaVisibility = function () {
      if (!metaToggle || !sideExtra) return;

      if (isNarrow.matches) {
        sideExtra.setAttribute("data-collapsed", "true");
        metaToggle.setAttribute("aria-expanded", "false");
        metaToggle.textContent = i18n.showDetails;
        return;
      }

      sideExtra.setAttribute("data-collapsed", "false");
      metaToggle.setAttribute("aria-expanded", "true");
      metaToggle.textContent = i18n.hideDetails;
    };

    if (metaToggle && sideExtra) {
      syncMetaVisibility();
      if (isNarrow.addEventListener) {
        isNarrow.addEventListener("change", syncMetaVisibility);
      } else if (isNarrow.addListener) {
        isNarrow.addListener(syncMetaVisibility);
      }

      metaToggle.addEventListener("click", function () {
        var collapsed = sideExtra.getAttribute("data-collapsed") === "true";
        sideExtra.setAttribute("data-collapsed", collapsed ? "false" : "true");
        metaToggle.setAttribute("aria-expanded", collapsed ? "true" : "false");
        metaToggle.textContent = collapsed ? i18n.hideDetails : i18n.showDetails;
      });
    }

    /* --- table of contents --- */
    var content = document.querySelector(".entry-content");
    var outline = document.getElementById("post-outline");
    var outlineBlock = document.getElementById("post-outline-block");
    var list = document.getElementById("post-outline-list");
    if (!content || !outline || !list || !outlineBlock) return;

    var headings = content.querySelectorAll("h2, h3, h4, h5, h6");
    if (!headings.length) {
      outlineBlock.style.display = "none";
      return;
    }

    // Accent-safe slugify: "Élégie d'été" -> "elegie-d-ete"
    var slugify = function (text) {
      return text
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "")
        .toLowerCase()
        .trim()
        .replace(/[\s\W-]+/g, "-")
        .replace(/^-+|-+$/g, "");
    };

    headings.forEach(function (heading, idx) {
      if (!heading.id) {
        heading.id = "section-" + slugify(heading.textContent || "part") + "-" + idx;
      }

      var item = document.createElement("li");
      item.className = "post-outline-item level-" + heading.tagName.toLowerCase();

      var link = document.createElement("a");
      link.href = "#" + heading.id;
      link.textContent = heading.textContent;

      item.appendChild(link);
      list.appendChild(item);
    });
  });
})();
