# Mr Mind

I built Mr Mind, a WordPress storefront for digital guides on habits and focus. The site started as a set of static HTML pages and moved to a custom WordPress theme so editors could manage the product catalog and page content from the admin.

[Visit the live site](https://mrmind.co/)

![Mr Mind homepage](homepage.png)

## What the project covers

- A custom PHP theme with shared templates for navigation, page layouts and product pages.
- Custom WordPress content types for products and testimonials, plus a taxonomy for product collections.
- Version-controlled ACF field definitions for product details, page sections and site settings.
- CSS and JavaScript carried over from the static prototype, with no page builder required.

## One design decision

The migration kept the existing CSS and JavaScript while moving repeated page markup into shared WordPress templates. Product content lives in structured fields so editors can update it from WordPress and the theme can render the same fields consistently across the site.

## Stack

WordPress, PHP, Advanced Custom Fields Pro, HTML, CSS and JavaScript.

## Theme code

[theme/post-types.php](theme/post-types.php) shows how the theme registers products, testimonials and product collections. It also adds type and price columns in WordPress admin and lets editors control the order of products in the catalog. This file is a sample from the theme.
