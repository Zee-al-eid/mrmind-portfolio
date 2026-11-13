<?php
/**
 * Front page.
 *
 * The homepage is a normal Page built from the same Flexible Content section
 * library as every other page, so the client can restructure it without a
 * developer. Falls back to the editor content, then to an on-brand starter
 * hero, so a fresh install never renders an empty page.
 *
 * @package MrMind
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$front_id = get_queried_object_id();
$rendered = 0;

if ( $front_id ) {
	$rendered = mrmind_render_sections( 'page_sections', $front_id, 'template-parts/page-sections' );
}

if ( ! $rendered ) {

	$has_body = $front_id && '' !== trim( (string) get_post_field( 'post_content', $front_id ) );

	if ( $has_body ) {
		while ( have_posts() ) :
			the_post();
			?>
			<section class="bg-void">
				<div class="narrative-beat reveal">
					<div class="text-content reading-col text-left">
						<div class="body-lg"><?php the_content(); ?></div>
					</div>
				</div>
			</section>
			<?php
		endwhile;
	} else {
		// Starter hero — replaced as soon as the client adds page sections.
		$cta_url = mrmind_option( 'cta_url', get_post_type_archive_link( 'product' ) );
		?>
		<section class="hero-section">
			<div class="bg-text"><?php esc_html_e( 'IDENTITY', 'mrmind' ); ?></div>
			<div class="narrative-beat reveal">
				<?php $mm_hero_tag = mrmind_heading_tag(); ?>
				<<?php echo esc_html( $mm_hero_tag ); ?> class="display-manifesto">
					<?php esc_html_e( 'YOU ARE NOT', 'mrmind' ); ?><br><span class="glow-gold text-gold"><?php esc_html_e( 'LAZY.', 'mrmind' ); ?></span>
				</<?php echo esc_html( $mm_hero_tag ); ?>>
				<div class="accent-spine"></div>
				<h2 class="display-beat hero-subtitle">
					<?php esc_html_e( 'You are mentally overloaded, unstructured, and trapped in patterns you don’t know how to break.', 'mrmind' ); ?>
				</h2>
				<?php if ( $cta_url ) : ?>
					<a href="<?php echo esc_url( $cta_url ); ?>" class="btn mt-48"><?php esc_html_e( 'The Reset System', 'mrmind' ); ?></a>
				<?php endif; ?>
				<div class="hero-summary-box mt-64">
					<p class="hero-summary-text">
						<?php esc_html_e( 'Add sections to this page under Page Sections to build the homepage — hero, narrative beats, product showcase, testimonials and more.', 'mrmind' ); ?>
					</p>
				</div>
			</div>
		</section>
		<?php
	}
}

get_footer();
