<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.wpdispensary.com/
 * @since      1.0.0
 *
 * @package    WPD_Inventory
 * @subpackage WPD_Inventory/admin
 * @author     WP Dispensary <contact@wpdispensary.com>
 */

/**
 * Flowers inventory management metabox
 *
 * Adds the Inventory metabox to specific custom post type
 *
 * @since    1.0.0
 */
function add_inventory_flowers_metaboxes() {
	add_meta_box(
		'wpdispensary_inventory_flowers',
		__( 'Inventory management', 'wpd-inventory' ),
		'wpdispensary_inventory_flowers',
		'flowers',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'add_inventory_flowers_metaboxes' );

/**
 * Building the metabox
 */
function wpdispensary_inventory_flowers() {
	global $post;

	/** Noncename needed to verify where the data originated */
	echo '<input type="hidden" name="inventoryflowersmeta_noncename" id="inventoryflowersmeta_noncename" value="' .
	wp_create_nonce( plugin_basename( __FILE__ ) ) . '" />';

	/** Get the inventory data if its already been entered */
	$inventoryflowers = get_post_meta( $post->ID, '_inventory_flowers', true );
	$inventorydisplay = get_post_meta( $post->ID , 'wpd_inventory_display', true );
	$inventorycheck   = checked( $inventorydisplay, 'add_wpd_inventory_display', false );

	/** Echo out the fields */
	echo '<div class="wpd-inventory">';
	echo '<p>' . __( 'Available Flowers (grams)', 'wpd-inventory' ) . ':</p>';
	echo '<input type="text" name="_inventory_flowers" value="' . $inventoryflowers  . '" class="widefat" />';
	echo '</div>';
	echo '<div class="wpd-inventory">';
	echo '<p><input type="checkbox" name="wpd_inventory_display" id="wpd_inventory_display" value="add_wpd_inventory_display" '. $inventorycheck .'><label for="wpd_inventory_display">' . __( 'Display inventory in Details table', 'wpd-inventory' ) . '</label></p>';
	echo '</div>';

}

/**
 * Save the metabox
 */
function wpdispensary_save_inventory_flowers_meta( $post_id, $post ) {

	/**
	 * Verify this came from the our screen and with proper authorization,
	 * because save_post can be triggered at other times
	 */
	if ( ! isset( $_POST['inventoryflowersmeta_noncename' ] ) || ! wp_verify_nonce( $_POST['inventoryflowersmeta_noncename'], plugin_basename( __FILE__ ) ) ) {
		return $post->ID;
	}

	/** Is the user allowed to edit the post or page? */
	if ( ! current_user_can( 'edit_post', $post->ID ) ) {
		return $post->ID;
	}

	/**
	 * OK, we're authenticated: we need to find and save the data
	 * We'll put it into an array to make it easier to loop though.
	 */
	$inventory_meta['_inventory_flowers']    = $_POST['_inventory_flowers'];
	$inventory_meta['wpd_inventory_display'] = $_POST['wpd_inventory_display'];

	/** Add values of $inventory_meta as custom fields */

	foreach ( $inventory_meta as $key => $value ) { /** Cycle through the $inventory_meta array! */
		if ( $post->post_type == 'revision' ) { /** Don't store custom data twice */
			return;
		}
		$value = implode( ',', (array) $value ); // If $value is an array, make it a CSV (unlikely)
		if ( get_post_meta( $post->ID, $key, false ) ) { // If the custom field already has a value
			update_post_meta( $post->ID, $key, $value );
		} else { // If the custom field doesn't have a value
			add_post_meta( $post->ID, $key, $value );
		}
		if ( ! $value ) { /** Delete if blank */
			delete_post_meta( $post->ID, $key );
		}
	}

}
add_action( 'save_post', 'wpdispensary_save_inventory_flowers_meta', 1, 2 ); // save the custom fields


/**
 * Edibles inventory management metabox
 *
 * Adds the inventory metabox to specific custom post type
 *
 * @since    1.0.0
 */
function add_inventory_edibles_metaboxes() {
	add_meta_box(
		'wpdispensary_inventory_edibles',
		__( 'Inventory management', 'wpd-inventory' ),
		'wpdispensary_inventory_edibles',
		'edibles',
		'side',
		'default'
	);

}
add_action( 'add_meta_boxes', 'add_inventory_edibles_metaboxes' );

/**
 * Building the metabox
 */
function wpdispensary_inventory_edibles() {
	global $post;

	/** Noncename needed to verify where the data originated */
	echo '<input type="hidden" name="inventoryediblesmeta_noncename" id="inventoryediblesmeta_noncename" value="' .
	wp_create_nonce( plugin_basename( __FILE__ ) ) . '" />';

	/** Get the inventory data if its already been entered */
	$inventoryedibles = get_post_meta( $post->ID, '_inventory_edibles', true );
    $inventorydisplay = get_post_meta( $post->ID , 'wpd_inventory_display', true );
	$inventorycheck   = checked( $inventorydisplay, 'add_wpd_inventory_display', false );

	/** Echo out the fields */
	echo '<div class="pricebox">';
	echo '<p>' . __( 'Available Edibles (units)', 'wpd-inventory' ) . ':</p>';
	echo '<input type="text" name="_inventory_edibles" value="' . $inventoryedibles  . '" class="widefat" />';
	echo '</div>';
	echo '<div class="wpd-inventory">';
	echo '<p><input type="checkbox" name="wpd_inventory_display" id="wpd_inventory_display" value="add_wpd_inventory_display" '. $inventorycheck .'><label for="wpd_inventory_display">' . __( 'Display inventory in Details table', 'wpd-inventory' ) . '</label></p>';
	echo '</div>';

}

/**
 * Save the metabox
 */
function wpdispensary_save_inventory_edibles_meta( $post_id, $post ) {

	/**
	 * Verify this came from the our screen and with proper authorization,
	 * because save_post can be triggered at other times
	 */
	if ( ! isset( $_POST['inventoryediblesmeta_noncename' ] ) || ! wp_verify_nonce( $_POST['inventoryediblesmeta_noncename'], plugin_basename( __FILE__ ) ) ) {
		return $post->ID;
	}

	/** Is the user allowed to edit the post or page? */
	if ( ! current_user_can( 'edit_post', $post->ID ) ) {
		return $post->ID;
	}

	/**
	 * OK, we're authenticated: we need to find and save the data
	 * We'll put it into an array to make it easier to loop though.
	 */
	$inventory_meta['_inventory_edibles']    = $_POST['_inventory_edibles'];
	$inventory_meta['wpd_inventory_display'] = $_POST['wpd_inventory_display'];

	/** Add values of $inventory_meta as custom fields */

	foreach ( $inventory_meta as $key => $value ) { /** Cycle through the $inventory_meta array! */
		if ( $post->post_type == 'revision' ) { /** Don't store custom data twice */
			return;
		}
		$value = implode( ',', (array) $value ); // If $value is an array, make it a CSV (unlikely)
		if ( get_post_meta( $post->ID, $key, false ) ) { // If the custom field already has a value
			update_post_meta( $post->ID, $key, $value );
		} else { // If the custom field doesn't have a value
			add_post_meta( $post->ID, $key, $value );
		}
		if ( ! $value ) { /** Delete if blank */
			delete_post_meta( $post->ID, $key );
		}
	}

}
add_action( 'save_post', 'wpdispensary_save_inventory_edibles_meta', 1, 2 ); // save the custom fields


/**
 * Concentrates inventory management metabox
 *
 * Adds the inventory metabox to specific custom post type
 *
 * @since    1.0.0
 */
function add_inventory_concentrates_metaboxes() {
	add_meta_box(
		'wpdispensary_inventory_concentrates',
		__( 'Inventory management', 'wpd-inventory' ),
		'wpdispensary_inventory_concentrates',
		'concentrates',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'add_inventory_concentrates_metaboxes' );

/**
 * Building the metabox
 */
function wpdispensary_inventory_concentrates() {
	global $post;

	/** Noncename needed to verify where the data originated */
	echo '<input type="hidden" name="inventoryconcentratesmeta_noncename" id="inventoryconcentratesmeta_noncename" value="' .
	wp_create_nonce( plugin_basename( __FILE__ ) ) . '" />';

	/** Get the inventory data if its already been entered */
	$inventoryconcentrates     = get_post_meta( $post->ID, '_inventory_concentrates', true );
	$inventoryconcentrateseach = get_post_meta( $post->ID, '_inventory_concentrates_each', true );
    $inventorydisplay          = get_post_meta( $post->ID , 'wpd_inventory_display', true );
	$inventorycheck            = checked( $inventorydisplay, 'add_wpd_inventory_display', false );

	/** Echo out the fields */
	echo '<div class="wpd-inventory">';
	echo '<p>' . __( 'Available grams', 'wpd-inventory' ) . ':</p>';
	echo '<input type="text" name="_inventory_concentrates" value="' . $inventoryconcentrates  . '" class="widefat" />';
	echo '</div>';
	echo '<div class="wpd-inventory">';
	echo '<p>' . __( 'Available units', 'wpd-inventory' ) . ':</p>';
	echo '<input type="text" name="_inventory_concentrates_each" value="' . $inventoryconcentrateseach  . '" class="widefat" />';
	echo '</div>';
	echo '<div class="wpd-inventory">';
	echo '<p><input type="checkbox" name="wpd_inventory_display" id="wpd_inventory_display" value="add_wpd_inventory_display" '. $inventorycheck .'><label for="wpd_inventory_display">' . __( 'Display inventory in Details table', 'wpd-inventory' ) . '</label></p>';
	echo '</div>';

}

/**
 * Save the metabox
 */
function wpdispensary_save_inventory_concentrates_meta( $post_id, $post ) {

	/**
	 * Verify this came from the our screen and with proper authorization,
	 * because save_post can be triggered at other times
	 */
	if ( ! isset( $_POST['inventoryconcentratesmeta_noncename' ] ) || ! wp_verify_nonce( $_POST['inventoryconcentratesmeta_noncename'], plugin_basename( __FILE__ ) ) ) {
		return $post->ID;
	}

	/** Is the user allowed to edit the post or page? */
	if ( ! current_user_can( 'edit_post', $post->ID ) ) {
		return $post->ID;
	}

	/**
	 * OK, we're authenticated: we need to find and save the data
	 * We'll put it into an array to make it easier to loop though.
	 */
	$inventory_meta['_inventory_concentrates']      = $_POST['_inventory_concentrates'];
	$inventory_meta['_inventory_concentrates_each'] = $_POST['_inventory_concentrates_each'];
	$inventory_meta['wpd_inventory_display']        = $_POST['wpd_inventory_display'];

	/** Add values of $inventory_meta as custom fields */

	foreach ( $inventory_meta as $key => $value ) { /** Cycle through the $inventory_meta array! */
		if ( $post->post_type == 'revision' ) { /** Don't store custom data twice */
			return;
		}
		$value = implode( ',', (array) $value ); // If $value is an array, make it a CSV (unlikely)
		if ( get_post_meta( $post->ID, $key, false ) ) { // If the custom field already has a value
			update_post_meta( $post->ID, $key, $value );
		} else { // If the custom field doesn't have a value
			add_post_meta( $post->ID, $key, $value );
		}
		if ( ! $value ) { /** Delete if blank */
			delete_post_meta( $post->ID, $key );
		}
	}

}
add_action( 'save_post', 'wpdispensary_save_inventory_concentrates_meta', 1, 2 ); // save the custom fields


/**
 * Pre-rolls inventory management metabox
 *
 * Adds the inventory metabox to specific custom post type
 *
 * @since    1.0.0
 */
function add_inventory_prerolls_metaboxes() {
	add_meta_box(
		'wpdispensary_inventory_prerolls',
		__( 'Inventory management', 'wpd-inventory' ),
		'wpdispensary_inventory_prerolls',
		'prerolls',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'add_inventory_prerolls_metaboxes' );

/**
 * Building the metabox
 */
function wpdispensary_inventory_prerolls() {
	global $post;

	/** Noncename needed to verify where the data originated */
	echo '<input type="hidden" name="inventoryprerollsmeta_noncename" id="inventoryprerollsmeta_noncename" value="' .
	wp_create_nonce( plugin_basename( __FILE__ ) ) . '" />';

	/** Get the inventory data if its already been entered */
	$inventoryprerolls = get_post_meta( $post->ID, '_inventory_prerolls', true );
    $inventorydisplay  = get_post_meta( $post->ID , 'wpd_inventory_display', true );
	$inventorycheck    = checked( $inventorydisplay, 'add_wpd_inventory_display', false );

	/** Echo out the fields */
	echo '<div class="wpd-inventory">';
	echo '<p>' . __( 'Available Pre-rolls (units)', 'wpd-inventory' ) . ':</p>';
	echo '<input type="text" name="_inventory_prerolls" value="' . $inventoryprerolls  . '" class="widefat" />';
	echo '</div>';
	echo '<div class="wpd-inventory">';
	echo '<p><input type="checkbox" name="wpd_inventory_display" id="wpd_inventory_display" value="add_wpd_inventory_display" '. $inventorycheck .'><label for="wpd_inventory_display">' . __( 'Display inventory in Details table', 'wpd-inventory' ) . '</label></p>';
	echo '</div>';

}

/**
 * Save the metabox
 */
function wpdispensary_save_inventory_prerolls_meta( $post_id, $post ) {

	/**
	 * Verify this came from the our screen and with proper authorization,
	 * because save_post can be triggered at other times
	 */
	if ( ! isset( $_POST['inventoryprerollsmeta_noncename' ] ) || ! wp_verify_nonce( $_POST['inventoryprerollsmeta_noncename'], plugin_basename( __FILE__ ) ) ) {
		return $post->ID;
	}

	/** Is the user allowed to edit the post or page? */
	if ( ! current_user_can( 'edit_post', $post->ID ) ) {
		return $post->ID;
	}

	/**
	 * OK, we're authenticated: we need to find and save the data
	 * We'll put it into an array to make it easier to loop though.
	 */
	$inventory_meta['_inventory_prerolls']   = $_POST['_inventory_prerolls'];
	$inventory_meta['wpd_inventory_display'] = $_POST['wpd_inventory_display'];

	/** Add values of $inventory_meta as custom fields */

	foreach ( $inventory_meta as $key => $value ) { /** Cycle through the $inventory_meta array! */
		if ( $post->post_type == 'revision' ) { /** Don't store custom data twice */
			return;
		}
		$value = implode( ',', (array) $value ); // If $value is an array, make it a CSV (unlikely)
		if ( get_post_meta( $post->ID, $key, false ) ) { // If the custom field already has a value
			update_post_meta( $post->ID, $key, $value );
		} else { // If the custom field doesn't have a value
			add_post_meta( $post->ID, $key, $value );
		}
		if ( ! $value ) { /** Delete if blank */
			delete_post_meta( $post->ID, $key );
		}
	}

}
add_action( 'save_post', 'wpdispensary_save_inventory_prerolls_meta', 1, 2 ); // save the custom fields


/**
 * Topicals inventory management metabox
 *
 * Adds the inventory metabox to specific custom post type
 *
 * @since    1.0.0
 */
function add_inventory_topicals_metaboxes() {
	add_meta_box(
		'wpdispensary_inventory_topicals',
		__( 'Inventory management', 'wpd-inventory' ),
		'wpdispensary_inventory_topicals',
		'topicals',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'add_inventory_topicals_metaboxes' );

/**
 * Building the metabox
 */
function wpdispensary_inventory_topicals() {
	global $post;

	/** Noncename needed to verify where the data originated */
	echo '<input type="hidden" name="inventorytopicalsmeta_noncename" id="inventorytopicalsmeta_noncename" value="' .
	wp_create_nonce( plugin_basename( __FILE__ ) ) . '" />';

	/** Get the inventory data if its already been entered */
	$inventorytopicals = get_post_meta( $post->ID, '_inventory_topicals', true );
    $inventorydisplay  = get_post_meta( $post->ID , 'wpd_inventory_display', true );
	$inventorycheck    = checked( $inventorydisplay, 'add_wpd_inventory_display', false );

	/** Echo out the fields */
	echo '<div class="wpd-inventory">';
	echo '<p>' . __( 'Available Topicals (units)', 'wpd-inventory' ) . ':</p>';
	echo '<input type="text" name="_inventory_topicals" value="' . $inventorytopicals  . '" class="widefat" />';
	echo '</div>';
	echo '<div class="wpd-inventory">';
	echo '<p><input type="checkbox" name="wpd_inventory_display" id="wpd_inventory_display" value="add_wpd_inventory_display" '. $inventorycheck .'><label for="wpd_inventory_display">' . __( 'Display inventory in Details table', 'wpd-inventory' ) . '</label></p>';
	echo '</div>';

}

/**
 * Save the metabox
 */
function wpdispensary_save_inventory_topicals_meta( $post_id, $post ) {

	/**
	 * Verify this came from the our screen and with proper authorization,
	 * because save_post can be triggered at other times
	 */
	if ( ! isset( $_POST['inventorytopicalsmeta_noncename' ] ) || ! wp_verify_nonce( $_POST['inventorytopicalsmeta_noncename'], plugin_basename( __FILE__ ) ) ) {
		return $post->ID;
	}

	/** Is the user allowed to edit the post or page? */
	if ( ! current_user_can( 'edit_post', $post->ID ) ) {
		return $post->ID;
	}

	/**
	 * OK, we're authenticated: we need to find and save the data
	 * We'll put it into an array to make it easier to loop though.
	 */
	$inventory_meta['_inventory_topicals']   = $_POST['_inventory_topicals'];
	$inventory_meta['wpd_inventory_display'] = $_POST['wpd_inventory_display'];

	/** Add values of $inventory_meta as custom fields */

	foreach ( $inventory_meta as $key => $value ) { /** Cycle through the $inventory_meta array! */
		if ( $post->post_type == 'revision' ) { /** Don't store custom data twice */
			return;
		}
		$value = implode( ',', (array) $value ); // If $value is an array, make it a CSV (unlikely)
		if ( get_post_meta( $post->ID, $key, false ) ) { // If the custom field already has a value
			update_post_meta( $post->ID, $key, $value );
		} else { // If the custom field doesn't have a value
			add_post_meta( $post->ID, $key, $value );
		}
		if ( ! $value ) { /** Delete if blank */
			delete_post_meta( $post->ID, $key );
		}
	}

}
add_action( 'save_post', 'wpdispensary_save_inventory_topicals_meta', 1, 2 ); // save the custom fields


/**
 * Growers inventory management metabox
 *
 * Adds the Inventory metabox to specific custom post type
 *
 * @since    1.0.0
 */
function add_inventory_growers_metaboxes() {
	add_meta_box(
		'wpdispensary_inventory_growers',
		__( 'Inventory management', 'wpd-inventory' ),
		'wpdispensary_inventory_growers',
		'growers',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'add_inventory_growers_metaboxes' );

/**
 * Building the metabox
 */
function wpdispensary_inventory_growers() {
	global $post;

	/** Noncename needed to verify where the data originated */
	echo '<input type="hidden" name="inventorygrowersmeta_noncename" id="inventorygrowersmeta_noncename" value="' .
	wp_create_nonce( plugin_basename( __FILE__ ) ) . '" />';

	/** Get the inventory data if its already been entered */
	$inventoryseeds   = get_post_meta( $post->ID, '_inventory_seeds', true );
	$inventoryclones  = get_post_meta( $post->ID, '_inventory_clones', true );
    $inventorydisplay = get_post_meta( $post->ID , 'wpd_inventory_display', true );
	$inventorycheck   = checked( $inventorydisplay, 'add_wpd_inventory_display', false );

	/** Echo out the fields */
	echo '<div class="wpd-inventory">';
	echo '<p>' . __( 'Available Seeds (units)', 'wpd-inventory' ) . ':</p>';
	echo '<input type="text" name="_inventory_seeds" value="' . $inventoryseeds  . '" class="widefat" />';
	echo '<p>' . __( 'Available Clones (units)', 'wpd-inventory' ) . ':</p>';
	echo '<input type="text" name="_inventory_clones" value="' . $inventoryclones  . '" class="widefat" />';
	echo '</div>';
	echo '<div class="wpd-inventory">';
	echo '<p><input type="checkbox" name="wpd_inventory_display" id="wpd_inventory_display" value="add_wpd_inventory_display" '. $inventorycheck .'><label for="wpd_inventory_display">' . __( 'Display inventory in Details table', 'wpd-inventory' ) . '</label></p>';
	echo '</div>';

}

/**
 * Save the metabox
 */
function wpdispensary_save_inventory_growers_meta( $post_id, $post ) {

	/**
	 * Verify this came from the our screen and with proper authorization,
	 * because save_post can be triggered at other times
	 */
	if ( ! isset( $_POST['inventorygrowersmeta_noncename' ] ) || ! wp_verify_nonce( $_POST['inventorygrowersmeta_noncename'], plugin_basename( __FILE__ ) ) ) {
		return $post->ID;
	}

	/** Is the user allowed to edit the post or page? */
	if ( ! current_user_can( 'edit_post', $post->ID ) ) {
		return $post->ID;
	}

	/**
	 * OK, we're authenticated: we need to find and save the data
	 * We'll put it into an array to make it easier to loop though.
	 */
	$inventory_meta['_inventory_seeds']      = $_POST['_inventory_seeds'];
	$inventory_meta['_inventory_clones']     = $_POST['_inventory_clones'];
	$inventory_meta['wpd_inventory_display'] = $_POST['wpd_inventory_display'];

	/** Add values of $inventory_meta as custom fields */

	foreach ( $inventory_meta as $key => $value ) { /** Cycle through the $inventory_meta array! */
		if ( $post->post_type == 'revision' ) { /** Don't store custom data twice */
			return;
		}
		$value = implode( ',', (array) $value ); // If $value is an array, make it a CSV (unlikely)
		if ( get_post_meta( $post->ID, $key, false ) ) { // If the custom field already has a value
			update_post_meta( $post->ID, $key, $value );
		} else { // If the custom field doesn't have a value
			add_post_meta( $post->ID, $key, $value );
		}
		if ( ! $value ) { /** Delete if blank */
			delete_post_meta( $post->ID, $key );
		}
	}

}
add_action( 'save_post', 'wpdispensary_save_inventory_growers_meta', 1, 2 ); // save the custom fields


/**
 * Tinctures inventory management metabox
 *
 * Adds the inventory metabox to specific custom post type
 *
 * @since    1.4.0
 */
function add_inventory_tinctures_metaboxes() {
	add_meta_box(
		'wpdispensary_inventory_tinctures',
		__( 'Inventory management', 'wpd-inventory' ),
		'wpdispensary_inventory_tinctures',
		'tinctures',
		'side',
		'default'
	);

}

add_action( 'add_meta_boxes', 'add_inventory_tinctures_metaboxes' );

/**
 * Building the metabox
 */
function wpdispensary_inventory_tinctures() {
	global $post;

	/** Noncename needed to verify where the data originated */
	echo '<input type="hidden" name="inventorytincturesmeta_noncename" id="inventorytincturesmeta_noncename" value="' .
	wp_create_nonce( plugin_basename( __FILE__ ) ) . '" />';

	/** Get the inventory data if its already been entered */
	$inventorytinctures = get_post_meta( $post->ID, '_inventory_tinctures', true );
	$inventorydisplay   = get_post_meta( $post->ID , 'wpd_inventory_display', true );
	$inventorycheck     = checked( $inventorydisplay, 'add_wpd_inventory_display', false );

	/** Echo out the fields */
	echo '<div class="pricebox">';
	echo '<p>' . __( 'Available Tinctures (units)', 'wpd-inventory' ) . ':</p>';
	echo '<input type="text" name="_inventory_tinctures" value="' . $inventorytinctures  . '" class="widefat" />';
	echo '</div>';
	echo '<div class="wpd-inventory">';
	echo '<p><input type="checkbox" name="wpd_inventory_display" id="wpd_inventory_display" value="add_wpd_inventory_display" '. $inventorycheck .'><label for="wpd_inventory_display">' . __( 'Display inventory in Details table', 'wpd-inventory' ) . '</label></p>';
	echo '</div>';

}

/**
 * Save the metabox
 */
function wpdispensary_save_inventory_tinctures_meta( $post_id, $post ) {

	/**
	 * Verify this came from the our screen and with proper authorization,
	 * because save_post can be triggered at other times
	 */
	if ( ! isset( $_POST['inventorytincturesmeta_noncename' ] ) || ! wp_verify_nonce( $_POST['inventorytincturesmeta_noncename'], plugin_basename( __FILE__ ) ) ) {
		return $post->ID;
	}

	/** Is the user allowed to edit the post or page? */
	if ( ! current_user_can( 'edit_post', $post->ID ) ) {
		return $post->ID;
	}

	/**
	 * OK, we're authenticated: we need to find and save the data
	 * We'll put it into an array to make it easier to loop though.
	 */
	$inventory_meta['_inventory_tinctures']  = $_POST['_inventory_tinctures'];
	$inventory_meta['wpd_inventory_display'] = $_POST['wpd_inventory_display'];

	/** Add values of $inventory_meta as custom fields */

	foreach ( $inventory_meta as $key => $value ) { /** Cycle through the $inventory_meta array! */
		if ( $post->post_type == 'revision' ) { /** Don't store custom data twice */
			return;
		}
		$value = implode( ',', (array) $value ); // If $value is an array, make it a CSV (unlikely)
		if ( get_post_meta( $post->ID, $key, false ) ) { // If the custom field already has a value
			update_post_meta( $post->ID, $key, $value );
		} else { // If the custom field doesn't have a value
			add_post_meta( $post->ID, $key, $value );
		}
		if ( ! $value ) { /** Delete if blank */
			delete_post_meta( $post->ID, $key );
		}
	}

}
add_action( 'save_post', 'wpdispensary_save_inventory_tinctures_meta', 1, 2 ); // save the custom fields

/**
 * Gear inventory management metabox
 *
 * Adds the inventory metabox to specific custom post type
 *
 * @since    1.4.0
 */
function add_inventory_gear_metaboxes() {
	add_meta_box(
		'wpdispensary_inventory_gear',
		__( 'Inventory management', 'wpd-inventory' ),
		'wpdispensary_inventory_gear',
		'gear',
		'side',
		'default'
	);

}
add_action( 'add_meta_boxes', 'add_inventory_gear_metaboxes' );

/**
 * Building the metabox
 */
function wpdispensary_inventory_gear() {
	global $post;

	/** Noncename needed to verify where the data originated */
	echo '<input type="hidden" name="inventorygearmeta_noncename" id="inventorygearmeta_noncename" value="' .
	wp_create_nonce( plugin_basename( __FILE__ ) ) . '" />';

	/** Get the inventory data if its already been entered */
	$inventorygear    = get_post_meta( $post->ID, '_inventory_gear', true );
	$inventorydisplay = get_post_meta( $post->ID , 'wpd_inventory_display', true );
	$inventorycheck   = checked( $inventorydisplay, 'add_wpd_inventory_display', false );

	/** Echo out the fields */
	echo '<div class="pricebox">';
	echo '<p>' . __( 'Available Gear (units)', 'wpd-inventory' ) . ':</p>';
	echo '<input type="text" name="_inventory_gear" value="' . $inventorygear  . '" class="widefat" />';
	echo '</div>';
	echo '<div class="wpd-inventory">';
	echo '<p><input type="checkbox" name="wpd_inventory_display" id="wpd_inventory_display" value="add_wpd_inventory_display" '. $inventorycheck .'><label for="wpd_inventory_display">' . __( 'Display inventory in Details table', 'wpd-inventory' ) . '</label></p>';
	echo '</div>';

}

/**
 * Save the metabox
 */
function wpdispensary_save_inventory_gear_meta( $post_id, $post ) {

	/**
	 * Verify this came from the our screen and with proper authorization,
	 * because save_post can be triggered at other times
	 */
	if ( ! isset( $_POST['inventorygearmeta_noncename' ] ) || ! wp_verify_nonce( $_POST['inventorygearmeta_noncename'], plugin_basename( __FILE__ ) ) ) {
		return $post->ID;
	}

	/** Is the user allowed to edit the post or page? */
	if ( ! current_user_can( 'edit_post', $post->ID ) ) {
		return $post->ID;
	}

	/**
	 * OK, we're authenticated: we need to find and save the data
	 * We'll put it into an array to make it easier to loop though.
	 */
	$inventory_meta['_inventory_gear']       = $_POST['_inventory_gear'];
	$inventory_meta['wpd_inventory_display'] = $_POST['wpd_inventory_display'];

	/** Add values of $inventory_meta as custom fields */

	foreach ( $inventory_meta as $key => $value ) { /** Cycle through the $inventory_meta array! */
		if ( $post->post_type == 'revision' ) { /** Don't store custom data twice */
			return;
		}
		$value = implode( ',', (array) $value ); // If $value is an array, make it a CSV (unlikely)
		if ( get_post_meta( $post->ID, $key, false ) ) { // If the custom field already has a value
			update_post_meta( $post->ID, $key, $value );
		} else { // If the custom field doesn't have a value
			add_post_meta( $post->ID, $key, $value );
		}
		if ( ! $value ) { /** Delete if blank */
			delete_post_meta( $post->ID, $key );
		}
	}

}
add_action( 'save_post', 'wpdispensary_save_inventory_gear_meta', 1, 2 ); // save the custom fields
