<?php
/**
 * Checks that theme.json and every style variation only reference presets that exist.
 *
 * @package BlockStarter
 */

namespace BlockStarter\Tests\Unit;

use BlockStarter\Tests\ThemeFiles;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Static checks on theme.json and styles/.
 */
final class ThemeJsonTest extends TestCase {

	private const PRESET_SETTINGS = array(
		'color'       => array( 'color', 'palette' ),
		'font-size'   => array( 'typography', 'fontSizes' ),
		'font-family' => array( 'typography', 'fontFamilies' ),
		'spacing'     => array( 'spacing', 'spacingSizes' ),
	);

	/**
	 * Every JSON file the theme ships.
	 *
	 * @return array<string, array{string}>
	 */
	public static function json_files(): array {
		$files = array_merge( array( 'theme.json' ), ThemeFiles::style_variations() );

		return array_combine( $files, array_map( static fn ( $file ) => array( $file ), $files ) );
	}

	#[DataProvider( 'json_files' )]
	public function test_file_is_theme_json_version_3( string $file ): void {
		$this->assertSame( 3, ThemeFiles::json( $file )['version'] );
	}

	#[DataProvider( 'json_files' )]
	public function test_file_only_references_presets_defined_in_theme_json( string $file ): void {
		$source  = (string) file_get_contents( ThemeFiles::path( $file ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$defined = self::defined_presets();

		preg_match_all( '/var:preset\|([a-z-]+)\|([a-z0-9-]+)/', $source, $matches, PREG_SET_ORDER );

		$unknown = array();
		foreach ( $matches as list( , $type, $slug ) ) {
			if ( ! in_array( $slug, $defined[ $type ] ?? array(), true ) ) {
				$unknown[] = "$type|$slug";
			}
		}

		$this->assertSame( array(), $unknown );
	}

	public function test_style_variations_redefine_the_same_palette_slugs(): void {
		$base_slugs = self::defined_presets()['color'];

		foreach ( ThemeFiles::style_variations() as $file ) {
			$palette = ThemeFiles::json( $file )['settings']['color']['palette'] ?? null;
			if ( null === $palette ) {
				continue;
			}

			$this->assertSame( $base_slugs, array_column( $palette, 'slug' ), $file );
		}
	}

	public function test_section_styles_name_the_blocks_they_apply_to(): void {
		$sections = ThemeFiles::glob( 'styles/sections/*.json' );
		$this->assertNotSame( array(), $sections );

		foreach ( $sections as $file ) {
			$json = ThemeFiles::json( $file );
			$this->assertSame( basename( $file, '.json' ), $json['slug'], $file );
			$this->assertContains( 'core/group', $json['blockTypes'], $file );
		}
	}

	public function test_every_custom_template_has_a_file(): void {
		foreach ( ThemeFiles::json( 'theme.json' )['customTemplates'] as $template ) {
			$this->assertFileExists( ThemeFiles::path( "templates/{$template['name']}.html" ) );
		}
	}

	/**
	 * Preset slugs from theme.json, keyed by the type used in var:preset|<type>|<slug>.
	 *
	 * @return array<string, list<string>>
	 */
	private static function defined_presets(): array {
		$settings = ThemeFiles::json( 'theme.json' )['settings'];

		$presets = array();
		foreach ( self::PRESET_SETTINGS as $type => list( $group, $key ) ) {
			$presets[ $type ] = array_column( $settings[ $group ][ $key ], 'slug' );
		}

		return $presets;
	}
}
