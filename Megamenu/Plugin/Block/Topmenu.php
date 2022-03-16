<?php

namespace Dotsquares\Megamenu\Plugin\Block;

use Magento\Catalog\Model\Category;
use Magento\Framework\Data\Collection;
use Magento\Framework\Data\Tree\Node;

/**
 * Plugin for top menu block
 */
class Topmenu
{
    /**
     * Catalog category
     *
     * @var \Magento\Catalog\Helper\Category
     */
    protected $catalogCategory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Magento\Catalog\Model\Layer\Resolver
     */
    private $layerResolver;

    /**
     * Initialize dependencies.
     *
     * @param \Magento\Catalog\Helper\Category $catalogCategory
     * @param \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     */
    public function __construct(
        \Magento\Catalog\Helper\Category $catalogCategory,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver
    ) {
        $this->catalogCategory = $catalogCategory;
        $this->collectionFactory = $categoryCollectionFactory;
        $this->storeManager = $storeManager;
        $this->layerResolver = $layerResolver;
    }

    /**
     * Build category tree for menu block.
     *
     * @param \Magento\Theme\Block\Html\Topmenu $subject
     * @param string $outermostClass
     * @param string $childrenWrapClass
     * @param int $limit
     * @return void
     * @SuppressWarnings("PMD.UnusedFormalParameter")
     */
    public function beforeGetHtml(
        \Magento\Theme\Block\Html\Topmenu $subject,
        $outermostClass = '',
        $childrenWrapClass = '',
        $limit = 0
    ) {
        $rootId = $this->storeManager->getStore()->getRootCategoryId();
        $storeId = $this->storeManager->getStore()->getId();
        /** @var \Magento\Catalog\Model\ResourceModel\Category\Collection $collection */
        $collection = $this->getCategoryTree($storeId, $rootId);
        $currentCategory = $this->getCurrentCategory();
        $mapping = [$rootId => $subject->getMenu()];  // use nodes stack to avoid recursion
        foreach ($collection as $category) {
            $categoryParentId = $category->getParentId();
            if (!isset($mapping[$categoryParentId])) {
                $parentIds = $category->getParentIds();
                foreach ($parentIds as $parentId) {
                    if (isset($mapping[$parentId])) {
                        $categoryParentId = $parentId;
                    }
                }
            }

            /** @var Node $parentCategoryNode */
            $parentCategoryNode = $mapping[$categoryParentId];

            $categoryNode = new Node(
                $this->getCategoryAsArray(
                    $category,
                    $currentCategory,
                    $category->getParentId() == $categoryParentId
                ),
                'id',
                $parentCategoryNode->getTree(),
                $parentCategoryNode
            );
            $parentCategoryNode->addChild($categoryNode);

            $mapping[$category->getId()] = $categoryNode; //add node in stack
        }
    }

    /**
     * Add list of associated identities to the top menu block for caching purposes.
     *
     * @param \Magento\Theme\Block\Html\Topmenu $subject
     * @return void
     */
    public function beforeGetIdentities(\Magento\Theme\Block\Html\Topmenu $subject)
    {
        $subject->addIdentity(Category::CACHE_TAG);
        $rootId = $this->storeManager->getStore()->getRootCategoryId();
        $storeId = $this->storeManager->getStore()->getId();
        /** @var \Magento\Catalog\Model\ResourceModel\Category\Collection $collection */
        $collection = $this->getCategoryTree($storeId, $rootId);
        $mapping = [$rootId => $subject->getMenu()];  // use nodes stack to avoid recursion
        foreach ($collection as $category) {
            if (!isset($mapping[$category->getParentId()])) {
                continue;
            }
            $subject->addIdentity(Category::CACHE_TAG . '_' . $category->getId());
        }
    }

    /**
     * Get current Category from catalog layer
     *
     * @return \Magento\Catalog\Model\Category
     */
    private function getCurrentCategory()
    {
        $catalogLayer = $this->layerResolver->get();

        if (!$catalogLayer) {
            return null;
        }

        return $catalogLayer->getCurrentCategory();
    }

