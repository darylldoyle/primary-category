<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://enshrined.co.uk
 * @since      1.0.0
 *
 * @package    Enshrined_Primary_Category
 * @subpackage Enshrined_Primary_Category/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Enshrined_Primary_Category
 * @subpackage Enshrined_Primary_Category/public
 * @author     Daryll Doyle <daryll@enshrined.co.uk>
 */
class Enshrined_Primary_Category_Public {

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
	 * @param      string $plugin_name The name of the plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

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
		 * defined in Enshrined_Primary_Category_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Enshrined_Primary_Category_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/enshrined-primary-category-public.css',
			array(), $this->version, 'all' );

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
		 * defined in Enshrined_Primary_Category_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Enshrined_Primary_Category_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/enshrined-primary-category-public.js',
			array( 'jquery' ), $this->version, false );

	}

	/**
	 * Locate template.
	 *
	 * Locate the called template.
	 * Search Order:
	 * 1. /themes/theme/primary-category/$template_name
	 * 2. /themes/theme/$template_name
	 * 3. /plugins/enshrined-primary-category/templates/$template_name.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 *
	 * @param    string $template_name Template to load.
	 * @param    string $string $template_path    Path to templates.
	 * @param    string $default_path Default path to template files.
	 *
	 * @return    string                            Path to the template file.
	 */
	protected function locate_template( $template_name, $template_path = '', $default_path = '' ) {
		// Set variable to search in woocommerce-plugin-templates folder of theme.
		if ( ! $template_path ) {
			$template_path = 'primary-category/';
		}
		// Set default plugin templates path.
		if ( ! $default_path ) {
			$default_path = plugin_dir_path( __FILE__ ) . 'templates/'; // Path to the template folder
		}
		// Search template file in theme folder.
		$template = locate_template( array(
			$template_path . $template_name,
			$template_name
		) );
		// Get plugins template file.
		if ( ! $template ) {
			$template = $default_path . $template_name;
		}

		return apply_filters( 'enshrined_primary_category_locate_template', $template, $template_name, $template_path,
			$default_path );
	}

	/**
	 * Get template.
	 *
	 * Search for the template and include the file.
	 *
	 * @since 1.0.0
	 *
	 * @see locate_template()
	 *
	 * @param string $template_name Template to load.
	 * @param array $args Args passed for the template file.
	 * @param string $string $template_path    Path to templates.
	 * @param string $default_path Default path to template files.
	 */
	public function get_template( $template_name, $args = array(), $tempate_path = '', $default_path = '' ) {
		if ( is_array( $args ) && isset( $args ) ) {
			extract( $args );
		}
		$template_file = $this->locate_template( $template_name, $tempate_path, $default_path );
		if ( ! file_exists( $template_file ) ) {
			_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $template_file ),
				$this->version );

			return;
		}

		include $template_file;
	}

	/**
	 * Shortcode [primary_category].
	 *
	 * @since 1.0.0
	 *
	 * @param array $atts {
	 *
	 *  @type   string    category  The category to be searched upon, either slug or ID.
	 *  @type   integer   per_page  The number of posts to show per page.
	 *  @type   string    post_type The post type to search on, defaults to all.
	 * }
	 *
	 * @return string
	 */
	public function define_shortcode( $atts ) {

		/**
		 * Sanitise our attributes and get any defaults
		 */
		$a = shortcode_atts( array(
			'category'  => '',
			'post_type' => 'any',
			'per_page'  => 15,
		), $atts );

		// We didn't pass a category ID so try and get it from a slug
		if ( ! is_numeric( $a['category'] ) ) {
			$cat = get_category_by_slug( $a['category'] );
			if ( $cat ) {
				$a['category'] = $cat->term_id;
			}
		}

		$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;

		/**
		 * Set up our WP_Query args.
		 */
		$args = array(
			'meta_query'     => array(
				array(
					'key'     => $this->plugin_name,
					'value'   => (int) $a['category'],
					'compare' => '=',
				),
			),
			'post_status'    => 'publish',
			'post_type'      => $a['post_type'],
			'posts_per_page' => (int) $a['per_page'],
			'paged'          => $paged,
		);

		/**
		 * The WP_Query arguments.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args {
		 *     standard WP_Query args, pre populated from the shortcode args.
		 * }
		 */
		$args = apply_filters( 'enshrined_primary_category_shortcode_args', $args );

		$query = new WP_Query( $args );

		ob_start();
		if ( $query->have_posts() ) :
			while ( $query->have_posts() ) : $query->the_post();
				$this->get_template( 'shortcode.php' );
			endwhile;

			$big = 99999999;

			echo paginate_links( array(
				'base'               => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format'             => '?paged=%#%',
				'current'            => max( 1, get_query_var( 'paged' ) ),
				'total'              => $query->max_num_pages,
				'before_page_number' => '<span class="screen-reader-text">' . __( 'Page', 'enshrined-primary-category' ) . ' </span>'
			) );

		else :
			/**
			 * The WP_Query arguments.
			 *
			 * @since 1.0.0
			 *
			 * @param string . The text to show if no posts were found.
			 */
			echo apply_filters( 'enshrined_primary_category_no_posts', __( 'No posts found', 'enshrined-primary-category' ) );
		endif;

		wp_reset_postdata();

		return ob_get_clean();
	}

}
