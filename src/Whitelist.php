<?php

declare(strict_types=1);

namespace Kalessil\Composer\Plugins\ProductionDependenciesGuard;

use Composer\Package\CompletePackageInterface;

final class Whitelist
{
    /** @var array<string,array<string>|null> */
    private array $whitelist;

    public function __construct(array $whitelist)
    {
        $this->whitelist = $whitelist;
    }

    public function canUse(CompletePackageInterface $package, string $rule): bool
    {
        $packageName = strtolower($package->getName());

        return (
            isset($this->whitelist[$packageName]) &&
            (empty($this->whitelist[$packageName]) || in_array($rule, $this->whitelist[$packageName], true))
        );
    }
}
