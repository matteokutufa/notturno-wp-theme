<?php
/**
 * page-etiquettes.php — indice di TUTTE le etichette (crea una pagina con slug "etiquettes").
 * Template Name: Étiquettes (indice tag)
 * @package notturno
 */
get_header();
$tags = get_tags( array( 'orderby' => 'count', 'order' => 'DESC' ) );
$max  = $tags ? max( array_map( function( $t ){ return $t->count; }, $tags ) ) : 1;
?>
<section class="pad" style="padding:80px 56px 40px;">
	<div class="eyebrow" style="margin-bottom:22px;"><?php esc_html_e( 'Index · toutes les étiquettes', 'notturno' ); ?></div>
	<h1 class="serif responsive-hero" style="font-size:124px; margin:0; letter-spacing:-0.025em; line-height:0.95;">
		<span class="serif-italic" style="color:var(--accent)">Étiquettes.</span>
	</h1>
	<p class="body-lead" style="margin-top:28px; max-width:620px;">
		<?php printf( esc_html__( 'Le etichette sono curate a mano. Sono %s in totale. Le dimensioni riflettono il numero di articoli, non l\'importanza.', 'notturno' ), esc_html( count( $tags ) ) ); ?>
	</p>
</section>

<section class="pad-x" style="padding:40px 56px; border-top:1px solid var(--hairline); border-bottom:1px solid var(--hairline);">
	<div class="eyebrow" style="margin-bottom:24px;">· Nuvola complète</div>
	<div style="display:flex; flex-wrap:wrap; gap:20px 32px; align-items:baseline;">
		<?php foreach ( $tags as $tg ) :
			$ratio = $tg->count / $max;
			$sz = 14 + $ratio * 60;
			$col = $ratio > 0.6 ? 'var(--accent)' : ( $ratio > 0.35 ? 'var(--fg-0)' : ( $ratio > 0.2 ? 'var(--fg-1)' : 'var(--fg-2)' ) );
		?>
			<a href="<?php echo esc_url( get_tag_link( $tg ) ); ?>" style="font-family:var(--font-display); font-size:<?php echo esc_attr( $sz ); ?>px; font-style:<?php echo $ratio > 0.5 ? 'italic' : 'normal'; ?>; color:<?php echo esc_attr( $col ); ?>; text-decoration:none; line-height:1;">
				<?php echo esc_html( $tg->name ); ?><span style="font-family:var(--font-mono); font-size:10px; color:var(--fg-3); margin-left:4px; vertical-align:super;"><?php echo esc_html( $tg->count ); ?></span>
			</a>
		<?php endforeach; ?>
	</div>
</section>

<?php if ( get_the_content() ) : ?>
<section class="pad entry-content" style="padding:60px 56px;"><?php the_content(); ?></section>
<?php endif; ?>

<?php get_footer(); ?>
