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

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



/**
 * @since 1.1.0
 */
class ElementCamp_Menu_List extends Widget_Base {

    /**
     * Retrieve the widget name.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'tcgelements-menu-list';
    }

    /**
     * Retrieve the widget title.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__( 'Menu List', 'element-camp' );
    }

    /**
     * Retrieve the widget icon.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-nav-menu tce-widget-badge';
    }
    public function get_script_depends() {
        return ['tcgelements-menu-list'];
    }
    /**
     * Retrieve the list of categories the widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * Note that currently Elementor supports only one category.
     * When multiple categories passed, Elementor uses the first one.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories() {
        return [ 'elementcamp-elements' ];
    }

    /**
     * Register the widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.1.0
     *
     * @access protected
     */
    protected function _register_controls() {
        $this->start_controls_section(
            'section_title',
            [
                'label' => esc_html__( 'Menu to Display', 'element-camp' ),
            ]
        );

        $menus =elementcamp_navmenu_navbar_menu_choices();
        if ( ! empty( $menus ) ) {
            $this->add_control(
                'elementcamp_menu',
                [
                    'label'   => __( 'Select Menu', 'element-camp' ),
                    'type'    => Controls_Manager::SELECT,
                    'options' => $menus,
                    'default' => array_keys( $menus )[0],
                ]
            );
        } else {
            $this->add_control(
                'menu',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => '<strong>' . __( 'There are no menus in your site.', 'element-camp' ) . '</strong><br>' . sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to create one.', 'element-camp' ), admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
                    'separator' => 'after',
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                ]
            );
        }
        $this->add_responsive_control(
            'text_align',
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
                    '{{WRAPPER}} .tcgelements-menu-list ul .menu-item' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'dropdown_icon',
            [
                'label' => esc_html__('Drop Down Icon', 'element-camp'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'skin' => 'inline',
                'label_block' => false,
                'default' => [
                    'value' => 'fa fa-chevron-down',
                    'library' => 'fa-regular',
                ],
            ]
        );
        $this->add_control(
            'dropdown_icon_active',
            [
                'label' => esc_html__('Active Drop Down Icon', 'element-camp'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'skin' => 'inline',
                'label_block' => false,
                'default' => [
                    'value' => 'fa fa-chevron-up',
                    'library' => 'fa-regular',
                ],
            ]
        );
        $this->add_control(
            'animations',
            [
                'label' => esc_html__('Animation', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'hover-animation' => esc_html__('Hover Animation', 'element-camp'),
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_style_main',
            [
                'label' => esc_html__( 'Menu List Style', 'element-camp' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'menu_list_padding',
            [
                'label' => esc_html__('Menu List Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em' , 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-menu-list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'menu_list_height',
            [
                'label' => esc_html__( 'Menu List Height', 'element-camp' ),
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
                'size_units' => [ 'px', 'vh', '%','custom' ],
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
                    '{{WRAPPER}} .tcgelements-menu-list' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'link_display',
            [
                'label' => esc_html__('Link Display', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'inline-block',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->start_controls_tabs('tabs_menu_links_style');
        $this->start_controls_tab(
            'tab_menu_normal',
            [
                'label' => esc_html__('Normal', 'element-camp'),
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'menu_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'links_stroke',
                'selector' => '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'links_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a',
            ]
        );

        $this->add_control(
            'menu_color',
            [
                'label' => esc_html__('Main Menu Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'menu_item_background',
                'types' => ['classic', 'gradient'],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a',
            ]
        );
        $this->add_responsive_control(
            'menu_item_padding',
            [
                'label' => esc_html__('Padding Menu Items', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em' , 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-menu-list > div > ul > .menu-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'menu_item_border',
                'selector' => '{{WRAPPER}} .tcgelements-menu-list > div > ul > .menu-item',
            ]
        );
        $this->add_responsive_control(
            'menu_item_navbar_padding',
            [
                'label' => esc_html__('Padding Menu Items Link', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em' , 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'menu_item_navbar_margin',
            [
                'label' => esc_html__('Margin Menu Items', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em' , 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-menu-list .navbar-nav .nav-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'overlay_style',
            [
                'label' => esc_html__('Overlay', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'element-camp'),
                'label_off' => esc_html__('Hide', 'element-camp'),
                'return_value' => 'yes',
                'default' => 'yes',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a::after' => 'position: absolute;content: "";',
                ],
            ]
        );
        $this->add_control(
            'line_style_heading',
            [
                'label' => esc_html__('After Line Options', 'element-camp'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'overlay_style' => 'yes'
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'line_color',
                'label' => esc_html__('Line Color', 'element-camp'),
                'types' => ['classic', 'gradient'],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a::after',
                'condition' => [
                    'overlay_style' => 'yes'
                ]
            ]
        );
        $this->add_responsive_control(
            'line_width',
            [
                'label' => esc_html__( 'Line Width', 'element-camp' ),
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
                'size_units' => [ '%', 'px', 'vw','custom' ],
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
                    '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a::after' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'overlay_style' => 'yes'
                ]
            ]
        );
        $this->add_responsive_control(
            'line_height',
            [
                'label' => esc_html__( 'Line Height', 'element-camp' ),
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
                'size_units' => [ 'px', 'vh', '%','custom' ],
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
                    '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a::after' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'overlay_style' => 'yes'
                ]
            ]
        );
        $this->add_control(
            'line_opacity',
            [
                'label' => esc_html__( 'Line Opacity', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a::after' => 'opacity: {{SIZE}};',
                ],
                'condition' => [
                    'overlay_style' => 'yes'
                ]
            ]
        );
        $this->add_control(
            'dark_mode_style_heading',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control(
            'menu_color_dark_mode',
            [
                'label' => esc_html__( 'Main Menu Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-menu-list ul .menu-item a' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-menu-list ul .menu-item a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'menu_color_border_dark_mode',
            [
                'label' => esc_html__( 'Border Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-menu-list > div > ul > .menu-item' => 'border-color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-menu-list > div > ul > .menu-item' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_menu_hover',
            [
                'label' => esc_html__('Hover', 'element-camp'),
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'menu_typography_hover',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a:hover',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'links_stroke_hover',
                'selector' => '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a:hover',
            ]
        );
        $this->add_control(
            'menu_color_hover',
            [
                'label' => esc_html__( 'Main Menu Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'links_shadow_hover',
                'selector' => '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a:hover',
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'menu_item_background_hover',
                'types' => ['classic', 'gradient'],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a:hover',
            ]
        );
        $this->add_responsive_control(
            'menu_item_navbar_padding_hover',
            [
                'label' => esc_html__('Padding Menu Items Link', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em' , 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'line_style_heading_hover',
            [
                'label' => esc_html__('Line Style Options', 'element-camp'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'overlay_style' => 'yes'
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'line_color_hover',
                'label' => esc_html__('Line Color', 'element-camp'),
                'types' => ['classic', 'gradient'],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a:hover::after',
                'condition' => [
                    'overlay_style' => 'yes'
                ]
            ]
        );
        $this->add_responsive_control(
            'line_width_hover',
            [
                'label' => esc_html__( 'Line Width', 'element-camp' ),
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
                'size_units' => [ '%', 'px', 'vw','custom' ],
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
                    '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a:hover::after' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'overlay_style' => 'yes'
                ]
            ]
        );
        $this->add_responsive_control(
            'line_height_hover',
            [
                'label' => esc_html__( 'Line Height', 'element-camp' ),
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
                'size_units' => [ 'px', 'vh', '%','custom' ],
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
                    '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a:hover::after' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'overlay_style' => 'yes'
                ]
            ]
        );
        $this->add_control(
            'dark_mode_style_heading_hover',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control(
            'menu_color_dark_mode_hover',
            [
                'label' => esc_html__( 'Main Menu Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-menu-list ul .menu-item a:hover' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-menu-list ul .menu-item a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'menu_item_dark_mode_background_hover',
                'selector' => '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a:hover',
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
        $this->end_controls_tabs();
        $this->end_controls_section();
        $this->start_controls_section(
            'icon_style',
            [
                'label' => esc_html__( 'Drop Down Icon Style', 'element-camp' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'dropdown_icon_margin',
            [
                'label' => esc_html__('Drop Down Icon Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'menu_icon_size',
            [
                'label' => esc_html__( 'Drop Down Icon size', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__('Icon Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'position_for_icon',
            [
                'label' => esc_html__( 'Position', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'absolute' => esc_html__( 'Absolute', 'element-camp' ),
                    'relative' => esc_html__( 'Relative', 'element-camp' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a i' => 'position: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a svg' => 'position: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'icon_offset_orientation_h',
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
                    'position_for_icon!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_offset_x',
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
                    '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a i' => 'left: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a svg' => 'left: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'icon_offset_orientation_h' => 'start',
                    'position_for_icon!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_offset_x_end',
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
                    '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a i' => 'right: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a svg' => 'right: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'icon_offset_orientation_h' => 'end',
                    'position_for_icon!' => '',
                ],
            ]
        );
        $this->add_control(
            'icon_offset_orientation_v',
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
                    'position_for_icon!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_offset_y',
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
                    '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a i' => 'top: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a svg' => 'top: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'icon_offset_orientation_v' => 'start',
                    'position_for_icon!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a i' => 'bottom: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a svg' => 'bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'icon_offset_orientation_v' => 'end',
                    'position_for_icon!' => '',
                ],
            ]
        );
        $this->add_control(
            'translate_icon_y',
            [
                'label' => esc_html__( 'Translate Y', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'default' => [
                    'size' => 0,
                ],
            ]
        );
        $this->add_control(
            'translate_icon_x',
            [
                'label' => esc_html__( 'Translate X', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'default' => [
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a i' => 'transform: translate({{translate_icon_x.SIZE}}{{translate_icon_x.UNIT}},{{translate_icon_y.SIZE}}{{translate_icon_y.UNIT}})',
                    '{{WRAPPER}} .tcgelements-menu-list ul .menu-item a svg' => 'transform: translate({{translate_icon_x.SIZE}}{{translate_icon_x.UNIT}},{{translate_icon_y.SIZE}}{{translate_icon_y.UNIT}})',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_style_drop',
            [
                'label' => esc_html__( 'Drop Down Items', 'element-camp' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'sub_menu_border',
                'selector' => '{{WRAPPER}} .tcgelements-menu-list ul .menu-item .sub-menu',
            ]
        );
        $this->add_responsive_control(
            'drop_down_menu_padding',
            [
                'label' => esc_html__('Drop Down Menu Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em' , 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-menu-list ul .menu-item .sub-menu.sub-open' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'drop_down_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-menu-list ul .menu-item .sub-menu a',
            ]
        );
        $this->add_responsive_control(
            'drop_items_padding',
            [
                'label' => esc_html__('Drop Down Items Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em' , 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-menu-list ul .menu-item .sub-menu a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'drop_margin',
            [
                'label' => esc_html__('Drop Down Items Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-menu-list ul .menu-item .sub-menu a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs(
            'dropdown_items_tabs',
        );
        
        $this->start_controls_tab(
            'dropdown_item_normal_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_control(
            'drop_down_opacity',
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
                    '{{WRAPPER}} .tcgelements-menu-list ul .menu-item .sub-menu a' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'drop_down_color',
            [
                'label' => esc_html__('Drop Down Items Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-menu-list ul .menu-item .sub-menu a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'drop_down_items_background',
                'types' => ['classic', 'gradient'],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .tcgelements-menu-list ul .menu-item .sub-menu a',
            ]
        );
        $this->add_control(
            'dark_mode_style_drop_down_items',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control(
            'sub_menu_border_color_dark_mode',
            [
                'label' => esc_html__( 'Sub Menu Border Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-menu-list ul .menu-item .sub-menu' => 'border-color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-menu-list ul .menu-item .sub-menu' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'drop_down_items_background_dark_mode',
                'selector' => '{{WRAPPER}} .tcgelements-menu-list ul .menu-item .sub-menu a',
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
            'dropdown_item_hover_tab',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
            ]
        );
        $this->add_control(
            'drop_down_opacity_hover',
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
                    '{{WRAPPER}} .tcgelements-menu-list ul .menu-item .sub-menu a:hover' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'drop_down_color_hover',
            [
                'label' => esc_html__('Drop Down Items Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-menu-list ul .menu-item .sub-menu a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.1.0
     *
     * @access protected
     */
    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.1.0
     *
     * @access protected
     */
    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.1.0
     *
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings();
        if (!empty($settings['dropdown_icon'])) {
            ob_start();
            Icons_Manager::render_icon($settings['dropdown_icon'], ['aria-hidden' => 'true']);
            $dropdown_icon = ob_get_clean();
        }

        if (!empty($settings['dropdown_icon_active'])) {
            ob_start();
            Icons_Manager::render_icon($settings['dropdown_icon_active'], ['aria-hidden' => 'true']);
            $dropdown_icon_active = ob_get_clean();
        }
        $selected_menu = $settings['elementcamp_menu'] ?? '';
        $menu_class="navbar-nav";
        $animations = $settings['animations'];
        if (!empty($selected_menu)) {
            $menu_args = array(
                'menu' => $selected_menu,
                'menu_class' => $menu_class,
                'echo' => false,
            );
            $menu_html = wp_nav_menu($menu_args);
            ?>
            <div class="tcgelements-menu-list <?=esc_attr($animations)?>" <?php if (!empty($settings['dropdown_icon']['value'] && !empty($settings['dropdown_icon_active']['value']))) : ?> data-dropdown-icon="<?= esc_attr($dropdown_icon) ?>" data-dropdown-icon-active="<?= esc_attr($dropdown_icon_active) ?>" <?php endif;?>>
                <?=$menu_html?>
            </div>
            <?php
        }
    }
}

