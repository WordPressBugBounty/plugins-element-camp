<?php

namespace ElementCampPlugin\Elementor;
defined('ABSPATH') || exit(); // Exit if accessed directly

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Plugin;
use Elementor\Group_Control_Base;

add_action('elementor/init', function() {

    if (!class_exists('Elementor\Group_Control_Background')) {
        return;
    }

    class TCG_Pro_Group_Background extends Group_Control_Background {

        public $controls = [];
        protected static $fields;
        private static $background_types;

        public function array_insert_after(array $array, $key, array $new)
        {
            $keys = array_keys($array);
            $index = array_search($key, $keys);
            $pos = false === $index ? count($array) : $index + 1;
            return array_merge(array_slice($array, 0, $pos), $new, array_slice($array, $pos));
        }

        public function init_fields()
        {

            $active_breakpoints = Plugin::$instance->breakpoints->get_active_breakpoints();

            $location_device_args = [];
            $location_device_defaults = [
                'default' => [
                    'unit' => '%',
                ],
            ];

            $angel_device_args = [];
            $angel_device_defaults = [
                'default' => [
                    'unit' => 'deg',
                ],
            ];

            $position_device_args = [];
            $position_device_defaults = [
                'default' => 'center center',
            ];

            foreach ($active_breakpoints as $breakpoint_name => $breakpoint) {
                $location_device_args[$breakpoint_name] = $location_device_defaults;
                $angel_device_args[$breakpoint_name] = $angel_device_defaults;
                $position_device_args[$breakpoint_name] = $position_device_defaults;
            }

            // Third Color Controls
            $controls['color_c'] = [
                'label' => 'Third Color',
                'type' => Controls_Manager::COLOR,
                'default' => '#f2295b',
                'render_type' => 'ui',
                'condition' => [
                    'background' => ['tcg_gradient', 'tcg_gradient_4'],
                ],
                'of_type' => 'gradient',
            ];

            $controls['color_c_stop'] = [
                'label' => 'Third Color Location',
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%', 'custom'],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'device_args' => $location_device_args,
                'responsive' => true,
                'render_type' => 'ui',
                'condition' => [
                    'background' => ['tcg_gradient', 'tcg_gradient_4'],
                ],
                'of_type' => 'gradient',
            ];

            // Fourth Color Controls
            $controls['color_d'] = [
                'label' => 'Fourth Color',
                'type' => Controls_Manager::COLOR,
                'default' => '#8b5cf6',
                'render_type' => 'ui',
                'condition' => [
                    'background' => ['tcg_gradient_4'],
                ],
                'of_type' => 'gradient',
            ];

            $controls['color_d_stop'] = [
                'label' => 'Fourth Color Location',
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%', 'custom'],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'device_args' => $location_device_args,
                'responsive' => true,
                'render_type' => 'ui',
                'condition' => [
                    'background' => ['tcg_gradient_4'],
                ],
                'of_type' => 'gradient',
            ];

            $controls['tcg_gradient_angle'] = [
                'label' => 'Angle',
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['deg', 'grad', 'rad', 'turn', 'custom'],
                'default' => [
                    'unit' => 'deg',
                    'size' => 180,
                ],
                'device_args' => $angel_device_args,
                'responsive' => true,
                'selectors' => [
                    '{{SELECTOR}}' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}}{{#color_c.VALUE}}, {{color_c.VALUE}} {{color_c_stop.SIZE}}{{color_c_stop.UNIT}}{{/color_c.VALUE}}{{#color_d.VALUE}}, {{color_d.VALUE}} {{color_d_stop.SIZE}}{{color_d_stop.UNIT}}{{/color_d.VALUE}})',
                ],
                'condition' => [
                    'background' => ['tcg_gradient', 'tcg_gradient_4'],
                    'gradient_type' => 'linear',
                ],
                'of_type' => 'tcg_gradient',
            ];

            $controls['tcg_gradient_position'] = [
                'label' => 'Position',
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'center center' => 'Center Center',
                    'center left' => 'Center Left',
                    'center right' => 'Center Right',
                    'top center' => 'Top Center',
                    'top left' => 'Top Left',
                    'top right' => 'Top Right',
                    'bottom center' => 'Bottom Center',
                    'bottom left' => 'Bottom Left',
                    'bottom right' => 'Bottom Right',
                ],
                'default' => 'center center',
                'device_args' => $position_device_args,
                'responsive' => true,
                'selectors' => [
                    '{{SELECTOR}}' => 'background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}}{{#color_c.VALUE}}, {{color_c.VALUE}} {{color_c_stop.SIZE}}{{color_c_stop.UNIT}}{{/color_c.VALUE}}{{#color_d.VALUE}}, {{color_d.VALUE}} {{color_d_stop.SIZE}}{{color_d_stop.UNIT}}{{/color_d.VALUE}})',
                ],
                'condition' => [
                    'background' => ['tcg_gradient', 'tcg_gradient_4'],
                    'gradient_type' => 'radial',
                ],
                'of_type' => 'tcg_gradient',
            ];

            $fields = parent::init_fields();

            $fields = $this->array_insert_after(
                $fields,
                'color_b_stop',
                $controls
            );
            $modified_fields = [];

            foreach ($fields as $key => $field) {
                if (isset($field['condition']) && array_key_exists('background', $field['condition']) && in_array('gradient', $field['condition']['background']) && $key != 'gradient_position' && $key != 'gradient_angle') {
                    array_push($field['condition']['background'], 'tcg_gradient', 'tcg_gradient_4');
                    $modified_fields[$key] = $field;
                } else {
                    $modified_fields[$key] = $field;
                }
            }

            return $modified_fields;
        }

        protected function prepare_fields($fields)
        {
            $args = $this->get_args();

            $background_types = parent::get_background_types();

            // Add new background types
            $background_types['tcg_gradient'] = [
                'title' => '3 Colors Gradient',
                'icon' => 'eicon-barcode',
            ];

            $background_types['tcg_gradient_4'] = [
                'title' => '4 Colors Gradient',
                'icon' => 'eicon-editor-list-ol',
            ];

            $choose_types = [];

            foreach ($args['types'] as $type) {
                if (isset($background_types[$type])) {
                    $choose_types[$type] = $background_types[$type];
                }
            }

            $fields['background']['options'] = $choose_types;

            return Group_Control_Base::prepare_fields($fields);
        }
    }
});