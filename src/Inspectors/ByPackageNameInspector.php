<?php

declare(strict_types=1);

namespace Kalessil\Composer\Plugins\ProductionDependenciesGuard\Inspectors;

use Composer\Package\CompletePackageInterface;

final class ByPackageNameInspector implements InspectorInterface
{
    private const VENDORS = [
        'phpunit/',
        'codeception/',
        'behat/',
        'phpspec/',
        'phpstan/',
    ];

    private const PACKAGES = [
        /* Security and compliance tooling */
        'kalessil/production-dependencies-guard',
        'mediact/dependency-guard',
        'roave/security-advisories',
        'sensiolabs/security-checker',

        /* PHPUnit extensions and tooling */
        'brianium/paratest',
        'codedungeon/phpunit-result-printer',
        'johnkary/phpunit-speedtrap',
        'mybuilder/phpunit-accelerator',
        'satooshi/php-coveralls',
        'spatie/phpunit-watcher',

        /* Framework components and tooling */
        'barryvdh/laravel-debugbar',
        'beyondcode/laravel-dump-server',
        'filp/whoops',
        'insolita/yii2-codestat',
        'nunomaduro/collision',
        'nunomaduro/larastan',
        'orchestra/testbench',
        'symfony/maker-bundle',
        'symfony/phpunit-bridge',
        'wnx/laravel-stats',
        'yiisoft/yii2-debug',
        'yiisoft/yii2-gii',
        'zendframework/zend-debug',
        'zendframework/zend-test',

        /* Development tools */
        'humbug/humbug',
        'infection/infection',
        'mikey179/vfsstream',
        'mockery/mockery',
        'phing/phing',

        /* SCA and code quality tools */
        'consistence/coding-standard',
        'doctrine/coding-standard',
        'friendsofphp/php-cs-fixer',
        'jakub-onderka/php-parallel-lint',
        'pdepend/pdepend',
        'phan/phan',
        'phpcompatibility/php-compatibility',
        'phploc/phploc',
        'phpmd/phpmd',
        'phpro/grumphp',
        'povils/phpmnd',
        'sebastian/phpcpd',
        'slevomat/coding-standard',
        'squizlabs/php_codesniffer',
        'sstalle/php7cc',
        'sylius-labs/coding-standard',
        'vimeo/psalm',
        'wimg/php-compatibility',
        'wp-coding-standards/wpcs',
        'yiisoft/yii2-coding-standards',
        'zendframework/zend-coding-standard',
    ];

    private function containsByVendor(string $dependency): bool
    {
        return array_filter(self::VENDORS, static function (string $vendor) use ($dependency): bool {
            return stripos($dependency, $vendor) === 0;
        }) !== [];
    }

    private function contains(string $dependency): bool
    {
        return in_array($dependency, self::PACKAGES, true);
    }

    public function canUse(CompletePackageInterface $package): bool
    {
        return !$this->contains($packageName = strtolower($package->getName())) && !$this->containsByVendor($packageName);
    }
}
