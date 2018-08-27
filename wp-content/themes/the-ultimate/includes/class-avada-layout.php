<?php

// class Avada_Layout {

// 	public function __construct() {

// 		add_filter( 'body_class', array( $this, 'body_class' ) );
// 		add_filter( 'wrapper_class', array( $this, 'wrapper_class' ) );

// 	}

// 	/**
// 	 * alter the body classes
// 	 */
// 	public function body_class( $classes ) {

// 		$classes[] = 'fusion-body';

// 		if ( is_page_template( 'blank.php' ) ) {
// 			$classes[] = 'body_blank';
// 		}

// 		if ( ! Avada()->settings->get( 'header_sticky_tablet' ) ) {
// 			$classes[] = 'no-tablet-sticky-header';
// 		}

// 		if ( ! Avada()->settings->get( 'header_sticky_mobile' ) ) {
// 			$classes[] = 'no-mobile-sticky-header';
// 		}

// 		if ( Avada()->settings->get( 'mobile_slidingbar_widgets' ) ) {
// 			$classes[] = 'no-mobile-slidingbar';
// 		}

// 		if ( Avada()->settings->get( 'status_totop' ) ) {
// 			$classes[] = 'no-totop';
// 		}

// 		if ( ! Avada()->settings->get( 'status_totop_mobile' ) ) {
// 			$classes[] = 'no-mobile-totop';
// 		}

// 		if ( 'horizontal' == Avada()->settings->get( 'woocommerce_product_tab_design' ) && is_singular( 'product' ) ) {
// 			$classes[] = 'woo-tabs-horizontal';
// 		}

// 		if ( 'modern' == Avada()->settings->get( 'mobile_menu_design' ) ) {
// 			$mobile_logo_pos = strtolower( Avada()->settings->get( 'logo_alignment' ) );
// 			$mobile_logo_pos = ( 'center' == strtolower( Avada()->settings->get( 'logo_alignment' ) ) ) ? 'left' : $mobile_logo_pos;
// 			$classes[] = 'mobile-logo-pos-' . $mobile_logo_pos;
// 		}

// 		$classes[] = ( ( 'Boxed' == Avada()->settings->get( 'layout' ) && 'default' == get_post_meta( Avada::c_pageID(), 'pyre_page_bg_layout', true ) ) || 'boxed' == get_post_meta( Avada::c_pageID(), 'pyre_page_bg_layout', true ) ) ? 'layout-boxed-mode' : 'layout-wide-mode';

// 		if ( $this->has_sidebar() ) {
// 			$classes[] = 'has-sidebar';
// 		}

// 		if ( $this->has_double_sidebars() ) {
// 			$classes[] = 'double-sidebars';
// 		}

// 		if ( 'no' != get_post_meta( Avada::c_pageID(), 'pyre_display_header', true ) ) {

// 			if ( 'Left' == Avada()->settings->get( 'header_position' ) || 'Right' == Avada()->settings->get( 'header_position' ) ) {
// 				$classes[] = 'side-header';
// 			}

// 			if ( 'Left' == Avada()->settings->get( 'header_position' ) ) {
// 				$classes[] = 'side-header-left';
// 			}

// 			if ( 'Right' == Avada()->settings->get( 'header_position' ) ) {
// 				$classes[] = 'side-header-right';
// 			}

// 		}

// 		$body_classes[] = 'mobile-menu-design-' . Avada()->settings->get( 'mobile_menu_design' );

// 		return $classes;

// 	}

// 	/**
// 	 * Get the sidebar
// 	 */
// 	public function get_sidebar( $id = 'sidebar_1' ) {

// 		$sidebar_1 = get_post_meta( Avada::c_pageID(), 'sbg_selected_sidebar_replacement', true );
// 		$sidebar_2 = get_post_meta( Avada::c_pageID(), 'sbg_selected_sidebar_2_replacement', true );

// 		if ( is_single() && ! is_singular( 'avada_portfolio' ) && ! is_singular( 'product' ) && ! is_bbpress()  && ! is_buddypress() ) {

// 			if ( Avada()->settings->get( 'posts_global_sidebar' ) ) {
// 				$sidebar_1 = ( 'None' != Avada()->settings->get( 'posts_sidebar' ) ) ? array( Avada()->settings->get( 'posts_sidebar' ) ) : '';
// 				$sidebar_2 = ( 'None' != Avada()->settings->get( 'posts_sidebar_2' ) ) ? array( Avada()->settings->get( 'posts_sidebar_2' ) ) : '';
// 			}

