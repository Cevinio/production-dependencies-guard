# production-dependencies-guard

Prevents development packages from being added into `require` and getting into production environment. In practical field 
prevents e.g. debug tool-bars deployment into production environments.

Additionally, you can configure the guard to decline packages with missing/unfit license, abandoned or mentioning `debug` 
in description and analyze packages on basis of composer.lock (deeper analysis).

> This repository was forked from [kalessil/production-dependencies-guard](https://github.com/kalessil/production-dependencies-guard) because it is unmaintained.  
> Credits go to [Vladimir Reznichenko](https://github.com/kalessil) for creating the original production dependency guard.

# Installation

`composer require --dev cevinio/production-dependencies-guard:dev-main`

# Configuration

Additional guard checks can be enabled in the top-level composer.json file:
```
{
    "name": "...",

    "extra": {
        "production-dependencies-guard": [
            "check-lock-file",
            "check-description",
            "check-license",
            "check-abandoned",
            
            "white-list:vendor/package-one",
            "white-list:vendor/package-two:abandoned,description",
            
            "accept-license:MIT",
            "accept-license:proprietary"
        ]
    }
}
```

- `white-list:<package>` adds a package to white-list, so it's not getting reported in spite of violations
- `white-list:<package>:<guard>,...` adds a package to white-list only for the specified guards
- `check-lock-file` uses composer.lock instead of composer.json, allowing deeper dependencies analysis
- `check-description` enables description and keywords analysis (searches `debug`), allowing to detect custom dev-packages
- `check-abandoned` enables abandoned packages checking
- `check-license` enables license checking (packages must provide license information)
- `accept-license:<license>` specifies which licenses should be accepted (if the setting omitted, any license incl. proprietary)

# Usage

When the package is added to require-dev section of your `composer.json` file (`"cevinio/production-dependencies-guard": "dev-main"`),
it'll **prevent adding dev-packages into `require` section**. Since dev-packages has no security guaranties 
(not intended for production use, only development purposes), this also improves your application security.

```
composer require --dev cevinio/production-dependencies-guard:dev-main

composer require phpunit/phpunit:*
# it should be `composer require --dev phpunit/phpunit:*` here
```

will run with an error (profit!):

```
./composer.json has been updated

Installation failed, reverting ./composer.json to its original content.

[RuntimeException]                                                                   
  Dependencies guard has found violations in require-dependencies (source: manifest):  
   - phpunit/phpunit: dev-package-name
```

# Stability

This package is only available in its `dev-main` version: according to the package purpose.