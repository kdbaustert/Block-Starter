<?php
/**
 * Title: Call to action
 * Slug: block-starter/call-to-action
 * Categories: block-starter, call-to-action
 * Keywords: cta, contact, button
 * Viewport Width: 1400
 * Description: A dark full-width band with a heading and one button.
 *
 * @package BlockStarter
 */

?>
<!-- wp:group {"align":"full","className":"is-style-section-dark","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-section-dark" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)">
	<!-- wp:heading {"textAlign":"center"} -->
	<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Have a site that needs a calmer home?', 'block-starter' ); ?></h2>
	<!-- /wp:heading -->

	<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
	<div class="wp-block-buttons">
		<!-- wp:button -->
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( 'mailto:' . antispambot( get_option( 'admin_email' ) ) ); ?>"><?php esc_html_e( 'Email us', 'block-starter' ); ?></a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
