<?php

declare(strict_types=1);

namespace SocialLinks\Profile;

use SocialLinks\ProfileMatch;
use SocialLinks\Type;

final class FacebookProfile
{
    public static function get(): array
    {
        return [
            new ProfileMatch(
                match: '(https?://)?(www.)?facebook.com/({PROFILE_ID})/?',
                group: 3,
                type: Type::DESKTOP,
                pattern: 'https://facebook.com/{PROFILE_ID}',
            ),
            new ProfileMatch(
                match: '(https?://)?m.facebook.com/({PROFILE_ID})/?',
                group: 2,
                type: Type::MOBILE,
                pattern: 'https://m.facebook.com/{PROFILE_ID}',
            ),
            new ProfileMatch(
                match: '({PROFILE_ID})',
                group: 1,
            ),
        ];
    }
}
