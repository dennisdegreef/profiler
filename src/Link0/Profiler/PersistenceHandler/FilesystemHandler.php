<?php

/**
 * FilesystemHandler.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler\PersistenceHandler;

use League\Flysystem\FilesystemInterface;
use Link0\Profiler\Exception;
use Link0\Profiler\PersistenceHandler;
use Link0\Profiler\PersistenceHandlerInterface;
use Link0\Profiler\Profile;

/**
 * FilesystemHandler implementation for PersistenceHandler
 *
 * @package Link0\Profiler\PersistenceHandler
 */
final class FilesystemHandler extends PersistenceHandler implements PersistenceHandlerInterface
{
    /**
     * @var FilesystemInterface $filesystem
     */
    private $filesystem;

    /**
     * @var string $path The path where the profile files are stored
     */
    private $path;

    /**
     * @param \League\Flysystem\FilesystemInterface $filesystem
     * @param string $path      OPTIONAL The path from the root given in the filesystem
     * @param string $extension OPTIONAL The extension of the profile files
     */
    public function __construct(FilesystemInterface $filesystem, $path = '/', $extension = 'profile')
    {
        $this->filesystem = $filesystem;
        $this->path = $path;
        $this->extension = $extension;
    }

    /**
     * @return FilesystemInterface $filesystem
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }

    /**
     * @return string $path
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string $extension
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param  string $identifier The file identifier
     * @return string $fullPath The full path to the profile file
     */
    public function getFullPath($identifier)
    {
        return rtrim($this->getPath(), '/') . '/' . $identifier . '.' . $this->getExtension();
    }

    /**
     * Returns a list of Identifier strings
     * Unfortunately the list() method is reserved
     *
     * @return string[]
     */
    public function getList()
    {
        $identifiers = array();
        foreach($this->filesystem->listFiles($this->getPath()) as $file) {
            $identifiers[] = $file['filename'];
        }
        return $identifiers;

    }

    /**
     * @param  string       $identifier
     * @return Profile|null $profile
     */
    public function retrieve($identifier)
    {
        $file = $this->getFilesystem()->get($this->getFullPath($identifier));

        return unserialize($file->read());
    }

    /**
     * @param  Profile $profile
     * @throws \Link0\Profiler\Exception
     * @return PersistenceHandlerInterface
     */
    public function persist(Profile $profile)
    {
        if(!$this->getFilesystem()->put($this->getFullPath($profile->getIdentifier()), serialize($profile))) {
            throw new Exception("Unable to persist Profile[identifier={$profile->getIdentifier()}]");
        }

        return $this;
    }

    /**
     * @throws \Link0\Profiler\Exception
     * @return PersistenceHandlerInterface $this
     */
    public function emptyList()
    {
        foreach($this->getList() as $item) {
            if(!$this->getFilesystem()->delete($this->getFullPath($item))) {
                throw new Exception("Unable to delete Profile[identifier={$item}]");
            }
        }

        return $this;
    }
}