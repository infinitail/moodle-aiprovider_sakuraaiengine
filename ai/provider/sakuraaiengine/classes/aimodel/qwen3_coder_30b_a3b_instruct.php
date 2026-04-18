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

namespace aiprovider_sakuraaiengine\aimodel;

use core_ai\aimodel\base;
use MoodleQuickForm;

/**
 * Qwen3-Coder-30B-A3B-Instruct AI model.
 *
 * @package    aiprovider_sakuraaiengine
 * @copyright  infinitail
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qwen3_coder_30b_a3b_instruct extends base implements sakuraaiengine_base {

    #[\Override]
    public function get_model_name(): string {
        return 'Qwen3-Coder-30B-A3B-Instruct';
    }

    #[\Override]
    public function get_model_display_name(): string {
        return 'Qwen3-Coder-30B-A3B-Instruct';
    }

    #[\Override]
    public function has_model_settings(): bool {
        return false;
    }

    #[\Override]
    public function add_model_settings(MoodleQuickForm $mform): void {
        $mform->addElement(
            'text',
            'max_tokens',
            get_string('settings_max_tokens', 'aiprovider_sakuraaiengine'),
        );
        $mform->setType('max_tokens', PARAM_INT);
        $mform->addHelpButton('max_tokens', 'settings_max_tokens', 'aiprovider_sakuraaiengine');

        $mform->addElement(
            'text',
            'temperature',
            get_string('settings_temperature', 'aiprovider_sakuraaiengine'),
        );
        $mform->setType('temperature', PARAM_FLOAT);
        $mform->addHelpButton('temperature', 'settings_temperature', 'aiprovider_sakuraaiengine');
    }

    #[\Override]
    public function model_type(): int {
        return self::MODEL_TYPE_TEXT;
    }
}
