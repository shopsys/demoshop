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

### Fixed
- [#8 - Category now has second description attribute that is displayed on the product list page above the product list](https://github.com/shopsys/demoshop/pull/8)
    - GoogleFeedItemFactory: removed unused imports
- [#38 - Pagination url parameter is fixed based on upgrade notes](https://github.com/shopsys/demoshop/pull/38)

### Removed
- [#36 - Production Docker file was removed due to multistage build](https://github.com/shopsys/demoshop/pull/36)

[@LukasHeinz]:(https://github.com/LukasHeinz)
