<?php
/*
Plugin Name: Complete Facebook
Plugin URI: http://www.matbra.com/en/code/complete-facebook-wordpress-plugin/
Description: Enable Facebook comment/like/send/share, send you notifcations (enable/disable), add OpenGraph metadata, creates a Like Button Widget.
Version: 1.5.1
Author: Matheus Bratfisch
Author URI: http://www.matbra.com
*/

include('metabox.php');
include('admin_settings.php');
include('like_process.php'); // should be included before 'comment_process.php'
include('comment_process.php');
include('notification.php');
include('fb-widget.php');
include("opengraph.php");

// ADD LINK
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'settings_linkCFB' );
function settings_linkCFB($x) {
  $settings_link = '<a href="options-general.php?page=wp-facebook-comments">Settings</a>';
  array_unshift($x, $settings_link);
  return $x;
}

// ADD QUERY VARS
function cfb_plugin_query_vars($vars) {
    $vars[] = 'cfb';
    return $vars;
}
add_filter('query_vars', 'cfb_plugin_query_vars');

function cfb_parse_request($wp) {
    // only process requests with "my-plugin=ajax-handler"
    if (array_key_exists('cfb', $wp->query_vars) 
            && $wp->query_vars['cfb'] == 'ajax-handler') {
		cfb_send_notification();
        die();
    }
}
add_action('parse_request', 'cfb_parse_request');

// ADD XMLNS
function cfb_add_xmlns($attrb) { 
	$cfbglb = get_option('cfb_global');
	if($cfbglb['includesdk'] == 'on' ){
	   	echo " xmlns:fb=\"http://ogp.me/ns/fb#\" ";
    }
    return $attrb;
}
add_filter('language_attributes', 'cfb_add_xmlns');

// INIT add_action to COMMENT and LIKE
function cfb_init() {
	$gl_optn = get_option('cfb_global');
	$cm_optn = get_option('cfb_com');
	if( is_singular()) { // if its a post, page or attachment page, unless do nothing
		$fxd = $cm_optn['option'];
		global $post;
		$mtx = get_post_meta($post->ID, 'wfbcomments_CFB', false);
		
        // COMMENTS
		// if not disable for all post 
		// then check if enable for all or enable for that post type or enable in individual setting
		// if comes this far, do what needs to be done
		if($fxd != "disable_all" && ($fxd == "enable_all" || $fxd == "en".$post->post_type || $mtx[0]['enable_fb_coms'] == "yes")) {
			if($cm_optn['pos'] == "before_wp" ) {
				add_filter('comments_array', 'add_comment_boxwrapper_for_filter', 12 );
			} else if ($cm_optn['pos'] == "after_wp") {
					add_action('comment_form_before', 'add_comment_boxCFB');
			} else if ($cm_optn['pos'] == "after_form") {
					add_action('comment_form_after', 'add_comment_boxCFB');
			}
		}

		// LIKES
        $like_optn = get_option('cfb_like');
        $fxdm = $like_optn['option'];
        if($fxdm != "disable_all" && ($fxdm == "enable_all" || $fxdm == "en".$post->post_type || $mtx[0]['add_like_btn'] == "yes")) {
            if($like_optn['pos'] == "after_title")
                    add_filter('the_content', 'like_dis_after_title', 100); // very last
             else if($like_optn['pos'] == "after_content")
                     add_filter('the_content', 'like_dis_after_content', 1); // immediately after content
              else if($like_optn['pos'] == "after_tags")
                      add_filter('comments_array', 'like_dis_after_tags', 1);
        
        }
		     
    } else if ($gl_optn['all'] == "on") { 
    	$like_optn = get_option('cfb_like');
        $fxdm = $like_optn['option'];
        if($fxdm != "disable_all") { 
        	if($fxdm == "enable_all") { 
		    	if($like_optn['pos'] == "after_title")
		        	add_filter('the_content', 'like_dis_after_title', 100); // very last
		        else if($like_optn['pos'] == "after_content")
		         	add_filter('the_content', 'like_dis_after_content', 1); // immediately after content
				else if($like_optn['pos'] == "after_tags")
		         	add_filter('comments_array', 'like_dis_after_tags', 1);
        	} else { 
        		if($like_optn['pos'] == "after_title")
		        	add_filter('the_content', 'like_dis_after_title_check', 100); // very last
		        else if($like_optn['pos'] == "after_content")
		         	add_filter('the_content', 'like_dis_after_content_check', 1); // immediately after content
				else if($like_optn['pos'] == "after_tags")
		         	add_filter('comments_array', 'like_dis_after_tags_check', 1);
        	}
        }
    }
    
    $og_optn = get_option('cfb_og');
    if($og_optn['eog'] == "on") { 
    	echo cfb_openGraphMeta();
    }
    
    if(strlen($cm_optn['mods']) > 0)
    	echo add_modCFB();
		
    if($gl_optn['includesdk'] == 'on' ){
    	add_action('wp_footer', 'fbsdk_includeCFB');
    }
	
	if($like_optn['share_off'] != 'on'){
		add_action('wp_footer', 'facebook_share', 15);
	}	
    
}   
add_action('wp_head', 'cfb_init');

$cfb_sdk_included = false;
function fbsdk_includeCFB(){
	global $cfb_sdk_included;
	if(!$cfb_sdk_included) { 
			$cfb_sdk_included = true;
		    $cfbglb = get_option('cfb_global');
		    $cfb_not = get_option('cfb_not');
		    echo  "
		 <div id=\"fb-root\"></div>
		<script>
		
		window.fbAsyncInit = function() {
		    FB.init({
		      appId      : '" . $cfbglb['appid'] . "', // App ID
		      //channelUrl : '//WWW.YOUR_DOMAIN.COM/channel.html', // Channel File
		      status     : true, // check login status
		      cookie     : true, // enable cookies to allow the server to access the session
		      xfbml      : true  // parse XFBML
		    });";
		
		
		
		    // Additional initialization code here
		    if($cfb_not['ec'] == "on") { 
		    	
				echo "
		FB.Event.subscribe('comment.create', function (response) {
		        jQuery.ajax({
		url: '" . get_bloginfo("wpurl") . "/?cfb=ajax-handler',
		data: 't=comment&h=' + response.href,
		//success: function (data) { alert(response.href); },
		//error: function (data) { alert(response.href); }
		});
		    });";
			
		    }
		    
			if($cfb_not['el'] == "on") {
				
				echo "
		FB.Event.subscribe('edge.create', function (response) {
		        jQuery.ajax({
		url: '" . get_bloginfo("wpurl") . "/?cfb=ajax-handler',
		data: 't=like&h=' + response,
		//success: function (data) { alert(response); },
		//error: function (data) { alert(response); }
		});
		    });";
				
			}
		 	
			if ($cfbglb["lang"] == "")  
				$lang = "en_US";
			else 
			 	$lang = $cfbglb["lang"];
			 	
			echo "};
			
		(function(d){
		     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
		     if (d.getElementById(id)) {return;}
		     js = d.createElement('script'); js.id = id; js.async = true;
		     js.src = '//connect.facebook.net/" . $lang . "/all.js';
		     ref.parentNode.insertBefore(js, ref);
		   }(document));</script>";
		
		
		 echo "<!-- added by complete facebook plugin -->";
	}
}
?>