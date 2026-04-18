# Contributing

## Scope

This repository contains the GitHub project wrapper and the Moodle plugin source for `aiprovider_sakuraaiengine`.

## Development Rules

1. Keep the Moodle plugin source under `ai/provider/sakuraaiengine`.
2. Preserve Moodle coding style and component naming.
3. Update both `README.md` and `README-ja.md` when changing public-facing behavior.
4. Add or update PHPUnit tests when changing provider behavior or model discovery.

## Testing

Run tests from a Moodle development environment that includes this plugin in the correct component path.

## Release Preparation

Before publishing a release:

1. Verify `version.php` values.
2. Verify supported model names and endpoint details.
3. Confirm documentation matches the current Moodle compatibility.
4. Confirm no secrets or environment-specific files are tracked.