<?php

namespace Dotsquares\Base\Model\Response;

use Magento\Framework\App;
use Magento\Framework\Filesystem\File\ReadInterface;

interface OctetResponseInterface extends App\Response\HttpInterface, App\PageCache\NotCacheableInterface
{
    public const FILE = 'file';
    public const FILE_URL = 'url';

    public function sendOctetResponse();

    public function getContentDisposition(): string;

    public function getReadResourceByPath(string $readResourcePath): ReadInterface;

    public function setReadResource(ReadInterface $readResource): OctetResponseInterface;

    public function getFileName(): ?string;

    public function setFileName(string $fileName): OctetResponseInterface;
}
