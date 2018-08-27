<?php

class Avada_Portfolio {

    /**
     * The class constructor
     */
    public function __construct() {
        add_filter( 'pre_get_posts', array( $this, 'set_post_filters' ) );
    }

    /**
     * Modify the query (using the 'pre_get_posts' filter)
     */
    public function set_post_filters( $query ) {

        if ( $query->is_main_query() && ( is_tax( 'portfolio_category' ) || is_tax( 'portfolio_skills' ) || is_tax( 'portfolio_tags' ) ) ) {
            $query->set( 'posts_per_page', Avada()->settings->get( 'portfolio_items' ) );
        }

        return $query;

    }

}

// Omit closing PHP tag to avoid "Headers already sent" issues.
