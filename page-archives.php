<?php
/**
 * page-archives.php — cronologia completa raggruppata per anno → mese.
 * Template Name: Archives (cronologia)
 * Crea una pagina con slug "archives" e assegna questo template.
 * @package notturno
 */
get_header();

$all = new WP_Query( array( 'posts_per_page' => -1, 'ignore_sticky_posts' => true, 'orderby' => 'date', 'order' => 'DESC' ) );
$total = $all->found_posts;
?>
<section class="pad" style="padding:80px 56px 40px;">
	<div style="display:flex; justify-content:space-between; align-items:end; gap:40px; flex-wrap:wrap;">
		<div>
			<div class="eyebrow" style="margin-bottom:22px;"><?php esc_html_e( 'Index général · cronologie', 'notturno' ); ?></div>
			<h1 class="serif responsive-hero" style="font-size:124px; margin:0; letter-spacing:-0.025em; line-height:0.95;">
				<span class="serif-italic" style="color:var(--accent)">Archives.</span>
			</h1>
		</div>
		<div class="eyebrow" style="padding-bottom:8px;"><?php printf( esc_html__( '%s entrées au total', 'notturno' ), esc_html( $total ) ); ?></div>
	</div>
</section>

<section class="pad-x section-rule" style="padding:20px 56px 80px;">
	<?php
	$current_year = '';
	$current_month = '';
	if ( $all->have_posts() ) : while ( $all->have_posts() ) : $all->the_post();
		$year = get_the_date( 'Y' );
		$month_key = get_the_date( 'Y-m' );
		$month_label = get_the_date( 'F' );
		if ( $year !== $current_year ) :
			if ( $current_year !== '' ) echo '</ul></div></div>';
			$current_year = $year;
			$current_month = '';
	?>
		<div class="archives-year-group">
			<div class="archives-year-head">
				<h2 class="serif archives-year-title"><?php echo esc_html( $year ); ?></h2>
			</div>
			<div class="archives-months">
	<?php endif; $cat = get_the_category(); ?>
		<?php if ( $month_key !== $current_month ) : ?>
			<?php if ( '' !== $current_month ) echo '</ul></div>'; ?>
			<?php $current_month = $month_key; ?>
			<div class="archives-month-group">
				<h3 class="archives-month-title"><?php echo esc_html( $month_label ); ?></h3>
				<ul class="archives-entry-list">
		<?php endif; ?>
			<li class="archives-entry-row">
				<span class="entry-num archives-entry-number">№ <?php echo esc_html( notturno_entry_number() ); ?></span>
				<span class="entry-num archives-entry-day"><?php echo esc_html( get_the_date( 'd' ) ); ?></span>
				<div class="archives-entry-main">
					<a href="<?php the_permalink(); ?>" class="archives-entry-link"><?php the_title(); ?></a>
					<div class="archives-entry-meta">
						<?php if ( $cat ) : ?><span class="cat-badge"><?php echo esc_html( $cat[0]->name ); ?></span><?php endif; ?>
						<span class="entry-num"><?php echo esc_html( get_the_date( 'D j M' ) ); ?></span>
					</div>
				</div>
			</li>
	<?php endwhile; echo '</ul></div></div></div>'; endif; wp_reset_postdata(); ?>
</section>

<?php get_footer(); ?>
