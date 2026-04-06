<?php

declare(strict_types=1);

namespace SocialLinks\Tests;

use PHPUnit\Framework\TestCase;
use SocialLinks\SocialLinks;
use SocialLinks\Type;
use SocialLinks\Config;
use SocialLinks\ProfileMatch;

final class SocialLinksTest extends TestCase
{
    private SocialLinks $sl;

    protected function setUp(): void
    {
        $this->sl = new SocialLinks();
    }

    public function testTypeDesktopIsDefined(): void
    {
        $this->assertSame(0, Type::DESKTOP);
    }

    public function testTypeMobileIsDefined(): void
    {
        $this->assertSame(1, Type::MOBILE);
    }

    public function testTypesAreDistinct(): void
    {
        $this->assertNotSame(Type::MOBILE, Type::DESKTOP);
    }

    // isValid
    public function testIsValidHttp(): void
    {
        $this->assertTrue($this->sl->isValid('linkedin', 'http://www.linkedin.com/in/loka1'));
    }

    public function testIsValidHttps(): void
    {
        $this->assertTrue($this->sl->isValid('linkedin', 'https://www.linkedin.com/in/loka1'));
    }

    public function testIsValidHttpNoWww(): void
    {
        $this->assertTrue($this->sl->isValid('linkedin', 'http://linkedin.com/in/loka1'));
    }

    public function testIsValidHttpsNoWww(): void
    {
        $this->assertTrue($this->sl->isValid('linkedin', 'https://linkedin.com/in/loka1'));
    }

    public function testIsValidNoProtocol(): void
    {
        $this->assertTrue($this->sl->isValid('linkedin', 'www.linkedin.com/in/loka1'));
    }

    public function testIsValidNoProtocolNoWww(): void
    {
        $this->assertTrue($this->sl->isValid('linkedin', 'linkedin.com/in/loka1'));
    }

    public function testIsValidOnlyId(): void
    {
        $this->assertTrue($this->sl->isValid('linkedin', 'loka1'));
    }

    // getProfileId
    public function testGetProfileIdHttp(): void
    {
        $this->assertSame('loka1', $this->sl->getProfileId('linkedin', 'http://www.linkedin.com/in/loka1'));
    }

    public function testGetProfileIdHttps(): void
    {
        $this->assertSame('loka1', $this->sl->getProfileId('linkedin', 'https://www.linkedin.com/in/loka1'));
    }

    public function testGetProfileIdHttpNoWww(): void
    {
        $this->assertSame('loka1', $this->sl->getProfileId('linkedin', 'http://linkedin.com/in/loka1'));
    }

    public function testGetProfileIdHttpsNoWww(): void
    {
        $this->assertSame('loka1', $this->sl->getProfileId('linkedin', 'https://linkedin.com/in/loka1'));
    }

    public function testGetProfileIdNoProtocol(): void
    {
        $this->assertSame('loka1', $this->sl->getProfileId('linkedin', 'www.linkedin.com/in/loka1'));
    }

    public function testGetProfileIdOnlyId(): void
    {
        $this->assertSame('loka1', $this->sl->getProfileId('linkedin', 'loka1'));
    }

    // getLink
    public function testGetLinkDefaultDesktop(): void
    {
        $this->assertSame('https://linkedin.com/in/loka1', $this->sl->getLink('linkedin', 'loka1'));
    }

    public function testGetLinkDesktop(): void
    {
        $this->assertSame('https://linkedin.com/in/loka1', $this->sl->getLink('linkedin', 'loka1', Type::DESKTOP));
    }

    public function testGetLinkMobile(): void
    {
        $this->assertSame('https://linkedin.com/mwlite/in/loka1', $this->sl->getLink('linkedin', 'loka1', Type::MOBILE));
    }

    // sanitize
    public function testSanitizeHttpsWww(): void
    {
        $this->assertSame('https://linkedin.com/in/loka1', $this->sl->sanitize('linkedin', 'https://www.linkedin.com/in/loka1'));
    }

    public function testSanitizeHttps(): void
    {
        $this->assertSame('https://linkedin.com/in/loka1', $this->sl->sanitize('linkedin', 'https://linkedin.com/in/loka1'));
    }

    public function testSanitizeHttp(): void
    {
        $this->assertSame('https://linkedin.com/in/loka1', $this->sl->sanitize('linkedin', 'http://linkedin.com/in/loka1'));
    }

    public function testSanitizeWww(): void
    {
        $this->assertSame('https://linkedin.com/in/loka1', $this->sl->sanitize('linkedin', 'www.linkedin.com/in/loka1'));
    }

    public function testSanitizeBare(): void
    {
        $this->assertSame('https://linkedin.com/in/loka1', $this->sl->sanitize('linkedin', 'linkedin.com/in/loka1'));
    }

    public function testSanitizeAsMobile(): void
    {
        $this->assertSame('https://linkedin.com/mwlite/in/loka1', $this->sl->sanitize('linkedin', 'linkedin.com/in/loka1', Type::MOBILE));
    }

    public function testSanitizeMobileAsMobile(): void
    {
        $this->assertSame('https://linkedin.com/mwlite/in/loka1', $this->sl->sanitize('linkedin', 'linkedin.com/mwlite/in/loka1'));
    }

    public function testSanitizeMobileAsDesktop(): void
    {
        $this->assertSame('https://linkedin.com/in/loka1', $this->sl->sanitize('linkedin', 'linkedin.com/mwlite/in/loka1', Type::DESKTOP));
    }

    public function testSanitizeTrailingSlash(): void
    {
        $this->assertSame('https://linkedin.com/in/loka1', $this->sl->sanitize('linkedin', 'linkedin.com/in/loka1/'));
    }

