<?php

declare(strict_types=1);

namespace Dotsquares\ShopbyBase\Plugin\Catalog\Api\ProductAttributeRepositoryInterface;

use Dotsquares\ShopbyBase\Api\Data\FilterSettingInterface;
use Dotsquares\ShopbyBase\Api\Data\FilterSettingRepositoryInterface;
use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

class SetExtensionAttributes
{
    /**
     * @var FilterSettingRepositoryInterface
     */
    private $filterSettingRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        FilterSettingRepositoryInterface $filterSettingRepository,
        LoggerInterface $logger
    ) {
        $this->filterSettingRepository = $filterSettingRepository;
        $this->logger = $logger;
    }

    public function afterGet(
        ProductAttributeRepositoryInterface $subject,
        ProductAttributeInterface $entity,
        string $attributeCode
    ): ProductAttributeInterface {
        if (!$entity->getExtensionAttributes()->getFilterSetting()) {
            try {
                $filterSetting = $this->filterSettingRepository->getByAttributeCode($attributeCode);
                $extensionAttributes = $entity->getExtensionAttributes();
                $extensionAttributes->setFilterSetting($filterSetting);
                $entity->setExtensionAttributes($extensionAttributes);
            } catch (LocalizedException $e) {
                $this->logger->critical($e);
            }
        }

        return $entity;
    }
}
