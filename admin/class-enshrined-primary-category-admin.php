<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://enshrined.co.uk
 * @since      1.0.0
 *
 * @package    Enshrined_Primary_Category
 * @subpackage Enshrined_Primary_Category/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Enshrined_Primary_Category
 * @subpackage Enshrined_Primary_Category/admin
 * @author     Daryll Doyle <daryll@enshrined.co.uk>
 */
class Enshrined_Primary_Category_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of this plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

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
		 * defined in Enshrined_Primary_Category_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Enshrined_Primary_Category_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/enshrined-primary-category-admin.css',
			array(), $this->version, 'all' );

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
		 * defined in Enshrined_Primary_Category_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Enshrined_Primary_Category_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/enshrined-primary-category-admin.js',
			array( 'jquery' ), $this->version, false );
		// We cast to an object so that JS handles it properly if empty
		$chosen_cats = (object) $this->get_chosen_categories();
		wp_localize_script( $this->plugin_name, 'enshrined_primary_categories', $chosen_cats );
		wp_enqueue_script( $this->plugin_name );
	}

	/**
	 * Add our metabox to the post type
	 *
	 * @since   1.0.0
	 */
	public function add_meta_box() {

		add_meta_box(
			'enshrined-primary-category-meta-box',
			__( 'Primary Category', 'enshrined-primary-category' ),
			array( $this, 'render_enshrined_primary_category_meta_box' ),
			'post',
			'side',
			'default'
		);

	}

	/**
	 * Render our category meta box
	 *
	 * @since   1.0.0
	 */
	public function render_enshrined_primary_category_meta_box() {
		global $post;
		$enshrined_chosen_categories = $this->get_chosen_categories();
		$primary_cat = get_post_meta( $post->ID, $this->plugin_name, true );

		wp_nonce_field( basename( __FILE__ ), 'enshrined_primary_category_nonce' );
		include plugin_dir_path( __FILE__ ) . 'partials/enshrined-primary-category-admin-meta-box.php';
	}

	/**Get the categories for this post
	 *
	 * @since   1.0.0
	 *
	 * @return array
	 */
	public function get_chosen_categories() {
		global $post;
		$post_categories = wp_get_post_categories( $post->ID );
		$cats            = array();

		foreach ( $post_categories as $c ) {
			$cat    = get_category( $c );
			$cats[ $cat->cat_ID ] = array( 'name' => $cat->name, 'id' => $cat->cat_ID );
		}

		return $cats;
	}

	/**
	 * Store our primary post data
	 *
	 * @since   1.0.0
	 *
	 * @param int $post_id The post ID.
	 */
	public function save_post_data( $post_id ) {

		// Verify meta box nonce
		if ( !isset( $_POST['enshrined_primary_category_nonce'] ) || !wp_verify_nonce( $_POST['enshrined_primary_category_nonce'], basename( __FILE__ ) ) ){
			return;
		}

		// Check user permissions
		if ( ! current_user_can( 'edit_post', $post_id ) ){
			return;
		}

		// Lets save the post
		if( isset( $_POST['enshrined_primary_category'] ) ) {
			// This will only ever be an int so it's safe to cast to one
			update_post_meta( $post_id, $this->plugin_name, (int) $_POST['enshrined_primary_category'] );
		}
	}
}
