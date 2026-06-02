<?php
/**
 * Header — nav con switch tema + lingua. Voci menu in francese.
 * @package notturno
 */
?>
<!doctype html>
<html <?php language_attributes(); ?> data-theme="dark">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="site ambient">

	<nav class="nav">
		<div class="nav-top">
			<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php if ( has_custom_logo() ) : the_custom_logo(); else : ?>
					<span class="brand-mark"><?php bloginfo( 'name' ); ?></span>
					<span class="brand-domain">· <?php echo esc_html( get_bloginfo( 'description' ) ); ?></span>
				<?php endif; ?>
			</a>

			<div class="nav-tools">
				<button class="nav-search-toggle" type="button" data-search-open aria-label="<?php esc_attr_e( 'Apri la ricerca', 'notturno' ); ?>">
					<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
						<circle cx="11" cy="11" r="6.5"></circle>
						<path d="M16 16l5 5"></path>
					</svg>
				</button>
				<button class="theme-toggle" data-theme-toggle aria-label="<?php esc_attr_e( 'Changer le thème', 'notturno' ); ?>">
					<span>☾</span><span>Auto</span>
				</button>
				<?php notturno_language_switcher(); ?>
				<button class="burger" data-burger aria-label="<?php esc_attr_e( 'Menu', 'notturno' ); ?>">
					<span></span><span></span><span></span>
				</button>
			</div>
		</div>

		<div class="nav-menu-row">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'nav-list',
				'fallback_cb'    => 'notturno_nav_fallback',
				'depth'          => 1,
			) );
			?>
		</div>
	</nav>

	<div class="mobile-menu" data-mobile-menu>
		<?php
		wp_nav_menu( array(
			'theme_location' => 'primary',
			'container'      => false,
			'menu_class'     => 'mobile-nav-list',
			'fallback_cb'    => 'notturno_nav_fallback',
			'depth'          => 1,
		) );
		?>
	</div>

	<div class="search-modal" data-search-modal hidden>
		<div class="search-modal-backdrop" data-search-close></div>
		<div class="search-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="search-modal-title">
			<div class="search-modal-head">
				<div>
					<div class="eyebrow"><?php esc_html_e( 'Recherche · catalogue intégral', 'notturno' ); ?></div>
					<h2 class="serif search-modal-title" id="search-modal-title"><?php esc_html_e( 'Que recherchez-vous?', 'notturno' ); ?></h2>
				</div>
				<button class="search-modal-close" type="button" data-search-close aria-label="<?php esc_attr_e( 'Fermer la recherche', 'notturno' ); ?>">×</button>
			</div>

			<form class="search-modal-form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<input type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'terme, tag, titre…', 'notturno' ); ?>" data-search-input />
				<button type="submit"><?php esc_html_e( 'Rechercher', 'notturno' ); ?></button>
			</form>

			<p class="search-modal-note"><?php esc_html_e( 'Appuyez sur Entrée pour ouvrir la page des résultats.', 'notturno' ); ?></p>
		</div>
	</div>

	<main class="site-main" id="main">
<?php
/**
 * Menu di fallback in francese se l'utente non ne ha ancora configurato uno.
 */
function notturno_nav_fallback() {
	$items = array(
		''            => 'Accueil',
		'projets'     => 'Projets',
		'idees'       => 'Idées',
		'liens'       => 'Liens',
		'journal'     => 'Journal',
		'a-propos'    => 'À propos',
		'contact'     => 'Contact',
	);
	echo '<ul class="nav-list">';
	foreach ( $items as $slug => $label ) {
		$url = $slug ? home_url( '/' . $slug . '/' ) : home_url( '/' );
		printf( '<li><a href="%s">%s</a></li>', esc_url( $url ), esc_html( $label ) );
	}
	echo '</ul>';
}
