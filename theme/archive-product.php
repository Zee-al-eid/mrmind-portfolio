<?php
/**
 * Product archive — "The Reset System" hub.
 *
 * Reproduces the-reset-system.html, auto-listing Products instead of the
 * hardcoded catalogue. Ordering follows the page-attributes menu order, so the
 * client drag-orders products without touching code.
 *
 * @package MrMind
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<section class="catalog-hero">
	<div class="bg-text"><?php esc_html_e( 'THE RESET SYSTEM', 'mrmind' ); ?></div>
	<div class="narrative-beat reveal">
		<?php $mm_hero_tag = mrmind_heading_tag(); ?>
		<<?php echo esc_html( $mm_hero_tag ); ?> class="display-manifesto">
			<?php esc_html_e( 'THE COMPLETE', 'mrmind' ); ?><br><span class="glow-gold text-gold"><?php esc_html_e( 'RESET SYSTEM.', 'mrmind' ); ?></span>
		</<?php echo esc_html( $mm_hero_tag ); ?>>
		<div class="accent-spine"></div>
		<h2 class="display-beat hero-subtitle">
			<?php esc_html_e( 'Practical digital systems designed to build discipline, focus, structure and consistency.', 'mrmind' ); ?>
		</h2>
	</div>
</section>

<section class="catalog-list">
	<div class="bg-text"><?php esc_html_e( 'SYSTEMS', 'mrmind' ); ?></div>
	<div class="narrative-beat reveal">
		<?php if ( have_posts() ) : ?>
			<div class="catalog-stack">
				<?php
				while ( have_posts() ) :
					the_post();

					$product_id = get_the_ID();
					$price      = mrmind_field( 'price', $product_id, '' );
					$is_free    = mrmind_product_is_free( $product_id );
					$image_id   = mrmind_product_image_id( $product_id );
					$tagline    = mrmind_field( 'tagline', $product_id );
					$blurb      = $tagline ? $tagline : get_the_excerpt();
					?>
					<div class="grid-alternating">
						<div class="text-content">
							<h3 class="display-beat fs-fluid-sm mt-8"><?php the_title(); ?></h3>

							<?php if ( $blurb ) : ?>
								<p class="body-md mt-16 text-dim lh-16"><?php echo esc_html( $blurb ); ?></p>
							<?php endif; ?>

							<?php if ( '' !== $price ) : ?>
								<div class="catalog-price"><?php echo esc_html( mrmind_format_price( $price, $product_id, false ) ); ?></div>
							<?php endif; ?>

							<a href="<?php the_permalink(); ?>" class="btn <?php echo $is_free ? 'btn-outline ' : ''; ?>btn-catalog">
								<?php echo $is_free ? esc_html__( 'GET IT FREE', 'mrmind' ) : esc_html__( 'VIEW DETAILS', 'mrmind' ); ?>
							</a>
						</div>

						<div class="mockup-container">
							<a href="<?php the_permalink(); ?>" class="catalog-card-link">
								<?php
								if ( $image_id ) {
									echo wp_get_attachment_image( $image_id, 'large', false, array(
										'class'   => 'mockup-card hover-scale',
										'alt'     => esc_attr( get_the_title() ),
										'loading' => 'lazy',
									) );
								}
								?>
							</a>
						</div>
					</div>
					<?php
				endwhile;
				?>
			</div>

			<?php the_posts_pagination( array( 'mid_size' => 1 ) ); ?>

		<?php else : ?>
			<p class="body-lg text-dim text-center"><?php esc_html_e( 'No products published yet.', 'mrmind' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php
// Featured testimonials round out the hub, as on the static site.
get_template_part( 'template-parts/testimonials', null, array(
	'testimonials' => mrmind_featured_testimonials( 3 ),
) );

get_footer();
