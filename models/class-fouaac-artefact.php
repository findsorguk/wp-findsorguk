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

    public function __construct( array $data, $url )
    {
        $this->data = $data;
        $this->url = $url;
        $this->id = $this->data['id'];
        $this->old_find_id = $this->data['old_findID'];
        $this->object_type = $this->data['objecttype'];
        $this->broad_period = $this->data['broadperiod'];
        $this->filename = $this->data['filename'];
        $this->image_dir = $this->data['imagedir'];

    }

    /**
     * @return array
     */
    public function get_data()
    {
        return $this->data;
    }

    /**
     * @return mixed
     */
    public function get_url()
    {
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function get_id()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function get_old_find_id()
    {
        return $this->old_find_id;
    }

    /**
     * @return mixed
     */
    public function get_object_type()
    {
        return $this->object_type;
    }

    /**
     * @return mixed
     */
    public function get_broad_period()
    {
        return $this->broad_period;
    }

    /**
     * @return mixed
     */
    public function get_filename()
    {
        return $this->filename;
    }

    /**
     * @return mixed
     */
    public function get_image_directory()
    {
        return $this->image_directory;
    }




}