<?php

/*
Plugin Name: Random Excerpts Fader
Plugin URI: http://www.jackreichert.com/plugins/random-excerpts-fader/
Description: Creates a widget that takes randomly a number of excerpts from a category of your choice and fades them in and out. Perfect for displaying testimonials.
Version: 1.4
Author: Jack Reichert	
Author URI: http://www.jackreichert.com/
License: GPLv2

  Copyright 2010  Jack Reichert  (email : awesome@jackreichert.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, visit http://codex.wordpress.org/GPL    
    or write to the Free Software Foundation, Inc., 
    51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    
*/

class reFader_widget extends WP_Widget {

	function reFader_widget() {    // The widget construct. Initiating our plugin data.
		$widgetData = array( 'classname'   => 'reFader_widget',
		                     'description' => __( 'Display excerpts from a category of your choice and fades them in and out... jQuery Style!' )
		);
		$this->WP_Widget( 'reFader_widget', __( 'Random Excerpts Fader' ), $widgetData );
	}

	function widget( $args, $instance ) { // Displays the widget on the screen.
		extract( $args );
		echo $before_widget;
		echo $before_title . $instance['title'] . $after_title;
		$widget_instance = new reFader;
		echo $widget_instance->reFader( $instance );
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) { // Updates the settings.
		return $new_instance;
	}

	function form( $instance ) {    // The admin form.
		$defaults       = array(
			'title'    => 'Random Excerpts',
			'amount'   => 5,
			'cat'      => 0,
			'length'   => 50,
			'duration' => 5000,
			'linked'   => 'no'
		);
		$instance       = wp_parse_args( $instance, $defaults );
		$ref_categories = get_categories( 'hide_empty=0' );
		foreach ( $ref_categories as $c ) {
			$ref_cat[] = array( $c->cat_ID, $c->cat_name );
		} ?>
		<div id="reFader-admin-panel">
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>">Widget title:</label>
				<input type="text" class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>"
				       id="<?php echo $this->get_field_id( 'title' ); ?>" value="<?php echo $instance['title']; ?>"/>
			</p>

			<p>
				<select name="<?php echo $this->get_field_name( 'cat' ); ?>"
				        id="<?php echo $this->get_field_id( 'cat' ); ?>">
					<option value="-1" <?php if ( $instance['cat'] == '-1' ) {
						echo 'selected="true"';
					} ?>>All Categories
					</option>
					<?php
					foreach ( $ref_cat as $cat ) {
						$extra = "";
						if ( $instance['cat'] == $cat[0] ) {
							$extra = 'selected';
						}
						echo '<option value="' . $cat[0] . '" ' . $extra . '>' . $cat[1] . "</option>\n";
					} ?>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'amount' ); ?>">Number of posts:</label>
				<input type="text" size="2" name="<?php echo $this->get_field_name( 'amount' ); ?>"
				       id="<?php echo $this->get_field_id( 'amount' ); ?>" value="<?php echo $instance['amount']; ?>"/>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'length' ); ?>">Excerpt Word Length:</label>
				<input type="text" size="3" name="<?php echo $this->get_field_name( 'length' ); ?>"
				       id="<?php echo $this->get_field_id( 'length' ); ?>" value="<?php echo $instance['length']; ?>"/>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'duration' ); ?>">Fade duration:</label>
				<input type="text" size="5" name="<?php echo $this->get_field_name( 'duration' ); ?>"
				       id="<?php echo $this->get_field_id( 'duration' ); ?>"
				       value="<?php echo $instance['duration']; ?>"/>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'use_featured' ); ?>">Use featured image instead of
					excerpt?</label>
				<select name="<?php echo $this->get_field_name( 'use_featured' ); ?>"
				        id="<?php echo $this->get_field_id( 'use_featured' ); ?>">
					<option
						value="yes" <?php echo( ( $instance['use_featured'] == 'yes' ) ? 'selected="selected"' : '' ); ?>>
						Yes
					</option>
					<option
						value="no" <?php echo( ( $instance['use_featured'] != 'yes' ) ? 'selected="selected"' : '' ); ?>>
						No
					</option>
				</select><br>
				<label for="<?php echo $this->get_field_id( 'featured_size' ); ?>">Which size?</label>
				<?php $featured_sizes = get_intermediate_image_sizes(); ?>
				<select name="<?php echo $this->get_field_name( 'featured_size' ); ?>"
				        id="<?php echo $this->get_field_id( 'featured_size' ); ?>">
					<?php foreach ( $featured_sizes as $ind => $f_size ) : ?>
						<option
							value="<?php echo $ind; ?>" <?php echo( ( $instance['featured_size'] == $ind ) ? 'selected="selected"' : '' ); ?>><?php echo $f_size; ?></option>
					<?php endforeach; ?>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'linked' ); ?>">Link title to post?</label>
				<select name="<?php echo $this->get_field_name( 'linked' ); ?>"
				        id="<?php echo $this->get_field_id( 'linked' ); ?>">
					<option value="yes" <?php echo( ( $instance['linked'] == 'yes' ) ? 'selected="selected"' : '' ); ?>>
						Yes
					</option>
					<option value="no" <?php echo( ( $instance['linked'] != 'yes' ) ? 'selected="selected"' : '' ); ?>>
						No
					</option>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'url' ); ?>">Link all to one url:</label>
				<input type="text" class="widefat" name="<?php echo $this->get_field_name( 'url' ); ?>"
				       id="<?php echo $this->get_field_id( 'url' ); ?>" value="<?php echo $instance['url']; ?>"/>
			</p>
		</div>
	<?php }

}

