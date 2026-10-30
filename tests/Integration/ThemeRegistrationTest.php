<?php
/**
 * What WordPress sees once the theme is active.
 *
 * @package BlockStarter
 */

namespace BlockStarter\Tests\Integration;

use BlockStarter\Tests\ThemeFiles;
use WP_Block_Patterns_Registry;
use WP_Block_Styles_Registry;
use WP_Block_Type_Registry;
use WP_Theme_JSON_Resolver;

/**
 * Patterns, style variations, section styles and bindings sources are registered.
 */
final class ThemeRegistrationTest extends TestCase {

	public function test_every_pattern_file_is_registered(): void {
		$registry = WP_Block_Patterns_Registry::get_instance();

		foreach ( ThemeFiles::pattern_slugs() as $slug ) {
			$this->assertTrue( $registry->is_registered( $slug ), $slug );
		}
	}

	public function test_hidden_patterns_stay_out_of_the_inserter(): void {
		$pattern = WP_Block_Patterns_Registry::get_instance()->get_registered( 'block-starter/hidden-404' );

		$this->assertFalse( $pattern['inserter'] );
	}

	public function test_full_style_variations_are_offered(): void {
		$titles = array_column( WP_Theme_JSON_Resolver::get_style_variations(), 'title' );

		$this->assertContains( 'Ink', $titles );
		$this->assertContains( 'Sage', $titles );
	}

	public function test_section_styles_are_registered_as_block_styles(): void {
		// Section styles are registered while theme.json resolves, which a front-end request triggers.
		wp_get_global_stylesheet();
		$styles = WP_Block_Styles_Registry::get_instance();

		foreach ( array( 'section-muted', 'section-accent', 'section-dark' ) as $name ) {
			$this->assertTrue( $styles->is_registered( 'core/group', $name ), $name );
		}
	}

	public function test_bindings_sources_are_registered(): void {
		$this->assertNotNull( get_block_bindings_source( 'block-starter/announcement' ) );
		$this->assertNotNull( get_block_bindings_source( 'block-starter/reading-time' ) );
	}

	public function test_templates_and_parts_only_use_registered_blocks(): void {
		$registry = WP_Block_Type_Registry::get_instance();
		$unknown  = array();

		foreach ( array_merge( ThemeFiles::glob( 'templates/*.html' ), ThemeFiles::glob( 'parts/*.html' ) ) as $file ) {
			$blocks = parse_blocks( (string) file_get_contents( ThemeFiles::path( $file ) ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			foreach ( self::block_names( $blocks ) as $name ) {
				if ( ! $registry->is_registered( $name ) ) {
					$unknown[] = "$file: $name";
				}
			}
		}

		$this->assertSame( array(), $unknown );
	}

	/**
	 * Names of every block in a parsed tree, skipping freeform whitespace.
	 *
	 * @param array<int, array<string, mixed>> $blocks Output of parse_blocks().
	 * @return list<string>
	 */
	private static function block_names( array $blocks ): array {
		$names = array();
		foreach ( $blocks as $block ) {
			if ( null !== $block['blockName'] ) {
				$names[] = $block['blockName'];
			}
			$names = array_merge( $names, self::block_names( $block['innerBlocks'] ) );
		}

		return $names;
	}
}