// 			if ( class_exists( 'Tribe__Events__Main' ) && tribe_is_event( Avada::c_pageID() ) && Avada()->settings->get( 'pages_global_sidebar' ) ) {
// 				$sidebar_1 = ( 'None' != Avada()->settings->get( 'pages_sidebar' ) ) ? array( Avada()->settings->get( 'pages_sidebar' ) ) : '';
// 				$sidebar_2 = ( 'None' != Avada()->settings->get( 'pages_sidebar_2' ) ) ? array( Avada()->settings->get( 'pages_sidebar_2' ) ) : '';
// 			}

// 		} elseif ( is_singular( 'avada_portfolio' ) ) {

// 			if ( Avada()->settings->get( 'portfolio_global_sidebar' ) ) {
// 				$sidebar_1 = ( 'None' != Avada()->settings->get( 'portfolio_sidebar' ) ) ? array( Avada()->settings->get( 'portfolio_sidebar' ) ) : '';
// 				$sidebar_2 = ( 'None' != Avada()->settings->get( 'portfolio_sidebar_2' ) ) ? array( Avada()->settings->get( 'portfolio_sidebar_2' ) ) : '';
// 			}

// 		} elseif ( is_singular( 'product' ) || ( class_exists('WooCommerce') && is_shop() ) ) {

// 			if ( Avada()->settings->get( 'woo_global_sidebar' ) ) {
// 				$sidebar_1 = ( 'None' != Avada()->settings->get( 'woo_sidebar' ) ) ? array( Avada()->settings->get( 'woo_sidebar' ) ) : '';
// 				$sidebar_2 = ( 'None' != Avada()->settings->get( 'woo_sidebar_2' ) ) ? array( Avada()->settings->get( 'woo_sidebar_2' ) ) : '';
// 			}

// 		} elseif ( ( is_page() || is_page_template() ) && ( ! is_page_template( '100-width.php' ) && ! is_page_template( 'blank.php' ) ) ) {

// 			if ( Avada()->settings->get( 'pages_global_sidebar' ) ) {
// 				$sidebar_1 = ( 'None' != Avada()->settings->get( 'pages_sidebar' ) ) ? array( Avada()->settings->get( 'pages_sidebar' ) ) : '';
// 				$sidebar_2 = ( 'None' != Avada()->settings->get( 'pages_sidebar_2' ) ) ? array( Avada()->settings->get( 'pages_sidebar_2' ) ) : '';
// 			}

// 		}

// 		if ( is_home() ) {
// 			$sidebar_1 = Avada()->settings->get( 'blog_archive_sidebar' );
// 			$sidebar_2 = Avada()->settings->get( 'blog_archive_sidebar_2' );
// 		}

// 		if ( is_archive() && ( ! is_buddypress() && ! is_bbpress() && ( class_exists( 'WooCommerce' ) && ! is_shop() ) || ! class_exists( 'WooCommerce' ) ) && ! is_tax( 'portfolio_category' ) && ! is_tax( 'portfolio_skills' )  && ! is_tax( 'portfolio_tags' ) && ! is_tax( 'product_cat' ) && ! is_tax( 'product_tag' ) ) {
// 			$sidebar_1 = Avada()->settings->get( 'blog_archive_sidebar' );
// 			$sidebar_2 = Avada()->settings->get( 'blog_archive_sidebar_2' );
// 		}

// 		if ( is_tax( 'portfolio_category' ) || is_tax( 'portfolio_skills' )  || is_tax( 'portfolio_tags' ) ) {
// 			$sidebar_1 = Avada()->settings->get( 'portfolio_archive_sidebar' );
// 			$sidebar_2 = Avada()->settings->get( 'portfolio_archive_sidebar_2' );
// 		}

// 		if ( is_tax( 'product_cat' ) || is_tax( 'product_tag' ) ) {
// 			$sidebar_1 = Avada()->settings->get( 'woocommerce_archive_sidebar' );
// 			$sidebar_2 = Avada()->settings->get( 'woocommerce_archive_sidebar_2' );
// 		}

// 		if ( is_search() ) {
// 			$sidebar_1 = Avada()->settings->get( 'search_sidebar' );
// 			$sidebar_2 = Avada()->settings->get( 'search_sidebar_2' );
// 		}

