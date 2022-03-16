<?php

namespace Dotsquares\Base\Model\Import\Behavior;

class BehaviorProvider implements BehaviorProviderInterface
{
    /**
     * @var \Dotsquares\Base\Model\Import\Behavior\BehaviorInterface[]
     */
    private $behaviors;

    public function __construct($behaviors)
    {
        $this->behaviors = [];
        foreach ($behaviors as $behaviorCode => $behavior) {
            if (!($behavior instanceof BehaviorInterface)) {
                throw new \Dotsquares\Base\Exceptions\WrongBehaviorInterface();
            }

            $this->behaviors[$behaviorCode] = $behavior;
        }
    }

    /**
     * @inheritdoc
     */
    public function getBehavior($behaviorCode)
    {
        if (!isset($this->behaviors[$behaviorCode])) {
            throw new \Dotsquares\Base\Exceptions\NonExistentImportBehavior();
        }
        return $this->behaviors[$behaviorCode];
    }
}
