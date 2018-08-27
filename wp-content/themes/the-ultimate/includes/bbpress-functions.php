<?php

add_filter( 'bbp_get_forum_pagination_links', 'tf_get_forum_pagination_links', 1 );
if ( ! function_exists( 'tf_get_forum_pagination_links' ) ) {

	function tf_get_forum_pagination_links() {

		$bbp = bbpress();

		$pagination_links = $bbp->topic_query->pagination_links;

		$pagination_links = str_replace( 'page-numbers current', 'current', $pagination_links );
		$pagination_links = str_replace( 'page-numbers', 'inactive', $pagination_links );
		$pagination_links = str_replace( 'prev inactive', 'pagination-prev', $pagination_links );
		$pagination_links = str_replace( 'next inactive', 'pagination-next', $pagination_links );

		$pagination_links = str_replace( '&larr;', __( 'Previous', 'Avada' ) . '<span class="page-prev"></span>', $pagination_links );
		$pagination_links = str_replace( '&rarr;', __( 'Next', 'Avada' ) . '<span class="page-next"></span>', $pagination_links );

		return $pagination_links;

	}

}

add_filter( 'bbp_get_topic_pagination_links', 'tf_get_topic_pagination_links', 1 );
if ( ! function_exists( 'tf_get_topic_pagination_links' ) ) {

	function tf_get_topic_pagination_links() {

		$bbp = bbpress();

		$pagination_links = $bbp->reply_query->pagination_links;
		$permalink		  = get_permalink( $bbp->current_topic_id );
		$max_num_pages	  = $bbp->reply_query->max_num_pages;
		$paged			  = $bbp->reply_query->paged;

		$pagination_links = str_replace( 'page-numbers current', 'current', $pagination_links );
		$pagination_links = str_replace( 'page-numbers', 'inactive', $pagination_links );
		$pagination_links = str_replace( 'prev inactive', 'pagination-prev', $pagination_links );
		$pagination_links = str_replace( 'next inactive', 'pagination-next', $pagination_links );

		$pagination_links = str_replace( '&larr;', __( 'Previous', 'Avada' ) . '<span class="page-prev"></span>', $pagination_links );
		$pagination_links = str_replace( '&rarr;', __( 'Next', 'Avada' ) . '<span class="page-next"></span>', $pagination_links );

		return $pagination_links;

	}

}

add_filter( 'bbp_get_search_pagination_links', 'tf_get_search_pagination_links', 1 );
if ( ! function_exists( 'tf_get_search_pagination_links' ) ) {

	function tf_get_search_pagination_links() {

		$bbp = bbpress();

		$pagination_links = $bbp->search_query->pagination_links;

		$pagination_links = str_replace( 'page-numbers current', 'current', $pagination_links );
		$pagination_links = str_replace( 'page-numbers', 'inactive', $pagination_links );
		$pagination_links = str_replace( 'prev inactive', 'pagination-prev', $pagination_links );
		$pagination_links = str_replace( 'next inactive', 'pagination-next', $pagination_links );

		$pagination_links = str_replace( '&larr;', __( 'Previous', 'Avada' ) . '<span class="page-prev"></span>', $pagination_links );
		$pagination_links = str_replace( '&rarr;', __( 'Next', 'Avada' ) . '<span class="page-next"></span>', $pagination_links );

		return $pagination_links;

	}

}
