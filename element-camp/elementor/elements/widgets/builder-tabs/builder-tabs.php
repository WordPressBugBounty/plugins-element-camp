<?php
namespace ElementCampPlugin\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Utils;
use Elementor\Group_Control_Border;
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

class ElementCamp_Builder_Tabs extends Widget_Base
{
    public function get_name()
    {
        return 'Tcgelements-builder-tabs';
    }

    public function get_title()
    {
        return esc_html__('Builder Tabs', 'element-camp');
    }

    public function get_icon()
    {
        return 'eicon-tabs tce-widget-badge';
    }
    public function get_script_depends() {
        return ['bootstrap.bundle.min'];
    }
    /**
     * Retrieve the list of categories the widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * Note that currently Elementor supports only one category.
     * When multiple categories passed, Elementor uses the first one.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories()
    {
        return ['elementcamp-elements'];
    }

    protected function _register_controls()
    {
        $this->start_controls_section(
            'section_items',
            [
                'label' => esc_html__('Tabs Items', 'element-camp'),
            ]
        );
        $repeater = new \Elementor\Repeater();
        $repeater->add_control(
            'nav_tab',
            [
                'label' => esc_html__('Tab Title', 'element-camp'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Elementor Builder', 'element-camp'),
                'description' => esc_html__('You can use <small></small> tag', 'element-camp'),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'nav_tab_icon',
            [
                'label' => esc_html__('Icon', 'element-camp'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'skin' => 'inline',
                'label_block' => false,
            ]
        );
        $this->add_control(
            'nav_tabs',
            [
                'label' => esc_html__('Nav Tabs', 'element-camp'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ nav_tab }}}',
                'default' => [
                    [
                        'nav_tab' => esc_html__('Elementor Builder', 'element-camp'),
                    ],
                    [
                        'nav_tab' => esc_html__('Header Builder', 'element-camp'),
                    ],
                    [
                        'nav_tab' => esc_html__('Footer Builder', 'element-camp'),
                    ],
                ],
            ]
        );
        $repeater2 = new \Elementor\Repeater();
        $repeater2->add_control(
            'sub_title',
            [
                'label' => esc_html__('Sub Title', 'element-camp'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Elementor Builder', 'element-camp'),
                'label_block' => true,
            ]
        );
        $repeater2->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'element-camp'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Elementor Builder', 'element-camp'),
                'label_block' => true,
            ]
        );
        $repeater2->add_control(
            'description',
            [
                'label' => esc_html__('Description', 'element-camp'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => esc_html__('No coding required to create beautiful websites. The live editor has everything you need to build your website.', 'element-camp'),
                'label_block' => true,
            ]
        );
        $repeater2->add_control(
            'image',
            [
                'label' => esc_html__('Image', 'element-camp'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ]
            ]
        );
        $repeater2->add_control(
            'body',
            [
                'label' => '',
                'type' => Controls_Manager::WYSIWYG,
                'default' => '<p>' . esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'element-camp' ) . '</p>',
            ]
        );
        $repeater2->add_control(
            'body_icon',
            [
                'label' => esc_html__('Body Icon', 'element-camp'),
                'description' => esc_html__('Works If There\'s li in the body "<li></li>"', 'element-camp'),
                'type' => Controls_Manager::MEDIA,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs {{CURRENT_ITEM}} .body li::before' => 'background-image: url({{URL}})',
                ],
            ]
        );
        $this->add_control(
            'nav_content',
            [
                'label' => esc_html__('Nav Content', 'element-camp'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater2->get_controls(),
                'title_field' => '{{{ title }}}',
                'default' => [
                    [
                        'sub_title' => esc_html__('Elementor Builder Sub Title', 'element-camp'),
                        'title' => esc_html__('Elementor Builder Title', 'element-camp'),
                        'body' => esc_html__('Elementor Builder Body', 'element-camp'),
                    ],
                    [
                        'sub_title' => esc_html__('Header Builder Sub Title', 'element-camp'),
                        'title' => esc_html__('Header Builder Title', 'element-camp'),
                        'body' => esc_html__('Header Builder Body', 'element-camp'),
                    ],
                    [
                        'sub_title' => esc_html__('Footer Builder Sub Title', 'element-camp'),
                        'title' => esc_html__('Footer Builder Title', 'element-camp'),
                        'body' => esc_html__('Footer Builder Body', 'element-camp'),
                    ],
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'layout_section',
            [
                'label' => esc_html__('Layout', 'element-camp'),
            ]
        );

        $this->add_responsive_control(
            'image_columns',
            [
                'label' => esc_html__('Image Column Width', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => '6',
                'tablet_default' => '12',
                'mobile_default' => '12',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                    '9' => '9',
                    '10' => '10',
                    '11' => '11',
                    '12' => '12',
                ],
            ]
        );

        $this->add_responsive_control(
            'info_columns',
            [
                'label' => esc_html__('Info Column Width', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => '6',
                'tablet_default' => '12',
                'mobile_default' => '12',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                    '9' => '9',
                    '10' => '10',
                    '11' => '11',
                    '12' => '12',
                ],
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'widget_style',
            [
                'label' => esc_html__('Widget Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'widget_container_display',
            [
                'label' => esc_html__('Wrapper Display', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'block',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'widget_wrapper_display_position',
            [
                'label' => esc_html__( 'Title Position', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'before' => [
                        'title' => esc_html__( 'Before', 'element-camp' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'after' => [
                        'title' => esc_html__( 'After', 'element-camp' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                    'start' => [
                        'title' => esc_html__( 'Start', 'element-camp' ),
                        'icon' => "eicon-h-align-right",
                    ],
                    'end' => [
                        'title' => esc_html__( 'End', 'element-camp' ),
                        'icon' => "eicon-h-align-left",
                    ],
                ],
                'selectors_dictionary' => [
                    'before' => 'flex-direction: column;',
                    'after' => 'flex-direction: column-reverse;',
                    'start' => 'flex-direction: row;',
                    'end' => 'flex-direction: row-reverse;',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs' => '{{VALUE}}',
                ],
                'condition' => [ 'widget_container_display' => 'flex' ],
            ]
        );
        $this->add_responsive_control(
            'widget_wrapper_justify_content',
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
                    '{{WRAPPER}} .tcgelements-builder-tabs' => 'justify-content: {{VALUE}};',
                ],
                'condition'=> [ 'widget_container_display' => 'flex' ],
                'responsive' => true,
            ]);
        $this->add_responsive_control(
            'widget_wrapper_align_items',
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
                    '{{WRAPPER}} .tcgelements-builder-tabs' => 'align-items: {{VALUE}};',
                ],
                'condition'=> [ 'widget_container_display' => 'flex' ],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'widget_wrapper_flex_wrap',
            [
                'label' => esc_html__( 'Wrap', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'nowrap' => [
                        'title' => esc_html__( 'No Wrap', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-nowrap',
                    ],
                    'wrap' => [
                        'title' => esc_html__( 'Wrap', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-wrap',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs' => 'flex-wrap: {{VALUE}};',
                ],
                'condition'=>[
                    'widget_container_display'=>['flex','inline-flex']
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'tab_style',
            [
                'label' => esc_html__('Tab', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'nav_heading',
            [
                'label' => esc_html__( 'Nav Pills', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );
        $this->add_responsive_control(
            'nav_display',
            [
                'label' => esc_html__('Nav Display', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'flex',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .nav-pills' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'nav_justify_content',
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
                    '{{WRAPPER}} .tcgelements-builder-tabs .nav-pills' => 'justify-content: {{VALUE}};',
                ],
                'condition'=> [ 'nav_display' => 'flex' ],
                'responsive' => true,
            ]);
        $this->add_responsive_control(
            'nav_align_items',
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
                    '{{WRAPPER}} .tcgelements-builder-tabs .nav-pills' => 'align-items: {{VALUE}};',
                ],
                'condition'=> [ 'nav_display' => 'flex' ],
                'responsive' => true,
            ]);
        $this->add_responsive_control(
            'nav_flex_gaps_x',
            [
                'label' => esc_html__( 'Gaps (X)', 'element-camp' ),
                'condition' => [ 'nav_display' => 'flex' ],
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%', 'px', 'vw', 'custom' ],
            ]
        );
        $this->add_responsive_control(
            'nav_flex_gaps_y',
            [
                'label' => esc_html__( 'Gaps (Y)', 'element-camp' ),
                'condition' => [ 'nav_display' => 'flex' ],
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%', 'px', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .nav-pills' => 'gap: {{nav_flex_gaps_x.SIZE}}{{nav_flex_gaps_x.UNIT}} {{nav_flex_gaps_y.SIZE}}{{nav_flex_gaps_y.UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'nav_width',
            [
                'label' => esc_html__( 'Nav Width', 'element-camp' ),
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
                'size_units' => [ '%', 'px', 'vw', 'custom' ],
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
                    '{{WRAPPER}} .tcgelements-builder-tabs .nav-pills' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'nav_margin',
            [
                'label' => esc_html__('Nav Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .nav-pills' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'nav_padding',
            [
                'label' => esc_html__('Nav Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .nav-pills' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'nav_background',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-builder-tabs .nav-pills',
            ]
        );
        $this->add_control(
            'nav_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .nav-pills' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'nav_box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} .tcgelements-builder-tabs .nav-pills',
            ]
        );
        $this->add_control(
            'nav_item_heading',
            [
                'label' => esc_html__( 'Nav Item Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'nav_item_width',
            [
                'label' => esc_html__( 'Nav Item Width', 'element-camp' ),
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
                'size_units' => [ '%', 'px', 'vw', 'custom' ],
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
                    '{{WRAPPER}} .tcgelements-builder-tabs .nav-pills .nav-item' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'nav_link_width',
            [
                'label' => esc_html__( 'Nav Link Width', 'element-camp' ),
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
                'size_units' => [ '%', 'px', 'vw', 'custom' ],
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
                    '{{WRAPPER}} .tcgelements-builder-tabs .nav-pills .nav-link' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'nav_link_display',
            [
                'label' => esc_html__('Nav Link Display', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'block',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .nav-pills .nav-link' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'nav_link_justify_content',
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
                    '{{WRAPPER}} .tcgelements-builder-tabs .nav-pills .nav-link' => 'justify-content: {{VALUE}};',
                ],
                'condition'=> [ 'nav_link_display' => 'flex' ],
                'responsive' => true,
            ]);
        $this->add_responsive_control(
            'nav_link_align_items',
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
                    '{{WRAPPER}} .tcgelements-builder-tabs .nav-pills .nav-link' => 'align-items: {{VALUE}};',
                ],
                'condition'=> [ 'nav_link_display' => 'flex' ],
                'responsive' => true,
            ]);
        $this->add_responsive_control(
            'tab_link_margin',
            [
                'label' => esc_html__('Tab Item Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .nav-pills .nav-item .nav-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'tab_link_padding',
            [
                'label' => esc_html__('Tab Item Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .nav-pills .nav-item .nav-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tab_link_typography',
                'label' => esc_html__('Tab Item', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-builder-tabs .nav-pills .nav-item .nav-link',
            ]
        );
        $this->add_control(
            'tab_link_border_radius',
            [
                'label' => esc_html__('Tab Item Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'separator'=>'after',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .nav-pills .nav-item .nav-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tab_link_box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}}  .tcgelements-builder-tabs .nav-pills .nav-item .nav-link',
            ]
        );
        $this->add_responsive_control(
            'tab_link_icon_margin',
            [
                'label' => esc_html__('Icon Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .nav-pills .nav-item .nav-link i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-builder-tabs .nav-pills .nav-item .nav-link svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'tab_link_icon_size',
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
                    '{{WRAPPER}} .tcgelements-builder-tabs .nav-pills .nav-item .nav-link i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-builder-tabs .nav-pills .nav-item .nav-link svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs(
            'tab_item_tabs',
        );

        $this->start_controls_tab(
            'Normal',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );

        $this->add_control(
            'tab_link_color',
            [
                'label' => esc_html__( 'Tab Item Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .nav-pills .nav-item .nav-link' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'tab_icon_color',
            [
                'label' => esc_html__( 'Tab Icon Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .nav-pills .nav-item .nav-link i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-builder-tabs .nav-pills .nav-item .nav-link svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'tab_link_background',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-builder-tabs .nav-pills .nav-item .nav-link',
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'Active',
            [
                'label'   => esc_html__( 'Active', 'element-camp' ),
            ]
        );
        $this->add_control(
            'active_tab_link_color',
            [
                'label' => esc_html__( 'Active Tab Item Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .nav-pills .nav-item .nav-link.active' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'active_tab_icon_color',
            [
                'label' => esc_html__( 'Active Tab Icon Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .nav-pills .nav-item .nav-link.active i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-builder-tabs .nav-pills .nav-item .nav-link.active svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'active_tab_icon_rotate',
            [
                'label' => esc_html__( 'Active Tab Icon Rotate', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => -360,
                        'max' => 360,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .nav-pills .nav-item .nav-link.active i' => 'transform: rotate({{SIZE}}deg);',
                    '{{WRAPPER}} .tcgelements-builder-tabs .nav-pills .nav-item .nav-link.active svg' => 'transform: rotate({{SIZE}}deg);',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'active_tab_link_background',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-builder-tabs .nav-pills .nav-item .nav-link.active',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control(
            'tab_link_small_heading',
            [
                'label' => esc_html__('Small Style', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'tab_link_small_margin',
            [
                'label' => esc_html__('Small Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .nav-pills .nav-item .nav-link small' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'tab_link_small_padding',
            [
                'label' => esc_html__('Small Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .nav-pills .nav-item .nav-link small' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tab_link_small_typography',
                'selector' => '{{WRAPPER}} .tcgelements-builder-tabs .nav-pills .nav-item .nav-link small',
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'content_style',
            [
                'label' => esc_html__('Content', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'content_justify_content',
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
                    '{{WRAPPER}} .tcgelements-builder-tabs .tab-content .builder-content .row' => 'justify-content: {{VALUE}} !important;',
                ],
                'responsive' => true,
            ]);
        $this->add_responsive_control(
            'content_align_items',
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
                    '{{WRAPPER}} .tcgelements-builder-tabs .tab-content .builder-content .row' => 'align-items: {{VALUE}} !important;',

                ],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'content_padding',
            [
                'label' => esc_html__('Tab Content Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .tab-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
//        #539D5622
        $this->add_responsive_control(
            'content_border_radius',
            [
                'label' => esc_html__( 'Content Border Radius', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .tab-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'content_background',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-builder-tabs .tab-content',
            ]
        );
        $this->add_responsive_control(
            'content_width',
            [
                'label' => esc_html__( 'Tab Content Width', 'element-camp' ),
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
                'size_units' => [ '%', 'px', 'vw', 'custom' ],
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
                    '{{WRAPPER}} .tcgelements-builder-tabs .tab-content' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'info_container_padding',
            [
                'label' => esc_html__('Info Container Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .builder-content .info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'info_container_margin',
            [
                'label' => esc_html__('Info Container Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .builder-content .info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'info_container_border_radius',
            [
                'label' => esc_html__('Info Container Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .builder-content .info' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'info_container_background',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-builder-tabs .builder-content .info',
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'sub_title_typography',
                'label' => esc_html__('Sub Title', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-builder-tabs .builder-content .sub-title',
            ]
        );
        $this->add_control(
            'sub_title_color',
            [
                'label' => esc_html__( 'Sub Title Color', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .builder-content .sub-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'sub_title_margin',
            [
                'label' => esc_html__('Sub Title Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'separator'=>'after',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .builder-content .sub-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__('Title', 'element-camp'),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_ACCENT,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-builder-tabs .builder-content .title',
            ]
        );
        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .builder-content .title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'title_background_clip_text',
            [
                'label' => esc_html__('Background Clip Text', 'element-camp'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'description' => esc_html__( 'This will apply text-fill-color transparent to the text, creating a background-clip effect.', 'element-camp' ),
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .builder-content .title' => 'background-clip: text;-webkit-text-fill-color: transparent;',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'title_background_color',
                'types' => [ 'classic','gradient','tcg_gradient', 'tcg_gradient_4' ],
                'selector' => '{{WRAPPER}} .tcgelements-builder-tabs .builder-content .title',
            ]
        );
        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Title Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'separator'=> 'after',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .builder-content .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'label' => esc_html__('Description', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-builder-tabs .builder-content .description',
            ]
        );
        $this->add_control(
            'description_color',
            [
                'label' => esc_html__( 'Description Color', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .builder-content .description' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'desc_margin',
            [
                'label' => esc_html__('Description Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'separator'=>'after',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .builder-content .description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'body_typography',
                'label' => esc_html__('Body', 'element-camp'),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_ACCENT,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-builder-tabs .builder-content .body , {{WRAPPER}} .tcgelements-builder-tabs .builder-content .body > *',
            ]
        );
        $this->add_control(
            'body_color',
            [
                'label' => esc_html__( 'Body Color', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .builder-content .body' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-builder-tabs .builder-content .body > *' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'body_icon_color',
            [
                'label' => esc_html__( 'Body Icon Color', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .builder-content .body' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-builder-tabs .builder-content .body > *' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'body_margin',
            [
                'label' => esc_html__('Body Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'separator'=>'after',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .builder-content .body' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'list_items_style',
            [
                'label' => esc_html__('List Items Style', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'list_item_text_typography',
                'label' => esc_html__('List Item Text', 'element-camp'),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_ACCENT,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-builder-tabs .builder-content .body ul li',
            ]
        );
        $this->add_control(
            'list_item_text_color',
            [
                'label' => esc_html__( 'List Item Text Color', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .builder-content .body ul li' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'list_item_icon_size',
            [
                'label' => esc_html__('List Item Icon size', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'custom', 'rem', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .builder-content .body ul li::before' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-builder-tabs .builder-content .body ul li::before' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'image_style',
            [
                'label' => esc_html__('Image Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );
        $this->add_control(
            'image_div_order',
            [
                'label' => esc_html__('Image Order', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '0',
                'options' => [
                    '0' => esc_html__('First', 'element-camp'),
                    '1' => esc_html__('Second', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .tab-content .builder-content > .row > div:first-child' => 'order: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'image_container_heading',
            [
                'label' => esc_html__('Image Container Style', 'element-camp'),
                'type' => Controls_Manager::HEADING
            ]
        );
        $this->add_control(
            'img_container_overflow',
            [
                'label' => esc_html__( 'Image Container Overflow Hidden', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'yes', 'element-camp' ),
                'label_off' => esc_html__( 'No', 'element-camp' ),
                'default' => esc_html__( 'yes', 'element-camp' ),
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .tab-content .builder-content .img' => 'overflow: hidden;',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_container_margin',
            [
                'label' => esc_html__('Image Container Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .tab-content .builder-content .img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_container_padding',
            [
                'label' => esc_html__('Image Container Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .tab-content .builder-content .img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'img_container_width',
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
                    '{{WRAPPER}} .tcgelements-builder-tabs .tab-content .builder-content .img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'img_container_height',
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
                    '{{WRAPPER}} .tcgelements-builder-tabs .tab-content .builder-content .img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_container_border',
                'selector' => '{{WRAPPER}} .tcgelements-builder-tabs .tab-content .builder-content .img',
            ]
        );
        $this->add_responsive_control(
            'img_container_border_radius',
            [
                'label' => esc_html__( 'Image Container Border Radius', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .tab-content .builder-content .img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'image_heading',
            [
                'label' => esc_html__('Image Style', 'element-camp'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_responsive_control(
            'image_margin',
            [
                'label' => esc_html__('Image Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .tab-content .builder-content .img img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_padding',
            [
                'label' => esc_html__('Image Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .tab-content .builder-content .img img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
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
                    '{{WRAPPER}} .tcgelements-builder-tabs .tab-content .builder-content .img img' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-builder-tabs .tab-content .builder-content .img img' => 'height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-builder-tabs .tab-content .builder-content .img img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'object-position',
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
                    '{{WRAPPER}} .tcgelements-builder-tabs .tab-content .builder-content .img img' => 'object-position: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .tcgelements-builder-tabs .tab-content .builder-content .img img',
            ]
        );

        $this->add_responsive_control(
            'img_border_radius',
            [
                'label' => esc_html__( 'Image Border Radius', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs .tab-content .builder-content .img img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $id = uniqid();
        ?>
        <div class="tcgelements-builder-tabs">
            <ul class="nav nav-pills" id="pills-tab-<?=esc_attr($id)?>" role="tablist">
                <?php foreach ($settings['nav_tabs'] as $index=>$item) : ?>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?php if ($index==0)echo 'active'?>" id="pills-tab<?=$index?>-tab" data-bs-toggle="pill" data-bs-target="#pills-tab<?=$index?>">
                            <?=__($item['nav_tab'])?>
                            <?php if (!empty($item['nav_tab_icon']['value'])) : ?>
                                <?php \Elementor\Icons_Manager::render_icon( $item['nav_tab_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                            <?php endif;?>
                        </button>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="tab-content" id="pills-tabContent-<?=esc_attr($id)?>">
                <?php foreach ($settings['nav_content'] as $index=>$item) :
                    $has_image = !empty($item['image']['url']);
                    $image_col_class = $this->get_column_classes($settings, 'image');
                    $info_col_class = $this->get_column_classes($settings, 'info');
                    ?>
                    <div class="tab-pane fade <?php echo 'elementor-repeater-item-' . esc_attr( $item['_id'] ) . ''; ?> <?php if ($index==0)echo 'active show'?>" id="pills-tab<?=$index?>">
                        <div class="builder-content">
                            <div class="row align-items-center">
                                <?php if ($has_image) : ?>
                                    <div class="<?php echo esc_attr($image_col_class); ?>">
                                        <div class="img">
                                            <img src="<?=esc_url($item['image']['url'])?>" alt="<?php if (!empty($item['image']['alt'])) echo esc_attr($item['image']['alt']); ?>">
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="<?php echo esc_attr($info_col_class); ?>">
                                    <div class="info">
                                        <p class="sub-title"> <?=esc_html($item['sub_title'])?> </p>
                                        <h2 class="title"> <?=esc_html($item['title'])?> </h2>
                                        <div class="description"> <?=esc_html($item['description'])?> </div>
                                        <div class="body"><?=__($item['body'],'element-camp')?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
    /**
     * Get responsive column classes
     *
     * @param array $settings Widget settings
     * @param string $type 'image' or 'info'
     * @return string CSS classes
     */
    private function get_column_classes($settings, $type = 'image') {
        $prefix = $type . '_columns';

        $desktop = !empty($settings[$prefix]) ? (int)$settings[$prefix] : 6;
        $tablet = !empty($settings[$prefix . '_tablet']) ? (int)$settings[$prefix . '_tablet'] : 6;
        $mobile = !empty($settings[$prefix . '_mobile']) ? (int)$settings[$prefix . '_mobile'] : 12;

        $desktop_class = 'col-lg-' . $desktop;
        $tablet_class = 'col-md-' . $tablet;
        $mobile_class = 'col-' . $mobile;

        return "{$desktop_class} {$tablet_class} {$mobile_class}";
    }
}
