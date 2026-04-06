<?php

declare(strict_types=1);

namespace SocialLinks\Profile;

use SocialLinks\ProfileMatch;
use SocialLinks\Type;

final class TwitterProfile
{
    public static function get(): array
    {
        return [
            new ProfileMatch(
                match: '(https?://)?(www.)?(twitter.com|x.com)/@?({PROFILE_ID})/?',
                group: 4,
                type: Type::DESKTOP,
                pattern: 'https://x.com/{PROFILE_ID}',
            ),
            new ProfileMatch(
                match: '(https?://)?mobile.twitter.com/@?({PROFILE_ID})/?',
                group: 2,
                type: Type::MOBILE,
                pattern: 'https://x.com/{PROFILE_ID}',
            ),
            new ProfileMatch(
                match: '@?({PROFILE_ID})',
                group: 1,
            ),
        ];
    }
}
