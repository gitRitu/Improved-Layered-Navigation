<?php

namespace Dotsquares\Base\Plugin\Backend\Model\Menu;

use Dotsquares\Base\Model\Feed\ExtensionsProvider;
use Dotsquares\Base\Model\ModuleInfoProvider;
use Magento\Backend\Model\Menu\Item as NativeItem;

class Item
{
    public const BASE_MARKETPLACE = 'Dotsquares_Base::marketplace';

    public const SEO_PARAMS = '?utm_source=extension&utm_medium=backend&utm_campaign=main_menu_to_user_guide';

    public const MARKET_URL = 'https://marketplace.magento.com/partner/dotsquaresltd';

    public const MAGENTO_MARKET_URL = 'https://marketplace.magento.com/partner/dotsquaresltd';

    /**
     * @var ExtensionsProvider
     */
    private $extensionsProvider;

    /**
     * @var ModuleInfoProvider
     */
    private $moduleInfoProvider;

    public function __construct(
        ExtensionsProvider $extensionsProvider,
        ModuleInfoProvider $moduleInfoProvider
    ) {
        $this->extensionsProvider = $extensionsProvider;
        $this->moduleInfoProvider = $moduleInfoProvider;
    }

    /**
     * @param NativeItem $subject
     * @param $url
     *
     * @return string
     */
    public function afterGetUrl(NativeItem $subject, $url)
    {
        $id = $subject->getId();
        if ($id === self::BASE_MARKETPLACE) {
            $url = $this->moduleInfoProvider->isOriginMarketplace() ? self::MAGENTO_MARKET_URL : self::MARKET_URL;
        }

        /* we can't add guide link into item object - find link again */
        if (strpos($id, '::menuguide') !== false
            && strpos($id, 'Dotsquares') !== false
        ) {
            $moduleCode = explode('::', $subject->getId());
            $moduleCode = $moduleCode[0];
            $moduleInfo = $this->extensionsProvider->getFeedModuleData($moduleCode);
            if (isset($moduleInfo['guide']) && $moduleInfo['guide']) {
                $url = $moduleInfo['guide'];
                $seoLink = self::SEO_PARAMS;
                if (strpos($url, '?') !== false) {
                    $seoLink = str_replace('?', '&', $seoLink);
                }
                $url .= $seoLink;
            } else {
                $url = '';
            }
        }

        return $url;
    }
}
