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

$shortcode_form = "<h1>Add artefact shortcode</h1>";
$shortcode_form.= "<form id='fouaac_shortcode' name='fouaac_shortcode' method='POST' action=''>";
// ARTEFACT //
$shortcode_form.= "<fieldset><legend>Artefact</legend>";
// entry-type
$shortcode_form.= "<label for='entry-type'>" . 'Choose how to enter the artefact:' . "</label>";
$shortcode_form.= "<select id='entry-type' name='entry-type'>";
$shortcode_form.= "<option value='url' selected='selected'>" . 'URL' . "</option>";
$shortcode_form.= "<option value='unique-id'>" . 'Unique ID' . "</option>";
$shortcode_form.= "<option value='record-id'>" . 'Record ID' . "</option>";
$shortcode_form.= "</select>";
// id (from url)
$shortcode_form.= "<label for='id'>" . 'Enter the full web address of the artefact record:' . "</label>";
$shortcode_form.= "<input type='text' id='id' name='id' size='55' required />";
$shortcode_form.= "<p id='id-explanation'>Example: <strong>https://finds.org.uk/database/artefacts/record/id/828850</strong></p>";
$shortcode_form.= "</fieldset>";
// OPTIONS //
$shortcode_form.= "<fieldset><legend>Options</legend>";
// caption-option
$shortcode_form.= "<label for='caption-option'>" . 'Caption display:' . "</label>";
$shortcode_form.= "<select id='caption-option' name='caption-option'>";
$shortcode_form.= "<option value='auto' selected='selected'>" . 'Automatic caption' . "</option>";
$shortcode_form.= "<option value='none'>" . 'No caption' . "</option>";
$shortcode_form.= "</select>";
// caption-text
$shortcode_form.= "<label for='caption-text'>" . 'Caption text (optional):' . "</label>";
$shortcode_form.= "<input type='text' id='caption-text' name='caption-text' size='40'/>";
$shortcode_form.= "<p id='caption-text-explanation'>If you leave this blank a caption will be generated automatically.</p>";
// figure-size
$shortcode_form.= "<label for='figure-size'>" . 'Figure size:' . "</label>";
$shortcode_form.= "<select id='figure-size' name='figure-size'>";
$shortcode_form.= "<option value='small'>" . 'Small' . "</option>";
$shortcode_form.= "<option value='medium' selected='selected'>" . 'Medium' . "</option>";
$shortcode_form.= "<option value='large'>" . 'Large' . "</option>";
$shortcode_form.= "</select>";
$shortcode_form.= "<p id='figure-size-explanation'>Medium is recommended.</p>";
$shortcode_form.= "</fieldset>";
$shortcode_form.= "<input type='button' id='fouaac_shortcode_submit' name='submit' class='button button-primary button-large' value='"."Insert Shortcode"."' />";
$shortcode_form.= "</form>";
echo $shortcode_form;
?>

<script type="text/javascript" charset="utf-8">
    //<![CDATA[

    // If not already inserted, insert css into the head
    if ( jQuery("style[id='fouaac-form-styles']").length == 0 ) {
        var css = '<style id="fouaac-form-styles">';
        css = css + 'form#fouaac_shortcode p.fouaac_validation_error {color: red}';
        css = css + 'form#fouaac_shortcode input.fouaac_validation_error {border:1px solid red;}';
        css = css + 'form#fouaac_shortcode fieldset {margin-bottom: 25px}';
        css = css + 'form#fouaac_shortcode fieldset label {padding-top: 5px; display:block; font-size: 14px}';
        css = css + 'form#fouaac_shortcode fieldset p {margin: 0; padding: 0}';
        css = css + 'form#fouaac_shortcode fieldset legend {padding-bottom: 5px; font-size: 18px}';
        css = css + '</style>';
        jQuery("head").append( css );
    }

    /**
     * Insert the shortcode when the submit button is clicked
     */
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

        // Check if user has entered an artefact id
        if ( values['id'] ) {

            var defaults = {'caption-option':'auto',
                'figure-size':'medium'};

            // Clear the submit button and entry-type select so shortcode does not take their values
            values['submit'] = null;
            values['entry-type'] = null;
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

        } else { // No artefact id entered, send user a message
            jQuery( "#id-explanation" ).html( "An artefact is required!" );
            jQuery( "#id-explanation" ).addClass( "fouaac_validation_error" );
            jQuery( "input[name='id']" ).addClass( "fouaac_validation_error" );
        }


    });

    /**
     * Change text label and explanation text for artefact input to match whatever entry-type is chosen by the user
     */
    // If a change is detected in the entry-type selection drop down
    jQuery( "select[id='entry-type']" ).change( function() {
        // Get the value of the currently selected option
        var entryOption = jQuery( "select[id='entry-type']" ).val();
        var newLabel = '';
        var newExplanation = '';
        // Depending on what entry-type the user chooses, update the label, explanation and text input size
        switch ( entryOption ) {
        case 'url':
                newLabel = 'Enter the full web address of the artefact record:';
                newExplanation = 'Example: <strong>https://finds.org.uk/database/artefacts/record/id/828850</strong>';
                jQuery('#id').attr('size', 55);
            break;
        case 'unique-id':
            newLabel = 'Enter the unique ID found on the artefact\'s page:';
            newExplanation = 'Example: <strong>IOW-647A2A</strong>';
            jQuery('#id').attr('size', 20);
            break;
        case 'record-id':
            newLabel = 'Enter the record ID found at the end of the web address:';
            newExplanation = 'Example: for https://finds.org.uk/database/artefacts/record/id/828850 the record ID is <strong>828850</strong>';
            jQuery('#id').attr('size', 15);
            break;
        default:
        }
        jQuery( "label[for='id']" ).text( newLabel );
        jQuery( "#id-explanation" ).html( newExplanation );

    });

    /**
     * Toggle the visibility of the caption text input depending on whether the user chooses automatic or no caption
     */
    // If a change is detected in the entry-type selection dropdown
    jQuery( "select[id='caption-option']" ).change( function() {
        // Toggle the visibility
        jQuery( "label[for='caption-text']" ).toggle( "fast" );
        jQuery( "input[name='caption-text']" ).toggle( "fast" );
        jQuery( "#caption-text-explanation" ).toggle( "fast" );

    });

    //]]>
</script>
