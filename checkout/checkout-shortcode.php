<?php
// Add Checkout Shortcode.
function wpd_ecommerce_checkout_shortcode() {

if ( ! is_user_logged_in() ) {
    echo '<p>You must be logged in to checkout.</p>';
    echo '<p><a href="' . get_bloginfo( 'url' ) . '/account/" class="button wpd-ecommerce return">Login</a></p>';
} else {
    // Verify that there's an active session.
	if ( ! empty( $_SESSION['wpd_ecommerce'] ) ) {

		// Include notifications.
		echo wpd_ecommerce_notifications();

        global $current_user, $wp_roles;

        $error = array();

        /* If checkout is submitted, do something specific . */
        if ( 'POST' == $_SERVER['REQUEST_METHOD'] && ! empty( $_POST['action'] ) && $_POST['action'] == 'wpd-ecommerce-checkout' ) {

            /** Update Email Address */
            if ( ! empty( $_POST['email'] ) ) {
                if ( ! is_email( esc_attr( $_POST['email'] ) ) )
                    $error[] = __( 'The Email you entered is not valid. Please try again.', 'wpd-ecommerce' );
                elseif( email_exists( esc_attr( $_POST['email'] ) ) != $current_user->ID )
                    $error[] = __( 'This email is already used by another user. Try a different one.', 'wpd-ecommerce' );
                else {
                    wp_update_user( array ( 'ID' => $current_user->ID, 'user_email' => esc_attr( $_POST['email'] ) ) );
                }
            }

            /** Update First Name */
            if ( ! empty( $_POST['first-name'] ) )
                update_user_meta( $current_user->ID, 'first_name', esc_attr( $_POST['first-name'] ) );
            /** Update Last Name */
            if ( ! empty( $_POST['last-name'] ) )
                update_user_meta( $current_user->ID, 'last_name', esc_attr( $_POST['last-name'] ) );
            /** Update Phone Number */
            if ( ! empty( $_POST['phone_number'] ) )
                update_user_meta( $current_user->ID, 'phone_number', esc_attr( $_POST['phone_number'] ) );
            /** Update Address Line 1 */
            if ( ! empty( $_POST['address_line_1'] ) )
                update_user_meta( $current_user->ID, 'address_line_1', esc_attr( $_POST['address_line_1'] ) );
            /** Update Address Line 2 */
            if ( ! empty( $_POST['address_line_2'] ) )
                update_user_meta( $current_user->ID, 'address_line_2', esc_attr( $_POST['address_line_2'] ) );
            /** Update City */
            if ( ! empty( $_POST['city'] ) )
                update_user_meta( $current_user->ID, 'city', esc_attr( $_POST['city'] ) );
            /** Update State/County */
            if ( ! empty( $_POST['state_county'] ) )
                update_user_meta( $current_user->ID, 'state_county', esc_attr( $_POST['state_county'] ) );
            /** Update Postcode/Zip */
            if ( ! empty( $_POST['postcode_zip'] ) )
                update_user_meta( $current_user->ID, 'postcode_zip', esc_attr( $_POST['postcode_zip'] ) );
            /** Update Country */
            if ( ! empty( $_POST['country'] ) )
                update_user_meta( $current_user->ID, 'country', esc_attr( $_POST['country'] ) );

            /**
             * Redirect so the page will show updated info.
             */
            if ( count( $error ) == 0 ) {
                //action hook for plugins and extra fields saving
                do_action( 'edit_user_profile_update', $current_user->ID );
            }

            $wpd_general  = get_option( 'wpdas_general' );
            $min_checkout = $wpd_general['wpd_ecommerce_checkout_minimum_order'];

            if ( '' !== $wpd_payments['wpd_ecommerce_checkout_minimum_order'] ) {
                if ( $_SESSION['wpd_ecommerce']->sum >= $min_checkout ) {
                    // Run success codes.
                    wpd_ecommerce_checkout_success();
                } else {
                    $str = '<div class="wpd-ecommerce-notifications failed"><strong>' . __( 'Error', 'wpd-ecommerce' ) . ':</strong> ' . __( 'The minimum order amount required to checkout is', 'wpd-ecommerce' ) . ' ' . wpd_currency_code() . $min_checkout . '</div>';
                    echo $str;
                }
            } else {
                wpd_ecommerce_checkout_success();
            }
        }
        ?>

		<?php do_action( 'wpd_ecommerce_checkout_billing_details_form_before' ); ?>

        <form method="post" id="checkout" class="wpd-ecommerce form checkout" action="<?php the_permalink(); ?>">

		<?php do_action( 'wpd_ecommerce_checkout_billing_details_form_inside_before' ); ?>

		<h3 class='wpd-ecommerce patient-title'><?php _e( 'Billing details', 'wpd-ecommerce' ); ?></h3>

		<?php do_action( 'wpd_ecommerce_checkout_billing_details_form_after_billing_details_title' ); ?>

        <p class="form-row first form-first-name">
            <label for="first-name"><?php _e('First Name', 'wpd-ecommerce' ); ?><span class="required">*</span></label>
            <input class="text-input" name="first-name" type="text" id="first-name" value="<?php the_author_meta( 'first_name', $current_user->ID ); ?>" />
        </p><!-- .form-first-name -->
        <p class="form-row last form-last-name">
            <label for="last-name"><?php _e('Last Name', 'wpd-ecommerce' ); ?><span class="required">*</span></label>
            <input class="text-input" name="last-name" type="text" id="last-name" value="<?php the_author_meta( 'last_name', $current_user->ID ); ?>" />
        </p><!-- .form-last-name -->

        <p class="form-row form-email">
            <label for="email"><?php _e( 'E-mail', 'wpd-ecommerce' ); ?><span class="required">*</span></label>
            <input class="text-input" name="email" type="text" id="email" value="<?php the_author_meta( 'user_email', $current_user->ID ); ?>" />
        </p><!-- .form-email -->

        <p class="form-row form-phone-number">
            <label for="phone-number"><?php _e( 'Phone number', 'wpd-ecommerce' ); ?></label>
            <input class="text-input" name="phone_number" type="text" id="phone_number" value="<?php the_author_meta( 'phone_number', $current_user->ID ); ?>" />
        </p><!-- .form-phone-number -->

        <p class="form-row form-address-line">
            <label for="address-line"><?php _e( 'Street address', 'wpd-ecommerce' ); ?></label>
            <input class="text-input" name="address_line_1" type="text" id="address_line_1" value="<?php the_author_meta( 'address_line_1', $current_user->ID ); ?>" placeholder="<?php _e( 'House number and street name', 'wpd-ecommerce' ); ?>" />
            <input class="text-input" name="address_line_2" type="text" id="address_line_2" value="<?php the_author_meta( 'address_line_2', $current_user->ID ); ?>" placeholder="<?php _e( 'Apartment, unit, etc. (optional)', 'wpd-ecommerce' ); ?>" />
        </p><!-- .form-address-line -->

        <p class="form-row form-city">
            <label for="city"><?php _e( 'City', 'wpd-ecommerce' ); ?></label>
            <input class="text-input" name="city" type="text" id="city" value="<?php the_author_meta( 'city', $current_user->ID ); ?>" />
        </p><!-- .form-city -->

        <p class="form-row form-state-county">
            <label for="state-county"><?php _e( 'State / County', 'wpd-ecommerce' ); ?></label>
            <input class="text-input" name="state_county" type="text" id="state_county" value="<?php the_author_meta( 'state_county', $current_user->ID ); ?>" />
        </p><!-- .form-state-county -->

        <p class="form-row form-postcode-zip">
            <label for="email"><?php _e( 'Postcode / ZIP', 'wpd-ecommerce' ); ?></label>
            <input class="text-input" name="postcode_zip" type="text" id="postcode_zip" value="<?php the_author_meta( 'postcode_zip', $current_user->ID ); ?>" />
        </p><!-- .form-postcode-zip -->

        <p class="form-row form-country">
            <label for="email"><?php _e( 'Country', 'wpd-ecommerce' ); ?></label>
            <input class="text-input" name="country" type="text" id="country" value="<?php the_author_meta( 'country', $current_user->ID ); ?>" />
        </p><!-- .form-phone-country -->

		<?php do_action( 'wpd_ecommerce_checkout_billing_details_form_after_billing_details' ); ?>

		<h3 class='wpd-ecommerce patient-order'><?php _e( 'Your order', 'wpd-ecommerce' ); ?></h3>

		<?php do_action( 'wpd_ecommerce_checkout_billing_details_form_after_your_order_title' ); ?>

        <?php
        $str  = '<table class="wpd-ecommerce widget checkout">';
        $str .= '<thead>';
        $str .= '<tr><td>' . __( 'Product', 'wpd-ecommerce' ) . '</td><td>' . __( 'Total', 'wpd-ecommerce' ) . '</td></tr>';
        $str .= '</thead>';
        $str .= '<tbody>';

        foreach( $_SESSION['wpd_ecommerce']->item_array as $id=>$amount ):
            $i             = new Item( $id, '', '', '' );
            $item_old_id   = preg_replace( '/[^0-9.]+/', '', $id );
            $item_meta_key = preg_replace( '/[0-9]+/', '', $id );

            if ( in_array( get_post_type( $item_old_id ), array( 'edibles', 'prerolls', 'growers', 'gear', 'tinctures' ) ) ) {

                $units_per_pack = esc_html( get_post_meta( $item_old_id, '_unitsperpack', true ) );

                $item_old_id   = preg_replace( '/[^0-9.]+/', '', $i->id );
                $item_meta_key = preg_replace( '/[0-9]+/', '', $i->id );

                if ( '_priceperpack' === $item_meta_key ) {
                    $regular_price = esc_html( get_post_meta( $item_old_id, '_priceperpack', true ) );
                } else {
                    $regular_price = esc_html( get_post_meta( $item_old_id, '_priceeach', true ) );
                }

                if ( '_priceperpack' === $item_meta_key ) {
                    $weightname = $units_per_pack . ' pack';
                } else {
                    $weightname = '';
                }

            } elseif ( 'topicals' === get_post_type( $item_old_id ) ) {

                $units_per_pack = esc_html( get_post_meta( $item_old_id, '_unitsperpack', true ) );

                $item_old_id   = preg_replace( '/[^0-9.]+/', '', $i->id );
                $item_meta_key = preg_replace( '/[0-9]+/', '', $i->id );

                if ( '_pricetopical' === $item_meta_key ) {
                    $regular_price = esc_html( get_post_meta( $item_old_id, '_pricetopical', true ) );
                } elseif ( '_priceperpack' === $item_meta_key ) {
                    $regular_price = esc_html( get_post_meta( $item_old_id, '_priceperpack', true ) );
                } elseif ( '_priceeach' === $item_meta_key ) {
                    $regular_price = esc_html( get_post_meta( $item_old_id, '_priceeach', true ) );
                }

                if ( '_priceperpack' === $item_meta_key ) {
                    $weightname = $units_per_pack . ' pack';
                } else {
                    $weightname = '';
                }

            } elseif ( 'flowers' === get_post_type( $item_old_id ) ) {
                $regular_price = esc_html( get_post_meta( $item_old_id, $item_meta_key, true ) );

                /**
                 * @todo make flower_names through the entier plugin filterable.
                 */
                $flower_names = array(
                    '1 g'    => '_gram',
                    '2 g'    => '_twograms',
                    '1/8 oz' => '_eighth',
                    '5 g'    => '_fivegrams',
                    '1/4 oz' => '_quarter',
                    '1/2 oz' => '_halfounce',
                    '1 oz'   => '_ounce',
                );

                $item_old_id        = preg_replace( '/[^0-9.]+/', '', $i->id );
                $flower_weight_cart = preg_replace( '/[0-9]+/', '', $i->id );

                foreach ( $flower_names as $value=>$key ) {
                    if ( $key == $flower_weight_cart ) {
                        $weightname = " - " . $value;
                    }
                }

            } elseif ( 'concentrates' === get_post_type( $item_old_id ) ) {
                $regular_price = esc_html( get_post_meta( $item_old_id, $item_meta_key, true ) );

                /**
                 * @todo make concentrate_names through the entire plugin filterable.
                 */
                $concentrates_names = array(
                    '1/2 g' => '_halfgram',
                    '1 g'   => '_gram',
                    '2 g'   => '_twograms',
                );

                $item_old_id             = preg_replace( '/[^0-9.]+/', '', $i->id );
                $concentrate_weight_cart = preg_replace( '/[0-9]+/', '', $i->id );

                foreach ( $concentrates_names as $value=>$key ) {
                    if ( $key == $concentrate_weight_cart ) {
                        $weightname = " - " . $value;
                    }
                }
                if ( '_priceeach' === $concentrate_weight_cart ) {
                    $weightname = '';
                }
            } else {
                // Do nothing.
            }

            // print_r( $i );

            $total_price = $amount * $regular_price;

            $str .=	"<tr><td>" . $i->thumbnail . "<a href='" . $i->permalink . "' class='wpd-ecommerce-widget title'>" . $i->title . "" . $weightname . "</a> x <strong>" . $amount . "</strong></td><td><span class='wpd-ecommerce-widget amount'>" . CURRENCY . number_format( $total_price, 2, '.', ',' ) . "</span></td></tr>";

        endforeach;

        $total_price = ( number_format((float)$_SESSION['wpd_ecommerce']->sales_tax, 2, '.', ',' ) + number_format((float)$_SESSION['wpd_ecommerce']->excise_tax, 2, '.', ',' ) + number_format((float)$_SESSION['wpd_ecommerce']->payment_type_amount, 2, '.', ',' ) + $_SESSION['wpd_ecommerce']->sum );

        $str .= "<tr><td><strong>" . __( 'Subtotal', 'wpd-ecommerce' ) . "</strong></td><td>" . CURRENCY . number_format( (float)$_SESSION['wpd_ecommerce']->sum, 2, '.', ',' ) . "</td></tr>";
		if ( 0 !== $_SESSION['wpd_ecommerce']->coupon_code ) {
			$str .= "<tr><td><strong>" . __( 'Coupon', 'wpd-ecommerce' ) . ":<br />" . $_SESSION['wpd_ecommerce']->coupon_code . "</strong></td><td>-" . CURRENCY . number_format((float)$_SESSION['wpd_ecommerce']->coupon_amount, 2, '.', ',' ) . " (<a href='" . get_the_permalink() . "?remove_coupon=". $_SESSION['wpd_ecommerce']->coupon_code . "'>" . __( 'Remove', 'wpd-ecommerce' ) . "?</a>)</td></tr>";
		}
        if ( NULL !== SALES_TAX ) {
            $str .= "<tr><td><strong>" . __( 'Sales tax', 'wpd-ecommerce' ) . "</strong></td><td>" . CURRENCY . number_format((float)$_SESSION['wpd_ecommerce']->sales_tax, 2, '.', ',' ) . "</td></tr>";
        }
        if ( NULL !== EXCISE_TAX ) {
            $str .= "<tr><td><strong>" . __( 'Excise tax', 'wpd-ecommerce' ) . "</strong></td><td>" . CURRENCY . number_format((float)$_SESSION['wpd_ecommerce']->excise_tax, 2, '.', ',' ) . "</td></tr>";
        }
		if ( NULL !== PAYMENT_TYPE_AMOUNT ) {
			$str .= "<tr><td><strong>" . PAYMENT_TYPE_NAME . "</strong></td><td>" . CURRENCY . number_format((float)$_SESSION['wpd_ecommerce']->payment_type_amount, 2, '.', ',' ) . "</td></tr>";
		}
        $str .= "<tr><td><strong>" . __( 'Total', 'wpd-ecommerce' ) . "</strong></td><td>" . CURRENCY . number_format( $total_price, 2, '.', ',' ) . "</td></tr>";

        $str .= "</tbody>";
        $str .= "</table>";

        $str .= "<p class='form-submit'><input name='checkout-submit' type='submit' id='checkoutsubmit' class='submit button' value='" . __( 'Place Order', 'wpd-ecommerce' ) . "' />" . wp_nonce_field( 'wpd-ecommerce-checkout' ) . "<input name='action' type='hidden' id='action' value='wpd-ecommerce-checkout' /></p>";
        $str .= "</form>";

        echo $str;

	} else {
        echo "<p>" . __( 'You can check out after adding some products to your cart', 'wpd-ecommerce' ) . "</p>";

        $wpdas_pages = get_option( 'wpdas_pages' );
        $menu_page   = $wpdas_pages['wpd_pages_setup_menu_page'];

        echo '<p><a href="' . get_bloginfo( 'url' ) . '/' . $menu_page . '" class="button wpd-ecommerce return">' . __( 'Return to menu', 'wpd-ecommerce' ) . '</a></p>';
	}
} // is user logged in
}
add_shortcode( 'wpd_checkout', 'wpd_ecommerce_checkout_shortcode' );

