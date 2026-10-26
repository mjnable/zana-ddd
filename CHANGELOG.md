# Changelog

All Zana packages share one version number and are released together. A release that did
not change this package is not listed.

## 2.3.0 — 2026-10-26

### Changed

- Tests and tooling (`tests/`, `phpunit.xml.dist`, `phpstan.neon.dist`) are no longer in Composer
  archives, so they stay out of production `vendor/` folders (FE-17).

## 2.0.0 — 2026-10-26

The final 2.0 release. No changes since 2.0.0-rc.1.

## 2.0.0-alpha.3 — 2026-10-24

First release of the 2.0 line. Domain-Driven Design and CQRS building blocks for Domain and Application code. No framework dependencies. Requires PHP 8.5 or later.

Upgrading from 1.x: `ValueObject` is `readonly` and `with()` is gone, and event ids are UUID v7. See [Upgrading from 1.x](https://github.com/mjnable/zana-docs/blob/main/upgrading.md).

Documentation: [the ddd guide](https://github.com/mjnable/zana-docs/blob/main/guides/ddd.md).
