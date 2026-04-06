<?php

declare(strict_types=1);

namespace SocialLinks;

final class Config
{
    public function __construct(
        public readonly bool $usePredefinedProfiles = true,
        public readonly bool $trimInput = true,
        public readonly bool $allowQueryParams = false,
    ) {}
}
