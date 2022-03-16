<?php

declare(strict_types=1);

namespace Dotsquares\Shopby\Plugin\Catalog\Model\ResourceModel\Eav\Attribute;

use Dotsquares\Base\Model\Serializer;
use Dotsquares\Shopby\Helper\FilterSetting as FilterSettingHelper;
use Dotsquares\ShopbyBase\Model\FilterSettingFactory;
use Dotsquares\ShopbyBase\Api\Data\FilterSettingInterface;
use Dotsquares\ShopbyBase\Api\Data\OptionSettingRepositoryInterface;
use Dotsquares\ShopbyBase\Model\Cache\Type;
use Dotsquares\ShopbyBase\Api\Data\FilterSettingRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute as EavAttributeResource;
use Magento\Config\Model\Config\Factory as ConfigFactory;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class SaveFilterSettingOnAttributeSave
{
    /**
     * @var FilterSettingRepositoryInterface
     */
    private $filterSettingRepository;

    /**
     * @var FilterSettingFactory
     */
    private $filterSettingFactory;

    /**
     * @var ConfigFactory
     */
    private $configFactory;

    /**
     * @var FilterSettingHelper
     */
    private $filterSettingHelper;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var  TypeListInterface
     */
    private $cacheTypeList;

    /**
     * @var OptionSettingRepositoryInterface
     */
    private $optionSettingRepository;

    public function __construct(
        FilterSettingRepositoryInterface $filterSettingRepository,
        FilterSettingFactory $filterSettingFactory,
        ConfigFactory $configFactory,
        FilterSettingHelper $filterSettingHelper,
        Serializer $serializer,
        TypeListInterface $typeList,
        OptionSettingRepositoryInterface $optionSettingRepository
    ) {
        $this->filterSettingRepository = $filterSettingRepository;
        $this->filterSettingFactory = $filterSettingFactory;
        $this->configFactory = $configFactory;
        $this->filterSettingHelper = $filterSettingHelper;
        $this->serializer = $serializer;
        $this->cacheTypeList = $typeList;
        $this->optionSettingRepository = $optionSettingRepository;
    }

    /**
     * @param EavAttributeResource $subject
     * @param \Closure $proceed
     * @return mixed
     * @throws \Exception
     */
    public function aroundSave(EavAttributeResource $subject, \Closure $proceed)
    {
        if (!$subject->hasData('attribute_code')) {
            return $proceed();
        }

        $filterSetting = $this->getFilterSetting($subject);
        $this->prepareFilterSettingData($subject, $filterSetting);

        $connection = $filterSetting->getResource()->getConnection();
        $result = $proceed();
        try {
            $connection->beginTransaction();
            $this->deleteFromAmshopbyOptionSettings($subject);
            $this->filterSettingRepository->save($filterSetting);

            foreach ($this->filterSettingHelper->getKeyValueForCategoryFilterConfig() as $dataKey => $configPath) {
                if ($subject->getData($dataKey) !== null) {
                    $configModel = $this->configFactory->create();
                    $configModel->setDataByPath($configPath, $subject->getData($dataKey));
                    $configModel->save();
                }
            }

            $connection->commit();
            $this->cacheTypeList->invalidate(Type::TYPE_IDENTIFIER);
        } catch (\Exception $e) {
            $connection->rollBack();
            throw $e;
        }

        return $result;
    }

    private function getFilterSetting(EavAttributeResource $attributeResource): FilterSettingInterface
    {
        try {
            $filterSetting = $this->filterSettingRepository->getByAttributeCode($attributeResource->getAttributeCode());
        } catch (NoSuchEntityException $e) {
            $filterSetting = $this->filterSettingFactory->create();
        }

        return $filterSetting;
    }

    private function prepareData(array $data): array
    {
        $multipleData = ['categories_filter', 'attributes_filter', 'attributes_options_filter'];
        $sliderRange = ['slider_min', 'slider_max'];

        foreach ($multipleData as $multiple) {
            if (array_key_exists($multiple, $data) && is_array($data[$multiple])) {
                $data[$multiple] = implode(',', array_filter($data[$multiple], [$this, 'callbackNotEmpty']));
            } elseif (!array_key_exists($multiple, $data)) {
                $data[$multiple] = '';
            }
        }

        foreach ($sliderRange as $slider) {
            if (!isset($data[$slider]) || $data[$slider] === '') {
                $data[$slider] = null;
            }
        }

        return $data;
    }

    private function callbackNotEmpty(string $element): bool
    {
        return $element !== '';
    }

    private function prepareFilterSettingData(
        EavAttributeResource $attributeResource,
        FilterSettingInterface $filterSetting
    ): void {
        $data = $this->prepareData($attributeResource->getData());
        $data['tooltip'] = $this->serializer->serialize($data['tooltip'] ?? '');
        $data['attribute_url_alias'] =
            isset($data['attribute_url_alias'])
                ? $this->serializer->serialize($data['attribute_url_alias'])
                : '';
        //in the case of a conflict when column 'tooltip' exists in catalog_eav_attribute
        $attributeResource->setData('tooltip', null);
        $filterSetting->addData($data);

        $currentFilterCode = $filterSetting->getAttributeCode();
        if (empty($currentFilterCode)) {
            $filterSetting->setAttributeCode($attributeResource->getAttributeCode());
        }
    }

    private function deleteFromAmshopbyOptionSettings(EavAttributeResource $attributeResource): void
    {
        $option = $attributeResource->getOption();

        if (isset($option['delete'])) {
            foreach ($option['delete'] as $optionId => $value) {
                if ($value) {
                    $this->optionSettingRepository->deleteByOptionId($optionId);
                }
            }
        }
    }
}
