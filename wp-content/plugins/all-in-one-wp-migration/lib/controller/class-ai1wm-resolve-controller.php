<?php
/**
 * Copyright (C) 2014 ServMask Inc.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * ███████╗███████╗██████╗ ██╗   ██╗███╗   ███╗ █████╗ ███████╗██╗  ██╗
 * ██╔════╝██╔════╝██╔══██╗██║   ██║████╗ ████║██╔══██╗██╔════╝██║ ██╔╝
 * ███████╗█████╗  ██████╔╝██║   ██║██╔████╔██║███████║███████╗█████╔╝
 * ╚════██║██╔══╝  ██╔══██╗╚██╗ ██╔╝██║╚██╔╝██║██╔══██║╚════██║██╔═██╗
 * ███████║███████╗██║  ██║ ╚████╔╝ ██║ ╚═╝ ██║██║  ██║███████║██║  ██╗
 * ╚══════╝╚══════╝╚═╝  ╚═╝  ╚═══╝  ╚═╝     ╚═╝╚═╝  ╚═╝╚══════╝╚═╝  ╚═╝
 */

class Ai1wm_Resolve_Controller {

	public static function resolve( $args = array() ) {

		// Set arguments
		if ( empty( $args ) ) {
			$args = ai1wm_urldecode( $_REQUEST );
		}

		// Set secret key
		$secret_key = null;
		if ( isset( $args['secret_key'] ) ) {
			$secret_key = $args['secret_key'];
		}

		// Verify secret key by using the value in the database, not in cache
		if ( $secret_key !== get_site_option( AI1WM_SECRET_KEY, false, false ) ) {
			Ai1wm_Status::set(
				array(
					'type'    => 'error',
					'title'   => __( "Unable to resolve", AI1WM_PLUGIN_NAME ),
					'message' => __( "Unable to authenticate your request with secret_key = \"{$secret_key}\"", AI1WM_PLUGIN_NAME ),
				)
			);
			exit;
		}

		// Set IP address
		if ( isset( $args['url_ip'] ) && ( $ip = $args['url_ip' ] ) ) {
			update_site_option( AI1WM_URL_IP, $ip );
		}

		// Set transport layer
		if ( isset( $args['url_transport'] ) && ( $transport = $args['url_transport'] ) ) {
			if ( $transport === 'curl' ) {
				update_site_option( AI1WM_URL_TRANSPORT, array( 'curl', 'ai1wm' ) );
			} else {
				update_site_option( AI1WM_URL_TRANSPORT, array( 'ai1wm', 'curl' ) );
			}
		}
	}
}
