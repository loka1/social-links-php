# social-links/php

PHP library for validating, sanitizing, and detecting social media profile URLs.

## Installation

```bash
composer require social-links/php
```

## Usage

```php
use SocialLinks\SocialLinks;
use SocialLinks\Type;

$sl = new SocialLinks();
```

### Validate a URL

```php
$sl->isValid('facebook', 'https://www.facebook.com/zuck');    // true
$sl->isValid('twitter', 'https://x.com/elonmusk');            // true
$sl->isValid('instagram', 'https://instagram.com/instagram'); // true
$sl->isValid('linkedin', 'https://linkedin.com/in/satyanadella'); // true
```

### Extract profile ID

```php
$sl->getProfileId('facebook', 'https://www.facebook.com/zuck'); // "zuck"
$sl->getProfileId('twitter', 'https://x.com/elonmusk');         // "elonmusk"
```

### Build a canonical URL

```php
$sl->getLink('facebook', 'zuck');                         // "https://facebook.com/zuck"
$sl->getLink('twitter', 'elonmusk');                      // "https://x.com/elonmusk"
$sl->getLink('linkedin', 'satyanadella', Type::MOBILE);   // "https://linkedin.com/mwlite/in/satyanadella"
```

### Sanitize a messy URL

```php
$sl->sanitize('facebook', 'http://www.facebook.com/zuck/');          // "https://facebook.com/zuck"
$sl->sanitize('twitter', 'https://twitter.com/elonmusk');            // "https://x.com/elonmusk"
$sl->sanitize('instagram', 'https://www.instagram.com/instagram/');  // "https://instagram.com/instagram"
$sl->sanitize('linkedin', 'http://de.linkedin.com/in/loka1/');   // "https://linkedin.com/in/loka1"
```

### Auto-detect a profile

```php
$sl->detectProfile('https://www.facebook.com/zuck');  // "facebook"
$sl->detectProfile('https://x.com/elonmusk');         // "twitter"
$sl->detectProfile('https://instagram.com/instagram');// "instagram"
$sl->detectProfile('https://linkedin.com/in/test');   // "linkedin"
$sl->detectProfile('https://example.com');            // ""
```

### Custom profiles

```php
use SocialLinks\ProfileMatch;
use SocialLinks\Type;

$sl = new SocialLinks(new Config(usePredefinedProfiles: false));
$sl->addProfile('myNetwork', [
    new ProfileMatch(
        match: '(https?://)?mynetwork.com/({PROFILE_ID})/?',
        group: 2,
        type: Type::DESKTOP,
        pattern: 'https://mynetwork.com/{PROFILE_ID}',
    ),
    new ProfileMatch(
        match: '({PROFILE_ID})',
        group: 1,
    ),
]);

$sl->isValid('myNetwork', 'https://mynetwork.com/johndoe'); // true
```

### Configuration

```php
use SocialLinks\Config;

// Disable predefined profiles
new SocialLinks(new Config(usePredefinedProfiles: false));

// Disable input trimming
new SocialLinks(new Config(trimInput: false));

// Allow URLs with query parameters
new SocialLinks(new Config(allowQueryParams: true));
```

## Supported Networks

| Network   | Desktop | Mobile |
|-----------|---------|--------|
| Facebook  | facebook.com | m.facebook.com |
| Twitter   | twitter.com, x.com | mobile.twitter.com |
| Instagram | instagram.com | m.instagram.com |
| LinkedIn  | linkedin.com (with localized subdomains) | linkedin.com/mwlite |

## Requirements

- PHP >= 8.1
- No runtime dependencies

## License

MIT
