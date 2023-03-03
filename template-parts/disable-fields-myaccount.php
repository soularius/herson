<?php

remove_action( 'template_redirect', 'save_account_details' );
add_action( 'template_redirect', 'save_account_details' ) ;

function save_account_details() {
    echo 'hello world';
    $nonce_value = wc_get_var( $_REQUEST['save-account-details-nonce'], wc_get_var( $_REQUEST['_wpnonce'], '' ) ); // @codingStandardsIgnoreLine.

    if ( ! wp_verify_nonce( $nonce_value, 'save_account_details' ) ) {
        return;
    }

    if ( empty( $_POST['action'] ) || 'save_account_details' !== $_POST['action'] ) {
        return;
    }

    wc_nocache_headers();

    $user_id = get_current_user_id();

    if ( $user_id <= 0 ) {
        return;
    }

    // $account_first_name   = ! empty( $_POST['account_first_name'] ) ? wc_clean( wp_unslash( $_POST['account_first_name'] ) ) : '';
    // $account_last_name    = ! empty( $_POST['account_last_name'] ) ? wc_clean( wp_unslash( $_POST['account_last_name'] ) ) : '';
    // $account_display_name = ! empty( $_POST['account_display_name'] ) ? wc_clean( wp_unslash( $_POST['account_display_name'] ) ) : '';
    // $account_email        = ! empty( $_POST['account_email'] ) ? wc_clean( wp_unslash( $_POST['account_email'] ) ) : '';
    $pass_cur             = ! empty( $_POST['password_current'] ) ? $_POST['password_current'] : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
    $pass1                = ! empty( $_POST['password_1'] ) ? $_POST['password_1'] : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
    $pass2                = ! empty( $_POST['password_2'] ) ? $_POST['password_2'] : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
    $save_pass            = true;

    // Current user data.
    $current_user       = get_user_by( 'id', $user_id );
    // $current_first_name = $current_user->first_name;
    // $current_last_name  = $current_user->last_name;
    // $current_email      = $current_user->user_email;

    // New user data.
    $user               = new stdClass();
    $user->ID           = $user_id;
    // $user->first_name   = $account_first_name;
    // $user->last_name    = $account_last_name;
    // $user->display_name = $account_display_name;

    // Prevent display name to be changed to email.
    if ( is_email( $account_display_name ) ) {
        wc_add_notice( __( 'Display name cannot be changed to email address due to privacy concern.', 'woocommerce' ), 'error' );
    }

    // Handle required fields.
    // $required_fields = apply_filters(
    // 	'woocommerce_save_account_details_required_fields',
    // 	array(
    // 		'account_first_name'   => __( 'First name', 'woocommerce' ),
    // 		'account_last_name'    => __( 'Last name', 'woocommerce' ),
    // 		'account_display_name' => __( 'Display name', 'woocommerce' ),
    // 		'account_email'        => __( 'Email address', 'woocommerce' ),
    // 	)
    // );

    foreach ( $required_fields as $field_key => $field_name ) {
        if ( empty( $_POST[ $field_key ] ) ) {
            /* translators: %s: Field name. */
            wc_add_notice( sprintf( __( '%s is a required field.', 'woocommerce' ), '<strong>' . esc_html( $field_name ) . '</strong>' ), 'error', array( 'id' => $field_key ) );
        }
    }

    if ( $account_email ) {
        $account_email = sanitize_email( $account_email );
        if ( ! is_email( $account_email ) ) {
            wc_add_notice( __( 'Please provide a valid email address.', 'woocommerce' ), 'error' );
        } elseif ( email_exists( $account_email ) && $account_email !== $current_user->user_email ) {
            wc_add_notice( __( 'This email address is already registered.', 'woocommerce' ), 'error' );
        }
        $user->user_email = $account_email;
    }

    if ( ! empty( $pass_cur ) && empty( $pass1 ) && empty( $pass2 ) ) {
        wc_add_notice( __( 'Please fill out all password fields.', 'woocommerce' ), 'error' );
        $save_pass = false;
    } elseif ( ! empty( $pass1 ) && empty( $pass_cur ) ) {
        wc_add_notice( __( 'Please enter your current password.', 'woocommerce' ), 'error' );
        $save_pass = false;
    } elseif ( ! empty( $pass1 ) && empty( $pass2 ) ) {
        wc_add_notice( __( 'Please re-enter your password.', 'woocommerce' ), 'error' );
        $save_pass = false;
    } elseif ( ( ! empty( $pass1 ) || ! empty( $pass2 ) ) && $pass1 !== $pass2 ) {
        wc_add_notice( __( 'New passwords do not match.', 'woocommerce' ), 'error' );
        $save_pass = false;
    } elseif ( ! empty( $pass1 ) && ! wp_check_password( $pass_cur, $current_user->user_pass, $current_user->ID ) ) {
        wc_add_notice( __( 'Your current password is incorrect.', 'woocommerce' ), 'error' );
        $save_pass = false;
    }

    if ( $pass1 && $save_pass ) {
        $user->user_pass = $pass1;
    }

    // Allow plugins to return their own errors.
    $errors = new WP_Error();
    do_action_ref_array( 'woocommerce_save_account_details_errors', array( &$errors, &$user ) );

    if ( $errors->get_error_messages() ) {
        foreach ( $errors->get_error_messages() as $error ) {
            wc_add_notice( $error, 'error' );
        }
    }

    if ( wc_notice_count( 'error' ) === 0 ) {
        wp_update_user( $user );

        // Update customer object to keep data in sync.
        $customer = new WC_Customer( $user->ID );

        if ( $customer ) {
            // Keep billing data in sync if data changed.
            if ( is_email( $user->user_email ) && $current_email !== $user->user_email ) {
                $customer->set_billing_email( $user->user_email );
            }

            if ( $current_first_name !== $user->first_name ) {
                $customer->set_billing_first_name( $user->first_name );
            }

            if ( $current_last_name !== $user->last_name ) {
                $customer->set_billing_last_name( $user->last_name );
            }

            $customer->save();
        }

        wc_add_notice( __( 'Account details changed successfully.', 'woocommerce' ) );

        do_action( 'woocommerce_save_account_details', $user->ID );

        wp_safe_redirect( wc_get_endpoint_url( 'edit-account', '', wc_get_page_permalink( 'myaccount' ) ) );
        exit;
    }
}


?>