// 		if ( ( is_bbpress() || is_buddypress() ) && ! bbp_is_forum_archive() && ! bbp_is_topic_archive() && ! bbp_is_user_home() && ! bbp_is_search() ) {
// 			$sidebar_1 = Avada()->settings->get( 'ppbress_sidebar' );
// 			$sidebar_2 = Avada()->settings->get( 'ppbress_sidebar_2' );

// 			if ( Avada()->settings->get( 'bbpress_global_sidebar' ) ) {
// 				$sidebar_1 = Avada()->settings->get( 'ppbress_sidebar' );
// 				$sidebar_2 = Avada()->settings->get( 'ppbress_sidebar_2' );
// 			} else {
// 				$sidebar_1 = get_post_meta( Avada::c_pageID(), 'sbg_selected_sidebar_replacement', true );
// 				$sidebar_2 = get_post_meta( Avada::c_pageID(), 'sbg_selected_sidebar_2_replacement', true );
// 			}
// 		}

// 		if ( ( is_bbpress() || is_buddypress() ) && ( bbp_is_forum_archive() || bbp_is_topic_archive() || bbp_is_user_home() || bbp_is_search() ) ) {
// 			$sidebar_1 = Avada()->settings->get( 'ppbress_sidebar' );
// 			$sidebar_2 = Avada()->settings->get( 'ppbress_sidebar_2' );
// 		}

// 		if ( class_exists( 'Tribe__Events__Main' ) && is_events_archive() ) {
// 			$sidebar_1 = ( 'None' != Avada()->settings->get( 'pages_sidebar' ) ) ? array( Avada()->settings->get( 'pages_sidebar' ) ) : '';
// 			$sidebar_2 = ( 'None' != Avada()->settings->get( 'pages_sidebar_2' ) ) ? array( Avada()->settings->get( 'pages_sidebar_2' ) ) : '';
// 		}

// 		if ( 'sidebar_1' == $id ) {
// 			return $sidebar_1;
// 		} elseif ( 'sidebar_2' == $id ) {
// 			return $sidebar_2;
// 		}

// 	}

// 	/**
// 	 * The wrapper classes
// 	 */
// 	public function wrapper_class() {

// 		$classes = array();

// 		if ( is_page_template( 'blank.php' ) ) {
// 			$classes[]  = 'wrapper_blank';
// 		}

// 		$classes = implode( ' ', $classes );

// 		return $classes;

// 	}

// 	/**
// 	 * Determine if our template has a sidebar or not.
// 	 */
// 	public function has_sidebar( $context = 'auto' ) {

// 		global $post;

// 		$has_sidebar = false;

// 		$sidebar_1 = $this->get_sidebar( 'sidebar_1' );
// 		$sidebar_2 = $this->get_sidebar( 'sidebar_2' );

// 		$object_id      = get_queried_object_id();
// 		$slider_page_id = '';
// 		$slider_page_id = ( ! is_search() && ! is_home() && ! is_front_page() && ! is_archive() && isset( $object_id ) ) ? $object_id : $slider_page_id;
// 		$slider_page_id = ( ! is_home() && is_front_page() && isset( $object_id ) ) ? $object_id : $slider_page_id;
// 		$slider_page_id = ( is_home() && ! is_front_page() ) ? get_option( 'page_for_posts' ) : $slider_page_id;
// 		$slider_page_id = ( class_exists( 'WooCommerce' ) && is_shop() ) ? get_option( 'woocommerce_shop_page_id' ) : $slider_page_id;

// 		$page_template = '';
// 		if ( is_woocommerce() ) {
// 			$custom_fields = get_post_custom_values( '_wp_page_template', Avada::c_pageID() );
// 			$page_template = ( is_array( $custom_fields ) && ! empty( $custom_fields ) ) ? $custom_fields[0] : '';
// 		}

// 		if ( 'bbpress' == $context ) {

// 			$has_sidebar = ( is_array( $sidebar_1 ) && ( $sidebar_1[0] || '0' === $sidebar_1[0] ) ) ? true : false;

// 			if ( Avada()->settings->get( 'bbpress_global_sidebar' ) ) {

// 				$sidebar_1 = Avada()->settings->get( 'ppbress_sidebar' );
// 				$has_sidebar = ( 'None' != $sidebar_1 ) ? true : false;

// 			}

// 		} elseif ( 'contact' == $context ) {

// 			if ( Avada()->settings->get( 'pages_global_sidebar' ) ) {

