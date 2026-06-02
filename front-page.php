<?php
/**
 * front-page.php — homepage minimale con ultimi 10 post:
 * 1 evidenza + 9 box, ordinati dal più nuovo.
 * @package notturno
 */
get_header();

$home_posts = new WP_Query(
	array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => 10,
		'ignore_sticky_posts' => true,
		'orderby'             => 'date',
		'order'               => 'DESC',
	)
);

$total = wp_count_posts()->publish;
$spotify_track = function_exists( 'notturno_get_spotify_track' ) ? notturno_get_spotify_track() : null;
$spotify_config = function_exists( 'notturno_get_spotify_config' ) ? notturno_get_spotify_config() : array();
$spotify_fallback = ! empty( $spotify_config['fallback_label'] ) ? (string) $spotify_config['fallback_label'] : 'Nocturne in E-flat, Op. 9 No. 2';
$strip_summary_items = function_exists( 'notturno_home_strip_summary_items' ) ? notturno_home_strip_summary_items() : array();
$strip_datetime = function_exists( 'notturno_home_strip_datetime_fr' ) ? notturno_home_strip_datetime_fr() : date_i18n( 'Y, j F — H:i' );
$strip_summary_parts = array();
foreach ( $strip_summary_items as $summary_item ) {
	$strip_summary_parts[] = $summary_item['count'] . ' ' . $summary_item['label'];
}
$published_line = sprintf( esc_html__( '%s articles publies', 'notturno' ), esc_html( $total ) );
if ( ! empty( $strip_summary_parts ) ) {
	$published_line .= ' — ' . implode( ' · ', $strip_summary_parts );
}
$home_tags = get_tags( array( 'orderby' => 'count', 'order' => 'DESC', 'number' => 18 ) );
$home_tags_max = $home_tags ? max( array_map( function( $tag ) { return (int) $tag->count; }, $home_tags ) ) : 1;
?>

<!-- HERO ORIGINALE -->
<section class="pad" style="padding: 80px 56px 60px;">
	<div class="home-opening-grid">
		<div>
			<div class="eyebrow" style="margin-bottom: 28px;">
				<span class="dot" style="margin-right:10px;"></span>
				<?php printf( esc_html__( 'Carnet — édition №%1$s · %2$s', 'notturno' ), esc_html( str_pad( $total % 100, 2, '0', STR_PAD_LEFT ) ), esc_html( date_i18n( 'M Y' ) ) ); ?>
			</div>
			<h1 class="serif responsive-hero home-intro-title" style="margin:0; letter-spacing:-0.02em;">
				<?php
				echo wp_kses(
					__( 'Pensieri, progetti<br />e <span class="serif-italic" style="color:var(--accent)">piccoli notturni</span><br />digitali.', 'notturno' ),
					array(
						'br'   => array(),
						'span' => array( 'class' => array(), 'style' => array() ),
					)
				);
				?>
			</h1>
		</div>
		<div style="padding-bottom:18px;">
			<p class="body-lead" style="margin:0; max-width:440px;">
				<?php esc_html_e( 'Un carnet personale di scritti, esperimenti e raccolte. Tre lingue, una sola voce — pubblicato la sera, quando il rumore si ritira.', 'notturno' ); ?>
			</p>
			<div style="display:flex; gap:16px; margin-top:32px; flex-wrap:wrap;">
				<a class="btn-ghost" href="<?php echo esc_url( home_url( '/journal/' ) ); ?>">Lire le journal →</a>
				<a class="btn-ghost" href="<?php echo esc_url( get_feed_link() ); ?>" style="border:1px solid transparent; padding-left:6px;"><?php esc_html_e( 'S’abonner au flux', 'notturno' ); ?></a>
			</div>
		</div>
	</div>
</section>

<!-- STRIP ORIGINALE -->
<div class="strip">
	<div class="strip-row strip-row-meta">
		<span><a class="strip-easter-egg-link" href="https://www.openstreetmap.org/#map=19/43.786229/11.226287" target="_blank" rel="noopener noreferrer" title="J'habite plus ou moins ici.">43°47′10″N · 11°13′34″E</a></span>
		<span><?php echo esc_html( $strip_datetime ); ?></span>
	</div>
	<div class="strip-row strip-row-track">
		<span class="strip-published-label"><?php echo esc_html( $published_line ); ?></span>
		<span class="strip-track-label">
		<?php if ( $spotify_track && ! empty( $spotify_track['url'] ) ) : ?>
			<a href="<?php echo esc_url( $spotify_track['url'] ); ?>" target="_blank" rel="noopener noreferrer" style="color:inherit; text-decoration:none;">
				<?php echo esc_html( 'écouter — ' . $spotify_track['artist'] . ' · ' . $spotify_track['title'] ); ?>
			</a>
		<?php elseif ( $spotify_track ) : ?>
			<?php echo esc_html( 'écouter — ' . $spotify_track['artist'] . ' · ' . $spotify_track['title'] ); ?>
		<?php else : ?>
			<?php echo esc_html( 'écouter — ' . $spotify_fallback ); ?>
		<?php endif; ?>
		</span>
	</div>
</div>

