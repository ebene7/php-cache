<?php

namespace E7\Cache\Simple;

use E7\Cache\Exception\CacheException;

/**
 * FileCache
 */
class FileCache extends AbstractCache
{
    /**
     * @var string
     */
    private $directory;

    /**
     * {@inheritDoc}
     */
    public function init()
    {
        parent::init();

        $options = $this->getOptions();

        if (empty($options['directory'])) {
            $directory = sys_get_temp_dir() . '/e7-cache';
        } else {
            $directory = realpath($options['directory']) ?: $options['directory'];
        }

        // namespace
        if (!empty($options['namespace'])) {
            $directory .= DIRECTORY_SEPARATOR . $options['namespace'];
        }

        if (!file_exists($directory)
            && !mkdir($directory, 0777, true)) {
            throw new CacheException('Cannot create cache directory');
        }

        $this->directory = $directory;
    }

    /**
     * @return  string
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * {@inheritDoc}
     */
    public function get($key, $default = null)
    {
        $filename = $this->getFilenameByKey($key);
        $this->cleanExpired($key);

        if (!file_exists($filename) || !$fp = fopen($filename, 'rb')) {
            return $default;
        }

        $id = fgets($fp);
        $expiresAt = (int) fgets($fp);
        $value = stream_get_contents($fp);
        fclose($fp);

        return unserialize($value);
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value, $ttl = null)
    {
        if (!is_writable($this->directory)) {
            throw new CacheException(sprintf('Cache directory is not writable (%s)', $this->directory));
        }

        // - normalize ttl
        $ttl = $this->normalizeTtl($ttl);
        $filename = $this->getFilenameByKey($key);
        $id = $this->getIdByKey($key);
        $expiresAt = time() + (null !== $ttl ? $ttl : 31536000);  // 31536000 = one year in seconds
        $content = implode(PHP_EOL, [$id, $expiresAt, serialize($value)]);

        return !(false === file_put_contents($filename, $content))
               && touch($filename, $expiresAt);
    }

    /**
     * {@inheritDoc}
     */
    public function clear()
    {
        $success = true;

        $directoryIterator = new \RecursiveDirectoryIterator($this->directory, \FilesystemIterator::SKIP_DOTS);

        foreach (new \RecursiveIteratorIterator($directoryIterator) as $filename) {
            if ($filename->isDir()) {
                continue;
            }

            $success = (unlink($filename) || !file_exists($filename)) && $success;
        }

        return $success;
    }

    /**
     * {@inheritDoc}
     */
    public function delete($key)
    {
        $filename = $this->getFilenameByKey($key);
        return unlink($filename);
    }

    /**
     * @var string
     */
    public function has($key)
    {
        $filename = $this->getFilenameByKey($key);
        $this->cleanExpired($key);
        return file_exists($filename);
    }

    /**
     * @param   string $key
     * @return  string
     */
    public function getFilenameByKey($key)
    {
        $id = $this->getIdByKey($key);
        $hash = md5(static::class . '-' . $id);
        $directory = implode(DIRECTORY_SEPARATOR, [$this->directory, $hash[0], $hash[1]]);

        if (!file_exists($directory) && !mkdir($directory, 0777, true)) {
            throw new CacheException('Cannot create cache directory');
        }

        return $directory . DIRECTORY_SEPARATOR . substr($hash, 2, 20);
    }

    /**
     * {@inheritDoc}
     */
    protected function cleanExpired($key)
    {
        $filename = $this->getFilenameByKey($key);
        if (file_exists($filename) && filemtime($filename) <= time()) {
            return $this->delete($key);
        }
        return false;
    }
}

