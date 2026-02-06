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

class ElementCamp_Throwable_Content extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'tcgelements-throwable-content';
    }

    public function get_title()
    {
        return esc_html__('Throwable Content', 'element-camp');
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
        return ['matter.min','tcgelements-throwable'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Settings', 'element-camp'),
            ]
        );
        $repeater = new \Elementor\Repeater();
        $repeater->add_control(
            'text',
            [
                'label' => esc_html__('Text', 'element-camp'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__('Customer Engagement', 'element-camp'),
            ]
        );
        $repeater->add_control(
            'image',
            [
                'label' => esc_html__('Choose Image', 'element-camp'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );
        $repeater->add_control(
            'icon',
            [
                'label' => esc_html__('Icon', 'element-camp'),
                'type' => Controls_Manager::ICONS,
            ]
        );
        $repeater->add_control(
            'icon_position',
            [
                'label' => esc_html__('Icon Position', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'before',
                'options' => [
                    'before' => esc_html__('Before Text', 'element-camp'),
                    'after' => esc_html__('After Text', 'element-camp'),
                ],
                'condition' => [
                    'icon[value]!' => '',
                ],
            ]
        );
        $repeater->add_control(
            'separator_panel_style',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );
        $repeater->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'text_background',
                'label' => esc_html__( 'Background', 'element-camp' ),
                'types' => [ 'classic','gradient' ],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .tcgelements-throwable-content {{CURRENT_ITEM}}.item .txt',
            ]
        );
        $repeater->add_control(
            'text_blur_method',
            [
                'label' => esc_html__('Blur Method', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'backdrop-filter' => 'backdrop-filter',
                    'filter' => 'filter',
                ],
                'default' => 'backdrop-filter',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-throwable-content {{CURRENT_ITEM}}.item .txt' => '{{VALUE}}: blur({{text_blur_value.SIZE}}px);',
                ],
            ]
        );
        $repeater->add_control(
            'text_blur_value',
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
        $repeater->add_control(
            'text_color',
            [
                'label' => esc_html__('Text Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-throwable-content {{CURRENT_ITEM}}.item .txt' => 'color: {{VALUE}};',
                ],
            ]
        );
        $repeater->add_responsive_control(
            'text_width',
            [
                'label' => esc_html__( 'Text Width', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-throwable-content {{CURRENT_ITEM}}.item .txt' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $repeater->add_responsive_control(
            'text_height',
            [
                'label' => esc_html__( 'Text Height', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-throwable-content {{CURRENT_ITEM}}.item .txt' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $repeater->add_responsive_control(
            'text_display',
            [
                'label' => esc_html__('Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'inline-block',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'inline-flex' => esc_html__('Inline Flex', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-throwable-content {{CURRENT_ITEM}}.item .txt' => 'display: {{VALUE}};'
                ]
            ]
        );
        $repeater->add_responsive_control(
            'text_justify_content',
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
                    '{{WRAPPER}} .tcgelements-throwable-content {{CURRENT_ITEM}}.item .txt' => 'justify-content: {{VALUE}};',
                ],
                'condition'=> ['text_display'=> ['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $repeater->add_responsive_control(
            'text_align_items',
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
                    '{{WRAPPER}} .tcgelements-throwable-content {{CURRENT_ITEM}}.item .txt' => 'align-items: {{VALUE}};',
                ],
                'condition'=> ['text_display'=> ['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $repeater->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'text_border',
                'label' => esc_html__( 'Text Border', 'element-camp' ),
                'selector' => '{{WRAPPER}} .tcgelements-throwable-content {{CURRENT_ITEM}}.item .txt',
            ]
        );
        $repeater->add_control(
            'image_options',
            [
                'label' => esc_html__( 'Image Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'image[url]!' => '',
                ],
            ]
        );
        $repeater->add_responsive_control(
            'image_width',
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
                'condition' => [
                    'image[url]!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-throwable-content {{CURRENT_ITEM}}.item .img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $repeater->add_responsive_control(
            'image_height',
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
                'condition' => [
                    'image[url]!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-throwable-content {{CURRENT_ITEM}}.item .img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $repeater->add_control(
            'icon_options',
            [
                'label' => esc_html__( 'Icon Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'icon[value]!' => '',
                ],
            ]
        );
        $repeater->add_control(
            'icon_color',
            [
                'label' => esc_html__('Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-throwable-content {{CURRENT_ITEM}}.item .icon-wrapper i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-throwable-content {{CURRENT_ITEM}}.item .icon-wrapper svg' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'icon[value]!' => '',
                ],
            ]
        );
        $repeater->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'icon_background',
                'label' => esc_html__( 'Icon Background', 'element-camp' ),
                'types' => [ 'classic','gradient' ],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .tcgelements-throwable-content {{CURRENT_ITEM}}.item .icon-wrapper',
                'condition' => [
                    'icon[value]!' => '',
                ],
            ]
        );
        $this->add_control(
            'items',
            [
                'label' => esc_html__('Items', 'element-camp'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{text}}}',
                'default' => [
                    [
                        'text' => esc_html__('Text #1', 'element-camp'),
                    ],
                    [
                        'text' => esc_html__('Text #2', 'element-camp'),
                    ],
                    [
                        'text' => esc_html__('Text #3', 'element-camp'),
                    ],
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_item_style',
            [
                'label' => esc_html__( 'Style', 'element-camp' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'throwable_container_overflow',
            [
                'label' => esc_html__( 'Throwable Container Overflow Hidden', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'yes', 'element-camp' ),
                'label_off' => esc_html__( 'No', 'element-camp' ),
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-throwable-content' => 'overflow: hidden;',
                ],
            ]
        );
        $this->add_responsive_control(
            'div_height',
            [
                'label' => esc_html__('Height', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                ],
                'size_units' => ['px', '%', 'vh', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-throwable-content' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'item_options',
            [
                'label' => esc_html__( 'Item Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'before' => 'before',
            ]
        );
        $this->add_responsive_control(
            'item_display',
            [
                'label' => esc_html__('Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'block',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'inline-flex' => esc_html__('Inline Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-throwable-content .item' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'item_wrapper_justify_content',
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
                    '{{WRAPPER}} .tcgelements-throwable-content .item' => 'justify-content: {{VALUE}};',
                ],
                'condition'=> [ 'item_display'=> ['flex','inline-flex'] ],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'item_align_items',
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
                    '{{WRAPPER}} .tcgelements-throwable-content .item' => 'align-items: {{VALUE}};',
                ],
                'condition'=> [ 'item_display'=> ['flex','inline-flex'] ],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'item_padding',
            [
                'label' => esc_html__('Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-throwable-content .item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'item_background',
                'label' => esc_html__( 'Background', 'element-camp' ),
                'types' => [ 'classic','gradient' ],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .tcgelements-throwable-content .item',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'label' => esc_html__( 'Border', 'element-camp' ),
                'selector' => '{{WRAPPER}} .tcgelements-throwable-content .item',
            ]
        );
        $this->add_responsive_control(
            'item_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-throwable-content .item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_box_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-throwable-content .item',
            ]
        );
        $this->add_control(
            'item_user_select',
            [
                'label' => esc_html__('Item User Select', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'auto' => esc_html__('Auto', 'element-camp'),
                    'none' => esc_html__('None', 'element-camp'),
                    'text' => esc_html__('Text', 'element-camp'),
                    'all' => esc_html__('All', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-throwable-content .item' => 'user-select: {{VALUE}};'
                ]
            ]
        );
        $this->add_control(
            'position_for_item',
            [
                'label' => esc_html__( 'Item Position', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'absolute' => esc_html__( 'Absolute', 'element-camp' ),
                    'relative' => esc_html__( 'Relative', 'element-camp' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-throwable-content .item' => 'position: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'item_offset_orientation_h',
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
                    'position_for_item!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'item_offset_x',
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
                    '{{WRAPPER}} .tcgelements-throwable-content .item' => 'left: {{SIZE}}{{UNIT}};right:auto',
                ],
                'condition' => [
                    'item_offset_orientation_h' => 'start',
                    'position_for_item!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'item_offset_x_end',
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
                    '{{WRAPPER}} .tcgelements-throwable-content .item' => 'right: {{SIZE}}{{UNIT}};left:auto',
                ],
                'condition' => [
                    'item_offset_orientation_h' => 'end',
                    'position_for_item!' => '',
                ],
            ]
        );
        $this->add_control(
            'item_offset_orientation_v',
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
                    'position_for_item!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'item_offset_y',
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
                    '{{WRAPPER}} .tcgelements-throwable-content .item' => 'top: {{SIZE}}{{UNIT}};bottom:auto',
                ],
                'condition' => [
                    'item_offset_orientation_v' => 'start',
                    'position_for_item!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'item_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-throwable-content .item' => 'bottom: {{SIZE}}{{UNIT}};top:auto;',
                ],
                'condition' => [
                    'item_offset_orientation_v' => 'end',
                    'position_for_item!' => '',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__( 'Text Style', 'element-camp' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'label' => esc_html__('Text', 'element-camp'),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_ACCENT,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-throwable-content .item .txt',
            ]
        );
        $this->add_control(
            'text_color',
            [
                'label' => esc_html__('Text Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-throwable-content .item .txt' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'text_padding',
            [
                'label' => esc_html__('Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-throwable-content .item .txt' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'text_background',
                'label' => esc_html__( 'Background', 'element-camp' ),
                'types' => [ 'classic','gradient' ],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .tcgelements-throwable-content .item .txt',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'text_border',
                'label' => esc_html__( 'Text Border', 'element-camp' ),
                'selector' => '{{WRAPPER}} .tcgelements-throwable-content .item .txt',
            ]
        );
        $this->add_responsive_control(
            'text_border_radius',
            [
                'label' => esc_html__( 'Text Border Radius', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-throwable-content .item .txt' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'text_box_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-throwable-content .item .txt',
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_icon_style',
            [
                'label' => esc_html__( 'Icon Style', 'element-camp' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'icon_wrapper_options',
            [
                'label' => esc_html__( 'Icon Wrapper Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );
        $this->add_control(
            'position_for_icon_wrapper',
            [
                'label' => esc_html__( 'icon wrapper Position', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'absolute' => esc_html__( 'Absolute', 'element-camp' ),
                    'relative' => esc_html__( 'Relative', 'element-camp' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-throwable-content .item .icon-wrapper' => 'position: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_wrapper_offset_orientation_h',
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
                    'position_for_icon_wrapper!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_wrapper_offset_x',
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
                    '{{WRAPPER}} .tcgelements-throwable-content .item .icon-wrapper' => 'left: {{SIZE}}{{UNIT}};right:auto',
                ],
                'condition' => [
                    'icon_wrapper_offset_orientation_h' => 'start',
                    'position_for_icon_wrapper!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_wrapper_offset_x_end',
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
                    '{{WRAPPER}} .tcgelements-throwable-content .item .icon-wrapper' => 'right: {{SIZE}}{{UNIT}};left:auto',
                ],
                'condition' => [
                    'icon_wrapper_offset_orientation_h' => 'end',
                    'position_for_icon_wrapper!' => '',
                ],
            ]
        );
        $this->add_control(
            'icon_wrapper_offset_orientation_v',
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
                    'position_for_icon_wrapper!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_wrapper_offset_y',
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
                    '{{WRAPPER}} .tcgelements-throwable-content .item .icon-wrapper' => 'top: {{SIZE}}{{UNIT}};bottom:auto',
                ],
                'condition' => [
                    'icon_wrapper_offset_orientation_v' => 'start',
                    'position_for_icon_wrapper!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_wrapper_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-throwable-content .item .icon-wrapper' => 'bottom: {{SIZE}}{{UNIT}};top:auto;',
                ],
                'condition' => [
                    'icon_wrapper_offset_orientation_v' => 'end',
                    'position_for_icon_wrapper!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_wrapper_display',
            [
                'label' => esc_html__('Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'inline-block',
                'options' => [
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'inline-flex' => esc_html__('Inline Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-throwable-content .item .icon-wrapper' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'icon_wrapper_justify_content',
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
                    '{{WRAPPER}} .tcgelements-throwable-content .item .icon-wrapper' => 'justify-content: {{VALUE}};',
                ],
                'condition'=> [ 'icon_wrapper_display'=> ['flex','inline-flex'] ],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'icon_wrapper_align_items',
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
                    '{{WRAPPER}} .tcgelements-throwable-content .item .icon-wrapper' => 'align-items: {{VALUE}};',
                ],
                'condition'=> [ 'icon_wrapper_display'=> ['flex','inline-flex'] ],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'icon_wrapper_width',
            [
                'label' => esc_html__('Icon Wrapper Width', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-throwable-content .item .icon-wrapper' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_wrapper_height',
            [
                'label' => esc_html__('Icon Wrapper Height', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-throwable-content .item .icon-wrapper' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_wrapper_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-throwable-content .item .icon-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; width: calc(100% - ({{RIGHT}}{{UNIT}} + {{LEFT}}{{UNIT}}));',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'icon_wrapper_background',
                'label' => esc_html__( 'Icon Background', 'element-camp' ),
                'types' => [ 'classic','gradient' ],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .tcgelements-throwable-content .item .icon-wrapper',
            ]
        );
        $this->add_responsive_control(
            'icon_wrapper_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-throwable-content .item .icon-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'icon_options',
            [
                'label' => esc_html__( 'Icon Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__('Icon Size', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 18,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-throwable-content .item .icon-wrapper i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-throwable-content .item .icon-wrapper svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__('Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-throwable-content .item .icon-wrapper i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-throwable-content .item .icon-wrapper svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();
    }
    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="tcgelements-throwable-content" data-tp-throwable-scene="true">
            <?php foreach($settings['items'] as $item) : ?>
                <div class="item <?php echo 'elementor-repeater-item-' . esc_attr( $item['_id'] ) . ''; ?>" data-tp-throwable-el="">
                        <?php
                        $icon_position = !empty($item['icon_position']) ? $item['icon_position'] : 'before';
                        $has_icon = !empty($item['icon']['value']);
                        ?>
                        <?php if ($icon_position === 'before' && $has_icon) : ?>
                            <span class="icon-wrapper">
                                <?php Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true']); ?>
                            </span>
                        <?php endif; ?>
                        <?php if (!empty($item['text'])) : ?>
                            <span class="txt"> <?=esc_html($item['text'])?> </span>
                        <?php endif;?>
                        <?php if ($icon_position === 'after' && $has_icon) : ?>
                            <span class="icon-wrapper">
                                <?php Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true']); ?>
                            </span>
                        <?php endif; ?>
                        <?php if (!empty($item['image']['url'])) : ?>
                            <img class="img" src="<?= esc_url($item['image']['url']); ?>" alt="<?php if (!empty($item['image']['alt'])) echo esc_attr($item['image']['alt']); ?>" >
                        <?php endif;?>
                </div>
            <?php endforeach;?>
        </div>
        <?php
    }

}
