<?php

class Avada_Sanitize {

	/**
	 * Sanitize values like for example 10px, 30% etc.
	 */
	public static function size( $value ) {

		// Return empty if there are no numbers in the value.
		// Prevents some CSS errors.
		if ( ! preg_match( '#[0-9]#' , $value ) ) {
			return;
		}

		// Trim the value
		$value = trim( $value );
		// The array of valid units
		$units = array( 'rem', 'em', 'ex', '%', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ch', 'vh', 'vw', 'vmin', 'vmax' );
		foreach ( $units as $unit ) {
			// The raw value without the units
			$raw_value = filter_var( $value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
			// Find what unit we're using
			if ( false !== strpos( $value, $unit ) ) {
				$unit_used = $unit;
			}
		}
		$unit_used = ( isset( $unit_used ) ) ? $unit_used : '';

		return $raw_value . $unit_used;

	}


	/**
	 * Sanitises a HEX value.
	 * (part of the Kirki Toolkit)
	 * The way this works is by splitting the string in 6 substrings.
	 * Each sub-string is individually sanitized, and the result is then returned.
	 *
	 * @var     string      The hex value of a color
	 * @param   boolean     Whether we want to include a hash (#) at the beginning or not
	 * @return  string      The sanitized hex color.
	 */
	 public static function hex( $color, $default = false ) {

	 	if( ! $color ) {
	 		$color = $default;
	 	}

		// Remove any spaces and special characters before and after the string
		$color = trim( $color );
		// Remove any trailing '#' symbols from the color value
		$color = str_replace( '#', '', $color );
		// If the string is 6 characters long then use it in pairs.
		if ( 3 == strlen( $color ) ) {
			$color = substr( $color, 0, 1 ) . substr( $color, 0, 1 ) . substr( $color, 1, 1 ) . substr( $color, 1, 1 ) . substr( $color, 2, 1 ) . substr( $color, 2, 1 );
		}
		$substr = array();
		for ( $i = 0; $i <= 5; $i++ ) {
			$default    = ( 0 == $i ) ? 'F' : ( $substr[$i-1] );
			$substr[$i] = substr( $color, $i, 1 );
			$substr[$i] = ( false === $substr[$i] || ! ctype_xdigit( $substr[$i] ) ) ? $default : $substr[$i];
		}
		$hex = implode( '', $substr );
		return '#' . $hex;

	}

	/**
	 * Sanitizes an rgba color value
	 * (part of the Kirki Toolkit)
	 */
	public static function rgba( $value ) {
		// If empty or an array return transparent
		if ( empty( $value ) || is_array( $value ) ) {
			return 'rgba(0,0,0,0)';
		}
		// If string does not start with 'rgba', then treat as hex
		// sanitize the hex color and finally convert hex to rgba
		if ( false === strpos( $value, 'rgba' ) ) {
			return self::get_rgba( self::hex( $value ) );
		}
		// By now we know the string is formatted as an rgba color so we need to further sanitize it.
		$value  = str_replace( ' ', '', $value );
		sscanf( $value, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );
		return 'rgba(' . $red . ',' . $green . ',' . $blue . ',' . $alpha . ')';
	}

	/**
	 * Sanitize colors.
	 * (part of the Kirki Toolkit)
	 * Determine if the current value is a hex or an rgba color and call the appropriate method.
	 *
	 * @since 0.8.5
	 * @return string
	 */
	public static function color( $value ) {

		if ( 'transparent' == $value ) {
			return 'transparent';
		}

		// Is this an rgba color or a hex?
		$mode = ( false === strpos( $value, 'rgba' ) ) ? 'rgba' : 'hex';

		if ( 'rgba' == $mode ) {
			return self::hex( $value );
		} else {
			return self::rgba( $value );
		}

	}

	/**
	 * Gets the rgb value of the $hex color.
	 * (part of the Kirki Toolkit)
	 *
	 * @var     string      The hex value of a color
	 * @param   int         Opacity level (1-100)
	 * @return  string
	 */
	public static function get_rgba( $hex = '#fff', $opacity = 100 ) {
		$hex = self::hex( $hex, false );
		// Make sure that opacity is properly formatted :
		// Set the opacity to 100 if a larger value has been entered by mistake.
		// If a negative value is used, then set to 0.
		// If an opacity value is entered in a decimal form (for example 0.25), then multiply by 100.
		if ( $opacity >= 100 ) {
			$opacity = 100;
		} elseif ( $opacity < 0 ) {
			$opacity = 0;
		} elseif ( $opacity < 1 && $opacity != 0 ) {
			$opacity = ( $opacity * 100 );
		} else {
			$opacity = $opacity;
		}
		// Divide the opacity by 100 to end-up with a CSS value for the opacity
		$opacity = ( $opacity / 100 );
		$color = 'rgba(' . self::get_rgb( $hex, true ) . ', ' . $opacity . ')';
		return $color;
	}

	/**
	 * Gets the rgb value of the $hex color.
	 * (part of the Kirki Toolkit)
	 *
	 * @var     string      The hex value of a color
	 * @param   boolean     Whether we want to implode the values or not
	 * @return  mixed       array|string
	 */
	public static function get_rgb( $hex, $implode = false ) {
		// Remove any trailing '#' symbols from the color value
		$hex = self::hex( $hex, false );
		$red    = hexdec( substr( $hex, 0, 2 ) );
		$green  = hexdec( substr( $hex, 2, 2 ) );
		$blue   = hexdec( substr( $hex, 4, 2 ) );
		// rgb is an array
		$rgb = array( $red, $green, $blue );
		return ( $implode ) ? implode( ',', $rgb ) : $rgb;
	}

	/**
	 * Properly escape some characters in image URLs so that they may be properly used in CSS.
	 * From W3C:
	 * > Some characters appearing in an unquoted URI,
	 * > such as parentheses, white space characters, single quotes (') and double quotes ("),
	 * > must be escaped with a backslash so that the resulting URI value is a URI token: '\(', '\)'.
	 */
	public static function css_asset_url( $url ) {

		$url = esc_url_raw( $url );

		$url = str_replace( '(', '\(', $url );
		$url = str_replace( ')', '\)', $url );
		$url = str_replace( '"', '\"', $url );
		$url = str_replace( ' ', '\ ', $url );
		$url = str_replace( "'", "\'", $url );

		return $url;

	}

}
