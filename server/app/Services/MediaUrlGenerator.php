<?php

namespace App\Services;

use DateTimeInterface;
use Spatie\MediaLibrary\UrlGenerator\BaseUrlGenerator;
use Spatie\MediaLibrary\Exceptions\UrlCannotBeDetermined;

class MediaUrlGenerator extends BaseUrlGenerator
{
    /**
     * Get the url for a media item.
     *
     * @return string
     */
    public function getUrl(): string
    {
        $url = $this->getBaseMediaDirectoryUrl().'/'.$this->getPathRelativeToRoot();
        $url = $this->makeCompatibleForNonUnixHosts($url);
        $url = $this->rawUrlEncodeFilename($url);

        return $url;
    }

    /**
     * Get the temporary url for a media item.
     *
     * @param DateTimeInterface $expiration
     * @param array              $options
     *
     * @return string
     */
    public function getTemporaryUrl(DateTimeInterface $expiration, array $options = []): string
    {
        throw UrlCannotBeDetermined::filesystemDoesNotSupportTemporaryUrls();
    }

    /**
     * Get the url to the directory containing responsive images.
     *
     * @return string
     */
    public function getResponsiveImagesDirectoryUrl(): string
    {
        return $this->config->get("filesystems.disks.{$this->media->disk}.url").'/'
            .$this->pathGenerator->getPathForResponsiveImages($this->media);
    }

    protected function getBaseMediaDirectoryUrl(): string
    {
        if ($diskUrl = $this->config->get("filesystems.disks.{$this->media->disk}.url")) {
            return str_replace(url('/'), '', $diskUrl);
        }

        return $this->getBaseMediaDirectory();
    }

    protected function getBaseMediaDirectory(): string
    {
        return str_replace(storage_path('app'), '', $this->getStoragePath());
    }

    protected function getStoragePath() : string
    {
        $diskRootPath = $this->config->get("filesystems.disks.{$this->media->disk}.root");

        return realpath($diskRootPath);
    }

    protected function makeCompatibleForNonUnixHosts(string $url): string
    {
        if (DIRECTORY_SEPARATOR != '/') {
            $url = str_replace(DIRECTORY_SEPARATOR, '/', $url);
        }

        return $url;
    }
}
