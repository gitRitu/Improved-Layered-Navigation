<?php

namespace Dotsquares\ShopbySeo\Plugin\Shopby\Controller;

use Magento\Framework\App\RequestInterface;

class Router
{
    /**
     * @var \Dotsquares\ShopbySeo\Helper\Url
     */
    private $urlHelper;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var string
     */
    private $identifier;

    public function __construct(\Dotsquares\ShopbySeo\Helper\Url $urlHelper)
    {
        $this->urlHelper = $urlHelper;
    }

    /**
     * @param $subject
     * @param RequestInterface $request
     * @param $identifier
     * @return array
     */
    public function beforeCheckMatchExpressions($subject, RequestInterface $request, $identifier)
    {
        $this->request = $request;
        $this->identifier = $identifier;
        return [$request, $identifier];
    }

    /**
     * @param $subject
     * @param $result
     * @return bool
     */
    public function afterCheckMatchExpressions($subject, $result)
    {
        if ($this->urlHelper->isSeoUrlEnabled()) {
            $hasParams = $this->request->getMetaData(\Dotsquares\ShopbySeo\Helper\Data::HAS_PARSED_PARAMS)
                && !$this->request->getMetaData(\Dotsquares\ShopbySeo\Helper\Data::HAS_ROUTE_PARAMS);
            return $result || $hasParams;
        }
        return $result;
    }
}
