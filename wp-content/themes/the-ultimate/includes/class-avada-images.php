<?php

class Avada_Images {

    public function __construct() {
    	global $smof_data;
    	
        if ( ! $smof_data['status_lightbox'] ) {
        	add_filter( 'wp_get_attachment_link', array( $this, 'pretty_links' ) );
        }
        
        add_filter( 'jpeg_quality', array( $this, 'image_full_quality' ) );
        add_filter( 'wp_editor_set_quality', array( $this, 'image_full_quality' ) );
    }

    /**
     * Adds rel="prettyPhoto" to links
     */
    public function pretty_links( $content ) {
        $content = preg_replace( "/<a/", "<a rel=\"iLightbox[postimages]\"" ,$content, 1 );
        return $content;
    }

    /**
     * Modify the image quality and set it to 100
     */
    public function image_full_quality( $quality ) {
    	return 100;
    }

}

// Omit closing PHP tag to avoid "Headers already sent" issues.
