<?php

namespace Dotsquares\Shopby\Plugin\Elasticsearch\Model\Adapter\BucketBuilder;

use Dotsquares\Shopby\Plugin\Elasticsearch\Model\Adapter\BucketBuilderInterface as BucketBuilderInterface;
use Magento\Framework\Search\Request\BucketInterface as RequestBucketInterface;
use Magento\Framework\Search\Dynamic\DataProviderInterface;

class IsNew implements BucketBuilderInterface
{
    public const IS_NEW_FROM_INDEX = 1;

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
                $values[$resultBucket['key']] = [
                    'value' => $resultBucket['key'],
                    'count' => $resultBucket['doc_count'],
                ];
            }
        }
        return $values;
    }
}
