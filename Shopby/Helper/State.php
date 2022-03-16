<?php

namespace Dotsquares\Shopby\Helper;

use Magento\Framework\App\Helper\Context;
use Dotsquares\ShopbyBase\Api\UrlBuilderInterface;

class State extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var UrlBuilderInterface
     */
    private $dsUrlBuilder;

    public function __construct(
        Context $context,
        UrlBuilderInterface $dsUrlBuilder
    ) {
        parent::__construct($context);
        $this->dsUrlBuilder = $dsUrlBuilder;
    }

    /**
     * @return mixed
     */
    public function getCurrentUrl()
    {
        $params['_current'] = true;
        $params['_use_rewrite'] = true;
        $params['_query'] = ['_' => null, 'shopbyAjax' => null, 'dt' => null, 'df' => null];

        $result = str_replace('&amp;', '&', $this->dsUrlBuilder->getUrl('*/*/*', $params));
        return $result;
    }
}
