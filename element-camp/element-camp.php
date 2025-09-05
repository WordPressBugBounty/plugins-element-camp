<?php
/**
 * Plugin Name: ElementCamp
 * Plugin URI: https://themescamp.com/
 * Description: This is a plugin with elements bundle for Elementor builder.
 * Author: themesCamp
 * Author URI: https://themescamp.com
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Version: 2.2.2
 * Text Domain: element-camp
 * Domain Path: /lang
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'ELEMENTCAMP__FILE__', __FILE__ );
define( 'ELEMENTCAMP_URL', plugins_url( '/', ELEMENTCAMP__FILE__ ) );
define( 'ELEMENTCAMP_PLUGIN_BASE', plugin_basename( ELEMENTCAMP__FILE__ ) );


/**
 * Check and deactivate old plugin (themescamp-elements) if active.
 * Run this when ElementCamp plugin is activated.
 */
function elementcamp_upgrade_check() {
    // Check if the old plugin 'themescamp-elements' is active
    if ( is_plugin_active( 'themescamp-elements/themescamp-elements.php' ) ) {
        // Deactivate old plugin
        deactivate_plugins( 'themescamp-elements/themescamp-elements.php' );
    }
}
register_activation_hook( __FILE__, 'elementcamp_upgrade_check' );


/**
 *
 * Load the plugin after Elementor (and other plugins) are loaded.
 *
 * @since 1.0.0
 */
function elementcamp_plg_load() {
	// Load localization file
	load_plugin_textdomain( 'element-camp' );

	// Require the main plugin file 
	require( __DIR__ . '/init.php' );

}
add_action( 'plugins_loaded','elementcamp_plg_load' );


function elementcamp_plg_fail_load_out_of_date() {
	if ( ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	$file_path = 'elementor/elementor.php';

	$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
	$message = '<p>' . __( 'Tcgelements Plugin is not working because you are using an old version of Elementor.', 'element-camp' ) . '</p>';
	$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, __( 'Update Elementor Now', 'element-camp' ) ) . '</p>';

	echo '<div class="error">' . $message . '</div>';
}

//include elementor addon
include('inc/elementor-addon.php');

//include elementor addon
include('inc/elemntor-extras.php');

//plugin translation
function elementcamp_textdomain_translation() {
    load_plugin_textdomain('element-camp', false, dirname(plugin_basename(__FILE__)) . '/lang/');
} // end custom_theme_setup
add_action('after_setup_theme', 'elementcamp_textdomain_translation');

// TCE Badge
function elementcamp_badge_style() {
    wp_enqueue_style( 'tce-widget-badge', plugins_url( 'elements/assets/css/global/tce-badge.css', __FILE__ ) );
}
add_action( 'elementor/editor/after_enqueue_styles', 'elementcamp_badge_style' );
