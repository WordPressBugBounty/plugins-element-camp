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
 * @since 1.0.0
 */
class ElementCamp_Cart_Count extends Widget_Base {

    /**
     * Retrieve the widget name.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'tcgelements-cart-count';
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
    public function get_title() {
        return esc_html__( 'Cart Count', 'element-camp' );
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
    public function get_icon() {
        return 'eicon-cart tce-widget-badge';
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
    public function get_categories() {
        return [ 'elementcamp-elements' ];
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
    protected function _register_controls() {

        $start = is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');
        $end = !is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');

        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Product Settings.', 'element-camp' ),
            ]
        );
        if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
            $this->add_control(
                'cart_count_type',
                [
                    'label' => esc_html__( 'Cart Count Type', 'element-camp' ),
                    'type'=> Controls_Manager::SELECT,
                    'default'=> 'text',
                    'options'=> [
                        'text'=> esc_html__( 'Text','element-camp'),
                        'icon'=> esc_html__( 'Icon','element-camp'),
                        'text_icon'=> esc_html__( 'Text & Icon','element-camp'),
                    ],
                ]
            );
            $this->add_control(
                'cart_count_icon',
                [
                    'label' => esc_html__('Cart Icon', 'element-camp'),
                    'type' => Controls_Manager::ICONS,
                    'fa4compatibility' => 'icon',
                    'default' => [
                        'value' => 'fas fa-shopping-cart',
                        'library' => 'fa-solid',
                    ],
                    'skin' => 'inline',
                    'label_block' => false,
                    'condition' => [
                        'cart_count_type' => ['icon', 'text_icon']
                    ]
                ]
            );
            $this->add_control(
                'cart_count_text',
                [
                    'label' => esc_html__( 'Cart Count Text', 'element-camp' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => esc_html__( 'Cart', 'element-camp' ),
                    'condition' => [
                        'cart_count_type' => ['text', 'text_icon']
                    ]
                ]
            );
            $this->add_control(
                'icon_position',
                [
                    'label' => esc_html__( 'Icon Position', 'element-camp' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'before',
                    'options' => [
                        'before' => esc_html__( 'Before Text', 'element-camp' ),
                        'after' => esc_html__( 'After Text', 'element-camp' ),
                    ],
                    'condition' => [
                        'cart_count_type' => 'text_icon'
                    ]
                ]
            );
            $this->add_control(
                'show_cart_number',
                [
                    'label' => esc_html__( 'Show Cart Count Number', 'element-camp' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( 'Show', 'element-camp' ),
                    'label_off' => esc_html__( 'Hide', 'element-camp' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );
        } else {
            // Display a warning message if WooCommerce is not active
            $this->add_control(
                'woocommerce_warning',
                [
                    'type' => \Elementor\Controls_Manager::RAW_HTML,
                    'raw' => '<strong>' . __( 'WooCommerce is not active.', 'element-camp' ) . '</strong><br>'
                        . sprintf( __( 'Please activate WooCommerce to use this widget. <a href="%s" target="_blank">Go to Plugins Page</a>.', 'element-camp' ), esc_url(admin_url('plugins.php')) ),
                    'separator' => 'after',
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
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
            'cart_count_text_options',
            [
                'label' => esc_html__( 'Cart Count Text Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'label' => esc_html__('Text Typography', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-cart-count',
            ]
        );
        $this->start_controls_tabs(
            'cart_count_text_tabs',
        );
        $this->start_controls_tab(
            'cart_count_text_normal_tab',
            [
                'label'   => esc_html__('Normal', 'element-camp'),
            ]
        );
        $this->add_control(
            'text_color',
            [
                'label' => esc_html__( 'Text Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-cart-count' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'cart_count_text_hover_tab',
            [
                'label'   => esc_html__('Normal', 'element-camp'),
            ]
        );
        $this->add_control(
            'text_hover_color',
            [
                'label' => esc_html__( 'Text Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-cart-count:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'cart_count_number_options',
            [
                'label' => esc_html__( 'Cart Count Number Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'show_cart_number' => 'yes'
                ]
            ]
        );
        $this->add_control(
            'cart_count_number_positioning',
            [
                'label' => esc_html__('Position', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'unset' => esc_html__('unset', 'element-camp'),
                    'absolute' => esc_html__('absolute', 'element-camp'),
                    'relative' => esc_html__('relative', 'element-camp'),
                ],
                'label_block' => true,
                'default' => 'unset',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-cart-count .cart-count-number' => 'position: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'cart_count_number_offset_orientation_h',
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
                'condition' => [
                    'cart_count_number_positioning!' => 'unset',
                ],
            ]
        );

        $this->add_responsive_control(
            'cart_count_number_offset_x',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-cart-count .cart-count-number' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-cart-count .cart-count-number' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'cart_count_number_positioning!' => 'unset',
                    'cart_count_number_offset_orientation_h!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'cart_count_number_offset_x_end',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-cart-count .cart-count-number' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-cart-count .cart-count-number' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'cart_count_number_positioning!' => 'unset',
                    'cart_count_number_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_control(
            'cart_count_number_offset_orientation_v',
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
                'condition' => [
                    'cart_count_number_positioning!' => 'unset',
                ],
            ]
        );

        $this->add_responsive_control(
            'cart_count_number_offset_y',
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
                    '{{WRAPPER}} .tcgelements-cart-count .cart-count-number' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'cart_count_number_positioning!' => 'unset',
                    'cart_count_number_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'cart_count_number_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-cart-count .cart-count-number' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'cart_count_number_positioning!' => 'unset',
                    'cart_count_number_offset_orientation_v' => 'end',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'number_typography',
                'label' => esc_html__('Number Typography', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-cart-count .cart-count-number',
                'condition' => [
                    'show_cart_number' => 'yes'
                ]
            ]
        );
        $this->add_control(
            'number_color',
            [
                'label' => esc_html__( 'Number Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-cart-count .cart-count-number' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_cart_number' => 'yes'
                ]
            ]
        );
        $this->add_responsive_control(
            'number_margin',
            [
                'label' => esc_html__('Number Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-cart-count .cart-count-number' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'show_cart_number' => 'yes'
                ]
            ]
        );
        $this->add_responsive_control(
            'number_padding',
            [
                'label' => esc_html__('Number Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-cart-count .cart-count-number' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'show_cart_number' => 'yes'
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'number_background',
                'label' => esc_html__('Background', 'element-camp'),
                'types' => ['classic', 'gradient'],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .tcgelements-cart-count .cart-count-number',
                'condition' => [
                    'show_cart_number' => 'yes'
                ]
            ]
        );
        $this->add_control(
            'number_border_radius',
            [
                'label' => esc_html__('Number Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-cart-count .cart-count-number' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'show_cart_number' => 'yes'
                ]
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'icon_style',
            [
                'label' => esc_html__('Icon Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'svg_size',
            [
                'label' => esc_html__('SVG Size', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 8,
                        'max' => 128,
                    ],
                    'em' => [
                        'min' => 0.5,
                        'max' => 8,
                    ],
                    'rem' => [
                        'min' => 0.5,
                        'max' => 8,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-cart-count svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'svg_margin',
            [
                'label' => esc_html__('SVG Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-cart-count svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'svg_padding',
            [
                'label' => esc_html__('SVG Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-cart-count svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs(
            'svg_tabs',
        );
        $this->start_controls_tab(
            'svg_normal_tab',
            [
                'label'   => esc_html__('Normal', 'element-camp'),
            ]
        );
        $this->add_control(
            'svg_color',
            [
                'label' => esc_html__('SVG Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-cart-count svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'svg_hover_tab',
            [
                'label'   => esc_html__('Normal', 'element-camp'),
            ]
        );
        $this->add_control(
            'svg_hover_color',
            [
                'label' => esc_html__('SVG Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-cart-count:hover svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
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
     * @since 1.0.0
     *
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings();

        if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
            return;
        };
        $settings = $this->get_settings_for_display();
        if ( null === WC()->cart ) {
            wc_load_cart();
        }
        $cart_count = WC()->cart->get_cart_contents_count();
        ?>

        <a href="<?= esc_url( wc_get_cart_url() );?>" class="tcgelements-cart-count <?php echo esc_attr($settings['cart_count_type']); ?>">
            <?php
            if($settings['cart_count_type'] == 'text') {
                echo esc_html($settings['cart_count_text']);
            } else if($settings['cart_count_type'] == 'icon') {
                Icons_Manager::render_icon( $settings['cart_count_icon'] );
            } else if($settings['cart_count_type'] == 'text_icon') {
                if($settings['icon_position'] == 'before') {
                    Icons_Manager::render_icon( $settings['cart_count_icon'] );
                    echo ' ' . esc_html($settings['cart_count_text']);
                } else {
                    echo esc_html($settings['cart_count_text']) . ' ';
                    Icons_Manager::render_icon( $settings['cart_count_icon'] );
                }
            }
            ?>
            <?php if($settings['show_cart_number'] == 'yes') : ?>
                <span class="cart-count-number"><?=esc_html($cart_count);?></span>
            <?php endif; ?>
        </a>

        <?php

    }
}