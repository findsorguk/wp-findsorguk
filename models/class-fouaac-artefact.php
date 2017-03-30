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

    public function __construct( array $data )
    {
        $this->id = $data[ 'id' ];
        $this->old_find_id = $data[ 'old_findID' ];
        $this->object_type = $data[ 'objecttype' ];
        $this->broad_period = $data[ 'broadperiod' ];
        $this->filename = $data[ 'filename' ];
        $this->image_directory = $data[ 'imagedir' ];
        $this->image_label = $data[ 'imageLabel' ];
        $this->image_copyright_holder = $data[ 'imageCopyrightHolder' ];
        $this->image_license = $data[ 'imageLicense' ];
        $this->image_license_acronym = $this->lookup_license_acronym( $data[ 'imageLicense' ] );

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
        if ( ! is_null ( $image_license ) ) {
            $acronyms = $this->get_cc_license_acronyms();
            return $acronyms[$image_license];
        } else {
            return null;
        }
    }






}