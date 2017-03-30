/*
 Finds.org.uk Artefacts and Coins TinyMCE Plugin
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

(function() {
    tinymce.create('tinymce.plugins.fouaac', {
        /**
         * Initializes the plugin, this will be executed after the plugin has been created.
         * This call is done before the editor instance has finished it's initialization so use the onInit event
         * of the editor instance to intercept that event.
         *
         * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
         * @param {string} url Absolute URL to where the plugin is located.
         */
        init : function(ed, url) {

            ed.addButton('fouaac', {
                title : 'Finds.org.uk Artefacts and Coins Shortcode',
                image : url + '/fouaac.png',
                onclick: function () {
                    //ed.windowManager.alert('Button working!');
                    //Adjust width and height values according to the size of the viewport
                    var viewport_width = jQuery(window).width();
                    var viewport_height = jQuery(window).height();
                    var width = ( 720 < viewport_width ) ? 720 : viewport_width;
                    var height = ( viewport_height > 600 ) ? 600 : viewport_height;
                    width = width - 80;
                    height = height - 84;
                    //Display a modal ThickBox to display the form for collecting attribute information from the user
                    tb_show( 'Finds.org.uk Artefacts and Coins Shortcode', '#TB_inline?width=' + width
                        + '&height=' + height + '&inlineId=fouaac-form' );
                    // Load the form
                    jQuery( function() {
                        // Dynamic load
                        jQuery('#TB_ajaxContent').load( url + '/fouaac-shortcode-form.php' );
                    });

                }
            });

        },

        /**
         * Creates control instances based in the incoming name. This method is normally not
         * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
         * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
         * method can be used to create those.
         *
         * @param {String} n Name of the control to create.
         * @param {tinymce.ControlManager} cm Control manager to use in order to create new control.
         * @return {tinymce.ui.Control} New control instance or null if no control was created.
         */
        createControl : function(n, cm) {
            return null;
        },

        /**
         * Returns information about the plugin as a name/value array.
         * The current keys are longname, author, authorurl, infourl and version.
         *
         * @return {Object} Name/value array containing information about the plugin.
         */
        getInfo : function() {
            return {
                longname : 'Finds.org.uk Artefacts and Coins Shortcode Button',
                author : 'Mary Chester-Kadwell',
                authorurl : 'https://github.com/mchesterkadwell',
                infourl : 'https://github.com/findsorguk/wp-findsorguk',
                version : "0.1"
            };
        }
    });

    // Register plugin
    tinymce.PluginManager.add( 'fouaac', tinymce.plugins.fouaac );
})();