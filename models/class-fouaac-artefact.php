<?php

/**
 * Represents a single finds.org.uk artefact record.
 *
 * Description.
 *
 * @since 1.0.0
 * @TODO documentation
 */
class Fouaac_Artefact
{
    private $data;
    private $url;
    private $id;
    private $old_find_id;
    private $object_type;
    private $broad_period;
    private $filename;
    private $image_directory;
    private $image_label;
    private $image_copyright_holder;
    private $image_license;
    private $image_license_acronym;

    private $cc_license_acronyms = array(
        'Attribution-NonCommercial-ShareAlike License' => 'BY-NC-SA',
        'Attribution-NonCommercial License' => 'BY-NC-ND',
        'Attribution License' => 'BY',
        'Attribution-ShareAlike License' => 'BY-SA'
    );

    public function __construct( array $data, $url )
    {
        $this->data = $data;
        $this->url = $url;
        $this->id = $this->data[ 'id' ];
        $this->old_find_id = $this->data[ 'old_findID' ];
        $this->object_type = $this->data[ 'objecttype' ];
        $this->broad_period = $this->data[ 'broadperiod' ];
        $this->filename = $this->data[ 'filename' ];
        $this->image_directory = $this->data[ 'imagedir' ];
        $this->image_label = $this->data[ 'imageLabel' ];
        $this->image_copyright_holder = $this->data[ 'imageCopyrightHolder' ];
        $this->image_license = $this->data[ 'imageLicense' ];
        $this->image_license_acronym = $this->lookup_license_acronym( $this->data[ 'imageLicense' ] );

    }

    /**
     * @return array
     */
    public function get_data()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function get_url()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function get_id()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function get_old_find_id()
    {
        return $this->old_find_id;
    }

    /**
     * @return string
     */
    public function get_object_type()
    {
        return $this->object_type;
    }

    /**
     * @return string
     */
    public function get_broad_period()
    {
        return $this->broad_period;
    }

    /**
     * @return string
     */
    public function get_filename()
    {
        return $this->filename;
    }

    /**
     * @return string
     */
    public function get_image_directory()
    {
        return $this->image_directory;
    }

    /**
     * @return string
     */
    public function get_image_label()
    {
        return $this->image_label;
    }

    /**
     * @return string
     */
    public function get_image_copyright_holder()
    {
        return $this->image_copyright_holder;
    }

    /**
     * @return string
     */
    public function get_image_license()
    {
        return $this->image_license;
    }

    /**
     * @return string
     */
    public function get_image_license_acronym()
    {
        return $this->image_license_acronym;
    }

    /**
     * @return string
     */
    public function get_cc_license_acronyms()
    {
        return $this->cc_license_acronyms;
    }

    /**
     * @return string
     */
    private function lookup_license_acronym( $image_license )
    {
        $acronyms = $this->get_cc_license_acronyms();
        return $acronyms[ $image_license ];
    }






}