<?php
/**
 * Contact form handling.
 *
 * Deliberately plugin-free: one admin-ajax endpoint, nonce checked, honeypot,
 * and a per-IP rate limit held in a transient.
 *
 * @package Magenta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const MAGENTA_CONTACT_ACTION = 'magenta_contact';

function magenta_handle_contact(): void {
	check_ajax_referer( MAGENTA_CONTACT_ACTION, 'nonce' );

	// Honeypot: bots fill every field they find.
	if ( ! empty( $_POST['website'] ) ) {
		wp_send_json_success( array( 'message' => __( 'Thanks - we will be in touch.', 'magenta' ) ) );
	}

	$ip  = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'unknown';
	$key = 'magenta_contact_' . md5( $ip );
	if ( get_transient( $key ) ) {
		wp_send_json_error(
			array( 'message' => __( 'You just sent one. Give it a minute.', 'magenta' ) ),
			429
		);
	}

	$name    = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
	$email   = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$company = sanitize_text_field( wp_unslash( $_POST['company'] ?? '' ) );
	$service = sanitize_text_field( wp_unslash( $_POST['service'] ?? '' ) );
	$message = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );

	if ( '' === $name || ! is_email( $email ) ) {
		wp_send_json_error(
			array( 'message' => __( 'We need a name and a working email address.', 'magenta' ) ),
			400
		);
	}

	$to = apply_filters( 'magenta_contact_recipient', get_option( 'admin_email' ) );

	$subject = sprintf(
		/* translators: 1: company or person name. */
		__( 'New job enquiry - %s', 'magenta' ),
		$company ?: $name
	);

	$body = implode(
		"\n",
		array(
			'Name: ' . $name,
			'Email: ' . $email,
			'Company: ' . ( $company ?: '-' ),
			'Service: ' . ( $service ?: '-' ),
			'',
			'Message:',
			$message ?: '-',
			'',
			'---',
			'Sent from ' . home_url( '/' ),
		)
	);

	$sent = wp_mail(
		$to,
		$subject,
		$body,
		array(
			'Content-Type: text/plain; charset=UTF-8',
			'Reply-To: ' . $name . ' <' . $email . '>',
		)
	);

	if ( ! $sent ) {
		wp_send_json_error(
			array( 'message' => __( 'Something went wrong sending that. Try us on Instagram instead.', 'magenta' ) ),
			500
		);
	}

	set_transient( $key, 1, MINUTE_IN_SECONDS );

	wp_send_json_success(
		array( 'message' => __( 'Got it. We will come back to you shortly.', 'magenta' ) )
	);
}
add_action( 'wp_ajax_' . MAGENTA_CONTACT_ACTION, 'magenta_handle_contact' );
add_action( 'wp_ajax_nopriv_' . MAGENTA_CONTACT_ACTION, 'magenta_handle_contact' );
