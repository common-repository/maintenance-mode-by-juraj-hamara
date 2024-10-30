<?php
/*
Plugin Name: Maintenance mode by Juraj Hamara
Plugin URI: https://jurajhamara.sk/plugin/maintenance-mode/
Description: A lightweight Maintenance Mode plugin
Version: 1.0.2
Author: Juraj Hamara
Author URI: https://jurajhamara.sk
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: juraj-hamara-maintenance
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// fixing PHP error messages
error_reporting( 0 );

function load_translation() {
	load_plugin_textdomain( 'juraj-hamara-maintenance', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'load_translation' );

function jurajhamara_maintenance_mode() {
	if ( ! current_user_can( 'delete_published_posts' ) || ! is_user_logged_in() ) {
		wp_die( __( '<h1>The site is currently under maintenance.</h1><br>Please come back later.', 'juraj-hamara-maintenance' ) );
	}
}
add_action( 'get_header', 'jurajhamara_maintenance_mode' );

// enqueue inline scripts
function jurajhamara_include_inline_style() {
	wp_register_style( 'maintenance-mode-style', false );
	wp_enqueue_style( 'maintenance-mode-style' );

	wp_add_inline_style( 'maintenance-mode-style', '.maintenance-mode, #wpadminbar:not(.mobile) .ab-top-menu>li.maintenance-mode:hover>.ab-item, #wpadminbar:not(.mobile) .ab-top-menu>li.maintenance-mode>.ab-item:focus { background: #eb3a34 !important; color: #f0f0f1 !important; }' );
}
add_action( 'admin_enqueue_scripts', 'jurajhamara_include_inline_style', 100 );
add_action( 'wp_enqueue_scripts', 'jurajhamara_include_inline_style', 100 );

// display admin bar notice
function jurajhamara_notice_toolbar( $wp_admin_bar ) {
	$args = array(
		'id'    => 'maintenance-mode',
		'title' => __( 'Ongoing maintenance', 'juraj-hamara-maintenance' ),
		'href'  => '',
		'meta'  => array( 'class' => 'maintenance-mode' ),
	);
	$wp_admin_bar->add_node( $args );
}
add_action( 'admin_bar_menu', 'jurajhamara_notice_toolbar', 9999 );