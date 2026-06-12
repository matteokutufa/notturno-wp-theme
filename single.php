<?php
/**
 * single.php — articolo singolo con sommario, drop-cap, tag.
 * Lo script (TOC, copy link, toggle) è in assets/single.js,
 * accodato con stringhe localizzate da functions.php.
 * @package notturno
 */
get_header();

while ( have_posts() ) : the_post();
	$cat = get_the_category();
	$cat_name = $cat ? $cat[0]->name : '';
	$tags = get_the_tags();
	$subtitle = notturno_get_subtitle();
	$has_cover = has_post_thumbnail();
	$share_url = get_permalink();
	$share_title = get_the_title();
	$share_text = $share_title . ' — ' . get_bloginfo( 'name' );
	$encoded_url = rawurlencode( $share_url );
	$encoded_title = rawurlencode( $share_title );
	$encoded_text = rawurlencode( $share_text );
	$email_subject = rawurlencode( $share_title );
	$email_body = rawurlencode( $share_title . "\n\n" . $share_url );
	$share_icon_base = trailingslashit( get_template_directory_uri() ) . 'assets/icons/share/';
?>

<!-- breadcrumb -->
<div class="pad-x" style="padding:28px 56px 0;">
	<div class="eyebrow">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="color:var(--fg-2); text-decoration:none;">Accueil</a>
		<?php if ( $cat ) : ?>
			<span style="margin:0 14px; color:var(--fg-3);">/</span>
			<a href="<?php echo esc_url( get_category_link( $cat[0] ) ); ?>" style="color:var(--fg-2); text-decoration:none;"><?php echo esc_html( $cat_name ); ?></a>
		<?php endif; ?>
		<span style="margin:0 14px; color:var(--fg-3);">/</span>
		<span style="color:var(--accent);">№ <?php echo esc_html( notturno_entry_number() ); ?> — <?php echo esc_html( get_the_title() ); ?></span>
	</div>
</div>

<!-- TITOLO -->
<article class="pad single-head" style="padding-bottom:24px;">
	<div class="single-head-wrap">
		<div class="single-head-content">
		<div style="display:flex; gap:24px; margin-bottom:36px; align-items:center;">
			<?php if ( $cat_name ) : ?><span class="cat-badge"><?php echo esc_html( $cat_name ); ?></span><?php endif; ?>
			<span style="width:40px; height:1px; background:var(--fg-3);"></span>
			<span class="entry-num"><?php echo esc_html( get_the_date( 'j M Y' ) ); ?> · <?php echo esc_html( notturno_reading_label() ); ?></span>
		</div>
		<h1 class="serif responsive-h1" style="margin:0 0 28px; font-size:88px; line-height:0.98; letter-spacing:-0.03em; "><?php the_title(); ?></h1>

		<?php if ( $subtitle ) : ?>
			<p class="post-subtitle"><?php echo esc_html( $subtitle ); ?></p>
		<?php endif; ?>

		<?php if ( $has_cover ) : ?>
			<div class="cover-img" style="aspect-ratio:16/9; margin:0 0 26px; background-image:url('<?php echo esc_url( get_the_post_thumbnail_url( null, 'large' ) ); ?>');"></div>
		<?php endif; ?>

		<?php if ( has_excerpt() ) : ?>
			<p class="body-lead" style="max-width:720px; font-size:22px; line-height:1.55; "><?php echo esc_html( get_the_excerpt() ); ?></p>
		<?php endif; ?>
		</div>
	</div>
</article>

