<?php
/*
 Finds.org.uk Artefacts and Coins Shortcode Form
 Version 1.0
 Author Mary Chester-Kadwell
 Author URI https://github.com/mchesterkadwell
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

$br = "<br />";
$p_open = "<p>";
$p_close = "<p>";

$shortcode_form = "<form id='fouaac_shortcode' name='fouaac_shortcode' method='POST' action=''>";
$shortcode_form.= $p_open;
// id (from url)
$shortcode_form.= "<label for='id'>" . 'URL of the finds.org.uk artefact you want to insert' . "</label>";
$shortcode_form.= $br;
$shortcode_form.= "<input type='text' id='id' name='id' size='50' />";
$shortcode_form.= $br;
// caption-option
$shortcode_form.= "<label for='caption-option'>" . 'Automatic caption or no caption?' . "</label>";
$shortcode_form.= $br;
$shortcode_form.= "<select id='caption-option' name='caption-option'>";
$shortcode_form.= "<option value='auto' selected='selected'>" . 'auto' . "</option>";
$shortcode_form.= "<option value='none'>" . 'none' . "</option>";
$shortcode_form.= "</select>";
$shortcode_form.= $br;
// caption-text
$shortcode_form.= "<label for='caption-text'>" . 'Optional caption text' . "</label>";
$shortcode_form.= $br;
$shortcode_form.= "<input type='text' id='caption-text' name='caption-text' size='40'/>";
$shortcode_form.= $br;
// figure-size
$shortcode_form.= "<label for='figure-size'>" . 'Figure size (medium is the default)' . "</label>";
$shortcode_form.= $br;
$shortcode_form.= "<select id='figure-size' name='figure-size'>";
$shortcode_form.= "<option value='small'>" . 'small' . "</option>";
$shortcode_form.= "<option value='medium' selected='selected'>" . 'medium' . "</option>";
$shortcode_form.= "<option value='large'>" . 'large' . "</option>";
$shortcode_form.= "</select>";
$shortcode_form.= $br;
$shortcode_form.= $br;
$shortcode_form.= "<input type='button' id='fouaac_shortcode_submit' name='submit' value='"."Insert Shortcode"."' />";
$shortcode_form.= $p_close;
$shortcode_form.= "</form>";
echo $shortcode_form;
?>

<script type="text/javascript" charset="utf-8">
    //<![CDATA[
    jQuery('#fouaac_shortcode_submit').click(function(){

        // Count the number of words in some text (string)
        function fouaac_count_words( text ) {
            return text.split(/[ \t\r\n]/).length;
        }

        // Get the form fields
        var values = {};
        jQuery('#TB_ajaxContent form :input').each(function(index,field) {
            name = '#TB_ajaxContent form #'+field.id;
            values[jQuery(name).attr('name')] = jQuery(name).val();
        });

        var defaults = {'caption-option':'auto',
            'figure-size':'medium'};

        // Clear the submit button so shortcode does not take its value
        values['submit'] = null;
        // Start shortcode text
        var fouaac_shortcode = '[artefact';
        // Get the attributes and values
        for( attributes in values ) {
            // If not empty or null
            if( values[attributes] ) {
                // And the values are not the default values
                if( values[attributes] != defaults[attributes] ) {
                    // If the value has more than 1 word
                    if( fouaac_count_words( String( values[attributes] ) ) > 1 ) {
                        // Add the key="value" pair with quotes around the value
                        fouaac_shortcode += ' '+ attributes + '="' + values[attributes]+ '"';
                    } else {
                        // Otherwise just add the key=value pair
                        fouaac_shortcode += ' ' + attributes + '=' + values[attributes];
                    }
                }
            }
        }
        // End shortcode text
        fouaac_shortcode += ']';
        // Insert shortcode into the active editor
        tinyMCE.activeEditor.execCommand('mceInsertContent', false, fouaac_shortcode);
        // Close Thickbox
        tb_remove();
    });
    //]]>
</script>