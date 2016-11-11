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
    private $url;
    private $json_url;

    /**
     * Fouaac_Json_Importer constructor.
     */
    public function __construct( $url ) {
        $this->url = $url;
        $this->json_url = $this->create_json_url( $this->url );

    }

    /**
     * @return mixed
     */
    public function get_url() {
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function get_json_url() {
        return $this->json_url;
    }

    private function create_json_url( $url ) {
        return $this->url . '/format/json';

    }

    public function import_json() {
        $response = wp_remote_get( $this->get_json_url() );
        $json_body = wp_remote_retrieve_body( $response );
        $json_object = json_decode( $json_body, true );
        $json_as_php_array = $json_object['record'][0];
        return $json_as_php_array;

    }


}