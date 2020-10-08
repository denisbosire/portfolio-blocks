<?php
/**
 * Plugin Name: portfolio-block
 * Plugin URI: 
 * Description: Gutenberg Portfolio blocks
 * Author: fortisthemes
 * Author URI: https://thepixeltribe.com
 * Version: 1.0.0
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package CGB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Block Initializer.
 */
require_once plugin_dir_path( __FILE__ ) . 'src/init.php';
require_once plugin_dir_path( __FILE__ ) . 'src/pro-portfolio/pro-portfolio.php';