<?php if ( $home_posts->have_posts() ) : ?>
	<section class="pad-x home-list-wrap" style="padding-top:66px; padding-bottom:80px;">
		<div class="home-list-layout">
			<aside class="home-list-note">
				<div class="eyebrow" style="margin-bottom:14px;"><?php esc_html_e( 'Notes', 'notturno' ); ?></div>
				<h2 class="serif home-list-note-title"><?php echo wp_kses( __( 'Un <span class="serif-italic">flux ordonné</span> de publications récentes.', 'notturno' ), array( 'span' => array( 'class' => array() ) ) ); ?></h2>
				<p class="home-list-note-copy"><?php esc_html_e( 'Qui trovi gli ultimi articoli in ordine cronologico, dal piu recente al piu vecchio. Ogni scheda mantiene una struttura stabile per facilitare la lettura e la scansione.', 'notturno' ); ?></p>
			</aside>

			<div class="home-post-list">
				<?php while ( $home_posts->have_posts() ) : $home_posts->the_post(); ?>
					<?php
					$card_tags = get_the_tags();
					$card_excerpt_raw = has_excerpt() ? get_the_excerpt() : wp_strip_all_tags( get_the_content( null, false ) );
					$card_excerpt = wp_html_excerpt( trim( $card_excerpt_raw ), 50, '…' );
					$has_cover = has_post_thumbnail();
					?>
					<article class="home-post-item">
						<a href="<?php the_permalink(); ?>" class="home-post-link<?php echo $has_cover ? ' has-cover' : ' no-cover'; ?>">
							<?php if ( $has_cover ) : ?>
							<div class="home-post-media-wrap" aria-hidden="true">
									<div class="home-post-media" style="background-image:url('<?php echo esc_url( get_the_post_thumbnail_url( null, 'medium_large' ) ); ?>');"></div>
							</div>
							<?php endif; ?>

							<div class="home-post-content">
								<div class="home-post-meta">
									<?php $c = get_the_category(); if ( $c ) : ?><span class="cat-badge"><?php echo esc_html( $c[0]->name ); ?></span><?php endif; ?>
									<span class="entry-num"><?php echo esc_html( get_the_date( 'd.m.y' ) ); ?></span>
								</div>

								<h2 class="serif home-post-title"><?php the_title(); ?></h2>

								<?php if ( ! empty( $card_excerpt ) ) : ?>
									<p class="home-post-excerpt"><?php echo esc_html( $card_excerpt ); ?></p>
								<?php endif; ?>
							</div>

							<?php if ( $card_tags ) : ?>
								<div class="home-tags-block home-tags-block-compact home-post-tags-block">
									<div class="home-tags-label"><?php esc_html_e( 'Etiquettes', 'notturno' ); ?></div>
									<div class="home-tags">
										<?php foreach ( array_slice( $card_tags, 0, 4 ) as $i => $tg ) : ?>
											<span class="tag<?php echo 0 === $i ? ' is-accent' : ''; ?>"><?php echo esc_html( $tg->name ); ?></span>
										<?php endforeach; ?>
									</div>
								</div>
							<?php endif; ?>
						</a>
					</article>
				<?php endwhile; ?>
			</div>
		</div>
	</section>
<?php else : ?>
	<section class="pad" style="padding-top:20px;">
		<p class="body-lead" style="max-width:760px; margin:0 auto;"><?php esc_html_e( 'Nessun articolo pubblicato al momento.', 'notturno' ); ?></p>
	</section>
<?php endif; wp_reset_postdata(); ?>

<?php if ( ! empty( $home_tags ) ) : ?>
	<section class="pad-x home-cloud-section">
		<div class="home-cloud-layout">
			<div class="home-cloud-copy">
				<div class="eyebrow" style="margin-bottom:18px;"><?php esc_html_e( 'Par étiquette', 'notturno' ); ?></div>
				<h2 class="serif home-cloud-title"><?php echo wp_kses( __( 'Naviguer<br><span class="serif-italic">par affinité.</span>', 'notturno' ), array( 'br' => array(), 'span' => array( 'class' => array() ) ) ); ?></h2>
				<p class="home-cloud-text"><?php esc_html_e( 'I tag sono curati a mano. Le dimensioni riflettono il numero di articoli, non la mia opinione.', 'notturno' ); ?></p>
			</div>
			<div class="home-cloud-tags">
				<?php foreach ( $home_tags as $tg ) :
					$ratio = $home_tags_max > 0 ? ( (int) $tg->count / $home_tags_max ) : 0;
					$size = 14 + ( $ratio * 34 );
					$color = $ratio > 0.66 ? 'var(--accent)' : ( $ratio > 0.34 ? 'var(--fg-0)' : 'var(--fg-1)' );
				?>
					<a href="<?php echo esc_url( get_tag_link( $tg ) ); ?>" class="home-cloud-tag" style="font-size:<?php echo esc_attr( $size ); ?>px; color:<?php echo esc_attr( $color ); ?>; font-style:<?php echo $ratio > 0.5 ? 'italic' : 'normal'; ?>;">
						<?php echo esc_html( $tg->name ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php get_footer(); ?>
