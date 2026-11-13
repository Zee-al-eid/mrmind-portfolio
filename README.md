# Mr Mind | WordPress storefront

I built Mr Mind end to end, from the original HTML prototype to the live WordPress storefront. It sells digital guides individually and in bundles. I built the frontend and custom PHP theme, then set up the content models so editors could update pages and products from WordPress.

[Visit mrmind.co](https://mrmind.co/)

![Mr Mind homepage with its main headline and navigation](homepage.png)

## The problem I solved

The prototype had separate HTML files for the homepage, product pages and supporting pages. Shared navigation and layout lived in multiple files. Every new product or layout change meant editing markup. The WordPress migration gave editors control over the catalog and page content while carrying over the existing CSS and JavaScript.

## How the site works

| Area | Implementation |
| --- | --- |
| Homepage and editorial pages | WordPress pages use an ordered list of ACF sections. Editors can assemble pages from heroes, narrative blocks, product showcases, testimonials, FAQs and other layouts. |
| Product catalog | A custom Product post type holds the catalog. A Product Collection taxonomy groups related entries. The archive uses the editor's sort order and falls back to sorting by title. |
| Product details | A shared template handles individual guides and bundles. It renders included products for bundles and uses shared components for testimonials and purchase calls to action. |
| Testimonials | Editors manage testimonials as reusable entries and link them to products. The theme can display them on relevant pages without duplicating the content. |
| Content schemas | ACF field definitions live in JSON alongside the PHP templates so changes to the editor fields can be reviewed in Git. |
| Presentation | The custom theme shares navigation and layout across pages and carries forward the visual style and interactions from the static site. |

## Three decisions behind the build

**Give editors layout choices.** The page schema defines 13 section layouts, each with its own fields. Editors can change the order or content from WordPress. The homepage falls back to the standard page body and then a starter hero, so an empty section list still renders a usable page.

**Use one product template for singles and bundles.** The template checks the product type and adds the included products section when a bundle has entries. Both formats share the same product page structure and testimonial component.

**Make the catalog manageable from the admin.** The Product list shows type and price alongside the title. Editors can set product order with page attributes. The theme applies that order to the public catalog and uses title as a secondary sort.

## Read the code

| File | What to review |
| --- | --- |
| [Homepage template](theme/front-page.php) | How the page chooses between ACF sections, editor content and the starter hero. |
| [Standard page template](theme/page.php) | How the theme uses the shared section system outside the homepage. |
| [Page section schema](theme/acf-json/group_mm_page_sections.json) | The fields available to editors for each of the 13 layouts. |
| [Content model](theme/post-types.php) | Registration, catalog ordering and custom admin columns. |
| [Product archive](theme/archive-product.php) | How product entries are rendered into the catalog. |
| [Product detail template](theme/single-product.php) | How individual products and bundles share the same template. |
| [Testimonial component](theme/template-parts/testimonials.php) | How testimonial entries become a reusable part of the page. |
| [Testimonial schema](theme/acf-json/group_mm_testimonial.json) | The quote, rating and product relationship fields. |

The samples are excerpts from the custom theme. They depend on WordPress, ACF Pro and supporting theme code. The live site shows how they work together.

**Stack** · WordPress, PHP, Advanced Custom Fields Pro, HTML, CSS and JavaScript
