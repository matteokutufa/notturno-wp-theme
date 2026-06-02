# notturno — tema WordPress

Porting WordPress del tema editoriale **notturno**: dark/light automatico (alba/tramonto),
tipografia classica (Cormorant Garamond + Geist + JetBrains Mono), menù in francese,
pensato per un blog personale testuale multilingua.

## Installazione

1. Comprimi la cartella `notturno/` in `notturno.zip` (o copiala in `wp-content/themes/`).
2. **Aspetto → Temi → Aggiungi nuovo → Carica tema** → seleziona lo zip → **Attiva**.
3. **Aspetto → Personalizza** per logo, titolo, descrizione.

## Configurazione iniziale

### Categorie
Crea le categorie in **Articoli → Categorie**, con questi slug:
`projets`, `idees`, `liens`, `journal`. I titoli possono restare in francese.

### Menù (in francese)
**Aspetto → Menu** → crea un menu, assegnalo alla posizione **"Menu principal (français)"**.
Voci suggerite: Accueil · Projets · Idées · Liens · Journal · À propos · Contact.
Se non configuri nulla, il tema mostra un menù francese di default.

### Homepage
**Impostazioni → Lettura → "Una pagina statica"** se vuoi la home editoriale (`front-page.php`
è comunque usata in automatico). Altrimenti la home mostra gli ultimi articoli.

### Pagine speciali
Crea due pagine e assegna il template (menù a destra in fase di modifica pagina):
- Pagina **"Archives"** (slug `archives`) → template *Archives (cronologia)*
- Pagina **"Étiquettes"** (slug `etiquettes`) → template *Étiquettes (indice tag)*

### About & Contact
- **À propos**: pagina normale, usa l'editor a blocchi.
- **Contact**: crea una pagina e incolla lo **shortcode** di un plugin form
  (Contact Form 7, WPForms, Fluent Forms). Eredita lo stile del tema.
  Per la destinazione email configurabile, imposta il "To" nel plugin —
  esattamente il requisito "casella di destinazione configurabile".

## Multilingua (IT base · FR · EN)

Il tema è **translation-ready** e integrato con **Polylang** (consigliato, gratuito):

1. Installa **Polylang** → aggiungi le lingue Italiano (default), Français, English.
2. Lo switcher in header (`IT · FR · EN`) si popola automaticamente via `pll_the_languages()`.
3. Le voci di menù restano in **francese** in tutte le lingue, come da design — basta
   assegnare lo stesso menu francese a tutte le lingue, oppure un solo menu non tradotto.
4. I link "secondari" (Archivio, Etichette, Cerca, RSS) seguono la lingua del sito
   tramite le stringhe traducibili del tema (text-domain `notturno`).

> Alternativa: **WPML** funziona allo stesso modo per i contenuti; lo switcher di lingua
> usa il fallback statico in header — sostituisci gli href in `functions.php`
> (`notturno_language_switcher`).

### Stringhe del tema
Le etichette UI sono in `__( '…', 'notturno' )`. Per tradurle:
- con Polylang: usa **Loco Translate** o il pannello "Traduzioni stringhe";
- genera un `.pot` da `languages/` con WP-CLI: `wp i18n make-pot . languages/notturno.pot`.

## Tema chiaro/scuro

Switch automatico in base all'orario del visitatore (chiaro 06:30→19:30, scuro altrimenti),
override manuale **Auto / Jour / Nuit** dal pulsante in header, persistito in `localStorage`.
Nessun flash al caricamento: `data-theme` viene impostato inline in `<head>`.

## Struttura file

```
notturno/
├─ style.css              # header tema + CSS completo (variabili, dark/light)
├─ theme.json             # palette + tipografia per l'editor a blocchi
├─ functions.php          # setup, enqueue, menu, helper editoriali, switcher lingua
├─ assets/ui.js           # switch tema + menu mobile
├─ header.php · footer.php
├─ front-page.php         # Accueil (hero, featured, tag cloud)
├─ index.php              # fallback listing
├─ archive.php            # categorie + archivi (indice editoriale)
├─ single.php             # articolo (sommario, drop-cap, tag)
├─ tag.php                # dettaglio etichetta
├─ search.php             # ricerca
├─ page.php               # pagine statiche
├─ page-archives.php      # template "Archives"
├─ page-etiquettes.php    # template "Étiquettes"
├─ comments.php
└─ 404.php
```

## Note

- I caratteri sono caricati da Google Fonts. Per il self-hosting (GDPR/performance),
  scarica i woff2 e sostituisci l'enqueue in `functions.php` con un `@font-face` locale.
- Il drop-cap usa `::first-letter` sul primo paragrafo dell'articolo — automatico.
- Le immagini di copertina usano l'**immagine in evidenza** del post.
- Per il numero "№" editoriale, il tema usa l'ID del post: è stabile ma non sequenziale.
