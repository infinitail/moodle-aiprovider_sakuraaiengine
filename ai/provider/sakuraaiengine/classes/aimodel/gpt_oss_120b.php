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
 * gpt-oss-120b AI model.
 *
 * @package    aiprovider_sakuraaiengine
 * @copyright  infinitail
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class gpt_oss_120b extends base implements sakuraaiengine_base {

    #[\Override]
    public function get_model_name(): string {
        return 'gpt-oss-120b';
    }

    #[\Override]
    public function get_model_display_name(): string {
        return 'gpt-oss-120b';
    }

    #[\Override]
    public function has_model_settings(): bool {
        return true;
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

        /*
        $mform->addElement(
            'select',
            'reasoning_model',
            get_string('settings_reasoning_model', 'aiprovider_sakuraaiengine'),
            [
                true => get_string('settings_reasoning_model_enabled', 'aiprovider_sakuraaiengine'),
                false => get_string('settings_reasoning_model_disabled', 'aiprovider_sakuraaiengine'),
            ]
        );
        $mform->addHelpButton('reasoning_model', 'settings_reasoning_model', 'aiprovider_sakuraaiengine');

        $mform->addElement(
            'select',
            'reasoning_effort',
            get_string('settings_reasoning_effort', 'aiprovider_sakuraaiengine'),
            [
                'low' => get_string('settings_reasoning_effort_low', 'aiprovider_sakuraaiengine'),
                'medium' => get_string('settings_reasoning_effort_medium', 'aiprovider_sakuraaiengine'),
                'high' => get_string('settings_reasoning_effort_high', 'aiprovider_sakuraaiengine'),
            ]
        );
        $mform->addHelpButton('reasoning_effort', 'settings_reasoning_effort', 'aiprovider_sakuraaiengine');
        */

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
