<?php
/**
 * Loads Composer, and WordPress too when WP_PATH points at an install with this theme active.
 * Without WP_PATH the integration suite skips itself; CI runs it with --fail-on-skipped.
 *
 * @package BlockStarter
 */

$block_starter_wp_path = getenv( 'WP_PATH' );

require_once dirname( __DIR__ ) . '/vendor/autoload.php';

if ( false === $block_starter_wp_path || '' === $block_starter_wp_path ) {
	return;
}

// wp-load.php expects a web request; these keep it quiet under the CLI.
$_SERVER['HTTP_HOST']   = 'localhost';
$_SERVER['SERVER_NAME'] = 'localhost';
$_SERVER['REQUEST_URI'] = '/';

require_once rtrim( $block_starter_wp_path, '/' ) . '/wp-load.php';

if ( 'block-starter' !== get_stylesheet() ) {
	fwrite( STDERR, 'Activate the block-starter theme in ' . $block_starter_wp_path . " before running the integration suite.\n" ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fwrite
	exit( 1 );
}
