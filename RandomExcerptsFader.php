<?php
/*
Plugin Name: Random Excerpts Fader
Plugin URI: http://www.jackreichert.com/2010/09/random-excerpts-fader/
Description: Creates a widget that takes randomly a number of excerpts from a category of your choice and fades them in and out. Perfect for displaying testimonials.
Version: 1.2.4
Author: Jack Reichert	
Author URI: http://www.jackreichert.com/about
License: GPLv2

  Copyright 2010  Jack Reichert  (email : contact@jackreichert.com)

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

class reFader extends WP_Widget {

	function reFader() { 	// The widget construct. Initiating our plugin data.
		$widgetData = array( 'classname' => 'reFader', 'description' => __( 'Display excerpts from a category of your choice and fades them in and out... jQuery Style!' ) );
		$this->WP_Widget('reFader', __('Random Excerpts Fader'), $widgetData);
	} 

	function widget($args, $instance) { // Displays the widget on the screen.
		extract($args);
		echo $before_widget;
		echo $before_title . $instance['title'] . $after_title; 
		$this->RandomExcerptsFader($instance);
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance) { // Updates the settings.
		return $new_instance;
	}
	
	function form($instance) {	// The admin form. 
		$defaults = array( 'title' => 'Random Excerpts' ,'amount' => 5, 'cat' => 0, 'length' => 50, 'duration' => 5000, 'linked'=>'no');
		$instance = wp_parse_args($instance, $defaults); 
		$ref_categories = get_categories('hide_empty=0');
				foreach ( $ref_categories as $c ) {
					$ref_cat[] = array( $c->cat_ID, $c->cat_name );
				}?>
		<div id="reFader-admin-panel">		
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>">Widget title:</label>
				<input type="text" class="widefat" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo $instance['title']; ?>" />
			</p>
			<p>
				<select name="<?php echo $this->get_field_name('cat'); ?>" id="<?php echo $this->get_field_id('cat'); ?>">
					<option value="-1" <?php if ($instance['cat'] == '-1') { echo 'selected="true"'; } ?>>All Categories</option>
				<?php 
				foreach( $ref_cat as $cat ) {	
					$extra = "";	
					if( $instance['cat'] == $cat[0] ) { 
						$extra = 'selected'; 
					}
					echo '<option value="'.$cat[0].'" '.$extra.'>'.$cat[1]."</option>\n";
				} ?>
				</select>
			</p>			
			<p>
				<label for="<?php echo $this->get_field_id('amount'); ?>">Number of posts:</label>
				<input type="text" size="2" name="<?php echo $this->get_field_name('amount'); ?>" id="<?php echo $this->get_field_id('amount'); ?>" value="<?php echo $instance['amount']; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('length'); ?>">Excerpt Word Length:</label>
				<input type="text" size="3" name="<?php echo $this->get_field_name('length'); ?>" id="<?php echo $this->get_field_id('length'); ?>" value="<?php echo $instance['length']; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('duration'); ?>">Fade duration:</label>			
				<input type="text" size="5" name="<?php echo $this->get_field_name('duration'); ?>" id="<?php echo $this->get_field_id('duration'); ?>" value="<?php echo $instance['duration']; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('linked'); ?>">Link title to post?</label>			
				<input type="checkbox" name="<?php echo $this->get_field_name('linked'); ?>" id="<?php echo $this->get_field_id('linked'); ?>" value="yes" <?php echo (($instance['linked']=='yes')?'checked="checked"':''); ?> />
			</p>
		</div>
<?php	} 

	function truncWords($string, $words = 55) { //creates custom size excerpt
	    $string = explode(' ', strip_tags($string));
	    if (count($string) > $words) {
	        return implode(' ', array_slice($string, 0, $words));
	    }
	
	    return implode(' ', $string);
	}

	function RandomExcerptsFader($instance) { // gets the posts ?>
		<div id="RandomExcerpts">
		<?php 
			$excerpts = get_posts('showposts='.$instance['amount'].'&cat='.$instance['cat'].'&orderby=rand');
			foreach($excerpts as $excerpt) : ?>
				<p class="hide">"<?php echo $this->truncWords($excerpt->post_content, $instance['length']); ?>"<br />
				<span class="testimonial-title"><?php echo (($instance['linked']=='yes') ? '<a href="'.get_permalink($excerpt->ID).'">'.$excerpt->post_title.'</a>' : get_the_title()); ?></span></p>
	<?php 	endforeach; ?>
			<div id="duration"><?php echo $instance['duration']; ?></div>
		</div>		
	<?php 
	} 

} 

// Register the widget.
add_action('widgets_init', create_function('', 'return register_widget("reFader");'));
wp_enqueue_script("jquery");
wp_enqueue_script('reFader_js', WP_PLUGIN_URL.'/random-excerpts-fader/RandomExcerptsFader.js', array('jquery')); 
wp_register_style('reFaderStylesheet', WP_PLUGIN_URL . '/random-excerpts-fader/RandomExcerptsFader.css');
wp_enqueue_style( 'reFaderStylesheet');                       
?>