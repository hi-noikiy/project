<?php

namespace Micro\Frameworks\Filesystem;

/**
 * Interface for the filesystem adapters
 */
interface Adapter
{
    /**
     * Reads the content of the file
     *
     * @param string $key
     *
     * @return string|boolean if cannot read content
     */
    public function read($key);


    /**
     * Writes the given content into the file
     *
     * @param string $key
     * @param string $content
     *
     * @return integer|boolean The number of bytes that were written into the file
     */
    public function write($key, $content, $size=0);


    /**
     * Indicates whether the file exists
     *
     * @param string $key
     *
     * @return boolean
     */
    public function exists($key);


    /**
     * Deletes the file
     *
     * @param string $key
     *
     * @return boolean
     */
    public function delete($key);


    /**
     * up the file
     *
     * @param string $key
     * @param string $file
     *
     * @return boolean
     */
    public function upload($key, $file);


    /**
     * Returns the last modified time
     *
     * @param string $key
     *
     * @return integer|boolean An UNIX like timestamp or false
     */
    public function mtime($key);


    /**
     * Returns adapter info
     *
     * @param string $key
     *
     * @return array about info
     */
    public function getInfo();




}
