# Sakura AI Engine Provider for Moodle

`moodle-aiprovider_sakuraaiengine` is a Moodle AI provider plugin that connects Moodle's AI subsystem to Sakura AI Engine.

## Status

- Component: `aiprovider_sakuraaiengine`
- Plugin version: `2025102702`
- Required Moodle version: `2024100700` (Moodle 4.5.0 or later)
- Maturity: `MATURITY_ALPHA`

## Compatibility

This plugin is designed to run on both Moodle 4.5 and Moodle 5.0+, with defensive runtime branching.

- Moodle 4.5:
  - supports `generate_text`, `summarise_text`
  - provider settings are defined via `settings.php`
  - action settings are read from plugin config values (`action_<name>_*`)
  - `explain_text` and per-model hook UI are disabled
- Moodle 5.0+:
  - supports `generate_text`, `summarise_text`, `explain_text`
  - provider settings and model settings are provided via hooks (`db/hooks.php`, `classes/hook_listener.php`)
  - provider instance action settings are read from `actionconfig`

## Features

- Supports text generation and summarization across Moodle 4.5/5.0+
- Supports `explain_text` on Moodle 5.0+
- Supports model selection and per-model settings on Moodle 5.0+
- Supports extra model parameters via JSON (`modelextraparams`)
- Authenticates with either provider-level `account_token` or `local_byoaitoken` (if installed)

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

The repository includes these built-in model classes:

- `gpt_oss_120b`
- `llm_jp_31_8x13b_instruct4`
- `qwen3_coder_30b_a3b_instruct`
- `qwen3_coder_480b_a35b_instruct_fp8`

All built-in models are treated as text models. On Moodle 4.5, predefined model UI is not available and the model is configured via action config text settings.

## Configuration

Provider-level settings:

- `account_token`

Action-level settings (Moodle 5.0+ form):

- `modeltemplate` / `model`
- `systeminstruction`
- `max_tokens`
- `temperature`
- `modelextraparams`

Action-level settings (Moodle 4.5 fallback):

- `action_generate_text_model`
- `action_generate_text_systeminstruction`
- `action_summarise_text_model`
- `action_summarise_text_systeminstruction`

The `modelextraparams` field accepts a JSON object that is merged directly into the request payload sent to Sakura AI Engine.

## Implementation Notes

- Provider entry point: `aiprovider_sakuraaiengine\provider`
- Version branching helper: `aiprovider_sakuraaiengine\compat`
- Request processing base class: `aiprovider_sakuraaiengine\abstract_processor`
- Current text endpoint: `https://api.ai.sakura.ad.jp/v1/chat/completions`
- `generate_image` remains intentionally disabled; commented code paths are left in place for future work
- Defensive guards ensure Moodle 5.0-only hooks and APIs are not executed on Moodle 4.5

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

Before creating a public release, verify supported Moodle versions, runtime branch behavior, and bundled model list still match the current implementation.

## License

GNU GPL v3 or later. See `LICENSE`.
