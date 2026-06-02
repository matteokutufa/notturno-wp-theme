<?php
/**
 * page.php — pagine statiche (À propos, Contact, ecc.).
 * Per il form di contatto: usa uno shortcode (es. Contact Form 7 / WPForms)
 * nel contenuto della pagina — eredita lo stile editoriale automaticamente.
 * @package notturno
 */
get_header();

while ( have_posts() ) : the_post(); ?>
<section class="pad page-shell">
	<div class="page-wrap">
		<div class="eyebrow page-kicker">· <?php echo esc_html( get_the_title() ); ?></div>
		<h1 class="serif responsive-h1 page-title"><?php the_title(); ?></h1>

		<?php if ( has_excerpt() ) : ?>
			<p class="body-lead page-lead"><?php echo esc_html( get_the_excerpt() ); ?></p>
		<?php endif; ?>

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="cover-img page-cover" style="background-image:url('<?php echo esc_url( get_the_post_thumbnail_url( null, 'large' ) ); ?>');"></div>
		<?php endif; ?>

		<div class="entry-content page-content"><?php the_content(); ?></div>
	</div>
</section>

<?php
	if ( comments_open() || get_comments_number() ) comments_template();
endwhile;

get_footer();
