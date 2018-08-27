<?php

class Fusion_Widget_Tweets extends WP_Widget {

	public function __construct() {

		$widget_ops = array( 'classname' => 'tweets', 'description' => '' );
		$control_ops = array( 'id_base' => 'tweets-widget' );

		parent::__construct('tweets-widget', 'Avada: Twitter', $widget_ops, $control_ops);

	}

	public function widget( $args, $instance ) {

		extract( $args );

		$title               = apply_filters( 'widget_title', isset( $instance['title'] ) ? $instance['title'] : '' );
		$consumer_key        = isset( $instance['consumer_key'] ) ? $instance['consumer_key'] : '';
		$consumer_secret     = isset( $instance['consumer_secret'] ) ? $instance['consumer_secret'] : '';
		$access_token        = isset( $instance['access_token'] ) ? $instance['access_token'] : '';
		$access_token_secret = isset( $instance['access_token_secret'] ) ? $instance['access_token_secret'] : '';
		$twitter_id          = isset( $instance['twitter_id'] ) ? $instance['twitter_id'] : '';
		$count               = (int) isset( $instance['count'] ) ? $instance['count'] : 3;
		$widget_id           = $args['widget_id'];

		echo $before_widget;

		if ( $title ) {
			echo $before_title.$title.$after_title;
		}

		if ( $twitter_id && $consumer_key && $consumer_secret && $access_token && $access_token_secret && $count ) {

			$transName = 'list_tweets_' . $widget_id;
			$cacheTime = 10;

			if ( false === ( $twitterData = get_transient( $transName ) ) ) {

				$token = get_option( 'cfTwitterToken_' . $widget_id );
				// get a new token anyways
				delete_option( 'cfTwitterToken_'.$widget_id );

				// getting new auth bearer only if we don't have one
				if( ! $token ) {

					// preparing credentials
					$credentials = $consumer_key . ':' . $consumer_secret;
					$toSend = base64_encode( $credentials );

					// http post arguments
					$args = array(
						'method'      => 'POST',
						'httpversion' => '1.1',
						'blocking'    => true,
						'headers'     => array(
							'Authorization' => 'Basic ' . $toSend,
							'Content-Type'  => 'application/x-www-form-urlencoded;charset=UTF-8'
						),
						'body' => array(
							'grant_type' => 'client_credentials'
						)
					);

					add_filter( 'https_ssl_verify', '__return_false' );
					$response = wp_remote_post( 'https://api.twitter.com/oauth2/token', $args );

					$keys = json_decode( wp_remote_retrieve_body( $response ) );

					if ( $keys ) {
						// saving token to wp_options table
						update_option( 'cfTwitterToken_' . $widget_id, $keys->access_token );
						$token = $keys->access_token;
					}
				}

				// we have bearer token wether we obtained it from API or from options
				$args = array(
					'httpversion' => '1.1',
					'blocking'    => true,
					'headers'     => array(
						'Authorization' => "Bearer $token"
					)
				);

				add_filter( 'https_ssl_verify', '__return_false' );
				$api_url  = 'https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=' . $twitter_id . '&count=' . $count;
				$response = wp_remote_get( $api_url, $args );

				set_transient( $transName, wp_remote_retrieve_body( $response ), 60 * $cacheTime );

			}

			@$twitter = json_decode( get_transient( $transName ) );
			?>

			<?php if ( $twitter && is_array( $twitter ) ) : ?>
				<div class="twitter-box">
					<div class="twitter-holder">
						<div class="b">
							<div class="tweets-container" id="tweets_<?php echo $widget_id; ?>">
								<ul class="jtwt">
									<?php foreach ( $twitter as $tweet ) : ?>
										<li class="jtwt_tweet">
											<p class="jtwt_tweet_text">
												<?php
												$latestTweet = $this->tweet_get_html( $tweet );
												echo $latestTweet;
												?>
											</p>
											<?php
											$twitterTime = strtotime( $tweet->created_at );
											$timeAgo = $this->ago( $twitterTime );
											?>
											<a href="http://twitter.com/<?php echo $tweet->user->screen_name; ?>/statuses/<?php echo $tweet->id_str; ?>" class="jtwt_date"><?php echo $timeAgo; ?></a>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
						</div>
					</div>
					<span class="arrow"></span>
				</div>
			<?php endif;
		}

		echo $after_widget;

	}

