# Ang Bantayog — WordPress Theme v3.0.0

Official theme for **ANG BANTAYOG**, the school paper of Libertad National High
School, Surallah, South Cotabato.

Version 3.0 turns the paper into a full online publishing platform. The feature
set is drawn from four reference sites, combined into one theme:

| Source | What was taken |
|---|---|
| **dost.gov.ph** | Announcement ticker, document/transparency library with reference numbers, accessibility toolbar (text size + high contrast), partner logo strip, rotating hero banner |
| **rappler.com** | Fact Check content type with verdicts, mega-menu navigation, newsletter call-out, reading progress bar, share bar, author box, related posts, topic hubs |
| **insiderph.com** | Featured + Latest split lede, live **Timeline** with relative timestamps and Load More, trending topic chips, Editors' Pick and most-discussed rails, column/series labels |
| **insightph.juanclick.org** | Cover hero with tagline and socials, stacked category sections with "explore all", cards carrying byline affiliation and reading time, all-articles index with filters |

No plugins required. Nothing is loaded from a third-party CDN.

---

## Install

### On Hostinger (or any cPanel host)

1. Download or export this repository as a ZIP.
2. **Important:** the folder inside the ZIP must be named `ang-bantayog`.
   A ZIP from GitHub's "Download ZIP" button unpacks to
   `ang-bantayog-<branch>` — rename it before zipping, or use the release ZIP.
3. WordPress admin → **Appearance → Themes → Add New → Upload Theme**.
4. Choose the ZIP, **Install Now**, then **Activate**.
5. Go to **Settings → Permalinks** and click **Save Changes** once. This is
   required: the custom post types (`fact-check`, `dokumento`, `anunsyo`) will
   return 404 until permalinks are flushed.

### Locally (XAMPP)

Copy the `ang-bantayog` folder into `wp-content/themes/`, then activate it and
flush permalinks as in step 5.

---

## What activation creates automatically

Switching the theme on once creates:

- **Categories:** Balita, Opinyon, Lathalain, Isports, Multimedia,
  Agham at Teknolohiya, Literatura, Editorial
- **Document types:** Patakaran, Ulat, Isyu ng Pahayagan (PDF), Mga Form
- **Columns/series:** BANTAYOG VIEW, BANTAYOG INFO, MGA BUHAY NA MAY SAYSAY
- **Pages:** Tungkol Sa Amin, Mga Artikulo, Aklatan ng Dokumento,
  Makipag-ugnayan — each already assigned its page template

---

## Setup after activating

**Appearance → Customize** has four Ang Bantayog panels:

- **Pagkakakilanlan** — cover tagline, topbar line, cover image
- **Social at Kontak** — Facebook, Instagram, YouTube, TikTok, X, newsroom email
- **Subscribe** — subscribe form URL (Google Form, Mailchimp, anything). The
  subscribe button and newsletter band stay hidden until this is filled in.
- **Homepage** — a switch for each homepage block, extra category sections by
  slug, and the Timeline item count

**Appearance → Menus** has three locations: Primary (supports one level of
dropdown), Utility (topbar) and Footer/Legal. If no Primary menu is assigned,
the theme falls back to a sensible built-in menu.

---

## Content types

| Type | Admin menu | Fields beyond the editor |
|---|---|---|
| Post | Posts | Deck, author affiliation, Editors' Pick, LIVE flag, Topic, Column/Series |
| Fact Check | Fact Check | Verdict (TAMA / MALI / MAPANLINLANG / HINDI MAPAGTIBAY), the claim, claim source |
| Dokumento | Mga Dokumento | File URL, reference number, document date, document type |
| Anunsyo | Mga Anunsyo | Title only — feeds the ticker |
| Kasosyo | Mga Kasosyo | Featured image as logo, website URL |

**Deck** is the standfirst under a headline. **Affiliation** prints after the
byline, e.g. *Juan Dela Cruz — Grade 11, Engineering 1*.

To upload a document: Media → Add New, copy the file URL, paste it into the
document's **URL ng file** field.

---

## File layout

```
ang-bantayog/
├── style.css                     theme header + base tokens (light/dark)
├── functions.php                 bootstrap: setup, assets, search, loads inc/
├── header.php  footer.php        a11y bar, ticker, masthead, nav / footer
├── front-page.php                the 10-block homepage
├── single.php                    article + progress, share, author, related
├── single-ab_factcheck.php       fact check with verdict panel
├── index.php  archive.php  category.php  taxonomy.php  author.php
├── search.php  404.php  page.php  sidebar.php  comments.php
├── inc/
│   ├── template-tags.php         reading time, relative time, byline, queries
│   ├── cpt.php                   post types + meta boxes
│   ├── taxonomies.php            Topic, Series, Document type
│   ├── customizer.php            all theme options
│   ├── widgets.php               sidebar + 2 footer areas
│   ├── ajax.php                  Load More endpoint
│   └── starter-content.php       activation setup
├── template-parts/               cards, section headings, share, author, chips
├── page-templates/               about, articles index, documents, contact
└── assets/
    ├── css/publishing-v3.css     all v3 components
    ├── js/publishing-v3.js       carousel, ticker, load-more, a11y, progress
    └── img/                      seal, cover, 6 article placeholders (SVG)
```

Placeholder SVGs are used wherever a post has no featured image, so the site
never shows a broken frame before real photos are uploaded.

---

## Accessibility

- Text size (A− / A / A+) and high-contrast mode, both remembered per reader
- Skip link, visible focus, `aria-*` on carousel, ticker, menus and toggles
- `prefers-reduced-motion` stops the ticker scroll, carousel autoplay and the
  LIVE badge pulse
- Load More moves focus to the first new item
- Print stylesheet strips the furniture and prints the article

## Browser behaviour without JavaScript

The carousel shows its first slide, the ticker becomes a static list, Load More
does nothing (archives still paginate normally), and the accessibility toolbar
falls back to browser zoom. Nothing becomes unreachable.

---

## Testing notes

v3.0.0 was rendered against a live WordPress install with fixture content
covering every feature. All templates returned HTTP 200 with no PHP notices,
warnings or fatals: homepage, single post, fact-check single and archive,
document single and archive, category, topic and series taxonomies, author
archive, search (with and without results), 404, all four page templates, the
feed, and the Load More AJAX endpoint.

## License

GNU General Public License v2 or later.
