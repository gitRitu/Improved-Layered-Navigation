<?php

namespace Dotsquares\Base\Model\Import\Behavior;

interface BehaviorProviderInterface
{
    /**
     * @param string $behaviorCode
     *
     * @throws \Dotsquares\Base\Exceptions\NonExistentImportBehavior
     * @return \Dotsquares\Base\Model\Import\Behavior\BehaviorInterface
     */
    public function getBehavior($behaviorCode);
}
