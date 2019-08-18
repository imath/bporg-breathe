<?php
/**
 * o2 Hovercards service.
 *
 * @package bporg-breathe
 * @since 1.0.0
 */

namespace {
	/**
	 * Fetch Trac ticket.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args {
	 *        Arguments to fetch tickets for the service.
	 *
	 *        @type string $id      The ID of the trac ticket.
	 *        @type string $service The name of the service being requested.
	 *        @type string $url     The url of the ticket.
	 * }
	 * @return array The response to send.
	 */
	function handle_trac( $args = array() ) {
		$ticket  = explode( '-', $args[ 'id' ] );
		$id      = $ticket[0];
		$trac    = $ticket[1];
		$url     = esc_url( $args[ 'url' ] );

		$cache_key = "$id-$trac";
		$csv       = wp_cache_get( $cache_key, 'o2_hovercards' );

		// Query for it if not cached yet.
		if ( false === $csv ) {
			$request  = sprintf( "https://%s.trac.wordpress.org/ticket/%s?format=csv", esc_attr( $trac ), intval( $id ) );
			$response = wp_remote_get( $request );

			if ( 200 != wp_remote_retrieve_response_code( $response ) ) {
				$csv = '';
			} else {
				$csv = wp_remote_retrieve_body( $response );
			}

			wp_cache_set( "$id-$trac", $csv, 'o2_hovercards' );
		}

		if ( empty( $csv ) ) {
			return array();
		}

		// Create the array of info from the CSV
		$header     = explode( "\n", $csv );
		$header     = str_getcsv( $header[0] );
		$csv        = str_getcsv( $csv );
		$ticketdata = array();

		for ( $i = count( $header ); $i < count( $csv ); $i++ ) {
			$ticketdata[ $header[ $i % count( $header ) + 1 ] ] = $csv[ $i ];
		}

		$title       = esc_attr( $ticketdata['summary'] );
		$subtitle    = sprintf( "Ticket #%d", intval( $id ) );
		$description = esc_html( $ticketdata['description'] );
		$meta        = array();

		if ( isset( $ticketdata['owner'] ) && $ticketdata['owner'] ) {
			$owner = esc_html( $ticketdata['owner'] );

			$meta['Owner'] = array(
				'name'       => esc_attr( $ticketdata['owner'] ),
				'avatar_url' => get_avatar_url( sprintf( '%s@chat.wordpress.org', $owner ), array( 'size' => 50 ) ),
			);
		}

		if ( isset( $ticketdata['reporter'] ) && $ticketdata['reporter'] && ! isset( $meta['Owner'] ) ) {
			$reporter = esc_html( $ticketdata['reporter'] );

			$meta['Reporter'] = array(
				'name'       => $reporter,
				'avatar_url' => get_avatar_url( sprintf( '%s@chat.wordpress.org', $reporter ), array( 'size' => 50 ) ),
			);
		}

		if ( isset( $ticketdata['type'] ) && $ticketdata['type'] ) {
			$meta['Type'] = esc_attr( $ticketdata['type'] );
		}

		if ( isset( $ticketdata['version'] ) && $ticketdata['version'] ) {
			$meta['Version'] = esc_attr( $ticketdata['version'] );
		}

		if ( isset( $ticketdata['status'] ) && $ticketdata['status'] ) {
			$meta['Status'] = esc_attr( $ticketdata['status'] );
		}

		if ( isset( $ticketdata['component'] ) && $ticketdata['component'] ) {
			$meta['Component'] = esc_attr( $ticketdata['component'] );
		}

		if ( isset( $ticketdata['severity'] ) && $ticketdata['severity'] ) {
			$meta['Severity'] = esc_attr( $ticketdata['severity'] );
		}

		if ( isset( $ticketdata['resolution'] ) && $ticketdata['resolution'] ) {
			$meta['Resolution'] = esc_attr( $ticketdata['resolution'] );
		}

		$keywords = array();
		if ( isset( $ticketdata['keywords'] ) && $ticketdata['keywords'] ) {
			$tags = explode( ' ', $ticketdata['keywords'] );

			foreach ( $tags as $tag ) {
				$keyword = esc_attr( $tag );

				$keywords[ $tag ] = sprintf(
					'https://%1$s.trac.wordpress.org/query?status=!closed&keywords=~%2$s',
					esc_attr( $trac ),
					$keyword
				);
			}
		}

		// Return an array with all the things we need.
		return array(
			'title'       => $title,
			'subtitle'    => $subtitle,
			'url'         => $url,
			'description' => $description,
			'keywords'    => $keywords,
			'meta'        => $meta,
		);
	}

	/**
	 * Add Trac to o2 HoverCards services.
	 *
	 * @since 1.0.0
	 *
	 * @return boolean True if registered. False otherwise.
	 */
	function add_trac() {
		return o2_hovercards_add_service(
			array(
				'service'  => 'trac',
				'key'      => '#(\d+)-(core|ios|android|blackberry|nokia|webos|plugins|bbpress|supportpress|glotpress|backpress|buddypress|meta|windows)',
				'url'      => '<a href="https://$2.trac.wordpress.org/ticket/$1">$0</a>',
				'ticket'   => '$1-$2',
				'callback' => __NAMESPACE__ . '\handle_trac',
			)
		);
	}
	add_action( 'init', 'add_trac', 1 );
}
