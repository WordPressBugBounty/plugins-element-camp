<?php

namespace ElementCampPlugin\Elementor;

use Elementor\Group_Control_Background;
use Elementor\Controls_Manager;
use Elementor\Plugin;

defined('ABSPATH') || exit(); // Exit if accessed directly

/**
 *  Elementor extra features
 */

class TCG_Pro_Elements_Extender
{

    public function __construct()
    {

        // add elements 3 gradient colors background
        add_action('elementor/element/common/_section_background/before_section_end', function ($stack) {

            $section_bg = Plugin::instance()->controls_manager->get_control_from_stack($stack->get_unique_name(), '_background_background');
            $section_bg['options']['tcg_gradient'] = [
                'title' => esc_html__('3 Colors Gradient', 'elementor'),
                'icon' => 'eicon-barcode',
            ];
            $stack->update_control('_background_background', $section_bg);
        }, 10, 3);

        // elements controls
        add_action('elementor/element/before_section_end', [$this, 'register_tc_element_background_controls'], 10, 3);
        add_action('elementor/element/before_section_end', [$this, 'register_tc_element_border_controls'], 10, 3);
        add_action('elementor/element/after_section_end', [$this, 'register_tc_dark_mode_responsive_controls'], 10, 3);
        add_action('elementor/element/after_section_end', [$this, 'register_tc_smooth_scroll_animations_controls'], 10, 3);
        add_action('elementor/element/after_section_end', [$this, 'register_tc_css_animations_controls'], 10, 3);
    }

