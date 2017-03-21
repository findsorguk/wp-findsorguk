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

/*  Copyright 2016  Mary Chester-Kadwell  (email : mchester-kadwell@britishmuseum.org)

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

// Blocks direct access to plugin file
defined( 'ABSPATH' ) or exit("Plugin must not be accessed directly.");


// Enqueues CSS styles
add_action( 'wp_enqueue_scripts', 'fouaac_load_style' );

function fouaac_load_style() {
    wp_register_style( 'fouaac-style', plugins_url('/css/fouaac-style.css', __FILE__) );
    wp_enqueue_style( 'fouaac-style');

}

// Registers a shortcode [artefact] to display an artefact record in posts
add_shortcode( 'artefact', 'fouaac_display_artefact' );

function fouaac_display_artefact( $attr ) {
    // Inserts default attribute values
    $attributes = shortcode_atts( array(
        'url' => '',
        'caption-option' => 'auto',
        'caption-text' => '',
        'figure-size' => 'medium'
    ),
        $attr, 'artefact'
    );
    // Loads controller class
    require_once plugin_dir_path( __FILE__ ) . 'controllers/class-fouaac-artefact-controller.php';
    $artefact_controller = new Fouaac_Artefact_Controller( $attributes );
    $artefact_controller->display_artefact();

}






