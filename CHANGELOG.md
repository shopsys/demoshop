# Changelog
Changes are formatted by release version.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## Unreleased
### Added
- [#34 - Ability to install demo instances](https://github.com/shopsys/demoshop/pull/34)
- [#7 - Product now has condition attribute that is displayed on product detail page and is generated in google feed](https://github.com/shopsys/demoshop/pull/7)
- [#8 - Category now has second description attribute that is displayed on the product list page above the product list](https://github.com/shopsys/demoshop/pull/8)
- [#9 - Introduced a quick way to improve performance by caching in twig templates](https://github.com/shopsys/demoshop/pull/9)
    - cache invalidation is done by 5 minutes interval
    - performance improved by ~15%
- [#6 - Shipping method with pickup places](https://github.com/shopsys/demoshop/pull/6)
    - added new shipping method Zasilkovna
    - pick up places are downloaded by cron
- [#15 - Company account with multiple users](https://github.com/shopsys/demoshop/pull/15)
- [#31 - Discount for customer](https://github.com/shopsys/demoshop/pull/31)
    - administration of customer now contains discount field with range validation that is reflected into User entity
    - prices of products are recalculated for logged customers based on custom discount
    - discount works also for companies with multiple users
    - `filterFormMacro.html.twig` now counts with discount coeficient
- [#55 - Public demoshop](https://github.com/shopsys/demoshop/pull/55)
    - domains and domains_url search index routing were added for german locale
    - CategoryRepositoryTest was fixed for third domain
    - translations were copied from en language and prefixed with (de)

### Changed
- [#1 - Basic changes in docs, readme etc. after copying from project-base](https://github.com/shopsys/demoshop/pull/1) : [@LukasHeinz]
- [#13 - The product flags functionality is now hidden from admin and FE](https://github.com/shopsys/demoshop/pull/13)
- [#20 - Upgrade to shopsys framework 7.0.0-beta1](https://github.com/shopsys/demoshop/pull/20)
- [#24 - Production demoshop](https://github.com/shopsys/demoshop/pull/24)
    - administration route prefix is modifiable via `parameters.yml`
    - added Dockerfile for production
- [#19 - Table tags and inline styles can be used in ckEditor for email templates](https://github.com/shopsys/demoshop/pull/19)
    - `ivory_ck_editor.yml` is updated so email templates wysiwyg can parse table tags and inline styles for all elements
    - datafixtures for email templates are added so demoshop has now customized emails
- [#27 - Upgrade to shopsys framework 7.0.0-beta4](https://github.com/shopsys/demoshop/pull/27)
    - renamed Database tests to Functional tests
    - content-test directory is used instead of content during the test
    - updated coding standards and applied
    - registered new form types (OrderItemsType, DisplayOnlyCustomerType)
    - OrderFormType in Admin is extended on a class level. No need to extend twig template anymore
    - block loading of original data fixtures, if overridden
    - Shopsys\FrameworkBundle\DataFixtures\Demo\MailTemplateDataFixture blocked from loading, because its overridden in ShopBundle 
    - Shopsys\FrameworkBundle\DataFixtures\Demo\MultidomainMailTemplateDataFixture blocked from loading, because its overridden in ShopBundle
    - fixed wrong links in mail template data fixtures
- [#33 - Upgrade demoshop to version beta5](https://github.com/shopsys/demoshop/pull/33)
    - added the ability to deploy to Google Cloud using Terraform, Kustomize and Kubernetes 
    - updated `.dockerignore` so it ignores infrastructure, and .ci folders and docker/nginx directory is not excluded during building php-fpm image
    - removed `--verbose` from esc phing targets as the package was upgraded and now outputs name of each file checked in the verbose mode
    - switched to Debian PHP-FPM image
    - added support for custom prefixing in redis
    - changed usage of methods that used services because the service layer was removed
    - trusted proxies are loaded from DIC parameter `trusted_proxies` instead of being hard-coded
    - removed `UserFactory`, `ProductFactory` and `CategoryFactory` because factories are extendable and there was no point of using them instead of factories from FrameworkBundle
- [#37 - Upgrade demoshop to version beta6](https://github.com/shopsys/demoshop/pull/37)
    - upgraded to php 7.3
    - refactored order item
    - added support for multiple image sizes
    - upgraded npm packages to the latest version
    - unified countries across domains with translations and domain dependency
    - added install script
    - use configuration file to define directories that need to be created 
    - fixed JS validation of forms in popup windows
    - microservices have been removed
    - cart refactored
    - implemented new acceptance test for promo code functionality testing
    - `oneup_flysystem` removed
    - removed usages of inherited `OrderItem` classes
    - warm up the production cache before generating error pages
- [#39 - Upgrade to stable version 7.0.0](https://github.com/shopsys/demoshop/pull/39)
    - phing target build-demo is improved
    - translations from previous changes are fixed
    - promocode naming is unified
    - datafixtures were copied into ShopBundle\Datafixture namespace
    - datafixture services were registrered in Resources/config/services/commands.yml and Resources/config/services/data_fixtures.yml
    - custom MailTemplates were merged
    - base directory for data fixture files were changed in paths.yml
    - MoneyType is integrated into project
    - default image sizes were implemented for individual devices width
- [#46 - Upgrade to version 7.1.0](https://github.com/shopsys/demoshop/pull/46)
    - add support of functional tests of Redis
    - redesigned print page of product detail page
    - add custom message for unique e-mail validation
    - remove option `choice_name` from `brands` in ShopBundle/Form/Front/Product/ProductFilterFormType.php
    - create Shopsys\ShopBundle\Model\AdvancedSearch\ProductAdvancedSearchConfig by extending Shopsys\FrameworkBundle\Model\AdvancedSearch\ProductAdvancedSearchConfig
    - use private method recursivelyCountCategoriesInCategoryTree instead of array_sum
    - fix EntityExtensionTest
    - improve deployment process and avoid possible Redis cache problems
- [#47 - Upgrade to version 7.2.0](https://github.com/shopsys/demoshop/pull/47)
    - postgres service in `docker-compose.yml` uses the provided configuration file
    - updated nginx max body size limit
    - upgraded `shopsys/*` composer dependencies to `v7.2.0`
    - upgraded other composer and npm dependencies
    - using `TransformString::removeDriveLetterFromPath` transformer for absolute paths in `local_filesystem`
    - using shorter syntax for mocked methods returning values in tests
    - added extra error page when current domain cannot be resolved with explicit hint about `overwrite_domain_url` parameter (for TEST environment)
    - using standard redis separators (`:`) in prefixes
    - build version is used in redis prefix of the twig cache and contains the environment (avoids the usage of a wrong cache)
    - translations are extracted from overwritten templates now
    - using redis as cache for doctrine and framework
    - moved `cron.yml` to a standard location
    - using interchangeable product filtering via Elasticsearch
    - reconfigured `fm_elfinder` to use `main_filesystem`
- [#48 - Upgrade to version 7.2.1](https://github.com/shopsys/demoshop/pull/48)
    - upgraded `shopsys/*` composer dependencies to `v7.2.1`
    - call of `Form::isSubmitted()` was moved before `Form::isValid()`
    - typo categoriyWithLazyLoadedVisibleChildren was fixed in twig template
    - elasticsearch index prefix is used also during tests
- [#51 - the product flags functionality is now visible from admin and FE (revert of #13)](https://github.com/shopsys/demoshop/pull/51)
- [#53 - Upgrade to version 7.3.0](https://github.com/shopsys/demoshop/pull/53)
    - updated all shopsys/* composer dependencies to v7.3.0
    - upgraded other composer dependencies
    - updated migrations-lock.yml with new database migration
    - incompatible excluded_404s from monolog is now unset
    - updated Dockerfile production stage build
    - updated Elasticsearch build configuration
    - name.keyword field in Elasticsearch changed to sort properly in languages
    - extended DI configuration
    - removed useless route front_category_panel
    - used phing configuration from shopsys/framework
    - phpstan level raised to 1
    - installation script now not running tests and standards checks
    - fixed inconsistently named field shortDescription in Elasticsearch
    - added test for creating product variants
    - CustomerPassword:setNewPassword is now not indexed by robots
    - password inputs now uses autocomplete=new-password
    - tests updated to use interfaces of factories fetched from DIC
    - removed trailing spaces in README file
    - added test of order editing
    - RedisFacadeTest is updated with version from 7.3.0
    - added Elasticsearch structure update test
    - read-model is now used for product lists
- [#56 - Upgrade to version 8.0.0](https://github.com/shopsys/demoshop/pull/56)
    - updated to PHP 7.2 in composer.json
    - updated all shopsys/* composer dependencies to v8.0.0 (written as ^8.0 for easier future upgrading)
    - `vim`, `nano`, `mc`, and `htop` installed in the `php-fpm` Docker image
    - updated `commerceguys/intl` to `v1.0`
    - `egeloen/ckeditor-bundle` replaced by `friendsofsymfony/ckeditor-bundle`
    - simplified configuration of localized router

### Fixed
- [#8 - Category now has second description attribute that is displayed on the product list page above the product list](https://github.com/shopsys/demoshop/pull/8)
    - GoogleFeedItemFactory: removed unused imports
- [#38 - Pagination url parameter is fixed based on upgrade notes](https://github.com/shopsys/demoshop/pull/38)
- [#43 - Autocomplete action for pickup place controller is fixed based on upgrade notes](https://github.com/shopsys/demoshop/pull/43)

### Removed
- [#36 - Production Docker file was removed due to multistage build](https://github.com/shopsys/demoshop/pull/36)

[@LukasHeinz]:(https://github.com/LukasHeinz)
