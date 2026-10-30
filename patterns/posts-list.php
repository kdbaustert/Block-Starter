<?php
/**
 * Title: Posts list
 * Slug: block-starter/posts-list
 * Categories: block-starter, query
 * Block Types: core/query
 * Description: Posts as a single column of title, meta and excerpt, with pagination.
 *
 * @package BlockStarter
 */

?>
<!-- wp:query {"queryId":1,"query":{"perPage":10,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","inherit":true}} -->
<div class="wp-block-query">
	<!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|60"}}} -->
		<!-- wp:post-title {"isLink":true,"fontSize":"x-large"} /-->

		<!-- wp:template-part {"slug":"post-meta"} /-->

		<!-- wp:post-excerpt {"moreText":"<?php echo esc_attr__( 'Keep reading', 'block-starter' ); ?>","excerptLength":40} /-->
	<!-- /wp:post-template -->

	<!-- wp:query-no-results -->
		<!-- wp:paragraph -->
		<p><?php esc_html_e( 'Nothing here yet.', 'block-starter' ); ?></p>
		<!-- /wp:paragraph -->
	<!-- /wp:query-no-results -->

	<!-- wp:query-pagination {"layout":{"type":"flex","justifyContent":"space-between"},"style":{"spacing":{"margin":{"top":"var:preset|spacing|60"}}}} -->
		<!-- wp:query-pagination-previous /-->

		<!-- wp:query-pagination-numbers /-->

		<!-- wp:query-pagination-next /-->
	<!-- /wp:query-pagination -->
</div>
<!-- /wp:query -->
