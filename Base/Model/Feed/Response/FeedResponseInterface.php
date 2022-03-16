<?php

declare(strict_types=1);

namespace Dotsquares\Base\Model\Feed\Response;

interface FeedResponseInterface
{
    public function getContent(): ?string;

    public function setContent(?string $content): FeedResponseInterface;

    public function getStatus(): ?string;

    public function setStatus(?string $status): FeedResponseInterface;

    public function isNeedToUpdateCache(): bool;

    public function isFailed(): bool;
}
