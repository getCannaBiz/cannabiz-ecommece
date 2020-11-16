<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.wpdispensary.com/
 * @since      1.0.0
 *
 * @package    WPD_Inventory
 * @subpackage WPD_Inventory/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WPD_Inventory
 * @subpackage WPD_Inventory/admin
 * @author     WP Dispensary <contact@wpdispensary.com>
 */
class WPD_Inventory_Admin {

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
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		// Enqueue the admin stylesheet.
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wpd-inventory-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		// Enqueue the admin javascript file.
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wpd-inventory-admin.js', array( 'jquery' ), $this->version, false );
		// Localize the admin javascript file.
		wp_localize_script( $this->plugin_name, 'WPaAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		// Enqueue the tablesorter javascript files.
		wp_enqueue_script( 'tablesorter', plugin_dir_url( __FILE__ ) . 'js/jquery.tablesorter.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'tablesorter-jquery', plugin_dir_url( __FILE__ ) . 'js/tablesorter.js', array( 'jquery' ), $this->version, false );
	}

}
