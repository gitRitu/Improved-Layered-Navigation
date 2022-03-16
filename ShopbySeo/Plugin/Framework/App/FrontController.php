<?php

namespace Dotsquares\ShopbySeo\Plugin\Framework\App;

use Magento\Framework\App\FrontController as DefaultFronController;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;

class FrontController
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var \Dotsquares\ShopbyBase\Model\UrlBuilder
     */
    private $urlBuilder;

    /**
     * @var \Magento\Framework\App\ResponseInterface
     */
    private $response;

    /**
     * @var \Dotsquares\ShopbySeo\Helper\Data
     */
    private $helper;

    public function __construct(
        \Dotsquares\ShopbyBase\Model\UrlBuilder $urlBuilder,
        \Magento\Framework\App\ResponseInterface $response,
        \Dotsquares\ShopbySeo\Helper\Data $helper
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->response = $response;
        $this->helper = $helper;
    }

    /**
     * @param DefaultFronController $subject
     * @param RequestInterface $request
     * @return array
     */
    public function beforeDispatch(DefaultFronController $subject, RequestInterface $request)
    {
        $this->request = $request;
        return [$request];
    }

    /**
     * @param DefaultFronController $subject
     * @param ResponseInterface|\Magento\Framework\Controller\ResultInterface $result
     * @return ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function afterDispatch(DefaultFronController $subject, $result)
    {
        if ($this->request->getMetaData(\Dotsquares\ShopbySeo\Helper\Data::SEO_REDIRECT_FLAG)
            && $this->helper->isAllowedRequest($this->request)
        ) {
            $this->response->setRedirect($this->urlBuilder->getCurrentUrl(), \Zend\Http\Response::STATUS_CODE_302);
            $this->request->setDispatched(true);
            return $this->response;
        }
        return $result;
    }
}
