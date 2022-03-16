<?php
/**
* @author Dotsquares Team
* @copyright Copyright (c) 2022 Dotsquares (https://www.amasty.com)
* @package Dotsquares_Base
*/


namespace Dotsquares\Base\Block\Adminhtml;

class Messages extends \Magento\Backend\Block\Template
{
    public const DOTSQUARES_BASE_SECTION_NAME = 'dotsquares_base';
    /**
     * @var \Dotsquares\Base\Model\AdminNotification\Messages
     */
    private $messageManager;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    private $request;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Dotsquares\Base\Model\AdminNotification\Messages $messageManager,
        \Magento\Framework\App\Request\Http $request,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->messageManager = $messageManager;
        $this->request = $request;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messageManager->getMessages();
    }

    /**
     * @return string
     */
    public function _toHtml()
    {
        $html  = '';
        if ($this->request->getParam('section') === self::DOTSQUARES_BASE_SECTION_NAME) {
            $html = parent::_toHtml();
        }

        return $html;
    }
}
