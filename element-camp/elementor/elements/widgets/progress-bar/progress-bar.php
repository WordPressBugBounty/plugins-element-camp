<?php

namespace ElementCampPlugin\Widgets;

use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Frontend;
use Elementor\Icons_Manager;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Image_Size;
use Elementor\Scheme_Base;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Elementor progress widget.
 *
 * Elementor widget that displays an escalating progress bar.
 *
 * @since 1.0.0
 */
class ElementCamp_Progress_Bar extends Widget_Base
{

    public function get_name()
    {
        return 'tcgelements-progress-bar';
    }

    public function get_title()
    {
        return esc_html__('Progress Bar', 'elementcamp_plg');
    }

    public function get_icon()
    {
        return 'eicon-skill-bar tce-widget-badge';
    }

    public function get_categories()
    {
        return ['elementcamp-elements'];
    }

    public function get_keywords()
    {
        return ['progress', 'bar'];
    }

    public function get_script_depends(): array
    {
        return ['tcgelements-progress-bar'];
    }

    /**
     * Register progress widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 3.1.0
     * @access protected
     */
    protected function register_controls()
    {

        $start = is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');
        $end = !is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');

        $this->start_controls_section(
            'section_progress',
            [
                'label' => esc_html__('Progress Bar', 'elementor'),
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'elementor'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__('Enter your title', 'elementor'),
                'default' => esc_html__('My Skill', 'elementor'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => esc_html__('Title HTML Tag', 'elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ],
                'default' => 'span',
                'condition' => [
                    'title!' => '',
                ],
            ]
        );

        $this->add_control(
            'progress_type',
            [
                'label' => esc_html__('Type', 'elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'elementor'),
                    'info' => esc_html__('Info', 'elementor'),
                    'success' => esc_html__('Success', 'elementor'),
                    'warning' => esc_html__('Warning', 'elementor'),
                    'danger' => esc_html__('Danger', 'elementor'),
                ],
                'default' => '',
                'condition' => [
                    'progress_type!' => '', // a workaround to hide the control, unless it's in use (not default).
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'percent',
            [
                'label' => esc_html__('Percentage', 'elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 50,
                    'unit' => '%',
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'display_percentage',
            [
                'label' => esc_html__('Display Percentage', 'elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'elementor'),
                'label_off' => esc_html__('Hide', 'elementor'),
                'return_value' => 'show',
                'default' => 'show',
            ]
        );

        $this->add_control(
            'display_percentage_position',
            [
                'label' => esc_html__('Percentage Position', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'inside' => esc_html__('Inside', 'element-camp'),
                    'outside' => esc_html__('Outside', 'element-camp'),
                ],
                'default' => 'inside',
                'condition' => [
                    'display_percentage' => 'show',
                ],
            ]
        );

        $this->add_control(
            'inner_text',
            [
                'label' => esc_html__('Inner Text', 'elementor'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__('e.g. Web Designer', 'elementor'),
                'default' => esc_html__('Web Designer', 'elementor'),
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_progress_style',
            [
                'label' => esc_html__('Progress Bar', 'elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'progress_container_overflow',
            [
                'label' => esc_html__('Progress Container Overflow', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'hidden' => esc_html__('Hidden', 'element-camp'),
                    'visible' => esc_html__('Visible', 'element-camp'),
                ],
                'label_block' => true,
                'default' => 'hidden',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-progress-wrapper' => 'overflow: {{VALUE}};',
                ],

            ]
        );

        $this->add_control(
            'bar_color',
            [
                'label' => esc_html__('Color', 'elementor'),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-progress-wrapper .tcgelements-progress-bar' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'bar_color_gradient',
                'selector' => '{{WRAPPER}} .tcgelements-progress-wrapper .tcgelements-progress-bar',
                'types' => ['gradient', 'tcg_gradient','tcg_gradient_4'],
                'fields_options' => [
                    'background' => [
                        'label' => esc_html_x('Gradient Color', 'Background Control', 'themescamp-plugin'),
                    ]
                ]
            ]
        );

        $this->add_control(
            'bar_dotted_color_switch',
            [
                'label'        => esc_html__('Dotted Color', 'element-camp'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'element-camp'),
                'label_off'    => esc_html__('Hide', 'element-camp'),
                'return_value' => 'yes',
                'default'      => false,
            ]
        );

        $this->add_control(
            'bar_dotted_color',
            [
                'label' => esc_html__('First Color', 'Background Control', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '#A9997A',
                'title' => esc_html__('Background Color', 'Background Control', 'element-camp'),
                'condition' => [
                    'bar_dotted_color_switch' => ['yes'],
                ],
            ]
        );

        $this->add_control(
            'bar_dotted_color_b',
            [
                'label' => esc_html__('Second Color', 'Background Control', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '#A9997A',
                'render_type' => 'ui',
                'condition' => [
                    'bar_dotted_color_switch' => ['yes'],
                ],
            ]
        );

        $this->add_control(
            'bar_dotted_color_b_width',
            [
                'label'   => esc_html__('Stripe Width', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', 'custom' ],
                'default' => [
                    'unit' => 'px',
                    'size' => 4,
                ],
                'render_type' => 'ui',
                'condition' => [
                    'bar_dotted_color_switch' => ['yes'],
                ],
            ]
        );

        $this->add_control(
            'bar_dotted_color_c',
            [
                'label' => esc_html__('Third Color', 'Background Control', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => 'transparent',
                'render_type' => 'ui',
                'condition' => [
                    'bar_dotted_color_switch' => ['yes'],
                ],
            ]
        );

        $this->add_control(
            'bar_dotted_color_c_width',
            [
                'label'   => esc_html__('Stripe Width', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', 'custom' ],
                'default' => [
                    'unit' => 'px',
                    'size' => 4,
                ],
                'render_type' => 'ui',
                'condition' => [
                    'bar_dotted_color_switch' => ['yes'],
                ],
            ]
        );

        $this->add_control(
            'bar_dotted_color_d',
            [
                'label' => esc_html__('Fourth Color', 'Background Control', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => 'transparent',
                'render_type' => 'ui',
                'condition' => [
                    'bar_dotted_color_switch' => ['yes'],
                ],
            ]
        );

        $this->add_control(
            'bar_dotted_color_d_width',
            [
                'label'   => esc_html__('Stripe Width', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', 'custom' ],
                'default' => [
                    'unit' => 'px',
                    'size' => 8,
                ],
                'render_type' => 'ui',
                'condition' => [
                    'bar_dotted_color_switch' => ['yes'],
                ],
            ]
        );

        $this->add_control(
            'bar_dotted_gradient_angle',
            [
                'label' => _x('Angle', 'Background Control', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['deg'],
                'default' => [
                    'unit' => 'deg',
                    'size' => 90,
                ],
                'range' => [
                    'deg' => [
                        'step' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-progress-wrapper .tcgelements-progress-bar' => 'background: repeating-linear-gradient({{SIZE}}{{UNIT}}, {{bar_dotted_color.VALUE}}, {{bar_dotted_color_b.VALUE}} {{bar_dotted_color_b_width.SIZE}}{{bar_dotted_color_b_width.UNIT}}, {{bar_dotted_color_c.VALUE}} {{bar_dotted_color_c_width.SIZE}}{{bar_dotted_color_c_width.UNIT}}, {{bar_dotted_color_d.VALUE}} {{bar_dotted_color_d_width.SIZE}}{{bar_dotted_color_d_width.UNIT}});',
                ],

                'condition' => [
                    'bar_dotted_color_switch' => ['yes'],
                ],
            ]
        );

        $this->add_control(
            'bar_bg_color',
            [
                'label' => esc_html__('Background Color', 'elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-progress-wrapper' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'progress_bar_dotted_background',
            [
                'label'        => esc_html__('Dotted Background', 'element-camp'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'element-camp'),
                'label_off'    => esc_html__('Hide', 'element-camp'),
                'return_value' => 'yes',
                'default'      => false,
            ]
        );

        $this->add_control(
            'progress_bar_dotted_color',
            [
                'label' => esc_html__('First Color', 'Background Control', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ccc5',
                'title' => esc_html__('Background Color', 'Background Control', 'element-camp'),
                'condition' => [
                    'progress_bar_dotted_background' => ['yes'],
                ],
            ]
        );

        $this->add_control(
            'progress_bar_dotted_color_b',
            [
                'label' => esc_html__('Second Color', 'Background Control', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ccc5',
                'render_type' => 'ui',
                'condition' => [
                    'progress_bar_dotted_background' => ['yes'],
                ],
            ]
        );

        $this->add_control(
            'progress_bar_dotted_color_b_width',
            [
                'label'   => esc_html__('Stripe Width', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', 'custom' ],
                'default' => [
                    'unit' => 'px',
                    'size' => 4,
                ],
                'render_type' => 'ui',
                'condition' => [
                    'progress_bar_dotted_background' => ['yes'],
                ],
            ]
        );

        $this->add_control(
            'progress_bar_dotted_color_c',
            [
                'label' => esc_html__('Third Color', 'Background Control', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => 'transparent',
                'render_type' => 'ui',
                'condition' => [
                    'progress_bar_dotted_background' => ['yes'],
                ],
            ]
        );

        $this->add_control(
            'progress_bar_dotted_color_c_width',
            [
                'label'   => esc_html__('Stripe Width', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', 'custom' ],
                'default' => [
                    'unit' => 'px',
                    'size' => 4,
                ],
                'render_type' => 'ui',
                'condition' => [
                    'progress_bar_dotted_background' => ['yes'],
                ],
            ]
        );

        $this->add_control(
            'progress_bar_dotted_color_d',
            [
                'label' => esc_html__('Fourth Color', 'Background Control', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => 'transparent',
                'render_type' => 'ui',
                'condition' => [
                    'progress_bar_dotted_background' => ['yes'],
                ],
            ]
        );

        $this->add_control(
            'progress_bar_dotted_color_d_width',
            [
                'label'   => esc_html__('Stripe Width', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', 'custom' ],
                'default' => [
                    'unit' => 'px',
                    'size' => 8,
                ],
                'render_type' => 'ui',
                'condition' => [
                    'progress_bar_dotted_background' => ['yes'],
                ],
            ]
        );

        $this->add_control(
            'progress_bar_gradient_angle',
            [
                'label' => _x('Angle', 'Background Control', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['deg'],
                'default' => [
                    'unit' => 'deg',
                    'size' => 90,
                ],
                'range' => [
                    'deg' => [
                        'step' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-progress-wrapper' => 'background: repeating-linear-gradient({{SIZE}}{{UNIT}}, {{progress_bar_dotted_color.VALUE}}, {{progress_bar_dotted_color_b.VALUE}} {{progress_bar_dotted_color_b_width.SIZE}}{{progress_bar_dotted_color_b_width.UNIT}}, {{progress_bar_dotted_color_c.VALUE}} {{progress_bar_dotted_color_c_width.SIZE}}{{progress_bar_dotted_color_c_width.UNIT}}, {{progress_bar_dotted_color_d.VALUE}} {{progress_bar_dotted_color_d_width.SIZE}}{{progress_bar_dotted_color_d_width.UNIT}});',
                ],

                'condition' => [
                    'progress_bar_dotted_background' => ['yes'],
                ],
            ]
        );

        $this->add_control(
            'bar_height',
            [
                'label' => esc_html__('Height', 'elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-progress-bar' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'bar_border_radius',
            [
                'label' => esc_html__('Border Radius', 'elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-progress-wrapper' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'bar_inline_border_radius',
            [
                'label' => esc_html__('Inline Border Radius', 'elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-progress-bar' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'bar_opacity',
            [
                'label' => esc_html__('Bar Opacity', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-progress-bar' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_control(
            'inner_text_heading',
            [
                'label' => esc_html__('Inner Text', 'elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'inner_text_position',
            [
                'label' => esc_html__('Inner Text Position', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'before' => esc_html__('Before Percentage', 'element-camp'),
                    'after' => esc_html__('After Percentage', 'element-camp'),
                ],
                'default' => 'before',
                'condition' => [
                    'display_percentage' => 'show',
                    'inner_text!' => '',
                ],
                'prefix_class' => 'tce-text-position-',
            ]
        );

        $this->add_responsive_control(
            'bar_inner_text_padding',
            [
                'label' => esc_html__('Inner Text Padding', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-progress-bar .tcgelements-progress-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'bar_inline_color',
            [
                'label' => esc_html__('Color', 'elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-progress-bar' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'bar_inner_typography',
                'selector' => '{{WRAPPER}} .tcgelements-progress-bar',
                'exclude' => [
                    'line_height',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'bar_inner_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-progress-bar',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_percentage',
            [
                'label' => esc_html__('Percentage Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'percentage_padding',
            [
                'label' => esc_html__('Padding', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-progress-bar .tcgelements-progress-percentage' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-progress-wrapper .tcgelements-progress-percentage' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'perctenage_background_color',
                'selector' => '{{WRAPPER}} .tcgelements-progress-wrapper .tcgelements-progress-percentage',
                'types' => ['classic', 'gradient'],
            ]
        );

        $this->add_control(
            'percentage_positioning',
            [
                'label' => esc_html__('Position', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'unset' => esc_html__('unset', 'element-camp'),
                    'relative' => esc_html__('relative', 'element-camp'),
                    'absolute' => esc_html__('absolute', 'element-camp'),
                ],
                'label_block' => true,
                'default' => 'unset',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-progress-bar .tcgelements-progress-percentage' => 'position: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-progress-wrapper .tcgelements-progress-percentage' => 'position: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'percentage_offset_orientation_v',
            [
                'label' => esc_html__('Vertical Orientation', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => esc_html__('Top', 'element-camp'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'end' => [
                        'title' => esc_html__('Bottom', 'element-camp'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'render_type' => 'ui',
                'condition' => [
                    'percentage_positioning!' => 'unset',
                ],
            ]
        );

        $this->add_responsive_control(
            'percentage_offset_y',
            [
                'label' => esc_html__('Offset', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-progress-bar .tcgelements-progress-percentage' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                    '{{WRAPPER}} .tcgelements-progress-wrapper .tcgelements-progress-percentage' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'percentage_positioning!' => 'unset',
                    'percentage_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'percentage_offset_y_end',
            [
                'label' => esc_html__('Offset', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-progress-bar .tcgelements-progress-percentage' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                    '{{WRAPPER}} .tcgelements-progress-wrapper .tcgelements-progress-percentage' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'percentage_positioning!' => 'unset',
                    'percentage_offset_orientation_v' => 'end',
                ],
            ]
        );

        $this->add_control(
            'percentage_offset_orientation_h',
            [
                'label' => esc_html__('Horizontal Orientation', 'element-camp'),
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
                'condition' => [
                    'percentage_positioning!' => 'unset',
                ],
                'render_type' => 'ui',
            ]
        );

        $this->add_responsive_control(
            'percentage_offset_x',
            [
                'label' => esc_html__('Offset', 'element-camp'),
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-progress-bar .tcgelements-progress-percentage' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-progress-wrapper .tcgelements-progress-percentage' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-progress-bar .tcgelements-progress-percentage' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-progress-wrapper .tcgelements-progress-percentage' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'percentage_offset_orientation_h!' => 'end',
                    'percentage_positioning!' => 'unset',
                ],
            ]
        );

        $this->add_responsive_control(
            'percentage_offset_x_end',
            [
                'label' => esc_html__('Offset', 'element-camp'),
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-progress-bar .tcgelements-progress-percentage' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-progress-wrapper .tcgelements-progress-percentage' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-progress-bar .tcgelements-progress-percentage' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-progress-wrapper .tcgelements-progress-percentage' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'percentage_offset_orientation_h' => 'end',
                    'percentage_positioning!' => 'unset',
                ],
            ]
        );

        $this->add_control(
            'percentage_container_overflow',
            [
                'label' => esc_html__('Percentage Overflow Visible', 'element-camp'),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => esc_html__('Off', 'element-camp'),
                'label_on' => esc_html__('On', 'element-camp'),
                'default' => false,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-progress-wrapper' => 'overflow: visible;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'perctenage_typography',
                'selector' => '{{WRAPPER}} .tcgelements-progress-wrapper .tcgelements-progress-percentage',
            ]
        );

        $this->add_control(
            'perctenage_color',
            [
                'label' => __('Perctenage Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-progress-wrapper .tcgelements-progress-percentage' => 'color: {{VALUE}}'
                ]
            ]
        );

        $this->add_responsive_control(
            'percentage_border_radius',
            [
                'label' => esc_html__('Percentage Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-progress-wrapper .tcgelements-progress-percentage' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_title',
            [
                'label' => esc_html__('Title Style', 'elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-progress-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Text Color', 'elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-progress-title' => 'color: {{VALUE}};',
                ],
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .tcgelements-progress-title',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'title_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-progress-title',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render progress widget output on the frontend.
     * Make sure value does no exceed 100%.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();

        if (empty($settings['title']) && empty($settings['percent']['size'])) {
            return;
        }

        $progressbar_id = 'tcgelements-progress-bar-' . $this->get_id();

        $progress_percentage = is_numeric($settings['percent']['size']) ? $settings['percent']['size'] : '0';
        if (100 < $progress_percentage) {
            $progress_percentage = 100;
        }

        if (! Utils::is_empty($settings['title'])) {
            $this->add_render_attribute(
                'title',
                [
                    'class' => 'tcgelements-progress-title',
                    'id' => $progressbar_id,
                ]
            );

            $this->add_inline_editing_attributes('title');

            $this->add_render_attribute('wrapper', 'aria-labelledby', $progressbar_id);
        }

        $this->add_render_attribute(
            'wrapper',
            [
                'class' => 'tcgelements-progress-wrapper',
                'role' => 'progressbar',
                'aria-valuemin' => '0',
                'aria-valuemax' => '100',
                'aria-valuenow' => $progress_percentage,
            ]
        );

        if (! empty($settings['inner_text'])) {
            $this->add_render_attribute('wrapper', 'aria-valuetext', "{$progress_percentage}% ({$settings['inner_text']})");
        }

        if (! empty($settings['progress_type'])) {
            $this->add_render_attribute('wrapper', 'class', 'progress-' . $settings['progress_type']);
        }

        $this->add_render_attribute(
            'progress-bar',
            [
                'class' => 'tcgelements-progress-bar',
                'data-max' => $progress_percentage . '%',
            ]
        );

        $this->add_render_attribute('inner_text', 'class', 'tcgelements-progress-text');

        $this->add_inline_editing_attributes('inner_text');

        
        if (! Utils::is_empty($settings['title'])) { ?>
            <<?php Utils::print_validated_html_tag($settings['title_tag']); ?> <?php $this->print_render_attribute_string('title'); ?>>
                <?php echo wp_kses_post($settings['title']); ?>
            </<?php Utils::print_validated_html_tag($settings['title_tag']); ?>>
        <?php } ?>

        <div <?php $this->print_render_attribute_string('wrapper'); ?>>
            <?php if ('show' === $settings['display_percentage'] && $settings['display_percentage_position'] == 'outside') { ?>
                <span class="tcgelements-progress-percentage"><?php echo $progress_percentage; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                                                                ?>%</span>
            <?php } ?>
            <div <?php $this->print_render_attribute_string('progress-bar'); ?>>
                <span <?php $this->print_render_attribute_string('inner_text'); ?>><?php echo wp_kses_post($settings['inner_text']); ?></span>
                <?php if ('show' === $settings['display_percentage'] && $settings['display_percentage_position'] == 'inside') { ?>
                    <span class="tcgelements-progress-percentage"><?php echo $progress_percentage; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                                                                    ?>%</span>
                <?php } ?>
            </div>
        </div>
<?php
    }
}
