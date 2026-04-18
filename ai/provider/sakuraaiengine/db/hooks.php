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
 * Hook listener callbacks for the Sakura AI Engine Provider.
 *
 * @package    aiprovider_sakuraaiengine
 * @copyright  infinitail
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$callbacks = [];

// after_ai_provider_form_hook is available when Moodle uses the hook-based
// provider settings UI (Moodle 5.0+). On Moodle 4.x the settings are
// defined in settings.php instead.
if (class_exists(\core_ai\hook\after_ai_provider_form_hook::class)) {
    $callbacks[] = [
        'hook' => \core_ai\hook\after_ai_provider_form_hook::class,
        'callback' => \aiprovider_sakuraaiengine\hook_listener::class . '::set_form_definition_for_aiprovider_sakuraaiengine',
    ];
}

// after_ai_action_settings_form_hook was added in Moodle 5.0 (MDL-82980).
// It enables per-model settings inside the action configuration form.
if (class_exists(\core_ai\hook\after_ai_action_settings_form_hook::class)) {
    $callbacks[] = [
        'hook' => \core_ai\hook\after_ai_action_settings_form_hook::class,
        'callback' => \aiprovider_sakuraaiengine\hook_listener::class
            . '::set_model_form_definition_for_aiprovider_sakuraaiengine',
    ];
}
