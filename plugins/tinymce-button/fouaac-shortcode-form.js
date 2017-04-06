//<![CDATA[
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