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

    // Shortcode attributes
    private $url;
    private $caption;
    private $caption_text;
    private $figure_size;
    private $new_caption_text;


    public function __construct( $attributes ) {
        var_dump($attributes);
        $this->url = esc_url_raw( $attributes['url'], array( 'https' ) );
        $this->caption = sanitize_text_field( $attributes['caption'] );
        $this->caption_text = sanitize_text_field( $attributes['caption-text'] );
        $this->figure_size = sanitize_text_field( $attributes['figure-size'] );
        $this->load_dependencies();
    }

    /**
     * @return string
     */
    public function get_url() {
        return $this->url;
    }

    /**
     * @return string
     */
    public function get_caption() {
        return $this->caption;
    }

    /**
     * @return string
     */
    public function get_caption_text() {
        return $this->caption_text;
    }

    /**
     * @return string
     */
    public function get_figure_size() {
        return $this->figure_size;
    }

    /**
     * @return string
     */
    public function get_new_caption_text() {
        return $this->new_caption_text;
    }


    private function load_dependencies() {
        require_once( plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-fouaac-artefact.php' );
        require_once( plugin_dir_path( dirname( __FILE__ ) ) . 'views/fouaac-artefact-figure-single.php' );
        require_once( plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-fouaac-json-importer.php' );
        require_once( plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-fouaac-caption-creator.php' );

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
            $caption = new Fouaac_Caption_Creator( 'artefact',
                $artefact,
                $this->get_caption(),
                $this->get_caption_text() );
            $this->new_caption_text = $caption->create_caption();

        }

    }


}