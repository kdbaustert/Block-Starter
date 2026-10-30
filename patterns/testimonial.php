<?php
/**
 * Title: Testimonial
 * Slug: block-starter/testimonial
 * Categories: block-starter, testimonials
 * Keywords: quote, review, testimonial
 * Viewport Width: 1000
 * Description: A single pull quote with a name and role.
 *
 * @package BlockStarter
 */

?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">
	<!-- wp:quote -->
	<blockquote class="wp-block-quote">
		<!-- wp:paragraph -->
		<p><?php esc_html_e( 'They rebuilt our site in six weeks and our editors stopped emailing the developer. That alone paid for it.', 'block-starter' ); ?></p>
		<!-- /wp:paragraph -->
		<cite><?php esc_html_e( 'Dana Ortiz, Managing Editor', 'block-starter' ); ?></cite>
	</blockquote>
	<!-- /wp:quote -->
</div>
<!-- /wp:group -->
