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
            new ProfileMatch(
                match: '({PROFILE_ID})',
                group: 1,
            ),
        ];
    }
}
