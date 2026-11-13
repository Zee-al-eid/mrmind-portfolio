# Mr Mind

I built [mrmind.co](https://mrmind.co/), a WordPress storefront for digital guides. I took it from a static HTML prototype to a live site with a custom PHP theme, an editable homepage and a structured product catalog.

[Visit the live storefront](https://mrmind.co/)

![Mr Mind homepage showing the navigation and main headline](homepage.png)

## What I built

- **An editor-managed homepage.** Editors can reorder sections in WordPress. The theme renders those sections and falls back to the page body or a starter hero when the page has no configured sections.
- **A catalog for individual guides and bundles.** Products and collections have their own WordPress content types and taxonomy. Editors can set the catalog order and see product type and price in the admin list.
- **Reusable testimonials.** Testimonials are entered once and can be associated with products. Their field definitions are versioned alongside the theme.

## Inside the theme

| Sample | What to look for |
| --- | --- |
| [Homepage template](theme/front-page.php) | How structured page sections, editor content and the starter hero are selected. |
| [Product detail template](theme/single-product.php) | How one template handles individual products and bundles, including related products, testimonials and purchase calls to action. |
| [Content model](theme/post-types.php) | WordPress hooks for registration, catalog ordering and custom admin columns. |
| [Testimonial fields](theme/acf-json/group_mm_testimonial.json) | The ACF schema for testimonial content and product relationships. |

The main decision was to keep the existing CSS and JavaScript during the migration and move repeated markup into shared PHP templates. That kept the visual design consistent while giving editors control over content and page structure.

These samples come from the custom theme and depend on WordPress, ACF Pro and other theme helpers. The live storefront shows the complete implementation.
