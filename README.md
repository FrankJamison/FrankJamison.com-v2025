# FrankJamison.com (2025–2026) — Portfolio Site

Hi — I’m Frank Jamison. This repository contains the source for my personal portfolio site.

I built it as a deliberately “simple web” project (PHP + HTML/CSS/JS) with no framework and no build step. The goal is to keep the site fast to load, easy to host, and easy to maintain while still demonstrating solid front-end structure and basic backend form handling.

## 2026 refresh highlights

As of January 2026, I’ve done a small “polish + hardening” pass focused on accessibility, SEO, and form abuse prevention:

- **Accessibility:** improved keyboard/mobile navigation behavior, focus visibility, and reduced-motion handling.
- **SEO:** expanded structured data (JSON-LD) and added profile links via `sameAs`.
- **Contact form:** added lightweight abuse controls (rate limiting + token/timing).
- **Repo cleanup:** removed unused vendored scripts so `js/` better reflects what’s actually used.

## What I’m demonstrating here

- **One-page portfolio UX** with a sticky navigation bar and smooth-scrolling section navigation.
- **Responsive layout** using Bootstrap’s grid system.
- **A consistent visual design** (hero background + overlay, clear section rhythm, “inverse” dark sections for contrast).
- **Practical server-side fundamentals**: validation, safe-ish email composition, and simple sanitization against header injection.

## Tech stack

**Server-side**
- PHP (no framework)
- Contact form uses PHP `mail()`

**Front-end**
- Bootstrap 3 (layout + components) (CDN)
- Font Awesome (icons) (CDN)
- Google Fonts (Montserrat + Cardo)
- jQuery (CDN)
- Plugins (vendored in `js/`):
  - Ekko Lightbox (`js/ekko-lightbox.js`)
  - Sticky header plugin (`js/jquery.sticky.js`)
  - Smooth scroll helper (`js/jquery.scrollTo.min.js`)

**No build tooling**
- No npm, no bundler, no transpilation. Assets are served directly.

## Site structure (information architecture)

The site is implemented as a single-page layout in [index.php](index.php):

- `#intro` — Full-screen hero with background image overlay.
- `#about` — Bio + portrait.
- `#services` — “Skills” cards (icon + title + short description).
- `#portfolio` — Gallery tiles that link to external sites.
- `#goals` — Long-form narrative section.
- `#contact` — Contact form.

## Design & UX implementation details

**Layout + responsiveness**
- I use Bootstrap’s grid (`.col-md-*`, `.col-sm-*`) to keep layout predictable across breakpoints.
- The navigation collapses into the standard Bootstrap mobile menu.

**Hero treatment**
- `#intro` uses a background image plus an `.overlay` layer to keep text readable.
- The hero is full height with a `min-height` guard so it doesn’t collapse on smaller viewports.

**Theme styling**
- Primary theme styles live in [css/style.default.css](css/style.default.css).
- Site-specific tweaks live in [css/custom.css](css/custom.css) (portfolio tile title styling, honeypot field hidden in the contact form, etc.).

**Navigation behavior**
- Sticky header behavior is enabled by `js/front.js` + `js/jquery.sticky.js`.
- Smooth scrolling uses `js/jquery.scrollTo.min.js` targeting `.scroll-to` and `#navigation a`.

**Lightbox support**
- I included Ekko Lightbox support in `js/front.js` (delegated click handler).
- Current portfolio tiles open external websites (not lightbox), but the lightbox wiring is there for image-gallery style use.

## Contact form (backend + security basics)

The contact form posts back to the same page:

- Form action: `$_SERVER['PHP_SELF'] . '#contact'`
- Submit key: `submit`

**Validation approach**
- Regex validation is done via `fieldValidation()` in [includes/functions.inc.php](includes/functions.inc.php).
- I sanitize strings via `clean_string()` (removes tokens like `bcc:`, `content-type`, `href`, etc.).

**Email delivery**
- Destination: `frank@frankjamison.com`
- Subject: `FrankJamison.com Contact Form Submission`
- Uses `@mail(...)` with `From`/`Reply-To` headers derived from the submitted email.

