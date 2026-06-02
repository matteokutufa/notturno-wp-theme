<?php
/**
 * Config Spotify per la strip homepage.
 *
 * mode:
 * - currently_playing: prova prima il brano in ascolto ora, poi fallback playlist_id
 * - playlist_latest: usa solo l'ultimo brano della playlist
 */
return array(
	'enabled'        => false,
	'mode'           => 'currently_playing',
	'playlist_id'    => 'INSERISCI_PLAYLIST_ID',
	'client_id'      => 'INSERISCI_CLIENT_ID',
	'client_secret'  => 'INSERISCI_CLIENT_SECRET',
	'refresh_token'  => 'INSERISCI_REFRESH_TOKEN',

	// Opzionale: se preferisci passare un token gia pronto (scadenza breve).
	'static_token'   => '',

	// Cache del risultato (secondi).
	'cache_ttl'      => 90,

	// Testo mostrato se Spotify non e disponibile.
	'fallback_label' => 'Nocturne in E-flat, Op. 9 No. 2',
);
