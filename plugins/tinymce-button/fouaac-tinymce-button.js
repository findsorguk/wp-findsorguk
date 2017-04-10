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
         * Initialise the plugin.
         *
         * @param {tinymce.Editor} ed Editor instance to initialise the plugin in.
         * @param {string} url Absolute URL to where the plugin is located.
         */
        init : function(ed, url) {

            ed.addButton('fouaac', {
                title : 'Finds.org.uk Artefacts and Coins Shortcode',
                image : url + '/fouaac.png',
                onclick: function () {
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
                        // Dynamically load the html and js of the modal
                        var ajaxContent = jQuery('#TB_ajaxContent');
                        ajaxContent.load( url + '/fouaac-shortcode-form.php' , function() {
                                ajaxContent.append("<script type='text/javascript' charset='utf-8' src='"
                                                    + url + "/fouaac-shortcode-form.js'></script>");
                        });
                    });

                }
            });

        },


        /**
         * Return information about the plugin as an object.
         *
         * @return {Object} Information about the plugin.
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