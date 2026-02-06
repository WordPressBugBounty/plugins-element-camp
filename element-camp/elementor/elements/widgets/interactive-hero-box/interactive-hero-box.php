<?php

namespace ElementCampPlugin\Widgets;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

/**
 * Interactive Hero Box Widget
 *
 * Elementor widget for interactive hero boxes with background images.
 *
 * @since 1.0.0
 */
class ElementCamp_Interactive_Hero_Box extends Widget_Base
{

    /**
     * Retrieve the widget name.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'tcgelements-interactive-hero-box';
    }

    /**
     * Retrieve the widget title.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget title.
     */
    public function get_title()
    {
        return __('Interactive Hero Box', 'element-camp');
    }

    /**
     * Retrieve the widget icon.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'eicon-gallery-grid tce-widget-badge';
    }

    /**
     * Retrieve the list of categories the widget belongs to.
     *
     * @since 1.0.0
     * @access public
     * @return array Widget categories.
     */
    public function get_categories()
    {
        return ['elementcamp-elements'];
    }

    /**
     * Retrieve the list of scripts the widget depended on.
     *
     * @since 1.0.0
     * @access public
     * @return array Widget scripts dependencies.
     */
    public function get_script_depends()
    {
        return ['tcgelements-interactive-hero-box'];
    }

    /**
     * Retrieve the list of styles the widget depended on.
     *
     * @since 1.0.0
     * @access public
     * @return array Widget styles dependencies.
     */
    public function get_style_depends()
    {
        return ['tcgelements-interactive-hero-box'];
    }

