<?php
/**
 * Checks that templates and parts only point at parts and patterns the theme ships.
 *
 * @package BlockStarter
 */

namespace BlockStarter\Tests\Unit;

use BlockStarter\Tests\ThemeFiles;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Static checks on templates/, parts/ and patterns/.
 */
final class TemplatesTest extends TestCase {

	/**
	 * Every block markup file: templates, parts and patterns.
	 *
	 * @return array<string, array{string}>
	 */
	public static function markup_files(): array {
		$files = array_merge(
			ThemeFiles::glob( 'templates/*.html' ),
			ThemeFiles::glob( 'parts/*.html' ),
			ThemeFiles::glob( 'patterns/*.php' )
		);

		return array_combine( $files, array_map( static fn ( $file ) => array( $file ), $files ) );
	}

	public function test_theme_has_the_templates_wordpress_falls_back_to(): void {
		foreach ( array( 'index', 'single', 'page', 'archive', 'search', '404' ) as $name ) {
			$this->assertFileExists( ThemeFiles::path( "templates/$name.html" ) );
		}
	}

	/**
	 * Every wp:template-part slug used, one case per use.
	 *
	 * @return array<string, array{string}>
	 */
	public static function template_part_references(): array {
		return self::references( 'template-part' );
	}

	/**
	 * Every wp:pattern slug used, one case per use.
	 *
	 * @return array<string, array{string}>
	 */
	public static function pattern_references(): array {
		return self::references( 'pattern' );
	}

	#[DataProvider( 'template_part_references' )]
	public function test_template_part_exists_and_is_declared( string $slug ): void {
		$this->assertFileExists( ThemeFiles::path( "parts/$slug.html" ) );
		$this->assertContains( $slug, array_column( ThemeFiles::json( 'theme.json' )['templateParts'], 'name' ) );
	}

	#[DataProvider( 'pattern_references' )]
	public function test_pattern_exists( string $slug ): void {
		$this->assertContains( $slug, ThemeFiles::pattern_slugs() );
	}

	#[DataProvider( 'markup_files' )]
	public function test_block_comments_are_balanced( string $file ): void {
		$source = (string) file_get_contents( ThemeFiles::path( $file ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		preg_match_all( '#<!-- (/?)wp:([a-z0-9/-]+)(?:\s+(?:\{.*?\}\s+)?)?(/?)-->#s', $source, $matches, PREG_SET_ORDER );

		$open = array();
		foreach ( $matches as list( , $closing, $name, $self_closing ) ) {
			if ( '/' === $self_closing ) {
				continue;
			}
			if ( '' === $closing ) {
				$open[] = $name;
				continue;
			}
			$this->assertSame( $name, array_pop( $open ), "$file closes wp:$name out of order" );
		}

		$this->assertSame( array(), $open, "$file leaves blocks open" );
	}

	public function test_patterns_have_a_title_and_a_unique_prefixed_slug(): void {
		$slugs = array();

		foreach ( ThemeFiles::glob( 'patterns/*.php' ) as $file ) {
			$headers = ThemeFiles::pattern_headers( $file );
			$this->assertNotEmpty( $headers['Title'] ?? '', "$file has no Title" );
			$this->assertSame( 'block-starter/' . basename( $file, '.php' ), $headers['Slug'] ?? '', $file );
			$slugs[] = $headers['Slug'];
		}

		$this->assertSame( array_unique( $slugs ), $slugs );
	}

	/**
	 * Slugs passed to a block across all markup files, keyed "file: slug" for readable failures.
	 *
	 * @param string $block Core block name without the namespace, e.g. "pattern".
	 * @return array<string, array{string}>
	 */
	private static function references( string $block ): array {
		$cases = array();

		foreach ( self::markup_files() as list( $file ) ) {
			$source = (string) file_get_contents( ThemeFiles::path( $file ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			preg_match_all( '/<!-- wp:' . preg_quote( $block, '/' ) . ' (\{.*?\}) \/?-->/', $source, $matches );

			foreach ( $matches[1] as $attrs ) {
				$slug                    = json_decode( $attrs, true, 512, JSON_THROW_ON_ERROR )['slug'];
				$cases[ "$file: $slug" ] = array( $slug );
			}
		}

		return $cases;
	}
}
