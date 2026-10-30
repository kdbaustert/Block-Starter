<?php
/**
 * Block Starter bootstrap. Everything else lives in src/ under the BlockStarter namespace.
 *
 * @package BlockStarter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$block_starter_autoload = __DIR__ . '/vendor/autoload.php';

if ( ! is_readable( $block_starter_autoload ) ) {
	add_action(
		'admin_notices',
		static function (): void {
			printf(
				'<div class="notice notice-error"><p>%s</p></div>',
				esc_html__( 'Block Starter is missing vendor/autoload.php. Run "composer install --no-dev" in the theme folder.', 'block-starter' )
			);
		}
	);
	return;
}

require_once $block_starter_autoload;

BlockStarter\Theme::boot();
