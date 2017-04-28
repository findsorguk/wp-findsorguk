<?php

/**
 * Importer for find.org.uk artefact record JSON data.
 *
 * Description.
 *
 * @since 1.0.0
 * @TODO documentation
 */
class Fouaac_Json_Importer
{
    private $record_id;
    private $json_url;
    private $response_timeout = 5; // 5 second default timeout to wait for json response
    private $redirects_allowed = 0; //No redirects - get the original response

    /**
     * Fouaac_Json_Importer constructor.
     */
    public function __construct( $record_id ) {
        $this->record_id = (string)$record_id;
        $this->json_url = $this->create_json_url();

    }

    /**
     * @return mixed
     */
    public function get_record_id() {
        return $this->record_id;
    }

    /**
     * @return mixed
     */
    public function get_json_url() {
        return $this->json_url;
    }

    private function create_json_url() {
        return sprintf('%s://%s/database/artefacts/record/id/%s/format/json',
            Fouaac_Artefact_Controller::FOUACC_REQUIRED_SCHEME,
            Fouaac_Artefact_Controller::FOUACC_REQUIRED_HOST,
            $this->get_record_id()
        );
    }

    public function import_json() {
        //get the response from the url within the timeout time
        $response = wp_remote_get( $this->get_json_url(),
            array('timeout' => $this->response_timeout,
                'redirection' => $this->redirects_allowed)
        );
        //if there is a wp error in the get request itself (like a timeout)
        if ( is_wp_error( $response )) {
            $error_message = $response->get_error_message();
            return $this->report_error( $error_message );
        } else {
            $response_code = wp_remote_retrieve_response_code( $response );
            //if the response code is 200 OK then decode the json into a php array
            if ($response_code == 200) {
                $json_body = wp_remote_retrieve_body( $response );
                $json_object = json_decode( $json_body, true );
                $json_as_php_array = $json_object[ 'record' ][ 0 ];
                $json_as_php_array['record'] = 'artefact';
                return $json_as_php_array;
            } else {
                return $this->report_error( $response_code );
            }
        }
    }

    /**
     * @return string
     */
    public function report_error( $error_info ) {
        $error = array( 'record' => 'error' );
        $error['error_info'] = $error_info;
        switch ( $error_info ) {
            case 301: // Moved permanently. Server returns this when the record is not on public display.
                //(Then redirects to an information page. We don't follow the redirect here.)
                $error['error message'] = "The artefact record you have specified is not 
                                            on public display so cannot be used 
                                            (error {$error_info}).";
                break;
            case 401: //Unauthorized. Server returns this when the path in the artefact URL has been malformed.
                //Hopefully the user should never see this as the path is hard coded and the parameters validated!
                $error['error message'] = "Your artefact record URL is malformed and caused an error on the server 
                                            (error {$error_info}).";
                break;
            case 404: //Not found.
                $error['error message'] = "The artefact record you have specified cannot be found  
                                            (error {$error_info}).";
                break;
            case 410: //Gone.
                $error['error message'] = "The artefact record you have specified has been removed permanently
                                            (error {$error_info}).";
                break;
            case 500: //Internal server error.
                $error['error message'] = "The artefact record you have specified has returned a server error
                                            (error {$error_info}).";
                break;
            default:
                $error['error message'] = "There was some problem fetching the artefact record you specified. 
                                            You may have a connection problem or the finds.org.uk database might be down 
                                            (error {$error_info}).";
        }
        return $error;
    }



}