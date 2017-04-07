// This line is for debugging in Chrome devtools
//# sourceURL=fouaac-shortcode-form.js

//<![CDATA[

/**
 * No-conflict WordPress wrapper. $ can be used safely inside this function for jQuery.
 */
jQuery(function ($) {

    /**
     * Insert the shortcode when the submit button is clicked
     */
    $('#fouaac-shortcode-submit').click(function () {

        // Get the form fields
        var values = {};
        $('#TB_ajaxContent form :input').each(function (index, field) {
            name = '#TB_ajaxContent form #' + field.id;
            values[$(name).attr('name')] = $(name).val();
        });

        var artefactId = values['id'];
        var entryType = values['entry-type'];

        // Check if user has entered a valid artefact id
        var recordId = fouaacGetValidRecordId(artefactId, entryType);
        if (recordId) {
            // Reset any error message
            fouaacResetErrorMessage();
            // Reset the id value with the new validated value
            values['id'] = recordId;
            // Clear the submit button and entry-type select so shortcode does not take their values
            values['submit'] = null;
            values['entry-type'] = null;

            var defaults = {
                'caption-option': 'auto',
                'figure-size': 'medium'
            };

            // Start shortcode text
            var fouaacShortcode = '[artefact';
            // Get the attributes and values
            for (attributes in values) {
                // If not empty or null
                if (values[attributes]) {
                    // And the values are not the default values
                    if (values[attributes] != defaults[attributes]) {
                        // If the value has more than 1 word
                        if (fouaacCountWords(String(values[attributes])) > 1) {
                            // Add the key="value" pair with quotes around the value
                            fouaacShortcode += ' ' + attributes + '="' + values[attributes] + '"';
                        } else {
                            // Otherwise just add the key=value pair
                            fouaacShortcode += ' ' + attributes + '=' + values[attributes];
                        }
                    }
                }
            }
            // End shortcode text
            fouaacShortcode += ']';
            // Insert shortcode into the active editor
            tinyMCE.activeEditor.execCommand('mceInsertContent', false, fouaacShortcode);
            // Close Thickbox
            tb_remove();

        }

    });

    /**
     * Change text label and explanation text for artefact input to match whatever entry-type is chosen by the user.
     */
    // If a change is detected in the entry-type selection drop down
    $("select[id='entry-type']").change(function () {
        // Reset any error message
        fouaacResetErrorMessage();
        // Get the value of the currently selected option
        var entryType = $("select[id='entry-type']").val();
        var newLabel = '';
        var newExplanation = '';
        // Depending on what entry-type the user chooses, update the label, explanation and text input size
        switch (entryType) {
            case 'url':
                newLabel = 'Enter the full web address of the artefact record:';
                newExplanation = 'Example: <strong>https://finds.org.uk/database/artefacts/record/id/828850</strong>';
                $('#id').attr('size', 55);
                break;
            case 'unique-id':
                newLabel = 'Enter the unique ID found on the artefact\'s page:';
                newExplanation = 'Example: <strong>IOW-647A2A</strong>';
                $('#id').attr('size', 20);
                break;
            case 'record-id':
                newLabel = 'Enter the record ID found at the end of the web address:';
                newExplanation = 'Example: for https://finds.org.uk/database/artefacts/record/id/828850 the record ID is <strong>828850</strong>';
                $('#id').attr('size', 15);
                break;
            default:
        }
        $("label[for='id']").text(newLabel);
        $("#id-explanation").html(newExplanation);

    });

    /**
     * Toggle the visibility of the caption text input depending on whether the user chooses automatic or no caption.
     */
    // If a change is detected in the entry-type selection dropdown
    $("select[id='caption-option']").change(function () {
        // Toggle the visibility
        $("label[for='caption-text']").toggle("fast");
        $("input[name='caption-text']").toggle("fast");
        $("#caption-text-explanation").toggle("fast");

    });

    /**
     * Helper functions for validating and processing input on submission
     */

    /**
     * Get a valid record id according to the artefact's entry type (url, unique id or record id).
     *
     * If the id is not valid, returns the empty string.
     *
     * @param {string} id Id of the artefact.
     * @param {string} type Entry type of the artefact.
     * @return {string} A record id, the empty string if not valid.
     */
    function fouaacGetValidRecordId(artefactId, entryType) {
        var recordId = '';
        // Check if no id is provided (ignoring whitespace)
        if (!artefactId.trim()) {
            fouaacDisplayErrorMessage("No artefact entered. Please enter an artefact and try again.");
            return recordId;
        } else { // Returns an artefact id according to entry type
            switch (entryType) {
                case 'url':
                    recordId = fouaacExtractRecordId(artefactId);
                    if (!recordId) {
                        fouaacDisplayErrorMessage("Please check your URL and try again.");
                    }
                    break;
                case 'unique-id':
                    recordId = fouaacLookupRecordId(artefactId);
                    if (!recordId) {
                        fouaacDisplayErrorMessage("Your unique ID does not match an artefact record on public display. " +
                            "Please check and try again.");
                    }
                    break;
                case 'record-id':
                    recordId = artefactId.trim();
                    if (!fouaacIsInt1To999999(recordId)) {
                        fouaacDisplayErrorMessage("Please check your record ID and try again.");
                        recordId = '';
                    }
                    break;
                default:
                    return false;
            }
        }
        return recordId;
    }

    /**
     * Extract the record id from a valid finds.org.uk url.
     *
     * If the url is not valid, returns the empty string.
     *
     * @param {string} url Find.org.uk url.
     * @return {string} Record id.
     */
    function fouaacExtractRecordId(url) {
        var prefixWithHttps = "https://finds.org.uk/database/artefacts/record/id/";
        var prefixWithHttp = "http://finds.org.uk/database/artefacts/record/id/";
        var prefixNoScheme = "finds.org.uk/database/artefacts/record/id/";
        var prefix = '';
        var recordId = '';

        // Trim for white space
        url = url.trim();
        // Check url starts with correct prefix
        if (url.startsWith(prefixWithHttps)) {
            prefix = prefixWithHttps;
        } else if (url.startsWith(prefixWithHttp)) {
            prefix = prefixWithHttp;
        } else if (url.startsWith(prefixNoScheme)) {
            prefix = prefixNoScheme;
        }
        // If there is a correct prefix remove the url to get the record id at the end and remove any slashes
        if (prefix) {
            recordId = url.substring(prefix.length).replace(/\//g, '');
            // If the record id is a number between 1 and 999999, return it
            if (fouaacIsInt1To999999(recordId)) {
                return recordId;
            }
        }
        // Return an empty recordId if the url is not as expected
        return recordId;
    }

    /**
     * Lookup an artefact record id from a unique id (aka 'old finds id').
     *
     * Makes an ajax call to the finds.org.uk Solr server and checks the response to make sure it is a valid unique id
     * and that the record is on public display.
     *
     * @param {string} id Unique id of artefact.
     * @return {string} Record id of artefact.
     */
    function fouaacLookupRecordId(id) {

    }

    /**
     * Display the supplied error message in the page.
     *
     * @param {string} message Error message.
     * @return {undefined}
     */
    function fouaacDisplayErrorMessage(message) {
        $("#id-explanation").html(message);
        $("#id-explanation").addClass("fouaac-validation-error");
        $("input[name='id']").addClass("fouaac-validation-error");
    }

    /**
     * Reset any error messages in the page.
     *
     * @return {undefined}
     */
    function fouaacResetErrorMessage() {
        if ($("input[name='id']").hasClass("fouaac-validation-error")) {
            $("input[name='id']").removeClass("fouaac-validation-error");
            $("#id-explanation").html('');
            $("#id-explanation").removeClass("fouaac-validation-error");
        }
    }

    /**
     * Count the number of words in a text string.
     *
     * @param {string} text Text with words to count.
     * @return {number} Number of words found.
     */
    function fouaacCountWords(text) {
        return text.split(/[ \t\r\n]/).length;
    }

    /**
     * Check if a value is an integer between 1 and 999999.
     *
     * @param {string} val Value to check.
     * @return {boolean} True if 1-999999, otherwise false.
     */
    function fouaacIsInt1To999999(val) {
        var num = parseInt(val, 10);
        return !isNaN(num) && val == num && val.toString() == num.toString() && num > 1 && num < 1000000;
    }


});

//]]>

