<?php

declare(strict_types=1);

namespace Dotsquares\ShopbyBase\Block\Adminhtml\Option;

class StoreSwitcher extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $optionId = $this->getRequest()->getParam('option_id');
        $filterCode = $this->getRequest()->getParam('attribute_code');
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'preview_form',
                    'action' => $this->getUrl('*/*/settings', [
                        'option_id' => (int)$optionId,
                        'attribute_code' => $filterCode
                    ]),
                ],
            ]
        );
        $form->setUseContainer(true);
        $form->addField('preview_selected_store', 'hidden', ['name' => 'store', 'id'=>'preview_selected_store']);

        $this->setForm($form);
        return parent::_prepareForm();
    }
}
