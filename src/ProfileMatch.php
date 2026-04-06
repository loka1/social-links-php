<?php

declare(strict_types=1);

namespace SocialLinks;

final class ProfileMatch
{
    public function __construct(
        public readonly string $match,
        public readonly int $group,
        public readonly ?int $type = null,
        public readonly ?string $pattern = null,
    ) {}
}
