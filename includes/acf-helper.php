<?php
/**
 * Dinkum Interactive.
 *
 * @package WC_HPC
 */

/**
 * Add ACF load point.
 *
 * @param array $paths Array of ACF load folder paths.
 * @return array $paths Array of ACF load folder paths.
 */
function wc_hpc_acf_add_load_point( $paths ) {

	$paths[] = WC_HPC_DIR_ACF;

	return $paths;
}
add_filter( 'acf/settings/load_json', 'wc_hpc_acf_add_load_point' );

/**
 * Add ACF save point.
 *
 * @param array $paths Array of ACF load folder paths.
 * @return array $paths Array of ACF load folder paths.
 */
function wc_hpc_acf_add_save_point( $paths ) {

	$paths = WC_HPC_DIR_ACF;

	return $paths;
}
// add_filter( 'acf/settings/save_json', 'wc_hpc_acf_add_save_point' );
