<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://enshrined.co.uk
 * @since             1.0.0
 * @package           Enshrined_Primary_Category
 *
 * @wordpress-plugin
 * Plugin Name:       Primary Category
 * Plugin URI:        https://enshrined.co.uk
 * Description:       Allows you to assign a primary category to a WordPress post.
 * Version:           1.0.0
 * Author:            Daryll Doyle
 * Author URI:        https://enshrined.co.uk
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       enshrined-primary-category
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-enshrined-primary-category-activator.php
 */
function activate_enshrined_primary_category() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-enshrined-primary-category-activator.php';
	Enshrined_Primary_Category_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-enshrined-primary-category-deactivator.php
 */
function deactivate_enshrined_primary_category() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-enshrined-primary-category-deactivator.php';
	Enshrined_Primary_Category_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_enshrined_primary_category' );
register_deactivation_hook( __FILE__, 'deactivate_enshrined_primary_category' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-enshrined-primary-category.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_enshrined_primary_category() {

	$plugin = new Enshrined_Primary_Category();
	$plugin->run();

}
run_enshrined_primary_category();
