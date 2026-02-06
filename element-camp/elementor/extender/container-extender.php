<?php

namespace ElementCampPlugin\Elementor;

use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Group_Control_Typography;


defined('ABSPATH') || exit(); // Exit if accessed directly

/**
 *  Elementor extra features
 */
class TCG_Pro_Container_Extender
{

    public function __construct()
    {

        // add container 3 gradient colors background
        add_action('elementor/element/container/section_background/before_section_end', function ($stack) {

            $section_bg = Plugin::instance()->controls_manager->get_control_from_stack($stack->get_unique_name(), 'background_background');
            $section_bg['options']['tcg_gradient'] = [
                'title' => esc_html__('3 Colors Gradient', 'elementor'),
                'icon' => 'eicon-barcode',
            ];
            $section_bg['options']['tcg_gradient_4'] = [
                'title' => esc_html__('4 Colors Gradient', 'elementor'),
                'icon' => 'eicon-barcode',
            ];
            $stack->update_control('background_background', $section_bg);
        }, 10, 3);

        // ADD THIS: Update hover background to include custom gradients
        add_action('elementor/element/container/section_background/before_section_end', function ($stack) {
            $section_bg_hover = Plugin::instance()->controls_manager->get_control_from_stack($stack->get_unique_name(), 'background_hover_background');
            if ($section_bg_hover) {
                $section_bg_hover['options']['tcg_gradient'] = [
                    'title' => esc_html__('3 Colors Gradient', 'elementor'),
                    'icon' => 'eicon-barcode',
                ];
                $section_bg_hover['options']['tcg_gradient_4'] = [
                    'title' => esc_html__('4 Colors Gradient', 'elementor'),
                    'icon' => 'eicon-barcode',
                ];
                $stack->update_control('background_hover_background', $section_bg_hover);
            }
        }, 15, 3); // Higher priority to ensure it runs after the normal background

        // add container parent hover background
        add_action('elementor/element/container/section_background/before_section_end', function ($stack) {
            $contaner_hover_bg = Plugin::instance()->controls_manager->get_control_groups('background');
            foreach ($contaner_hover_bg->get_fields() as $field_key => $field) {
                //var_dump($field_key);
                $control_id = 'background_hover_' . $field_key;
                $old_control_data = Plugin::instance()->controls_manager->get_control_from_stack($stack->get_unique_name(), $control_id);
                if (isset($old_control_data['selectors'])) {
                    $new_selectors = [];
                    foreach ($old_control_data['selectors'] as $selector => $style) {
                        $new_selectors['{{WRAPPER}}.tc-parent-container-hover-active'] = $style;
                    }

                    $old_control_data['selectors'] = array_merge($old_control_data['selectors'], $new_selectors);

                    $stack->update_control($control_id, $old_control_data);
                    //var_dump($old_control_data['selectors']);
                }
            }
        }, 10, 3);

        // add container 3 gradient colors background Overlay
        add_action('elementor/element/container/section_background_overlay/before_section_end', function ($stack) {

            $section_bg = Plugin::instance()->controls_manager->get_control_from_stack($stack->get_unique_name(), 'background_overlay_background');
            $section_bg['options']['tcg_gradient'] = [
                'title' => esc_html__('3 Colors Gradient', 'elementor'),
                'icon' => 'eicon-barcode',
            ];
            $section_bg['options']['tcg_gradient_4'] = [
                'title' => esc_html__('4 Colors Gradient', 'elementor'),
                'icon' => 'eicon-barcode',
            ];
            $stack->update_control('background_overlay_background', $section_bg);
        }, 10, 3);

        add_action('elementor/element/container/section_background_overlay/before_section_end', function ($stack) {
            $section_bg_overlay_hover = Plugin::instance()->controls_manager->get_control_from_stack($stack->get_unique_name(), 'background_overlay_hover_background');
            if ($section_bg_overlay_hover) {
                $section_bg_overlay_hover['options']['tcg_gradient'] = [
                    'title' => esc_html__('3 Colors Gradient', 'elementor'),
                    'icon' => 'eicon-barcode',
                ];
                $section_bg_overlay_hover['options']['tcg_gradient_4'] = [
                    'title' => esc_html__('4 Colors Gradient', 'elementor'),
                    'icon' => 'eicon-barcode',
                ];
                $stack->update_control('background_overlay_hover_background', $section_bg_overlay_hover);
            }
        }, 15, 3); // Higher priority

        // container controls
        add_action('elementor/frontend/container/before_render', [$this, 'before_container_render'], 10, 1);
        add_action('elementor/element/container/section_background/after_section_start', [$this, 'register_tc_container_background_controls_start'], 10, 3);
        add_action('elementor/element/container/section_background/before_section_end', [$this, 'register_tc_container_background_controls'], 10, 3);
        add_action('elementor/element/container/section_background_overlay/before_section_end', [$this, 'register_tc_container_background_overlay_controls'], 10, 3);
        add_action('elementor/element/container/section_border/before_section_end', [$this, 'register_tc_container_border_controls'], 10, 3);
        add_action('elementor/element/container/section_border/before_section_end', [$this, 'register_tc_container_outline_controls'], 10, 3);
        add_action('elementor/element/container/section_effects/after_section_end', [$this, 'register_tc_container_clip_path_controls'], 10, 3);
        add_action('elementor/element/container/section_effects/after_section_end', [$this, 'register_tc_container_padding_hover_controls'], 10, 3);
        add_action('elementor/element/container/section_effects/after_section_end', [$this, 'register_tc_container_style_controls'], 10, 3);
        add_action('elementor/element/container/section_effects/after_section_end', [$this, 'register_tc_container_hover_controls'], 10, 3);
        add_action('elementor/element/container/section_effects/after_section_end', [$this, 'register_tc_container_animations_controls'], 10, 3);
        add_action('elementor/element/container/section_effects/after_section_end', [$this, 'register_tc_container_float_cursor_style_controls'], 10, 3);
        add_action('elementor/element/container/section_layout_additional_options/before_section_end', [$this, 'register_tc_container_additional_options_controls'], 10, 3);
        add_action('elementor/element/container/section_background/before_section_end', [$this, 'register_tc_container_background_hover_controls'], 10, 3);
        add_action('elementor/element/container/section_background/before_section_end', [$this, 'register_tc_container_background_normal_controls'], 10, 3);
    }

    function register_tc_container_background_normal_controls($widget, $args)
    {

        $widget->start_injection([
            'at' => 'after',
            'of' => 'handle_slideshow_asset_loading',
        ]);

        $widget->add_control(
            'tc_container_background_blur_divider',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $widget->add_control(
            'tc_container_background_blur_method',
            [
                'label' => esc_html__('TCG Background Blur Method', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'backdrop-filter' => 'backdrop-filter',
                    'filter' => 'filter',
                ],
                'default' => 'backdrop-filter',
                'selectors' => [
                    '{{WRAPPER}}' => '-webkit-{{VALUE}}: blur({{tc_container_background_blur.SIZE}}px); {{VALUE}}: blur({{tc_container_background_blur.SIZE}}px);',
                ],
            ]
        );

        $widget->add_control(
            'tc_container_background_blur',
            [
                'label' => esc_html__('TCG Background Blur', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 250,
                    ],
                ],
            ]
        );

        $widget->end_injection();
    }

