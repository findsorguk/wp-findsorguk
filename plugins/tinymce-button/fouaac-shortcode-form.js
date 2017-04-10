// This line is for debugging in Chrome devtools
//# sourceURL=fouaac-shortcode-form.js

//<![CDATA[

/**
 * No-conflict WordPress wrapper. $ can be used safely inside this function for jQuery.
 */
jQuery(function ($) {

        /**
         * ------------
         * Form actions
         * ------------
         */

        /**
         * Insert the shortcode when the submit button is clicked.
         */
        $('#fouaac-shortcode-submit').click(function () {
            // Get the entry type and value
            var entryType = $('#entry-type').val();
            var entryValue = $("#" + entryType).val();

            // If no input is provided, display error
            if (!entryValue.trim()) {
                fouaacDisplayErrorMessage("No artefact entered. Please enter an artefact and try again.");
            } else { // Otherwise, validate input and try to submit form

                // If the entry-type is 'unique-id'
                if (entryType === "unique-id") {
                    // @TODO Validate the input (with regex!)
                    // Look up the record id (send ajax request) and try to submit the form
                    fouaacLookupRecordIdAndSubmit();
                }
            }

            // If the entry-type is 'url'
            if (entryType === "url") {
                // Extract the record id from the url
                var validRecordId = fouaacExtractRecordId();
                // If record id is valid, update the input and submit the form
                if (validRecordId) {
                    $("#record-id").val(validRecordId);
                    fouaacSubmitForm();
                } else {
                    fouaacDisplayErrorMessage("Please check your URL for errors and try again.");
                }
            }

            // If the entry-type is 'record-id'
            if (entryType === "record-id") {
                var validRecordId = fouaacValidateRecordId();
                // If record id is valid, update the input and submit the form
                if (validRecordId) {
                    $("#record-id").val(validRecordId);
                    fouaacSubmitForm();
                } else {
                    fouaacDisplayErrorMessage("Please check your record ID for errors and try again.");
                }
            }

    });

    /**
     * Show the input (url, unique-id or record-id) to match whatever entry-type is chosen by the user.
     */
    // If a change is detected in the entry-type selection drop down
    $("#entry-type").change(function () {
        // Reset any error message
        fouaacResetErrorMessage();
        // Get the value of the currently selected option
        var entryType = $('#entry-type option:selected').val();
        // Depending on what entry-type the user chooses, display the label, text input and explanation accordingly
        switch (entryType) {
            case 'url':
                $('.unique-id').hide();
                $('.record-id').hide();
                $('.url').show();
                $('label.url').css('display', 'block');
                break;
            case 'unique-id':
                $('.url').hide();
                $('.record-id').hide();
                $('.unique-id').show();
                $('label.unique-id').css('display', 'block');
                break;
            case 'record-id':
                $('.url').hide();
                $('.unique-id').hide();
                $('.record-id').show();
                $('label.record-id').css('display', 'block');
                break;
            default:
        }
    });

    /**
     * Toggle the visibility of the caption text input depending on whether the user chooses automatic or no caption.
     */
    // If a change is detected in the entry-type selection dropdown
    $("#caption-option").change(function () {
        // Toggle the visibility
        $("label.caption-text").toggle("fast");
        $("#caption-text").toggle("fast");
        $("#caption-text-explanation").toggle("fast");

    });

    /**
     * -----------------------------------------------------------------------
     * Helper functions for validating and processing input, reporting errors
     * and submitting the form to insert the shortcode
     * -----------------------------------------------------------------------
     */

    /**
     * Submit the form: create and insert the shortcode and close the modal box.
     *
     * @return {undefined}
     */
    function fouaacSubmitForm() {
        // Get the form fields
        var values = {};
        $('#TB_ajaxContent form :input').each(function (index, field) {
            name = '#TB_ajaxContent form #' + field.id;
            values[$(name).attr('name')] = $(name).val();
        });

        // Clear the submit button, entry-type select, url and unique-id inputs so shortcode does not take their values
        values['submit'] = null;
        values['entry-type'] = null;
        values['url'] = null;
        values['unique-id'] = null;

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
                    if (countWords(String(values[attributes])) > 1) {
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


        /**
         * Count the number of words in a text string.
         *
         * @param {string} text Text with words to count.
         * @return {number} Number of words found.
         */
        function countWords(text) {
            return text.split(/[ \t\r\n]/).length;
        }

    }

    /**
     * Display the supplied error message in the page.
     *
     * @param {string} message Error message.
     * @return {undefined}
     */
    function fouaacDisplayErrorMessage(message) {
        var entryType = $('#entry-type').val();
        $("#" + entryType + "-explanation").html(message).addClass("fouaac-validation-error");
        $("#" + entryType).addClass("fouaac-validation-error");
    }

    /**
     * Reset any error messages in the page.
     *
     * @return {undefined}
     */
    function fouaacResetErrorMessage() {
        // If there is an error message
        if ($(".artefact-input").hasClass("fouaac-validation-error")) {
            var entryType = $('#entry-type option:selected').val();
            // Remove the error class from the input
            $("#" + entryType).removeClass("fouaac-validation-error");
            // Remove error class from explanation and reset explanation text
            $("#" + entryType + "-explanation").removeClass("fouaac-validation-error").html(function () {
                var entryType = $('#entry-type').val();
                switch (entryType) {
                    case 'url':
                        return "Example: <strong>https://finds.org.uk/database/artefacts/record/id/828850</strong>";
                        break;
                    case 'unique-id':
                        return "Example: <strong>IOW-647A2A</strong>";
                        break;
                    case 'record-id':
                        return "Example: for https://finds.org.uk/database/artefacts/record/id/828850 the record ID is <strong>828850</strong>"
                        break;
                    default:
                }
            });
        }
    }

    /**
     * Check if record id is valid, and if so, return a version trimmed of whitespace.
     *
     * If the record id is not valid, returns the empty string.
     *
     * @return {string} A record id, the empty string if not valid.
     */
    function fouaacValidateRecordId() {
        var recordId = $("#record-id").val().trim();
        if (fouaacIsInt1To999999(recordId)) {
            return recordId;
        } else {
            return '';
        }
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

    /**
     * Extract the record id from a valid finds.org.uk url.
     *
     * If the url is not valid, returns the empty string.
     *
     * @return {string} Record id.
     */
    function fouaacExtractRecordId() {
        var prefixWithHttps = "https://finds.org.uk/database/artefacts/record/id/";
        var prefixWithHttp = "http://finds.org.uk/database/artefacts/record/id/";
        var prefixNoScheme = "finds.org.uk/database/artefacts/record/id/";
        var prefix = '';
        var recordId = '';
        var url = $("#url").val().trim();

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
            } else {
                return '';
            }
        }
        // Return an empty recordId if the url is not as expected
        return recordId;
    }


    /**
     * Lookup an artefact record id from a unique id (aka 'old finds id') and try to submit the form.
     *
     * Makes an ajax call to the finds.org.uk Solr server and checks the response to make sure it is a valid unique id
     * and that the record is on public display. If not, displays an error message.
     *
     * @return {undefined}
     */
    function fouaacLookupRecordIdAndSubmit() {
        $.ajax({
            url: 'https://finds.org.uk/database/search/results/q/old_findID%3A'
            + $("#unique-id").val()
            + '/format/json',
            type: 'GET',
            timeout: 5000,
            dataType: 'json',
            success: function (data) {
                console.log(data);
                if (data.meta.totalResults == 0) {
                    // No record returned so error
                    fouaacDisplayErrorMessage("No artefact with that unique ID available. Please check and try again.");
                } else if (data.results[0].id) {
                    // Record id available so update the input value with the record id, and submit the form
                    $('#record-id').val(data.results[0].id);
                    fouaacSubmitForm();
                } else {
                    fouaacDisplayErrorMessage("Something has gone wrong. Please check your unique ID for errors and try again.");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                fouaacDisplayErrorMessage("Something has gone wrong when trying to contact finds.org.uk: " +
                    textStatus + " (" + errorThrown + ")");
            }
        });
    }

});


//]]>