    /**
     * Convert category to array
     *
     * @param \Magento\Catalog\Model\Category $category
     * @param \Magento\Catalog\Model\Category $currentCategory
     * @param bool $isParentActive
     * @return array
     */
    private function getCategoryAsArray($category, $currentCategory, $isParentActive)
    {
        $result =  [
            'name' => $category->getName(),
            'id' => 'category-node-' . $category->getId(),
            'url' => $this->catalogCategory->getCategoryUrl($category),
            'has_active' => in_array((string)$category->getId(), explode('/', $currentCategory->getPath()), true),
            'is_active' => $category->getId() == $currentCategory->getId(),
            'is_category' => true,
            'is_parent_active' => $isParentActive
        ];

        $categoryMenuData = $category->getData();
        $customCategoryUrl = null;

        if (isset($categoryMenuData['dotsquares_category_url'])) {
            $customCategoryUrl = trim($categoryMenuData['dotsquares_category_url']);
        }

        if (isset($customCategoryUrl) && strlen($customCategoryUrl)) {

            if (strpos($customCategoryUrl, 'http://') === 0 || strpos($customCategoryUrl, 'https://') === 0) {
                $result['url'] = $customCategoryUrl;
            } elseif ($customCategoryUrl == '#') {
                $result['url'] = 'javascript:void(0);';
            } else {
                $result['url'] = $this->storeManager->getStore()->getBaseUrl() . ltrim($customCategoryUrl, '//');
            }
        }

        $result['open_in_newtab'] = 0;
        if (isset($categoryMenuData['dotsquares_category_url_newtab']) && $categoryMenuData['dotsquares_category_url_newtab']) {
            $result['open_in_newtab'] = 1;
        }

	    $result['dotsquares_mm_display_mode'] = '';
        if (isset($categoryMenuData['dotsquares_mm_display_mode'])) {
	        $result['dotsquares_mm_display_mode'] = $categoryMenuData['dotsquares_mm_display_mode'];
        }

	    $result['dotsquares_mm_columns_number'] = '';
        if (isset($categoryMenuData['dotsquares_mm_columns_number'])) {
        	$colNumber = (int) $categoryMenuData['dotsquares_mm_columns_number'];
	        $result['dotsquares_mm_columns_number'] = $colNumber;
        }

	    $result['dotsquares_mm_column_width'] = '';
        if (isset($categoryMenuData['dotsquares_mm_column_width'])) {
	        $result['dotsquares_mm_column_width'] = $categoryMenuData['dotsquares_mm_column_width'];
        }

        $result['dotsquares_mm_top_block_type'] = '';
        if (isset($categoryMenuData['dotsquares_mm_top_block_type'])) {
            $result['dotsquares_mm_top_block_type'] = $categoryMenuData['dotsquares_mm_top_block_type'];
        }

        $result['dotsquares_mm_top_block_cms'] = '';
        if (isset($categoryMenuData['dotsquares_mm_top_block_cms'])) {
            $result['dotsquares_mm_top_block_cms'] = $categoryMenuData['dotsquares_mm_top_block_cms'];
        }

        $result['dotsquares_mm_top_block'] = '';
        if (isset($categoryMenuData['dotsquares_mm_top_block'])) {
            $result['dotsquares_mm_top_block'] = $categoryMenuData['dotsquares_mm_top_block'];
        }

        $result['dotsquares_mm_right_block_type'] = '';
        if (isset($categoryMenuData['dotsquares_mm_right_block_type'])) {
            $result['dotsquares_mm_right_block_type'] = $categoryMenuData['dotsquares_mm_right_block_type'];
        }

        $result['dotsquares_mm_right_block_cms'] = '';
        if (isset($categoryMenuData['dotsquares_mm_right_block_cms'])) {
            $result['dotsquares_mm_right_block_cms'] = $categoryMenuData['dotsquares_mm_right_block_cms'];
        }

        $result['dotsquares_mm_right_block'] = '';
        if (isset($categoryMenuData['dotsquares_mm_right_block'])) {
            $result['dotsquares_mm_right_block'] = $categoryMenuData['dotsquares_mm_right_block'];
        }

        $result['dotsquares_mm_bottom_block_type'] = '';
        if (isset($categoryMenuData['dotsquares_mm_bottom_block_type'])) {
            $result['dotsquares_mm_bottom_block_type'] = $categoryMenuData['dotsquares_mm_bottom_block_type'];
        }

        $result['dotsquares_mm_bottom_block_cms'] = '';
        if (isset($categoryMenuData['dotsquares_mm_bottom_block_cms'])) {
            $result['dotsquares_mm_bottom_block_cms'] = $categoryMenuData['dotsquares_mm_bottom_block_cms'];
        }

        $result['dotsquares_mm_bottom_block'] = '';
        if (isset($categoryMenuData['dotsquares_mm_bottom_block'])) {
            $result['dotsquares_mm_bottom_block'] = $categoryMenuData['dotsquares_mm_bottom_block'];
        }

        $result['dotsquares_mm_left_block_type'] = '';
        if (isset($categoryMenuData['dotsquares_mm_left_block_type'])) {
            $result['dotsquares_mm_left_block_type'] = $categoryMenuData['dotsquares_mm_left_block_type'];
        }

        $result['dotsquares_mm_left_block_cms'] = '';
        if (isset($categoryMenuData['dotsquares_mm_left_block_cms'])) {
            $result['dotsquares_mm_left_block_cms'] = $categoryMenuData['dotsquares_mm_left_block_cms'];
        }

        $result['dotsquares_mm_left_block'] = '';
        if (isset($categoryMenuData['dotsquares_mm_left_block'])) {
            $result['dotsquares_mm_left_block'] = $categoryMenuData['dotsquares_mm_left_block'];
        }

        $result['dotsquares_mm_mob_hide_allcat'] = 0;
        if (isset($categoryMenuData['dotsquares_mm_mob_hide_allcat'])) {
            $result['dotsquares_mm_mob_hide_allcat'] = $categoryMenuData['dotsquares_mm_mob_hide_allcat'];
        }

        $result['dotsquares_mm_font_color'] = '';
        if (isset($categoryMenuData['dotsquares_mm_font_color'])) {
            $result['dotsquares_mm_font_color'] = $categoryMenuData['dotsquares_mm_font_color'];
        }

        $result['dotsquares_mm_font_hover_color'] = '';
        if (isset($categoryMenuData['dotsquares_mm_font_hover_color'])) {
            $result['dotsquares_mm_font_hover_color'] = $categoryMenuData['dotsquares_mm_font_hover_color'];
        }

        $result['dotsquares_mm_show_arrows'] = 0;
        if (isset($categoryMenuData['dotsquares_mm_show_arrows']) && $categoryMenuData['dotsquares_mm_show_arrows']) {
            $result['dotsquares_mm_show_arrows'] = 1;
        }

        $result['dotsquares_mm_dynamic_sc_flag'] = 0;
        if (isset($categoryMenuData['dotsquares_mm_dynamic_sc_flag']) && $categoryMenuData['dotsquares_mm_dynamic_sc_flag']) {
            $result['dotsquares_mm_dynamic_sc_flag'] = 1;
        }

        $result['dotsquares_mm_dynamic_sc_opts'] = '';
        if (isset($categoryMenuData['dotsquares_mm_dynamic_sc_opts']) && $categoryMenuData['dotsquares_mm_dynamic_sc_opts']) {
            $result['dotsquares_mm_dynamic_sc_opts'] = $categoryMenuData['dotsquares_mm_dynamic_sc_opts'];
        }

        $result['dotsquares_mm_image_enable'] = 0;
        if (isset($categoryMenuData['dotsquares_mm_image_enable']) && $categoryMenuData['dotsquares_mm_image_enable']) {
            $result['dotsquares_mm_image_enable'] = 1;
        }

        $result['dotsquares_mm_image_height'] = '';
        if (isset($categoryMenuData['dotsquares_mm_image_height'])) {
            $result['dotsquares_mm_image_height'] = $categoryMenuData['dotsquares_mm_image_height'];
        }

        $result['dotsquares_mm_image_width'] = '';
        if (isset($categoryMenuData['dotsquares_mm_image_width'])) {
            $result['dotsquares_mm_image_width'] = $categoryMenuData['dotsquares_mm_image_width'];
        }

        $result['dotsquares_mm_image_name_align'] = 'center';
        if (isset($categoryMenuData['dotsquares_mm_image_name_align'])) {
            $result['dotsquares_mm_image_name_align'] = $categoryMenuData['dotsquares_mm_image_name_align'];
        }

        $result['dotsquares_mm_image'] = '';
        if (isset($categoryMenuData['dotsquares_mm_image'])) {
            $result['dotsquares_mm_image'] = $categoryMenuData['dotsquares_mm_image'];
        }

        $result['dotsquares_mm_label_text'] = '';
        if (isset($categoryMenuData['dotsquares_mm_label_text'])) {
            $result['dotsquares_mm_label_text'] = $categoryMenuData['dotsquares_mm_label_text'];
        }

        $result['dotsquares_mm_label_font_color'] = '';
        if (isset($categoryMenuData['dotsquares_mm_label_font_color'])) {
            $result['dotsquares_mm_label_font_color'] = $categoryMenuData['dotsquares_mm_label_font_color'];
        }

        $result['dotsquares_mm_label_background_color'] = '';
        if (isset($categoryMenuData['dotsquares_mm_label_background_color'])) {
            $result['dotsquares_mm_label_background_color'] = $categoryMenuData['dotsquares_mm_label_background_color'];
        }

        $result['dotsquares_mm_image_alt'] = '';
        if (isset($categoryMenuData['dotsquares_mm_image_alt'])) {
            $result['dotsquares_mm_image_alt'] = $categoryMenuData['dotsquares_mm_image_alt'];
        }

        $result['dotsquares_mm_label_position'] = '';
        if (isset($categoryMenuData['dotsquares_mm_label_position'])) {
            $result['dotsquares_mm_label_position'] = $categoryMenuData['dotsquares_mm_label_position'];
        }

        $result['dotsquares_mm_image_radius'] = '';
        if (isset($categoryMenuData['dotsquares_mm_image_radius'])) {
            $result['dotsquares_mm_image_radius'] = $categoryMenuData['dotsquares_mm_image_radius'];
        }

        $result['dotsquares_mm_image_position'] = '';
        if (isset($categoryMenuData['dotsquares_mm_image_position'])) {
            $result['dotsquares_mm_image_position'] = $categoryMenuData['dotsquares_mm_image_position'];
        }

        return $result;
    }

