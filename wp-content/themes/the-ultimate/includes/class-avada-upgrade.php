<?php

class Avada_Upgrade {

	public function __construct() {
		$current_version  = Avada::$version;
		$previous_version = get_option( 'avada_version', false );
		$avada_options    = get_option( 'Avada_options', array() );	

		// Show upgrade notice
		add_action( 'admin_notices', array( $this, 'upgrade_notice' ) );

		// Action on upgrade notices
		add_action( 'admin_init', array( $this, 'notices_action' ) );

		// No need to proceed if this has already run
		if ( $previous_version == $current_version ) {
			return;
		}

		if ( empty( $avada_options ) ) {

			// This is a fresh installation
			$this->fresh_installation();

		} else {

			// The previous version was less than 3.8.5
			if ( version_compare( $previous_version, '3.8.5', '<' ) ) {
				$this->pre_385();
			}

		}

		$this->update_db_version();

	}

	/**
	 * Actions to run on  a fresh installation
	 */
	public function fresh_installation() {
	}

	/**
	 * Run if previous version is < 385
	 */
	public function pre_385() {
		$options = get_option( 'Avada_options', array() );

		// We no longer have a less compiler.
		// Migrate the less_compiler option to the new dynamic_css_compiler option.
		if ( isset( $options['less_compiler'] ) ) {
			$options['dynamic_css_compiler'] = $options['less_compiler'];
		}

		// We added an independent theme option for content box icons
		if ( isset( $options['icon_color'] ) ) {
			$options['content_box_icon_color'] = $options['icon_color'];
		}

		if ( isset( $options['icon_circle_color'] ) ) {
			$options['content_box_icon_bg_color'] = $options['icon_circle_color'];
		}

		if ( isset( $options['icon_border_color'] ) ) {
			$options['content_box_icon_bg_inner_border_color'] = $options['icon_border_color'];
		}

		$options['post_titles_font_size'] = $options['h2_font_size'];
		$options['post_titles_extras_font_size'] = $options['h2_font_size'];
		$options['post_titles_font_lh'] = $options['h2_font_lh'];

		// Update the options with our modifications.
		update_option( 'Avada_options', $options );

		// Reset the css
		update_option( 'avada_dynamic_css_posts', array() );
	}

	/**
	 * Notices that will show to users that upgrade
	 */
	public function upgrade_notice() {
		global $current_user;
		$user_id = $current_user->ID;
		$previous_version = get_option( 'avada_version', false );

		/* Check that the user hasn't already clicked to ignore the message */
		if ( current_user_can( 'edit_theme_options' ) && ! get_user_meta( $user_id, 'avada_pre_386_notice', TRUE ) ) {
	        echo '<div class="updated error">';
	        if ( version_compare( $previous_version, '3.8.5', '<' ) ) {
		        echo '<p><strong>The following important changes were made to Avada 3.8.5:</strong></p>';
		        echo '<ol>';
		        echo '<li><strong>CHANGED:</strong> Sidebar, Footer and Sliding Bar widget title HTML tag is changed from h3 to h4 for SEO improvements.</li>';
		        echo '<li><strong>DEPRECATED:</strong> Icon Flip shortcode option was deprecated from flip boxes, content boxes and fontawesome shortcode. Alternatively, you can use the icon rotate option.</li>';
		        echo '</ol>';
	    	}
	        if ( version_compare( $previous_version, '3.8.6', '<' ) ) {
		        echo '<p><strong>The following important changes were made to Avada 3.8.6:</strong></p>';
		        echo '<ol>';
		        echo '<li><strong>DEPRECATED:</strong> Fixed Mode for iPad will be deprecated in Avada 3.8.7. Fixed Mode will be moved into a plugin.</li>';
		        echo '<li><strong>CHANGED:</strong> Titles for "Related Posts" and "Comments" on single post page are changed from H2 to H3 for SEO improvements.</li>';
		        echo '</ol>';
	    	}
	        printf('<p><strong>' . __('<a href="%1$s" class="%2$s" target="_blank">View Changelog</a>', 'Avada'), 'http://theme-fusion.com/avada-documentation/changelog.txt', 'view-changelog button-primary' );
	        printf(__('<a href="%1$s" class="%2$s" style="margin:0 4px;">Dismiss this notice</a>', 'Avada') . '</strong></p>', esc_url( add_query_arg( 'avada_pre_386_notice', '0' ) ), 'dismiss-notice button-secondary' );
	        echo '</div>';
		}
	}

	/**
	 * Action to take when user clicks on notices button
	 */
	public function notices_action() {
		global $current_user;
		$user_id = $current_user->ID;

		// Don't show 3.8 notice
		if ( isset( $_GET['avada_pre_386_notice'] ) && '0' == $_GET['avada_pre_386_notice'] ) {
			add_user_meta( $user_id, 'avada_pre_386_notice', TRUE, true );
		}
	}

	/**
	 * Update the avada version in the database.
	 */
	public function update_db_version() {
		update_option( 'avada_version', Avada::$version );
	}

}

// Omit closing PHP tag to avoid "Headers already sent" issues.
