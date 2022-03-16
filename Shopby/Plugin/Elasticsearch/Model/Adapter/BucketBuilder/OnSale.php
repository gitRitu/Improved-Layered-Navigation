<?php

namespace Dotsquares\Shopby\Plugin\Elasticsearch\Model\Adapter\BucketBuilder;

use Dotsquares\Shopby\Plugin\Elasticsearch\Model\Adapter\BucketBuilderInterface as BucketBuilderInterface;
use Magento\Framework\Search\Request\BucketInterface as RequestBucketInterface;
use Magento\Framework\Search\Dynamic\DataProviderInterface;

class OnSale implements BucketBuilderInterface
{
    public const ON_SALE_INDEX = 1;

    /**
     * @param RequestBucketInterface $bucket
     * @param array $queryResult
     * @return array
     */
    public function build(
        RequestBucketInterface $bucket,
        array $queryResult
    ) {
        $values = [];
        if (isset($queryResult['aggregations'][$bucket->getName()]['buckets'])) {
            foreach ($queryResult['aggregations'][$bucket->getName()]['buckets'] as $resultBucket) {
                if ($resultBucket['key'] == self::ON_SALE_INDEX) {
                    $values[self::ON_SALE_INDEX] = [
                        'value' => self::ON_SALE_INDEX,
                        'count' => $resultBucket['doc_count'],
                    ];
                }
            }
        }
        return $values;
    }
}