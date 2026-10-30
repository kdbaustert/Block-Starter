<?php
/**
 * Title: Hero
 * Slug: block-starter/hero
 * Categories: block-starter, banner
 * Keywords: hero, intro, heading
 * Viewport Width: 1400
 * Description: A large headline with a short intro and two buttons.
 *
 * @package BlockStarter
 */

?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)">
	<!-- wp:paragraph {"textColor":"accent","fontSize":"small","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.12em","fontWeight":"600"}}} -->
	<p class="has-accent-color has-text-color has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase"><?php esc_html_e( 'Independent studio', 'block-starter' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:heading {"level":1,"fontSize":"xx-large"} -->
	<h1 class="wp-block-heading has-xx-large-font-size"><?php esc_html_e( 'We design and build careful, quiet websites.', 'block-starter' ); ?></h1>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"textColor":"contrast-2","fontSize":"large"} -->
	<p class="has-contrast-2-color has-text-color has-large-font-size"><?php esc_html_e( 'Small team, long relationships. We work with publishers, nonprofits and local businesses that care how their site reads.', 'block-starter' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|40"}}}} -->
	<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--40)">
		<!-- wp:button -->
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Start a project', 'block-starter' ); ?></a></div>
		<!-- /wp:button -->

		<!-- wp:button {"className":"is-style-outline"} -->
		<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/work/' ) ); ?>"><?php esc_html_e( 'See our work', 'block-starter' ); ?></a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
