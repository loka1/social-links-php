<?php

declare(strict_types=1);

namespace SocialLinks;

use SocialLinks\Profile\FacebookProfile;
use SocialLinks\Profile\TwitterProfile;
use SocialLinks\Profile\InstagramProfile;
use SocialLinks\Profile\LinkedInProfile;

class SocialLinks
{
    private const PROFILE_ID = '[A-Za-z0-9_\-\.]+';
    private const QUERY_PARAM = '(\?.*)?';

    /** @var array<string, ProfileMatch[]> */
    private array $profiles = [];
    private readonly Config $config;

    public function __construct(?Config $config = null)
    {
        $this->config = $config ?? new Config();

        if ($this->config->usePredefinedProfiles) {
            $this->addProfile('facebook', FacebookProfile::get());
            $this->addProfile('twitter', TwitterProfile::get());
            $this->addProfile('instagram', InstagramProfile::get());
            $this->addProfile('linkedin', LinkedInProfile::get());
        }
    }

    private function trim(string $input): string
    {
        return $this->config->trimInput ? trim($input) : $input;
    }

    private function createRegexp(ProfileMatch $profileMatch): string
    {
        $str = str_replace('{PROFILE_ID}', self::PROFILE_ID, $profileMatch->match);
        $isTyped = $profileMatch->type !== null;
        $suffix = ($this->config->allowQueryParams && $isTyped) ? self::QUERY_PARAM : '';
        return '~^' . $str . $suffix . '$~';
    }

    /**
     * @param ProfileMatch[] $matches
     */
    private function findIndex(array $matches, string $link): int
    {
        foreach ($matches as $i => $match) {
            if (preg_match($this->createRegexp($match), $link)) {
                return $i;
            }
        }
        return -1;
    }

    /**
     * @param ProfileMatch[] $profileMatches
     */
    public function addProfile(string $profileName, array $profileMatches): bool
    {
        if ($this->hasProfile($profileName)) {
            return false;
        }
        $this->profiles[$profileName] = $profileMatches;
        return true;
    }

    public function cleanProfiles(): void
    {
        $this->profiles = [];
    }

    public function isValid(string $profileName, string $link): bool
    {
        if (!$this->hasProfile($profileName)) {
            return false;
        }
        $matches = $this->profiles[$profileName];
        return $this->findIndex($matches, $this->trim($link)) !== -1;
    }

    public function getProfileId(string $profileName, string $link): string
    {
        if (!$this->hasProfile($profileName)) {
            throw new \InvalidArgumentException("There is no profile {$profileName} defined");
        }
        $matches = $this->profiles[$profileName] ?? [];
        $trimmed = $this->trim($link);
        $idx = $this->findIndex($matches, $trimmed);
        if ($idx === -1) {
            throw new \InvalidArgumentException("Link has not matched with profile {$profileName}");
        }
        $regs = [];
        preg_match($this->createRegexp($matches[$idx]), $trimmed, $regs);
        return $regs[$matches[$idx]->group];
    }

    public function getLink(string $profileName, string $id, int $type = Type::DEFAULT): string
    {
        if (!$this->hasProfile($profileName)) {
            throw new \InvalidArgumentException("There is no profile {$profileName} defined");
        }
        $matches = $this->profiles[$profileName] ?? [];
        $weakType = $type === Type::DEFAULT ? Type::DESKTOP : $type;
        $found = null;
        foreach ($matches as $match) {
            if ($type === Type::DEFAULT || $match->type === $weakType) {
                $found = $match;
                break;
            }
        }
        if ($found === null || $found->pattern === null) {
            throw new \InvalidArgumentException("There is no pattern for profile {$profileName}");
        }
        return str_replace('{PROFILE_ID}', $this->trim($id), $found->pattern);
    }

    public function sanitize(string $profileName, string $link, int $type = Type::DEFAULT): string
    {
        $trimmed = $this->trim($link);
        $profileId = $this->getProfileId($profileName, $trimmed);
        $matches = $this->profiles[$profileName] ?? [];
        $idx = $this->findIndex($matches, $trimmed);
        $matchedType = $type !== Type::DEFAULT ? $type : ($matches[$idx]->type ?? Type::DEFAULT);
        return $this->getLink($profileName, $profileId, $matchedType);
    }

    public function hasProfile(string $profileName): bool
    {
        return array_key_exists($profileName, $this->profiles);
    }

    /**
     * @return string[]
     */
    public function getProfileNames(): array
    {
        return array_keys($this->profiles);
    }

    /**
     * @return array<array{profileName: string, score: int}>
     */
    public function scoreProfiles(string $link): array
    {
        $result = [];
        foreach ($this->getProfileNames() as $profileName) {
            $matches = $this->profiles[$profileName];
            $score = 0;
            foreach ($matches as $match) {
                if (preg_match($this->createRegexp($match), $link)) {
                    $score++;
                }
            }
            if ($score > 0) {
                $result[] = ['profileName' => $profileName, 'score' => $score];
            }
        }
        usort($result, fn($a, $b) => $b['score'] <=> $a['score']);
        return $result;
    }

    public function detectProfile(string $link): string
    {
        $scores = $this->scoreProfiles($link);
        if (count($scores) === 0) {
            return '';
        }
        return $scores[0]['profileName'];
    }
}