// 				$sidebar_1 = ( 'None' != Avada()->settings->get( 'pages_sidebar' ) ) ? array( Avada()->settings->get( 'pages_sidebar' ) ) : '';
// 				$sidebar_2 = ( 'None' != Avada()->settings->get( 'pages_sidebar_2' ) ) ? array( Avada()->settings->get( 'pages_sidebar_2' ) ) : '';

// 			}

// 			$has_sidebar = ( is_array( $sidebar_1 ) && ( $sidebar_1[0] || '0' == $sidebar_1[0] ) ) ? true : false;

// 		} else {

// 			if ( is_post_type_archive( 'avada_portfolio' ) ) {
// 				$has_sidebar = ( 'None' != Avada()->settings->get( 'portfolio_archive_sidebar' ) ) ? true : false;
// 			} elseif ( is_post_type_archive( 'wpfc_sermon' ) ) {
// 				$has_sidebar = ( 'None' != Avada()->settings->get( 'blog_archive_sidebar' ) ) ? true : false;
// 			} else {
// 				$has_sidebar = ( 'None' != Avada()->settings->get( 'blog_archive_sidebar' ) ) ? true : false;
// 			}

// 		}

// 		if ( is_array( $sidebar_1 ) && ! empty( $sidebar_1 ) && ( $sidebar_1[0] || '0' == $sidebar_1[0] ) && ! is_buddypress() && ! is_bbpress() && ! is_page_template( '100-width.php' ) && ( ! class_exists( 'WooCommerce' ) || ( class_exists( 'WooCommerce' ) && ! is_cart() && ! is_checkout() && ! is_account_page() && ! ( get_option( 'woocommerce_thanks_page_id' ) && is_page( get_option( 'woocommerce_thanks_page_id' ) ) ) ) ) ) {
// 			$has_sidebar = true;
// 		}

// 		if ( is_home() && 'None' != $sidebar_1 ) {
// 			$has_sidebar = true;
// 		}

// 		if ( is_archive() && ( ! is_buddypress() && ! is_bbpress() && ( class_exists( 'WooCommerce' ) && ! is_shop() ) || ! class_exists( 'WooCommerce' ) ) && ! is_tax( 'portfolio_category' ) && ! is_tax( 'portfolio_skills' )  && ! is_tax( 'portfolio_tags' ) && ! is_tax( 'product_cat' ) && ! is_tax( 'product_tag' ) ) {
// 			if ( 'None' != $sidebar_1 ) {
// 				$has_sidebar = true;
// 			}
// 		}

// 		if ( is_tax( 'portfolio_category' ) || is_tax( 'portfolio_skills' )  || is_tax( 'portfolio_tags' ) ) {
// 			if ( 'None' != $sidebar_1 ) {
// 				$has_sidebar = true;
// 			}
// 		}

// 		if ( is_tax( 'product_cat' ) || is_tax( 'product_tag' ) ) {
// 			if ( 'None' != $sidebar_1 ) {
// 				$has_sidebar = true;
// 			}
// 		}

// 		if ( is_search() ) {
// 			if ( 'None' != $sidebar_1 ) {
// 				$has_sidebar = true;
// 			}
// 		}

// 		if ( ( is_bbpress() || is_buddypress() ) && ! bbp_is_forum_archive() && ! bbp_is_topic_archive() && ! bbp_is_user_home() && ! bbp_is_search() ) {
// 			if ( Avada()->settings->get( 'bbpress_global_sidebar' ) ) {
// 				if ( 'None' != $sidebar_1 ) {
// 					$has_sidebar = true;
// 				}
// 			} else {
// 				if ( is_array( $sidebar_1 ) && $sidebar_1[0] ) {
// 					$has_sidebar = true;
// 				}
// 			}
// 		}

// 		if ( ( is_bbpress() || is_buddypress() ) && ( bbp_is_forum_archive() || bbp_is_topic_archive() || bbp_is_user_home() || bbp_is_search() ) ) {
// 			if ( 'None' != $sidebar_1 ) {
// 				$has_sidebar = true;
// 			}
// 		}

// 		if ( 'left' == get_post_meta( $post->ID, 'pyre_sidebar_position', true ) ) {
// 			$has_sidebar = true;
// 		} elseif ( 'right' == get_post_meta( $post->ID, 'pyre_sidebar_position', true ) ) {
// 			$has_sidebar = true;
// 		} elseif ( 'default' == get_post_meta( $post->ID, 'pyre_sidebar_position', true ) || ! metadata_exists( 'post', $post->ID, 'pyre_sidebar_position' ) ) {
// 			if ( 'Left' == Avada()->settings->get( 'default_sidebar_pos' ) ) {
// 				$has_sidebar = true;
// 			} elseif ( 'Right' == Avada()->settings->get( 'default_sidebar_pos' ) ) {
// 				$has_sidebar = true;
// 			}
// 		}

