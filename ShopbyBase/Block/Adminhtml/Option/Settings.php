<?php

declare(strict_types=1);

namespace Dotsquares\ShopbyBase\Block\Adminhtml\Option;

use Dotsquares\ShopbyBase\Api\Data\FilterSettingInterface;
use Dotsquares\ShopbyBase\Block\Adminhtml\Form\Renderer\Fieldset\Element;
use Dotsquares\ShopbyBase\Helper\OptionSetting;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;

/**
 * @api
 */
class Settings extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var Element
     */
    protected $renderer;

    /**
     * @var OptionSetting
     */
    protected $settingHelper;

    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Element $renderer,
        OptionSetting $settingHelper,
        array $data = []
    ) {
        $this->renderer = $renderer;
        $this->settingHelper = $settingHelper;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _prepareForm()
    {
        $attributeCode = $this->getRequest()->getParam(FilterSettingInterface::ATTRIBUTE_CODE);
        $optionId = $this->getRequest()->getParam('option_id');
        $storeId = $this->getRequest()->getParam('store', 0);
        $model = $this->settingHelper->getSettingByValue($optionId, $attributeCode, $storeId);
        $model->setCurrentStoreId($storeId);

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'edit_options_form',
                    'class' => 'admin__scope-old',
                    'action' => $this->getUrl('*/*/save', [
                        'option_id' => (int)$optionId,
                        'attribute_code' => $attributeCode,
                        'store' => (int)$storeId
                    ]),
                    'method' => 'post',
                    'enctype' => 'multipart/form-data',
                ],
            ]
        );
        $form->setUseContainer(true);
        $form->setFieldsetElementRenderer($this->renderer);
        $form->setDataObject($model);

        $this->_eventManager->dispatch(
            'dsshopby_option_form_build_after',
            [
                'form' => $form,
                'setting' => $model,
                'store_id' => $storeId
            ]
        );

        $form->setValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
