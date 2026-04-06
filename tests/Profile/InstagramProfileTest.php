<?php

declare(strict_types=1);

namespace SocialLinks\Tests\Profile;

use PHPUnit\Framework\TestCase;
use SocialLinks\SocialLinks;
use SocialLinks\Type;

final class InstagramProfileTest extends TestCase
{
    private SocialLinks $sl;

    protected function setUp(): void
    {
        $this->sl = new SocialLinks();
    }

    public function testInstagram(): void
    {
        $profile = 'instagram';
        $profileId = 'javascript.js';
        $desktop = "https://instagram.com/{$profileId}";
        $mobile = "https://m.instagram.com/{$profileId}";

        $this->assertTrue($this->sl->hasProfile($profile));

        $this->assertTrue($this->sl->isValid($profile, $desktop));
        $this->assertTrue($this->sl->isValid($profile, $mobile));

        $this->assertSame($profileId, $this->sl->getProfileId($profile, $desktop));
        $this->assertSame($profileId, $this->sl->getProfileId($profile, $mobile));

        $this->assertSame($desktop, $this->sl->getLink($profile, $profileId));
        $this->assertSame($desktop, $this->sl->getLink($profile, $profileId, Type::DESKTOP));
        $this->assertSame($mobile, $this->sl->getLink($profile, $profileId, Type::MOBILE));

        $this->assertSame($desktop, $this->sl->sanitize($profile, $desktop));
        $this->assertSame($desktop, $this->sl->sanitize($profile, $desktop, Type::DESKTOP));
        $this->assertSame($mobile, $this->sl->sanitize($profile, $mobile, Type::MOBILE));
    }
}
