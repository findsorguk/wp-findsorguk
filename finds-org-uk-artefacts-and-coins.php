<?php
/*
Plugin Name: Finds.org.uk Artefacts and Coins
Description: Display up-to-date artefacts and coins data from the Portable Antiquities Scheme (finds.org.uk) on your Wordpress blog.
Version:     1.0.0
Author:      Mary Chester-Kadwell
Author URI:  https://finds.org.uk/
License:     GPL3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Text Domain: finds-org-uk
*/

/*  Copyright 2017  Mary Chester-Kadwell  (email : mchester-kadwell@britishmuseum.org)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License version 3 as published by
    the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Block direct access to plugin file
defined( 'ABSPATH' ) or exit("Plugin must not be accessed directly.");


// Enqueue CSS styles
add_action( 'wp_enqueue_scripts', 'fouaac_load_style' );

/**
 * Load CSS styles
 *
 */
function fouaac_load_style() {
    wp_register_style( 'fouaac-style', plugins_url('/css/fouaac-style.css', __FILE__) );
    wp_enqueue_style( 'fouaac-style');

}

// Add action to fire the TinyMCE editor button code after wp has finished loading
add_action('init', 'fouaac_shortcode_button');

/**
 * Register button and plugin for TinyMCE button
 *
 * Users can click on this button in the TinyMCE visual editor to choose shortcode attributes and insert
 * the shortcode automatically.
 */
function fouaac_shortcode_button() {
    if ( current_user_can('edit_posts') && current_user_can('edit_pages') ) {
        add_filter( 'mce_external_plugins', 'fouaac_shortcode_plugin' );
        add_filter( 'mce_buttons', 'fouaac_register_button' );
    }
}

/**
 * Load the plugin javascript file that creates the new button
 *
 * @param array $plugins An array of all plugins.
 * @return array
 */
function fouaac_shortcode_plugin( $plugin_array ) {
    $plugin_array['fouaac'] = plugin_dir_url(__FILE__) .'plugins/tinymce-button/fouaac-tinymce-button.js';
    return $plugin_array;
}

/**
 * Register a button named 'fouaac' to the editor buttons
 *
 * @param array $buttons An array of buttons.
 * @return array
 */
function fouaac_register_button( $buttons ) {
    array_push( $buttons, 'fouaac' ); // Button name 'fouaac'
    return $buttons;
}

// Register a shortcode [artefact] to display an artefact record in posts
add_shortcode( 'artefact', 'fouaac_display_artefact' );

/**
 * Shortcode function for [artefact] shortcode.
 *
 * Shortcode attributes:
 * 'id' is the record id of the finds.org.uk artefact record - found on the end of the record URL.
 * 'caption-option' can be 'none' to turn off the caption; defaults to 'auto' which displays 'caption-text'
 *  or an automatic caption if no 'caption-text' is provided.
 * 'caption-text' is the desired manual caption text.
 * 'figure-size' is the display size of the image; can be 'small', 'medium' or 'large'; defaults to 'medium'.
 * @TODO implement 'figure-size'
 *
 * @since 1.0.0
 * @param array $attr Shortcode attributes.
 * @return string HTML to display
 */

function fouaac_display_artefact( $attr ) {
    // Insert default attribute values
    $attributes = shortcode_atts( array(
        'id' => '',
        'caption-option' => 'auto',
        'caption-text' => '',
        'figure-size' => 'medium'
    ),
        $attr, 'artefact'
    );
    // Load controller class
    require_once plugin_dir_path( __FILE__ ) . 'controllers/class-fouaac-artefact-controller.php';
    $artefact_controller = new Fouaac_Artefact_Controller( $attributes );
    return $artefact_controller->display_artefact();

}





