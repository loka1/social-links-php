<?php

declare(strict_types=1);

namespace SocialLinks\Tests\Profile;

use PHPUnit\Framework\TestCase;
use SocialLinks\SocialLinks;
use SocialLinks\Type;

final class LinkedInProfileTest extends TestCase
{
    private SocialLinks $sl;

    protected function setUp(): void
    {
        $this->sl = new SocialLinks();
    }

    public function testLinkedin(): void
    {
        $profile = 'linkedin';
        $profileId = 'gkucmierz';
        $desktop = "https://linkedin.com/in/{$profileId}";
        $mobile = "https://linkedin.com/mwlite/in/{$profileId}";

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

    public function testLocalizedUrls(): void
    {
        $profile = 'linkedin';
        $this->assertTrue($this->sl->isValid($profile, 'https://de.linkedin.com/in/anton-begehr/'));
        $this->assertTrue($this->sl->isValid($profile, 'https://de.linkedin.com/mwlite/in/anton-begehr/'));
        $this->assertSame('https://linkedin.com/in/anton-begehr', $this->sl->sanitize($profile, 'https://de.linkedin.com/in/anton-begehr/'));
        $this->assertSame('https://linkedin.com/mwlite/in/anton-begehr', $this->sl->sanitize($profile, 'https://de.linkedin.com/mwlite/in/anton-begehr/', Type::MOBILE));
    }
}