    /**
     * Get Category Tree
     *
     * @param int $storeId
     * @param int $rootId
     * @return \Magento\Catalog\Model\ResourceModel\Category\Collection
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function getCategoryTree($storeId, $rootId)
    {
        /** @var \Magento\Catalog\Model\ResourceModel\Category\Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->setStoreId($storeId);
        $collection->addAttributeToSelect(array(
        		'name',
		        'dotsquares_category_url',
		        'dotsquares_category_url_newtab',
		        'dotsquares_mm_display_mode',
		        'dotsquares_mm_columns_number',
		        'dotsquares_mm_column_width',
		        'dotsquares_mm_top_block_type',
		        'dotsquares_mm_top_block_cms',
		        'dotsquares_mm_top_block',
		        'dotsquares_mm_right_block',
                'dotsquares_mm_right_block_type',
                'dotsquares_mm_right_block_cms',
                'dotsquares_mm_bottom_block_type',
                'dotsquares_mm_bottom_block_cms',
		        'dotsquares_mm_bottom_block',
                'dotsquares_mm_left_block_type',
                'dotsquares_mm_left_block_cms',
		        'dotsquares_mm_left_block',
		        'dotsquares_mm_mob_hide_allcat',
		        'dotsquares_mm_font_color',
		        'dotsquares_mm_font_hover_color',
		        'dotsquares_mm_show_arrows',
		        'dotsquares_mm_dynamic_sc_flag',
		        'dotsquares_mm_dynamic_sc_opts',
		        'dotsquares_mm_image_enable',
		        'dotsquares_mm_image_height',
		        'dotsquares_mm_image_width',
		        'dotsquares_mm_image_name_align',
		        'dotsquares_mm_image',
                'dotsquares_mm_label_text',
                'dotsquares_mm_label_font_color',
                'dotsquares_mm_label_background_color',
                'dotsquares_mm_label_position',
                'dotsquares_mm_image_alt',
                'dotsquares_mm_image_radius',
                'dotsquares_mm_image_position'
            )
        );
        $collection->addFieldToFilter('path', ['like' => '1/' . $rootId . '/%']); //load only from store root
        $collection->addAttributeToFilter('include_in_menu', 1);
        $collection->addIsActiveFilter();
        $collection->addNavigationMaxDepthFilter();
        $collection->addUrlRewriteToResult();
        $collection->addOrder('level', Collection::SORT_ORDER_ASC);
        $collection->addOrder('position', Collection::SORT_ORDER_ASC);
        $collection->addOrder('parent_id', Collection::SORT_ORDER_ASC);
        $collection->addOrder('entity_id', Collection::SORT_ORDER_ASC);

        return $collection;
    }

    /**
     * Add active
     *
     * @param \Magento\Theme\Block\Html\Topmenu $subject
     * @param string[] $result
     * @return string[]
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetCacheKeyInfo(\Magento\Theme\Block\Html\Topmenu $subject, array $result)
    {
        $activeCategory = $this->getCurrentCategory();
        if ($activeCategory) {
            $result[] = Category::CACHE_TAG . '_' . $activeCategory->getId();
        }

        return $result;
    }
}
