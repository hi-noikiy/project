<?php

namespace Micro\Frameworks\Filesystem;

use Micro\Frameworks\Filesystem\Adapter;
use Micro\Frameworks\Filesystem\Path;

class Oss implements Adapter
{
    protected $service;
    protected $bucket;
    protected $options;

    public function __construct(\ALIOSS $service, $bucket, $options = array())
    {
        $this->service = $service;
        $this->bucket  = $bucket;
        $this->options = $options;
    }

    /**
     * {@inheritDoc}
     */
    public function read($key)
    {
        $response = $this->service->get_object(
            $this->bucket,
            $this->computePath($key),
            $this->options
        );

        if (!$response->isOK()) {
            return false;
        }

        return $response->body;
    }

    /**
     * {@inheritDoc}
     */
    public function write($key, $content, $size=0)
    {
        $response = $this->service->upload_file_by_content(
            $this->bucket,
            $this->computePath($key),
            $options = array( 
               'content' => $content, 
               'length'  => $size, 
            )
        );        

        if (!$response->isOK()) {
            return false;
        };

        return intval($response->header['_info']['size_upload']);
    }

    /**
     * {@inheritDoc}
     */
    public function exists($key)
    {
        $response = $this->service->is_object_exist(
            $this->bucket,
            $this->computePath($key)
        );

        return $response->isOK();
    }


    /**
     * {@inheritDoc}
     */
    public function delete($key)
    {
        $response = $this->service->delete_object(
            $this->bucket,
            $this->computePath($key)
        );

        return $response->isOK();
    }

    /**
    * {@inheritDoc}
    */
    public function upload($key, $file)
    {
        $response = $this->service->upload_file_by_file(
            $this->bucket,
            $this->computePath($key),
            $file
        );

        if (!$response->isOK()) {
            return false;
        };

        return intval($response->header['_info']['size_upload']);
    }

    /**
    * {@inheritDoc}
    */
    public function getInfo()
    {
        return array('type' => 'oss', 'directory' => $this->bucket);
    }



    /**
     * {@inheritDoc}
    */
    public function mtime($key)
    {
        $response = $this->service->get_object_metadata(
            $this->bucket,
            $this->computePath($key)
        );

        if (!$response->isOK()) {
            return -1;
        };

        return intval($response->header['_info']['filetime']);
    }

   

    /**
     * Computes the path for the specified key taking the bucket in account
     *
     * @param string $key The key for which to compute the path
     *
     * @return string
     */
    private function computePath($key)
    {
        return $key;
    }
}
