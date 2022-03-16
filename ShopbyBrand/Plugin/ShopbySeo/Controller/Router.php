<?php

namespace Dotsquares\ShopbyBrand\Plugin\ShopbySeo\Controller;

use Magento\Framework\App\RequestInterface;

class Router
{
    public const SINGLE_PARAM = 1;

    /**
     * @var \Dotsquares\ShopbyBrand\Helper\Data
     */
    private $brandHelper;

    public function __construct(\Dotsquares\ShopbyBrand\Helper\Data $brandHelper)
    {
        $this->brandHelper = $brandHelper;
    }

    /**
     * @param $subject
     * @param callable $proceed
     * @param RequestInterface $request
     * @param $identifier
     * @param $params
     */
    public function aroundModifyRequest(
        $subject,
        callable $proceed,
        RequestInterface $request,
        $identifier,
        $params
    ) {
        $brandAttributeCode = $this->brandHelper->getBrandAttributeCode();
        $brandUrlKey = $this->brandHelper->getBrandUrlKey();
        if (count($params) == self::SINGLE_PARAM
            && isset($params[$brandAttributeCode])
            && trim($identifier, '/') == $brandUrlKey
        ) {
            return $subject;
        }

        return $proceed($request, $identifier, $params);
    }
}
