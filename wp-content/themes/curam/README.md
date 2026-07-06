# Curam — Walk-In Wine Cabinets Australia

A bespoke WordPress theme for Walk-In Wine Cabinets Australia. Classic PHP theme (no FSE/block-editor dependency). Designed for private collectors and hospitality operators.

---

## Theme structure

```
curam/
├── assets/
│   ├── css/
│   │   └── theme.css          # All styles — design tokens, layout, components
│   ├── images/                # Theme images (hero, gallery placeholders, logo kit)
│   └── js/
│       └── theme.js           # Mobile nav, active link highlight, gallery filter
│
├── templates/
│   ├── front-page.php         # Home page
│   ├── page.php               # Generic page fallback
│   ├── page-about.php         # /about/
│   ├── page-products.php      # /products/ — filtered gallery
│   ├── page-enquire.php       # /enquire/ — enquiry form
│   ├── single.php             # Blog post
│   ├── single-case_study.php  # Case study detail page
│   ├── archive-case_study.php # Case studies listing
│   └── index.php              # Blog archive
│
├── header.php                 # <head>, site header, nav
├── footer.php                 # Site footer, wp_footer()
├── functions.php              # Theme setup, enqueue, CPT, form handler
├── style.css                  # WordPress theme header (metadata only)
└── README.md
```

---

## Custom post type

**Case Studies** (`case_study`) — registered in `functions.php`.

Archive URL: `/case-studies/`

### Custom fields (post meta)

Set these in the WordPress admin under each case study, or via code:

| Key | Example value | Used in |
|---|---|---|
| `_cs_location` | `Yarra Valley, VIC` | Hero eyebrow, archive card |
| `_cs_type` | `Private Residence — Walk-In Cellar` | Hero subtitle, archive card |
| `_cs_bottles` | `1,800` | Stats bar, archive card |
| `_cs_temp` | `14°C` | Stats bar |
| `_cs_duration` | `9` | Stats bar (appends "Weeks build") |

---

## Enquiry form

Form handler registered in `functions.php` via `admin_post_curam_enquiry` / `admin_post_nopriv_curam_enquiry`.

Fields: Name, Phone, Email, City, Deadline (date), Message. Includes nonce verification and a honeypot field.

On success, sends an email to the site's admin address and redirects back to the enquiry page with `?enquiry=sent`.

---

## Design tokens

All colour, typography and spacing values are defined as CSS custom properties in `theme.css` under `:root`. Key tokens:

| Token | Value | Use |
|---|---|---|
| `--bordeaux` | `#5e2333` | Primary brand colour, buttons, accents |
| `--brass` / `--brass-light` | `#a4834b` / `#c4a86f` | Eyebrows, hover states, stats |
| `--ink` | `#221e19` | Body text, dark sections |
| `--paper` | `#f6f0e4` | Page background |
| `--linen` | `#fbf8f1` | Light surface, on-dark text |
| `--font-serif` | Cormorant Garamond | Headings, pull quotes |
| `--font-sans` | Work Sans | Body, nav, labels |

---

## Adding content

### Blog posts
Create via **Posts → Add New** in the WordPress admin. Assign to one of the four categories: Collecting, Craft, Hospitality, Commissioning. Add a featured image — the journal grid and single post template use it.

### Case studies
Create via **Case Studies → Add New**. Set the five custom fields listed above (under "Custom post type"). Add a featured image — it becomes the full-bleed hero on the detail page.

### Gallery (Products page)
Currently uses static placeholder images from `assets/images/`. To replace with real photography, edit `page-products.php` and update the `src` paths and `data-cats` filter attributes.

---

## To do before launch

- [ ] Replace placeholder phone number `1300 000 000` in `header.php` and `footer.php`
- [ ] Update Facebook and Instagram URLs in `footer.php`
- [ ] Delete the default "Hello World" WordPress post via **Posts → All Posts**
- [ ] Replace placeholder gallery images in `page-products.php` with real photography
- [ ] Add real case study photography as featured images
- [ ] Set up email deliverability (SMTP plugin) so the enquiry form sends reliably
