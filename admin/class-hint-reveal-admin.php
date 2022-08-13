<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Hint_Reveal
 * @subpackage Hint_Reveal/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Hint_Reveal
 * @subpackage Hint_Reveal/admin
 * @author     Developer Junayed <admin@easeare.com>
 */
class Hint_Reveal_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/hint-reveal-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( 'hint-reveal-vue', plugin_dir_url( __FILE__ ) . 'js/hint-reveal-vue.js', array(  ), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/hint-reveal-admin.js', array( 'jquery', 'hint-reveal-vue' ), $this->version, true );
		wp_localize_script( $this->plugin_name, "hintreveal", array(
			'ajaxurl' => admin_url( "admin-ajax.php" ),
			'nonce'	=> wp_create_nonce( "hreveal_nonce" )
		) );

	}

	function admin_menu_allback(){
		add_menu_page( "Hint Reveal", "Hint Reveal", "manage_options", "hint-reveal", [$this, "hint_reveal_menu_page"], "dashicons-editor-help", 45 );
		add_submenu_page( "hint-reveal", "Add new", "Add new", "manage_options", "hintreveal-add-new", [$this, "add_new_hint_reveal"], null );
	}
	
	function hint_reveal_menu_page(){
		if(isset($_GET['page']) && $_GET['page'] === 'hint-reveal' && isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])){
			echo '<h3>Edit Raveal</h3><hr>';
			require_once plugin_dir_path( __FILE__ )."partials/add_new_hint_reveal_new.php";
		}else{
			$reveal = new Hint_Reveal_Table();
			?>
			<div class="wrap" id="reveal-table">
				<h3 class="heading3">Hint Reveal</h3>
				<hr>
				<form action="" method="post">
				<?php $reveal->prepare_items(); ?>
				<?php $reveal->display(); ?>
				</form>
			</div>
			<?php
		}
	}
	function add_new_hint_reveal(){
		echo '<h3>New Raveal</h3><hr>';
		require_once plugin_dir_path( __FILE__ )."partials/add_new_hint_reveal_new.php";
	}

	function get_reveal_data(){
		if(!wp_verify_nonce( $_GET['nonce'], "hreveal_nonce" )){
			echo json_encode(array("error" => "Invalid Request"));
		}
		global $wpdb;

		if(isset($_GET['id'])){
			$id = intval($_GET['id']);
			$result = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}hint_reveal WHERE ID = $id");

			if($result){
				echo json_encode(array(
					"title" => $result->title,
					'data' => unserialize($result->data)
				));
			}

			die;
		}

		die;
	}

	function save_hint_reveals(){
		if(!wp_verify_nonce( $_POST['nonce'], "hreveal_nonce" )){
			echo json_encode(array("error" => "Invalid Request"));
		}

		if(isset($_POST['title'])){
			global $wpdb;

			$title = stripslashes(sanitize_text_field( $_POST['title'] ));
			$data = ((isset($_POST['data']))?$_POST['data']:[]);

			if(isset($_POST['id']) && $_POST['id'] !== 'new'){
				$wpdb->update($wpdb->prefix.'hint_reveal', array(
					'title' => $title,
					'data' => serialize($data)
				), array("ID" => $_POST['id']), array("%s", "%s"), array("%d"));

				echo json_encode(array("success" => "Success"));
			}else{
				$wpdb->insert($wpdb->prefix.'hint_reveal', array(
					'title' => $title,
					'data' => serialize($data)
				));
				
				if($wpdb->insert_id){
					echo json_encode(array("redirect" => admin_url("admin.php?page=hint-reveal&action=edit&id=".$wpdb->insert_id)));
				}
			}
		}

		die;
	}
}
