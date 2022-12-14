<?php

declare(strict_types=1);

namespace Kalessil\Composer\Plugins\ProductionDependenciesGuard\Inspectors;

use Composer\Package\CompletePackageInterface;

final class ByPackageAbandonedInspector implements InspectorInterface
{
    public function canUse(CompletePackageInterface $package): bool
    {
        return !$package->isAbandoned();
    }
}
