<?php

namespace ElementCampPlugin\Widgets;

use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Frontend;
use Elementor\Icons_Manager;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Image_Size;
use Elementor\Scheme_Base;
use Elementor\Group_Control_Background;

if (!defined('ABSPATH')) exit; // Exit if accessed directly



/**
 * @since 1.0.0
 */
class ElementCamp_Portfolio_Box extends Widget_Base
{

    /**
     * Retrieve the widget name.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'tcgelements-portfolio-box';
    }

    public function get_script_depends()
    {
        return ['tcgelements-portfolio-box'];
    }


    /**
     * Retrieve the widget title.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return __('Portfolio Box', 'element-camp');
    }

    /**
     * Retrieve the widget icon.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'eicon-slider-album tce-widget-badge';
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

    /**
     * Register the widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     *
     * @access protected
     */

    protected function register_controls()
    {
        $this->start_controls_section(
            'settings',
            [
                'label' => __('Settings', 'element-camp'),

            ]
        );

        $this->add_control(
            'portfolio_item',
            [
                'label' => __('Item to display', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'default' => '4',
            ]
        );

        $this->add_control(
            'portfolio_offset',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'default' => 0,
                'description' => esc_html__('Number of posts to skip.', 'element-camp'),
            ]
        );

        $this->add_control(
            'meta_separator',
            [
                'label' => esc_html__('Categories Separator', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'default' => ', ',
            ]
        );

        $this->add_control(
            'post_order',
            [
                'label' => __('Orders', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'DESC' => __('Descending', 'element-camp'),
                    'ASC' => __('Ascending', 'element-camp'),
                    'rand' => __('Random', 'element-camp'),
                ],
                'default' => 'DESC',
            ]
        );

        $this->add_control(
            'sort_cat',
            [
                'label' => __('Sort Portfolio by Portfolio Category', 'element-camp'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => __('Yes', 'element-camp'),
                'label_off' => __('No', 'element-camp'),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'blog_cat',
            [
                'label'   => __('Category to Show', 'element-camp'),
                'type'    => Controls_Manager::SELECT2,
                'options' => themescamp_tax_choice(),
                'condition' => [
                    'sort_cat' => 'yes',
                ],
                'multiple'   => 'true',
            ]
        );

        $this->add_control(
            'sort_tag',
            [
                'label' => __('Sort post by Tags', 'element-camp'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => __('Yes', 'element-camp'),
                'label_off' => __('No', 'element-camp'),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'blog_tag',
            [
                'label'   => __('Tags', 'element-camp'),
                'type'    => Controls_Manager::SELECT,
                'options' => themescamp_portfolio_tag_choice(),
                'condition' => [
                    'sort_tag' => 'yes',
                ],
                'multiple'   => 'true',
            ]
        );

        $this->add_control(
            'activation_method',
            [
                'label' => esc_html__('Activation Method', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'click',
                'options' => [
                    'click' => esc_html__('Click (Default)', 'element-camp'),
                    'hover' => esc_html__('Hover', 'element-camp'),
                ],
                'description' => esc_html__('Choose how users interact with portfolio items. Default is click to maintain compatibility.', 'element-camp'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'box_styles',
            [
                'label' => __('Box Styles', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
            'box_display',
            [
                'label' => esc_html__('Box Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'flex',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs' => 'display: {{VALUE}};'
                ]
            ]
        );

        $this->add_responsive_control(
            'box_display_justify_content',
            [
                'label' => esc_html__('Justify Content', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'default' => '',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Start', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-start-h',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-center-h',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('End', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-end-h',
                    ],
                    'space-between' => [
                        'title' => esc_html__('Space Between', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-space-between-h',
                    ],
                    'space-around' => [
                        'title' => esc_html__('Space Around', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-space-around-h',
                    ],
                    'space-evenly' => [
                        'title' => esc_html__('Space Evenly', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-space-evenly-h',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs' => 'justify-content: {{VALUE}};',
                ],
                'condition' => ['box_display' => 'flex'],
                'responsive' => true,
            ]
        );

        $this->add_responsive_control(
            'box_display_align_items',
            [
                'label' => esc_html__('Align Items', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'default' => '',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Start', 'element-camp'),
                        'icon' => 'eicon-flex eicon-align-start-v',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'element-camp'),
                        'icon' => 'eicon-flex eicon-align-center-v',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('End', 'element-camp'),
                        'icon' => 'eicon-flex eicon-align-end-v',
                    ],
                    'stretch' => [
                        'title' => esc_html__('Stretch', 'element-camp'),
                        'icon' => 'eicon-flex eicon-align-stretch-v',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs' => 'align-items: {{VALUE}};',
                ],
                'condition' => ['box_display' => 'flex'],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'box_display_position',
            [
                'label' => esc_html__('Display Position', 'bazario'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'before' => [
                        'title' => esc_html__('Before', 'bazario'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'after' => [
                        'title' => esc_html__('After', 'bazario'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                    'start' => [
                        'title' => esc_html__('Start', 'bazario'),
                        'icon' => "eicon-h-align-right",
                    ],
                    'end' => [
                        'title' => esc_html__('End', 'bazario'),
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
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs' => '{{VALUE}}',
                ],
                'condition' => ['box_display' => 'flex'],
            ]
        );

        $this->add_responsive_control(
            'box_display_flex_wrap',
            [
                'label' => esc_html__('Wrap', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'nowrap' => [
                        'title' => esc_html__('No Wrap', 'element-camp'),
                        'icon' => 'eicon-flex eicon-nowrap',
                    ],
                    'wrap' => [
                        'title' => esc_html__('Wrap', 'element-camp'),
                        'icon' => 'eicon-flex eicon-wrap',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs' => 'flex-wrap: {{VALUE}};',
                ],
                'condition' => ['box_display' => 'flex'],
            ]
        );

        $this->add_responsive_control(
            'box_margin',
            [
                'label' => esc_html__('Box Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'box_padding',
            [
                'label' => esc_html__('Box Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_control(
            'box_border_radius',
            [
                'label' => esc_html__('Box Border Radius', 'tcgbase_plg'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'box_height',
            [
                'label' => esc_html__('Box Height', 'element-camp'),
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
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'box_style_tabs'
        );

        $this->start_controls_tab(
            'box_style_normal_tab',
            [
                'label' => esc_html__('Normal', 'element-camp'),
            ]
        );

        $this->add_responsive_control(
            'box_width',
            [
                'label' => esc_html__('Box Width', 'element-camp'),
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
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab(
            'box_style_active_tab',
            [
                'label' => esc_html__('Active', 'element-camp'),
            ]
        );

        $this->add_responsive_control(
            'active_box_width',
            [
                'label' => esc_html__('Box Width', 'element-camp'),
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
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item.active' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'portfolio_item_styling',
            [
                'label' => __('Portfolio Item', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'item_display',
            [
                'label' => esc_html__('item Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item' => 'display: {{VALUE}};'
                ]
            ]
        );

        $this->add_responsive_control(
            'item_display_justify_content',
            [
                'label' => esc_html__('Justify Content', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'default' => '',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Start', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-start-h',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-center-h',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('End', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-end-h',
                    ],
                    'space-between' => [
                        'title' => esc_html__('Space Between', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-space-between-h',
                    ],
                    'space-around' => [
                        'title' => esc_html__('Space Around', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-space-around-h',
                    ],
                    'space-evenly' => [
                        'title' => esc_html__('Space Evenly', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-space-evenly-h',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item' => 'justify-content: {{VALUE}};',
                ],
                'condition' => ['item_display' => 'flex'],
                'responsive' => true,
            ]
        );

        $this->add_responsive_control(
            'item_display_align_items',
            [
                'label' => esc_html__('Align Items', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'default' => '',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Start', 'element-camp'),
                        'icon' => 'eicon-flex eicon-align-start-v',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'element-camp'),
                        'icon' => 'eicon-flex eicon-align-center-v',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('End', 'element-camp'),
                        'icon' => 'eicon-flex eicon-align-end-v',
                    ],
                    'stretch' => [
                        'title' => esc_html__('Stretch', 'element-camp'),
                        'icon' => 'eicon-flex eicon-align-stretch-v',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item' => 'align-items: {{VALUE}};',
                ],
                'condition' => ['item_display' => 'flex'],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'item_display_position',
            [
                'label' => esc_html__('Display Position', 'bazario'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'before' => [
                        'title' => esc_html__('Before', 'bazario'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'after' => [
                        'title' => esc_html__('After', 'bazario'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                    'start' => [
                        'title' => esc_html__('Start', 'bazario'),
                        'icon' => "eicon-h-align-right",
                    ],
                    'end' => [
                        'title' => esc_html__('End', 'bazario'),
                        'icon' => "eicon-h-align-left",
                    ],
                ],
                'default' => 'before',
                'selectors_dictionary' => [
                    'before' => 'flex-direction: column;',
                    'after' => 'flex-direction: column-reverse;',
                    'start' => 'flex-direction: row;',
                    'end' => 'flex-direction: row-reverse;',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item' => '{{VALUE}}',
                ],
                'condition' => ['item_display' => 'flex'],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'portfolio_info_styling',
            [
                'label' => __('Portfolio Info', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'info_width',
            [
                'label' => esc_html__( 'Info Width', 'element-camp' ),
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
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'info_display',
            [
                'label' => esc_html__('Info Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'flex',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                    'none' => esc_html__('None', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont' => 'display: {{VALUE}};'
                ]
            ]
        );

        $this->add_responsive_control(
            'info_display_justify_content',
            [
                'label' => esc_html__('Justify Content', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'default' => '',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Start', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-start-h',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-center-h',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('End', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-end-h',
                    ],
                    'space-between' => [
                        'title' => esc_html__('Space Between', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-space-between-h',
                    ],
                    'space-around' => [
                        'title' => esc_html__('Space Around', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-space-around-h',
                    ],
                    'space-evenly' => [
                        'title' => esc_html__('Space Evenly', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-space-evenly-h',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont' => 'justify-content: {{VALUE}};',
                ],
                'condition' => ['info_display' => 'flex'],
                'responsive' => true,
            ]
        );

        $this->add_responsive_control(
            'info_display_align_items',
            [
                'label' => esc_html__('Align Items', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'default' => '',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Start', 'element-camp'),
                        'icon' => 'eicon-flex eicon-align-start-v',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'element-camp'),
                        'icon' => 'eicon-flex eicon-align-center-v',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('End', 'element-camp'),
                        'icon' => 'eicon-flex eicon-align-end-v',
                    ],
                    'stretch' => [
                        'title' => esc_html__('Stretch', 'element-camp'),
                        'icon' => 'eicon-flex eicon-align-stretch-v',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont' => 'align-items: {{VALUE}};',
                ],
                'condition' => ['info_display' => 'flex'],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'info_display_position',
            [
                'label' => esc_html__('Display Position', 'bazario'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'before' => [
                        'title' => esc_html__('Before', 'bazario'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'after' => [
                        'title' => esc_html__('After', 'bazario'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                    'start' => [
                        'title' => esc_html__('Start', 'bazario'),
                        'icon' => "eicon-h-align-right",
                    ],
                    'end' => [
                        'title' => esc_html__('End', 'bazario'),
                        'icon' => "eicon-h-align-left",
                    ],
                ],
                'default' => 'before',
                'selectors_dictionary' => [
                    'before' => 'flex-direction: column;',
                    'after' => 'flex-direction: column-reverse;',
                    'start' => 'flex-direction: row;',
                    'end' => 'flex-direction: row-reverse;',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont' => '{{VALUE}}',
                ],
                'condition' => ['info_display' => 'flex'],
            ]
        );

        $this->add_responsive_control(
            'info_display_flex_wrap',
            [
                'label' => esc_html__('Wrap', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'nowrap' => [
                        'title' => esc_html__('No Wrap', 'element-camp'),
                        'icon' => 'eicon-flex eicon-nowrap',
                    ],
                    'wrap' => [
                        'title' => esc_html__('Wrap', 'element-camp'),
                        'icon' => 'eicon-flex eicon-wrap',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont' => 'flex-wrap: {{VALUE}};',
                ],
                'condition' => ['info_display' => 'flex'],
            ]
        );

        $this->add_responsive_control(
            'item_info_padding',
            [
                'label' => __('Info Padding', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'item_info_margin',
            [
                'label' => __('Info Margin', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_info_border_radius',
            [
                'label' => esc_html__('Info Border Radius', 'themescamp-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'position_for_info',
            [
                'label' => esc_html__( 'Position', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'absolute',
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'unset' => esc_html__( 'unset', 'element-camp' ),
                    'absolute' => esc_html__( 'Absolute', 'element-camp' ),
                    'relative' => esc_html__( 'Relative', 'element-camp' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont' => 'position: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $start = is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');
        $end = !is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');

        $this->add_control(
            'info_offset_orientation_h',
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
            ]
        );

        $this->add_responsive_control(
            'info_offset_x',
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
                    'body:not(.rtl) {{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'info_offset_orientation_h!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'info_offset_x_end',
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
                    'body:not(.rtl) {{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'info_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_control(
            'info_offset_orientation_v',
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
            ]
        );

        $this->add_responsive_control(
            'info_offset_y',
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
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'info_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'info_offset_y_end',
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
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'info_offset_orientation_v' => 'end',
                ],
            ]
        );

        $this->add_control(
            'info_transform_translate_popover',
            [
                'label' => esc_html__('Transform Options', 'element-camp'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'element-camp'),
                'label_on' => esc_html__('Custom', 'element-camp'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );
        $this->start_popover();
        $this->add_control(
            'info_transform_translateX_effect',
            [
                'label' => esc_html__('Translate X', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont' => '--e-transform-tcg-portfolio-box-work-boxs-item-cont-translateX: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_control(
            'info_transform_translateY_effect',
            [
                'label' => esc_html__('Translate Y', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont' => '--e-transform-tcg-portfolio-box-work-boxs-item-cont-translateY: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->end_popover();
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'info_item_background_color',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont',
            ]
        );
        $this->add_control(
            'info_item_opacity',
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
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'title_style',
            [
                'label' => esc_html__('Title Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'item_title_typography',
                'selector' => '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont .title a',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'item_title__background_color',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont .title',
            ]
        );
        $this->add_control(
            'item_title_color',
            [
                'label' => esc_html__('Title Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont .title a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'item_title_padding',
            [
                'label' => esc_html__('Title Padding', 'themescamp-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont .title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );
        $this->add_responsive_control(
            'item_title_margin',
            [
                'label' => esc_html__('Title Margin', 'themescamp-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );
        $this->add_responsive_control(
            'item_title_border_radius',
            [
                'label' => esc_html__('Title Border Radius', 'themescamp-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont .title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'item_title_dark_mode',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'item_title_color_dark_mode',
            [
                'label' => esc_html__('Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont .title a' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont .title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'info_title_tabs',
        );
        $this->start_controls_tab(
            'info_title_normal_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_control(
            'info_title_opacity',
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
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont .title' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'info_title_transform_translate_popover',
            [
                'label' => esc_html__('Transform Options', 'element-camp'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'element-camp'),
                'label_on' => esc_html__('Custom', 'element-camp'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );
        $this->start_popover();
        $this->add_control(
            'info_title_transform_translateX_effect',
            [
                'label' => esc_html__('Translate X', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont .title' => '--e-transform-tcg-portfolio-box-work-boxs-item-title-translateX: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_control(
            'info_title_transform_translateY_effect',
            [
                'label' => esc_html__('Translate Y', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont .title' => '--e-transform-tcg-portfolio-box-work-boxs-item-title-translateY: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->end_popover();
        $this->end_controls_tab();

        $this->start_controls_tab(
            'info_title_active_tab',
            [
                'label'   => esc_html__( 'Active', 'element-camp' ),
            ]
        );
        $this->add_control(
            'info_title_transform_translate_popover_active',
            [
                'label' => esc_html__('Transform Options', 'element-camp'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'element-camp'),
                'label_on' => esc_html__('Custom', 'element-camp'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );
        $this->start_popover();
        $this->add_control(
            'info_title_transform_translateX_effect_active',
            [
                'label' => esc_html__('Translate X', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item.active .cont .title' => '--e-transform-tcg-portfolio-box-work-boxs-item-title-translateX: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_control(
            'info_title_transform_translateY_effect_active',
            [
                'label' => esc_html__('Translate Y', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item.active .cont .title' => '--e-transform-tcg-portfolio-box-work-boxs-item-title-translateY: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->end_popover();
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        $this->start_controls_section(
            'portfolio_category_styling',
            [
                'label' => __('Portfolio Category', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'item_category_typography',
                'selector' => '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont .sub-title a',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'category_background_color',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont .sub-title',
            ]
        );

        $this->add_control(
            'item_category_color',
            [
                'label' => esc_html__('Category Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont .sub-title a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont .sub-title' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'item_category_margin',
            [
                'label' => esc_html__('Category Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont .sub-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_category_padding',
            [
                'label' => esc_html__('Category Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont .sub-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_category_border_radius',
            [
                'label' => esc_html__('Category Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont .sub-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'item_tag_dark_mode',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'item_tag_color_dark_mode',
            [
                'label' => esc_html__('Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont .sub-title a' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont .sub-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'info_tag_tabs',
        );
        $this->start_controls_tab(
            'info_tag_normal_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_control(
            'info_tag_transform_translate_popover',
            [
                'label' => esc_html__('Transform Options', 'element-camp'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'element-camp'),
                'label_on' => esc_html__('Custom', 'element-camp'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );
        $this->start_popover();
        $this->add_control(
            'info_tag_transform_translateX_effect',
            [
                'label' => esc_html__('Translate X', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont .sub-title' => '--e-transform-tcg-portfolio-box-work-boxs-item-tag-translateX: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_control(
            'info_tag_transform_translateY_effect',
            [
                'label' => esc_html__('Translate Y', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont .sub-title' => '--e-transform-tcg-portfolio-box-work-boxs-item-tag-translateY: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->end_popover();
        $this->end_controls_tab();

        $this->start_controls_tab(
            'info_tag_active_tab',
            [
                'label'   => esc_html__( 'Active', 'element-camp' ),
            ]
        );
        $this->add_control(
            'info_tag_transform_translate_popover_active',
            [
                'label' => esc_html__('Transform Options', 'element-camp'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'element-camp'),
                'label_on' => esc_html__('Custom', 'element-camp'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );
        $this->start_popover();
        $this->add_control(
            'info_tag_transform_translateX_effect_active',
            [
                'label' => esc_html__('Translate X', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item.active .cont .sub-title' => '--e-transform-tcg-portfolio-box-work-boxs-item-tag-translateX: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_control(
            'info_tag_transform_translateY_effect_active',
            [
                'label' => esc_html__('Translate Y', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item.active .cont .sub-title' => '--e-transform-tcg-portfolio-box-work-boxs-item-tag-translateY: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->end_popover();
        $this->add_control(
            'portfolio_category_active_transition_delay',
            [
                'label' => esc_html__( 'Transition Delay (s)', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item .cont .sub-title' => 'transition-delay: {{SIZE}}s;',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'image_style',
            [
                'label' => esc_html__('Image Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_responsive_control(
            'img_width',
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
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item .inner' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'img_height',
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
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item .inner' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'img_object-fit',
            [
                'label' => esc_html__('Object Fit', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'img_height[size]!' => '',
                ],
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'fill' => esc_html__('Fill', 'element-camp'),
                    'cover' => esc_html__('Cover', 'element-camp'),
                    'contain' => esc_html__('Contain', 'element-camp'),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item .portfolio-image' => 'object-fit: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'img_object-position',
            [
                'label' => esc_html__('Object Position', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'center center' => esc_html__('Center Center', 'element-camp'),
                    'center left' => esc_html__('Center Left', 'element-camp'),
                    'center right' => esc_html__('Center Right', 'element-camp'),
                    'top center' => esc_html__('Top Center', 'element-camp'),
                    'top left' => esc_html__('Top Left', 'element-camp'),
                    'top right' => esc_html__('Top Right', 'element-camp'),
                    'bottom center' => esc_html__('Bottom Center', 'element-camp'),
                    'bottom left' => esc_html__('Bottom Left', 'element-camp'),
                    'bottom right' => esc_html__('Bottom Right', 'element-camp'),
                ],
                'default' => 'center center',
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item .portfolio-image' => 'object-position: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcg-portfolio-box .work-boxs .item .portfolio-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
    }

    protected function render()
    {
        global $post;
        $settings = $this->get_settings();

        $tcg_paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

        if ($settings['post_order'] != 'rand') {
            $order_key = 'order';
            $order_val = $settings['post_order'];
        } else {
            $order_key = 'orderby';
            $order_val = 'rand';
        }

        $tax_query = [];
        if ($settings['sort_cat'] == 'yes' && !empty($settings['blog_cat'])) {
            $tax_query[] = [
                'taxonomy' => 'portfolio_category',
                'field'    => 'term_id',
                'terms'    => $settings['blog_cat'],
            ];
        }
        if ($settings['sort_tag'] == 'yes' && !empty($settings['blog_tag'])) {
            $tax_query[] = [
                'taxonomy' => 'porto_tag',
                'field'    => 'term_id',
                'terms'    => $settings['blog_tag'],
            ];
        }

        $query_args = [
            'paged'          => $tcg_paged,
            'posts_per_page' => $settings['portfolio_item'],
            'post_type'      => 'portfolio',
            $order_key       => $order_val,
        ];

        if (!empty($tax_query)) {
            $query_args['tax_query'] = $tax_query;
        }

        $term = get_queried_object();
        if (isset($term->taxonomy)) {
            if ('portfolio_category' == $term->taxonomy) {
                $query_args['tax_query'] = [
                    [
                        'taxonomy' => 'portfolio_category',
                        'field'    => 'slug',
                        'terms'    => $term->slug,
                    ],
                ];
            } elseif ('porto_tag' == $term->taxonomy) {
                $query_args['tax_query'] = [
                    [
                        'taxonomy' => 'porto_tag',
                        'field'    => 'slug',
                        'terms'    => $term->slug,
                    ],
                ];
            }
        }

        if (!empty($settings['portfolio_offset'])) {
            $query_args['offset'] = intval($settings['portfolio_offset']);
        }

        $destudio_work = new \WP_Query($query_args);
        $activation_method = $settings['activation_method'] ?? 'click';

        ?>

        <div class="tcg-portfolio-box" data-activation-method="<?php echo esc_attr($activation_method); ?>">
            <div class="work-boxs">
                <?php
                if ($destudio_work->have_posts()) :
                    while ($destudio_work->have_posts()) : $destudio_work->the_post();

                        $destudio_taxs = wp_get_post_terms(get_the_ID(), 'portfolio_category');
                ?>
                        <div class="item">
                            <div class="inner">
                                <img src="<?php echo esc_url(get_the_post_thumbnail_url()); ?>" alt="image" class="portfolio-image">
                            </div>
                            <div class="cont">
                                <h5 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                                <h6 class="sub-title tag">
                                    <?php
                                    if ($destudio_taxs && !is_wp_error($destudio_taxs)) :
                                        $count = 1;
                                        foreach ($destudio_taxs as $destudio_tax) :
                                            if ($count != 1) echo esc_html($settings['meta_separator']);
                                    ?>
                                            <a href="<?php echo esc_url(get_term_link($destudio_tax)); ?>">
                                                <?php echo esc_html($destudio_tax->name); ?>
                                            </a>
                                    <?php
                                            $count++;
                                        endforeach;
                                    endif;
                                    ?>
                                </h6>
                            </div>
                        </div>
                <?php
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
        </div>
<?php
    }
}
