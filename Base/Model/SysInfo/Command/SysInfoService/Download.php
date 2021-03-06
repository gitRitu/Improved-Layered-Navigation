<?php

declare(strict_types=1);

namespace Dotsquares\Base\Model\SysInfo\Command\SysInfoService;

use Dotsquares\Base\Model\SysInfo\Formatter\Xml;
use Dotsquares\Base\Model\SysInfo\Formatter\XmlFactory;
use Dotsquares\Base\Model\SysInfo\Provider\Collector;
use Dotsquares\Base\Model\SysInfo\Provider\CollectorPool;
use Magento\Framework\Exception\NotFoundException;

class Download
{
    /**
     * @var Collector
     */
    private $collector;

    /**
     * @var XmlFactory
     */
    private $xmlFactory;

    public function __construct(Collector $collector, XmlFactory $xmlFactory)
    {
        $this->collector = $collector;
        $this->xmlFactory = $xmlFactory;
    }

    /**
     * @return Xml
     * @throws NotFoundException
     */
    public function execute()
    {
        $data = $this->collector->collect(CollectorPool::SYS_INFO_SERVICE_GROUP);

        return $this->xmlFactory->create(['data' => $data, 'rootNodeName' => 'info']);
    }
}
