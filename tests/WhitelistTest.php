<?php

declare(strict_types=1);

namespace Kalessil\Composer\Plugins\ProductionDependenciesGuard;

use Composer\Package\CompletePackageInterface;
use PHPUnit\Framework\TestCase;

final class WhitelistTest extends TestCase
{
    public function testComponent(): void
    {
        $package = $this->createMock(CompletePackageInterface::class);

        $package->expects($this->atLeastOnce())->method('getName')->willReturn(
            'package1',
            'Package2',
            'package2',
            'package3',
            '...',
            'vendor/package',
        );

        $component = new Whitelist([
            'package1' => [],
            'package2' => ['abandoned'],
            '...' => ['description'],
        ]);

        $this->assertTrue($component->canUse($package, 'lock-file'));
        $this->assertTrue($component->canUse($package, 'abandoned'));
        $this->assertFalse($component->canUse($package, 'license'));
        $this->assertFalse($component->canUse($package, 'description'));
        $this->assertTrue($component->canUse($package, 'description'));
        $this->assertFalse($component->canUse($package, 'description'));
    }
}