// 		if ( class_exists( 'Tribe__Events__Main' ) && is_events_archive() ) {
// 			if ( is_array( $sidebar_1 ) && $sidebar_1[0] && ! is_bbpress() && ! is_page_template( '100-width.php' ) && ( ! class_exists( 'WooCommerce' ) || ( class_exists( 'WooCommerce' ) && ! is_cart() && ! is_checkout() && ! is_account_page() && ! ( get_option( 'woocommerce_thanks_page_id' ) && is_page( get_option( 'woocommerce_thanks_page_id' ) ) ) ) ) ) {
// 				$has_sidebar = true;
// 			}
// 		}

// 		if ( is_page_template( '100-width.php' ) || is_page_template( 'blank.php' ) || 'yes' == get_post_meta( $slider_page_id, 'pyre_portfolio_width_100', true ) || ( avada_is_portfolio_template() && 'yes' == get_post_meta( Avada::c_pageID(), 'pyre_portfolio_width_100', true ) ) || '100-width.php' == $page_template ) {
// 			$has_sidebar = false;
// 		}

// 		return $has_sidebar;

// 	}

// 	/**
// 	 * Determine if we have 2 sidebars simultaneously displayed.
// 	 */
// 	public function has_double_sidebars( $context = 'auto' ) {

// 		global $post;

// 		$has_double_sidebars = false;

// 		$sidebar_1 = $this->get_sidebar( 'sidebar_1' );
// 		$sidebar_2 = $this->get_sidebar( 'sidebar_2' );

// 		if ( 'bbpress' == $context ) {

// 			if ( ( is_array( $sidebar_1 ) && ( $sidebar_1[0] || '0' === $sidebar_1[0] ) ) && ( is_array( $sidebar_2 ) && ( $sidebar_2[0] || '0' === $sidebar_2[0] ) ) ) {
// 				$has_double_sidebars = true;
// 			}

// 			if ( Avada()->settings->get( 'bbpress_global_sidebar' ) ) {

// 				$sidebar_1 = Avada()->settings->get( 'ppbress_sidebar' );
// 				$sidebar_2 = Avada()->settings->get( 'ppbress_sidebar_2' );

// 				$has_double_sidebars = ( 'None' != $sidebar_1 && 'None' != $sidebar_2 ) ? true : false;

// 			}

// 		} elseif ( 'contact' == $context ) {

// 			if ( Avada()->settings->get( 'pages_global_sidebar' ) ) {

// 				$sidebar_1 = ( 'None' != Avada()->settings->get( 'pages_sidebar' ) ) ? array( Avada()->settings->get( 'pages_sidebar' ) ) : '';
// 				$sidebar_2 = ( 'None' != Avada()->settings->get( 'pages_sidebar_2' ) ) ? array( Avada()->settings->get( 'pages_sidebar_2' ) ) : '';

// 				if ( 'None' != Avada()->settings->get( 'pages_sidebar' ) && 'None' != Avada()->settings->get( 'pages_sidebar_2' ) ) {
// 					$double_sidebars = true;
// 				}

// 			}

// 			if ( ( is_array( $sidebar_1 ) && ( $sidebar_1[0] || '0' === $sidebar_1[0] ) ) && ( is_array( $sidebar_2 ) && ( $sidebar_2[0] || '0' === $sidebar_2[0] ) ) ) {
// 				$has_double_sidebars = true;
// 			}

// 		} else {

// 			// If $this->has_sidebar is true then we have to check for the 2ndary sidebar.
// 			// If $this->has_sidebar is false then we fallback to false.
// 			if ( $this->has_sidebar() ) {

// 				if ( is_post_type_archive( 'avada_portfolio' ) ) {
// 					$has_double_sidebars = ( 'None' != Avada()->settings->get( 'portfolio_archive_sidebar_2' ) ) ? true : false;
// 				} elseif ( is_post_type_archive( 'wpfc_sermon' ) ) {
// 					$has_double_sidebars = ( 'None' != Avada()->settings->get( 'blog_archive_sidebar_2' ) ) ? true : false;
// 				} else { // Fallback
// 					$has_double_sidebars = ( 'None' != Avada()->settings->get( 'blog_archive_sidebar_2' ) ) ? true : false;
// 				}

