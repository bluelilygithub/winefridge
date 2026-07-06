=== Curam — Walk-In Wine Cabinets Australia ===
A warm-editorial luxury WordPress block theme.
By Bluelily Studios · v1.0.0

------------------------------------------------------------
INSTALL (Local WP → SiteGround)
------------------------------------------------------------
1. Copy the whole "curam" folder into:  wp-content/themes/curam
   (In Local WP: right-click the site → "Reveal in Finder/Explorer" → app/public/wp-content/themes/)
2. WordPress admin → Appearance → Themes → activate "Curam — Walk-In Wine Cabinets Australia".
3. Go to Settings → Permalinks and click "Save" once (registers the Case Study URLs).

Requires WordPress 6.4+ and PHP 7.4+. No plugins required.

------------------------------------------------------------
FIRST-RUN SETUP
------------------------------------------------------------
• Front page: Settings → Reading → "Your homepage displays" → A static page →
  create/select a page and it will use the front-page template automatically,
  OR leave it and the theme's front-page.html renders the full landing.
• Menus: the header/footer links are in the header/footer template parts
  (Appearance → Editor → Patterns → Template Parts). Edit the URLs to match your pages.
• Create these Pages and assign templates (Page → Template panel, right sidebar):
    - "Enquire"   → template "Enquiry Page"
    - "Products"  → template "Products / Collection Gallery"
    - "About", "FAQ" → default Page template (add content with blocks; FAQ pattern available)
• Blog/Journal: create a "Journal" (or "Blog") page and set it as Posts page under
  Settings → Reading. Add Posts as normal.

------------------------------------------------------------
CASE STUDIES + FILTERED GALLERY
------------------------------------------------------------
• A "Case Studies" post type is registered (left admin menu).
• Each Case Study can be tagged with a "Collection" (Walk-In Cellars / Wine Walls /
  Bespoke Cabinets — seeded automatically). Set a Featured Image for the gallery tile.
• The filtered gallery appears on the Products page, the Case Studies archive
  (/case-studies/), and anywhere via the shortcode:  [curam_gallery count="12"]
  Filter chips are generated from the Collection terms.

------------------------------------------------------------
ENQUIRY FORM (emails via WordPress)
------------------------------------------------------------
• The Enquiry page and the CTA buttons point to the built-in form.
• Submissions email to the site admin address (Settings → General → Admin Email).
  To send elsewhere, add to a small plugin or child theme:
      add_filter('curam_enquiry_recipient', fn() => 'sales@walkinwine.com.au');
• Deliverability on shared hosting (SiteGround): install an SMTP plugin
  (e.g. WP Mail SMTP) so wp_mail() sends reliably. The form itself needs no plugin.
• Includes a nonce + honeypot for basic spam protection.

------------------------------------------------------------
IMAGERY (placeholders → real photos)
------------------------------------------------------------
Every image area shows an art-direction note (what to shoot). To replace:
• Marketing sections live as Block Patterns (Appearance → Editor → Patterns → "Curam — Wine").
  Edit the pattern and swap the placeholder <div class="cu-ph"> for an Image block,
  or edit the pattern PHP in /patterns and drop in your <img>.
• Case Study / Post tiles use the Featured Image — just set it on each entry.
Art direction summary: warm white balance, rich shadows, architectural framing,
golden-hour interiors, people used sparingly for scale. Avoid cool/blue tones.

------------------------------------------------------------
BRAND
------------------------------------------------------------
Colours:  Paper #f6f0e4 · Ink #221e19 · Bordeaux #5e2333 · Brass #a4834b
Type:     Cormorant Garamond (display serif) + Work Sans (UI/body), via Google Fonts.
All tokens live in theme.json (editor palette/typography) and assets/css/theme.css.

------------------------------------------------------------
FILES
------------------------------------------------------------
style.css              Theme header
theme.json             Design tokens → editor palette, typography, spacing, buttons
functions.php          Enqueues, Case Study CPT + Collection taxonomy, enquiry handler, [curam_gallery]
assets/css/theme.css   Section & pattern styling
assets/js/theme.js     Mobile nav + gallery filtering
parts/                 header, footer (template parts)
templates/             front-page, index, page, single, archive, single/archive-case_study,
                       page-enquire, page-products, search, 404
patterns/              hero, intro, collection, craft, clientele, case-feature, journal,
                       cta, enquiry-form, faq, products-gallery
