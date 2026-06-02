<?php
/**
 * 404.php — pagina non trovata, stile editoriale notturno.
 * @package notturno
 */
get_header();
?>
<section class="pad" style="padding:120px 56px; display:flex; flex-direction:column; justify-content:center; align-items:center; text-align:center; gap:48px;">
	<div class="eyebrow" style="color:var(--accent);"><?php esc_html_e( 'Erreur · 404 · page introuvable', 'notturno' ); ?></div>

	<h1 class="serif responsive-hero" style="font-size:220px; margin:0; line-height:0.85; letter-spacing:-0.04em;">
		<span style="color:var(--fg-3)">4</span><span class="serif-italic" style="color:var(--accent)">0</span><span style="color:var(--fg-3)">4</span>
	</h1>

	<p class="body-lead" style="max-width:540px; font-size:22px; line-height:1.5;">
		<?php esc_html_e( 'La pagina che cerchi', 'notturno' ); ?> <span class="serif-italic"><?php esc_html_e( 'non è qui', 'notturno' ); ?></span>, <?php esc_html_e( 'oppure non è mai stata. Capita, in un sito che si riscrive di notte.', 'notturno' ); ?>
	</p>

	<div style="display:flex; gap:16px; flex-wrap:wrap; justify-content:center;">
		<a class="btn-ghost" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( "Ritorna all'accueil", 'notturno' ); ?></a>
		<a class="btn-ghost" href="<?php echo esc_url( get_search_link() ); ?>"><?php esc_html_e( 'Tenter une recherche', 'notturno' ); ?></a>
		<a class="btn-ghost" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Signaler le lien', 'notturno' ); ?></a>
	</div>
</section>
<?php get_footer(); ?>
