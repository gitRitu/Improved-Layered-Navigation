<?php

namespace Dotsquares\Base\Block\Adminhtml;

use Magento\Backend\Block\Template;

class Import extends Template
{
    /**
     * @var string
     */
    private $importEntityTypeCode;

    public function __construct(
        Template\Context $context,
        array $data = []
    ) {
        if (empty($data['entityTypeCode'])) {
            throw new \Dotsquares\Base\Exceptions\EntityTypeCodeNotSet();
        }
        $this->importEntityTypeCode = $data['entityTypeCode'];
        parent::__construct($context, $data);
    }

    public function getImportEntity()
    {
        return $this->importEntityTypeCode;
    }
}