/**
 * Fire this off when the order is a success.
 */
function wpd_ecommerce_checkout_success() {

    $page_date    = date( 'Y-m-d H:i:s' );
    $current_user = wp_get_current_user();

    $customer_details  = '';
    $customer_details .= '<p><strong>' . __( 'Name', 'wpd-ecommerce' ) . ':</strong> ' . $current_user->first_name . ' ' . $current_user->last_name . '</p>';
    $customer_details .= '<p><strong>' . __( 'Email', 'wpd-ecommerce' ) . ':</strong> ' . $current_user->user_email . '</p>';

    $customer_id = $current_user->ID;

    // Order database variables.
    $wpd_orders_data      = array();
    $wpd_orders_item_data = array();

    echo "<h3 class='wpd-ecommerce patient-order'>" . __( 'Your order', 'wpd-ecommerce' ) . "</h3>";

    $str  = '';
    $str  = '<table style="border-collapse: collapse;width: 100%;max-width: 600px;margin: 0 auto;" class="wpd-ecommerce widget checkout">';
    $str .= '<thead style="border: 1px solid #DDD;">';
    $str .= '<tr style="font-weight: 700;"><td style="text-align: left; padding: 10px;">' . __( 'Product', 'wpd-ecommerce' ) . '</td><td style="text-align: left;">' . __( 'Total', 'wpd-ecommerce' ) . '</td></tr>';
    $str .= '</thead>';
    $str .= '<tbody style="border-bottom: 1px solid #DDD;">';

    /**
     * Loop through each item in the cart
     */
    foreach( $_SESSION['wpd_ecommerce']->item_array as $id=>$amount ):
        $i             = new Item( $id, '', '', '' );
        $item_old_id   = preg_replace( '/[^0-9.]+/', '', $id );
        $item_meta_key = preg_replace( '/[0-9]+/', '', $id );

        if ( in_array( get_post_type( $item_old_id ), array( 'edibles', 'prerolls', 'growers', 'gear', 'tinctures' ) ) ) {

            $units_per_pack = esc_html( get_post_meta( $item_old_id, '_unitsperpack', true ) );

            $item_old_id   = preg_replace( '/[^0-9.]+/', '', $i->id );
            $item_meta_key = preg_replace( '/[0-9]+/', '', $i->id );

            if ( '_priceperpack' === $item_meta_key ) {
                $regular_price = esc_html( get_post_meta( $item_old_id, '_priceperpack', true ) );
            } else {
                $regular_price = esc_html( get_post_meta( $item_old_id, '_priceeach', true ) );
            }

            if ( '_priceperpack' === $item_meta_key ) {
                $weightname = $units_per_pack . ' pack';
            } else {
                $weightname = '';
            }

        } elseif ( 'topicals' === get_post_type( $item_old_id ) ) {

            $units_per_pack = esc_html( get_post_meta( $item_old_id, '_unitsperpack', true ) );

            $item_old_id   = preg_replace( '/[^0-9.]+/', '', $i->id );
            $item_meta_key = preg_replace( '/[0-9]+/', '', $i->id );

            if ( '_pricetopical' === $item_meta_key ) {
                $regular_price = esc_html( get_post_meta( $item_old_id, '_pricetopical', true ) );
            } elseif ( '_priceperpack' === $item_meta_key ) {
                $regular_price = esc_html( get_post_meta( $item_old_id, '_priceperpack', true ) );
            } elseif ( '_priceeach' === $item_meta_key ) {
                $regular_price = esc_html( get_post_meta( $item_old_id, '_priceeach', true ) );
            }

            if ( '_priceperpack' === $item_meta_key ) {
                $weightname = $units_per_pack . ' pack';
            } else {
                $weightname = '';
            }

        } elseif ( 'flowers' === get_post_type( $item_old_id ) ) {
            $regular_price = esc_html( get_post_meta( $item_old_id, $item_meta_key, true ) );

            /**
             * @todo make flower_names through the entier plugin filterable.
             */
            $flower_names = array(
                '1 g'    => '_gram',
                '2 g'    => '_twograms',
                '1/8 oz' => '_eighth',
                '5 g'    => '_fivegrams',
                '1/4 oz' => '_quarter',
                '1/2 oz' => '_halfounce',
                '1 oz'   => '_ounce',
            );

            $item_old_id        = preg_replace( '/[^0-9.]+/', '', $i->id );
            $flower_weight_cart = preg_replace( '/[0-9]+/', '', $i->id );

            foreach ( $flower_names as $value=>$key ) {
                if ( $key == $flower_weight_cart ) {
                    $weightname = $value;
                }
            }

        } elseif ( 'concentrates' === get_post_type( $item_old_id ) ) {
            $regular_price = esc_html( get_post_meta( $item_old_id, $item_meta_key, true ) );

            /**
             * @todo make concentrate_names through the entier plugin filterable.
             */
            $concentrates_names = array(
                '1/2 g' => '_halfgram',
                '1 g'   => '_gram',
                '2 g'   => '_twograms',
            );

            $item_old_id             = preg_replace( '/[^0-9.]+/', '', $i->id );
            $concentrate_weight_cart = preg_replace( '/[0-9]+/', '', $i->id );

            foreach ( $concentrates_names as $value=>$key ) {
                if ( $key == $concentrate_weight_cart ) {
                    $weightname = $value;
                }
            }
            if ( '_priceeach' === $concentrate_weight_cart ) {
                $weightname = '';
            }
        } else {
            // Do nothing.
        }

        // print_r( $i );

        // Total price.
        $total_price = $amount * $regular_price;

        // Order name.
        $order_item_name = $i->title . ' - ' . $weightname;

        // Add order details to array.
        $wpd_orders_data[] = array(
            $i->id => $order_item_name
        );

        // Get cart item data.
        $orders_meta_insert[] = array(
            'order_item_id'        => $i->id,
            'order_item_name'      => $i->title,
            'item_id'              => $item_old_id,
            'item_url'             => $i->permalink,
            'item_image_url'       => get_the_post_thumbnail_url( $i->id, 'full' ),
            'item_image_url_thumb' => get_the_post_thumbnail_url( $i->id, 'thumbnail' ),
            'item_variation'       => $item_meta_key,
            'item_variation_name'  => $weightname,
            'quantity'             => $amount,
            'single_price'         => $regular_price,
            'total_price'          => $total_price
        );

        // Add item quantity to array.
        $total_items[] = $amount;

        $str .=	"<tr style='border-bottom: 1px solid #DDD; border-left: 1px solid #DDD; border-right: 1px solid #DDD;'><td style='padding: 12px 12px; vertical-align: middle;'>" . $i->thumbnail . "<a href='" . $i->permalink . "' class='wpd-ecommerce-widget title'>" . $i->title . "" . $weightname . "</a> x <strong>" . $amount . "</strong></td><td style='padding: 12px 12px; vertical-align: middle;'><span class='wpd-ecommerce-widget amount'>" . CURRENCY . number_format( $total_price, 2, '.', ',' ) . "</span></td></tr>";

    endforeach;

    $str .= "</tbody>";
    $str .= "</table>";

    // Total price.
    $total_price = ( number_format((float)$_SESSION['wpd_ecommerce']->sales_tax, 2, '.', ',' ) + number_format((float)$_SESSION['wpd_ecommerce']->excise_tax, 2, '.', ',' ) + number_format((float)$_SESSION['wpd_ecommerce']->payment_type_amount, 2, '.', ',' ) + $_SESSION['wpd_ecommerce']->sum );

    // Coupon total.
    $coupon_total = $_SESSION['wpd_ecommerce']->coupon_amount;

    // Create orders array.
    $orders_insert   = array();
    $orders_insert[] = array(
        'order_subtotal'            => number_format((float)$_SESSION['wpd_ecommerce']->sum, 2, '.', ',' ),
        'order_coupon_code'         => $_SESSION['wpd_ecommerce']->coupon_code,
        'order_coupon_amount'       => number_format((float)$coupon_total, 2, '.', ',' ),
        'order_payment_type_name'   => PAYMENT_TYPE_NAME,
        'order_payment_type_amount' => number_format((float)$_SESSION['wpd_ecommerce']->payment_type_amount, 2, '.', ',' ),
        'order_sales_tax'           => number_format((float)$_SESSION['wpd_ecommerce']->sales_tax, 2, '.', ',' ),
        'order_excise_tax'          => number_format((float)$_SESSION['wpd_ecommerce']->excise_tax, 2, '.', ',' ),
        'order_total'               => number_format((float)$total_price, 2, '.', ',' ),
        'order_items'               => array_sum( $total_items )
    );

    /**
     * Create new ORDER in WordPress
     */
    $wpd_order = array(
        'post_type'   => 'wpd_orders',
        'post_status' => 'publish',
        'post_author' => 1,
        'date'        => $page_date, // YYYY-MM-DDTHH:MM:SS
        'meta_input'  => array(
            'wpd_order_customer_details' => $customer_details,
            'wpd_order_customer_id'      => $customer_id,
            'wpd_order_status'           => 'wpd-processing',
            'wpd_order_total_price'      => number_format((float)$total_price, 2, '.', ',' ),
            'wpd_order_sales_tax'        => number_format((float)$_SESSION['wpd_ecommerce']->sales_tax, 2, '.', ',' ),
            'wpd_order_excise_tax'       => number_format((float)$_SESSION['wpd_ecommerce']->excise_tax, 2, '.', ',' ),
            'wpd_order_subtotal_price'   => number_format((float)$_SESSION['wpd_ecommerce']->sum, 2, '.', ',' ),
            'wpd_order_items'            => array_sum( $total_items )
        ),
    );

    // Insert the order into WordPress.
    $wpd_order_id = wp_insert_post( $wpd_order );

    global $wpdb;

    /**
     * Insert order details into wpd_orders table.
     *
     * @since 1.0.0
     */

    // Get orders data.
    $orders_data    = array_values( $wpd_orders_data );
    $orders_details = array_values( $orders_insert );

    $od = -1;

    // loop through cart.
    foreach( $_SESSION['wpd_ecommerce']->item_array as $id=>$amount ):
        $od++;

        // Loop through order items.
        foreach ( $orders_data[$od] as $id=>$name ) {
            // Insert data into database.
            $wpdb->insert( $wpdb->prefix . 'wpd_orders', array(
                'order_id'    => $wpd_order_id,
                'order_type'  => 'product',
                'order_key'   => $id,
                'order_value' => $name,
            ) );
        }

    endforeach;

    // Get order details.
    foreach ( $orders_details[0] as $id=>$name ) {
        $wpdb->insert( $wpdb->prefix . 'wpd_orders', array(
            'order_id'    => $wpd_order_id,
            'order_type'  => 'details',
            'order_key'   => $id,
            'order_value' => $name,
        ) );
    }

    // Get row's from database with current $wpd_order_id.
    $get_order_data = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpd_orders WHERE order_id = {$wpd_order_id} AND order_type = 'product'", ARRAY_A );

    $od++;

    /**
     * Add order meta to each order item in database
     */
    $i = -1;

    // Loop through each product in the database.
    foreach( $get_order_data as $order_value ) {
        $i++;
        $order_id_key = $order_value['item_id'];
        $array        = array_values( $orders_meta_insert );

        // Get key/value of each array result.
        foreach( $array[$i] as $key => $value ) {
            // Does this 4 times.
            $wpdb->insert( $wpdb->prefix . 'wpd_orders_meta', array(
                'item_id'    => $order_id_key,
                'meta_key'   => $key,
                'meta_value' => $value,
            ));
        }
    
    }

    /**
     * Inventory updates
     * 
     * @since 1.0
     */
    wpd_ecommerce_inventory_management_updates( $wpd_order_id );

     // This updates the new order with custom title, etc.
    $updated_post = array(
        'ID'            => $wpd_order_id,
        'post_title'    => 'Order #' . $wpd_order_id,
        'post_status'   => 'publish', // Now it's public
        'post_type'     => 'wpd_orders'
    );
    wp_update_post( $updated_post );

    /**
     * Email order details to Administrator.
     * 
     * @since 1.0
     */
    $order             = $wpd_order_id;
    $order_customer_id = get_post_meta( $wpd_order_id, 'wpd_order_customer_id', true );
    $user_info         = get_userdata( $order_customer_id );
    $to                = get_option( 'admin_email' );
    $subject           = 'New order: #' . $order;

    $message   = '<p>Hello Administrator,</p>';
    $message  .= '<p>' . get_bloginfo( 'name' ) . ' just received a new order from ' . $user_info->first_name . ' ' . $user_info->last_name . '.</p>';
    $message  .= $str;

    $headers[] = 'From: ' . get_bloginfo( 'name' ) . ' <' . get_option( 'admin_email' ) . '>';
    $headers[] = 'Content-Type: text/html';
    $headers[] = 'charset=UTF-8';

    wp_mail( $to, $subject, $message, $headers, '' );

    /**
     * Email order details to Patient.
     * 
     * @since 1.0
     */
    $order             = $wpd_order_id;
    $order_customer_id = get_post_meta( $wpd_order_id, 'wpd_order_customer_id', true );
    $user_info         = get_userdata( $order_customer_id );
    $to_patient        = $user_info->user_email;
    $subject_patient   = 'Thank you for your order: #' . $order;

    $message   = '<p>Hello ' . $user_info->first_name . ',</p>';
    $message  .= '<p>Thank you for your order. You can see details of your order below as well as in your account at ' . get_bloginfo( 'name' ) . '</p>';
    $message  .= '<p>- ' . get_bloginfo( 'name' ) . '<br />' . get_bloginfo( 'url' ) . '</p>';
    $message  .= $str;

    $headers_patient[] = 'From: ' . get_bloginfo( 'name' ) . ' <' . get_option( 'admin_email' ) . '>';
    $headers_patient[] = 'Content-Type: text/html';
    $headers_patient[] = 'charset=UTF-8';

    wp_mail( $to_patient, $subject_patient, $message, $headers_patient, '' );

    /**
     * Destroy session
     * 
     * @since 1.0
     */
    wpd_ecommerce_destroy_session();

    // Redirect to the order page.
    wp_redirect( get_bloginfo( 'url' ) . '/order/' . $wpd_order_id . '?order=thank-you' );

    exit;
}