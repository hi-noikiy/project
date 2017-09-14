<?php

namespace Micro\Frameworks\Filesystem;

use Micro\Frameworks\Filesystem\Adapter;
use Micro\Frameworks\Filesystem\Path;

/**
 * Adapter for the local filesystem
 */
class Local implements Adapter
{
    protected $directory;
    private $create;
    private $mode;

    /**
     * Constructor
     *
     * @param string  $directory Directory where the filesystem is located
     * @param boolean $create    Whether to create the directory if it does not
     *                            exist (default FALSE)
     * @param integer $mode      Mode for mkdir
     *
     * @throws RuntimeException if the specified directory does not exist and
     *                          could not be created
     */
    public function __construct($directory, $create = false, $mode = 0777)
    {
        $this->directory = Path::normalize($directory);

        if (is_link($this->directory)) {
            $this->directory = realpath($this->directory);
        }

        $this->create = $create;
        $this->mode = $mode;
    }

    /**
     * {@inheritDoc}
     */
    public function read($key)
    {
        return file_get_contents($this->computePath($key));
    }

    /**
     * {@inheritDoc}
     */
    public function write($key, $content, $size=0)
    {
        $path = $this->computePath($key);
        $this->ensureDirectoryExists(dirname($path), true);
        return file_put_contents($path, $content);
    }

    /**
     * {@inheritDoc}
     */
    public function exists($key)
    {
        return file_exists($this->computePath($key));
    }

    /**
     * {@inheritDoc}
     */
    public function delete($key)
    {
        return unlink($this->computePath($key));
    }


    /**
    * {@inheritDoc}
    */
    public function upload($key, $file)
    {
        $path = $this->computePath($key);
        $this->ensureDirectoryExists(dirname($path), true);
        return move_uploaded_file($file, $path);
    }


    /**
     * {@inheritDoc}
     */
    public function mtime($key)
    {
        return filemtime($this->computePath($key));
    }

    /**
     * {@inheritDoc}
     */
    public function getInfo()
    {
        return array('type' => 'local', 'directory' => $this->directory);
    }

    /**
     * Computes the path from the specified key
     *
     * @param string $key The key which for to compute the path
     *
     * @return string A path
     *
     * @throws OutOfBoundsException If the computed path is out of the
     *                              directory
     * @throws RuntimeException If directory does not exists and cannot be created
     */
    protected function computePath($key)
    {
        $this->ensureDirectoryExists($this->directory, $this->create);
        return $this->normalizePath($this->directory . '/' . $key);
    }

    /**
     * Ensures the specified directory exists, creates it if it does not
     *
     * @param string  $directory Path of the directory to test
     * @param boolean $create    Whether to create the directory if it does
     *                            not exist
     *
     * @throws RuntimeException if the directory does not exists and could not
     *                          be created
     */
    protected function ensureDirectoryExists($directory, $create = false)
    {
        if (!is_dir($directory)) {
            if (!$create) {
                throw new \RuntimeException(sprintf('The directory "%s" does not exist.', $directory));
            }
            $this->createDirectory($directory);
        }
    }

    /**
     * Normalizes the given path
     *
     * @param string $path
     *
     * @return string
     */
    protected function normalizePath($path)
    {
        $path = Path::normalize($path);

        if (0 !== strpos($path, $this->directory)) {
            throw new \OutOfBoundsException(sprintf('The path "%s" is out of the filesystem.', $path));
        }
        return $path;
    }

    /**
     * Creates the specified directory and its parents
     *
     * @param string $directory Path of the directory to create
     *
     * @throws InvalidArgumentException if the directory already exists
     * @throws RuntimeException         if the directory could not be created
     */
    protected function createDirectory($directory)
    {
        $created = mkdir($directory, $this->mode, true);
        if (!$created) {
            if (!is_dir($directory)) {
                throw new \RuntimeException(sprintf('The directory \'%s\' could not be created.', $directory));
            }
        }
    }
}
