<?php
class CFBWidget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'CFBWidget', // Base ID
			'Complete Facebook Widget', // Name
			array( 'description' => __( 'Show like and send button.', 'text_domain' ), ) // Args
		);
		if ( is_active_widget( false, false, $this->id_base, true ) ) {
			$cfbglb = get_option('cfb_global');
    		if($cfbglb['includesdk'] == 'on' ){
            	add_action('wp_footer', 'fbsdk_includeCFB');
        	}
		}
	}

	
	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo like_btn_display($instance["href"]);
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['href'] = strip_tags($new_instance['href']);
		
		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
			$title 			 	  = $instance[ 'title' ];
			$href 	 	 	  = $instance[ 'href' ];
			if($href == "") {
				$href = home_url();
			}
			
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e("Title:"); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr($title) ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id( 'href' ); ?>"><?php _e("Like href:"); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'username' ); ?>" name="<?php echo $this->get_field_name( 'href' ); ?>" type="text" value="<?php echo esc_attr($href); ?>" /></label></p>
		<?php 
	}
	

} 
add_action( 'widgets_init', create_function( '', 'register_widget( "CFBWidget" );' ) );