    function register_tc_element_background_controls($widget, $widget_id, $args)
    {
        static $widgets = [
            '_section_background', /* Section */
        ];

        if (!in_array($widget_id, $widgets)) {
            return;
        }

        $widget->add_control(
            'tc_element_background_divider_dark_mode',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $widget->add_control(
            'tc_element_background_title_dark_mode',
            [
                'label' => esc_html__('TCG Dark Mode Background', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'none',
            ]
        );

        $widget->start_controls_tabs('_tabs_tc_element_background_dark_mode');

        $widget->start_controls_tab(
            '_tab_tc_element_background_normal_dark_mode',
            [
                'label' => esc_html__('Normal', 'element-camp'),
            ]
        );

        $widget->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'tc_element_background_dark_mode',
                'selector' => '{{WRAPPER}} > .elementor-widget-container',
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
            '_tab_tc_element_background_hover_dark_mode',
            [
                'label' => esc_html__('Hover', 'element-camp'),
            ]
        );

        $widget->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'tc_element_background_hover_dark_mode',
                'selector' => '{{WRAPPER}} > .elementor-widget-container:hover',
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

        $widget->add_control(
            'tc_element_background_blur_divider',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $widget->add_control(
            'tc_element_background_blur',
            [
                'label' => esc_html__('TCG Background Blur', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 250,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} > .elementor-widget-container' => 'backdrop-filter: blur({{SIZE}}px);',
                ],
            ]
        );
    }

    function register_tc_element_border_controls($widget, $widget_id, $args)
    {
        static $widgets = [
            '_section_border', /* Section */
        ];

        if (!in_array($widget_id, $widgets)) {
            return;
        }

        $widget->add_control(
            'tc_element_border_divider_dark_mode',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $widget->add_control(
            'tc_element_border_title_dark_mode',
            [
                'label' => esc_html__('TCG Dark Mode Border', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'none',
            ]
        );

        $widget->add_control(
            'tc_element_border_color_dark_mode',
            [
                'label' => esc_html__('Border Color (Dark Mode)', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} > .elementor-widget-container' => 'border-color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} > .elementor-widget-container' => 'border-color: {{VALUE}};',
                ],
            ]
        );
    }

    function register_tc_smooth_scroll_animations_controls($widget, $widget_id, $args)
    {

        static $widgets = [
            'section_effects', /* Section */
        ];

        if (!in_array($widget_id, $widgets)) {
            return;
        }

        $widget->start_controls_section(
            'tc_smooth_scroll_effects_section',
            [
                'label' => __('TCG Smooth Scroll Effects', 'element-camp'),
                'tab' => Controls_Manager::TAB_ADVANCED,
            ]
        );

        $widget->add_control(
            'tc_smooth_scroll_effects',
            [
                'label' => __('Smooth Scroll Effect', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'element-camp'),
                    'parallax' => esc_html__('Parallax', 'element-camp'),
                    'pin-spacer' => esc_html__('Pin Spacer', 'element-camp'),
                ],
                'default' => 'none',
                'render_type' => 'ui',
                'frontend_available' => true,
            ]
        );

        $widget->add_control(
            'tc_smooth_scroll_parallax_speed',
            [
                'label' => __('Speed', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'default' => 1.2,
                'min' => 0,
                'max' => 10,
                'step' => 0.1,
                'condition' => [
                    'tc_smooth_scroll_effects' => 'parallax',
                ],
                'render_type' => 'ui',
                'frontend_available' => true,
            ]
        );

        $widget->add_control(
            'tc_smooth_scroll_parallax_lag',
            [
                'label' => __('Lag', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0,
                'min' => 0,
                'max' => 10,
                'step' => 0.1,
                'condition' => [
                    'tc_smooth_scroll_effects' => 'parallax',
                ],
                'render_type' => 'ui',
                'frontend_available' => true,
            ]
        );

        $widget->add_control(
            'tc_pin_spacer_type',
            [
                'label' => __('Pin Spacer Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'section-pin-spacer' => esc_html__('Section Pin Spacer', 'element-camp'),
                    'cards-pin-spacer' => esc_html__('Cards Pin Spacer (Stack Effect)', 'element-camp'),
                ],
                'default' => 'section-pin-spacer',
                'render_type' => 'ui',
                'frontend_available' => true,
                'condition' => [
                    'tc_smooth_scroll_effects' => 'pin-spacer',
                ],
            ]
        );

        $widget->add_control(
            'tc_cards_pin_spacer_item_selector',
            [
                'label' => esc_html__('Card Item Selector', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'default' => '.pin-card',
                'placeholder' => '.pin-card, .card-item',
                'description' => esc_html__('CSS selector for individual card items to stack', 'element-camp'),
                'condition' => [
                    'tc_smooth_scroll_effects' => 'pin-spacer',
                    'tc_pin_spacer_type' => 'cards-pin-spacer',
                ],
                'render_type' => 'ui',
                'frontend_available' => true,
            ]
        );

        $widget->add_control(
            'tc_cards_pin_spacer_start',
            [
                'label' => esc_html__('Start Position', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'default' => 'top top',
                'placeholder' => 'top top, top bottom, top center',
                'description' => esc_html__('When to start pinning (e.g., "top top", "top bottom")', 'element-camp'),
                'condition' => [
                    'tc_smooth_scroll_effects' => 'pin-spacer',
                    'tc_pin_spacer_type' => 'cards-pin-spacer',
                ],
                'render_type' => 'ui',
                'frontend_available' => true,
            ]
        );

        $widget->add_control(
            'tc_cards_pin_spacer_base_scale',
            [
                'label' => esc_html__('Base Scale', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0.5,
                        'max' => 1,
                        'step' => 0.01,
                    ],
                ],
                'default' => [
                    'size' => 0.85,
                ],
                'description' => esc_html__('Starting scale for cards (e.g., 0.85)', 'element-camp'),
                'condition' => [
                    'tc_smooth_scroll_effects' => 'pin-spacer',
                    'tc_pin_spacer_type' => 'cards-pin-spacer',
                ],
                'render_type' => 'ui',
                'frontend_available' => true,
            ]
        );

        $widget->add_control(
            'tc_cards_pin_spacer_scale_increment',
            [
                'label' => esc_html__('Scale Increment', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 0.1,
                        'step' => 0.01,
                    ],
                ],
                'default' => [
                    'size' => 0.02,
                ],
                'description' => esc_html__('Scale increase per card (e.g., 0.02)', 'element-camp'),
                'condition' => [
                    'tc_smooth_scroll_effects' => 'pin-spacer',
                    'tc_pin_spacer_type' => 'cards-pin-spacer',
                ],
                'render_type' => 'ui',
                'frontend_available' => true,
            ]
        );

        $widget->add_control(
            'tc_cards_pin_spacer_end_offset',
            [
                'label' => esc_html__('End Offset (px)', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'default' => 500,
                'description' => esc_html__('Offset for end trigger (e.g., 500)', 'element-camp'),
                'condition' => [
                    'tc_smooth_scroll_effects' => 'pin-spacer',
                    'tc_pin_spacer_type' => 'cards-pin-spacer',
                ],
                'render_type' => 'ui',
                'frontend_available' => true,
            ]
        );

        $widget->add_control(
            'pin_spacer_start',
            [
                'label' => esc_html__('Start : ', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'default' => 'bottom bottom',
                'condition' => [
                    'tc_smooth_scroll_effects' => 'pin-spacer',
                    'tc_pin_spacer_type' => 'section-pin-spacer',
                ],
                'render_type' => 'ui',
                'frontend_available' => true,
            ]
        );
        $widget->add_control(
            'pin_spacer_end',
            [
                'label' => esc_html__('End : ', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'default' => 'bottom top',
                'condition' => [
                    'tc_smooth_scroll_effects' => 'pin-spacer',
                    'tc_pin_spacer_type' => 'section-pin-spacer',
                ],
                'render_type' => 'ui',
                'frontend_available' => true,
            ]
        );

        $widget->add_control(
            'tc_pin_spacer_custom_trigger',
            [
                'label' => esc_html__('Custom Trigger Selector (Optional)', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => '.my-trigger-element',
                'description' => esc_html__('Leave empty to use current element as trigger. Enter CSS selector to use custom trigger element.', 'element-camp'),
                'condition' => [
                    'tc_smooth_scroll_effects' => 'pin-spacer',
                    'tc_pin_spacer_type' => 'section-pin-spacer',
                ],
                'render_type' => 'ui',
                'frontend_available' => true,
            ]
        );

        $widget->add_control(
            'tc_pin_spacer_enable_timeline',
            [
                'label' => esc_html__('Enable Timeline Animation', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'tc_smooth_scroll_effects' => 'pin-spacer',
                    'tc_pin_spacer_type' => 'section-pin-spacer',
                ],
                'render_type' => 'ui',
                'frontend_available' => true,
            ]
        );

        $widget->add_control(
            'tc_pin_spacer_animation_target',
            [
                'label' => esc_html__('Animation Target Selector', 'element-camp'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '.elementor-heading-title',
                'placeholder' => '.your-element-class',
                'description' => esc_html__('CSS selector for the element to animate (e.g., .elementor-heading-title, .sec-head-crev)', 'element-camp'),
                'condition' => [
                    'tc_smooth_scroll_effects' => 'pin-spacer',
                    'tc_pin_spacer_type' => 'section-pin-spacer',
                    'tc_pin_spacer_enable_timeline' => 'yes',
                ],
                'render_type' => 'ui',
                'frontend_available' => true,
            ]
        );

        $widget->add_control(
            'tc_pin_spacer_scrub',
            [
                'label' => esc_html__('Enable Scrub Animation', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => esc_html__('Sync animation with scroll position', 'element-camp'),
                'condition' => [
                    'tc_smooth_scroll_effects' => 'pin-spacer',
                    'tc_pin_spacer_type' => 'section-pin-spacer',
                    'tc_pin_spacer_enable_timeline' => 'yes',
                ],
                'render_type' => 'ui',
                'frontend_available' => true,
            ]
        );

        $widget->add_control(
            'tc_pin_spacer_animation_heading',
            [
                'label' => esc_html__('Animation Properties', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'tc_smooth_scroll_effects' => 'pin-spacer',
                    'tc_pin_spacer_type' => 'section-pin-spacer',
                    'tc_pin_spacer_enable_timeline' => 'yes',
                ],
            ]
        );

        $widget->add_control(
            'tc_pin_spacer_scale_value',
            [
                'label' => esc_html__('Scale Value', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0.1,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 1.3,
                ],
                'condition' => [
                    'tc_smooth_scroll_effects' => 'pin-spacer',
                    'tc_pin_spacer_type' => 'section-pin-spacer',
                    'tc_pin_spacer_enable_timeline' => 'yes',
                ],
                'render_type' => 'ui',
                'frontend_available' => true,
            ]
        );

        $widget->add_control(
            'tc_pin_spacer_opacity_value',
            [
                'label' => esc_html__('Opacity Value', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 0,
                ],
                'condition' => [
                    'tc_smooth_scroll_effects' => 'pin-spacer',
                    'tc_pin_spacer_type' => 'section-pin-spacer',
                    'tc_pin_spacer_enable_timeline' => 'yes',
                ],
                'render_type' => 'ui',
                'frontend_available' => true,
            ]
        );

        $widget->add_control(
            'tc_pin_spacer_animation_ease',
            [
                'label' => esc_html__('Animation Ease', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'Linear.easeNone' => esc_html__('Linear', 'element-camp'),
                    'Power1.easeOut' => esc_html__('Power1 Out', 'element-camp'),
                    'Power2.easeOut' => esc_html__('Power2 Out', 'element-camp'),
                    'Power3.easeOut' => esc_html__('Power3 Out', 'element-camp'),
                    'Power1.easeIn' => esc_html__('Power1 In', 'element-camp'),
                    'Power2.easeIn' => esc_html__('Power2 In', 'element-camp'),
                    'Power3.easeIn' => esc_html__('Power3 In', 'element-camp'),
                    'Power1.easeInOut' => esc_html__('Power1 InOut', 'element-camp'),
                    'Power2.easeInOut' => esc_html__('Power2 InOut', 'element-camp'),
                    'Power3.easeInOut' => esc_html__('Power3 InOut', 'element-camp'),
                    'Back.easeOut' => esc_html__('Back Out', 'element-camp'),
                    'Back.easeIn' => esc_html__('Back In', 'element-camp'),
                    'Back.easeInOut' => esc_html__('Back InOut', 'element-camp'),
                    'Elastic.easeOut' => esc_html__('Elastic Out', 'element-camp'),
                    'Bounce.easeOut' => esc_html__('Bounce Out', 'element-camp'),
                ],
                'default' => 'Linear.easeNone',
                'condition' => [
                    'tc_smooth_scroll_effects' => 'pin-spacer',
                    'tc_pin_spacer_type' => 'section-pin-spacer',
                    'tc_pin_spacer_enable_timeline' => 'yes',
                ],
                'render_type' => 'ui',
                'frontend_available' => true,
            ]
        );

        $widget->end_controls_section();
    }
    function register_tc_css_animations_controls($widget, $widget_id, $args)
    {
        static $widgets = [
            'section_effects', /* Section */
        ];

        if (!in_array($widget_id, $widgets)) {
            return;
        }

        $widget->start_controls_section(
            'tc_css_animations_section',
            [
                'label' => __('TCG CSS Animations', 'element-camp'),
                'tab' => Controls_Manager::TAB_ADVANCED,
            ]
        );

        $widget->add_control(
            'tc_css_effects',
            [
                'label' => __('CSS Animation Effect', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'element-camp'),
                    'up-down' => esc_html__('Up Down', 'element-camp'),
                    'pulse' => esc_html__('Scale Pulse', 'element-camp'),
                    'pulse2' => esc_html__('Pulse', 'element-camp'),
                ],
                'default' => 'none',
                'render_type' => 'ui',
                'frontend_available' => true,
                'prefix_class' => 'tcg-animation-',
            ]
        );

        $widget->add_control(
            'tc_css_animation_up_down_range',
            [
                'label' => __('Up Down Animation Range', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'min' => -100,
                'max' => 100,
                'step' => 1,
                'default' => -30,
                'condition' => [
                    'tc_css_effects' => 'up-down',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--tcg-up-down-range: {{VALUE}}px;',
                ],
            ]
        );

        $widget->add_control(
            'tc_css_animation_pulse_opacity',
            [
                'label' => __('Pulse Opacity', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0.01,
                'max' => 1,
                'step' => 0.01,
                'condition' => [
                    'tc_css_effects' => 'pulse2',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--tcg-pulse-opacity: {{VALUE}};',
                ],
            ]
        );

        $widget->add_control(
            'tc_css_animation_duration',
            [
                'label' => __('Animation Duration (s)', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0.5,
                'max' => 20,
                'step' => 0.1,
                'condition' => [
                    'tc_css_effects!' => 'none',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'animation-duration: {{VALUE}}s;',
                ],
            ]
        );

        $widget->add_control(
            'tc_css_animation_delay',
            [
                'label' => __('Animation Delay (s)', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 20,
                'step' => 0.1,
                'condition' => [
                    'tc_css_effects!' => 'none',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'animation-delay: {{VALUE}}s;',
                ],
            ]
        );

        $widget->end_controls_section();
    }

    function register_tc_dark_mode_responsive_controls($widget, $widget_id, $args)
    {
        static $widgets = [
            '_section_responsive', /* Section */
        ];

        if (!in_array($widget_id, $widgets)) {
            return;
        }

        $widget->start_controls_section(
            'tc_dark_mode_responsive_section',
            [
                'label' => __('TCG Dark Mode Responsive', 'element-camp'),
                'tab' => Controls_Manager::TAB_ADVANCED,
            ]
        );

        $widget->add_control(
            'tc_dark_mode_responsive_hide_in_dark',
            [
                'label' => esc_html__('Hide in Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'element-camp'),
                'label_off' => esc_html__('Hide', 'element-camp'),
                'return_value' => 'yes',
                'default' => 'no',
                'frontend_available' => true,
            ]
        );

        $widget->add_control(
            'tc_dark_mode_responsive_hide_in_light',
            [
                'label' => esc_html__('Hide in Light Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'element-camp'),
                'label_off' => esc_html__('Hide', 'element-camp'),
                'return_value' => 'yes',
                'default' => 'no',
                'frontend_available' => true,
            ]
        );

        $widget->end_controls_section();
    }
}
