<?php

namespace Dotsquares\Base\Plugin\AdminNotification\Block\Grid\Renderer;

use Magento\AdminNotification\Block\Grid\Renderer\Notice as NativeNotice;

class Notice
{
    public function aroundRender(
        NativeNotice $subject,
        \Closure $proceed,
        \Magento\Framework\DataObject $row
    ) {
        $result = $proceed($row);

        $dotsquaresLogo = '';
        $dotsquaresImage = '';
        if ($row->getData('is_dotsquares')) {
            if ($row->getData('image_url')) {
                $dotsquaresImage = ' style="background: url(' . $row->getData("image_url") . ') no-repeat;"';
            } else {
                $dotsquaresLogo = ' dotsquares-grid-logo';
            }
        }
        $result = '<div class="dsbase-grid-message' . $dotsquaresLogo . '"' . $dotsquaresImage . '>' . $result . '</div>';

        return  $result;
    }
}
