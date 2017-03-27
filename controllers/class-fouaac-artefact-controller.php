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
     * @var string $url URL of a finds.org.uk record.
     */
    private $url;
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
        $this->url = $this->clean_up_url( $attributes['url'] );
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
        require_once( plugin_dir_path( dirname( __FILE__ ) ) . 'views/fouaac-artefact-figure-single.php' );
    }

    /**
     * Loads template dependency for the error template.
     *
     * @since 1.0.0
     * @access private
     *
     */
    private function load_error_template_dependency() {
        require_once( plugin_dir_path( dirname( __FILE__ ) ) . 'views/fouaac-error.php' );
    }

    /**
     * Displays an artefact record specified by a finds.org.uk URL.
     *
     * @since 1.0.0
     *
     */
    public function display_artefact() {
        $url_valid = $this->validate_url( $this->get_url() );
        //if the URL is valid
        if ( $url_valid ) {
            $json_importer = new Fouaac_Json_Importer( $this->get_url() );
            $artefact_data = $json_importer->import_json();
            //and there is a 200 OK response from the finds.org.uk server
            if ( $artefact_data['record'] === 'artefact' ) {
                $this->set_artefact_record( new Fouaac_Artefact( $artefact_data, $this->get_url() ) );
                $caption = new Fouaac_Caption_Creator( 'artefact',
                    $this->get_artefact_record(),
                    $this->get_caption_option(),
                    $this->get_caption_text()
                );
                $this->set_caption_text_display( $caption->create_caption() );
                $this->load_artefact_template_dependency();
                get_template_part('fouaac-artefact-figure', 'single');
            } elseif ( $artefact_data['record'] === 'error' ) {
                $this->set_error_message( $artefact_data['error message'] );
                $this->display_error();
            }

        } else {
            $this->display_error();
        }

    }

    /**
     * Displays an error message when things have gone wrong.
     *
     * @since 1.0.0
     *
     */
    public function display_error() {
        $this->load_error_template_dependency();
        get_template_part('fouaac-error', '');

    }

    /**
     * Cleans up the finds.org.uk URL provided by the user in the shortcode.
     *
     * Note that this function has been named clean_up_url() to avoid confusion
     * with the deprecated WordPress function clean_url()
     *
     * @since 1.0.0
     * @access private
     *
     * @param string $url URL of the finds.org.uk record.
     * @return string $clean_url Cleaned URL.
     */
    private function clean_up_url( $url ) {
        //escape the url safely and filter for acceptable protocols
        //note that esc_url_raw() returns an empty string if some other protocol is used
        $clean_url = esc_url_raw( $url , array('http', self::FOUACC_REQUIRED_SCHEME) );
        //remove trailing slashes
        $clean_url = rtrim( $clean_url , '/' );
        //if the scheme is http, replace with https
        if ( strncmp( $clean_url, 'http://', 7) === 0 ) {
            $clean_url = substr( $clean_url, 7);
            $clean_url = self::FOUACC_REQUIRED_SCHEME . '://' . $clean_url;

        }
        return $clean_url;

    }

    /**
     * Validates the finds.org.uk URL provided by the user in the shortcode.
     *
     * Checks if the URL provides a correct scheme, host, path and numerical record ID that match the
     * form expected for a valid finds.org.uk URL.
     *
     * @since 1.0.0
     * @access private
     *
     * @param string $url URL of the finds.org.uk record.
     * @return bool Valid or not.
     */
    private function validate_url( $url ) {
        $required_scheme = self::FOUACC_REQUIRED_SCHEME;
        $required_host = self::FOUACC_REQUIRED_HOST;
        $required_path_tokens = array('database', 'artefacts', 'record', 'id');

        if ( empty( $url ) ) {
            $this->set_error_message('Your URL is not valid or no URL was provided. 
                                        Please check your URL and try again.');
            return false;

        } else {
            //tokenise the url
            $scheme = parse_url($url, PHP_URL_SCHEME);
            $host = parse_url($url, PHP_URL_HOST);
            $path = parse_url($url, PHP_URL_PATH);

            //check if the scheme matches https
            if ( ! ( $scheme === $required_scheme ) ) {
                $this->set_error_message( "Your URL should start with {$required_scheme}://. 
                                            Please check your URL and try again." );
                return false;
            }

            //check the host matches finds.org.uk
            if ( ! ( $host === $required_host ) ) {
                $this->set_error_message( "Your URL is not valid. 
                                            The website should be '{$required_host}', 
                                            but we found '{$host}'. 
                                            Please check your URL and try again." );
                return false;
            }

            //split the path into tokens and check the parts
            //note that this will throw an error for records from the hoards module
            $path_tokens = explode( "/", $path );
            $number_of_tokens_to_check = sizeof( $required_path_tokens );
            for ( $i = 1; $i <= $number_of_tokens_to_check; $i++ ) {
                if ( ! ( $path_tokens[ $i ] === $required_path_tokens[ $i - 1 ] ) ) {
                    $this->set_error_message( "Your URL is not valid. 
                                                Please check the spelling of your URL and try again." );
                    return false;

                }
            }

            $record_id = basename( $path );
            if ( ! ( ctype_digit( $record_id ) ) ) {
                $this->set_error_message( "Your URL is not valid.
                                            There's a problem with the record ID at the end of the URL.
                                            Please check your URL and try again." );
                return false;
            }

        }

        return true;
    }

}