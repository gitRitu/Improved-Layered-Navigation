<?php
namespace Dotsquares\Megamenu\Block\Adminhtml\System\Config;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Dotsquares\Megamenu\Model\Attribute\Source\CategoryLayout;

/**
 * Class DependeciesScJsTemplate
 * @package Dotsquares\Megamenu\Block\Adminhtml\System\Config
 */
class DependeciesScJsTemplate extends \Magento\Backend\Block\Template
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;

    /**
     * @var CategoryRepositoryInterface
     */
    protected $_categoryRepository;

    /**
     * @var string
     */
    protected $_template = 'Dotsquares_Megamenu::system/config/dependencies_sc_js.phtml';

    /**
     * DependeciesJsTemplate constructor.
     * @param CategoryRepositoryInterface $categoryRepository
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    )
    {
        $this->_registry = $registry;
        $this->_categoryRepository = $categoryRepository;
        parent::__construct($context, $data);
    }

    protected function _construct()
    {
        $this->setData('template', $this->_template);
        parent::_construct();
    }

    /**
     * @return bool
     */
    public function areSubcategoryOptionsVisible() {
        $currentCategory = $this->_registry->registry('current_category');
        if (!$currentCategory->getId()) {
            $parentRequestCategId = $this->getRequest()->getParam('parent');
            $storeId = $this->getRequest()->getParam('store');
            $parentCategory = $this->_categoryRepository->get($parentRequestCategId, $storeId);
            $subcategoriesLayout = $parentCategory->getData('dotsquares_sc_layout');
        } else {
            $subcategoriesLayout = $currentCategory->getParentCategory()->getData('dotsquares_sc_layout');
        }

        if ($subcategoriesLayout == CategoryLayout::LAYOUT_IMAGES) {
            return true;
        }

        return false;
    }
}
