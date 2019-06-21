<?php 

/**
 * Plugin Name:       Convert ACF Videos to LD Videos
 * Plugin URI:        https://github.com/jcasabona/acf-ld-swap-videos
 * Description:       A simple plugin to convert an ACF field value to the native LD videos value.
 * Version:           1.0.0
 * Author:            Joe Casabona
 * Author URI:        https:/casabona.org/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

 function jc_swap_videos() {
	 $lessons = new WP_Query( 
		array( 
			'post_type' => array( 'sfwd-lessons', 'sfwd-topic' ),
			'posts_per_page' => -1,
		)
	);
 
	if ( $lessons->have_posts() ) {
		while ( $lessons->have_posts() ) {
			$lessons->the_post();
			// echo 'On post '. get_the_ID(); 
			$video_url = jc_get_source_url( 'acf', 'video_url' );
			if ( $video_url ) {
				// echo ' - Video URL: '. get_field( 'video_url');
				learndash_update_setting( get_the_ID(), 'lesson_video_enabled', 'on' );
				learndash_update_setting( get_the_ID(), 'lesson_video_url', $video_url ); 
				learndash_update_setting( get_the_ID(), 'lesson_video_auto_start', true );
			}

			// echo ' - LD Video URL: ' . learndash_get_setting( get_the_ID(), 'lesson_video_url' ) . ' <br/>';
		}
	}
}

// Only call this once, on activation
register_activation_hook( __FILE__, 'jc_swap_videos' );

function jc_get_source_url( $method = 'acf', $field_name = 'video_url' ) {
	switch( $method ) {
		case 'csv': 
			// Do something
			break;
		case 'meta': 
			return get_post_meta( get_the_ID(), $field_name );
			break; // unncessary but standard
		case 'acf': 
			return get_field( $field );
			break;
		default: 
			return false;
	}
}

