<?php

//<fb:like href="http://faddd.com" send="true" layout="button_count" width="450" show_faces="true" action="recommend" font="tahoma"></fb:like>

function like_btn_display($href=''){
    $cfb_like = get_option('cfb_like');
    $txt = "<div id='cfb_like' style=\"".$cfb_like['css']."\">";

	if($href == "") {
		$prm_link = wp_get_shortlink(); // better than permalink because doesn't affect if link structure changed
    
    	if($prm_link == "") 
    		$prm_link = get_permalink();
	} else { 
		$prm_link = $href;
	}
    
	//Placing share button if not excluded
	if($cfb_like['share_off'] != 'on'){
		$mk = file_get_contents('http://graph.facebook.com/'.$prm_link);
    		$fook = json_decode($mk);
		$txt .= "<img onclick='facebook_share();' style='' src='".WP_PLUGIN_URL."/complete-facebook/fbshare.jpg'/>    <span style='background: white;'><small>  $fook->shares</small>   </span>";
  
	}
	

	//Placing like button	
    $txt .= "<fb:like href=\"".$prm_link."\" send=\"".($cfb_like['send'] == 'on' ? "true" : "false")."\" ";
    $txt .= ($cfb_like['layout'] != "standard" ? "layout=\"".$cfb_like['layout']."\" " : "");
    $txt .= "show_faces=\"".($cfb_like['faces'] == 'on' ? "true" : "false")."\" ";
    $txt .= ($cfb_like['verb'] != "like" ? "action=\"".$cfb_like['verb']."\" " : "");
    $txt .= "font=\"".$cfb_like['font']."\"></fb:like></div><br/>";
	
    return $txt;
}

function like_dis_after_title_check($content) {
	global $post;
	$mtx = get_post_meta($post->ID, 'wfbcomments_CFB', false);
	$like_optn = get_option('cfb_like');
	$fxdm = $like_optn['option'];
	if($mtx[0]['add_like_btn'] == "yes" || $fxdm == "en".$post->post_type) {
	    return like_dis_after_title($content);
	}
	return $content;
}

function like_dis_after_content_check($content){
	global $post;
	$mtx = get_post_meta($post->ID, 'wfbcomments_CFB', false);
	$like_optn = get_option('cfb_like');
	$fxdm = $like_optn['option'];
	if($mtx[0]['add_like_btn'] == "yes" || $fxdm == "en".$post->post_type) {
	    return like_dis_after_content($content);
	}
	return $content;
}

function like_dis_after_tags_check($comments){
	global $post;
	$mtx = get_post_meta($post->ID, 'wfbcomments_CFB', false);
	$like_optn = get_option('cfb_like');
	$fxdm = $like_optn['option'];
	if($mtx[0]['add_like_btn'] == "yes" || $fxdm == "en".$post->post_type) {
	    return like_dis_after_tags($content);
	}
	return $comments;
}

function like_dis_after_title($content) {
    return like_btn_display().$content;
}

function like_dis_after_content($content){

    return $content.like_btn_display();
}

function like_dis_after_tags($comments){
    
    echo like_btn_display();
    return $comments;
}