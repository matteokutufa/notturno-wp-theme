<?php
/**
 * template-parts/card.php — card articolo per liste laterali e griglie.
 * Variabile attesa: $args['index'] (numero progressivo opzionale).
 * @package notturno
 */
$cat = get_the_category();
$cat_name = ! empty( $cat ) ? $cat[0]->name : '';
?>
<article class="card" style="padding: 22px 0; border-bottom: 1px solid var(--hairline); display: grid; grid-template-columns: auto 1fr auto; gap: 24px; align-items: baseline;">
	<span class="entry-num">№ <?php echo esc_html( notturno_entry_number() ); ?></span>
	<div>
		<div style="display:flex; gap:14px; margin-bottom:8px;">
			<?php if ( $cat_name ) : ?><span class="cat-badge"><?php echo esc_html( $cat_name ); ?></span><?php endif; ?>
			<span class="entry-num"><?php echo esc_html( get_the_date( 'j M' ) ); ?></span>
		</div>
		<a href="<?php the_permalink(); ?>" style="text-decoration:none; color:inherit;">
			<h4 class="serif" style="font-size:22px; margin:0; line-height:1.2;"><?php the_title(); ?></h4>
		</a>
	</div>
	<span class="entry-num" style="align-self:center;">→</span>
</article>
