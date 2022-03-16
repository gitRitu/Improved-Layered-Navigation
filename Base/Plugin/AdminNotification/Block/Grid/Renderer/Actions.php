<?php

namespace Dotsquares\Base\Plugin\AdminNotification\Block\Grid\Renderer;

use Magento\AdminNotification\Block\Grid\Renderer\Actions as NativeActions;

class Actions
{
    /**
     * @var \Magento\Framework\UrlInterface
     */
    private $urlBuilder;

    public function __construct(
        \Magento\Framework\UrlInterface $urlBuilder
    ) {
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @param NativeActions $subject
     * @param \Closure $proceed
     * @param \Magento\Framework\DataObject $row
     *
     * @return string
     */
    public function aroundRender(
        NativeActions $subject,
        \Closure $proceed,
        \Magento\Framework\DataObject $row
    ) {
        $result = $proceed($row);
        if ($row->getData('is_dotsquares')) {
            $result .= sprintf(
                '<a class="action" href="%s" title="%2$s">%2$s</a>',
                $this->urlBuilder->getUrl('dsbase/notification/frequency', ['action' => 'less']),
                __('Show less of these messages')
            );
            $result .= sprintf(
                '<a class="action" href="%s" title="%2$s">%2$s</a>',
                $this->urlBuilder->getUrl('dsbase/notification/frequency', ['action' => 'more']),
                __('Show more of these messages')
            );
            $result .= sprintf(
                '<a class="action" href="%s" title="%2$s">%2$s</a>',
                $this->urlBuilder->getUrl(
                    'adminhtml/system_config/edit',
                    ['section' => 'dotsquares_base', '_fragment' => 'dotsquares_base_notifications-head']
                ),
                __('Unsubscribe')
            );
        }

        return  $result;
    }
}
