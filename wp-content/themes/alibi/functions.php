<?php


	// Widgets
  if(function_exists('register_sidebar')) {
	register_sidebar(array(
		'name' => __('Sidebar'),
		
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',

	));
	
			
}

function buildMenu(){
	global $wpdb;
	
	$menu = '<li><a href="'.get_option('home').'/" title="Home"><span>Home</span></a></li>';
	
	$pages = $wpdb->get_results("SELECT ID, post_title as title, guid FROM $wpdb->posts WHERE post_type='page' AND post_status='publish' AND post_parent='0' ORDER BY menu_order");

	foreach ($pages as $page){
	  
 	$url = get_page_link($page->ID);
	
		$menu .=  sprintf('<li><a href="%s" title="'.__($page->title).'"><span>'.__($page->title).'</span></a></li>', $url);
	}
	return $menu;
}				

function ap_add_theme_page() {
	global $wpdb;

	$errorFlag = false;	
	if ($_GET['page'] == basename(__FILE__)) {
	
	    // save settings
		if ( 'save' == $_REQUEST['action'] ) {

			if (valid_colour($_REQUEST['ap_linkColour'])){
					update_option('ap_linkColour', $_REQUEST['ap_linkColour']);
			} else {
 				$errorFlag = true;
			}
			
	
							
			// goto theme edit page
			if($errorFlag){
					header("Location: themes.php?page=functions.php&error=true");
					die;
			} else {
					header("Location: themes.php?page=functions.php&saved=true");
					die;
			}
			
			
  		// reset defaults
		} else if('reset' == $_REQUEST['action']) {
			delete_option('ap_linkColour');
					
			header("Location: themes.php?page=functions.php&reset=true");
			die;

		}
	}

    add_theme_page(__('Alibi Theme Options'), __('Alibi Options'), 'edit_themes', basename(__FILE__), 'ap_theme_page');

}

function ap_theme_page() {

	global $wpdb;

	
 ?>

	<script language="javascript"><?php include(TEMPLATEPATH . '/scripts/picker.js'); ?></script>
	

<div class="wrap">


<?php if ($_REQUEST['saved'] ) echo '<div style="margin:10px 0;" id="message" class="updated fade"><p><strong>'.__('Settings saved.','').'</strong></p></div>';
	if ($_REQUEST['reset'] ) echo '<div style="margin:10px 0;" id="message" class="updated fade"><p><strong>'.__('Settings reset.','').'</strong></p></div>';
	if ($_REQUEST['error'] ) echo '<div style="margin:10px 0;" id="message" class="error fade"><p><strong>'.__('Error - invalid color code','').'</strong></p></div>';

 ?>


<h2><?php _e('Alibi Theme Options - 2 col', 'alibi') ;?></h2>

<p><?php _e('Alibi theme by <a href="http://www.blogchemistry.com/">BlogChemistry</a> - available in 2 and 3 column layout versions - otherwise the option is short and sweet...','alibi'); ?></p>

<form name="tcp" method="post">


<table width="100%" cellspacing="2" cellpadding="5" class="form-table">

<?php


		
		ap_th(__('Link Color','alibi'));
		
		$setLinkColour = get_settings('ap_linkColour');
		$valLinkColour = !empty($setLinkColour) ? $setLinkColour : '#FF0000';
		
		ap_input( 'ap_linkColour', 'text', '', $valLinkColour );

		echo ' <a href="javascript:TCP.popup(document.forms[\'tcp\'].elements[\'ap_linkColour\'])"><img width="15" height="13" border="0" alt="Pick a color" src="'. get_bloginfo('template_directory').'/images/sel.gif"></a><br /><small>6 figure hex code, with hash</small>';
		ap_cth();	
	
	
	
	
	
	
	
		
?>

</table>


<?php ap_input('save', 'submit', '', __('Save Settings','')); ?>


<input type="hidden" name="action" value="save" />

</form>



<form method="post">




<?php

	ap_input('reset', 'submit', '', __('Restore Default Settings'));
	
?>


<input type="hidden" name="action" value="reset" />

</form>

<?php
}

add_action('admin_menu', 'ap_add_theme_page');


function ap_input($var, $type, $description='', $value='', $selected='', $onchange='' ) {


 	echo "\n";
 	
	switch( $type ){
	
	    case "text":

	 		printf('<input name="%s" id="%s"  type="%s" style="width: 60%%" class="code" value="%s" onchange="%s" />', $var, $var, $type, $value, $onchange);
			 
			break;
			
		case "submit":
		
			printf('<p class="submit"><input name="%s" type="%s" value="%s" /><p>',  $var, $type, $value);

			break;

		case "option":
		
			if($selected == $value)  $extra = 'selected '; 

			printf('<option value="%s" %s>%s</option>', $value, $extra, $description);
		
		    break;
  		case "radio":
  		if($selected == $value)  $extra = 'checked '; 
  		
			printf('<label><input name="%s" id="%s" type="%s" value="%s" %s /> %s</label> &nbsp;', $var, $var, $type, $value, $extra, $description); 
 			
  			break;
  			
		case "checkbox":
		
			if($selected == $value)  $extra = 'checked '; 

				printf('<label><input name="%s" id="%s" type="%s" value="%s" %s /> %s</label><br/>', $var, $var, $type, $value, $extra, $description); 

  			break;

		case "textarea":
		
			printf('<textarea name="%s" id="%s" style="width: 60%%; height: 10em;" class="code"></textarea>',$var, $var, $value ); 
		
		    break;
	}

}

function ap_th( $title ) {


   	echo '<tr valign="top">';
		echo '<th align="right" width="33%" scope="row">'.$title.' </th>';
		echo '<td>';

}

function ap_cth() {

	echo '</td>';
	echo '</tr>';
	
}

function valid_colour($var){
	$regex = '^#([a-f]|[A-F]|[0-9]){6}^';
	return preg_match($regex,$var);
}
	
function ap_themeColour() {

	$tc =  get_settings('ap_linkColour');
	if (empty($tc)) $tc = '#FF0000';
	echo $tc;
	return NULL;
}
?>