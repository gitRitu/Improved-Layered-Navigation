<?php

namespace Dotsquares\ShopbyBase\Controller\Adminhtml\Option;

use Dotsquares\ShopbyBase\Api\Data\OptionSettingRepositoryInterface;
use Dotsquares\ShopbyBase\Api\Data\OptionSettingInterface;
use Dotsquares\ShopbyBase\Helper\FilterSetting;
use Dotsquares\ShopbyBase\Model\Cache\Type;
use Magento\Backend\App\Action;
use Magento\CatalogSearch\Model\Indexer\Fulltext;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Indexer\IndexerRegistry;
use Magento\Store\Model\Store;
use Psr\Log\LoggerInterface;

class Save extends \Dotsquares\ShopbyBase\Controller\Adminhtml\Option
{
    /**
     * @var  TypeListInterface
     */
    private $cacheTypeList;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var OptionSettingRepositoryInterface
     */
    private $optionSettingRepository;

    /**
     * @var IndexerRegistry
     */
    private $indexerRegistry;

    public function __construct(
        Action\Context $context,
        TypeListInterface $typeList,
        LoggerInterface $logger,
        OptionSettingRepositoryInterface $optionSettingRepository,
        IndexerRegistry $indexerRegistry
    ) {
        parent::__construct($context);
        $this->cacheTypeList = $typeList;
        $this->logger = $logger;
        $this->optionSettingRepository = $optionSettingRepository;
        $this->indexerRegistry = $indexerRegistry;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        $filterCode = FilterSetting::ATTR_PREFIX . $this->getRequest()->getParam('attribute_code');
        $optionId = (int)$this->getRequest()->getParam('option_id');
        $storeId = (int)$this->getRequest()->getParam('store', Store::DEFAULT_STORE_ID);
        if ($data = $this->getRequest()->getPostValue()) {
            try {
                $issetUrlAlias = isset($data['url_alias']) && $data['url_alias'];
                if ($issetUrlAlias && !$this->isUniqueAlias($data['url_alias'], $optionId)) {
                    $this->messageManager->addErrorMessage(
                        __('A brand with the same URL alias already exists. Please enter a unique value.')
                    );
                    if ($this->getRequest()->isAjax()) {
                        $this->_redirectRefer();
                    } else {
                        $this->redirectBack($filterCode, $optionId, $storeId);
                    }

                    return;
                }
                /** @var \Dotsquares\ShopbyBase\Model\OptionSetting $model */
                $model = $this->_objectManager->create(\Dotsquares\ShopbyBase\Model\OptionSetting::class);
                $data = $this->filterData($data);

                if ($storeId != Store::DEFAULT_STORE_ID) {
                    $this->checkDefaultOption($filterCode, $optionId);
                }
                $model->saveData($filterCode, $optionId, $storeId, $data);

                $this->indexerRegistry->get(Fulltext::INDEXER_ID)->invalidate();
                $this->cacheTypeList->invalidate(Type::TYPE_IDENTIFIER);
                $this->messageManager->addSuccessMessage(__('You saved the item.'));
                $this->_session->setPageData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->redirectBack($filterCode, $optionId, $storeId);
                    return;
                }
                $this->_redirectRefer();
                return;
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->_redirectRefer();
                return;
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(
                    __('Something went wrong while saving the item data. Please review the error log.')
                );
                $this->logger->critical($e);
                $this->_session->setPageData($data);
                $this->_redirectRefer();
                return;
            }
        }
        $this->_redirectRefer();
    }

    private function redirectBack(string $filterCode, int $optionId, int $storeId): void
    {
        //phpcs:ignore Magento2.Legacy.ObsoleteResponse.RedirectResponseMethodFound
        $this->_redirect(
            '*/*/edit',
            [
                'attribute_code' => $filterCode,
                'option_id' => $optionId,
                'store' => $storeId
            ]
        );
    }

    protected function _redirectRefer()
    {
        //phpcs:ignore Magento2.Legacy.ObsoleteResponse.ForwardResponseMethodFound
        $this->_forward('settings');
    }

    /**
     * @param $data
     *
     * @return mixed
     */
    protected function filterData($data)
    {
        $inputFilter = new \Zend_Filter_Input(
            [],
            [],
            $data
        );
        $data = $inputFilter->getUnescaped();
        $data[OptionSettingInterface::TOP_CMS_BLOCK_ID]
            = ($data[OptionSettingInterface::TOP_CMS_BLOCK_ID] ?? '') ?: null;
        $data[OptionSettingInterface::BOTTOM_CMS_BLOCK_ID]
            = ($data[OptionSettingInterface::BOTTOM_CMS_BLOCK_ID] ?? '') ?: null;

        return $data;
    }

    private function isUniqueAlias(string $alias, int $optionId): bool
    {
        try {
            $option = $this->optionSettingRepository->get($alias, 'url_alias');
            $isUnique = $option->getValue() == $optionId;
        } catch (NoSuchEntityException $e) {
            $isUnique = true;
        }

        return $isUnique;
    }

    private function checkDefaultOption(string $filterCode, int $optionId): void
    {
        $setting = $this->optionSettingRepository->getByParams($filterCode, $optionId, Store::DEFAULT_STORE_ID);
        if (!$setting->getId()) {
            $setting->saveData($filterCode, $optionId, Store::DEFAULT_STORE_ID, []);
        }
    }
}
