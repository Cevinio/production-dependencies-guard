<?php

declare(strict_types=1);

namespace Kalessil\Composer\Plugins\ProductionDependenciesGuard\Suppliers;

use Composer\Factory;

final class FromComposerLockSupplier implements SupplierInterface
{
    /** @var array<string,array<int,string>> */
    private array $dependencies = [];

    public function packages(): array
    {
        $manifest = json_decode(
            json: file_get_contents(substr(Factory::getComposerFile(), 0, -5) . '.lock'),
            associative: true,
            flags: JSON_THROW_ON_ERROR
        );

        $packages = array_map(static function (array $package): array {
            $package['name'] = strtolower($package['name']);

            return $package;
        }, $manifest['packages'] ?? []);

        foreach ($packages as $package) {
            $this->dependencies[$package['name']] = array_map('strtolower', array_keys($package['require'] ?? []));
        }

        return array_column($packages, 'name');
    }

    /** @return array<int, string> */
    public function why(string $package): array
    {
        $which = array_filter(
            $this->dependencies,
            static function (array $packages) use ($package): bool {
                return in_array($package, $packages, true);
            }
        );
        return $which === [] ? ['manifest'] : array_keys($which);
    }
}
