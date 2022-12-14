<?php

declare(strict_types=1);

namespace Kalessil\Composer\Plugins\ProductionDependenciesGuard\Inspectors;

use Composer\Package\CompletePackageInterface;

final class ByPackageLicenseInspector implements InspectorInterface
{
    /** @var array<string> */
    private array $allowed;

    /** @param array<string> $allowed */
    public function __construct(array $allowed)
    {
        $this->allowed = $allowed;
    }

    public function canUse(CompletePackageInterface $package): bool
    {
        return array_intersect(
            array_map(static function (string $license): string {
                return strtolower(trim($license));
            }, $package->getLicense()),
            $this->allowed
        ) !== [];
    }
}
