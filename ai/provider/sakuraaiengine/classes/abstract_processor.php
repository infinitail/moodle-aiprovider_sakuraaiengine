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

use core\http_client;
use core_ai\process_base;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

/**
 * Class process text generation.
 *
 * @package    aiprovider_sakuraaiengine
 * @copyright  infinitail
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class abstract_processor extends process_base {
    /**
     * Get normalized action name from action class.
     *
     * @return string
     */
    protected function get_action_name(): string {
        return substr($this->action::class, (strrpos($this->action::class, '\\') + 1));
    }

    /**
     * Get current action settings from either 5.0 actionconfig or 4.5 plugin config.
     *
     * @return array
     */
    protected function get_action_settings(): array {
        // Moodle 5.0+ stores per-action settings in provider actionconfig.
        if (property_exists($this->provider, 'actionconfig')) {
            return $this->provider->actionconfig[$this->action::class]['settings'] ?? [];
        }

        // Moodle 4.5 fallback: read action settings from plugin config.
        $actionname = $this->get_action_name();
        return [
            'model' => (string) get_config('aiprovider_sakuraaiengine', "action_{$actionname}_model"),
            'systeminstruction' => (string) get_config('aiprovider_sakuraaiengine', "action_{$actionname}_systeminstruction"),
            'modelextraparams' => (string) get_config('aiprovider_sakuraaiengine', "action_{$actionname}_modelextraparams"),
        ];
    }

    /**
     * Get the endpoint URI.
     *
     * @return UriInterface
     */
    protected function get_endpoint(): UriInterface {
        switch ($this::class) {
            /*
            case process_transcript_audio::class:
                $url = 'https://api.ai.sakura.ad.jp/v1/audio/transcriptions';
                break;
            */
                
            case process_generate_text::class:
            case process_summarise_text::class:
            case process_explain_text::class:
            default:
                $url = 'https://api.ai.sakura.ad.jp/v1/chat/completions';
                break;
        }

        return new Uri($url);
    }

    /**
     * Get the name of the model to use.
     *
     * @return string
     */
    protected function get_model(): string {
        $settings = $this->get_action_settings();
        return $settings['model'] ?? 'gpt-oss-120b';
    }

    /**
     * Get the model settings.
     *
     * @return array
     */
    protected function get_model_settings(): array {
        $settings = $this->get_action_settings();
        if (!empty($settings['modelextraparams'])) {
            // Custom model settings.
            $params = json_decode($settings['modelextraparams'], true);
            if (is_array($params)) {
                foreach ($params as $key => $param) {
                    $settings[$key] = $param;
                }
            }
        }

        // Unset unnecessary settings.
        unset(
            $settings['model'],
            $settings['systeminstruction'],
            $settings['providerid'],
            $settings['modelextraparams'],
        );
        return $settings;
    }

    /**
     * Get the system instructions.
     *
     * @return string
     */
    protected function get_system_instruction(): string {
        return $this->action::get_system_instruction();
    }

    /**
     * Create the request object to send to the Sakura AI Engine API.
     * This object contains all the required parameters for the request.
     *
     * @param string $userid The user id.
     * @return RequestInterface The request object to send to the Sakura AI Engine API.
     */
    abstract protected function create_request_object(string $userid): RequestInterface;

    /**
     * Handle a successful response from the external AI api.
     *
     * @param ResponseInterface $response The response object.
     * @return array The response.
     */
    abstract protected function handle_api_success(ResponseInterface $response): array;

    #[\Override]
    protected function query_ai_api(): array {
        $request = $this->create_request_object($this->provider->generate_userid($this->action->get_configuration('userid')));
        $request = $this->provider->add_authentication_headers($request);

        $client = \core\di::get(http_client::class);
        try {
            // Call the external AI service.
            $response = $client->send($request, [
                'base_uri' => $this->get_endpoint(),
                RequestOptions::HTTP_ERRORS => false,
            ]);
        } catch (RequestException $e) {
            // Handle any exceptions.
            return [
                'success' => false,
                'errorcode' => $e->getCode(),
                'errormessage' => $e->getMessage(),
            ];
        }

        // Double-check the response codes, in case of a non 200 that didn't throw an error.
        $status = $response->getStatusCode();
        if ($status === 200) {
            return $this->handle_api_success($response);
        } else {
            return $this->handle_api_error($response);
        }
    }

    /**
     * Handle an error from the external AI api.
     *
     * @param ResponseInterface $response The response object.
     * @return array The error response.
     */
    protected function handle_api_error(ResponseInterface $response): array {
        $responsearr = [
            'success' => false,
            'errorcode' => $response->getStatusCode(),
        ];

        $status = $response->getStatusCode();
        if ($status >= 500 && $status < 600) {
            $responsearr['errormessage'] = $response->getReasonPhrase();
        } else {
            $rawbody = (string)$response->getBody();
            $bodyobj = json_decode($rawbody);

            if (is_object($bodyobj) && isset($bodyobj->error) && is_object($bodyobj->error) && isset($bodyobj->error->message)) {
                $responsearr['errormessage'] = (string)$bodyobj->error->message;
            } else if (!empty($rawbody)) {
                $responsearr['errormessage'] = trim($rawbody);
            } else {
                $responsearr['errormessage'] = $response->getReasonPhrase();
            }
        }

        return $responsearr;
    }
}