	public function tweet_get_html( $tweet, $links = true, $users = true, $hashtags = true ) {

		$return   = $tweet->text;
		$entities = array();

		if ( $links && is_array( $tweet->entities->urls ) ) {

			foreach ( $tweet->entities->urls as $e ) {
				$temp['start']       = $e->indices[0];
				$temp['end']         = $e->indices[1];
				$temp['replacement'] = '<a href="' . $e->expanded_url . '" target="_blank">' . $e->display_url . '</a>';
				$entities[]          = $temp;
			}

		}

		if ( $users && is_array( $tweet->entities->user_mentions ) ) {

			foreach ( $tweet->entities->user_mentions as $e ) {
				$temp['start']       = $e->indices[0];
				$temp['end']         = $e->indices[1];
				$temp['replacement'] = '<a href="https://twitter.com/' . $e->screen_name . '" target="_blank">@' . $e->screen_name . '</a>';
				$entities[]          = $temp;
			}

		}

		if ( $hashtags && is_array( $tweet->entities->hashtags ) ) {

			foreach ( $tweet->entities->hashtags as $e ) {
				$temp['start']       = $e->indices[0];
				$temp['end']         = $e->indices[1];
				$temp['replacement'] = '<a href="https://twitter.com/hashtag/' . $e->text . '?src=hash" target="_blank">#' . $e->text . '</a>';
				$entities[]          = $temp;
			}

		}

		usort( $entities, array( $this, 'sort_tweets' ) );

		foreach ( $entities as $item ) {
			$return = substr_replace( $return, $item['replacement'], $item['start'], $item['end'] - $item['start'] );
		}

		return $return;
	}

	public function ago( $time ) {

		$periods        = array( __( 'second', 'Avada' ), __( 'minute', 'Avada' ), __( 'hour', 'Avada' ), __( 'day', 'Avada' ), __( 'week', 'Avada' ), __( 'month', 'Avada' ), __( 'year', 'Avada' ), __( 'decade', 'Avada' ) );
		$periods_plural = array( __( 'seconds', 'Avada' ), __( 'minutes', 'Avada' ), __( 'hours', 'Avada' ), __( 'days', 'Avada' ), __( 'weeks', 'Avada' ), __( 'months', 'Avada' ), __( 'years', 'Avada' ), __( 'decades', 'Avada' ) );
		$lengths        = array( '60', '60', '24', '7', '4.35', '12', '10' );
		$now = time();
		$difference = $now - $time;
		$tense = __( 'ago', 'Avada' );

		for ( $j = 0; $difference >= $lengths[$j] && $j < count( $lengths ) -1; $j++ ) {
			$difference /= $lengths[$j];
		}

		$difference = round( $difference );

		if ( 1 != $difference ) {
			$periods[$j] = $periods_plural[$j];
		}

		return sprintf( '%s %s %s', $difference, $periods[$j], $tense );

	}

	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title']               = strip_tags( $new_instance['title'] );
		$instance['consumer_key']        = $new_instance['consumer_key'];
		$instance['consumer_secret']     = $new_instance['consumer_secret'];
		$instance['access_token']        = $new_instance['access_token'];
		$instance['access_token_secret'] = $new_instance['access_token_secret'];
		$instance['twitter_id']          = $new_instance['twitter_id'];
		$instance['count']               = $new_instance['count'];

		return $instance;

	}

	public function form( $instance ) {

		$defaults = array(
			'title'               => __( 'Recent Tweets', 'Avada' ),
			'twitter_id'          => '',
			'count'               => 3,
			'consumer_key'        => '',
			'consumer_secret'     => '',
			'access_token'        => '',
			'access_token_secret' => ''
		);

		$instance         = wp_parse_args( (array) $instance, $defaults );
		$twitter_apps_url = 'http://dev.twitter.com/apps';

		?>

		<p><a href="<?php echo $twitter_apps_url; ?>"><?php _e( 'Find or Create your Twitter App', 'Avada' ); ?></a></p>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'Avada' ); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'consumer_key' ); ?>"><?php _e( 'Consumer Key:', 'Avada' ); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'consumer_key' ); ?>" name="<?php echo $this->get_field_name( 'consumer_key' ); ?>" value="<?php echo $instance['consumer_key']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'consumer_secret' ); ?>"><?php _e( 'Consumer Secret:', 'Avada' ); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'consumer_secret' ); ?>" name="<?php echo $this->get_field_name( 'consumer_secret' ); ?>" value="<?php echo $instance['consumer_secret']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'access_token' ); ?>"><?php _e( 'Access Token:', 'Avada' ); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'access_token' ); ?>" name="<?php echo $this->get_field_name( 'access_token' ); ?>" value="<?php echo $instance['access_token']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'access_token_secret' ); ?>"><?php _e( 'Access Token Secret:', 'Avada' ); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'access_token_secret' ); ?>" name="<?php echo $this->get_field_name( 'access_token_secret' ); ?>" value="<?php echo $instance['access_token_secret']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'twitter_id' ); ?>"><?php _e( 'Twitter Username:', 'Avada' ); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'twitter_id' ); ?>" name="<?php echo $this->get_field_name( 'twitter_id' ); ?>" value="<?php echo $instance['twitter_id']; ?>" />
		</p>

			<label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php _e( 'Number of Tweets:', 'Avada' ); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" value="<?php echo $instance['count']; ?>" />
		</p>

	<?php
	}

	public function sort_tweets( $a, $b ) { return ( $b['start'] - $a['start'] ); }
}

// Omit closing PHP tag to avoid "Headers already sent" issues.
