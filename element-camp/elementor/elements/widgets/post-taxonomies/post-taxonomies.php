<?php
namespace ElementCampPlugin\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use Elementor\Core\Schemes\Typography;
use Elementor\Icons_Manager;


if (!defined('ABSPATH')) exit;

class ElementCamp_Post_Taxonomies extends Widget_Base
{
    public function get_name()
    {
        return 'tcgelements-post-taxonomies';
    }

    public function get_title()
    {
        return esc_html__('Post Taxonomies', 'element-camp');
    }

    public function get_icon()
    {
        return 'eicon-post-list tce-widget-badge';
    }

    public function get_categories()
    {
        return ['elementcamp-elements'];
    }

    protected function _register_controls()
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Post Taxonomies', 'element-camp'),
            ]
        );

        $this->add_control(
            'taxonomy_type',
            [
                'label' => esc_html__('Taxonomy Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'category' => esc_html__('Categories', 'element-camp'),
                    'post_tag' => esc_html__('Tags', 'element-camp'),
                ],
                'default' => 'category',
            ]
        );

        $this->add_control(
            'select_tax',
            [
                'label' => esc_html__('Specific Taxonomies', 'element-camp'),
                'description' => esc_html__('By Default Widget Displays All Taxonomies', 'element-camp'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => esc_html__('Yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'show_post_count',
            [
                'label' => esc_html__('Show Post Count', 'element-camp'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => esc_html__('Yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'number_format',
            [
                'label' => esc_html__('Number Format', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'normal' => esc_html__('Normal', 'element-camp'),
                    'leading_zero' => esc_html__('01 Format', 'element-camp'),
                ],
                'default' => 'normal',
                'condition' => ['show_post_count' => 'yes'],
            ]
        );

        $this->add_control(
            'show_order_count',
            [
                'label' => esc_html__('Show Order Count', 'element-camp'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => esc_html__('Yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'order_count_format',
            [
                'label' => esc_html__('Order Count Format', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'normal' => esc_html__('Normal (1, 2, 3...)', 'element-camp'),
                    'leading_zero' => esc_html__('Leading Zero (01, 02, 03... for < 10)', 'element-camp'),
                ],
                'default' => 'normal',
                'condition' => ['show_order_count' => 'yes'],
            ]
        );

        $this->add_control(
            'order_count_position',
            [
                'label' => esc_html__('Order Count Position', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'before' => esc_html__('Before Text', 'element-camp'),
                    'after' => esc_html__('After Text', 'element-camp'),
                ],
                'default' => 'before',
                'condition' => ['show_order_count' => 'yes'],
            ]
        );

        $this->add_control(
            'categories',
            [
                'label' => esc_html__('Categories', 'element-camp'),
                'type' => Controls_Manager::SELECT2,
                'options' => $this->get_categories_options(),
                'condition' => [
                    'select_tax' => 'yes',
                    'taxonomy_type' => 'category',
                ],
                'multiple' => true,
            ]
        );

        $this->add_control(
            'tags',
            [
                'label' => esc_html__('Tags', 'element-camp'),
                'type' => Controls_Manager::SELECT2,
                'options' => $this->get_tags_options(),
                'condition' => [
                    'select_tax' => 'yes',
                    'taxonomy_type' => 'post_tag',
                ],
                'multiple' => true,
            ]
        );

        $this->add_control(
            'selected_icon',
            [
                'label' => esc_html__('Icon', 'element-camp'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'skin' => 'inline',
                'label_block' => false,
            ]
        );

        $this->add_control(
            'icon_align',
            [
                'label' => esc_html__('Icon Position', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => esc_html__('Before', 'element-camp'),
                    'right' => esc_html__('After', 'element-camp'),
                ],
                'condition' => [
                    'selected_icon[value]!' => '',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'taxonomy_wrapper_style',
            [
                'label' => esc_html('Taxonomies Container Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
            'taxonomy_wrapper_display',
            [
                'label' => esc_html__('Taxonomies Wrapper Display', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'flex',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-post-taxonomies' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'taxonomy_wrapper_display_position',
            [
                'label' => esc_html__( 'Taxonomies', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-post-taxonomies' => '{{VALUE}}',
                ],
                'condition' => [ 'taxonomy_wrapper_display' => 'flex' ],
            ]
        );
        $this->add_responsive_control(
            'taxonomy_wrapper_justify_content',
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
                    '{{WRAPPER}} .tcgelements-post-taxonomies' => 'justify-content: {{VALUE}};',
                ],
                'condition'=> [ 'taxonomy_wrapper_display' => 'flex' ],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'taxonomy_wrapper_align_items',
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
                    '{{WRAPPER}} .tcgelements-post-taxonomies' => 'align-items: {{VALUE}};',
                ],
                'condition'=> [ 'taxonomy_wrapper_display' => 'flex' ],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'taxonomy_wrapper_flex_wrap',
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
                    '{{WRAPPER}} .tcgelements-post-taxonomies' => 'flex-wrap: {{VALUE}};',
                ],
                'condition'=> [ 'taxonomy_wrapper_display' => 'flex' ],
                ]
            );
        $this->end_controls_section();
        $this->start_controls_section(
            'taxonomy_style',
            [
                'label' => esc_html('Taxonomy Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );
        $this->add_control(
            'tax_display',
            [
                'label' => esc_html__('Taxonomy Display', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'inline-block',
                'options' => [
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'block' => esc_html__('Block', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-post-taxonomies a' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'tax_justify_content',
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
                    '{{WRAPPER}} .tcgelements-post-taxonomies a' => 'justify-content: {{VALUE}};',
                ],
                'condition'=> [ 'tax_display' => 'flex' ],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'tax_align_items',
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
                    '{{WRAPPER}} .tcgelements-post-taxonomies a' => 'align-items: {{VALUE}};',
                ],
                'condition'=> [ 'tax_display' => 'flex' ],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'tax_margin',
            [
                'label' => esc_html__('Taxonomy Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em' , 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-post-taxonomies a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'tax_padding',
            [
                'label' => esc_html__('Taxonomy Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em' , 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-post-taxonomies a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'tax_border_radius',
            [
                'label' => esc_html__('Taxonomy Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'rem', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-post-taxonomies a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after'
            ]
        );
        $this->start_controls_tabs(
            'tax_tabs',
        );
        $this->start_controls_tab(
            'normal',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__('Taxonomy', 'element-camp'),
                'name' => 'link_typography',
                'selector' => '{{WRAPPER}} .tcgelements-post-taxonomies a',
            ]
        );
        $this->add_control(
            'tax_color',
            [
                'label'       => esc_html__( 'Taxonomy Color', 'element-camp' ),
                'type'     => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-post-taxonomies a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .tcgelements-post-taxonomies a svg' => 'fill: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'tax_border',
                'selector' => '{{WRAPPER}} .tcgelements-post-taxonomies a',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'tax_background_color',
                'label' => esc_html__('Taxonomy Background', 'element-camp'),
                'types' => [ 'classic','gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-post-taxonomies a',
            ]
        );
        $this->add_control(
            'tax_style_dark_mode',
            [
                'label' => esc_html__( 'Dark Mode', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'tax_color_dark_mode',
            [
                'label' => esc_html__( 'Taxonomy Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-post-taxonomies a' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-post-taxonomies a' => 'color: {{VALUE}};',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-post-taxonomies a svg' => 'fill: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-post-taxonomies a svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'tax_border_color_dark_mode',
            [
                'label' => esc_html__( 'Border Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-post-taxonomies a' => 'border-color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-post-taxonomies a' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'hover',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__('Taxonomy', 'element-camp'),
                'name' => 'link_hover_typography',
                'selector' => '{{WRAPPER}} .tcgelements-post-taxonomies a:hover',
            ]
        );
        $this->add_control(
            'tax_hover_color',
            [
                'label'       => esc_html__( 'Taxonomy Color', 'element-camp' ),
                'type'     => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-post-taxonomies a:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .tcgelements-post-taxonomies a:hover svg' => 'fill: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'tax_border_hover',
                'selector' => '{{WRAPPER}} .tcgelements-post-taxonomies a:hover',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'tax_background_color_hover',
                'label' => esc_html__('Taxonomy Background', 'element-camp'),
                'types' => [ 'classic','gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-post-taxonomies a:hover',
            ]
        );
        $this->add_control(
            'tax_style_dark_mode_hover',
            [
                'label' => esc_html__( 'Dark Mode', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'tax_color_dark_mode_hover',
            [
                'label' => esc_html__( 'Taxonomy Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-post-taxonomies a:hover' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-post-taxonomies a:hover' => 'color: {{VALUE}};',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-post-taxonomies a:hover svg' => 'fill: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-post-taxonomies a:hover svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'tax_border_color_dark_mode_hover',
            [
                'label' => esc_html__( 'Border Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-post-taxonomies a:hover' => 'border-color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-post-taxonomies a:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'last_item_tab',
            [
                'label'   => esc_html__( 'Last', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'tax_border_last',
                'selector' => '{{WRAPPER}} .tcgelements-post-taxonomies a:last-of-type',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        $this->start_controls_section(
            'counter_style',
            [
                'label' => esc_html('Counter Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['show_post_count'=>'yes'],
            ]
        );
        $this->add_control(
            'counter_display',
            [
                'label' => esc_html__('Counter Display', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'inline-block',
                'options' => [
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'block' => esc_html__('Block', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                    'inline-flex' => esc_html__('Inline Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-post-taxonomies a .count' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'counter_justify_content',
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
                    '{{WRAPPER}} .tcgelements-post-taxonomies a .count' => 'justify-content: {{VALUE}};',
                ],
                'condition'=> [ 'counter_display' => ['flex','inline-flex'] ],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'counter_align_items',
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
                    '{{WRAPPER}} .tcgelements-post-taxonomies a .count' => 'align-items: {{VALUE}};',
                ],
                'condition'=> [ 'counter_display' => ['flex','inline-flex'] ],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'count_width',
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
                    '{{WRAPPER}} .tcgelements-post-taxonomies a .count' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'count_height',
            [
                'label' => esc_html__('Height', 'element-camp'),
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
                'size_units' => ['px', 'vh', '%', 'vw', 'rem', 'custom'],
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
                    '{{WRAPPER}} .tcgelements-post-taxonomies a .count' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'count_padding',
            [
                'label' => esc_html__('Count Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em' , 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-post-taxonomies a .count' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'count_margin',
            [
                'label' => esc_html__('Count Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em' , 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-post-taxonomies a .count' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'count_border_radius',
            [
                'label' => esc_html__('Count Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'rem', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-post-taxonomies a .count' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after'
            ]
        );
        $this->start_controls_tabs(
            'counter_tabs',
        );
        $this->start_controls_tab(
            'counter_normal',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__('Count Typography', 'element-camp'),
                'name' => 'count_typography',
                'selector' => '{{WRAPPER}} .tcgelements-post-taxonomies a .count',
            ]
        );
        $this->add_control(
            'count_color',
            [
                'label'       => esc_html__( 'Count Color', 'element-camp' ),
                'type'     => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-post-taxonomies a .count' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'counter_background_color',
                'label' => esc_html__('Counter Background', 'element-camp'),
                'types' => [ 'classic','gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-post-taxonomies a .count',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'counter_hover',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
            ]
        );
        $this->add_control(
            'count_hover_color',
            [
                'label'       => esc_html__( 'Count Color', 'element-camp' ),
                'type'     => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-post-taxonomies a:hover .count' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__('Count Typography', 'element-camp'),
                'name' => 'count_hover_typography',
                'selector' => '{{WRAPPER}} .tcgelements-post-taxonomies a:hover .count',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'counter_background_color_hover',
                'label' => esc_html__('Counter Background', 'element-camp'),
                'types' => [ 'classic','gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-post-taxonomies a:hover .count',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        $this->start_controls_section(
            'order_counter_style',
            [
                'label' => esc_html('Order Count Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['show_order_count'=>'yes'],
            ]
        );

        $this->add_control(
            'order_counter_display',
            [
                'label' => esc_html__('Order Counter Display', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'inline-block',
                'options' => [
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'block' => esc_html__('Block', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                    'inline-flex' => esc_html__('Inline Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-post-taxonomies a .order-count' => 'display: {{VALUE}};'
                ]
            ]
        );

        $this->add_responsive_control(
            'order_counter_justify_content',
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
                    '{{WRAPPER}} .tcgelements-post-taxonomies a .order-count' => 'justify-content: {{VALUE}};',
                ],
                'condition'=> [ 'order_counter_display' => ['flex','inline-flex'] ],
                'responsive' => true,
            ]
        );

        $this->add_responsive_control(
            'order_counter_align_items',
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
                    '{{WRAPPER}} .tcgelements-post-taxonomies a .order-count' => 'align-items: {{VALUE}};',
                ],
                'condition'=> [ 'order_counter_display' => ['flex','inline-flex'] ],
                'responsive' => true,
            ]
        );

        $this->add_responsive_control(
            'order_count_width',
            [
                'label' => esc_html__('Width', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-post-taxonomies a .order-count' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'order_count_height',
            [
                'label' => esc_html__('Height', 'element-camp'),
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
                'size_units' => ['px', 'vh', '%', 'vw', 'rem', 'custom'],
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
                    '{{WRAPPER}} .tcgelements-post-taxonomies a .order-count' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'order_count_padding',
            [
                'label' => esc_html__('Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em' , 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-post-taxonomies a .order-count' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'order_count_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em' , 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-post-taxonomies a .order-count' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'order_count_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'rem', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-post-taxonomies a .order-count' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after'
            ]
        );
        $this->add_control(
            'order_count_transition',
            [
                'label' => esc_html__('Order Count Transition', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-post-taxonomies a .order-count' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );

        $this->start_controls_tabs(
            'order_counter_tabs',
        );

        $this->start_controls_tab(
            'order_counter_normal',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__('Typography', 'element-camp'),
                'name' => 'order_count_typography',
                'selector' => '{{WRAPPER}} .tcgelements-post-taxonomies a .order-count',
            ]
        );

        $this->add_control(
            'order_count_color',
            [
                'label'       => esc_html__( 'Color', 'element-camp' ),
                'type'     => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-post-taxonomies a .order-count' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'order_counter_background_color',
                'label' => esc_html__('Background', 'element-camp'),
                'types' => [ 'classic','gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-post-taxonomies a .order-count',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'order_count_border',
                'selector' => '{{WRAPPER}} .tcgelements-post-taxonomies a .order-count',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'order_counter_hover',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
            ]
        );

        $this->add_control(
            'order_count_hover_color',
            [
                'label'       => esc_html__( 'Color', 'element-camp' ),
                'type'     => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-post-taxonomies a:hover .order-count' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__('Typography', 'element-camp'),
                'name' => 'order_count_hover_typography',
                'selector' => '{{WRAPPER}} .tcgelements-post-taxonomies a:hover .order-count',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'order_counter_background_color_hover',
                'label' => esc_html__('Background', 'element-camp'),
                'types' => [ 'classic','gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-post-taxonomies a:hover .order-count',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'order_count_border_hover',
                'selector' => '{{WRAPPER}} .tcgelements-post-taxonomies a:hover .order-count',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        $this->start_controls_section(
            'section_icon_style',
            [
                'label' => esc_html__('Icon', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'selected_icon!' => '',
                ]
            ]
        );
        $this->add_control(
            'taxonomy_icon_padding',
            [
                'label' => esc_html__('Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-post-taxonomies a .icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'taxonomy_icon_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em' , 'rem' , 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-post-taxonomies a .icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs('tabs_taxonomy_icon_style');

        $this->start_controls_tab(
            'tab_taxonomy_icon',
            [
                'label' => esc_html__('Normal', 'element-camp'),
            ]
        );
        $this->add_control(
            'taxonomy_icon_color',
            [
                'label' => __('Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-post-taxonomies a .icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-post-taxonomies a .icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_taxonomy_icon_hover',
            [
                'label' => esc_html__('Hover', 'element-camp'),
            ]
        );
        $this->add_control(
            'taxonomy_icon_color_hover',
            [
                'label' => esc_html__('Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-post-taxonomies a:hover .icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-post-taxonomies a:hover .icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $taxonomy_type = $settings['taxonomy_type'];
        $show_specific_taxonomies = $settings['select_tax'] === 'yes';

        if ($show_specific_taxonomies) {
            if ($taxonomy_type == 'category') {
                $selected_taxonomies = $settings['categories'];
            } elseif ($taxonomy_type == 'post_tag') {
                $selected_taxonomies = $settings['tags'];
            }
        } else {
            $selected_taxonomies = array_keys($this->get_taxonomy_options($taxonomy_type));
        }

        $number_format = $settings['number_format'];
        $show_order_count = $settings['show_order_count'] === 'yes';
        $order_count_format = isset($settings['order_count_format']) ? $settings['order_count_format'] : 'normal';
        $order_count_position = isset($settings['order_count_position']) ? $settings['order_count_position'] : 'before';

        $order_index = 1;

        ?>
        <div class="tcgelements-post-taxonomies">
            <?php
            foreach ($selected_taxonomies as $term_id) :
                $term = get_term($term_id, $taxonomy_type);
                if (!is_wp_error($term)) :
                    $term_link = get_term_link($term);
                    $term_name = $term->name;
                    $count = $term->count;

                    // Format post count
                    if ($number_format === 'leading_zero') {
                        $formatted_count = sprintf('%02d', $count);
                    } else {
                        $formatted_count = $count;
                    }

                    // Format order count
                    if ($show_order_count) {
                        if ($order_count_format === 'leading_zero' && $order_index < 10) {
                            $formatted_order_count = sprintf('%02d', $order_index);
                        } else {
                            $formatted_order_count = $order_index;
                        }
                    }
                    ?>
                    <a class="taxonomy" href="<?php echo esc_url($term_link)?>">
                        <?php if ($show_order_count && $order_count_position === 'before') : ?>
                            <span class="order-count"><?php echo esc_html($formatted_order_count) ?></span>
                        <?php endif; ?>

                        <?php if (!empty($settings['selected_icon']['value']) and ($settings['icon_align'] == 'left')) : ?>
                            <span class="icon">
                        <?php Icons_Manager::render_icon($settings['selected_icon'], ['aria-hidden' => 'true']); ?>
                    </span>
                        <?php endif; ?>

                        <span><?php echo esc_html($term_name)?></span>

                        <?php if ($settings['show_post_count'] === 'yes') : ?>
                            <span class="count"><?php echo esc_html($formatted_count) ?></span>
                        <?php endif;?>

                        <?php if (!empty($settings['selected_icon']['value']) and ($settings['icon_align'] == 'right')) : ?>
                            <span class="icon">
                        <?php Icons_Manager::render_icon($settings['selected_icon'], ['aria-hidden' => 'true']); ?>
                    </span>
                        <?php endif; ?>

                        <?php if ($show_order_count && $order_count_position === 'after') : ?>
                            <span class="order-count"><?php echo esc_html($formatted_order_count) ?></span>
                        <?php endif; ?>
                    </a>
                    <?php
                    $order_index++;
                endif;
            endforeach;
            ?>
        </div>
        <?php
    }
    private function get_taxonomy_options($taxonomy_type = 'category')
    {
        $args = array(
            'taxonomy' => $taxonomy_type,
            'hide_empty' => false,
        );

        $terms = get_terms($args);
        $taxonomy_options = [];

        foreach ($terms as $term) {
            $taxonomy_options[$term->term_id] = $term->name;
        }

        return $taxonomy_options;
    }

    private function get_categories_options()
    {
        return $this->get_taxonomy_options('category');
    }

    private function get_tags_options()
    {
        return $this->get_taxonomy_options('post_tag');
    }
}