**Spam prevention**
- The form includes a visually hidden “honeypot” field (CSS `display: none`) intended to catch bots.
- Lightweight abuse controls are also implemented server-side:
  - A session-backed form token + minimum-submit-time check to reduce automated posts
  - Best-effort IP/user-agent rate limiting (file-based) to slow repeated submissions

## Local development

### Option A (recommended on Windows): Apache + PHP (XAMPP / Laragon / WAMP)

This workspace is set up for a local virtual host:
- `http://2025frankjamison.localhost/`

VS Code includes a task that opens that URL:
- [Open in Browser task](.vscode/tasks.json)

Typical Apache vhost values:
- `ServerName`: `2025frankjamison.localhost`
- `DocumentRoot`: the repo folder (this workspace is `D:/Websites/2025FrankJamison`)

Hosts entry (run as admin):
- Edit `C:\Windows\System32\drivers\etc\hosts`
- Add: `127.0.0.1  2025frankjamison.localhost`

### Option B: PHP built-in server

From the repo root:

```bash
php -S localhost:8000 -t .
```

Then open `http://localhost:8000/`.

Note: PHP `mail()` typically won’t work out-of-the-box on Windows without SMTP/sendmail configuration.

## Deployment

There is no build step, so deployment is a straight copy:

- Copy the repository to your web host.
- Point the document root at the repo folder.
- Ensure PHP is enabled.
- Ensure the host supports email sending via PHP `mail()` (or swap to an SMTP-based approach).

## Project structure

- [index.php](index.php): main page + contact form handling
- [contact.php](contact.php): older/alternate contact handler (expects `comments`; not used by the main form)
- [includes/functions.inc.php](includes/functions.inc.php): validation + sanitization helpers
- [css/](css): theme and custom styling
- [js/](js): site behavior + vendored plugins
- [img/](img): images and portfolio thumbnails

## Customization (developer handoff)

**Update content**
- Edit [index.php](index.php) section-by-section (`#about`, `#services`, `#portfolio`, `#goals`).

**Update portfolio tiles**
- Replace/add thumbnails in [img/](img).
- Update the anchors and images in the `#portfolio` section in [index.php](index.php).

**Update styling**
- Put site-specific tweaks in [css/custom.css](css/custom.css).
- If you need larger theme changes, adjust [css/style.default.css](css/style.default.css).

**Update behavior**
- Site behavior is in `js/front.js`.
- Most other files in `js/` are third-party plugins.

## Accessibility & SEO improvements I’d make next

I’ve already implemented the “quick wins” (clean heading hierarchy, improved meta tags, better alt text on portfolio thumbnails, better form error handling, JSON-LD structured data, visible focus styles, and reduced-motion handling across the site’s key interactions). If I were hardening this further, these are the next improvements I’d tackle:

- **Keyboard/mobile nav UX:** continue auditing real-device tab order and edge-case screen-reader behavior.
- **Structured data validation:** JSON-LD now includes `sameAs` profile links; validate via Google’s Rich Results Test and address any warnings.
- **Abuse controls:** implemented basic rate limiting + form token/timing; add CAPTCHA only if needed.

## Known implementation notes (for maintainers)

These are the remaining “worth knowing” items after cleanup:

- **jQuery local fallback:** the site uses CDN jQuery with a local fallback at `js/jquery.min.js`.
- **Repo cleanup:** unused vendored scripts were removed (e.g., old jQuery copies, `jquery.cookie.js`, `jquery.stellar.min.js`, `main.js`, `respond.min.js`, `gmaps.js`).
- **Bootstrap JS:** the site currently loads Bootstrap JS from a CDN; `js/bootstrap.min.js` remains in the repo and can be removed if I don’t need an offline fallback.

## Credits / third-party assets

This repo uses third-party libraries via CDNs and the `css/` + `js/` folders (Bootstrap, Font Awesome, jQuery, and plugins). Refer to upstream licenses and documentation as needed.

