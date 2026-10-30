<?php
/**
 * Title: Three features
 * Slug: block-starter/features
 * Categories: block-starter, columns
 * Keywords: features, services, columns
 * Viewport Width: 1400
 * Description: A heading over three columns of short feature descriptions.
 *
 * @package BlockStarter
 */

$block_starter_features = array(
	array(
		'title' => __( 'Editorial design', 'block-starter' ),
		'text'  => __( 'Type, rhythm and layout chosen for long reading, not for a screenshot.', 'block-starter' ),
	),
	array(
		'title' => __( 'Block themes', 'block-starter' ),
		'text'  => __( 'Sites your team can edit in the Site Editor without breaking the design.', 'block-starter' ),
	),
	array(
		'title' => __( 'Ongoing care', 'block-starter' ),
		'text'  => __( 'Updates, backups and small changes, handled by the people who built it.', 'block-starter' ),
	),
);
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">
	<!-- wp:heading {"align":"wide"} -->
	<h2 class="wp-block-heading alignwide"><?php esc_html_e( 'What we do', 'block-starter' ); ?></h2>
	<!-- /wp:heading -->

	<!-- wp:columns {"align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|50"},"blockGap":{"left":"var:preset|spacing|50"}}}} -->
	<div class="wp-block-columns alignwide" style="margin-top:var(--wp--preset--spacing--50)">
		<?php foreach ( $block_starter_features as $block_starter_feature ) : ?>
		<!-- wp:column {"className":"is-style-section-muted","style":{"spacing":{"padding":{"top":"var:preset|spacing|40","right":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40"}}}} -->
		<div class="wp-block-column is-style-section-muted" style="padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)">
			<!-- wp:heading {"level":3} -->
			<h3 class="wp-block-heading"><?php echo esc_html( $block_starter_feature['title'] ); ?></h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p><?php echo esc_html( $block_starter_feature['text'] ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->
		<?php endforeach; ?>
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
