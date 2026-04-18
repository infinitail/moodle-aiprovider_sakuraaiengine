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
 * Strings for component aiprovider_sakuraaiengine, language 'en'.
 *
 * @package    aiprovider_sakuraaiengine
 * @copyright  infinitail
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Sakura AI Engine Provider';
$string['privacy:metadata'] = 'The Sakura AI Engine Provider plugin does not store any personal data.';
$string['account_token'] = 'Account Token';
$string['account_token_help'] = 'Generate your account token from '
	. '<a href="https://secure.sakura.ad.jp/ai/account-tokens" target="_blank">here</a> '
	. 'and enter it here to allow Moodle to access the Sakura AI Engine API.';
$string['action:generate_text:model'] = 'AI model';
$string['action:generate_text:model_help'] = 'The model used to generate the text response.';
$string['action:generate_text:systeminstruction'] = 'System instruction';
$string['action:generate_text:systeminstruction_help'] = 'This instruction is sent to the AI model '
	. 'along with the user\'s prompt. Editing this instruction is not recommended '
	. 'unless absolutely required.';
$string['action:summarise_text:model'] = 'AI model';
$string['action:summarise_text:model_help'] = 'The model used to summarise the provided text.';
$string['action:summarise_text:systeminstruction'] = 'System instruction';
$string['action:summarise_text:systeminstruction_help'] = 'This instruction is sent to the AI model '
	. 'along with the user\'s prompt. Editing this instruction is not recommended '
	. 'unless absolutely required.';
$string['action:explain_text:endpoint'] = 'API endpoint';
$string['action:explain_text:model'] = 'AI model';
$string['action:explain_text:model_help'] = 'The model used to explain the provided text.';
$string['action:explain_text:systeminstruction'] = 'System instruction';
$string['action:explain_text:systeminstruction_help'] = 'This instruction is sent to the AI model '
	. 'along with the user\'s prompt. Editing this instruction is not recommended '
	. 'unless absolutely required.';
$string['settings'] = 'Settings';
$string['settings_help'] = 'Sakura AI Engine Provider Settings';
$string['settings_max_tokens'] = 'Max Tokens';
$string['settings_max_tokens_help'] = 'The maximum number of tokens to generate in the response.';
$string['settings_temperature'] = 'Temperature';
$string['settings_temperature_help'] = 'The sampling temperature to use, between 0 and 2. '
	. 'Higher values will make the output more random, while lower will make it '
	. 'more focused and deterministic.';
$string['extraparams'] = 'Extra model parameters (JSON)';
$string['extraparams_help'] = 'Optional JSON object merged into the request payload. Example: {"top_p": 0.9}';
$string['invalidjson'] = 'Please provide valid JSON.';
$string['settings_toolchoice'] = 'Tool Choice';
$string['settings_toolchoice_help'] = 'The tool choice setting determines how the AI model '
	. 'will choose which tool to use when generating a response. '
	. 'The options are: <br><br>
<ul>
<li><strong>none</strong>: The AI model will not use any tools.</li>
<li><strong>auto</strong>: The AI model can use any tool available.</li>
<li><strong>required</strong>: The AI model must use a tool to generate a response.</li>
</ul>';
