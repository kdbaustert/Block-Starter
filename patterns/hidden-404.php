<?php
/**
 * Title: 404 content
 * Slug: block-starter/hidden-404
 * Inserter: no
 *
 * @package BlockStarter
 */

?>
<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading"><?php esc_html_e( 'Page not found', 'block-starter' ); ?></h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"contrast-2"} -->
<p class="has-contrast-2-color has-text-color"><?php esc_html_e( 'The page you wanted has moved or never existed. Try a search instead.', 'block-starter' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:search {"label":"<?php echo esc_attr__( 'Search', 'block-starter' ); ?>","showLabel":false,"buttonText":"<?php echo esc_attr__( 'Search', 'block-starter' ); ?>"} /-->
