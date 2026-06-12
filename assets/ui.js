/* notturno — UI: switch tema (auto/jour/nuit) + menu mobile */
(function () {
  "use strict";

  function autoFromTime() {
    var d = new Date();
    var h = d.getHours() + d.getMinutes() / 60;
    return h >= 6.5 && h < 19.5 ? "light" : "dark";
  }

  function resolve(mode) {
    return mode === "auto" ? autoFromTime() : mode;
  }

  function apply(mode) {
    var r = resolve(mode);
    document.documentElement.setAttribute("data-theme", r);
    if (mode === "auto") localStorage.removeItem("notturno.theme");
    else localStorage.setItem("notturno.theme", mode);
    return r;
  }

  document.addEventListener("DOMContentLoaded", function () {
    var btn = document.querySelector("[data-theme-toggle]");
    var saved = localStorage.getItem("notturno.theme") || "auto";
    var mode = saved;

    function label() {
      var r = resolve(mode);
      var icon = r === "light" ? "☀" : "☾";
      var word = mode === "auto" ? "Auto" : mode === "light" ? "Jour" : "Nuit";
      if (btn) btn.innerHTML = "<span>" + icon + "</span><span>" + word + "</span>";
    }

    apply(mode);
    label();

    if (btn) {
      btn.addEventListener("click", function () {
        mode = mode === "auto" ? "light" : mode === "light" ? "dark" : "auto";
        apply(mode);
        label();
      });
    }

    // riallinea automaticamente al passaggio alba/tramonto se in auto
    setInterval(function () {
      if ((localStorage.getItem("notturno.theme") || "auto") === "auto") {
        apply("auto");
        label();
      }
    }, 5 * 60 * 1000);

    // menu mobile (a11y: aria-expanded synced with state, Escape closes)
    var burger = document.querySelector("[data-burger]");
    var menu = document.querySelector("[data-mobile-menu]");
    if (burger && menu) {
      var setMenu = function (open) {
        menu.classList.toggle("open", open);
        burger.setAttribute("aria-expanded", open ? "true" : "false");
      };
      setMenu(menu.classList.contains("open"));

      burger.addEventListener("click", function () {
        setMenu(!menu.classList.contains("open"));
      });
      document.addEventListener("keydown", function (event) {
        if (event.key === "Escape" && menu.classList.contains("open")) {
          setMenu(false);
          burger.focus();
        }
      });
    }

    // modal ricerca
    var searchOpen = document.querySelector("[data-search-open]");
    var searchModal = document.querySelector("[data-search-modal]");
    var searchInput = document.querySelector("[data-search-input]");
    var searchClose = document.querySelectorAll("[data-search-close]");

    function openSearch() {
      if (!searchModal) return;
      searchModal.hidden = false;
      document.documentElement.classList.add("search-modal-open");
      document.body.classList.add("search-modal-open");
      window.setTimeout(function () {
        if (searchInput) searchInput.focus();
      }, 20);
    }

    function closeSearch() {
      if (!searchModal) return;
      searchModal.hidden = true;
      document.documentElement.classList.remove("search-modal-open");
      document.body.classList.remove("search-modal-open");
      if (searchOpen) searchOpen.focus();
    }

    if (searchOpen && searchModal) {
      searchOpen.addEventListener("click", openSearch);
      searchClose.forEach(function (node) {
        node.addEventListener("click", closeSearch);
      });
      document.addEventListener("keydown", function (event) {
        if (event.key === "Escape" && !searchModal.hidden) {
          closeSearch();
        }
      });

      // simple focus trap inside the dialog while open
      document.addEventListener("keydown", function (event) {
        if (event.key !== "Tab" || searchModal.hidden) return;
        var focusables = searchModal.querySelectorAll(
          'a[href], button:not([disabled]), input, [tabindex]:not([tabindex="-1"])'
        );
        if (!focusables.length) return;
        var first = focusables[0];
        var last = focusables[focusables.length - 1];
        if (event.shiftKey && document.activeElement === first) {
          event.preventDefault();
          last.focus();
        } else if (!event.shiftKey && document.activeElement === last) {
          event.preventDefault();
          first.focus();
        }
      });
    }
  });
})();
