<?php
/**
 * tag.php — pagina dettaglio etichetta: header #tag + griglia di card.
 * @package notturno
 */

get_header();
$tag = get_queried_object();
?>

<section class="pad" style="padding:80px 56px 40px;">
	<div style="display:grid; grid-template-columns:1fr auto; align-items:end; gap:40px;">
		<div>
			<div class="eyebrow" style="margin-bottom:22px;"><?php esc_html_e( 'Étiquette', 'notturno' ); ?></div>
			<h1 class="serif responsive-hero" style="font-size:124px; margin:0; letter-spacing:-0.025em; line-height:0.95;">
				<span class="serif-italic" style="color:var(--accent)"><?php echo esc_html( $tag->name ); ?>.</span>
			</h1>
		</div>
		<div class="eyebrow" style="padding-bottom:8px;"><?php printf( esc_html__( '%s articles', 'notturno' ), esc_html( $tag->count ) ); ?></div>
	</div>
	<?php if ( $tag->description ) : ?>
		<p class="body-lead" style="margin-top:28px; max-width:540px;"><?php echo esc_html( $tag->description ); ?></p>
	<?php endif; ?>
</section>

<section class="pad-x section-rule" style="padding:20px 56px 80px;">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post();
		$cat = get_the_category();
	?>
		<div class="archive-index-row archive-index-row-category">
			<span class="entry-num archive-index-number">№ <?php echo esc_html( notturno_entry_number() ); ?></span>
			<span class="entry-num archive-index-date"><?php echo esc_html( get_the_date( 'd M' ) ); ?></span>
			<h3 class="serif archive-index-title"><a class="archive-index-title-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			<div class="archive-index-tags archive-index-side-meta">
				<?php if ( $cat ) : ?>
					<a class="tag archive-index-tag-link" style="font-size:9.5px;" href="<?php echo esc_url( get_category_link( $cat[0] ) ); ?>"><?php echo esc_html( $cat[0]->name ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	<?php endwhile; endif; ?>
	<div class="pagination"><?php echo paginate_links( array( 'mid_size' => 1, 'prev_text' => '← Précédents', 'next_text' => 'Suivants →' ) ); ?></div>
</section>

<?php get_footer(); ?>
