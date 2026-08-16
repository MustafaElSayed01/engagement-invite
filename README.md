# Nour El Din & Mariam — Engagement Invitation

Vanilla HTML/CSS/JS + tiny PHP RSVP backend. No framework, no build step, no database.

## 1. Edit the invitation
Open `config.js` and change:
- names
- event date/time
- venue/city
- Google Maps URL
- theme colors
- optional music path

The main palette is `#E8D8E8` and all site colors are centralized in `config.js`.

## 2. Images
Only the two supplied images are used:
- `assets/couple-illustration.jpg`
- `assets/islamic-invitation.jpg`

There is no photo gallery and no requirement for real couple photos.

## 3. RSVP
Upload to a PHP-enabled host. Responses are saved to `responses.jsonl`.

Dashboard: `/admin/`

For a public deployment, protect `/admin/` with server-side password authentication.

## 4. Local test
With PHP installed, from this folder run:

`php -S localhost:8000`

Then open `http://localhost:8000`.