    /**
     * Register the widget controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls()
    {
        $start = is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');
        $end = !is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');

        // Content Section - Boxes
        $this->start_controls_section(
            'section_boxes',
            [
                'label' => __('Boxes', 'element-camp'),
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

        $repeater = new Repeater();

        $repeater->add_control(
            'box_number',
            [
                'label' => esc_html__('Box Number', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'default' => '01',
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'box_date',
            [
                'label' => esc_html__('Date/Category', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Architecture / 2025',
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'box_title',
            [
                'label' => esc_html__('Title', 'element-camp'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'Crafting Spaces with Timeless Vision',
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'box_link',
            [
                'label' => esc_html__('Link', 'element-camp'),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => '#',
                ],
            ]
        );

        $repeater->add_control(
            'background_image',
            [
                'label' => esc_html__('Background Image', 'element-camp'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'boxes_list',
            [
                'label' => esc_html__('Boxes List', 'element-camp'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'box_number' => '01',
                        'box_date' => 'Architecture / 2025',
                        'box_title' => 'Crafting Spaces with Timeless Vision',
                    ],
                    [
                        'box_number' => '02',
                        'box_date' => 'Architecture / 2025',
                        'box_title' => 'Minimal & Modern',
                    ],
                    [
                        'box_number' => '03',
                        'box_date' => 'Architecture / 2025',
                        'box_title' => 'Breathing Walls For Better Living',
                    ],
                    [
                        'box_number' => '04',
                        'box_date' => 'Architecture / 2025',
                        'box_title' => 'Designing With Nature, Not Against',
                    ],
                ],
                'title_field' => '{{{ box_number }}} - {{{ box_title }}}',
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => esc_html__('Columns', 'elementcamp_plg'),
                'type' => Controls_Manager::SELECT,
                'default' => '6',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ],
            ]
        );

        $this->end_controls_section();

        // Content Section - Settings
        $this->start_controls_section(
            'section_settings',
            [
                'label' => __('Settings', 'element-camp'),
            ]
        );

        $this->add_control(
            'hover_effect',
            [
                'label' => esc_html__('Hover Effect', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'zoom',
                'options' => [
                    'zoom' => esc_html__('Zoom', 'element-camp'),
                    'fade' => esc_html__('Fade Only', 'element-camp'),
                ],
            ]
        );

        $this->add_control(
            'transition_duration',
            [
                'label' => esc_html__('Transition Duration (s)', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    's' => [
                        'min' => 0.1,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 1,
                ],
            ]
        );

        $this->add_control(
            'zoom_duration',
            [
                'label' => esc_html__('Zoom Duration (s)', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    's' => [
                        'min' => 1,
                        'max' => 60,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'size' => 30,
                ],
                'condition' => [
                    'hover_effect' => 'zoom',
                ],
            ]
        );

        $this->add_control(
            'blur_amount',
            [
                'label' => esc_html__('Initial Blur (px)', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'size' => 10,
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Container
        $this->start_controls_section(
            'section_container_style',
            [
                'label' => __('Container', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'space_between_x',
            [
                'label' => esc_html__('Space Between Columns (Horizontal)', 'themescamp-core'),
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
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'custom'],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-interactive-hero-box .row:not(.gx-0):not(.gx-1):not(.gx-2):not(.gx-3):not(.gx-4):not(.gx-5)' => 'margin-right: -{{SIZE}}{{UNIT}}; margin-left: -{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-interactive-hero-box .row:not(.gx-0):not(.gx-1):not(.gx-2):not(.gx-3):not(.gx-4):not(.gx-5) > *' => 'padding-right: {{SIZE}}{{UNIT}}; padding-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_min_height',
            [
                'label' => esc_html__('Min Height', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'vh',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-interactive-hero-box' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label' => esc_html__('Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'default' => [
                    'top' => '120',
                    'right' => '0',
                    'bottom' => '50',
                    'left' => '0',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-interactive-hero-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'container_text_color',
            [
                'label' => esc_html__('Text Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-interactive-hero-box' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'overlay_background',
                'label' => esc_html__('Overlay Background', 'element-camp'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-interactive-hero-box::before',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'color' => [
                        'default' => '#000000',
                    ],
                ],
            ]
        );

        $this->add_control(
            'overlay_opacity',
            [
                'label' => esc_html__('Overlay Opacity', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.01,
                    ],
                ],
                'default' => [
                    'size' => 0.4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-interactive-hero-box::before' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Box Item
        $this->start_controls_section(
            'section_box_style',
            [
                'label' => __('Box Item', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'box_height',
            [
                'label' => esc_html__('Height', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 300,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-interactive-hero-box .box-item' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'box_padding',
            [
                'label' => esc_html__('Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'default' => [
                    'top' => '20',
                    'right' => '20',
                    'bottom' => '20',
                    'left' => '20',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-interactive-hero-box .box-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'box_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'default' => [
                    'top' => '20',
                    'right' => '0',
                    'bottom' => '20',
                    'left' => '0',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-interactive-hero-box .box-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'box_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-interactive-hero-box .box-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'box_transition',
            [
                'label' => esc_html__('Transition Duration (s)', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    's' => [
                        'min' => 0.1,
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-interactive-hero-box .box-item' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );

        $this->start_controls_tabs('box_state_tabs');

        $this->start_controls_tab(
            'box_normal_tab',
            [
                'label' => esc_html__('Normal', 'element-camp'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'box_background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-interactive-hero-box .box-item',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'box_border',
                'selector' => '{{WRAPPER}} .tcgelements-interactive-hero-box .box-item',
                'fields_options' => [
                    'border' => [
                        'default' => 'solid',
                    ],
                    'width' => [
                        'default' => [
                            'top' => '1',
                            'right' => '1',
                            'bottom' => '1',
                            'left' => '1',
                        ],
                    ],
                    'color' => [
                        'default' => 'rgba(255, 255, 255, 0.33)',
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-interactive-hero-box .box-item',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'box_hover_tab',
            [
                'label' => esc_html__('Hover/Active', 'element-camp'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'box_hover_background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-interactive-hero-box .box-item:hover, {{WRAPPER}} .tcgelements-interactive-hero-box .box-item.active',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'box_hover_border',
                'selector' => '{{WRAPPER}} .tcgelements-interactive-hero-box .box-item:hover,{{WRAPPER}} .tcgelements-interactive-hero-box .box-item.active',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_hover_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-interactive-hero-box .box-item:hover, {{WRAPPER}} .tcgelements-interactive-hero-box .box-item.active',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        // Style Section - Number
        $this->start_controls_section(
            'section_number_style',
            [
                'label' => __('Number', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'number_typography',
                'selector' => '{{WRAPPER}} .tcgelements-interactive-hero-box .box-item .num',
            ]
        );

        $this->add_control(
            'number_color',
            [
                'label' => esc_html__('Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-interactive-hero-box .box-item .num' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'number_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-interactive-hero-box .box-item .num' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Date
        $this->start_controls_section(
            'section_date_style',
            [
                'label' => __('Date/Category', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'date_typography',
                'selector' => '{{WRAPPER}} .tcgelements-interactive-hero-box .box-item .date',
            ]
        );

        $this->add_control(
            'date_color',
            [
                'label' => esc_html__('Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '#8bc53f',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-interactive-hero-box .box-item .date' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'date_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '12',
                    'left' => '0',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-interactive-hero-box .box-item .date' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Title
        $this->start_controls_section(
            'section_title_style',
            [
                'label' => __('Title', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .tcgelements-interactive-hero-box .box-item .card-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-interactive-hero-box .box-item .card-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-interactive-hero-box .box-item .card-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render the widget output on the frontend.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $widget_id = 'tcgelements-hero-' . $this->get_id();

        $transition_duration = $settings['transition_duration']['size'] ?? 1;
        $zoom_duration = $settings['zoom_duration']['size'] ?? 30;
        $blur_amount = $settings['blur_amount']['size'] ?? 10;
        $hover_effect = $settings['hover_effect'] ?? 'zoom';

        ?>
        <div class="tcgelements-interactive-hero-box" id="<?php echo esc_attr($widget_id); ?>" data-transition="<?php echo esc_attr($transition_duration); ?>" data-zoom="<?php echo esc_attr($zoom_duration); ?>" data-blur="<?php echo esc_attr($blur_amount); ?>" data-effect="<?php echo esc_attr($hover_effect); ?>">
            <div class="container">
                <div class="boxes">
                    <div class="row">
                        <?php $itemCount=1; foreach ($settings['boxes_list'] as $index => $item) :
                            $box_key = 'box-' . $index;
                            $bg_class = 'bg-' . $index;
                            $active_class = ($settings['active_item']===$itemCount) ? ' active' : '';
                            $target = $item['box_link']['is_external'] ? ' target="_blank"' : '';
                            $nofollow = $item['box_link']['nofollow'] ? ' rel="nofollow"' : '';
                            $column_class = $this->get_column_classes($settings);
                            ?>
                            <div class="<?php echo esc_attr($column_class); ?>">
                                <a href="<?php echo esc_url($item['box_link']['url']); ?>" class="box-item<?php echo esc_attr($active_class); ?>" data-bg="<?php echo esc_attr($bg_class); ?>" <?php echo $target . $nofollow; ?>>
                                    <span class="num text-end"> <?php echo esc_html($item['box_number']); ?> </span>
                                    <div class="info">
                                        <div class="date fsz-12 cr-green1 mb-3"> <?php echo esc_html($item['box_date']); ?> </div>
                                        <h6 class="card-title fsz-24"> <?php echo esc_html($item['box_title']); ?> </h6>
                                    </div>
                                </a>
                            </div>
                        <?php $itemCount++; endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="boxes-bg">
                <?php $itemCount=1; foreach ($settings['boxes_list'] as $index => $item) :
                    $bg_class = 'bg-' . $index;
                    $show_class = ($settings['active_item']===$itemCount) ? ' show' : '';
                    $image_url = $item['background_image']['url'];
                    $image_alt = !empty($item['background_image']['alt']) ? $item['background_image']['alt'] : $item['box_title'];
                    ?>
                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>" class="bg <?php echo esc_attr($bg_class . $show_class); ?>">
                <?php $itemCount++; endforeach; ?>
            </div>
        </div>
        <?php
    }

    /**
     * Get responsive column classes
     *
     * @param array $settings Widget settings
     * @return string CSS classes
     */
    private function get_column_classes($settings) {
        $desktop = !empty($settings['columns']) ? (int)$settings['columns'] : 6;
        $tablet = !empty($settings['columns_tablet']) ? (int)$settings['columns_tablet'] : 1;
        $mobile = !empty($settings['columns_mobile']) ? (int)$settings['columns_mobile'] : 1;

        // Calculate column sizes
        $desktop_class = 'col-lg-' . (12 / $desktop);
        $tablet_class = 'col-md-' . (12 / $tablet);
        $mobile_class = 'col-' . (12 / $mobile);

        return "{$desktop_class} {$tablet_class} {$mobile_class}";
    }
}