<?php

declare(strict_types=1);

namespace Kalessil\Composer\Plugins\ProductionDependenciesGuard\Suppliers;

use Composer\Factory;

final class FromComposerManifestSupplier implements SupplierInterface
{
    public function packages(): array
    {
        $manifest = json_decode(
            json: file_get_contents(Factory::getComposerFile()),
            associative: true,
            flags: JSON_THROW_ON_ERROR
        );

        return array_map('strtolower', array_keys($manifest['require'] ?? []));
    }

    public function why(string $package): array
    {
        return ['manifest'];
    }
}
