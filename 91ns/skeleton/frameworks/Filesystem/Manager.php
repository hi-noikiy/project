<?php

namespace Micro\Frameworks\Filesystem;

use Micro\Frameworks\Filesystem\Adapter;
use Micro\Frameworks\Filesystem\Path;
use Micro\Frameworks\Filesystem\FileAlreadyExists;
use Micro\Frameworks\Filesystem\FileNotFound;

/**
 * A filesystem is used to store and retrieve files
 */
class Manager
{
    protected $adapter;

    /**
     * Constructor
     *
     * @param Adapter $adapter A configured Adapter instance
     */
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Returns the adapter
     *
     * @return Adapter
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * Indicates whether the file matching the specified key exists
     *
     * @param string $key
     *
     * @return boolean TRUE if the file exists, FALSE otherwise
     */
    public function has($key)
    {
        return $this->adapter->exists($key);
    }

    /**
     * Writes the given content into the file
     *
     * @param string  $key                 Key of the file
     * @param string  $content             Content to write in the file
     * @param boolean $overwrite           Whether to overwrite the file if exists
     * @throws FileAlreadyExists 		   When file already exists and overwrite is false
     * @throws \RuntimeException           When for any reason content could not be written
     *
     * @return integer The number of bytes that were written into the file
     */
    public function write($key, $content, $overwrite = false)
    {
        if (!$overwrite && $this->has($key)) {
            throw new FileAlreadyExists($key);
        }

        $numBytes = $this->adapter->write($key, $content);

        if (false === $numBytes) {
            throw new \RuntimeException(sprintf('Could not write the "%s" key content.', $key));
        }

        return $numBytes;
    }



    /**
     * Upload the given file into the file
     *
     * @param string  $key                 Key of the file
     * @param string  $file                upload file
     * @param boolean $overwrite           Whether to overwrite the file if exists
     * @throws FileAlreadyExists           When file already exists and overwrite is false
     * @throws \RuntimeException           When for any reason content could not be written
     *
     * @return integer The number of bytes that were written into the file
     */
    public function upload($key, $file, $overwrite = false)
    {
        if (!$overwrite && $this->has($key)) {
            throw new FileAlreadyExists($key);
        }

        $success = $this->adapter->upload($key, $file);

        if (false === $success) {
            throw new \RuntimeException(sprintf('Could not upload the "%s" key file.', $key));
        }

        return $success;
    }

    /**
     * Reads the content from the file
     *
     * @param  string                 $key Key of the file
     * @throws FileNotFound when file does not exist
     * @throws \RuntimeException      when cannot read file
     *
     * @return string
     */
    public function read($key)
    {
        $this->assertHasFile($key);

        $content = $this->adapter->read($key);

        if (false === $content) {
            throw new \RuntimeException(sprintf('Could not read the "%s" key content.', $key));
        }

        return $content;
    }

    /**
     * Deletes the file matching the specified key
     *
     * @param string $key
     * @throws \RuntimeException when cannot read file
     *
     * @return boolean
     */
    public function delete($key)
    {
        $this->assertHasFile($key);

        if ($this->adapter->delete($key)) {
            return true;
        }

        throw new \RuntimeException(sprintf('Could not remove the "%s" key.', $key));
    }

    /**
     * Returns the last modified time of the specified file
     *
     * @param string $key
     *
     * @return integer An UNIX like timestamp
     */
    public function mtime($key)
    {
        $this->assertHasFile($key);
        return $this->adapter->mtime($key);
    }

    /**
    * Returns the current adaptor info
    *
    * @return integer An UNIX like timestamp
    */
    public function getInfo()
    {
        return $this->adapter->getInfo();
    }

    /**
     * Checks if matching file by given key exists in the filesystem
     *
     * Key must be non empty string, otherwise it will throw Exception\FileNotFound
     * {@see http://php.net/manual/en/function.empty.php}
     *
     * @param string $key
     *
     * @throws FileNotFound   when sourceKey does not exist
     */
    private function assertHasFile($key)
    {
        if (! empty($key) && ! $this->has($key)) {
            throw new FileNotFound($key);
        }
    }
}
