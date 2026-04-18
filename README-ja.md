# Moodle向け Sakura AI Engine Provider

`moodle-aiprovider_sakuraaiengine` は、Moodle の AI サブシステムを Sakura AI Engine に接続するための AI provider プラグインです。

## ステータス

- コンポーネント: `aiprovider_sakuraaiengine`
- プラグインバージョン: `2025102702`
- 必要 Moodle バージョン: `2024100700` (Moodle 4.5.0 以降)
- Maturity: `MATURITY_ALPHA`

## 互換性

このプラグインは、防御的な実行時分岐により Moodle 4.5 と Moodle 5.0+ の両方で動作するよう設計されています。

- Moodle 4.5:
  - `generate_text` と `summarise_text` をサポート
  - provider 設定は `settings.php` で定義
  - action 設定はプラグイン設定値 (`action_<name>_*`) から読み込み
  - `explain_text` とモデル拡張 hook UI は無効
- Moodle 5.0+:
  - `generate_text`、`summarise_text`、`explain_text` をサポート
  - provider 設定とモデル設定は hook (`db/hooks.php`, `classes/hook_listener.php`) で提供
  - provider インスタンスの action 設定は `actionconfig` から読み込み

## 主な機能

- Moodle 4.5/5.0+ でのテキスト生成・要約に対応
- Moodle 5.0+ で `explain_text` に対応
- Moodle 5.0+ でアクションごとのモデル選択とモデル別設定に対応
- JSON 形式の追加モデルパラメータ (`modelextraparams`) に対応
- 認証方式:
  - プロバイダ設定の `account_token`
  - `local_byoaitoken` 導入時はそのトークン解決も利用可能

## リポジトリ構成

このリポジトリは、GitHub で単独公開する前提の構成です。

- `README.md` / `README-ja.md`: リポジトリ用ドキュメント
- `ai/provider/sakuraaiengine`: Moodle プラグイン本体

Moodle に手動配置する場合は、`ai/provider/sakuraaiengine` の内容を次の場所に配置します。

```text
<moodle-root>/ai/provider/sakuraaiengine
```

## インストール

1. `ai/provider/sakuraaiengine` を Moodle の `ai/provider/` 配下へコピーします。
2. Site administration へアクセスして Moodle のアップグレードを実行します。
3. Moodle の AI provider 設定画面から本プラグインを有効化して設定します。
4. `account_token` を設定するか、必要に応じて `local_byoaitoken` を導入します。

## 同梱モデル

現在、次の組み込みモデルクラスを含みます。

- `gpt_oss_120b`
- `llm_jp_31_8x13b_instruct4`
- `qwen3_coder_30b_a3b_instruct`
- `qwen3_coder_480b_a35b_instruct_fp8`

同梱モデルはすべてテキストモデルとして扱われます。Moodle 4.5 では predefined model UI は利用できず、action 設定のテキスト値としてモデル名を設定します。

## 設定項目

Provider レベル:

- `account_token`

Action レベル (Moodle 5.0+ フォーム):

- `modeltemplate` / `model`
- `systeminstruction`
- `max_tokens`
- `temperature`
- `modelextraparams`

Action レベル (Moodle 4.5 フォールバック):

- `action_generate_text_model`
- `action_generate_text_systeminstruction`
- `action_summarise_text_model`
- `action_summarise_text_systeminstruction`

`modelextraparams` は、Sakura AI Engine へ送るリクエストペイロードにそのままマージする JSON オブジェクトです。

## 実装メモ

- Provider エントリポイント: `aiprovider_sakuraaiengine\provider`
- バージョン分岐ヘルパー: `aiprovider_sakuraaiengine\compat`
- リクエスト処理の基底クラス: `aiprovider_sakuraaiengine\abstract_processor`
- 現在のテキストエンドポイント: `https://api.ai.sakura.ad.jp/v1/chat/completions`
- `generate_image` は将来対応用のコメントアウト済みコードが残っていますが、現状は無効です
- Moodle 4.5 で Moodle 5.0 専用 API を実行しないための防御的ガードを実装しています

## テスト

同梱している PHPUnit テスト:

- `ai/provider/sakuraaiengine/tests/provider_test.php`
- `ai/provider/sakuraaiengine/tests/aimodel_helper_test.php`

現在のカバレッジは、provider のアクション登録とモデル helper の挙動を中心にしています。

このリポジトリ単体では Moodle のテストブートストラップを含まないため、テスト実行は Moodle 開発環境上で行ってください。

## 公開メモ

GitHub 公開向けに次を満たす構成にしています。

- リポジトリ説明はルートへ配置
- プラグイン本体は Moodle のコンポーネントパスを維持
- ルートに GPL-3.0-or-later のライセンスを配置

公開前には、対応 Moodle バージョン、実行時分岐の挙動、同梱モデル一覧が実装と一致していることを確認してください。

## ライセンス

GNU GPL v3 以降です。`LICENSE` を参照してください。
