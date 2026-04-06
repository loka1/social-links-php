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
        $this->assertTrue($this->sl->isValid('linkedin', 'http://www.linkedin.com/in/gkucmierz'));
    }

    public function testIsValidHttps(): void
    {
        $this->assertTrue($this->sl->isValid('linkedin', 'https://www.linkedin.com/in/gkucmierz'));
    }

    public function testIsValidHttpNoWww(): void
    {
        $this->assertTrue($this->sl->isValid('linkedin', 'http://linkedin.com/in/gkucmierz'));
    }

    public function testIsValidHttpsNoWww(): void
    {
        $this->assertTrue($this->sl->isValid('linkedin', 'https://linkedin.com/in/gkucmierz'));
    }

    public function testIsValidNoProtocol(): void
    {
        $this->assertTrue($this->sl->isValid('linkedin', 'www.linkedin.com/in/gkucmierz'));
    }

    public function testIsValidNoProtocolNoWww(): void
    {
        $this->assertTrue($this->sl->isValid('linkedin', 'linkedin.com/in/gkucmierz'));
    }

    public function testIsValidOnlyId(): void
    {
        $this->assertTrue($this->sl->isValid('linkedin', 'gkucmierz'));
    }

    // getProfileId
    public function testGetProfileIdHttp(): void
    {
        $this->assertSame('gkucmierz', $this->sl->getProfileId('linkedin', 'http://www.linkedin.com/in/gkucmierz'));
    }

    public function testGetProfileIdHttps(): void
    {
        $this->assertSame('gkucmierz', $this->sl->getProfileId('linkedin', 'https://www.linkedin.com/in/gkucmierz'));
    }

    public function testGetProfileIdHttpNoWww(): void
    {
        $this->assertSame('gkucmierz', $this->sl->getProfileId('linkedin', 'http://linkedin.com/in/gkucmierz'));
    }

    public function testGetProfileIdHttpsNoWww(): void
    {
        $this->assertSame('gkucmierz', $this->sl->getProfileId('linkedin', 'https://linkedin.com/in/gkucmierz'));
    }

    public function testGetProfileIdNoProtocol(): void
    {
        $this->assertSame('gkucmierz', $this->sl->getProfileId('linkedin', 'www.linkedin.com/in/gkucmierz'));
    }

    public function testGetProfileIdOnlyId(): void
    {
        $this->assertSame('gkucmierz', $this->sl->getProfileId('linkedin', 'gkucmierz'));
    }

    // getLink
    public function testGetLinkDefaultDesktop(): void
    {
        $this->assertSame('https://linkedin.com/in/gkucmierz', $this->sl->getLink('linkedin', 'gkucmierz'));
    }

    public function testGetLinkDesktop(): void
    {
        $this->assertSame('https://linkedin.com/in/gkucmierz', $this->sl->getLink('linkedin', 'gkucmierz', Type::DESKTOP));
    }

    public function testGetLinkMobile(): void
    {
        $this->assertSame('https://linkedin.com/mwlite/in/gkucmierz', $this->sl->getLink('linkedin', 'gkucmierz', Type::MOBILE));
    }

    // sanitize
    public function testSanitizeHttpsWww(): void
    {
        $this->assertSame('https://linkedin.com/in/gkucmierz', $this->sl->sanitize('linkedin', 'https://www.linkedin.com/in/gkucmierz'));
    }

    public function testSanitizeHttps(): void
    {
        $this->assertSame('https://linkedin.com/in/gkucmierz', $this->sl->sanitize('linkedin', 'https://linkedin.com/in/gkucmierz'));
    }

    public function testSanitizeHttp(): void
    {
        $this->assertSame('https://linkedin.com/in/gkucmierz', $this->sl->sanitize('linkedin', 'http://linkedin.com/in/gkucmierz'));
    }

    public function testSanitizeWww(): void
    {
        $this->assertSame('https://linkedin.com/in/gkucmierz', $this->sl->sanitize('linkedin', 'www.linkedin.com/in/gkucmierz'));
    }

    public function testSanitizeBare(): void
    {
        $this->assertSame('https://linkedin.com/in/gkucmierz', $this->sl->sanitize('linkedin', 'linkedin.com/in/gkucmierz'));
    }

    public function testSanitizeAsMobile(): void
    {
        $this->assertSame('https://linkedin.com/mwlite/in/gkucmierz', $this->sl->sanitize('linkedin', 'linkedin.com/in/gkucmierz', Type::MOBILE));
    }

    public function testSanitizeMobileAsMobile(): void
    {
        $this->assertSame('https://linkedin.com/mwlite/in/gkucmierz', $this->sl->sanitize('linkedin', 'linkedin.com/mwlite/in/gkucmierz'));
    }

    public function testSanitizeMobileAsDesktop(): void
    {
        $this->assertSame('https://linkedin.com/in/gkucmierz', $this->sl->sanitize('linkedin', 'linkedin.com/mwlite/in/gkucmierz', Type::DESKTOP));
    }

    public function testSanitizeTrailingSlash(): void
    {
        $this->assertSame('https://linkedin.com/in/gkucmierz', $this->sl->sanitize('linkedin', 'linkedin.com/in/gkucmierz/'));
    }

    public function testSanitizeOnlyProfileId(): void
    {
        $this->assertSame('https://linkedin.com/in/gkucmierz', $this->sl->sanitize('linkedin', 'gkucmierz'));
    }

    public function testSanitizeOnlyProfileIdAsMobile(): void
    {
        $this->assertSame('https://linkedin.com/mwlite/in/gkucmierz', $this->sl->sanitize('linkedin', 'gkucmierz', Type::MOBILE));
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
        $this->assertSame('https://linkedin.com/in/gkucmierz', $sl->getLink('linkedin', 'gkucmierz'));
    }

    public function testUsePredefinedProfilesFalse(): void
    {
        $sl = new SocialLinks(new Config(usePredefinedProfiles: false));
        $this->expectException(\InvalidArgumentException::class);
        $sl->getLink('linkedin', 'gkucmierz');
    }

    // config: trimInput
    public function testTrimInputDefault(): void
    {
        $whitespace = " \t\n";
        $this->assertTrue($this->sl->isValid('linkedin', "{$whitespace}http://www.linkedin.com/in/gkucmierz{$whitespace}"));
    }

    public function testTrimInputEnabled(): void
    {
        $sl = new SocialLinks(new Config(trimInput: true));
        $whitespace = " \t\n";
        $this->assertTrue($sl->isValid('linkedin', "{$whitespace}http://www.linkedin.com/in/gkucmierz{$whitespace}"));
    }

    public function testTrimInputDisabled(): void
    {
        $sl = new SocialLinks(new Config(trimInput: false));
        $whitespace = " \t\n";
        $this->assertFalse($sl->isValid('linkedin', "{$whitespace}http://www.linkedin.com/in/gkucmierz{$whitespace}"));
    }

    // config: allowQueryParams
    public function testAllowQueryParamsDefault(): void
    {
        $this->assertFalse($this->sl->isValid('linkedin', 'http://www.linkedin.com/in/gkucmierz?param=123'));
    }

    public function testAllowQueryParamsEnabled(): void
    {
        $sl = new SocialLinks(new Config(allowQueryParams: true));
        $this->assertTrue($sl->isValid('linkedin', 'http://www.linkedin.com/in/gkucmierz?param=123&param2=abc'));
    }

    public function testAllowQueryParamsDisabled(): void
    {
        $sl = new SocialLinks(new Config(allowQueryParams: false));
        $this->assertFalse($sl->isValid('linkedin', 'http://www.linkedin.com/in/gkucmierz?param=123&param2=abc'));
    }

    public function testSanitizeQueryParamsWithAllowQueryParams(): void
    {
        $sl = new SocialLinks(new Config(allowQueryParams: true));
        $this->assertSame('https://linkedin.com/in/gkucmierz', $sl->sanitize('linkedin', 'http://www.linkedin.com/in/gkucmierz?param=123&param2=abc'));
    }

    public function testSanitizeQueryParamsWithoutAllowQueryParams(): void
    {
        $sl = new SocialLinks(new Config(allowQueryParams: false));
        $this->expectException(\InvalidArgumentException::class);
        $sl->sanitize('linkedin', 'http://www.linkedin.com/in/gkucmierz?param=123&param2=abc');
    }

    public function testAllowQueryParamsDoesNotApplyToBareId(): void
    {
        $sl = new SocialLinks(new Config(allowQueryParams: true));
        $this->expectException(\InvalidArgumentException::class);
        $sl->sanitize('linkedin', 'gkucmierz?param=123');
    }

    // custom profiles
    public function testCleanProfiles(): void
    {
        $sl = new SocialLinks(new Config(usePredefinedProfiles: false));
        $this->expectException(\InvalidArgumentException::class);
        $sl->getLink('linkedin', 'gkucmierz');
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
        $this->assertSame('facebook', $this->sl->detectProfile('https://facebook.com/gkucmierz'));
        $this->assertSame('facebook', $this->sl->detectProfile('http://facebook.com/abc'));
        $this->assertSame('facebook', $this->sl->detectProfile('facebook.com/abc'));
        $this->assertSame('facebook', $this->sl->detectProfile('www.facebook.com/gkucmierz'));
    }

    public function testDetectTwitter(): void
    {
        $this->assertSame('twitter', $this->sl->detectProfile('https://x.com/gkucmierz'));
        $this->assertSame('twitter', $this->sl->detectProfile('https://twitter.com/gkucmierz'));
    }

    public function testDetectInstagram(): void
    {
        $this->assertSame('instagram', $this->sl->detectProfile('https://instagram.com/gkucmierz'));
    }

    public function testDetectLinkedIn(): void
    {
        $this->assertSame('linkedin', $this->sl->detectProfile('https://linkedin.com/in/gkucmierz'));
    }

    public function testDetectProfileNoMatch(): void
    {
        $this->assertSame('', $this->sl->detectProfile('https://www.codewars.com/kata/my-languages'));
        $this->assertSame('', $this->sl->detectProfile(''));
    }
}
