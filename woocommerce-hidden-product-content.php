<?php
/**
 * Dinkum Interactive.
 *
 * @package WC_HPC
 */

/*
Plugin Name: WooCommerce Hidden Product Content
Plugin URI: https://www.dinkuminteractive.com/
Description: Customized development kit.
Version: 1.0.0
Author: Dinkum Interactive
Author URI: https://www.dinkuminteractive.com/
License: GPLv2 or later
Text Domain: wchpc
*/

// Constants.
$wc_hpc_constants = array(
	'WC_HPC_DIR'     => plugin_dir_path( __FILE__ ),
	'WC_HPC_DIR_INC' => plugin_dir_path( __FILE__ ) . '/includes',
	'WC_HPC_DIR_ACF' => plugin_dir_path( __FILE__ ) . '/acf-json',
	'WC_HPC_DIR_TMP' => plugin_dir_path( __FILE__ ) . '/templates',
	'WC_HPC_DIR_JS'  => plugin_dir_url( __FILE__ ) . '/assets/js',
	'WC_HPC_DIR_CSS' => plugin_dir_url( __FILE__ ) . '/assets/css',
);

foreach ( $wc_hpc_constants as $key => $val ) {

	if ( ! defined( $key ) ) {

		define( $key, $val );
	}
}

// ACF Helper.
require_once WC_HPC_DIR_INC . '/acf-helper.php';

// Core functions.
require_once WC_HPC_DIR_INC . '/core-functions.php';

// Default filters.
require_once WC_HPC_DIR_INC . '/filters.php';

// Default filters.
require_once WC_HPC_DIR_INC . '/actions.php';

// Add as shortcode.
require_once WC_HPC_DIR_INC . '/shortcodes.php';

// Add as block.
require_once WC_HPC_DIR_INC . '/blocks.php';
