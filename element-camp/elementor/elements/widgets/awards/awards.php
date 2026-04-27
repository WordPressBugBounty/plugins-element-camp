<?php

namespace ElementCampPlugin\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

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
use Elementor\Repeater;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Css_Filter;


class ElementCamp_Awards extends Widget_Base {

    public function get_name() {
        return 'tcgelements-awards';
    }

    public function get_title() {
        return esc_html__( 'Awards', 'element-camp' );
    }

    public function get_icon() {
        return 'eicon-posts-ticker tce-widget-badge';
    }

    public function get_categories() {
        return [ 'elementcamp-elements' ];
    }

    public function get_script_depends() {
        return [ 'tcgelements-awards' ];
    }

    protected function register_controls() {

        // ========== CONTENT SECTION ==========
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Awards List', 'element-camp' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'year',
            [
                'label'   => esc_html__( 'Year', 'element-camp' ),
                'type'    => Controls_Manager::TEXT,
                'default' => '2024',
                'dynamic' => [ 'active' => true ],
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label'   => esc_html__( 'Award Title', 'element-camp' ),
                'type'    => Controls_Manager::TEXT,
                'default' => 'Article on Medium',
                'dynamic' => [ 'active' => true ],
            ]
        );

        $repeater->add_control(
            'position',
            [
                'label'   => esc_html__( 'Position / Category', 'element-camp' ),
                'type'    => Controls_Manager::TEXT,
                'default' => 'UI/UX design',
                'dynamic' => [ 'active' => true ],
            ]
        );

        $repeater->add_control(
            'selected_icon',
            [
                'label' => esc_html__('Icon', 'element-camp'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'skin' => 'inline',
                'label_block' => false,
            ]
        );

        $repeater->add_control(
            'image',
            [
                'label'   => esc_html__( 'Hover Image', 'element-camp' ),
                'type'    => Controls_Manager::MEDIA,
                'default' => [ 'url' => Utils::get_placeholder_image_src() ],
                'dynamic' => [ 'active' => true ],
            ]
        );

        $this->add_control(
            'awards_list',
            [
                'label'       => esc_html__( 'Awards', 'element-camp' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'year'     => '2024',
                        'title'    => 'Article on Medium',
                        'position' => 'UI/UX design',
                        'image'    => [ 'url' => Utils::get_placeholder_image_src() ],
                    ],
                    [
                        'year'     => '2023',
                        'title'    => 'Awwwards Site of the Day',
                        'position' => 'Web Design',
                        'image'    => [ 'url' => Utils::get_placeholder_image_src() ],
                    ],
                ],
                'title_field' => '{{{ year }}} - {{{ title }}}',
            ]
        );

        $this->end_controls_section();

