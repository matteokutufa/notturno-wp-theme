<?php
/**
 * notturno — functions.php
 * Setup tema, enqueue asset, menu, helper editoriali.
 *
 * @package notturno
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'NOTTURNO_VERSION', '1.0.0' );

/* -------------------------------------------------------------------------
 * 1 · Theme support
 * ---------------------------------------------------------------------- */
function notturno_setup() {
	load_theme_textdomain( 'notturno', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'custom-logo', array( 'height' => 48, 'width' => 48, 'flex-height' => true, 'flex-width' => true ) );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_editor_style( 'style.css' );

	// Menù in francese — il nome resta in francese in tutte le lingue.
	register_nav_menus( array(
		'primary' => __( 'Menu principal (français)', 'notturno' ),
		'footer'  => __( 'Menu pied de page', 'notturno' ),
	) );
}
add_action( 'after_setup_theme', 'notturno_setup' );

/* -------------------------------------------------------------------------
 * 2 · Asset (font + stylesheet + script tema)
 * ---------------------------------------------------------------------- */
function notturno_assets() {
	// Google Fonts — Cormorant Garamond + Geist + JetBrains Mono
	wp_enqueue_style(
		'notturno-fonts',
		'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400;1,500&family=Geist:wght@300;400;500;600&family=JetBrains+Mono:wght@400;500&display=swap',
		array(),
		null
	);

	wp_enqueue_style( 'notturno-style', get_stylesheet_uri(), array( 'notturno-fonts' ), NOTTURNO_VERSION );

	// Script: switch tema (auto/jour/nuit) + menu mobile
	wp_enqueue_script( 'notturno-ui', get_template_directory_uri() . '/assets/ui.js', array(), NOTTURNO_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'notturno_assets' );

/* -------------------------------------------------------------------------
 * 3 · Theme resolver inline — evita il flash impostando data-theme prima del render
 * ---------------------------------------------------------------------- */
function notturno_theme_head() {
	?>
	<script>
	(function () {
		try {
			var mode = localStorage.getItem('notturno.theme') || 'auto';
			var h = new Date().getHours() + new Date().getMinutes() / 60;
			var auto = (h >= 6.5 && h < 19.5) ? 'light' : 'dark';
			var resolved = mode === 'auto' ? auto : mode;
			document.documentElement.setAttribute('data-theme', resolved);
		} catch (e) {
			document.documentElement.setAttribute('data-theme', 'dark');
		}
	})();
	</script>
	<?php
}
add_action( 'wp_head', 'notturno_theme_head', 1 );

/* -------------------------------------------------------------------------
 * 4 · Helper editoriali
 * ---------------------------------------------------------------------- */

/** Numero progressivo "editoriale" di un post (basato sull'ID, stabile). */
function notturno_entry_number( $post_id = null ) {
	$post_id = $post_id ?: get_the_ID();
	// usa il conteggio pubblicato come riferimento leggibile
	return str_pad( $post_id % 1000, 3, '0', STR_PAD_LEFT );
}

/** Tempo di lettura stimato in minuti. */
function notturno_reading_time( $post_id = null ) {
	$content = get_post_field( 'post_content', $post_id ?: get_the_ID() );
	$words   = str_word_count( wp_strip_all_tags( $content ) );
	return max( 1, (int) round( $words / 220 ) );
}

/** Etichetta "min di lettura" localizzata. */
function notturno_reading_label() {
	$min = notturno_reading_time();
	/* translators: %d minuti di lettura */
	return sprintf( _n( '%d min de lecture', '%d min de lecture', $min, 'notturno' ), $min );
}

/**
 * Switcher lingua compatibile con Polylang e WPML.
 * Mostra lo switcher solo se un plugin multilanguage e attivo/configurato.
 */
function notturno_language_switcher() {
	$items = array();

	// 1) Polylang
	if ( function_exists( 'pll_the_languages' ) ) {
		$langs = pll_the_languages( array( 'raw' => 1 ) );
		if ( ! empty( $langs ) && is_array( $langs ) ) {
			foreach ( $langs as $lang ) {
				if ( empty( $lang['url'] ) || empty( $lang['slug'] ) ) {
					continue;
				}

				$items[] = array(
					'url'     => (string) $lang['url'],
					'code'    => strtoupper( (string) $lang['slug'] ),
					'current' => ! empty( $lang['current_lang'] ),
				);
			}
		}
	}

	// 2) WPML
	if ( empty( $items ) && ( defined( 'ICL_SITEPRESS_VERSION' ) || has_filter( 'wpml_active_languages' ) ) ) {
		$wpml_langs = apply_filters(
			'wpml_active_languages',
			null,
			array(
				'skip_missing' => 0,
				'orderby'      => 'code',
			)
		);

		if ( ! empty( $wpml_langs ) && is_array( $wpml_langs ) ) {
			foreach ( $wpml_langs as $lang ) {
				if ( empty( $lang['url'] ) || empty( $lang['language_code'] ) ) {
					continue;
				}

				$items[] = array(
					'url'     => (string) $lang['url'],
					'code'    => strtoupper( (string) $lang['language_code'] ),
					'current' => ! empty( $lang['active'] ),
				);
			}
		}
	}

	// Nessun plugin lingua attivo/configurato: non mostrare switcher.
	if ( empty( $items ) ) {
		return;
	}

	echo '<div class="lang"><ul>';
	$i = 0;
	foreach ( $items as $item ) {
		if ( $i++ > 0 ) {
			echo '<span>·</span>';
		}
		$cls = $item['current'] ? ' class="current-lang"' : '';
		printf( '<li%s><a href="%s">%s</a></li>', $cls, esc_url( $item['url'] ), esc_html( $item['code'] ) );
	}
	echo '</ul></div>';
}

/** Colophon localizzato (riusa le stringhe del tema, traducibili). */
function notturno_colophon() {
	return __( 'Un carnet personale di scritti, esperimenti e raccolte. Pubblicato la sera, ospitato in casa, scritto a mano in tre lingue.', 'notturno' );
}

/**
 * Restituisce le 3 categorie con piu contenuti pubblicati per la strip home.
 * Se non esistono categorie valorizzate, restituisce array vuoto.
 */
function notturno_home_strip_summary_items() {
	$terms = get_terms(
		array(
			'taxonomy'   => 'category',
			'hide_empty' => true,
			'orderby'    => 'count',
			'order'      => 'DESC',
			'number'     => 3,
		)
	);

	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return array();
	}

	$items = array();
	foreach ( $terms as $term ) {
		$count = isset( $term->count ) ? (int) $term->count : 0;
		if ( $count < 1 ) {
			continue;
		}

		$items[] = array(
			'label' => (string) $term->name,
			'count' => $count,
			'url'   => get_term_link( $term ),
		);
		if ( count( $items ) >= 3 ) {
			break;
		}
	}

	return $items;
}

