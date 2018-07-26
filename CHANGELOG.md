# Changelog
Changes are formatted by release version.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## Unreleased
### Added
- [#7 - Product now has condition attribute that is displayed on product detail page and is generated in google feed](https://github.com/shopsys/demoshop/pull/7)
- [#8 - Category now has second description attribute that is displayed on the product list page above the product list](https://github.com/shopsys/demoshop/pull/8)
- [#9 - Introduced a quick way to improve performance by caching in twig templates](https://github.com/shopsys/demoshop/pull/9)
    - cache invalidation is done by 5 minutes interval
    - performance improved by ~15%
- [#6 - Shipping method with pickup places](https://github.com/shopsys/demoshop/pull/6)
    - added new shipping method Zasilkovna
    - pick up places are downloaded by cron

### Changed
- [#1 - Basic changes in docs, readme etc. after copying from project-base](https://github.com/shopsys/demoshop/pull/1) : [@LukasHeinz]

### Fixed
- [#8 - Category now has second description attribute that is displayed on the product list page above the product list](https://github.com/shopsys/demoshop/pull/8)
    - GoogleFeedItemFactory: removed unused imports
 
[@LukasHeinz]:(https://github.com/LukasHeinz)