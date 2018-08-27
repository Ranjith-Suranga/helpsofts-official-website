<?php

/**
 * Format of the $css array:
 * $css['media-query']['element']['property'] = value
 *
 * If no media query is required then set it to 'global'
 *
 * If we want to add multiple values for the same property then we have to make it an array like this:
 * $css[media-query][element]['property'][] = value1
 * $css[media-query][element]['property'][] = value2
 *
 * Multiple values defined as an array above will be parsed separately.
 */
function avada_dynamic_css_array() {

	global $wp_version;

	$c_pageID = Avada::c_pageID();

	$isiPad = (bool) strpos( $_SERVER['HTTP_USER_AGENT'], 'iPad' );

	$css = array();

	$site_width = (int) Avada()->settings->get( 'site_width' );

	// The site width WITH units appended
	if ( false === strpos( Avada()->settings->get( 'site_width' ), '%' ) && false === strpos( Avada()->settings->get( 'site_width' ), 'px' ) ) {
		$site_width_with_units = Avada_Sanitize::size( Avada()->settings->get( 'site_width' ) . 'px' );
	} else {
		$site_width_with_units = Avada_Sanitize::size( Avada()->settings->get( 'site_width' ) );
	}
	// The site width as an integer value (WITHOUT units appended)
	$site_width_without_units = (int) Avada_Sanitize::size( Avada()->settings->get( 'site_width' ) );

	// Is the site width a percent value?
	$site_width_percent = ( false !== strpos( Avada()->settings->get( 'site_width' ), '%' ) ) ? true : false;

	$theme_info = wp_get_theme();
	if ( $theme_info->parent_theme ) {
		$template_dir = basename( get_template_directory() );
		$theme_info   = wp_get_theme( $template_dir );
	}

	$css['global']['.' . $theme_info->get( 'Name' ) . "_" . str_replace( '.', '', $theme_info->get( 'Version' ) )]['color'] = 'green';

	if ( ( $isiPad && Avada()->settings->get( 'ipad_potrait' ) ) || ! Avada()->settings->get( 'responsive' ) ) {
		$css['global']['.ua-mobile #wrapper']['width']    = '100% !important';
		$css['global']['.ua-mobile #wrapper']['overflow'] = 'hidden !important';
	}

	$side_header_width = ( 'Top' == Avada()->settings->get( 'header_position' ) ) ? 0 : intval( Avada()->settings->get( 'side_header_width' ) );

	if ( class_exists( 'WooCommerce' ) ) {

		if ( 'horizontal' == Avada()->settings->get( 'woocommerce_product_tab_design' ) ) {

			$css['global']['.woocommerce-tabs > .tabs']['width']         = '100%';
			$css['global']['.woocommerce-tabs > .tabs']['margin']        = '0px';
			$css['global']['.woocommerce-tabs > .tabs']['border-bottom'] = '1px solid #dddddd';

			$css['global']['.woocommerce-tabs > .tabs li']['float'] = 'left';

			$css['global']['.woocommerce-tabs > .tabs li a']['border']  = 'none !important';
			$css['global']['.woocommerce-tabs > .tabs li a']['padding'] = '10px 20px';

			$css['global']['.woocommerce-tabs > .tabs .active']['border'] = '1px solid #dddddd';
			$css['global']['.woocommerce-tabs > .tabs .active']['height'] = '40px';

			$css['global']['.woocommerce-tabs > .tabs .active:hover a']['cursor'] = 'default';

			$css['global']['.woocommerce-tabs .entry-content']['float']      = 'left';
			$css['global']['.woocommerce-tabs .entry-content']['margin']     = '0px';
			$css['global']['.woocommerce-tabs .entry-content']['width']      = '100%';
			$css['global']['.woocommerce-tabs .entry-content']['border-top'] = 'none';

			if ( Avada()->settings->get( 'responsive' ) ) {
				$css['@media all and (max-width: 965px)']['.tabs']['margin-bottom'] = '0px !important';
				$elements = array(
					'#wrapper .woocommerce-tabs .tabs',
					'#wrapper .woocommerce-tabs .panel'
				);
				$css['@media all and (max-width: 965px)'][avada_implode( $elements )]['float'] = 'left !important';

				$css['@media all and (max-width: 470px)']['.woocommerce-tabs > .tabs li']['float']         = 'left';
				$css['@media all and (max-width: 470px)']['.woocommerce-tabs > .tabs li']['width']         = '100%';
				$css['@media all and (max-width: 470px)']['.woocommerce-tabs > .tabs li']['margin-bottom'] = '2px';
				$css['@media all and (max-width: 470px)']['.woocommerce-tabs > .tabs li']['border-bottom'] = '1px solid #dddddd';
				$css['@media all and (max-width: 470px)']['.woocommerce-tabs > .tabs li']['border-left']   = 'none !important';
				$css['@media all and (max-width: 470px)']['.woocommerce-tabs > .tabs li']['border-right']  = 'none !important';
				$css['@media all and (max-width: 470px)']['.woocommerce-tabs > .tabs li']['border-top']    = 'none !important';

				$css['@media all and (max-width: 470px)']['.woocommerce-tabs > .tabs .active']['height'] = 'auto';

				$css['@media all and (max-width: 470px)']['.woocommerce-tabs .entry-content']['float']      = 'left';
				$css['@media all and (max-width: 470px)']['.woocommerce-tabs .entry-content']['width']      = '100%';
				$css['@media all and (max-width: 470px)']['.woocommerce-tabs .entry-content']['margin-top'] = '20px !important';
				$css['@media all and (max-width: 470px)']['.woocommerce-tabs .entry-content']['border-top'] = '1px solid #dddddd';

				$css['@media all and (max-width: 470px)']['.woocommerce-tabs > .tabs']['border-bottom'] = 'none';
			}
		}

		if ( '' != Avada()->settings->get( 'timeline_bg_color' ) && 'transparent' != Avada()->settings->get( 'timeline_bg_color' ) ) {
			$css['global']['.products .product-list-view']['padding-left']  = '20px';
			$css['global']['.products .product-list-view']['padding-right'] = '20px';
		}

	}

	if ( ! Avada()->settings->get( 'smooth_scrolling' ) ) {
		if ( Avada()->settings->get( 'responsive' ) ) {
			$css['@media only screen and (min-width: 800px)']['.no-overflow-y body']['padding-right'] = '9px';
			$css['@media only screen and (min-width: 800px)']['.no-overflow-y #slidingbar-area']['right'] = '9px';
		}
	}

	$elements = array(
		'html',
		'body',
		'html body.custom-background',
	);
	if ( class_exists( 'WooCommerce' ) ) {
		$elements[] = '.woocommerce-tabs > .tabs .active a';
	}
	$css['global'][avada_implode( $elements )]['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'content_bg_color' ), Avada()->settings->get_default( 'content_bg_color' ) );

	if ( 'Wide' == Avada()->settings->get( 'layout' ) ) {
		$css['global'][avada_implode( $elements )]['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'content_bg_color' ), Avada()->settings->get_default( 'content_bg_color' ) );
	} elseif ( 'Boxed' == Avada()->settings->get( 'layout' ) ) {
		$css['global'][avada_implode( $elements )]['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'bg_color' ), Avada()->settings->get_default( 'bg_color' ) );
	}

	if ( ! $site_width_percent ) {

		$elements = array(
			'#main',
			'.fusion-secondary-header',
			'.sticky-header .sticky-shadow',
			'.tfs-slider .slide-content-container',
			'.header-v4 #small-nav',
			'.header-v5 #small-nav',
			'.fusion-footer-copyright-area',
			'.fusion-footer-widget-area',
			'#slidingbar',
			'.fusion-page-title-bar',
		);
		$css['global'][avada_implode( $elements )]['padding-left']  = '30px';
		$css['global'][avada_implode( $elements )]['padding-right'] = '30px';

		$elements = array(
			'.width-100 .nonhundred-percent-fullwidth',
			'.width-100 .fusion-section-separator',
		);


		if ( get_post_meta( $c_pageID, 'pyre_hundredp_padding', true ) || get_post_meta( $c_pageID, 'pyre_hundredp_padding', true ) == '0' ) {
			$css['global'][avada_implode( $elements )]['padding-left']  = Avada_Sanitize::size( get_post_meta( $c_pageID, 'pyre_hundredp_padding', true ) ) . '';
			$css['global'][avada_implode( $elements )]['padding-right'] = Avada_Sanitize::size( get_post_meta( $c_pageID, 'pyre_hundredp_padding', true ) ) . '';
		} elseif ( Avada()->settings->get( 'hundredp_padding' ) || Avada()->settings->get( 'hundredp_padding' ) == '0' ) {
			$css['global'][avada_implode( $elements )]['padding-left']  = Avada_Sanitize::size( Avada()->settings->get( 'hundredp_padding' ) ) . '';
			$css['global'][avada_implode( $elements )]['padding-right'] = Avada_Sanitize::size( Avada()->settings->get( 'hundredp_padding' ) ) . '';
		}

		$elements = array(
			'.width-100 .fullwidth-box',
			'.width-100 .fusion-section-separator',
		);

		if ( get_post_meta( $c_pageID, 'pyre_hundredp_padding', true ) || get_post_meta( $c_pageID, 'pyre_hundredp_padding', true ) == '0' ) {
			$css['global'][avada_implode( $elements )]['margin-left']  = '-' . Avada_Sanitize::size( get_post_meta( $c_pageID, 'pyre_hundredp_padding', true ) ) . '!important';
			$css['global'][avada_implode( $elements )]['margin-right'] = '-' . Avada_Sanitize::size( get_post_meta( $c_pageID, 'pyre_hundredp_padding', true ) ) . '!important';
		} elseif ( Avada()->settings->get( 'hundredp_padding' ) || Avada()->settings->get( 'hundredp_padding' ) == '0' ) {
			$css['global'][avada_implode( $elements )]['margin-left']  = '-' . Avada_Sanitize::size( Avada()->settings->get( 'hundredp_padding' ) ) . '!important';
			$css['global'][avada_implode( $elements )]['margin-right'] = '-' . Avada_Sanitize::size( Avada()->settings->get( 'hundredp_padding' ) ) . '!important';
		}

		if ( Avada()->settings->get( 'responsive' ) ) {

			$media_query = '@media only screen and (max-width: 800px)';
			$css[$media_query]['.fusion-mobile-menu-design-modern .fusion-secondary-header']['padding-left'] = '0 !important';
			$css[$media_query]['.fusion-mobile-menu-design-modern .fusion-secondary-header']['padding-right'] = '0 !important';
			$css[$media_query]['#side-header']['width'] = 'auto';

			$media_query = '@media only screen and (max-width: ' . $site_width_with_units . ')';
			$css[$media_query]['.width-100#main']['padding-left']  = '30px !important';
			$css[$media_query]['.width-100#main']['padding-right'] = '30px !important';

			$elements = array(
				'.width-100 .nonhundred-percent-fullwidth',
				'.width-100 .fusion-section-separator'
			);
            $css[$media_query][avada_implode( $elements )]['padding-left']  = '30px !important';
            $css[$media_query][avada_implode( $elements )]['padding-right'] = '30px !important';

			$elements = array(
				'.width-100 .fullwidth-box',
				'.width-100 .fusion-section-separator'
			);
            $css[$media_query][avada_implode( $elements )]['margin-left']   = '-30px !important';
            $css[$media_query][avada_implode( $elements )]['margin-right']  = '-30px !important';


            // For header left and right, we need to apply padding at:
            // Site width + side header width + 30px * 2 ( 30 extra for it not to jump so harshly )
            if( Avada()->settings->get( 'header_position' ) == 'Left' || Avada()->settings->get( 'header_position' ) == 'Right' ) {
            	$side_header_width_without_units = Avada_Sanitize::size( Avada()->settings->get( 'side_header_width' ) );
            	$side_header_fwc_breakpoint = $site_width_without_units + $side_header_width_without_units + 60;

            	$media_query = '@media only screen and (max-width: ' . $side_header_fwc_breakpoint . 'px)';

				$elements = array(
					'.width-100 .nonhundred-percent-fullwidth',
					'.width-100 .fusion-section-separator'
				);
	            $css[$media_query][avada_implode( $elements )]['padding-left']  = '30px !important';
	            $css[$media_query][avada_implode( $elements )]['padding-right'] = '30px !important';
	            //$css[$media_query][avada_implode( $elements )]['margin-left']   = '-30px !important';
	            //$css[$media_query][avada_implode( $elements )]['margin-right']  = '-30px !important';
            }
		}

	}

	$css['global']['.fusion-mobile-menu-design-modern .fusion-mobile-nav-holder li a']['padding-left']  = '30px';
	$css['global']['.fusion-mobile-menu-design-modern .fusion-mobile-nav-holder li a']['padding-right'] = '30px';

	$css['global']['.fusion-mobile-menu-design-modern .fusion-mobile-nav-holder .fusion-mobile-nav-item .fusion-open-submenu']['padding-right'] = '35px';

	$css['global']['.fusion-mobile-menu-design-modern .fusion-mobile-nav-holder .fusion-mobile-nav-item a']['padding-left']  = '30px';
	$css['global']['.fusion-mobile-menu-design-modern .fusion-mobile-nav-holder .fusion-mobile-nav-item a']['padding-right'] = '30px';
	$css['global']['.fusion-mobile-menu-design-modern .fusion-mobile-nav-holder .fusion-mobile-nav-item li a']['padding-left'] = '42px';
	$css['global']['.fusion-mobile-menu-design-modern .fusion-mobile-nav-holder .fusion-mobile-nav-item li li a']['padding-left'] = '55px';
	$css['global']['.fusion-mobile-menu-design-modern .fusion-mobile-nav-holder .fusion-mobile-nav-item li li li a']['padding-left'] = '68px';
	$css['global']['.fusion-mobile-menu-design-modern .fusion-mobile-nav-holder .fusion-mobile-nav-item li li li li a']['padding-left'] = '81px';

	if ( is_rtl() ) {
		$css['global']['.rtl .fusion-mobile-menu-design-modern .fusion-mobile-nav-holder .fusion-mobile-nav-item .fusion-open-submenu']['padding-left']  = '30px';
		$css['global']['.rtl .fusion-mobile-menu-design-modern .fusion-mobile-nav-holder .fusion-mobile-nav-item .fusion-open-submenu']['padding-right'] = '15px';
		$css['global']['.rtl .fusion-mobile-menu-design-modern .fusion-mobile-nav-holder .fusion-mobile-nav-item a']['padding-left']  = '30px';
		$css['global']['.rtl .fusion-mobile-menu-design-modern .fusion-mobile-nav-holder .fusion-mobile-nav-item a']['padding-right'] = '30px';
		$css['global']['.rtl .fusion-mobile-menu-design-modern .fusion-mobile-nav-holder .fusion-mobile-nav-item li a']['padding-left']  = '0';
		$css['global']['.rtl .fusion-mobile-menu-design-modern .fusion-mobile-nav-holder .fusion-mobile-nav-item li a']['padding-right'] = '42px';
		$css['global']['.rtl .fusion-mobile-menu-design-modern .fusion-mobile-nav-holder .fusion-mobile-nav-item li li a']['padding-left']  = '0';
		$css['global']['.rtl .fusion-mobile-menu-design-modern .fusion-mobile-nav-holder .fusion-mobile-nav-item li li a']['padding-right'] = '55px';
		$css['global']['.rtl .fusion-mobile-menu-design-modern .fusion-mobile-nav-holder .fusion-mobile-nav-item li li li a']['padding-left']  = '0';
		$css['global']['.rtl .fusion-mobile-menu-design-modern .fusion-mobile-nav-holder .fusion-mobile-nav-item li li li a']['padding-right'] = '68px';
		$css['global']['.rtl .fusion-mobile-menu-design-modern .fusion-mobile-nav-holder .fusion-mobile-nav-item li li li li a']['padding-left']  = '0';
		$css['global']['.rtl .fusion-mobile-menu-design-modern .fusion-mobile-nav-holder .fusion-mobile-nav-item li li li li a']['padding-right'] = '81px';
	}

	if ( Avada()->settings->get( 'responsive' ) ) {

		$media_query = '@media only screen and (min-width: ' . ( 850 + (int) Avada()->settings->get( 'side_header_width' ) ) . 'px) and (max-width: ' . ( 930 + (int) Avada()->settings->get( 'side_header_width' ) ) . 'px)';

		$elements = array(
			'.grid-layout-6 .fusion-post-grid',
			'.fusion-portfolio-six .fusion-portfolio-post'
		);
		$css[$media_query][avada_implode( $elements )]['width']  = '20% !important';

		$elements = array(
			'.grid-layout-5 .fusion-post-grid',
			'.fusion-portfolio-five .fusion-portfolio-post'
		);
		$css[$media_query][]['width'] = '25% !important';

		$media_query = '@media only screen and (min-width: 800px) and (max-width: ' . ( 850 + (int) Avada()->settings->get( 'side_header_width' ) ) . 'px)';
		$elements = array(
			'.grid-layout-6 .fusion-post-grid',
			'.fusion-portfolio-six .fusion-portfolio-post'
		);
		$css[$media_query][avada_implode( $elements )]['width']  = '25% !important';

		$elements = array(
			'.grid-layout-5 .fusion-post-grid',
			'.fusion-portfolio-five .fusion-portfolio-post'
		);
		$css[$media_query][avada_implode( $elements )]['width'] = '33.3333333333% !important';

		$elements = array(
			'.grid-layout-4 .fusion-post-grid',
			'.fusion-portfolio-four .fusion-portfolio-post'
		);
		$css[$media_query][avada_implode( $elements )]['width'] = '33.3333333333% !important';

		$media_query = '@media only screen and (min-width: 700px ) and (max-width: 800px)';

		$elements = array(
			'.fusion-blog-layout-grid-6 .fusion-post-grid',
			'.fusion-portfolio-six .fusion-portfolio-post'
		);
		$css[$media_query][avada_implode( $elements )]['width'] = '33.3333333333% !important';

		$elements = array(
			'.fusion-blog-layout-grid-5 .fusion-post-grid',
			'.fusion-blog-layout-grid-4 .fusion-post-grid',
			'.fusion-blog-layout-grid-3 .fusion-post-grid',
			'.fusion-portfolio-five .fusion-portfolio-post',
			'.fusion-portfolio-four .fusion-portfolio-post',
			'.fusion-portfolio-three .fusion-portfolio-post',
			'.fusion-portfolio-masonry .fusion-portfolio-post'
		);
		$css[$media_query][avada_implode( $elements )]['width'] = '50% !important';

		$media_query = '@media only screen and (min-width: 640px) and (max-width: 700px)';
		$elements = array(
			'.fusion-blog-layout-grid-6 .fusion-post-grid',
			'.fusion-blog-layout-grid-5 .fusion-post-grid',
			'.fusion-blog-layout-grid-4 .fusion-post-grid',
			'.fusion-blog-layout-grid-3 .fusion-post-grid',
			'.fusion-portfolio-six .fusion-portfolio-post',
			'.fusion-portfolio-five .fusion-portfolio-post',
			'.fusion-portfolio-four .fusion-portfolio-post',
			'.fusion-portfolio-three .fusion-portfolio-post',
			'.fusion-portfolio-masonry .fusion-portfolio-post'
		);
		$css[$media_query][avada_implode( $elements )]['width'] = '50% !important';

		$media_query = '@media only screen and (max-width: 640px)';
		$elements = array(
			'.fusion-blog-layout-grid .fusion-post-grid',
			'.fusion-portfolio-post'
		);
		$css[$media_query][avada_implode( $elements )]['width'] = '100% !important';

		$media_query = '@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) and (orientation: portrait)';
		$elements = array(
			'.fusion-blog-layout-grid-6 .fusion-post-grid',
			'.fusion-portfolio-six .fusion-portfolio-post'
		);
		$css[$media_query][avada_implode( $elements )]['width'] = '33.3333333333% !important';

		$elements = array(
			'.fusion-blog-layout-grid-5 .fusion-post-grid',
			'.fusion-blog-layout-grid-4 .fusion-post-grid',
			'.fusion-blog-layout-grid-3 .fusion-post-grid',
			'.fusion-portfolio-five .fusion-portfolio-post',
			'.fusion-portfolio-four .fusion-portfolio-post',
			'.fusion-portfolio-three .fusion-portfolio-post',
			'.fusion-portfolio-masonry .fusion-portfolio-post'
		);
		$css[$media_query][avada_implode( $elements )]['width'] = '50% !important';

		if ( Avada()->settings->get( 'footerw_bg_image' ) && ( in_array( Avada()->settings->get( 'footer_special_effects' ), array( 'footer_parallax_effect', 'footer_area_bg_parallax', 'footer_sticky_with_parallax_bg_image' ) ) ) ) {
			$css[$media_query]['.fusion-body #wrapper']['background-color'] = 'transparent';
		}

		$media_query = '@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) and (orientation: landscape)';
		if ( Avada()->settings->get( 'footerw_bg_image' ) && ( in_array( Avada()->settings->get( 'footer_special_effects' ), array( 'footer_parallax_effect', 'footer_area_bg_parallax', 'footer_sticky_with_parallax_bg_image' ) ) ) ) {
			$css[$media_query]['.fusion-body #wrapper']['background-color'] = 'transparent';
		}

	}

	$elements = array(
		'a:hover',
		'.tooltip-shortcode'
	);
	$css['global'][avada_implode( $elements )]['color'] = Avada_Sanitize::color( Avada()->settings->get( 'primary_color' ), Avada()->settings->get_default( 'primary_color' ) );

	$elements = array(
		'.fusion-footer-widget-area ul li a:hover',
		'.fusion-footer-widget-area .fusion-tabs-widget .tab-holder .news-list li .post-holder a:hover',
		'.fusion-footer-widget-area .fusion-accordian .panel-title a:hover',
		'#slidingbar-area ul li a:hover',
		'#slidingbar-area .fusion-accordian .panel-title a:hover',
		'.fusion-filters .fusion-filter.fusion-active a',
		'.project-content .project-info .project-info-box a:hover',
		'#main .post h2 a:hover',
		'#main .about-author .title a:hover',
		'span.dropcap',
		'.fusion-footer-widget-area a:hover',
		'#slidingbar-area a:hover',
		'.fusion-copyright-notice a:hover',
		'.sidebar .widget_categories li a:hover',
		'.sidebar .widget li a:hover',
		'.fusion-date-and-formats .fusion-format-box i',
		'h5.toggle:hover a',
		'.tooltip-shortcode',
		'.content-box-percentage',
		'.fusion-popover',
		'.more a:hover:after',
		'.fusion-read-more:hover:after',
		'.pagination-prev:hover:before',
		'.pagination-next:hover:after',
		'.single-navigation a[rel=prev]:hover:before',
		'.single-navigation a[rel=next]:hover:after',
		'.sidebar .widget_nav_menu li a:hover:before',
		'.sidebar .widget_categories li a:hover:before',
		'.sidebar .widget .recentcomments:hover:before',
		'.sidebar .widget_recent_entries li a:hover:before',
		'.sidebar .widget_archive li a:hover:before',
		'.sidebar .widget_pages li a:hover:before',
		'.sidebar .widget_links li a:hover:before',
		'.side-nav .arrow:hover:after',
		'#wrapper .jtwt .jtwt_tweet a:hover',
		'.star-rating:before',
		'.star-rating span:before',
		'#wrapper .sidebar .current_page_item > a',
		'#wrapper .sidebar .current-menu-item > a',
		'#wrapper .sidebar .current_page_item > a:before',
		'#wrapper .sidebar .current-menu-item > a:before',
		'#wrapper .fusion-footer-widget-area .current_page_item > a',
		'#wrapper .fusion-footer-widget-area .current-menu-item > a',
		'#wrapper .fusion-footer-widget-area .current_page_item > a:before',
		'#wrapper .fusion-footer-widget-area .current-menu-item > a:before',
		'#wrapper #slidingbar-area .current_page_item > a',
		'#wrapper #slidingbar-area .current-menu-item > a',
		'#wrapper #slidingbar-area .current_page_item > a:before',
		'#wrapper #slidingbar-area .current-menu-item > a:before',
		'.side-nav ul > li.current_page_item > a',
		'.side-nav li.current_page_ancestor > a',
		'.fusion-accordian .panel-title a:hover',
		'.price ins .amount',
		'.price > .amount',
	);
	if ( is_rtl() ) {
		$elements[] = '.rtl .more a:hover:before';
		$elements[] = '.rtl .fusion-read-more:hover:before';
	}
	if ( class_exists( 'GFForms' ) ) {
		$elements[] = '.gform_wrapper span.ginput_total';
		$elements[] = '.gform_wrapper span.ginput_product_price';
		$elements[] = '.ginput_shipping_price';
	}
	if ( class_exists( 'bbPress' ) ) {
		$elements[] = '.bbp-topic-pagination .prev:hover:before';
		$elements[] = '.bbp-topic-pagination .next:hover:after';
		$elements[] = '.bbp-topics-front ul.super-sticky a:hover';
		$elements[] = '.bbp-topics ul.super-sticky a:hover';
		$elements[] = '.bbp-topics ul.sticky a:hover';
		$elements[] = '.bbp-forum-content ul.sticky a:hover';

	}
	if ( class_exists( 'WooCommerce' ) ) {
		$elements[] = '.woocommerce .address .edit:hover:after';
		$elements[] = '.woocommerce-tabs .tabs a:hover .arrow:after';
		$elements[] = '.woocommerce-pagination .prev:hover';
		$elements[] = '.woocommerce-pagination .next:hover';
		$elements[] = '.woocommerce-pagination .prev:hover:before';
		$elements[] = '.woocommerce-pagination .next:hover:after';
		$elements[] = '.woocommerce-tabs .tabs li.active a';
		$elements[] = '.woocommerce-tabs .tabs li.active a .arrow:after';
		$elements[] = '.woocommerce-side-nav li.active a';
		$elements[] = '.woocommerce-side-nav li.active a:after';
		$elements[] = '.my_account_orders .order-actions a:hover:after';
		$elements[] = '.avada-order-details .shop_table.order_details tfoot tr:last-child .amount';
		$elements[] = '#wrapper .cart-checkout a:hover';
		$elements[] = '#wrapper .cart-checkout a:hover:before';
		$elements[] = '.widget_shopping_cart_content .total .amount';
		$elements[] = '.widget_layered_nav li a:hover:before';
		$elements[] = '.widget_product_categories li a:hover:before';
		$elements[] = '.my_account_orders .order-number a';
		$elements[] = '.shop_table .product-subtotal .amount';
		$elements[] = '.cart_totals .order-total .amount';
		$elements[] = '.checkout .shop_table tfoot .order-total .amount';
		$elements[] = '#final-order-details .mini-order-details tr:last-child .amount';
	}
	$css['global'][avada_implode( $elements )]['color'] = Avada_Sanitize::color( Avada()->settings->get( 'primary_color' ), Avada()->settings->get_default( 'primary_color' ) );

	$elements = array(
		'.link-area-link-icon-hover .heading h2',
		'.link-area-box-hover .heading h2',
		'.link-area-link-icon-hover.link-area-box .fusion-read-more',
		'.link-area-link-icon-hover.link-area-box .fusion-read-more::after',
		'.link-area-link-icon-hover.link-area-box .fusion-read-more::before',
		'.link-area-link-icon-hover .icon .circle-no',
		'.link-area-box-hover .icon .circle-no'
	);
	$css['global'][avada_implode( $elements )]['color'] = Avada_Sanitize::color( Avada()->settings->get( 'primary_color' ), Avada()->settings->get_default( 'primary_color' ) ) . ' !important';

	$elements = array(
		'.fusion-content-boxes .heading-link:hover .icon i.circle-yes',
		'.fusion-content-boxes .link-area-box:hover .heading-link .icon i.circle-yes',
		'.fusion-accordian .panel-title a:hover .fa-fusion-box',
		'.link-area-link-icon-hover .heading .icon i.circle-yes',
		'.link-area-box-hover .heading .icon i.circle-yes',
	);
	$css['global'][avada_implode( $elements )]['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'primary_color' ), Avada()->settings->get_default( 'primary_color' ) ) . ' !important';
	$css['global'][avada_implode( $elements )]['border-color']     = Avada_Sanitize::color( Avada()->settings->get( 'primary_color' ), Avada()->settings->get_default( 'primary_color' ) ) . ' !important';

	$css['global']['.sidebar .fusion-image-wrapper .fusion-rollover .fusion-rollover-content a:hover']['color'] = '#333333';

	$elements = array( '.star-rating:before', '.star-rating span:before' );
	$css['global'][avada_implode( $elements )]['color'] = Avada_Sanitize::color( Avada()->settings->get( 'primary_color' ), Avada()->settings->get_default( 'primary_color' ) );

	$elements = array( '.tagcloud a:hover', '#slidingbar-area .tagcloud a:hover', '.fusion-footer-widget-area .tagcloud a:hover' );
	$css['global'][avada_implode( $elements )]['color']       = '#FFFFFF';
	$css['global'][avada_implode( $elements )]['text-shadow'] = 'none';

	$elements = array(
		'.reading-box',
		'.fusion-filters .fusion-filter.fusion-active a',
		'#wrapper .fusion-tabs-widget .tab-holder .tabs li.active a',
		'#wrapper .post-content blockquote',
		'.progress-bar-content',
		'.pagination .current',
		'.pagination a.inactive:hover',
		'#nav ul li > a:hover',
		'#sticky-nav ul li > a:hover',
		'.tagcloud a:hover',
		'#wrapper .fusion-tabs.classic .nav-tabs > li.active .tab-link:hover',
		'#wrapper .fusion-tabs.classic .nav-tabs > li.active .tab-link:focus',
		'#wrapper .fusion-tabs.classic .nav-tabs > li.active .tab-link',
		'#wrapper .fusion-tabs.vertical-tabs.classic .nav-tabs > li.active .tab-link'
	);
	if ( class_exists( 'bbPress' ) ) {
		$elements[] = '.bbp-topic-pagination .current';
		$elements[] = '#bbpress-forums div.bbp-topic-tags a:hover';
	}
	if ( class_exists( 'WooCommerce' ) ) {
		$elements[] = '.woocommerce-pagination .page-numbers.current';
		$elements[] = '.woocommerce-pagination .page-numbers:hover';
		$elements[] = '.woocommerce-pagination .current';
	}
	$css['global'][avada_implode( $elements )]['border-color'] = Avada_Sanitize::color( Avada()->settings->get( 'primary_color' ), Avada()->settings->get_default( 'primary_color' ) );

	$css['global']['#wrapper .side-nav li.current_page_item a']['border-right-color'] = Avada_Sanitize::color( Avada()->settings->get( 'primary_color' ), Avada()->settings->get_default( 'primary_color' ) );
	$css['global']['#wrapper .side-nav li.current_page_item a']['border-left-color']  = Avada_Sanitize::color( Avada()->settings->get( 'primary_color' ), Avada()->settings->get_default( 'primary_color' ) );

	$elements = array(
		'.fusion-accordian .panel-title .active .fa-fusion-box',
		'ul.circle-yes li:before',
		'.circle-yes ul li:before',
		'.progress-bar-content',
		'.pagination .current',
		'.fusion-date-and-formats .fusion-date-box',
		'.table-2 table thead',
		'.tagcloud a:hover',
		'#toTop:hover',
		'#wrapper .search-table .search-button input[type="submit"]:hover',
		'ul.arrow li:before',
	);
	if ( class_exists( 'bbPress' ) ) {
		$elements[] = '.bbp-topic-pagination .current';
		$elements[] = '#bbpress-forums div.bbp-topic-tags a:hover';
	}
	if ( class_exists( 'WooCommerce' ) ) {
		$elements[] = '.onsale';
		$elements[] = '.woocommerce-pagination .current';
		$elements[] = '.woocommerce .social-share li a:hover i';
		$elements[] = '.price_slider_wrapper .ui-slider .ui-slider-range';
		$elements[] = '.cart-loading';
		$elements[] = 'p.demo_store';
		$elements[] = '.avada-myaccount-data .digital-downloads li:before';
		$elements[] = '.avada-thank-you .order_details li:before';
		$elements[] = '.sidebar .widget_layered_nav li.chosen';
		$elements[] = '.sidebar .widget_layered_nav_filters li.chosen';
	}
	$css['global'][avada_implode( $elements )]['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'primary_color' ), Avada()->settings->get_default( 'primary_color' ) );

	if ( class_exists( 'WooCommerce' ) ) {
		$css['global']['.woocommerce .social-share li a:hover i']['border-color'] = Avada_Sanitize::color( Avada()->settings->get( 'primary_color' ), Avada()->settings->get_default( 'primary_color' ) );
	}

	if ( class_exists( 'bbPress' ) ) {
		$elements = array(
			'.bbp-topics-front ul.super-sticky',
			'.bbp-topics ul.super-sticky',
			'.bbp-topics ul.sticky',
			'.bbp-forum-content ul.sticky'
		);
		$css['global'][avada_implode( $elements )]['background-color'] = '#ffffe8';
		$css['global'][avada_implode( $elements )]['opacity']          = '1';
	}

	if ( Avada()->settings->get( 'slidingbar_widgets' ) ) {

		if ( Avada()->settings->get( 'slidingbar_bg_color' ) ) {

			$color = Avada()->settings->get( 'slidingbar_bg_color' );
			if( ! $color ) {
				$color = Avada()->settings->get_default( 'slidingbar_bg_color' );
			}
			$rgb   = fusion_hex2rgb( $color['color'] );
			$rgba  = 'rgba( ' . $rgb[0] . ',' . $rgb[1] . ',' . $rgb[2] . ',' . $color['opacity'] . ')';

			$css['global']['#slidingbar']['background-color'][] = Avada_Sanitize::color( $color['color'] );
			$css['global']['#slidingbar']['background-color'][] = Avada_Sanitize::color( $rgba );

			$css['global']['.sb-toggle-wrapper']['border-top-color'][] = $color['color'];
			$css['global']['.sb-toggle-wrapper']['border-top-color'][] = $rgba;

			$css['global']['#wrapper #slidingbar-area .fusion-tabs-widget .tab-holder .tabs li']['border-color'][] = $color['color'];
			$css['global']['#wrapper #slidingbar-area .fusion-tabs-widget .tab-holder .tabs li']['border-color'][] = $rgba;

			if ( Avada()->settings->get( 'slidingbar_top_border' ) ) {

				$css['global']['#slidingbar-area']['border-bottom'][] = '3px solid ' . $color['color'];
				$css['global']['#slidingbar-area']['border-bottom'][] = '3px solid ' . $rgba;

				$css['global']['.fusion-header-wrapper']['margin-top']   = '3px';
				$css['global']['.admin-bar p.demo_store']['padding-top'] = '13px';

			}

			if ( ( ( 'Boxed' == Avada()->settings->get( 'layout' ) && 'default' == get_post_meta( $c_pageID, 'pyre_page_bg_layout', true ) ) || 'boxed' == get_post_meta( $c_pageID, 'pyre_page_bg_layout', true ) ) && 'Top' != Avada()->settings->get( 'header_position' ) ) {
				$elements = array(
					'.side-header-right #slidingbar-area',
					'.side-header-left #slidingbar-area'
				);
				$css['global'][avada_implode( $elements )]['top'] = 'auto';
			}

		}

	}

	$elements = array(
		'#main',
		'#wrapper',
		'.fusion-separator .icon-wrapper',
		'html',
		'body',
	);
	if ( class_exists( 'bbPress' ) ) {
		$elements[] = '.bbp-arrow';
	}
	if ( class_exists( 'WooCommerce' ) ) {
		$elements[] = '.woocommerce-tabs > .tabs .active a';
	}
	$css['global'][avada_implode( $elements )]['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'content_bg_color' ), Avada()->settings->get_default( 'content_bg_color' ) );

	$css['global']['.fusion-footer-widget-area']['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'footer_bg_color' ), Avada()->settings->get_default( 'footer_bg_color' ) );

	$css['global']['#wrapper .fusion-footer-widget-area .fusion-tabs-widget .tab-holder .tabs li']['border-color'] = Avada_Sanitize::color( Avada()->settings->get( 'footer_bg_color' ), Avada()->settings->get_default( 'footer_bg_color' ) );

	$css['global']['.fusion-footer-widget-area']['border-color'] = Avada_Sanitize::color( Avada()->settings->get( 'footer_border_color' ), Avada()->settings->get_default( 'footer_border_color' ) );

	$css['global']['.fusion-footer-copyright-area']['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'copyright_bg_color' ), Avada()->settings->get_default( 'copyright_bg_color' ) );
	$css['global']['.fusion-footer-copyright-area']['border-color']     = Avada_Sanitize::color( Avada()->settings->get( 'copyright_border_color' ), Avada()->settings->get_default( 'copyright_border_color' ) );

	$css['global']['.sep-boxed-pricing .panel-heading']['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'pricing_box_color' ), Avada()->settings->get_default( 'pricing_box_color' ) );
	$css['global']['.sep-boxed-pricing .panel-heading']['border-color']     = Avada_Sanitize::color( Avada()->settings->get( 'pricing_box_color' ), Avada()->settings->get_default( 'pricing_box_color' ) );

	$elements = array(
		'.fusion-pricing-table .panel-body .price .integer-part',
		'.fusion-pricing-table .panel-body .price .decimal-part',
		'.full-boxed-pricing.fusion-pricing-table .standout .panel-heading h3'
	);
	$css['global'][avada_implode( $elements )]['color'] = Avada_Sanitize::color( Avada()->settings->get( 'pricing_box_color' ), Avada()->settings->get_default( 'pricing_box_color' ) );

	$image_rollover_opacity               = ( Avada()->settings->get( 'image_gradient_top_color', 'opacity' ) ) ? Avada()->settings->get( 'image_gradient_top_color', 'opacity' ) : 1;
	$image_rollover_gradient_top_color    = Avada()->settings->get( 'image_gradient_top_color', 'color' );
	if( ! $image_rollover_gradient_top_color ) {
		$image_rollover_gradient_top_color = Avada()->settings->get_default( 'image_gradient_top_color', 'color' );
	}
	$image_rollover_gradient_bottom_color = Avada()->settings->get( 'image_gradient_bottom_color' );
	if( ! $image_rollover_gradient_bottom_color ) {
		$image_rollover_gradient_bottom_color = Avada()->settings->get_default( 'image_gradient_bottom_color' );
	}

	if ( '' != $image_rollover_gradient_top_color ) {
		$image_rollover_gradient_top       = fusion_hex2rgb( $image_rollover_gradient_top_color );
		$image_rollover_gradient_top_color = 'rgba(' . $image_rollover_gradient_top[0] . ',' . $image_rollover_gradient_top[1] . ',' . $image_rollover_gradient_top[2] . ',' . $image_rollover_opacity . ')';
	}

	if ( '' != $image_rollover_gradient_bottom_color ) {
		$image_rollover_gradient_bottom       = fusion_hex2rgb( $image_rollover_gradient_bottom_color );
		$image_rollover_gradient_bottom_color = 'rgba(' . $image_rollover_gradient_bottom[0] . ',' . $image_rollover_gradient_bottom[1] . ',' . $image_rollover_gradient_bottom[2] . ',' . $image_rollover_opacity . ')';
	}

	$css['global']['.fusion-image-wrapper .fusion-rollover']['background-image'][] = 'linear-gradient(top, ' . Avada_Sanitize::color( $image_rollover_gradient_top_color ) . ' 0%, ' . Avada_Sanitize::color( $image_rollover_gradient_bottom_color ) . ' 100%)';
	$css['global']['.fusion-image-wrapper .fusion-rollover']['background-image'][] = '-webkit-gradient(linear, left top, left bottom, color-stop(0, ' . Avada_Sanitize::color( $image_rollover_gradient_top_color ) . '), color-stop(1, ' . Avada_Sanitize::color( $image_rollover_gradient_bottom_color ) . '))';
	$css['global']['.fusion-image-wrapper .fusion-rollover']['background-image'][] = 'filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=' . Avada_Sanitize::color( Avada()->settings->get( 'image_gradient_top_color', 'color' ), Avada()->settings->get_default( 'image_gradient_top_color', 'color' ) ) . ', endColorstr=' .  Avada_Sanitize::color( Avada()->settings->get( 'image_gradient_bottom_color' ), Avada()->settings->get_default( 'image_gradient_bottom_color' ) ) . '), progid: DXImageTransform.Microsoft.Alpha(Opacity=0)';

	$css['global']['.no-cssgradients .fusion-image-wrapper .fusion-rollover']['background'] =  Avada_Sanitize::color( Avada()->settings->get( 'image_gradient_top_color', 'color' ), Avada()->settings->get_default( 'image_gradient_top_color', 'color' ) );

	$css['global']['.fusion-image-wrapper:hover .fusion-rollover']['filter'] = 'progid:DXImageTransform.Microsoft.gradient(startColorstr=' . Avada_Sanitize::color( Avada()->settings->get( 'image_gradient_top_color', 'color' ), Avada()->settings->get_default( 'image_gradient_top_color', 'color' ) ) . ', endColorstr=' . Avada_Sanitize::color( Avada()->settings->get( 'image_gradient_bottom_color' ), Avada()->settings->get_default( 'image_gradient_bottom_color' ) ) . '), progid: DXImageTransform.Microsoft.Alpha(Opacity=100)';

	$button_gradient_top_color          = ( ! Avada()->settings->get( 'button_gradient_top_color' ) )          ? 'transparent' : Avada_Sanitize::color( Avada()->settings->get( 'button_gradient_top_color' ) );
	$button_gradient_bottom_color       = ( ! Avada()->settings->get( 'button_gradient_bottom_color' ) )       ? 'transparent' : Avada_Sanitize::color( Avada()->settings->get( 'button_gradient_bottom_color' ) );
	$button_accent_color                = ( ! Avada()->settings->get( 'button_accent_color' ) )                ? 'transparent' : Avada_Sanitize::color( Avada()->settings->get( 'button_accent_color' ) );
	$button_gradient_top_hover_color    = ( ! Avada()->settings->get( 'button_gradient_top_color_hover' ) )    ? 'transparent' : Avada_Sanitize::color( Avada()->settings->get( 'button_gradient_top_color_hover' ) );
	$button_gradient_bottom_hover_color = ( ! Avada()->settings->get( 'button_gradient_bottom_color_hover' ) ) ? 'transparent' : Avada_Sanitize::color( Avada()->settings->get( 'button_gradient_bottom_color_hover' ) );
	$button_accent_hover_color          = ( ! Avada()->settings->get( 'button_accent_hover_color' ) )          ? 'transparent' : Avada_Sanitize::color( Avada()->settings->get( 'button_accent_hover_color' ) );

	$elements = array(
		'.fusion-portfolio-one .fusion-button',
		'#main .comment-submit',
		'#reviews input#submit',
		'.comment-form input[type="submit"]',
		'.button-default',
		'.fusion-button-default',
		'.button.default',
		'.ticket-selector-submit-btn[type=submit]'
	);
	if ( class_exists( 'GFForms' ) ) {
		$elements[] = '.gform_wrapper .gform_button';
		$elements[] = '.gform_wrapper .button';
		$elements[] = '.gform_page_footer input[type="button"]';
	}
	if ( defined( 'WPCF7_PLUGIN' ) ) {
		$elements[] = '.wpcf7-form input[type="submit"]';
		$elements[] = '.wpcf7-submit';
	}
	if ( class_exists( 'bbPress' ) ) {
		$elements[] = '.bbp-submit-wrapper .button';
		$elements[] = '#bbp_user_edit_submit';
	}
	if ( class_exists( 'WooCommerce' ) ) {
		$elements[] = '.price_slider_amount button';
		$elements[] = '.woocommerce .single_add_to_cart_button';
		$elements[] = '.woocommerce button.button';
		$elements[] = '.woocommerce .shipping-calculator-form .button';
		$elements[] = '.woocommerce .checkout #place_order';
		$elements[] = '.woocommerce .checkout_coupon .button';
		$elements[] = '.woocommerce .login .button';
		$elements[] = '.woocommerce .register .button';
		$elements[] = '.woocommerce .avada-order-details .order-again .button';
		$elements[] = '.woocommerce .avada-order-details .order-again .button';
		$elements[] = '.woocommerce .lost_reset_password input[type="submit"]';
	}
	$css['global'][avada_implode( $elements )]['background']         = $button_gradient_top_color;
	$css['global'][avada_implode( $elements )]['color']              = $button_accent_color;
	if ( $button_gradient_top_color != $button_gradient_bottom_color ) {
		$css['global'][avada_implode( $elements )]['background-image'][] = '-webkit-gradient( linear, left bottom, left top, from( ' . $button_gradient_bottom_color . ' ), to( ' . $button_gradient_top_color . ' ) )';
		$css['global'][avada_implode( $elements )]['background-image'][] = 'linear-gradient( to top, ' . $button_gradient_bottom_color . ', ' . $button_gradient_top_color . ' )';
	}
	if ( Avada()->settings->get( 'button_shape' ) != 'Pill' ) {
		$css['global'][avada_implode( $elements )]['filter']             = 'progid:DXImageTransform.Microsoft.gradient(startColorstr=' . $button_gradient_top_color . ', endColorstr=' . $button_gradient_bottom_color . ')';
	}
	$css['global'][avada_implode( $elements )]['transition']         = 'all .2s';

	$elements = array(
		'.no-cssgradients .fusion-portfolio-one .fusion-button',
		'.no-cssgradients #main .comment-submit',
		'.no-cssgradients #reviews input#submit',
		'.no-cssgradients .comment-form input[type="submit"]',
		'.no-cssgradients .button-default',
		'.no-cssgradients .fusion-button-default',
		'.no-cssgradients .button.default',
		'.no-cssgradients .ticket-selector-submit-btn[type="submit"]',
		'.link-type-button-bar .fusion-read-more'
	);
	if ( class_exists( 'GFForms' ) ) {
		$elements[] = '.no-cssgradients .gform_wrapper .gform_button';
		$elements[] = '.no-cssgradients .gform_wrapper .button';
		$elements[] = '.no-cssgradients .gform_page_footer input[type="button"]';
	}
	if ( defined( 'WPCF7_PLUGIN' ) ) {
		$elements[] = '.no-cssgradients .wpcf7-form input[type="submit"]';
		$elements[] = '.no-cssgradients .wpcf7-submit';
	}
	if ( class_exists( 'bbPress' ) ) {
		$elements[] = '.no-cssgradients .bbp-submit-wrapper .button';
		$elements[] = '.no-cssgradients #bbp_user_edit_submit';
	}
	if ( class_exists( 'WooCommerce' ) ) {
		$elements[] = '.no-cssgradients .price_slider_amount button';
		$elements[] = '.no-cssgradients .woocommerce .single_add_to_cart_button';
		$elements[] = '.no-cssgradients .woocommerce button.button';
		$elements[] = '.no-cssgradients .woocommerce .shipping-calculator-form .button';
		$elements[] = '.no-cssgradients .woocommerce .checkout #place_order';
		$elements[] = '.no-cssgradients .woocommerce .checkout_coupon .button';
		$elements[] = '.no-cssgradients .woocommerce .login .button';
		$elements[] = '.no-cssgradients .woocommerce .register .button';
		$elements[] = '.no-cssgradients .woocommerce .avada-order-details .order-again .button';
		$elements[] = '.no-cssgradients .woocommerce .lost_reset_password input[type="submit"]';
	}
	$css['global'][avada_implode( $elements )]['background'] = $button_gradient_top_color;

	$elements = array(
		'.fusion-portfolio-one .fusion-button:hover',
		'#main .comment-submit:hover',
		'#reviews input#submit:hover',
		'.comment-form input[type="submit"]:hover',
		'.button-default:hover',
		'.fusion-button-default:hover',
		'.button.default:hover',
		'.ticket-selector-submit-btn[type="submit"]:hover',
	);
	if ( class_exists( 'GFForms' ) ) {
		$elements[] = '.gform_wrapper .gform_button:hover';
		$elements[] = '.gform_wrapper .button:hover';
		$elements[] = '.gform_page_footer input[type="button"]:hover';
	}
	if ( defined( 'WPCF7_PLUGIN' ) ) {
		$elements[] = '.wpcf7-form input[type="submit"]:hover';
		$elements[] = '.wpcf7-submit:hover';
	}
	if ( class_exists( 'bbPress' ) ) {
		$elements[] = '.bbp-submit-wrapper .button:hover';
		$elements[] = '#bbp_user_edit_submit:hover';
	}
	if ( class_exists( 'WooCommerce' ) ) {
		$elements[] = '.price_slider_amount button:hover';
		$elements[] = '.woocommerce .single_add_to_cart_button:hover';
		$elements[] = '.woocommerce .shipping-calculator-form .button:hover';
		$elements[] = '.woocommerce .checkout #place_order:hover';
		$elements[] = '.woocommerce .checkout_coupon .button:hover';
		$elements[] = '.woocommerce .login .button:hover';
		$elements[] = '.woocommerce .register .button:hover';
		$elements[] = '.woocommerce .avada-order-details .order-again .button:hover';
		$elements[] = '.woocommerce .lost_reset_password input[type="submit"]:hover';
	}
	$css['global'][avada_implode( $elements )]['background'] = $button_gradient_top_hover_color;
	$css['global'][avada_implode( $elements )]['color'] = $button_accent_hover_color;
	if ( $button_gradient_top_hover_color != $button_gradient_bottom_hover_color ) {
		$css['global'][avada_implode( $elements )]['background-image'][] = '-webkit-gradient( linear, left bottom, left top, from( ' . $button_gradient_bottom_hover_color . ' ), to( ' . $button_gradient_top_hover_color . ' ) )';
		$css['global'][avada_implode( $elements )]['background-image'][] = 'linear-gradient( to top, ' . $button_gradient_bottom_hover_color . ', ' . $button_gradient_top_hover_color . ' )';
	}
	if ( Avada()->settings->get( 'button_shape' ) != 'Pill' ) {
		$css['global'][avada_implode( $elements )]['filter'] = 'progid:DXImageTransform.Microsoft.gradient(startColorstr=' . $button_gradient_top_hover_color . ', endColorstr=' . $button_gradient_bottom_hover_color . ')';
	}
	$elements = array(
		'.no-cssgradients .fusion-portfolio-one .fusion-button:hover',
		'.no-cssgradients #main .comment-submit:hover',
		'.no-cssgradients #reviews input#submit:hover',
		'.no-cssgradients .comment-form input[type="submit"]:hover',
		'.no-cssgradients .button-default:hover',
		'.no-cssgradients .fusion-button-default:hover',
		'.no-cssgradinets .button.default:hover',
		'.no-cssgradients .ticket-selector-submit-btn[type="submit"]:hover',
	);
	if ( class_exists( 'GFForms' ) ) {
		$elements[] = '.no-cssgradients .gform_wrapper .gform_button:hover';
		$elements[] = '.no-cssgradients .gform_wrapper .button:hover';
		$elements[] = '.no-cssgradients .gform_page_footer input[type="button"]:hover';
	}
	if ( defined( 'WPCF7_PLUGIN' ) ) {
		$elements[] = '.no-cssgradients .wpcf7-form input[type="submit"]:hover';
		$elements[] = '.no-cssgradients .wpcf7-submit:hover';
	}
	if ( class_exists( 'bbPress' ) ) {
		$elements[] = '.no-cssgradients .bbp-submit-wrapper .button:hover';
		$elements[] = '.no-cssgradients #bbp_user_edit_submit:hover';
	}
	if ( class_exists( 'WooCommerce' ) ) {
		$elements[] = '.no-cssgradients .price_slider_amount button:hover';
		$elements[] = '.no-cssgradients .woocommerce .single_add_to_cart_button:hover';
		$elements[] = '.no-cssgradients .woocommerce .shipping-calculator-form .button:hover';
		$elements[] = '.no-cssgradients .woocommerce .checkout #place_order:hover';
		$elements[] = '.no-cssgradients .woocommerce .checkout_coupon .button:hover';
		$elements[] = '.no-cssgradients .woocommerce .login .button:hover';
		$elements[] = '.no-cssgradients .woocommerce .register .button:hover';
		$elements[] = '.no-cssgradients .woocommerce .avada-order-details .order-again .button:hover';
		$elements[] = '.no-cssgradients .woocommerce .lost_reset_password input[type="submit"]:hover';
	}
	$css['global'][avada_implode( $elements )]['background'] = $button_gradient_top_hover_color . ' !important';

	$elements = array(
		'.link-type-button-bar .fusion-read-more',
		'.link-type-button-bar .fusion-read-more:after',
		'.link-type-button-bar .fusion-read-more:before'
	);

	$css['global'][avada_implode( $elements )]['color'] = $button_accent_color;

	$elements = array(
		'.link-type-button-bar .fusion-read-more:hover',
		'.link-type-button-bar .fusion-read-more:hover:after',
		'.link-type-button-bar .fusion-read-more:hover:before',
		'.link-type-button-bar.link-area-box:hover .fusion-read-more',
		'.link-type-button-bar.link-area-box:hover .fusion-read-more:after',
		'.link-type-button-bar.link-area-box:hover .fusion-read-more:before'
	);

	$css['global'][avada_implode( $elements )]['color'] = $button_accent_color . ' !important';

	$elements = array(
		'.fusion-image-wrapper .fusion-rollover .fusion-rollover-link',
		'.fusion-image-wrapper .fusion-rollover .fusion-rollover-gallery'
	);
	$css['global'][avada_implode( $elements )]['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'image_rollover_text_color' ), Avada()->settings->get_default( 'image_rollover_text_color' ) );

	$elements = array(
		'.fusion-rollover .fusion-rollover-content .fusion-rollover-title',
		'.fusion-image-wrapper .fusion-rollover .fusion-rollover-content .fusion-rollover-title a',
		'.fusion-image-wrapper .fusion-rollover .fusion-rollover-content .fusion-rollover-categories',
		'.fusion-image-wrapper .fusion-rollover .fusion-rollover-content .fusion-rollover-categories a',
		'.fusion-image-wrapper .fusion-rollover .fusion-rollover-content a',
	);
	$css['global'][avada_implode( $elements )]['color'] = Avada_Sanitize::color( Avada()->settings->get( 'image_rollover_text_color' ), Avada()->settings->get_default( 'image_rollover_text_color' ) );

	$css['global']['.fusion-page-title-bar']['border-color'] = Avada_Sanitize::color( Avada()->settings->get( 'page_title_border_color' ), Avada()->settings->get_default( 'page_title_border_color' ) );

	if ( 'transparent' == Avada()->settings->get( 'page_title_border_color' ) ) {
		$css['global']['.fusion-page-title-bar']['border'] = 'none';
	}

	if ( Avada()->settings->get( 'footer_sticky_height' ) && ( in_array( Avada()->settings->get( 'footer_special_effects' ), array( 'footer_sticky', 'footer_sticky_with_parallax_bg_image' ) ) ) ) {

		if ( Avada()->settings->get( 'responsive' ) ) {
			$media_query = '@media only screen and (min-width: 640px)';

			$elements = array( 'html', 'body', '#boxed-wrapper', '#wrapper' );
			$css[$media_query][avada_implode( $elements )]['height']     = '100%';
			$css[$media_query]['.above-footer-wrapper']['min-height']    = '100%';
			$css[$media_query]['.above-footer-wrapper']['margin-bottom'] = (int) Avada()->settings->get( 'footer_sticky_height' ) * ( -1 ) . 'px';
			$css[$media_query]['.above-footer-wrapper:after']['content'] = '""';
			$css[$media_query]['.above-footer-wrapper:after']['display'] = 'block';
			$css[$media_query]['.above-footer-wrapper:after']['height']  = Avada_Sanitize::size( Avada()->settings->get( 'footer_sticky_height' ) );
			$css[$media_query]['.fusion-footer']['height']               = Avada_Sanitize::size( Avada()->settings->get( 'footer_sticky_height' ) );

			$media_query = '@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) and (orientation: landscape)';

			$elements = array( 'html', 'body', '#boxed-wrapper', '#wrapper' );
			$css[$media_query][avada_implode( $elements )]['height']     = 'auto';
			$css[$media_query]['.above-footer-wrapper']['min-height']    = 'none';
			$css[$media_query]['.above-footer-wrapper']['margin-bottom'] = '0';
			$css[$media_query]['.above-footer-wrapper:after']['height']  = 'auto';
			$css[$media_query]['.fusion-footer']['height']               = 'auto';

			$media_query = '@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) and (orientation: portrait)';
			$elements = array( 'html', 'body', '#boxed-wrapper', '#wrapper' );
			$css[$media_query][avada_implode( $elements )]['height']     = 'auto';
			$css[$media_query]['.above-footer-wrapper']['min-height']    = 'none';
			$css[$media_query]['.above-footer-wrapper']['margin-bottom'] = '0';
			$css[$media_query]['.above-footer-wrapper:after']['height']  = 'auto';
			$css[$media_query]['.fusion-footer']['height']               = 'auto';
		}

	}

	if ( Avada()->settings->get( 'footerw_bg_image' ) ) {

		$css['global']['.fusion-footer-widget-area']['background-image']    = 'url("' . Avada_Sanitize::css_asset_url( Avada()->settings->get( 'footerw_bg_image' ) ) . '")';
		$css['global']['.fusion-footer-widget-area']['background-repeat']   = esc_attr( Avada()->settings->get( 'footerw_bg_repeat' ) );
		$css['global']['.fusion-footer-widget-area']['background-position'] = esc_attr( Avada()->settings->get( 'footerw_bg_pos' ) );

		if ( Avada()->settings->get( 'footerw_bg_full' ) ) {

			$css['global']['.fusion-footer-widget-area']['background-attachment'] = 'scroll';
			$css['global']['.fusion-footer-widget-area']['background-position']   = 'center center';
			$css['global']['.fusion-footer-widget-area']['background-size']       = 'cover';

		}

	}

	if ( in_array( Avada()->settings->get( 'footer_special_effects' ), array( 'footer_area_bg_parallax', 'footer_sticky_with_parallax_bg_image'  ) ) ) {
		$css['global']['.fusion-footer-widget-area']['background-attachment'] = 'fixed';
		$css['global']['.fusion-footer-widget-area']['background-position']   = 'top center';
	}

	if ( Avada()->settings->get( 'footer_special_effects' ) == 'footer_parallax_effect' ) {
		$elements = array(
			'#sliders-container',
			'.fusion-page-title-bar',
			'#main'
		);

		$css['global'][avada_implode( $elements )]['position']  = 'relative';
		$css['global'][avada_implode( $elements )]['z-index']   = '1';
	}

	if ( Avada()->settings->get( 'footer_special_effects' ) == 'footer_area_bg_parallax' ) {
		$media_query = '@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) and (orientation: portrait)';

		$css[$media_query]['.fusion-footer-widget-area']['background-attachment'] = 'static';
		$css[$media_query]['.fusion-footer-widget-area']['margin']   = '0';
		$css[$media_query]['.fusion-footer-widget-area']['padding']   = '0';

		$css[$media_query]['#main']['margin-bottom']   = '0';

		$media_query = '@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) and (orientation: landscape)';

		$css[$media_query]['.fusion-footer-widget-area']['background-attachment'] = 'static';
		$css[$media_query]['.fusion-footer-widget-area']['margin']   = '0';
		$css[$media_query]['.fusion-footer-widget-area']['padding']   = '0';

		$css[$media_query]['#main']['margin-bottom']   = '0';
	}

	$css['global']['.fusion-footer-widget-area']['padding-top']    = Avada_Sanitize::size( Avada()->settings->get( 'footer_area_top_padding' ) );
	$css['global']['.fusion-footer-widget-area']['padding-bottom'] = Avada_Sanitize::size( Avada()->settings->get( 'footer_area_bottom_padding' ) );

	$elements = array(
		'.fusion-footer-widget-area > .fusion-row',
		'.fusion-footer-copyright-area > .fusion-row'
	);
	$css['global'][avada_implode( $elements )]['padding-left']  = Avada_Sanitize::size( Avada()->settings->get( 'footer_area_left_padding' ) );
	$css['global'][avada_implode( $elements )]['padding-right'] = Avada_Sanitize::size( Avada()->settings->get( 'footer_area_right_padding' ) );

	if ( Avada()->settings->get( 'footer_100_width' ) ) {
		$elements = array(
			'.layout-wide-mode .fusion-footer-widget-area > .fusion-row',
			'.layout-wide-mode .fusion-footer-copyright-area > .fusion-row',
		);
		$css['global'][avada_implode( $elements )]['max-width'] = '100% !important';
	}

	$css['global']['.fusion-footer-copyright-area']['padding-top']    = Avada_Sanitize::size( Avada()->settings->get( 'copyright_top_padding' ) );
	$css['global']['.fusion-footer-copyright-area']['padding-bottom'] = Avada_Sanitize::size( Avada()->settings->get( 'copyright_bottom_padding' ) );

	$css['global']['.fontawesome-icon.circle-yes']['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'icon_circle_color' ), Avada()->settings->get_default( 'icon_circle_color' ) );
	$elements = array(
		'.fontawesome-icon.circle-yes',
		'.content-box-shortcode-timeline'
	);
	$css['global'][avada_implode( $elements )]['border-color'] = Avada_Sanitize::color( Avada()->settings->get( 'icon_border_color' ), Avada()->settings->get_default( 'icon_border_color' ) );

	$elements = array(
		'.fontawesome-icon',
		'.fontawesome-icon.circle-yes',
		'.post-content .error-menu li:before',
		'.post-content .error-menu li:after',
	);
	if ( class_exists( 'WooCommerce' ) ) {
		$elements[] = '.avada-myaccount-data .digital-downloads li:before';
		$elements[] = '.avada-myaccount-data .digital-downloads li:after';
		$elements[] = '.avada-thank-you .order_details li:before';
		$elements[] = '.avada-thank-you .order_details li:after';
	}
	$css['global'][avada_implode( $elements )]['color'] = Avada_Sanitize::color( Avada()->settings->get( 'icon_color' ), Avada()->settings->get_default( 'icon_color' ) );

	$elements = array( '.fusion-title .title-sep', '.product .product-border' );
	$css['global'][avada_implode( $elements )]['border-color'] = Avada_Sanitize::color( Avada()->settings->get( 'title_border_color' ), Avada()->settings->get_default( 'title_border_color' ) );

	$elements = array( '.review blockquote q', '.post-content blockquote', '.checkout .payment_methods .payment_box' );
	$css['global'][avada_implode( $elements )]['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'testimonial_bg_color' ), Avada()->settings->get_default( 'testimonial_bg_color' ) );

	$css['global']['.fusion-testimonials .author:after']['border-top-color'] = Avada_Sanitize::color( Avada()->settings->get( 'testimonial_bg_color' ), Avada()->settings->get_default( 'testimonial_bg_color' ) );

	$elements = array( '.review blockquote q', '.post-content blockquote' );
	$css['global'][avada_implode( $elements )]['color'] = Avada_Sanitize::color( Avada()->settings->get( 'testimonial_text_color' ), Avada()->settings->get_default( 'testimonial_text_color' ) );

	$is_custom_font = ( null !== Avada()->settings->get( 'custom_font_woff' ) && Avada()->settings->get( 'custom_font_woff' ) ) &&
							( null !== Avada()->settings->get( 'custom_font_ttf' ) && Avada()->settings->get( 'custom_font_ttf' ) ) &&
							( null !== Avada()->settings->get( 'custom_font_svg' ) && Avada()->settings->get( 'custom_font_svg' ) ) &&
							( null !== Avada()->settings->get( 'custom_font_eot' ) && Avada()->settings->get( 'custom_font_eot' ) );

	if ( $is_custom_font ) {
		$css['global']['@font-face']['font-family'] = 'MuseoSlab500Regular';
		$css['global']['@font-face']['src'][]       = 'url("' . Avada_Sanitize::css_asset_url( Avada()->settings->get( 'custom_font_eot' ) ) . '")';
		$css['global']['@font-face']['src'][]       = 'url("' . Avada_Sanitize::css_asset_url( Avada()->settings->get( 'custom_font_eot' ) ) . '?#iefix") format("eot"), url("' . Avada_Sanitize::css_asset_url( Avada()->settings->get( 'custom_font_woff' ) ) . '") format("woff"), url("' . Avada_Sanitize::css_asset_url( Avada()->settings->get( 'custom_font_ttf' ) ) . '") format("truetype"), url("' . Avada_Sanitize::css_asset_url( Avada()->settings->get( 'custom_font_svg' ) ). '#MuseoSlab500Regular") format("svg")';
		$css['global']['@font-face']['font-weight'] = '400';
		$css['global']['@font-face']['font-style']  = 'normal';
	}

	if ( 'None' != Avada()->settings->get( 'google_body' ) ) {
		$font = "'" . Avada()->settings->get( 'google_body' ) . "', Arial, Helvetica, sans-serif";
	} elseif ( 'Select Font' != Avada()->settings->get( 'standard_body' ) ) {
		$font = Avada()->settings->get( 'standard_body' );
	}

	$elements = array(
		'body',
		'#nav ul li ul li a',
		'#sticky-nav ul li ul li a',
		'.more',
		'.avada-container h3',
		'.meta .fusion-date',
		'.review blockquote q',
		'.review blockquote div strong',
		'.project-content .project-info h4',
		'.post-content blockquote',
		'.fusion-load-more-button',
		'.ei-title h3',
		'.comment-form input[type="submit"]',
		'.fusion-page-title-bar h3',
		'.fusion-blog-shortcode .fusion-timeline-date',
		'#reviews #comments > h2',
		'.fusion-image-wrapper .fusion-rollover .fusion-rollover-content .fusion-rollover-title',
		'.fusion-image-wrapper .fusion-rollover .fusion-rollover-content .fusion-rollover-categories',
		'.fusion-image-wrapper .fusion-rollover .fusion-rollover-content a',
		'.fusion-image-wrapper .fusion-rollover .fusion-rollover-content .price',
		'#wrapper #nav ul li ul li > a',
		'#wrapper #sticky-nav ul li ul li > a',
		'.ticket-selector-submit-btn[type="submit"]',
	);
	if ( class_exists( 'GFForms' ) ) {
		$elements[] = '.gform_wrapper .gform_button';
		$elements[] = '.gform_wrapper .button';
		$elements[] = '.gform_page_footer input[type="button"]';
	}
	if ( defined( 'WPCF7_PLUGIN' ) ) {
		$elements[] = '.wpcf7-form input[type="submit"]';
	}
	if ( class_exists( 'bbPress' ) ) {
		$elements[] = '#bbp_user_edit_submit';
	}
	if ( class_exists( 'WooCommerce' ) ) {
		$elements[] = '.woocommerce-success-message .button';
		$elements[] = '.woocommerce .single_add_to_cart_button';
		$elements[] = '.woocommerce button.button';
		$elements[] = '.woocommerce .shipping-calculator-form .button';
		$elements[] = '.woocommerce .checkout #place_order';
		$elements[] = '.woocommerce .checkout_coupon .button';
		$elements[] = '.woocommerce .login .button';
		$elements[] = '.woocommerce .register .button';
	}
	$css['global'][avada_implode( $elements )]['font-family'] = $font;
	$css['global'][avada_implode( $elements )]['font-weight'] = esc_attr( Avada()->settings->get( 'font_weight_body' ) );

	if ( 'None' != Avada()->settings->get( 'google_nav' ) ) {
		$nav_font = "'" . Avada()->settings->get( 'google_nav' ) . "', Arial, Helvetica, sans-serif";
	} elseif ( 'Select Font' != Avada()->settings->get( 'standard_nav' ) ) {
		$nav_font = Avada()->settings->get( 'standard_nav' );
	}

	if ( $is_custom_font ) {
		$nav_font =  '\'MuseoSlab500Regular\', Arial, Helvetica, sans-serif';
	}

	$elements = array(
		'.avada-container h3',
		'.review blockquote div strong',
		'.fusion-footer-widget-area h3',
		'.fusion-footer-widget-area .widget-title',
		'#slidingbar-area  h3',
		'.project-content .project-info h4',
		'.fusion-load-more-button',
		'.comment-form input[type="submit"]',
		'.ticket-selector-submit-btn[type="submit"]',
	);
	if ( class_exists( 'GFForms' ) ) {
		$elements[] = '.gform_wrapper .gform_button';
		$elements[] = '.gform_wrapper .button';
		$elements[] = '.gform_page_footer input[type="button"]';
	}
	if ( defined( 'WPCF7_PLUGIN' ) ) {
		$elements[] = '.wpcf7-form input[type="submit"]';
	}
	if ( class_exists( 'bbPress' ) ) {
		$elements[] = '#bbp_user_edit_submit';
	}
	if ( class_exists( 'WooCommerce' ) ) {
		$elements[] = '.woocommerce .single_add_to_cart_button';
		$elements[] = '.woocommerce button.button';
		$elements[] = '.woocommerce .shipping-calculator-form .button';
		$elements[] = '.woocommerce .checkout #place_order';
		$elements[] = '.woocommerce .checkout_coupon .button';
		$elements[] = '.woocommerce .login .button';
		$elements[] = '.woocommerce .register .button';
		$elements[] = '.woocommerce .avada-order-details .order-again .button';
	}
	$css['global'][avada_implode( $elements )]['font-weight'] = 'bold';

	$elements = array(
		'.meta .fusion-date',
		'.review blockquote q',
		'.post-content blockquote'
	);
	$css['global'][avada_implode( $elements )]['font-style'] = 'italic';

	$css['global']['.side-nav li a']['font-family'] = $nav_font;
	$css['global']['.side-nav li a']['font-weight'] = esc_attr( Avada()->settings->get( 'font_weight_menu' ) );

	if ( ! $is_custom_font && 'None' != Avada()->settings->get( 'google_headings' ) ) {
		$headings_font = "'" . Avada()->settings->get( 'google_headings' ) . "', Arial, Helvetica, sans-serif";
	} elseif ( ! $is_custom_font && 'Select Font' != Avada()->settings->get( 'standard_headings' ) ) {
		$headings_font = Avada()->settings->get( 'standard_headings' );
	} else {
		$headings_font = false;
	}

	if ( $headings_font ) {

		$elements = array(
			'#main .reading-box h2',
			'#main h2',
			'.fusion-page-title-bar h1',
			'.fusion-image-wrapper .fusion-rollover .fusion-rollover-content .fusion-rollover-title',
			'.fusion-image-wrapper .fusion-rollover .fusion-rollover-content .fusion-rollover-title a',
			'#main .post h2',
			'.sidebar .widget h4',
			'#wrapper .fusion-tabs-widget .tab-holder .tabs li a',
			'.share-box h4',
			'.project-content h3',
			'.fusion-author .fusion-author-title',
			'.fusion-pricing-table .title-row',
			'.fusion-pricing-table .pricing-row',
			'.fusion-person .person-desc .person-author .person-author-wrapper',
			'.fusion-accordian .panel-title',
			'.fusion-accordian .panel-heading a',
			'.fusion-tabs .nav-tabs  li .fusion-tab-heading',
			'.fusion-carousel-title',
			'.post-content h1',
			'.post-content h2',
			'.post-content h3',
			'.post-content h4',
			'.post-content h5',
			'.post-content h6',
			'.ei-title h2',
			'table th',
			'.main-flex .slide-content h2',
			'.main-flex .slide-content h3',
			'.fusion-modal .modal-title',
			'.popover .popover-title',
			'.fusion-flip-box .flip-box-heading-back',
			'.fusion-header-tagline',
			'.fusion-title h3',
		);
		if ( class_exists( 'WooCommerce' ) ) {
			$elements[] = '.woocommerce-success-message .msg';
			$elements[] = '.product-title';
			$elements[] = '.cart-empty';
		}
		$css['global'][avada_implode( $elements )]['font-family'] = $headings_font;

		$css['global']['.project-content .project-info h4']['font-family'] = $headings_font;

	}

	$elements = array(
		'#main .reading-box h2',
		'#main h2',
		'.fusion-page-title-bar h1',
		'.fusion-image-wrapper .fusion-rollover .fusion-rollover-content .fusion-rollover-title',
		'.fusion-image-wrapper .fusion-rollover .fusion-rollover-content .fusion-rollover-title a',
		'#main .post h2',
		'.sidebar .widget h4',
		'#wrapper .fusion-tabs-widget .tab-holder .tabs li a',
		'.share-box h4',
		'.project-content h3',
		'.fusion-author .fusion-author-title',
		'.fusion-pricing-table .title-row',
		'.fusion-pricing-table .pricing-row',
		'.fusion-person .person-desc .person-author .person-author-wrapper',
		'.fusion-accordian .panel-title',
		'.fusion-accordian .panel-heading a',
		'.fusion-tabs .nav-tabs  li .fusion-tab-heading',
		'.fusion-carousel-title',
		'.post-content h1',
		'.post-content h2',
		'.post-content h3',
		'.post-content h4',
		'.post-content h5',
		'.post-content h6',
		'.ei-title h2',
		'table th',
		'.main-flex .slide-content h2',
		'.main-flex .slide-content h3',
		'.fusion-modal .modal-title',
		'.popover .popover-title',
		'.fusion-flip-box .flip-box-heading-back',
		'.fusion-header-tagline',
		'.fusion-title h3',
	);
	if ( class_exists( 'WooCommerce' ) ) {
		$elements[] = '.woocommerce-success-message .msg';
		$elements[] = '.product-title';
		$elements[] = '.cart-empty';
	}
	$css['global'][avada_implode( $elements )]['font-weight'] = esc_attr( Avada()->settings->get( 'font_weight_headings' ) );

	if ( 'None' != Avada()->settings->get( 'google_footer_headings' ) ) {
		$footer_headings_font = "'" . Avada()->settings->get( 'google_footer_headings' ) . "', Arial, Helvetica, sans-serif";
	} elseif ( 'Select Font' != Avada()->settings->get( 'standard_footer_headings' ) ) {
		$footer_headings_font = Avada()->settings->get( 'standard_footer_headings' );
	}

	$elements = array( '.fusion-footer-widget-area h3', '.fusion-footer-widget-area .widget-title', '#slidingbar-area h3', '#slidingbar-area .widget-title' );
	$css['global'][avada_implode( $elements )]['font-family'] = $footer_headings_font;
	$css['global'][avada_implode( $elements )]['font-weight'] = esc_attr( Avada()->settings->get( 'font_weight_footer_headings' ) );

	if ( Avada()->settings->get( 'body_font_size' ) ) {

		$elements = array(
			'body',
			'.sidebar .slide-excerpt h2',
			'.fusion-footer-widget-area .slide-excerpt h2',
			'#slidingbar-area .slide-excerpt h2',
			'.jtwt .jtwt_tweet',
			'.sidebar .jtwt .jtwt_tweet'
		);
		$css['global'][avada_implode( $elements )]['font-size']   = intval( Avada()->settings->get( 'body_font_size' ) ) . 'px';
		$css['global'][avada_implode( $elements )]['line-height'] = round( Avada()->settings->get( 'body_font_size' ) * 1.5 ) . 'px';

		$elements = array(
			'.project-content .project-info h4',
			'.fusion-footer-widget-area ul',
			'#slidingbar-area ul',
			'.fusion-tabs-widget .tab-holder .news-list li .post-holder a',
			'.fusion-tabs-widget .tab-holder .news-list li .post-holder .meta'
		);
		if ( class_exists( 'GFForms' ) ) {
			$elements[] = '.gform_wrapper label';
			$elements[] = '.gform_wrapper .gfield_description';
		}
		$css['global'][avada_implode( $elements )]['font-size']   = intval( Avada()->settings->get( 'body_font_size' ) ) . 'px';
		$css['global'][avada_implode( $elements )]['line-height'] = round( Avada()->settings->get( 'body_font_size' ) * 1.5 ) . 'px';

		$css['global']['.fusion-blog-layout-timeline .fusion-timeline-date']['font-size'] = Avada_Sanitize::size( Avada()->settings->get( 'body_font_size' ) );

		$elements = array(
			'.counter-box-content',
			'.fusion-alert',
			'.fusion-progressbar .sr-only',
			'.post-content blockquote',
			'.review blockquote q'
		);
		$css['global'][avada_implode( $elements )]['font-size'] = intval( Avada()->settings->get( 'body_font_size' ) ) . 'px';

	}

	if ( Avada()->settings->get( 'body_font_lh' ) ) {
		$elements = array(
			'body',
			'.sidebar .slide-excerpt h2',
			'.fusion-footer-widget-area .slide-excerpt h2',
			'#slidingbar-area .slide-excerpt h2',
			'.post-content blockquote',
			'.review blockquote q',
			'.project-content .project-info h4',
			'.fusion-accordian .panel-body',
			'#side-header .fusion-contact-info',
			'#side-header .header-social .top-menu'
		);
		$css['global'][avada_implode( $elements )]['line-height'] = intval( Avada()->settings->get( 'body_font_lh' ) ) . 'px';
	}

	if ( Avada()->settings->get( 'breadcrumbs_font_size' ) ) {
		$elements = array(
			'.fusion-page-title-bar .fusion-breadcrumbs',
			'.fusion-page-title-bar .fusion-breadcrumbs li',
			'.fusion-page-title-bar .fusion-breadcrumbs li a'
		);
		$css['global'][avada_implode( $elements )]['font-size'] = intval( Avada()->settings->get( 'breadcrumbs_font_size' ) ) . 'px';
	}

	if ( Avada()->settings->get( 'side_nav_font_size' ) ) {
		$css['global']['.side-nav li a']['font-size'] = intval( Avada()->settings->get( 'side_nav_font_size' ) ) . 'px';
	}

	if ( Avada()->settings->get( 'sidew_font_size' ) ) {
		$css['global']['.sidebar .widget h4']['font-size'] = intval( Avada()->settings->get( 'sidew_font_size' ) ) . 'px';
	}

	if ( Avada()->settings->get( 'slidingbar_font_size' ) ) {
		$elements = array(
			'#slidingbar-area h3',
			'#slidingbar-area .widget-title'
		);
		$css['global'][avada_implode( $elements )]['font-size']   = intval( Avada()->settings->get( 'slidingbar_font_size' ) ) . 'px';
		$css['global'][avada_implode( $elements )]['line-height'] = intval( Avada()->settings->get( 'slidingbar_font_size' ) ) . 'px';
	}

	if ( Avada()->settings->get( 'footw_font_size' ) ) {
		$elements = array(
			'.fusion-footer-widget-area h3',
			'.fusion-footer-widget-area .widget-title'
		);
		$css['global'][avada_implode( $elements )]['font-size']   = intval( Avada()->settings->get( 'footw_font_size' ) ) . 'px';
		$css['global'][avada_implode( $elements )]['line-height'] = intval( Avada()->settings->get( 'footw_font_size' ) ) . 'px';
	}

	if ( Avada()->settings->get( 'copyright_font_size' ) ) {
		$css['global']['.fusion-copyright-notice']['font-size'] = intval( Avada()->settings->get( 'copyright_font_size' ) ) . 'px';
	}

	$elements = array(
		'#main .fusion-row',
		'.fusion-footer-widget-area .fusion-row',
		'#slidingbar-area .fusion-row',
		'.fusion-footer-copyright-area .fusion-row',
		'.fusion-page-title-row',
		'.tfs-slider .slide-content-container .slide-content'
	);
	$css['global'][avada_implode( $elements )]['max-width'] = $site_width_with_units;

	if ( ! Avada()->settings->get( 'responsive' ) ) {

		if ( 'Top' == Avada()->settings->get( 'header_position' ) ) {
			$elements = array( 'html', 'body' );
			$css['global'][avada_implode( $elements )]['overflow-x'] = 'hidden';
		} else {
			$css['global']['.ua-mobile #wrapper']['width'] = 'auto !important';
		}

		$media_query = '@media screen and (max-width: 800px)';
		$css[$media_query]['.fullwidth-box']['background-attachment'] = 'scroll !important';
		$css[$media_query]['.no-mobile-totop .to-top-container']['display'] = 'none';
		$css[$media_query]['.no-mobile-slidingbar #slidingbar-area']['display'] = 'none';

		$media_query = '@media screen and (max-width: 782px)';
		$elements = array( 'body.admin-bar #wrapper #slidingbar-area', '.admin-bar p.demo_store' );
		$css[$media_query][avada_implode( $elements )]['top'] = '46px';
		$css[$media_query]['body.body_blank.admin-bar']['top'] = '45px';
		$css[$media_query]['html #wpadminbar']['z-index']  = '99999 !important';
		$css[$media_query]['html #wpadminbar']['position'] = 'fixed !important';

		$media_query = '@media only screen and (min-device-width: 320px) and (max-device-width: 640px)';
		$css[$media_query]['.fullwidth-box']['background-attachment'] = 'scroll !important';
		$css[$media_query]['.no-mobile-totop .to-top-container']['display'] = 'none';
		$css[$media_query]['.no-mobile-slidingbar #slidingbar-area']['display'] = 'none';

		$media_query = '@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) and (orientation: portrait)';
		$css[$media_query]['.fullwidth-box']['background-attachment'] = 'scroll !important';

		$media_query = '@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) and (orientation: landscape)';
		$css[$media_query]['.fullwidth-box']['background-attachment'] = 'scroll !important';

	}

	if ( Avada()->settings->get( 'h1_font_size' ) ) {
		$css['global']['.post-content h1']['font-size']   = intval( Avada()->settings->get( 'h1_font_size' ) ) . 'px';
	}
	if ( Avada()->settings->get( 'h1_font_size' ) ) {
		$css['global']['.post-content h1']['line-height'] = intval( Avada()->settings->get( 'h1_font_lh' ) ) . 'px';
	}

	$elements = array(
		'#wrapper .post-content h2',
		'#wrapper .fusion-title h2',
		'#wrapper #main .post-content .fusion-title h2',
		'#wrapper .title h2',
		'#wrapper #main .post-content .title h2',
		'#wrapper  #main .post h2',
		'#wrapper  #main .post h2',
		'#main .fusion-portfolio h2',
		'h2.entry-title'
	);
	if ( class_exists( 'WooCommerce' ) ) {
		$elements[] = '#wrapper .woocommerce .checkout h3';
	}
	if ( Avada()->settings->get( 'h2_font_size' ) ) {
		$css['global'][avada_implode( $elements )]['font-size']   = intval( Avada()->settings->get( 'h2_font_size' ) ) . 'px';
	}
	if ( Avada()->settings->get( 'h2_font_lh' ) ) {
		$css['global'][avada_implode( $elements )]['line-height'] = round( intval( Avada()->settings->get( 'h2_font_lh' ) ) * 1.5 ) . 'px';

		$elements = array(
			'#wrapper .post-content h2',
			'#wrapper .fusion-title h2',
			'#wrapper #main .post-content .fusion-title h2',
			'#wrapper .title h2',
			'#wrapper #main .post-content .title h2',
			'#wrapper #main .post h2',
			'#main .fusion-portfolio h2',
			'h2.entry-title'
		);
		if ( class_exists( 'WooCommerce' ) ) {
			$elements[] = '#wrapper  .woocommerce .checkout h3';
			$elements[] = '.cart-empty';
		}
		$css['global'][avada_implode( $elements )]['line-height'] = intval( Avada()->settings->get( 'h2_font_lh' ) ) . 'px';
	}
	$elements = array(
		'#wrapper #main .post > h2.entry-title',
		'#wrapper #main .fusion-post-content > h2.entry-title',
		'#wrapper #main .fusion-portfolio-content > h2.entry-title'
	);
	if ( Avada()->settings->get( 'post_titles_font_size' ) ) {
		$css['global'][avada_implode( $elements )]['font-size']   = intval( Avada()->settings->get( 'post_titles_font_size' ) ) . 'px';
	}
	if ( Avada()->settings->get( 'post_titles_font_lh' ) ) {
		$css['global'][avada_implode( $elements )]['line-height'] = intval( Avada()->settings->get( 'post_titles_font_lh' ) ) . 'px';
	}

	if ( Avada()->settings->get( 'post_titles_extras_font_size' ) ) {
		$elements = array(
			'#wrapper #main .about-author h3',
			'#wrapper #main #comments h3',
			'#wrapper #main #respond h3',
			'#wrapper #main .related-posts h3'
		);
		$css['global'][avada_implode( $elements )]['font-size'] = intval( Avada()->settings->get( 'post_titles_extras_font_size' ) ) . 'px';
		$css['global'][avada_implode( $elements )]['line-height'] = round( intval( Avada()->settings->get( 'post_titles_extras_font_size' ) ) * 1.5 ) . 'px';
	}

	if ( Avada()->settings->get( 'h3_font_size' ) ) {

		$elements = array( '.post-content h3', '.project-content h3' );
		if ( class_exists( 'WooCommerce' ) ) {
			$elements[] = '.product-title';
		}
		$css['global'][avada_implode( $elements )]['font-size'] = intval( Avada()->settings->get( 'h3_font_size' ) ) . 'px';

		$elements = array( '.fusion-modal .modal-title' );
		if ( class_exists( 'WooCommerce' ) ) {
			$elements[] = 'p.demo_store';
		}
		$css['global'][avada_implode( $elements )]['font-size'] = intval( Avada()->settings->get( 'h3_font_size' ) ) . 'px';

	}

	if ( Avada()->settings->get( 'h3_font_lh' ) ) {

		$css['global'][avada_implode( $elements )]['line-height'] = round( intval( Avada()->settings->get( 'h3_font_lh' ) ) * 1.5 ) . 'px';

		$elements = array( '.post-content h3', '.project-content h3' );
		if ( class_exists( 'WooCommerce' ) ) {
			$elements[] = '.product-title';
		}
		$css['global'][avada_implode( $elements )]['line-height'] = intval( Avada()->settings->get( 'h3_font_lh' ) ) . 'px';

	}

	if ( Avada()->settings->get( 'h4_font_size' ) ) {

		$elements = array(
			'.post-content h4',
			'.fusion-portfolio-post .fusion-portfolio-content h4',
			'.fusion-rollover .fusion-rollover-content .fusion-rollover-title',
			'.fusion-person .person-author-wrapper .person-name',
			'.fusion-person .person-author-wrapper .person-title',
			'.fusion-carousel-title'
		);
		$css['global'][avada_implode( $elements )]['font-size'] = intval( Avada()->settings->get( 'h4_font_size' ) ) . 'px';

		$elements = array(
			'#wrapper .fusion-tabs-widget .tab-holder .tabs li a',
			'.person-author-wrapper',
			'#reviews #comments > h2',
			'.popover .popover-title',
			'.fusion-flip-box .flip-box-heading-back'
		);
		$css['global'][avada_implode( $elements )]['font-size'] = intval( Avada()->settings->get( 'h4_font_size' ) ) . 'px';

		$elements = array(
			'.fusion-accordian .panel-title',
			'.fusion-sharing-box h4',
			'.fusion-tabs .nav-tabs > li .fusion-tab-heading',
		);
		$css['global'][avada_implode( $elements )]['font-size'] = intval( Avada()->settings->get( 'h4_font_size' ) ) . 'px';

	}

	if ( Avada()->settings->get( 'h4_font_lh' ) ) {
		$css['global'][avada_implode( $elements )]['line-height'] = round( intval( Avada()->settings->get( 'h4_font_lh' ) ) * 1.5 ) . 'px';

		$elements = array(
			'.post-content h4',
			'.fusion-portfolio-post .fusion-portfolio-content h4',
			'.fusion-rollover .fusion-rollover-content .fusion-rollover-title',
			'.fusion-person .person-author-wrapper .person-name',
			'.fusion-person .person-author-wrapper .person-title',
			'.fusion-carousel-title'
		);
		$css['global'][avada_implode( $elements )]['line-height'] = intval( Avada()->settings->get( 'h4_font_lh' ) ) . 'px';

	}

	if ( Avada()->settings->get( 'h5_font_size' ) ) {
		$css['global']['.post-content h5']['font-size']   = intval( Avada()->settings->get( 'h5_font_size' ) ) . 'px';
	}

	if ( Avada()->settings->get( 'h5_font_lh' ) ) {
		$css['global']['.post-content h5']['line-height'] = intval( Avada()->settings->get( 'h5_font_lh' ) ) . 'px';
	}

	if ( Avada()->settings->get( 'h6_font_size' ) ) {
		$css['global']['.post-content h6']['font-size']   = intval( Avada()->settings->get( 'h6_font_size' ) ) . 'px';
	}

	if ( Avada()->settings->get( 'h6_font_lh' ) ) {
		$css['global']['.post-content h6']['line-height'] = intval( Avada()->settings->get( 'h6_font_lh' ) ) . 'px';
	}

	if ( Avada()->settings->get( 'es_title_font_size' ) ) {
		$css['global']['.ei-title h2']['font-size']   = intval( Avada()->settings->get( 'es_title_font_size' ) ) . 'px';
		$css['global']['.ei-title h2']['line-height'] = round( Avada()->settings->get( 'es_title_font_size' ) * 1.5 ) . 'px';
	}

	if ( Avada()->settings->get( 'es_caption_font_size' ) ) {
		$css['global']['.ei-title h3']['font-size']   = intval( Avada()->settings->get( 'es_caption_font_size' ) ) . 'px';
		$css['global']['.ei-title h3']['line-height'] = round( intval( Avada()->settings->get( 'es_caption_font_size' ) ) * 1.5 ) . 'px';
	}

	if ( Avada()->settings->get( 'meta_font_size' ) ) {

		$elements = array(
			'.fusion-image-wrapper .fusion-rollover .fusion-rollover-content .fusion-rollover-categories',
			'.fusion-image-wrapper .fusion-rollover .fusion-rollover-content .fusion-rollover-categories a',
			'.fusion-recent-posts .columns .column .meta',
			'.fusion-carousel-meta',
			'.fusion-single-line-meta'
		);
		$css['global'][avada_implode( $elements )]['font-size']   = intval( Avada()->settings->get( 'meta_font_size' ) ) . 'px';
		$css['global'][avada_implode( $elements )]['line-height'] = round( intval( Avada()->settings->get( 'meta_font_size' ) ) * 1.5 ) . 'px';

		$elements = array(
			'.fusion-meta',
			'.fusion-meta-info',
			'.fusion-recent-posts .columns .column .meta',
			'.post .single-line-meta',
			'.fusion-carousel-meta'
		);
		$css['global'][avada_implode( $elements )]['font-size'] = intval( Avada()->settings->get( 'meta_font_size' ) ) . 'px';

	}

	if ( Avada()->settings->get( 'woo_icon_font_size' ) ) {

		$elements = array(
			'.fusion-image-wrapper .fusion-rollover .fusion-rollover-content .fusion-product-buttons a',
			'.product-buttons a'
		);
		$css['global'][avada_implode( $elements )]['font-size']   = intval( Avada()->settings->get( 'woo_icon_font_size' ) ) . 'px';
		$css['global'][avada_implode( $elements )]['line-height'] = round( intval( Avada()->settings->get( 'woo_icon_font_size' ) ) * 1.5 ) . 'px';

	}

	if ( Avada()->settings->get( 'pagination_font_size' ) ) {

		$elements = array(
			'.pagination',
			'.page-links',
			'.pagination .pagination-next',
			'.pagination .pagination-prev',
		);
		if ( class_exists( 'WooCommerce' ) ) {
			$elements[] = '.woocommerce-pagination';
			$elements[] = '.woocommerce-pagination .next';
			$elements[] = '.woocommerce-pagination .prev';
		}
		$css['global'][avada_implode( $elements )]['font-size'] = intval( Avada()->settings->get( 'pagination_font_size' ) ) . 'px';

	}

	$elements = array(
		'body',
		'.post .post-content',
		'.post-content blockquote',
		'#wrapper .fusion-tabs-widget .tab-holder .news-list li .post-holder .meta',
		'.sidebar .jtwt',
		'#wrapper .meta',
		'.review blockquote div',
		'.search input',
		'.project-content .project-info h4',
		'.title-row',
		'.fusion-rollover .price .amount',
		'.quantity .qty',
		'.quantity .minus',
		'.quantity .plus',
		'.fusion-blog-timeline-layout .fusion-timeline-date',
		'#reviews #comments > h2',
		'.sidebar .widget_nav_menu li',
		'.sidebar .widget_categories li',
		'.sidebar .widget_meta li',
		'.sidebar .widget .recentcomments',
		'.sidebar .widget_recent_entries li',
		'.sidebar .widget_archive li',
		'.sidebar .widget_pages li',
		'.sidebar .widget_links li',
	);
	if ( class_exists( 'WooCommerce' ) ) {
		$elements[] = '.sidebar .widget_product_categories li';
		$elements[] = '.sidebar .widget_layered_nav li';
	}
	$css['global'][avada_implode( $elements )]['color'] = Avada_Sanitize::color( Avada()->settings->get( 'body_text_color' ), Avada()->settings->get_default( 'body_text_color' ) );

	$elements = array(
		'.post-content h1',
		'.title h1',
		'.fusion-post-content h1'
	);
	if ( class_exists( 'WooCommerce' ) ) {
		$elements[] = '.woocommerce-success-message .msg';
		$elements[] = '.woocommerce-message';
	}
	$css['global'][avada_implode( $elements )]['color'] = Avada_Sanitize::color( Avada()->settings->get( 'h1_color' ), Avada()->settings->get_default( 'h1_color' ) );

	$elements = array(
		'#main .post h2',
		'.post-content h2',
		'.fusion-title h2',
		'.title h2',
		'.search-page-search-form h2',
		'.cart-empty',
		'.fusion-post-content h2'
	);
	if ( class_exists( 'WooCommerce' ) ) {
		$elements[] = '.woocommerce-tabs h2';
		$elements[] = '.woocommerce h2';
		$elements[] = '.woocommerce .checkout h3';
	}
	$css['global'][avada_implode( $elements )]['color'] = Avada_Sanitize::color( Avada()->settings->get( 'h2_color' ), Avada()->settings->get_default( 'h2_color' ) );

	$elements = array(
		'.post-content h3',
		'.project-content h3',
		'.fusion-title h3',
		'.title h3',
		'.person-author-wrapper span',
		'.product-title',
		'.fusion-post-content h3'
	);
	$css['global'][avada_implode( $elements )]['color'] = Avada_Sanitize::color( Avada()->settings->get( 'h3_color' ), Avada()->settings->get_default( 'h3_color' ) );

	$elements = array(
		'.post-content h4',
		'.project-content .project-info h4',
		'.share-box h4',
		'.fusion-title h4',
		'.title h4',
		'.sidebar .widget h4',
		'#wrapper .fusion-tabs-widget .tab-holder .tabs li a',
		'.fusion-accordian .panel-title a',
		'.fusion-carousel-title',
		'.fusion-tabs .nav-tabs > li .fusion-tab-heading',
		'.fusion-post-content h4'
	);
	$css['global'][avada_implode( $elements )]['color'] = Avada_Sanitize::color( Avada()->settings->get( 'h4_color' ), Avada()->settings->get_default( 'h4_color' ) );

	$elements = array(
		'.post-content h5',
		'.fusion-title h5',
		'.title h5',
		'.fusion-post-content h5'
	);
	$css['global'][avada_implode( $elements )]['color'] = Avada_Sanitize::color( Avada()->settings->get( 'h5_color' ), Avada()->settings->get_default( 'h5_color' ) );

	$elements = array(
		'.post-content h6',
		'.fusion-title h6',
		'.title h6',
		'.fusion-post-content h6'
	);
	$css['global'][avada_implode( $elements )]['color'] = Avada_Sanitize::color( Avada()->settings->get( 'h6_color' ), Avada()->settings->get_default( 'h6_color' ) );

	$elements = array( '.fusion-page-title-bar h1', '.fusion-page-title-bar h3' );
	$css['global'][avada_implode( $elements )]['color'] = Avada_Sanitize::color( Avada()->settings->get( 'page_title_color' ), Avada()->settings->get_default( 'page_title_color' ) );

	$css['global']['.sep-boxed-pricing .panel-heading h3']['color'] = Avada_Sanitize::color( Avada()->settings->get( 'sep_pricing_box_heading_color' ), Avada()->settings->get_default( 'sep_pricing_box_heading_color' ) );

	$css['global']['.full-boxed-pricing.fusion-pricing-table .panel-heading h3']['color'] = Avada_Sanitize::color( Avada()->settings->get( 'full_boxed_pricing_box_heading_color' ), Avada()->settings->get_default( 'full_boxed_pricing_box_heading_color' ) );

	$elements = array(
		'body a',
		'body a:before',
		'body a:after',
		'.single-navigation a[rel="prev"]:before',
		'.single-navigation a[rel="next"]:after',
		'.project-content .project-info .project-info-box a',
		'.sidebar .widget li a',
		'.sidebar .widget .recentcomments',
		'.sidebar .widget_categories li',
		'#main .post h2 a',
		'.about-author .title a',
		'.shop_attributes tr th',
		'.fusion-rollover a',
		'.fusion-load-more-button'
	);
	if ( class_exists( 'WooCommerce' ) ) {
		$elements[] = '.fusion-woo-featured-products-slider .price .amount';
		$elements[] = 'z.my_account_orders thead tr th';
		$elements[] = '.shop_table thead tr th';
		$elements[] = '.cart_totals table th';
		$elements[] = '.checkout .shop_table tfoot th';
		$elements[] = '.checkout .payment_methods label';
		$elements[] = '#final-order-details .mini-order-details th';
		$elements[] = '#main .product .product_title';
		$elements[] = '.shop_table.order_details tr th';
		$elements[] = '.widget_layered_nav li.chosen a';
		$elements[] = '.widget_layered_nav li.chosen a:before';
		$elements[] = '.widget_layered_nav_filters li.chosen a';
		$elements[] = '.widget_layered_nav_filters li.chosen a:before';
	}
	$css['global'][avada_implode( $elements )]['color'] = Avada_Sanitize::color( Avada()->settings->get( 'link_color' ), Avada()->settings->get_default( 'link_color' ) );

	$css['global']['body #toTop:before']['color'] = '#fff';

	$elements = array(
		'.fusion-page-title-bar .fusion-breadcrumbs',
		'.fusion-page-title-bar .fusion-breadcrumbs a'
	);
	$css['global'][avada_implode( $elements )]['color'] = Avada_Sanitize::color( Avada()->settings->get( 'breadcrumbs_text_color' ), Avada()->settings->get_default( 'breadcrumbs_text_color' ) );

	$elements = array(
		'#slidingbar-area h3',
		'#slidingbar-area .widget-title'
	);
	$css['global'][avada_implode( $elements )]['color'] = Avada_Sanitize::color( Avada()->settings->get( 'slidingbar_headings_color' ), Avada()->settings->get_default( 'slidingbar_headings_color' ) );

	$elements = array(
		'#slidingbar-area',
		'#slidingbar-area .fusion-column',
		'#slidingbar-area .jtwt',
		'#slidingbar-area .jtwt .jtwt_tweet'
	);
	$css['global'][avada_implode( $elements )]['color'] = Avada_Sanitize::color( Avada()->settings->get( 'slidingbar_text_color' ), Avada()->settings->get_default( 'slidingbar_text_color' ) );

	$elements = array(
		'#slidingbar-area a',
		' #slidingbar-area .jtwt .jtwt_tweet a',
		'#wrapper #slidingbar-area .fusion-tabs-widget .tab-holder .tabs li a',
		'#slidingbar-area .fusion-accordian .panel-title a'
	);
	$css['global'][avada_implode( $elements )]['color'] = Avada_Sanitize::color( Avada()->settings->get( 'slidingbar_link_color' ), Avada()->settings->get_default( 'slidingbar_link_color' ) );

	$elements = array(
		'.sidebar .widget h4',
		'.sidebar .widget .heading h4'
	);
	$css['global'][avada_implode( $elements )]['color'] = Avada_Sanitize::color( Avada()->settings->get( 'sidebar_heading_color' ), Avada()->settings->get_default( 'sidebar_heading_color' ) );

	$elements = array(
		'.fusion-footer-widget-area h3',
		'.fusion-footer-widget-area .widget-title',
		'.fusion-footer-widget-column .product-title'
	);
	$css['global'][avada_implode( $elements )]['color'] = Avada_Sanitize::color( Avada()->settings->get( 'footer_headings_color' ), Avada()->settings->get_default( 'footer_headings_color' ) );

	$elements = array(
		'.fusion-footer-widget-area',
		'.fusion-footer-widget-area article.col',
		'.fusion-footer-widget-area .jtwt',
		'.fusion-footer-widget-area .jtwt .jtwt_tweet',
		'.fusion-copyright-notice'
	);
	$css['global'][avada_implode( $elements )]['color'] = Avada_Sanitize::color( Avada()->settings->get( 'footer_text_color' ), Avada()->settings->get_default( 'footer_text_color' ) );

	$elements = array(
		'.fusion-footer-widget-area a',
		'.fusion-footer-widget-area .jtwt .jtwt_tweet a',
		'#wrapper .fusion-footer-widget-area .fusion-tabs-widget .tab-holder .tabs li a',
		'.fusion-footer-widget-area .fusion-tabs-widget .tab-holder .news-list li .post-holder a',
		'.fusion-copyright-notice a',
		'.fusion-footer-widget-area .fusion-accordian .panel-title a'
	);
	$css['global'][avada_implode( $elements )]['color'] = Avada_Sanitize::color( Avada()->settings->get( 'footer_link_color' ), Avada()->settings->get_default( 'footer_link_color' ) );

	$css['global']['.ei-title h2']['color'] = Avada_Sanitize::color( Avada()->settings->get( 'es_title_color' ), Avada()->settings->get_default( 'es_title_color' ) );
	$css['global']['.ei-title h3']['color'] = Avada_Sanitize::color( Avada()->settings->get( 'es_caption_color' ), Avada()->settings->get_default( 'es_caption_color' ) );

	$elements = array(
		'.sep-single',
		'.sep-double',
		'.sep-dashed',
		'.sep-dotted',
		'.search-page-search-form',
		'.ls-avada',
		'.avada-skin-rev',
		'.es-carousel-wrapper.fusion-carousel-small .es-carousel ul li img',
		'.fusion-accordian .fusion-panel',
		'.progress-bar',
		'#small-nav',
		'.fusion-filters',
		'.single-navigation',
		'.project-content .project-info .project-info-box',
		'.post .fusion-meta-info',
		'.fusion-blog-layout-grid .post .post-wrapper',
		'.fusion-blog-layout-grid .post .fusion-content-sep',
		'.fusion-portfolio .fusion-portfolio-boxed .fusion-portfolio-post-wrapper',
		'.fusion-portfolio .fusion-portfolio-boxed .fusion-content-sep',
		'.fusion-portfolio-one .fusion-portfolio-boxed .fusion-portfolio-post-wrapper',
		'.fusion-blog-layout-grid .post .flexslider',
		'.fusion-layout-timeline .post',
		'.fusion-layout-timeline .post .fusion-content-sep',
		'.fusion-layout-timeline .post .flexslider',
		'.fusion-timeline-date',
		'.fusion-timeline-arrow',
		'.fusion-counters-box .fusion-counter-box .counter-box-border',
		'tr td',
		'.table',
		'.table > thead > tr > th',
		'.table > tbody > tr > th',
		'.table > tfoot > tr > th',
		'.table > thead > tr > td',
		'.table > tbody > tr > td',
		'.table > tfoot > tr > td',
		'.table-1 table',
		'.table-1 table th',
		'.table-1 tr td',
		'.tkt-slctr-tbl-wrap-dv table',
		'.tkt-slctr-tbl-wrap-dv tr td',
		'.table-2 table thead',
		'.table-2 tr td',
		'.sidebar .widget li a',
		'.sidebar .widget .recentcomments',
		'.sidebar .widget_categories li',
		'#wrapper .fusion-tabs-widget .tab-holder',
		'.commentlist .the-comment',
		'.side-nav',
		'#wrapper .side-nav li a',
		'h5.toggle.active + .toggle-content',
		'#wrapper .side-nav li.current_page_item li a',
		'.tabs-vertical .tabset',
		'.tabs-vertical .tabs-container .tab_content',
		'.fusion-tabs.vertical-tabs.clean .nav-tabs li .tab-link',
		'.pagination a.inactive',
		'.page-links a',
		'.fusion-author .fusion-author-social',
		'.side-nav li a',
		'.price_slider_wrapper',
		'.tagcloud a',
		'.sidebar .widget_nav_menu li',
		'.sidebar .widget_meta li',
		'.sidebar .widget_recent_entries li',
		'.sidebar .widget_archive li',
		'.sidebar .widget_pages li',
		'.sidebar .widget_links li',
		'#customer_login_box',
		'.chzn-container-single .chzn-single',
		'.chzn-container-single .chzn-single div',
		'.chzn-drop',
		'.input-radio',
		'.panel.entry-content',
		'.quantity',
		'.quantity .minus',
		'.quantity .qty',
		'#reviews li .comment-text',
		'#customer_login .col-1',
		'#customer_login .col-2',
		'#customer_login h2',
	);
	if ( is_rtl() ) {
		$elements[] = '.rtl .side-nav';
	}
	if ( class_exists( 'bbPress' ) ) {
		$elements[] = '.bbp-topic-pagination .page-numbers';
	}
	if ( class_exists( 'WooCommerce' ) ) {
		$elements[] = '.fusion-body .avada_myaccount_user';
		$elements[] = '.fusion-body .myaccount_user_container span';
		$elements[] = '.woocommerce-pagination .page-numbers';
		$elements[] = '.woo-tabs-horizontal .woocommerce-tabs > .tabs .active';
		$elements[] = '.woo-tabs-horizontal .woocommerce-tabs > .tabs';
		$elements[] = '.fusion-body .woocommerce-side-nav li a';
		$elements[] = '.fusion-body .woocommerce-content-box';
		$elements[] = '.fusion-body .woocommerce-content-box h2';
		$elements[] = '.fusion-body .woocommerce .address h4';
		$elements[] = '.fusion-body .woocommerce-tabs .tabs li a';
		$elements[] = '.fusion-body .woocommerce .social-share';
		$elements[] = '.fusion-body .woocommerce .social-share li';
		$elements[] = '.fusion-body .woocommerce-success-message';
		$elements[] = '.fusion-body .woocommerce .cross-sells';
		$elements[] = '.fusion-body .woocommerce-message';
		$elements[] = '.fusion-body .woocommerce .checkout #customer_details .col-1';
		$elements[] = '.fusion-body .woocommerce .checkout #customer_details .col-2';
		$elements[] = '.fusion-body .woocommerce .checkout h3';
		$elements[] = '.fusion-body .woocommerce .cross-sells h2';
		$elements[] = '.fusion-body .woocommerce .addresses .title';
		$elements[] = '.sidebar .widget_product_categories li';
		$elements[] = '.widget_product_categories li';
		$elements[] = '.widget_layered_nav li';
		$elements[] = '.sidebar .product_list_widget li';
		$elements[] = '.sidebar .widget_layered_nav li';
		$elements[] = '.fusion-body .my_account_orders tr';
		$elements[] = '.side-nav-left .side-nav';
		$elements[] = '.fusion-body .shop_table tr';
		$elements[] = '.fusion-body .cart_totals .total';
		$elements[] = '.fusion-body .checkout .shop_table tfoot';
		$elements[] = '.fusion-body .shop_attributes tr';
		$elements[] = '.fusion-body .cart-totals-buttons';
		$elements[] = '.fusion-body .cart_totals';
		$elements[] = '.fusion-body .shipping_calculator';
		$elements[] = '.fusion-body .coupon';
		$elements[] = '.fusion-body .cart_totals h2';
		$elements[] = '.fusion-body .shipping_calculator h2';
		$elements[] = '.fusion-body .coupon h2';
		$elements[] = '.fusion-body .order-total';
		$elements[] = '.fusion-body .woocommerce .cart-empty';
		$elements[] = '.fusion-body .woocommerce .return-to-shop';
		$elements[] = '.fusion-body .avada-order-details .shop_table.order_details tfoot';
		$elements[] = '#final-order-details .mini-order-details tr:last-child';
		$elements[] = '.fusion-body .order-info';
		if ( is_rtl() ) {
			$elements[] = '.rtl .woocommerce .social-share li';
		}
	}
	$css['global'][avada_implode( $elements )]['border-color'] = Avada_Sanitize::color( Avada()->settings->get( 'sep_color' ), Avada()->settings->get_default( 'sep_color' ) );

	$css['global']['.price_slider_wrapper .ui-widget-content']['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'sep_color' ), Avada()->settings->get_default( 'sep_color' ) );
	if ( class_exists( 'GFForms' ) ) {
		$css['global']['.gform_wrapper .gsection']['border-bottom'] = '1px dotted ' . Avada_Sanitize::color( Avada()->settings->get( 'sep_color' ), Avada()->settings->get_default( 'sep_color' ) );
	}

	$load_more_bg_color_rgb = fusion_hex2rgb( Avada()->settings->get( 'load_more_posts_button_bg_color' ) );
	$load_more_posts_button_bg_color_hover = 'rgba(' . $load_more_bg_color_rgb[0] . ',' . $load_more_bg_color_rgb[1] . ',' . $load_more_bg_color_rgb[2] . ',0.8)';

	$css['global']['.fusion-load-more-button']['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'load_more_posts_button_bg_color' ), Avada()->settings->get_default( 'load_more_posts_button_bg_color' ) );
	$css['global']['.fusion-load-more-button:hover']['background-color'] = Avada_Sanitize::color( $load_more_posts_button_bg_color_hover );

	$elements = array( '.quantity .minus', '.quantity .plus' );
	$css['global'][avada_implode( $elements )]['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'qty_bg_color' ), Avada()->settings->get_default( 'qty_bg_color' ) );

	$elements = array( '.quantity .minus:hover', '.quantity .plus:hover' );
	$css['global'][avada_implode( $elements )]['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'qty_bg_hover_color' ), Avada()->settings->get_default( 'qty_bg_hover_color' ) );

	$css['global']['.sb-toggle-wrapper .sb-toggle:after']['color'] = Avada_Sanitize::color( Avada()->settings->get( 'slidingbar_toggle_icon_color' ), Avada()->settings->get_default( 'slidingbar_toggle_icon_color' ) );

	$elements = array(
		'#slidingbar-area .widget_categories li a',
		'#slidingbar-area li.recentcomments',
		'#slidingbar-area ul li a',
		'#slidingbar-area .product_list_widget li',
		'#slidingbar-area .widget_recent_entries ul li'
	);
	$css['global'][avada_implode( $elements )]['border-bottom-color'] = Avada_Sanitize::color( Avada()->settings->get( 'slidingbar_toggle_icon_color' ), Avada()->settings->get_default( 'slidingbar_toggle_icon_color' ) );

	$elements = array(
		'#slidingbar-area .tagcloud a',
		'#wrapper #slidingbar-area .fusion-tabs-widget .tab-holder',
		'#wrapper #slidingbar-area .fusion-tabs-widget .tab-holder .news-list li',
		'#slidingbar-area .fusion-accordian .fusion-panel'
	);
	$css['global'][avada_implode( $elements )]['border-color'] = Avada_Sanitize::color( Avada()->settings->get( 'slidingbar_divider_color' ), Avada()->settings->get_default( 'slidingbar_divider_color' ) );

	$elements = array(
		'.fusion-footer-widget-area .widget_categories li a',
		'.fusion-footer-widget-area li.recentcomments',
		'.fusion-footer-widget-area ul li a',
		'.fusion-footer-widget-area .product_list_widget li',
		'.fusion-footer-widget-area .tagcloud a',
		'#wrapper .fusion-footer-widget-area .fusion-tabs-widget .tab-holder',
		'#wrapper .fusion-footer-widget-area .fusion-tabs-widget .tab-holder .news-list li',
		'.fusion-footer-widget-area .widget_recent_entries li',
		'.fusion-footer-widget-area .fusion-accordian .fusion-panel',
	);
	$css['global'][avada_implode( $elements )]['border-color'] = Avada_Sanitize::color( Avada()->settings->get( 'footer_divider_color' ), Avada()->settings->get_default( 'footer_divider_color' ) );

	$elements = array(
		'.input-text',
		'input[type="text"]',
		'textarea',
		'input.s',
		'#comment-input input',
		'#comment-textarea textarea',
		'.comment-form-comment textarea',
		'.post-password-form .password',
		'.main-nav-search-form input',
		'.search-page-search-form input',
		'.chzn-container-single .chzn-single',
		'.chzn-container .chzn-drop',
		'.avada-select-parent select',
		'.avada-select-parent .select-arrow',
		'#wrapper .select-arrow',
		'.avada-select .select2-container .select2-choice',
		'.avada-select .select2-container .select2-choice2',
		'select',
		'#wrapper .search-table .search-field input'
	);
	if ( defined( 'ICL_SITEPRESS_VERSION' || class_exists( 'SitePress' ) ) ) {
		$elements[] = '#lang_sel_click a.lang_sel_sel';
		$elements[] = '#lang_sel_click ul ul a';
		$elements[] = '#lang_sel_click ul ul a:visited';
		$elements[] = '#lang_sel_click a';
		$elements[] = '#lang_sel_click a:visited';
	}
	if ( class_exists( 'GFForms' ) ) {
		$elements[] = '.gform_wrapper .gfield input[type="text"]';
		$elements[] = '.gform_wrapper .gfield input[type="email"]';
		$elements[] = '.gform_wrapper .gfield textarea';
		$elements[] = '.gform_wrapper .gfield select';
	}
	if ( defined( 'WPCF7_PLUGIN' ) ) {
		$elements[] = '.wpcf7-form .wpcf7-text';
		$elements[] = '.wpcf7-form .wpcf7-quiz';
		$elements[] = '.wpcf7-form .wpcf7-number';
		$elements[] = '.wpcf7-form textarea';
		$elements[] = '.wpcf7-form .wpcf7-select';
		$elements[] = '.wpcf7-captchar';
		$elements[] = '.wpcf7-form .wpcf7-date';
	}
	if ( class_exists( 'bbPress' ) ) {
		$elements[] = '#bbpress-forums .bbp-search-form #bbp_search';
		$elements[] = '.bbp-reply-form input#bbp_topic_tags';
		$elements[] = '.bbp-topic-form input#bbp_topic_title';
		$elements[] = '.bbp-topic-form input#bbp_topic_tags';
		$elements[] = '.bbp-topic-form select#bbp_stick_topic_select';
		$elements[] = '.bbp-topic-form select#bbp_topic_status_select';
		$elements[] = '#bbpress-forums div.bbp-the-content-wrapper textarea.bbp-the-content';
		$elements[] = '.bbp-login-form input';
	}
	$css['global'][avada_implode( $elements )]['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'form_bg_color' ), Avada()->settings->get_default( 'form_bg_color' ) );

	$elements = array(
		'.input-text',
		'input[type="text"]',
		'textarea',
		'input.s',
		'input.s .placeholder',
		'#comment-input input',
		'#comment-textarea textarea',
		'#comment-input .placeholder',
		'#comment-textarea .placeholder',
		'.comment-form-comment textarea',
		'.post-password-form .password',
		'.avada-select .select2-container .select2-choice',
		'.avada-select .select2-container .select2-choice2',
		'select',
		'.main-nav-search-form input',
		'.search-page-search-form input',
		'.chzn-container-single .chzn-single',
		'.chzn-container .chzn-drop',
		'.avada-select-parent select',
		'#wrapper .search-table .search-field input'
	);
	if ( class_exists( 'GFForms' ) ) {
		$elements[] = '.gform_wrapper .gfield input[type="text"]';
		$elements[] = '.gform_wrapper .gfield input[type="email"]';
		$elements[] = '.gform_wrapper .gfield textarea';
		$elements[] = '.gform_wrapper .gfield select';
	}
	if ( defined( 'WPCF7_PLUGIN' ) ) {
		$elements[] = '.wpcf7-form .wpcf7-text';
		$elements[] = '.wpcf7-form .wpcf7-quiz';
		$elements[] = '.wpcf7-form .wpcf7-number';
		$elements[] = '.wpcf7-form textarea';
		$elements[] = '.wpcf7-form .wpcf7-select';
		$elements[] = '.wpcf7-select-parent .select-arrow';
		$elements[] = '.wpcf7-captchar';
		$elements[] = '.wpcf7-form .wpcf7-date';
	}
	if ( class_exists( 'bbPress' ) ) {
		$elements[] = '#bbpress-forums .bbp-search-form #bbp_search';
		$elements[] = '.bbp-reply-form input#bbp_topic_tags';
		$elements[] = '.bbp-topic-form input#bbp_topic_title';
		$elements[] = '.bbp-topic-form input#bbp_topic_tags';
		$elements[] = '.bbp-topic-form select#bbp_stick_topic_select';
		$elements[] = '.bbp-topic-form select#bbp_topic_status_select';
		$elements[] = '#bbpress-forums div.bbp-the-content-wrapper textarea.bbp-the-content';
		$elements[] = '.bbp-login-form input';
	}
	$css['global'][avada_implode( $elements )]['color'] = Avada_Sanitize::color( Avada()->settings->get( 'form_text_color' ), Avada()->settings->get_default( 'form_text_color' ) );

	$elements = array(
		'input#s::-webkit-input-placeholder',
		'#comment-input input::-webkit-input-placeholder',
		'.post-password-form .password::-webkit-input-placeholder',
		'#comment-textarea textarea::-webkit-input-placeholder',
		'.comment-form-comment textarea::-webkit-input-placeholder',
		'.input-text::-webkit-input-placeholder',

		'input#s:-moz-placeholder',
		'#comment-input input:-moz-placeholder',
		'.post-password-form .password::-moz-input-placeholder',
		'#comment-textarea textarea:-moz-placeholder',
		'.comment-form-comment textarea:-moz-placeholder',
		'.input-text:-moz-placeholder',

		'input#s:-ms-input-placeholder',
		'#comment-input input:-ms-input-placeholder',
		'.post-password-form .password::-ms-input-placeholder',
		'#comment-textarea textarea:-moz-placeholder',
		'.comment-form-comment textarea:-ms-input-placeholder',
		'.input-text:-ms-input-placeholder',
	);
	$css['global'][avada_implode( $elements )]['color'] = Avada_Sanitize::color( Avada()->settings->get( 'form_text_color' ), Avada()->settings->get_default( 'form_text_color' ) );

	$elements = array(
		'.input-text',
		'input[type="text"]',
		'textarea',
		'input.s',
		'#comment-input input',
		'#comment-textarea textarea',
		'.comment-form-comment textarea',
		'.post-password-form .password',
		'.gravity-select-parent .select-arrow',
		'.select-arrow',
		'.main-nav-search-form input',
		'.search-page-search-form input',
		'.chzn-container-single .chzn-single',
		'.chzn-container .chzn-drop',
		'.avada-select-parent select',
		'.avada-select-parent .select-arrow',
		'select',
		'#wrapper .search-table .search-field input',
		'.avada-select .select2-container .select2-choice',
		'.avada-select .select2-container .select2-choice .select2-arrow',
		'.avada-select .select2-container .select2-choice2 .select2-arrow',
	);
	if ( defined( 'ICL_SITEPRESS_VERSION' || class_exists( 'SitePress' ) ) ) {
		$elements[] = '#lang_sel_click a.lang_sel_sel';
		$elements[] = '#lang_sel_click ul ul a';
		$elements[] = '#lang_sel_click ul ul a:visited';
		$elements[] = '#lang_sel_click a';
		$elements[] = '#lang_sel_click a:visited';
	}
	if ( class_exists( 'GFForms' ) ) {
		$elements[] = '.gform_wrapper .gfield input[type="text"]';
		$elements[] = '.gform_wrapper .gfield input[type="email"]';
		$elements[] = '.gform_wrapper .gfield textarea';
		$elements[] = '.gform_wrapper .gfield_select[multiple=multiple]';
		$elements[] = '.gform_wrapper .gfield select';
	}
	if ( defined( 'WPCF7_PLUGIN' ) ) {
		$elements[] = '.wpcf7-form .wpcf7-text';
		$elements[] = '.wpcf7-form .wpcf7-quiz';
		$elements[] = '.wpcf7-form .wpcf7-number';
		$elements[] = '.wpcf7-form textarea';
		$elements[] = '.wpcf7-form .wpcf7-select';
		$elements[] = '.wpcf7-select-parent .select-arrow';
		$elements[] = '.wpcf7-captchar';
		$elements[] = '.wpcf7-form .wpcf7-date';
	}
	if ( class_exists( 'bbPress' ) ) {
		$elements[] = '#bbpress-forums .quicktags-toolbar';
		$elements[] = '#bbpress-forums .bbp-search-form #bbp_search';
		$elements[] = '.bbp-reply-form input#bbp_topic_tags';
		$elements[] = '.bbp-topic-form input#bbp_topic_title';
		$elements[] = '.bbp-topic-form input#bbp_topic_tags';
		$elements[] = '.bbp-topic-form select#bbp_stick_topic_select';
		$elements[] = '.bbp-topic-form select#bbp_topic_status_select';
		$elements[] = '#bbpress-forums div.bbp-the-content-wrapper textarea.bbp-the-content';
		$elements[] = '#wp-bbp_topic_content-editor-container';
		$elements[] = '#wp-bbp_reply_content-editor-container';
		$elements[] = '.bbp-login-form input';
		$elements[] = '#bbpress-forums .wp-editor-container';
		$elements[] = '#wp-bbp_topic_content-editor-container';
		$elements[] = '#wp-bbp_reply_content-editor-container';
	}
	if ( class_exists( 'WooCommerce' ) ) {
		$elements[] = '.woocommerce-checkout .select2-drop-active';
	}
	$css['global'][avada_implode( $elements )]['border-color'] = Avada_Sanitize::color( Avada()->settings->get( 'form_border_color' ), Avada()->settings->get_default( 'form_border_color' ) );

	$elements = array( '.select-arrow', '.select2-arrow' );
	$css['global'][avada_implode( $elements )]['color'] = Avada_Sanitize::color( Avada()->settings->get( 'form_border_color' ), Avada()->settings->get_default( 'form_border_color' ) );

	if ( Avada()->settings->get( 'avada_styles_dropdowns' ) ) {

		$css['global']['select']['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'form_border_color' ), Avada()->settings->get_default( 'form_border_color' ) );
		$css['global']['select']['color']            = Avada_Sanitize::color( Avada()->settings->get( 'form_text_color' ), Avada()->settings->get_default( 'form_text_color' ) );
		$css['global']['select']['border']           = '1px solid ' . Avada_Sanitize::color( Avada()->settings->get( 'form_border_color' ), Avada()->settings->get_default( 'form_border_color' ) );
		$css['global']['select']['font-size']        = '13px';
		$css['global']['select']['height']           = '35px';
		$css['global']['select']['text-indent']      = '5px';
		$css['global']['select']['width']            = '100%';

		$css['global']['select::-webkit-input-placeholder']['color'] = Avada_Sanitize::color( Avada()->settings->get( 'form_text_color' ), Avada()->settings->get_default( 'form_text_color' ) );
		$css['global']['select:-moz-placeholder']['color'] = Avada_Sanitize::color( Avada()->settings->get( 'form_text_color' ), Avada()->settings->get_default( 'form_text_color' ) );

	}

	if ( Avada()->settings->get( 'page_title_font_size' ) ) {
		$css['global']['.fusion-page-title-bar h1']['font-size']   = intval( Avada()->settings->get( 'page_title_font_size' ) ) . 'px';
	}
	$css['global']['.fusion-page-title-bar h1']['line-height']     = 'normal';

	if ( Avada()->settings->get( 'page_title_subheader_font_size' ) ) {
		$css['global']['.fusion-page-title-bar h3']['font-size']   = intval( Avada()->settings->get( 'page_title_subheader_font_size' ) ) . 'px';
		$css['global']['.fusion-page-title-bar h3']['line-height'] = intval( Avada()->settings->get( 'page_title_subheader_font_size' ) ) + 12 . 'px';
	}

	if ( Avada()->settings->get( 'content_width' ) ) {

		if ( false !== strpos( Avada()->settings->get( 'content_width' ), 'px' ) ) {

			$content_width = str_replace( 'px', '', Avada()->settings->get( 'content_width' ) );
			$content_unit  = 'px';
			$margin        = 100;

		} else {

			$content_width = str_replace( '%', '', Avada()->settings->get( 'content_width' ) );
			$content_unit  = '%';
			$margin        = 6;

		}

		$css['global']['#content']['width'] = Avada_Sanitize::size( $content_width - $margin . $content_unit );

	}

	if ( Avada()->settings->get( 'sidebar_width' ) ) {

		if ( false !== strpos( Avada()->settings->get( 'sidebar_width' ), 'px' ) ) {

			$sidebar_width = str_replace( 'px', '', Avada()->settings->get( 'sidebar_width' ) );
			$sidebar_unit  = 'px';

		} else {

			$sidebar_width = str_replace( '%', '', Avada()->settings->get( 'sidebar_width' ) );
			$sidebar_unit  = '%';

		}

		$css['global']['#main .sidebar']['width'] = Avada_Sanitize::size( $sidebar_width . $sidebar_unit );

	}

	if ( Avada()->settings->get( 'content_width_2' ) && Avada()->settings->get( 'sidebar_2_1_width' ) && Avada()->settings->get( 'sidebar_2_2_width' ) ) {

		if ( false !== strpos( Avada()->settings->get( 'content_width_2' ), 'px' ) ) {

			$content_width_2 = str_replace( 'px', '', Avada()->settings->get( 'content_width_2' ) );
			$content_2_unit  = 'px';
			$margin          = 100;

		} else {

			$content_width_2 = str_replace( '%', '', Avada()->settings->get( 'content_width_2' ) );
			$content_2_unit  = '%';
			$margin          = 6;

		}

		if ( false !== strpos( Avada()->settings->get( 'sidebar_2_1_width' ), 'px' ) ) {

			$sidebar_2_1_width = str_replace( 'px', '', Avada()->settings->get( 'sidebar_2_1_width' ) );
			$sidebar_2_1_unit  = 'px';

		} else {

			$sidebar_2_1_width = str_replace( '%', '', Avada()->settings->get( 'sidebar_2_1_width' ) );
			$sidebar_2_1_unit  = '%';

		}

		if ( false !== strpos( Avada()->settings->get( 'sidebar_2_2_width' ), 'px' ) ) {

			$sidebar_2_2_width = str_replace( 'px', '', Avada()->settings->get( 'sidebar_2_2_width' ) );
			$sidebar_2_2_unit  = 'px';

		} else {

			$sidebar_2_2_width = str_replace( '%', '', Avada()->settings->get( 'sidebar_2_2_width' ) );
			$sidebar_2_2_unit  = '%';

		}

	}

	if ( Avada()->settings->get( 'content_width_2' ) ) {

		$css['global']['.double-sidebars #content']['width']       = ( $content_width_2 - $margin ) . $content_2_unit;
		$css['global']['.double-sidebars #content']['margin-left'] = ( $sidebar_2_1_width + $margin / 2 ) . $content_2_unit;

	}

	if ( Avada()->settings->get( 'sidebar_2_1_width' ) ) {

		$css['global']['.double-sidebars #main #sidebar']['width']       = $sidebar_2_1_width . $sidebar_2_1_unit;
		$css['global']['.double-sidebars #main #sidebar']['margin-left'] = - ( $content_width_2 + $sidebar_2_1_width - $margin / 2 ) . $content_2_unit;

	}

	if ( Avada()->settings->get( 'sidebar_2_2_width' ) ) {

		$css['global']['.double-sidebars #main #sidebar-2']['width']       = $sidebar_2_2_width . $sidebar_2_2_unit;
		$css['global']['.double-sidebars #main #sidebar-2']['margin-left'] = ( $margin / 2 ) . $content_2_unit;

	}

	$css['global']['#main .sidebar']['background-color'] = Avada()->settings->get( 'sidebar_bg_color' );
	$css['global']['#main .sidebar']['padding']          = Avada_Sanitize::size( Avada()->settings->get( 'sidebar_padding' ) );

	$css['global']['.fusion-accordian .panel-title a .fa-fusion-box']['background-color'] = Avada()->settings->get( 'accordian_inactive_color' );

	$css['global']['.progress-bar-content']['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'counter_filled_color' ), Avada()->settings->get_default( 'counter_filled_color' ) );
	$css['global']['.progress-bar-content']['border-color']     = Avada_Sanitize::color( Avada()->settings->get( 'counter_filled_color' ), Avada()->settings->get_default( 'counter_filled_color' ) );

	$css['global']['.content-box-percentage']['color'] = Avada_Sanitize::color( Avada()->settings->get( 'counter_filled_color' ), Avada()->settings->get_default( 'counter_filled_color' ) );

	$css['global']['.progress-bar']['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'counter_unfilled_color' ), Avada()->settings->get_default( 'counter_unfilled_color' ) );
	$css['global']['.progress-bar']['border-color']     = Avada_Sanitize::color( Avada()->settings->get( 'counter_unfilled_color' ), Avada()->settings->get_default( 'counter_unfilled_color' ) );

	$css['global']['#wrapper .fusion-date-and-formats .fusion-format-box']['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'dates_box_color' ), Avada()->settings->get_default( 'dates_box_color' ) );

	$elements = array(
		'.fusion-carousel .fusion-carousel-nav .fusion-nav-prev',
		'.fusion-carousel .fusion-carousel-nav .fusion-nav-next',
	);
	$css['global'][avada_implode( $elements )]['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'carousel_nav_color' ), Avada()->settings->get_default( 'carousel_nav_color' ) );

	$elements = avada_map_selector( $elements, ':hover' );
	$css['global'][avada_implode( $elements )]['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'carousel_hover_color' ), Avada()->settings->get_default( 'carousel_hover_color' ) );

	$elements = array(
		'.fusion-flexslider .flex-direction-nav .flex-prev',
		'.fusion-flexslider .flex-direction-nav .flex-next',
	);
	$css['global'][avada_implode( $elements )]['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'carousel_nav_color' ), Avada()->settings->get_default( 'carousel_nav_color' ) );

	$elements = avada_map_selector( $elements, ':hover' );
	$css['global'][avada_implode( $elements )]['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'carousel_hover_color' ), Avada()->settings->get_default( 'carousel_hover_color' ) );

	$css['global']['.content-boxes .col']['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'content_box_bg_color' ), Avada()->settings->get_default( 'content_box_bg_color' ) );

	$css['global']['#wrapper .sidebar .fusion-tabs-widget .tabs-container']['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'tabs_bg_color' ), Avada()->settings->get_default( 'tabs_bg_color' ) );
	$css['global']['body .sidebar .fusion-tabs-widget .tab-hold .tabs li']['border-right'] = '1px solid ' . Avada_Sanitize::color( Avada()->settings->get( 'tabs_bg_color' ), Avada()->settings->get_default( 'tabs_bg_color' ) );
	if ( is_rtl() ) {
		$css['global']['body.rtl #wrapper .sidebar .fusion-tabs-widget .tab-hold .tabset li']['border-left-color'] = Avada_Sanitize::color( Avada()->settings->get( 'tabs_bg_color' ), Avada()->settings->get_default( 'tabs_bg_color' ) );
	}

	$elements = array(
		'body .sidebar .fusion-tabs-widget .tab-holder .tabs li a',
		'.sidebar .fusion-tabs-widget .tab-holder .tabs li a',
	);
	$css['global'][avada_implode( $elements )]['background']    = Avada_Sanitize::color( Avada()->settings->get( 'tabs_inactive_color' ), Avada()->settings->get_default( 'tabs_inactive_color' ) );
	$css['global'][avada_implode( $elements )]['border-bottom'] = '0';
	$css['global'][avada_implode( $elements )]['color']         = Avada_Sanitize::color( Avada()->settings->get( 'body_text_color' ), Avada()->settings->get_default( 'body_text_color' ) );

	$css['global']['body .sidebar .fusion-tabs-widget .tab-hold .tabs li a:hover']['background']    = Avada_Sanitize::color( Avada()->settings->get( 'tabs_bg_color' ), Avada()->settings->get_default( 'tabs_bg_color' ) );
	$css['global']['body .sidebar .fusion-tabs-widget .tab-hold .tabs li a:hover']['border-bottom'] = '0';

	$elements = array(
		'body .sidebar .fusion-tabs-widget .tab-hold .tabs li.active a',
		'body .sidebar .fusion-tabs-widget .tab-holder .tabs li.active a'
	);
	$css['global'][avada_implode( $elements )]['background']       = Avada_Sanitize::color( Avada()->settings->get( 'tabs_bg_color' ), Avada()->settings->get_default( 'tabs_bg_color' ) );
	$css['global'][avada_implode( $elements )]['border-bottom']    = '0';
	$css['global'][avada_implode( $elements )]['border-top-color'] = Avada_Sanitize::color( Avada()->settings->get( 'primary_color' ), Avada()->settings->get_default( 'primary_color' ) );

	$elements = array(
		'#wrapper .sidebar .fusion-tabs-widget .tab-holder',
		'.sidebar .fusion-tabs-widget .tab-holder .news-list li',
	);
	$css['global'][avada_implode( $elements )]['border-color'] = Avada_Sanitize::color( Avada()->settings->get( 'tabs_border_color' ), Avada()->settings->get_default( 'tabs_border_color' ) );

	$css['global']['.fusion-single-sharing-box']['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'social_bg_color' ), Avada()->settings->get_default( 'social_bg_color' ) );
	if ( Avada()->settings->get( 'social_bg_color' ) == 'transparent' ) {
		$css['global']['.fusion-single-sharing-box']['padding'] = '0';
	}

	$elements = array(
		'.fusion-blog-layout-grid .post .fusion-post-wrapper',
		'.fusion-blog-layout-timeline .post',
		'.fusion-portfolio.fusion-portfolio-boxed .fusion-portfolio-content-wrapper',
		'.products li.product'
	);
	$css['global'][avada_implode( $elements )]['background-color'] = Avada()->settings->get( 'timeline_bg_color' ) ? Avada_Sanitize::color( Avada()->settings->get( 'timeline_bg_color' ) ) : 'transparent';

	$elements = array(
		'.fusion-blog-layout-grid .post .flexslider',
		'.fusion-blog-layout-grid .post .fusion-post-wrapper',
		'.fusion-blog-layout-grid .post .fusion-content-sep',
		'.products li',
		'.product-details-container',
		'.product-buttons',
		'.product-buttons-container',
		'.product .product-buttons',
		'.fusion-blog-layout-timeline .fusion-timeline-line',
		'.fusion-blog-timeline-layout .post',
		'.fusion-blog-timeline-layout .post .fusion-content-sep',
		'.fusion-blog-timeline-layout .post .flexslider',
		'.fusion-blog-layout-timeline .post',
		'.fusion-blog-layout-timeline .post .fusion-content-sep',
		'.fusion-portfolio.fusion-portfolio-boxed .fusion-portfolio-content-wrapper',
		'.fusion-portfolio.fusion-portfolio-boxed .fusion-content-sep',
		'.fusion-blog-layout-timeline .post .flexslider',
		'.fusion-blog-layout-timeline .fusion-timeline-date'
	);
	$css['global'][avada_implode( $elements )]['border-color'] = Avada_Sanitize::color( Avada()->settings->get( 'timeline_color' ), Avada()->settings->get_default( 'timeline_color' ) );

	$elements = array(
		'.fusion-blog-layout-timeline .fusion-timeline-circle',
		'.fusion-blog-layout-timeline .fusion-timeline-date',
		'.fusion-blog-timeline-layout .fusion-timeline-circle',
		'.fusion-blog-timeline-layout .fusion-timeline-date'
	);
	$css['global'][avada_implode( $elements )]['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'timeline_color' ), Avada()->settings->get_default( 'timeline_color' ) );

	$elements = array(
		'.fusion-timeline-icon',
		'.fusion-timeline-arrow:before',
		'.fusion-blog-timeline-layout .fusion-timeline-icon',
		'.fusion-blog-timeline-layout .fusion-timeline-arrow:before'
	);
	$css['global'][avada_implode( $elements )]['color'] = Avada_Sanitize::color( Avada()->settings->get( 'timeline_color' ), Avada()->settings->get_default( 'timeline_color' ) );

	$elements = array(
		'div.indicator-hint'
	);
	if ( class_exists( 'bbPress' ) ) {
		$elements[] = '#bbpress-forums li.bbp-header';
		$elements[] = '#bbpress-forums div.bbp-reply-header';
		$elements[] = '#bbpress-forums #bbp-single-user-details #bbp-user-navigation li.current a';
		$elements[] = 'div.bbp-template-notice';

	}
	$css['global'][avada_implode( $elements )]['background'] = Avada_Sanitize::color( Avada()->settings->get( 'bbp_forum_header_bg' ), Avada()->settings->get_default( 'bbp_forum_header_bg' ) );

	if ( class_exists( 'bbPress' ) ) {
		$css['global']['#bbpress-forums .bbp-replies div.even']['background'] = 'transparent';
	}
	$elements = array( 'div.indicator-hint' );
	if ( class_exists( 'bbPress' ) ) {
		$elements[] = '#bbpress-forums ul.bbp-lead-topic';
		$elements[] = '#bbpress-forums ul.bbp-topics';
		$elements[] = '#bbpress-forums ul.bbp-forums';
		$elements[] = '#bbpress-forums ul.bbp-replies';
		$elements[] = '#bbpress-forums ul.bbp-search-results';
		$elements[] = '#bbpress-forums li.bbp-body ul.forum';
		$elements[] = '#bbpress-forums li.bbp-body ul.topic';
		$elements[] = '#bbpress-forums div.bbp-reply-content';
		$elements[] = '#bbpress-forums div.bbp-reply-header';
		$elements[] = '#bbpress-forums div.bbp-reply-author .bbp-reply-post-date';
		$elements[] = '#bbpress-forums div.bbp-topic-tags a';
		$elements[] = '#bbpress-forums #bbp-single-user-details';
		$elements[] = 'div.bbp-template-notice';
		$elements[] = '.bbp-arrow';
	}
	$css['global'][avada_implode( $elements )]['border-color'] = Avada_Sanitize::color( Avada()->settings->get( 'bbp_forum_border_color' ), Avada()->settings->get_default( 'bbp_forum_border_color' ) );

	if ( 'Dark' == Avada()->settings->get( 'scheme_type' ) ) {

		$css['global']['.fusion-rollover .price .amount']['color'] = '#333333';
		$css['global']['.meta li']['border-color']   = Avada_Sanitize::color( Avada()->settings->get( 'body_text_color' ), Avada()->settings->get_default( 'body_text_color' ) );
		$css['global']['.error_page .oops']['color'] = '#2F2F30';

		if ( class_exists( 'bbPress' ) ) {
			$elements = array( '.bbp-arrow', '#bbpress-forums .quicktags-toolbar' );
			$css['global'][avada_implode( $elements )]['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'content_bg_color' ), Avada()->settings->get_default( 'content_bg_color' ) );
		}

		$css['global']['#toTop']['background-color'] = '#111111';

		$css['global']['.chzn-container-single .chzn-single']['background-image'] = 'none';
		$css['global']['.chzn-container-single .chzn-single']['box-shadow']       = 'none';

		$elements = array( '.catalog-ordering a', '.order-dropdown > li:after', '.order-dropdown ul li a' );
		$css['global'][avada_implode( $elements )]['color'] = Avada_Sanitize::color( Avada()->settings->get( 'form_text_color' ), Avada()->settings->get_default( 'form_text_color' ) );

		$elements = array(
			'.order-dropdown li',
			'.order-dropdown .current-li',
			'.order-dropdown > li:after',
			'.order-dropdown ul li a',
			'.catalog-ordering .order li a',
			'.order-dropdown li',
			'.order-dropdown .current-li',
			'.order-dropdown ul',
			'.order-dropdown ul li a',
			'.catalog-ordering .order li a'
		);
		$css['global'][avada_implode( $elements )]['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'form_bg_color' ), Avada()->settings->get_default( 'form_bg_color' ) );

		$elements = array(
			'.order-dropdown li:hover',
			'.order-dropdown .current-li:hover',
			'.order-dropdown ul li a:hover',
			'.catalog-ordering .order li a:hover'
		);
		$css['global'][avada_implode( $elements )]['background-color'] = '#29292A';

		if ( class_exists( 'bbPress' ) ) {

			$elements = array(
				'.bbp-topics-front ul.super-sticky',
				'.bbp-topics ul.super-sticky',
				'.bbp-topics ul.sticky',
				'.bbp-forum-content ul.sticky'
			);
			$css['global'][avada_implode( $elements )]['background-color'] = '#3E3E3E';

			$elements = array(
				'.bbp-topics-front ul.super-sticky a',
				'.bbp-topics ul.super-sticky a',
				'.bbp-topics ul.sticky a',
				'.bbp-forum-content ul.sticky a'
			);
			$css['global'][avada_implode( $elements )]['color'] = '#FFFFFF';

		}

		$elements = array(
			'.pagination-prev:before',
			'.pagination-next:after',
		);
		if ( class_exists( 'WooCommerce' ) ) {
			$elements[] = '.woocommerce-pagination .prev:before';
			$elements[] = '.woocommerce-pagination .next:after';
		}
		$css['global'][avada_implode( $elements )]['color'] = '#747474';

		$elements = array( '.table-1 table', '.tkt-slctr-tbl-wrap-dv table' );
		$css['global'][avada_implode( $elements )]['background-color']   = '#313132';
		$css['global'][avada_implode( $elements )]['box-shadow']         = '0 1px 3px rgba(0, 0, 0, 0.08), inset 0 0 0 1px rgba(62, 62, 62, 0.5)';

		$elements = array(
			'.table-1 table th',
			'.tkt-slctr-tbl-wrap-dv table th',
			'.table-1 tbody tr:nth-child(2n)',
			'.tkt-slctr-tbl-wrap-dv tbody tr:nth-child(2n)'
		);
		$css['global'][avada_implode( $elements )]['background-color'] = '#212122';

	}

	if ( Avada()->settings->get( 'blog_grid_column_spacing' ) || '0' === Avada()->settings->get( 'blog_grid_column_spacing' ) ) {

		$css['global']['#posts-container.fusion-blog-layout-grid']['margin'] = '-' . intval( Avada()->settings->get( 'blog_grid_column_spacing' ) / 2 ) . 'px -' . intval( Avada()->settings->get( 'blog_grid_column_spacing' ) / 2 ) . 'px 0 -' . intval( Avada()->settings->get( 'blog_grid_column_spacing' ) / 2 ) . 'px';

		$css['global']['#posts-container.fusion-blog-layout-grid .fusion-post-grid']['padding'] = intval( Avada()->settings->get( 'blog_grid_column_spacing' ) / 2 ) . 'px';

	}

	$css['global']['.quicktags-toolbar input']['background'][]     = 'linear-gradient(to top, ' . Avada_Sanitize::color( Avada()->settings->get( 'content_bg_color' ), Avada()->settings->get_default( 'content_bg_color' ) ) . ', ' . Avada_Sanitize::color( Avada()->settings->get( 'form_bg_color' ), Avada()->settings->get_default( 'form_bg_color' ) ) . ' ) #3E3E3E';
	$css['global']['.quicktags-toolbar input']['background-image'] = '-webkit-gradient( linear, left top, left bottom, color-stop(0, ' . Avada_Sanitize::color( Avada()->settings->get( 'form_bg_color' ), Avada()->settings->get_default( 'form_bg_color' ) ) . '), color-stop(1, ' . Avada_Sanitize::color( Avada()->settings->get( 'content_bg_color' ), Avada()->settings->get_default( 'content_bg_color' ) ) . '))';
	$css['global']['.quicktags-toolbar input']['filter']           = 'progid:DXImageTransform.Microsoft.gradient(startColorstr=' . Avada_Sanitize::color( Avada()->settings->get( 'form_bg_color' ), Avada()->settings->get_default( 'form_bg_color' ) ) . ', endColorstr=' . Avada_Sanitize::color( Avada()->settings->get( 'content_bg_color' ), Avada()->settings->get_default( 'content_bg_color' ) ) . '), progid: DXImageTransform.Microsoft.Alpha(Opacity=0)';
	$css['global']['.quicktags-toolbar input']['border']           = '1px solid ' . Avada_Sanitize::color( Avada()->settings->get( 'form_border_color' ), Avada()->settings->get_default( 'form_border_color' ) );
	$css['global']['.quicktags-toolbar input']['color']            = Avada_Sanitize::color( Avada()->settings->get( 'form_text_color' ), Avada()->settings->get_default( 'form_text_color' ) );

	$css['global']['.quicktags-toolbar input:hover']['background'] = Avada_Sanitize::color( Avada()->settings->get( 'form_bg_color' ), Avada()->settings->get_default( 'form_bg_color' ) );

	if ( ! Avada()->settings->get( 'breadcrumb_mobile' ) ) {
		if ( Avada()->settings->get( 'responsive' ) ) {
			$media_query = '@media only screen and (max-width: ' . ( 940 + (int) Avada()->settings->get( 'side_header_width' ) ) . 'px)';
			$css[$media_query]['	.fusion-body .fusion-page-title-bar .fusion-breadcrumbs']['display'] = 'none';

			$media_query = '@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) and (orientation: portrait)';
			$css[$media_query]['.fusion-body .fusion-page-title-bar .fusion-breadcrumbs']['display'] = 'none';
		}
	}

	if ( ! Avada()->settings->get( 'image_rollover' ) ) {
		$css['global']['.fusion-rollover']['display'] = 'none';
	}

	if ( Avada()->settings->get( 'image_rollover_direction' ) != 'left' ) {

		switch ( Avada()->settings->get( 'image_rollover_direction' ) ) {

			case 'right' :
				$image_rollover_direction_value       = 'translateX(100%)';
				$image_rollover_direction_hover_value = '';
				break;
			case 'bottom' :
				$image_rollover_direction_value       = 'translateY(100%)';
				$image_rollover_direction_hover_value = 'translateY(0%)';
				break;
			case 'top' :
				$image_rollover_direction_value       = 'translateY(-100%)';
				$image_rollover_direction_hover_value = 'translateY(0%)';
				break;
			case 'center_horiz' :
				$image_rollover_direction_value       = 'scaleX(0)';
				$image_rollover_direction_hover_value = 'scaleX(1)';
				break;
			case 'center_vertical' :
				$image_rollover_direction_value       = 'scaleY(0)';
				$image_rollover_direction_hover_value = 'scaleY(1)';
				break;
		}

		$css['global']['.fusion-image-wrapper .fusion-rollover']['transform'] = $image_rollover_direction_value;

		if ( '' != $image_rollover_direction_hover_value ) {
			$css['global']['.fusion-image-wrapper:hover .fusion-rollover']['transform'] = $image_rollover_direction_hover_value;
		}

	}

	$css['global']['.ei-slider']['width']  = Avada_Sanitize::size( Avada()->settings->get( 'tfes_slider_width' ) );
	$css['global']['.ei-slider']['height'] = Avada_Sanitize::size( Avada()->settings->get( 'tfes_slider_height' ) );

	/**
	 * Buttons
	 */

	$elements = array(
		'.button.default',
		'.fusion-button.fusion-button-default',
		'#comment-submit',
		'#reviews input#submit',
		'.ticket-selector-submit-btn[type="submit"]',
	);
	if ( class_exists( 'GFForms' ) ) {
		$elements[] = '.gform_wrapper .gform_button';
		$elements[] = '.gform_wrapper .button';
		$elements[] = '.gform_page_footer input[type="button"]';
	}
	if ( defined( 'WPCF7_PLUGIN' ) ) {
		$elements[] = '.wpcf7-form input[type="submit"]';
		$elements[] = '.wpcf7-submit';
	}
	if ( class_exists( 'bbPress' ) ) {
		$elements[] = '.bbp-submit-wrapper button';
		$elements[] = '.bbp-submit-wrapper .button';
		$elements[] = '#bbp_user_edit_submit';
	}
	if ( class_exists( 'WooCommerce' ) ) {
		$elements[] = '.woocommerce .checkout #place_order';
		$elements[] = '.woocommerce .single_add_to_cart_button';
		$elements[] = '.woocommerce button.button';
		$elements[] = '.woocommerce .login .button';
		$elements[] = '.woocommerce .register .button';
	}
	$css['global'][avada_implode( $elements )]['border-color'] = Avada_Sanitize::color( Avada()->settings->get( 'button_accent_color' ), Avada()->settings->get_default( 'button_accent_color' ) );

	$elements = avada_map_selector( $elements, ':hover' );
	$css['global'][avada_implode( $elements )]['border-color'] = Avada_Sanitize::color( Avada()->settings->get( 'button_accent_hover_color' ), Avada()->settings->get_default( 'button_accent_hover_color' ) );

	$button_size = strtolower( esc_attr( Avada()->settings->get( 'button_size' ) ) );

	$elements = array(
		'.button.default',
		'.fusion-button-default',
	);
	if ( class_exists( 'GFForms' ) ) {
		$elements[] = '.fusion-body #main .gform_wrapper .gform_button';
		$elements[] = '.fusion-body #main .gform_wrapper .button';
		$elements[] = '.fusion-body #main .gform_wrapper .gform_footer .gform_button';
	}
	if ( defined( 'WPCF7_PLUGIN' ) ) {
		$elements[] = '.wpcf7-form input[type="submit"]';
		$elements[] = '.wpcf7-submit';
	}
	if ( class_exists( 'WooCommerce' ) ) {
		$elements[] = '.woocommerce .checkout #place_order';
	}

	switch ( $button_size ) {

		case 'small' :
			$css['global'][avada_implode( $elements )]['padding']     = '9px 20px';
			$css['global'][avada_implode( $elements )]['line-height'] = '14px';
			$css['global'][avada_implode( $elements )]['font-size']   = '12px';
			if ( '3d' == Avada()->settings->get( 'button_type' ) ) {
				$css['global'][avada_implode( $elements )]['box-shadow'] = 'inset 0px 1px 0px #ffffff, 0px 2px 0px ' . Avada_Sanitize::color( Avada()->settings->get( 'button_bevel_color' ), Avada()->settings->get_default( 'button_bevel_color' ) ) . ', 1px 4px 4px 2px rgba(0, 0, 0, 0.3)';
			}
			break;

		case 'medium' :
			$css['global'][avada_implode( $elements )]['padding']     = '11px 23px';
			$css['global'][avada_implode( $elements )]['line-height'] = '16px';
			$css['global'][avada_implode( $elements )]['font-size']   = '13px';
			if ( '3d' == Avada()->settings->get( 'button_type' ) ) {
				$css['global'][avada_implode( $elements )]['box-shadow'] = 'inset 0px 1px 0px #ffffff, 0px 3px 0px ' . Avada_Sanitize::color( Avada()->settings->get( 'button_bevel_color' ), Avada()->settings->get_default( 'button_bevel_color' ) ) . ', 1px 5px 5px 3px rgba(0, 0, 0, 0.3)';
			}
			break;

		case 'large' :
			$css['global'][avada_implode( $elements )]['padding']     = '13px 29px';
			$css['global'][avada_implode( $elements )]['line-height'] = '17px';
			$css['global'][avada_implode( $elements )]['font-size']   = '14px';
			if ( '3d' == Avada()->settings->get( 'button_type' ) ) {
				$css['global'][avada_implode( $elements )]['box-shadow'] = 'inset 0px 1px 0px #ffffff, 0px 4px 0px ' . Avada_Sanitize::color( Avada()->settings->get( 'button_bevel_color' ), Avada()->settings->get_default( 'button_bevel_color' ) ) . ', 1px 6px 6px 3px rgba(0, 0, 0, 0.3)';
			}
			break;

		case 'xlarge' :
			$css['global'][avada_implode( $elements )]['padding']     = '17px 40px';
			$css['global'][avada_implode( $elements )]['line-height'] = '21px';
			$css['global'][avada_implode( $elements )]['font-size']   = '18px';
			if ( '3d' == Avada()->settings->get( 'button_type' ) ) {
				$css['global'][avada_implode( $elements )]['box-shadow'] = 'inset 0px 1px 0px #ffffff, 0px 5px 0px ' . Avada_Sanitize::color( Avada()->settings->get( 'button_bevel_color' ), Avada()->settings->get_default( 'button_bevel_color' ) ) . ', 1px 7px 7px 3px rgba(0, 0, 0, 0.3)';
			}
			break;
		default : // Fallback to medium
			$css['global'][avada_implode( $elements )]['padding']     = '11px 23px';
			$css['global'][avada_implode( $elements )]['line-height'] = '16px';
			$css['global'][avada_implode( $elements )]['font-size']   = '13px';
			if ( '3d' == Avada()->settings->get( 'button_type' ) ) {
				$css['global'][avada_implode( $elements )]['box-shadow'] = 'inset 0px 1px 0px #ffffff, 0px 3px 0px ' . Avada_Sanitize::color( Avada()->settings->get( 'button_bevel_color' ), Avada()->settings->get_default( 'button_bevel_color' ) ) . ', 1px 5px 5px 3px rgba(0, 0, 0, 0.3)';
			}

	}

	$elements = array(
		'.button.default.button-3d.button-small',
		'.fusion-button.button-small.button-3d',
		'.ticket-selector-submit-btn[type="submit"]',
		'.fusion-button.fusion-button-3d.fusion-button-small'
	);
	$css['global'][avada_implode( $elements )]['box-shadow'] = 'inset 0px 1px 0px #ffffff, 0px 2px 0px ' . Avada_Sanitize::color( Avada()->settings->get( 'button_bevel_color' ), Avada()->settings->get_default( 'button_bevel_color' ) ) . ', 1px 4px 4px 2px rgba(0, 0, 0, 0.3)';

	$elements = avada_map_selector( $elements, ':active' );
	$css['global'][avada_implode( $elements )]['box-shadow'] = 'inset 0px 1px 0px #ffffff, 0px 1px 0px ' . Avada_Sanitize::color( Avada()->settings->get( 'button_bevel_color' ), Avada()->settings->get_default( 'button_bevel_color' ) ) . ', 1px 4px 4px 2px rgba(0, 0, 0, 0.3)';

	$elements = array(
		'.button.default.button-3d.button-medium',
		'.fusion-button.button-medium.button-3d',
		'.fusion-button.fusion-button-3d.fusion-button-medium'
	);
	$css['global'][avada_implode( $elements )]['box-shadow'] = 'inset 0px 1px 0px #ffffff, 0px 3px 0px ' . Avada_Sanitize::color( Avada()->settings->get( 'button_bevel_color' ), Avada()->settings->get_default( 'button_bevel_color' ) ) . ', 1px 5px 5px 3px rgba(0, 0, 0, 0.3)';

	$elements = avada_map_selector( $elements, ':active' );
	$css['global'][avada_implode( $elements )]['box-shadow'] = 'inset 0px 1px 0px #ffffff, 0px 1px 0px ' . Avada_Sanitize::color( Avada()->settings->get( 'button_bevel_color' ), Avada()->settings->get_default( 'button_bevel_color' ) ) . ', 1px 5px 5px 3px rgba(0, 0, 0, 0.3)';

	$elements = array(
		'.button.default.button-3d.button-large',
		'.fusion-button.button-large.button-3d',
		'.fusion-button.fusion-button-3d.fusion-button-large'
	);
	$css['global'][avada_implode( $elements )]['box-shadow'] = 'inset 0px 1px 0px #ffffff, 0px 4px 0px ' . Avada_Sanitize::color( Avada()->settings->get( 'button_bevel_color' ), Avada()->settings->get_default( 'button_bevel_color' ) ) . ', 1px 5px 6px 3px rgba(0, 0, 0, 0.3)';

	$elements = avada_map_selector( $elements, ':active' );
	$css['global'][avada_implode( $elements )]['box-shadow'] = 'inset 0px 1px 0px #ffffff, 0px 1px 0px ' . Avada_Sanitize::color( Avada()->settings->get( 'button_bevel_color' ), Avada()->settings->get_default( 'button_bevel_color' ) ) . ', 1px 6px 6px 3px rgba(0, 0, 0, 0.3)';

	$elements = array(
		'.button.default.button-3d.button-xlarge',
		'.fusion-button.button-xlarge.button-3d',
		'.fusion-button.fusion-button-3d.fusion-button-xlarge'
	);
	$css['global'][avada_implode( $elements )]['box-shadow'] = 'inset 0px 1px 0px #ffffff, 0px 5px 0px ' . Avada_Sanitize::color( Avada()->settings->get( 'button_bevel_color' ), Avada()->settings->get_default( 'button_bevel_color' ) ) . ', 1px 7px 7px 3px rgba(0, 0, 0, 0.3)';

	$elements = avada_map_selector( $elements, ':active' );
	$css['global'][avada_implode( $elements )]['box-shadow'] = 'inset 0px 1px 0px #ffffff, 0px 2px 0px ' . Avada_Sanitize::color( Avada()->settings->get( 'button_bevel_color' ), Avada()->settings->get_default( 'button_bevel_color' ) ) . ', 1px 7px 7px 3px rgba(0, 0, 0, 0.3)';

	if ( '3d' == Avada()->settings->get( 'button_type' ) ) {

		$elements = array(
			'.button.default.small',
			'.fusion-button.fusion-button-default.fusion-button-small',
			'#reviews input#submit',
			'.ticket-selector-submit-btn[type="submit"]',
		);
		if ( class_exists( 'GFForms' ) ) {
			$elements[] = '.gform_page_footer input[type="button"]';
			$elements[] = '.gform_wrapper .gform_button';
			$elements[] = '.gform_wrapper .button';
		}
		if ( defined( 'WPCF7_PLUGIN' ) ) {
			$elements[] = '.wpcf7-form input[type="submit"].fusion-button-small';
			$elements[] = '.wpcf7-submit.fusion-button-small';
		}
		if ( class_exists( 'bbPress' ) ) {
			$elements[] = '.bbp-submit-wrapper .button';
			$elements[] = '#bbp_user_edit_submit';
		}
		if ( class_exists( 'WooCommerce' ) ) {
			$elements[] = '.woocommerce .login .button';
			$elements[] = '.woocommerce .register .button';
		}
		$css['global'][avada_implode( $elements )]['box-shadow'] = 'inset 0px 1px 0px #ffffff, 0px 2px 0px ' . Avada_Sanitize::color( Avada()->settings->get( 'button_bevel_color' ), Avada()->settings->get_default( 'button_bevel_color' ) ) . ', 1px 4px 4px 2px rgba(0, 0, 0, 0.3)';

		$elements = avada_map_selector( $elements, ':active' );
		$css['global'][avada_implode( $elements )]['box-shadow'] = 'inset 0px 1px 0px #ffffff, 0px 1px 0px ' . Avada_Sanitize::color( Avada()->settings->get( 'button_bevel_color' ), Avada()->settings->get_default( 'button_bevel_color' ) ) . ', 1px 4px 4px 2px rgba(0, 0, 0, 0.3)';

		$elements = array(
			'.button.default.medium',
			'.fusion-button.fusion-button-default.fusion-button-medium',
			'#comment-submit',
		);
		if ( defined( 'WPCF7_PLUGIN' ) ) {
			$elements[] = '.wpcf7-form input[type="submit"].fusion-button-medium';
			$elements[] = '.wpcf7-submit.fusion-button-medium';
		}
		if ( class_exists( 'bbPress' ) ) {
			$elements[] = '.bbp-submit-wrapper .button.button-medium';
		}
		if ( class_exists( 'WooCommerce' ) ) {
			$elements[] = '.woocommerce .checkout #place_order';
			$elements[] = '.woocommerce .single_add_to_cart_button';
			$elements[] = '.woocommerce button.button';

		}
		$css['global'][avada_implode( $elements )]['box-shadow'] = 'inset 0px 1px 0px #ffffff, 0px 3px 0px ' . Avada_Sanitize::color( Avada()->settings->get( 'button_bevel_color' ), Avada()->settings->get_default( 'button_bevel_color' ) ) . ', 1px 5px 5px 3px rgba(0, 0, 0, 0.3)';

		$elements = avada_map_selector( $elements, ':active' );
		$css['global'][avada_implode( $elements )]['box-shadow'] = 'inset 0px 1px 0px #ffffff, 0px 1px 0px ' . Avada_Sanitize::color( Avada()->settings->get( 'button_bevel_color' ), Avada()->settings->get_default( 'button_bevel_color' ) ) . ', 1px 5px 5px 3px rgba(0, 0, 0, 0.3)';

		$elements = array(
			'.button.default.large',
			'.fusion-button.fusion-button-default.fusion-button-large',
		);
		if ( defined( 'WPCF7_PLUGIN' ) ) {
			$elements[] = '.wpcf7-form input[type="submit"].fusion-button-large';
			$elements[] = '.wpcf7-submit.fusion-button-large';
		}
		if ( class_exists( 'bbPress' ) ) {
			$elements[] = '.bbp-submit-wrapper .button.button-large';
		}
		$css['global'][avada_implode( $elements )]['box-shadow'] = 'inset 0px 1px 0px #ffffff, 0px 4px 0px ' . Avada_Sanitize::color( Avada()->settings->get( 'button_bevel_color' ), Avada()->settings->get_default( 'button_bevel_color' ) ) . ', 1px 6px 6px 3px rgba(0, 0, 0, 0.3)';

		$elements = avada_map_selector( $elements, ':active' );
		$css['global'][avada_implode( $elements )]['box-shadow'] = 'inset 0px 1px 0px #ffffff, 0px 1px 0px ' . Avada_Sanitize::color( Avada()->settings->get( 'button_bevel_color' ), Avada()->settings->get_default( 'button_bevel_color' ) ) . ', 1px 6px 6px 3px rgba(0, 0, 0, 0.3)';

		$elements = array(
			'.button.default.xlarge',
			'.fusion-button.fusion-button-default.fusion-button-xlarge',
		);
		if ( defined( 'WPCF7_PLUGIN' ) ) {
			$elements[] = '.wpcf7-form input[type="submit"].fusion-button-xlarge';
			$elements[] = '.wpcf7-submit.fusion-button-xlarge';
		}
		if ( class_exists( 'bbPress' ) ) {
			$elements[] = '.bbp-submit-wrapper .button.button-xlarge';
		}
		$css['global'][avada_implode( $elements )]['box-shadow'] = 'inset 0px 1px 0px #ffffff, 0px 5px 0px ' . Avada_Sanitize::color( Avada()->settings->get( 'button_bevel_color' ), Avada()->settings->get_default( 'button_bevel_color' ) ) . ', 1px 7px 7px 3px rgba(0, 0, 0, 0.3)';

		$elements = avada_map_selector( $elements, ':active' );
		$css['global'][avada_implode( $elements )]['box-shadow'] = 'inset 0px 2px 0px #ffffff, 0px 2px 0px ' . Avada_Sanitize::color( Avada()->settings->get( 'button_bevel_color' ), Avada()->settings->get_default( 'button_bevel_color' ) ) . ', 1px 7px 7px 3px rgba(0, 0, 0, 0.3)';

	}

	$elements = array(
		'.button.default',
		'.fusion-button',
		'.button-default',
		'.fusion-button-default',
		'#comment-submit',
		'#reviews input#submit',
	);
	if ( class_exists( 'GFForms' ) ) {
		$elements[] = '.gform_wrapper .gform_button';
		$elements[] = '.gform_wrapper .button';
		$elements[] = '.gform_page_footer input[type="button"]';
	}
	if ( defined( 'WPCF7_PLUGIN' ) ) {
		$elements[] = '.wpcf7-form input[type="submit"]';
		$elements[] = '.wpcf7-submit';
	}
	if ( class_exists( 'bbPress' ) ) {
		$elements[] = '.bbp-submit-wrapper .button';
		$elements[] = '#bbp_user_edit_submit';
	}
	if ( class_exists( 'WooCommerce' ) ) {
		$elements[] = '.woocommerce .checkout #place_order';
		$elements[] = '.woocommerce .single_add_to_cart_button';
		$elements[] = '.woocommerce button.button';
		$elements[] = '.woocommerce .login .button';
		$elements[] = '.woocommerce .register .button';
	}
	$css['global'][avada_implode( $elements )]['border-width'] = Avada_Sanitize::size( Avada()->settings->get( 'button_border_width' ) );
	$css['global'][avada_implode( $elements )]['border-style'] = 'solid';

	$elements = array(
		'.button.default:hover',
		'.fusion-button.button-default:hover',
		'.ticket-selector-submit-btn[type="submit"]'
	);
	$css['global'][avada_implode( $elements )]['border-width'] = Avada_Sanitize::size( Avada()->settings->get( 'button_border_width' ) );
	$css['global'][avada_implode( $elements )]['border-style'] = 'solid';

	$css['global']['.fusion-menu-item-button .menu-text']['border-color'] =  Avada()->settings->get( 'button_accent_color' );
	$css['global']['.fusion-menu-item-button:hover .menu-text']['border-color'] =  Avada()->settings->get( 'button_accent_hover_color' );

	$elements = array(
		'.button.default',
		'.button-default',
		'.fusion-button-default',
		'#comment-submit',
		'#reviews input#submit',
		'.ticket-selector-submit-btn[type="submit"]',
	);
	if ( class_exists( 'GFForms' ) ) {
		$elements[] = '.gform_page_footer input[type="button"]';
		$elements[] = '.gform_wrapper .gform_button';
		$elements[] = '.gform_wrapper .button';
	}
	if ( defined( 'WPCF7_PLUGIN' ) ) {
		$elements[] = '.wpcf7-form input[type="submit"]';
		$elements[] = '.wpcf7-submit';
	}
	if ( class_exists( 'bbPress' ) ) {
		$elements[] = '.bbp-submit-wrapper .button';
		$elements[] = '#bbp_user_edit_submit';

	}
	if ( class_exists( 'WooCommerce' ) ) {
		$elements[] = '.woocommerce .checkout #place_order';
		$elements[] = '.woocommerce .single_add_to_cart_button';
		$elements[] = '.woocommerce button.button';
		$elements[] = '.woocommerce .avada-shipping-calculator-form .button';
		$elements[] = '.woocommerce .login .button';
		$elements[] = '.woocommerce .register .button';
	}
	if ( 'Pill' == Avada()->settings->get( 'button_shape' ) ) {
		$css['global'][avada_implode( $elements )]['border-radius'] = '25px';
	} elseif ( 'Square' == Avada()->settings->get( 'button_shape' ) ) {
		$css['global'][avada_implode( $elements )]['border-radius'] = '0';
	} elseif ( 'Round' == Avada()->settings->get( 'button_shape' ) ) {
		$css['global'][avada_implode( $elements )]['border-radius'] = '2px';
	}

	$css['global']['.reading-box']['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'tagline_bg' ), Avada()->settings->get_default( 'tagline_bg' ) );

	$css['global']['.isotope .isotope-item']['transition-property'] = 'top, left, opacity';

	if ( Avada()->settings->get( 'link_image_rollover' ) ) {
		$css['global']['.fusion-rollover .link-icon']['display'] = 'none !important';
	}

	if ( Avada()->settings->get( 'zoom_image_rollover' ) ) {
		$css['global']['.fusion-rollover .gallery-icon']['display'] = 'none !important';
	}

	if ( Avada()->settings->get( 'title_image_rollover' ) ) {
		$css['global']['.fusion-rollover .fusion-rollover-title']['display'] = 'none';
	}

	if ( Avada()->settings->get( 'cats_image_rollover' ) ) {
		$css['global']['.fusion-rollover .fusion-rollover-categories']['display'] = 'none';
	}

	if ( class_exists( 'WooCommerce' ) ) {
		if ( Avada()->settings->get( 'woocommerce_one_page_checkout' ) ) {

			$elements = array(
				'.woocommerce .checkout #customer_details .col-1',
				'.woocommerce .checkout #customer_details .col-2'
			);
			$css['global'][avada_implode( $elements )]['box-sizing']    = 'border-box';
			$css['global'][avada_implode( $elements )]['border']        = '1px solid';
			$css['global'][avada_implode( $elements )]['overflow']      = 'hidden';
			$css['global'][avada_implode( $elements )]['padding']       = '30px';
			$css['global'][avada_implode( $elements )]['margin-bottom'] = '30px';
			$css['global'][avada_implode( $elements )]['float']         = 'left';
			$css['global'][avada_implode( $elements )]['width']         = '48%';
			$css['global'][avada_implode( $elements )]['margin-right']  = '4%';

			if ( is_rtl() ) {

				$elements = array(
					'.rtl .woocommerce form.checkout #customer_details .col-1',
					'.rtl .woocommerce form.checkout #customer_details .col-2'
				);
				$css['global'][avada_implode( $elements )]['float'] = 'right';

				$css['global']['.rtl .woocommerce form.checkout #customer_details .col-1']['margin-left']  = '4%';
				$css['global']['.rtl .woocommerce form.checkout #customer_details .col-1']['margin-right'] = 0;

			}

			$elements = array(
				'.woocommerce form.checkout #customer_details .col-1',
				'.woocommerce form.checkout #customer_details .col-2',
			);
			$css['global'][avada_implode( $elements )]['border-color'] = Avada_Sanitize::color( Avada()->settings->get( 'sep_color' ), Avada()->settings->get_default( 'sep_color' ) );

			$css['global']['.woocommerce form.checkout #customer_details div:last-child']['margin-right'] = '0';

			$css['global']['.woocommerce form.checkout .avada-checkout-no-shipping #customer_details .col-1']['width']        = '100%';
			$css['global']['.woocommerce form.checkout .avada-checkout-no-shipping #customer_details .col-1']['margin-right'] = '0';
			$css['global']['.woocommerce form.checkout .avada-checkout-no-shipping #customer_details .col-2']['display']      = 'none';

		} else {

			$elements = array(
				'.woocommerce form.checkout .col-2',
				'.woocommerce form.checkout #order_review_heading',
				'.woocommerce form.checkout #order_review'
			);
			$css['global'][avada_implode( $elements )]['display'] = 'none';

		}

	}

	if ( Avada()->settings->get( 'responsive' ) ) {

		if ( ! Avada()->settings->get( 'ipad_potrait' ) ) {

			$media_query = '@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) and (orientation: landscape)';
			$css[$media_query]['#wrapper .fusion-page-title-bar']['height'] = Avada_Sanitize::size( Avada()->settings->get( 'page_title_height' ) ) . ' !important';

		}

	}

	if ( Avada()->settings->get( 'page_title_100_width' ) ) {
		$css['global']['.layout-wide-mode .fusion-page-title-row']['max-width'] = '100%';
	}

	if ( 'None' != Avada()->settings->get( 'google_button' ) ) {
		$button_font = "'" . esc_attr( Avada()->settings->get( 'google_button' ) ) . "', Arial, Helvetica, sans-serif";
	} elseif ( 'Select Font' != Avada()->settings->get( 'standard_button' ) ) {
		$button_font = esc_attr( Avada()->settings->get( 'standard_button' ) );
	}

	$elements = array(
		'.fusion-button',
		'.fusion-load-more-button',
		'.comment-form input[type="submit"]',
		'.ticket-selector-submit-btn[type="submit"]'
	);
	if ( class_exists( 'GFForms' ) ) {
		$elements[] = '.gform_wrapper .gform_button';
		$elements[] = '.gform_wrapper .button';
		$elements[] = '.gform_page_footer input[type="button"]';
	}
	if ( defined( 'WPCF7_PLUGIN' ) ) {
		$elements[] = '.wpcf7-form input[type="submit"]';
	}
	if ( class_exists( 'bbPress' ) ) {
		$elements[] = '#bbp_user_edit_submit';
	}
	if ( class_exists( 'WooCommerce' ) ) {
		$elements[] = '.woocommerce .single_add_to_cart_button';
		$elements[] = '.woocommerce button.button';
		$elements[] = '.woocommerce .shipping-calculator-form .button';
		$elements[] = '.woocommerce .checkout #place_order';
		$elements[] = '.woocommerce .checkout_coupon .button';
		$elements[] = '.woocommerce .login .button';
		$elements[] = '.woocommerce .register .button';
		$elements[] = '.woocommerce .avada-order-details .order-again .button';
	}
	$css['global'][avada_implode( $elements )]['font-family'] = $button_font;
	$css['global'][avada_implode( $elements )]['font-weight'] = esc_attr( Avada()->settings->get( 'font_weight_button' ) );
	if ( Avada()->settings->get( 'button_font_ls' ) ) {
		$css['global'][avada_implode( $elements )]['letter-spacing'] = intval( Avada()->settings->get( 'button_font_ls' ) ) . 'px';
	}

	$elements = array(
		'.fusion-image-wrapper .fusion-rollover .fusion-rollover-link',
		'.fusion-image-wrapper .fusion-rollover .fusion-rollover-gallery'
	);
	if ( Avada()->settings->get( 'icon_circle_image_rollover' ) ) {
		$css['global'][avada_implode( $elements )]['background'] = 'none';
		$css['global'][avada_implode( $elements )]['width']      = round( intval( Avada()->settings->get( 'image_rollover_icon_size' ) ) * 0.5 + intval( Avada()->settings->get( 'image_rollover_icon_size' ) ) ) . 'px';
		$css['global'][avada_implode( $elements )]['height']     = round( intval( Avada()->settings->get( 'image_rollover_icon_size' ) ) * 0.5 + intval( Avada()->settings->get( 'image_rollover_icon_size' ) ) ) . 'px';
	} else {
		$css['global'][avada_implode( $elements )]['width']      = round( intval( Avada()->settings->get( 'image_rollover_icon_size' ) ) * 1.41 + intval( Avada()->settings->get( 'image_rollover_icon_size' ) ) ) . 'px';
		$css['global'][avada_implode( $elements )]['height']     = round( intval( Avada()->settings->get( 'image_rollover_icon_size' ) ) * 1.41 + intval( Avada()->settings->get( 'image_rollover_icon_size' ) ) ) . 'px';
	}

	$elements = array(
		'.fusion-image-wrapper .fusion-rollover .fusion-rollover-link:before',
		'.fusion-image-wrapper .fusion-rollover .fusion-rollover-gallery:before'
	);
	if ( Avada()->settings->get( 'image_rollover_icon_size' ) ) {
		$css['global'][avada_implode( $elements )]['font-size']   = intval( Avada()->settings->get( 'image_rollover_icon_size' ) ) . 'px';
		$css['global'][avada_implode( $elements )]['margin-left'] = '-' . intval( Avada()->settings->get( 'image_rollover_icon_size' ) / 2 ) . 'px';
		if ( Avada()->settings->get( 'icon_circle_image_rollover' ) ) {
			$css['global'][avada_implode( $elements )]['line-height'] = round( intval( Avada()->settings->get( 'image_rollover_icon_size' ) ) * 0.5 + intval( Avada()->settings->get( 'image_rollover_icon_size' ) ) ) . 'px';
		} else {
			$css['global'][avada_implode( $elements )]['line-height'] = round( intval( Avada()->settings->get( 'image_rollover_icon_size' ) ) * 1.41 + intval( Avada()->settings->get( 'image_rollover_icon_size' ) ) ) . 'px';
		}
	}
	$css['global'][avada_implode( $elements )]['color'] = Avada_Sanitize::color( Avada()->settings->get( 'image_rollover_icon_color' ), Avada()->settings->get_default( 'image_rollover_icon_color' ) );

	$css['global']['.searchform .search-table .search-field input']['height'] = fusion_strip_unit( Avada()->settings->get( 'search_form_height' ) ) . 'px';

	$css['global']['.searchform .search-table .search-button input[type="submit"]']['height']      = fusion_strip_unit( Avada()->settings->get( 'search_form_height' ) ) . 'px';
	$css['global']['.searchform .search-table .search-button input[type="submit"]']['width']       = fusion_strip_unit( Avada()->settings->get( 'search_form_height' ) ) . 'px';
	$css['global']['.searchform .search-table .search-button input[type="submit"]']['line-height'] = fusion_strip_unit( Avada()->settings->get( 'search_form_height' ) ) . 'px';


	/**
	 * Headings
	 */
	$elements = array( 'h1', '.fusion-title-size-one' );
	$css['global'][avada_implode( $elements )]['margin-top']    = Avada_Sanitize::size( Avada()->settings->get( 'h1_top_margin' ) . 'em' );
	$css['global'][avada_implode( $elements )]['margin-bottom'] = Avada_Sanitize::size( Avada()->settings->get( 'h1_bottom_margin' ) . 'em' );
	if ( Avada()->settings->get( 'h1_font_ls' ) ) {
		$css['global'][avada_implode( $elements )]['letter-spacing'] = intval( Avada()->settings->get( 'h1_font_ls' ) ) . 'px';
	}

	$elements = array( 'h2', '.fusion-title-size-two' );
	$css['global'][avada_implode( $elements )]['margin-top']    = Avada_Sanitize::size( Avada()->settings->get( 'h2_top_margin' ) . 'em' );
	$css['global'][avada_implode( $elements )]['margin-bottom'] = Avada_Sanitize::size( Avada()->settings->get( 'h2_bottom_margin' ) . 'em' );
	if ( Avada()->settings->get( 'h2_font_ls' ) ) {
		$css['global'][avada_implode( $elements )]['letter-spacing'] = intval( Avada()->settings->get( 'h2_font_ls' ) ) . 'px';
	}

	$elements = array( 'h3', '.fusion-title-size-three' );
	$css['global'][avada_implode( $elements )]['margin-top']    = Avada_Sanitize::size( Avada()->settings->get( 'h3_top_margin' ) . 'em' );
	$css['global'][avada_implode( $elements )]['margin-bottom'] = Avada_Sanitize::size( Avada()->settings->get( 'h3_bottom_margin' ) . 'em' );
	if ( Avada()->settings->get( 'h3_font_ls' ) ) {
		$css['global'][avada_implode( $elements )]['letter-spacing'] = intval( Avada()->settings->get( 'h3_font_ls' ) ) . 'px';
	}

	$elements = array( 'h4', '.fusion-title-size-four' );
	$css['global'][avada_implode( $elements )]['margin-top']    = Avada_Sanitize::size( Avada()->settings->get( 'h4_top_margin' ) . 'em' );
	$css['global'][avada_implode( $elements )]['margin-bottom'] = Avada_Sanitize::size( Avada()->settings->get( 'h4_bottom_margin' ) . 'em' );
	if ( Avada()->settings->get( 'h4_font_ls' ) ) {
		$css['global'][avada_implode( $elements )]['letter-spacing'] = intval( Avada()->settings->get( 'h4_font_ls' ) ) . 'px';
	}

	$elements = array( 'h5', '.fusion-title-size-five' );
	$css['global'][avada_implode( $elements )]['margin-top']    = Avada_Sanitize::size( Avada()->settings->get( 'h5_top_margin' ) . 'em' );
	$css['global'][avada_implode( $elements )]['margin-bottom'] = Avada_Sanitize::size( Avada()->settings->get( 'h5_bottom_margin' ) . 'em' );
	if ( Avada()->settings->get( 'h5_font_ls' ) ) {
		$css['global'][avada_implode( $elements )]['letter-spacing'] = intval( Avada()->settings->get( 'h5_font_ls' ) ) . 'px';
	}

	$elements = array( 'h6', '.fusion-title-size-six' );
	$css['global'][avada_implode( $elements )]['margin-top']    = Avada_Sanitize::size( Avada()->settings->get( 'h6_top_margin' ) . 'em' );
	$css['global'][avada_implode( $elements )]['margin-bottom'] = Avada_Sanitize::size( Avada()->settings->get( 'h6_bottom_margin' ) . 'em' );
	if ( Avada()->settings->get( 'h6_font_ls' ) ) {
		$css['global'][avada_implode( $elements )]['letter-spacing'] = intval( Avada()->settings->get( 'h6_font_ls' ) ) . 'px';
	}

	/**
	 * HEADER IS NUMBER 5
	 */


	/**
	 * Header Styles
	 */
	$css['global']['.fusion-logo']['margin-top']    = Avada_Sanitize::size( Avada()->settings->get( 'margin_logo_top' ) );
	$css['global']['.fusion-logo']['margin-right']  = Avada_Sanitize::size( Avada()->settings->get( 'margin_logo_right' ) );
	$css['global']['.fusion-logo']['margin-bottom'] = Avada_Sanitize::size( Avada()->settings->get( 'margin_logo_bottom' ) );
	$css['global']['.fusion-logo']['margin-left']   = Avada_Sanitize::size( Avada()->settings->get( 'margin_logo_left' ) );

	if ( Avada()->settings->get( 'header_shadow' ) ) {

		$elements = array(
			'.fusion-header-shadow:after',
			'body.side-header-left #side-header.header-shadow:before',
			'body.side-header-right #side-header.header-shadow:before'
		);
		$css['global'][avada_implode( $elements )]['content']        = '""';
		$css['global'][avada_implode( $elements )]['z-index']        = '99996';
		$css['global'][avada_implode( $elements )]['position']       = 'absolute';
		$css['global'][avada_implode( $elements )]['left']           = '0';
		$css['global'][avada_implode( $elements )]['top']            = '0';
		$css['global'][avada_implode( $elements )]['height']         = '100%';
		$css['global'][avada_implode( $elements )]['width']          = '100%';
		$css['global'][avada_implode( $elements )]['pointer-events'] = 'none';

		$elements = array(
			'.fusion-header-shadow .fusion-mobile-menu-design-classic',
			'.fusion-header-shadow .fusion-mobile-menu-design-modern'
		);
		$css['global'][avada_implode( $elements )]['box-shadow'] = '0px 10px 50px -2px rgba(0, 0, 0, 0.14)';
		$css['global']['body.side-header-left #side-header.header-shadow:before']['box-shadow'] = '10px 0px 50px -2px rgba(0, 0, 0, 0.14)';
		$css['global']['body.side-header-right #side-header.header-shadow:before']['box-shadow'] = '-10px 0px 50px -2px rgba(0, 0, 0, 0.14)';

		$elements = array(
			'.fusion-is-sticky:before',
			'.fusion-is-sticky:after'
		);
		$css['global'][avada_implode( $elements )]['display'] = 'none';

	}

	$css['global']['.fusion-header-wrapper .fusion-row']['padding-left']  = Avada_Sanitize::size( Avada()->settings->get( 'padding_header_left' ) );
	$css['global']['.fusion-header-wrapper .fusion-row']['padding-right'] = Avada_Sanitize::size( Avada()->settings->get( 'padding_header_right' ) );
	$css['global']['.fusion-header-wrapper .fusion-row']['max-width']     = $site_width_with_units;

	$elements = array(
		'.fusion-header-v2 .fusion-header',
		'.fusion-header-v3 .fusion-header',
		'.fusion-header-v4 .fusion-header',
		'.fusion-header-v5 .fusion-header',
	);
	$css['global'][avada_implode( $elements )]['border-bottom-color'] = Avada_Sanitize::color( Avada()->settings->get( 'header_border_color' ), Avada()->settings->get_default( 'header_border_color' ) );

	$css['global']['#side-header .fusion-secondary-menu-search-inner']['border-top-color'] = Avada_Sanitize::color( Avada()->settings->get( 'header_border_color' ), Avada()->settings->get_default( 'header_border_color' ) );

	$css['global']['.fusion-header .fusion-row']['padding-top']    = Avada_Sanitize::size( Avada()->settings->get( 'margin_header_top' ) );
	$css['global']['.fusion-header .fusion-row']['padding-bottom'] = Avada_Sanitize::size( Avada()->settings->get( 'margin_header_bottom' ) );

	$css['global']['.fusion-secondary-header']['background-color']    = Avada_Sanitize::color( Avada()->settings->get( 'header_top_bg_color' ), Avada()->settings->get_default( 'header_top_bg_color' ) );
	if ( Avada()->settings->get( 'snav_font_size' ) ) {
		$css['global']['.fusion-secondary-header']['font-size']       = intval( Avada()->settings->get( 'snav_font_size' ) ) . 'px';
	}
	$css['global']['.fusion-secondary-header']['color']               = Avada_Sanitize::color( Avada()->settings->get( 'snav_color' ), Avada()->settings->get_default( 'snav_color' ) );
	$css['global']['.fusion-secondary-header']['border-bottom-color'] = Avada_Sanitize::color( Avada()->settings->get( 'header_border_color' ), Avada()->settings->get_default( 'header_border_color' ) );

	$elements = array(
		'.fusion-secondary-header a',
		'.fusion-secondary-header a:hover'
	);
	$css['global'][avada_implode( $elements )]['color'] = Avada_Sanitize::color( Avada()->settings->get( 'snav_color' ), Avada()->settings->get_default( 'snav_color' ) );

	$css['global']['.fusion-header-v2 .fusion-secondary-header']['border-top-color'] = Avada_Sanitize::color( Avada()->settings->get( 'primary_color' ), Avada()->settings->get_default( 'primary_color' ) );

	$css['global']['.fusion-mobile-menu-design-modern .fusion-secondary-header .fusion-alignleft']['border-bottom-color'] = Avada_Sanitize::color( Avada()->settings->get( 'header_border_color' ), Avada()->settings->get_default( 'header_border_color' ) );

	if ( Avada()->settings->get( 'tagline_font_size' ) ) {
		$css['global']['.fusion-header-tagline']['font-size'] = intval( Avada()->settings->get( 'tagline_font_size' ) ) . 'px';
	}
	$css['global']['.fusion-header-tagline']['color'] = Avada_Sanitize::color( Avada()->settings->get( 'tagline_font_color' ), Avada()->settings->get_default( 'tagline_font_color' ) );

	$elements = array(
		'.fusion-secondary-main-menu',
		'.fusion-mobile-menu-sep'
	);
	$css['global'][avada_implode( $elements )]['border-bottom-color'] = Avada_Sanitize::color( Avada()->settings->get( 'header_border_color' ), Avada()->settings->get_default( 'header_border_color' ) );

	$css['global']['#side-header']['width']          = intval( $side_header_width ) . 'px';
	$css['global']['#side-header']['padding-top']    = Avada_Sanitize::size( Avada()->settings->get( 'margin_header_top' ) );
	$css['global']['#side-header']['padding-bottom'] = Avada_Sanitize::size( Avada()->settings->get( 'margin_header_bottom' ) );
	$css['global']['#side-header']['border-color']   = Avada_Sanitize::color( Avada()->settings->get( 'header_border_color' ), Avada()->settings->get_default( 'header_border_color' ) );

	$css['global']['#side-header .side-header-content']['padding-left']  = Avada_Sanitize::size( Avada()->settings->get( 'padding_header_left' ) );
	$css['global']['#side-header .side-header-content']['padding-right'] = Avada_Sanitize::size( Avada()->settings->get( 'padding_header_left' ) );

	$css['global']['#side-header .fusion-main-menu > ul > li > a']['padding-left']               = Avada_Sanitize::size( Avada()->settings->get( 'padding_header_left' ) );
	$css['global']['#side-header .fusion-main-menu > ul > li > a']['padding-right']              = Avada_Sanitize::size( Avada()->settings->get( 'padding_header_right' ) );
	$css['global']['.side-header-left .fusion-main-menu > ul > li > a > .fusion-caret']['right'] = Avada_Sanitize::size( Avada()->settings->get( 'padding_header_right' ) );
	$css['global']['.side-header-right .fusion-main-menu > ul > li > a > .fusion-caret']['left'] = Avada_Sanitize::size( Avada()->settings->get( 'padding_header_left' ) );
	$css['global']['#side-header .fusion-main-menu > ul > li > a']['border-top-color']           = Avada_Sanitize::color( Avada()->settings->get( 'header_border_color' ), Avada()->settings->get_default( 'header_border_color' ) );
	$css['global']['#side-header .fusion-main-menu > ul > li > a']['border-bottom-color']        = Avada_Sanitize::color( Avada()->settings->get( 'header_border_color' ), Avada()->settings->get_default( 'header_border_color' ) );
	$css['global']['#side-header .fusion-main-menu > ul > li > a']['text-align']                 = esc_attr( Avada()->settings->get( 'menu_text_align' ) );

	$elements = array(
		'#side-header .fusion-main-menu > ul > li.current-menu-ancestor > a',
		'#side-header .fusion-main-menu > ul > li.current-menu-item > a'
	);
	$css['global'][avada_implode( $elements )]['color']              = Avada_Sanitize::color( Avada()->settings->get( 'menu_hover_first_color' ), Avada()->settings->get_default( 'menu_hover
		' ) );
	$css['global'][avada_implode( $elements )]['border-right-color'] = Avada_Sanitize::color( Avada()->settings->get( 'menu_hover_first_color' ), Avada()->settings->get_default( 'menu_hover_first_color' ) );
	$css['global'][avada_implode( $elements )]['border-left-color']  = Avada_Sanitize::color( Avada()->settings->get( 'menu_hover_first_color' ), Avada()->settings->get_default( 'menu_hover_first_color' ) );

	$css['global']['body.side-header-left #side-header .fusion-main-menu > ul > li > ul']['left'] = intval( $side_header_width - 1 ) . 'px';

	$css['global']['body.side-header-left #side-header .fusion-main-menu .fusion-custom-menu-item-contents']['top']  = '0';
	$css['global']['body.side-header-left #side-header .fusion-main-menu .fusion-custom-menu-item-contents']['left'] = intval( $side_header_width - 1 ) . 'px';

	$css['global']['#side-header .fusion-main-menu .fusion-main-menu-search .fusion-custom-menu-item-contents']['border-top-width'] = '1px';
	$css['global']['#side-header .fusion-main-menu .fusion-main-menu-search .fusion-custom-menu-item-contents']['border-top-style'] = 'solid';

	$elements = array(
		'#side-header .side-header-content-1',
		'#side-header .side-header-content-2',
		'#side-header .fusion-secondary-menu > ul > li > a'
	);
	$css['global'][avada_implode( $elements )]['color'] = Avada_Sanitize::color( Avada()->settings->get( 'header_top_menu_sub_color' ), Avada()->settings->get_default( 'header_top_menu_sub_color' ) );
	if ( Avada()->settings->get( 'snav_font_size' ) ) {
		$css['global'][avada_implode( $elements )]['font-size'] = intval( Avada()->settings->get( 'snav_font_size' ) ) . 'px';
	}

	if ( Avada()->settings->get( 'nav_highlight_border' ) ) {
		$elements = array(
			'.side-header-left #side-header .fusion-main-menu > ul > li.current-menu-ancestor > a',
			'.side-header-left #side-header .fusion-main-menu > ul > li.current-menu-item > a'
		);
		$css['global'][avada_implode( $elements )]['border-right-width'] = intval( Avada()->settings->get( 'nav_highlight_border' ) ) . 'px';

		$elements = array(
			'.side-header-right #side-header .fusion-main-menu > ul > li.current-menu-ancestor > a',
			'.side-header-right #side-header .fusion-main-menu > ul > li.current-menu-item > a'
		);
		$css['global'][avada_implode( $elements )]['border-left-width'] = intval( Avada()->settings->get( 'nav_highlight_border' ) ) . 'px';
	}

	$elements = array(
		'.side-header-right #side-header .fusion-main-menu ul .fusion-dropdown-menu .sub-menu li ul',
		'.side-header-right #side-header .fusion-main-menu ul .fusion-dropdown-menu .sub-menu',
		'.side-header-right #side-header .fusion-main-menu ul .fusion-menu-login-box .sub-menu'
	);
	$css['global'][avada_implode( $elements )]['left'] = '-' . Avada_Sanitize::size( Avada()->settings->get( 'dropdown_menu_width' ) );

	$css['global']['.side-header-right #side-header .fusion-main-menu-search .fusion-custom-menu-item-contents']['left'] = '-250px';

	$css['global']['.side-header-right #side-header .fusion-main-menu-cart .fusion-custom-menu-item-contents']['left'] = '-180px';

	/**
	 * Main Menu Styles
	 */
	if ( Avada()->settings->get( 'nav_padding' ) ) {
		$css['global']['.fusion-main-menu > ul > li']['padding-right'] = intval( Avada()->settings->get( 'nav_padding' ) ) . 'px';
	}
	if ( Avada()->settings->get( 'nav_highlight_border' ) ) {
		$css['global']['.fusion-main-menu > ul > li > a']['border-top'] = intval( Avada()->settings->get( 'nav_highlight_border' ) ) . 'px solid transparent';
	}

	if ( Avada()->settings->get( 'nav_height' ) ) {
		$css['global']['.fusion-main-menu > ul > li > a']['height'] = intval( Avada()->settings->get( 'nav_height' ) ) . 'px';
		$css['global']['.fusion-main-menu > ul > li > a']['line-height'] = intval( Avada()->settings->get( 'nav_height' ) ) . 'px';
	}
	$css['global']['.fusion-main-menu > ul > li > a']['font-family'] = $nav_font;
	$css['global']['.fusion-main-menu > ul > li > a']['font-weight'] = esc_attr( Avada()->settings->get( 'font_weight_menu' ) );

	if ( Avada()->settings->get( 'nav_font_size' ) ) {
		$css['global']['.fusion-main-menu > ul > li > a']['font-size'] = intval( Avada()->settings->get( 'nav_font_size' ) ) . 'px';
		$css['global']['.fusion-megamenu-icon img']['max-height'] = intval( Avada()->settings->get( 'nav_font_size' ) ) . 'px';
	}
	$css['global']['.fusion-main-menu > ul > li > a']['color'] = Avada_Sanitize::color( Avada()->settings->get( 'menu_first_color' ), Avada()->settings->get_default( 'menu_first_color' ) );
	if ( Avada()->settings->get( 'menu_font_ls' ) ) {
		$css['global']['.fusion-main-menu > ul > li > a']['letter-spacing'] = intval( Avada()->settings->get( 'menu_font_ls' ) ) . 'px';
	}

	$css['global']['.fusion-main-menu > ul > li > a:hover']['color']        = Avada_Sanitize::color( Avada()->settings->get( 'menu_hover_first_color' ), Avada()->settings->get_default( 'menu_hover_first_color' ) );
	$css['global']['.fusion-main-menu > ul > li > a:hover']['border-color'] = Avada_Sanitize::color( Avada()->settings->get( 'menu_hover_first_color' ), Avada()->settings->get_default( 'menu_hover_first_color' ) );
	$css['global']['.fusion-main-menu > ul > .fusion-menu-item-button > a:hover']['border-color'] = 'transparent';

	$css['global']['#side-header .fusion-main-menu > ul > li > a']['height'] = 'auto';
	if ( Avada()->settings->get( 'nav_height' ) ) {
		$css['global']['#side-header .fusion-main-menu > ul > li > a']['min-height'] = intval( Avada()->settings->get( 'nav_height' ) ) . 'px';
	}

	$elements = array(
		'.fusion-main-menu .current_page_item > a',
		'.fusion-main-menu .current-menu-item > a',
		'.fusion-main-menu .current-menu-parent > a',
		'.fusion-main-menu .current-menu-ancestor > a'
	);
	$css['global'][avada_implode( $elements )]['color']        = Avada_Sanitize::color( Avada()->settings->get( 'menu_hover_first_color' ), Avada()->settings->get_default( 'menu_hover_first_color' ) );
	$css['global'][avada_implode( $elements )]['border-color'] = Avada_Sanitize::color( Avada()->settings->get( 'menu_hover_first_color' ), Avada()->settings->get_default( 'menu_hover_first_color' ) );
	$css['global']['.fusion-main-menu > ul > .fusion-menu-item-button > a']['border-color'] = 'transparent';

	$css['global']['.fusion-main-menu .fusion-main-menu-icon:after']['color']  = Avada_Sanitize::color( Avada()->settings->get( 'menu_first_color' ), Avada()->settings->get_default( 'menu_first_color' ) );
	if ( Avada()->settings->get( 'nav_font_size' ) ) {
		$css['global']['.fusion-main-menu .fusion-main-menu-icon:after']['height'] = intval( Avada()->settings->get( 'nav_font_size' ) ) . 'px';
		$css['global']['.fusion-main-menu .fusion-main-menu-icon:after']['width']  = intval( Avada()->settings->get( 'nav_font_size' ) ) . 'px';
	}

	if ( Avada()->settings->get( 'main_nav_icon_circle' ) ) {
		$css['global']['.fusion-main-menu .fusion-main-menu-icon:after']['border']  = '1px solid #333333';
		$css['global']['.fusion-main-menu .fusion-main-menu-icon:after']['padding'] = '5px';
	}

	$css['global']['.fusion-main-menu .fusion-main-menu-icon:hover']['border-color'] = 'transparent';

	$css['global']['.fusion-main-menu .fusion-main-menu-icon:hover:after']['color'] = Avada_Sanitize::color( Avada()->settings->get( 'menu_hover_first_color' ), Avada()->settings->get_default( 'menu_hover_first_color' ) );

	if ( Avada()->settings->get( 'main_nav_icon_circle' ) ) {
		$css['global']['.fusion-main-menu .fusion-main-menu-icon:hover:after']['border'] = '1px solid ' . Avada_Sanitize::color( Avada()->settings->get( 'menu_hover_first_color' ), Avada()->settings->get_default( 'menu_hover_first_color' ) );
	}

	$elements = array(
		'.fusion-main-menu .fusion-main-menu-search-open .fusion-main-menu-icon:after',
		'.fusion-main-menu .fusion-main-menu-icon-active:after'
	);
	$css['global'][avada_implode( $elements )]['color'] = Avada_Sanitize::color( Avada()->settings->get( 'menu_hover_first_color' ), Avada()->settings->get_default( 'menu_hover_first_color' ) );

	if ( Avada()->settings->get( 'main_nav_icon_circle' ) ) {
		$elements = array(
			'.fusion-main-menu .fusion-main-menu-search-open .fusion-main-menu-icon:after',
			'.fusion-main-menu .fusion-main-menu-icon-active:after'
		);
		$css['global'][avada_implode( $elements )]['border'] = '1px solid ' . Avada_Sanitize::color( Avada()->settings->get( 'menu_hover_first_color' ), Avada()->settings->get_default( 'menu_hover_first_color' ) );
	}

	$css['global']['.fusion-main-menu .sub-menu']['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'menu_sub_bg_color' ), Avada()->settings->get_default( 'menu_sub_bg_color' ) );
	$css['global']['.fusion-main-menu .sub-menu']['width']            = Avada_Sanitize::size( Avada()->settings->get( 'dropdown_menu_width' ) );
	$css['global']['.fusion-main-menu .sub-menu']['border-top']       = '3px solid ' . Avada_Sanitize::color( Avada()->settings->get( 'menu_hover_first_color' ), Avada()->settings->get_default( 'menu_hover_first_color' ) );
	$css['global']['.fusion-main-menu .sub-menu']['font-family']      = $font;
	$css['global']['.fusion-main-menu .sub-menu']['font-weight']      = esc_attr( Avada()->settings->get( 'font_weight_body' ) );
	if ( Avada()->settings->get( 'megamenu_shadow' ) ) {
		$css['global']['.fusion-main-menu .sub-menu']['box-shadow']   = '1px 1px 30px rgba(0, 0, 0, 0.06)';
	}

	$css['global']['.fusion-main-menu .sub-menu ul']['left'] = Avada_Sanitize::size( Avada()->settings->get( 'dropdown_menu_width' ) );
	$css['global']['.fusion-main-menu .sub-menu ul']['top']  = '-3px';

	if ( Avada()->settings->get( 'mainmenu_dropdown_display_divider' ) ) {
		$css['global']['.fusion-main-menu .sub-menu li a']['border-bottom'] = '1px solid ' . Avada_Sanitize::color( Avada()->settings->get( 'menu_sub_sep_color' ), Avada()->settings->get_default( 'menu_sub_sep_color' ) );
	} else {
		$css['global']['.fusion-main-menu .sub-menu li a']['border-bottom'] = 'none';
	}
	$css['global']['.fusion-main-menu .sub-menu li a']['padding-top']   	= Avada_Sanitize::size( Avada()->settings->get( 'mainmenu_dropdown_vertical_padding' ) );
	$css['global']['.fusion-main-menu .sub-menu li a']['padding-bottom']	= Avada_Sanitize::size( Avada()->settings->get( 'mainmenu_dropdown_vertical_padding' ) );
	$css['global']['.fusion-main-menu .sub-menu li a']['color']         	= Avada_Sanitize::color( Avada()->settings->get( 'menu_sub_color' ), Avada()->settings->get_default( 'menu_sub_color' ) );
	$css['global']['.fusion-main-menu .sub-menu li a']['font-family']   	= $font;
	$css['global']['.fusion-main-menu .sub-menu li a']['font-weight']   	= esc_attr( Avada()->settings->get( 'font_weight_body' ) );
	if ( Avada()->settings->get( 'nav_dropdown_font_size' ) ) {
		$css['global']['.fusion-main-menu .sub-menu li a']['font-size']     = intval( Avada()->settings->get( 'nav_dropdown_font_size' ) ) . 'px';
	}

	$css['global']['.fusion-main-menu .sub-menu li a:hover']['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'menu_bg_hover_color' ), Avada()->settings->get_default( 'menu_bg_hover_color' ) );

	$elements = array(
		'.fusion-main-menu .sub-menu .current_page_item > a',
		'.fusion-main-menu .sub-menu .current-menu-item > a',
		'.fusion-main-menu .sub-menu .current-menu-parent > a'
	);
	$css['global'][avada_implode( $elements )]['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'menu_bg_hover_color' ), Avada()->settings->get_default( 'menu_bg_hover_color' ) );

	$css['global']['.fusion-main-menu .fusion-custom-menu-item-contents']['font-family'] = $font;
	$css['global']['.fusion-main-menu .fusion-custom-menu-item-contents']['font-weight'] = esc_attr( Avada()->settings->get( 'font_weight_body' ) );

	$elements = array(
		'.fusion-main-menu .fusion-main-menu-search .fusion-custom-menu-item-contents',
		'.fusion-main-menu .fusion-main-menu-cart .fusion-custom-menu-item-contents',
		'.fusion-main-menu .fusion-menu-login-box .fusion-custom-menu-item-contents'
	);
	$css['global'][avada_implode( $elements )]['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'menu_sub_bg_color' ), Avada()->settings->get_default( 'menu_sub_bg_color' ) );
	$css['global'][avada_implode( $elements )]['border-color']     = Avada_Sanitize::color( Avada()->settings->get( 'menu_sub_sep_color' ), Avada()->settings->get_default( 'menu_sub_sep_color' ) );

	if ( is_rtl() ) {

		$css['global']['.rtl .fusion-main-menu > ul > li']['padding-right'] = '0';
		if ( Avada()->settings->get( 'nav_padding' ) ) {
			$css['global']['.rtl .fusion-main-menu > ul > li']['padding-left'] = intval( Avada()->settings->get( 'nav_padding' ) ) . 'px';
		}

		$css['global']['.rtl .fusion-main-menu .sub-menu ul']['left']  = 'auto';
		$css['global']['.rtl .fusion-main-menu .sub-menu ul']['right'] = Avada_Sanitize::size( Avada()->settings->get( 'dropdown_menu_width' ) );

	}

	/**
	 * Secondary Menu Styles
	 */

	$css['global']['.fusion-secondary-menu > ul > li']['border-color'] = Avada_Sanitize::color( Avada()->settings->get( 'header_top_first_border_color' ), Avada()->settings->get_default( 'header_top_first_border_color' ) );

	if ( Avada()->settings->get( 'sec_menu_lh' ) ) {
		$css['global']['.fusion-secondary-menu > ul > li > a']['height']      = intval( Avada()->settings->get( 'sec_menu_lh' ) ) . 'px';
		$css['global']['.fusion-secondary-menu > ul > li > a']['line-height'] = intval( Avada()->settings->get( 'sec_menu_lh' ) ) . 'px';
	}

	$css['global']['.fusion-secondary-menu .sub-menu']['width']            = Avada_Sanitize::size( Avada()->settings->get( 'topmenu_dropwdown_width' ) );
	$css['global']['.fusion-secondary-menu .sub-menu']['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'header_top_sub_bg_color' ), Avada()->settings->get_default( 'header_top_sub_bg_color' ) );
	$css['global']['.fusion-secondary-menu .sub-menu']['border-color']     = Avada_Sanitize::color( Avada()->settings->get( 'header_top_menu_sub_sep_color' ), Avada()->settings->get_default( 'header_top_menu_sub_sep_color' ) );

	$css['global']['.fusion-secondary-menu .sub-menu a']['border-color'] = Avada_Sanitize::color( Avada()->settings->get( 'header_top_menu_sub_sep_color' ), Avada()->settings->get_default( 'header_top_menu_sub_sep_color' ) );
	$css['global']['.fusion-secondary-menu .sub-menu a']['color']        = Avada_Sanitize::color( Avada()->settings->get( 'header_top_menu_sub_color' ), Avada()->settings->get_default( 'header_top_menu_sub_color' ) );

	$css['global']['.fusion-secondary-menu .sub-menu a:hover']['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'header_top_menu_bg_hover_color' ), Avada()->settings->get_default( 'header_top_menu_bg_hover_color' ) );
	$css['global']['.fusion-secondary-menu .sub-menu a:hover']['color']            = Avada_Sanitize::color( Avada()->settings->get( 'header_top_menu_sub_hover_color' ), Avada()->settings->get_default( 'header_top_menu_sub_hover_color' ) );

	$css['global']['.fusion-secondary-menu > ul > li > .sub-menu .sub-menu']['left'] = Avada_Sanitize::size( Avada()->settings->get( 'topmenu_dropwdown_width' ) );

	$css['global']['.fusion-secondary-menu .fusion-custom-menu-item-contents']['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'header_top_sub_bg_color' ), Avada()->settings->get_default( 'header_top_sub_bg_color' ) );
	$css['global']['.fusion-secondary-menu .fusion-custom-menu-item-contents']['border-color']     = Avada_Sanitize::color( Avada()->settings->get( 'header_top_menu_sub_sep_color' ), Avada()->settings->get_default( 'header_top_menu_sub_sep_color' ) );
	$css['global']['.fusion-secondary-menu .fusion-custom-menu-item-contents']['color']            = Avada_Sanitize::color( Avada()->settings->get( 'header_top_menu_sub_color' ), Avada()->settings->get_default( 'header_top_menu_sub_color' ) );

	$elements = array(
		'.fusion-secondary-menu .fusion-secondary-menu-icon',
		'.fusion-secondary-menu .fusion-secondary-menu-icon:hover'
	);
	$css['global'][avada_implode( $elements )]['color'] = Avada()->settings->get( 'menu_first_color' );

	$css['global']['.fusion-secondary-menu .fusion-menu-cart-items a']['color'] = Avada_Sanitize::color( Avada()->settings->get( 'header_top_menu_sub_color' ), Avada()->settings->get_default( 'header_top_menu_sub_color' ) );

	$css['global']['.fusion-secondary-menu .fusion-menu-cart-item a']['border-color'] = Avada_Sanitize::color( Avada()->settings->get( 'header_top_menu_sub_sep_color' ), Avada()->settings->get_default( 'header_top_menu_sub_sep_color' ) );

	$css['global']['.fusion-secondary-menu .fusion-menu-cart-item img']['border-color'] = Avada_Sanitize::color( Avada()->settings->get( 'sep_color' ), Avada()->settings->get_default( 'sep_color' ) );

	$css['global']['.fusion-secondary-menu .fusion-menu-cart-item a:hover']['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'header_top_menu_bg_hover_color' ), Avada()->settings->get_default( 'header_top_menu_bg_hover_color' ) );
	$css['global']['.fusion-secondary-menu .fusion-menu-cart-item a:hover']['color']            = Avada_Sanitize::color( Avada()->settings->get( 'header_top_menu_sub_hover_color' ), Avada()->settings->get_default( 'header_top_menu_sub_hover_color' ) );

	if ( class_exists( 'WooCommerce' ) ) {
		$css['global']['.fusion-secondary-menu .fusion-menu-cart-checkout']['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'woo_cart_bg_color' ), Avada()->settings->get_default( 'woo_cart_bg_color' ) );

		$css['global']['.fusion-secondary-menu .fusion-menu-cart-checkout a:before']['color'] = Avada_Sanitize::color( Avada()->settings->get( 'header_top_menu_sub_color' ), Avada()->settings->get_default( 'header_top_menu_sub_color' ) );

		$elements = array(
			'.fusion-secondary-menu .fusion-menu-cart-checkout a:hover',
			'.fusion-secondary-menu .fusion-menu-cart-checkout a:hover:before'
		);
		$css['global'][avada_implode( $elements )]['color'] = Avada_Sanitize::color( Avada()->settings->get( 'header_top_menu_sub_hover_color' ), Avada()->settings->get_default( 'header_top_menu_sub_hover_color' ) );
	}

	$css['global']['.fusion-secondary-menu-icon']['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'woo_cart_bg_color' ), Avada()->settings->get_default( 'woo_cart_bg_color' ) );
	$css['global']['.fusion-secondary-menu-icon']['color']            = Avada_Sanitize::color( Avada()->settings->get( 'menu_first_color' ), Avada()->settings->get_default( 'menu_first_color' ) );

	$elements = array(
		'.fusion-secondary-menu-icon:before',
		'.fusion-secondary-menu-icon:after'
	);
	$css['global'][avada_implode( $elements )]['color'] = Avada_Sanitize::color( Avada()->settings->get( 'menu_first_color' ), Avada()->settings->get_default( 'menu_first_color' ) );

	if ( is_rtl() ) {
		$css['global']['.rtl .fusion-secondary-menu > ul > li:first-child']['border-left'] = '1px solid ' . Avada_Sanitize::color( Avada()->settings->get( 'header_top_first_border_color' ), Avada()->settings->get_default( 'header_top_first_border_color' ) );

		$css['global']['.rtl .fusion-secondary-menu > ul > li > .sub-menu .sub-menu']['left']  = 'auto';
		$css['global']['.rtl .fusion-secondary-menu > ul > li > .sub-menu .sub-menu']['right'] = Avada_Sanitize::size( Avada()->settings->get( 'topmenu_dropwdown_width' ) );
	}

	if ( Avada()->settings->get( 'sec_menu_lh' ) ) {
		$css['global']['.fusion-contact-info']['line-height'] = intval( Avada()->settings->get( 'sec_menu_lh' ) ) . 'px';
	}

	/**
	 * Common Menu Styles
	 */

	if ( class_exists( 'WooCommerce' ) ) {
		if ( Avada()->settings->get( 'woo_icon_font_size' ) ) {
			$css['global']['.fusion-menu-cart-items']['font-size']   = intval( Avada()->settings->get( 'woo_icon_font_size' ) ) . 'px';
			$css['global']['.fusion-menu-cart-items']['line-height'] = round( intval( Avada()->settings->get( 'woo_icon_font_size' ) ) * 1.5 ) . 'px';
		}

		$css['global']['.fusion-menu-cart-items a']['color'] = Avada_Sanitize::color( Avada()->settings->get( 'menu_sub_color' ), Avada()->settings->get_default( 'menu_sub_color' ) );

		$css['global']['.fusion-menu-cart-item a']['border-color'] = Avada_Sanitize::color( Avada()->settings->get( 'menu_sub_sep_color' ), Avada()->settings->get_default( 'menu_sub_sep_color' ) );

		$css['global']['.fusion-menu-cart-item img']['border-color'] = Avada_Sanitize::color( Avada()->settings->get( 'sep_color' ), Avada()->settings->get_default( 'sep_color' ) );

		$css['global']['.fusion-menu-cart-item a:hover']['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'menu_bg_hover_color' ), Avada()->settings->get_default( 'menu_bg_hover_color' ) );

		$css['global']['.fusion-menu-cart-checkout']['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'woo_cart_bg_color' ), Avada()->settings->get_default( 'woo_cart_bg_color' ) );

		$css['global']['.fusion-menu-cart-checkout a:before']['color'] = Avada_Sanitize::color( Avada()->settings->get( 'menu_sub_color' ), Avada()->settings->get_default( 'menu_sub_color' ) );

		$elements = array(
			'.fusion-menu-cart-checkout a:hover',
			'.fusion-menu-cart-checkout a:hover:before'
		);
		$elements['global']['color'] = Avada_Sanitize::color( Avada()->settings->get( 'primary_color' ), Avada()->settings->get_default( 'primary_color' ) );
	}

	/**
	 * Megamenu Styles
	 */

	$css['global']['.fusion-megamenu-holder']['border-color'] = Avada_Sanitize::color( Avada()->settings->get( 'menu_hover_first_color' ), Avada()->settings->get_default( 'menu_hover_first_color' ) );

	$css['global']['.fusion-megamenu']['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'menu_sub_bg_color' ), Avada()->settings->get_default( 'menu_sub_bg_color' ) );
	if ( Avada()->settings->get( 'megamenu_shadow' ) ) {
		$css['global']['.fusion-megamenu']['box-shadow'] = '1px 1px 30px rgba(0, 0, 0, 0.06)';
	}

	$css['global']['.fusion-megamenu-wrapper .fusion-megamenu-submenu']['border-color'] 				= Avada_Sanitize::color( Avada()->settings->get( 'menu_sub_sep_color' ), Avada()->settings->get_default( 'menu_sub_sep_color' ) );

	$css['global']['.fusion-megamenu-wrapper .fusion-megamenu-submenu .sub-menu a']['padding-top']	 	= Avada_Sanitize::size( Avada()->settings->get( 'megamenu_item_vertical_padding' ) );
	$css['global']['.fusion-megamenu-wrapper .fusion-megamenu-submenu .sub-menu a']['padding-bottom']	= Avada_Sanitize::size( Avada()->settings->get( 'megamenu_item_vertical_padding' ) );
	if ( Avada()->settings->get( 'megamenu_item_display_divider' ) ) {
		$css['global']['.fusion-megamenu-wrapper .fusion-megamenu-submenu .sub-menu a']['border-bottom'] = '1px solid ' . Avada_Sanitize::color( Avada()->settings->get( 'menu_sub_sep_color' ), Avada()->settings->get_default( 'menu_sub_sep_color' ) );
		$css['global']['.fusion-megamenu-wrapper .fusion-megamenu-submenu > a']['border-bottom'] = '1px solid ' . Avada_Sanitize::color( Avada()->settings->get( 'menu_sub_sep_color' ), Avada()->settings->get_default( 'menu_sub_sep_color' ) );
		$css['global']['#side-header .fusion-main-menu > ul .sub-menu > li:last-child > a']['border-bottom'] = '1px solid ' . Avada_Sanitize::color( Avada()->settings->get( 'menu_sub_sep_color' ), Avada()->settings->get_default( 'menu_sub_sep_color' ) );
		$css['global']['.fusion-megamenu-wrapper .fusion-megamenu-submenu']['padding-bottom'] = '0';
		$css['global']['.fusion-megamenu-wrapper .fusion-megamenu-submenu-notitle']['padding-top'] = '0';
	}

	$css['global']['.fusion-megamenu-wrapper .fusion-megamenu-submenu > a:hover']['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'menu_bg_hover_color' ), Avada()->settings->get_default( 'menu_bg_hover_color' ) );
	$css['global']['.fusion-megamenu-wrapper .fusion-megamenu-submenu > a:hover']['color']            = Avada_Sanitize::color( Avada()->settings->get( 'menu_sub_color' ), Avada()->settings->get_default( 'menu_sub_color' ) );
	$css['global']['.fusion-megamenu-wrapper .fusion-megamenu-submenu > a:hover']['font-family']      = $font;
	$css['global']['.fusion-megamenu-wrapper .fusion-megamenu-submenu > a:hover']['font-weight']      = esc_attr( Avada()->settings->get( 'font_weight_body' ) );
	$css['global']['.fusion-megamenu-wrapper .fusion-megamenu-submenu > a:hover']['font-size']        = Avada_Sanitize::size( Avada()->settings->get( 'nav_dropdown_font_size' ) );

	if ( $headings_font ) {
		$css['global']['.fusion-megamenu-title']['font-family'] = $headings_font;
	}
	$css['global']['.fusion-megamenu-title']['font-weight'] = esc_attr( Avada()->settings->get( 'font_weight_headings' ) );
	$css['global']['.fusion-megamenu-title']['font-size']   = fusion_strip_unit( Avada()->settings->get( 'megamenu_title_size' ) ). 'px';
	$css['global']['.fusion-megamenu-title']['color']       = Avada_Sanitize::color( Avada()->settings->get( 'menu_first_color' ), Avada()->settings->get_default( 'menu_first_color' ) );

	$css['global']['.fusion-megamenu-title a']['color'] = Avada_Sanitize::color( Avada()->settings->get( 'menu_first_color' ), Avada()->settings->get_default( 'menu_first_color' ) );

	$css['global']['.fusion-megamenu-bullet']['border-left-color'] = Avada_Sanitize::color( Avada()->settings->get( 'menu_sub_color' ), Avada()->settings->get_default( 'menu_sub_color' ) );

	if ( is_rtl() ) {
		$css['global']['.rtl .fusion-megamenu-bullet']['border-right-color'] = Avada_Sanitize::color( Avada()->settings->get( 'menu_sub_color' ), Avada()->settings->get_default( 'menu_sub_color' ) );
	}

	$css['global']['.fusion-megamenu-widgets-container']['color']       = Avada_Sanitize::color( Avada()->settings->get( 'menu_sub_color' ), Avada()->settings->get_default( 'menu_sub_color' ) );
	$css['global']['.fusion-megamenu-widgets-container']['font-family'] = $font;
	$css['global']['.fusion-megamenu-widgets-container']['font-weight'] = esc_attr( Avada()->settings->get( 'font_weight_body' ) );
	if ( Avada()->settings->get( 'nav_dropdown_font_size' ) ) {
		$css['global']['.fusion-megamenu-widgets-container']['font-size'] = intval( Avada()->settings->get( 'nav_dropdown_font_size' ) ) . 'px';
	}

	if ( is_rtl() ) {
		$css['global']['.rtl .fusion-megamenu-wrapper .fusion-megamenu-submenu .sub-menu ul']['right'] = 'auto';
	}

	/**
	 * Sticky Header Styles
	 */

	if ( '' != Avada()->settings->get( 'header_sticky_bg_color', 'color' ) ) {
		$rgba = fusion_hex2rgb( Avada()->settings->get( 'header_sticky_bg_color', 'color' ) );
		$sticky_header_bg = 'rgba(' . $rgba[0] . ',' . $rgba[1] . ',' . $rgba[2] . ',' . Avada()->settings->get( 'header_sticky_bg_color', 'opacity' ) . ')';
	}

	if ( isset( $sticky_header_bg ) ) {

		$elements = array(
			'.fusion-header-wrapper.fusion-is-sticky .fusion-header',
			'.fusion-header-wrapper.fusion-is-sticky .fusion-secondary-main-menu'
		);
		$css['global'][avada_implode( $elements )]['background-color'] = Avada_Sanitize::color( $sticky_header_bg );

		$elements = array(
			'.no-rgba .fusion-header-wrapper.fusion-is-sticky .fusion-header',
			'.no-rgba .fusion-header-wrapper.fusion-is-sticky .fusion-secondary-main-menu'
		);
		$css['global'][avada_implode( $elements )]['background-color'] = Avada_Sanitize::color( $sticky_header_bg );
		$css['global'][avada_implode( $elements )]['opacity']          = esc_attr( Avada()->settings->get( 'header_sticky_bg_color', 'opacity' ) );
		$css['global'][avada_implode( $elements )]['filter']           = 'progid: DXImageTransform.Microsoft.Alpha(Opacity=' . esc_attr( Avada()->settings->get( 'header_sticky_bg_color', 'opacity' ) ) * 100 . ')';

	}

	if ( Avada()->settings->get( 'header_sticky_nav_padding' ) ) {
		$css['global']['.fusion-is-sticky .fusion-main-menu > ul > li']['padding-right'] = intval( Avada()->settings->get( 'header_sticky_nav_padding' ) ) . 'px';
	}

	$css['global']['.fusion-is-sticky .fusion-main-menu > ul > li:last-child']['padding-right'] = '0';

	if ( Avada()->settings->get( 'header_sticky_nav_font_size' ) ) {
		$css['global']['.fusion-is-sticky .fusion-main-menu > ul > li > a']['font-size'] = intval( Avada()->settings->get( 'header_sticky_nav_font_size' ) ) . 'px';
	}

	if ( is_rtl() ) {
		$css['global']['.rtl .fusion-is-sticky .fusion-main-menu > ul > li']['padding-right'] = '0';
		if ( Avada()->settings->get( 'header_sticky_nav_font_size' ) ) {
			$css['global']['.rtl .fusion-is-sticky .fusion-main-menu > ul > li']['padding-left']  = intval( Avada()->settings->get( 'header_sticky_nav_padding' ) ) . 'px';
		}
		$css['global']['.rtl .fusion-is-sticky .fusion-main-menu > ul > li:last-child']['padding-left'] = '0';
	}

	/**
	 * Mobile Menu Styles
	 */

	$css['global']['.fusion-mobile-selector']['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'mobile_menu_background_color' ), Avada()->settings->get_default( 'mobile_menu_background_color' ) );
	$css['global']['.fusion-mobile-selector']['border-color']     = Avada_Sanitize::color( Avada()->settings->get( 'mobile_menu_border_color' ), Avada()->settings->get_default( 'mobile_menu_border_color' ) );
	if ( Avada()->settings->get( 'mobile_menu_font_size' ) ) {
		$css['global']['.fusion-mobile-selector']['font-size']    = intval( Avada()->settings->get( 'mobile_menu_font_size' ) ) . 'px';
	}
	if ( Avada()->settings->get( 'mobile_menu_nav_height' ) ) {
		$css['global']['.fusion-mobile-selector']['height']       = intval( Avada()->settings->get( 'mobile_menu_nav_height' ) ) . 'px';
		$css['global']['.fusion-mobile-selector']['line-height']  = intval( Avada()->settings->get( 'mobile_menu_nav_height' ) ) . 'px';
	}
	$css['global']['.fusion-mobile-selector']['color']            = Avada_Sanitize::color( Avada()->settings->get( 'mobile_menu_font_color' ), Avada()->settings->get_default( 'mobile_menu_font_color' ) );

	$elements = array(
		'.fusion-selector-down',
	);
	if ( is_rtl() ) {
		$elements[] = '.rtl .fusion-selector-down';
	}
	if ( Avada()->settings->get( 'mobile_menu_nav_height' ) ) {
		$css['global'][avada_implode( $elements )]['height']      = intval( Avada()->settings->get( 'mobile_menu_nav_height' ) - 2 ) . 'px';
		$css['global'][avada_implode( $elements )]['line-height'] = intval( Avada()->settings->get( 'mobile_menu_nav_height' ) - 2 ) . 'px';
	}
	$css['global'][avada_implode( $elements )]['border-color'] = Avada_Sanitize::color( Avada()->settings->get( 'mobile_menu_border_color' ), Avada()->settings->get_default( 'mobile_menu_border_color' ) );

	$elements = array(
		'.fusion-selector-down:before',
	);
	if ( is_rtl() ) {
		$elements[] = '.rtl .fusion-selector-down:before';
	}
	$css['global'][avada_implode( $elements )]['color'] = Avada_Sanitize::color( Avada()->settings->get( 'mobile_menu_toggle_color' ), Avada()->settings->get_default( 'mobile_menu_toggle_color' ) );

	if ( 35 < Avada()->settings->get( 'mobile_menu_font_size' ) ) {
		$css['global']['.fusion-selector-down']['font-size'] = '30px';
	}

	$elements = array(
		'.fusion-mobile-nav-holder > ul',
		'.fusion-mobile-menu-design-modern .fusion-mobile-nav-holder > ul'
	);
	$css['global'][avada_implode( $elements )]['border-color'] = Avada_Sanitize::color( Avada()->settings->get( 'mobile_menu_border_color' ), Avada()->settings->get_default( 'mobile_menu_border_color' ) );

	$css['global']['.fusion-mobile-nav-item a']['color']            = Avada_Sanitize::color( Avada()->settings->get( 'mobile_menu_font_color' ), Avada()->settings->get_default( 'mobile_menu_font_color' ) );
	if ( Avada()->settings->get( 'mobile_menu_font_size' ) ) {
		$css['global']['.fusion-mobile-nav-item a']['font-size']    = intval( Avada()->settings->get( 'mobile_menu_font_size' ) ) . 'px';
	}
	$css['global']['.fusion-mobile-nav-item a']['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'mobile_menu_background_color' ), Avada()->settings->get_default( 'mobile_menu_background_color' ) );
	$css['global']['.fusion-mobile-nav-item a']['border-color']     = Avada_Sanitize::color( Avada()->settings->get( 'mobile_menu_border_color' ), Avada()->settings->get_default( 'mobile_menu_border_color' ) );
	if ( Avada()->settings->get( 'mobile_menu_nav_height' ) ) {
		$css['global']['.fusion-mobile-nav-item a']['height']       = intval( Avada()->settings->get( 'mobile_menu_nav_height' ) ) . 'px';
		$css['global']['.fusion-mobile-nav-item a']['line-height']  = intval( Avada()->settings->get( 'mobile_menu_nav_height' ) ) . 'px';
	}

	$css['global']['.fusion-mobile-nav-item a:hover']['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'mobile_menu_hover_color' ), Avada()->settings->get_default( 'mobile_menu_hover_color' ) );

	$css['global']['.fusion-mobile-nav-item a:before']['color'] = Avada_Sanitize::color( Avada()->settings->get( 'mobile_menu_font_color' ), Avada()->settings->get_default( 'mobile_menu_font_color' ) );

	$css['global']['.fusion-mobile-current-nav-item > a']['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'mobile_menu_hover_color' ), Avada()->settings->get_default( 'mobile_menu_hover_color' ) );

	$css['global']['.fusion-mobile-menu-icons']['margin-top'] = Avada_Sanitize::size( Avada()->settings->get( 'mobile_menu_icons_top_margin' ) );

	$css['global']['.fusion-mobile-menu-icons a']['color'] = Avada_Sanitize::color( Avada()->settings->get( 'mobile_menu_toggle_color' ), Avada()->settings->get_default( 'mobile_menu_toggle_color' ) );

	$css['global']['.fusion-mobile-menu-icons a:before']['color'] = Avada_Sanitize::color( Avada()->settings->get( 'mobile_menu_toggle_color' ), Avada()->settings->get_default( 'mobile_menu_toggle_color' ) );

	if ( Avada()->settings->get( 'mobile_menu_font_size' ) ) {
		$css['global']['.fusion-open-submenu']['font-size']   = intval( Avada()->settings->get( 'mobile_menu_font_size' ) ) . 'px';
	}
	if ( Avada()->settings->get( 'mobile_menu_nav_height' ) ) {
		$css['global']['.fusion-open-submenu']['height']      = intval( Avada()->settings->get( 'mobile_menu_nav_height' ) ) . 'px';
		$css['global']['.fusion-open-submenu']['line-height'] = intval( Avada()->settings->get( 'mobile_menu_nav_height' ) ) . 'px';
	}

	if ( 30 < Avada()->settings->get( 'mobile_menu_font_size' ) ) {
		$css['global']['.fusion-open-submenu']['font-size'] = '20px';
	}

	$css['global']['.fusion-open-submenu:hover']['color'] = Avada_Sanitize::color( Avada()->settings->get( 'primary_color' ), Avada()->settings->get_default( 'primary_color' ) );

	/**
	 * Shortcodes
	 */
	if ( Avada()->settings->get( 'content_box_title_size' ) ) {
		$css['global']['#wrapper .post-content .content-box-heading']['font-size']   = intval( Avada()->settings->get( 'content_box_title_size' ) ) . 'px';
	}
	if ( Avada()->settings->get( 'h2_font_lh' ) ) {
		$css['global']['#wrapper .post-content .content-box-heading']['line-height'] = intval( Avada()->settings->get( 'h2_font_lh' ) ) . 'px';
	}
	if ( Avada()->settings->get( 'content_box_title_color' ) ) {
		$css['global']['#wrapper .post-content .content-box-heading']['color'] = Avada()->settings->get( 'content_box_title_color' );
	}
	if ( Avada()->settings->get( 'content_box_body_color' ) ) {
		$css['global']['.fusion-content-boxes .content-container']['color'] = Avada()->settings->get( 'content_box_body_color' );
	}

	/**
	 * Social Links
	 */

	if ( Avada()->settings->get( 'header_social_links_font_size' ) ) {
		$css['global']['.fusion-social-links-header .fusion-social-networks a']['font-size'] = intval( Avada()->settings->get( 'header_social_links_font_size' ) ) . 'px';
	}
	if ( Avada()->settings->get( 'header_social_links_boxed_padding' ) ) {
		$css['global']['.fusion-social-links-header .fusion-social-networks.boxed-icons a']['padding'] = intval( Avada()->settings->get( 'header_social_links_boxed_padding' ) ) . 'px';
	}
	if ( Avada()->settings->get( 'footer_social_links_font_size' ) ) {
		$css['global']['.fusion-social-links-footer .fusion-social-networks a']['font-size'] = intval( Avada()->settings->get( 'footer_social_links_font_size' ) ) . 'px';
	}
	if ( Avada()->settings->get( 'footer_social_links_boxed_padding' ) ) {
		$css['global']['.fusion-social-links-footer .fusion-social-networks.boxed-icons a']['padding'] = intval( Avada()->settings->get( 'footer_social_links_boxed_padding' ) ) . 'px';
	}
	if ( Avada()->settings->get( 'sharing_social_links_font_size' ) ) {
		$css['global']['.fusion-sharing-box .fusion-social-networks a']['font-size'] = intval( Avada()->settings->get( 'sharing_social_links_font_size' ) ) . 'px';
	}
	if ( Avada()->settings->get( 'sharing_social_links_boxed_padding' ) ) {
		$css['global']['.fusion-sharing-box .fusion-social-networks.boxed-icons a']['padding'] = intval( Avada()->settings->get( 'sharing_social_links_boxed_padding' ) ) . 'px';
	}

	$elements = array(
		'.post-content .fusion-social-links .fusion-social-networks a',
		'.widget .fusion-social-links .fusion-social-networks a'
	);

	if ( Avada()->settings->get( 'social_links_font_size' ) ) {
		$css['global'][avada_implode( $elements )]['font-size'] = intval( Avada()->settings->get( 'social_links_font_size' ) ) . 'px';
	}

	$elements = array(
		'.post-content .fusion-social-links .fusion-social-networks.boxed-icons a',
		'.widget .fusion-social-links .fusion-social-networks.boxed-icons a'
	);

	if ( Avada()->settings->get( 'social_links_boxed_padding' ) ) {
		$css['global'][avada_implode( $elements )]['padding'] = intval( Avada()->settings->get( 'social_links_boxed_padding' ) ) . 'px';
	}

	if ( class_exists( 'WooCommerce' ) ) {

		/**
		 * Woocommerce - Dynamic Styling
		 */

		$css['global']['.product-images .crossfade-images']['background'] = Avada_Sanitize::color( Avada()->settings->get( 'title_border_color' ), Avada()->settings->get_default( 'title_border_color' ) );

		$css['global']['.products .product-list-view']['border-color'] = Avada_Sanitize::color( Avada()->settings->get( 'sep_color' ), Avada()->settings->get_default( 'sep_color' ) );

		$elements = array(
			'.products .product-list-view .product-excerpt-container',
			'.products .product-list-view .product-details-container'
		);
		$css['global'][avada_implode( $elements )]['border-color'] = Avada_Sanitize::color( Avada()->settings->get( 'sep_color' ), Avada()->settings->get_default( 'sep_color' ) );

		$css['global']['.order-dropdown']['color'] = Avada_Sanitize::color( Avada()->settings->get( 'woo_dropdown_text_color' ), Avada()->settings->get_default( 'woo_dropdown_text_color' ) );

		$css['global']['.order-dropdown > li:after']['border-color'] = Avada_Sanitize::color( Avada()->settings->get( 'woo_dropdown_border_color' ), Avada()->settings->get_default( 'woo_dropdown_border_color' ) );

		$elements = array(
			'.order-dropdown a',
			'.order-dropdown a:hover'
		);
		$css['global'][avada_implode( $elements )]['color'] = Avada_Sanitize::color( Avada()->settings->get( 'woo_dropdown_text_color' ), Avada()->settings->get_default( 'woo_dropdown_text_color' ) );

		$elements = array(
			'.order-dropdown .current-li',
			'.order-dropdown ul li a'
		);
		$css['global'][avada_implode( $elements )]['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'woo_dropdown_bg_color' ), Avada()->settings->get_default( 'woo_dropdown_bg_color' ) );
		$css['global'][avada_implode( $elements )]['border-color']     = Avada_Sanitize::color( Avada()->settings->get( 'woo_dropdown_border_color' ), Avada()->settings->get_default( 'woo_dropdown_border_color' ) );

		$css['global']['.order-dropdown ul li a:hover']['color'] = Avada_Sanitize::color( Avada()->settings->get( 'woo_dropdown_text_color' ), Avada()->settings->get_default( 'woo_dropdown_text_color' ) );

		if ( Avada()->settings->get( 'woo_dropdown_bg_color' ) ) {
			$css['global']['.order-dropdown ul li a:hover']['background-color'] = Avada_Sanitize::color( fusion_color_luminance( Avada()->settings->get( 'woo_dropdown_bg_color' ), 0.1 ) );
		}

		$css['global']['.catalog-ordering .order li a']['color']            = Avada_Sanitize::color( Avada()->settings->get( 'woo_dropdown_text_color' ), Avada()->settings->get_default( 'woo_dropdown_text_color' ) );
		$css['global']['.catalog-ordering .order li a']['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'woo_dropdown_bg_color' ), Avada()->settings->get_default( 'woo_dropdown_bg_color' ) );
		$css['global']['.catalog-ordering .order li a']['border-color']     = Avada_Sanitize::color( Avada()->settings->get( 'woo_dropdown_border_color' ), Avada()->settings->get_default( 'woo_dropdown_border_color' ) );

		$css['global']['.fusion-grid-list-view']['border-color'] = Avada_Sanitize::color( Avada()->settings->get( 'woo_dropdown_border_color' ), Avada()->settings->get_default( 'woo_dropdown_border_color' ) );

		$css['global']['.fusion-grid-list-view li']['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'woo_dropdown_bg_color' ), Avada()->settings->get_default( 'woo_dropdown_bg_color' ) );
		$css['global']['.fusion-grid-list-view li']['border-color']     = Avada_Sanitize::color( Avada()->settings->get( 'woo_dropdown_border_color' ), Avada()->settings->get_default( 'woo_dropdown_border_color' ) );

		$css['global']['.fusion-grid-list-view a']['color'] = Avada_Sanitize::color( Avada()->settings->get( 'woo_dropdown_text_color' ), Avada()->settings->get_default( 'woo_dropdown_text_color' ) );

		$css['global']['.fusion-grid-list-view li a:hover']['color'] = Avada_Sanitize::color( Avada()->settings->get( 'woo_dropdown_text_color' ), Avada()->settings->get_default( 'woo_dropdown_text_color' ) );

		if ( Avada()->settings->get( 'woo_dropdown_bg_color' ) ) {
			$css['global']['.fusion-grid-list-view li a:hover']['background-color'] = Avada_Sanitize::color( fusion_color_luminance( Avada()->settings->get( 'woo_dropdown_bg_color' ), 0.1 ) );
		}

		if ( Avada()->settings->get( 'woo_dropdown_bg_color' ) ) {
			$css['global']['.fusion-grid-list-view li.active-view']['background-color'] = Avada_Sanitize::color( fusion_color_luminance( Avada()->settings->get( 'woo_dropdown_bg_color' ), 0.05 ) );
		}

		if ( Avada()->settings->get( 'woo_dropdown_text_color' ) ) {
			$css['global']['.fusion-grid-list-view li.active-view a i']['color'] = Avada_Sanitize::color( fusion_color_luminance( Avada()->settings->get( 'woo_dropdown_text_color' ), 0.95 ) );
		}

	}

	if ( Avada()->settings->get( 'responsive' ) ) {

		/**
		 * Media
		 */

		/**
		 *  01 Side Header Mobile Styles
		 *  02 only screen and ( max-width: 800px )
		 *    # Layout
		 *    # General Styles
		 *    # Responsive Headers
		 *    # Page Title Bar
		 *    # Blog Layouts
		 *    # Author Page - Info
		 *  03 only screen and ( max-width: 640px )
		 *    # General Styles
		 *    # Page Title Bar
		 *    # Blog Layouts
		 *    # Filters
		 *  04 only screen and ( min-device-width: 320px ) and ( max-device-width: 640px )
		 *    # General Styles
		 *    # Page Title Bar
		 *  05 media.css CSS
		 */

		/**
		 * Side Header Mobile Styles
		 */

		$media_query = '@media only screen and (max-width: ' . Avada_Sanitize::size( Avada()->settings->get( 'side_header_break_point' ) ) . ')';

		$css[$media_query]['body.side-header #wrapper']['margin-left']  = '0 !important';
		$css[$media_query]['body.side-header #wrapper']['margin-right'] = '0 !important';

		$css[$media_query]['#side-header']['position'] = 'static';
		$css[$media_query]['#side-header']['height']   = 'auto';
		$css[$media_query]['#side-header']['width']    = '100% !important';
		$css[$media_query]['#side-header']['padding']  = '20px 30px 20px 30px !important';
		$css[$media_query]['#side-header']['margin']   = '0 !important';
		$css[$media_query]['#side-header']['border']   = 'none !important';

		$css[$media_query]['#side-header .side-header-wrapper']['padding-bottom'] = '0';

		if ( is_rtl() ) {
			$css[$media_query]['body.rtl #side-header']['position'] = 'static !important';
		}

		$elements = array(
			'#side-header .header-social',
			'#side-header .header-v4-content'
		);
		$css[$media_query][avada_implode( $elements )]['display'] = 'none';

		$css[$media_query]['#side-header .fusion-logo']['margin'] = '0 !important';
		$css[$media_query]['#side-header .fusion-logo']['float']  = 'left';

		$css[$media_query]['#side-header .side-header-content']['padding'] = '0 !important';

		$css[$media_query]['#side-header.fusion-mobile-menu-design-classic .fusion-logo']['float']      = 'none';
		$css[$media_query]['#side-header.fusion-mobile-menu-design-classic .fusion-logo']['text-align'] = 'center';

		$elements = array(
			'body.side-header #wrapper #side-header.header-shadow:before',
			'body #wrapper .header-shadow:after'
		);
		$css[$media_query][avada_implode( $elements )]['position']   = 'static';
		$css[$media_query][avada_implode( $elements )]['height']     = 'auto';
		$css[$media_query][avada_implode( $elements )]['box-shadow'] = 'none';

		$elements = array(
			'#side-header .fusion-main-menu',
			'#side-header .side-header-content-1-2',
			'#side-header .side-header-content-3'
		);
		$css[$media_query][avada_implode( $elements )]['display'] = 'none';

		$css[$media_query]['#side-header .fusion-logo']['margin'] = '0';

		$css[$media_query]['#side-header.fusion-mobile-menu-design-classic .fusion-main-menu-container .fusion-mobile-nav-holder']['display']    = 'block';
		$css[$media_query]['#side-header.fusion-mobile-menu-design-classic .fusion-main-menu-container .fusion-mobile-nav-holder']['margin-top'] = '20px';

		$css[$media_query]['#side-header.fusion-mobile-menu-design-classic .fusion-main-menu-container .fusion-mobile-sticky-nav-holder']['display'] = 'none';

		$css[$media_query]['#side-header.fusion-mobile-menu-design-modern .fusion-logo']['float']  = 'left';
		$css[$media_query]['#side-header.fusion-mobile-menu-design-modern .fusion-logo']['margin'] = '0';

		$css[$media_query]['#side-header.fusion-mobile-menu-design-modern .fusion-logo-left']['float'] = 'left';

		$css[$media_query]['#side-header.fusion-mobile-menu-design-modern .fusion-logo-right']['float'] = 'right';

		$css[$media_query]['#side-header.fusion-mobile-menu-design-modern .fusion-logo-center']['float'] = 'left';

		$css[$media_query]['#side-header.fusion-mobile-menu-design-modern .fusion-mobile-menu-icons']['display'] = 'block';

		$css[$media_query]['#side-header.fusion-mobile-menu-design-modern .fusion-logo-menu-right .fusion-mobile-menu-icons']['float'] = 'left';

		$css[$media_query]['#side-header.fusion-mobile-menu-design-modern .fusion-logo-menu-left .fusion-mobile-menu-icons']['float'] = 'right';

		$css[$media_query]['#side-header.fusion-mobile-menu-design-modern .fusion-logo-menu-left .fusion-mobile-menu-icons a:last-child']['margin-left'] = '0';


		$elements = array(
			'#side-header.fusion-mobile-menu-design-modern .fusion-main-menu-container .fusion-mobile-nav-holder',
			'#side-header.fusion-mobile-menu-design-modern .side-header-wrapper > .fusion-secondary-menu-search'
		);

		$css[$media_query][avada_implode( $elements )]['padding-top']    = '20px';
		$css[$media_query][avada_implode( $elements )]['margin-left']    = '-30px';
		$css[$media_query][avada_implode( $elements )]['margin-right']   = '-30px';
		$css[$media_query][avada_implode( $elements )]['margin-bottom']  = '-20px';

		$css[$media_query]['#side-header.fusion-mobile-menu-design-modern .fusion-main-menu-container .fusion-mobile-nav-holder > ul']['display']       = 'block';
		$css[$media_query]['#side-header.fusion-mobile-menu-design-modern .fusion-main-menu-container .fusion-mobile-nav-holder > ul']['border-right']  = '0';
		$css[$media_query]['#side-header.fusion-mobile-menu-design-modern .fusion-main-menu-container .fusion-mobile-nav-holder > ul']['border-left']   = '0';
		$css[$media_query]['#side-header.fusion-mobile-menu-design-modern .fusion-main-menu-container .fusion-mobile-nav-holder > ul']['border-bottom'] = '0';


		$css[$media_query]['#side-header.fusion-is-sticky.fusion-sticky-menu-1 .fusion-mobile-nav-holder']['display'] = 'none';

		$css[$media_query]['#side-header.fusion-is-sticky.fusion-sticky-menu-1 .fusion-mobile-sticky-nav-holder']['display'] = 'none';


		$media_query = '@media only screen and (max-width: ' . ( intval( $side_header_width ) + 800 ) . 'px)';

		$css[$media_query]['.no-overflow-y']['overflow-y'] = 'visible !important';

		$css[$media_query]['.fusion-layout-column']['margin-left']  = '0';
		$css[$media_query]['.fusion-layout-column']['margin-right'] = '0';

		$elements = array(
			'.fusion-layout-column:nth-child(5n)',
			'.fusion-layout-column:nth-child(4n)',
			'.fusion-layout-column:nth-child(3n)',
			'.fusion-layout-column:nth-child(2n)',
		);
		$css[$media_query][avada_implode( $elements )]['margin-left']  = '0';
		$css[$media_query][avada_implode( $elements )]['margin-right'] = '0';

		$css[$media_query]['.fusion-layout-column.fusion-spacing-no']['margin-bottom'] = '0';
		$css[$media_query]['.fusion-layout-column.fusion-spacing-no']['width']         = '100%';

		$css[$media_query]['.fusion-layout-column.fusion-spacing-yes']['width'] = '100%';

		$css[$media_query]['.fusion-filters']['border-bottom'] = '0';

		$css[$media_query]['.fusion-body .fusion-filter']['float']         = 'none';
		$css[$media_query]['.fusion-body .fusion-filter']['margin']        = '0';
		$css[$media_query]['.fusion-body .fusion-filter']['border-bottom'] = '1px solid #E7E6E6';

		// Responsive Headers
		$css[$media_query]['.fusion-header .fusion-row']['padding-left']  = '0';
		$css[$media_query]['.fusion-header .fusion-row']['padding-right'] = '0';

		$elements = array(
			'.fusion-header-wrapper .fusion-header',
			'.fusion-header-wrapper #side-header',
			'.fusion-header-wrapper .fusion-secondary-main-menu'
		);
		$css[$media_query][avada_implode( $elements )]['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'mobile_header_bg_color' ), Avada()->settings->get_default( 'mobile_header_bg_color' ) );

		$css[$media_query]['.fusion-header-wrapper .fusion-row']['padding-left']  = '0';
		$css[$media_query]['.fusion-header-wrapper .fusion-row']['padding-right'] = '0';

		$elements = array(
			'.fusion-footer-widget-area > .fusion-row',
			'.fusion-footer-copyright-area > .fusion-row'
		);
		$css[$media_query][avada_implode( $elements )]['padding-left']  = '0';
		$css[$media_query][avada_implode( $elements )]['padding-right'] = '0';

		$css[$media_query]['.fusion-secondary-header .fusion-row']['display'] = 'block';
		$css[$media_query]['.fusion-secondary-header .fusion-alignleft']['margin-right'] = '0';
		$css[$media_query]['.fusion-secondary-header .fusion-alignright']['margin-left'] = '0';
		$css[$media_query]['body.fusion-body .fusion-secondary-header .fusion-alignright > *']['float'] = 'none';
		$css[$media_query]['body.fusion-body .fusion-secondary-header .fusion-alignright .fusion-social-links-header .boxed-icons']['margin-bottom'] = '5px';

		$elements = array(
			'.fusion-mobile-menu-design-classic.fusion-header-v1 .fusion-header',
			'.fusion-mobile-menu-design-classic.fusion-header-v2 .fusion-header',
			'.fusion-mobile-menu-design-classic.fusion-header-v3 .fusion-header'
		);
		$css[$media_query][avada_implode( $elements )]['padding-top']    = '20px';
		$css[$media_query][avada_implode( $elements )]['padding-bottom'] = '20px';

		$elements = array(
			'.fusion-mobile-menu-design-classic.fusion-header-v1 .fusion-logo',
			'.fusion-mobile-menu-design-classic.fusion-header-v2 .fusion-logo',
			'.fusion-mobile-menu-design-classic.fusion-header-v3 .fusion-logo',
			'.fusion-mobile-menu-design-classic.fusion-header-v1 .fusion-logo a',
			'.fusion-mobile-menu-design-classic.fusion-header-v2 .fusion-logo a',
			'.fusion-mobile-menu-design-classic.fusion-header-v3 .fusion-logo a'
		);
		$css[$media_query][avada_implode( $elements )]['float']      = 'none';
		$css[$media_query][avada_implode( $elements )]['text-align'] = 'center';
		$css[$media_query][avada_implode( $elements )]['margin']     = '0 !important';

		$elements = array(
			'.fusion-mobile-menu-design-classic.fusion-header-v1 .fusion-main-menu',
			'.fusion-mobile-menu-design-classic.fusion-header-v2 .fusion-main-menu',
			'.fusion-mobile-menu-design-classic.fusion-header-v3 .fusion-main-menu'
		);
		$css[$media_query][avada_implode( $elements )]['display'] = 'none';

		$elements = array(
			'.fusion-mobile-menu-design-classic.fusion-header-v1 .fusion-mobile-nav-holder',
			'.fusion-mobile-menu-design-classic.fusion-header-v2 .fusion-mobile-nav-holder',
			'.fusion-mobile-menu-design-classic.fusion-header-v3 .fusion-mobile-nav-holder'
		);
		$css[$media_query][avada_implode( $elements )]['display']    = 'block';
		$css[$media_query][avada_implode( $elements )]['margin-top'] = '20px';

		$css[$media_query]['.fusion-mobile-menu-design-classic .fusion-secondary-header']['padding'] = '10px';

		$css[$media_query]['.fusion-mobile-menu-design-classic .fusion-secondary-header .fusion-mobile-nav-holder']['margin-top'] = '0';

		$elements = array(
			'.fusion-mobile-menu-design-classic.fusion-header-v4 .fusion-header',
			'.fusion-mobile-menu-design-classic.fusion-header-v5 .fusion-header'
		);
		$css[$media_query][avada_implode( $elements )]['padding-top']    = '20px';
		$css[$media_query][avada_implode( $elements )]['padding-bottom'] = '20px';

		$elements = array(
			'.fusion-mobile-menu-design-classic.fusion-header-v4 .fusion-secondary-main-menu',
			'.fusion-mobile-menu-design-classic.fusion-header-v5 .fusion-secondary-main-menu'
		);
		$css[$media_query][avada_implode( $elements )]['padding-top']    = '6px';
		$css[$media_query][avada_implode( $elements )]['padding-bottom'] = '6px';

		$elements = array(
			'.fusion-mobile-menu-design-classic.fusion-header-v4 .fusion-main-menu',
			'.fusion-mobile-menu-design-classic.fusion-header-v5 .fusion-main-menu'
		);
		$css[$media_query][avada_implode( $elements )]['display'] = 'none';

		$elements = array(
			'.fusion-mobile-menu-design-classic.fusion-header-v4 .fusion-mobile-nav-holder',
			'.fusion-mobile-menu-design-classic.fusion-header-v5 .fusion-mobile-nav-holder'
		);
		$css[$media_query][avada_implode( $elements )]['display'] = 'block';

		$elements = array(
			'.fusion-mobile-menu-design-classic.fusion-header-v4 .fusion-logo',
			'.fusion-mobile-menu-design-classic.fusion-header-v5 .fusion-logo',
			'.fusion-mobile-menu-design-classic.fusion-header-v4 .fusion-logo a',
			'.fusion-mobile-menu-design-classic.fusion-header-v5 .fusion-logo a'
		);
		$css[$media_query][avada_implode( $elements )]['float']      = 'none';
		$css[$media_query][avada_implode( $elements )]['text-align'] = 'center';
		$css[$media_query][avada_implode( $elements )]['margin']     = '0 !important';

		$elements = array(
			'.fusion-mobile-menu-design-classic.fusion-header-v4 .searchform',
			'.fusion-mobile-menu-design-classic.fusion-header-v5 .searchform'
		);
		$css[$media_query][avada_implode( $elements )]['display']    = 'block';
		$css[$media_query][avada_implode( $elements )]['float']      = 'none';
		$css[$media_query][avada_implode( $elements )]['width']      = '100%';
		$css[$media_query][avada_implode( $elements )]['margin']     = '0';
		$css[$media_query][avada_implode( $elements )]['margin-top'] = '13px';

		$elements = array(
			'.fusion-mobile-menu-design-classic.fusion-header-v4 .search-table',
			'.fusion-mobile-menu-design-classic.fusion-header-v5 .search-table'
		);
		$css[$media_query][avada_implode( $elements )]['width'] = '100%';

		$css[$media_query]['.fusion-mobile-menu-design-classic.fusion-header-v4 .fusion-logo a']['float'] = 'none';

		$css[$media_query]['.fusion-mobile-menu-design-classic.fusion-header-v4 .fusion-header-banner']['margin-top'] = '10px';

		$css[$media_query]['.fusion-mobile-menu-design-classic.fusion-header-v4 .fusion-secondary-main-menu .searchform']['display'] = 'none';

		$css[$media_query]['.fusion-mobile-menu-design-classic .fusion-alignleft']['margin-bottom'] = '10px';

		$elements = array(
			'.fusion-mobile-menu-design-classic .fusion-alignleft',
			'.fusion-mobile-menu-design-classic .fusion-alignright'
		);
		$css[$media_query][avada_implode( $elements )]['float']       = 'none';
		$css[$media_query][avada_implode( $elements )]['width']       = '100%';
		$css[$media_query][avada_implode( $elements )]['line-height'] = 'normal';
		$css[$media_query][avada_implode( $elements )]['display']     = 'block';

		$css[$media_query]['.fusion-mobile-menu-design-classic .fusion-contact-info']['text-align']  = 'center';
		$css[$media_query]['.fusion-mobile-menu-design-classic .fusion-contact-info']['line-height'] = 'normal';

		$css[$media_query]['.fusion-mobile-menu-design-classic .fusion-secondary-menu']['display'] = 'none';

		$css[$media_query]['.fusion-mobile-menu-design-classic .fusion-social-links-header']['max-width']     = '100%';
		$css[$media_query]['.fusion-mobile-menu-design-classic .fusion-social-links-header']['margin-top']    = '5px';
		$css[$media_query]['.fusion-mobile-menu-design-classic .fusion-social-links-header']['text-align']    = 'center';

		$css[$media_query]['.fusion-mobile-menu-design-classic .fusion-social-links-header a']['margin-bottom'] = '5px';

		$css[$media_query]['.fusion-mobile-menu-design-classic .fusion-header-tagline']['float']       = 'none';
		$css[$media_query]['.fusion-mobile-menu-design-classic .fusion-header-tagline']['text-align']  = 'center';
		$css[$media_query]['.fusion-mobile-menu-design-classic .fusion-header-tagline']['margin-top']  = '10px';
		$css[$media_query]['.fusion-mobile-menu-design-classic .fusion-header-tagline']['line-height'] = '24px';

		$css[$media_query]['.fusion-mobile-menu-design-classic .fusion-header-banner']['float']      = 'none';
		$css[$media_query]['.fusion-mobile-menu-design-classic .fusion-header-banner']['text-align'] = 'center';
		$css[$media_query]['.fusion-mobile-menu-design-classic .fusion-header-banner']['margin']     = '0 auto';
		$css[$media_query]['.fusion-mobile-menu-design-classic .fusion-header-banner']['width']      = '100%';
		$css[$media_query]['.fusion-mobile-menu-design-classic .fusion-header-banner']['margin-top'] = '20px';
		$css[$media_query]['.fusion-mobile-menu-design-classic .fusion-header-banner']['clear']      = 'both';

		$elements = array(
			'.fusion-mobile-menu-design-modern .ubermenu-responsive-toggle',
			'.fusion-mobile-menu-design-modern .ubermenu-sticky-toggle-wrapper'
		);
		$css[$media_query][avada_implode( $elements )]['clear'] = 'both';

		$elements = array(
			'.fusion-mobile-menu-design-modern.fusion-header-v1 .fusion-main-menu',
			'.fusion-mobile-menu-design-modern.fusion-header-v2 .fusion-main-menu',
			'.fusion-mobile-menu-design-modern.fusion-header-v3 .fusion-main-menu',
			'.fusion-mobile-menu-design-modern.fusion-header-v4 .fusion-main-menu',
			'.fusion-mobile-menu-design-modern.fusion-header-v5 .fusion-main-menu'
		);
		$css[$media_query][avada_implode( $elements )]['display'] = 'none';

		$elements = array(
			'.fusion-mobile-menu-design-modern.fusion-header-v1 .fusion-header',
			'.fusion-mobile-menu-design-modern.fusion-header-v2 .fusion-header',
			'.fusion-mobile-menu-design-modern.fusion-header-v3 .fusion-header',
			'.fusion-mobile-menu-design-modern.fusion-header-v4 .fusion-header',
			'.fusion-mobile-menu-design-modern.fusion-header-v5 .fusion-header'
		);
		$css[$media_query][avada_implode( $elements )]['padding-top']    = '20px';
		$css[$media_query][avada_implode( $elements )]['padding-bottom'] = '20px';

		$elements = avada_map_selector( $elements, ' .fusion-row' );
		$css[$media_query][avada_implode( $elements )]['width'] = '100%';

		$elements = array(
			'.fusion-mobile-menu-design-modern.fusion-header-v1 .fusion-logo',
			'.fusion-mobile-menu-design-modern.fusion-header-v2 .fusion-logo',
			'.fusion-mobile-menu-design-modern.fusion-header-v3 .fusion-logo',
			'.fusion-mobile-menu-design-modern.fusion-header-v4 .fusion-logo',
			'.fusion-mobile-menu-design-modern.fusion-header-v5 .fusion-logo'
		);
		$css[$media_query][avada_implode( $elements )]['margin'] = '0 !important';

		$elements = array(
			'.fusion-mobile-menu-design-modern.fusion-header-v1 .modern-mobile-menu-expanded .fusion-logo',
			'.fusion-mobile-menu-design-modern.fusion-header-v2 .modern-mobile-menu-expanded .fusion-logo',
			'.fusion-mobile-menu-design-modern.fusion-header-v3 .modern-mobile-menu-expanded .fusion-logo',
			'.fusion-mobile-menu-design-modern.fusion-header-v4 .modern-mobile-menu-expanded .fusion-logo',
			'.fusion-mobile-menu-design-modern.fusion-header-v5 .modern-mobile-menu-expanded .fusion-logo'
		);
		$css[$media_query][avada_implode( $elements )]['margin-bottom'] = '20px !important';

		$elements = array(
			'.fusion-mobile-menu-design-modern.fusion-header-v1 .fusion-mobile-nav-holder',
			'.fusion-mobile-menu-design-modern.fusion-header-v2 .fusion-mobile-nav-holder',
			'.fusion-mobile-menu-design-modern.fusion-header-v3 .fusion-mobile-nav-holder',
			'.fusion-mobile-menu-design-modern.fusion-header-v4 .fusion-mobile-nav-holder',
			'.fusion-mobile-menu-design-modern.fusion-header-v5 .fusion-mobile-nav-holder'
		);
		$css[$media_query][avada_implode( $elements )]['padding-top']   = '20px';
		$css[$media_query][avada_implode( $elements )]['margin-left']   = '-30px';
		$css[$media_query][avada_implode( $elements )]['margin-right']  = '-30px';
		$css[$media_query][avada_implode( $elements )]['margin-bottom'] = '-20px';

		$elements = avada_map_selector( $elements, ' > ul' );
		$css[$media_query][avada_implode( $elements )]['display'] = 'block';

		$elements = array(
			'.fusion-mobile-menu-design-modern.fusion-header-v1 .fusion-mobile-sticky-nav-holder',
			'.fusion-mobile-menu-design-modern.fusion-header-v2 .fusion-mobile-sticky-nav-holder',
			'.fusion-mobile-menu-design-modern.fusion-header-v3 .fusion-mobile-sticky-nav-holder',
			'.fusion-mobile-menu-design-modern.fusion-header-v4 .fusion-mobile-sticky-nav-holder',
			'.fusion-mobile-menu-design-modern.fusion-header-v5 .fusion-mobile-sticky-nav-holder'
		);
		$css[$media_query][avada_implode( $elements )]['display'] = 'none';

		$elements = array(
			'.fusion-mobile-menu-design-modern.fusion-header-v1 .fusion-mobile-menu-icons',
			'.fusion-mobile-menu-design-modern.fusion-header-v2 .fusion-mobile-menu-icons',
			'.fusion-mobile-menu-design-modern.fusion-header-v3 .fusion-mobile-menu-icons',
			'.fusion-mobile-menu-design-modern.fusion-header-v4 .fusion-mobile-menu-icons',
			'.fusion-mobile-menu-design-modern.fusion-header-v5 .fusion-mobile-menu-icons'
		);
		$css[$media_query][avada_implode( $elements )]['display'] = 'block';


		$css[$media_query]['.fusion-mobile-menu-design-modern .fusion-secondary-header']['padding'] = '0px';

		$css[$media_query]['.fusion-mobile-menu-design-modern .fusion-secondary-header .fusion-row']['padding-left']  = '0px';
		$css[$media_query]['.fusion-mobile-menu-design-modern .fusion-secondary-header .fusion-row']['padding-right'] = '0px';

		$css[$media_query]['.fusion-mobile-menu-design-modern .fusion-social-links-header']['max-width']  = '100%';
		$css[$media_query]['.fusion-mobile-menu-design-modern .fusion-social-links-header']['text-align'] = 'center';
		$css[$media_query]['.fusion-mobile-menu-design-modern .fusion-social-links-header']['margin-top'] = '10px';
		$css[$media_query]['.fusion-mobile-menu-design-modern .fusion-social-links-header']['margin-bottom'] = '8px';

		$css[$media_query]['.fusion-mobile-menu-design-modern .fusion-social-links-header a']['margin-right']  = '20px';
		$css[$media_query]['.fusion-mobile-menu-design-modern .fusion-social-links-header a']['margin-bottom'] = '5px';

		$css[$media_query]['.fusion-mobile-menu-design-modern .fusion-alignleft']['border-bottom'] = '1px solid transparent';

		$elements = array(
			'.fusion-mobile-menu-design-modern .fusion-alignleft',
			'.fusion-mobile-menu-design-modern .fusion-alignright'
		);
		$css[$media_query][avada_implode( $elements )]['width']      = '100%';
		$css[$media_query][avada_implode( $elements )]['float']      = 'none';
		$css[$media_query][avada_implode( $elements )]['display']    = 'block';


		$elements = array(
			'.fusion-body .fusion-mobile-menu-design-modern .fusion-secondary-header .fusion-alignleft',
			'.fusion-body .fusion-mobile-menu-design-modern .fusion-secondary-header .fusion-alignright'
		);
		$css[$media_query][avada_implode( $elements )]['text-align'] = 'center';

		$css[$media_query]['.fusion-mobile-menu-design-modern .fusion-secondary-menu > ul > li']['display']    				= 'inline-block';
		$css[$media_query]['.fusion-body .fusion-mobile-menu-design-modern .fusion-secondary-menu > ul > li']['float']      = 'none';
		$css[$media_query]['.fusion-mobile-menu-design-modern .fusion-secondary-menu > ul > li']['text-align'] 				= 'left';

		$css[$media_query]['.fusion-mobile-menu-design-modern .fusion-secondary-menu-cart']['border-right'] = '0';

		$css[$media_query]['.fusion-mobile-menu-design-modern .fusion-secondary-menu-icon']['background-color'] = 'transparent';
		$css[$media_query]['.fusion-mobile-menu-design-modern .fusion-secondary-menu-icon']['padding-left']     = '10px';
		$css[$media_query]['.fusion-mobile-menu-design-modern .fusion-secondary-menu-icon']['padding-right']    = '7px';
		$css[$media_query]['.fusion-mobile-menu-design-modern .fusion-secondary-menu-icon']['min-width']        = '100%';

		$css[$media_query]['.fusion-mobile-menu-design-modern .fusion-secondary-menu-icon:after']['display'] = 'none';

		$elements = array(
			'.fusion-mobile-menu-design-modern .fusion-secondary-menu .fusion-secondary-menu-icon',
			'.fusion-mobile-menu-design-modern .fusion-secondary-menu .fusion-secondary-menu-icon:hover',
			'.fusion-mobile-menu-design-modern .fusion-secondary-menu-icon:before'
		);
		$css[$media_query][avada_implode( $elements )]['color'] = Avada_Sanitize::color( Avada()->settings->get( 'snav_color' ), Avada()->settings->get_default( 'snav_color' ) );

		$css[$media_query]['.fusion-mobile-menu-design-modern .fusion-header-tagline']['margin-top']  = '10px';
		$css[$media_query]['.fusion-mobile-menu-design-modern .fusion-header-tagline']['float']       = 'none';
		$css[$media_query]['.fusion-mobile-menu-design-modern .fusion-header-tagline']['line-height'] = '24px';

		$css[$media_query]['.fusion-mobile-menu-design-modern.fusion-header-v4 .fusion-logo']['width'] = '50%';
		$css[$media_query]['.fusion-mobile-menu-design-modern.fusion-header-v4 .fusion-logo']['float'] = 'left';

		$css[$media_query]['.fusion-mobile-menu-design-modern.fusion-header-v4 .fusion-logo a']['float'] = 'none';

		$css[$media_query]['.fusion-mobile-menu-design-modern.fusion-header-v4 .fusion-logo .searchform']['float']   = 'none';
		$css[$media_query]['.fusion-mobile-menu-design-modern.fusion-header-v4 .fusion-logo .searchform']['display'] = 'none';

		$css[$media_query]['.fusion-mobile-menu-design-modern.fusion-header-v4 .fusion-header-banner']['margin-top'] = '10px';

		$css[$media_query]['.fusion-mobile-menu-design-modern.fusion-header-v5.fusion-logo-center .fusion-logo']['float'] = 'left';

		if ( is_rtl() ) {
			$css[$media_query]['.rtl .fusion-mobile-menu-design-modern.fusion-header-v5.fusion-logo-center .fusion-logo']['float'] = 'right';

			$css[$media_query]['.rtl .fusion-mobile-menu-design-modern.fusion-header-v5.fusion-logo-center .fusion-mobile-menu-icons']['float'] = 'left';

			$css[$media_query]['.rtl .fusion-mobile-menu-design-modern.fusion-header-v5.fusion-logo-center .fusion-mobile-menu-icons a']['float']        = 'left';
			$css[$media_query]['.rtl .fusion-mobile-menu-design-modern.fusion-header-v5.fusion-logo-center .fusion-mobile-menu-icons a']['margin-left']  = '0';
			$css[$media_query]['.rtl .fusion-mobile-menu-design-modern.fusion-header-v5.fusion-logo-center .fusion-mobile-menu-icons a']['margin-right'] = '15px';
		}

		$elements = array(
			'.fusion-mobile-menu-design-modern.fusion-header-v4 .fusion-mobile-nav-holder',
			'.fusion-mobile-menu-design-modern.fusion-header-v5 .fusion-mobile-nav-holder'
		);
		$css[$media_query][avada_implode( $elements )]['padding-top']   = '0';
		$css[$media_query][avada_implode( $elements )]['margin-left']   = '-30px';
		$css[$media_query][avada_implode( $elements )]['margin-right']  = '-30px';
		$css[$media_query][avada_implode( $elements )]['margin-bottom'] = '0';

		$elements = array(
			'.fusion-mobile-menu-design-modern.fusion-header-v4 .fusion-secondary-main-menu',
			'.fusion-mobile-menu-design-modern.fusion-header-v5 .fusion-secondary-main-menu'
		);
		$css[$media_query][avada_implode( $elements )]['position'] = 'static';
		$css[$media_query][avada_implode( $elements )]['border']   = '0';

		$elements = array(
			'.fusion-mobile-menu-design-modern.fusion-header-v4 .fusion-secondary-main-menu .fusion-mobile-nav-holder > ul',
			'.fusion-mobile-menu-design-modern.fusion-header-v5 .fusion-secondary-main-menu .fusion-mobile-nav-holder > ul'
		);
		$css[$media_query][avada_implode( $elements )]['border'] = '0';

		$elements = array(
			'.fusion-mobile-menu-design-modern.fusion-header-v4 .fusion-secondary-main-menu .searchform',
			'.fusion-mobile-menu-design-modern.fusion-header-v5 .fusion-secondary-main-menu .searchform'
		);
		$css[$media_query][avada_implode( $elements )]['float'] = 'none';

		$elements = array(
			'.fusion-is-sticky .fusion-mobile-menu-design-modern.fusion-header-v4 .fusion-sticky-header-wrapper',
			'.fusion-is-sticky .fusion-mobile-menu-design-modern.fusion-header-v5 .fusion-sticky-header-wrapper'
		);
		$css[$media_query][avada_implode( $elements )]['position'] = 'fixed';
		$css[$media_query][avada_implode( $elements )]['width']    = '100%';

		$elements = array(
			'.fusion-mobile-menu-design-modern.fusion-logo-right.fusion-header-v4 .fusion-logo',
			'.fusion-mobile-menu-design-modern.fusion-logo-right.fusion-header-v5 .fusion-logo'
		);
		$css[$media_query][avada_implode( $elements )]['float'] = 'right';

		$elements = array(
			'.fusion-mobile-menu-design-modern.fusion-sticky-menu-only.fusion-header-v4 .fusion-secondary-main-menu',
			'.fusion-mobile-menu-design-modern.fusion-sticky-menu-only.fusion-header-v5 .fusion-secondary-main-menu'
		);
		$css[$media_query][avada_implode( $elements )]['position'] = 'static';

		$elements = array(
			'.fusion-mobile-menu-design-modern.fusion-sticky-menu-only.fusion-header-v4 .fusion-header-tagline',
			'.fusion-mobile-menu-design-modern.fusion-sticky-menu-only.fusion-header-v5 .fusion-header-tagline'
		);
		$css[$media_query][avada_implode( $elements )]['display'] = 'none';

		$elements = array(
			'.fusion-mobile-menu-design-classic.fusion-header-v1 .fusion-mobile-sticky-nav-holder',
			'.fusion-mobile-menu-design-classic.fusion-header-v2 .fusion-mobile-sticky-nav-holder',
			'.fusion-mobile-menu-design-classic.fusion-header-v3 .fusion-mobile-sticky-nav-holder',
			'.fusion-mobile-menu-design-classic.fusion-header-v4 .fusion-mobile-sticky-nav-holder',
			'.fusion-mobile-menu-design-classic.fusion-header-v5 .fusion-mobile-sticky-nav-holder',
			'.fusion-mobile-menu-design-modern.fusion-header-v1 .fusion-mobile-sticky-nav-holder',
			'.fusion-mobile-menu-design-modern.fusion-header-v2 .fusion-mobile-sticky-nav-holder',
			'.fusion-mobile-menu-design-modern.fusion-header-v3 .fusion-mobile-sticky-nav-holder',
			'.fusion-mobile-menu-design-modern.fusion-header-v4 .fusion-mobile-sticky-nav-holder',
			'.fusion-mobile-menu-design-modern.fusion-header-v5 .fusion-mobile-sticky-nav-holder'
		);
		$css[$media_query][avada_implode( $elements )]['display'] = 'none';

		$elements = array(
			'.fusion-is-sticky .fusion-mobile-menu-design-classic.fusion-header-v1.fusion-sticky-menu-1 .fusion-mobile-nav-holder',
			'.fusion-is-sticky .fusion-mobile-menu-design-classic.fusion-header-v2.fusion-sticky-menu-1 .fusion-mobile-nav-holder',
			'.fusion-is-sticky .fusion-mobile-menu-design-classic.fusion-header-v3.fusion-sticky-menu-1 .fusion-mobile-nav-holder',
			'.fusion-is-sticky .fusion-mobile-menu-design-classic.fusion-header-v4.fusion-sticky-menu-1 .fusion-mobile-nav-holder',
			'.fusion-is-sticky .fusion-mobile-menu-design-classic.fusion-header-v5.fusion-sticky-menu-1 .fusion-mobile-nav-holder',
			'.fusion-is-sticky .fusion-mobile-menu-design-modern.fusion-header-v1.fusion-sticky-menu-1 .fusion-mobile-nav-holder',
			'.fusion-is-sticky .fusion-mobile-menu-design-modern.fusion-header-v2.fusion-sticky-menu-1 .fusion-mobile-nav-holder',
			'.fusion-is-sticky .fusion-mobile-menu-design-modern.fusion-header-v3.fusion-sticky-menu-1 .fusion-mobile-nav-holder',
			'.fusion-is-sticky .fusion-mobile-menu-design-modern.fusion-header-v4.fusion-sticky-menu-1 .fusion-mobile-nav-holder',
			'.fusion-is-sticky .fusion-mobile-menu-design-modern.fusion-header-v5.fusion-sticky-menu-1 .fusion-mobile-nav-holder'
		);
		$css[$media_query][avada_implode( $elements )]['display'] = 'none';

		$elements = array(
			'.fusion-mobile-menu-design-classic .fusion-mobile-nav-item',
			'.fusion-mobile-menu-design-modern .fusion-mobile-nav-item',
			'.fusion-mobile-menu-design-classic .fusion-mobile-selector',
			'.fusion-mobile-menu-design-modern .fusion-mobile-selector'
		);

		if ( in_array( Avada()->settings->get( 'mobile_menu_text_align' ), array( 'left', 'right', 'center' ) ) ) {
			$css[$media_query][avada_implode( $elements )]['text-align'] = esc_attr( Avada()->settings->get( 'mobile_menu_text_align' ) );
		}

		if ( 'right' == Avada()->settings->get( 'mobile_menu_text_align' ) ) {

			$elements = array(
				'.fusion-mobile-menu-design-classic .fusion-selector-down',
				'.fusion-mobile-menu-design-modern .fusion-selector-down'
			);
			$css[$media_query][avada_implode( $elements )]['left']               = '7px';
			$css[$media_query][avada_implode( $elements )]['right']              = '0px';
			$css[$media_query][avada_implode( $elements )]['border-left']        = '0px';
			$css[$media_query][avada_implode( $elements )]['border-right-width'] = '1px';
			$css[$media_query][avada_implode( $elements )]['border-right-style'] = 'solid';

			$elements = avada_map_selector( $elements, ':before' );
			$css[$media_query][avada_implode( $elements )]['margin-left']  = '0';
			$css[$media_query][avada_implode( $elements )]['margin-right'] = '12px';

			$elements = array(
				'.fusion-mobile-menu-design-classic .fusion-open-submenu',
				'.fusion-mobile-menu-design-modern .fusion-open-submenu'
			);
			$css[$media_query][avada_implode( $elements )]['right'] = 'auto';
			$css[$media_query][avada_implode( $elements )]['left']  = '0';

			$elements = array(
				'.fusion-mobile-menu-design-classic .fusion-mobile-nav-holder li.fusion-mobile-nav-item a:before',
				'.fusion-mobile-menu-design-modern .fusion-mobile-nav-holder li.fusion-mobile-nav-item a:before'
			);
			$css[$media_query][avada_implode( $elements )]['display'] = 'none';

			$elements = array(
				'.fusion-mobile-menu-design-classic .fusion-mobile-nav-holder li.fusion-mobile-nav-item li a',
				'.fusion-mobile-menu-design-modern .fusion-mobile-nav-holder li.fusion-mobile-nav-item li a'
			);
			$css[$media_query][avada_implode( $elements )]['padding-right'] = '27px';

			$elements = avada_map_selector( $elements, ':after' );
			$css[$media_query][avada_implode( $elements )]['content']      = '"-"';
			$css[$media_query][avada_implode( $elements )]['margin-right'] = '-6px';
			$css[$media_query][avada_implode( $elements )]['margin-left']  = '2px';

			$elements = array(
				'.fusion-mobile-menu-design-classic .fusion-mobile-nav-holder li.fusion-mobile-nav-item li li a',
				'.fusion-mobile-menu-design-modern .fusion-mobile-nav-holder li.fusion-mobile-nav-item li li a'
			);
			$css[$media_query][avada_implode( $elements )]['padding-right'] = '40px';

			$elements = avada_map_selector( $elements, ':after' );
			$css[$media_query][avada_implode( $elements )]['content']      = '"--"';
			$css[$media_query][avada_implode( $elements )]['margin-right'] = '-10px';
			$css[$media_query][avada_implode( $elements )]['margin-left']  = '2px';

			$elements = array(
				'.fusion-mobile-menu-design-classic .fusion-mobile-nav-holder li.fusion-mobile-nav-item li li li a',
				'.fusion-mobile-menu-design-modern .fusion-mobile-nav-holder li.fusion-mobile-nav-item li li li a'
			);
			$css[$media_query][avada_implode( $elements )]['padding-right'] = '53px';

			$elements = avada_map_selector( $elements, ':after' );
			$css[$media_query][avada_implode( $elements )]['content']      = '"---"';
			$css[$media_query][avada_implode( $elements )]['margin-right'] = '-14px';
			$css[$media_query][avada_implode( $elements )]['margin-left']  = '2px';

			$elements = array(
				'.fusion-mobile-menu-design-classic .fusion-mobile-nav-holder li.fusion-mobile-nav-item li li li li a',
				'.fusion-mobile-menu-design-modern .fusion-mobile-nav-holder li.fusion-mobile-nav-item li li li li a'
			);
			$css[$media_query][avada_implode( $elements )]['padding-right'] = '66px';

			$elements = avada_map_selector( $elements, ':after' );
			$css[$media_query][avada_implode( $elements )]['content']      = '"----"';
			$css[$media_query][avada_implode( $elements )]['margin-right'] = '-18px';
			$css[$media_query][avada_implode( $elements )]['margin-left']  = '2px';

		}

		$elements = array(
			'.fusion-is-sticky .fusion-mobile-menu-design-classic.fusion-header-v1.fusion-sticky-menu-1 .fusion-mobile-sticky-nav-holder',
			'.fusion-is-sticky .fusion-mobile-menu-design-classic.fusion-header-v2.fusion-sticky-menu-1 .fusion-mobile-sticky-nav-holder',
			'.fusion-is-sticky .fusion-mobile-menu-design-classic.fusion-header-v3.fusion-sticky-menu-1 .fusion-mobile-sticky-nav-holder',
			'.fusion-is-sticky .fusion-mobile-menu-design-classic.fusion-header-v4.fusion-sticky-menu-1 .fusion-mobile-sticky-nav-holder',
			'.fusion-is-sticky .fusion-mobile-menu-design-classic.fusion-header-v5.fusion-sticky-menu-1 .fusion-mobile-sticky-nav-holder'
		);
		$css[$media_query][avada_implode( $elements )]['display'] = 'block';

		$css[$media_query]['.fusion-mobile-menu-design-classic .fusion-mobile-nav-holder .fusion-secondary-menu-icon']['text-align'] = 'inherit';

		$elements = array(
			'.fusion-mobile-menu-design-classic .fusion-mobile-nav-holder .fusion-secondary-menu-icon:before',
			'.fusion-mobile-menu-design-classic .fusion-mobile-nav-holder .fusion-secondary-menu-icon:after'
		);
		$css[$media_query][avada_implode( $elements )]['display'] = 'none';

		/**
		 * Page Title Bar
		 */

		if ( 'auto' != Avada()->settings->get( 'page_title_mobile_height' ) ) {

			$css[$media_query]['.fusion-body .fusion-page-title-bar']['padding-top']    = '5px';
			$css[$media_query]['.fusion-body .fusion-page-title-bar']['padding-bottom'] = '5px';
			$css[$media_query]['.fusion-body .fusion-page-title-bar']['min-height']     = fusion_strip_unit( Avada()->settings->get( 'page_title_mobile_height' ) ) - 10 . 'px';
			$css[$media_query]['.fusion-body .fusion-page-title-bar']['height']         = 'auto';

		} else {

			$css[$media_query]['.fusion-body .fusion-page-title-bar']['padding-top']    = '10px';
			$css[$media_query]['.fusion-body .fusion-page-title-bar']['padding-bottom'] = '10px';
			$css[$media_query]['.fusion-body .fusion-page-title-bar']['height']         = 'auto';
			$css[$media_query]['.fusion-page-title-row']['height'] = 'auto';

		}

		$elements = array(
			'.fusion-page-title-bar-left .fusion-page-title-captions',
			'.fusion-page-title-bar-right .fusion-page-title-captions',
			'.fusion-page-title-bar-left .fusion-page-title-secondary',
			'.fusion-page-title-bar-right .fusion-page-title-secondary'
		);
		$css[$media_query][avada_implode( $elements )]['display']     = 'block';
		$css[$media_query][avada_implode( $elements )]['float']       = 'none';
		$css[$media_query][avada_implode( $elements )]['width']       = '100%';
		$css[$media_query][avada_implode( $elements )]['line-height'] = 'normal';

		$css[$media_query]['.fusion-page-title-bar-left .fusion-page-title-secondary']['text-align'] = 'left';

		$css[$media_query]['.fusion-page-title-bar-left .searchform']['display']   = 'block';
		$css[$media_query]['.fusion-page-title-bar-left .searchform']['max-width'] = '100%';

		$css[$media_query]['.fusion-page-title-bar-right .fusion-page-title-secondary']['text-align'] = 'right';

		$css[$media_query]['.fusion-page-title-bar-right .searchform']['max-width'] = '100%';

		if ( 'auto' != Avada()->settings->get( 'page_title_mobile_height' ) ) {

			$css[$media_query]['.fusion-page-title-row']['display']    = 'table';
			$css[$media_query]['.fusion-page-title-row']['width']      = '100%';
			$css[$media_query]['.fusion-page-title-row']['min-height'] = fusion_strip_unit( Avada()->settings->get( 'page_title_mobile_height' ) ) - 20 . 'px';

			$css[$media_query]['.fusion-page-title-bar-center .fusion-page-title-row']['width'] = 'auto';

			$css[$media_query]['.fusion-page-title-wrapper']['display']        = 'table-cell';
			$css[$media_query]['.fusion-page-title-wrapper']['vertical-align'] = 'middle';

		}

		// Blog medium alternate layout

		$elements = array(
			'.fusion-body .fusion-blog-layout-medium-alternate .fusion-post-content',
			'.fusion-body .fusion-blog-layout-medium-alternate .has-post-thumbnail .fusion-post-content'
		);
		$css[$media_query][avada_implode( $elements )]['float']       = 'none';
		$css[$media_query][avada_implode( $elements )]['clear']       = 'both';
		$css[$media_query][avada_implode( $elements )]['margin']      = '0';
		$css[$media_query][avada_implode( $elements )]['padding-top'] = '20px';

		// Author Page - Info

		$css[$media_query]['.fusion-author .fusion-social-networks']['display'] = 'block';
		$css[$media_query]['.fusion-body .fusion-author .fusion-social-networks']['text-align'] = 'center';
		$css[$media_query]['.fusion-author .fusion-social-networks']['margin-top'] = '10px';

		$css[$media_query]['.fusion-author .fusion-social-networks .fusion-social-network-icon:first-child']['margin-left'] = '0';

		$css[$media_query]['.fusion-author-tagline']['display']      = 'block';
		$css[$media_query]['.fusion-author-tagline']['float']      = 'none';
		$css[$media_query]['.fusion-author-tagline']['text-align'] = 'center';
		$css[$media_query]['.fusion-author-tagline']['max-width']  = '100%';

		// Mobile Logo
		$elements = array(
			'.fusion-mobile-logo-1 .fusion-standard-logo',
			'#side-header .fusion-mobile-logo-1 .fusion-standard-logo'
		);
		$css[$media_query][avada_implode( $elements )]['display'] = 'none';

		$elements = array(
			'.fusion-mobile-logo-1 .fusion-mobile-logo-1x',
			'#side-header .fusion-mobile-logo-1 .fusion-mobile-logo-1x'
		);
		$css[$media_query][avada_implode( $elements )]['display'] = 'inline-block';

		$css[$media_query]['.fusion-secondary-menu-icon']['min-width'] = '100%';

		$elements = array(
			'.fusion-content-boxes.content-boxes-clean-vertical .content-box-column',
			'.fusion-content-boxes.content-boxes-clean-horizontal .content-box-column'
		);
		$css[$media_query][avada_implode( $elements )]['border-right-width'] = '1px';

		$elements = array(
			'.fusion-content-boxes .content-box-shortcode-timeline'
		);
		$css[$media_query][avada_implode( $elements )]['display'] = 'none';

		$media_query = '@media only screen and (max-width: ' . ( intval( $side_header_width ) + 800 ) . 'px) and (-webkit-min-device-pixel-ratio: 1.5), only screen and (max-width: ' . ( $side_header_width + 800 ) . 'px) and (min-resolution: 144dpi), only screen and (max-width: ' . ( $side_header_width + 800 ) . 'px) and (min-resolution: 1.5dppx)';

		$elements = array(
			'.fusion-mobile-logo-1 .fusion-mobile-logo-1x',
			'#side-header .fusion-mobile-logo-1 .fusion-mobile-logo-1x'
		);
		$css[$media_query][avada_implode( $elements )]['display'] = 'none';

		$elements = array(
			'.fusion-mobile-logo-1 .fusion-mobile-logo-2x',
			'#side-header .fusion-mobile-logo-1 .fusion-mobile-logo-2x'
		);
		$css[$media_query][avada_implode( $elements )]['display'] = 'inline-block';

		/**
		 * only screen and ( max-width: 640px )
		 */

		$media_query = '@media only screen and (max-width: ' . ( intval( $side_header_width ) + 640 ) . 'px)';

		// Page Title Bar

		$css[$media_query]['.fusion-body .fusion-page-title-bar']['max-height'] = 'none';

		$css[$media_query]['.fusion-body .fusion-page-title-bar h1']['margin'] = '0';

		$css[$media_query]['.fusion-body .fusion-page-title-secondary']['margin-top'] = '2px';

		// Blog general styles
		$elements = array(
			'.fusion-blog-layout-large .fusion-meta-info .fusion-alignleft',
			'.fusion-blog-layout-medium .fusion-meta-info .fusion-alignleft',
			'.fusion-blog-layout-large .fusion-meta-info .fusion-alignright',
			'.fusion-blog-layout-medium .fusion-meta-info .fusion-alignright'
		);
		$css[$media_query][avada_implode( $elements )]['display'] = 'block';
		$css[$media_query][avada_implode( $elements )]['float']   = 'none';
		$css[$media_query][avada_implode( $elements )]['margin']  = '0';
		$css[$media_query][avada_implode( $elements )]['width']   = '100%';

		// Blog medium layout
		$css[$media_query]['.fusion-body .fusion-blog-layout-medium .fusion-post-slideshow']['float']  = 'none';
		$css[$media_query]['.fusion-body .fusion-blog-layout-medium .fusion-post-slideshow']['margin'] = '0 0 20px 0';
		$css[$media_query]['.fusion-body .fusion-blog-layout-medium .fusion-post-slideshow']['height'] = 'auto';
		$css[$media_query]['.fusion-body .fusion-blog-layout-medium .fusion-post-slideshow']['width']  = 'auto';

		// Blog large alternate layout
		$css[$media_query]['.fusion-blog-layout-large-alternate .fusion-date-and-formats']['margin-bottom'] = '55px';

		$css[$media_query]['.fusion-body .fusion-blog-layout-large-alternate .fusion-post-content']['margin'] = '0';

		// Blog medium alternate layout
		$css[$media_query]['.fusion-blog-layout-medium-alternate .has-post-thumbnail .fusion-post-slideshow']['display']      = 'inline-block';
		$css[$media_query]['.fusion-blog-layout-medium-alternate .has-post-thumbnail .fusion-post-slideshow']['float']        = 'none';
		$css[$media_query]['.fusion-blog-layout-medium-alternate .has-post-thumbnail .fusion-post-slideshow']['margin-right'] = '0';
		$css[$media_query]['.fusion-blog-layout-medium-alternate .has-post-thumbnail .fusion-post-slideshow']['max-width']    = '197px';

		// Blog grid layout
		$css[$media_query]['.fusion-blog-layout-grid .fusion-post-grid']['position'] = 'static';
		$css[$media_query]['.fusion-blog-layout-grid .fusion-post-grid']['width']    = '100%';

		/**
		 * media.css CSS
		 */

		$media_query = '@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) and (orientation: portrait)';

		$elements = array(
			'.fusion-secondary-header .fusion-row',
			'.fusion-header .fusion-row',
			'.footer-area > .fusion-row',
			'#footer > .fusion-row'
		);
		$css[$media_query][avada_implode( $elements )]['padding-left']  = '0px !important';
		$css[$media_query][avada_implode( $elements )]['padding-right'] = '0px !important';

		$media_query = '@media only screen and (max-width: ' . ( intval( $side_header_width ) + 1000 ) . 'px)';
		$css[$media_query]['.no-csstransforms .sep-boxed-pricing .column']['margin-left'] = '1.5% !important';

		if ( class_exists( 'WooCommerce' ) ) {

			$media_query = '@media only screen and (max-width: ' . ( intval( $side_header_width ) + 965 ) . 'px)';

			$elements = array(
				'#wrapper .woocommerce-tabs .tabs',
				'#wrapper .woocommerce-tabs .panel'
			);
			$css[$media_query][avada_implode( $elements )]['float']        = 'none';
			$css[$media_query][avada_implode( $elements )]['margin-left']  = 'auto';
			$css[$media_query][avada_implode( $elements )]['margin-right'] = 'auto';
			$css[$media_query][avada_implode( $elements )]['width']        = '100% !important';

			$elements = array(
				'.woocommerce-tabs .tabs',
				'.woocommerce-side-nav'
			);
			$css[$media_query][avada_implode( $elements )]['margin-bottom'] = '25px';

			$css[$media_query]['.coupon .input-text']['width'] = '100% !important';

			$css[$media_query]['.coupon .button']['margin-top'] = '20px';

			$media_query = '@media only screen and (max-width: ' . ( intval( $side_header_width ) + 900 ) . 'px)';

			$elements = array(
				'.woocommerce #customer_login .login .form-row',
				'.woocommerce #customer_login .login .lost_password'
			);
			$css[$media_query][avada_implode( $elements )]['float'] = 'none';

			$elements = array(
				'.woocommerce #customer_login .login .inline',
				'.woocommerce #customer_login .login .lost_password'
			);
			$css[$media_query][avada_implode( $elements )]['display']      = 'block';
			$css[$media_query][avada_implode( $elements )]['margin-left']  = '0';
			$css[$media_query][avada_implode( $elements )]['margin-right'] = '0';

		}

		$media_query = '@media only screen and (min-width: 800px)';

		$css[$media_query]['body.side-header-right.layout-boxed-mode #side-header']['position'] = 'absolute';
		$css[$media_query]['body.side-header-right.layout-boxed-mode #side-header']['top']      = '0';

		$css[$media_query]['body.side-header-right.layout-boxed-mode #side-header .side-header-wrapper']['position'] = 'absolute';

		$media_query = '@media only screen and (max-width: 800px)';

		$elements = array(
			'.fusion-columns-5 .fusion-column:first-child',
			'.fusion-columns-4 .fusion-column:first-child',
			'.fusion-columns-3 .fusion-column:first-child',
			'.fusion-columns-2 .fusion-column:first-child',
			'.fusion-columns-1 .fusion-column:first-child'
		);
		$css[$media_query][avada_implode( $elements )]['margin-left'] = '0';

		$elements = array(
			'.fusion-columns-5 .col-lg-2',
			'.fusion-columns-5 .col-md-2',
			'.fusion-columns-5 .col-sm-2'
		);
		$css[$media_query][avada_implode( $elements )]['width'] = '100%';

		$css[$media_query]['.fusion-columns .fusion-column']['float']      = 'none';
		$css[$media_query]['.fusion-columns .fusion-column']['width']      = '100% !important';
		$css[$media_query]['.fusion-columns .fusion-column']['margin']     = '0 0 50px';
		$css[$media_query]['.fusion-columns .fusion-column']['box-sizing'] = 'border-box';

		if ( is_rtl() ) {
			$css[$media_query]['.rtl .fusion-column']['float'] = 'none';
		}

		$css[$media_query]['.avada-container .columns']['float']         = 'none';
		$css[$media_query]['.avada-container .columns']['width']         = '100%';
		$css[$media_query]['.avada-container .columns']['margin-bottom'] = '20px';

		$css[$media_query]['.avada-container .columns .col']['float'] = 'left';

		$css[$media_query]['.avada-container .col img']['display'] = 'block';
		$css[$media_query]['.avada-container .col img']['margin']  = '0 auto';

		$css[$media_query]['#wrapper']['width'] = 'auto !important';
		// $css[$media_query]['#wrapper']['overflow-x'] = 'hidden';

		$css[$media_query]['.create-block-format-context']['display'] = 'none';

		$css[$media_query]['.review']['float'] = 'none';
		$css[$media_query]['.review']['width'] = '100%';

		$elements = array(
			'.fusion-copyright-notice',
			'.fusion-body .fusion-social-links-footer'
		);
		$css[$media_query][avada_implode( $elements )]['display']    = 'block';
		$css[$media_query][avada_implode( $elements )]['text-align'] = 'center';

		$css[$media_query]['.fusion-social-links-footer']['width'] = 'auto';

		$css[$media_query]['.fusion-social-links-footer .fusion-social-networks']['display']    = 'inline-block';
		$css[$media_query]['.fusion-social-links-footer .fusion-social-networks']['float']      = 'none';
		$css[$media_query]['.fusion-social-links-footer .fusion-social-networks']['margin-top'] = '0';

		$css[$media_query]['.fusion-copyright-notice']['padding'] = '0 0 15px';

		$elements = array(
			'.fusion-copyright-notice:after',
			'.fusion-social-networks:after'
		);
		$css[$media_query][avada_implode( $elements )]['content'] = '""';
		$css[$media_query][avada_implode( $elements )]['display'] = 'block';
		$css[$media_query][avada_implode( $elements )]['clear']   = 'both';

		$elements = array(
			'.fusion-social-networks li',
			'.fusion-copyright-notice li'
		);
		$css[$media_query][avada_implode( $elements )]['float']   = 'none';
		$css[$media_query][avada_implode( $elements )]['display'] = 'inline-block';

		$css[$media_query]['.fusion-title']['margin-top']    = '0px !important';
		$css[$media_query]['.fusion-title']['margin-bottom'] = '20px !important';

		$css[$media_query]['#main .cart-empty']['float']         = 'none';
		$css[$media_query]['#main .cart-empty']['text-align']    = 'center';
		$css[$media_query]['#main .cart-empty']['border-top']    = '1px solid';
		$css[$media_query]['#main .cart-empty']['border-bottom'] = 'none';
		$css[$media_query]['#main .cart-empty']['width']         = '100%';
		$css[$media_query]['#main .cart-empty']['line-height']   = 'normal !important';
		$css[$media_query]['#main .cart-empty']['height']        = 'auto !important';
		$css[$media_query]['#main .cart-empty']['margin-bottom'] = '10px';
		$css[$media_query]['#main .cart-empty']['padding-top']   = '10px';

		$css[$media_query]['#main .return-to-shop']['float']          = 'none';
		$css[$media_query]['#main .return-to-shop']['border-top']     = 'none';
		$css[$media_query]['#main .return-to-shop']['border-bottom']  = '1px solid';
		$css[$media_query]['#main .return-to-shop']['width']          = '100%';
		$css[$media_query]['#main .return-to-shop']['text-align']     = 'center';
		$css[$media_query]['#main .return-to-shop']['line-height']    = 'normal !important';
		$css[$media_query]['#main .return-to-shop']['height']         = 'auto !important';
		$css[$media_query]['#main .return-to-shop']['padding-bottom'] = '10px';

		if ( class_exists( 'WooCommerce' ) ) {

			$css[$media_query]['.woocommerce .checkout_coupon .promo-code-heading']['display']       = 'block';
			$css[$media_query]['.woocommerce .checkout_coupon .promo-code-heading']['margin-bottom'] = '10px !important';
			$css[$media_query]['.woocommerce .checkout_coupon .promo-code-heading']['float']         = 'none';
			$css[$media_query]['.woocommerce .checkout_coupon .promo-code-heading']['text-align']    = 'center';

			$css[$media_query]['.woocommerce .checkout_coupon .coupon-contents']['display'] = 'block';
			$css[$media_query]['.woocommerce .checkout_coupon .coupon-contents']['float']   = 'none';
			$css[$media_query]['.woocommerce .checkout_coupon .coupon-contents']['margin']  = '0';

			$css[$media_query]['.woocommerce .checkout_coupon .coupon-input']['display']       = 'block';
			$css[$media_query]['.woocommerce .checkout_coupon .coupon-input']['width']         = 'auto !important';
			$css[$media_query]['.woocommerce .checkout_coupon .coupon-input']['float']         = 'none';
			$css[$media_query]['.woocommerce .checkout_coupon .coupon-input']['text-align']    = 'center';
			$css[$media_query]['.woocommerce .checkout_coupon .coupon-input']['margin-right']  = '0';
			$css[$media_query]['.woocommerce .checkout_coupon .coupon-input']['margin-bottom'] = '10px !important';

			$css[$media_query]['.woocommerce .checkout_coupon .coupon-button']['display']      = 'block';
			$css[$media_query]['.woocommerce .checkout_coupon .coupon-button']['margin-right'] = '0';
			$css[$media_query]['.woocommerce .checkout_coupon .coupon-button']['float']        = 'none';
			$css[$media_query]['.woocommerce .checkout_coupon .coupon-button']['text-align']   = 'center';

		}

		$css[$media_query]['#content.full-width']['margin-bottom'] = '0';

		$css[$media_query]['.sidebar .social_links .social li']['width']        = 'auto';
		$css[$media_query]['.sidebar .social_links .social li']['margin-right'] = '5px';

		$css[$media_query]['#comment-input']['margin-bottom'] = '0';

		$css[$media_query]['#comment-input input']['width']         = '90%';
		$css[$media_query]['#comment-input input']['float']         = 'none !important';
		$css[$media_query]['#comment-input input']['margin-bottom'] = '10px';

		$css[$media_query]['#comment-textarea textarea']['width'] = '90%';

		$css[$media_query]['.widget.facebook_like iframe']['width']     = '100% !important';
		$css[$media_query]['.widget.facebook_like iframe']['max-width'] = 'none !important';

		$css[$media_query]['.pagination']['margin-top'] = '40px';

		$css[$media_query]['.portfolio-one .portfolio-item .image']['float']         = 'none';
		$css[$media_query]['.portfolio-one .portfolio-item .image']['width']         = 'auto';
		$css[$media_query]['.portfolio-one .portfolio-item .image']['height']        = 'auto';
		$css[$media_query]['.portfolio-one .portfolio-item .image']['margin-bottom'] = '20px';

		$css[$media_query]['h5.toggle span.toggle-title']['width'] = '80%';

		$css[$media_query]['#wrapper .sep-boxed-pricing .panel-wrapper']['padding'] = '0';

		$elements = array(
			'#wrapper .full-boxed-pricing .column',
			'#wrapper .sep-boxed-pricing .column'
		);
		$css[$media_query][avada_implode( $elements )]['float']         = 'none';
		$css[$media_query][avada_implode( $elements )]['margin-bottom'] = '10px';
		$css[$media_query][avada_implode( $elements )]['margin-left']   = '0';
		$css[$media_query][avada_implode( $elements )]['width']         = '100%';

		$css[$media_query]['.share-box']['height'] = 'auto';

		$css[$media_query]['#wrapper .share-box h4']['float']       = 'none';
		$css[$media_query]['#wrapper .share-box h4']['line-height'] = '20px !important';
		$css[$media_query]['#wrapper .share-box h4']['margin-top']  = '0';
		$css[$media_query]['#wrapper .share-box h4']['padding']     = '0';

		$css[$media_query]['.share-box ul']['float']          = 'none';
		$css[$media_query]['.share-box ul']['overflow']       = 'hidden';
		$css[$media_query]['.share-box ul']['padding']        = '0 25px';
		$css[$media_query]['.share-box ul']['padding-bottom'] = '15px';
		$css[$media_query]['.share-box ul']['margin-top']     = '0px';

		$css[$media_query]['.project-content .project-description']['float'] = 'none !important';

		$css[$media_query]['.single-avada_portfolio .portfolio-half .project-content .project-description h3']['margin-top'] = '24px';

		$css[$media_query]['.project-content .fusion-project-description-details']['margin-bottom'] = '50px';

		$elements = array(
			'.project-content .project-description',
			'.project-content .project-info'
		);
		$css[$media_query][avada_implode( $elements )]['width'] = '100% !important';

		$css[$media_query]['.portfolio-half .flexslider']['width'] = '100% !important';

		$css[$media_query]['.portfolio-half .project-content']['width'] = '100% !important';

		$css[$media_query]['#style_selector']['display'] = 'none';

		$elements = array(
			'.ls-avada .ls-nav-prev',
			'.ls-avada .ls-nav-next'
		);
		$css[$media_query][avada_implode( $elements )]['display'] = 'none !important';

		$css[$media_query]['#footer .social-networks']['width']    = '100%';
		$css[$media_query]['#footer .social-networks']['margin']   = '0 auto';
		$css[$media_query]['#footer .social-networks']['position'] = 'relative';
		$css[$media_query]['#footer .social-networks']['left']     = '-11px';

		$css[$media_query]['.tab-holder .tabs']['height'] = 'auto !important';
		$css[$media_query]['.tab-holder .tabs']['width']  = '100% !important';

		$css[$media_query]['.shortcode-tabs .tab-hold .tabs li']['width'] = '100% !important';

		$elements = array(
			'body .shortcode-tabs .tab-hold .tabs li',
			'body.dark .sidebar .tab-hold .tabs li'
		);
		$css[$media_query][avada_implode( $elements )]['border-right'] = 'none !important';

		$css[$media_query]['.error-message']['line-height'] = '170px';
		$css[$media_query]['.error-message']['margin-top']  = '20px';

		$css[$media_query]['.error_page .useful_links']['width']        = '100%';
		$css[$media_query]['.error-page .useful_links']['padding-left'] = '0';

		$css[$media_query]['.fusion-google-map']['width']         = '100% !important';
		$css[$media_query]['.fusion-google-map']['margin-bottom'] = '20px !important';

		$css[$media_query]['.social_links_shortcode .social li']['width'] = '10% !important';

		$css[$media_query]['#wrapper .ei-slider']['width'] = '100% !important';

		$css[$media_query]['#wrapper .ei-slider']['height'] = '200px !important';

		$css[$media_query]['.progress-bar']['margin-bottom'] = '10px !important';

		$css[$media_query]['#wrapper .content-boxes-icon-boxed .content-wrapper-boxed']['min-height']     = 'inherit !important';
		$css[$media_query]['#wrapper .content-boxes-icon-boxed .content-wrapper-boxed']['padding-bottom'] = '20px';
		$css[$media_query]['#wrapper .content-boxes-icon-boxed .content-wrapper-boxed']['padding-left']   = '3%';
		$css[$media_query]['#wrapper .content-boxes-icon-boxed .content-wrapper-boxed']['padding-right']  = '3%';

		$elements = array(
			'#wrapper .content-boxes-icon-on-top .content-box-column',
			'#wrapper .content-boxes-icon-boxed .content-box-column'
		);
		$css[$media_query][avada_implode( $elements )]['margin-bottom'] = '55px';

		$css[$media_query]['.fusion-counters-box .fusion-counter-box']['margin-bottom'] = '20px';
		$css[$media_query]['.fusion-counters-box .fusion-counter-box']['padding']       = '0 15px';

		$css[$media_query]['.fusion-counters-box .fusion-counter-box:last-child']['margin-bottom'] = '0';

		$css[$media_query]['.popup']['display'] = 'none !important';

		$css[$media_query]['.share-box .social-networks']['text-align'] = 'left';

		if ( class_exists( 'WooCommerce' ) ) {
			$css[$media_query]['.fusion-body .products li']['width'] = '225px';

			$elements = array(
				'.products li',
				'#wrapper .catalog-ordering > ul',
				'#main .products li:nth-child(3n)',
				'#main .products li:nth-child(4n)',
				'#main .has-sidebar .products li',
				'.avada-myaccount-data .addresses .col-1',
				'.avada-myaccount-data .addresses .col-2',
				'.avada-customer-details .addresses .col-1',
				'.avada-customer-details .addresses .col-2'
			);

			$css[$media_query][avada_implode( $elements )]['float']        = 'none !important';
			$css[$media_query][avada_implode( $elements )]['margin-left']  = 'auto !important';
			$css[$media_query][avada_implode( $elements )]['margin-right'] = 'auto !important';

			$elements = array(
				'.avada-myaccount-data .addresses .col-1',
				'.avada-myaccount-data .addresses .col-2',
				'.avada-customer-details .addresses .col-1',
				'.avada-customer-details .addresses .col-2'
			);
			$css[$media_query][avada_implode( $elements )]['margin'] = '0 !important';
			$css[$media_query][avada_implode( $elements )]['width']  = '100%';

			$css[$media_query]['#wrapper .catalog-ordering']['margin-bottom'] = '50px';

			$css[$media_query]['#wrapper .catalog-ordering .order']['width'] = '33px';

			$elements = array(
				'#wrapper .catalog-ordering > ul',
				'.catalog-ordering .order'
			);
			$css[$media_query][avada_implode( $elements )]['margin-bottom'] = '10px';

			$css[$media_query]['#wrapper .order-dropdown > li:hover > ul']['display']  = 'block';
			$css[$media_query]['#wrapper .order-dropdown > li:hover > ul']['position'] = 'relative';
			$css[$media_query]['#wrapper .order-dropdown > li:hover > ul']['top']      = '0';

			$css[$media_query]['#wrapper .orderby-order-container']['overflow']      = 'hidden';
			$css[$media_query]['#wrapper .orderby-order-container']['margin']        = '0 auto';
			$css[$media_query]['#wrapper .orderby-order-container']['width']         = '215px';
			$css[$media_query]['#wrapper .orderby-order-container']['margin-bottom'] = '10px';
			$css[$media_query]['#wrapper .orderby-order-container']['float']         = 'none';

			$css[$media_query]['#wrapper .orderby.order-dropdown']['float']        = 'left';
			$css[$media_query]['#wrapper .orderby.order-dropdown']['margin-right'] = '6px';

			$css[$media_query]['#wrapper .sort-count.order-dropdown']['width'] = '215px';

			$css[$media_query]['#wrapper .sort-count.order-dropdown ul a']['width'] = '215px';

			$css[$media_query]['#wrapper .catalog-ordering .order']['float']  = 'left';
			$css[$media_query]['#wrapper .catalog-ordering .order']['margin'] = '0';

			if ( is_rtl() ) {
				$css[$media_query]['.rtl #wrapper .orderby.order-dropdown']['float']  = 'right';
				$css[$media_query]['.rtl #wrapper .orderby.order-dropdown']['margin'] = '0';

				$css[$media_query]['.rtl #wrapper .catalog-ordering .order']['float']        = 'right';
				$css[$media_query]['.rtl #wrapper .catalog-ordering .order']['margin-right'] = '6px';
			}

			$css[$media_query]['.fusion-grid-list-view']['width'] = '74px';

			$elements = array(
				'.woocommerce #customer_login .login .form-row',
				'.woocommerce #customer_login .login .lost_password'
			);
			$css[$media_query][avada_implode( $elements )]['float'] = 'none';

			$elements = array(
				'.woocommerce #customer_login .login .inline',
				'.woocommerce #customer_login .login .lost_password'
			);
			$css[$media_query][avada_implode( $elements )]['display']     = 'block';
			$css[$media_query][avada_implode( $elements )]['margin-left'] = '0';

			$css[$media_query]['.avada-myaccount-data .my_account_orders .order-number']['padding-right'] = '8px';

			$css[$media_query]['.avada-myaccount-data .my_account_orders .order-actions']['padding-left'] = '8px';

			$css[$media_query]['.shop_table .product-name']['width'] = '35%';

			$css[$media_query]['form.checkout .shop_table tfoot th']['padding-right'] = '20px';

			$elements = array(
				'#wrapper .product .images',
				'#wrapper .product .summary.entry-summary',
				'#wrapper .woocommerce-tabs .tabs',
				'#wrapper .woocommerce-tabs .panel',
				'#wrapper .woocommerce-side-nav',
				'#wrapper .woocommerce-content-box',
				'#wrapper .shipping-coupon',
				'#wrapper .cart-totals-buttons',
				'#wrapper #customer_login .col-1',
				'#wrapper #customer_login .col-2',
				'#wrapper .woocommerce form.checkout #customer_details .col-1',
				'#wrapper .woocommerce form.checkout #customer_details .col-2'
			);
			$css[$media_query][avada_implode( $elements )]['float']        = 'none';
			$css[$media_query][avada_implode( $elements )]['margin-left']  = 'auto';
			$css[$media_query][avada_implode( $elements )]['margin-right'] = 'auto';
			$css[$media_query][avada_implode( $elements )]['width']        = '100% !important';

			$elements = array(
				'#customer_login .col-1',
				'.coupon'
			);
			$css[$media_query][avada_implode( $elements )]['margin-bottom'] = '20px';

			$css[$media_query]['.shop_table .product-thumbnail']['float'] = 'none';

			$css[$media_query]['.product-info']['margin-left'] = '0';
			$css[$media_query]['.product-info']['margin-top']  = '10px';

			$css[$media_query]['.product .entry-summary div .price']['float'] = 'none';

			$css[$media_query]['.product .entry-summary .woocommerce-product-rating']['float']       = 'none';
			$css[$media_query]['.product .entry-summary .woocommerce-product-rating']['margin-left'] = '0';

			$elements = array(
				'.woocommerce-tabs .tabs',
				'.woocommerce-side-nav'
			);
			$css[$media_query][avada_implode( $elements )]['margin-bottom'] = '25px';

			$css[$media_query]['.woocommerce-tabs .panel']['width']   = '91% !important';
			$css[$media_query]['.woocommerce-tabs .panel']['padding'] = '4% !important';

			$css[$media_query]['#reviews li .avatar']['display'] = 'none';

			$css[$media_query]['#reviews li .comment-text']['width']       = '90% !important';
			$css[$media_query]['#reviews li .comment-text']['margin-left'] = '0 !important';
			$css[$media_query]['#reviews li .comment-text']['padding']     = '5% !important';

			$css[$media_query]['.woocommerce-container .social-share']['overflow'] = 'hidden';

			$css[$media_query]['.woocommerce-container .social-share li']['display']       = 'block';
			$css[$media_query]['.woocommerce-container .social-share li']['float']         = 'left';
			$css[$media_query]['.woocommerce-container .social-share li']['margin']        = '0 auto';
			$css[$media_query]['.woocommerce-container .social-share li']['border-right']  = '0 !important';
			$css[$media_query]['.woocommerce-container .social-share li']['border-left']   = '0 !important';
			$css[$media_query]['.woocommerce-container .social-share li']['padding-left']  = '0 !important';
			$css[$media_query]['.woocommerce-container .social-share li']['padding-right'] = '0 !important';
			$css[$media_query]['.woocommerce-container .social-share li']['width']         = '50%';

			$css[$media_query]['.has-sidebar .woocommerce-container .social-share li']['width'] = '50%';

			$css[$media_query]['.myaccount_user_container span']['width']        = '100%';
			$css[$media_query]['.myaccount_user_container span']['float']        = 'none';
			$css[$media_query]['.myaccount_user_container span']['display']      = 'block';
			$css[$media_query]['.myaccount_user_container span']['padding']      = '5px 0px';
			$css[$media_query]['.myaccount_user_container span']['border-right'] = 0;

			$css[$media_query]['.myaccount_user_container span.username']['margin-top'] = '10px';

			$css[$media_query]['.myaccount_user_container span.view-cart']['margin-bottom'] = '10px';

			if ( is_rtl() ) {
				$css[$media_query]['.rtl .myaccount_user_container span']['border-left'] = '0';
			}

			$elements = array(
				'.shop_table .product-thumbnail img',
				'.shop_table .product-thumbnail .product-info',
				'.shop_table .product-thumbnail .product-info p'
			);
			$css[$media_query][avada_implode( $elements )]['float']   = 'none';
			$css[$media_query][avada_implode( $elements )]['width']   = '100%';
			$css[$media_query][avada_implode( $elements )]['margin']  = '0 !important';
			$css[$media_query][avada_implode( $elements )]['padding'] = '0';

			$css[$media_query]['.shop_table .product-thumbnail']['padding'] = '10px 0px';

			$css[$media_query]['.product .images']['margin-bottom'] = '30px';

			$css[$media_query]['#customer_login_box .button']['float']         = 'left';
			$css[$media_query]['#customer_login_box .button']['margin-bottom'] = '15px';

			$css[$media_query]['#customer_login_box .remember-box']['clear']   = 'both';
			$css[$media_query]['#customer_login_box .remember-box']['display'] = 'block';
			$css[$media_query]['#customer_login_box .remember-box']['padding'] = '0';
			$css[$media_query]['#customer_login_box .remember-box']['width']   = '125px';
			$css[$media_query]['#customer_login_box .remember-box']['float']   = 'left';

			$css[$media_query]['#customer_login_box .lost_password']['float'] = 'left';

		}

		if ( defined( 'WPCF7_PLUGIN' ) ) {

			$elements = array(
				'.wpcf7-form .wpcf7-text',
				'.wpcf7-form .wpcf7-quiz',
				'.wpcf7-form .wpcf7-number',
				'.wpcf7-form textarea'
			);
			$css[$media_query][avada_implode( $elements )]['float']      = 'none !important';
			$css[$media_query][avada_implode( $elements )]['width']      = '100% !important';
			$css[$media_query][avada_implode( $elements )]['box-sizing'] = 'border-box';

		}

		if ( class_exists( 'GFForms' ) ) {
			$elements = array(
				'.gform_wrapper .right_label input.medium',
				'.gform_wrapper .right_label select.medium',
				'.gform_wrapper .left_label input.medium',
				'.gform_wrapper .left_label select.medium'
			);
			$css[$media_query][avada_implode( $elements )]['width'] = '35% !important';
		}

		$elements = array(
			'.product .images #slider .flex-direction-nav',
			'.product .images #carousel .flex-direction-nav'
		);
		$css[$media_query][avada_implode( $elements )]['display'] = 'none !important';

		if ( class_exists( 'WooCommerce' ) ) {
			$elements = array(
				'.myaccount_user_container span.msg',
				'.myaccount_user_container span:last-child'
			);
			$css[$media_query][avada_implode( $elements )]['padding-left']  = '0 !important';
			$css[$media_query][avada_implode( $elements )]['padding-right'] = '0 !important';
		}

		$css[$media_query]['.fullwidth-box']['background-attachment'] = 'scroll !important';

		$css[$media_query]['#toTop']['bottom']        = '30px';
		$css[$media_query]['#toTop']['border-radius'] = '4px';
		$css[$media_query]['#toTop']['height']        = '40px';
		$css[$media_query]['#toTop']['z-index']       = '10000';

		$css[$media_query]['#toTop:before']['line-height'] = '38px';

		$css[$media_query]['#toTop:hover']['background-color'] = '#333333';

		$css[$media_query]['.no-mobile-totop .to-top-container']['display'] = 'none';

		$css[$media_query]['.no-mobile-slidingbar #slidingbar-area']['display'] = 'none';

		$css[$media_query]['.no-mobile-slidingbar.mobile-logo-pos-left .mobile-menu-icons']['margin-right'] = '0';

		if ( is_rtl() ) {
			$css[$media_query]['.rtl.no-mobile-slidingbar.mobile-logo-pos-right .mobile-menu-icons']['margin-left'] = '0';
		}

		$css[$media_query]['.tfs-slider .slide-content-container .btn']['min-height']    = '0 !important';
		$css[$media_query]['.tfs-slider .slide-content-container .btn']['padding-left']  = '30px';
		$css[$media_query]['.tfs-slider .slide-content-container .btn']['padding-right'] = '30px !important';
		$css[$media_query]['.tfs-slider .slide-content-container .btn']['height']        = '26px !important';
		$css[$media_query]['.tfs-slider .slide-content-container .btn']['line-height']   = '26px !important';

		$css[$media_query]['.fusion-soundcloud iframe']['width'] = '100%';

		$elements = array(
			'.ua-mobile .fusion-page-title-bar',
			'.ua-mobile .footer-area',
			'.ua-mobile body',
			'.ua-mobile #main'
		);
		$css[$media_query][avada_implode( $elements )]['background-attachment'] = 'scroll !important';

		if ( class_exists( 'RevSliderFront' ) ) {
			$css[$media_query]['.fusion-revslider-mobile-padding']['padding-left']  = '30px !important';
			$css[$media_query]['.fusion-revslider-mobile-padding']['padding-right'] = '30px !important';
		}

		$media_query = '@media screen and (max-width: 782px)';
		$elements = array(
			'body.admin-bar #wrapper #slidingbar-area',
			'body.layout-boxed-mode.side-header-right #slidingbar-area',
			'.admin-bar p.demo_store'
		);
		$css[$media_query][avada_implode( $elements )]['top'] = '46px';
		$css[$media_query]['body.body_blank.admin-bar']['top'] = '45px';
		$css[$media_query]['html #wpadminbar']['z-index']  = '99999 !important';
		$css[$media_query]['html #wpadminbar']['position'] = 'fixed !important';

		$media_query = '@media screen and (max-width: 768px)';
		$css[$media_query]['.fusion-tabs.vertical-tabs .tab-pane']['max-width'] = 'none !important';

		$media_query = '@media screen and (max-width: 767px)';
		$css[$media_query]['#content']['width']       = '100% !important';
		$css[$media_query]['#content']['margin-left'] = '0px !important';
		$css[$media_query]['.sidebar']['width']       = '100% !important';
		$css[$media_query]['.sidebar']['float']       = 'none !important';
		$css[$media_query]['.sidebar']['margin-left'] = '0 !important';
		$css[$media_query]['.sidebar']['clear']       = 'both';

		$media_query = '@media only screen and (min-device-width: 320px) and (max-device-width: 640px)';

		$css[$media_query]['#wrapper']['width']      = 'auto !important';
		$css[$media_query]['#wrapper']['overflow-x'] = 'hidden !important';

		$css[$media_query]['.fusion-columns .fusion-column']['float']      = 'none';
		$css[$media_query]['.fusion-columns .fusion-column']['width']      = '100% !important';
		$css[$media_query]['.fusion-columns .fusion-column']['margin']     = '0 0 50px';
		$css[$media_query]['.fusion-columns .fusion-column']['box-sizing'] = 'border-box';

		$elements = array(
			'.footer-area .fusion-columns .fusion-column',
			'#slidingbar-area .fusion-columns .fusion-column'
		);
		$css[$media_query][avada_implode( $elements )]['float'] = 'left';
		$css[$media_query][avada_implode( $elements )]['width'] = '98% !important';

		$css[$media_query]['.avada-container .columns']['float']         = 'none';
		$css[$media_query]['.avada-container .columns']['width']         = '100%';
		$css[$media_query]['.avada-container .columns']['margin-bottom'] = '20px';

		$css[$media_query]['.avada-container .columns .col']['float'] = 'left';

		$css[$media_query]['.avada-container .col img']['display'] = 'block';
		$css[$media_query]['.avada-container .col img']['margin']  = '0 auto';

		$css[$media_query]['.review']['float'] = 'none';
		$css[$media_query]['.review']['width'] = '100%';

		$elements = array(
			'.social-networks',
			'.copyright'
		);
		$css[$media_query][avada_implode( $elements )]['float']      = 'none';
		$css[$media_query][avada_implode( $elements )]['padding']    = '0 0 15px';
		$css[$media_query][avada_implode( $elements )]['text-align'] = 'center';

		$elements = array(
			'.copyright:after',
			'.social-networks:after'
		);
		$css[$media_query][avada_implode( $elements )]['content'] = '""';
		$css[$media_query][avada_implode( $elements )]['display'] = 'block';
		$css[$media_query][avada_implode( $elements )]['clear']   = 'both';

		$elements = array(
			'.social-networks li',
			'.copyright li'
		);
		$css[$media_query][avada_implode( $elements )]['float']   = 'none';
		$css[$media_query][avada_implode( $elements )]['display'] = 'inline-block';

		$css[$media_query]['.continue']['display'] = 'none';

		$css[$media_query]['.mobile-button']['display'] = 'block !important';
		$css[$media_query]['.mobile-button']['float']   = 'none';

		$css[$media_query]['.title']['margin-top']    = '0px !important';
		$css[$media_query]['.title']['margin-bottom'] = '20px !important';

		$css[$media_query]['#content']['width']         = '100% !important';
		$css[$media_query]['#content']['float']         = 'none !important';
		$css[$media_query]['#content']['margin-left']   = '0 !important';
		$css[$media_query]['#content']['margin-bottom'] = '50px';

		$css[$media_query]['#content.full-width']['margin-bottom'] = '0';

		$css[$media_query]['.sidebar']['width'] = '100% !important';
		$css[$media_query]['.sidebar']['float'] = 'none !important';

		$css[$media_query]['.sidebar .social_links .social li']['width']        = 'auto';
		$css[$media_query]['.sidebar .social_links .social li']['margin-right'] = '5px';

		$css[$media_query]['#comment-input']['margin-bottom'] = '0';

		$css[$media_query]['#comment-input input']['width']         = '90%';
		$css[$media_query]['#comment-input input']['float']         = 'none !important';
		$css[$media_query]['#comment-input input']['margin-bottom'] = '10px';

		$css[$media_query]['#comment-textarea textarea']['width'] = '90%';

		$css[$media_query]['.widget.facebook_like iframe']['width']     = '100% !important';
		$css[$media_query]['.widget.facebook_like iframe']['max-width'] = 'none !important';

		$css[$media_query]['.pagination']['margin-top'] = '40px';

		$css[$media_query]['.portfolio-one .portfolio-item .image']['float']         = 'none';
		$css[$media_query]['.portfolio-one .portfolio-item .image']['width']         = 'auto';
		$css[$media_query]['.portfolio-one .portfolio-item .image']['height']        = 'auto';
		$css[$media_query]['.portfolio-one .portfolio-item .image']['margin-bottom'] = '20px';

		$css[$media_query]['h5.toggle span.toggle-title']['width'] = '80%';

		$css[$media_query]['#wrapper .sep-boxed-pricing .panel-wrapper']['padding'] = '0';

		$elements = array(
			'#wrapper .full-boxed-pricing .column',
			'#wrapper .sep-boxed-pricing .column'
		);
		$css[$media_query][avada_implode( $elements )]['float']         = 'none';
		$css[$media_query][avada_implode( $elements )]['margin-bottom'] = '10px';
		$css[$media_query][avada_implode( $elements )]['margin-left']   = '0';
		$css[$media_query][avada_implode( $elements )]['width']         = '100%';

		$css[$media_query]['.share-box']['height'] = 'auto';

		$css[$media_query]['#wrapper .share-box h4']['float']       = 'none';
		$css[$media_query]['#wrapper .share-box h4']['line-height'] = '20px !important';
		$css[$media_query]['#wrapper .share-box h4']['margin-top']  = '0';
		$css[$media_query]['#wrapper .share-box h4']['padding']     = '0';

		$css[$media_query]['.share-box ul']['float']          = 'none';
		$css[$media_query]['.share-box ul']['overflow']       ='hidden';
		$css[$media_query]['.share-box ul']['padding']        = '0 25px';
		$css[$media_query]['.share-box ul']['padding-bottom'] = '25px';
		$css[$media_query]['.share-box ul']['margin-top']     = '0px';

		$css[$media_query]['.project-content .project-description']['float'] = 'none !important';

		$css[$media_query]['.project-content .fusion-project-description-details']['margin-bottom'] = '50px';

		$elements = array(
			'.project-content .project-description',
			'.project-content .project-info'
		);
		$css[$media_query][avada_implode( $elements )]['width'] = '100% !important';

		$css[$media_query]['.portfolio-half .flexslider']['width'] = '100% !important';

		$css[$media_query]['.portfolio-half .project-content']['width'] = '100% !important';

		$css[$media_query]['#style_selector']['display'] = 'none';

		$elements = array(
			'.ls-avada .ls-nav-prev',
			'.ls-avada .ls-nav-next'
		);
		$css[$media_query][avada_implode( $elements )]['display'] = 'none !important';

		$css[$media_query]['#footer .social-networks']['width']    = '100%';
		$css[$media_query]['#footer .social-networks']['margin']   = '0 auto';
		$css[$media_query]['#footer .social-networks']['position'] = 'relative';
		$css[$media_query]['#footer .social-networks']['left']     = '-11px';

		$css[$media_query]['.recent-works-items a']['max-width'] = '64px';

		$elements = array(
			'.footer-area .flickr_badge_image img',
			'#slidingbar-area .flickr_badge_image img'
		);
		$css[$media_query][avada_implode( $elements )]['max-width'] = '64px';
		$css[$media_query][avada_implode( $elements )]['padding']   = '3px !important';

		$css[$media_query]['.tab-holder .tabs']['height'] = 'auto !important';
		$css[$media_query]['.tab-holder .tabs']['width']  = '100% !important';

		$css[$media_query]['.shortcode-tabs .tab-hold .tabs li']['width'] = '100% !important';

		$elements = array(
			'body .shortcode-tabs .tab-hold .tabs li',
			'body.dark .sidebar .tab-hold .tabs li'
		);
		$css[$media_query][avada_implode( $elements )]['border-right'] = 'none !important';

		$css[$media_query]['.error_page .useful_links']['width']        = '100%';
		$css[$media_query]['.error_page .useful_links']['padding-left'] = '0';

		$css[$media_query]['.fusion-google-map']['width']         = '100% !important';
		$css[$media_query]['.fusion-google-map']['margin-bottom'] = '20px !important';

		$css[$media_query]['.social_links_shortcode .social li']['width'] = '10% !important';

		$css[$media_query]['#wrapper .ei-slider']['width']  = '100% !important';
		$css[$media_query]['#wrapper .ei-slider']['height'] = '200px !important';

		$css[$media_query]['.progress-bar']['margin-bottom'] = '10px !important';

		$css[$media_query]['#wrapper .content-boxes-icon-boxed .content-wrapper-boxed']['min-height']     = 'inherit !important';
		$css[$media_query]['#wrapper .content-boxes-icon-boxed .content-wrapper-boxed']['padding-bottom'] = '20px';
		$css[$media_query]['#wrapper .content-boxes-icon-boxed .content-wrapper-boxed']['padding-left']   = '3% !important';
		$css[$media_query]['#wrapper .content-boxes-icon-boxed .content-wrapper-boxed']['padding-right']  = '3% !important';

		$elements = array(
			'#wrapper .content-boxes-icon-on-top .content-box-column',
			'#wrapper .content-boxes-icon-boxed .content-box-column'
		);
		$css[$media_query][avada_implode( $elements )]['margin-bottom'] = '55px';

		$css[$media_query]['.share-box .social-networks']['text-align'] = 'left';

		$css[$media_query]['#content']['width']       = '100% !important';
		$css[$media_query]['#content']['margin-left'] = '0px !important';

		$css[$media_query]['.sidebar']['width']       = '100% !important';
		$css[$media_query]['.sidebar']['float']       = 'none !important';
		$css[$media_query]['.sidebar']['margin-left'] = '0 !important';
		$css[$media_query]['.sidebar']['clear']       = 'both';

		$media_query = '@media only screen and (max-width: 640px)';

		$elements = array(
			'.avada-container .columns .col',
			'.footer-area .fusion-columns .fusion-column',
			'#slidingbar-area .columns .col'
		);
		$css[$media_query][avada_implode( $elements )]['float'] = 'none';
		$css[$media_query][avada_implode( $elements )]['width'] = '100%';

		$elements = array(
			'.wooslider-direction-nav',
			'.wooslider-pauseplay',
			'.flex-direction-nav'
		);
		$css[$media_query][avada_implode( $elements )]['display'] = 'none';

		$css[$media_query]['.share-box ul li']['margin-bottom'] ='10px';
		$css[$media_query]['.share-box ul li']['margin-right']  ='15px';

		$css[$media_query]['.buttons a']['margin-right'] = '5px';

		$elements = array(
			'.ls-avada .ls-nav-prev',
			'.ls-avada .ls-nav-next'
		);
		$css[$media_query][avada_implode( $elements )]['display'] = 'none !important';

		$css[$media_query]['#wrapper .ei-slider']['width']  = '100% !important';
		$css[$media_query]['#wrapper .ei-slider']['height'] = '200px !important';

		$css[$media_query]['.progress-bar']['margin-bottom'] = '10px !important';

		$css[$media_query]['#wrapper .content-boxes-icon-boxed .content-wrapper-boxed']['min-height']     = 'inherit !important';
		$css[$media_query]['#wrapper .content-boxes-icon-boxed .content-wrapper-boxed']['padding-bottom'] = '20px';
		$css[$media_query]['#wrapper .content-boxes-icon-boxed .content-wrapper-boxed']['padding-left']   = '3% !important';
		$css[$media_query]['#wrapper .content-boxes-icon-boxed .content-wrapper-boxed']['padding-right']  = '3% !important';

		$elements = array(
			'#wrapper .content-boxes-icon-on-top .content-box-column',
			'#wrapper .content-boxes-icon-boxed .content-box-column'
		);
		$css[$media_query][avada_implode( $elements )]['margin-bottom'] = '55px';

		$css[$media_query]['#wrapper .content-boxes-icon-boxed .content-box-column .heading h2']['margin-top'] = '-5px';

		$css[$media_query]['#wrapper .content-boxes-icon-boxed .content-box-column .more']['margin-top'] = '12px';

		$css[$media_query]['.page-template-contact-php .fusion-google-map']['height'] = '270px !important';

		$css[$media_query]['.share-box .social-networks li']['margin-right'] = '20px !important';

		$css[$media_query]['.timeline-icon']['display'] = 'none !important';

		$css[$media_query]['.timeline-layout']['padding-top'] = '0 !important';

		$css[$media_query]['.fusion-counters-circle .counter-circle-wrapper']['display']      = 'block';
		$css[$media_query]['.fusion-counters-circle .counter-circle-wrapper']['margin-right'] = 'auto';
		$css[$media_query]['.fusion-counters-circle .counter-circle-wrapper']['margin-left']  = 'auto';

		$css[$media_query]['.post-content .wooslider .wooslider-control-thumbs']['margin-top'] = '-10px';

		$css[$media_query]['body .wooslider .overlay-full.layout-text-left .slide-excerpt']['padding'] = '20px !important';

		$css[$media_query]['.content-boxes-icon-boxed .col']['box-sizing'] = 'border-box';

		$css[$media_query]['.social_links_shortcode li']['height'] = '40px !important';

		$css[$media_query]['.products-slider .es-nav span']['transform'] = 'scale(0.5) !important';

		if ( class_exists( 'WooCommerce' ) ) {

			$css[$media_query]['.shop_table .product-quantity']['display'] = 'none';

			$css[$media_query]['.shop_table .filler-td']['display'] = 'none';

			$css[$media_query]['.my_account_orders .order-status']['display'] = 'none';

			$css[$media_query]['.my_account_orders .order-date']['display'] = 'none';

			$css[$media_query]['.my_account_orders .order-number time']['display']     = 'block !important';
			$css[$media_query]['.my_account_orders .order-number time']['font-size']   = '10px';
			$css[$media_query]['.my_account_orders .order-number time']['line-height'] = 'normal';

		}

		$css[$media_query]['.portfolio-masonry .portfolio-item']['width'] = '100% !important';

		if ( class_exists( 'bbPress' ) ) {

			$css[$media_query]['#bbpress-forums #bbp-single-user-details #bbp-user-avatar img.avatar']['width']  = '80px !important';
			$css[$media_query]['#bbpress-forums #bbp-single-user-details #bbp-user-avatar img.avatar']['height'] = '80px !important';

			$css[$media_query]['#bbpress-forums #bbp-single-user-details #bbp-user-avatar']['width'] = '80px !important';

			$css[$media_query]['#bbpress-forums #bbp-single-user-details #bbp-user-navigation']['margin-left'] = '110px !important';

			$css[$media_query]['#bbpress-forums #bbp-single-user-details #bbp-user-navigation .first-col']['width'] = '47% !important';

			$css[$media_query]['#bbpress-forums #bbp-single-user-details #bbp-user-navigation .second-col']['margin-left'] = '53% !important';
			$css[$media_query]['#bbpress-forums #bbp-single-user-details #bbp-user-navigation .second-col']['width']       = '47% !important';

		}

		$elements = array(
			'.table-1 table',
			'.tkt-slctr-tbl-wrap-dv table'
		);
		$css[$media_query][avada_implode( $elements )]['border-collapse'] = 'collapse';
		$css[$media_query][avada_implode( $elements )]['border-spacing']  = '0';
		$css[$media_query][avada_implode( $elements )]['width']           = '100%';

		$elements = array(
			'.table-1 td',
			'.table-1 th',
			'.tkt-slctr-tbl-wrap-dv td',
			'.tkt-slctr-tbl-wrap-dv th'
		);
		$css[$media_query][avada_implode( $elements )]['white-space'] = 'nowrap';

		$css[$media_query]['.table-2 table']['border-collapse'] = 'collapse';
		$css[$media_query]['.table-2 table']['border-spacing']  = '0';
		$css[$media_query]['.table-2 table']['width']           = '100%';

		$elements = array(
			'.table-2 td',
			'.table-2 th'
		);
		$css[$media_query][avada_implode( $elements )]['white-space'] = 'nowrap';

		$elements = array(
			'.page-title-bar',
			'.footer-area',
			'body',
			'#main'
		);
		$css[$media_query][avada_implode( $elements )]['background-attachment'] = 'scroll !important';

		$css[$media_query]['.tfs-slider[data-animation="slide"]']['height'] = 'auto !important';

		$css[$media_query]['#wrapper .share-box h4']['display']       = 'block';
		$css[$media_query]['#wrapper .share-box h4']['float']         = 'none';
		$css[$media_query]['#wrapper .share-box h4']['line-height']   = '20px !important';
		$css[$media_query]['#wrapper .share-box h4']['margin-top']    = '0';
		$css[$media_query]['#wrapper .share-box h4']['padding']       = '0';
		$css[$media_query]['#wrapper .share-box h4']['margin-bottom'] = '10px';

		$css[$media_query]['.fusion-sharing-box .fusion-social-networks']['float']      = 'none';
		$css[$media_query]['.fusion-sharing-box .fusion-social-networks']['display']    = 'block';
		$css[$media_query]['.fusion-sharing-box .fusion-social-networks']['width']      = '100%';
		$css[$media_query]['.fusion-sharing-box .fusion-social-networks']['text-align'] = 'left';

		$css[$media_query]['#content']['width']        = '100% !important';
		$css[$media_query]['#content']['margin-left'] = '0px !important';

		$css[$media_query]['.sidebar']['width']       = '100% !important';
		$css[$media_query]['.sidebar']['float']       = 'none !important';
		$css[$media_query]['.sidebar']['margin-left'] = '0 !important';
		$css[$media_query]['.sidebar']['clear']       = 'both';

		$css[$media_query]['.fusion-hide-on-mobile']['display'] = 'none';

		// Blog timeline layout

		$css[$media_query]['.fusion-blog-layout-timeline']['padding-top'] = '0';

		$css[$media_query]['.fusion-blog-layout-timeline .fusion-post-timeline']['float'] = 'none';
		$css[$media_query]['.fusion-blog-layout-timeline .fusion-post-timeline']['width'] = '100%';

		$css[$media_query]['.fusion-blog-layout-timeline .fusion-timeline-date']['margin-bottom'] = '0';
		$css[$media_query]['.fusion-blog-layout-timeline .fusion-timeline-date']['margin-top']    = '2px';

		$elements = array(
			'.fusion-timeline-icon',
			'.fusion-timeline-line',
			'.fusion-timeline-circle',
			'.fusion-timeline-arrow'
		);
		$css[$media_query][avada_implode( $elements )]['display'] = 'none';

		if ( class_exists( 'bbPress' ) ) {

			$media_query = '@media only screen and (max-width: 480px)';
			$css[$media_query]['#bbpress-forums .bbp-body div.bbp-reply-author']['width'] = '71% !important';
			$css[$media_query]['.bbp-arrow']['display'] = 'none';
			$css[$media_query]['div.bbp-submit-wrapper']['float'] = 'right !important';

		}

		$media_query = '@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) and (orientation: portrait)';
		$css[$media_query]['#wrapper .ei-slider']['width'] = '100%';
		$elements = array(
			'.fullwidth-box',
			'.page-title-bar',
			'.fusion-footer-widget-area',
			'body',
			'#main'
		);
		$css[$media_query][avada_implode( $elements )]['background-attachment'] = 'scroll !important';

		$media_query = '@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) and (orientation: landscape)';
		$css[$media_query]['#wrapper .ei-slider']['width'] = '100%';
		$elements = array(
			'.fullwidth-box',
			'.page-title-bar',
			'.fusion-footer-widget-area',
			'body',
			'#main'
		);
		$css[$media_query][avada_implode( $elements )]['background-attachment'] = 'scroll !important';

		$media_query = '@media only screen and (min-device-width: 768px) and (max-device-width: 1024px)';
		$css[$media_query]['#wrapper .ei-slider']['width'] = '100%';

		$media_query = '@media only screen and (min-device-width: 320px) and (max-device-width: 480px)';
		$css[$media_query]['#wrapper .ei-slider']['width'] = '100%';

		if ( class_exists( 'GFForms' ) ) {

			$media_query = '@media all and (max-width: 480px), all and (max-device-width: 480px)';
			$elements = array(
				'body.fusion-body .gform_wrapper .ginput_container',
				'body.fusion-body .gform_wrapper div.ginput_complex',
				'body.fusion-body .gform_wrapper div.gf_page_steps',
				'body.fusion-body .gform_wrapper div.gf_page_steps div',
				'body.fusion-body .gform_wrapper .ginput_container input.small',
				'body.fusion-body .gform_wrapper .ginput_container input.medium',
				'body.fusion-body .gform_wrapper .ginput_container input.large',
				'body.fusion-body .gform_wrapper .ginput_container select.small',
				'body.fusion-body .gform_wrapper .ginput_container select.medium',
				'body.fusion-body .gform_wrapper .ginput_container select.large',
				'body.fusion-body .gform_wrapper .ginput_container textarea.small',
				'body.fusion-body .gform_wrapper .ginput_container textarea.medium',
				'body.fusion-body .gform_wrapper .ginput_container textarea.large',
				'body.fusion-body .gform_wrapper .ginput_complex .ginput_right input[type="text"]',
				'body.fusion-body .gform_wrapper .ginput_complex .ginput_right input[type="url"]',
				'body.fusion-body .gform_wrapper .ginput_complex .ginput_right input[type="email"]',
				'body.fusion-body .gform_wrapper .ginput_complex .ginput_right input[type="tel"]',
				'body.fusion-body .gform_wrapper .ginput_complex .ginput_right input[type="number"]',
				'body.fusion-body .gform_wrapper .ginput_complex .ginput_right input[type="password"]',
				'body.fusion-body .gform_wrapper .ginput_complex .ginput_left input[type="text"]',
				'body.fusion-body .gform_wrapper .ginput_complex .ginput_left input[type="url"]',
				'body.fusion-body .gform_wrapper .ginput_complex .ginput_left input[type="email"]',
				'body.fusion-body .gform_wrapper .ginput_complex .ginput_left input[type="tel"]',
				'body.fusion-body .gform_wrapper .ginput_complex .ginput_left input[type="number"]',
				'body.fusion-body .gform_wrapper .ginput_complex .ginput_left input[type="password"]',
				'body.fusion-body .gform_wrapper .ginput_complex .ginput_full input[type="text"]',
				'body.fusion-body .gform_wrapper .ginput_complex .ginput_full input[type="url"]',
				'body.fusion-body .gform_wrapper .ginput_complex .ginput_full input[type="email"]',
				'body.fusion-body .gform_wrapper .ginput_complex .ginput_full input[type="tel"]',
				'body.fusion-body .gform_wrapper .ginput_complex .ginput_full input[type="number"]',
				'body.fusion-body .gform_wrapper .ginput_complex .ginput_full input[type="password"]',
				'body.fusion-body .gform_wrapper .ginput_complex .ginput_full select',
				'body.fusion-body .gform_wrapper input.gform_button.button',
				'body.fusion-body .gform_wrapper input[type="submit"]',
				'body.fusion-body .gform_wrapper .gfield_time_hour input',
				'body.fusion-body .gform_wrapper .gfield_time_minute input',
				'body.fusion-body .gform_wrapper .gfield_date_month input',
				'body.fusion-body .gform_wrapper .gfield_date_day input',
				'body.fusion-body .gform_wrapper .gfield_date_year input',
				'.gfield_time_ampm .gravity-select-parent',
				'body.fusion-body .gform_wrapper .ginput_complex input[type="text"]',
				'body.fusion-body .gform_wrapper .ginput_complex input[type="url"]',
				'body.fusion-body .gform_wrapper .ginput_complex input[type="email"]',
				'body.fusion-body .gform_wrapper .ginput_complex input[type="tel"]',
				'body.fusion-body .gform_wrapper .ginput_complex input[type="number"]',
				'body.fusion-body .gform_wrapper .ginput_complex input[type="password"]',
				'body.fusion-body .gform_wrapper .ginput_complex .gravity-select-parent',
				'body.fusion-body .gravity-select-parent'
			);
			$css[$media_query][avada_implode( $elements )]['width'] = '100% !important';
			$elements = array(
				'.gform_wrapper .gform_page_footer input[type="button"]',
				'.gform_wrapper .gform_button',
				'.gform_wrapper .button'
			);
			$css[$media_query][avada_implode( $elements )]['padding-left']  = '0';
			$css[$media_query][avada_implode( $elements )]['padding-right'] = '0';

		}

		if ( ! Avada()->settings->get( 'ipad_potrait' ) ) {

			$media_query = '@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) and (orientation: portrait)';

			$elements = array(
				'.fusion-columns-5 .fusion-column:first-child',
				'.fusion-columns-4 .fusion-column:first-child',
				'.fusion-columns-3 .fusion-column:first-child',
				'.fusion-columns-2 .fusion-column:first-child',
				'.fusion-columns-1 .fusion-column:first-child'
			);
			$css[$media_query][avada_implode( $elements )]['margin-left'] = '0';

			$elements = array(
				'.fusion-column:nth-child(5n)',
				'.fusion-column:nth-child(4n)',
				'.fusion-column:nth-child(3n)',
				'.fusion-column:nth-child(2n)',
				'.fusion-column'
			);
			$css[$media_query][avada_implode( $elements )]['margin-right'] = '0';

			$css[$media_query]['#wrapper']['width']      = 'auto !important';
			//$css[$media_query]['#wrapper']['overflow-x'] = 'hidden';

			$css[$media_query]['.create-block-format-context']['display'] = 'none';

			$css[$media_query]['.columns .col']['float']      = 'none';
			$css[$media_query]['.columns .col']['width']      = '100% !important';
			$css[$media_query]['.columns .col']['margin']     = '0 0 20px';
			$css[$media_query]['.columns .col']['box-sizing'] = 'border-box';

			$css[$media_query]['.avada-container .columns']['float']         = 'none';
			$css[$media_query]['.avada-container .columns']['width']         = '100%';
			$css[$media_query]['.avada-container .columns']['margin-bottom'] = '20px';

			$css[$media_query]['.avada-container .columns .col']['float'] = 'left';

			$css[$media_query]['.avada-container .col img']['display'] = 'block';
			$css[$media_query]['.avada-container .col img']['margin']  = '0 auto';

			$css[$media_query]['.review']['float'] = 'none';
			$css[$media_query]['.review']['width'] = '100%';

			$elements = array(
				'.fusion-social-networks',
				'.fusion-social-links-footer'
			);
			$css[$media_query][avada_implode( $elements )]['display']    = 'block';
			$css[$media_query][avada_implode( $elements )]['text-align'] = 'center';

			$css[$media_query]['.fusion-social-links-footer']['width'] = 'auto';

			$css[$media_query]['.fusion-social-links-footer .fusion-social-networks']['display'] = 'inline-block';
			$css[$media_query]['.fusion-social-links-footer .fusion-social-networks']['float']   = 'none';

			$css[$media_query]['.fusion-social-links-footer .fusion-social-networks .fusion-social-network-icon:first-child']['margin-left']  = '0';
			$css[$media_query]['.fusion-social-links-footer .fusion-social-networks .fusion-social-network-icon:first-child']['margin-right'] = '0';

			$css[$media_query]['.fusion-social-networks']['padding'] = '0 0 15px';

			$css[$media_query]['.fusion-author .fusion-author-ssocial .fusion-author-tagline']['float']      = 'none';
			$css[$media_query]['.fusion-author .fusion-author-ssocial .fusion-author-tagline']['text-align'] = 'center';
			$css[$media_query]['.fusion-author .fusion-author-ssocial .fusion-author-tagline']['max-width']  = '100%';

			$css[$media_query]['.fusion-author .fusion-author-ssocial .fusion-social-networks']['text-align'] = 'center';

			$css[$media_query]['.fusion-author .fusion-author-ssocial .fusion-social-networks .fusion-social-network-icon:first-child']['margin-left'] = '0';

			$css[$media_query]['.fusion-social-networks:after']['content'] = '""';
			$css[$media_query]['.fusion-social-networks:after']['display'] = 'block';
			$css[$media_query]['.fusion-social-networks:after']['clear']   = 'both';

			$css[$media_query]['.fusion-social-networks li']['float']   = 'none';
			$css[$media_query]['.fusion-social-networks li']['display'] = 'inline-block';

			$elements = array(
				'.fusion-reading-box-container .reading-box.reading-box-center',
				'.fusion-reading-box-container .reading-box.reading-box-right'
			);
			$css[$media_query][avada_implode( $elements )]['text-align'] = 'left';

			$css[$media_query]['.fusion-reading-box-container .continue']['display'] = 'block';

			$css[$media_query]['.fusion-reading-box-container .mobile-button']['display'] = 'none';
			$css[$media_query]['.fusion-reading-box-container .mobile-button']['float']   = 'none';

			$css[$media_query]['.fusion-title']['margin-top']    = '0px !important';
			$css[$media_query]['.fusion-title']['margin-bottom'] = '20px !important';

			if ( class_exists( 'WooCommerce' ) ) {

				$css[$media_query]['#main .cart-empty']['float']         = 'none';
				$css[$media_query]['#main .cart-empty']['text-align']    = 'center';
				$css[$media_query]['#main .cart-empty']['border-top']    = '1px solid';
				$css[$media_query]['#main .cart-empty']['border-bottom'] = 'none';
				$css[$media_query]['#main .cart-empty']['width']         = '100%';
				$css[$media_query]['#main .cart-empty']['line-height']   = 'normal !important';
				$css[$media_query]['#main .cart-empty']['height']        = 'auto !important';
				$css[$media_query]['#main .cart-empty']['margin-bottom'] = '10px';
				$css[$media_query]['#main .cart-empty']['padding-top']   = '10px';

				$css[$media_query]['#main .return-to-shop']['float']          = 'none';
				$css[$media_query]['#main .return-to-shop']['border-top']     = 'none';
				$css[$media_query]['#main .return-to-shop']['border-bottom']  = '1px solid';
				$css[$media_query]['#main .return-to-shop']['width']          = '100%';
				$css[$media_query]['#main .return-to-shop']['text-align']     = 'center';
				$css[$media_query]['#main .return-to-shop']['line-height']    = 'normal !important';
				$css[$media_query]['#main .return-to-shop']['height']         = 'auto !important';
				$css[$media_query]['#main .return-to-shop']['padding-bottom'] = '10px';

				$css[$media_query]['.woocommerce .checkout_coupon .promo-code-heading']['display']       = 'block';
				$css[$media_query]['.woocommerce .checkout_coupon .promo-code-heading']['margin-bottom'] = '10px !important';
				$css[$media_query]['.woocommerce .checkout_coupon .promo-code-heading']['float']         = 'none';
				$css[$media_query]['.woocommerce .checkout_coupon .promo-code-heading']['text-align']    = 'center';

				$css[$media_query]['.woocommerce .checkout_coupon .coupon-contents']['display'] = 'block';
				$css[$media_query]['.woocommerce .checkout_coupon .coupon-contents']['float']   = 'none';
				$css[$media_query]['.woocommerce .checkout_coupon .coupon-contents']['margin']  = '0';

				$css[$media_query]['.woocommerce .checkout_coupon .coupon-input']['display']       = 'block';
				$css[$media_query]['.woocommerce .checkout_coupon .coupon-input']['width']         = 'auto !important';
				$css[$media_query]['.woocommerce .checkout_coupon .coupon-input']['float']         = 'none';
				$css[$media_query]['.woocommerce .checkout_coupon .coupon-input']['text-align']    = 'center';
				$css[$media_query]['.woocommerce .checkout_coupon .coupon-input']['margin-right']  ='0';
				$css[$media_query]['.woocommerce .checkout_coupon .coupon-input']['margin-bottom'] = '10px !important';

				$css[$media_query]['.woocommerce .checkout_coupon .coupon-button']['display']      = 'block';
				$css[$media_query]['.woocommerce .checkout_coupon .coupon-button']['margin-right'] = '0';
				$css[$media_query]['.woocommerce .checkout_coupon .coupon-button']['float']        = 'none';
				$css[$media_query]['.woocommerce .checkout_coupon .coupon-button']['text-align']   = 'center';

			}

			// Page Title Bar

			if ( 'auto' != Avada()->settings->get( 'page_title_mobile_height' ) ) {

				$css[$media_query]['.fusion-body .fusion-page-title-bar']['height'] = Avada_Sanitize::size( Avada()->settings->get( 'page_title_mobile_height' ) );

			} else {

				$css[$media_query]['.fusion-body .fusion-page-title-bar']['padding-top']    = '10px';
				$css[$media_query]['.fusion-body .fusion-page-title-bar']['padding-bottom'] = '10px';
				$css[$media_query]['.fusion-body .fusion-page-title-bar']['height']         = 'auto';

			}

			$elements = array(
				'.fusion-page-title-bar-left .fusion-page-title-captions',
				'.fusion-page-title-bar-right .fusion-page-title-captions',
				'.fusion-page-title-bar-left .fusion-page-title-secondary',
				'.fusion-page-title-bar-right .fusion-page-title-secondary'
			);
			$css[$media_query][avada_implode( $elements )]['display']     = 'block';
			$css[$media_query][avada_implode( $elements )]['float']       = 'none';
			$css[$media_query][avada_implode( $elements )]['width']       = '100%';
			$css[$media_query][avada_implode( $elements )]['line-height'] = 'normal';

			$css[$media_query]['.fusion-page-title-bar-left .fusion-page-title-secondary']['text-align'] = 'left';

			$css[$media_query]['.fusion-page-title-bar-left .searchform']['display']   = 'block';
			$css[$media_query]['.fusion-page-title-bar-left .searchform']['max-width'] = '100%';

			$css[$media_query]['.fusion-page-title-bar-right .fusion-page-title-secondary']['text-align'] = 'right';

			$css[$media_query]['.fusion-page-title-bar-right .searchform']['max-width'] = '100%';

			if ( 'auto' != Avada()->settings->get( 'page_title_mobile_height' ) ) {

				$css[$media_query]['.fusion-page-title-row']['display']    = 'table';
				$css[$media_query]['.fusion-page-title-row']['width']      = '100%';
				$css[$media_query]['.fusion-page-title-row']['height']      = '100%';
				$css[$media_query]['.fusion-page-title-row']['min-height'] = fusion_strip_unit( Avada()->settings->get( 'page_title_mobile_height' ) ) - 20 . 'px';

				$css[$media_query]['.fusion-page-title-wrapper']['display']        = 'table-cell';
				$css[$media_query]['.fusion-page-title-wrapper']['vertical-align'] = 'middle';

			}

			$css[$media_query]['.sidebar .social_links .social li']['width']        = 'auto';
			$css[$media_query]['.sidebar .social_links .social li']['margin-right'] = '5px';

			$css[$media_query]['#comment-input']['margin-bottom'] = '0';

			$css[$media_query]['#comment-input input']['width']         = '90%';
			$css[$media_query]['#comment-input input']['float']         = 'none !important';
			$css[$media_query]['#comment-input input']['margin-bottom'] = '10px';

			$css[$media_query]['#comment-textarea textarea']['width'] = '90%';

			$css[$media_query]['.pagination']['margin-top'] = '40px';

			$css[$media_query]['.portfolio-one .portfolio-item .image']['float']         = 'none';
			$css[$media_query]['.portfolio-one .portfolio-item .image']['width']         = 'auto';
			$css[$media_query]['.portfolio-one .portfolio-item .image']['height']        = 'auto';
			$css[$media_query]['.portfolio-one .portfolio-item .image']['margin-bottom'] = '20px';

			$css[$media_query]['h5.toggle span.toggle-title']['width'] = '80%';

			$css[$media_query]['#wrapper .sep-boxed-pricing .panel-wrapper']['padding'] = '0';

			$elements = array(
				'#wrapper .full-boxed-pricing .column',
				'#wrapper .sep-boxed-pricing .column'
			);
			$css[$media_query][avada_implode( $elements )]['float']         = 'none';
			$css[$media_query][avada_implode( $elements )]['margin-bottom'] = '10px';
			$css[$media_query][avada_implode( $elements )]['margin-left']   = '0';
			$css[$media_query][avada_implode( $elements )]['width']         = '100%';

			$css[$media_query]['.share-box']['height'] = 'auto';

			$css[$media_query]['#wrapper .share-box h4']['float']       = 'none';
			$css[$media_query]['#wrapper .share-box h4']['line-height'] = '20px !important';
			$css[$media_query]['#wrapper .share-box h4']['padding']     = '0';

			$css[$media_query]['.share-box ul']['float']          = 'none';
			$css[$media_query]['.share-box ul']['overflow']       = 'hidden';
			$css[$media_query]['.share-box ul']['padding']        = '0 25px';
			$css[$media_query]['.share-box ul']['padding-bottom'] = '15px';
			$css[$media_query]['.share-box ul']['margin-top']     = '0px';

			$css[$media_query]['.project-content .project-description']['float'] = 'none !important';

			$css[$media_query]['.project-content .fusion-project-description-details']['margin-bottom'] = '50px';

			$elements = array(
				'.project-content .project-description',
				'.project-content .project-info'
			);
			$css[$media_query][avada_implode( $elements )]['width'] = '100% !important';

			$css[$media_query]['.portfolio-half .flexslider']['width'] = '100%';

			$css[$media_query]['.portfolio-half .project-content']['width'] = '100% !important';

			$css[$media_query]['#style_selector']['display'] = 'none';

			$elements = array(
				'.portfolio-tabs',
				'.faq-tabs'
			);
			$css[$media_query][avada_implode( $elements )]['height']              = 'auto';
			$css[$media_query][avada_implode( $elements )]['border-bottom-width'] = '1px';
			$css[$media_query][avada_implode( $elements )]['border-bottom-style'] = 'solid';

			$elements = array(
				'.portfolio-tabs li',
				'.faq-tabs li'
			);
			$css[$media_query][avada_implode( $elements )]['float']         = 'left';
			$css[$media_query][avada_implode( $elements )]['margin-right']  = '30px';
			$css[$media_query][avada_implode( $elements )]['border-bottom'] = '0';

			$elements = array(
				'.ls-avada .ls-nav-prev',
				'.ls-avada .ls-nav-next'
			);
			$css[$media_query][avada_implode( $elements )]['display'] = 'none !important';

			$elements = array(
				'nav#nav',
				'nav#sticky-nav'
			);
			$css[$media_query][avada_implode( $elements )]['margin-right'] = '0';

			$css[$media_query]['#footer .social-networks']['width']    = '100%';
			$css[$media_query]['#footer .social-networks']['margin']   = '0 auto';
			$css[$media_query]['#footer .social-networks']['position'] = 'relative';
			$css[$media_query]['#footer .social-networks']['left']     = '-11px';

			$css[$media_query]['.tab-holder .tabs']['height'] = 'auto !important';
			$css[$media_query]['.tab-holder .tabs']['width']  = '100% !important';

			$css[$media_query]['.shortcode-tabs .tab-hold .tabs li']['width'] = '100% !important';

			$elements = array(
				'body .shortcode-tabs .tab-hold .tabs li',
				'body.dark .sidebar .tab-hold .tabs li'
			);
			$css[$media_query][avada_implode( $elements )]['border-right'] = 'none !important';

			$css[$media_query]['.error-message']['line-height'] = '170px';
			$css[$media_query]['.error-message']['margin-top']  = '20px';

			$css[$media_query]['.error_page .useful_links']['width']        = '100%';
			$css[$media_query]['.error_page .useful_links']['padding-left'] = '0';

			$css[$media_query]['.fusion-google-map']['width']         = '100% !important';
			$css[$media_query]['.fusion-google-map']['margin-bottom'] = '20px !important';

			$css[$media_query]['.social_links_shortcode .social li']['width'] = '10% !important';

			$css[$media_query]['#wrapper .ei-slider']['width']  = '100% !important';
			$css[$media_query]['#wrapper .ei-slider']['height'] = '200px !important';

			$css[$media_query]['.progress-bar']['margin-bottom'] = '10px !important';

			$css[$media_query]['.fusion-blog-layout-medium-alternate .fusion-post-content']['float']      = 'none';
			$css[$media_query]['.fusion-blog-layout-medium-alternate .fusion-post-content']['width']      = '100% !important';
			$css[$media_query]['.fusion-blog-layout-medium-alternate .fusion-post-content']['margin-top'] = '20px';

			$css[$media_query]['#wrapper .content-boxes-icon-boxed .content-wrapper-boxed']['min-height']     = 'inherit !important';
			$css[$media_query]['#wrapper .content-boxes-icon-boxed .content-wrapper-boxed']['padding-bottom'] = '20px';
			$css[$media_query]['#wrapper .content-boxes-icon-boxed .content-wrapper-boxed']['padding-left']   = '3%';
			$css[$media_query]['#wrapper .content-boxes-icon-boxed .content-wrapper-boxed']['padding-right']  = '3%';

			$elements = array(
				'#wrapper .content-boxes-icon-on-top .content-box-column',
				'#wrapper .content-boxes-icon-boxed .content-box-column'
			);
			$css[$media_query][avada_implode( $elements )]['margin-bottom'] = '55px';

			$css[$media_query]['.fusion-counters-box .fusion-counter-box']['margin-bottom'] = '20px';
			$css[$media_query]['.fusion-counters-box .fusion-counter-box']['padding']       = '0 15px';

			$css[$media_query]['.fusion-counters-box .fusion-counter-box:last-child']['margin-bottom'] = '0';

			$css[$media_query]['.popup']['display'] = 'none !important';

			$css[$media_query]['.share-box .social-networks']['text-align'] = 'left';

			if ( class_exists( 'WooCommerce' ) ) {

				$elements = array(
					'.catalog-ordering .order',
					'.avada-myaccount-data .addresses .col-1',
					'.avada-myaccount-data .addresses .col-2',
					'.avada-customer-details .addresses .col-1',
					'.avada-customer-details .addresses .col-2'
				);
				$css[$media_query][avada_implode( $elements )]['float']        = 'none !important';
				$css[$media_query][avada_implode( $elements )]['margin-left']  = 'auto !important';
				$css[$media_query][avada_implode( $elements )]['margin-right'] = 'auto !important';

				$css[$media_query]['#wrapper .catalog-ordering > .fusion-grid-list-view']['float']        = 'left !important';
				$css[$media_query]['#wrapper .catalog-ordering > .fusion-grid-list-view']['margin-left']  = '0 !important';
				$css[$media_query]['#wrapper .catalog-ordering > .fusion-grid-list-view']['margin-right'] = '0 !important';

				$elements = array(
					'.avada-myaccount-data .addresses .col-1',
					'.avada-myaccount-data .addresses .col-2',
					'.avada-customer-details .addresses .col-1',
					'.avada-customer-details .addresses .col-2'
				);
				$css[$media_query][avada_implode( $elements )]['margin'] = '0 !important';
				$css[$media_query][avada_implode( $elements )]['width']  = '100%';

				$css[$media_query]['.catalog-ordering']['margin-bottom'] = '50px';

				$css[$media_query]['.catalog-ordering .order']['width'] = '33px';

				$elements = array(
					'.catalog-ordering > ul',
					'.catalog-ordering .order'
				);
				$css[$media_query][avada_implode( $elements )]['margin-bottom'] = '10px';

				$css[$media_query]['.order-dropdown > li:hover > ul']['display']  = 'block';
				$css[$media_query]['.order-dropdown > li:hover > ul']['position'] = 'relative';
				$css[$media_query]['.order-dropdown > li:hover > ul']['top']      = '0';

				$css[$media_query]['#wrapper .orderby-order-container']['overflow']      = 'visible';
				$css[$media_query]['#wrapper .orderby-order-container']['width']         = 'auto';
				$css[$media_query]['#wrapper .orderby-order-container']['margin-bottom'] = '10px';
				$css[$media_query]['#wrapper .orderby-order-container']['float']         = 'left';

				$css[$media_query]['#wrapper .orderby.order-dropdown']['float']        = 'left';
				$css[$media_query]['#wrapper .orderby.order-dropdown']['margin-right'] = '7px';

				$css[$media_query]['#wrapper .catalog-ordering .sort-count.order-dropdown']['width']        = '215px';
				$css[$media_query]['#wrapper .catalog-ordering .sort-count.order-dropdown']['float']        = 'left !important';
				$css[$media_query]['#wrapper .catalog-ordering .sort-count.order-dropdown']['margin-left']  = '7px !important';
				$css[$media_query]['#wrapper .catalog-ordering .sort-count.order-dropdown']['margin-right'] = '7px !important';

				$css[$media_query]['#wrapper .sort-count.order-dropdown ul a']['width'] = '215px';

				$css[$media_query]['#wrapper .catalog-ordering .order']['float']         = 'left !important';
				$css[$media_query]['#wrapper .catalog-ordering .order']['margin-bottom'] = '0 !important';

				$elements = array(
					'.products-2 li:nth-child(2n+1)',
					'.products-3 li:nth-child(3n+1)',
					'.products-4 li:nth-child(4n+1)',
					'.products-5 li:nth-child(5n+1)',
					'.products-6 li:nth-child(6n+1)'
				);
				$css[$media_query][avada_implode( $elements )]['clear'] = 'none !important';

				$css[$media_query]['#main .products li:nth-child(3n+1)']['clear'] = 'both !important';

				$elements = array(
					'.products li',
					'#main .products li:nth-child(3n)',
					'#main .products li:nth-child(4n)'
				);
				$css[$media_query][avada_implode( $elements )]['width']        = '32.3% !important';
				$css[$media_query][avada_implode( $elements )]['float']        = 'left !important';
				$css[$media_query][avada_implode( $elements )]['margin-right'] = '1% !important';

				$elements = array(
					'.woocommerce #customer_login .login .form-row',
					'.woocommerce #customer_login .login .lost_password'
				);
				$css[$media_query][avada_implode( $elements )]['float'] = 'none';

				$elements = array(
					'.woocommerce #customer_login .login .inline',
					'.woocommerce #customer_login .login .lost_password'
				);
				$css[$media_query][avada_implode( $elements )]['display']     = 'block';
				$css[$media_query][avada_implode( $elements )]['margin-left'] = '0';

				$css[$media_query]['.avada-myaccount-data .my_account_orders .order-number']['padding-right'] = '8px';

				$css[$media_query]['.avada-myaccount-data .my_account_orders .order-actions']['padding-left'] = '8px';

				$css[$media_query]['.shop_table .product-name']['width'] = '35%';

				$elements = array(
					'#wrapper .woocommerce-side-nav',
					'#wrapper .woocommerce-content-box',
					'#wrapper .shipping-coupon',
					'#wrapper .cart_totals',
					'#wrapper #customer_login .col-1',
					'#wrapper #customer_login .col-2',
					'#wrapper .woocommerce form.checkout #customer_details .col-1',
					'#wrapper .woocommerce form.checkout #customer_details .col-2'
				);
				$css[$media_query][avada_implode( $elements )]['float']        = 'none';
				$css[$media_query][avada_implode( $elements )]['margin-left']  = 'auto';
				$css[$media_query][avada_implode( $elements )]['margin-right'] = 'auto';
				$css[$media_query][avada_implode( $elements )]['width']        = '100% !important';

				$elements = array(
					'#customer_login .col-1',
					'.coupon'
				);
				$css[$media_query][avada_implode( $elements )]['margin-bottom'] = '20px';

				$css[$media_query]['.shop_table .product-thumbnail']['float'] = 'none';

				$css[$media_query]['.product-info']['margin-left'] = '0';
				$css[$media_query]['.product-info']['margin-top']  = '10px';

				$css[$media_query]['.product .entry-summary div .price']['float'] = 'none';

				$css[$media_query]['.product .entry-summary .woocommerce-product-rating']['float']       = 'none';
				$css[$media_query]['.product .entry-summary .woocommerce-product-rating']['margin-left'] = '0';

				$elements = array(
					'.woocommerce-tabs .tabs',
					'.woocommerce-side-nav'
				);
				$css[$media_query][avada_implode( $elements )]['margin-bottom'] = '25px';

				$css[$media_query]['.woocommerce-tabs .panel']['width']   = '91% !important';
				$css[$media_query]['.woocommerce-tabs .panel']['padding'] = '4% !important';

				$css[$media_query]['#reviews li .avatar']['display'] = 'none';

				$css[$media_query]['#reviews li .comment-text']['width']       = '90% !important';
				$css[$media_query]['#reviews li .comment-text']['margin-left'] = '0 !important';
				$css[$media_query]['#reviews li .comment-text']['padding']     = '5% !important';

				$css[$media_query]['.woocommerce-container .social-share']['overflow'] = 'hidden';

				$css[$media_query]['.woocommerce-container .social-share li']['display']       = 'block';
				$css[$media_query]['.woocommerce-container .social-share li']['float']         = 'left';
				$css[$media_query]['.woocommerce-container .social-share li']['margin']        = '0 auto';
				$css[$media_query]['.woocommerce-container .social-share li']['border-right']  = '0 !important';
				$css[$media_query]['.woocommerce-container .social-share li']['border-left']   = '0 !important';
				$css[$media_query]['.woocommerce-container .social-share li']['padding-left']  = '0 !important';
				$css[$media_query]['.woocommerce-container .social-share li']['padding-right'] = '0 !important';
				$css[$media_query]['.woocommerce-container .social-share li']['width']         = '25%';

				$css[$media_query]['.has-sidebar .woocommerce-container .social-share li']['width'] = '50%';

				$css[$media_query]['.myaccount_user_container span']['width']        = '100%';
				$css[$media_query]['.myaccount_user_container span']['float']        = 'none';
				$css[$media_query]['.myaccount_user_container span']['display']      = 'block';
				$css[$media_query]['.myaccount_user_container span']['padding']      = '10px 0px';
				$css[$media_query]['.myaccount_user_container span']['border-right'] = '0';

				if ( is_rtl() ) {
					$css[$media_query]['.rtl .myaccount_user_container span']['border-left'] = '0';
				}

				$elements = array(
					'.shop_table .product-thumbnail img',
					'.shop_table .product-thumbnail .product-info',
					'.shop_table .product-thumbnail .product-info p'
				);
				$css[$media_query][avada_implode( $elements )]['float']   = 'none';
				$css[$media_query][avada_implode( $elements )]['width']   = '100%';
				$css[$media_query][avada_implode( $elements )]['margin']  = '0 !important';
				$css[$media_query][avada_implode( $elements )]['padding'] = '0';

				$css[$media_query]['.shop_table .product-thumbnail']['padding'] = '10px 0px';

				$css[$media_query]['.product .images']['margin-bottom'] = '30px';

				$css[$media_query]['#customer_login_box .button']['float']         = 'left';
				$css[$media_query]['#customer_login_box .button']['margin-bottom'] = '15px';

				$css[$media_query]['#customer_login_box .remember-box']['clear']   = 'both';
				$css[$media_query]['#customer_login_box .remember-box']['display'] = 'block';
				$css[$media_query]['#customer_login_box .remember-box']['padding'] = '0';
				$css[$media_query]['#customer_login_box .remember-box']['width']   = '125px';
				$css[$media_query]['#customer_login_box .remember-box']['float']   = 'left';

				$css[$media_query]['#customer_login_box .lost_password']['float'] = 'left';

				$elements = array(
					'#wrapper .product .images',
					'#wrapper .product .summary.entry-summary'
				);
				$css[$media_query][avada_implode( $elements )]['width'] = '50% !important';
				$css[$media_query][avada_implode( $elements )]['float'] = 'left !important';

				$css[$media_query]['#wrapper .product .summary.entry-summary']['width']       = '48% !important';
				$css[$media_query]['#wrapper .product .summary.entry-summary']['margin-left'] = '2% !important';

				$css[$media_query]['#wrapper .woocommerce-tabs .tabs']['width'] = '24% !important';
				$css[$media_query]['#wrapper .woocommerce-tabs .tabs']['float'] = 'left !important';

				$css[$media_query]['#wrapper .woocommerce-tabs .panel']['float']   = 'right !important';
				$css[$media_query]['#wrapper .woocommerce-tabs .panel']['width']   = '70% !important';
				$css[$media_query]['#wrapper .woocommerce-tabs .panel']['padding'] = '4% !important';

				$elements = array(
					'.product .images #slider .flex-direction-nav',
					'.product .images #carousel .flex-direction-nav'
				);
				$css[$media_query][avada_implode( $elements )]['display'] = 'none !important';

				$elements = array(
					'.myaccount_user_container span.msg',
					'.myaccount_user_container span:last-child'
				);
				$css[$media_query][avada_implode( $elements )]['padding-left']  = '0 !important';
				$css[$media_query][avada_implode( $elements )]['padding-right'] = '0 !important';

			}

			$css[$media_query]['body #small-nav']['visibility'] = 'visible !important';

			$elements = array();
			if ( class_exists( 'GFForms' ) ) {
				$elements[] = '.gform_wrapper .ginput_complex .ginput_left';
				$elements[] = '.gform_wrapper .ginput_complex .ginput_right';
				$elements[] = '.gform_wrapper .gfield input[type="text"]';
				$elements[] = '.gform_wrapper .gfield textarea';
			}
			if ( defined( 'WPCF7_PLUGIN' ) ) {
				$elements[] = '.wpcf7-form .wpcf7-text';
				$elements[] = '.wpcf7-form .wpcf7-quiz';
				$elements[] = '.wpcf7-form .wpcf7-number';
				$elements[] = '.wpcf7-form textarea';
			}

			$css[$media_query][avada_implode( $elements )]['float']      = 'none !important';
			$css[$media_query][avada_implode( $elements )]['width']      = '100% !important';
			$css[$media_query][avada_implode( $elements )]['box-sizing'] = 'border-box';

			$css[$media_query]['#nav-uber #megaMenu']['width'] = '100%';

			$css[$media_query]['.fullwidth-box']['background-attachment'] = 'scroll';

			$css[$media_query]['#toTop']['bottom']        = '30px';
			$css[$media_query]['#toTop']['border-radius'] = '4px';
			$css[$media_query]['#toTop']['height']        = '40px';
			$css[$media_query]['#toTop']['z-index']       = '10000';

			$css[$media_query]['#toTop:before']['line-height'] = '38px';

			$css[$media_query]['#toTop:hover']['background-color'] = '#333333';

			$css[$media_query]['.no-mobile-totop .to-top-container']['display'] = 'none';

			$css[$media_query]['.no-mobile-slidingbar #slidingbar-area']['display'] = 'none';

			$css[$media_query]['.tfs-slider .slide-content-container .btn']['min-height']    = '0 !important';
			$css[$media_query]['.tfs-slider .slide-content-container .btn']['padding-left']  = '20px';
			$css[$media_query]['.tfs-slider .slide-content-container .btn']['padding-right'] = '20px !important';
			$css[$media_query]['.tfs-slider .slide-content-container .btn']['height']        = '26px !important';
			$css[$media_query]['.tfs-slider .slide-content-container .btn']['line-height']   = '26px !important';

			$css[$media_query]['.fusion-soundcloud iframe']['width'] = '100%';

			$elements = array(
				'.fusion-columns-2 .fusion-column',
				'.fusion-columns-2 .fusion-flip-box-wrapper',
				'.fusion-columns-4 .fusion-column',
				'.fusion-columns-4 .fusion-flip-box-wrapper'
			);
			$css[$media_query][avada_implode( $elements )]['width'] = '50% !important';
			$css[$media_query][avada_implode( $elements )]['float'] = 'left !important';

			$elements = array(
				'.fusion-columns-2 .fusion-column:nth-child(3n)',
				'.fusion-columns-4 .fusion-column:nth-child(3n)',
				'.fusion-columns-2 .fusion-flip-box-wrapper:nth-child(3n)'
			);
			$css[$media_query][avada_implode( $elements )]['clear'] = 'both';

			$elements = array(
				'.fusion-columns-3 .fusion-column',
				'.fusion-columns-3 .fusion-flip-box-wrapper',
				'.fusion-columns-5 .fusion-column',
				'.fusion-columns-5 .fusion-flip-box-wrapper',
				'.fusion-columns-6 .fusion-column',
				'.fusion-columns-6 .fusion-flip-box-wrapper',
				'.fusion-columns-5 .col-lg-2',
				'.fusion-columns-5 .col-md-2',
				'.fusion-columns-5 .col-sm-2'
			);
			$css[$media_query][avada_implode( $elements )]['width'] = '33.33% !important';
			$css[$media_query][avada_implode( $elements )]['float'] = 'left !important';

			$elements = array(
				'.fusion-columns-3 .fusion-column:nth-child(4n)',
				'.fusion-columns-3 .fusion-flip-box-wrapper:nth-child(4n)',
				'.fusion-columns-5 .fusion-column:nth-child(4n)',
				'.fusion-columns-5 .fusion-flip-box-wrapper:nth-child(4n)',
				'.fusion-columns-6 .fusion-column:nth-child(4n)',
				'.fusion-columns-6 .fusion-flip-box-wrapper:nth-child(4n)'
			);
			$css[$media_query][avada_implode( $elements )]['clear'] = 'both';

			$elements = array(
				'.footer-area .fusion-column',
				'#slidingbar .fusion-column'
			);
			$css[$media_query][avada_implode( $elements )]['margin-bottom'] = '40px';

			$elements = array(
				'.fusion-layout-column.fusion-one-sixth',
				'.fusion-layout-column.fusion-five-sixth',
				'.fusion-layout-column.fusion-one-fifth',
				'.fusion-layout-column.fusion-two-fifth',
				'.fusion-layout-column.fusion-three-fifth',
				'.fusion-layout-column.fusion-four-fifth',
				'.fusion-layout-column.fusion-one-fourth',
				'.fusion-layout-column.fusion-three-fourth',
				'.fusion-layout-column.fusion-one-third',
				'.fusion-layout-column.fusion-two-third',
				'.fusion-layout-column.fusion-one-half'
			);
			$css[$media_query][avada_implode( $elements )]['position']      = 'relative';
			$css[$media_query][avada_implode( $elements )]['float']         = 'left';
			$css[$media_query][avada_implode( $elements )]['margin-right']  = '4%';
			$css[$media_query][avada_implode( $elements )]['margin-bottom'] = '20px';

			$css[$media_query]['.fusion-layout-column.fusion-one-sixth']['width']    = '13.3333%';
			$css[$media_query]['.fusion-layout-column.fusion-five-sixth']['width']   = '82.6666%';
			$css[$media_query]['.fusion-layout-column.fusion-one-fifth']['width']    = '16.8%';
			$css[$media_query]['.fusion-layout-column.fusion-two-fifth']['width']    = '37.6%';
			$css[$media_query]['.fusion-layout-column.fusion-three-fifth']['width']  = '58.4%';
			$css[$media_query]['.fusion-layout-column.fusion-four-fifth']['width']   = '79.2%';
			$css[$media_query]['.fusion-layout-column.fusion-one-fourth']['width']   = '22%';
			$css[$media_query]['.fusion-layout-column.fusion-three-fourth']['width'] = '74%';
			$css[$media_query]['.fusion-layout-column.fusion-one-third']['width']    = '30.6666%';
			$css[$media_query]['.fusion-layout-column.fusion-two-third']['width']    = '65.3333%';
			$css[$media_query]['.fusion-layout-column.fusion-one-half']['width']     = '48%';

			// No spacing Columns

			$css[$media_query]['.fusion-layout-column.fusion-spacing-no']['margin-left']  = '0';
			$css[$media_query]['.fusion-layout-column.fusion-spacing-no']['margin-right'] = '0';

			$css[$media_query]['.fusion-layout-column.fusion-one-sixth.fusion-spacing-no']['width']    = '16.6666666667% !important';
			$css[$media_query]['.fusion-layout-column.fusion-five-sixth.fusion-spacing-no']['width']   = '83.333333333% !important';
			$css[$media_query]['.fusion-layout-column.fusion-one-fifth.fusion-spacing-no']['width']    = '20% !important';
			$css[$media_query]['.fusion-layout-column.fusion-two-fifth.fusion-spacing-no']['width']    = '40% !important';
			$css[$media_query]['.fusion-layout-column.fusion-three-fifth.fusion-spacing-no']['width']  = '60% !important';
			$css[$media_query]['.fusion-layout-column.fusion-four-fifth.fusion-spacing-no']['width']   = '80% !important';
			$css[$media_query]['.fusion-layout-column.fusion-one-fourth.fusion-spacing-no']['width']   = '25% !important';
			$css[$media_query]['.fusion-layout-column.fusion-three-fourth.fusion-spacing-no']['width'] = '75% !important';
			$css[$media_query]['.fusion-layout-column.fusion-one-third.fusion-spacing-no']['width']    = '33.33333333% !important';
			$css[$media_query]['.fusion-layout-column.fusion-two-third.fusion-spacing-no']['width']    = '66.66666667% !important';
			$css[$media_query]['.fusion-layout-column.fusion-one-half.fusion-spacing-no']['width']     = '50% !important';

			$css[$media_query]['.fusion-layout-column.fusion-column-last']['clear']        = 'right';
			$css[$media_query]['.fusion-layout-column.fusion-column-last']['zoom']         = '1';
			$css[$media_query]['.fusion-layout-column.fusion-column-last']['margin-left']  = '0';
			$css[$media_query]['.fusion-layout-column.fusion-column-last']['margin-right'] = '0';

			$css[$media_query]['.fusion-column.fusion-spacing-no']['margin-bottom'] = '0';
			$css[$media_query]['.fusion-column.fusion-spacing-no']['width']         = '100% !important';

			$css[$media_query]['.sidebar']['margin-left'] = '0 !important';
			$css[$media_query]['.sidebar']['width']       = '25% !important';

			$css[$media_query]['#content']['margin-left'] = '0 !important';

			$elements = array(
				'.has-sidebar #main #content',
				'#main #content.with-sidebar',
				'.has-sidebar .project-content .project-description'
			);
			$css[$media_query][avada_implode( $elements )]['width'] = '72% !important';

			$css[$media_query]['.sidebar-position-left .sidebar']['float'] = 'left !important';

			$css[$media_query]['.sidebar-position-left #content']['float'] = 'right !important';

			$css[$media_query]['.sidebar-position-right .sidebar']['float'] = 'right !important';

			$css[$media_query]['.sidebar-position-right #content']['float'] = 'left !important';

			$css[$media_query]['#sidebar-2']['clear'] = 'left';

			$elements = array(
				'.ua-mobile .page-title-bar',
				'.ua-mobile .fusion-footer-widget-area',
				'.ua-mobile body',
				'.ua-mobile #main'
			);
			$css[$media_query][avada_implode( $elements )]['background-attachment'] = 'scroll !important';

			$elements = array(
				'.fusion-secondary-header .fusion-row',
				'.fusion-header .fusion-row',
				'.footer-area > .fusion-row',
				'#footer > .fusion-row',
				'#header-sticky .fusion-row'
			);
			$css[$media_query][avada_implode( $elements )]['padding-left']  = '0px !important';
			$css[$media_query][avada_implode( $elements )]['padding-right'] = '0px !important';

			$css[$media_query]['.error-message']['font-size'] = '130px';

		}

	}

	if ( ! Avada()->settings->get( 'responsive' ) ) {

		$css['global']['.ua-mobile #wrapper']['width']    		= '100% !important';
		$css['global']['.ua-mobile #wrapper']['overflow'] 		= 'hidden !important';
		$css['global']['.ua-mobile #slidingbar-area']['width'] 	= $site_width_with_units;
		$css['global']['.ua-mobile #slidingbar-area']['left'] 	= '0';

	}

	// WPML Flag positioning on the main menu when header is on the Left/Right.
	if ( class_exists( 'SitePress' ) && 'Top' != Avada()->settings->get( 'header_position' ) ) {
		$css['global']['.fusion-main-menu > ul > li > a .iclflag']['margin-top'] = '14px !important';
	}

	/**
	 * IE11
	 */
	if ( strpos( false !== $_SERVER['HTTP_USER_AGENT'], 'Trident/7.0; rv:11.0' ) ) {

		$elements = array(
			'.avada-select-parent .select-arrow',
			'.select-arrow',
		);
		if ( defined( 'WPCF7_PLUGIN' ) ) {
			$elements[] = '.wpcf7-select-parent .select-arrow';
		}

		$css['global'][avada_implode( $elements )]['height']      = '33px';
		$css['global'][avada_implode( $elements )]['line-height'] = '33px';

		$css['global']['.gravity-select-parent .select-arrow']['height']      = '24px';
		$css['global']['.gravity-select-parent .select-arrow']['line-height'] = '24px';

		if ( class_exists( 'GFForms' ) ) {
			$elements = array(
				'#wrapper .gf_browser_ie.gform_wrapper .button',
				'#wrapper .gf_browser_ie.gform_wrapper .gform_footer input.button'
			);
			$css['global'][avada_implode( $elements )]['padding'] = '0 20px';
		}

	}

	/**
	 * IE11 hack
	 */
	$media_query = '@media screen and (-ms-high-contrast: active), (-ms-high-contrast: none)';
	$elements = array(
		'.avada-select-parent .select-arrow',
		'.select-arrow',
	);
	if ( defined( 'WPCF7_PLUGIN' ) ) {
		'.wpcf7-select-parent .select-arrow';
	}

	$css['global'][avada_implode( $elements )]['height']      = '33px';
	$css['global'][avada_implode( $elements )]['line-height'] = '33px';

	$css[$media_query]['.gravity-select-parent .select-arrow']['height']      = '24px';
	$css[$media_query]['.gravity-select-parent .select-arrow']['line-height'] = '24px';

	if ( class_exists( 'GFForms' ) ) {
		$elements = array(
			'#wrapper .gf_browser_ie.gform_wrapper .button',
			'#wrapper .gf_browser_ie.gform_wrapper .gform_footer input.button',
		);
		$css[$media_query][avada_implode( $elements )]['padding'] = '0 20px';
	}

	$css[$media_query]['.fusion-imageframe, .imageframe-align-center']['font-size']   = '0px';
	$css[$media_query]['.fusion-imageframe, .imageframe-align-center']['line-height'] = 'normal';


	$hundredp_padding     = Avada()->settings->get( 'hundredp_padding' );
	$hundredp_padding_int = (int) $hundredp_padding;

	if ( get_post_meta( $c_pageID, 'pyre_hundredp_padding', true ) ) {
		$hundredp_padding     = get_post_meta( $c_pageID, 'pyre_hundredp_padding', true );
		$hundredp_padding_int = (int) $hundredp_padding;
	}

	if ( $site_width_percent) {

		$elements = array(
			'.fusion-secondary-header',
			'.header-v4 #small-nav',
			'.header-v5 #small-nav',
			'#main'
		);
		$css['global'][avada_implode( $elements )]['padding-left']  = '0px';
		$css['global'][avada_implode( $elements )]['padding-right'] = '0px';

		$elements = array(
			'#slidingbar .fusion-row',
			'#sliders-container .tfs-slider .slide-content-container',
			'#main .fusion-row',
			'.fusion-page-title-bar',
			'.fusion-header',
			'.fusion-footer-widget-area',
			'.fusion-footer-copyright-area',
			'.fusion-secondary-header .fusion-row'
		);
		$css['global'][avada_implode( $elements )]['padding-left']  = Avada_Sanitize::size( $hundredp_padding );
		$css['global'][avada_implode( $elements )]['padding-right'] = Avada_Sanitize::size( $hundredp_padding );

		$elements = array(
			'.fullwidth-box',
			'.fullwidth-box .fusion-row .fusion-full-width-sep'
		);
		$css['global'][avada_implode( $elements )]['margin-left']  = '-' . $hundredp_padding_int . 'px';
		$css['global'][avada_implode( $elements )]['margin-right'] = '-' . $hundredp_padding_int . 'px';

		$css['global']['#main.width-100 > .fusion-row']['padding-left']  = '0';
		$css['global']['#main.width-100 > .fusion-row']['padding-right'] = '0';

	}

	if ( 'Boxed' == Avada()->settings->get( 'layout' ) ) {

		$elements = array( 'html', 'body' );

		$background_color = ( get_post_meta( $c_pageID, 'pyre_page_bg_color', true ) ) ? get_post_meta( $c_pageID, 'pyre_page_bg_color', true ) : Avada()->settings->get( 'bg_color' );
		$css['global'][avada_implode( $elements )]['background-color'] = Avada_Sanitize::color( $background_color );

		if ( get_post_meta( $c_pageID, 'pyre_page_bg', true ) ) {

			$css['global']['body']['background-image']  = 'url("' . Avada_Sanitize::css_asset_url( get_post_meta( $c_pageID, 'pyre_page_bg', true ) ) . '")';
			$css['global']['body']['background-repeat'] = get_post_meta( $c_pageID, 'pyre_page_bg_repeat', true );

			if ( 'yes' == get_post_meta( $c_pageID, 'pyre_page_bg_full', true ) ) {

				$css['global']['body']['background-attachment'] = 'fixed';
				$css['global']['body']['background-position']   = 'center center';
				$css['global']['body']['background-size']       = 'cover';

			}

		} elseif ( Avada()->settings->get( 'bg_image' ) ) {

			$css['global']['body']['background-image']  = 'url("' . Avada_Sanitize::css_asset_url( Avada()->settings->get( 'bg_image' ) ) . '")';
			$css['global']['body']['background-repeat'] = esc_attr( Avada()->settings->get( 'bg_repeat' ) );

			if ( Avada()->settings->get( 'bg_full' ) ) {

				$css['global']['body']['background-attachment'] = 'fixed';
				$css['global']['body']['background-position']   = 'center center';
				$css['global']['body']['background-size']       = 'cover';

			}

		}

		if ( Avada()->settings->get( 'bg_pattern_option' ) && Avada()->settings->get( 'bg_pattern' ) && ! ( get_post_meta( $c_pageID, 'pyre_page_bg_color', true ) || get_post_meta( $c_pageID, 'pyre_page_bg', true ) ) ) {

			$elements = array( 'html', 'body' );
			$css['global'][avada_implode( $elements )]['background-image']  = 'url("' . Avada_Sanitize::css_asset_url( get_template_directory_uri() . '/assets/images/patterns/' . Avada()->settings->get( 'bg_pattern' ) . '.png' ) . '")';
			$css['global'][avada_implode( $elements )]['background-repeat'] = 'repeat';

		}

		$elements = array(
			'#wrapper',
			'.fusion-footer-parallax'
		);
		$css['global'][avada_implode( $elements )]['max-width'] = ( $site_width_percent ) ? $site_width_with_units : ( $site_width + 60 ) .  'px';
		$css['global'][avada_implode( $elements )]['margin']    = '0 auto';

		$css['global']['.wrapper_blank']['display'] = 'block';

		$media_query = '@media (min-width: 1014px)';
		$css[$media_query]['body #header-sticky.sticky-header']['width']  = ( $site_width_percent ) ? $site_width_with_units : ( $site_width + 60 ) .  'px';
		$css[$media_query]['body #header-sticky.sticky-header']['left']   = '0';
		$css[$media_query]['body #header-sticky.sticky-header']['right']  = '0';
		$css[$media_query]['body #header-sticky.sticky-header']['margin'] = '0 auto';

		if ( Avada()->settings->get( 'responsive' ) && $site_width_percent ) {

			$elements = array(
				'#main .fusion-row',
				'.fusion-footer-widget-area .fusion-row',
				'#slidingbar-area .fusion-row',
				'.fusion-footer-copyright-area .fusion-row',
				'.fusion-page-title-row',
				'.fusion-secondary-header .fusion-row',
				'#small-nav .fusion-row',
				'.fusion-header .fusion-row'
			);
			$css['global'][avada_implode( $elements )]['max-width'] = 'none';
			$css['global'][avada_implode( $elements )]['padding']   = '0 10px';

		}

		if ( Avada()->settings->get( 'responsive' ) ) {

			$media_query = '@media only screen and (min-width: 801px) and (max-width: 1014px)';
			$css[$media_query]['#wrapper']['width'] = 'auto';

			$media_query = '@media only screen and (min-device-width: 801px) and (max-device-width: 1014px)';
			$css[$media_query]['#wrapper']['width'] = 'auto';

		}

	}

	if ( 'Wide' == Avada()->settings->get( 'layout' ) ) {

		$css['global']['#wrapper']['width']     = '100%';
		$css['global']['#wrapper']['max-width'] = 'none';

		$media_query = '@media only screen and (min-width: 801px) and (max-width: 1014px)';
		$css[$media_query]['#wrapper']['width'] = 'auto';

		$media_query = '@media only screen and (min-device-width: 801px) and (max-device-width: 1014px)';
		$css[$media_query]['#wrapper']['width'] = 'auto';

	}

	if ( 'boxed' == get_post_meta( $c_pageID, 'pyre_page_bg_layout', true ) ) {

		$elements = array( 'html', 'body' );

		$background_color = ( get_post_meta( $c_pageID, 'pyre_page_bg_color', true ) ) ? get_post_meta( $c_pageID, 'pyre_page_bg_color', true ) : Avada()->settings->get( 'bg_color' );
		$css['global'][avada_implode( $elements )]['background-color'] = Avada_Sanitize::color( $background_color );

		if ( get_post_meta( $c_pageID, 'pyre_page_bg', true ) ) {

			$css['global']['body']['background-image']  = 'url("' . Avada_Sanitize::css_asset_url( get_post_meta( $c_pageID, 'pyre_page_bg', true ) ) . '")';
			$css['global']['body']['background-repeat'] = get_post_meta( $c_pageID, 'pyre_page_bg_repeat', true );

			if ( 'yes' == get_post_meta( $c_pageID, 'pyre_page_bg_full', true ) ) {

				$css['global']['body']['background-attachment'] = 'fixed';
				$css['global']['body']['background-position']   = 'center center';
				$css['global']['body']['background-size']       = 'cover';

			}

		} elseif ( Avada()->settings->get( 'bg_image' ) ) {

			$css['global']['body']['background-image']  = 'url("' . Avada_Sanitize::css_asset_url( Avada()->settings->get( 'bg_image' ) ) . '")';
			$css['global']['body']['background-repeat'] = esc_attr( Avada()->settings->get( 'bg_repeat' ) );

			if ( Avada()->settings->get( 'bg_full' ) ) {

				$css['global']['body']['background-attachment'] = 'fixed';
				$css['global']['body']['background-position']   = 'center center';
				$css['global']['body']['background-size']       = 'cover';

			}

		}

		if ( Avada()->settings->get( 'bg_pattern_option' ) && Avada()->settings->get( 'bg_pattern' ) && ! ( get_post_meta( $c_pageID, 'pyre_page_bg_color', true ) || get_post_meta( $c_pageID, 'pyre_page_bg', true ) ) ) {

			$css['global']['body']['background-image']  = 'url("' . Avada_Sanitize::css_asset_url( get_template_directory_uri() . '/assets/images/patterns/' . Avada()->settings->get( 'bg_pattern' ) . '.png' ) . '")';
			$css['global']['body']['background-repeat'] = 'repeat';

		}

		$elements = array( '#wrapper', '.fusion-footer-parallax' );
		$css['global'][avada_implode( $elements )]['width']     = ( $site_width_percent ) ? $site_width_with_units : ( $site_width + 60 ) .  'px';
		$css['global'][avada_implode( $elements )]['margin']    = '0 auto';
		$css['global'][avada_implode( $elements )]['max-width'] = '100%';

		$css['global']['.wrapper_blank']['display'] = 'block';

		$media_query = '@media (min-width: 1014px)';
		$css[$media_query]['body #header-sticky.sticky-header']['width']  = ( $site_width_percent ) ? $site_width_with_units : ( $site_width + 60 ) .  'px';
		$css[$media_query]['body #header-sticky.sticky-header']['left']   = '0';
		$css[$media_query]['body #header-sticky.sticky-header']['right']  = '0';
		$css[$media_query]['body #header-sticky.sticky-header']['margin'] = '0 auto';

		$media_query = '@media only screen and (min-width: 801px) and (max-width: 1014px)';
		$css[$media_query]['#wrapper']['width'] = 'auto';

		$media_query = '@media only screen and (min-device-width: 801px) and (max-device-width: 1014px)';
		$css[$media_query]['#wrapper']['width'] = 'auto';

	}

	if ( 'wide' == get_post_meta( $c_pageID, 'pyre_page_bg_layout', true ) ) {

		$css['global']['#wrapper']['width']     = '100%';
		$css['global']['#wrapper']['max-width'] = 'none';

		$media_query = '@media only screen and (min-width: 801px) and (max-width: 1014px)';
		$css[$media_query]['#wrapper']['width'] = 'auto';

		$media_query = '@media only screen and (min-device-width: 801px) and (max-device-width: 1014px)';
		$css[$media_query]['#wrapper']['width'] = 'auto';

		$css['global']['body #header-sticky.sticky-header']['width']  = '100%';
		$css['global']['body #header-sticky.sticky-header']['left']   = '0';
		$css['global']['body #header-sticky.sticky-header']['right']  = '0';
		$css['global']['body #header-sticky.sticky-header']['margin'] = '0 auto';

	}

	if ( get_post_meta( $c_pageID, 'pyre_page_bg', true ) || Avada()->settings->get( 'bg_image' ) ) {
		$css['global']['html']['background'] = 'none';
	}

	if ( Avada()->settings->get( 'mobile_nav_padding' ) ) {

		if ( Avada()->settings->get( 'mobile_nav_padding' ) ) {
			$media_query = '@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) and (orientation: portrait)';
			$css[$media_query]['.fusion-main-menu > ul > li']['padding-right'] = intval( Avada()->settings->get( 'mobile_nav_padding' ) ) . 'px';
		}

		if ( Avada()->settings->get( 'mobile_nav_padding' ) ) {
			$media_query = '@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) and (orientation: landscape)';
			$css[$media_query]['.fusion-main-menu > ul > li']['padding-right'] = intval( Avada()->settings->get( 'mobile_nav_padding' ) ) . 'px';
		}

	}

	if ( get_post_meta ( $c_pageID, 'pyre_page_title_bar_bg', true ) ) {
		$css['global']['.fusion-page-title-bar']['background-image'] = 'url("' . Avada_Sanitize::css_asset_url( get_post_meta( $c_pageID, 'pyre_page_title_bar_bg', true ) ) . '")';
	} elseif( Avada()->settings->get( 'page_title_bg' ) ) {
		$css['global']['.fusion-page-title-bar']['background-image'] = 'url("' . Avada_Sanitize::css_asset_url( Avada()->settings->get( 'page_title_bg' ) ) . '")';
	}

	if ( get_post_meta( $c_pageID, 'pyre_page_title_bar_bg_color', true ) ) {
		$css['global']['.fusion-page-title-bar']['background-color'] = get_post_meta( $c_pageID, 'pyre_page_title_bar_bg_color', true );
	} elseif ( Avada()->settings->get( 'page_title_bg_color' ) ) {
		$css['global']['.fusion-page-title-bar']['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'page_title_bg_color' ), Avada()->settings->get_default( 'page_title_bg_color' ) );
	}

	if ( get_post_meta( $c_pageID, 'pyre_page_title_bar_borders_color', true ) ) {
		$css['global']['.fusion-page-title-bar']['border-color'] = get_post_meta( $c_pageID, 'pyre_page_title_bar_borders_color', true );
	}


	$elements = array( '.fusion-header', '#side-header' );
	if ( Avada()->settings->get( 'header_bg_image' ) ) {

		$css['global'][avada_implode( $elements )]['background-image'] = 'url("' . Avada_Sanitize::css_asset_url( Avada()->settings->get( 'header_bg_image' ) ) . '")';
		if ( in_array( Avada()->settings->get( 'header_bg_repeat' ), array( 'repeat-y', 'no-repeat' ) ) ) {
			$css['global'][avada_implode( $elements )]['background-position'] = 'center center';
		}
		$css['global'][avada_implode( $elements )]['background-repeat'] = esc_attr( Avada()->settings->get( 'header_bg_repeat' ) );
		if ( Avada()->settings->get( 'header_bg_full' ) ) {
			if ( 'Top' == Avada()->settings->get( 'header_position' ) ) {
				$css['global'][avada_implode( $elements )]['background-attachment'] = 'scroll';
			}
			$css['global'][avada_implode( $elements )]['background-position'] = 'center center';
			$css['global'][avada_implode( $elements )]['background-size']     = 'cover';
		}
		if ( Avada()->settings->get( 'header_bg_parallax' ) && 'Top' == Avada()->settings->get( 'header_position' ) ) {
			$css['global'][avada_implode( $elements )]['background-attachment'] = 'fixed';
			$css['global'][avada_implode( $elements )]['background-position']   = 'top center';
		}
	}

	$elements = array(
		'.fusion-header',
		'#side-header',
		'.layout-boxed-mode .side-header-wrapper'
	);
	if ( get_post_meta( $c_pageID, 'pyre_header_bg_color', true ) ) {

		if ( '' != get_post_meta( $c_pageID, 'pyre_header_bg_opacity', true ) ) {
			$header_bg_opacity = get_post_meta( $c_pageID, 'pyre_header_bg_opacity', true );
		} elseif ( Avada()->settings->get( 'header_bg_color' ) ) {
			$header_bg_opacity = Avada()->settings->get( 'header_bg_color', 'opacity' );
		} else {
			$header_bg_opacity = 1;
		}

		$header_bg_color_rgb = fusion_hex2rgb( get_post_meta( $c_pageID, 'pyre_header_bg_color', true ) );

		if ( get_post_meta( $c_pageID, 'pyre_header_bg_color', true ) ) {

			$css['global'][avada_implode( $elements )]['background-color'] = get_post_meta( $c_pageID, 'pyre_header_bg_color', true );

			if ( ( function_exists( 'is_shop' ) && is_shop() ) || ( ! is_archive() && ! is_404() && ! is_search() ) ) {
				$css['global'][avada_implode( $elements )]['background-color'] = 'rgba(' . $header_bg_color_rgb[0] . ',' . $header_bg_color_rgb[1] . ',' . $header_bg_color_rgb[2] . ',' . $header_bg_opacity . ')';
			}

		}

	} elseif ( Avada()->settings->get( 'header_bg_color' ) ) {

		if ( '' != get_post_meta( $c_pageID, 'pyre_header_bg_opacity', true ) ) {
			$header_bg_opacity = get_post_meta( $c_pageID, 'pyre_header_bg_opacity', true );
		} elseif ( Avada()->settings->get( 'header_bg_color' ) ) {
			$header_bg_opacity = Avada()->settings->get( 'header_bg_color', 'opacity' );
		} else {
			$header_bg_opacity = 1;
		}

		$header_bg_color_rgb = fusion_hex2rgb( Avada()->settings->get( 'header_bg_color', 'color' ) );

		if ( Avada()->settings->get( 'header_bg_color', 'color' ) ) {

			$css['global'][avada_implode( $elements )]['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'header_bg_color', 'color' ), Avada()->settings->get_default( 'header_bg_color', 'color' ) );

			if ( ( function_exists( 'is_shop' ) && is_shop() ) || ( ! is_archive() && ! is_404() && ! is_search() ) ) {
				$css['global'][avada_implode( $elements )]['background-color'] = Avada_Sanitize::color( 'rgba(' . $header_bg_color_rgb[0] . ',' . $header_bg_color_rgb[1] . ',' . $header_bg_color_rgb[2] . ',' . $header_bg_opacity . ')' );
			}

		}

	}

	if ( Avada()->settings->get( 'menu_h45_bg_color' ) ) {

		if ( '' != get_post_meta( $c_pageID, 'pyre_header_bg_opacity', true ) ) {
			$header_bg_opacity = get_post_meta( $c_pageID, 'pyre_header_bg_opacity', true );
		} elseif ( Avada()->settings->get( 'menu_h45_bg_color' ) ) {
			$header_bg_opacity = Avada()->settings->get( 'header_bg_color', 'opacity' );
		} else {
			$header_bg_opacity = 1;
		}

		$header_bg_color_rgb = fusion_hex2rgb( Avada()->settings->get( 'menu_h45_bg_color' ) );

		if ( Avada()->settings->get( 'menu_h45_bg_color' ) ) {

			$css['global']['.fusion-secondary-main-menu']['background-color'] = Avada_Sanitize::color( Avada()->settings->get( 'menu_h45_bg_color' ), Avada()->settings->get_default( 'menu_h45_bg_color' ) );

			if ( ! is_archive() || ( function_exists( 'is_shop' ) && is_shop() ) ) {
				$css['global']['.fusion-secondary-main-menu']['background-color'] = Avada_Sanitize::color( 'rgba(' . $header_bg_color_rgb[0] . ',' . $header_bg_color_rgb[1] . ',' . $header_bg_color_rgb[2] . ',' . $header_bg_opacity . ')' );
			}

		}

	}

	$elements = array( '.fusion-header', '#side-header' );

	if ( get_post_meta( $c_pageID, 'pyre_header_bg', true ) ) {

		$css['global'][avada_implode( $elements )]['background-image'] = 'url("' . Avada_Sanitize::css_asset_url( get_post_meta( $c_pageID, 'pyre_header_bg', true ) ) . '")';

		if ( in_array( get_post_meta( $c_pageID, 'pyre_header_bg_repeat', true ), array( 'repeat-y', 'no-repeat' ) ) ) {
			$css['global'][avada_implode( $elements )]['background-position'] = 'center center';
		}

		$css['global'][avada_implode( $elements )]['background-repeat'] = get_post_meta( $c_pageID, 'pyre_header_bg_repeat', true );

		if ( 'yes' == get_post_meta( $c_pageID, 'pyre_header_bg_full', true ) ) {

			if ( 'Top' == Avada()->settings->get( 'header_position' ) ) {
				$css['global'][avada_implode( $elements )]['background-attachment'] = 'fixed';
			}
			$css['global'][avada_implode( $elements )]['background-position'] = 'center center';
			$css['global'][avada_implode( $elements )]['background-size'] = 'cover';

		}

		if ( Avada()->settings->get( 'header_bg_parallax' ) && 'Top' == Avada()->settings->get( 'header_position' ) ) {
			$css['global'][avada_implode( $elements )]['background-attachment'] = 'fixed';
			$css['global'][avada_implode( $elements )]['background-position']   = 'top center';
		}

	}

	if ( ( ( 1 > Avada()->settings->get( 'header_bg_color', 'opacity' ) && ! get_post_meta( $c_pageID, 'pyre_header_bg_opacity', true ) ) || ( '' != get_post_meta( $c_pageID, 'pyre_header_bg_opacity', true ) && 1 > get_post_meta( $c_pageID, 'pyre_header_bg_opacity', true ) ) ) && ! is_search() && ! is_404() && ! is_author() && ( ! is_archive() || ( class_exists( 'WooCommerce') && is_shop() ) ) ) {

		$media_query = '@media only screen and (min-width: 800px)';
		$elements = array(
			'.fusion-header',
			'.fusion-secondary-header'
		);
		$css['global'][avada_implode( $elements )]['border-top'] = 'none';

		$elements = array(
			'.fusion-header-v1 .fusion-header',
			'.fusion-secondary-main-menu'
		);
		$css['global'][avada_implode( $elements )]['border'] = 'none';

		if ( 'boxed' == fusion_get_option( 'layout', 'page_bg_layout', $c_pageID ) ) {

			$css['global']['.fusion-header-wrapper']['position'] = 'absolute';
			$css['global']['.fusion-header-wrapper']['z-index']  = '10000';

			if ( $site_width_percent ) {
				$css['global']['.fusion-header-wrapper']['width']    = $site_width_with_units;
			} else {
				$css['global']['.fusion-header-wrapper']['width']    = ( $site_width + 60 ) . 'px';
			}

		} else {

			$css['global']['.fusion-header-wrapper']['position'] = 'absolute';
			$css['global']['.fusion-header-wrapper']['left']     = '0';
			$css['global']['.fusion-header-wrapper']['right']    = '0';
			$css['global']['.fusion-header-wrapper']['z-index']  = '10000';

		}

	}

	/**
	 * If the header opacity is < 1, then do not display the header background image.
	 */
	if ( '' != get_post_meta( $c_pageID, 'pyre_header_bg_opacity', true ) ) {
		$header_bg_opacity = get_post_meta( $c_pageID, 'pyre_header_bg_opacity', true );
	} elseif ( Avada()->settings->get( 'header_bg_color' ) ) {
		$header_bg_opacity = Avada()->settings->get( 'header_bg_color', 'opacity' );
	} else {
		$header_bg_opacity = 1;
	}

	if ( 1 > $header_bg_opacity ) {
		$elements = array(
			'.fusion-header',
			'#side-header',
		);
		$css['global'][avada_implode( $elements )]['background-image'] = '';
	}

	if ( 'no' == get_post_meta( $c_pageID, 'pyre_avada_rev_styles', true ) || ( ! Avada()->settings->get( 'avada_rev_styles' ) && 'yes' != get_post_meta( $c_pageID, 'pyre_avada_rev_styles', true ) ) ) {

		$css['global']['.rev_slider_wrapper']['position'] = 'relative';

		if ( class_exists( 'RevSliderFront' ) ) {

			if ( ( '1' == Avada()->settings->get( 'header_bg_color', 'opacity' ) && ! get_post_meta( $c_pageID, 'pyre_header_bg_opacity', true ) ) || ( get_post_meta( $c_pageID, 'pyre_header_bg_opacity', true ) && 1 == get_post_meta( $c_pageID, 'pyre_header_bg_opacity', true ) ) ) {

				$css['global']['.rev_slider_wrapper .shadow-left']['position']            = 'absolute';
				$css['global']['.rev_slider_wrapper .shadow-left']['pointer-events']      = 'none';
				$css['global']['.rev_slider_wrapper .shadow-left']['background-image']    = 'url("' . Avada_Sanitize::css_asset_url( get_template_directory_uri() . '/assets/images/shadow-top.png' ) . '")';
				$css['global']['.rev_slider_wrapper .shadow-left']['background-repeat']   = 'no-repeat';
				$css['global']['.rev_slider_wrapper .shadow-left']['background-position'] = 'top center';
				$css['global']['.rev_slider_wrapper .shadow-left']['height']              = '42px';
				$css['global']['.rev_slider_wrapper .shadow-left']['width']               = '100%';
				$css['global']['.rev_slider_wrapper .shadow-left']['top']                 = '0';
				$css['global']['.rev_slider_wrapper .shadow-left']['z-index']             = '99';

				$css['global']['.rev_slider_wrapper .shadow-left']['top'] = '-1px';

			}

			$css['global']['.rev_slider_wrapper .shadow-right']['position']            = 'absolute';
			$css['global']['.rev_slider_wrapper .shadow-right']['pointer-events']      = 'none';
			$css['global']['.rev_slider_wrapper .shadow-right']['background-image']    = 'url("' . Avada_Sanitize::css_asset_url( get_template_directory_uri() . '/assets/images/shadow-bottom.png' ) . '")';
			$css['global']['.rev_slider_wrapper .shadow-right']['background-repeat']   = 'no-repeat';
			$css['global']['.rev_slider_wrapper .shadow-right']['background-position'] = 'bottom center';
			$css['global']['.rev_slider_wrapper .shadow-right']['height']              = '32px';
			$css['global']['.rev_slider_wrapper .shadow-right']['width']               = '100%';
			$css['global']['.rev_slider_wrapper .shadow-right']['bottom']              = '0';
			$css['global']['.rev_slider_wrapper .shadow-right']['z-index']             = '99';

		}

		$css['global']['.avada-skin-rev']['border-top']    = '1px solid #d2d3d4';
		$css['global']['.avada-skin-rev']['border-bottom'] = '1px solid #d2d3d4';
		$css['global']['.avada-skin-rev']['box-sizing']    = 'content-box';

		$css['global']['.tparrows']['border-radius'] = '0';

		if ( class_exists( 'RevSliderFront' ) ) {

			$elements = array(
				'.rev_slider_wrapper .tp-leftarrow',
				'.rev_slider_wrapper .tp-rightarrow'
			);
			$css['global'][avada_implode( $elements )]['opacity']          = '0.8';
			$css['global'][avada_implode( $elements )]['position']         = 'absolute';
			$css['global'][avada_implode( $elements )]['top']              = '50% !important';
			$css['global'][avada_implode( $elements )]['margin-top']       = '-31px !important';
			$css['global'][avada_implode( $elements )]['width']            = '63px !important';
			$css['global'][avada_implode( $elements )]['height']           = '63px !important';
			$css['global'][avada_implode( $elements )]['background']       = 'none';
			$css['global'][avada_implode( $elements )]['background-color'] = 'rgba(0, 0, 0, 0.5)';
			$css['global'][avada_implode( $elements )]['color']            = '#fff';


			$css['global']['.rev_slider_wrapper .tp-leftarrow:before']['content']                = '"\e61e"';
			$css['global']['.rev_slider_wrapper .tp-leftarrow:before']['-webkit-font-smoothing'] = 'antialiased';

			$css['global']['.rev_slider_wrapper .tp-rightarrow:before']['content']                = '"\e620"';
			$css['global']['.rev_slider_wrapper .tp-rightarrow:before']['-webkit-font-smoothing'] = 'antialiased';

			$elements = array(
				'.rev_slider_wrapper .tp-leftarrow:before',
				'.rev_slider_wrapper .tp-rightarrow:before'
			);
			$css['global'][avada_implode( $elements )]['position']    = 'absolute';
			$css['global'][avada_implode( $elements )]['padding']     = '0';
			$css['global'][avada_implode( $elements )]['width']       = '100%';
			$css['global'][avada_implode( $elements )]['line-height'] = '63px';
			$css['global'][avada_implode( $elements )]['text-align']  = 'center';
			$css['global'][avada_implode( $elements )]['font-size']   = '25px';
			$css['global'][avada_implode( $elements )]['font-family'] = "'icomoon'";

			$css['global']['.rev_slider_wrapper .tp-leftarrow:before']['margin-left']  = '-2px';

			$css['global']['.rev_slider_wrapper .tp-rightarrow:before']['margin-left'] = '-1px';

			$css['global']['.rev_slider_wrapper .tp-rightarrow']['left']  = 'auto';
			$css['global']['.rev_slider_wrapper .tp-rightarrow']['right'] = '0';

			$elements = array(
				'.no-rgba .rev_slider_wrapper .tp-leftarrow',
				'.no-rgba .rev_slider_wrapper .tp-rightarrow'
			);
			$css['global'][avada_implode( $elements )]['background-color'] = '#ccc';

			$elements = array(
				'.rev_slider_wrapper:hover .tp-leftarrow',
				'.rev_slider_wrapper:hover .tp-rightarrow'
			);
			$css['global'][avada_implode( $elements )]['display'] = 'block';
			$css['global'][avada_implode( $elements )]['opacity'] = '0.8';

			$elements = array(
				'.rev_slider_wrapper .tp-leftarrow:hover',
				'.rev_slider_wrapper .tp-rightarrow:hover'
			);
			$css['global'][avada_implode( $elements )]['opacity'] = '1';

			$css['global']['.rev_slider_wrapper .tp-leftarrow']['background-position'] = '19px 19px';
			$css['global']['.rev_slider_wrapper .tp-leftarrow']['left']                = '0';
			$css['global']['.rev_slider_wrapper .tp-leftarrow']['margin-left']         = '0';
			$css['global']['.rev_slider_wrapper .tp-leftarrow']['z-index']             = '100';

			$css['global']['.rev_slider_wrapper .tp-rightarrow']['background-position'] = '29px 19px';
			$css['global']['.rev_slider_wrapper .tp-rightarrow']['right']               = '0';
			$css['global']['.rev_slider_wrapper .tp-rightarrow']['margin-left']         = '0';
			$css['global']['.rev_slider_wrapper .tp-rightarrow']['z-index']             = '100';

			$elements = array(
				'.rev_slider_wrapper .tp-leftarrow.hidearrows',
				'.rev_slider_wrapper .tp-rightarrow.hidearrows'
			);
			$css['global'][avada_implode( $elements )]['opacity'] = '0';

		}

		$css['global']['.tp-bullets .bullet.last']['clear'] = 'none';

	}

	if ( Avada()->settings->get( 'content_bg_image' ) && ! get_post_meta( $c_pageID, 'pyre_wide_page_bg_color', true ) ) {

		$css['global']['#main']['background-image']  = 'url("' . Avada_Sanitize::css_asset_url( Avada()->settings->get( 'content_bg_image' ) ) . '")';
		$css['global']['#main']['background-repeat'] = esc_attr( Avada()->settings->get( 'content_bg_repeat' ) );

		if ( Avada()->settings->get( 'content_bg_full' ) ) {

			$css['global']['#main']['background-attachment'] = 'fixed';
			$css['global']['#main']['background-position']   = 'center center';
			$css['global']['#main']['background-size']       = 'cover';

		}

	}

	if ( ( Avada()->settings->get( 'main_top_padding' ) || Avada()->settings->get( 'main_top_padding' ) == '0' ) && ! get_post_meta( $c_pageID, 'pyre_main_top_padding', true ) && get_post_meta( $c_pageID, 'pyre_main_top_padding', true ) != '0') {
		$css['global']['#main']['padding-top'] = Avada_Sanitize::size( Avada()->settings->get( 'main_top_padding' ) );
	}

	if ( ( Avada()->settings->get( 'main_bottom_padding' ) || Avada()->settings->get( 'main_bottom_padding' ) == '0' ) && ! get_post_meta( $c_pageID, 'pyre_main_bottom_padding', true ) &&  get_post_meta( $c_pageID, 'pyre_main_bottom_padding', true ) != '0' ) {
		$css['global']['#main']['padding-bottom'] = Avada_Sanitize::size( Avada()->settings->get( 'main_bottom_padding' ) );
	}

	if ( 'wide' == get_post_meta( $c_pageID, 'pyre_page_bg_layout', true ) && get_post_meta( $c_pageID, 'pyre_wide_page_bg_color', true ) ) {
		$elements = array( 'html', 'body', '#wrapper' );
		$css['global'][avada_implode( $elements )]['background-color'] = get_post_meta( $c_pageID, 'pyre_wide_page_bg_color', true );
	}

	if ( get_post_meta( $c_pageID, 'pyre_wide_page_bg_color', true ) ) {
		$elements = array(
			'#main',
			'#wrapper',
			'.fusion-separator .icon-wrapper',
		);
		if ( class_exists( 'bbPress' ) ) {
			$elements[] = '.bbp-arrow';
		}
		$css['global'][avada_implode( $elements )]['background-color'] = get_post_meta( $c_pageID, 'pyre_wide_page_bg_color', true );
	}

	if ( get_post_meta( $c_pageID, 'pyre_wide_page_bg', true ) ) {

		$css['global']['#main']['background-image']  = 'url("' . Avada_Sanitize::css_asset_url( get_post_meta( $c_pageID, 'pyre_wide_page_bg', true ) ) . '")';
		$css['global']['#main']['background-repeat'] = get_post_meta( $c_pageID, 'pyre_wide_page_bg_repeat', true );

		if ( 'yes' == get_post_meta( $c_pageID, 'pyre_wide_page_bg_full', true ) ) {

			$css['global']['#main']['background-attachment'] = 'fixed';
			$css['global']['#main']['background-position']   = 'center center';
			$css['global']['#main']['background-size']       = 'cover';

		}

	}

	if ( get_post_meta( $c_pageID, 'pyre_main_top_padding', true ) ) {
		$css['global']['#main']['padding-top'] = get_post_meta( $c_pageID, 'pyre_main_top_padding', true );
	}

	if ( get_post_meta( $c_pageID, 'pyre_main_bottom_padding', true ) ) {
		$css['global']['#main']['padding-bottom'] = get_post_meta( $c_pageID, 'pyre_main_bottom_padding', true );
	}

	if ( get_post_meta( $c_pageID, 'pyre_sidebar_bg_color', true ) ) {
		$css['global']['#main .sidebar']['background-color'] = get_post_meta( $c_pageID, 'pyre_sidebar_bg_color', true );
	}

	if ( Avada()->settings->get( 'page_title_bg_full' ) ) {
		$css['global']['.fusion-page-title-bar']['background-size'] = 'cover';
	}

	if ( 'yes' == get_post_meta( $c_pageID, 'pyre_page_title_bar_bg_full', true ) ) {
		$css['global']['.fusion-page-title-bar']['background-size'] = 'cover';
	} elseif ( 'no' == get_post_meta( $c_pageID, 'pyre_page_title_bar_bg_full', true ) ) {
		$css['global']['.fusion-page-title-bar']['background-size'] = 'auto';
	}

	if ( Avada()->settings->get( 'page_title_bg_parallax' ) ) {
		$css['global']['.fusion-page-title-bar']['background-attachment'] = 'fixed';
		$css['global']['.fusion-page-title-bar']['background-position']   = 'top center';
	}

	if ( 'yes' == get_post_meta( $c_pageID, 'pyre_page_title_bg_parallax', true ) ) {
		$css['global']['.fusion-page-title-bar']['background-attachment'] = 'fixed';
		$css['global']['.fusion-page-title-bar']['background-position']   = 'top center';
	} elseif ( 'no' == get_post_meta( $c_pageID, 'pyre_page_title_bg_parallax', true ) ) {
		$css['global']['.fusion-page-title-bar']['background-attachment'] = 'scroll';
	}

	if ( get_post_meta( $c_pageID, 'pyre_page_title_height', true ) ) {
		$css['global']['.fusion-page-title-bar']['height'] = Avada_Sanitize::size( get_post_meta( $c_pageID, 'pyre_page_title_height', true ) );
	} elseif ( Avada()->settings->get( 'page_title_height' ) ) {
		$css['global']['.fusion-page-title-bar']['height'] = Avada_Sanitize::size( Avada()->settings->get( 'page_title_height' ) );
	}


	if ( is_single() && get_post_meta( $c_pageID, 'pyre_fimg_width', true ) ) {

		if ( 'auto' != get_post_meta( $c_pageID, 'pyre_fimg_width', true ) ) {
			$css['global']['#post-' . $c_pageID . ' .fusion-post-slideshow']['max-width'] = get_post_meta( $c_pageID, 'pyre_fimg_width', true );
		} else {
			$css['global']['.fusion-post-slideshow .flex-control-nav']['position']   = 'relative';
			$css['global']['.fusion-post-slideshow .flex-control-nav']['text-align'] = 'center';
			$css['global']['.fusion-post-slideshow .flex-control-nav']['margin-top'] = '10px';
		}

		$css['global']['#post-' . $c_pageID . ' .fusion-post-slideshow img']['max-width'] = Avada_Sanitize::size( get_post_meta( $c_pageID, 'pyre_fimg_width', true ) );

		if ( 'auto' == get_post_meta( $c_pageID, 'pyre_fimg_width', true ) ) {
			$css['global']['#post-' . $c_pageID . ' .fusion-post-slideshow img']['width'] = Avada_Sanitize::size( get_post_meta( $c_pageID, 'pyre_fimg_width', true ) );
		}

	}

	if ( is_single() && get_post_meta( $c_pageID, 'pyre_fimg_height', true ) ) {
		$elements = array(
			'#post-' . $c_pageID . ' .fusion-post-slideshow',
			'#post-' . $c_pageID . ' .fusion-post-slideshow img'
		);
		$css['global'][avada_implode( $elements )]['max-height'] = get_post_meta( $c_pageID, 'pyre_fimg_height', true );
		$css['global']['#post-' . $c_pageID . ' .fusion-post-slideshow .slides']['max-height'] = '100%';
	}

	if ( get_post_meta( $c_pageID, 'pyre_page_title_bar_bg_retina', true ) ) {

		$media_query = '@media only screen and (-webkit-min-device-pixel-ratio: 1.5), only screen and (min-resolution: 144dpi), only screen and (min-resolution: 1.5dppx)';
		$css[$media_query]['.fusion-page-title-bar']['background-image'] = 'url("' . Avada_Sanitize::css_asset_url( get_post_meta( $c_pageID, 'pyre_page_title_bar_bg_retina', true ) ) . '")';
		$css[$media_query]['.fusion-page-title-bar']['background-size']  = 'cover';

	} elseif ( Avada()->settings->get( 'page_title_bg_retina' ) ) {

		$media_query = '@media only screen and (-webkit-min-device-pixel-ratio: 1.5), only screen and (min-resolution: 144dpi), only screen and (min-resolution: 1.5dppx)';
		$css[$media_query]['.fusion-page-title-bar']['background-image'] = 'url("' . Avada_Sanitize::css_asset_url( Avada()->settings->get( 'page_title_bg_retina' ) ) . '")';
		$css[$media_query]['.fusion-page-title-bar']['background-size']  = 'cover';

	}

	if ( ( 'content_only' == Avada()->settings->get( 'page_title_bar' ) && ( 'default' == get_post_meta( $c_pageID, 'pyre_page_title', true ) || ! get_post_meta( $c_pageID, 'pyre_page_title', true ) ) ) || 'yes_without_bar' == get_post_meta( $c_pageID, 'pyre_page_title', true ) ) {
		$css['global']['.fusion-page-title-bar']['background'] = 'none';
		$css['global']['.fusion-page-title-bar']['border']     = 'none';
	}

	$elements = array(
		'.width-100 .nonhundred-percent-fullwidth',
		'.width-100 .fusion-section-separator'
	);
	if ( get_post_meta( $c_pageID, 'pyre_hundredp_padding', true ) ) {
		$css['global'][avada_implode( $elements )]['margin-left']  = '-' . Avada_Sanitize::size( get_post_meta( $c_pageID, 'pyre_hundredp_padding', true ) );
		$css['global'][avada_implode( $elements )]['margin-right'] = '-' . Avada_Sanitize::size( get_post_meta( $c_pageID, 'pyre_hundredp_padding', true ) );
	} elseif ( Avada()->settings->get( 'hundredp_padding' ) ) {
		$css['global'][avada_implode( $elements )]['margin-left']  = '-' . Avada_Sanitize::size( Avada()->settings->get( 'hundredp_padding' ) );
		$css['global'][avada_implode( $elements )]['margin-right'] = '-' . Avada_Sanitize::size( Avada()->settings->get( 'hundredp_padding' ) );
	}

	if ( (float) $wp_version < 3.8) {
		$css['global']['#wpadminbar *']['color'] = '#ccc';
		$elements = array(
			'#wpadminbar .hover a',
			'#wpadminbar .hover a span'
		);
		$css['global'][avada_implode( $elements )]['color'] = '#464646';
	}

	if ( class_exists( 'WooCommerce' ) ) {

		$css['global']['.woocommerce-invalid:after']['content']    = __( 'Please enter correct details for this required field.', 'Avada' );
		$css['global']['.woocommerce-invalid:after']['display']    = 'inline-block';
		$css['global']['.woocommerce-invalid:after']['margin-top'] = '7px';
		$css['global']['.woocommerce-invalid:after']['color']      = 'red';

	}

	if ( get_post_meta( $c_pageID, 'pyre_fallback', true ) ) {

		$media_query = '@media only screen and (max-width: 940px)';
		$css[$media_query]['#sliders-container']['display'] = 'none';
		$css[$media_query]['#fallback-slide']['display'] = 'block';

		$media_query = '@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) and (orientation: portrait)';
		$css[$media_query]['#sliders-container']['display'] = 'none';
		$css[$media_query]['#fallback-slide']['display'] = 'block';

	}

	if ( Avada()->settings->get( 'side_header_width' ) && 'no' != get_post_meta( get_queried_object_id(), 'pyre_display_header', true ) ) {

		$side_header_width = Avada_Sanitize::size( Avada()->settings->get( 'side_header_width' ) );
		$side_header_width = (int) str_replace( 'px', '', $side_header_width );

		$elements = array(
			'body.side-header-left #wrapper',
			'.side-header-left .fusion-footer-parallax'
		);
		$css['global'][avada_implode( $elements )]['margin-left'] = Avada_Sanitize::size( Avada()->settings->get( 'side_header_width' ) );

		$elements = array(
			'body.side-header-right #wrapper',
			'.side-header-right .fusion-footer-parallax'
		);
		$css['global'][avada_implode( $elements )]['margin-right'] = Avada_Sanitize::size( Avada()->settings->get( 'side_header_width' ) );

		$elements = array(
			'body.side-header-left #side-header #nav > ul > li > ul',
			'body.side-header-left #side-header #nav .login-box',
			'body.side-header-left #side-header #nav .main-nav-search-form'
		);
		if ( class_exists( 'WooCommerce' ) ) {
			$elements[] = 'body.side-header-left #side-header #nav .cart-contents';
		}
		$css['global'][avada_implode( $elements )]['left'] = ( $side_header_width - 1 ) . 'px';

		if ( is_rtl() ) {
			$css['global']['body.rtl #boxed-wrapper']['position'] = 'relative';

			$css['global']['body.rtl.layout-boxed-mode.side-header-left #side-header']['position']    = 'absolute';
			$css['global']['body.rtl.layout-boxed-mode.side-header-left #side-header']['left']        = '0';
			$css['global']['body.rtl.layout-boxed-mode.side-header-left #side-header']['top']         = '0';
			$css['global']['body.rtl.layout-boxed-mode.side-header-left #side-header']['margin-left'] = '0px';

			$css['global']['body.rtl.side-header-left #side-header .side-header-wrapper']['position'] = 'fixed';
			$css['global']['body.rtl.side-header-left #side-header .side-header-wrapper']['width']    = Avada_Sanitize::size( Avada()->settings->get( 'side_header_width' ) );
		}

		if ( 'Boxed' != Avada()->settings->get( 'layout' ) && 'boxed' != get_post_meta( $c_pageID, 'pyre_page_bg_layout', true ) ) {

			$elements = array(
				'body.side-header-left #slidingbar .avada-row',
				'body.side-header-right #slidingbar .avada-row'
			);
			$css['global'][avada_implode( $elements )]['max-width'] = 'none';

		}

	}

	if ( ( ( 'Boxed' == Avada()->settings->get( 'layout' ) && 'wide' != get_post_meta( $c_pageID, 'pyre_page_bg_layout', true ) ) || 'boxed' == get_post_meta( $c_pageID, 'pyre_page_bg_layout', true ) ) && 'Top' != Avada()->settings->get( 'header_position' ) ) {

		$css['global']['#boxed-wrapper']['min-height'] = '100vh';

		if ( ! $site_width_percent ) {

			$elements = array(
				'#boxed-wrapper',
				'.fusion-body .fusion-footer-parallax'
			);
			$css['global'][avada_implode( $elements )]['margin']    = '0 auto';
			$css['global'][avada_implode( $elements )]['max-width'] = Avada_Sanitize::size( ( $site_width_without_units + Avada()->settings->get( 'side_header_width' ) + 60 ) . 'px' );

			$css['global']['#slidingbar-area .fusion-row']['max-width'] = intval( $site_width_without_units + Avada()->settings->get( 'side_header_width' ) ) . 'px';

		} else {

			$elements = array(
				'#boxed-wrapper',
				'#slidingbar-area .fusion-row',
				'.fusion-footer-parallax'
			);
			$css['global'][avada_implode( $elements )]['margin']      = '0 auto';
			$css['global'][avada_implode( $elements )]['max-width'][] = 'calc(' . $site_width_with_units . ' + ' . Avada_Sanitize::size( Avada()->settings->get( 'side_header_width' ) ) . ')';

			$css['global']['#wrapper']['max-width'] = 'none';

		}

		if ( 'Left' == Avada()->settings->get( 'header_position' ) ) {

			$css['global']['body.side-header-left #side-header']['left']        = 'auto';
			$css['global']['body.side-header-left #side-header']['margin-left'] = '-' . Avada_Sanitize::size( Avada()->settings->get( 'side_header_width' ) );

			$css['global']['.side-header-left .fusion-footer-parallax']['margin'] = '0 auto';
			$css['global']['.side-header-left .fusion-footer-parallax']['padding-left'] = Avada_Sanitize::size( Avada()->settings->get( 'side_header_width' ) );

		} else {

			$css['global']['#boxed-wrapper']['position'] = 'relative';

			$css['global']['body.admin-bar #wrapper #slidingbar-area']['top'] = '0';

			$css['global']['.side-header-right .fusion-footer-parallax']['margin'] = '0 auto';
			$css['global']['.side-header-right .fusion-footer-parallax']['padding-right'] = Avada_Sanitize::size( Avada()->settings->get( 'side_header_width' ) );

			$media_query = '@media only screen and (min-width: 800px)';
			$css[$media_query]['body.side-header-right #side-header']['position'] = 'absolute';
			$css[$media_query]['body.side-header-right #side-header']['top']      = '0';

			$css[$media_query]['body.side-header-right #side-header .side-header-wrapper']['position'] = 'fixed';
			$css[$media_query]['body.side-header-right #side-header .side-header-wrapper']['width']    = Avada_Sanitize::size( Avada()->settings->get( 'side_header_width' ) );

		}

	}

	if ( is_page_template( 'contact.php' ) && Avada()->settings->get( 'gmap_address' ) && ! Avada()->settings->get( 'status_gmap' ) ) {

		$css['global']['.avada-google-map']['width']  = Avada_Sanitize::size( Avada()->settings->get( 'gmap_width' ) );
		$css['global']['.avada-google-map']['margin'] = '0 auto';

		if ( '100%' != Avada()->settings->get( 'gmap_width' ) ) {

			$margin_top = ( Avada()->settings->get( 'gmap_topmargin' ) ) ? Avada_Sanitize::size( Avada()->settings->get( 'gmap_topmargin' ) ) : '55px';
			$css['global']['.avada-google-map']['margin-top'] = Avada_Sanitize::size( $margin_top );

		}

		$gmap_height = ( Avada()->settings->get( 'gmap_height' ) ) ? Avada()->settings->get( 'gmap_height' ) : '415px';
		$css['global']['.avada-google-map']['height'] = $gmap_height;

	} elseif ( is_page_template( 'contact-2.php' ) && Avada()->settings->get( 'gmap_address' ) && ! Avada()->settings->get( 'status_gmap' ) ) {

		$css['global']['.avada-google-map']['margin']     = '0 auto';
		$css['global']['.avada-google-map']['margin-top'] = '55px';
		$css['global']['.avada-google-map']['height']     = '415px !important';
		$css['global']['.avada-google-map']['width']      = '940px !important';

	}

	if ( 'yes' == get_post_meta( $c_pageID, 'pyre_footer_100_width', true ) ) {

		$elements = array(
			'.layout-wide-mode .fusion-footer-widget-area > .fusion-row',
			'.layout-wide-mode .fusion-footer-copyright-area > .fusion-row'
		);
		$css['global'][avada_implode( $elements )]['max-width'] = '100% !important';

	} elseif ( 'no' == get_post_meta( $c_pageID, 'pyre_footer_100_width', true ) ) {

		$elements = array(
			'.layout-wide-mode .fusion-footer-widget-area > .fusion-row',
			'.layout-wide-mode .fusion-footer-copyright-area > .fusion-row'
		);
		$css['global'][avada_implode( $elements )]['max-width'] = $site_width_with_units . ' !important';

	}

	if ( get_post_meta( $c_pageID, 'pyre_page_title_font_color', true ) && '' != get_post_meta( $c_pageID, 'pyre_page_title_font_color', true ) ) {

		$elements = array(
			'.fusion-page-title-bar h1',
			'.fusion-page-title-bar h3'
		);
		$css['global'][avada_implode( $elements )]['color'] = Avada_Sanitize::color( get_post_meta( $c_pageID, 'pyre_page_title_font_color', true ) );

	}

	if ( get_post_meta( $c_pageID, 'pyre_page_title_text_size', true ) && '' != get_post_meta( $c_pageID, 'pyre_page_title_text_size', true ) ) {

		$css['global']['.fusion-page-title-bar h1']['font-size']   = Avada_Sanitize::size( get_post_meta( $c_pageID, 'pyre_page_title_text_size', true ) );
		$css['global']['.fusion-page-title-bar h1']['line-height'] = 'normal';

	}

	if ( get_post_meta( $c_pageID, 'pyre_page_title_custom_subheader_text_size', true ) && '' != get_post_meta( $c_pageID, 'pyre_page_title_custom_subheader_text_size', true) ) {

		$css['global']['.fusion-page-title-bar h3']['font-size']   = Avada_Sanitize::size( get_post_meta( $c_pageID, 'pyre_page_title_custom_subheader_text_size', true ) );
		if ( Avada()->settings->get( 'page_title_subheader_font_size' ) ) {
			$css['global']['.fusion-page-title-bar h3']['line-height'] = ( intval( Avada()->settings->get( 'page_title_subheader_font_size' ) + 12 ) ) . 'px';
		}

	}

	if ( Avada()->settings->get( 'responsive' ) && ! Avada()->settings->get( 'ipad_potrait' ) && get_post_meta( $c_pageID, 'pyre_page_title_height', true ) ) {

		$media_query = '@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) and (orientation: portrait)';
		$css[$media_query]['#wrapper .fusion-page-title-bar']['height'] = Avada_Sanitize::size( get_post_meta( $c_pageID, 'pyre_page_title_height', true ) ) . ' !important';

	}

	if ( 'yes' == get_post_meta( $c_pageID, 'pyre_page_title_100_width', true ) ) {
		$css['global']['.layout-wide-mode .fusion-page-title-row']['max-width'] = '100%';
	}

	$header_width = Avada_Sanitize::size( Avada()->settings->get( 'header_100_width' ) );

	if ( 'yes' == get_post_meta( $c_pageID, 'pyre_header_100_width', true ) ) {
		$header_width = true;
	} elseif ( 'no' == get_post_meta( $c_pageID, 'pyre_header_100_width', true ) ) {
		$header_width = false;
	}

	if ( $header_width ) {
		$css['global']['.layout-wide-mode .fusion-header-wrapper .fusion-row']['max-width'] = '100%';
	}

	$media_query = '@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) and (orientation: portrait)';
	$css[$media_query]['.products .product-list-view']['width']     = '100% !important';
	$css[$media_query]['.products .product-list-view']['min-width'] = '100% !important';

	$button_text_color_brightness       = Avada_Sanitize::color( fusion_calc_color_brightness( Avada()->settings->get( 'button_accent_color' ) ) );
	$button_hover_text_color_brightness = Avada_Sanitize::color( fusion_calc_color_brightness( Avada()->settings->get( 'button_accent_hover_color' ) ) );

	$text_shadow_color = ( 140 < $button_hover_text_color_brightness ) ? '#333' : '#fff';

	if ( get_post_meta( $c_pageID, 'pyre_page_title_mobile_height', true ) ) {

		$media_query = '@media only screen and (max-width: 800px)';

		if ( 'auto' != get_post_meta( $c_pageID, 'pyre_page_title_mobile_height', true ) ) {

			$css[$media_query]['.fusion-body .fusion-page-title-bar']['height'] = Avada_Sanitize::size( get_post_meta( $c_pageID, 'pyre_page_title_mobile_height', true ) );

			$css[$media_query]['.fusion-page-title-row']['display'] = 'table';

			$css[$media_query]['.fusion-page-title-wrapper']['display']        = 'table-cell';
			$css[$media_query]['.fusion-page-title-wrapper']['vertical-align'] = 'middle';

		} else {

			$css[$media_query]['.fusion-body .fusion-page-title-bar']['padding-top']    = '10px';
			$css[$media_query]['.fusion-body .fusion-page-title-bar']['padding-bottom'] = '10px';
			$css[$media_query]['.fusion-body .fusion-page-title-bar']['height']         = 'auto';

		}

	}

	if ( Avada()->settings->get( 'responsive' ) ) {
		$media_query = '@media only screen and (max-width: 800px)';
		$css[$media_query]['.fusion-contact-info']['padding']     = '1em 30px';
		$css[$media_query]['.fusion-contact-info']['line-height'] = '1.5em';
	}

	if ( ! Avada()->settings->get( 'responsive' ) ) {
		$css['global']['body']['min-width']  = $site_width_with_units;

		if( ! $site_width_percent ) {
			$css['global']['html']['overflow-x'] = 'scroll';
			$css['global']['body']['overflow-x'] = 'scroll';
		}
	}


	// Animations
	$css['@-webkit-keyframes avadaSonarEffect']['0%']['opacity']             = '0.3';
	$css['@-webkit-keyframes avadaSonarEffect']['40%']['opacity']            = '0.5';
	$css['@-webkit-keyframes avadaSonarEffect']['40%']['box-shadow']         = '0 0 0 2px rgba(255,255,255,0.1), 0 0 10px 10px ' . Avada_Sanitize::color( Avada()->settings->get( 'primary_color' ), Avada()->settings->get_default( 'primary_color' ) ) . ', 0 0 0 10px rgba(255,255,255,0.5)';
	$css['@-webkit-keyframes avadaSonarEffect']['100%']['box-shadow']        = '0 0 0 2px rgba(255,255,255,0.1), 0 0 10px 10px '  . Avada_Sanitize::color( Avada()->settings->get( 'primary_color' ), Avada()->settings->get_default( 'primary_color' ) ) . ', 0 0 0 10px rgba(255,255,255,0.5)';
	$css['@-webkit-keyframes avadaSonarEffect']['100%']['-webkit-transform'] = 'scale(1.5)';
	$css['@-webkit-keyframes avadaSonarEffect']['100%']['opacity']           = '0';

	$css['@-moz-keyframes avadaSonarEffect']['0%']['opacity']          = '0.3';
	$css['@-moz-keyframes avadaSonarEffect']['40%']['opacity']         = '0.5';
	$css['@-moz-keyframes avadaSonarEffect']['40%']['box-shadow']      = '0 0 0 2px rgba(255,255,255,0.1), 0 0 10px 10px ' . Avada_Sanitize::color( Avada()->settings->get( 'primary_color' ), Avada()->settings->get_default( 'primary_color' ) ) . ', 0 0 0 10px rgba(255,255,255,0.5)';
	$css['@-moz-keyframes avadaSonarEffect']['100%']['box-shadow']     = '0 0 0 2px rgba(255,255,255,0.1), 0 0 10px 10px ' . Avada_Sanitize::color( Avada()->settings->get( 'primary_color' ), Avada()->settings->get_default( 'primary_color' ) ) . ', 0 0 0 10px rgba(255,255,255,0.5)';
	$css['@-moz-keyframes avadaSonarEffect']['100%']['-moz-transform'] = 'scale(1.5)';
	$css['@-moz-keyframes avadaSonarEffect']['100%']['opacity']        = '0';

	$css['@keyframes avadaSonarEffect']['0%']['opacity']      = '0.3';
	$css['@keyframes avadaSonarEffect']['40%']['opacity']     = '0.5';
	$css['@keyframes avadaSonarEffect']['40%']['box-shadow']  = '0 0 0 2px rgba(255,255,255,0.1), 0 0 10px 10px ' . Avada_Sanitize::color( Avada()->settings->get( 'primary_color' ), Avada()->settings->get_default( 'primary_color' ) ) . ', 0 0 0 10px rgba(255,255,255,0.5)';
	$css['@keyframes avadaSonarEffect']['100%']['box-shadow'] = '0 0 0 2px rgba(255,255,255,0.1), 0 0 10px 10px ' . Avada_Sanitize::color( Avada()->settings->get( 'primary_color' ), Avada()->settings->get_default( 'primary_color' ) ) . ', 0 0 0 10px rgba(255,255,255,0.5)';
	$css['@keyframes avadaSonarEffect']['100%']['transform']  = 'scale(1.5)';
	$css['@keyframes avadaSonarEffect']['100%']['opacity']    = '0';

	return apply_filters( 'avada_dynamic_css_array', $css );

}

/**
 * Helper function.
 * Merge and combine the CSS elements
 */
function avada_implode( $elements = array() ) {

	// Make sure our values are unique
	$elements = array_unique( $elements );
	// Sort elements alphabetically.
	// This way all duplicate items will be merged in the final CSS array.
	sort( $elements );

	// Implode items and return the value.
	return implode( ',', $elements );

}

/**
 * Maps elements from dynamic css to the selector
 */
function avada_map_selector( $elements, $selector ) {
	$array = array();

	foreach( $elements as $element ) {
		$array[] = $element . $selector;
	}

	return $array;
}

/**
 * Get the array of dynamically-generated CSS and convert it to a string.
 * Parses the array and adds prefixes for browser-support.
 */
function avada_dynamic_css_parser( $css ) {
	/**
	 * Prefixes
	 */
	foreach ( $css as $media_query => $elements ) {
		foreach ( $elements as $element => $style_array ) {
			foreach ( $style_array as $property => $value ) {
				// border-radius
				if ( 'border-radius' == $property ) {
					$css[$media_query][$element]['-webkit-border-radius'] = $value;
				}
				// box-shadow
				if ( 'box-shadow' == $property ) {
					$css[$media_query][$element]['-webkit-box-shadow'] = $value;
					$css[$media_query][$element]['-moz-box-shadow']    = $value;
				}
				// box-sizing
				elseif ( 'box-sizing' == $property ) {
					$css[$media_query][$element]['-webkit-box-sizing'] = $value;
					$css[$media_query][$element]['-moz-box-sizing']    = $value;
				}
				// text-shadow
				elseif ( 'text-shadow' == $property ) {
					$css[$media_query][$element]['-webkit-text-shadow'] = $value;
					$css[$media_query][$element]['-moz-text-shadow']    = $value;
				}
				// transform
				elseif ( 'transform' == $property ) {
					$css[$media_query][$element]['-webkit-transform'] = $value;
					$css[$media_query][$element]['-moz-transform']    = $value;
					$css[$media_query][$element]['-ms-transform']     = $value;
					$css[$media_query][$element]['-o-transform']      = $value;
				}
				// background-size
				elseif ( 'background-size' == $property ) {
					$css[$media_query][$element]['-webkit-background-size'] = $value;
					$css[$media_query][$element]['-moz-background-size']    = $value;
					$css[$media_query][$element]['-ms-background-size']     = $value;
					$css[$media_query][$element]['-o-background-size']      = $value;
				}
				// transition
				elseif ( 'transition' == $property ) {
					$css[$media_query][$element]['-webkit-transition'] = $value;
					$css[$media_query][$element]['-moz-transition']    = $value;
					$css[$media_query][$element]['-ms-transition']     = $value;
					$css[$media_query][$element]['-o-transition']      = $value;
				}
				// transition-property
				elseif ( 'transition-property' == $property ) {
					$css[$media_query][$element]['-webkit-transition-property'] = $value;
					$css[$media_query][$element]['-moz-transition-property']    = $value;
					$css[$media_query][$element]['-ms-transition-property']     = $value;
					$css[$media_query][$element]['-o-transition-property']      = $value;
				}
				// linear-gradient
				elseif ( is_array( $value ) ) {
					foreach ( $value as $subvalue ) {
						if ( false !== strpos( $subvalue, 'linear-gradient' ) ) {
							$css[$media_query][$element][$property][] = '-webkit-' . $subvalue;
							$css[$media_query][$element][$property][] = '-moz-' . $subvalue;
							$css[$media_query][$element][$property][] = '-ms-' . $subvalue;
							$css[$media_query][$element][$property][] = '-o-' . $subvalue;
						}
						// calc
						elseif ( 0 === stripos( $subvalue, 'calc' ) ) {
							$css[$media_query][$element][$property][] = '-webkit-' . $subvalue;
							$css[$media_query][$element][$property][] = '-moz-' . $subvalue;
							$css[$media_query][$element][$property][] = '-ms-' . $subvalue;
							$css[$media_query][$element][$property][] = '-o-' . $subvalue;
						}
					}
				}
			}
		}
	}

	/**
	 * Process the array of CSS properties and produce the final CSS
	 */
	$final_css = '';
	foreach ( $css as $media_query => $styles ) {

		/**
		 * Do not include any media queries if we're not in responsive mode.
		 */
		if ( false !== strpos( $media_query, '@media' ) && ! Avada()->settings->get( 'responsive' ) ) {
			continue;
		}

		$final_css .= ( 'global' != $media_query ) ? $media_query . '{' : '';

		foreach ( $styles as $style => $style_array ) {
			$final_css .= $style . '{';
				foreach ( $style_array as $property => $value ) {
					if ( is_array( $value ) ) {
						foreach ( $value as $sub_value ) {
							$final_css .= $property . ':' . $sub_value . ';';
						}
					} else {
						$final_css .= $property . ':' . $value . ';';
					}
				}
			$final_css .= '}';
		}

		$final_css .= ( 'global' != $media_query ) ? '}' : '';

	}

	return apply_filters( 'avada_dynamic_css', $final_css );

}

/**
 * Returns the dynamic CSS.
 * If possible, it also caches the CSS using WordPress transients
 *
 * @return  string  the dynamically-generated CSS.
 */
function avada_dynamic_css_cached() {
	/**
	 * Get the page ID
	 */
	$c_pageID = Avada()->dynamic_css->page_id();

	/**
	 * do we have WP_DEBUG set to true?
	 * If not then we can try caching it.
	 * If yes then we won't cache anything.
	 */
	if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {

		/**
		 * Build the transient name
		 */
		$transient_name = ( $c_pageID ) ? 'avada_dynamic_css_' . $c_pageID : 'avada_dynamic_css_global';

		/**
		 * Check if the dynamic CSS needs updating
		 * If it does, then calculate the CSS and then update the transient.
		 */
		if ( Avada()->dynamic_css->needs_update() ) {
			/**
			 * Calculate the dynamic CSS
			 */
			$dynamic_css = avada_dynamic_css_parser( avada_dynamic_css_array() );
			/**
			 * Append the user-entered dynamic CSS
			 */
			$dynamic_css .= Avada()->settings->get( 'custom_css' );
			/**
			 * Set the transient for an hour
			 */
			set_transient( $transient_name, $dynamic_css, 60 * 60 );
		} else {
			/**
			 * Check if the transient exists.
			 * If it does not exist, then generate the CSS and update the transient.
			 */
			if ( false === ( $dynamic_css = get_transient( $transient_name ) ) ) {
				/**
				 * Calculate the dynamic CSS
				 */
				$dynamic_css = avada_dynamic_css_parser( avada_dynamic_css_array() );
				/**
				 * Append the user-entered dynamic CSS
				 */
				$dynamic_css .= Avada()->settings->get( 'custom_css' );
				/**
				 * Set the transient for an hour
				 */
				set_transient( $transient_name, $dynamic_css, 60 * 60 );
			}
		}

	} else {
		/**
		 * Calculate the dynamic CSS
		 */
		$dynamic_css = avada_dynamic_css_parser( avada_dynamic_css_array() );
		/**
		 * Append the user-entered dynamic CSS
		 */
		$dynamic_css .= Avada()->settings->get( 'custom_css' );
	}

	return $dynamic_css;

}

// Omit closing PHP tag to avoid "Headers already sent" issues.
