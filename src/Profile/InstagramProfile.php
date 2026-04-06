<?php

declare(strict_types=1);

namespace SocialLinks\Profile;

use SocialLinks\ProfileMatch;
use SocialLinks\Type;

final class InstagramProfile
{
    public static function get(): array
    {
        return [
            new ProfileMatch(
                match: '(https?://)?(www.)?instagram.com/({PROFILE_ID})/?',
                group: 3,
                type: Type::DESKTOP,
                pattern: 'https://instagram.com/{PROFILE_ID}',
            ),
            new ProfileMatch(
                match: '(https?://)?m.instagram.com/({PROFILE_ID})/?',
                group: 2,
                type: Type::MOBILE,
                pattern: 'https://m.instagram.com/{PROFILE_ID}',
            ),
            new ProfileMatch(
                match: '@?({PROFILE_ID})',
                group: 1,
            ),
        ];
    }
}
