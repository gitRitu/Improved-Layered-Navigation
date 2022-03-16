<?php

declare(strict_types=1);

namespace Dotsquares\Base\Block\Adminhtml\System\Config\InformationBlocks;

use Dotsquares\Base\Block\Adminhtml\System\Config\Information;
use Dotsquares\Base\Model\ModuleInfoProvider;
use Magento\Framework\View\Element\Template;

class FeatureRequest extends Template
{
    public const FEATURE_LINK = 'https://products.amasty.com/request-a-feature';
    public const CAMPAIGN_NAME = 'request_a_feature';

    /**
     * @var string
     */
    protected $_template = 'Dotsquares_Base::config/information/feature_request.phtml';

    /**
     * @var ModuleInfoProvider
     */
    private $moduleInfoProvider;

    public function __construct(
        Template\Context $context,
        ModuleInfoProvider $moduleInfoProvider,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->moduleInfoProvider = $moduleInfoProvider;
    }

    public function getFeatureRequestLink(): string
    {
        return self::FEATURE_LINK . Information::SEO_PARAMS . self::CAMPAIGN_NAME;
    }

    public function isOriginMarketplace(): bool
    {
        return $this->moduleInfoProvider->isOriginMarketplace();
    }
}
