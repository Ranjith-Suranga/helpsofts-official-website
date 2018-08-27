<?php

/**
 * Require the installation of any required and/or recommended third-party plugins here.
 * See http://tgmpluginactivation.com/ for more details
 */
function avada_register_required_plugins() {
	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(
		array(
			'name'               => 'Revolution Slider',
			'slug'               => 'revslider',
			'source'             => get_template_directory() . '/framework/plugins/revslider.zip',
			'required'           => false,
			'version'            => '4.6.93',
			'force_activation'   => false,
			'force_deactivation' => false,
			'external_url'       => '',
			'image_url'          => AVADA_ADMIN_DIR . '../assets/images/rev_slider.png',
		),
		array(
			'name'               => 'Fusion Core',
			'slug'               => 'fusion-core',
			'source'             => get_template_directory() . '/framework/plugins/fusion-core.zip',
			'required'           => true,
			'version'            => '1.7.5.1',
			'force_activation'   => false,
			'force_deactivation' => false,
			'external_url'       => '',
			'image_url'          => AVADA_ADMIN_DIR . '../assets/images/fusion_core.png',
		),
		array(
			'name'               => 'LayerSlider WP',
			'slug'               => 'LayerSlider',
			'source'             => get_template_directory() . '/framework/plugins/LayerSlider.zip',
			'required'           => false,
			'version'            => '5.5.1',
			'force_activation'   => false,
			'force_deactivation' => false,
			'external_url'       => '',
			'image_url'          => AVADA_ADMIN_DIR . '../assets/images/layer_slider.png',
		),
	);

	// Change this to your theme text domain, used for internationalising strings
	$theme_text_domain = 'Avada';

	/**
	 * Array of configuration settings. Amend each line as needed.
	 * If you want the default strings to be available under your own theme domain,
	 * leave the strings uncommented.
	 * Some of the strings are added into a sprintf, so see the comments at the
	 * end of each line for what each argument will be.
	 */
	$config = array(
		'domain'           => 'Avada', // Text domain
		'default_path'     => '',
		'parent_menu_slug' => 'themes.php',
		'parent_url_slug'  => 'themes.php',
		'menu'             => 'install-required-plugins',
		'has_notices'      => true,
		'is_automatic'     => false,
		'message'          => '',
		'strings'          => array(
			'page_title'                      => __( 'Install Required Plugins', 'Avada' ),
			'menu_title'                      => __( 'Install Plugins', 'Avada' ),
			'installing'                      => __( 'Installing Plugin: %s', 'Avada' ), // %1$s = plugin name
			'oops'                            => __( 'Something went wrong with the plugin API.', 'Avada' ),
			'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin installed or update: %1$s.', 'This theme requires the following plugins installed or updated: %1$s.' ), // %1$s = plugin name(s)
			'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin installed or updated: %1$s.', 'This theme recommends the following plugins installed or updated: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s)
			'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
			'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s)
			'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s)
			'install_link'                    => _n_noop( 'Go Install Plugin', 'Go Install Plugins' ),
			'activate_link'                   => _n_noop( 'Go Activate Plugin', 'Go Activate Plugins' ),
			'return'                          => __( 'Return to Required Plugins Installer', 'Avada' ),
			'plugin_activated'                => __( 'Plugin activated successfully.', 'Avada' ),
			'complete'                        => __( 'All plugins installed and activated successfully. %s', 'Avada' ), // %1$s = dashboard link
			'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated' or 'error'
		)
	);

	tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'avada_register_required_plugins' );

// Omit closing PHP tag to avoid "Headers already sent" issues.