class reFader {
	function reFader( $options = array() ) {
		$excerpts     = get_posts( 'showposts=' . $options['amount'] . ( ( $options['cat'] != '-1' ) ? '&cat=' . $options['cat'] : '' ) . '&orderby=rand' );
		$all_excerpts = '';
		foreach ( $excerpts as $excerpt ) {
			$all_excerpts .= '<p>';
			if ( $options['use_featured'] == 'yes' && has_post_thumbnail( $excerpt->ID ) ) {
				$featured_sizes = get_intermediate_image_sizes();
				$all_excerpts .= get_the_post_thumbnail( $excerpt->ID, $featured_sizes[ $options['featured_size'] ] );
			} else {
				$all_excerpts .= ( ( $options['length'] != "-1" ) ? $this->truncWords( $excerpt->post_content, intval( $options['length'] ) ) : $excerpt->post_content );
			}
			$all_excerpts .= '<span class="testimonial-title">' . ( ( $options['linked'] == 'yes' || $options['url'] != '' ) ? '<a href="' . ( ( $options['url'] != '' ) ? $options['url'] : get_permalink( $excerpt->ID ) ) . '">' . $excerpt->post_title . '</a>' : $excerpt->post_title ) . '</span>';
			$all_excerpts .= '</p>';
		}

		return '<div class="RandomExcerpts">' .
		       $all_excerpts .
		       '<div class="duration" style="display:none;">' . $options['duration'] . '</div>' .
		       '</div>';
	}

	function truncWords( $string, $words = 55 ) { //creates custom size excerpt
		$string = explode( ' ', strip_tags( $string ) );
		if ( count( $string ) > $words ) {
			return implode( ' ', array_slice( $string, 0, $words ) );
		}

		return implode( ' ', $string );
	}

	function reFader_shortcode( $atts ) {
		$defaults = array(
			"title"         => "Random Excerpts",
			"cat"           => "-1",
			"amount"        => "5",
			"length"        => "50",
			"duration"      => "5000",
			"use_featured"  => "no",
			"featured_size" => "thumbnail",
			"linked"        => "yes",
			"url"           => ""
		);
		extract( shortcode_atts( $defaults, $atts ) );
		$newVals = array( "title"    => $title,
		                  "cat"      => $cat,
		                  "amount"   => $amount,
		                  "length"   => $length,
		                  "duration" => $duration,
		                  "use_featured"  => $use_featured,
		                  "featured_size" => $featured_size,
		                  "linked"   => $linked,
		                  "url"      => $url
		);
		$merged  = array_merge( $defaults, $newVals );

		$widget_instance = new reFader;

		return $widget_instance->reFader( $merged );
	}

}

add_shortcode( 'reFader', array( 'reFader', 'reFader_shortcode' ) );

// Register the widget.
add_action( 'widgets_init', create_function( '', 'return register_widget("reFader_widget");' ) );
wp_enqueue_script( "jquery" );
wp_enqueue_script( 'reFader_js', WP_PLUGIN_URL . '/random-excerpts-fader/RandomExcerptsFader.js', array( 'jquery' ) );
wp_register_style( 'reFaderStylesheet', WP_PLUGIN_URL . '/random-excerpts-fader/RandomExcerptsFader.css' );
wp_enqueue_style( 'reFaderStylesheet' );
?>