    function register_tc_container_background_hover_controls($widget, $args)
    {
        $widget->start_injection([
            'at' => 'before',
            'of' => 'background_hover_transition',
        ]);
        // add a control
        $widget->add_control(
            'tc_container_background_hover_blur_divider',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $widget->add_control(
            'tc_container_background_hover_blur_method',
            [
                'label' => esc_html__('TCG Background Blur Method', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'backdrop-filter' => 'backdrop-filter',
                    'filter' => 'filter',
                ],
                'default' => 'backdrop-filter',
                'selectors' => [
                    '{{WRAPPER}}:hover' => '{{VALUE}}: blur({{tc_container_background_hover_blur.SIZE}}px);',
                    '{{WRAPPER}}.tc-parent-container-hover-active' => '{{VALUE}}: blur({{tc_container_background_hover_blur.SIZE}}px);',
                ],
            ]
        );

        $widget->add_control(
            'tc_container_background_hover_blur',
            [
                'label' => esc_html__('TCG Background Blur', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 250,
                    ],
                ],
            ]
        );

        $widget->end_injection();
    }

    public function tc_selectors_refactor(array $selectors, string $value)
    {
        foreach ($selectors as $key => $selector) {
            $selectors[$key] = $value;
        }
        return $selectors;
    }
    function register_tc_container_background_controls_start($widget, $args)
    {

        $widget->add_control(
            'tc_container_hover_selector',
            [
                'label' => esc_html__('Choose Selector', 'themescamp-elements'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'container',
                'options' => [
                    'container'  => esc_html__('Container', 'themescamp-elements'),
                    'parent-container'  => esc_html__('Parent Container', 'themescamp-elements'),
                    'parent-parent-container'  => esc_html__('Parent Parent Container', 'themescamp-elements'),
                ],
                'render_type' => 'ui',
                'frontend_available' => true,
            ]
        );
    }

    function register_tc_container_clip_path_controls($widget, $args)
    {

        $widget->start_controls_section(
            'tcg_container_clip_path_section',
            [
                'label' => __('TCG Clip Path', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $widget->add_control(
            'tc_container_clip_path_popover_toggle',
            [
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label' => esc_html__('Clip Path', 'element-camp'),
                'label_off' => esc_html__('Default', 'element-camp'),
                'label_on' => esc_html__('Custom', 'element-camp'),
                'return_value' => 'yes',
            ]
        );

        $widget->start_popover();

        $widget->add_control(
            'tc_container_clip_path',
            [
                'label' => esc_html__('TCG Clip Path', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'element-camp'),
                    'circle' => esc_html__('Circle', 'element-camp'),
                    'ellipse' => esc_html__('Ellipse', 'element-camp'),
                    'inset' => esc_html__('Inset', 'element-camp'),
                    'polygon' => esc_html__('Polygon', 'element-camp'),
                ],
                'default' => 'none',
                'render_type' => 'ui',
                'frontend_available' => true,
                'selectors' => [
                    '{{WRAPPER}}' => 'clip-path: {{VALUE}}({{tc_container_clip_path_value.VALUE}});',
                ],
            ]
        );

        $widget->add_control(
            'tc_container_clip_path_value',
            [
                'label' => esc_html__('TCG Clip Path Value', 'element-camp'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '20% 0, 100% 15%, 100% 100%, 0 100%, 0 15%',
                'render_type' => 'ui',
                'frontend_available' => true,
                'condition' => [
                    'tc_container_clip_path' => ['circle', 'ellipse', 'inset', 'polygon'],
                ],
            ]
        );

        $widget->end_popover();

        $widget->end_controls_section();
    }

    function register_tc_container_style_controls($widget, $args)
    {

        $widget->start_controls_section(
            'tcg_container_style_section',
            [
                'label' => __('Additional Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $widget->add_control(
            'tcg_container_blend_mode',
            [
                'label' => esc_html__('Blend Mode', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Normal', 'element-camp'),
                    'multiply' => esc_html__('Multiply', 'element-camp'),
                    'screen' => esc_html__('Screen', 'element-camp'),
                    'overlay' => esc_html__('Overlay', 'element-camp'),
                    'darken' => esc_html__('Darken', 'element-camp'),
                    'lighten' => esc_html__('Lighten', 'element-camp'),
                    'color-dodge' => esc_html__('Color Dodge', 'element-camp'),
                    'saturation' => esc_html__('Saturation', 'element-camp'),
                    'color' => esc_html__('Color', 'element-camp'),
                    'difference' => esc_html__('Difference', 'element-camp'),
                    'exclusion' => esc_html__('Exclusion', 'element-camp'),
                    'hue' => esc_html__('Hue', 'element-camp'),
                    'luminosity' => esc_html__('Luminosity', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'mix-blend-mode: {{VALUE}}',
                ],
                'separator' => 'none',
            ]
        );

        $widget->end_controls_section();
    }

    function register_tc_container_hover_controls($widget, $args)
    {


        $widget->start_controls_section(
            'tcg_advanced_hover_section',
            [
                'label' => __('TCG Advanced Hover', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $widget->add_responsive_control(
            'tcg_advanced_hover',
            [
                'label' => esc_html__('TCG Advanced Hover', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'no' => esc_html__('Off', 'element-camp'),
                    'yes' => esc_html__('On', 'element-camp'),
                ],
                'default' => 'no',
                'render_type' => 'ui',
                'frontend_available' => true,
                'responsive' => [
                    'desktop' => [
                        'default' => 'no',
                    ],
                    'tablet' => [
                        'default' => 'no',
                    ],
                    'mobile' => [
                        'default' => 'no',
                    ],
                ],
            ]
        );

        $widget->add_control(
            'tcg_advanced_hover_transition',
            [
                'label' => esc_html__('Advanced Hover Transition', 'themescamp-elements'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 10,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'default' => [
                    'size' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}}.tc-container-advanced-hover' => 'animation: fadeOut {{SIZE}}s;',
                    '{{WRAPPER}}.tcg-container-adv-hover-active' => 'animation: fadeIn {{SIZE}}s;',
                ],
            ]
        );

        $widget->end_controls_section();
    }
    function register_tc_container_animations_controls($widget, $args)
    {

        $widget->start_controls_section(
            'tcg_advanced_animations_section',
            [
                'label' => __('TCG Animations', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $widget->add_control(
            'float_cursor',
            [
                'label' => esc_html__('Float Cursor', 'themescamp-plugin'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'themescamp-plugin'),
                'label_off' => esc_html__('Off', 'themescamp-plugin'),
                'return_value' => 'yes',
                'default' => 'no',
                'frontend_available' => true,
            ]
        );

        $widget->add_control(
            'float_cursor_text',
            [
                'label' => esc_html__('Float Cursor Text', 'themescamp-plugin'),
                'default' => esc_html__('Hold And Drag', 'themescamp-plugin'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'condition' => [
                    'float_cursor' => 'yes',
                ],
                'frontend_available' => true,
            ]
        );

        $widget->add_control(
            'float_cursor_icon',
            [
                'label' => esc_html__('Choose Icon', 'themescamp-plugin'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'condition' => [
                    'float_cursor' => 'yes',
                ],
                'frontend_available' => true,
            ]
        );

        $widget->add_control(
            'float_cursor_icon_position',
            [
                'label' => esc_html__('Icon Position', 'themescamp-plugin'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'before' => esc_html__('Before', 'themescamp-plugin'),
                    'after' => esc_html__('After', 'themescamp-plugin'),
                ],
                'default' => 'before',
                'condition' => [
                    'float_cursor' => 'yes',
                    'float_cursor_icon[value]!' => '',
                ],
                'frontend_available' => true,
            ]
        );

        $widget->add_control(
            'tcg_advanced_animations',
            [
                'label' => esc_html__('Animations', 'themescamp-elements'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'themescamp-elements'),
                    'tc-mouse-parallax' => esc_html__('Mouse Parallax', 'themescamp-elements'),
                ],
                'render_type' => 'ui',
                'frontend_available' => true,
            ]
        );

        $widget->add_control(
            'tcg_mouse_parallax_enable_scale',
            [
                'label' => esc_html__('Enable Scale Effect', 'themescamp-elements'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'themescamp-elements'),
                'label_off' => esc_html__('No', 'themescamp-elements'),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'tcg_advanced_animations' => 'tc-mouse-parallax',
                ],
                'render_type' => 'ui',
                'frontend_available' => true,
            ]
        );

        $widget->add_control(
            'tcg_mouse_parallax_scope',
            [
                'label' => esc_html__('Parallax Scope', 'themescamp-elements'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'global' => esc_html__('Global (Entire Page)', 'themescamp-elements'),
                    'container' => esc_html__('Container (Local Area)', 'themescamp-elements'),
                ],
                'default' => 'global',
                'description' => esc_html__('Global: Parallax based on mouse position on entire page. Container: Parallax based on mouse position within container only.', 'themescamp-elements'),
                'condition' => [
                    'tcg_advanced_animations' => 'tc-mouse-parallax',
                ],
                'render_type' => 'ui',
                'frontend_available' => true,
            ]
        );

        $widget->add_control(
            'tcg_mouse_parallax_item_class',
            [
                'label' => esc_html__('Parallax Item Class', 'themescamp-elements'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('e.g., parallaxed', 'themescamp-elements'),
                'description' => esc_html__('Leave empty to apply parallax to the container itself. Add a class to target specific child elements instead.', 'themescamp-elements'),
                'condition' => [
                    'tcg_advanced_animations' => 'tc-mouse-parallax',
                ],
                'render_type' => 'ui',
                'frontend_available' => true,
            ]
        );

        $widget->add_control(
            'tcg_mouse_parallax_scale_intensity',
            [
                'label' => esc_html__('Scale Intensity', 'themescamp-elements'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.01,
                    ],
                ],
                'default' => [
                    'size' => 0.15,
                ],
                'condition' => [
                    'tcg_advanced_animations' => 'tc-mouse-parallax',
                    'tcg_mouse_parallax_enable_scale' => 'yes',
                ],
                'render_type' => 'ui',
                'frontend_available' => true,
            ]
        );

        $widget->add_control(
            'tcg_advanced_gsap_animations',
            [
                'label' => esc_html__('Gsap Animations', 'themescamp-elements'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'themescamp-elements'),
                    'flip-scroll' => esc_html__('Flip Scroll', 'themescamp-elements'),
                ],
                'render_type' => 'ui',
                'frontend_available' => true,
            ]
        );

        $widget->end_controls_section();
    }
    function register_tc_container_float_cursor_style_controls($widget, $args)
    {

        $widget->start_controls_section(
            'tcg_float_cursor_style_section',
            [
                'label' => __('TCG Float Cursor Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['float_cursor' => 'yes']
            ]
        );

        $widget->add_control(
            'float_cursor_width',
            [
                'label' => esc_html__('Width', 'themescamp-plugin'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0.5,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                    'rem' => [
                        'min' => 0.5,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcg-float-cursor' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $widget->add_control(
            'float_cursor_height',
            [
                'label' => esc_html__('Height', 'themescamp-plugin'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0.5,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                    'rem' => [
                        'min' => 0.5,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcg-float-cursor' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $widget->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'float_cursor_text_typography',
                'selector' => '{{WRAPPER}} .tcg-float-cursor',
            ]
        );
        $widget->add_control(
            'float_cursor_text_color',
            [
                'label' => esc_html__('Text Color', 'themescamp-plugin'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcg-float-cursor' => 'color: {{VALUE}};',
                ],
            ]
        );
        $widget->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'float_cursor_background',
                'selector' => '{{WRAPPER}} .tcg-float-cursor',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
            ]
        );
        $widget->add_control(
            'float_cursor_blur_method',
            [
                'label' => esc_html__('Blur Method', 'themescamp-plugin'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'backdrop-filter' => 'backdrop-filter',
                    'filter' => 'filter',
                ],
                'default' => 'backdrop-filter',
                'selectors' => [
                    '{{WRAPPER}} .tcg-float-cursor' => '{{VALUE}}: blur({{float_cursor_blur_value.SIZE}}px);',
                ],
            ]
        );
        $widget->add_control(
            'float_cursor_blur_value',
            [
                'label' => esc_html__('Blur', 'themescamp-plugin'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 250,
                    ],
                ],
            ]
        );

        $widget->add_control(
            'float_cursor_icon_heading',
            [
                'label' => esc_html__('Icon', 'themescamp-plugin'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'float_cursor_icon[value]!' => '',
                ]
            ]
        );

        $widget->add_control(
            'float_cursor_icon_size',
            [
                'label' => esc_html__('Icon Size', 'themescamp-plugin'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0.5,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                    'rem' => [
                        'min' => 0.5,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcg-float-cursor i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcg-float-cursor svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'float_cursor_icon[value]!' => '',
                ]
            ]
        );

        $widget->add_control(
            'float_cursor_icon_color',
            [
                'label' => esc_html__('Icon Color', 'themescamp-plugin'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcg-float-cursor i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcg-float-cursor svg' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'float_cursor_icon[value]!' => '',
                ]
            ]
        );

        $widget->end_controls_section();
    }

    function register_tc_container_background_controls($widget, $args)
    {

        $widget->add_control(
            'tc_container_background_parallax_divider',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $widget->add_control(
            'tc_container_background_parallax',
            [
                'label' => esc_html__('TCG Background Parallax', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'element-camp'),
                'label_off' => esc_html__('Off', 'element-camp'),
                'return_value' => 'yes',
                'default' => 'no',
                'render_type' => 'ui',
                'frontend_available' => true,
            ]
        );

        $widget->add_control(
            'tc_container_double_background',
            [
                'label' => esc_html__('Double Background', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'return_value' => 'yes',
                'default' => 'no',
                'description' => esc_html__('Combine two backgrounds for complex gradient effects. For best results, use gradients for both layers.', 'element-camp'),
            ]
        );

        $widget->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'tc_container_double_background_second',
                'label' => esc_html__('Second Background', 'element-camp'),
                'types' => ['classic', 'gradient', 'tcg_gradient', 'tcg_gradient_4'],
                'selector' => '{{WRAPPER}}', // We'll handle this in JS
                'condition' => [
                    'tc_container_double_background' => 'yes',
                ],
            ]
        );

        $widget->add_control(
            'tc_container_background_divider_dark_mode',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $widget->add_control(
            'tc_container_background_title_dark_mode',
            [
                'label' => esc_html__('TCG Dark Mode Background', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'none',
            ]
        );

        $widget->start_controls_tabs('_tabs_tc_container_background_dark_mode');

        $widget->start_controls_tab(
            '_tab_tc_container_background_normal_dark_mode',
            [
                'label' => esc_html__('Normal', 'element-camp'),
            ]
        );

        $widget->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'tc_container_background_dark_mode',
                'selector' => '{{WRAPPER}}',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'fields_options' => [
                    'color' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-color: {{VALUE}};',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-color: {{VALUE}};',
                        ],
                    ],
                    'gradient_angle' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
                        ],
                    ],
                    'gradient_position' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
                        ],
                    ],
                    'image' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-image: url("{{URL}}");',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-image: url("{{URL}}");',
                        ],
                    ],
                    'position' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-position: {{VALUE}};',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-position: {{VALUE}};',
                        ],
                    ],
                    'xpos' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-position: {{SIZE}}{{UNIT}} {{ypos.SIZE}}{{ypos.UNIT}}',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-position: {{SIZE}}{{UNIT}} {{ypos.SIZE}}{{ypos.UNIT}}',
                        ],
                    ],
                    'ypos' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-position: {{SIZE}}{{UNIT}} {{ypos.SIZE}}{{ypos.UNIT}}',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-position: {{SIZE}}{{UNIT}} {{ypos.SIZE}}{{ypos.UNIT}}',
                        ],
                    ],
                    'attachment' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode (desktop+){{SELECTOR}}' => 'background-attachment: {{VALUE}};',
                            '} body.tcg-dark-mode (desktop+){{SELECTOR}}' => 'background-attachment: {{VALUE}};',
                        ],
                    ],
                    'repeat' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-repeat: {{VALUE}};',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-repeat: {{VALUE}};',
                        ],
                    ],
                    'size' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-size: {{VALUE}};',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-size: {{VALUE}};',
                        ],
                    ],
                    'bg_width' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-size: {{SIZE}}{{UNIT}} auto',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-size: {{SIZE}}{{UNIT}} auto',
                        ],
                    ],
                    'video_fallback' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background: url("{{URL}}") 50% 50%; background-size: cover;',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background: url("{{URL}}") 50% 50%; background-size: cover;',
                        ],
                    ],
                ]
            ]
        );

        $widget->end_controls_tab();

        $widget->start_controls_tab(
            '_tab_tc_container_background_hover_dark_mode',
            [
                'label' => esc_html__('Hover', 'element-camp'),
            ]
        );

        $widget->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'tc_container_background_hover_dark_mode',
                'selector' => '{{WRAPPER}}:hover',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'fields_options' => [
                    'color' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-color: {{VALUE}};',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-color: {{VALUE}};',
                        ],
                    ],
                    'gradient_angle' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
                        ],
                    ],
                    'gradient_position' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
                        ],
                    ],
                    'image' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-image: url("{{URL}}");',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-image: url("{{URL}}");',
                        ],
                    ],
                    'position' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-position: {{VALUE}};',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-position: {{VALUE}};',
                        ],
                    ],
                    'xpos' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-position: {{SIZE}}{{UNIT}} {{ypos.SIZE}}{{ypos.UNIT}}',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-position: {{SIZE}}{{UNIT}} {{ypos.SIZE}}{{ypos.UNIT}}',
                        ],
                    ],
                    'ypos' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-position: {{SIZE}}{{UNIT}} {{ypos.SIZE}}{{ypos.UNIT}}',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-position: {{SIZE}}{{UNIT}} {{ypos.SIZE}}{{ypos.UNIT}}',
                        ],
                    ],
                    'attachment' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode (desktop+){{SELECTOR}}' => 'background-attachment: {{VALUE}};',
                            '} body.tcg-dark-mode (desktop+){{SELECTOR}}' => 'background-attachment: {{VALUE}};',
                        ],
                    ],
                    'repeat' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-repeat: {{VALUE}};',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-repeat: {{VALUE}};',
                        ],
                    ],
                    'size' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-size: {{VALUE}};',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-size: {{VALUE}};',
                        ],
                    ],
                    'bg_width' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-size: {{SIZE}}{{UNIT}} auto',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-size: {{SIZE}}{{UNIT}} auto',
                        ],
                    ],
                    'video_fallback' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background: url("{{URL}}") 50% 50%; background-size: cover;',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background: url("{{URL}}") 50% 50%; background-size: cover;',
                        ],
                    ],
                ]
            ]
        );

        $widget->end_controls_tab();

        $widget->end_controls_tabs();
    }

    function register_tc_container_background_overlay_controls($widget, $args)
    {

        $widget->add_control(
            'tc_container_background_overlay_divider_dark_mode',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $widget->add_control(
            'tc_container_background_overlay_title_dark_mode',
            [
                'label' => esc_html__('TCG Dark Mode Background Overlay', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'none',
            ]
        );

        $widget->start_controls_tabs('_tabs_tc_container_background_overlay');

        $widget->start_controls_tab(
            '_tab_tc_container_background_overlay_normal_dark_mode',
            [
                'label' => esc_html__('Normal', 'element-camp'),
            ]
        );

        $tc_container_background_overlay_selectors = [
            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}}::before' => 'background-color: {{VALUE}};',
            '} body.tcg-dark-mode {{WRAPPER}}::before' => 'background-color: {{VALUE}};',

            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} > .elementor-background-video-container::before' => 'background-color: {{VALUE}};',
            '} body.tcg-dark-mode {{WRAPPER}} > .elementor-background-video-container::before' => 'background-color: {{VALUE}};',

            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} > .e-con-inner > .elementor-background-video-container::before' => 'background-color: {{VALUE}};',
            '} body.tcg-dark-mode {{WRAPPER}} > .e-con-inner > .elementor-background-video-container::before' => 'background-color: {{VALUE}};',

            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} > .e-con-inner > .elementor-background-slideshow::before' => 'background-color: {{VALUE}};',
            '} body.tcg-dark-mode {{WRAPPER}} > .e-con-inner > .elementor-background-slideshow::before' => 'background-color: {{VALUE}};',

            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} > .elementor-motion-effects-container > .elementor-motion-effects-layer::before' => 'background-color: {{VALUE}};',
            '} body.tcg-dark-mode {{WRAPPER}} > .elementor-motion-effects-container > .elementor-motion-effects-layer::before' => 'background-color: {{VALUE}};',
        ];

        $background_overlay_selector = '{{WRAPPER}}::before, {{WRAPPER}} > .elementor-background-video-container::before, {{WRAPPER}} > .e-con-inner > .elementor-background-video-container::before, {{WRAPPER}} > .elementor-background-slideshow::before, {{WRAPPER}} > .e-con-inner > .elementor-background-slideshow::before, {{WRAPPER}} > .elementor-motion-effects-container > .elementor-motion-effects-layer::before';

        $widget->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'tc_container_background_overlay_dark_mode',
                'selector' => '{{WRAPPER}} > .elementor-element-populated >  .elementor-background-overlay',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'fields_options' => [
                    'background' => [
                        'selectors' => [
                            // Hack to set the `::before` content in order to render it only when there is a background overlay.
                            $background_overlay_selector => '--background-overlay: \'\';',
                        ],
                    ],
                    'color' => [
                        'selectors' => $this->tc_selectors_refactor($tc_container_background_overlay_selectors, 'background-color: {{VALUE}};'),
                    ],
                    'gradient_angle' => [
                        'selectors' => $this->tc_selectors_refactor($tc_container_background_overlay_selectors, 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})'),
                    ],
                    'gradient_position' => [
                        'selectors' => $this->tc_selectors_refactor($tc_container_background_overlay_selectors, 'background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})'),
                    ],
                    'image' => [
                        'selectors' => $this->tc_selectors_refactor($tc_container_background_overlay_selectors, 'background-image: url("{{URL}}");'),
                    ],
                    'position' => [
                        'selectors' => $this->tc_selectors_refactor($tc_container_background_overlay_selectors, 'background-position: {{VALUE}};'),
                    ],
                    'xpos' => [
                        'selectors' => $this->tc_selectors_refactor($tc_container_background_overlay_selectors, 'background-position: {{SIZE}}{{UNIT}} {{ypos.SIZE}}{{ypos.UNIT}}'),
                    ],
                    'ypos' => [
                        'selectors' => $this->tc_selectors_refactor($tc_container_background_overlay_selectors, 'background-position: {{xpos.SIZE}}{{xpos.UNIT}} {{SIZE}}{{UNIT}}'),
                    ],
                    'attachment' => [
                        'selectors' => $this->tc_selectors_refactor($tc_container_background_overlay_selectors, 'background-attachment: {{VALUE}};'),
                    ],
                    'repeat' => [
                        'selectors' => $this->tc_selectors_refactor($tc_container_background_overlay_selectors, 'background-repeat: {{VALUE}};'),
                    ],
                    'size' => [
                        'selectors' => $this->tc_selectors_refactor($tc_container_background_overlay_selectors, 'background-size: {{VALUE}};'),
                    ],
                    'bg_width' => [
                        'selectors' => $this->tc_selectors_refactor($tc_container_background_overlay_selectors, 'background-size: {{SIZE}}{{UNIT}} auto'),
                    ],
                    'video_fallback' => [
                        'selectors' => $this->tc_selectors_refactor($tc_container_background_overlay_selectors, 'background: url("{{URL}}") 50% 50%; background-size: cover;'),
                    ],
                ]
            ]
        );

        $widget->add_responsive_control(
            'tc_container_background_overlay_opacity_dark_mode',
            [
                'label' => esc_html__('Opacity', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => .5,
                ],
                'range' => [
                    'px' => [
                        'max' => 1,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => $this->tc_selectors_refactor($tc_container_background_overlay_selectors, '--overlay-opacity: {{SIZE}};'),
                'condition' => [
                    'tc_container_background_overlay_dark_mode_background' => ['classic', 'gradient'],
                ],
            ]
        );

        $widget->end_controls_tab();

        $widget->start_controls_tab(
            '_tab_tc_container_background_overlay_hover_dark_mode',
            [
                'label' => esc_html__('Hover', 'element-camp'),
            ]
        );

        $tc_container_background_overlay_hover_selectors = [
            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}}:hover::before' => 'background-color: {{VALUE}};',
            '} body.tcg-dark-mode {{WRAPPER}}:hover::before' => 'background-color: {{VALUE}};',

            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}}:hover > .elementor-background-video-container::before' => 'background-color: {{VALUE}};',
            '} body.tcg-dark-mode {{WRAPPER}}:hover > .elementor-background-video-container::before' => 'background-color: {{VALUE}};',

            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}}:hover > .e-con-inner > .elementor-background-video-container::before' => 'background-color: {{VALUE}};',
            '} body.tcg-dark-mode {{WRAPPER}}:hover > .e-con-inner > .elementor-background-video-container::before' => 'background-color: {{VALUE}};',

            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} > .e-con-inner > .elementor-background-slideshow:hover::before' => 'background-color: {{VALUE}};',
            '} body.tcg-dark-mode {{WRAPPER}} > .e-con-inner > .elementor-background-slideshow:hover::before' => 'background-color: {{VALUE}};',

            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} > .elementor-motion-effects-container > .elementor-motion-effects-layer:hover::before' => 'background-color: {{VALUE}};',
            '} body.tcg-dark-mode {{WRAPPER}} > .elementor-motion-effects-container > .elementor-motion-effects-layer:hover::before' => 'background-color: {{VALUE}};',
        ];

        $background_overlay_hover_selector = '{{WRAPPER}}:hover::before, {{WRAPPER}}:hover > .elementor-background-video-container::before, {{WRAPPER}}:hover > .e-con-inner > .elementor-background-video-container::before, {{WRAPPER}} > .elementor-background-slideshow:hover::before, {{WRAPPER}} > .e-con-inner > .elementor-background-slideshow:hover::before';

        $widget->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'tc_container_background_overlay_hover_dark_mode',
                'selector' => '{{WRAPPER}}:hover > .elementor-element-populated >  .elementor-background-overlay',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'fields_options' => [
                    'background' => [
                        'selectors' => [
                            // Hack to set the `::before` content in order to render it only when there is a background overlay.
                            $background_overlay_hover_selector => '--background-overlay: \'\';',
                        ],
                    ],
                    'color' => [
                        'selectors' => $this->tc_selectors_refactor($tc_container_background_overlay_hover_selectors, 'background-color: {{VALUE}};'),
                    ],
                    'gradient_angle' => [
                        'selectors' => $this->tc_selectors_refactor($tc_container_background_overlay_hover_selectors, 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})'),
                    ],
                    'gradient_position' => [
                        'selectors' => $this->tc_selectors_refactor($tc_container_background_overlay_hover_selectors, 'background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})'),
                    ],
                    'image' => [
                        'selectors' => $this->tc_selectors_refactor($tc_container_background_overlay_hover_selectors, 'background-image: url("{{URL}}");'),
                    ],
                    'position' => [
                        'selectors' => $this->tc_selectors_refactor($tc_container_background_overlay_hover_selectors, 'background-position: {{VALUE}};'),
                    ],
                    'xpos' => [
                        'selectors' => $this->tc_selectors_refactor($tc_container_background_overlay_hover_selectors, 'background-position: {{SIZE}}{{UNIT}} {{ypos.SIZE}}{{ypos.UNIT}}'),
                    ],
                    'ypos' => [
                        'selectors' => $this->tc_selectors_refactor($tc_container_background_overlay_hover_selectors, 'background-position: {{xpos.SIZE}}{{xpos.UNIT}} {{SIZE}}{{UNIT}}'),
                    ],
                    'attachment' => [
                        'selectors' => $this->tc_selectors_refactor($tc_container_background_overlay_hover_selectors, 'background-attachment: {{VALUE}};'),
                    ],
                    'repeat' => [
                        'selectors' => $this->tc_selectors_refactor($tc_container_background_overlay_hover_selectors, 'background-repeat: {{VALUE}};'),
                    ],
                    'size' => [
                        'selectors' => $this->tc_selectors_refactor($tc_container_background_overlay_hover_selectors, 'background-size: {{VALUE}};'),
                    ],
                    'bg_width' => [
                        'selectors' => $this->tc_selectors_refactor($tc_container_background_overlay_hover_selectors, 'background-size: {{SIZE}}{{UNIT}} auto'),
                    ],
                    'video_fallback' => [
                        'selectors' => $this->tc_selectors_refactor($tc_container_background_overlay_hover_selectors, 'background: url("{{URL}}") 50% 50%; background-size: cover;'),
                    ],
                ]
            ]
        );

        $widget->add_responsive_control(
            'tc_container_background_overlay_opacity_hover_dark_mode',
            [
                'label' => esc_html__('Opacity', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => .5,
                ],
                'range' => [
                    'px' => [
                        'max' => 1,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => $this->tc_selectors_refactor($tc_container_background_overlay_hover_selectors, '--overlay-opacity: {{SIZE}};'),
                'condition' => [
                    'tc_container_background_overlay_hover_dark_mode_background' => ['classic', 'gradient'],
                ],
            ]
        );

        $widget->end_controls_tab();

        $widget->end_controls_tabs();
    }

    function register_tc_container_border_controls($widget, $args)
    {

        $widget->add_control(
            'advanced_border_popover-toggle',
            [
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label' => esc_html__('Custom Border Settings', 'themescamp-elements'),
                'label_off' => esc_html__('Default', 'themescamp-elements'),
                'label_on' => esc_html__('Custom', 'themescamp-elements'),
                'return_value' => 'yes',
            ]
        );

        $widget->start_popover();
        $widget->add_control(
            'advanced_border_top_style',
            [
                'label' => esc_html__('Border Top Style', 'themescamp-elements'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'themescamp-elements'),
                    'none' => esc_html__('None', 'themescamp-elements'),
                    'solid' => esc_html__('Solid', 'themescamp-elements'),
                    'double' => esc_html__('Double', 'themescamp-elements'),
                    'dashed' => esc_html__('Dashed', 'themescamp-elements'),
                    'dotted' => esc_html__('Dotted', 'themescamp-elements'),
                    'groove' => esc_html__('Groove', 'themescamp-elements'),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}' => 'border-top-style: {{VALUE}};',
                ],
            ]
        );
        $widget->add_control(
            'advanced_border_top_color',
            [
                'label' => esc_html__('Border Top Color', 'themescamp-elements'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => 'border-top-color: {{VALUE}};',
                ],
            ]
        );
        $widget->add_control(
            'advanced_border_top_width',
            [
                'label' => esc_html__('Border Top Width', 'themescamp-elements'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}}' => 'border-top-width: {{SIZE}}{{UNIT}};',
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
            ]
        );
        $widget->add_control(
            'border_bottom_separator_border',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );
        $widget->add_control(
            'advanced_border_bottom_style',
            [
                'label' => esc_html__('Border Bottom Style', 'themescamp-elements'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'themescamp-elements'),
                    'none' => esc_html__('None', 'themescamp-elements'),
                    'solid' => esc_html__('Solid', 'themescamp-elements'),
                    'double' => esc_html__('Double', 'themescamp-elements'),
                    'dashed' => esc_html__('Dashed', 'themescamp-elements'),
                    'dotted' => esc_html__('Dotted', 'themescamp-elements'),
                    'groove' => esc_html__('Groove', 'themescamp-elements'),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}' => 'border-bottom-style: {{VALUE}};',
                ],
            ]
        );
        $widget->add_control(
            'advanced_border_bottom_color',
            [
                'label' => esc_html__('Border Bottom Color', 'themescamp-elements'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => 'border-bottom-color: {{VALUE}};',
                ],
            ]
        );
        $widget->add_control(
            'advanced_border_bottom_width',
            [
                'label' => esc_html__('Border Bottom Width', 'themescamp-elements'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}}' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
            ]
        );
        $widget->add_control(
            'border_right_separator_border',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );
        $widget->add_control(
            'advanced_border_right_style',
            [
                'label' => esc_html__('Border Right Style', 'themescamp-elements'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'themescamp-elements'),
                    'none' => esc_html__('None', 'themescamp-elements'),
                    'solid' => esc_html__('Solid', 'themescamp-elements'),
                    'double' => esc_html__('Double', 'themescamp-elements'),
                    'dashed' => esc_html__('Dashed', 'themescamp-elements'),
                    'dotted' => esc_html__('Dotted', 'themescamp-elements'),
                    'groove' => esc_html__('Groove', 'themescamp-elements'),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}' => 'border-right-style: {{VALUE}};',
                ],
            ]
        );
        $widget->add_control(
            'advanced_border_right_color',
            [
                'label' => esc_html__('Border Right Color', 'themescamp-elements'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => 'border-right-color: {{VALUE}};',
                ],
            ]
        );
        $widget->add_control(
            'advanced_border_right_width',
            [
                'label' => esc_html__('Border Right Width', 'themescamp-elements'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}}' => 'border-right-width: {{SIZE}}{{UNIT}};',
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
            ]
        );
        $widget->add_control(
            'border_left_separator_border',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );
        $widget->add_control(
            'advanced_border_left_style',
            [
                'label' => esc_html__('Border Left Style', 'themescamp-elements'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'themescamp-elements'),
                    'none' => esc_html__('None', 'themescamp-elements'),
                    'solid' => esc_html__('Solid', 'themescamp-elements'),
                    'double' => esc_html__('Double', 'themescamp-elements'),
                    'dashed' => esc_html__('Dashed', 'themescamp-elements'),
                    'dotted' => esc_html__('Dotted', 'themescamp-elements'),
                    'groove' => esc_html__('Groove', 'themescamp-elements'),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}' => 'border-left-style: {{VALUE}};',
                ],
            ]
        );
        $widget->add_control(
            'advanced_border_left_color',
            [
                'label' => esc_html__('Border Left Color', 'themescamp-elements'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => 'border-left-color: {{VALUE}};',
                ],
            ]
        );
        $widget->add_control(
            'advanced_border_left_width',
            [
                'label' => esc_html__('Border Left Width', 'themescamp-elements'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}}' => 'border-left-width: {{SIZE}}{{UNIT}};',
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
            ]
        );

        $widget->end_popover();

        $widget->add_control(
            'tc_container_border_divider_dark_mode',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $widget->add_control(
            'tc_container_border_title_dark_mode',
            [
                'label' => esc_html__('TCG Dark Mode Border', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'none',
            ]
        );
        $widget->start_controls_tabs('_tabs_tc_container_border_dark_mode');
        $widget->start_controls_tab(
            '_tab_tc_container_border_normal_dark_mode',
            [
                'label' => esc_html__('Normal', 'element-camp'),
            ]
        );
        $widget->add_control(
            'tc_container_border_color_dark_mode',
            [
                'label' => esc_html__('Border Color (Dark Mode)', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}}' => 'border-color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}}' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $widget->end_controls_tab();
        $widget->start_controls_tab(
            '_tab_tc_container_border_hover_dark_mode',
            [
                'label' => esc_html__('Hover', 'element-camp'),
            ]
        );
        $widget->add_control(
            'tc_container_border_color_dark_mode_hover',
            [
                'label' => esc_html__('Border Color (Dark Mode)', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}}:hover' => 'border-color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}}:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $widget->end_controls_tab();
        $widget->end_controls_tabs();
    }

    function register_tc_container_outline_controls($widget, $args)
    {
        $widget->start_injection([
            'at' => 'after',
            'of' => 'advanced_border_popover-toggle',
        ]);

        $widget->add_control(
            'tc_container_outline',
            [
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label' => esc_html__('Outline', 'themescamp-elements'),
                'label_off' => esc_html__('Default', 'themescamp-elements'),
                'label_on' => esc_html__('Custom', 'themescamp-elements'),
                'return_value' => 'yes',
            ]
        );

        $widget->start_popover();

        $widget->add_control(
            'tc_container_outline_type',
            [
                'label' => esc_html__('Outline Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'none' => esc_html__('None', 'element-camp'),
                    'solid' => esc_html__('Solid', 'element-camp'),
                    'double' => esc_html__('Double', 'element-camp'),
                    'dotted' => esc_html__('Dotted', 'element-camp'),
                    'dashed' => esc_html__('Dashed', 'element-camp'),
                    'groove' => esc_html__('Groove', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'outline-style: {{VALUE}};',
                ],
            ]
        );

        $widget->add_responsive_control(
            'tc_container_outline_width',
            [
                'label' => esc_html__('Outline Width', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem', 'vw', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'condition' => [
                    'tc_container_outline_type!' => ['', 'none'],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'outline-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $widget->add_responsive_control(
            'tc_container_outline_color',
            [
                'label' => esc_html__('Outline Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}' => 'outline-color: {{VALUE}};',
                ],
                'condition' => [
                    'tc_container_outline_type!' => ['', 'none'],
                ],
            ]
        );

        $widget->add_responsive_control(
            'tc_container_outline_offset',
            [
                'label' => esc_html__('Outline Offset', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'condition' => [
                    'tc_container_outline_type!' => ['', 'none'],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'outline-offset: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $widget->end_popover();

        $widget->end_injection();
    }

    function register_tc_container_additional_options_controls($widget, $args)
    {

        $widget->add_control(
            'tc_container_pointers_events',
            [
                'label' => esc_html__('Pointer Events', 'themescamp-elements'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    ''  => esc_html__('Default', 'themescamp-elements'),
                    'auto'  => esc_html__('Auto', 'themescamp-elements'),
                    'none'  => esc_html__('None', 'themescamp-elements'),
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'pointer-events: {{VALUE}};',
                ],
            ]
        );
    }

    function register_tc_container_padding_hover_controls($widget, $args)
    {

        $widget->start_controls_section(
            'tc_container_hover_section',
            [
                'label' => __('TCG Hover Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_ADVANCED,
            ]
        );

        $start = is_rtl() ? esc_html__('Right', 'themescamp-plugin') : esc_html__('Left', 'themescamp-plugin');
        $end = !is_rtl() ? esc_html__('Right', 'themescamp-plugin') : esc_html__('Left', 'themescamp-plugin');

        $widget->add_control(
            'tc_container_hover_note',
            [
                'type' => \Elementor\Controls_Manager::ALERT,
                'alert_type' => 'info',
                'heading' => esc_html__('How Container Hover Works', 'themescamp-core'),
                'content' => __('💡 These hover controls work with the "Choose Selector" option in the Background section: <br>
                     Perfect for creating interactive card designs and complex hover animations.', 'themescamp-core'),
            ]
        );

        $widget->add_control(
            'tc_container_padding_hover_transition',
            [
                'label' => esc_html__('Transition Duration', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );

        $widget->add_control(
            'tc_container_padding_hover_heading',
            [
                'label' => esc_html__('Padding (Hover)', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'none',
            ]
        );

        $widget->add_responsive_control(
            'tc_container_padding_hover',
            [
                'label' => esc_html__('Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}}:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}}.tc-parent-container-hover-active' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $widget->add_control(
            'tc_container_position_hover_heading',
            [
                'label' => esc_html__('Position (Hover)', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $widget->add_responsive_control(
            'tc_container_position_hover',
            [
                'label' => esc_html__('Position Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'relative' => esc_html__('Relative', 'element-camp'),
                    'absolute' => esc_html__('Absolute', 'element-camp'),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}:hover' => 'position: {{VALUE}};',
                    '{{WRAPPER}}.tc-parent-container-hover-active' => 'position: {{VALUE}};',
                ],
            ]
        );

        $widget->add_control(
            'tc_container_hover_orientation_h',
            [
                'label' => esc_html__('Horizontal Orientation', 'themescamp-plugin'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => $start,
                        'icon' => 'eicon-h-align-left',
                    ],
                    'end' => [
                        'title' => $end,
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'classes' => 'elementor-control-start-end',
                'render_type' => 'ui',
            ]
        );

        $widget->add_responsive_control(
            'tc_container_hover_x',
            [
                'label' => esc_html__('Offset', 'themescamp-plugin'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh', 'custom'],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}}:hover' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}}:hover' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body:not(.rtl) {{WRAPPER}}.tc-parent-container-hover-active' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}}.tc-parent-container-hover-active' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'tc_container_hover_orientation_h!' => 'end',
                ],
            ]
        );

        $widget->add_responsive_control(
            'tc_container_hover_x_end',
            [
                'label' => esc_html__('Offset', 'themescamp-plugin'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 0.1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh', 'custom'],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}}:hover' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}}:hover' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body:not(.rtl) {{WRAPPER}}.tc-parent-container-hover-active' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}}.tc-parent-container-hover-active' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'tc_container_hover_orientation_h' => 'end',
                ],
            ]
        );

        $widget->add_control(
            'tc_container_hover_orientation_v',
            [
                'label' => esc_html__('Vertical Orientation', 'themescamp-plugin'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => esc_html__('Top', 'themescamp-plugin'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'end' => [
                        'title' => esc_html__('Bottom', 'themescamp-plugin'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'render_type' => 'ui',
            ]
        );

        $widget->add_responsive_control(
            'tc_container_hover_y',
            [
                'label' => esc_html__('Offset', 'themescamp-plugin'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vh', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}}:hover' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                    '{{WRAPPER}}.tc-parent-container-hover-active' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'tc_container_hover_orientation_v!' => 'end',
                ],
            ]
        );

        $widget->add_responsive_control(
            'tc_container_hover_y_end',
            [
                'label' => esc_html__('Offset', 'themescamp-plugin'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vh', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}}:hover' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                    '{{WRAPPER}}.tc-parent-container-hover-active' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'tc_container_hover_orientation_v' => 'end',
                ],
            ]
        );

        $widget->add_control(
            'tc_container_transform_hover_heading',
            [
                'label' => esc_html__('Transform (Hover)', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $widget->add_control(
            "_transform_scale_popover_tc_hover",
            [
                'label' => esc_html__( 'Scale', 'elementor' ),
                'type' => Controls_Manager::POPOVER_TOGGLE,
            ]
        );
        $widget->start_popover();
        $widget->add_responsive_control(
            "_transform_scale_effect_tc_hover",
            [
                'label' => esc_html__( 'Scale', 'elementor' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 2,
                        'step' => 0.1,
                    ],
                ],
                'condition' => [
                    "_transform_scale_popover_tc_hover!" => '',
                ],
                'selectors' => [
                    "{{WRAPPER}}.e-con:hover" => '--e-con-transform-scale: {{SIZE}};',
                    "{{WRAPPER}}.e-con.tc-parent-container-hover-active" => '--e-con-transform-scale: {{SIZE}};',
                ],
                'frontend_available' => true,
            ]
        );
        $widget->end_popover();
        $widget->end_controls_section();
    }

    function add_container_double_background_attributes($widget)
    {
        $settings = $widget->get_settings_for_display();

        if (!empty($settings['tc_container_double_background']) && $settings['tc_container_double_background'] === 'yes') {
            $widget->add_render_attribute('_wrapper', 'class', 'tc-double-bg-container');

            // Extract first background data (main background)
            $first_bg_data = $this->extract_container_background_data($settings, 'background_');

            // Extract second background data
            $second_bg_data = $this->extract_container_background_data($settings, 'tc_container_double_background_second_');

            // Add as data attributes
            $widget->add_render_attribute('_wrapper', 'data-first-bg-full', json_encode($first_bg_data));
            $widget->add_render_attribute('_wrapper', 'data-second-bg-full', json_encode($second_bg_data));
        }
    }

// Helper method to extract background data from container settings
    private function extract_container_background_data($settings, $prefix)
    {
        $bg_data = [];
        $bg_type_key = $prefix . 'background';

        if (isset($settings[$bg_type_key])) {
            $bg_data['background'] = $settings[$bg_type_key];

            switch ($settings[$bg_type_key]) {
                case 'classic':
                    $this->extract_container_classic_bg($settings, $prefix, $bg_data);
                    break;

                case 'gradient':
                    $this->extract_container_standard_gradient($settings, $prefix, $bg_data);
                    break;

                case 'tcg_gradient':
                case 'tcg_gradient_4':
                    $this->extract_container_tcg_gradient($settings, $prefix, $bg_data);
                    break;
            }
        }

        return $bg_data;
    }

    private function extract_container_classic_bg($settings, $prefix, &$bg_data)
    {
        $color_key = $prefix . 'color';
        if (isset($settings[$color_key]) && !empty($settings[$color_key])) {
            $bg_data['background_color'] = $settings[$color_key];
        }
    }

    private function extract_container_tcg_gradient($settings, $prefix, &$bg_data)
    {
        $keys = ['tcg_gradient_angle', 'color', 'color_b', 'color_c', 'color_d', 'color_stop', 'color_b_stop', 'color_c_stop', 'color_d_stop'];

        foreach ($keys as $key) {
            $full_key = $prefix . $key;
            if (isset($settings[$full_key]) && !is_null($settings[$full_key])) {
                $bg_data[$key] = $settings[$full_key];
            }
        }

        // CRITICAL FIX: Handle default angle when not explicitly set
        if (empty($bg_data['tcg_gradient_angle'])) {
            // Try to get from various possible keys
            $possible_angle_keys = [
                $prefix . 'tcg_gradient_angle',
                'background_tcg_gradient_angle',
                'tcg_gradient_angle'
            ];

            foreach ($possible_angle_keys as $angle_key) {
                if (isset($settings[$angle_key]) && !empty($settings[$angle_key])) {
                    $bg_data['tcg_gradient_angle'] = $settings[$angle_key];
                    break;
                }
            }

            // If still no angle found, use Elementor's default value for tcg gradients
            if (empty($bg_data['tcg_gradient_angle'])) {
                $bg_data['tcg_gradient_angle'] = [
                    'unit' => 'deg',
                    'size' => 180, // Elementor's default angle
                    'sizes' => []
                ];
            }
        }

        // Handle default color stops if not set
        $default_stops = [
            'color_stop' => ['unit' => '%', 'size' => 0, 'sizes' => []],
            'color_b_stop' => ['unit' => '%', 'size' => 25, 'sizes' => []],
            'color_c_stop' => ['unit' => '%', 'size' => 50, 'sizes' => []],
            'color_d_stop' => ['unit' => '%', 'size' => 100, 'sizes' => []]
        ];

        foreach ($default_stops as $stop_key => $default_value) {
            if (empty($bg_data[$stop_key])) {
                $full_key = $prefix . $stop_key;
                if (isset($settings[$full_key])) {
                    $bg_data[$stop_key] = $settings[$full_key];
                } else {
                    // Use default stop value
                    $bg_data[$stop_key] = $default_value;
                }
            }
        }
    }

    private function extract_container_standard_gradient($settings, $prefix, &$bg_data)
    {
        // Map of expected keys for standard gradients
        $gradient_keys = [
            'gradient_angle' => 'gradient_angle',
            'gradient_color' => 'gradient_color',
            'gradient_color_b' => 'gradient_color_b',
            'gradient_color_a_stop' => 'gradient_color_a_stop',
            'gradient_color_b_stop' => 'gradient_color_b_stop'
        ];

        // Also check for alternative key names (Elementor sometimes uses different naming)
        $alternative_keys = [
            'color' => 'color',
            'color_b' => 'color_b',
            'color_stop' => 'color_stop',
            'color_b_stop' => 'color_b_stop'
        ];

        // Extract primary gradient keys
        foreach ($gradient_keys as $key => $target_key) {
            $full_key = $prefix . $key;
            if (isset($settings[$full_key]) && !is_null($settings[$full_key]) && $settings[$full_key] !== '') {
                $bg_data[$target_key] = $settings[$full_key];
            }
        }

        // Extract alternative keys if primary ones are missing
        foreach ($alternative_keys as $key => $target_key) {
            $full_key = $prefix . $key;
            if (isset($settings[$full_key]) && !is_null($settings[$full_key]) && $settings[$full_key] !== '') {
                // Only set if we don't already have the gradient_ version
                if (!isset($bg_data['gradient_' . $target_key])) {
                    $bg_data[$target_key] = $settings[$full_key];
                }
            }
        }

        // Set defaults for missing values
        if (empty($bg_data['gradient_angle'])) {
            $bg_data['gradient_angle'] = [
                'unit' => 'deg',
                'size' => 90,
                'sizes' => []
            ];
        }

        if (empty($bg_data['gradient_color_a_stop']) && empty($bg_data['color_stop'])) {
            $bg_data['gradient_color_a_stop'] = ['unit' => '%', 'size' => 0, 'sizes' => []];
        }

        if (empty($bg_data['gradient_color_b_stop']) && empty($bg_data['color_b_stop'])) {
            $bg_data['gradient_color_b_stop'] = ['unit' => '%', 'size' => 100, 'sizes' => []];
        }

    }

    public function before_container_render($widget)
    {
        $this->add_container_double_background_attributes($widget);
    }
}
