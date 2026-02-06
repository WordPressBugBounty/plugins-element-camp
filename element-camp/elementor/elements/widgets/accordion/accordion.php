<?php
namespace ElementCampPlugin\Widgets;

use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\repeater;
use Elementor\Frontend;
use Elementor\Icons_Manager;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Image_Size;
use Elementor\Scheme_Base;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;

if (!defined('ABSPATH')) exit;

class ElementCamp_Accordion extends Widget_Base
{
    public function get_name()
    {
        return 'tcgelements-accordion';
    }

    public function get_title()
    {
        return esc_html__('Accordion', 'element-camp');
    }

    public function get_icon()
    {
        return 'eicon-accordion tce-widget-badge';
    }

    public function get_script_depends() {
        return ['bootstrap.bundle.min','tcgelements-accordion'];
    }

    public function get_categories()
    {
        return ['elementcamp-elements'];
    }
    protected function _register_controls()
    {
        $this->start_controls_section(
            'section_items',
            [
                'label' => esc_html__('Accordion Items', 'element-camp'),
            ]
        );
        $this->add_control(
            'icon_style',
            [
                'label' => esc_html__( 'Icon Style', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'chevron' => esc_html__( 'Chevron', 'element-camp' ),
                    'arrow' => esc_html__( 'Arrow', 'element-camp' ),
                    'custom' => esc_html__( 'Custom', 'element-camp' ),
                ],
                'default' => 'arrow',
            ]
        );

        $this->add_control(
            'item_icon',
            [
                'label' => esc_html__('Item Icon', 'element-camp'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'condition' =>['icon_style'=>'custom'],
                'skin' => 'inline',
                'label_block' => false,
            ]
        );

        $this->add_control(
            'active_item_icon',
            [
                'label' => esc_html__('Active Item Icon', 'element-camp'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'condition' =>['icon_style'=>'custom'],
                'skin' => 'inline',
                'label_block' => false,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'title_image',
            [
                'label' =>esc_html__('Title Image', 'element-camp'),
                'type' => Controls_Manager::MEDIA,
            ]
        );

        $repeater->add_control(
            'item_count',
            [
                'label' =>esc_html__('Item Count', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'description' => esc_html__( 'You can use <span></span> to set different style', 'element-camp' ),
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'element-camp'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => esc_html__('How can i make an order from themescamp?', 'element-camp'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'content',
            [
                'label' => esc_html__('Content', 'element-camp'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => '<p>' . esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'element-camp' ) . '</p>',
            ]
        );

        $this->add_control(
            'accordion_items',
            [
                'label' => esc_html__('Accordion Items', 'element-camp'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ title }}}',
                'default' => [
                    [
                        'title' => esc_html__('Accordion #1', 'element-camp'),
                        'content' => esc_html__('Accordion Content #1', 'element-camp'),
                    ],
                    [
                        'title' => esc_html__('Accordion #2', 'element-camp'),
                        'content' => esc_html__('Accordion Content #2', 'element-camp'),
                    ],
                    [
                        'title' => esc_html__('Accordion #3', 'element-camp'),
                        'content' => esc_html__('Accordion Content #3', 'element-camp'),
                    ],
                ],
            ]
        );
        $this->add_control(
            'active_item',
            [
                'label' => esc_html__('Active Item Number', 'element-camp'),
                'default'=>1,
                'type' => \Elementor\Controls_Manager::NUMBER,
            ]
        );
        $this->add_responsive_control(
            'space_between',
            [
                'label' => esc_html__( 'Space Between', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', 'custom' ],
                'separator'=>'before',
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'style_item_section',
            [
                'label' => esc_html__( 'Item Style', 'element-camp' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'accordion_item_overflow',
            [
                'label' => esc_html__('Item Overflow', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'hidden' => esc_html__('Hidden', 'element-camp'),
                    'visible' => esc_html__('Visible', 'element-camp'),
                ],
                'label_block' => true,
                'default' => 'visible',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item' => 'overflow: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'item_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'item_margin',
            [
                'label' => esc_html__('Item Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em' , 'rem' , 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'item_padding',
            [
                'label' => esc_html__('Item Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em' , 'rem' , 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_box_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-accordion .accordion-item',
            ]
        );
        $this->add_control(
            'item_blur_method',
            [
                'label' => esc_html__('Blur Method', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'backdrop-filter' => 'backdrop-filter',
                    'filter' => 'filter',
                ],
                'default' => 'backdrop-filter',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item' => '{{VALUE}}: blur({{item_blur_value.SIZE}}px);',
                ],
            ]
        );
        $this->add_control(
            'item_blur_value',
            [
                'label' => esc_html__('Blur', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 250,
                    ],
                ],
            ]
        );
        $this->start_controls_tabs(
            'accordion_item_tabs',
        );

        $this->start_controls_tab(
            'normal_item_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'background_item_color',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-accordion .accordion-item',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'selector' => '{{WRAPPER}} .tcgelements-accordion .accordion-item',
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'item_dark_mode_heading',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'item_dark_mode_border_color',
            [
                'label' => esc_html__('Border Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-accordion .accordion-item' => 'border-color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-accordion .accordion-item' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'item_background_dark_mode',
                'selector' => '{{WRAPPER}} .tcgelements-accordion .accordion-item',
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

        $this->end_controls_tab();

        $this->start_controls_tab(
            'active_item_tab',
            [
                'label'   => esc_html__( 'Active', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'background_item_color_active',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-accordion .accordion-item.active',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border_active',
                'selector' => '{{WRAPPER}} .tcgelements-accordion .accordion-item.active',
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'active_item_dark_mode_heading',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'active_item_dark_mode_border_color',
            [
                'label' => esc_html__('Border Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-accordion .accordion-item.active' => 'border-color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-accordion .accordion-item.active' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'active_item_background_dark_mode',
                'selector' => '{{WRAPPER}} .tcgelements-accordion .accordion-item.active',
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
        $this->end_controls_tab();

        $this->start_controls_tab(
            'last_item_tab',
            [
                'label'   => esc_html__( 'Last', 'element-camp' ),
            ]
        );
        $this->add_control(
            'last_item_margin',
            [
                'label' => esc_html__('Item Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em' , 'rem' , 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item:last-child' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border_last',
                'selector' => '{{WRAPPER}} .tcgelements-accordion .accordion-item:last-child',
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();

        $this->start_controls_section(
            'before_item_style',
            [
                'label' => esc_html__('Before Item Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'before_item_index',
            [
                'label' => esc_html__( 'Z-index', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item::before' => 'z-index: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'position_for_before_item',
            [
                'label' => esc_html__( 'Position', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'absolute' => esc_html__( 'Absolute', 'element-camp' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item::before' => 'position: {{VALUE}};content:"";',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'before_item_offset_orientation_h',
            [
                'label' => esc_html__( 'Horizontal Orientation', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => esc_html__( 'Start', 'element-camp' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'end' => [
                        'title' => esc_html__( 'End', 'element-camp' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'condition' => [
                    'position_for_before_item!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'before_item_offset_x',
            [
                'label' => esc_html__( 'Offset', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
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
                'default' => [
                    'size' => 0,
                ],
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item::before' => 'left: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'before_item_offset_orientation_h' => 'start',
                    'position_for_before_item!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'before_item_offset_x_end',
            [
                'label' => esc_html__( 'Offset', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
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
                'default' => [
                    'size' => 0,
                ],
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item::before' => 'right: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'before_item_offset_orientation_h' => 'end',
                    'position_for_before_item!' => '',
                ],
            ]
        );
        $this->add_control(
            'before_item_offset_orientation_v',
            [
                'label' => esc_html__( 'Vertical Orientation', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => esc_html__( 'Top', 'element-camp' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'end' => [
                        'title' => esc_html__( 'Bottom', 'element-camp' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'condition' => [
                    'position_for_before_item!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'before_item_offset_y',
            [
                'label' => esc_html__( 'Offset', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
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
                'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'vw', 'custom' ],
                'default' => [
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item::before' => 'top: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'before_item_offset_orientation_v' => 'start',
                    'position_for_before_item!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'before_item_offset_y_end',
            [
                'label' => esc_html__( 'Offset', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
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
                'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'vw', 'custom' ],
                'default' => [
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item::before' => 'bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'before_item_offset_orientation_v' => 'end',
                    'position_for_before_item!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'before_item_width',
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
                'size_units' => [ '%', 'px', 'vw', 'rem', 'custom' ],
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
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item::before' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'before_item_height',
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
                'size_units' => [ 'px', 'vh', '%', 'vw', 'rem', 'custom'],
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
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item::before' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'before_item_background_tabs',
        );

        $this->start_controls_tab(
            'before_item_normal_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'before_item_background_color',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-accordion .accordion-item::before',
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'before_item_last_tab',
            [
                'label'   => esc_html__( 'Last Item', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'before_item_background_color_last',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-accordion .accordion-item:last-child::before',
                'default' => 'transparent',
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();


        $this->start_controls_section(
            'style_title_section',
            [
                'label' => esc_html__( 'Title Style', 'element-camp' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'accordion_Header_heading',
            [
                'label' => esc_html__( 'Accordion Header', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );
        $this->add_responsive_control(
            'accordion_header_display',
            [
                'label' => esc_html__('Accordion Header Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'flex',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'inline-flex' => esc_html__('Inline Flex', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'accordion_header_justify_content',
            [
                'label' => esc_html__( 'Accordion Header Justify Content', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'default' => '',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Start','element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-start-h',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-center-h',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'End', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-end-h',
                    ],
                    'space-between' => [
                        'title' => esc_html__( 'Space Between', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-space-between-h',
                    ],
                    'space-around' => [
                        'title' => esc_html__( 'Space Around', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-space-around-h',
                    ],
                    'space-evenly' => [
                        'title' => esc_html__( 'Space Evenly', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-space-evenly-h',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header' => 'justify-content: {{VALUE}};',
                ],
                'condition'=>['accordion_header_display'=> ['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'accordion_header_align_items',
            [
                'label' => esc_html__( 'Accordion Header Align Items', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => '',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Start', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-align-start-v',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-align-center-v',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'End', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-align-end-v',
                    ],
                    'stretch' => [
                        'title' => esc_html__( 'Stretch', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-align-stretch-v',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header' => 'align-items: {{VALUE}};',
                ],
                'condition'=>['accordion_header_display'=> ['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'accordion_header_margin',
            [
                'label' => esc_html__( 'Accordion Header Margin', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'accordion_header_padding',
            [
                'label' => esc_html__( 'Accordion Header Padding', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'accordion_button_padding',
            [
                'label' => esc_html__( 'Accordion Button Padding', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_text_margin',
            [
                'label' => esc_html__( 'Title Margin', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );
        $this->add_responsive_control(
            'text_breakline',
            [
                'label' => esc_html__('Make Break Line Tag Hidden', 'element-camp'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-button br' => 'display: none;',
                ],
            ]
        );
        $this->add_control(
            'text_wrap',
            [
                'label' => esc_html__('Text Wrap', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'unset' => esc_html__('Unset', 'element-camp'),
                    'wrap' => esc_html__('Wrap', 'element-camp'),
                    'nowrap' => esc_html__('No Wrap', 'element-camp'),
                    'balance' => esc_html__('Balance', 'element-camp'),
                    'pretty' => esc_html__('Pretty', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button' => 'text-wrap: {{VALUE}};'
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'title_box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button',
            ]
        );
        $this->add_responsive_control(
            'title_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->start_controls_tabs(
            'title_tabs',
        );

        $this->start_controls_tab(
            'title_normal_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'label' => esc_html__('Button Typography', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button',
            ]
        );
        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Title Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'title_background_color',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'title_border',
                'selector' => '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button',
            ]
        );
        $this->add_control(
            'title_dark_mode_heading',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'title_color_dark_mode',
            [
                'label' => esc_html__('Title Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'title_border_color_dark_mode',
            [
                'label' => esc_html__('Border Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button' => 'border-color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'title_active_tab',
            [
                'label'   => esc_html__( 'Active', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography_active',
                'label' => esc_html__('Button Typography', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button:not(.collapsed)',
            ]
        );
        $this->add_control(
            'title_color_active',
            [
                'label' => esc_html__('Title Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button:not(.collapsed)' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'title_background_color_active',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button:not(.collapsed)',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'title_border_active',
                'selector' => '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button:not(.collapsed)',
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
        $this->start_controls_section(
            'style_image_section',
            [
                'label' => esc_html__( 'Image Style', 'element-camp' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'image_container_heading',
            [
                'label' => esc_html__( 'Image Container Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'after'
            ]
        );
        $this->add_responsive_control(
            'image_container_display',
            [
                'label' => esc_html__('Image Container Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'flex',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'inline-flex' => esc_html__('Inline Flex', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .img' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'image_container_justify_content',
            [
                'label' => esc_html__( 'Image Container Justify Content', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'default' => '',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Start','element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-start-h',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-center-h',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'End', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-end-h',
                    ],
                    'space-between' => [
                        'title' => esc_html__( 'Space Between', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-space-between-h',
                    ],
                    'space-around' => [
                        'title' => esc_html__( 'Space Around', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-space-around-h',
                    ],
                    'space-evenly' => [
                        'title' => esc_html__( 'Space Evenly', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-space-evenly-h',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .img' => 'justify-content: {{VALUE}};',
                ],
                'condition'=>['image_container_display'=> ['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'image_container_align_items',
            [
                'label' => esc_html__( 'Image Container Align Items', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => '',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Start', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-align-start-v',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-align-center-v',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'End', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-align-end-v',
                    ],
                    'stretch' => [
                        'title' => esc_html__( 'Stretch', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-align-stretch-v',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .img' => 'align-items: {{VALUE}};',
                ],
                'condition'=>['image_container_display'=> ['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'image_container_margin',
            [
                'label' => esc_html__('Image Container Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_container_padding',
            [
                'label' => esc_html__('Image Container Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_container_width',
            [
                'label' => esc_html__( 'Image Container Width', 'element-camp' ),
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
                'size_units' => [ '%', 'px', 'vw', 'rem', 'custom' ],
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
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_container_height',
            [
                'label' => esc_html__( 'Image Container Height', 'element-camp' ),
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
                'size_units' => [ 'px', 'vh', '%', 'vw', 'rem', 'custom'],
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
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs(
            'image_container_tabs',
        );
        $this->start_controls_tab(
            'image_container_normal_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'image_container_background_color',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .img',
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'image_container_active_tab',
            [
                'label'   => esc_html__( 'Active', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'image_container_background_color_active',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button:not(.collapsed) .img',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control(
            'image_heading',
            [
                'label' => esc_html__( 'Image Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'before'
            ]
        );
        $this->add_responsive_control(
            'image_width',
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
                'size_units' => [ '%', 'px', 'vw', 'rem', 'custom' ],
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
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .img img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_height',
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
                'size_units' => [ 'px', 'vh', '%', 'vw', 'rem', 'custom'],
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
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .img img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_object-fit',
            [
                'label' => esc_html__( 'Object Fit', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'fill' => esc_html__( 'Fill', 'element-camp' ),
                    'cover' => esc_html__( 'Cover', 'element-camp' ),
                    'contain' => esc_html__( 'Contain', 'element-camp' ),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .img img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_object-position',
            [
                'label' => esc_html__( 'Object Position', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'center center' => esc_html__( 'Center Center', 'element-camp' ),
                    'center left' => esc_html__( 'Center Left', 'element-camp' ),
                    'center right' => esc_html__( 'Center Right', 'element-camp' ),
                    'top center' => esc_html__( 'Top Center', 'element-camp' ),
                    'top left' => esc_html__( 'Top Left', 'element-camp' ),
                    'top right' => esc_html__( 'Top Right', 'element-camp' ),
                    'bottom center' => esc_html__( 'Bottom Center', 'element-camp' ),
                    'bottom left' => esc_html__( 'Bottom Left', 'element-camp' ),
                    'bottom right' => esc_html__( 'Bottom Right', 'element-camp' ),
                ],
                'default' => 'center center',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .img img' => 'object-position: {{VALUE}};',
                ],
            ]
        );
        $this->start_controls_tabs(
            'image_tabs',
        );
        $this->start_controls_tab(
            'image_normal_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'image_css_filters',
                'selector' => '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .img img',
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'image_active_tab',
            [
                'label'   => esc_html__( 'Active', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'image_css_filters_active',
                'selector' => '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button:not(.collapsed) .img img',
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
        $this->start_controls_section(
            'style_text_section',
            [
                'label' => esc_html__( 'Text Style', 'element-camp' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'accordion_body_width',
            [
                'label' => esc_html__('Width', 'element-camp'),
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
                'size_units' => ['%', 'px', 'vw', 'rem', 'custom'],
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
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-body' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'accordion_body_padding',
            [
                'label' => esc_html__( 'Accordion Body Padding', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'text_padding',
            [
                'label' => esc_html__( 'Text Padding', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-body > *' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'text_margin',
            [
                'label' => esc_html__( 'Text Margin', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-body > *' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'label' => esc_html__('Text Typography', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-body , {{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-body > *',
            ]
        );

        $this->add_responsive_control(
            'text_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-body' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'text_dark_mode_heading',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'text_color_dark_mode',
            [
                'label' => esc_html__('Text Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-body' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-body' => 'color: {{VALUE}};',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-body > *' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-body > *' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->start_controls_tabs(
            'accordion_body_tabs',
        );
        $this->start_controls_tab(
            'accordion_body_normal_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'body_background_color',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-body',
            ]
        );
        $this->add_control(
            'text_color',
            [
                'label' => esc_html__('Text Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-body' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-body > *' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'selector' => '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-body',
                'separator' => 'before',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'accordion_body_active_tab',
            [
                'label'   => esc_html__( 'Active', 'element-camp' ),
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'body_background_color_active',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-accordion .accordion-item.active .accordion-body',
            ]
        );

        $this->add_control(
            'text_color_active',
            [
                'label' => esc_html__('Text Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item.active .accordion-body' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item.active .accordion-body > *' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'text_active_border',
                'selector' => '{{WRAPPER}} .tcgelements-accordion .accordion-item.active .accordion-body',
                'separator' => 'before',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
        $this->start_controls_section(
            'style_count_section',
            [
                'label' => esc_html__( 'Count Style', 'element-camp' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'count_padding',
            [
                'label' => esc_html__( 'Count Padding', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-button .count' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'count_margin',
            [
                'label' => esc_html__( 'Count Margin', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-button .count' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'count_typography',
                'label' => esc_html__('Count Typography', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-button .count',
            ]
        );
        $this->add_control(
            'count_color',
            [
                'label' => esc_html__('Count Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-button .count' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'count_background',
                'label' => esc_html__('Count Background', 'element-camp'),
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-button .count',
            ]
        );

        $this->add_control(
            'count_span_style',
            [
                'label' => esc_html__( 'Span Count', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );
        $this->add_control(
            'count_span_color',
            [
                'label' => esc_html__( 'Text Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-button .count span' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'count_span_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-button .count span',
            ]
        );
        $this->add_responsive_control(
            'count_span_margin',
            [
                'label' => esc_html__('Span Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-button .count span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'style_icon_section',
            [
                'label' => esc_html__( 'Icon Style', 'element-camp' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'accordion_icon_order',
            [
                'label' => esc_html__( 'Order', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => '',
                'options' => [
                    'start' => [
                        'title' => esc_html__( 'Start', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-order-start',
                    ],
                    'end' => [
                        'title' => esc_html__( 'End', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-order-end',
                    ],
                    'custom' => [
                        'title' => esc_html__( 'Custom', 'element-camp' ),
                        'icon' => 'eicon-ellipsis-v',
                    ],
                ],
                'selectors_dictionary' => [
                    // Hacks to set the order to start / end.
                    // For example, if the user has 10 widgets, but wants to set the 5th one to be first,
                    // this hack should do the trick while taking in account elements with `order: 0` or less.
                    'start' => '-99999 /* order start hack */',
                    'end' => '99999 /* order end hack */',
                    'custom' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon' => 'order: {{VALUE}};',
                ],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'accordion_icon_custom',
            [
                'label' => esc_html__( 'Custom Order', 'element-camp' ),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon' => 'order: {{VALUE}};',
                ],
                'responsive' => true,
                'condition' => [
                    'accordion_icon_order' => 'custom',
                ],
            ]
        );
        $this->add_responsive_control(
            'accordion_icon_width',
            [
                'label' => esc_html__( 'Icon Width', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' , 'custom' , 'rem' , 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                ],
                'condition'=>['icon_style'=>'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-closed' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-opened' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-closed svg' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-opened svg' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'accordion_icon_height',
            [
                'label' => esc_html__( 'Icon Height', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' , 'custom' , 'rem' , 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                ],
                'condition'=>['icon_style'=>'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-closed svg' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-opened svg' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_margin',
            [
                'label' => esc_html__('Icon Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-opened' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-closed' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'icon_transition',
            [
                'label' => esc_html__( 'Icon Transition', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-closed i' => 'transition: all {{SIZE}}s ease;',
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-closed svg' => 'transition: all {{SIZE}}s ease;',
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-opened i' => 'transition: all {{SIZE}}s ease;',
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-opened svg' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );
        $this->start_controls_tabs(
            'icon_tabs',
        );

        $this->start_controls_tab(
            'Normal',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_responsive_control(
            'rotate_icon',
            [
                'label' => esc_html__( 'Rotate', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'deg','custom'],
                'default' => [
                    'size' => 0,
                    'unit' => 'deg',
                ],
                'tablet_default' => [
                    'unit' => 'deg',
                ],
                'mobile_default' => [
                    'unit' => 'deg',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-closed i' => 'transform: rotate({{rotate_icon.SIZE}}{{rotate_icon.UNIT}});',
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-closed svg' => 'transform: rotate({{rotate_icon.SIZE}}{{rotate_icon.UNIT}});',
                ],
            ]
        );
        $this->add_control(
            'icon_opacity',
            [
                'label' => esc_html__( 'Opacity', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button.arrow::after' => 'opacity: {{SIZE}};',
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-closed i' => 'opacity: {{SIZE}};',
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-closed svg' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_filter_invert',
            [
                'label' => esc_html__( 'Icon Filter Invert', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-closed' => 'filter: invert({{SIZE}});'
                ],
            ]
        );
        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__('Icon Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button.arrow::after' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-closed i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-closed svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-opened i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-opened svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'icon_background_color',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button.arrow::before , {{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-closed',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'icon_border',
                'selector' => '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button.arrow::before , {{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-closed',
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'icon_dark_mode_heading',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'icon_color_dark_mode',
            [
                'label' => esc_html__('Icon Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button.arrow::after' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button.arrow::after' => 'color: {{VALUE}};',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-closed i' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-closed i' => 'color: {{VALUE}};',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-opened i' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-opened i' => 'color: {{VALUE}};',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-closed svg' => 'fill: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-closed svg' => 'fill: {{VALUE}};',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-opened svg' => 'fill: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-opened svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'Active',
            [
                'label'   => esc_html__( 'Active', 'element-camp' ),
            ]
        );
        $this->add_responsive_control(
            'rotate_icon_active',
            [
                'label' => esc_html__( 'Rotate', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'deg','custom'],
                'default' => [
                    'size' => 0,
                    'unit' => 'deg',
                ],
                'tablet_default' => [
                    'unit' => 'deg',
                ],
                'mobile_default' => [
                    'unit' => 'deg',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-opened i' => 'transform: rotate({{rotate_icon_active.SIZE}}{{rotate_icon_active.UNIT}});',
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-opened svg' => 'transform: rotate({{rotate_icon_active.SIZE}}{{rotate_icon_active.UNIT}});',
                ],
            ]
        );
        $this->add_control(
            'active_icon_opacity',
            [
                'label' => esc_html__( 'Opacity', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button.arrow:not(.collapsed)::after' => 'opacity: {{SIZE}};',
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-opened svg' => 'opacity: {{SIZE}};',
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-opened i' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'active-icon_filter_invert',
            [
                'label' => esc_html__( 'Icon Filter Invert', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-opened' => 'filter: invert({{SIZE}});',
                ],
            ]
        );
        $this->add_control(
            'active_icon_color',
            [
                'label' => esc_html__('Icon Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button.arrow:not(.collapsed)::after' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-opened svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-opened i' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'active_icon_background_color',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button.arrow:not(.collapsed)::before , {{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-opened',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'active_icon_border',
                'selector' => '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button.arrow:not(.collapsed)::before,{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-opened',
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'icon_dark_mode_heading_active',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'icon_color_dark_mode_active',
            [
                'label' => esc_html__('Icon Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button.arrow:not(.collapsed)::after' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button.arrow:not(.collapsed)::after' => 'color: {{VALUE}};',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-opened i' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-opened i' => 'color: {{VALUE}};',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-opened svg' => 'fill: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-opened svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
        $this->start_controls_section(
            'icon_container_style',
            [
                'label' => esc_html__('Icon Container Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'icon_style' => 'custom',
                ],
            ]
        );
        $this->add_control(
            'position_for_icon_container',
            [
                'label' => esc_html__( 'Position', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'absolute' => esc_html__( 'Absolute', 'element-camp' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-opened' => 'position: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-closed' => 'position: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'container_icon_offset_orientation_h',
            [
                'label' => esc_html__( 'Horizontal Orientation', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => esc_html__( 'Start', 'element-camp' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'end' => [
                        'title' => esc_html__( 'End', 'element-camp' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'condition' => [
                    'position_for_icon_container!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'container_icon_offset_x',
            [
                'label' => esc_html__( 'Offset', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
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
                'default' => [
                    'size' => 0,
                ],
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-opened' => 'left: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-closed' => 'left: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'container_icon_offset_orientation_h' => 'start',
                    'position_for_icon_container!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'container_icon_offset_x_end',
            [
                'label' => esc_html__( 'Offset', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
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
                'default' => [
                    'size' => 0,
                ],
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-opened' => 'right: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-closed' => 'right: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'container_icon_offset_orientation_h' => 'end',
                    'position_for_icon_container!' => '',
                ],
            ]
        );
        $this->add_control(
            'container_icon_offset_orientation_v',
            [
                'label' => esc_html__( 'Vertical Orientation', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => esc_html__( 'Top', 'element-camp' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'end' => [
                        'title' => esc_html__( 'Bottom', 'element-camp' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'condition' => [
                    'position_for_icon_container!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'container_icon_offset_y',
            [
                'label' => esc_html__( 'Offset', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
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
                'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'vw', 'custom' ],
                'default' => [
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-opened' => 'top: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-closed' => 'top: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'container_icon_offset_orientation_v' => 'start',
                    'position_for_icon_container!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'container_icon_offset_y_end',
            [
                'label' => esc_html__( 'Offset', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
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
                'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'vw', 'custom' ],
                'default' => [
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-opened' => 'bottom: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-closed' => 'bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'container_icon_offset_orientation_v' => 'end',
                    'position_for_icon_container!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'container_icon_width',
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
                'size_units' => [ '%', 'px', 'vw', 'rem', 'custom' ],
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
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-opened' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-closed' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'container_icon_height',
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
                'size_units' => [ 'px', 'vh', '%', 'vw', 'rem', 'custom'],
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
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-opened' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-closed' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'container_icon_translate_y',
            [
                'label' => esc_html__( 'Translate Y', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
            ]
        );
        $this->add_control(
            'container_icon_translate_x',
            [
                'label' => esc_html__( 'Translate X', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-closed' => 'transform: translate({{container_icon_translate_x.SIZE}}{{container_icon_translate_x.UNIT}},{{container_icon_translate_y.SIZE}}{{container_icon_translate_y.UNIT}})',
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-opened' => 'transform: translate({{container_icon_translate_x.SIZE}}{{container_icon_translate_x.UNIT}},{{container_icon_translate_y.SIZE}}{{container_icon_translate_y.UNIT}})',
                ],
            ]
        );
        $this->add_control(
            'icon_container_transition',
            [
                'label' => esc_html__( 'Icon Container Transition', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-closed' => 'transition: all {{SIZE}}s ease;',
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-opened' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_container_padding',
            [
                'label' => esc_html__( 'Icon Container Padding', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-opened' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-closed' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs(
            'icon_container_tabs',
        );

        $this->start_controls_tab(
            'icon_container_normal',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'icon_container_border',
                'selector' => '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .tcgelements-accordion-icon-closed,{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .tcgelements-accordion-icon-opened',
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'icon_container_border_radius',
            [
                'label' => esc_html__( 'Icon Container Border Radius', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-opened' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-closed' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'icon_container_active',
            [
                'label'   => esc_html__( 'Active', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'icon_container_border_active',
                'selector' => '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-opened',
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'icon_container_border_radius_active',
            [
                'label' => esc_html__( 'Icon Container Border Radius', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-accordion .accordion-item .accordion-header .accordion-button .tcgelements-accordion-icon-opened' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after'
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $accordion_id = uniqid('accordion');
        $has_icon = (! empty( $settings['item_icon']['value'] ) );
        ?>
        <div class="tcgelements-accordion"  id="<?=esc_attr($accordion_id)?>">
            <?php  $itemCount=1; foreach ($settings['accordion_items'] as $item) : ?>
                <div class="accordion-item <?php if ($settings['active_item']==$itemCount) echo esc_attr('active')?>">
                    <h2 class="accordion-header">
                        <button class="accordion-button<?=esc_attr(' '.$settings['icon_style'])?> <?php if ($settings['active_item']!=$itemCount) echo esc_attr('collapsed')?>" data-bs-toggle="collapse" data-bs-target="#collapse<?=$itemCount.$accordion_id?>">
                            <?php if (!empty($item['title_image']['url'])) : ?>
                                <span class="img">
                                        <img src="<?= esc_url($item['title_image']['url']); ?>" alt="<?php if (!empty($item['title_image']['alt'])) echo esc_attr($item['title_image']['alt']); ?>" >
                                    </span>
                            <?php endif;
                            if (!empty($item['item_count'])) : ?>
                                <span class="count"><?=__($item['item_count'])?></span>
                            <?php endif;?>
                            <?=__($item['title'],'element-camp')?>
                            <?php if ( $has_icon ) : ?>
                                <span class="tcgelements-accordion-icon tcgelements-accordion-icon-closed"><?php Icons_Manager::render_icon( $settings['item_icon'] ); ?></span>
                                <span class="tcgelements-accordion-icon tcgelements-accordion-icon-opened"><?php Icons_Manager::render_icon( $settings['active_item_icon'] ); ?></span>
                            <?php endif; ?>
                        </button>
                    </h2>
                    <div id="collapse<?=$itemCount.$accordion_id?>" class="accordion-collapse collapse <?php if ($settings['active_item']==$itemCount) echo 'show'?>" data-bs-parent="<?= esc_attr('#' . $accordion_id) ?>">
                        <div class="accordion-body">
                            <?= $item['content']; ?>
                        </div>
                    </div>
                </div>
                <?php $itemCount++; endforeach;?>
        </div>
        <?php
    }
}
