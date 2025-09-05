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


if (!defined('ABSPATH')) exit; // Exit if accessed directly



/**
 * @since 1.1.0
 */
class ElementCamp_Dropdown extends Widget_Base
{

    /**
     * Get widget name.
     *
     * Retrieve dropdown widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'tcgelements-dropdown';
    }

    public function get_script_depends() {
        return ['bootstrap.bundle.min','tcgelements-dropdown'];
    }

    /**
     * Get widget title.
     *
     * Retrieve dropdown widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return esc_html__('Dropdown', 'element-camp');
    }

    /**
     * Get widget icon.
     *
     * Retrieve dropdown widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'eicon-bullet-list tce-widget-badge';
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
     * Get widget keywords.
     *
     * Retrieve the list of keywords the widget belongs to.
     *
     * @since 2.1.0
     * @access public
     *
     * @return array Widget keywords.
     */
    /**
     * Register dropdown widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 3.1.0
     * @access protected
     */
    protected function register_controls()
    {
        $this->start_controls_section(
            'section_icon',
            [
                'label' => esc_html__('Drop Down', 'element-camp'),
            ]
        );

        $this->add_control(
            'dropdown_controls',
            [
                'label' => esc_html__('Works On : ', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'click',
                'options' => [
                    'click' => esc_html__('On Click', 'element-camp'),
                    'hover' => esc_html__('On Hover', 'element-camp'),
                ],
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'text',
            [
                'label' => esc_html__('Text', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'placeholder' => esc_html__('Item', 'element-camp'),
                'default' => esc_html__('Item', 'element-camp'),
                'dynamic' => [
                    'active' => true,
                ],
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
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => esc_html__(' Link ', 'element-camp'),
                'type' => Controls_Manager::URL,
            ]
        );

        $this->add_control(
            'dropdown',
            [
                'label' => esc_html__('Items', 'element-camp'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'text' => esc_html__('Language 1', 'element-camp'),
                    ],
                    [
                        'text' => esc_html__('Language 2', 'element-camp'),
                    ],
                    [
                        'text' => esc_html__('Language 3', 'element-camp'),
                    ],
                ],
                'title_field' => '{{{ text }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_dropdown',
            [
                'label' => esc_html__('Dropdown', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'dropdown_toggle_heading',
            [
                'label' => esc_html__( 'Dropdown Toggle Heading', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->add_responsive_control(
            'toggle_padding',
            [
                'label' => esc_html__('Toggle Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-dropdown .dropdown-toggle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'toggle_margin',
            [
                'label' => esc_html__('Toggle Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-dropdown .dropdown-toggle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'label' => esc_html__('Dropdown Toggle', 'element-camp'),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-dropdown .dropdown-toggle,{{WRAPPER}} .tcgelements-dropdown .dropdown-toggle .txt',
            ]
        );

        $this->add_control(
            'dropdown_items_heading',
            [
                'label' => esc_html__( 'Dropdown Items Heading', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'before'
            ]
        );
        $this->add_responsive_control(
            'dropdown_item_padding',
            [
                'label' => esc_html__('Dropdown Item Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-dropdown .dropdown-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'dropdown_item_border',
                'selector' => '{{WRAPPER}} .tcgelements-dropdown .dropdown-item',
            ]
        );

        $this->add_responsive_control(
            'dropdown_item_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-dropdown .dropdown-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'item_typography',
                'label' => esc_html__('Dropdown Items', 'element-camp'),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-dropdown .dropdown-item , {{WRAPPER}} .tcgelements-dropdown .dropdown-item .txt',
            ]
        );

        $this->start_controls_tabs(
            'dropdown_tabs',
        );
        
        $this->start_controls_tab(
            'dropdown_normal_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );

        $this->add_control(
            'dropdown_text_color',
            [
                'label' => esc_html__( 'Dropdown Text Color', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-dropdown .dropdown-toggle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'items_color',
            [
                'label' => esc_html__( 'Items Text Color', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-dropdown .dropdown-item' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'background_item_color',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-dropdown .dropdown-item',
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab(
            'dropdown_hover_tab',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
            ]
        );
        
        $this->add_control(
            'dropdown_text_color_hover',
            [
                'label' => esc_html__( 'Dropdown Text Color Hover', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-dropdown .dropdown-toggle:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'items_color_hover',
            [
                'label' => esc_html__( 'Items Text Color', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-dropdown .dropdown-item:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'background_item_color_hover',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-dropdown .dropdown-item:hover',
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->end_controls_section();
        $this->start_controls_section(
            'section_dropdown_menu',
            [
                'label' => esc_html__('Dropdown Menu', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'dropdown_menu_position',
            [
                'label' => esc_html__( 'Dropdown Menu Position', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'dropdown_menu_positioning',
            [
                'label' => esc_html__('Position', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'unset' => esc_html__('unset', 'element-camp'),
                    'absolute' => esc_html__('absolute', 'element-camp'),
                    'relative' => esc_html__('relative', 'element-camp'),
                ],
                'label_block' => true,
                'default' => 'absolute',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-prev' => 'position: {{VALUE}};',
                ],
            ]
        );

        $start = is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');
        $end = !is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');

        $this->add_control(
            'dropdown_menu_offset_orientation_h',
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
            'dropdown_menu_offset_x',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-dropdown .dropdown-menu' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-dropdown .dropdown-menu' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'dropdown_menu_offset_orientation_h!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'dropdown_menu_offset_x_end',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-dropdown .dropdown-menu' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-dropdown .dropdown-menu' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'dropdown_menu_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_control(
            'dropdown_menu_offset_orientation_v',
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
            'dropdown_menu_offset_y',
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
                    '{{WRAPPER}} .tcgelements-dropdown .dropdown-menu' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'dropdown_menu_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'dropdown_menu_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-dropdown .dropdown-menu' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'dropdown_menu_offset_orientation_v' => 'end',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'dropdown_menu_border',
                'selector' => '{{WRAPPER}} .tcgelements-dropdown .dropdown-menu',
                'separator' => 'before'
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'background_dropdown_menu_color',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-dropdown .dropdown-menu',
            ]
        );

        $this->add_responsive_control(
            'dropdown_menu_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-dropdown .dropdown-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_overlay_style',
            [
                'label' => esc_html__( 'Overlay', 'element-camp' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'overlay_display',
            [
                'label' => esc_html__('Overlay Display', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'block',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'none' => esc_html__('none', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-dropdown::before' => 'display: {{VALUE}};',
                ],
            ]
        );
        $start = is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');
        $end = !is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');

        $this->add_control(
            'overlay_offset_orientation_h',
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
            'overlay_offset_x',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-dropdown::before' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-dropdown::before' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'overlay_offset_orientation_h!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'overlay_offset_x_end',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-dropdown::before' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-dropdown::before' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'overlay_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_control(
            'overlay_offset_orientation_v',
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
            'overlay_offset_y',
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
                    '{{WRAPPER}} .tcgelements-dropdown::before' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'overlay_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'overlay_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-dropdown::before' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'overlay_offset_orientation_v' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'overlay_width',
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
                    '{{WRAPPER}} .tcgelements-dropdown::before' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'overlay_height',
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
                'size_units' => [ 'px', 'vh', '%', 'custom' ],
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
                    '{{WRAPPER}} .tcgelements-dropdown::before' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'overlay_background_color',
                'label' => esc_html__('Overlay Background', 'element-camp'),
                'types' => [ 'classic','gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-dropdown::before',
            ]
        );
        $this->end_controls_section();
    }

    /**
     * Render dropdown widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $interaction = $settings['dropdown_controls'];
        ?>
        <div class="tcgelements-dropdown dropdown <?=esc_attr($interaction)?>">
            <?php
            $firstItem = reset($settings['dropdown']); // Get the first item
            ?>
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <?php if (!empty($firstItem['image']['url'])) : ?>
                    <img class="flag" src="<?= esc_url($firstItem['image']['url']) ?>" alt="<?php if (!empty($firstItem['image']['alt'])) echo esc_attr($firstItem['image']['alt']); ?>">
                <?php endif;?>
                <span class="txt"><?= esc_html($firstItem['text']) ?></span>
            </a>
            <ul class="dropdown-menu">
                <?php foreach (array_slice($settings['dropdown'], 1) as $index => $item) : ?>
                    <li>
                        <a class="dropdown-item" href="<?= esc_url($item['link']['url']) ?>" <?php if ( $item['link']['is_external'] ) echo'target="_blank"'; ?>>
                            <?php if (!empty($item['image']['url'])) : ?>
                                <img class="flag" src="<?= esc_url($item['image']['url']) ?>" alt="<?php if (!empty($item['image']['alt'])) echo esc_attr($item['image']['alt']); ?>">
                            <?php endif;?>
                            <span class="txt"><?= esc_html($item['text']) ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php
    }
}
