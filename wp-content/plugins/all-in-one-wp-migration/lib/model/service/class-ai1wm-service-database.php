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

class Ai1wm_Service_Database implements Ai1wm_Service_Interface {

	protected $args    = array();

	protected $storage = null;

	public function __construct( array $args = array() ) {
		$this->args = $args;
	}

	/**
	 * Import database
	 *
	 * @return void
	 */
	public function import() {
		global $wpdb;

		// Get configuration
		$service = new Ai1wm_Service_Package( $this->args );
		$config  = $service->import();

		$old_values = array();
		$new_values = array();

		// Get Site URL
		if ( isset( $config['SiteURL'] ) && ( $config['SiteURL'] !== site_url() ) ) {

			// Get domain
			$old_domain = parse_url( $config['SiteURL'], PHP_URL_HOST );
			$new_domain = parse_url( site_url(), PHP_URL_HOST );

			// Add plain Site URL
			$old_values[] = set_url_scheme( $config['SiteURL'], 'http' );
			$new_values[] = set_url_scheme( site_url() );

			// Add plain Site URL SSL
			$old_values[] = set_url_scheme( $config['SiteURL'], 'https' );
			$new_values[] = set_url_scheme( site_url() );

			// Add encoded Site URL
			$old_values[] = urlencode( set_url_scheme( $config['SiteURL'], 'http' ) );
			$new_values[] = urlencode( set_url_scheme( site_url() ) );

			// Add encoded Site URL SSL
			$old_values[] = urlencode( set_url_scheme( $config['SiteURL'], 'https' ) );
			$new_values[] = urlencode( set_url_scheme( site_url() ) );

			// Add escaped Site URL
			$old_values[] = addslashes( addcslashes( set_url_scheme( $config['SiteURL'], 'http' ), '/' ) );
			$new_values[] = addslashes( addcslashes( set_url_scheme( site_url() ), '/' ) );

			// Add escaped Site URL SSL
			$old_values[] = addslashes( addcslashes( set_url_scheme( $config['SiteURL'], 'https' ), '/' ) );
			$new_values[] = addslashes( addcslashes( set_url_scheme( site_url() ), '/' ) );

			// Add email
			$old_values[] = sprintf( "@%s", $old_domain );
			$new_values[] = sprintf( "@%s", $new_domain );
		}

		// Get Home URL
		if ( isset( $config['HomeURL'] ) && ( $config['HomeURL'] !== home_url() ) ) {

			// Add plain Home URL
			$old_values[] = set_url_scheme( $config['HomeURL'], 'http' );
			$new_values[] = set_url_scheme( home_url() );

			// Add plain Home URL SSL
			$old_values[] = set_url_scheme( $config['HomeURL'], 'https' );
			$new_values[] = set_url_scheme( home_url() );

			// Add encoded Home URL
			$old_values[] = urlencode( set_url_scheme( $config['HomeURL'], 'http' ) );
			$new_values[] = urlencode( set_url_scheme( home_url() ) );

			// Add encoded Home URL SSL
			$old_values[] = urlencode( set_url_scheme( $config['HomeURL'], 'https' ) );
			$new_values[] = urlencode( set_url_scheme( home_url() ) );

			// Add escaped Home URL
			$old_values[] = addslashes( addcslashes( set_url_scheme( $config['HomeURL'], 'http' ), '/' ) );
			$new_values[] = addslashes( addcslashes( set_url_scheme( home_url() ), '/' ) );

			// Add escaped Home URL SSL
			$old_values[] = addslashes( addcslashes( set_url_scheme( $config['HomeURL'], 'https' ), '/' ) );
			$new_values[] = addslashes( addcslashes( set_url_scheme( home_url() ), '/' ) );
		}

		// Get WordPress Content
		if ( isset( $config['WordPress']['Content'] ) && ( $config['WordPress']['Content'] !== WP_CONTENT_DIR ) ) {

			// Add plain WordPress Content
			$old_values[] = $config['WordPress']['Content'];
			$new_values[] = WP_CONTENT_DIR;

			// Get escaped WordPress Content
			$old_values[] = addslashes( addcslashes( $config['WordPress']['Content'], '\/' ) );
			$new_values[] = addslashes( addcslashes( WP_CONTENT_DIR, '\/' ) );
		}

		// Get user details
		if ( isset( $config['Import']['User']['Id'] ) && ( $id = $config['Import']['User']['Id'] ) ) {
			$meta = get_userdata( $id );
			$user = array(
				'user_login'           => $meta->user_login,
				'user_pass'            => $meta->user_pass,
				'user_nicename'        => $meta->user_nicename,
				'user_url'             => $meta->user_url,
				'user_email'           => $meta->user_email,
				'display_name'         => $meta->display_name,
				'nickname'             => $meta->nickname,
				'first_name'           => $meta->first_name,
				'last_name'            => $meta->last_name,
				'description'          => $meta->description,
				'rich_editing'         => $meta->rich_editing,
				'user_registered'      => $meta->user_registered,
				'jabber'               => $meta->jabber,
				'aim'                  => $meta->aim,
				'yim'                  => $meta->yim,
				'show_admin_bar_front' => $meta->show_admin_bar_front,
			);
		} else {
			$user = array();
		}

		// Get HTTP user
		$auth_user = get_site_option( AI1WM_AUTH_USER, false, false );

		// Get HTTP password
		$auth_password = get_site_option( AI1WM_AUTH_PASSWORD, false, false );

		// Get secret key
		$secret_key = get_site_option( AI1WM_SECRET_KEY, false, false );

		// Get database client
		$client = MysqlDumpFactory::makeMysqlDump( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

		// Set database options
		$client->setOldTablePrefixes( array( AI1WM_TABLE_PREFIX ) )
			   ->setNewTablePrefixes( array( $wpdb->prefix ) )
			   ->setOldReplaceValues( $old_values )
			   ->setNewReplaceValues( $new_values );

		// Flush database
		if ( ( $version = $config['Plugin']['Version'] ) ) {
			if ( $version !== 'develop' && version_compare( $version, '4.9', '<' ) ) {
				$client->setIncludeTablePrefixes( array( $wpdb->prefix ) );
				$client->flush();
			}
		}

		// Import database
		$client->import( $this->storage()->database() );

		// Clear WP options cache
		wp_cache_flush();

		// WP Migration
		if ( is_plugin_active( AI1WM_PLUGIN_BASENAME ) ) {
			activate_plugin( AI1WM_PLUGIN_BASENAME );
		}

		// Dropbox Extension
		if ( is_plugin_active( AI1WMDE_PLUGIN_BASENAME ) ) {
			activate_plugin( AI1WMDE_PLUGIN_BASENAME );
		}

		// Google Drive Extension
		if ( is_plugin_active( AI1WMGE_PLUGIN_BASENAME ) ) {
			activate_plugin( AI1WMGE_PLUGIN_BASENAME );
		}

		// Amazon S3 Extension
		if ( is_plugin_active( AI1WMSE_PLUGIN_BASENAME ) ) {
			activate_plugin( AI1WMSE_PLUGIN_BASENAME );
		}

		// Multisite Extension
		if ( is_plugin_active( AI1WMME_PLUGIN_BASENAME ) ) {
			activate_plugin( AI1WMME_PLUGIN_BASENAME );
		}

		// Unlimited Extension
		if ( is_plugin_active( AI1WMUE_PLUGIN_BASENAME ) ) {
			activate_plugin( AI1WMUE_PLUGIN_BASENAME );
		}

		// FTP Extension
		if ( is_plugin_active( AI1WMFE_PLUGIN_BASENAME ) ) {
			activate_plugin( AI1WMFE_PLUGIN_BASENAME );
		}

		// URL Extension
		if ( is_plugin_active( AI1WMLE_PLUGIN_BASENAME ) ) {
			activate_plugin( AI1WMLE_PLUGIN_BASENAME );
		}

		// Set new user identity
		if ( isset( $config['Export']['User']['Id'] ) && ( $id = $config['Export']['User']['Id'] ) ) {

			// Update user login and password
			if ( isset( $user['user_login'] ) && isset( $user['user_pass'] ) ) {
				$wpdb->update(
					$wpdb->users,
					array( 'user_login' => $user['user_login'], 'user_pass' => $user['user_pass'] ),
					array( 'ID' => $id ),
					array( '%s', '%s' ),
					array( '%d' )
				);

				// Unset user login
				unset( $user['user_login'] );

				// Unset user password
				unset( $user['user_pass'] );
			}

			// Update user details
			$result = wp_update_user( array( 'ID' => $id ) + $user );

			// Log the error
			if ( is_wp_error( $result ) ) {
				Ai1wm_Log::error( 'Exception while importing user identity: ' . $result->get_error_message() );
			}
		}

		// Set the new HTTP user
		update_site_option( AI1WM_AUTH_USER, $auth_user );

		// Set the new HTTP password
		update_site_option( AI1WM_AUTH_PASSWORD, $auth_password );

		// Set the new secret key value
		update_site_option( AI1WM_SECRET_KEY, $secret_key );
	}

	/**
	 * Export database
	 *
	 * @return void
	 */
	public function export() {
		global $wpdb;

		$clauses = array();

		// Spam comments
		if ( isset( $this->args['options']['no-spam-comments'] ) ) {
			$clauses[ $wpdb->comments ]    = " WHERE comment_approved != 'spam' ";
			$clauses[ $wpdb->commentmeta ] = sprintf(
				" WHERE comment_id IN ( SELECT comment_ID FROM `%s` WHERE comment_approved != 'spam' ) ",
				$wpdb->comments
			);
		}

		// Post revisions
		if ( isset( $this->args['options']['no-revisions'] ) ) {
			$clauses[ $wpdb->posts ] = " WHERE post_type != 'revision' ";
		}

		// Find and replace
		$old_values = array();
		$new_values = array();
		if ( isset( $this->args['options']['replace'] ) && ( $replace = $this->args['options']['replace'] ) ) {
			for ( $i = 0; $i < count( $replace['old-value'] ); $i++ ) {
				if ( ! empty( $replace['old-value'][$i] ) && ! empty( $replace['new-value'][$i] ) ) {
					$old_values[] = $replace['old-value'][$i];
					$new_values[] = $replace['new-value'][$i];
				}
			}
		}

		// Get database client
		$client = MysqlDumpFactory::makeMysqlDump( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

		// Set database options
		$client->setOldTablePrefixes( array( $wpdb->prefix ) )
			   ->setNewTablePrefixes( array( AI1WM_TABLE_PREFIX ) )
			   ->setOldReplaceValues( $old_values )
			   ->setNewReplaceValues( $new_values )
   			   ->setIncludeTablePrefixes( array( $wpdb->prefix ) )
			   ->setQueryClauses( $clauses )
			   ->setTablePrefixColumns( $wpdb->options, array( 'option_name' ) )
			   ->setTablePrefixColumns( $wpdb->usermeta, array( 'meta_key' ) );

		// Export database
		$client->export( $this->storage()->database() );
	}

	/*
	 * Get storage object
	 *
	 * @return Ai1wm_Storage
	 */
	protected function storage() {
		if ( $this->storage === null ) {
			$this->storage = new Ai1wm_Storage( $this->args );
		}

		return $this->storage;
	}
}
