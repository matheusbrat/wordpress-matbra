<?PHP
/*
Plugin Name: DisableSmartQuotesPlugin
Plugin URI: http://www.fayazmiraz.com/disable-auto-curly-quotes-in-wordpress/
Description:  WordPress Plugin to Disable auto curly quote conversion in post content, comment content and post excerpt
Version: 1.0
Author:  Use Your Name
Author URI:
*/
remove_filter('the_content', 'wptexturize');

remove_filter('the_excerpt', 'wptexturize');

remove_filter('comment_text', 'wptexturize');