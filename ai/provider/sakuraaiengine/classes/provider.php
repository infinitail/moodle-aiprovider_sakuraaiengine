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
if (compat::is_moodle_50_or_later()) {
    /**
     * Moodle 5.0+ provider implementation.
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
                \core_ai\aiactions\summarise_text::class,
            ];

            // explain_text was added in Moodle 5.0 (MDL-82942).
            if (class_exists(\core_ai\aiactions\explain_text::class)) {
                $actions[] = \core_ai\aiactions\explain_text::class;
            }

            return $actions;
        }

        #[\Override]
        public function add_authentication_headers(RequestInterface $request): RequestInterface {
            // Check if BYOAI Token plugin is installed and get token from there.
            if (class_exists(\local_byoaitoken\token::class) && !empty($this->id)) {
                $byoaitoken = \local_byoaitoken\token::get_token_for_provider($this->id);
                if (!empty($byoaitoken)) {
                    return $request->withAddedHeader('Authorization', "Bearer {$byoaitoken}");
                }
            }

            $token = $this->config['account_token'] ?? '';
            return $request->withAddedHeader('Authorization', "Bearer {$token}");
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

            if (class_exists(\local_byoaitoken\token::class) && !empty($this->id)) {
                $byoaitoken = \local_byoaitoken\token::get_token_for_provider($this->id);
                return !empty($byoaitoken);
            }

            return false;
        }
    }
} else {
    /**
     * Moodle 4.5 provider implementation.
     */
    class provider extends \core_ai\provider {
        /** @var string API token from plugin settings. */
        private string $accounttoken;

        /**
         * Class constructor.
         */
        public function __construct() {
            $this->accounttoken = (string) get_config('aiprovider_sakuraaiengine', 'account_token');
        }

        /**
         * Get the list of actions that this provider supports.
         *
         * @return array An array of action class names.
         */
        public function get_action_list(): array {
            return [
                \core_ai\aiactions\generate_text::class,
                \core_ai\aiactions\summarise_text::class,
            ];
        }

        /**
         * Keep 4.5 settings-based API and return per-action settings.
         *
         * @param string $action The action class name.
         * @param \admin_root $ADMIN Admin root object.
         * @param string $section Current section.
         * @param bool $hassiteconfig Whether user has moodle/site:config.
         * @return array
         */
        public function get_action_settings(
            string $action,
            \admin_root $ADMIN,
            string $section,
            bool $hassiteconfig
        ): array {
            $actionname = substr($action, (strrpos($action, '\\') + 1));
            if ($actionname !== 'generate_text' && $actionname !== 'summarise_text') {
                return [];
            }

            $settings = [];
            $settings[] = new \admin_setting_configtext(
                "aiprovider_sakuraaiengine/action_{$actionname}_model",
                new \lang_string("action:{$actionname}:model", 'aiprovider_sakuraaiengine'),
                '',
                'gpt-oss-120b',
                PARAM_TEXT,
            );

            $settings[] = new \admin_setting_configtextarea(
                "aiprovider_sakuraaiengine/action_{$actionname}_systeminstruction",
                new \lang_string("action:{$actionname}:systeminstruction", 'aiprovider_sakuraaiengine'),
                '',
                $action::get_system_instruction(),
                PARAM_TEXT,
            );

            return $settings;
        }

        /**
         * Add authentication headers.
         *
         * @param RequestInterface $request
         * @return RequestInterface
         */
        public function add_authentication_headers(RequestInterface $request): RequestInterface {
            return $request->withAddedHeader('Authorization', "Bearer {$this->accounttoken}");
        }

        /**
         * Generate a user id compatible with core_ai provider implementations.
         *
         * @param string $userid
         * @return string
         */
        public function generate_userid(string $userid): string {
            global $CFG;
            return hash('sha256', $CFG->siteidentifier . $userid);
        }

        /**
         * 4.5 requires explicit rate-limit hook point.
         *
         * @param \core_ai\aiactions\base $action
         * @return array|bool
         */
        public function is_request_allowed(\core_ai\aiactions\base $action): array|bool {
            return true;
        }

        /**
         * Check this provider has the minimal configuration to work.
         *
         * @return bool Return true if configured.
         */
        public function is_provider_configured(): bool {
            return !empty($this->accounttoken);
        }
    }
}
