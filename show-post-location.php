<?php
/**
 * Plugin Name: Show post by location
 * Plugin URI:  
 * Description: Show tickets based on your location
 * Version:     0.1
 * Author:      Manuel Muñoz Rodríguez
 * Author URI:  
 * Text Domain: spl-location
 * Domain Path: /languages
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package     Show post location
 * @author      Manuel Muñoz Rodríguez <mmr010496@gmail.com>
 * @copyright   2021
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 *
 * Prefix:      spl
 */

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

define( 'PLUGIN_SPL_PATH', plugin_dir_path( __FILE__ ) );

require_once PLUGIN_SPL_PATH . '/admin/class-show-post-location-setting.php';
require_once PLUGIN_SPL_PATH . '/admin/show-post-location-admin.php';
require_once PLUGIN_SPL_PATH . '/public/show-post-location-public.php';

add_action( 'plugins_loaded', 'spl_plugin_init' );
/**
 * Load localization files
 *
 * @return void
 */
function spl_plugin_init() {
	load_plugin_textdomain( 'show-post-location', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
