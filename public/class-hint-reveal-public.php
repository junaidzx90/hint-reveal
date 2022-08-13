<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Hint_Reveal
 * @subpackage Hint_Reveal/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Hint_Reveal
 * @subpackage Hint_Reveal/public
 * @author     Developer Junayed <admin@easeare.com>
 */
class Hint_Reveal_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_shortcode( "hint_reveal", [$this, "hint_reveal_callback"] );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Hint_Reveal_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Hint_Reveal_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/hint-reveal-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Hint_Reveal_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Hint_Reveal_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/hint-reveal-public.js', array( 'jquery' ), $this->version, true );

	}

	function hint_reveal_callback($atts){
		if(!isset($atts['id'])){
			return;
		}
		wp_localize_script( $this->plugin_name, "hintreveal", array(
			'ajaxurl' => admin_url( "admin-ajax.php" ),
			'nonce'	=> wp_create_nonce( "hreveal_nonce" ),
		) );


		ob_start();
		require plugin_dir_path( __FILE__ )."partials/hint-reveal-public-display.php";
		return ob_get_clean();
	}

	function get_public_reveal_data(){
		if(!wp_verify_nonce( $_GET['nonce'], "hreveal_nonce" )){
			echo json_encode(array("error" => "Invalid Request"));
		}
		global $wpdb;

		if(isset($_GET['id'])){
			$id = intval($_GET['id']);
			$result = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}hint_reveal WHERE ID = $id");

			if($result){
				echo json_encode(array('success' => unserialize($result->data)));
			}

			die;
		}

		die;
	}
}
