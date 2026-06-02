<?php
/**
 * search.php — risultati di ricerca con form prominente in stile editoriale.
 * @package notturno
 */
get_header();
global $wp_query;
?>
<section class="pad" style="padding:80px 56px 40px;">
	<div class="eyebrow" style="margin-bottom:22px;"><?php esc_html_e( 'Recherche · catalogue intégral', 'notturno' ); ?></div>
	<h1 class="serif responsive-hero" style="font-size:88px; margin:0; letter-spacing:-0.02em; line-height:1;">
		<?php esc_html_e( 'Cosa stai', 'notturno' ); ?> <span class="serif-italic" style="color:var(--accent)"><?php esc_html_e( 'cercando', 'notturno' ); ?></span>?
	</h1>

	<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" style="margin-top:48px; position:relative; max-width:720px;">
		<input type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'termine, tag, titolo…', 'notturno' ); ?>"
			style="width:100%; background:transparent; border:0; border-bottom:2px solid var(--accent); padding:14px 48px 14px 0; color:var(--fg-0); font-family:var(--font-display); font-size:44px; font-style:italic; outline:none;" />
		<button type="submit" style="position:absolute; right:0; top:50%; transform:translateY(-50%); background:transparent; border:0; cursor:pointer; font-family:var(--font-mono); font-size:12px; color:var(--accent); letter-spacing:var(--tracking-mid);">↵ ENTER</button>
	</form>

	<div style="display:flex; gap:28px; margin-top:18px; font-family:var(--font-mono); font-size:11px; color:var(--fg-2); letter-spacing:var(--tracking-mid); text-transform:uppercase;">
		<span style="color:var(--accent);">● <?php echo esc_html( $wp_query->found_posts ); ?> résultats</span>
		<span>pour « <?php echo esc_html( get_search_query() ); ?> »</span>
	</div>
</section>

<section class="pad-x section-rule" style="padding:20px 56px 60px;">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post();
		$cat = get_the_category();
	?>
		<a href="<?php the_permalink(); ?>" style="text-decoration:none; color:inherit; display:block;">
			<article style="padding:26px 0; border-bottom:1px solid var(--hairline); display:grid; grid-template-columns:100px 1fr; gap:32px;">
				<div>
					<div style="font-family:var(--font-mono); font-size:10px; color:var(--accent); letter-spacing:var(--tracking-mid); text-transform:uppercase; margin-bottom:4px;"><?php echo esc_html( get_post_type() ); ?></div>
					<?php if ( $cat ) : ?><span class="cat-badge" style="color:var(--fg-3)"><?php echo esc_html( $cat[0]->name ); ?></span><?php endif; ?>
				</div>
				<div>
					<h3 class="serif" style="font-size:26px; margin:0; line-height:1.2;"><?php the_title(); ?></h3>
					<p style="margin-top:10px; color:var(--fg-1); font-size:14px; line-height:1.65;"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 30 ) ); ?></p>
				</div>
			</article>
		</a>
	<?php endwhile; ?>
		<div class="pagination"><?php echo paginate_links( array( 'prev_text' => '←', 'next_text' => '→' ) ); ?></div>
	<?php else : ?>
		<p style="padding:60px 0; text-align:center; color:var(--fg-3); font-style:italic; font-family:var(--font-display); font-size:22px;">
			— <?php printf( esc_html__( 'Niente trovato per « %s ».', 'notturno' ), esc_html( get_search_query() ) ); ?>
		</p>
	<?php endif; ?>
</section>

<?php get_footer(); ?>
