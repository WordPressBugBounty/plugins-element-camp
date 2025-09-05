<?php
namespace ElementCampPlugin\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

use Elementor\Widget_Base;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Core\Schemes\Typography;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Frontend;
use Elementor\Icons_Manager;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Image_Size;
use Elementor\Scheme_Base;
use Elementor\Group_Control_Background;


/**
 * Elementor icon widget.
 *
 * Elementor widget that displays an icon from over 600+ icons.
 *
 * @since 1.0.0
 */
class ElementCamp_Search extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve icon widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'tcgelements-search';
    }

    public function get_script_depends() {
        return ['tcgelements-search'];
    }

    /**
     * Get widget title.
     *
     * Retrieve icon widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__( 'Search', 'element-camp' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve icon widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-search tce-widget-badge';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the icon widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * @since 2.0.0
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories() {
        return [ 'elementcamp-elements' ];
    }

    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the widget belongs to.
     *
     * @since 2.1.0
     * @access public
     *
     * @return array Widget keywords.
     */
    public function get_keywords() {
        return [ 'search', 'icon', 'link' ];
    }

    /**
     * Register icon widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 3.1.0
     * @access protected
     */
    protected function register_controls() {
        $this->start_controls_section(
            'section_icon',
            [
                'label' => esc_html__( 'Icon', 'element-camp' ),
            ]
        );
        $this->add_control(
            'search_style',
            [
                'label'   => esc_html__('Search Form Style', 'element-camp'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'field' => esc_html__('Field', 'element-camp'),
                    'icon' =>  esc_html__('Icon', 'element-camp'),
                ],
                'default' => 'field',
            ]
        );
        $this->add_control(
            'selected_icon',
            [
                'label' => esc_html__( 'Icon', 'element-camp' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'fa-solid',
                ],
            ]
        );
        $this->add_control(
            'open_icon',
            [
                'label' => esc_html__( 'Open Icon', 'element-camp' ),
                'type' => Controls_Manager::ICONS,
                'condition'=>['search_style'=>'icon'],
                'fa4compatibility' => 'icon',
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'fa-solid',
                ],
            ]
        );
        $this->add_control(
            'close_icon',
            [
                'label' => esc_html__( 'Close Icon', 'element-camp' ),
                'type' => Controls_Manager::ICONS,
                'condition'=>['search_style'=>'icon'],
                'fa4compatibility' => 'icon',
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_control(
            'place_holder_text',
            [
                'label' => esc_html__('Place Holder Text', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Search', 'element-camp'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'field_style',
            [
                'label' => esc_html__( 'Field Setting','element-camp' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'field_heading',
            [
                'label' => esc_html__('Field', 'element-camp'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'field_text',
            [
                'label' => esc_html__( 'Text Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-field .searchform input' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-search-icon input' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'input_text_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_ACCENT,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-search-field .searchform input,{{WRAPPER}} .tcgelements-search-icon input',
            ]
        );

        $this->add_control(
            'field_text_placeholder',
            [
                'label' => esc_html__( 'Placeholder Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-field .searchform input::placeholder' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-search-icon input::placeholder' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'field_bg',
            [
                'label' => esc_html__( 'Solid Background Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-field input' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-search-icon input' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'field_bg_advanced',
                'selector' => '{{WRAPPER}} .tcgelements-search-field input,{{WRAPPER}} .tcgelements-search-icon input',
                'types' => ['gradient', 'tcg_gradient'],
                'fields_options' => [
                    'background' => [
                        'label' => esc_html_x('Gradient Background', 'Background Control', 'themescamp-plugin'),
                    ]
                ]
            ]
        );

        $this->add_responsive_control(
            'field_padding',
            [
                'label' => esc_html__('Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'rem', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-field input' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-search-icon input' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'field_border',
                'label' => esc_html__( 'Border', 'element-camp' ),
                'selector' => '{{WRAPPER}} .tcgelements-search-field input ,{{WRAPPER}} .tcgelements-search-icon input ',
            ]
        );

        $this->add_control(
            'field_focus_border_color',
            [
                'label' => esc_html__( 'Focus Border Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-field input:focus' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-search-icon input:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'field_border_radius',
            [
                'label' => __('Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-field input' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-search-icon input' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'field_style_dark_mode',
            [
                'label' => esc_html__( 'Dark Mode', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'field_text_dark_mode',
            [
                'label' => esc_html__( 'Text Color', 'elementcamp_plg' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-search-field .searchform input' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-search-field .searchform input' => 'color: {{VALUE}};',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-search-icon .searchform input' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-search-icon .searchform input' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'field_text_placeholder_dark_mode',
            [
                'label' => esc_html__( 'Placeholder Color', 'elementcamp_plg' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-search-field .searchform input::placeholder' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-search-field .searchform input::placeholder' => 'color: {{VALUE}};',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-search-icon .searchform input::placeholder' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-search-icon .searchform input::placeholder' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'field_bg_dark_mode',
                'selector' => '{{WRAPPER}} .tcgelements-search-field input, {{WRAPPER}} .tcgelements-search-icon input',
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
        $this->end_controls_section();
        $this->start_controls_section(
            'field_button_style',
            [
                'label' => esc_html__( 'Button Setting','element-camp' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'icon_heading',
            [
                'label' => esc_html__('Icon', 'element-camp'),
                'type' => Controls_Manager::HEADING,
            ]
        );
        $this->add_control(
            'field_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-field .searchform .searchsubmit svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-search-icon .searchsubmit svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-search-field .searchform .searchsubmit' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-search-icon .searchsubmit' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'close_open_icon_color',
            [
                'label' => esc_html__( 'Close/Open Icon Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'condition' => ['search_style'=>'icon'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-icon .search-icon svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-search-icon .search-icon i' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__( 'Icon size', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-field .searchform .searchsubmit svg' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-search-icon .searchsubmit svg' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-search-field .searchform .searchsubmit i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-search-icon .searchsubmit i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'open_close_icon_size',
            [
                'label' => esc_html__( 'Open/Close Icon size', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'condition'=> ['search_style'=>'icon'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-icon .search-icon svg' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-search-icon .search-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'search_icon_padding',
            [
                'label' => esc_html__('Search Icon Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'rem', 'em', '%', 'custom'],
                'condition'=> ['search_style'=>'icon'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-icon .search-icon' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'button_advanced_options_heading',
            [
                'label' => esc_html__( 'Button Advanced Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'button_background',
                'label' => esc_html__('Background', 'element-camp'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-search-field .searchsubmit,{{WRAPPER}} .tcgelements-search-icon .searchsubmit',
            ]
        );
        $this->add_responsive_control(
            'button_width',
            [
                'label' => esc_html__( 'Width', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'size_units' => [ '%', 'px', 'vw','custom' ],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 150,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 150,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-field .searchsubmit' => 'width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-search-icon .searchsubmit' => 'width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_height',
            [
                'label' => esc_html__( 'Height', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
                'size_units' => [ 'px', 'vh', '%','custom' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 500,
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-field .searchsubmit' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-search-icon .searchsubmit' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'button_positioning',
            [
                'label' => esc_html__('Submit Button Position', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'unset' => esc_html__('unset', 'element-camp'),
                    'absolute' => esc_html__('absolute', 'element-camp'),
                    'relative' => esc_html__('relative', 'element-camp'),
                ],
                'label_block' => true,
                'default' => 'absolute',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-field .searchsubmit' => 'position: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-search-icon .searchsubmit' => 'position: {{VALUE}};',
                ],
            ]
        );

        $start = is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');
        $end = !is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');

        $this->add_control(
            'button_offset_orientation_h',
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
                'render_type' => 'ui',
                'condition' => [
                    'button_positioning' => 'absolute',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_offset_x',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-search-field .searchsubmit' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-search-field .searchsubmit' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-search-icon .searchsubmit' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-search-icon .searchsubmit' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'button_offset_orientation_h!' => 'end',
                    'button_positioning' => 'absolute',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_offset_x_end',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-search-field .searchsubmit' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-search-field .searchsubmit' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-search-icon .searchsubmit' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-search-icon .searchsubmit' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'button_offset_orientation_h' => 'end',
                    'button_positioning' => 'absolute',
                ],
            ]
        );

        $this->add_control(
            'button_offset_orientation_v',
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
                    'button_positioning' => 'absolute',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_offset_y',
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
                    '{{WRAPPER}} .tcgelements-search-field .searchsubmit' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                    '{{WRAPPER}} .tcgelements-search-icon .searchsubmit' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'button_offset_orientation_v!' => 'end',
                    'button_positioning' => 'absolute',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-search-field .searchsubmit' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                    '{{WRAPPER}} .tcgelements-search-icon .searchsubmit' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'button_offset_orientation_v' => 'end',
                    'button_positioning' => 'absolute',
                ],
            ]
        );
        $this->add_control(
            'button_translate_y',
            [
                'label' => esc_html__( 'Translate Y', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'default' => [
                    'size' => -50,
                    'unit' => '%',
                ],
            ]
        );
        $this->add_control(
            'button_translate_x',
            [
                'label' => esc_html__( 'Translate X', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'default' => [
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-field .searchsubmit' => 'transform: translate({{button_translate_x.SIZE}}{{button_translate_x.UNIT}},{{button_translate_y.SIZE}}{{button_translate_y.UNIT}})',
                ],
            ]
        );
        $this->end_controls_section();

    }

    /**
     * Render icon widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();?>
        <?php if($settings['search_style'] == 'field'): ?>
            <div class="tcgelements-search-field">
                <?php $elementcamp_unique_id = uniqid( 'search-form-' ); ?>
                <form role="search" method="get" id="<?php echo esc_attr( $elementcamp_unique_id ); ?>" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <input type="search" placeholder="<?php echo esc_attr__($settings['place_holder_text'],'tcgelements'); ?>" value="<?php get_search_query()?>" name="s">
                        <button type="submit" class="searchsubmit right">
                            <?php \Elementor\Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true', 'class' => 'features-icon' ] ); ?>
                        </button>
                </form>
            </div>
        <?php else:?>
            <div class="tcgelements-search-form">
                <div class="tcgelements-search-icon">
                    <div class="form-group">
                        <?php $elementcamp_unique_id = uniqid( 'search-form-' ); ?>
                        <form role="search" method="get" id="<?php echo esc_attr( $elementcamp_unique_id ); ?>" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                            <input type="text" name="s" placeholder="<?php echo esc_attr__($settings['place_holder_text'],'tcgelements'); ?>" value="<?php get_search_query()?>">
                            <button type="submit" class="searchsubmit">
                                <?php \Elementor\Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true', 'class' => 'features-icon' ] ); ?>
                            </button>
                        </form>
                    </div>
                    <div class="search-icon">
                        <span class="open-search">
                            <?php \Elementor\Icons_Manager::render_icon( $settings['open_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                        </span>
                        <span class="close-search">
                            <?php \Elementor\Icons_Manager::render_icon( $settings['close_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                        </span>
                    </div>
                </div>
            </div>
        <?php endif;?>
        <?php
    }
}
