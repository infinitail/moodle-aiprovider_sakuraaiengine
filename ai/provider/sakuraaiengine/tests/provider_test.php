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

namespace aiprovider_sakuraaiengine;

defined('MOODLE_INTERNAL') || die();

/**
 * Tests for Sakura AI Engine provider.
 *
 * @package    aiprovider_sakuraaiengine
 * @covers     \aiprovider_sakuraaiengine\provider
 */
class provider_test extends \advanced_testcase {
    /**
     * Ensure provider action list matches supported actions.
     */
    public function test_get_action_list_returns_expected_actions(): void {
        $actions = provider::get_action_list();

        $this->assertSame([
            \core_ai\aiactions\generate_text::class,
            \core_ai\aiactions\summarise_text::class,
            \core_ai\aiactions\explain_text::class,
        ], $actions);
    }

    /**
     * Unsupported actions should not have a settings form.
     */
    public function test_get_action_settings_returns_false_for_unknown_action(): void {
        $result = provider::get_action_settings('\\core_ai\\aiactions\\unknown_action');

        $this->assertFalse($result);
    }

    /**
     * Unsupported actions should have empty default settings.
     */
    public function test_get_action_setting_defaults_returns_empty_array_for_unknown_action(): void {
        $result = provider::get_action_setting_defaults('\\core_ai\\aiactions\\unknown_action');

        $this->assertSame([], $result);
    }

    /**
     * Action list should not contain duplicates.
     */
    public function test_get_action_list_has_no_duplicates(): void {
        $actions = provider::get_action_list();

        $this->assertSame($actions, array_values(array_unique($actions)));
    }
}
