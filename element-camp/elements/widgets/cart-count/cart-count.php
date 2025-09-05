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

        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Product Settings.', 'element-camp' ),
            ]
        );
        if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
            $this->add_control(
                'cart_count_text',
                [
                    'label' => esc_html__( 'Cart Count Text', 'tcgelements-plg' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => esc_html__( 'Cart', 'tcgelements-plg' ),
                ]
            );
        } else {
            // Display a warning message if WooCommerce is not active
            $this->add_control(
                'woocommerce_warning',
                [
                    'type' => \Elementor\Controls_Manager::RAW_HTML,
                    'raw' => '<strong>' . __( 'WooCommerce is not active.', 'tcgelements-plg' ) . '</strong><br>'
                        . sprintf( __( 'Please activate WooCommerce to use this widget. <a href="%s" target="_blank">Go to Plugins Page</a>.', 'tcgelements-plg' ), esc_url(admin_url('plugins.php')) ),
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
        $this->add_control(
            'cart_count_number_options',
            [
                'label' => esc_html__( 'Cart Count Number Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'number_typography',
                'label' => esc_html__('Number Typography', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-cart-count .cart-count-number',
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
            ]
        );
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

        <a href="<?= esc_url( wc_get_cart_url() );?>" class="tcgelements-cart-count">
            <?=esc_html($settings['cart_count_text']);?>
            <span class="cart-count-number"><?=esc_html($cart_count);?></span>
        </a>

        <?php

    }
}



