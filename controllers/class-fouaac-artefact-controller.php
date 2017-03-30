<?php

/**
 * Controller for displaying artefact records.
 *
 * Description.
 *
 * @since 1.0.0
 * @TODO caching
 * @TODO user interface
 */
class Fouaac_Artefact_Controller
{
    /**
     * URLs should use the https protocol.
     *
     * @since 1.0.0
     * @var string FOUACC_REQUIRED_SCHEME Required URL scheme.
     */
    const FOUACC_REQUIRED_SCHEME = 'https';

    /**
     * URLs should use this host
     *
     * @since 1.0.0
     * @TODO replace with development/production config
     * @var string FOUACC_REQUIRED_SCHEME Required URL scheme.
     */
    const FOUACC_REQUIRED_HOST = 'finds.org.uk';

    /**
     * Shortcode attributes.
     */
    /**
     * Shortcode attribute: URL of a finds.org.uk record.
     *
     * @since 1.0.0
     * @access private
     * @var string $record_id URL of a finds.org.uk record.
     */
    private $record_id;
    /**
     * Shortcode attribute: whether the caption displays or not.
     *
     * @since 1.0.0
     * @access private
     * @var string $caption_option Caption option.
     */
    private $caption_option;
    /**
     * Shortcode attribute: caption text provided by the user.
     *
     * @since 1.0.0
     * @access private
     * @var string $caption_text Caption text.
     */
    private $caption_text;
    /**
     * Shortcode attribute: figure size to display.
     *
     * @since 1.0.0
     * @access private
     * @var string $figure_size Figure size.
     */
    private $figure_size;

    /**
     * Caption text to display. May be automated or manually provided by the user.
     *
     * @since 1.0.0
     * @access private
     * @var string $caption_text_display Caption text to display.
     */
    private $caption_text_display;

    /**
     * Artefact record object containing all the data from the json response.
     *
     * @since 1.0.0
     * @access private
     * @var object $artefact_record Artefact record object.
     */
    private $artefact_record;

    /**
     * Error message to be displayed to the user.
     *
     * @since 1.0.0
     * @access private
     * @var string $error_message Error message.
     */
    private $error_message;

    /**
     * Constructor for Fouaac_Artefact_Controller class.
     *
     * @since 1.0.0
     * @access private
     *
     * @param array $attributes Shortcode attributes.
     */
    public function __construct( $attributes ) {
        $this->record_id = $this->clean_id( $attributes['id'] );
        $this->caption_option = sanitize_text_field( $attributes['caption-option'] );
        $this->caption_text = sanitize_text_field( $attributes['caption-text'] );
        $this->figure_size = sanitize_text_field( $attributes['figure-size'] );
        $this->load_dependencies();
    }

    /**
     * @return string
     */
    public function get_record_id() {
        return $this->record_id;
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

    /**
     * @return object
     */
    public function get_artefact_record() {
        return $this->artefact_record;
    }

    /**
     *
     */
    public function set_artefact_record( $artefact_object ) {
        $this->artefact_record = $artefact_object;
    }

    /**
     * @return string
     */
    public function get_error_message() {
        return $this->error_message;
    }

    /**
     *
     */
    public function set_error_message( $error ) {
        $this->error_message = $error;
    }

    /**
     * Loads class dependencies for the controller.
     *
     * @since 1.0.0
     * @access private
     *
     */
    private function load_dependencies() {
        require_once( plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-fouaac-artefact.php' );
        require_once( plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-fouaac-json-importer.php' );
        require_once( plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-fouaac-caption-creator.php' );
    }

    /**
     * Loads template dependency for the single figure artefact template.
     *
     * @since 1.0.0
     * @access private
     *
     */
    private function load_artefact_template_dependency() {
        ob_start();
        include ( plugin_dir_path( dirname( __FILE__ ) ) . 'views/fouaac-artefact-figure-single.php' );
        return ob_get_clean();

    }

    /**
     * Loads template dependency for the error template.
     *
     * @since 1.0.0
     * @access private
     *
     */
    private function load_error_template_dependency() {
        ob_start();
        include ( plugin_dir_path( dirname( __FILE__ ) ) . 'views/fouaac-error.php' );
        return ob_get_clean();

    }

    /**
     * Displays an artefact record specified by a finds.org.uk URL.
     *
     * @since 1.0.0
     *
     */
    public function display_artefact() {
        $record_id_valid = $this->validate_record_id( $this->get_record_id() );
        //if the record id is valid
        if ( $record_id_valid ) {
            $json_importer = new Fouaac_Json_Importer( $this->get_record_id() );
            $artefact_data = $json_importer->import_json();
            //and there is a 200 OK response from the finds.org.uk server
            if ( $artefact_data['record'] === 'artefact' ) {
                //create a new artefact record from the data
                $this->set_artefact_record( new Fouaac_Artefact( $artefact_data ) );
                //if there is an image available
                if ( !is_null( $this->get_artefact_record()->get_image_directory() ) ) {
                    //create a caption
                    $caption = new Fouaac_Caption_Creator('artefact',
                        $this->get_artefact_record(),
                        $this->get_caption_option(),
                        $this->get_caption_text()
                    );
                    $this->set_caption_text_display($caption->create_caption());
                    //display the artefact figure
                    return $this->load_artefact_template_dependency();
                } else { //if there is no image available
                    $this->set_error_message( "No image is available on this record." );
                    return $this->display_error();
                }
            } elseif ( $artefact_data['record'] === 'error' ) { //if there is no valid json response
                $this->set_error_message( $artefact_data['error message'] );
                return $this->display_error();
            }

        } else { //if the record id is invalid
            return $this->display_error();
        }

    }

    /**
     * Loads a template to display an error message when things have gone wrong.
     *
     * @since 1.0.0
     *
     */
    public function display_error() {
        return $this->load_error_template_dependency();

    }

    /**
     * Cleans any trailing slashes from the finds.org.uk record id provided by the user in the shortcode.
     *
     * Also makes sure it is a string.
     *
     * @since 1.0.0
     * @access private
     *
     * @param mixed $id ID of the finds.org.uk record.
     * @return string $clean_id Cleaned id.
     */
    private function clean_id( $record_id ) {
        //cast into a string
        $string_record_id = (string)$record_id;
        //remove any trailing slashes
        $clean_record_id = trim( $string_record_id , '/' );
        return $clean_record_id;

    }

    /**
     * Basic validation of the finds.org.uk record id provided by the user in the shortcode.
     *
     * Checks the id is a string of digits greater than 0 and not a huge number unlikely to be valid.
     *
     * @since 1.0.0
     * @access private
     *
     * @param string $record_id URL of the finds.org.uk record.
     * @return bool Valid or not.
     */
    private function validate_record_id( $record_id )
    {
        //check if the id is null, 0, "0", or the empty string, if so, error
        if ( empty( $record_id ) ) {
            $this->set_error_message('No record ID provided. 
                                        Please check your "id" attribute and try again.');
            return false;

        } else if ( ! ctype_digit( $record_id ) ) { //check if the id is a string of digits, if not, error
            $this->set_error_message( 'There\'s a problem with your record ID (not a number). 
                                        Please check your "id" attribute and try again.' );
            return false;
        } else if ( strlen($record_id) > 6 ) { //check if the id is too long to be a valid id, if so, error
            $this->set_error_message( 'There\'s a problem with your record ID (number is too long). 
                                            Please check your "id" attribute and try again.' );
            return false;
        }

        return true;
    }

}