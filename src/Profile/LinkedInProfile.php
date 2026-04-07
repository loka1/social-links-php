<?php

declare(strict_types=1);

namespace SocialLinks\Profile;

use SocialLinks\ProfileMatch;
use SocialLinks\Type;

final class LinkedInProfile
{
    public static function get(): array
    {
        return [
            // Personal profiles (/in/)
            new ProfileMatch(
                match: '(https?://)?([a-z]{2,3}.)?linkedin.com/in/({PROFILE_ID})/?',
                group: 3,
                type: Type::DESKTOP,
                pattern: 'https://linkedin.com/in/{PROFILE_ID}',
            ),
            new ProfileMatch(
                match: '(https?://)?([a-z]{2,3}.)?linkedin.com/mwlite/in/({PROFILE_ID})/?',
                group: 3,
                type: Type::MOBILE,
                pattern: 'https://linkedin.com/mwlite/in/{PROFILE_ID}',
            ),
            // Company pages
            new ProfileMatch(
                match: '(https?://)?([a-z]{2,3}.)?linkedin.com/company/({PROFILE_ID})/?',
                group: 3,
                type: Type::DESKTOP,
                pattern: 'https://linkedin.com/company/{PROFILE_ID}',
            ),
            // School pages
            new ProfileMatch(
                match: '(https?://)?([a-z]{2,3}.)?linkedin.com/school/({PROFILE_ID})/?',
                group: 3,
                type: Type::DESKTOP,
                pattern: 'https://linkedin.com/school/{PROFILE_ID}',
            ),
            // Showcase pages
            new ProfileMatch(
                match: '(https?://)?([a-z]{2,3}.)?linkedin.com/showcase/({PROFILE_ID})/?',
                group: 3,
                type: Type::DESKTOP,
                pattern: 'https://linkedin.com/showcase/{PROFILE_ID}',
            ),
            // Pub profiles
            new ProfileMatch(
                match: '(https?://)?([a-z]{2,3}.)?linkedin.com/pub/({PROFILE_ID}(/.*)?)/?',
                group: 3,
                type: Type::DESKTOP,
                pattern: 'https://linkedin.com/pub/{PROFILE_ID}',
            ),
            // Bare username
            new ProfileMatch(
                match: '({PROFILE_ID})',
                group: 1,
            ),
        ];
    }
}
