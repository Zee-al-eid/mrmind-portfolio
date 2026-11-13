<?php
/**
 * Default page template.
 *
 * Pages are built from the ACF Flexible Content section library
 * (template-parts/page-sections/). When a page has no sections yet, it falls
 * back to rendering the editor content in the reading column, so pages created
 * before the sections are filled in still look right.
 *
 * @package MrMind
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$rendered = mrmind_render_sections( 'page_sections', get_the_ID(), 'template-parts/page-sections' );

	if ( ! $rendered ) :
		?>
		<section class="bg-void">
			<div class="narrative-beat reveal">
				<div class="text-content reading-col text-left">
					<?php $mm_tag = mrmind_heading_tag(); ?>
					<<?php echo esc_html( $mm_tag ); ?> class="display-title mb-24"><?php the_title(); ?></<?php echo esc_html( $mm_tag ); ?>>
					<div class="divider-gold divider-mid"></div>
					<div class="body-lg"><?php the_content(); ?></div>
				</div>
			</div>
		</section>
		<?php
	endif;

endwhile;

get_footer();
