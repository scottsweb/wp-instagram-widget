<?php
/*
Plugin Name: WP Instagram Widget
Plugin URI: https://github.com/cftp/wp-instagram-widget
Description: A WordPress widget for showing your latest Instagram photos
Version: 1.3.1
Author: Scott Evans (Code For The People)
Author URI: http://codeforthepeople.com
Text Domain: wpiw
Domain Path: /assets/languages/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This comment is added for compatibility with the null framework https://github.com/scottsweb/null
Widget Name: Instagram Widget

Copyright © 2013 Code for the People ltd

                _____________
               /      ____   \
         _____/       \   \   \
        /\    \        \___\   \
       /  \    \                \
      /   /    /          _______\
     /   /    /          \       /
    /   /    /            \     /
    \   \    \ _____    ___\   /
     \   \    /\    \  /       \
      \   \  /  \____\/    _____\
       \   \/        /    /    / \
        \           /____/    /___\
         \                        /
          \______________________/


This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

*/

// load is_plugin_active function if required
if (!function_exists('is_plugin_inactive')) require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

// registers widget when bundled with the null framework
if (is_plugin_inactive('wp-instagram-widget/wordpress-instagram-widget.php') && function_exists('null_get_extensions')) {

	// register
	register_widget('null_instagram_widget');

	// text domain
	$wpiwdomain = 'null';
}

// register widget for standalone
if (is_plugin_active('wp-instagram-widget/wp-instagram-widget.php')) {

	// define some constants
	define('WP_INSTAGRAM_WIDGET_JS_URL',plugins_url('/assets/js',__FILE__));
	define('WP_INSTAGRAM_WIDGET_CSS_URL',plugins_url('/assets/css',__FILE__));
	define('WP_INSTAGRAM_WIDGET_IMAGES_URL',plugins_url('/assets/images',__FILE__));
	define('WP_INSTAGRAM_WIDGET_PATH', dirname(__FILE__));
	define('WP_INSTAGRAM_WIDGET_BASE', plugin_basename(__FILE__));
	define('WP_INSTAGRAM_WIDGET_FILE', __FILE__);

	// text domain
	$wpiwdomain = 'wpiw';

	// load language files
	load_plugin_textdomain($wpiwdomain, false, dirname(WP_INSTAGRAM_WIDGET_BASE) . '/assets/languages/');

	// register
	add_action('widgets_init', 'wpiw_widget');
}

function wpiw_widget() {
	register_widget('null_instagram_widget');
}

class null_instagram_widget extends WP_Widget {

	function null_instagram_widget() {
		global $wpiwdomain;
		$this->wpiwdomain = $wpiwdomain;
		$widget_ops = array('classname' => 'null-instagram-feed', 'description' => __('Displays your latest Instagram photos', $this->wpiwdomain) );
		$this->WP_Widget('null-instagram-feed', __('Instagram', $this->wpiwdomain), $widget_ops);
	}

