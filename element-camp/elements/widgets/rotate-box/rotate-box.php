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

if (!defined('ABSPATH')) exit;

class ElementCamp_Rotate_Box extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'tcgelements-rotate-box';
    }

    public function get_title()
    {
        return esc_html__('Rotate Box', 'element-camp');
    }

    public function get_icon()
    {
        return 'eicon-counter-circle tce-widget-badge';
    }

    public function get_categories()
    {
        return ['elementcamp-elements'];
    }
    
    public function get_script_depends() {
        return ['lity'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Rotate Text Settings', 'element-camp'),
            ]
        );
        $this->add_control(
            'play_button',
            [
                'label'        => esc_html__( 'Play Button', 'element-camp' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'element-camp' ),
                'label_off'    => esc_html__( 'No', 'element-camp' ),
            ]
        );
        $this->add_control(
            'link',
            [
                'label' => esc_html__( 'Link', 'element-camp' ),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => '',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'text',
            [
                'label' => esc_html__('Text', 'element-camp'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__('Rotate Text - Rotate Text - Rotate Text', 'element-camp'),
            ]
        );
        $this->add_control(
            'center_object',
            [
                'label' => esc_html__( 'Center Object', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'image',
                'options' => [
                    'image' => esc_html__( 'Image', 'element-camp' ),
                    'text' => esc_html__( 'Text', 'element-camp' ),
                    'icon' => esc_html__( 'Icon', 'element-camp' ),
                ],
            ]
        );
        $this->add_control(
            'center_text',
            [
                'label' => esc_html__('Center Text', 'element-camp'),
                'default' => esc_html__('Center Text', 'element-camp'),
                'condition'=>['center_object'=>'text'],
                'type' => \Elementor\Controls_Manager::TEXT,
            ]
        );
        $this->add_control(
            'image',
            [
                'label' => esc_html__('Image', 'element-camp'),
                'condition'=>['center_object'=>'image'],
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );
        $this->add_control(
            'selected_icon',
            [
                'label' => esc_html__('Icon', 'element-camp'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'condition'=>['center_object'=>'icon'],
                'skin' => 'inline',
                'label_block' => false,
            ]
        );
        $this->add_control(
            'opacity',
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
                    '{{WRAPPER}} .tcgelements-rotate-box' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'scale',
            [
                'label' => esc_html__( 'Scale', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 10,
                        'min' => 0,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-rotate-box' => 'transform: scale({{SIZE}});',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__( 'Style', 'element-camp' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'backdrop_filter_blur',
            [
                'label' => esc_html__( 'Backdrop Filter Blur', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px','custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-rotate-box' => 'backdrop-filter: blur({{SIZE}}{{UNIT}});',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'rotate_background',
                'label' => esc_html__( 'Background', 'element-camp' ),
                'types' => [ 'classic','gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-rotate-box',
            ]
        );
        $this->add_control(
            'rotate_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-rotate-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'width',
            [
                'label' => esc_html__( 'Width', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'range' => [
                    'px' => [
                        'max' => 1000,
                    ],
                ],
                'default' => [
                    'size' => 220,
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-rotate-box' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-rotate-box .rotate-circle svg' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'height',
            [
                'label' => esc_html__( 'Height', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 500,
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 220,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-rotate-box' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-rotate-box .rotate-circle svg' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'label' => esc_html__('Rotate Text', 'element-camp'),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_ACCENT,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-rotate-box .rotate-circle',
            ]
        );
        $this->add_responsive_control(
            'svg_scale',
            [
                'label' => esc_html__( 'SVG Scale', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 10,
                        'min' => 0,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-rotate-box svg' => 'transform: scale({{SIZE}});',
                ],
            ]
        );
        $this->add_control(
            'svg_color',
            [
                'label' => esc_html__('Text Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-rotate-box .rotate-circle svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'rotate_border',
                'label' => esc_html__( 'Rotate Circle Border', 'element-camp' ),
                'selector' => '{{WRAPPER}} .tcgelements-rotate-box .rotate-circle',
            ]
        );
        $this->add_control(
            'svg_border_radius',
            [
                'label' => esc_html__('SVG Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-rotate-box .rotate-circle svg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'svg_outline',
            [
                'label' => esc_html__( 'Outline', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__( 'None', 'element-camp' ),
                    'solid' => esc_html__( 'Solid', 'element-camp' ),
                    'dashed' => esc_html__( 'Dashed', 'element-camp' ),
                    'dotted' => esc_html__( 'Dotted', 'element-camp' ),
                ],
                'default' => 'none',
            ]
        );
        $this->add_control(
            'svg_outline_color',
            [
                'label' => esc_html__('Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'svg_outline!' => 'none',
                ],
            ]
        );
        $this->add_responsive_control(
            'svg_outline_width',
            [
                'label' => esc_html__( 'Outline Width', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-rotate-box .rotate-circle svg' => 'outline: {{svg_outline_width.SIZE}}{{svg_outline_width.UNIT}} {{svg_outline.VALUE}} {{svg_outline_color.VALUE}}',
                ],
                'condition' => [
                    'svg_outline!' => 'none',
                ],
            ]
        );
        $this->add_control(
            'svg_outline_offset',
            [
                'label' => esc_html__( 'Outline Offset', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 20,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-rotate-box .rotate-circle svg' => 'outline-offset: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'svg_outline!' => 'none',
                ],
            ]
        );
        $this->add_control(
            'center_text_style',
            [
                'label' => esc_html__( 'Center Text Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'before',
                'condition'=> ['center_object' => 'text'],
            ]
        );
        $this->add_responsive_control(
            'center_text_align',
            [
                'label' => esc_html__( 'Alignment', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'element-camp' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'element-camp' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'element-camp' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__( 'Justified', 'element-camp' ),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-rotate-box .center-text' => 'text-align: {{VALUE}};',
                ],
                'condition'=> ['center_object' => 'text'],
            ]
        );
        $this->add_responsive_control(
            'center_text_padding',
            [
                'label' => esc_html__('Center Text Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem', '%', 'custom'],
                'condition'=> ['center_object' => 'text'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-rotate-box .center-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'center_text_border',
                'label' => esc_html__( 'Center Text Border', 'element-camp' ),
                'selector' => '{{WRAPPER}} .tcgelements-rotate-box .center-text',
                'condition'=> ['center_object' => 'text'],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'center_typography',
                'label' => esc_html__('Center Text', 'element-camp'),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_ACCENT,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-rotate-box .center-text',
                'condition'=>['center_object'=>'text'],
            ]
        );
        $this->add_control(
            'text_color',
            [
                'label' => esc_html__('Center Text Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'condition'=>['center_object'=>'text'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-rotate-box .center-text' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'dark_mode_style',
            [
                'label' => esc_html__( 'Dark Mode', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control(
            'svg_color_dark_mode',
            [
                'label' => esc_html__( 'Rotate Color (Dark Mode)', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-rotate-box .rotate-circle svg' => 'fill: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-rotate-box .rotate-circle svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'outline_color_dark_mode',
            [
                'label' => esc_html__( 'Outline Color (Dark Mode)', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-rotate-box .rotate-circle svg' => 'outline-color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-rotate-box .rotate-circle svg' => 'outline-color: {{VALUE}};',
                ],
                'condition' => [
                    'svg_outline!' => 'none',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_icon_style',
            [
                'label' => esc_html__( 'Icon', 'element-camp' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>['center_object'=>'icon'],
            ]
        );
        $this->add_control(
            'icon_wrapper_display',
            [
                'label' => esc_html__('Icon Wrapper Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'flex',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-rotate-box .icon' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'justify_content',
            [
                'label' => esc_html__( 'Justify Content', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-rotate-box .icon' => 'justify-content: {{VALUE}};',
                ],
                'condition'=>['icon_wrapper_display'=>'flex'],
                'responsive' => true,
            ]);

        $this->add_responsive_control(
            'align_items',
            [
                'label' => esc_html__( 'Align Items', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-rotate-box .icon' => 'align-items: {{VALUE}};',
                ],
                'condition'=>['icon_wrapper_display'=>'flex'],
                'responsive' => true,
            ]);
        $this->add_responsive_control(
            'icon_wrapper_width',
            [
                'label' => esc_html__( 'Icon Wrapper Width', 'element-camp' ),
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
                'size_units' => [ '%', 'px', 'vw' ],
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
                    '{{WRAPPER}} .tcgelements-rotate-box .icon' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_wrapper_height',
            [
                'label' => esc_html__( 'Icon Wrapper Height', 'element-camp' ),
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
                'size_units' => [ 'px', 'vh', '%' ],
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
                    '{{WRAPPER}} .tcgelements-rotate-box .icon' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_border_radius',
            [
                'label' => esc_html__( 'Icon Border Radius', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-rotate-box .icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'icon_background_color',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-rotate-box .icon',
            ]
        );
        $this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__( 'Icon size', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' , 'custom' , 'rem' , 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-rotate-box .icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-rotate-box .icon svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__('Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-rotate-box .icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-rotate-box .icon svg' => 'fill: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-rotate-box .icon' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_padding',
            [
                'label' => esc_html__('Icon Phadding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-rotate-box .icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_image_style',
            [
                'label' => esc_html__( 'Image', 'element-camp' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>['center_object'=>'image'],
            ]
        );
        $this->add_responsive_control(
            'image_padding',
            [
                'label' => esc_html__('Image Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-rotate-box img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'img_width',
            [
                'label' => esc_html__( 'Image Width', 'element-camp' ),
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
                'size_units' => [ '%', 'px', 'vw' ],
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
                    '{{WRAPPER}} .tcgelements-rotate-box img' => 'width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'img_height',
            [
                'label' => esc_html__( 'Image Height', 'element-camp' ),
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
                'size_units' => [ 'px', 'vh', '%' ],
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
                    '{{WRAPPER}} .tcgelements-rotate-box img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'object-fit',
            [
                'label' => esc_html__( 'Object Fit', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'img_height[size]!' => '',
                ],
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'fill' => esc_html__( 'Fill', 'element-camp' ),
                    'cover' => esc_html__( 'Cover', 'element-camp' ),
                    'contain' => esc_html__( 'Contain', 'element-camp' ),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-rotate-box img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => esc_html__( 'Image Border Radius', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-rotate-box img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'image_background_color',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-rotate-box img',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_box_shadow',
                'label' => esc_html__('Image Box Shadow', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-rotate-box .image',
            ]
        );
        $this->add_control(
            'image_dark_mode_style',
            [
                'label' => esc_html__( 'Dark Mode', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control(
            'image_filter_invert',
            [
                'label' => esc_html__( 'Filter Inver', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-rotate-box .image' => 'filter: invert({{SIZE}});',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-rotate-box .image' => 'filter: invert({{SIZE}});',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_rotate_box_animations',
            [
                'label' => esc_html__( 'Animations', 'element-camp' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'rotate_box_animations',
            [
                'label' => esc_html__('Animations', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('None', 'element-camp'),
                    'parallax' => esc_html__('Parallax Animation', 'element-camp'),
                ],
            ]
        );
        $this->add_control(
            'parallax_speed',
            [
                'label' => esc_html__( 'Speed', 'element-camp' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 1,
                'min' => 0,
                'step' => 0.1,
                'condition' => [
                    'rotate_box_animations' => 'parallax',
                ],
            ]
        );
        $this->add_control(
            'parallax_lag',
            [
                'label' => esc_html__( 'Lag', 'element-camp' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.7,
                'min' => 0,
                'step' => 0.1,
                'condition' => [
                    'rotate_box_animations' => 'parallax',
                ],
            ]
        );
        $this->end_controls_section();
    }
    protected function render() {
        $settings = $this->get_settings_for_display();
        $unique_id=uniqid();
        $animation = $settings['rotate_box_animations'];
        ?>
        <a href="<?= esc_url($settings['link']['url']) ?>" class="tcgelements-rotate-box" <?php if($settings['play_button']=='yes') echo esc_attr('data-lity="video"') ?> <?php if ( $settings['link']['is_external'] ) echo'target="_blank"'; ?> <?php if ($animation=='parallax') : ?> data-speed="<?=$settings['parallax_speed']?>" data-lag="<?=$settings['parallax_lag']?>" <?php endif?> <?php if (is_rtl()) echo esc_attr("dir=ltr")?>>
            <div class="rotate-circle rotate-text">
                <svg class="textcircle" viewBox="0 0 500 500">
                    <defs>
                        <path id="textcircle<?=$unique_id?>" d="M250,400 a150,150 0 0,1 0,-300a150,150 0 0,1 0,300Z">
                        </path>
                    </defs>
                    <text>
                        <textPath xlink:href="#textcircle<?=$unique_id?>" textLength="900"> <?=esc_html($settings['text'])?> </textPath>
                    </text>
                </svg>
            </div>
            <?php if($settings['center_object']=='image' && !empty($settings['image']['url'])) : ?>
                <img src="<?=esc_url($settings['image']['url'])?>" alt="<?php if (!empty($settings['image']['alt'])) echo esc_attr($settings['image']['alt']); ?>" class="image">
            <?php elseif($settings['center_object']=='text' && !empty($settings['center_text'])) :?>
                <span class="center-text"><?=esc_html($settings['center_text'])?></span>
            <?php elseif($settings['center_object']=='icon' && !empty($settings['selected_icon'])) :?>
                <span class="icon"><?php Icons_Manager::render_icon($settings['selected_icon'], ['aria-hidden' => 'true']); ?></span>
            <?php endif;?>
        </a>
        <?php
    }

}