<!-- CORPO + META -->
<section class="pad-x" style="padding-bottom:80px;">
	<div class="article-layout">
		<aside class="post-outline" id="post-outline" aria-label="Sommaire">
			<div class="post-outline-block" id="post-outline-block">
				<div class="eyebrow" style="margin-bottom:12px;">Sommaire</div>
				<ol class="post-outline-list" id="post-outline-list"></ol>
			</div>

			<button type="button" class="post-meta-toggle" id="post-meta-toggle" aria-expanded="false" aria-controls="post-side-extra">
				<?php esc_html_e( 'Mostra dettagli', 'notturno' ); ?>
			</button>

			<div class="post-side-extra" id="post-side-extra" data-collapsed="false">

			<?php if ( $tags ) : ?>
				<div class="post-side-tags" id="post-side-tags">
					<div class="eyebrow" style="margin-bottom:10px;"><?php esc_html_e( 'Étiquettes', 'notturno' ); ?></div>
					<div class="post-side-tags-list">
						<?php foreach ( $tags as $i => $tg ) : ?>
							<a class="tag<?php echo $i === 0 ? ' is-accent' : ''; ?>" href="<?php echo esc_url( get_tag_link( $tg ) ); ?>"><?php echo esc_html( $tg->name ); ?></a>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>

			<div class="post-share" id="post-share">
				<div class="eyebrow" style="margin-bottom:10px;"><?php esc_html_e( 'Partager', 'notturno' ); ?></div>
				<div class="post-share-list">
					<a class="post-share-link" href="<?php echo esc_url( 'https://t.me/share/url?url=' . $encoded_url . '&text=' . $encoded_title ); ?>" target="_blank" rel="noopener noreferrer"><span class="post-share-icon icon-telegram" aria-hidden="true" style="--icon-url:url('<?php echo esc_url( $share_icon_base . 'telegram.svg' ); ?>');"></span><span class="post-share-label">Telegram</span></a>
					<a class="post-share-link" href="<?php echo esc_url( 'https://wa.me/?text=' . $encoded_text . '%20' . $encoded_url ); ?>" target="_blank" rel="noopener noreferrer"><span class="post-share-icon icon-whatsapp" aria-hidden="true" style="--icon-url:url('<?php echo esc_url( $share_icon_base . 'whatsapp.svg' ); ?>');"></span><span class="post-share-label">WhatsApp</span></a>
					<a class="post-share-link" href="<?php echo esc_url( 'https://www.facebook.com/sharer/sharer.php?u=' . $encoded_url ); ?>" target="_blank" rel="noopener noreferrer"><span class="post-share-icon icon-facebook" aria-hidden="true" style="--icon-url:url('<?php echo esc_url( $share_icon_base . 'facebook.svg' ); ?>');"></span><span class="post-share-label">Facebook</span></a>
					<a class="post-share-link" href="<?php echo esc_url( 'https://twitter.com/intent/tweet?url=' . $encoded_url . '&text=' . $encoded_title ); ?>" target="_blank" rel="noopener noreferrer"><span class="post-share-icon icon-twitter" aria-hidden="true" style="--icon-url:url('<?php echo esc_url( $share_icon_base . 'twitter.svg' ); ?>');"></span><span class="post-share-label">Twitter</span></a>
					<a class="post-share-link" href="<?php echo esc_url( 'https://bsky.app/intent/compose?text=' . $encoded_text . '%20' . $encoded_url ); ?>" target="_blank" rel="noopener noreferrer"><span class="post-share-icon icon-bluesky" aria-hidden="true" style="--icon-url:url('<?php echo esc_url( $share_icon_base . 'bluesky.svg' ); ?>');"></span><span class="post-share-label">Bluesky</span></a>
					<a class="post-share-link" href="<?php echo esc_url( 'https://www.linkedin.com/sharing/share-offsite/?url=' . $encoded_url ); ?>" target="_blank" rel="noopener noreferrer"><span class="post-share-icon icon-linkedin" aria-hidden="true" style="--icon-url:url('<?php echo esc_url( $share_icon_base . 'linkedin.svg' ); ?>');"></span><span class="post-share-label">LinkedIn</span></a>
					<button type="button" class="post-share-link post-share-copy" id="share-copy-link" data-url="<?php echo esc_attr( $share_url ); ?>"><span class="post-share-icon icon-link" aria-hidden="true" style="--icon-url:url('<?php echo esc_url( $share_icon_base . 'link.svg' ); ?>');"></span><span class="post-share-label"><?php esc_html_e( 'Copia link', 'notturno' ); ?></span></button>
					<a class="post-share-link" href="<?php echo esc_attr( 'mailto:?subject=' . $email_subject . '&body=' . $email_body ); ?>"><span class="post-share-icon icon-email" aria-hidden="true" style="--icon-url:url('<?php echo esc_url( $share_icon_base . 'email.svg' ); ?>');"></span><span class="post-share-label"><?php esc_html_e( 'Email', 'notturno' ); ?></span></a>
				</div>
			</div>
			</div>
		</aside>

		<div class="entry-content single-entry-content">
			<?php the_content(); ?>
			<?php wp_link_pages(); ?>
		</div>
	</div>
</section>

<?php if ( comments_open() || get_comments_number() ) : comments_template(); endif; ?>

<?php endwhile; get_footer(); ?>
