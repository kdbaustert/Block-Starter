<?php
/**
 * Base class for tests that need WordPress loaded.
 *
 * @package BlockStarter
 */

namespace BlockStarter\Tests\Integration;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

/**
 * Skips when tests/bootstrap.php ran without WP_PATH.
 */
abstract class TestCase extends PHPUnitTestCase {

	protected function setUp(): void {
		parent::setUp();

		if ( ! defined( 'ABSPATH' ) ) {
			$this->markTestSkipped( 'Set WP_PATH to a WordPress install with block-starter active.' );
		}
	}
}
