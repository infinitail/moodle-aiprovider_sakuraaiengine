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

/**
 * Compatibility helper for Moodle version-dependent behavior.
 *
 * @package    aiprovider_sakuraaiengine
 * @copyright  infinitail
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class compat {
    /** Moodle 5.0.0 release version number. */
    private const MOODLE_500_VERSION = 2025041400;

    /**
     * Return true when running on Moodle 5.0+.
     *
     * @return bool
     */
    public static function is_moodle_50_or_later(): bool {
        global $CFG;

        return !empty($CFG->version) && (int) $CFG->version >= self::MOODLE_500_VERSION;
    }
}