	function widget($args, $instance) {

		extract($args, EXTR_SKIP);

		$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
		$username = empty($instance['username']) ? '' : $instance['username'];
		$limit = empty($instance['number']) ? 9 : $instance['number'];
		$size = empty($instance['size']) ? 'thumbnail' : $instance['size'];
		$target = empty($instance['target']) ? '_self' : $instance['target'];
		$link = empty($instance['link']) ? '' : $instance['link'];

		echo $before_widget;
		if(!empty($title)) { echo $before_title . $title . $after_title; };

		do_action( 'wpiw_before_widget', $instance );

		if ($username != '') {

			$media_array = $this->scrape_instagram($username, $limit);

			if ( is_wp_error($media_array) ) {

			   echo $media_array->get_error_message();

			} else {

				// filter for images only?
				if ( $images_only = apply_filters( 'wpiw_images_only', FALSE ) )
					$media_array = array_filter( $media_array, array( $this, 'images_only' ) );

				?><ul class="instagram-pics"><?php
				foreach ($media_array as $item) {
					echo '<li><a href="'. esc_url( $item['link'] ) .'" target="'. esc_attr( $target ) .'"><img src="'. esc_url($item[$size]['url']) .'"  alt="'. esc_attr( $item['description'] ) .'" title="'. esc_attr( $item['description'] ).'"/></a></li>';
				}
				?></ul><?php
			}
		}

		if ($link != '') {
			?><p class="clear"><a href="//instagram.com/<?php echo trim($username); ?>" rel="me" target="<?php echo esc_attr( $target ); ?>"><?php echo $link; ?></a></p><?php
		}

		do_action( 'wpiw_after_widget', $instance );

		echo $after_widget;
	}

	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => __('Instagram', $this->wpiwdomain), 'username' => '', 'link' => __('Follow Us', $this->wpiwdomain), 'number' => 9, 'size' => 'thumbnail', 'target' => '_self') );
		$title = esc_attr($instance['title']);
		$username = esc_attr($instance['username']);
		$number = absint($instance['number']);
		$size = esc_attr($instance['size']);
		$target = esc_attr($instance['target']);
		$link = esc_attr($instance['link']);
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', $this->wpiwdomain); ?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Username', $this->wpiwdomain); ?>: <input class="widefat" id="<?php echo $this->get_field_id('username'); ?>" name="<?php echo $this->get_field_name('username'); ?>" type="text" value="<?php echo $username; ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of photos', $this->wpiwdomain); ?>: <input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('size'); ?>"><?php _e('Photo size', $this->wpiwdomain); ?>:</label>
			<select id="<?php echo $this->get_field_id('size'); ?>" name="<?php echo $this->get_field_name('size'); ?>" class="widefat">
				<option value="thumbnail" <?php selected('thumbnail', $size) ?>><?php _e('Thumbnail', $this->wpiwdomain); ?></option>
				<option value="large" <?php selected('large', $size) ?>><?php _e('Large', $this->wpiwdomain); ?></option>
			</select>
		</p>
		<p><label for="<?php echo $this->get_field_id('target'); ?>"><?php _e('Open links in', $this->wpiwdomain); ?>:</label>
			<select id="<?php echo $this->get_field_id('target'); ?>" name="<?php echo $this->get_field_name('target'); ?>" class="widefat">
				<option value="_self" <?php selected('_self', $target) ?>><?php _e('Current window (_self)', $this->wpiwdomain); ?></option>
				<option value="_blank" <?php selected('_blank', $target) ?>><?php _e('New window (_blank)', $this->wpiwdomain); ?></option>
			</select>
		</p>
		<p><label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Link text', $this->wpiwdomain); ?>: <input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo $link; ?>" /></label></p>
		<?php

	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['username'] = trim(strip_tags($new_instance['username']));
		$instance['number'] = !absint($new_instance['number']) ? 9 : $new_instance['number'];
		$instance['size'] = (($new_instance['size'] == 'thumbnail' || $new_instance['size'] == 'large') ? $new_instance['size'] : 'thumbnail');
		$instance['target'] = (($new_instance['target'] == '_self' || $new_instance['target'] == '_blank') ? $new_instance['target'] : '_self');
		$instance['link'] = strip_tags($new_instance['link']);
		return $instance;
	}

	// based on https://gist.github.com/cosmocatalano/4544576
	function scrape_instagram($username, $slice = 9) {

		$username = strtolower($username);

		if (false === ($instagram = get_transient('instagram-media-'.sanitize_title_with_dashes($username)))) {

			$remote = wp_remote_get('http://instagram.com/'.trim($username));

			if (is_wp_error($remote))
	  			return new WP_Error('site_down', __('Unable to communicate with Instagram.', $this->wpiwdomain));

  			if ( 200 != wp_remote_retrieve_response_code( $remote ) )
  				return new WP_Error('invalid_response', __('Instagram did not return a 200.', $this->wpiwdomain));

			$shards = explode('window._sharedData = ', $remote['body']);
			$insta_json = explode(';</script>', $shards[1]);
			$insta_array = json_decode($insta_json[0], TRUE);

			if (!$insta_array)
	  			return new WP_Error('bad_json', __('Instagram has returned invalid data.', $this->wpiwdomain));

			$images = $insta_array['entry_data']['UserProfile'][0]['userMedia'];

			$instagram = array();

			foreach ($images as $image) {

				if ($image['user']['username'] == $username) {

					$image['link']                          = preg_replace( "/^http:/i", "", $image['link'] );
					$image['images']['thumbnail']           = preg_replace( "/^http:/i", "", $image['images']['thumbnail'] );
					$image['images']['standard_resolution'] = preg_replace( "/^http:/i", "", $image['images']['standard_resolution'] );

					$instagram[] = array(
						'description'   => $image['caption']['text'],
						'link'          => $image['link'],
						'time'          => $image['created_time'],
						'comments'      => $image['comments']['count'],
						'likes'         => $image['likes']['count'],
						'thumbnail'     => $image['images']['thumbnail'],
						'large'         => $image['images']['standard_resolution'],
						'type'          => $image['type']
					);
				}
			}

			$instagram = base64_encode( serialize( $instagram ) );
			set_transient('instagram-media-'.sanitize_title_with_dashes($username), $instagram, apply_filters('null_instagram_cache_time', HOUR_IN_SECONDS*2));
		}

		$instagram = unserialize( base64_decode( $instagram ) );

		return array_slice($instagram, 0, $slice);
	}

	function images_only($media_item) {

		if ($media_item['type'] == 'image')
			return true;

		return false;
	}
}
?>