/**
 * Formato data esteso in francese per la strip home:
 * YYYY, j mois — HH:mm UTC · HH:mm Europe/Rome
 */
function notturno_home_strip_datetime_fr() {
	$utc_now = new DateTimeImmutable( 'now', new DateTimeZone( 'UTC' ) );
	$rome_now = $utc_now->setTimezone( new DateTimeZone( 'Europe/Rome' ) );

	$months = array(
		1  => 'janvier',
		2  => 'fevrier',
		3  => 'mars',
		4  => 'avril',
		5  => 'mai',
		6  => 'juin',
		7  => 'juillet',
		8  => 'aout',
		9  => 'septembre',
		10 => 'octobre',
		11 => 'novembre',
		12 => 'decembre',
	);

	$month_index = (int) $rome_now->format( 'n' );
	$month_name = isset( $months[ $month_index ] ) ? $months[ $month_index ] : $rome_now->format( 'F' );

	return sprintf(
		'%1$s, %2$s %3$s — %4$s UTC · %5$s Europe/Rome',
		$rome_now->format( 'Y' ),
		$rome_now->format( 'j' ),
		$month_name,
		$utc_now->format( 'H:i' ),
		$rome_now->format( 'H:i' )
	);
}

/* -------------------------------------------------------------------------
 * 5 · Excerpt — lunghezza e "leggi" su misura
 * ---------------------------------------------------------------------- */
function notturno_excerpt_length() { return 32; }
add_filter( 'excerpt_length', 'notturno_excerpt_length' );

function notturno_excerpt_more() { return '…'; }
add_filter( 'excerpt_more', 'notturno_excerpt_more' );

