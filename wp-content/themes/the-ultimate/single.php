<?php get_header(); ?>
	<?php
	$content_css = 'width:100%';
	$sidebar_css = 'display:none';
	$content_class = '';
	$sidebar_exists = false;
	$sidebar_left = '';
	$double_sidebars = false;

	$sidebar_1 = get_post_meta( $post->ID, 'sbg_selected_sidebar_replacement', true );
	$sidebar_2 = get_post_meta( $post->ID, 'sbg_selected_sidebar_2_replacement', true );

	if( Avada()->settings->get( 'posts_global_sidebar' ) ) {
		if( Avada()->settings->get( 'posts_sidebar' ) != 'None' ) {
			$sidebar_1 = array( Avada()->settings->get( 'posts_sidebar' ) );
		} else {
			$sidebar_1 = '';
		}

		if( Avada()->settings->get( 'posts_sidebar_2' ) != 'None' ) {
			$sidebar_2 = array( Avada()->settings->get( 'posts_sidebar_2' ) );
		} else {
			$sidebar_2 = '';
		}
	}

	if( ( is_array( $sidebar_1 ) && ( $sidebar_1[0] || $sidebar_1[0] === '0' ) ) && ( is_array( $sidebar_2 ) && ( $sidebar_2[0] || $sidebar_2[0] === '0' ) ) ) {
		$double_sidebars = true;
	}

	if( is_array( $sidebar_1 ) &&
		( $sidebar_1[0] || $sidebar_1[0] === '0' )
	) {
		$sidebar_exists = true;
	} else {
		$sidebar_exists = false;
	}

	if( ! $sidebar_exists ) {
		$content_css = 'width:100%';
		$sidebar_css = 'display:none';
		$sidebar_exists = false;
	} elseif(get_post_meta($post->ID, 'pyre_sidebar_position', true) == 'left') {
		$content_css = 'float:right;';
		$sidebar_css = 'float:left;';
		$content_class = 'portfolio-one-sidebar';
		$sidebar_exists = true;
		$sidebar_left = 1;
	} elseif(get_post_meta($post->ID, 'pyre_sidebar_position', true) == 'right') {
		$content_css = 'float:left;';
		$sidebar_css = 'float:right;';
		$content_class = 'portfolio-one-sidebar';
		$sidebar_exists = true;
	} elseif(get_post_meta($post->ID, 'pyre_sidebar_position', true) == 'default' || ! metadata_exists( 'post', $post->ID, 'pyre_sidebar_position' )) {
		$content_class = 'portfolio-one-sidebar';
		if(Avada()->settings->get( 'blog_sidebar_position' ) == 'Left') {
			$content_css = 'float:right;';
			$sidebar_css = 'float:left;';
			$sidebar_exists = true;
			$sidebar_left = 1;
		} elseif(Avada()->settings->get( 'blog_sidebar_position' ) == 'Right') {
			$content_css = 'float:left;';
			$sidebar_css = 'float:right;';
			$sidebar_exists = true;
			$sidebar_left = 2;
		}
	}

	if(get_post_meta($post->ID, 'pyre_sidebar_position', true) == 'right') {
		$sidebar_left = 2;
	}

	if( Avada()->settings->get( 'posts_global_sidebar' ) ) {
		if( Avada()->settings->get( 'posts_sidebar' ) != 'None' ) {
			$sidebar_1 = Avada()->settings->get( 'posts_sidebar' );

			if( Avada()->settings->get( 'blog_sidebar_position' ) == 'Right' ) {
				$content_css = 'float:left;';
				$sidebar_css = 'float:right;';
				$sidebar_left = 2;
			} else {
				$content_css = 'float:right;';
				$sidebar_css = 'float:left;';
				$sidebar_left = 1;
			}
		}

		if( Avada()->settings->get( 'posts_sidebar_2' ) != 'None' ) {
			$sidebar_2 = Avada()->settings->get( 'posts_sidebar_2' );
		}

		if( Avada()->settings->get( 'posts_sidebar' ) != 'None' && Avada()->settings->get( 'posts_sidebar_2' ) != 'None' ) {
			$double_sidebars = true;
		}
	} else {
		$sidebar_1 = '0';
		$sidebar_2 = '0';
	}

	if($double_sidebars == true) {
		$content_css = 'float:left;';
		$sidebar_css = 'float:left;';
		$sidebar_2_css = 'float:left;';
	} else {
		$sidebar_left = 1;
	}
	?>
	<div id="content" class="<?php echo $content_class; ?>" style="<?php echo $content_css; ?>">
		<?php if( ( ! Avada()->settings->get( 'blog_pn_nav' ) && get_post_meta($post->ID, 'pyre_post_pagination', true) != 'no' ) ||
				  ( Avada()->settings->get( 'blog_pn_nav' ) && get_post_meta($post->ID, 'pyre_post_pagination', true) == 'yes' ) ): ?>
		<div class="single-navigation clearfix">
			<?php previous_post_link('%link', __('Previous', 'Avada')); ?>
			<?php next_post_link('%link', __('Next', 'Avada')); ?>
		</div>
		<?php endif; ?>
		<?php while( have_posts() ): the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
			<?php
			$full_image = '';
			if( ! post_password_required($post->ID) ): // 1
			if(Avada()->settings->get( 'featured_images_single' )): // 2
			if( avada_number_of_featured_images() > 0 || get_post_meta( $post->ID, 'pyre_video', true ) ): // 3
			?>
			<div class="fusion-flexslider flexslider fusion-flexslider-loading post-slideshow fusion-post-slideshow">
				<ul class="slides">
					<?php if(get_post_meta($post->ID, 'pyre_video', true)): ?>
					<li>
						<div class="full-video">
							<?php echo get_post_meta($post->ID, 'pyre_video', true); ?>
						</div>
					</li>
					<?php endif; ?>
					<?php if( has_post_thumbnail() && get_post_meta( $post->ID, 'pyre_show_first_featured_image', true ) != 'yes' ): ?>
					<?php $attachment_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full'); ?>
					<?php $full_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full'); ?>
					<?php $attachment_data = wp_get_attachment_metadata(get_post_thumbnail_id()); ?>
					<li>
						<?php if( ! Avada()->settings->get( 'status_lightbox' ) && ! Avada()->settings->get( 'status_lightbox_single' ) ): ?>
						<a href="<?php echo $full_image[0]; ?>" data-rel="iLightbox[gallery<?php the_ID(); ?>]" title="<?php echo get_post_field('post_excerpt', get_post_thumbnail_id()); ?>" data-title="<?php echo get_post_field('post_title', get_post_thumbnail_id()); ?>" data-caption="<?php echo get_post_field('post_excerpt', get_post_thumbnail_id()); ?>"><img src="<?php echo $attachment_image[0]; ?>" alt="<?php echo get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true); ?>" /></a>
						<?php else: ?>
						<img src="<?php echo $attachment_image[0]; ?>" alt="<?php echo get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true); ?>" />
						<?php endif; ?>
					</li>
					<?php endif; ?>
					<?php
					$i = 2;
					while($i <= Avada()->settings->get( 'posts_slideshow_number' )):
					$attachment_new_id = kd_mfi_get_featured_image_id('featured-image-'.$i, 'post');
					if($attachment_new_id):
					?>
					<?php $attachment_image = wp_get_attachment_image_src($attachment_new_id, 'full'); ?>
					<?php $full_image = wp_get_attachment_image_src($attachment_new_id, 'full'); ?>
					<?php $attachment_data = wp_get_attachment_metadata($attachment_new_id); ?>
					<li>
						<?php if( ! Avada()->settings->get( 'status_lightbox' ) && ! Avada()->settings->get( 'status_lightbox_single' ) ): ?>
						<a href="<?php echo $full_image[0]; ?>" data-rel="iLightbox[gallery<?php the_ID(); ?>]" title="<?php echo get_post_field('post_excerpt', $attachment_new_id); ?>" data-title="<?php echo get_post_field( 'post_title', $attachment_new_id ); ?>" data-caption="<?php echo get_post_field('post_excerpt', $attachment_new_id ); ?>"><img src="<?php echo $attachment_image[0]; ?>" alt="<?php echo get_post_meta($attachment_new_id, '_wp_attachment_image_alt', true); ?>" /></a>
						<?php else: ?>
						<img src="<?php echo $attachment_image[0]; ?>" alt="<?php echo get_post_meta($attachment_new_id, '_wp_attachment_image_alt', true); ?>" />
						<?php endif; ?>
					</li>
					<?php endif; $i++; endwhile; ?>
				</ul>
			</div>
			<?php endif; // 3 ?>
			<?php endif; // 2 ?>
			<?php endif; // 1 ?>
			<?php if(Avada()->settings->get( 'blog_post_title' )): ?>
			<?php echo avada_render_post_title( $post->ID, FALSE, '', '2' ); ?>
			<?php elseif( ! Avada()->settings->get( 'disable_date_rich_snippet_pages' ) ): ?>
			<span class="entry-title" style="display: none;"><?php the_title(); ?></span>
			<?php endif; ?>
			<div class="post-content">
				<?php the_content(); ?>
				<?php avada_link_pages(); ?>
			</div>
			<?php if( ! post_password_required($post->ID) ): ?>
			<?php echo avada_render_post_metadata( 'single' ); ?>
			<?php if( ( Avada()->settings->get( 'social_sharing_box' ) && get_post_meta($post->ID, 'pyre_share_box', true) != 'no' ) ||
					  ( ! Avada()->settings->get( 'social_sharing_box' ) && get_post_meta($post->ID, 'pyre_share_box', true) == 'yes' ) ):
				$full_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
				$sharingbox_soical_icon_options = array (
					'sharingbox'		=> 'yes',
					'icon_colors' 		=> Avada()->settings->get( 'sharing_social_links_icon_color' ),
					'box_colors' 		=> Avada()->settings->get( 'sharing_social_links_box_color' ),
					'icon_boxed' 		=> Avada()->settings->get( 'sharing_social_links_boxed' ),
					'icon_boxed_radius' => Avada()->settings->get( 'sharing_social_links_boxed_radius' ),
					'tooltip_placement'	=> Avada()->settings->get( 'sharing_social_links_tooltip_placement' ),
                	'linktarget'        => Avada()->settings->get( 'social_icons_new' ),
					'title'				=> wp_strip_all_tags(get_the_title( $post->ID ), true),
					'description'		=> wp_strip_all_tags(get_the_title( $post->ID ), true),
					'link'				=> get_permalink( $post->ID ),
					'pinterest_image'	=> ($full_image) ? $full_image[0] : '',
				);
				?>
				<div class="fusion-sharing-box fusion-single-sharing-box share-box">
					<h4><?php echo __('Share This Story, Choose Your Platform!', 'Avada'); ?></h4>
					<?php echo $social_icons->render_social_icons( $sharingbox_soical_icon_options ); ?>
				</div>
			<?php endif; ?>
			<?php if( ( Avada()->settings->get( 'author_info' ) && get_post_meta($post->ID, 'pyre_author_info', true) != 'no' ) ||
					  ( ! Avada()->settings->get( 'author_info' ) && get_post_meta($post->ID, 'pyre_author_info', true) == 'yes' ) ): ?>
			<div class="about-author">
				<?php
					ob_start();
					the_author_posts_link();
					$title = sprintf( '%s %s', __( 'About the Author:', 'Avada' ), ob_get_clean() );
					echo do_shortcode( sprintf( '[title size="3" content_align="left" style_type="default"]%s[/title]', $title ) );
				?>
				<div class="about-author-container">
					<div class="avatar">
						<?php echo get_avatar(get_the_author_meta('email'), '72'); ?>
					</div>
					<div class="description">
						<?php the_author_meta("description"); ?>
					</div>
				</div>
			</div>
			<?php endif; ?>

			<?php
			// Render Related Posts
			echo avada_render_related_posts();
			?>

			<?php if( ( Avada()->settings->get( 'blog_comments' ) && get_post_meta($post->ID, 'pyre_post_comments', true ) != 'no' ) ||
					  ( ! Avada()->settings->get( 'blog_comments' ) && get_post_meta($post->ID, 'pyre_post_comments', true) == 'yes' ) ): ?>
				<?php
				wp_reset_query();
				comments_template();
				?>
			<?php endif; ?>
			<?php endif; ?>
		</div>
		<?php endwhile; ?>
		<?php wp_reset_query(); ?>
	</div>
	<?php if( $sidebar_exists == true ): ?>
	<div id="sidebar" class="sidebar" style="<?php echo $sidebar_css; ?>">
		<?php
		if($sidebar_left == 1) {
			generated_dynamic_sidebar($sidebar_1);
		}
		if($sidebar_left == 2) {
			generated_dynamic_sidebar_2($sidebar_2);
		}
		?>
	</div>
	<?php if( $double_sidebars == true ): ?>
	<div id="sidebar-2" class="sidebar" style="<?php echo $sidebar_2_css; ?>">
		<?php
		if($sidebar_left == 1) {
			generated_dynamic_sidebar_2($sidebar_2);
		}
		if($sidebar_left == 2) {
			generated_dynamic_sidebar($sidebar_1);
		}
		?>
	</div>
	<?php endif; ?>
	<?php endif; ?>
<?php get_footer();

// Omit closing PHP tag to avoid "Headers already sent" issues.
