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
 * Admin settings for aiprovider_sakuraaiengine (Moodle 4.x only).
 *
 * On Moodle 5.0 and later, provider settings are defined via the hook system
 * (hook_listener.php + db/hooks.php). This file is used as a fallback for
 * Moodle 4.x where the hook-based provider form is not available.
 *
 * @package    aiprovider_sakuraaiengine
 * @copyright  infinitail
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;

// On Moodle 5.0+, provider settings are supplied via hook callbacks.
if (!empty($CFG->version) && (int) $CFG->version >= 2025041400) {
    return;
}

use core_ai\admin\admin_settingspage_provider;

if ($hassiteconfig) {
    $settings = new admin_settingspage_provider(
        'aiprovider_sakuraaiengine',
        new lang_string('pluginname', 'aiprovider_sakuraaiengine'),
        'moodle/site:config',
        true,
    );

    // Bearer token for authentication.
    $settings->add(new admin_setting_configpasswordunmask(
        'aiprovider_sakuraaiengine/account_token',
        new lang_string('account_token', 'aiprovider_sakuraaiengine'),
        new lang_string('account_token_help', 'aiprovider_sakuraaiengine'),
        '',
    ));
}
