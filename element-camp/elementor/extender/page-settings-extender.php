<?php

namespace ElementCampPlugin\Elementor;

use Elementor\Controls_Manager;
use Elementor\Plugin;

defined('ABSPATH') || exit(); // Exit if accessed directly

/**
 * Page Settings extension for Elementor
 */
class TCG_Pro_Page_Settings_Extender
{
    public function __construct()
    {
        // Hook into Elementor's page settings system
        add_action('elementor/documents/register_controls', [$this, 'register_page_settings_controls']);

    }

    /**
     * Register custom controls to the page settings
     *
     * @param \Elementor\Core\DocumentTypes\Page $document The Page document
     */
    public function register_page_settings_controls($document)
    {
        if (!method_exists($document, 'get_name') || !in_array($document->get_name(), ['post', 'page', 'wp-page', 'wp-post'])) {
            return;
        }

        // Add custom controls to Style tab
        $this->add_custom_style_controls($document);
    }

    /**
     * Add custom controls to the Style tab
     */
    private function add_custom_style_controls($document)
    {
        $document->start_controls_section(
            'tcg_page_style_section',
            [
                'label' => esc_html__('TCG Page Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $document->add_control(
            'tcg_html_height_auto',
            [
                'label' => esc_html__('HTML Height Auto', 'element-camp'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'description' => esc_html__('Set HTML Height To Auto', 'element-camp'),
                'separator' => 'before',
                'default' => false,
                'selectors' => [
                    'html' => 'height: auto !important;',
                ],
            ]
        );

        $document->add_responsive_control(
            'tcg_html_overflow',
            [
                'label' => esc_html__('HTML Overflow', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'hidden'  => esc_html__('Hidden', 'element-camp'),
                    'auto'  => esc_html__('Auto', 'element-camp'),
                ],
                'selectors' => [
                    'html' => 'overflow: {{VALUE}};',
                ],
            ]
        );

        $document->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'page_background_dark_mode',
                'label' => esc_html_x('Page Dark Mode Background', 'Background Control', 'element-camp'),
                'types' => ['classic', 'gradient'],
                'fields_options' => [
                    'background' => [
                        'label' => esc_html_x('Page Dark Mode Background', 'Background Control', 'element-camp'),
                    ],
                    'color' => [
                        'selectors' => [
                            'body.tcg-dark-mode.elementor-page' => 'background-color: {{VALUE}};',
                            'body.tcg-dark-mode .elementor-motion-effects-layer' => 'background-color: {{VALUE}};',
                            '@media (prefers-color-scheme: dark) { body.tcg-auto-mode.elementor-page' => 'background-color: {{VALUE}}; }',
                            '@media (prefers-color-scheme: dark) { body.tcg-auto-mode .elementor-motion-effects-layer' => 'background-color: {{VALUE}}; }',
                        ],
                    ],
                    'gradient_angle' => [
                        'selectors' => [
                            'body.tcg-dark-mode.elementor-page' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}});',
                            'body.tcg-dark-mode .elementor-motion-effects-layer' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}});',
                            '@media (prefers-color-scheme: dark) { body.tcg-auto-mode.elementor-page' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}}); }',
                            '@media (prefers-color-scheme: dark) { body.tcg-auto-mode .elementor-motion-effects-layer' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}}); }',
                        ],
                    ],
                    'gradient_position' => [
                        'selectors' => [
                            'body.tcg-dark-mode' => 'background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}});',
                            '@media (prefers-color-scheme: dark) { body.tcg-auto-mode' => 'background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}}); }',
                        ],
                    ],
                    'image' => [
                        'selectors' => [
                            'body.tcg-dark-mode.elementor-page' => 'background-image: url("{{URL}}");',
                            'body.tcg-dark-mode .elementor-motion-effects-layer' => 'background-image: url("{{URL}}");',
                            '@media (prefers-color-scheme: dark) { body.tcg-auto-mode.elementor-page' => 'background-image: url("{{URL}}"); }',
                            '@media (prefers-color-scheme: dark) { body.tcg-auto-mode .elementor-motion-effects-layer' => 'background-image: url("{{URL}}"); }',
                        ],
                    ],
                    'position' => [
                        'selectors' => [
                            'body.tcg-dark-mode' => 'background-position: {{VALUE}};',
                            '@media (prefers-color-scheme: dark) { body.tcg-auto-mode' => 'background-position: {{VALUE}}; }',
                        ],
                    ],
                    'xpos' => [
                        'selectors' => [
                            'body.tcg-dark-mode' => 'background-position: {{SIZE}}{{UNIT}} {{ypos.SIZE}}{{ypos.UNIT}};',
                            '@media (prefers-color-scheme: dark) { body.tcg-auto-mode' => 'background-position: {{SIZE}}{{UNIT}} {{ypos.SIZE}}{{ypos.UNIT}}; }',
                        ],
                    ],
                    'ypos' => [
                        'selectors' => [
                            'body.tcg-dark-mode' => 'background-position: {{xpos.SIZE}}{{xpos.UNIT}} {{SIZE}}{{UNIT}};',
                            '@media (prefers-color-scheme: dark) { body.tcg-auto-mode' => 'background-position: {{xpos.SIZE}}{{xpos.UNIT}} {{SIZE}}{{UNIT}}; }',
                        ],
                    ],
                    'attachment' => [
                        'selectors' => [
                            '(desktop+)body.tcg-dark-mode' => 'background-attachment: {{VALUE}};',
                            '@media (prefers-color-scheme: dark) { (desktop+)body.tcg-auto-mode' => 'background-attachment: {{VALUE}}; }',
                        ],
                    ],
                    'repeat' => [
                        'selectors' => [
                            'body.tcg-dark-mode' => 'background-repeat: {{VALUE}};',
                            '@media (prefers-color-scheme: dark) { body.tcg-auto-mode' => 'background-repeat: {{VALUE}}; }',
                        ],
                    ],
                    'size' => [
                        'selectors' => [
                            'body.tcg-dark-mode' => 'background-size: {{VALUE}};',
                            '@media (prefers-color-scheme: dark) { body.tcg-auto-mode' => 'background-size: {{VALUE}}; }',
                        ],
                    ],
                    'bg_width' => [
                        'selectors' => [
                            'body.tcg-dark-mode' => 'background-size: {{SIZE}}{{UNIT}} auto;',
                            '@media (prefers-color-scheme: dark) { body.tcg-auto-mode' => 'background-size: {{SIZE}}{{UNIT}} auto; }',
                        ],
                    ],
                    'video_fallback' => [
                        'selectors' => [
                            'body.tcg-dark-mode' => 'background: url("{{URL}}") 50% 50%; background-size: cover;',
                            '@media (prefers-color-scheme: dark) { body.tcg-auto-mode' => 'background: url("{{URL}}") 50% 50%; background-size: cover; }',
                        ],
                    ],
                ]
            ]
        );

        $document->end_controls_section();
    }

}