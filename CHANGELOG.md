# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2026-05-11

### Added
- Initial release
- Canonical V1 DTOs for cross-brand player onboarding
- Laravel service provider with conditional publisher infrastructure
- HMAC signature verification middleware with anti-replay protection
- 13 publishable mapper stubs for brand-specific implementation
- Health, eligibility, and onboarding-payload API endpoints
- Rate limiting support (configurable per-minute per-IP)
- `OnboardingPayload::fingerprint()` for SHA-256 change detection
- Orchestra Testbench test suite with Pest 2.x
- PHPStan level 9 static analysis
- GitHub Actions CI workflow
