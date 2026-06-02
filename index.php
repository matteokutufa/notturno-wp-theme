<?php
/**
 * index.php — fallback richiesto da WordPress. Delega ad archive.php-style listing.
 * @package notturno
 */
get_header();
?>
<section class="pad" style="padding:80px 56px 40px;">
	<div class="eyebrow" style="margin-bottom:22px;"><?php esc_html_e( 'Index', 'notturno' ); ?></div>
	<h1 class="serif responsive-hero" style="font-size:96px; margin:0; letter-spacing:-0.025em;">
		<span class="serif-italic" style="color:var(--accent)"><?php echo esc_html( get_the_archive_title() ?: get_bloginfo( 'name' ) ); ?></span>
	</h1>
</section>

<section class="pad-x section-rule" style="padding:20px 56px 80px;">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post();
		$cat = get_the_category();
	?>
		<a class="list-row" href="<?php the_permalink(); ?>">
			<span class="entry-num" style="font-size:14px; color:var(--fg-3);">№ <?php echo esc_html( notturno_entry_number() ); ?></span>
			<span class="entry-num col-year" style="font-size:11px; color:var(--fg-2);">'<?php echo esc_html( get_the_date( 'y' ) ); ?></span>
			<div>
				<div style="display:flex; gap:14px; margin-bottom:6px;">
					<?php if ( $cat ) : ?><span class="cat-badge"><?php echo esc_html( $cat[0]->name ); ?></span><?php endif; ?>
				</div>
				<h3 class="serif responsive-h2" style="font-size:32px; margin:0; line-height:1.1;"><?php the_title(); ?></h3>
			</div>
			<div class="col-tags"></div>
			<div class="col-status" style="text-align:right;"></div>
		</a>
	<?php endwhile; ?>
		<div class="pagination"><?php echo paginate_links( array( 'prev_text' => '← Précédents', 'next_text' => 'Suivants →' ) ); ?></div>
	<?php else : ?>
		<p class="body-lead" style="padding:60px 0;"><?php esc_html_e( 'Nessun contenuto.', 'notturno' ); ?></p>
	<?php endif; ?>
</section>
<?php get_footer(); ?>
