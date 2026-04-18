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

use core_ai\form\action_settings_form;
use Psr\Http\Message\RequestInterface;

/**
 * Class provider.
 *
 * @package    aiprovider_sakuraaiengine
 * @copyright  infinitail
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider extends \core_ai\provider {
    /**
     * Get the list of actions that this provider supports.
     *
     * @return array An array of action class names.
     */
    public static function get_action_list(): array {
        $actions = [
            \core_ai\aiactions\generate_text::class,
            //\core_ai\aiactions\generate_image::class,
            \core_ai\aiactions\summarise_text::class,
        ];

        // explain_text was added in Moodle 5.0 (MDL-82942). Skip on 4.x.
        if (class_exists(\core_ai\aiactions\explain_text::class)) {
            $actions[] = \core_ai\aiactions\explain_text::class;
        }

        return $actions;
    }

    #[\Override]
    public function add_authentication_headers(RequestInterface $request): RequestInterface {
        // Check if BYOAI Token plugin is installed and get token from there.
        if (class_exists(\local_byoaitoken\token::class)) {
            $byoaitoken = \local_byoaitoken\token::get_token_for_provider($this->id);
            if (!empty($byoaitoken)) {
                return $request->withAddedHeader('Authorization', "Bearer {$byoaitoken}");
            }
        }

        return $request->withAddedHeader('Authorization', "Bearer {$this->config['account_token']}");
    }

    #[\Override]
    public static function get_action_settings(
        string $action,
        array $customdata = [],
    ): action_settings_form|bool {
        $actionname = substr($action, (strrpos($action, '\\') + 1));
        $customdata['actionname'] = $actionname;
        $customdata['action'] = $action;
        if ($actionname === 'generate_text' || $actionname === 'summarise_text' || $actionname === 'explain_text') {
            return new form\action_generate_text_form(customdata: $customdata);
        //} else if ($actionname === 'generate_image') {
        //    return new form\action_generate_image_form(customdata: $customdata);
        }

        return false;
    }

    #[\Override]
    public static function get_action_setting_defaults(string $action): array {
        $actionname = substr($action, (strrpos($action, '\\') + 1));
        $customdata = [
            'actionname' => $actionname,
            'action' => $action,
            'providername' => 'aiprovider_sakuraaiengine',
        ];
        if ($actionname === 'generate_text' || $actionname === 'summarise_text' || $actionname === 'explain_text') {
            $mform = new form\action_generate_text_form(customdata: $customdata);
            return $mform->get_defaults();
        //} else if ($actionname === 'generate_image') {
        //    $mform = new form\action_generate_image_form(customdata: $customdata);
        //    return $mform->get_defaults();
        }

        return [];
    }

    /**
     * Check this provider has the minimal configuration to work.
     *
     * @return bool Return true if configured.
     */
    public function is_provider_configured(): bool {
        if (!empty($this->config['account_token'])) {
            return true;
        }

        if (class_exists(\local_byoaitoken\token::class)) {
            $byoaitoken = \local_byoaitoken\token::get_token_for_provider($this->id);
            return !empty($byoaitoken);
        }

        return false;
    }
}
