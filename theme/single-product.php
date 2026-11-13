<?php
/**
 * Single Product template.
 *
 * Reproduces the static product-*.html layout, entirely field-driven:
 *   shop block (gallery + buy box) → reorderable Flexible Content sections
 *   → bundle contents (bundles only) → testimonials → final CTA.
 *
 * @package MrMind
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$product_id   = get_the_ID();
	$is_bundle    = ( 'bundle' === mrmind_field( 'product_type', $product_id, 'single' ) );
	$included     = $is_bundle ? mrmind_field( 'included_products', $product_id, array() ) : array();
	$testimonials = mrmind_product_testimonials( $product_id );
	$price        = mrmind_field( 'price', $product_id, '' );
	$checkout     = mrmind_checkout_url( $product_id );

	// 1. Shop block — gallery + buy box.
	get_template_part( 'template-parts/product/buy-box' );

	// 2. Client-ordered content sections. The returned count lets the
	//    testimonials block continue the light/dark alternation.
	$section_count = mrmind_render_sections( 'product_sections', $product_id );

	// 3. Bundle contents, with automatic total-value maths.
	if ( $is_bundle && ! empty( $included ) ) :
		$total = mrmind_bundle_total( $product_id );
		?>
		<section id="bundle" class="bg-glow-gold divider-top py-120">
			<div class="bg-text"><?php esc_html_e( 'BUNDLE', 'mrmind' ); ?></div>
			<div class="narrative-beat reveal">
				<div class="text-center">
					<h2 class="display-title"><?php esc_html_e( "What's Included", 'mrmind' ); ?></h2>
					<div class="divider-gold"></div>

					<div class="bundle-includes">
						<?php
						foreach ( $included as $child ) :
							$child_id = is_object( $child ) ? $child->ID : (int) $child;
							?>
							<span class="text-gold">✓ <?php echo esc_html( get_the_title( $child_id ) ); ?></span>
						<?php endforeach; ?>
					</div>

					<?php if ( '' !== $price ) : ?>
						<div class="bundle-price">
							<?php
							// Only show the struck-through total when the bundle is
							// actually cheaper than buying the parts — otherwise it
							// reads as a price increase.
							if ( $total > (float) $price ) :
								?>
								<span class="price-compare fs-24"><?php echo esc_html( mrmind_format_price( $total, $product_id ) ); ?></span>
							<?php endif; ?>
							<?php echo esc_html( mrmind_format_price( $price, $product_id ) ); ?>
						</div>
					<?php endif; ?>

					<?php if ( mrmind_product_is_ready( $product_id ) ) : ?>
						<a href="<?php echo esc_url( $checkout ); ?>"<?php echo mrmind_buy_attr( $product_id ); ?> class="btn btn-lg"><?php esc_html_e( 'SECURE THE BUNDLE', 'mrmind' ); ?></a>
					<?php else : ?>
						<?php mrmind_coming_soon_button( 'btn btn-lg' ); ?>
					<?php endif; ?>
				</div>
			</div>
		</section>
		<?php
	endif;

	// 4. Social proof. The bundle block above uses its own gold treatment, so
	//    only continue the alternation when it wasn't rendered.
	$testimonial_bg = ( $is_bundle && ! empty( $included ) )
		? 'bg-accent'
		: mrmind_section_bg( $section_count );

	get_template_part( 'template-parts/testimonials', null, array(
		'testimonials' => $testimonials,
		'lede'         => __( 'Real feedback from real customers.', 'mrmind' ),
		'bg_class'     => $testimonial_bg,
	) );

	// 5. Closing CTA — the static "premium-cta-card": copy + button beside a
	//    mockup, all field-driven with safe fallbacks to the product's own data.
	$is_free     = mrmind_product_is_free( $product_id );
	$cta_bg      = mrmind_field( 'cta_bgtext', $product_id, __( 'START', 'mrmind' ) );
	$cta_heading = mrmind_field( 'cta_heading', $product_id, '' );
	$cta_body    = mrmind_field( 'cta_body', $product_id, '' );
	$cta_tagline = mrmind_field( 'cta_tagline', $product_id, '' );
	$cta_image   = (int) mrmind_field( 'cta_image', $product_id, 0 );
	$cta_button  = mrmind_field( 'cta_button', $product_id, '' );
	if ( '' === $cta_heading ) {
		$cta_heading = get_the_title();
	}
	if ( '' === $cta_button ) {
		$cta_button = mrmind_field( 'cta_label', $product_id, __( 'GET INSTANT ACCESS', 'mrmind' ) );
	}
	?>
	<section id="purchase" class="bg-glow-gold divider-top py-120">
		<div class="bg-text"><?php echo esc_html( $cta_bg ); ?></div>
		<div class="narrative-beat reveal">
			<div class="premium-cta-card">
				<div class="section-split items-center gap-40">
					<div class="text-content">
						<h2 class="display-title mb-24 text-left fs-fluid-lg"><?php echo esc_html( $cta_heading ); ?></h2>

						<?php if ( $cta_body ) : ?>
							<p class="body-lg mb-40 text-dim text-left"><?php echo esc_html( $cta_body ); ?></p>
						<?php endif; ?>

						<?php if ( mrmind_product_is_ready( $product_id ) ) : ?>
							<a href="<?php echo esc_url( $checkout ); ?>"<?php echo mrmind_buy_attr( $product_id ); ?> class="btn btn-lg">
								<?php
								echo esc_html( $cta_button );
								if ( ! $is_free && '' !== $price ) {
									echo ' — ' . esc_html( mrmind_format_price( $price, $product_id, false ) );
								}
								?>
							</a>
						<?php else : ?>
							<?php mrmind_coming_soon_button( 'btn btn-lg' ); ?>
						<?php endif; ?>

						<?php if ( $cta_tagline ) : ?>
							<p class="cta-tagline"><?php echo esc_html( $cta_tagline ); ?></p>
						<?php endif; ?>
					</div>

					<?php if ( $cta_image ) : ?>
						<div class="image-container">
							<div class="image-frame">
								<?php
								echo wp_get_attachment_image( $cta_image, 'large', false, array(
									'alt'     => esc_attr( get_the_title() ),
									'loading' => 'lazy',
								) );
								?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
	<?php

endwhile;

get_footer();
