<?php
/**
 * Footer — colophon + link secondari (lingua del sito) + brand francese.
 * @package notturno
 */
?>
	</main><!-- .site-main -->

	<footer class="footer">
		<div>
			<div class="brand-mark serif-italic"><?php bloginfo( 'name' ); ?></div>
			<div class="colophon"><?php echo esc_html( notturno_colophon() ); ?></div>
		</div>

		<div class="footer-links">
			<a href="<?php echo esc_url( home_url( '/archives/' ) ); ?>"><?php esc_html_e( 'Archivio', 'notturno' ); ?></a>
			<a href="<?php echo esc_url( home_url( '/etiquettes/' ) ); ?>"><?php esc_html_e( 'Etichette', 'notturno' ); ?></a>
			<a href="<?php echo esc_url( get_search_link() ); ?>"><?php esc_html_e( 'Cerca', 'notturno' ); ?></a>
			<a href="<?php echo esc_url( get_feed_link() ); ?>"><?php esc_html_e( 'Feed RSS', 'notturno' ); ?></a>
			<?php
			wp_nav_menu( array(
				'theme_location' => 'footer',
				'container'      => false,
				'items_wrap'     => '%3$s',
				'fallback_cb'    => false,
				'depth'          => 1,
			) );
			?>
		</div>

		<div class="footer-meta">
			<div>&copy; <?php echo esc_html( date( 'Y' ) ); ?> · <?php bloginfo( 'name' ); ?></div>
			<div style="color: var(--fg-3)"><?php esc_html_e( 'Bâti à la main · Florence', 'notturno' ); ?></div>
		</div>
	</footer>

</div><!-- .site -->

<?php wp_footer(); ?>
</body>
</html>
