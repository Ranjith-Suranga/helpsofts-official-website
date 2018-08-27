<?php

/**
 * The main theme class
 */
class Avada {

	public static $instance = null;

	public static $version = '3.8.5';

	public $settings;

	public $init;
	public $social_icons;
	public $sidebars;
	public $portfolio;
	public $template;
	public $blog;
	public $updater;
	public $mfi;
	public $fonts;
	public $images;
	public $scripts;
	public $head;
	// public $layout;
	public $dynamic_css;
	public $upgrade;

	public $c_pageID = false;

	/**
	 * Access the single instance of this class
	 * @return Avada
	 */
	public static function get_instance() {
		if ( self::$instance==null ) {
			self::$instance = new Avada();
		}
		return self::$instance;
	}

	/**
	 * Shortcut method to get the settings
	 */
	public static function settings() {
		return self::get_instance()->settings->get_all();
	}

	/**
	 * The class constructor
	 */
	private function __construct() {

		// Instantiate secondary classes
		$this->init         = new Avada_Init();
		$this->social_icons = new Avada_Social_Icons();
		$this->sidebars     = new Avada_Sidebars();
		$this->portfolio    = new Avada_Portfolio();
		$this->template     = new Avada_Template();
		$this->blog         = new Avada_Blog();
		$this->fonts        = new Avada_Fonts();
		$this->image        = new Avada_Images();
		$this->scripts      = new Avada_Scripts();
		$this->head         = new Avada_Head();
		$this->dynamic_css  = new Avada_Dynamic_CSS();
		$this->updater      = new Avada_Updater();
		$this->upgrade      = new Avada_Upgrade();

		$this->c_pageID = self::c_pageID();


	}

	public static function c_pageID() {

		$object_id = get_queried_object_id();

		if ( ( get_option( 'show_on_front' ) && get_option( 'page_for_posts' ) && is_home() ) || ( get_option( 'page_for_posts' ) && is_archive() && ! is_post_type_archive() && ! is_tax() ) && ! ( is_tax( 'product_cat' ) || is_tax( 'product_tag' ) ) || ( get_option( 'page_for_posts' ) && is_search() ) ) {
			$c_pageID = get_option( 'page_for_posts' );
		} else {
			if ( isset( $object_id ) ) {
				$c_pageID = $object_id;
			}
			if ( ! is_singular() ) {
				$c_pageID = false;
			}
			if ( class_exists( 'WooCommerce' ) && ( is_shop() || is_tax( 'product_cat' ) || is_tax( 'product_tag' ) ) ) {
				$c_pageID = get_option( 'woocommerce_shop_page_id' );
			}

		}

		return $c_pageID;

	}

}

// Omit closing PHP tag to avoid "Headers already sent" issues.
