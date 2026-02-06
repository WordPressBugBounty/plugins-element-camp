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
use ElementCampPlugin\Elementor\Controls\TCG_Helper as ControlsHelper;

if (!defined('ABSPATH')) exit; // Exit if accessed directly



/**
 * @since 1.0.0
 */
class elementcamp_Interactive_Links_Showcase extends Widget_Base
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
        return 'tcgelements-interactive-links-showcase';
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
        return esc_html__('Interactive Links Showcase', 'element-camp');
    }

    public function get_script_depends()
    {
        return ['tcgelements-interactive-links-showcase'];
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
        return 'eicon-image-before-after tce-widget-badge';
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
    protected function _register_controls()
    {
        $post_types = ControlsHelper::get_post_types();
        $post_types['by_id'] = __('Manual Selection', 'themescamp-plugin');
        $taxonomies = get_taxonomies([], 'objects');

        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Portfolio Settings.', 'element-camp'),
            ]
        );
        $this->add_control(
            'display_terms_type',
            [
                'label' => esc_html__('Display Terms Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'categories' => esc_html__('Categories', 'element-camp'),
                    'tags' => esc_html__('Tags', 'element-camp'),
                ],
                'default' => 'categories',
            ]
        );
        $this->add_control(
            'meta_separator',
            [
                'label' => esc_html__('Categories/tags Separator', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'default' => ' / ',
            ]
        );

        $this->add_control(
            'post_type',
            [
                'label' => esc_html__('Source', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => $post_types,
                'default' => key($post_types),
            ]
        );
        $this->add_control(
            'posts_ids',
            [
                'label' => __('Search & Select', 'themescamp-plugin'),
                'type' => 'tcg-select2',
                'options' => ControlsHelper::get_post_list(),
                'label_block' => true,
                'multiple'    => true,
                'source_name' => 'post_type',
                'source_type' => 'any',
                'condition' => [
                    'post_type' => 'by_id',
                ],
            ]
        );
        $this->add_control(
            'posts_per_page',
            [
                'label' => __('Posts Per Page', 'themescamp-plugin'),
                'type' => Controls_Manager::NUMBER,
                'default' => '4',
                'min' => '1',
            ]
        );

        $this->add_control(
            'offset',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'default' => '0',
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => __('Order', 'themescamp-plugin'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'asc' => 'Ascending',
                    'desc' => 'Descending',
                ],
                'default' => 'desc',

            ]
        );
        $this->add_control(
            'orderby',
            [
                'label' => __('Order By', 'themescamp-plugin'),
                'type' => Controls_Manager::SELECT,
                'options' => ControlsHelper::get_post_orderby_options(),
                'default' => 'date',

            ]
        );

        $this->add_control(
            'authors',
            [
                'label' => __('Author', 'themescamp-plugin'),
                'label_block' => true,
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'default' => [],
                'options' => ControlsHelper::get_authors_list(),
                'condition' => [
                    'post_type!' => ['by_id', 'source_dynamic'],
                ],
            ]
        );
        $this->add_control(
            'post__not_in',
            [
                'label'       => __('Exclude', 'themescamp-plugin'),
                'type'        => 'tcg-select2',
                'label_block' => true,
                'multiple'    => true,
                'source_name' => 'post_type',
                'source_type' => 'any',
                'condition'   => [
                    'post_type!' => ['by_id', 'source_dynamic'],
                ],
            ]
        );
        foreach ($taxonomies as $taxonomy => $object) {
            if (!isset($object->object_type[0]) || !in_array($object->object_type[0], array_keys($post_types))) {
                continue;
            }

            $this->add_control(
                $taxonomy . '_ids',
                [
                    'label' => $object->label,
                    'type' => 'tcg-select2',
                    'label_block' => true,
                    'multiple' => true,
                    'source_name' => 'taxonomy',
                    'source_type' => $taxonomy,
                    'use_taxonomy_slug' => true,
                    'condition' => [
                        'post_type' => $object->object_type,
                    ],
                ]
            );
        }

        $this->end_controls_section();
        $this->start_controls_section(
            'style',
            [
                'label' => esc_html__('Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'item_heading',
            [
                'label' => esc_html__('Item', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );
        $this->add_responsive_control(
            'links_margin',
            [
                'label' => esc_html__('List Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-interactive-links-showcase .links-text ul' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );
        $this->add_responsive_control(
            'links_padding',
            [
                'label' => esc_html__('List padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-interactive-links-showcase .links-text ul' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );
        $this->add_responsive_control(
            'item_padding',
            [
                'label' => esc_html__('Item Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-interactive-links-showcase .links-text li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'item_width',
            [
                'label' => esc_html__('Item Width', 'element-camp'),
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
                'size_units' => ['%', 'px', 'vw', 'custom'],
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
                    '{{WRAPPER}} .tcgelements-interactive-links-showcase .links-text li' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'title_heading',
            [
                'label' => esc_html__('Title', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Title Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-interactive-links-showcase .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .tcgelements-interactive-links-showcase .title',
            ]
        );

        $this->start_controls_tabs(
            'title_style_tabs'
        );

        $this->start_controls_tab(
            'title_style_current_tab',
            [
                'label' => esc_html__('Current', 'element-camp'),
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Title Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-interactive-links-showcase .title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'title_stroke',
                'selector' => '{{WRAPPER}} .tcgelements-interactive-links-showcase .title',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'title_style_rest_tab',
            [
                'label' => esc_html__('Rest', 'element-camp'),
            ]
        );

        $this->add_control(
            'rest_title_color',
            [
                'label' => esc_html__('Title Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-interactive-links-showcase li.no-active .title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'rest_title_stroke',
                'selector' => '{{WRAPPER}} .tcgelements-interactive-links-showcase li.no-active .title',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'category_heading',
            [
                'label' => esc_html__('Category', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'cat_margin',
            [
                'label' => esc_html__('Category Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-interactive-links-showcase .taxonomy' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'cat_typography',
                'selector' => '{{WRAPPER}} .tcgelements-interactive-links-showcase .taxonomy',
            ]
        );

        $this->start_controls_tabs(
            'category_style_tabs'
        );

        $this->start_controls_tab(
            'category_style_current_tab',
            [
                'label' => esc_html__('Current', 'element-camp'),
            ]
        );

        $this->add_control(
            'category_color',
            [
                'label' => esc_html__('Title Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-interactive-links-showcase .taxonomy' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'category_stroke',
                'selector' => '{{WRAPPER}} .tcgelements-interactive-links-showcase .taxonomy',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'category_style_rest_tab',
            [
                'label' => esc_html__('Rest', 'element-camp'),
            ]
        );

        $this->add_control(
            'rest_category_color',
            [
                'label' => esc_html__('Title Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-interactive-links-showcase li.no-active .taxonomy' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'rest_category_stroke',
                'selector' => '{{WRAPPER}} .tcgelements-interactive-links-showcase li.no-active .taxonomy',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'number_heading',
            [
                'label' => esc_html__('Number', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'num_margin',
            [
                'label' => esc_html__('Number Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-interactive-links-showcase .num' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'num_typography',
                'selector' => '{{WRAPPER}} .tcgelements-interactive-links-showcase .num',
            ]
        );

        $this->start_controls_tabs(
            'number_style_tabs'
        );

        $this->start_controls_tab(
            'number_style_current_tab',
            [
                'label' => esc_html__('Current', 'element-camp'),
            ]
        );

        $this->add_control(
            'number_color',
            [
                'label' => esc_html__('Number Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-interactive-links-showcase .num' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'number_stroke',
                'selector' => '{{WRAPPER}} .tcgelements-interactive-links-showcase .num',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'number_style_rest_tab',
            [
                'label' => esc_html__('Rest', 'element-camp'),
            ]
        );

        $this->add_control(
            'rest_number_color',
            [
                'label' => esc_html__('Number Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-interactive-links-showcase li.no-active .num' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'rest_number_stroke',
                'selector' => '{{WRAPPER}} .tcgelements-interactive-links-showcase li.no-active .num',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'image_style',
            [
                'label' => esc_html__('Image Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_width',
            [
                'label' => esc_html__('width', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-interactive-links-showcase .links-img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_height',
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
                    '{{WRAPPER}} .tcgelements-interactive-links-showcase .links-img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $start = is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');
        $end = !is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');

        $this->add_control(
            'image_offset_orientation_h',
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
            'image_offset_x',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-interactive-links-showcase .links-img' => 'left: {{SIZE}}{{UNIT}};',
                    'body.rtl {{WRAPPER}} .tcgelements-interactive-links-showcase .links-img' => 'right: {{SIZE}}{{UNIT}};',
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-interactive-links-showcase .links-img' => 'left: {{SIZE}}{{UNIT}};',
                    'body.rtl {{WRAPPER}} .tcgelements-interactive-links-showcase .links-img' => 'right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'image_offset_orientation_h!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_offset_x_end',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-interactive-links-showcase .links-img' => 'right: {{SIZE}}{{UNIT}};',
                    'body.rtl {{WRAPPER}} .tcgelements-interactive-links-showcase .links-img' => 'left: {{SIZE}}{{UNIT}};',
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-interactive-links-showcase .links-img' => 'right: {{SIZE}}{{UNIT}};',
                    'body.rtl {{WRAPPER}} .tcgelements-interactive-links-showcase .links-img' => 'left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'image_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_control(
            'image_offset_orientation_v',
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
            'image_offset_y',
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
                    '{{WRAPPER}} .tcgelements-interactive-links-showcase .links-img' => 'top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'image_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-interactive-links-showcase .links-img' => 'bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'image_offset_orientation_v' => 'end',
                ],
            ]
        );

        $this->add_control(
            'image_transform_options',
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
            'image_translate_x',
            [
                'label' => esc_html__( 'Translate X', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-interactive-links-showcase .links-img' => 'transform : translateX({{SIZE}}{{UNIT}})',
                ],
            ]
        );
        $this->add_control(
            'image_translate_y',
            [
                'label' => esc_html__( 'Translate Y', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-interactive-links-showcase .links-img' => 'transform: translateY({{SIZE}}{{UNIT}})',
                ],
            ]
        );
        $this->end_popover();
        $this->end_controls_section();
    }

    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings();
        $query_settings = $this->get_settings();
        $query_settings = ControlsHelper::fix_old_query($query_settings);
        $args = ControlsHelper::get_query_args($query_settings);
        $args = ControlsHelper::get_dynamic_args($query_settings, $args);
        $query = new \WP_Query($args);
        $selected_term_type = $settings['display_terms_type'];
?>

        <div class="tcgelements-interactive-links-showcase">
            <div class="links-text d-flex justify-content-center">
                <ul class="rest">
                    <?php
                    $itemCount = 1;
                    if ($query->have_posts()) :
                        while ($query->have_posts()) :
                            $query->the_post();
                            global $post;
                            $terms_display = '';
                            if ($selected_term_type == 'categories') {
                                $taxonomy_names = get_post_taxonomies();
                                if ($taxonomy_names) {
                                    $categories = get_the_terms($post->ID, $taxonomy_names[0]);
                                    if ($categories && !is_wp_error($categories)) {
                                        $cat_names = wp_list_pluck($categories, 'name');
                                        $terms_display = implode($settings['meta_separator'], $cat_names);
                                    }
                                }
                            } elseif ($selected_term_type == 'tags') {
                                $taxonomy_names = get_post_taxonomies();
                                if ($taxonomy_names) {
                                    $tags = get_the_terms($post->ID, $taxonomy_names[1]);
                                    if ($tags && !is_wp_error($tags)) {
                                        $tag_names = wp_list_pluck($tags, 'name');
                                        $terms_display = implode($settings['meta_separator'], $tag_names);
                                    }
                                }
                            }
                    ?>
                            <li data-tab="tab-<?= esc_attr($itemCount) ?>">
                                <h2>
                                    <span class="num"><?= esc_html(sprintf('%02d', $itemCount)) ?>.</span>
                                    <a href="<?= esc_url(get_the_permalink()) ?>">
                                        <span class="taxonomy"><?= esc_html($terms_display) ?></span>
                                        <span class="title"><?= esc_html(get_the_title()) ?></span>
                                    </a>
                                </h2>
                            </li>
                    <?php
                            $itemCount++;
                        endwhile;
                        wp_reset_postdata();
                    endif;
                    ?>
                </ul>
            </div>
            <div class="links-img">
                <?php
                $itemCount = 1;
                if ($query->have_posts()) :
                    while ($query->have_posts()) :
                        $query->the_post(); ?>
                        <div class="img" id="tab-<?= esc_attr($itemCount) ?>">
                            <img src="<?= esc_url(get_the_post_thumbnail_url()) ?>" alt="<?= esc_attr(get_the_title()) ?>">
                        </div>
                <?php
                        $itemCount++;
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
        </div>
<?php
    }
}
