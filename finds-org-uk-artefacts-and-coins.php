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

// Block direct access to plugin file
defined( 'ABSPATH' ) or exit("Plugin must not be accessed directly.");

// Register a shortcode [find] to display artefact records in posts
add_shortcode( 'find', 'fouaac_display_artefact' );

// Register an action hook to display artefact records for use in templates
add_action( 'fouaac_display_artefact' , 'fouaac_display_artefact' );

/**
 * Displays an artefact record specified by a URL.
 *
 * Extracts the attribute 'url', retrieves the json version
 * of the corresponding finds.org.uk record and displays selected information
 * and the image from the record.
 *
 * @since 1.0.0
 *
 * @param array $attr attributes
 */
function fouaac_display_artefact( $attr ) {
    $url = $attr['url'];
    $json_url = $attr['url'] . '/format/json/';
    $img = 'https://finds.org.uk/images/bmorris/medium/2012%20T431.jpg';
    $response = wp_remote_get( $json_url );
    $json = wp_remote_retrieve_body( $response );
    $json_object = json_decode( $json );
    $find_record = $json_object->record[0];
    $label = $find_record->old_findID;
    $img_filename = $find_record->filename;
    echo $url;
    echo '<p><a href="' . $url . '">' . $label . '</a></p>';
    echo '<img src=' . $img . ' />';
    echo utf8_uri_encode( $img_filename );
}

