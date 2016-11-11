<?php

/**
 * Controller for displaying artefact records.
 *
 * Description.
 *
 * @since 1.0.0
 * @TODO documentation
 * @TODO abstract html generation to a view
 */
class Fouaac_Artefact_Controller
{

    private $attributes;
    private $url;

    public function __construct( $attr ) {
        // Check if the attributes are an array
        // If no attributes are included in the shortcode then $attr is the empty string
        if ( is_array( $attr )) {
            $this->attributes = $attr;
            // Check if a URL has been specified
            if ( array_key_exists( 'url' , $attr ) ) {
                $this->url = esc_url_raw( $attr['url'], array( 'https' ) );
            }

        }
        $this->load_dependencies();

    }

    /**
     * @return mixed
     */
    public function get_attributes() {
        return $this->attributes;
    }

    /**
     * @return string
     */
    public function get_url() {
        return $this->url;
    }

    private function load_dependencies() {
        require_once( plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-fouaac-json-importer.php');
        require_once( plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-fouaac-artefact.php');

    }

    /**
     * Displays an artefact record specified by a URL.
     *
     * @since 1.0.0
     *
     * @param array $attr Attributes.
     */
    public function display_artefact() {
        // If no URL is provided nothing is displayed
        if ( ! ( empty( $this->get_url() ) ) ) {
            $json_importer = new Fouaac_Json_Importer( $this->get_url() );
            $artefact_data = $json_importer->import_json();
            $artefact = new Fouaac_Artefact( $artefact_data, $this->get_url() );

            $img = 'https://finds.org.uk/images/bmorris/medium/2012%20T431.jpg';
            $label = $artefact->get_old_find_id();
            $img_filename = $artefact->get_filename();

            echo esc_url( $this->get_url() );
            echo '<p><a href="' . esc_url( $this->get_url() ) . '">' . $label . '</a></p>';
            echo '<img src=' . $img . ' />';
            echo utf8_uri_encode( $img_filename );

        }

    }



}