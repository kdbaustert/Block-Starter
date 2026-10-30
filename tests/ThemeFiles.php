<?php
/**
 * Reads the theme's own files for tests that don't need WordPress.
 *
 * @package BlockStarter
 */

namespace BlockStarter\Tests;

/**
 * Paths and parsed contents of theme.json, templates, parts, patterns and styles.
 */
final class ThemeFiles {

	/**
	 * Absolute path to a file or folder in the theme.
	 *
	 * @param string $relative Path from the theme root.
	 */
	public static function path( string $relative = '' ): string {
		return dirname( __DIR__ ) . '/' . ltrim( $relative, '/' );
	}

	/**
	 * Decoded JSON file. Throws on invalid JSON so the test fails with the parser's message.
	 *
	 * @param string $relative Path from the theme root.
	 * @return array<string, mixed>
	 */
	public static function json( string $relative ): array {
		return json_decode( (string) file_get_contents( self::path( $relative ) ), true, 512, JSON_THROW_ON_ERROR ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	}

	/**
	 * Theme-relative paths matching a glob, e.g. "templates/*.html".
	 *
	 * @param string $pattern Glob relative to the theme root.
	 * @return list<string>
	 */
	public static function glob( string $pattern ): array {
		$root = self::path();

		return array_map(
			static fn ( string $file ): string => substr( $file, strlen( $root ) ),
			glob( $root . $pattern ) ? glob( $root . $pattern ) : array()
		);
	}

	/**
	 * Every style variation file, including colors/, typography/ and sections/.
	 *
	 * @return list<string>
	 */
	public static function style_variations(): array {
		return array_merge( self::glob( 'styles/*.json' ), self::glob( 'styles/*/*.json' ) );
	}

	/**
	 * Header fields of a pattern file, keyed by name ("Title", "Slug", ...).
	 *
	 * @param string $relative Path from the theme root.
	 * @return array<string, string>
	 */
	public static function pattern_headers( string $relative ): array {
		$source = (string) file_get_contents( self::path( $relative ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		preg_match_all( '/^\s*\*\s*([A-Z][A-Za-z ]+):\s*(.+)$/m', $source, $matches, PREG_SET_ORDER );

		$headers = array();
		foreach ( $matches as $match ) {
			$headers[ trim( $match[1] ) ] = trim( $match[2] );
		}

		return $headers;
	}

	/**
	 * Slugs of every pattern file in patterns/.
	 *
	 * @return list<string>
	 */
	public static function pattern_slugs(): array {
		return array_map(
			static fn ( string $file ): string => self::pattern_headers( $file )['Slug'] ?? '',
			self::glob( 'patterns/*.php' )
		);
	}
}
