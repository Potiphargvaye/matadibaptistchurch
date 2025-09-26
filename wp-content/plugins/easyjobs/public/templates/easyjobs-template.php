<?php
/**
 * Template Name: Easyjobs Template
 * Description: Template for easyjobs pages
 *
 * @link       https://easy.jobs
 * @since      1.0.0
 *
 * @package    EasyJobs
 * @subpackage EasyJobs/public
 */
$ej_header = '';
$ej_footer = '';
if ( function_exists( 'wp_is_block_theme' ) && wp_is_block_theme() ) {
	$theme_name = wp_get_theme()->get('TextDomain');
	$ej_header = do_blocks('<!-- wp:template-part {"slug":"header","theme":"' . $theme_name . '"} /-->');
	$ej_footer = do_blocks('<!-- wp:template-part {"slug":"footer","theme":"' . $theme_name . '"} /-->');
}
Easyjobs_Helper::easyjobs_header($ej_header);
global $post;

?>
<div class="easyjobs-frontend-wrapper ej-frontend-fluid <?php echo get_post_meta( $post->ID, 'easyjobs_job_id', true ) == 'all' ? 'easyjobs-landing-page' : 'easyjobs-single-page'; ?>">
	<div class="easyjobs-content-wrapper">
		<?php
		/**
		 * Hooks anything before content
		 *
		 * @since 1.0.0
		 */
		do_action( 'easyjobs_before_content' );
		?>

		<?php if ( have_posts() ) : ?>
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<?php the_content(); ?>
			<?php endwhile; ?>
		<?php endif; ?>

		<?php
		/**
		 * Hooks anything after content
		 *
		 * @since 1.0.0
		 */
		do_action( 'easyjobs_after_content' );
		?>
	</div>
</div>

<?php Easyjobs_Helper::easyjobs_footer($ej_footer); ?>
