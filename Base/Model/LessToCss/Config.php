<?php

namespace Dotsquares\Base\Model\LessToCss;

use Magento\Framework\Config\CacheInterface;
use Dotsquares\Base\Model\LessToCss\Config\Reader;

/**
 * Extension attributes config
 */
class Config extends \Magento\Framework\Config\Data
{
    public const CACHE_ID = 'dotsquares_less_to_css';

    /**
     * Initialize reader and cache.
     *
     * @param Reader $reader
     * @param CacheInterface $cache
     */
    public function __construct(
        Reader $reader,
        CacheInterface $cache
    ) {
        parent::__construct($reader, $cache, self::CACHE_ID);
    }
}
