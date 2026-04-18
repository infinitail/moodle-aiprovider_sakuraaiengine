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

use aiprovider_sakuraaiengine\aimodel\gpt_oss_120b;
use aiprovider_sakuraaiengine\aimodel\llm_jp_31_8x13b_instruct4;
use aiprovider_sakuraaiengine\aimodel\qwen3_coder_30b_a3b_instruct;
use aiprovider_sakuraaiengine\aimodel\qwen3_coder_480b_a35b_instruct_fp8;
use aiprovider_sakuraaiengine\aimodel\sakuraaiengine_base;

/**
 * Tests for Sakura AI Engine model classes and helper.
 *
 * @package    aiprovider_sakuraaiengine
 * @covers     \aiprovider_sakuraaiengine\helper
 * @covers     \aiprovider_sakuraaiengine\aimodel\gpt_oss_120b
 * @covers     \aiprovider_sakuraaiengine\aimodel\llm_jp_31_8x13b_instruct4
 * @covers     \aiprovider_sakuraaiengine\aimodel\qwen3_coder_30b_a3b_instruct
 * @covers     \aiprovider_sakuraaiengine\aimodel\qwen3_coder_480b_a35b_instruct_fp8
 */
class aimodel_helper_test extends \advanced_testcase {
    /**
     * Known model classes expose expected metadata.
     *
     * @dataProvider model_metadata_provider
     * @param string $class
     * @param string $name
     * @param bool $hassettings
     */
    public function test_model_metadata(string $class, string $name, bool $hassettings): void {
        $model = new $class();

        $this->assertSame($name, $model->get_model_name());
        $this->assertSame($name, $model->get_model_display_name());
        $this->assertSame($hassettings, $model->has_model_settings());
        $this->assertSame(sakuraaiengine_base::MODEL_TYPE_TEXT, $model->model_type());
    }

    /**
     * helper::get_model_class should return the matching model instance.
     *
     * @dataProvider helper_lookup_provider
     * @param string $name
     * @param string $expectedclass
     */
    public function test_helper_get_model_class_returns_expected_model(string $name, string $expectedclass): void {
        $model = helper::get_model_class($name);

        $this->assertInstanceOf($expectedclass, $model);
    }

    /**
     * helper::get_model_class should return null for unknown model names.
     */
    public function test_helper_get_model_class_returns_null_for_unknown_model(): void {
        $model = helper::get_model_class('unknown-model-name');

        $this->assertNull($model);
    }

    /**
     * Ensure helper discovers at least the known built-in model classes.
     */
    public function test_helper_get_model_classes_contains_known_models(): void {
        $classes = helper::get_model_classes();

        $this->assertContains(gpt_oss_120b::class, $classes);
        $this->assertContains(llm_jp_31_8x13b_instruct4::class, $classes);
        $this->assertContains(qwen3_coder_30b_a3b_instruct::class, $classes);
        $this->assertContains(qwen3_coder_480b_a35b_instruct_fp8::class, $classes);
    }

    /**
     * Helper should return unique classes implementing the provider model interface.
     */
    public function test_helper_get_model_classes_are_unique_and_valid(): void {
        $classes = helper::get_model_classes();

        $this->assertNotEmpty($classes);
        $this->assertSame($classes, array_values(array_unique($classes)));

        foreach ($classes as $class) {
            $this->assertTrue(is_subclass_of($class, sakuraaiengine_base::class));
        }
    }

    /**
     * Model names provided by built-in models should be unique.
     */
    public function test_built_in_model_names_are_unique(): void {
        $models = [
            new gpt_oss_120b(),
            new llm_jp_31_8x13b_instruct4(),
            new qwen3_coder_30b_a3b_instruct(),
            new qwen3_coder_480b_a35b_instruct_fp8(),
        ];

        $names = array_map(static fn($model): string => $model->get_model_name(), $models);

        $this->assertSame($names, array_values(array_unique($names)));
    }

    /**
     * Model lookup should be exact-match for names.
     */
    public function test_helper_get_model_class_requires_exact_name_match(): void {
        $model = helper::get_model_class('GPT-OSS-120B');

        $this->assertNull($model);
    }

    /**
     * Interface constants should keep expected values.
     */
    public function test_model_type_constants(): void {
        $this->assertSame(1, sakuraaiengine_base::MODEL_TYPE_TEXT);
        $this->assertSame(2, sakuraaiengine_base::MODEL_TYPE_IMAGE);
        $this->assertSame(3, sakuraaiengine_base::MODEL_TYPE_AUDIO);
    }

    /**
     * Model metadata provider.
     *
     * @return array[]
     */
    public static function model_metadata_provider(): array {
        return [
            [gpt_oss_120b::class, 'gpt-oss-120b', true],
            [llm_jp_31_8x13b_instruct4::class, 'llm-jp-3.1-8x13b-instruct4', false],
            [qwen3_coder_30b_a3b_instruct::class, 'Qwen3-Coder-30B-A3B-Instruct', false],
            [qwen3_coder_480b_a35b_instruct_fp8::class, 'Qwen3-Coder-480B-A35B-Instruct-FP8', false],
        ];
    }

    /**
     * Helper lookup provider.
     *
     * @return array[]
     */
    public static function helper_lookup_provider(): array {
        return [
            ['gpt-oss-120b', gpt_oss_120b::class],
            ['llm-jp-3.1-8x13b-instruct4', llm_jp_31_8x13b_instruct4::class],
            ['Qwen3-Coder-30B-A3B-Instruct', qwen3_coder_30b_a3b_instruct::class],
            ['Qwen3-Coder-480B-A35B-Instruct-FP8', qwen3_coder_480b_a35b_instruct_fp8::class],
        ];
    }
}
