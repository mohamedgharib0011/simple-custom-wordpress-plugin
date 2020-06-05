<?php
/**
 *            @package Weekly Positive Message Plugin
 *            @wordpress-plugin
 *            Plugin Name: Weekly Positive Message Plugin
 *            Description: Weekly Positive Message Plugin provides shortcode to display messages under category 'Home Message'. It will display different message every 7 days.
 *            Version: 1.0
 *            Text Domain: weekly-positive-message
 */
 
function weekly_message_link(){

	$args = array( 'post_type' => 'post','posts_per_page'   => -1, 'offset'=> 0, 'category_name' => 'home-message' );
	$arr_posts = get_posts( $args );
	
	$length=count($arr_posts);
	
	// No messages found
	if($length==0){
		return "";
	}
		
	$current_time=strtotime("now");
	
	$weekly_message_last_updated = get_option('weekly_message_last_updated');
	$weekly_message_last_index = get_option('weekly_message_last_index');
	
	// first time
	if(!$weekly_message_last_updated){
		update_option( 'weekly_message_last_updated', $current_time );
		update_option( 'weekly_message_last_index', 0);
		return $arr_posts[0]->post_title;
	}
	
	$diff_in_days=($current_time -$weekly_message_last_updated) / (60*60*24);
	
	if($diff_in_days>=7){
		$weekly_message_last_index=($weekly_message_last_index+1) % $length;
		$weekly_message_last_updated=$current_time;
		update_option( 'weekly_message_last_updated', $weekly_message_last_updated );
	    update_option( 'weekly_message_last_index', $weekly_message_last_index);
	}
	
	return $arr_posts[$weekly_message_last_index]->post_title;

}
add_shortcode('weekly-message', 'weekly_message_link');
 
