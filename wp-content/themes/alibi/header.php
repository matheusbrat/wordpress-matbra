<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php bloginfo('name'); ?> <?php wp_title(); ?></title>
	
<meta http-equiv="imagetoolbar" content="no" />

<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/style.css" type="text/css" media="screen" />

<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />

<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<style type="text/css" media="screen">
	
body{
	background: #aaa url("<?php bloginfo('template_directory'); ?>/images/bodybg.gif");
				}
	
	#header{
	background: #444 url("<?php bloginfo('template_directory'); ?>/images/headerbg.jpg") repeat-x;
				}
	
.menu ul li.widget ul li{
	 background: #f2f2f2 url("<?php bloginfo('template_directory'); ?>/images/listbullet.gif") no-repeat left top;

				}
				
		a, a:hover{
		color: <?php ap_themeColour(); ?>;
		}
		
		.menu ul li.widget h3 {
		border-top: 3px solid <?php ap_themeColour(); ?>;
		}
		
	#tabs {
		border-bottom: 3px solid <?php ap_themeColour(); ?>;
		}
				
	#tabs a {
      background: url("<?php bloginfo('template_directory'); ?>/images/tableft.gif") no-repeat left top;
      }
			
    #tabs a span {
      background: url("<?php bloginfo('template_directory'); ?>/images/tabright.gif") no-repeat right top;
}				
				
	</style>
	
		<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/scripts/utils.js"></script>
	
<?php wp_head(); ?>

</head>
<body>

<div id="wrapper">
<div id="wrapper2">
<div id="wrapper3">
<div id="wrapper4">

<div id="header">
	

	
		<form id="searchform2" method="get" action="<?php bloginfo('home'); ?>">
		
			<input type="text"  onfocus="doClear(this)" value="<?php _e('Search'); ?>" name="s" id="s" size="25" /> <input type="submit" value="<?php _e('Go'); ?>" />
			
		</form>
	
	
		<h3><a href="<?php bloginfo('home'); ?>/"><?php bloginfo('name'); ?></a></h3>

		
			<h2><?php bloginfo('description'); ?></h2>
		

</div>


<div id="tabs">


  	<ul>
			<?php echo buildMenu(); ?>
	 </ul>

</div>
		