    public function testSanitizeOnlyProfileId(): void
    {
        $this->assertSame('https://linkedin.com/in/loka1', $this->sl->sanitize('linkedin', 'loka1'));
    }

    public function testSanitizeOnlyProfileIdAsMobile(): void
    {
        $this->assertSame('https://linkedin.com/mwlite/in/loka1', $this->sl->sanitize('linkedin', 'loka1', Type::MOBILE));
    }

    // getProfileNames
    public function testGetProfileNames(): void
    {
        $sl = new SocialLinks(new Config(usePredefinedProfiles: false));
        $this->assertSame([], $sl->getProfileNames());
        $sl->addProfile('test_profile', []);
        $this->assertSame(['test_profile'], $sl->getProfileNames());
    }

    // config: usePredefinedProfiles
    public function testUsePredefinedProfilesTrue(): void
    {
        $sl = new SocialLinks(new Config(usePredefinedProfiles: true));
        $this->assertSame('https://linkedin.com/in/loka1', $sl->getLink('linkedin', 'loka1'));
    }

    public function testUsePredefinedProfilesFalse(): void
    {
        $sl = new SocialLinks(new Config(usePredefinedProfiles: false));
        $this->expectException(\InvalidArgumentException::class);
        $sl->getLink('linkedin', 'loka1');
    }

    // config: trimInput
    public function testTrimInputDefault(): void
    {
        $whitespace = " \t\n";
        $this->assertTrue($this->sl->isValid('linkedin', "{$whitespace}http://www.linkedin.com/in/loka1{$whitespace}"));
    }

    public function testTrimInputEnabled(): void
    {
        $sl = new SocialLinks(new Config(trimInput: true));
        $whitespace = " \t\n";
        $this->assertTrue($sl->isValid('linkedin', "{$whitespace}http://www.linkedin.com/in/loka1{$whitespace}"));
    }

    public function testTrimInputDisabled(): void
    {
        $sl = new SocialLinks(new Config(trimInput: false));
        $whitespace = " \t\n";
        $this->assertFalse($sl->isValid('linkedin', "{$whitespace}http://www.linkedin.com/in/loka1{$whitespace}"));
    }

    // config: allowQueryParams
    public function testAllowQueryParamsDefault(): void
    {
        $this->assertFalse($this->sl->isValid('linkedin', 'http://www.linkedin.com/in/loka1?param=123'));
    }

    public function testAllowQueryParamsEnabled(): void
    {
        $sl = new SocialLinks(new Config(allowQueryParams: true));
        $this->assertTrue($sl->isValid('linkedin', 'http://www.linkedin.com/in/loka1?param=123&param2=abc'));
    }

    public function testAllowQueryParamsDisabled(): void
    {
        $sl = new SocialLinks(new Config(allowQueryParams: false));
        $this->assertFalse($sl->isValid('linkedin', 'http://www.linkedin.com/in/loka1?param=123&param2=abc'));
    }

    public function testSanitizeQueryParamsWithAllowQueryParams(): void
    {
        $sl = new SocialLinks(new Config(allowQueryParams: true));
        $this->assertSame('https://linkedin.com/in/loka1', $sl->sanitize('linkedin', 'http://www.linkedin.com/in/loka1?param=123&param2=abc'));
    }

    public function testSanitizeQueryParamsWithoutAllowQueryParams(): void
    {
        $sl = new SocialLinks(new Config(allowQueryParams: false));
        $this->expectException(\InvalidArgumentException::class);
        $sl->sanitize('linkedin', 'http://www.linkedin.com/in/loka1?param=123&param2=abc');
    }

    public function testAllowQueryParamsDoesNotApplyToBareId(): void
    {
        $sl = new SocialLinks(new Config(allowQueryParams: true));
        $this->expectException(\InvalidArgumentException::class);
        $sl->sanitize('linkedin', 'loka1?param=123');
    }

    // custom profiles
    public function testCleanProfiles(): void
    {
        $sl = new SocialLinks(new Config(usePredefinedProfiles: false));
        $this->expectException(\InvalidArgumentException::class);
        $sl->getLink('linkedin', 'loka1');
    }

    public function testAddProfile(): void
    {
        $name = 'test';
        $this->sl->addProfile($name, [
            new ProfileMatch(match: '(.{3})', group: 1, pattern: '-{PROFILE_ID}-'),
        ]);
        $this->assertTrue($this->sl->isValid($name, '123'));
        $this->assertSame('-123-', $this->sl->sanitize($name, '123'));
    }

    // detectProfile
    public function testDetectFacebook(): void
    {
        $this->assertSame('facebook', $this->sl->detectProfile('https://facebook.com/loka1'));
        $this->assertSame('facebook', $this->sl->detectProfile('http://facebook.com/abc'));
        $this->assertSame('facebook', $this->sl->detectProfile('facebook.com/abc'));
        $this->assertSame('facebook', $this->sl->detectProfile('www.facebook.com/loka1'));
    }

    public function testDetectTwitter(): void
    {
        $this->assertSame('twitter', $this->sl->detectProfile('https://x.com/loka1'));
        $this->assertSame('twitter', $this->sl->detectProfile('https://twitter.com/loka1'));
    }

    public function testDetectInstagram(): void
    {
        $this->assertSame('instagram', $this->sl->detectProfile('https://instagram.com/loka1'));
    }

    public function testDetectLinkedIn(): void
    {
        $this->assertSame('linkedin', $this->sl->detectProfile('https://linkedin.com/in/loka1'));
    }

    public function testDetectProfileNoMatch(): void
    {
        $this->assertSame('', $this->sl->detectProfile('https://www.codewars.com/kata/my-languages'));
        $this->assertSame('', $this->sl->detectProfile(''));
    }
}