// 			}

// 		}

// 		if ( is_array( $sidebar_1 ) && $sidebar_1[0] && is_array( $sidebar_2 ) && $sidebar_2[0] && ! is_buddypress() && ! is_bbpress() && ! is_page_template( '100-width.php' ) && ( ! class_exists( 'WooCommerce' ) || ( class_exists( 'WooCommerce' ) && ! is_cart() && ! is_checkout() && ! is_account_page() && ! ( get_option( 'woocommerce_thanks_page_id' ) && is_page( get_option( 'woocommerce_thanks_page_id' ) ) ) ) ) ) {
// 			$has_double_sidebars = true;
// 		}

// 		if ( is_page_template( 'side-navigation.php' ) && is_array( $sidebar_2 ) && $sidebar_2[0] ) {
// 			$has_double_sidebars = true;
// 		}

// 		if ( is_home() && 'None' != $sidebar_1 && 'None' != $sidebar_2 ) {
// 			$has_double_sidebars = true;
// 		}

// 		if ( is_archive() && ( ! is_buddypress() && ! is_bbpress() && ( class_exists( 'WooCommerce' ) && ! is_shop() ) || ! class_exists( 'WooCommerce' ) ) && ! is_tax( 'portfolio_category' ) && ! is_tax( 'portfolio_skills' )  && ! is_tax( 'portfolio_tags' ) && ! is_tax( 'product_cat' ) && ! is_tax( 'product_tag' ) ) {
// 			if ( 'None' != $sidebar_1 && 'None' != $sidebar_2 ) {
// 				$has_double_sidebars = true;
// 			}
// 		}

// 		if ( is_tax( 'portfolio_category' ) || is_tax( 'portfolio_skills' )  || is_tax( 'portfolio_tags' ) ) {
// 			if ( 'None' != $sidebar_1 && 'None' != $sidebar_2 ) {
// 				$has_double_sidebars = true;
// 			}
// 		}

// 		if ( is_tax( 'product_cat' ) || is_tax( 'product_tag' ) ) {
// 			if ( 'None' != $sidebar_1 && 'None' != $sidebar_2 ) {
// 				$has_double_sidebars = true;
// 			}
// 		}

// 		if ( is_search() ) {
// 			if ( 'None' != $sidebar_1 && 'None' != $sidebar_2 ) {
// 				$has_double_sidebars = true;
// 			}
// 		}

// 		if ( ( is_bbpress() || is_buddypress() ) && ! bbp_is_forum_archive() && ! bbp_is_topic_archive() && ! bbp_is_user_home() && ! bbp_is_search() ) {
// 			if ( Avada()->settings->get( 'bbpress_global_sidebar' ) ) {
// 				if ( 'None' != $sidebar_1 && 'None' != $sidebar_2 ) {
// 					$has_double_siebars = true;
// 				}
// 			} else {
// 				if ( is_array( $sidebar_1 ) && $sidebar_1[0] && is_array( $sidebar_2 ) && $sidebar_2[0] ) {
// 					$has_double_sidebars = true;
// 				}
// 			}
// 		}

// 		if ( ( is_bbpress() || is_buddypress() ) && ( bbp_is_forum_archive() || bbp_is_topic_archive() || bbp_is_user_home() || bbp_is_search() ) ) {
// 			if ( 'None' != $sidebar_1 && 'None' != $sidebar_2 ) {
// 				$has_double_sidebars = true;
// 			}
// 		}

// 		if ( class_exists( 'Tribe__Events__Main' ) && is_events_archive() ) {
// 			if ( is_array( $sidebar_1 ) && $sidebar_1[0] && is_array( $sidebar_2 ) && $sidebar_2[0] && ! is_bbpress() && ! is_page_template( '100-width.php' ) && ( ! class_exists( 'WooCommerce' ) || ( class_exists( 'WooCommerce' ) && ! is_cart() && ! is_checkout() && ! is_account_page() && ! ( get_option( 'woocommerce_thanks_page_id' ) && is_page( get_option( 'woocommerce_thanks_page_id' ) ) ) ) ) ) {
// 				$has_double_sidebars = true;
// 			}
// 		}

// 		return $has_double_sidebars;

// 	}

// }


// // Omit closing PHP tag to avoid "Headers already sent" issues.