        // ========== STYLE : ITEM CONTAINER ==========
        $this->start_controls_section(
            'item_style',
            [
                'label' => esc_html__( 'Item', 'element-camp' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label'      => esc_html__( 'Padding', 'element-camp' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default'    => [ 'top' => '35', 'right' => '30', 'bottom' => '35', 'left' => '30', 'unit' => 'px', 'isLinked' => false ],
                'selectors'  => [ '{{WRAPPER}} .tcgelements-awards .item-row' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
            ]
        );

        $this->add_responsive_control(
            'item_margin',
            [
                'label'      => esc_html__( 'Margin', 'element-camp' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [ '{{WRAPPER}} .tcgelements-awards .item-row' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
            ]
        );

        $this->add_control(
            'item_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'element-camp' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default'    => [ 'top' => '100', 'right' => '100', 'bottom' => '100', 'left' => '100', 'unit' => 'px', 'isLinked' => true ],
                'selectors'  => [ '{{WRAPPER}} .tcgelements-awards .item-row' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
            ]
        );
        $this->start_controls_tabs(
            'award_tabs',
        );
        $this->start_controls_tab(
            'normal_award_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'item_border',
                'selector' => '{{WRAPPER}} .tcgelements-awards .item-row',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'item_background',
                'label'    => esc_html__( 'Background', 'element-camp' ),
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-awards .item-row',
            ]
        );
        $this->add_control(
            'item_style_dark_mode',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'item_border_dark_mode',
            [
                'label' => esc_html__('Border Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-awards .item-row' => 'border-color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-awards .item-row' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'item_background_dark_mode',
                'selector' => '{{WRAPPER}} .tcgelements-awards .item-row',
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
                    'tcg_gradient_angle' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}}{{#color_c.VALUE}}, {{color_c.VALUE}} {{color_c_stop.SIZE}}{{color_c_stop.UNIT}}{{/color_c.VALUE}}{{#color_d.VALUE}}, {{color_d.VALUE}} {{color_d_stop.SIZE}}{{color_d_stop.UNIT}}{{/color_d.VALUE}})',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}}{{#color_c.VALUE}}, {{color_c.VALUE}} {{color_c_stop.SIZE}}{{color_c_stop.UNIT}}{{/color_c.VALUE}}{{#color_d.VALUE}}, {{color_d.VALUE}} {{color_d_stop.SIZE}}{{color_d_stop.UNIT}}{{/color_d.VALUE}})',
                        ],
                    ],
                    'tcg_gradient_position' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}}{{#color_c.VALUE}}, {{color_c.VALUE}} {{color_c_stop.SIZE}}{{color_c_stop.UNIT}}{{/color_c.VALUE}}{{#color_d.VALUE}}, {{color_d.VALUE}} {{color_d_stop.SIZE}}{{color_d_stop.UNIT}}{{/color_d.VALUE}})',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}}{{#color_c.VALUE}}, {{color_c.VALUE}} {{color_c_stop.SIZE}}{{color_c_stop.UNIT}}{{/color_c.VALUE}}{{#color_d.VALUE}}, {{color_d.VALUE}} {{color_d_stop.SIZE}}{{color_d_stop.UNIT}}{{/color_d.VALUE}})',
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
        $this->end_controls_tab();
        $this->start_controls_tab(
            'hover_award_tab',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'item_border_card_hover',
                'selector' => '{{WRAPPER}} .tcgelements-awards .item-row:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'item_background_card_hover',
                'label'    => esc_html__( 'Background', 'element-camp' ),
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-awards .item-row:hover',
            ]
        );
        $this->add_control(
            'item_style_dark_mode_hover',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'item_border_dark_mode_hover',
            [
                'label' => esc_html__('Border Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-awards .item-row:hover' => 'border-color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-awards .item-row:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'item_background_dark_mode_hover',
                'selector' => '{{WRAPPER}} .tcgelements-awards .item-row:hover',
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
                    'tcg_gradient_angle' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}}{{#color_c.VALUE}}, {{color_c.VALUE}} {{color_c_stop.SIZE}}{{color_c_stop.UNIT}}{{/color_c.VALUE}}{{#color_d.VALUE}}, {{color_d.VALUE}} {{color_d_stop.SIZE}}{{color_d_stop.UNIT}}{{/color_d.VALUE}})',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}}{{#color_c.VALUE}}, {{color_c.VALUE}} {{color_c_stop.SIZE}}{{color_c_stop.UNIT}}{{/color_c.VALUE}}{{#color_d.VALUE}}, {{color_d.VALUE}} {{color_d_stop.SIZE}}{{color_d_stop.UNIT}}{{/color_d.VALUE}})',
                        ],
                    ],
                    'tcg_gradient_position' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}}{{#color_c.VALUE}}, {{color_c.VALUE}} {{color_c_stop.SIZE}}{{color_c_stop.UNIT}}{{/color_c.VALUE}}{{#color_d.VALUE}}, {{color_d.VALUE}} {{color_d_stop.SIZE}}{{color_d_stop.UNIT}}{{/color_d.VALUE}})',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}}{{#color_c.VALUE}}, {{color_c.VALUE}} {{color_c_stop.SIZE}}{{color_c_stop.UNIT}}{{/color_c.VALUE}}{{#color_d.VALUE}}, {{color_d.VALUE}} {{color_d_stop.SIZE}}{{color_d_stop.UNIT}}{{/color_d.VALUE}})',
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
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        $this->start_controls_section(
            'typography_style',
            [
                'label' => esc_html__( 'Typography', 'element-camp' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'year_heading',
            [
                'label' => esc_html__( 'Year', 'element-camp' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );
        $this->add_responsive_control(
            'year_margin',
            [
                'label' => esc_html__('Year Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-awards .award-year' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'year_typography',
                'selector' => '{{WRAPPER}} .tcgelements-awards .award-year',
            ]
        );
        $this->start_controls_tabs(
            'award_year_tabs',
        );
        $this->start_controls_tab(
            'normal_award_year_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_control(
            'year_color',
            [
                'label'     => esc_html__( 'Year Color', 'element-camp' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [ '{{WRAPPER}} .tcgelements-awards .award-year' => 'color: {{VALUE}};' ],
            ]
        );
        $this->add_control(
            'year_color_dark_mode_heading',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'year_color_dark_mode',
            [
                'label'     => esc_html__( 'Year Color', 'element-camp' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-awards .award-year' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-awards .award-year' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'hover_award_year_tab',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
            ]
        );
        $this->add_control(
            'year_card_hover_color',
            [
                'label'     => esc_html__( 'Year Color', 'element-camp' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [ '{{WRAPPER}} .tcgelements-awards .item-row:hover .award-year' => 'color: {{VALUE}};' ],
            ]
        );
        $this->add_control(
            'year_color_dark_mode_hover_heading',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'year_color_dark_mode_hover',
            [
                'label'     => esc_html__( 'Year Color', 'element-camp' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-awards .item-row:hover .award-year' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-awards .item-row:hover .award-year' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control(
            'title_heading',
            [
                'label'     => esc_html__( 'Award Title', 'element-camp' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Title Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-awards .award-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .tcgelements-awards .award-text',
            ]
        );
        $this->start_controls_tabs(
            'award_title_tabs',
        );
        $this->start_controls_tab(
            'normal_award_title_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__( 'Title Color', 'element-camp' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [ '{{WRAPPER}} .tcgelements-awards .award-text' => 'color: {{VALUE}};' ],
            ]
        );
        $this->add_control(
            'title_color_dark_mode_heading',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'title_color_dark_mode',
            [
                'label'     => esc_html__( 'Title Color', 'element-camp' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-awards .award-text' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-awards .award-text' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'hover_award_title_tab',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
            ]
        );
        $this->add_control(
            'title_card_hover_color',
            [
                'label'     => esc_html__( 'Title Color', 'element-camp' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [ '{{WRAPPER}} .tcgelements-awards .item-row:hover .award-text' => 'color: {{VALUE}};' ],
            ]
        );
        $this->add_control(
            'title_color_dark_mode_hover_heading',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'title_color_dark_mode_hover',
            [
                'label'     => esc_html__( 'Title Color', 'element-camp' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-awards .item-row:hover .award-text' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-awards .item-row:hover .award-text' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control(
            'position_heading',
            [
                'label'     => esc_html__( 'Position', 'element-camp' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'position_margin',
            [
                'label' => esc_html__('Position Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-awards .award-position' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'position_typography',
                'selector' => '{{WRAPPER}} .tcgelements-awards .award-position',
            ]
        );
        $this->start_controls_tabs(
            'award_position_tabs',
        );
        $this->start_controls_tab(
            'normal_award_position_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_control(
            'position_color',
            [
                'label'     => esc_html__( 'Position Color', 'element-camp' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [ '{{WRAPPER}} .tcgelements-awards .award-position' => 'color: {{VALUE}};' ],
            ]
        );
        $this->add_control(
            'position_color_dark_mode_heading',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'position_color_dark_mode',
            [
                'label'     => esc_html__( 'Position Color', 'element-camp' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-awards .award-position' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-awards .award-position' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'hover_award_position_tab',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
            ]
        );
        $this->add_control(
            'position_card_hover_color',
            [
                'label'     => esc_html__( 'Position Color', 'element-camp' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [ '{{WRAPPER}} .tcgelements-awards .item-row:hover .award-position' => 'color: {{VALUE}};' ],
            ]
        );
        $this->add_control(
            'position_color_dark_mode_hover_heading',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'position_color_dark_mode_hover',
            [
                'label'     => esc_html__( 'Position Color', 'element-camp' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-awards .item-row:hover .award-position' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-awards .item-row:hover .award-position' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control(
            'icon_heading',
            [
                'label'     => esc_html__( 'Arrow Icon', 'element-camp' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'icon_size',
            [
                'label'      => esc_html__( 'Icon Size', 'element-camp' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 8, 'max' => 48 ] ],
                'selectors'  => [
                        '{{WRAPPER}} .tcgelements-awards .award-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .tcgelements-awards .award-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'award_icon_tabs',
        );
        $this->start_controls_tab(
            'normal_award_icon_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_control(
            'icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'element-camp' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-awards .award-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-awards .award-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_color_dark_mode_heading',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'icon_color_dark_mode',
            [
                'label'     => esc_html__( 'Icon Color', 'element-camp' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-awards .award-icon i' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-awards .award-icon i' => 'color: {{VALUE}};',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-awards .award-icons svg' => 'fill: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-awards .award-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'hover_award_icon_tab',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
            ]
        );
        $this->add_control(
            'icon_card_hover_color',
            [
                'label'     => esc_html__( 'Icon Color', 'element-camp' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-awards .item-row:hover .award-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-awards .item-row:hover .award-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_color_dark_mode_hover_heading',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'icon_color_dark_mode_hover',
            [
                'label'     => esc_html__( 'Icon Color', 'element-camp' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-awards .item-row:hover .award-icon i' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-awards .item-row:hover .award-icon i' => 'color: {{VALUE}};',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-awards .item-row:hover .award-icons svg' => 'fill: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-awards .item-row:hover .award-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        // ========== STYLE : HOVER IMAGE ==========
        $this->start_controls_section(
            'image_style',
            [
                'label' => esc_html__( 'Hover Image', 'element-camp' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_width',
            [
                'label'      => esc_html__( 'Width', 'element-camp' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem', '%' ],
                'range'      => [ 'px' => [ 'min' => 50, 'max' => 500 ] ],
                'default'    => [ 'unit' => 'px', 'size' => 210 ],
                'selectors'  => [ '{{WRAPPER}} .tcgelements-awards .award-reveal-img' => 'width: {{SIZE}}{{UNIT}};' ],
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label'      => esc_html__( 'Height', 'element-camp' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem', '%' ],
                'range'      => [ 'px' => [ 'min' => 50, 'max' => 500 ] ],
                'default'    => [ 'unit' => 'px', 'size' => 250 ],
                'selectors'  => [ '{{WRAPPER}} .tcgelements-awards .award-reveal-img' => 'height: {{SIZE}}{{UNIT}};' ],
            ]
        );

        $this->add_control(
            'image_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'element-camp' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default'    => [ 'top' => '10', 'right' => '10', 'bottom' => '10', 'left' => '10', 'unit' => 'px', 'isLinked' => true ],
                'selectors'  => [ '{{WRAPPER}} .tcgelements-awards .award-reveal-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'image_box_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-awards .award-reveal-img',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $awards   = $settings['awards_list'];
        if ( empty( $awards ) ) {
            return;
        }
        ?>
        <div class="tcgelements-awards">
            <?php foreach ( $awards as $item ) :
                ?>
                <div class="item-row hover-reveal-item">
                    <div class="row">
                        <div class="col-lg-8">
                            <div>
                                <span class="award-year"><?php echo esc_html( $item['year'] ); ?></span>
                                <span class="award-text"><?php echo esc_html( $item['title'] ); ?></span>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="cont">
                                <span class="award-position"><?php echo esc_html( $item['position'] ); ?></span>
                                <span class="award-icon">
                                        <?php Icons_Manager::render_icon($item['selected_icon'], ['aria-hidden' => 'true']); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <?php if ( !empty($item['image']['url']) ) : ?>
                        <img class="award-reveal-img" src="<?php echo esc_url($item['image']['url']); ?>" alt="<?php if (!empty($item['image']['alt'])) echo esc_attr($item['image']['alt']); ?>" >
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}