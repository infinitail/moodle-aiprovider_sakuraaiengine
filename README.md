# Sakura AI Engine Provider for Moodle

`moodle-aiprovider_sakuraaiengine` is a Moodle AI provider plugin that connects Moodle's AI subsystem to Sakura AI Engine.

## Status

- Component: `aiprovider_sakuraaiengine`
- Plugin version: `2025102701`
- Required Moodle version: `2025040800`
- Maturity: `MATURITY_ALPHA`

## Features

- Supports `generate_text`, `summarise_text`, and `explain_text`
- Allows selecting a model per action
- Supports extra model parameters via JSON
- Authenticates with either:
  - provider-level `account_token`
  - `local_byoaitoken` when that plugin is installed

## Repository Layout

This repository is intended to be published as a standalone GitHub project.

- `README.md` / `README-ja.md`: repository documentation
- `ai/provider/sakuraaiengine`: Moodle plugin source tree

When installing manually into Moodle, place the contents of `ai/provider/sakuraaiengine` at:

```text
<moodle-root>/ai/provider/sakuraaiengine
```

## Installation

1. Copy `ai/provider/sakuraaiengine` into your Moodle installation under `ai/provider/`.
2. Visit Site administration to trigger the Moodle upgrade.
3. Enable and configure the provider from the Moodle AI provider settings screen.
4. Set an `account_token`, or install `local_byoaitoken` if you want per-provider token resolution.

## Supported Models

The repository currently includes these built-in model classes:

- `gpt_oss_120b`
- `llm_jp_31_8x13b_instruct4`
- `qwen3_coder_30b_a3b_instruct`
- `qwen3_coder_480b_a35b_instruct_fp8`

All built-in models are treated as text models.

## Configuration

Provider-level settings:

- `account_token`

Action-level settings:

- `modeltemplate` / `model`
- `systeminstruction`
- `max_tokens`
- `temperature`
- `modelextraparams`

The `modelextraparams` field accepts a JSON object that is merged directly into the request payload sent to Sakura AI Engine.

## Implementation Notes

- Provider entry point: `aiprovider_sakuraaiengine\provider`
- Request processing base class: `aiprovider_sakuraaiengine\abstract_processor`
- Current text endpoint: `https://api.ai.sakura.ad.jp/v1/chat/completions`
- `generate_image` remains intentionally disabled; commented code paths are left in place for future work

## Tests

Included PHPUnit tests:

- `ai/provider/sakuraaiengine/tests/provider_test.php`
- `ai/provider/sakuraaiengine/tests/aimodel_helper_test.php`

Current coverage focuses on provider action registration and model helper behavior.

This repository does not ship a standalone Moodle test bootstrap, so tests must be executed from a Moodle development environment.

## Publishing Notes

This repository is structured for GitHub publication:

- repository-level documentation lives at the root
- plugin source stays in the Moodle component path
- GPL-3.0-or-later licensing is included in the repository root

Before creating a public release, verify the supported Moodle version, plugin maturity, and bundled model list still match the current implementation.

## License

GNU GPL v3 or later. See `LICENSE`.
