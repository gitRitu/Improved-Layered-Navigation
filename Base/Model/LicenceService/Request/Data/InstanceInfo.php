<?php

declare(strict_types=1);

namespace Dotsquares\Base\Model\LicenceService\Request\Data;

use Dotsquares\Base\Model\SimpleDataObject;
use Magento\Framework\Api\ExtensibleDataInterface;

class InstanceInfo extends SimpleDataObject implements ExtensibleDataInterface
{
    public const SYSTEM_INSTANCE_KEY = 'system_instance_key';
    public const MODULES = 'modules';
    public const DOMAINS = 'domains';

    /**
     * @param string|null $systemInstanceKey
     * @return $this
     */
    public function setSystemInstanceKey(?string $systemInstanceKey): self
    {
        return $this->setData(self::SYSTEM_INSTANCE_KEY, $systemInstanceKey);
    }

    /**
     * @return string|null
     */
    public function getSystemInstanceKey(): ?string
    {
        return $this->getData(self::SYSTEM_INSTANCE_KEY);
    }

    /**
     * @param \Dotsquares\Base\Model\LicenceService\Request\Data\InstanceInfo\Module[]|null $modules
     * @return $this
     */
    public function setModules(array $modules): self
    {
        return $this->setData(self::MODULES, $modules);
    }

    /**
     * @return \Dotsquares\Base\Model\LicenceService\Request\Data\InstanceInfo\Module[]|null
     */
    public function getModules(): ?array
    {
        return $this->getData(self::MODULES);
    }

    /**
     * @param \Dotsquares\Base\Model\LicenceService\Request\Data\InstanceInfo\Domain[]|null $domains
     * @return $this
     */
    public function setDomains(array $domains): self
    {
        return $this->setData(self::DOMAINS, $domains);
    }

    /**
     * @return \Dotsquares\Base\Model\LicenceService\Request\Data\InstanceInfo\Domain[]|null
     */
    public function getDomains(): ?array
    {
        return $this->getData(self::DOMAINS);
    }
}
