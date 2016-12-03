<?php

/**
 * Advanced Ads.
 *
 * @package   Advanced_Ads_Admin
 * @author    Thomas Maier <thomas.maier@webgilde.com>
 * @license   GPL-2.0+
 * @link      http://webgilde.com
 * @copyright 2013-2015 Thomas Maier, webgilde GmbH
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * @package Advanced_Ads_Admin
 * @author  Thomas Maier <thomas.maier@webgilde.com>
 */
class Advanced_Ads_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Instance of admin notice class.
	 *
	 * @since    1.5.2
	 * @var      object
	 */
	protected $notices = null;

	/**
	 * Slug of the settings page
	 *
	 * @since    1.0.0
	 * @var      string
	 */
	public $plugin_screen_hook_suffix = null;

	/**
	 * general plugin slug
	 *
	 * @since   1.0.0
	 * @var     string
	 */
	protected $plugin_slug = '';


	


	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			new Advanced_Ads_Ad_Ajax_Callbacks;
		} else {
			add_action( 'plugins_loaded', array( $this, 'wp_plugins_loaded' ) );
		}
		// add shortcode creator to TinyMCE
		Advanced_Ads_Shortcode_Creator::get_instance();

	}

	public function wp_plugins_loaded() {
		/*
         * Call $plugin_slug from public plugin class.
         *
         */
		$plugin = Advanced_Ads::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();
		

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ), 9 );

		// update placements
		add_action( 'admin_init', array('Advanced_Ads_Placements', 'update_placements') );
		// check for add-on updates
		add_action( 'admin_init', array($this, 'add_on_updater'), 1 );
		
		// check for update logic
		add_action( 'admin_notices', array($this, 'admin_notices') );
		

		// set 1 column layout on overview page as user and page option
		add_filter( 'screen_layout_columns', array('Advanced_Ads_Overview_Widgets_Callbacks', 'one_column_overview_page') );
		add_filter( 'get_user_option_screen_layout_toplevel_page_advanced', array( 'Advanced_Ads_Overview_Widgets_Callbacks', 'one_column_overview_page_user') );
		
		// add links to plugin page
		add_filter( 'plugin_action_links_' . ADVADS_BASE, array( $this, 'add_plugin_links' ) );
		// display information when user is going to disable the plugin
		// add_filter( 'after_plugin_row_' . ADVADS_BASE, array( $this, 'display_deactivation_message' ) );
		Advanced_Ads_Admin_Meta_Boxes::get_instance();
		Advanced_Ads_Admin_Menu::get_instance();
		Advanced_Ads_Admin_Ad_Type::get_instance();
		Advanced_Ads_Admin_Settings::get_instance();
	}
	
	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 */
	public function enqueue_admin_styles() {
		wp_enqueue_style( $this->plugin_slug . '-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), ADVADS_VERSION );
		if( self::screen_belongs_to_advanced_ads() ){
			// jQuery ui smoothness style 1.11.4
			wp_enqueue_style( $this->plugin_slug . '-jquery-ui-styles', plugins_url( 'assets/jquery-ui/jquery-ui.min.css', __FILE__ ), array(), '1.11.4' );
		}
		//wp_enqueue_style( 'jquery-style', '//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css' );
	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		// global js script
		wp_enqueue_script( $this->plugin_slug . '-admin-global-script', plugins_url( 'assets/js/admin-global.js', __FILE__ ), array('jquery'), ADVADS_VERSION );

		if( self::screen_belongs_to_advanced_ads() ){
		    wp_register_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery', 'jquery-ui-autocomplete' , 'jquery-ui-button' ), ADVADS_VERSION );
		    wp_register_script( $this->plugin_slug . '-wizard-script', plugins_url( 'assets/js/wizard.js', __FILE__ ), array('jquery'), ADVADS_VERSION );

		    // jquery ui
		    wp_enqueue_script( 'jquery-ui-accordion' );
		    wp_enqueue_script( 'jquery-ui-button' );
		    wp_enqueue_script( 'jquery-ui-tooltip' );

		    // just register this script for later inclusion on ad group list page
		    wp_register_script( 'inline-edit-group-ads', plugins_url( 'assets/js/inline-edit-group-ads.js', __FILE__ ), array('jquery'), ADVADS_VERSION );
		    
		    // register admin.js translations
		    $translation_array = array(
			    'condition_or' => __( 'or', 'advanced-ads' ),
			    'condition_and' => __( 'and', 'advanced-ads' ),
			    'after_paragraph_promt' => __( 'After which paragraph?', 'advanced-ads' ),
		    );
		    wp_localize_script( $this->plugin_slug . '-admin-script', 'advadstxt', $translation_array );
		    
		    wp_enqueue_script( $this->plugin_slug . '-admin-script' );
		    wp_enqueue_script( $this->plugin_slug . '-wizard-script' );
		}

		//call media manager for image upload only on ad edit pages
		$screen = get_current_screen();
		if( isset( $screen->id ) && Advanced_Ads::POST_TYPE_SLUG === $screen->id ) {
			// the 'wp_enqueue_media' function can be executed only once and should be called with the 'post' parameter
			// in this case, the '_wpMediaViewsL10n' js object inside html will contain id of the post, that is necessary to view oEmbed priview inside tinyMCE editor.
			// since other plugins can call the 'wp_enqueue_media' function without the 'post' parameter, Advanced Ads should call it earlier.
			global $post;
			wp_enqueue_media( array( 'post' => $post ) );
		}

	}

	/**
	 * check if the current screen belongs to Advanced Ads
	 *
	 * @since 1.6.6
	 * @return bool true if screen belongs to Advanced Ads
	 */
	static function screen_belongs_to_advanced_ads(){

		if( ! function_exists( 'get_current_screen' ) ){
		    return false;
		}
		
		$screen = get_current_screen();
		//echo $screen->id;
		if( !isset( $screen->id ) ) {
			return false;
		}

		$advads_pages = apply_filters( 'advanced-ads-dashboard-screens', array(
			'advanced-ads_page_advanced-ads-groups', // ad groups
			'edit-advanced_ads', // ads overview
			'advanced_ads', // ad edit page
			'advanced-ads_page_advanced-ads-placements', // placements
			'advanced-ads_page_advanced-ads-settings', // settings
			'toplevel_page_advanced-ads', // overview
			'admin_page_advanced-ads-debug', // debug
			'advanced-ads_page_advanced-ads-support', // support
			'admin_page_advanced-ads-intro', // intro
			'admin_page_advanced-ads-import-export', // import & export
		));

		if( in_array( $screen->id, $advads_pages )){
			return true;
		}

		return false;
	}


	/**
	 * get action from the params
	 *
	 * @since 1.0.0
	 */
	public function current_action() {
		if ( isset($_REQUEST['action']) && -1 != $_REQUEST['action'] ) {
			return $_REQUEST['action'];
		}

		return false;
	}

        
    /**
     *  get DateTimeZone object for the WP installation
     */
    public static function get_wp_timezone() {
        $_time_zone = get_option( 'timezone_string' );
        $time_zone = new DateTimeZone( 'UTC' );
        if ( $_time_zone ) {
            $time_zone = new DateTimeZone( $_time_zone );
        } else {
            $gmt_offset = floatval( get_option( 'gmt_offset' ) );
            $sign = ( 0 > $gmt_offset )? '-' : '+';
            $int = floor( abs( $gmt_offset ) );
            $frac = abs( $gmt_offset ) - $int;
            
            $gmt = '';
            if ( $gmt_offset ) {
                $gmt .= $sign . zeroise( $int, 2 ) . ':' . zeroise( 60 * $frac, 2 );
                $time_zone = date_create( '2017-10-01T12:00:00' . $gmt )->getTimezone();
            }
            
        }
        return $time_zone;
    }
    
    /**
     *  get literal expression of timezone
     */
    public static function timezone_get_name( $DTZ ) {
        if ( $DTZ instanceof DateTimeZone ) {
            $TZ = timezone_name_get( $DTZ );
            if ( 'UTC' == $TZ ) {
                return 'UTC+0';
            }
            if ( false === strpos( $TZ, '/' ) ) {
                $TZ = 'UTC' . $TZ;
            } else {
                $TZ = sprintf( __( 'time of %s', 'advanced-ads' ), $TZ );
            }
            return $TZ;
        }
        return 'UTC+0';
    }

	/**
	 * initiate the admin notices class
	 *
	 * @since 1.5.3
	 */
	public function admin_notices(){
		// display ad block warning to everyone who can edit ads
		if( current_user_can( Advanced_Ads_Plugin::user_cap( 'advanced_ads_edit_ads') ) ) {
			if ( $this->screen_belongs_to_advanced_ads() ){
				include ADVADS_BASE_PATH . 'admin/views/notices/adblock.php';
				include ADVADS_BASE_PATH . 'admin/views/notices/jqueryui_error.php';
			}
		}
		
		if( current_user_can( Advanced_Ads_Plugin::user_cap( 'advanced_ads_edit_ads') ) ) {
			$this->notices = Advanced_Ads_Admin_Notices::get_instance()->notices;
			Advanced_Ads_Admin_Notices::get_instance()->display_notices();
		}
	}

	/**
	 * save license key
	 *
	 * @since 1.2.0
	 * @param string $addon string with addon identifier
	 */
	public function activate_license( $addon = '', $plugin_name = '', $options_slug = '', $license_key = '' ) {

		if ( '' === $addon || '' === $plugin_name || '' === $options_slug ) {
			return __( 'Error while trying to register the license. Please contact support.', 'advanced-ads' );
		}
		
		$license_key = esc_attr( trim( $license_key ) );
		if ( '' == $license_key ) {
			return __( 'Please enter a valid license key', 'advanced-ads' );
		}
		
		if ( has_filter( 'advanced_ads_license_'. $options_slug ) ) {
			return apply_filters( 'advanced_ads_license_' . $options_slug, false, __METHOD__, $plugin_name, $options_slug, $license_key );
		}
		
		// check if license was already activated and abort activation if so
		/*if( $this->check_license($license_key, $plugin_name, $options_slug)){
		    return 1;
		}*/
		
		$api_params = array(
			'edd_action'=> 'activate_license',
			'license' 	=> $license_key,
			'item_name' => urlencode( $plugin_name ),
			'url'       => home_url()
		);
		// Call the custom API.
		$response = wp_remote_post( ADVADS_URL, array(
			'timeout'   => 15,
			'sslverify' => false,
			'body'      => $api_params
		) );
		
		if ( is_wp_error( $response ) ) {
			$body = wp_remote_retrieve_body( $response );
			if ( $body ){
			    return $body;
			} else {
			    // return print_r($response, true);
			    return __( 'License couldn’t be activated. Please try again later.', 'advanced-ads' );
			}
		}		

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
		// save license status
		update_option($options_slug . '-license-status', $license_data->license, false);
		if( !empty( $license_data->expires ) ){
			update_option($options_slug . '-license-expires', $license_data->expires, false); 
		}		

		// display activation problem
		if( !empty( $license_data->error )) {
		    // user friendly texts for errors
		    $errors = array(
			'license_not_activable' => __( 'This is the bundle license key.', 'advanced-ads' ),
			'item_name_mismatch' => __( 'This is not the correct key for this add-on.', 'advanced-ads' ),
			'no_activations_left' => __( 'There are no activations left.', 'advanced-ads' )
		    );
		    $error = isset( $errors[ $license_data->error ] ) ? $errors[ $license_data->error ] : $license_data->error;
		    if( 'expired' === $license_data->error ){
			return 'ex';
		    } else {
			if( isset($errors[ $license_data->error ] ) ) {
			    return $error;
			} else {
			    return sprintf( __('License is invalid. Reason: %s'), $error);
			}
		    }
		} else {
		    // reset license_expires admin notification
		    Advanced_Ads_Admin_Notices::get_instance()->remove_from_queue( 'license_expires' );
		    Advanced_Ads_Admin_Notices::get_instance()->remove_from_queue( 'license_expired' );
		    Advanced_Ads_Admin_Notices::get_instance()->remove_from_queue( 'license_invalid' );
		    // save license key
		    $licenses = $this->get_licenses();		    
		    $licenses[ $addon ] = $license_key;
		    $this->save_licenses( $licenses );
		}

		return 1;
	}
	
	/**
	 * check if a specific license key was already activated for the current page
	 * 
	 * @since 1.6.17
	 * @return bool true if already activated
	 * @deprecated since version 1.7.2 because it only checks if a key is valid, not if the url registered with that key
	 */
	public function check_license( $license_key = '', $plugin_name = '', $options_slug = '' ){
	    
		if ( has_filter( 'advanced_ads_license_'. $options_slug ) ) {
			return apply_filters( 'advanced_ads_license_' . $options_slug, false, __METHOD__, $plugin_name, $options_slug, $license_key );
		}
	    
		$api_params = array(
			'edd_action' => 'check_license',
			'license' => $license_key,
			'item_name' => urlencode( $plugin_name )
		);
		$response = wp_remote_get( add_query_arg( $api_params, ADVADS_URL ), array( 'timeout' => 15, 'sslverify' => false ) );
		if ( is_wp_error( $response ) ) {
			return false;
		}
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
		
		// if this license is still valid
		if( $license_data->license == 'valid' ) {
			update_option($options_slug . '-license-expires', $license_data->expires, false);
			update_option($options_slug . '-license-status', $license_data->license, false);
			
			return true;
		}
		return false;
	}	
	
	/**
	 * deactivate license key
	 *
	 * @since 1.6.11
	 * @param string $addon string with addon identifier
	 */
	public function deactivate_license( $addon = '', $plugin_name = '', $options_slug = '' ) {

		if ( '' === $addon || '' === $plugin_name || '' === $options_slug ) {
			return __( 'Error while trying to disable the license. Please contact support.', 'advanced-ads' );
		}		

		$licenses = $this->get_licenses();
		$license_key = isset($licenses[$addon]) ? $licenses[$addon] : '';

		if ( has_filter( 'advanced_ads_license_'. $options_slug ) ) {
			return apply_filters( 'advanced_ads_license_' . $options_slug, false, __METHOD__, $plugin_name, $options_slug, $license_key );
		}

		$api_params = array(
			'edd_action' => 'deactivate_license',
			'license'    => $license_key,
			'item_name'  => urlencode( $plugin_name )
		);
		// Send the remote request
		$response = wp_remote_post( ADVADS_URL, array( 
		    'body' => $api_params, 
		    'timeout' => 15,
		    'sslverify' => false,
		) );
		
		if ( is_wp_error( $response ) ) {
			$body = wp_remote_retrieve_body( $response );
			if ( $body ){
			    return $body;
			} else {
			    return __( 'License couldn’t be deactivated. Please try again later.', 'advanced-ads' );
			}
		}

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
		
		// save license status

		// remove data
		if( 'deactivated' === $license_data->license ) {
		    delete_option( $options_slug . '-license-status' );
		    delete_option( $options_slug . '-license-expires' );
		    Advanced_Ads_Admin_Notices::get_instance()->remove_from_queue( 'license_expires' );
		} elseif( 'failed' === $license_data->license ) {
		    update_option($options_slug . '-license-expires', $license_data->expires, false);
		    update_option($options_slug . '-license-status', $license_data->license, false);
		    return 'ex';
		} else {
		    return __( 'License couldn’t be deactivated. Please try again later.', 'advanced-ads' );
		}

		return 1;
	}
	
	/**
	 * get license keys for all add-ons
	 * 
	 * @since 1.6.15
	 * @return arr $licenses licenses
	 */
	public function get_licenses(){
	    
	    $licenses = array();
	    
	    if( is_multisite() ){
		    // if multisite, get option from main blog
		    global $current_site;
		    $licenses = get_blog_option( $current_site->blog_id, ADVADS_SLUG . '-licenses', array() );
		    
	    } else {
		    $licenses = get_option( ADVADS_SLUG . '-licenses', array() );
	    }
	    
	    return $licenses;
	}
	
	/**
	 * save license keys for all add-ons
	 * 
	 * @since 1.7.2
	 * @return arr $licenses licenses
	 */
	public function save_licenses( $licenses = array() ){
	    
	    if( is_multisite() ){
		    // if multisite, get option from main blog
		    global $current_site;
		    update_blog_option( $current_site->blog_id, ADVADS_SLUG . '-licenses', $licenses );
	    } else {
		    update_option( ADVADS_SLUG . '-licenses', $licenses );
	    }
	}
	
	/**
	 * get license status of an add-on
	 * 
	 * @since 1.6.15
	 * @param  str $slug slug of the add-on
	 * @return str $status license status, e.g. "valid" or "invalid"
	 */
	public function get_license_status( $slug = '' ){
	    
	    $status = false;
	    
	    if( is_multisite() ){
		    // if multisite, get option from main blog
		    global $current_site;
		    $status = get_blog_option( $current_site->blog_id, $slug . '-license-status', false);
	    } else {
		    $status = get_option( $slug . '-license-status', false);
	    }
	    
	    return $status;
	}
	
	/**
	 * get license expired value of an add-on
	 * 
	 * @since 1.6.15
	 * @param  str $slug slug of the add-on
	 * @return str $date expiry date of an add-on
	 */
	public function get_license_expires( $slug = '' ){
	    
	    $date = false;
	    
	    if( is_multisite() ){
		    // if multisite, get option from main blog
		    global $current_site;
		    $date = get_blog_option( $current_site->blog_id, $slug . '-license-expires', false);
	    } else {
		    $date = get_option( $slug . '-license-expires', false);
	    }
	    
	    return $date;
	}
	
	
	/*
         * add-on updater
	 *
	 * @since 1.5.7
         */
        public function add_on_updater(){
	    
		// ignore, if not main blog or is ajax
		if( ( is_multisite() && ! is_main_site() ) ){
		    return;
		}

		/**
		 * list of registered add ons
		 * contains:
		 *	    name
		 *	    version
		 *	    path
		 *	    options_slug
		 *	    short option slug (=key)
		 */
		$add_ons = apply_filters( 'advanced-ads-add-ons', array() );

		if( $add_ons === array() ) {
		    return;
		}
		
		// load license keys
		$licenses = get_option(ADVADS_SLUG . '-licenses', array());

		foreach( $add_ons as $_add_on_key => $_add_on ){

			// check if a license expired over time
			$expiry_date = $this->get_license_expires( $_add_on['options_slug'] );
			$now = time();
			if( $expiry_date && 'lifetime' !== $expiry_date && strtotime( $expiry_date ) < $now ){
				// remove license status
				delete_option( $_add_on['options_slug'] . '-license-status' );
				continue;
			}

			// check status
			if( $this->get_license_status( $_add_on['options_slug'] ) !== 'valid' ) {
				continue;
			}

			// retrieve our license key
			$license_key = isset($licenses[$_add_on_key]) ? $licenses[$_add_on_key] : '';

			// setup the updater
			if( $license_key ){
			    
				// register filter to set EDD transient to 86,400 seconds (day) instead of 3,600 (hours)
				$slug	    = basename( $_add_on['path'], '.php' );
				$transient_key = md5( serialize( $slug . $license_key ) );
				
				add_filter( 'expiration_of_transient_' . $transient_key, array( $this, 'set_expiration_of_update_transient' ) );
				
				new EDD_SL_Plugin_Updater( ADVADS_URL, $_add_on['path'], array(
					'version' 	=> $_add_on['version'],
					'license' 	=> $license_key,
					'item_name' => $_add_on['name'],
					'author' 	=> 'Thomas Maier'
				    )
				);
			}
		}
        }
	
	/**
	 * set the expiration of the updater transient key to 1 day instead of 1 hour to prevent too many update checks
	 */
	public function set_expiration_of_update_transient( $expiration ){

		return 86400;
	}
	
	/**
	 * add links to the plugins list
	 *
	 * @since 1.6.14
	 * @param arr $links array of links for the plugins, adapted when the current plugin is found.
	 * @param str $file  the filename for the current plugin, which the filter loops through.
	 * @return array $links
	 */
	function add_plugin_links( $links ) {
		// add link to settings
		//$settings_link = '<a href="' . admin_url( 'admin.php?page=advanced_ads&page=advanced-ads-settings' ) . '">' . __( 'Settings', 'advanced-ads' ) . '</a>';
		//array_unshift( $links, $settings_link );

		// add link to support page
		$support_link = '<a href="' . esc_url( admin_url( 'admin.php?page=advanced-ads-support' ) ) . '">' . __( 'Support', 'advanced-ads' ) . '</a>';
		array_unshift( $links, $support_link );

		// add link to add-ons
		$extend_link = '<a href="' . ADVADS_URL . 'add-ons/#utm_source=advanced-ads&utm_medium=link&utm_campaign=plugin-page" target="_blank">' . __( 'Add-Ons', 'advanced-ads' ) . '</a>';
		array_unshift( $links, $extend_link );
		
		return $links;
	}
	
	/**
	 * display message when someone is going to disable the plugin
	 * 
	 * @since 1.6.14
	 */
	function display_deactivation_message(){
	    
		// get email address
		$current_user = wp_get_current_user();
		if ( !($current_user instanceof WP_User) ){
		    $email = '';
		} else {
		    $email = trim( $current_user->user_email );
		}
		
		include ADVADS_BASE_PATH . 'admin/views/feedback_disable.php';
	}



}
