<?php
/**
 * EXAMPLE Spotify config — DO NOT put real credentials in this file.
 *
 * Recommended setup: define the NOTTURNO_SPOTIFY constant in wp-config.php
 * (outside the theme directory, outside git, outside release packages):
 *
 *   define( 'NOTTURNO_SPOTIFY', array(
 *       'enabled'       => true,
 *       'mode'          => 'currently_playing', // or 'playlist_latest'
 *       'playlist_id'   => 'foo',
 *       'client_id'     => 'foo',
 *       'client_secret' => 'bar',
 *       'refresh_token' => 'baz',
 *       'cache_ttl'     => 90,
 *       'fallback_label'=> 'Nocturne in E-flat, Op. 9 No. 2',
 *   ) );
 *
 * This file is only a local-dev fallback, is excluded from CI packages,
 * and is ignored when the constant is defined.
 */
return array(
	'enabled' => false,
);
