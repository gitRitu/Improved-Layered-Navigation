<?php

declare(strict_types=1);

namespace Dotsquares\GroupedOptions\Plugin\Shopby\Model\ResourceModel\Fulltext\Collection;

use Dotsquares\GroupedOptions\Model\FakeKeyGenerator;
use Dotsquares\GroupedOptions\Model\GroupAttr\DataFactoryProviderInterface;
use Dotsquares\Shopby\Model\ResourceModel\Fulltext\Collection as FulltextCollection;
use Magento\Catalog\Model\Product;
use Magento\Eav\Api\AttributeRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class AdjustFacetedDataToGroup
{
    /**
     * @var DataFactoryProviderInterface
     */
    private $dataFactoryProvider;

    /**
     * @var AttributeRepositoryInterface
     */
    private $attributeRepository;

    /**
     * @var FakeKeyGenerator
     */
    private $fakeKeyGenerator;

    public function __construct(
        DataFactoryProviderInterface $dataFactoryProvider,
        AttributeRepositoryInterface $attributeRepository,
        FakeKeyGenerator $fakeKeyGenerator
    ) {
        $this->dataFactoryProvider = $dataFactoryProvider;
        $this->attributeRepository = $attributeRepository;
        $this->fakeKeyGenerator = $fakeKeyGenerator;
    }

    public function afterGetFacetedData(FulltextCollection $subject, array $facetedData, string $attributeCode): array
    {
        $dataProvider = $this->dataFactoryProvider->create();
        try {
            $attribute = $this->attributeRepository->get(Product::ENTITY, $attributeCode);
            $groups = $dataProvider->getGroupsByAttributeId(
                (int) $attribute->getAttributeId()
            );
        } catch (NoSuchEntityException $e) {
            return $facetedData;
        }

        foreach ($groups as $group) {
            $key = $this->fakeKeyGenerator->generate((int) $group->getId());

            if (isset($facetedData[$key])) {
                $code = $group->getGroupCode();
                $facetedData[$code] = $facetedData[$key];
                unset($facetedData[$key]);
            }
        }

        return $facetedData;
    }
}
