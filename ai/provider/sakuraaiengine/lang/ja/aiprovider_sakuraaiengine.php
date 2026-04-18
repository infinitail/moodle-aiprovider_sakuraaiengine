<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Strings for component aiprovider_sakuraaiengine, language 'ja'.
 *
 * @package    aiprovider_sakuraaiengine
 * @copyright  infinitail
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'さくらのAI Engineプロバイダ';
$string['privacy:metadata'] = 'さくらのAI Engineプロバイダプラグインは、個人データを保存しません。';
$string['account_token'] = 'アカウントトークン';
$string['account_token_help'] = '<a href="https://secure.sakura.ad.jp/ai/account-tokens" target="_blank">'
	. 'こちらから</a>アカウントトークンを生成し、MoodleがさくらのAI Engine APIに'
	. 'アクセスできるようにここに入力してください。';
$string['action:generate_text:model'] = 'AIモデル';
$string['action:generate_text:model_help'] = 'テキスト応答を生成するために使用されるモデルです。';
$string['action:generate_text:systeminstruction'] = 'システム指示';
$string['action:generate_text:systeminstruction_help'] = 'この指示は、ユーザーのプロンプトとともにAIモデルに送信されます。この指示を編集することは、絶対に必要な場合を除いて推奨されません。';
$string['action:summarise_text:model'] = 'AIモデル';
$string['action:summarise_text:model_help'] = '提供されたテキストを要約するために使用されるモデルです。';
$string['action:summarise_text:systeminstruction'] = 'システム指示';
$string['action:summarise_text:systeminstruction_help'] = 'この指示は、ユーザーのプロンプトとともにAIモデルに送信されます。この指示を編集することは、絶対に必要な場合を除いて推奨されません。';
$string['action:explain_text:endpoint'] = 'APIエンドポイント';
$string['action:explain_text:model'] = 'AIモデル';
$string['action:explain_text:model_help'] = '提供されたテキストを説明するために使用されるモデルです。';
$string['action:explain_text:systeminstruction'] = 'システム指示';
$string['action:explain_text:systeminstruction_help'] = 'この指示は、ユーザーのプロンプトとともにAIモデルに送信されます。この指示を編集することは、絶対に必要な場合を除いて推奨されません。';
$string['extraparams'] = '追加モデルパラメータ（JSON）';
$string['extraparams_help'] = 'リクエストに追加でマージするJSONオブジェクトです。例: {"top_p": 0.9}';
$string['invalidjson'] = '有効なJSONを入力してください。';
