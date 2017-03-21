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
    private $caption_option;
    private $caption_text;
    private $figure_size;
    private $caption_text_display;


    public function __construct( $attributes ) {
        var_dump($attributes);
        $this->url = esc_url_raw( $attributes['url'], array( 'https' ) );
        $this->caption_option = sanitize_text_field( $attributes['caption-option'] );
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
    public function get_caption_option() {
        return $this->caption_option;
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
    public function get_caption_text_display() {
        return $this->caption_text_display;
    }

    /**
     *
     */
    public function set_caption_text_display( $caption_text_display ) {
        $this->caption_text_display = $caption_text_display;
    }

    private function load_dependencies() {
        require_once( plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-fouaac-artefact.php' );
        require_once( plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-fouaac-json-importer.php' );
        require_once( plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-fouaac-caption-creator.php' );

    }

    private function load_template_dependency() {
        require_once( plugin_dir_path( dirname( __FILE__ ) ) . 'views/fouaac-artefact-figure-single.php' );

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
                $this->get_caption_option(),
                $this->get_caption_text()
            );
            $this->set_caption_text_display( $caption->create_caption() );

            $this->load_template_dependency();
            get_template_part('fouaac-artefact-figure', 'single');

        }

    }


}