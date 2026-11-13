<?php
/**
 * Testimonial grid section.
 *
 * Renders into the static site's exact markup (.testimonial-grid >
 * .testimonial-card > .testimonial-stars + p.testimonial-quote +
 * .testimonial-author), so there is zero visual change from the original.
 *
 * Expects $args:
 *   - testimonials (WP_Post[]) required
 *   - heading      (string)
 *   - lede         (string)
 *   - bg_class     (string)
 *
 * @package MrMind
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$testimonials = $args['testimonials'] ?? array();

if ( empty( $testimonials ) ) {
	return;
}

$heading  = $args['heading'] ?? __( 'What People Are Saying', 'mrmind' );
$lede     = $args['lede'] ?? '';
$bg_class = $args['bg_class'] ?? 'bg-accent';
?>
<section id="reviews" class="<?php echo esc_attr( $bg_class ); ?>">
	<div class="bg-text"><?php esc_html_e( 'PROOF', 'mrmind' ); ?></div>
	<div class="narrative-beat reveal">
		<h2 class="display-title"><?php echo esc_html( $heading ); ?></h2>
		<div class="divider-gold"></div>

		<?php if ( $lede ) : ?>
			<p class="manifesto-para"><?php echo esc_html( $lede ); ?></p>
		<?php endif; ?>

		<div class="testimonial-grid">
			<?php
			foreach ( $testimonials as $testimonial ) :
				$t_id    = is_object( $testimonial ) ? $testimonial->ID : (int) $testimonial;
				$quote   = mrmind_field( 'quote', $t_id );
				$name    = mrmind_field( 'author_name', $t_id );
				$detail  = mrmind_field( 'author_detail', $t_id );
				$rating  = (int) mrmind_field( 'rating', $t_id, 5 );

				if ( ! $quote ) {
					continue;
				}

				$author = $name;
				if ( $name && $detail ) {
					$author = $name . ', ' . $detail;
				} elseif ( ! $name && $detail ) {
					$author = $detail;
				}
				?>
				<div class="testimonial-card">
					<div class="testimonial-stars"><?php echo esc_html( mrmind_stars( $rating ) ); ?></div>
					<p class="testimonial-quote"><?php echo esc_html( $quote ); ?></p>
					<?php if ( $author ) : ?>
						<div class="testimonial-author">— <?php echo esc_html( $author ); ?></div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
