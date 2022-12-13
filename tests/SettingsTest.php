<?php declare(strict_types=1);

namespace Kalessil\Composer\Plugins\ProductionDependenciesGuard;

use PHPUnit\Framework\TestCase;

final class SettingsTest extends TestCase
{
    public function testActivateAdditionalFeatures(): void
    {
        putenv(sprintf('COMPOSER=%s/data/for-settings-test.json', __DIR__));

        $settings = new Settings();

        $expectedWhiteList = [
            'vendor/trim' => [],
            'vendor/capitalization' => [],
            'vendor/package1' => [ 'description', 'license', 'lock-file' ],
            'vendor/package2' => [ 'abandoned', 'lock-file' ],
        ];

        $expectedAcceptLicense = [
            'trim',
            'capitalization',
        ];

        $this->assertTrue($settings->checkAbandoned());
        $this->assertTrue($settings->checkDescription());
        $this->assertTrue($settings->checkLicense());
        $this->assertTrue($settings->checkLockFile());
        $this->assertSame($expectedWhiteList, $settings->whiteList());
        $this->assertSame($expectedAcceptLicense, $settings->acceptLicense());
    }

    public function testActivateNoneFeatures(): void
    {
        putenv(sprintf('COMPOSER=%s/data/activate-none-features.json', __DIR__));

        $settings = new Settings();

        $this->assertFalse($settings->checkAbandoned());
        $this->assertFalse($settings->checkDescription());
        $this->assertFalse($settings->checkLicense());
        $this->assertFalse($settings->checkLockFile());
        $this->assertEmpty($settings->whiteList());
        $this->assertEmpty($settings->acceptLicense());
    }

    public function testMalformedBoolean(): void
    {
        putenv(sprintf('COMPOSER=%s/data/for-settings-test-malformed-boolean.json', __DIR__));

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Malformed setting, found unexpected colon: check-description');

        new Settings();
    }

    public function testMalformedListMissingColon(): void
    {
        putenv(sprintf('COMPOSER=%s/data/for-settings-test-malformed-list-missing-colon.json', __DIR__));

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Malformed setting, expected exactly one colon: accept-license');

        new Settings();
    }

    public function testMalformedListMissingValue(): void
    {
        putenv(sprintf('COMPOSER=%s/data/for-settings-test-malformed-list-missing-value.json', __DIR__));

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Malformed setting, missing value: accept-license');

        new Settings();
    }

    public function testMalformedListExtraColon(): void
    {
        putenv(sprintf('COMPOSER=%s/data/for-settings-test-malformed-list-extra-colon.json', __DIR__));

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Malformed setting, expected exactly one colon: accept-license');

        new Settings();
    }

    public function testMalformedOptionMissingColon(): void
    {
        putenv(sprintf('COMPOSER=%s/data/for-settings-test-malformed-option-missing-colon.json', __DIR__));

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Malformed setting, expected at least one and and most two colons: white-list');

        new Settings();
    }

    public function testMalformedOptionMissingValue(): void
    {
        putenv(sprintf('COMPOSER=%s/data/for-settings-test-malformed-option-missing-value.json', __DIR__));

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Malformed setting, missing value: white-list');

        new Settings();
    }

    public function testMalformedOptionExtraColon(): void
    {
        putenv(sprintf('COMPOSER=%s/data/for-settings-test-malformed-option-extra-colon.json', __DIR__));

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Malformed setting, expected at least one and and most two colons: white-list');

        new Settings();
    }

    public function testUnknownSetting(): void
    {
        putenv(sprintf('COMPOSER=%s/data/for-settings-test-unknown-setting.json', __DIR__));

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown setting: foobar');

        new Settings();
    }
}