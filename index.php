<?php
/*
Plugin Name: Carousel Plugin
Plugin URI: http://www.11online.us
Description: Easy way to edit Carousel JS
Version: 1.0
Revision Date: December 16, 2015
License: GNU General Public License 3.0 (GPL) http://www.gnu.org/licenses/gpl.html
Author: Eric Debelak
Author URI: http://www.11online.us
*/

define('CPT_SLIDER_VERSION','1.0');
define('CPT_SLIDER_NAME','Custom Post Type Slider');
define('CPT_SLIDER_ROOT',dirname(__FILE__).'/');
define('CPT_SLIDER_DIR',basename(dirname(__FILE__)));


add_shortcode('cpt_slider', 'cpt_slider_shortcode_add_code');

function cpt_slider_shortcode_add_code($atts) {
	$attributes = shortcode_atts( array(
        'number' => 3,
        'type' => 'post',
    ), $atts );

	$args = array(
		'posts_per_page'   => $attributes['number'],
		'offset'           => 0,
		'category'         => '',
		'category_name'    => '',
		'orderby'          => 'date',
		'order'            => 'DESC',
		'include'          => '',
		'exclude'          => '',
		'meta_key'         => '',
		'meta_value'       => '',
		'post_type'        => $attributes['type'],
		'post_mime_type'   => '',
		'post_parent'      => '',
		'author'	  	   => '',
		'author_name'	   => '',
		'post_status'      => 'publish',
		'suppress_filters' => true 
	);
	$posts_array = get_posts( $args );

	// add the js and css
	add_action('wp_footer', 'cpt_slider_add_code');

	function cpt_slider_add_code() {
		wp_enqueue_style( 'bootstrap_carousel_style', plugin_dir_url( __FILE__ ) . '/bootstrap/css/bootstrap.css' );
		wp_enqueue_script( 'bootstrap_carousel_script', plugin_dir_url( __FILE__ ) . '/bootstrap/js/bootstrap.min.js', array(), '1.0.0', true );
	}
	$speed = 3000;
	$text = '<div id="carousel" class="carousel slide" data-ride="carousel">
	<!-- Indicators -->
	<ol class="carousel-indicators">';
	$count = count($posts_array);
	for($i = 0; $i <  $count; $i++) {
			if($i == 0) {
				$text .= '<li data-target="#carousel" data-slide-to="0" class="active"></li>';
			} else {
				$text .='<li data-target="#carousel" data-slide-to="' . $i . '"></li>';
			}
	}
	$text .= '</ol>
	<!-- Wrapper for slides -->
	<div class="carousel-inner" role="listbox">';
	global $post;
  	foreach ($posts_array as $i => $post) {  	
  		setup_postdata( $post );

				if($i == 0) {
					$text .= '<div class="item active">';
				} else {
						$text .= '<div class="item">';
				}
	$text .= '<div class="carousel-caption">
				<h1><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h1>
				<p>' . get_the_excerpt() . '</p>
				<p><a href="' . get_the_permalink() . '" class="button">Read More</a></p>
			</div></div>';
	}

	$text .= '</div>
	<!-- Controls -->
	<a class="left carousel-control" href="#carousel" role="button" data-slide="prev">
	<span class="dashicons dashicons-arrow-left-alt2 glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
	<span class="sr-only">Previous</span>
	</a>
	<a class="right carousel-control" href="#carousel" role="button" data-slide="next">
	<span class="dashicons dashicons-arrow-right-alt2 glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
	<span class="sr-only">Next</span>
	</a>
	</div>';
	$text .= '<script>
	jQuery(document).ready(function() {
		jQuery(".carousel").carousel({
			interval: ' . $speed . '
		});
	});
	</script>';
	return $text;

}


?>
