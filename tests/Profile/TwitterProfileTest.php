<?php

declare(strict_types=1);

namespace SocialLinks\Tests\Profile;

use PHPUnit\Framework\TestCase;
use SocialLinks\SocialLinks;
use SocialLinks\Type;

final class TwitterProfileTest extends TestCase
{
    private SocialLinks $sl;

    protected function setUp(): void
    {
        $this->sl = new SocialLinks();
    }

    public function testTwitter(): void
    {
        $profile = 'twitter';
        $profileId = 'gkucmierz';
        $desktop = "https://x.com/{$profileId}";
        $mobile = "https://x.com/{$profileId}";

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

    public function testTwitterComSanitizesToXCom(): void
    {
        $this->assertSame('https://x.com/gkucmierz', $this->sl->sanitize('twitter', 'https://twitter.com/gkucmierz'));
        $this->assertSame('https://x.com/gkucmierz', $this->sl->sanitize('twitter', 'https://www.twitter.com/gkucmierz'));
    }
}
