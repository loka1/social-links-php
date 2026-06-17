<?php

declare(strict_types=1);

namespace SocialLinks\Tests\Profile;

use PHPUnit\Framework\TestCase;
use SocialLinks\SocialLinks;

final class FacebookProfileTest extends TestCase
{
    private SocialLinks $sl;

    protected function setUp(): void
    {
        $this->sl = new SocialLinks();
    }

    public function testIsValidFacebookPUrl(): void
    {
        $this->assertTrue($this->sl->isValid('facebook', 'https://www.facebook.com/p/SEVEN-HANDS-For-Engineering-Services-100064069293753/'));
    }

    public function testGetProfileIdFacebookPUrl(): void
    {
        $this->assertSame(
            'SEVEN-HANDS-For-Engineering-Services-100064069293753',
            $this->sl->getProfileId('facebook', 'https://www.facebook.com/p/SEVEN-HANDS-For-Engineering-Services-100064069293753/')
        );
    }

    public function testSanitizeFacebookPUrl(): void
    {
        $this->assertSame(
            'https://facebook.com/p/SEVEN-HANDS-For-Engineering-Services-100064069293753',
            $this->sl->sanitize('facebook', 'https://www.facebook.com/p/SEVEN-HANDS-For-Engineering-Services-100064069293753/')
        );
    }
}