/** Forza 9 articoli per pagina su archivi tag/categoria. */
function notturno_archive_posts_per_page( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_tag() || $query->is_category() ) {
		$query->set( 'posts_per_page', 9 );
	}
}
add_action( 'pre_get_posts', 'notturno_archive_posts_per_page' );

/* -------------------------------------------------------------------------
 * 6 · Body class per il container .site (gestito nei template) — no-op qui,
 *      ma utile come hook per estensioni future.
 * ---------------------------------------------------------------------- */
function notturno_body_classes( $classes ) {
	$classes[] = 'notturno';
	return $classes;
}
add_filter( 'body_class', 'notturno_body_classes' );

/* -------------------------------------------------------------------------
 * 7 · Sottotitolo (custom field)
 * ---------------------------------------------------------------------- */

/** Recupera il sottotitolo con fallback su chiavi legacy. */
function notturno_get_subtitle( $post_id = null ) {
	$post_id = $post_id ?: get_the_ID();
	$keys = array( 'notturno_subtitle', 'subtitle', 'sottotitolo' );

	foreach ( $keys as $key ) {
		$value = trim( (string) get_post_meta( $post_id, $key, true ) );
		if ( '' !== $value ) {
			return $value;
		}
	}

	return '';
}

/** Registra meta box "Sottotitolo" su articoli. */
function notturno_add_subtitle_metabox() {
	add_meta_box(
		'notturno_subtitle_metabox',
		__( 'Sottotitolo', 'notturno' ),
		'notturno_render_subtitle_metabox',
		'post',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'notturno_add_subtitle_metabox' );

/** Render campo del sottotitolo nel backend. */
function notturno_render_subtitle_metabox( $post ) {
	wp_nonce_field( 'notturno_save_subtitle', 'notturno_subtitle_nonce' );
	$value = get_post_meta( $post->ID, 'notturno_subtitle', true );
	?>
	<p>
		<label for="notturno_subtitle" class="screen-reader-text"><?php esc_html_e( 'Sottotitolo', 'notturno' ); ?></label>
		<input
			type="text"
			id="notturno_subtitle"
			name="notturno_subtitle"
			value="<?php echo esc_attr( $value ); ?>"
			style="width:100%;"
			maxlength="220"
		/>
	</p>
	<p style="margin-top:8px; color:#646970;">
		<?php esc_html_e( 'Se presente: appare sotto l\'immagine di copertina. Se non c\'è cover: appare sotto il titolo.', 'notturno' ); ?>
	</p>
	<?php
}

/** Salva sottotitolo articolo. */
function notturno_save_subtitle_metabox( $post_id ) {
	if ( ! isset( $_POST['notturno_subtitle_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['notturno_subtitle_nonce'] ) ), 'notturno_save_subtitle' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( ! isset( $_POST['notturno_subtitle'] ) ) {
		return;
	}

	$subtitle = sanitize_text_field( wp_unslash( $_POST['notturno_subtitle'] ) );

	if ( '' === $subtitle ) {
		delete_post_meta( $post_id, 'notturno_subtitle' );
		return;
	}

	update_post_meta( $post_id, 'notturno_subtitle', $subtitle );
}
add_action( 'save_post_post', 'notturno_save_subtitle_metabox' );

/* -------------------------------------------------------------------------
 * 8 · Spotify (strip homepage)
 * ---------------------------------------------------------------------- */

/** Carica configurazione Spotify da file tema. */
function notturno_get_spotify_config() {
	$defaults = array(
		'enabled'           => false,
		'mode'              => 'currently_playing',
		'playlist_id'       => '',
		'client_id'         => '',
		'client_secret'     => '',
		'refresh_token'     => '',
		'static_token'      => '',
		'cache_ttl'         => 90,
		'fallback_label'    => 'Nocturne in E-flat, Op. 9 No. 2',
	);

	$config_file = get_template_directory() . '/spotify-config.php';
	if ( ! file_exists( $config_file ) ) {
		return $defaults;
	}

	$loaded = include $config_file;
	if ( ! is_array( $loaded ) ) {
		return $defaults;
	}

	return array_merge( $defaults, $loaded );
}

/** Richiede un access token Spotify usando refresh_token o token statico. */
function notturno_get_spotify_access_token( $config ) {
	if ( ! empty( $config['static_token'] ) ) {
		return (string) $config['static_token'];
	}

	$cached = get_transient( 'notturno_spotify_access_token' );
	if ( is_string( $cached ) && '' !== $cached ) {
		return $cached;
	}

	if ( empty( $config['client_id'] ) || empty( $config['client_secret'] ) || empty( $config['refresh_token'] ) ) {
		return '';
	}

	$response = wp_remote_post(
		'https://accounts.spotify.com/api/token',
		array(
			'timeout' => 8,
			'headers' => array(
				'Authorization' => 'Basic ' . base64_encode( $config['client_id'] . ':' . $config['client_secret'] ),
			),
			'body' => array(
				'grant_type'    => 'refresh_token',
				'refresh_token' => $config['refresh_token'],
			),
		)
	);

	if ( is_wp_error( $response ) ) {
		return '';
	}

	$body = json_decode( wp_remote_retrieve_body( $response ), true );
	if ( empty( $body['access_token'] ) ) {
		return '';
	}

	$token = (string) $body['access_token'];
	$expires = isset( $body['expires_in'] ) ? max( 60, (int) $body['expires_in'] - 60 ) : 3000;
	set_transient( 'notturno_spotify_access_token', $token, $expires );

	return $token;
}

/** Effettua GET JSON verso Spotify API. */
function notturno_spotify_get_json( $url, $token ) {
	$response = wp_remote_get(
		$url,
		array(
			'timeout' => 8,
			'headers' => array(
				'Authorization' => 'Bearer ' . $token,
			),
		)
	);

	if ( is_wp_error( $response ) ) {
		return null;
	}

	$code = (int) wp_remote_retrieve_response_code( $response );
	if ( $code < 200 || $code >= 300 ) {
		return null;
	}

	$data = json_decode( wp_remote_retrieve_body( $response ), true );
	return is_array( $data ) ? $data : null;
}

/** Normalizza oggetto track Spotify in formato tema. */
function notturno_spotify_track_payload( $track ) {
	if ( ! is_array( $track ) || empty( $track['name'] ) ) {
		return null;
	}

	$artists = array();
	if ( ! empty( $track['artists'] ) && is_array( $track['artists'] ) ) {
		foreach ( $track['artists'] as $artist ) {
			if ( ! empty( $artist['name'] ) ) {
				$artists[] = $artist['name'];
			}
		}
	}

	return array(
		'title'  => (string) $track['name'],
		'artist' => implode( ', ', $artists ),
		'url'    => isset( $track['external_urls']['spotify'] ) ? (string) $track['external_urls']['spotify'] : '',
	);
}

/** Restituisce traccia da "in ascolto" o ultima traccia playlist configurata. */
function notturno_get_spotify_track() {
	$config = notturno_get_spotify_config();
	if ( empty( $config['enabled'] ) ) {
		return null;
	}

	$cache_key = 'notturno_spotify_track_' . md5( wp_json_encode( array( $config['mode'], $config['playlist_id'] ) ) );
	$cached = get_transient( $cache_key );
	if ( is_array( $cached ) ) {
		return $cached;
	}

	$token = notturno_get_spotify_access_token( $config );
	if ( '' === $token ) {
		return null;
	}

	$mode = (string) $config['mode'];
	$track = null;

	if ( 'currently_playing' === $mode || 'current' === $mode ) {
		$current = notturno_spotify_get_json( 'https://api.spotify.com/v1/me/player/currently-playing', $token );
		if ( is_array( $current ) && ! empty( $current['item'] ) ) {
			$track = notturno_spotify_track_payload( $current['item'] );
		}
	}

	if ( null === $track && ! empty( $config['playlist_id'] ) ) {
		$playlist_url = 'https://api.spotify.com/v1/playlists/' . rawurlencode( $config['playlist_id'] ) . '/tracks?limit=1';
		$playlist = notturno_spotify_get_json( $playlist_url, $token );
		if ( is_array( $playlist ) && ! empty( $playlist['items'][0]['track'] ) ) {
			$track = notturno_spotify_track_payload( $playlist['items'][0]['track'] );
		}
	}

	if ( is_array( $track ) ) {
		set_transient( $cache_key, $track, max( 20, (int) $config['cache_ttl'] ) );
	}

	return $track;
}
