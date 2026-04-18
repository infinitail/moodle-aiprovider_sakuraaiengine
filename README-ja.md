# Moodle向け Sakura AI Engine Provider

`moodle-aiprovider_sakuraaiengine` は、Moodle の AI サブシステムを Sakura AI Engine に接続するための AI provider プラグインです。

## ステータス

- コンポーネント: `aiprovider_sakuraaiengine`
- プラグインバージョン: `2025102701`
- 必要 Moodle バージョン: `2025040800`
- Maturity: `MATURITY_ALPHA`

## 主な機能

- `generate_text`、`summarise_text`、`explain_text` をサポート
- アクションごとのモデル選択
- JSON 形式の追加モデルパラメータに対応
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

同梱モデルはすべてテキストモデルとして扱われます。

## 設定項目

Provider レベル:

- `account_token`

Action レベル:

- `modeltemplate` / `model`
- `systeminstruction`
- `max_tokens`
- `temperature`
- `modelextraparams`

`modelextraparams` は、Sakura AI Engine へ送るリクエストペイロードにそのままマージする JSON オブジェクトです。

## 実装メモ

- Provider エントリポイント: `aiprovider_sakuraaiengine\provider`
- リクエスト処理の基底クラス: `aiprovider_sakuraaiengine\abstract_processor`
- 現在のテキストエンドポイント: `https://api.ai.sakura.ad.jp/v1/chat/completions`
- `generate_image` は将来対応用のコメントアウト済みコードが残っていますが、現状は無効です

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

公開前には、対応 Moodle バージョン、maturity、同梱モデル一覧が実装と一致していることを確認してください。

## ライセンス

GNU GPL v3 以降です。`LICENSE` を参照してください。
