<?php

declare(strict_types=1);

namespace Dotsquares\Base\Utils\Http\Response;

use Dotsquares\Base\Model\SimpleDataObject;
use Dotsquares\Base\Model\SimpleDataObjectFactory;
use Dotsquares\Base\Utils\Http\Response\Entity\ConfigPool;
use Dotsquares\Base\Utils\Http\Response\Entity\Converter;
use Magento\Framework\Exception\NotFoundException;

class ResponseFactory
{
    /**
     * @var Converter
     */
    private $converter;

    /**
     * @var ConfigPool
     */
    private $configPool;

    /**
     * @var SimpleDataObjectFactory
     */
    private $simpleDataObjectFactory;

    public function __construct(
        Converter $converter,
        ConfigPool $configPool,
        SimpleDataObjectFactory $simpleDataObjectFactory
    ) {
        $this->converter = $converter;
        $this->configPool = $configPool;
        $this->simpleDataObjectFactory = $simpleDataObjectFactory;
    }

    /**
     * @param string $url
     * @param mixed $response
     * @return SimpleDataObject
     */
    public function create(string $url, $response): SimpleDataObject
    {
        try {
            // phpcs:disable Magento2.Functions.DiscouragedFunction.Discouraged
            $path = parse_url($url, PHP_URL_PATH);
            $entityConfig = $this->configPool->get($path);
            if ($entityConfig->getType() == 'array') {
                $object = [];
                foreach ($response as $row) {
                    $object[] = $this->converter->convertToObject($row, $entityConfig);
                }
            } else {
                $object = $this->converter->convertToObject($response, $entityConfig);
            }
        } catch (NotFoundException $e) {
            $object = $this->simpleDataObjectFactory->create(['data' => $response ?? []]);
        }

        return $object;
    }
}
