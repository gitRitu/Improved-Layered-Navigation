<?php

declare(strict_types=1);

namespace Dotsquares\Base\Model\LicenceService\Request\Data\InstanceInfo;

use Dotsquares\Base\Model\SimpleDataObject;

class Domain extends SimpleDataObject
{
    public const URL = 'url';

    /**
     * @param string $url
     * @return $this
     */
    public function setUrl(string $url): self
    {
        return $this->setData(self::URL, $url);
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->getData(self::URL);
    }
}
