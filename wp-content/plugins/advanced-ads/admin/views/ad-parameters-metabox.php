<?php $types = Advanced_Ads::get_instance()->ad_types; ?>
<?php
/**
 * when changing ad type ad parameter content is loaded via ajax
 * @filesource admin/assets/js/admin.js
 * @filesource includes/class-ajax-callbacks.php ::load_ad_parameters_metabox
 * @filesource classes/ad-type-content.php :: renter_parameters()
 */
do_action( 'advanced-ads-ad-params-before', $ad, $types ); ?>
<div id="advanced-ads-tinymce-wrapper" style="display:none;">
	<?php 
		$args = array(
			// used here instead of textarea_rows, because of display:none
			'editor_height' => 300,
			'drag_drop_upload' => true,
		);
		wp_editor( '', 'advanced-ads-tinymce', $args );
	?>
</div>
<div id="advanced-ads-ad-parameters" class="advads-option-list">
    <?php $type = (isset($types[$ad->type])) ? $types[$ad->type] : current( $types );
	$type->render_parameters( $ad );

	include ADVADS_BASE_PATH . 'admin/views/ad-parameters-size.php'; ?>

	<?php
	if ( defined ( 'WP_DEBUG' ) && WP_DEBUG &&
		( $error = Advanced_Ads_Admin_Ad_Type::check_ad_dom_is_not_valid( $ad ) ) ) : ?>
		<p class="advads-error-message">
			<?php _e( 'The code of this ad might not work properly with the <em>Content</em> placement.', 'advanced-ads' ); 
			?>&nbsp;<?php printf(__( 'Reach out to <a href="%s">support</a> to get help.', 'advanced-ads' ), admin_url('admin.php?page=advanced-ads-support') ); 
			if ( true === WP_DEBUG ) : ?>
				<span style="white-space:pre-wrap"><?php echo $error; ?></span>
			<?php endif;
			?>
		</p>
	<?php endif; ?>
</div>
<?php do_action( 'advanced-ads-ad-params-after', $ad, $types );
