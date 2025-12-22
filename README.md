# 2025 FrankJamison.com

Personal website/portfolio for Frank Jamison.

This repo is intentionally “simple web”: mostly static HTML/CSS/JS with a small amount of PHP for the contact form. There is no build step, no package manager, and no framework—what you see in the repo is what gets served.

## Quick start (local)

### Option A (recommended): Apache + PHP (XAMPP / Laragon / WAMP)

This project is already wired for a local virtual host at:

- `http://2025frankjamison.localhost/`

VS Code includes a task that opens that URL:

- `.vscode/tasks.json` → **Open in Browser**

Suggested vhost configuration (Apache):

- **ServerName**: `2025frankjamison.localhost`
- **DocumentRoot**: `D:/Websites/2025-FrankJamison`

Windows hosts file entry (run as admin):

- File: `C:\Windows\System32\drivers\etc\hosts`
- Add:
  - `127.0.0.1  2025frankjamison.localhost`

### Option B: PHP built-in server (fastest if you don’t need email)

From the repo root:

```bash
php -S localhost:8000 -t .
```

Then open:

- `http://localhost:8000/`

Notes:
- The contact form uses PHP’s `mail()` which typically **won’t work** out-of-the-box on Windows without configuring SMTP/sendmail.
- The site is designed around the `2025frankjamison.localhost` vhost (see Option A).

## How the site works

### Main entrypoints

- `index.php`
  - The main single-page site (scrolling sections: intro, about, skills, portfolio, goals, contact).
  - Contains the contact form logic at the top of the file **and** the HTML for the entire page.

- `contact.php`
  - Legacy/alternate contact form handler (expects `comments` instead of `comment`).
  - Not used by the main form on `index.php` (the main form posts back to `index.php`).

### Contact form flow

The contact form is server-rendered and posts back to the same page:

- `<form action="<?= $_SERVER['PHP_SELF'] . '#contact' ?>" method="post">`

On submit:
1. PHP reads `firstName`, `lastName`, `emailAddress`, `comment`.
2. Input is validated using regex + helper functions from `includes/functions.inc.php`.
3. If valid, it sends an email via PHP `mail()` to `frank@frankjamison.com`.
4. The page shows a “Thank you…” message.
5. A `refresh` header is sent that redirects the browser after 5 seconds.

Important note for local dev:
- The redirect target is hard-coded to production:
  - `http://frankjamison.com/index.php#contact`
- If you submit the form locally, your browser may jump to the live site.
  - For local testing, temporarily comment out or adjust that header.

### JavaScript behavior

Most interactivity is handled by `js/front.js`:
- Lightbox support via `ekko-lightbox`.
- Sticky header behavior.
- Smooth scrolling for the nav (`.scroll-to` and `#navigation a`).

The repo also includes `js/gmaps.js` (GMaps.js library), but Google Maps is currently commented out in `index.php`.

## Project structure

Top-level:

- `index.php` — Main page + contact form logic
- `contact.php` — Legacy/alternate contact handler
- `includes/` — Small PHP helpers
- `css/` — Stylesheets (Bootstrap/theme + custom overrides)
- `js/` — Front-end JS (jQuery plugins + site scripts)
- `img/` — Images

Archived content:

- This repo previously included a `portfolio/` folder with older course projects and historical site snapshots.
- That archived folder is no longer part of the current working tree.
  - If you need it back, restore it from Git history (or keep it in a separate archive repo/folder).

### `includes/`

- `includes/functions.inc.php`
  - `fieldValidation($regex, $value)` returns the original value or `false`
  - `clean_string($value)` strips suspicious header-like strings

### `css/`

Contains vendor/theme CSS plus site overrides:
- `bootstrap*.css`, `font-awesome.css`, `style.default.css`, etc.
- `custom.css` is the intended place for project-specific tweaks.

## Common tasks

- Open locally in Chrome: run the VS Code task **Open in Browser**.
- Update content: edit `index.php` (most content is inline in the markup).
- Update images: add/replace files in `img/` and update `<img src="...">` references.
- Update styles: prefer changes in `css/custom.css`.

## Troubleshooting

### “I’m seeing the 2013 site / old content”

That’s almost always a local server configuration issue, not PHP “mixing” files:

- Verify your vhost `DocumentRoot` points to `D:/Websites/2025-FrankJamison`.
- Confirm the URL you’re opening:
  - `http://2025frankjamison.localhost/` (2025 site)
  - If you have other local vhosts (older site versions), make sure you’re not hitting the wrong host name.

### Contact form not sending email

- PHP `mail()` depends on server configuration.
  - On Windows, you typically need to configure SMTP in `php.ini` or use a local mail catcher.

### Assets not loading / broken styling

- Confirm the site is being served from the repo root (so relative paths like `css/custom.css` resolve).
- If you move the site into a subfolder, you’ll need to adjust relative links.

## Notes

- No build step: deploy by serving the folder from a PHP-capable web server.
- This repo intentionally avoids adding new dependencies unless necessary.
