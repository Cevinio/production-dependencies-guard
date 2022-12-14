<?php

declare(strict_types=1);

namespace Kalessil\Composer\Plugins\ProductionDependenciesGuard\Inspectors;

use Composer\Package\CompletePackageInterface;

final class ByPackageTypeInspector implements InspectorInterface
{
    private const DEV_PACKAGE_TYPES = [
        'phpcodesniffer-standard',
    ];

    public function canUse(CompletePackageInterface $package): bool
    {
        return !in_array(strtolower($package->getType()), self::DEV_PACKAGE_TYPES, true);
    }
}
