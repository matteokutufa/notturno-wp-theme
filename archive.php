<?php
/**
 * archive.php — listato categoria / archivi generici (formato indice editoriale).
 * Usato per Projets, Idées, Liens, Journal e qualunque archivio.
 * @package notturno
 */
get_header();

$obj = get_queried_object();
$title = single_term_title( '', false );
if ( ! $title ) $title = get_the_archive_title();
$count = ( isset( $obj->count ) ) ? $obj->count : ( $GLOBALS['wp_query']->found_posts ?? 0 );
$is_category_archive = is_category();
?>

<!-- HEADER -->
<section class="pad" style="padding:80px 56px 40px;">
	<div style="display:grid; grid-template-columns:1fr auto; align-items:end; gap:40px;">
		<div>
			<div class="eyebrow" style="margin-bottom:22px;"><?php echo esc_html( $is_category_archive ? __( 'Catégorie', 'notturno' ) : __( 'Archive', 'notturno' ) ); ?></div>
			<h1 class="serif responsive-hero" style="font-size:124px; margin:0; letter-spacing:-0.025em; line-height:0.95;">
				<span class="serif-italic" style="color:var(--accent)"><?php echo esc_html( $title ); ?>.</span>
			</h1>
			<?php if ( $obj && ! empty( $obj->description ) ) : ?>
				<p class="body-lead" style="margin-top:28px; max-width:540px;"><?php echo esc_html( $obj->description ); ?></p>
			<?php endif; ?>
		</div>
		<div class="eyebrow" style="padding-bottom:8px;"><?php printf( esc_html__( '%s articles', 'notturno' ), esc_html( $count ) ); ?></div>
	</div>
</section>

<!-- LISTA -->
<section class="pad-x section-rule" style="padding:20px 56px 80px;">
	<?php if ( have_posts() ) : ?>
		<?php if ( $is_category_archive ) : ?>
			<?php $current_year = ''; ?>
			<?php $current_month = ''; ?>
			<?php while ( have_posts() ) : the_post();
				$year = get_the_date( 'Y' );
				$month = get_the_date( 'F' );
				$tags = get_the_tags();
				if ( $year !== $current_year ) :
					if ( '' !== $current_year ) {
						echo '</div>';
					}
					$current_year = $year;
					$current_month = '';
			?>
				<div class="archive-year-block">
					<h2 class="serif archive-year-title"><?php echo esc_html( $year ); ?></h2>
			<?php endif; ?>
			<?php if ( $month !== $current_month ) : ?>
				<?php $current_month = $month; ?>
				<h3 class="serif archive-month-title"><?php echo esc_html( $month ); ?></h3>
			<?php endif; ?>
				<div class="archive-index-row archive-index-row-category">
					<span class="entry-num archive-index-number">№ <?php echo esc_html( notturno_entry_number() ); ?></span>
					<span class="entry-num archive-index-date"><?php echo esc_html( get_the_date( 'd M' ) ); ?></span>
					<h3 class="serif archive-index-title"><a class="archive-index-title-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<div class="archive-index-tags">
						<?php if ( $tags ) foreach ( array_slice( $tags, 0, 3 ) as $tg ) : ?>
							<a class="tag archive-index-tag-link" style="font-size:9.5px;" href="<?php echo esc_url( get_tag_link( $tg ) ); ?>"><?php echo esc_html( $tg->name ); ?></a>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endwhile; if ( '' !== $current_year ) echo '</div>'; ?>
		<?php else : ?>
			<?php while ( have_posts() ) : the_post();
				$tags = get_the_tags();
			?>
				<div class="archive-index-row">
					<span class="entry-num archive-index-number">№ <?php echo esc_html( notturno_entry_number() ); ?></span>
					<span class="entry-num archive-index-date"><?php echo esc_html( get_the_date( 'd M Y' ) ); ?></span>
					<h3 class="serif archive-index-title"><a class="archive-index-title-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<div class="archive-index-tags">
						<?php if ( $tags ) foreach ( array_slice( $tags, 0, 3 ) as $tg ) : ?>
							<a class="tag archive-index-tag-link" style="font-size:9.5px;" href="<?php echo esc_url( get_tag_link( $tg ) ); ?>"><?php echo esc_html( $tg->name ); ?></a>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endwhile; ?>
		<?php endif; ?>

		<div class="pagination">
			<?php echo paginate_links( array( 'mid_size' => 1, 'prev_text' => '← Précédents', 'next_text' => 'Suivants →' ) ); ?>
		</div>

	<?php else : ?>
		<p class="body-lead" style="padding:60px 0;"><?php esc_html_e( 'Niente qui, per ora.', 'notturno' ); ?></p>
	<?php endif; ?>
</section>

<?php get_footer(); ?>
