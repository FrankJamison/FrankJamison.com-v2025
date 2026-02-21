# FrankJamison.com v2025

Developer-focused source for the Frank Jamison portfolio site. This is intentionally a “simple web” project (PHP + HTML/CSS/JS) with no framework and no build step — deploy by copying files.

## Quick start

### Run locally (recommended)

This repo is set up to run as a local vhost at:

- `http://frankjamisoncomv2025.localhost/`

VS Code includes a task that opens that URL:

- [Open in Browser task](.vscode/tasks.json)

Typical Windows vhost setup (Laragon / WAMP / XAMPP / IIS + PHP):

- `ServerName`: `frankjamisoncomv2025.localhost`
- `DocumentRoot`: this repo folder (`D:\Websites\034-2025-FrankJamison.com-v2025`)

Hosts entry (run as admin):

- Edit `C:\Windows\System32\drivers\etc\hosts`
- Add: `127.0.0.1  frankjamisoncomv2025.localhost`

### Run locally (PHP built-in server)

From the repo root:

```bash
php -S localhost:8000 -t .
```

Then open `http://localhost:8000/`.

Note: PHP `mail()` typically will not work out-of-the-box on Windows without SMTP/sendmail configuration.

## Requirements

- PHP 7.0+ (code uses `??` and `random_bytes()`)
- Any web server that can run PHP (Apache/Nginx/IIS) or PHP’s built-in server for quick local testing
- If you want the contact form to send mail: a host/environment with a working `mail()` transport (or update the implementation to use SMTP)

## Tech stack

**Server-side**

- PHP (no framework, no build tooling)
- Contact form sends mail via PHP `mail()`

**Front-end**

- Bootstrap 3 (CDN)
- Font Awesome 4 (CDN)
- Google Fonts (Montserrat + Cardo)
- jQuery (CDN) with a local fallback to `js/jquery.min.js`
- Vendored plugins:
  - Ekko Lightbox (`js/ekko-lightbox.js`)
  - Sticky header plugin (`js/jquery.sticky.js`)
  - Smooth scroll helper (`js/jquery.scrollTo.min.js`)

## Entry points

- [index.php](index.php): single-page portfolio + contact form handling
- [contact.php](contact.php): legacy contact handler (expects `comments`; not used by the main form)

## Information architecture (sections)

The site is a single-page layout implemented in [index.php](index.php) with these anchors:

- `#intro` — hero
- `#about` — bio
- `#services` — skills
- `#portfolio` — project tiles
- `#goals` — narrative section
- `#contact` — contact form

## Front-end behavior (where to change things)

**Sticky header + scrolling**

- Implemented in `js/front.js` using `js/jquery.sticky.js` and `js/jquery.scrollTo.min.js`.
- Smooth scroll is disabled when the user has “reduced motion” enabled.

**Accessibility touches**

- Visible focus styles and reduced-motion CSS live in [css/custom.css](css/custom.css).
- Mobile nav keyboard behavior (Escape to close, focus management, `aria-expanded` sync) is in `js/front.js`.

**Lightbox**

- Wiring exists via Ekko Lightbox in `js/front.js` for any element with `data-toggle="lightbox"`.

## Contact form (server-side)

The contact form posts back to [index.php](index.php) and either re-renders with inline errors or hides the form on “success”.

### Key config

In [index.php](index.php), update:

- `$emailTo` (destination)
- `$emailSubject`

### Validation + sanitization

- Regex validation uses `fieldValidation()` in [includes/functions.inc.php](includes/functions.inc.php).
- Header-injection string stripping uses `clean_string()` in [includes/functions.inc.php](includes/functions.inc.php).
- Email is additionally checked with `FILTER_VALIDATE_EMAIL`.

### Abuse controls (best-effort)

These are intentionally lightweight, not a full authentication/CSRF solution:

- Session token (`$_SESSION['contact_form_token']`) checked with `hash_equals()`
- Minimum submit time: rejects posts submitted in under ~2 seconds
- Honeypot field: `specialInstructions` (hidden via `.special-instructions { display: none; }`)
- File-based rate limiting (via `sys_get_temp_dir()`): currently `5` attempts per `3600` seconds, plus a minimum `8` seconds between attempts per IP + user-agent fingerprint

If you change rate limiting, look for the call to `rate_limit_check_and_record(...)` in [index.php](index.php).

### Email delivery notes

- The implementation sets `From:` and `Reply-To:` to the submitted email address.
- Some hosts reject mail with an untrusted `From:` domain; if deliverability is an issue, switch to a fixed `From:` address at your domain and keep the user email in `Reply-To:`.

## Styling

- Base theme styles: [css/style.default.css](css/style.default.css)
- Site-specific overrides: [css/custom.css](css/custom.css)
- Bootstrap is loaded from a CDN; local copies exist in [css/](css) as a fallback/for reference.

## Assets

- Images and thumbnails: [img/](img)
- `img/originals/` contains source images; optimized assets live alongside the site images.

## Deployment

No build step. Deployment is a straight copy:

1. Upload/copy the repository to your PHP-capable host
2. Point the web root at the repo folder
3. Ensure PHP sessions work (contact form relies on sessions for abuse controls)
4. Ensure outbound mail is configured if you want the contact form to send messages

## Troubleshooting

- **Contact form never sends mail locally (Windows):** expected unless PHP mail transport is configured. Test on a host with working SMTP/sendmail.
- **Contact form always says “refresh and try again”:** sessions are failing or the form token isn’t persisting; confirm cookies are enabled and the server can write session data.
- **Rate limit triggers unexpectedly:** rate limiting keys off IP + user-agent and stores counters in the system temp directory; confirm the PHP process can write to `sys_get_temp_dir()`.

## Third-party assets

This repo uses third-party libraries via CDNs and the `css/` + `js/` folders (Bootstrap, Font Awesome, jQuery, and plugins). Refer to upstream licenses/documentation as needed.
