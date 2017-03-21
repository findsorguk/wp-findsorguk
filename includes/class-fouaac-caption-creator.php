<?php

/**
 * Creates a figure caption.
 *
 * Description.
 *
 * @since 1.0.0
 * @TODO documentation
 */
class Fouaac_Caption_Creator
{

    private $display_type;
    private $data_object;
    private $caption_option;
    private $caption_text;


    /**
     * Fouaac_Caption_Creator constructor.
     */
    public function __construct( $display_type, $data_object, $caption_option, $caption_text ) {
        $this->display_type = $display_type;
        $this->data_object = $data_object;
        $this->caption_option = $caption_option;
        $this->caption_text = $caption_text;

    }


    function get_display_type() {
        return $this->display_type;

    }

    function get_data_object() {
        return $this->data_object;

    }

    function get_caption_option() {
        return $this->caption_option;

    }

    function get_caption_text() {
        return $this->caption_text;

    }


    public function create_caption() {
        switch ( $this->get_display_type() ) {
            case 'artefact':
                return $this->create_artefact_caption();
        break;
            default:
                return '';
        }

    }

    private function create_artefact_caption() {
        // If there is no caption-text provided, create the caption according to the caption-option
        if ( empty( $this->get_caption_text() )  ) {
            switch ( $this->get_caption_option() ) {
                case 'none':
                    return '';
                break;
                case 'auto':
                    $text = sprintf("%s %s",
                        $this->get_data_object()->get_broad_period(),
                        $this->get_data_object()->get_object_type()
                    );
                    $caption = $this->title_string( $text );
                    return $caption;
                break;
                default:
                    return '';
            }
            // If there is any caption-text provided, this overrides the caption-options and is displayed
        } elseif ( ! (empty( $this->get_caption_text() ) ) ) {
            $caption = $this->trim_string( $this->get_caption_text() );
            return $caption;
        } else {
            return '';
        }


    }

    private function title_string( $string ) {
        $lower_string = strtolower( $string );
        $title_string = ucfirst( $lower_string );
        return $title_string;

    }

    private function trim_string( $string ) {
        $trim_string = trim( $string );
        return $string;

    }

}