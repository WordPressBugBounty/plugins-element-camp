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
use Elementor\Group_Control_Css_Filter;
use Elementor\Repeater;
use Elementor\Group_Control_Text_Stroke;
use Elementor\POPOVER_TOGGLE;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Elementor shop categories widget.
 *
 * Elementor widget that displays WooCommerce product categories.
 *
 * @since 1.0.0
 */
class ElementCamp_Shop_Categories extends Widget_Base {

    /**
     * Check if WooCommerce is active
     *
     * @return bool
     */
    private function is_woocommerce_active() {
        return in_array(
            'woocommerce/woocommerce.php',
            apply_filters('active_plugins', get_option('active_plugins'))
        );
    }

    public function get_name() {
        return 'tcgelements-shop-categories';
    }

    public function get_title() {
        return esc_html__('Shop Categories', 'elementcamp_plg');
    }

    public function get_icon() {
        return 'eicon-products tce-widget-badge';
    }

    public function get_categories() {
        return ['elementcamp-elements'];
    }

    public function get_keywords() {
        return [ 'shop', 'products', 'woocommerce', 'categories' ];
    }

    public function get_script_depends() {
        return ['swiper', 'tcgelements-shop-categories-slider'];
    }

    /**
     * Retrieve the list of scripts the widget depended on.
     *
     * Used to set styles dependencies required to run the widget.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return array Widget styles dependencies.
     */
    public function get_style_depends()
    {
        return ['swiper'];
    }

    /**
     * Register shop categories widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */

    private function get_product_categories(): array
    {
        $categories = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
        ]);

        $options = [];
        if (!empty($categories) && !is_wp_error($categories)) {
            foreach ($categories as $category) {
                $options[$category->term_id] = $category->name;
            }
        }

        return $options;
    }
    protected function register_controls() {
        // Check if WooCommerce is active
        if (!$this->is_woocommerce_active()) {
            $this->start_controls_section(
                'section_woocommerce_notice',
                [
                    'label' => esc_html__('Notice', 'elementcamp_plg'),
                ]
            );

            $this->add_control(
                'woocommerce_notice',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => esc_html__('WooCommerce is not active. Please install and activate WooCommerce to use this widget.', 'elementcamp_plg'),
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                ]
            );

            $this->end_controls_section();
            return;
        }

        // Content Section
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Content', 'elementcamp_plg'),
            ]
        );

        $this->add_control(
            'display_mode',
            [
                'label' => esc_html__('Display Mode', 'elementcamp_plg'),
                'type' => Controls_Manager::SELECT,
                'default' => 'grid',
                'options' => [
                    'grid' => esc_html__('Grid (Columns)', 'elementcamp_plg'),
                    'slider' => esc_html__('Slider', 'elementcamp_plg'),
                ],
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
                'condition' => ['display_mode' => 'grid'],
            ]
        );

        $this->add_control(
            'number',
            [
                'label' => esc_html__('Number of Categories', 'elementcamp_plg'),
                'type' => Controls_Manager::NUMBER,
                'default' => 6,
                'min' => 1,
                'max' => 100,
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label' => esc_html__('Order By', 'elementcamp_plg'),
                'type' => Controls_Manager::SELECT,
                'default' => 'name',
                'options' => [
                    'name' => esc_html__('Name', 'elementcamp_plg'),
                    'id' => esc_html__('ID', 'elementcamp_plg'),
                    'count' => esc_html__('Product Count', 'elementcamp_plg'),
                ],
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => esc_html__('Order', 'elementcamp_plg'),
                'type' => Controls_Manager::SELECT,
                'default' => 'ASC',
                'options' => [
                    'ASC' => esc_html__('Ascending', 'elementcamp_plg'),
                    'DESC' => esc_html__('Descending', 'elementcamp_plg'),
                ],
            ]
        );

        $this->add_control(
            'hide_empty',
            [
                'label' => esc_html__('Hide Empty Categories', 'elementcamp_plg'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => esc_html__('Yes', 'elementcamp_plg'),
                'label_off' => esc_html__('No', 'elementcamp_plg'),
            ]
        );

        $this->add_control(
            'exclude_categories',
            [
                'label' => esc_html__('Exclude Categories', 'elementcamp_plg'),
                'type' => Controls_Manager::SELECT2,
                'options' => $this->get_product_categories(),
                'multiple' => true,
                'description' => esc_html__('Select specific categories to exclude from the list.', 'elementcamp_plg'),
            ]
        );
        $this->add_control(
            'include_categories',
            [
                'label' => esc_html__('Include Specific Categories', 'elementcamp_plg'),
                'type' => Controls_Manager::SELECT2,
                'options' => $this->get_product_categories(),
                'multiple' => true,
                'description' => esc_html__('Select specific categories to display. Leave empty to display all categories.', 'elementcamp_plg'),
            ]
        );

        $this->add_control(
            'show_count',
            [
                'label' => esc_html__('Show Product Count', 'elementcamp_plg'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => esc_html__('Yes', 'elementcamp_plg'),
                'label_off' => esc_html__('No', 'elementcamp_plg'),
            ]
        );

        $this->add_control(
            'count_before_text',
            [
                'label' => esc_html__('Before Count Text', 'elementcamp_plg'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__('/', 'elementcamp_plg'),
                'condition' => [
                    'show_count' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'count_text',
            [
                'label' => esc_html__('Count Text', 'elementcamp_plg'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Products', 'elementcamp_plg'),
                'placeholder' => esc_html__('Products', 'elementcamp_plg'),
                'condition' => [
                    'show_count' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'slider_settings',
            [
                'label' => __('Slider Settings', 'elementcamp_plg'),
                'condition' => [
                    'display_mode' => 'slider',
                ]
            ]
        );

        $this->add_control(
            'speed',
            [
                'label' => esc_html__('Speed', 'elementcamp_plg'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 50000,
                'step' => 100,
                'default' => 500,
            ]
        );

        $this->add_control(
            'direction',
            [
                'label' => esc_html__('Direction', 'elementcamp_plg'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'horizontal' => esc_html__('Horizontal', 'elementcamp_plg'),
                    'vertical' => esc_html__('Vertical', 'elementcamp_plg'),
                ],
                'label_block' => true,
                'default' => 'horizontal',
            ]
        );

        $this->add_control(
            'effect',
            [
                'label' => esc_html__('Effect', 'elementcamp_plg'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'slide' => esc_html__('Slide', 'elementcamp_plg'),
                    'fade' => esc_html__('Fade', 'elementcamp_plg'),
                    'cube' => esc_html__('Cube', 'elementcamp_plg'),
                    'coverflow' => esc_html__('Cover flow', 'elementcamp_plg'),
                    'flip' => esc_html__('Flip', 'elementcamp_plg'),
                    'cards' => esc_html__('Cards', 'elementcamp_plg'),
                ],
                'label_block' => true,
                'default' => 'slide',
            ]
        );

        $this->add_control(
            'loop',
            [
                'label' => esc_html__('Loop', 'elementcamp_plg'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'elementcamp_plg'),
                'label_off' => esc_html__('Off', 'elementcamp_plg'),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => esc_html__('Autoplay', 'elementcamp_plg'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'elementcamp_plg'),
                'label_off' => esc_html__('Off', 'elementcamp_plg'),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $this->add_control(
            'autoplay_delay',
            [
                'label' => esc_html__('Autoplay Delay', 'elementcamp_plg'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 50000,
                'step' => 100,
                'default' => 5000,
                'condition' => [
                    'autoplay' => 'true',
                ]
            ]
        );

        $this->add_control(
            'centeredSlides',
            [
                'label' => esc_html__('Centered Slides', 'elementcamp_plg'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'elementcamp_plg'),
                'label_off' => esc_html__('Off', 'elementcamp_plg'),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $this->add_control(
            'arrows',
            [
                'label' => esc_html__('Slider Arrows', 'elementcamp_plg'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'elementcamp_plg'),
                'label_off' => esc_html__('Off', 'elementcamp_plg'),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $this->add_control(
            'pagination',
            [
                'label' => esc_html__('Slider Pagination', 'elementcamp_plg'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'elementcamp_plg'),
                'label_off' => esc_html__('Off', 'elementcamp_plg'),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $this->add_control(
            'paginationType',
            [
                'label' => esc_html__('Pagination Type', 'elementcamp_plg'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'bullets' => esc_html__('Bullets', 'elementcamp_plg'),
                    'fraction' => esc_html__('Fraction', 'elementcamp_plg'),
                    'progressbar' => esc_html__('Progress Bar', 'elementcamp_plg'),
                ],
                'label_block' => true,
                'default' => 'bullets',
                'condition' => [
                    'pagination' => 'true'
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_slider_responsive',
            [
                'label' => __('Slider Responsive', 'elementcamp_plg'),
                'condition' => [
                    'display_mode' => 'slider',
                ]
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'breakpoint',
            [
                'label' => esc_html__('Break Point', 'elementcamp_plg'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 992,
            ]
        );

        $repeater->add_control(
            'spaceBetween',
            [
                'label' => esc_html__('Space Between', 'elementcamp_plg'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 550,
                'step' => 1,
                'default' => 30,
            ]
        );

        $repeater->add_control(
            'slidesPerView',
            [
                'label' => esc_html__('Slides Per View', 'elementcamp_plg'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 100,
                'step' => 1,
                'default' => 1,
            ]
        );

        $this->add_control(
            'responsive_settings',
            [
                'label' => esc_html__('Custom Break Points', 'elementcamp_plg'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'breakpoint' => 1200,
                        'slidesPerView' => 4,
                        'spaceBetween' => 30
                    ],
                    [
                        'breakpoint' => 992,
                        'slidesPerView' => 3,
                        'spaceBetween' => 20
                    ],
                    [
                        'breakpoint' => 600,
                        'slidesPerView' => 2,
                        'spaceBetween' => 15
                    ],
                ],
                'title_field' => 'Screen size {{{ breakpoint }}}px',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_container_style',
            [
                'label' => __('Slider Container Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'display_mode' => 'slider',
                ]
            ]
        );

        $this->add_control(
            'slider_container_overflow',
            [
                'label' => esc_html__('Slider Container Overflow', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'hidden' => esc_html__('Hidden', 'element-camp'),
                    'visible' => esc_html__('Visible', 'element-camp'),
                ],
                'label_block' => true,
                'default' => 'hidden',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-container' => 'overflow: {{VALUE}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'slider_container_margin',
            [
                'label' => esc_html__('Slider Container Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_container_padding',
            [
                'label' => esc_html__('Slider Container Padding', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_container_width',
            [
                'label' => esc_html__('Width', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-container' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_container_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Tab - General
        $this->start_controls_section(
            'section_style_general',
            [
                'label' => esc_html__('General', 'elementcamp_plg'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'display_mode' => 'grid',
                ]
            ]
        );

        $this->add_responsive_control(
            'column_gap',
            [
                'label' => esc_html__('Columns Gap', 'elementcamp_plg'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 12,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories .row:not(.gx-0):not(.gx-1):not(.gx-2):not(.gx-3):not(.gx-4):not(.gx-5)' => 'margin-left: -{{SIZE}}{{UNIT}}; margin-right: -{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-shop-categories .row:not(.gx-0):not(.gx-1):not(.gx-2):not(.gx-3):not(.gx-4):not(.gx-5) > *' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Tab - Card
        $this->start_controls_section(
            'section_style_card',
            [
                'label' => esc_html__('Card', 'elementcamp_plg'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'card_alignment',
            [
                'label' => esc_html__('Card Alignment', 'elementcamp_plg'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'elementcamp_plg'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'elementcamp_plg'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'elementcamp_plg'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories .shop-category' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->start_controls_tabs('card_style_tabs');

        $this->start_controls_tab(
            'card_style_normal',
            [
                'label' => esc_html__('Normal', 'elementcamp_plg'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'card_background',
                'label' => esc_html__('Background', 'elementcamp_plg'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories .shop-category',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'card_border',
                'label' => esc_html__('Border', 'elementcamp_plg'),
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories .shop-category',
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label' => esc_html__('Border Radius', 'elementcamp_plg'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories .shop-category' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label' => esc_html__('Padding', 'elementcamp_plg'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories .shop-category' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_margin',
            [
                'label' => esc_html__('Margin', 'elementcamp_plg'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories .shop-category' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_box_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories .shop-category',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'card_style_hover',
            [
                'label' => esc_html__('Hover', 'elementcamp_plg'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'card_background_hover',
                'label' => esc_html__('Background', 'elementcamp_plg'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories .shop-category:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_box_shadow_hover',
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories .shop-category:hover',
            ]
        );

        $this->add_control(
            'card_hover_transition',
            [
                'label' => esc_html__('Transition Duration', 'elementcamp_plg'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0.3,
                ],
                'range' => [
                    'px' => [
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories .shop-category' => 'transition-duration: {{SIZE}}s',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        // Style Tab - Image
        $this->start_controls_section(
            'section_style_image',
            [
                'label' => esc_html__('Image', 'elementcamp_plg'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'img_container_heading_separator',
            [
                'label'     => esc_html__('Image Container', 'elementcamp_plg'),
                'type'      => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'image_container_overflow',
            [
                'label' => esc_html__('Image Container Overflow', 'elementcamp_plg'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'hidden' => esc_html__('Hidden', 'elementcamp_plg'),
                    'visible' => esc_html__('Visible', 'elementcamp_plg'),
                ],
                'label_block' => true,
                'default' => 'visible',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories .shop-category .img' => 'overflow: {{VALUE}};',
                ],

            ]
        );


        $this->add_responsive_control(
            'img_container_width',
            [
                'label' => esc_html__( 'Image Container Width', 'elementcamp_plg' ),
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
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
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
                    '{{WRAPPER}} .tcgelements-shop-categories .shop-category .img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'img_container_height',
            [
                'label' => esc_html__( 'Image Container Height', 'elementcamp_plg' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 500,
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories .shop-category .img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_container_margin',
            [
                'label' => esc_html__('Margin', 'elementcamp_plg'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories .shop-category .img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '.rtl {{WRAPPER}} .tcgelements-shop-categories .shop-category .img' => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'img_heading_separator',
            [
                'label'     => esc_html__('Image', 'elementcamp_plg'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );


        $this->add_responsive_control(
            'img_width',
            [
                'label' => esc_html__( 'Image Width', 'elementcamp_plg' ),
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
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
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
                    '{{WRAPPER}} .tcgelements-shop-categories .shop-category .img img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'img_max_width',
            [
                'label' => esc_html__( 'Image Max Width', 'elementcamp_plg' ),
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
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
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
                    '{{WRAPPER}} .tcgelements-shop-categories .shop-category .img img' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'img_height',
            [
                'label' => esc_html__( 'Image Height', 'elementcamp_plg' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 500,
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories .shop-category .img img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'img_max_height',
            [
                'label' => esc_html__( 'Image Max Height', 'elementcamp_plg' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 500,
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories .shop-category .img img' => 'max-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'img_object-fit',
            [
                'label' => esc_html__( 'Object Fit', 'elementcamp_plg' ),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'img_height[size]!' => '',
                ],
                'options' => [
                    '' => esc_html__( 'Default', 'elementcamp_plg' ),
                    'fill' => esc_html__( 'Fill', 'elementcamp_plg' ),
                    'cover' => esc_html__( 'Cover', 'elementcamp_plg' ),
                    'contain' => esc_html__( 'Contain', 'elementcamp_plg' ),
                    'scale-down' => esc_html__( 'Scale Down', 'elementcamp_plg' ),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories .shop-category .img img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'img_object-position',
            [
                'label' => esc_html__( 'Object Position', 'elementcamp_plg' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'center center' => esc_html__( 'Center Center', 'elementcamp_plg' ),
                    'center left' => esc_html__( 'Center Left', 'elementcamp_plg' ),
                    'center right' => esc_html__( 'Center Right', 'elementcamp_plg' ),
                    'top center' => esc_html__( 'Top Center', 'elementcamp_plg' ),
                    'top left' => esc_html__( 'Top Left', 'elementcamp_plg' ),
                    'top right' => esc_html__( 'Top Right', 'elementcamp_plg' ),
                    'bottom center' => esc_html__( 'Bottom Center', 'elementcamp_plg' ),
                    'bottom left' => esc_html__( 'Bottom Left', 'elementcamp_plg' ),
                    'bottom right' => esc_html__( 'Bottom Right', 'elementcamp_plg' ),
                ],
                'default' => 'center center',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories .shop-category .img img' => 'object-position: {{VALUE}};',
                ],
                'condition' => [
                    'img_height[size]!' => '',
                    'object-fit' => [ 'cover', 'contain', 'scale-down' ],
                ],
            ]
        );

        $this->add_responsive_control(
            'img_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-shop-categories .shop-category .img img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('image_img_tabs',);

        $this->start_controls_tab(
            'image_normal_tab',
            [
                'label'   => esc_html__( 'Normal', 'elementcamp_plg' ),
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'item_img_css_filters',
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories .shop-category .img img',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'image_hover_tab',
            [
                'label'   => esc_html__( 'Hover', 'elementcamp_plg' ),
            ]
        );
        $this->add_control(
            'image_transition',
            [
                'label' => esc_html__( 'Transition', 'elementcamp_plg' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories .shop-category .img img' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'item_hover_img_css_filters',
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories .shop-category:hover .img img',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        // Style Tab - Title
        $this->start_controls_section(
            'section_style_title',
            [
                'label' => esc_html__('Title', 'elementcamp_plg'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'title_display',
            [
                'label' => esc_html__('Title Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'block',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'inline' => esc_html__('Inline', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories .shop-category .title' => 'display: {{VALUE}};'
                ]
            ]
        );


        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories .shop-category .title',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Margin', 'elementcamp_plg'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories .shop-category .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '0.25',
                    'left' => '0',
                    'unit' => 'rem',
                    'isLinked' => false,
                ],
            ]
        );

        $this->start_controls_tabs('title_style_tabs');
        $this->start_controls_tab(
            'title_normal_tab',
            [
                'label'   => esc_html__( 'Normal', 'elementcamp_plg' ),
            ]
        );
        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Title Color', 'elementcamp_plg'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories .shop-category .title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'title_card_hover_tab',
            [
                'label'   => esc_html__( 'Hover', 'elementcamp_plg' ),
            ]
        );
        $this->add_control(
            'title_card_hover_color',
            [
                'label' => esc_html__('Title Color', 'elementcamp_plg'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories .shop-category:hover .title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'title_hover_transition',
            [
                'label' => esc_html__('Transition Duration', 'elementcamp_plg'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0.3,
                ],
                'range' => [
                    'px' => [
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories .shop-category .title' => 'transition-duration: {{SIZE}}s',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        // Style Tab - Count
        $this->start_controls_section(
            'section_style_count',
            [
                'label' => esc_html__('Count', 'elementcamp_plg'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_count' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'count_display',
            [
                'label' => esc_html__('Count Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'block',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'inline' => esc_html__('Inline', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories .shop-category .count' => 'display: {{VALUE}};'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'count_typography',
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories .shop-category .count',
            ]
        );

        $this->start_controls_tabs('count_style_tabs');

        $this->start_controls_tab(
            'count_normal_tab',
            [
                'label'   => esc_html__( 'Normal', 'elementcamp_plg' ),
            ]
        );
        $this->add_control(
            'count_color',
            [
                'label' => esc_html__('Text Color', 'elementcamp_plg'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories .shop-category .count' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'count_card_hover_tab',
            [
                'label'   => esc_html__( 'hover', 'elementcamp_plg' ),
            ]
        );
        $this->add_control(
            'count_card_hover_color',
            [
                'label' => esc_html__('Text Color', 'elementcamp_plg'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories .shop-category:hover .count' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'count_hover_transition',
            [
                'label' => esc_html__('Transition Duration', 'elementcamp_plg'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0.3,
                ],
                'range' => [
                    'px' => [
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories .shop-category .count' => 'transition-duration: {{SIZE}}s',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_arrows_style',
            [
                'label' => __('Arrows Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'arrows' => 'true',
                    'display_mode' => 'slider',
                ]
            ]
        );

        $this->add_control(
            'arrows_positioning',
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows' => 'position: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrows_width',
            [
                'label' => esc_html__('Width', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrows_height',
            [
                'label' => esc_html__('Height', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'tabs_arrows_style'
        );

        $this->start_controls_tab(
            'style_arrows_normal_tab',
            [
                'label' => esc_html__('Normal', 'element-camp'),
            ]
        );

        $this->add_control(
            'arrows_arrow_color',
            [
                'label' => esc_html__('Arrow Color', 'element-camp'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrows_icon_size',
            [
                'label' => esc_html__('Icon Size', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'arrows_arrow_typography',
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows .tcgelements-shop-categories-slider-arrow-text',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'arrows_background',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows',
                'exclude' => ['image']
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'arrows_border',
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows',
            ]
        );

        $this->add_responsive_control(
            'arrows_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'arrows_box_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'style_arrows_hover_tab',
            [
                'label' => esc_html__('Hover', 'element-camp'),
            ]
        );

        $this->add_control(
            'arrows_arrow_color_hover',
            [
                'label' => esc_html__('Arrow Color', 'element-camp'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows:hover' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'arrows_arrow_typography_hover',
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows:hover .tcgelements-shop-categories-slider-arrow-text',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'arrows_background_hover',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows:hover',
                'exclude' => ['image']
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'arrows_border_hover',
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows:hover',
            ]
        );

        $this->add_responsive_control(
            'arrows_border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'arrows_box_shadow_hover',
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'arrows_padding',
            [
                'label' => esc_html__('Padding', 'element-camp'),
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
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrows_margin',
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrows_text_margin',
            [
                'label' => esc_html__('Arrows text Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrow-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'arrows_style' => ['text', 'text-icon'],
                ],
            ]
        );

        $this->add_control(
            'arrows_position',
            [
                'label' => esc_html__('Arrows Position', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'arrows_offset_orientation_v',
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
            'arrows_offset_y',
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'arrows_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrows_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'arrows_offset_orientation_v' => 'end',
                ],
            ]
        );
        $this->add_control(
            'arrows_transform_options',
            [
                'label' => esc_html__('Arrows Transform Options', 'element-camp'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'element-camp'),
                'label_on' => esc_html__('Custom', 'element-camp'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );
        $this->start_popover();
        $this->add_control(
            'arrows_translate_x',
            [
                'label' => esc_html__('Arrows Translate X', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows' => '--e-transform-tcgelements-shop-categories-slider-arrows-translateX: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_control(
            'arrows_translate_y',
            [
                'label' => esc_html__('Arrows Translate Y', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows' => '--e-transform-tcgelements-shop-categories-slider-arrows-translateY: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->end_popover();
        $this->add_responsive_control(
            'arrows_z_index',
            [
                'label' => esc_html__('Z-Index', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows' => 'z-index: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_each_arrow_style',
            [
                'label' => __('Each Arrow Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'arrows' => 'true',
                ]
            ]
        );

        $this->add_control(
            'next_arrow_options',
            [
                'label' => esc_html__('Next Arrow Options', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'next_arrow_positioning',
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-next' => 'position: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'next_arrow_width',
            [
                'label' => esc_html__('Width', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-next' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'next_arrow_height',
            [
                'label' => esc_html__('Height', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-next' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'tabs_next_arrow_style'
        );

        $this->start_controls_tab(
            'style_next_arrow_normal_tab',
            [
                'label' => esc_html__('Normal', 'element-camp'),
            ]
        );

        $this->add_control(
            'next_arrow_color',
            [
                'label' => esc_html__('Arrow Color', 'element-camp'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-next' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'next_arrow_typography',
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-next .tcgelements-shop-categories-slider-arrow-text',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'next_arrow_background',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-next',
                'exclude' => ['image']
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'next_arrow_border',
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-next',
            ]
        );

        $this->add_responsive_control(
            'next_arrow_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'next_arrow_box_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-next',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'style_next_arrow_hover_tab',
            [
                'label' => esc_html__('Hover', 'element-camp'),
            ]
        );

        $this->add_control(
            'next_arrow_color_hover',
            [
                'label' => esc_html__('Arrow Color', 'element-camp'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-next:hover' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'next_arrow_typography_hover',
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-next:hover .tcgelements-shop-categories-slider-arrow-text',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'next_arrow_background_hover',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-next:hover',
                'exclude' => ['image']
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'next_arrow_border_hover',
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-next:hover',
            ]
        );

        $this->add_responsive_control(
            'next_arrow_border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-next:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'next_arrow_box_shadow_hover',
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-next:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'next_arrow_padding',
            [
                'label' => esc_html__('Padding', 'element-camp'),
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
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-next' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'next_arrow_margin',
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-next' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'next_arrow_position',
            [
                'label' => esc_html__('Arrows Position', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $start = is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');
        $end = !is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');

        $this->add_responsive_control(
            'next_arrow_offset_orientation_h',
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
            'next_arrow_offset_x',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-next' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-next' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'next_arrow_offset_orientation_h!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'next_arrow_offset_x_end',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-next' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-next' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'next_arrow_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'next_arrow_offset_orientation_v',
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
            'next_arrow_offset_y',
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-next' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'next_arrow_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'next_arrow_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-next' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'next_arrow_offset_orientation_v' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'next_arrow_z_index',
            [
                'label' => esc_html__('Z-Index', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-next' => 'z-index: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'prev_arrow_options',
            [
                'label' => esc_html__('Prev Arrow Options', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'prev_arrow_positioning',
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-prev' => 'position: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'prev_arrow_width',
            [
                'label' => esc_html__('Width', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-prev' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'prev_arrow_height',
            [
                'label' => esc_html__('Height', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-prev' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'tabs_prev_arrow_style'
        );

        $this->start_controls_tab(
            'style_prev_arrow_normal_tab',
            [
                'label' => esc_html__('Normal', 'element-camp'),
            ]
        );

        $this->add_control(
            'prev_arrow_color',
            [
                'label' => esc_html__('Arrow Color', 'element-camp'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-prev' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'prev_arrow_typography',
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-prev .tcgelements-shop-categories-slider-arrow-text',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'prev_arrow_background',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-prev',
                'exclude' => ['image']
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'prev_arrow_border',
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-prev',
            ]
        );

        $this->add_responsive_control(
            'prev_arrow_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'prev_arrow_box_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-prev',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'style_prev_arrow_hover_tab',
            [
                'label' => esc_html__('Hover', 'element-camp'),
            ]
        );

        $this->add_control(
            'prev_arrow_color_hover',
            [
                'label' => esc_html__('Arrow Color', 'element-camp'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-prev:hover' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'prev_arrow_typography_hover',
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-prev:hover .tcgelements-shop-categories-slider-arrow-text',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'prev_arrow_background_hover',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-prev:hover',
                'exclude' => ['image']
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'prev_arrow_border_hover',
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-prev:hover',
            ]
        );

        $this->add_responsive_control(
            'prev_arrow_border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-prev:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'prev_arrow_box_shadow_hover',
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-prev:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'prev_arrow_padding',
            [
                'label' => esc_html__('Padding', 'element-camp'),
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
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-prev' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'prev_arrow_margin',
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-prev' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'prev_arrow_position',
            [
                'label' => esc_html__('Arrows Position', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $start = is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');
        $end = !is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');

        $this->add_responsive_control(
            'prev_arrow_offset_orientation_h',
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
            'prev_arrow_offset_x',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-prev' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-prev' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'prev_arrow_offset_orientation_h!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'prev_arrow_offset_x_end',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-prev' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-prev' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'prev_arrow_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'prev_arrow_offset_orientation_v',
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
            'prev_arrow_offset_y',
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-prev' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'prev_arrow_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'prev_arrow_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-prev' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'prev_arrow_offset_orientation_v' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'prev_arrow_z_index',
            [
                'label' => esc_html__('Z-Index', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .tcgelements-shop-categories-slider-arrows.swiper-button-prev' => 'z-index: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_pagination_progress_bar_style',
            [
                'label' => __('Pagination Progress Bar Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'pagination' => 'true',
                    'paginationType' => 'progressbar'
                ]
            ]
        );

        $this->add_responsive_control(
            'pagination_progress_bar_width',
            [
                'label' => esc_html__('Width', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-progressbar.swiper-pagination-horizontal' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_progress_bar_size',
            [
                'label' => esc_html__('Size', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-progressbar.swiper-pagination-horizontal' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-progressbar.swiper-pagination-vertical' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_progress_bar_offset_orientation_h',
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
                'render_type' => 'ui',
            ]
        );

        $this->add_responsive_control(
            'pagination_progress_bar_offset_x',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-progressbar.swiper-pagination-horizontal' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-progressbar.swiper-pagination-horizontal' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'pagination_progress_bar_offset_orientation_h!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_progress_bar_offset_x_end',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-progressbar.swiper-pagination-horizontal' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-progressbar.swiper-pagination-horizontal' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'pagination_progress_bar_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_control(
            'pagination_progress_bar_offset_orientation_v',
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
            'pagination_progress_bar_offset_y',
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-progressbar.swiper-pagination-horizontal' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'pagination_progress_bar_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_progress_bar_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-progressbar.swiper-pagination-horizontal' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'pagination_progress_bar_offset_orientation_v' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'progress_bar_margin',
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-progressbar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; width: calc(100% - ({{RIGHT}}{{UNIT}} + {{LEFT}}{{UNIT}}));',
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-progressbar.swiper-progressbar-vertical' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; height: calc(100% - (({{TOP}}{{UNIT}} + {{BOTTOM}}{{UNIT}}) * 3));',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'pagination_progress_bar_progress',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-progressbar .swiper-pagination-progressbar-fill',
                'exclude' => ['image'],
                'fields_options' => [
                    'background' => [
                        'label' => esc_html_x('Progress Bar Color', 'Background Control', 'element-camp'),
                    ]
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'pagination_progress_bar_background',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-progressbar',
                'exclude' => ['image'],
                'fields_options' => [
                    'background' => [
                        'label' => esc_html_x('Progress Bar Background', 'Background Control', 'element-camp'),
                    ]
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'pagination_progress_bar_border',
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-progressbar',
            ]
        );

        $this->add_responsive_control(
            'pagination_progress_bar_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-progressbar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-progressbar .swiper-pagination-progressbar-fill' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_pagination_bullets_style',
            [
                'label' => __('Pagination Bullets Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'pagination' => 'true',
                    'paginationType' => 'bullets'
                ]
            ]
        );

        $this->add_control(
            'pagination_bullets_container_style',
            [
                'label' => esc_html__('Pagination Bullets Container Style', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'pagination_bullets_container_border',
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-bullets',
            ]
        );

        $this->add_responsive_control(
            'pagination_bullets_container_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-bullets' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_bullets_padding',
            [
                'label' => esc_html__('Padding', 'element-camp'),
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
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-bullets' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_bullets_margin',
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-bullets' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_bullets_positioning',
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-bullets' => 'position: {{VALUE}};',
                ],
            ]
        );

        $start = is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');
        $end = !is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');

        $this->add_control(
            'pagination_bullets_offset_orientation_h',
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
            'pagination_bullets_offset_x',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-bullets.swiper-pagination-horizontal' => 'left: {{SIZE}}{{UNIT}}; right: unset; transform: translateX(50%);',
                    'body.rtl {{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-bullets.swiper-pagination-horizontal' => 'right: {{SIZE}}{{UNIT}}; left: unset; transform: translateX(-50%);',
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-bullets.swiper-pagination-vertical' => 'left: {{SIZE}}{{UNIT}}; right: unset; transform: translateY(50%);',
                    'body.rtl {{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-bullets.swiper-pagination-vertical' => 'right: {{SIZE}}{{UNIT}}; left: unset; transform: translateY(50%);',
                ],
                'condition' => [
                    'pagination_bullets_offset_orientation_h!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_bullets_offset_x_end',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-bullets.swiper-pagination-horizontal' => 'right: {{SIZE}}{{UNIT}}; left: unset; transform: translateX(-50%);',
                    'body.rtl {{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-bullets.swiper-pagination-horizontal' => 'left: {{SIZE}}{{UNIT}}; right: unset; transform: translateX(50%);',
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-bullets.swiper-pagination-vertical' => 'right: {{SIZE}}{{UNIT}}; left: unset; transform: translateY(50%);',
                    'body.rtl {{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-bullets.swiper-pagination-vertical' => 'left: {{SIZE}}{{UNIT}}; right: unset; transform: translateY(50%);',
                ],
                'condition' => [
                    'pagination_bullets_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_control(
            'pagination_bullets_offset_orientation_v',
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
            'pagination_bullets_offset_y',
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-bullets' => 'top: {{SIZE}}{{UNIT}}; bottom: unset; transform: translateY(50%) translateX(50%);',
                ],
                'condition' => [
                    'pagination_bullets_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_bullets_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-bullets' => 'bottom: {{SIZE}}{{UNIT}}; top: unset; transform: translateY(-50%) translateX(50%);',
                ],
                'condition' => [
                    'pagination_bullets_offset_orientation_v' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_bullets_z_index',
            [
                'label' => esc_html__('Z-Index', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-bullets' => 'z-index: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_bullets_style_dark_mode',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'pagination_bullets_border_dark_mode',
            [
                'label' => esc_html__('Border Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-bullets' => 'border-color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-bullets' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_bullet_space',
            [
                'label' => esc_html__('Space', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-bullets .swiper-pagination-bullet' => 'margin: 0 {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs(
            'tabs_pagination_bullets_style'
        );

        $this->start_controls_tab(
            'style_pagination_bullets_normal_tab',
            [
                'label' => esc_html__('Normal', 'element-camp'),
            ]
        );

        $this->add_responsive_control(
            'pagination_bullet_size',
            [
                'label' => esc_html__('Size', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-bullets .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_bullet_height',
            [
                'label' => esc_html__('Height', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-bullets .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'pagination_bullet_background',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-bullets .swiper-pagination-bullet',
                'exclude' => ['image'],
                'fields_options' => [
                    'background' => [
                        'label' => esc_html_x('Bullet Background', 'Background Control', 'element-camp'),
                    ]
                ]
            ]
        );

        $this->add_responsive_control(
            'pagination_bullet_opacity',
            [
                'label' => esc_html__('Bullet Opacity', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-bullets .swiper-pagination-bullet' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_bullet_outline',
            [
                'label' => esc_html__('Outline', 'element-camp'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'element-camp'),
                'label_on' => esc_html__('Custom', 'element-camp'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->start_popover();

        $this->add_control(
            'pagination_bullet_outline_type',
            [
                'label' => esc_html__('Border Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'none' => esc_html__('None', 'element-camp'),
                    'solid' => esc_html__('Solid', 'element-camp'),
                    'double' => esc_html__('Double', 'element-camp'),
                    'dotted' => esc_html__('Dotted', 'element-camp'),
                    'dashed' => esc_html__('Dashed', 'element-camp'),
                    'groove' => esc_html__('Groove', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-bullets .swiper-pagination-bullet' => 'outline-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_bullet_outline_width',
            [
                'label' => esc_html__('Bullet Width', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem', 'vw', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'condition' => [
                    'pagination_bullet_outline_type!' => ['', 'none'],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-bullets .swiper-pagination-bullet' => 'outline-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_bullet_outline_color',
            [
                'label' => esc_html__('Border Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-bullets .swiper-pagination-bullet' => 'outline-color: {{VALUE}};',
                ],
                'condition' => [
                    'pagination_bullet_outline_type!' => ['', 'none'],
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_bullet_outline_offset',
            [
                'label' => esc_html__('Bullet Offset', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'condition' => [
                    'pagination_bullet_outline_type!' => ['', 'none'],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-bullets .swiper-pagination-bullet' => 'outline-offset: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_popover();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'pagination_bullet_border',
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-bullets .swiper-pagination-bullet',
            ]
        );

        $this->add_responsive_control(
            'pagination_bullet_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-bullets .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'style_pagination_bullets_active_tab',
            [
                'label' => esc_html__('Active', 'element-camp'),
            ]
        );

        $this->add_responsive_control(
            'pagination_active_bullet_size',
            [
                'label' => esc_html__('Size', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_active_bullet_height',
            [
                'label' => esc_html__('Height', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'pagination_active_bullet_background',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active',
                'exclude' => ['image'],
                'fields_options' => [
                    'background' => [
                        'label' => esc_html_x('Active Bullet Color', 'Background Control', 'element-camp'),
                    ]
                ]
            ]
        );

        $this->add_responsive_control(
            'pagination_active_bullet_opacity',
            [
                'label' => esc_html__('Bullet Opacity', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_active_bullet_outline',
            [
                'label' => esc_html__('Outline', 'element-camp'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'element-camp'),
                'label_on' => esc_html__('Custom', 'element-camp'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->start_popover();

        $this->add_control(
            'pagination_active_bullet_outline_type',
            [
                'label' => esc_html__('Border Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'none' => esc_html__('None', 'element-camp'),
                    'solid' => esc_html__('Solid', 'element-camp'),
                    'double' => esc_html__('Double', 'element-camp'),
                    'dotted' => esc_html__('Dotted', 'element-camp'),
                    'dashed' => esc_html__('Dashed', 'element-camp'),
                    'groove' => esc_html__('Groove', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'outline-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_active_bullet_outline_width',
            [
                'label' => esc_html__('Bullet Width', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem', 'vw', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'condition' => [
                    'pagination_active_bullet_outline_type!' => ['', 'none'],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'outline-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_active_bullet_outline_color',
            [
                'label' => esc_html__('Border Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'outline-color: {{VALUE}};',
                ],
                'condition' => [
                    'pagination_active_bullet_outline_type!' => ['', 'none'],
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_active_bullet_outline_offset',
            [
                'label' => esc_html__('Bullet Offset', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'condition' => [
                    'pagination_active_bullet_outline_type!' => ['', 'none'],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'outline-offset: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_popover();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'pagination_active_bullet_border',
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active',
            ]
        );

        $this->add_responsive_control(
            'pagination_active_bullet_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_pagination_fraction_style',
            [
                'label' => __('Pagination Fraction Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'pagination' => 'true',
                    'paginationType' => 'fraction'
                ]
            ]
        );

        $start = is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');
        $end = !is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');

        $this->add_control(
            'pagination_fraction_offset_orientation_h',
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
            'pagination_fraction_offset_x',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination' => 'left: {{SIZE}}{{UNIT}}; transform: translateX(-50%);',
                    'body.rtl {{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination' => 'right: {{SIZE}}{{UNIT}}; transform: translateX(50%);',
                ],
                'condition' => [
                    'pagination_fraction_offset_orientation_h!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_fraction_offset_x_end',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination' => 'right: {{SIZE}}{{UNIT}}; transform: translateX(50%);',
                    'body.rtl {{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination' => 'left: {{SIZE}}{{UNIT}}; transform: translateX(-50%);',
                ],
                'condition' => [
                    'pagination_fraction_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_control(
            'pagination_fraction_offset_orientation_v',
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
            'pagination_fraction_offset_y',
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination' => 'top: {{SIZE}}{{UNIT}}; bottom: unset; transform: translateY(50%);',
                ],
                'condition' => [
                    'pagination_fraction_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_fraction_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination' => 'bottom: {{SIZE}}{{UNIT}}; top: unset; transform: translateY(-50%);',
                ],
                'condition' => [
                    'pagination_fraction_offset_orientation_v' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_fraction_z_index',
            [
                'label' => esc_html__('Z-Index', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination' => 'z-index: {{VALUE}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'tabs_pagination_fraction_style'
        );

        $this->start_controls_tab(
            'style_pagination_fraction_normal_tab',
            [
                'label' => esc_html__('Normal', 'element-camp'),
            ]
        );

        $this->add_control(
            'pagination_fraction_color',
            [
                'label' => esc_html__('Text Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-fraction' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'pagination_fraction_typography',
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-fraction',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'pagination_fraction_text_stroke',
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-fraction',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'pagination_fraction_text_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-fraction',
            ]
        );

        $this->add_control(
            'pagination_fraction_blend_mode',
            [
                'label' => esc_html__('Blend Mode', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Normal', 'element-camp'),
                    'multiply' => 'Multiply',
                    'screen' => 'Screen',
                    'overlay' => 'Overlay',
                    'darken' => 'Darken',
                    'lighten' => 'Lighten',
                    'color-dodge' => 'Color Dodge',
                    'saturation' => 'Saturation',
                    'color' => 'Color',
                    'difference' => 'Difference',
                    'exclusion' => 'Exclusion',
                    'hue' => 'Hue',
                    'luminosity' => 'Luminosity',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-fraction' => 'mix-blend-mode: {{VALUE}}',
                ],
                'separator' => 'none',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'style_pagination_fraction_current_tab',
            [
                'label' => esc_html__('Current', 'element-camp'),
            ]
        );

        $this->add_control(
            'pagination_fraction_current_color',
            [
                'label' => esc_html__('Text Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-fraction .swiper-pagination-current' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'pagination_fraction_current_typography',
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-fraction .swiper-pagination-current',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'pagination_fraction_current_text_stroke',
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-fraction .swiper-pagination-current',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'pagination_fraction_current_text_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-fraction .swiper-pagination-current',
            ]
        );

        $this->add_control(
            'pagination_fraction_current_blend_mode',
            [
                'label' => esc_html__('Blend Mode', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Normal', 'element-camp'),
                    'multiply' => 'Multiply',
                    'screen' => 'Screen',
                    'overlay' => 'Overlay',
                    'darken' => 'Darken',
                    'lighten' => 'Lighten',
                    'color-dodge' => 'Color Dodge',
                    'saturation' => 'Saturation',
                    'color' => 'Color',
                    'difference' => 'Difference',
                    'exclusion' => 'Exclusion',
                    'hue' => 'Hue',
                    'luminosity' => 'Luminosity',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-fraction .swiper-pagination-current' => 'mix-blend-mode: {{VALUE}}',
                ],
                'separator' => 'none',
            ]
        );

        $this->add_responsive_control(
            'pagination_fraction_current_margin',
            [
                'label' => esc_html__('Current Margin', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-fraction .swiper-pagination-current' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'style_pagination_fraction_total_tab',
            [
                'label' => esc_html__('Total', 'element-camp'),
            ]
        );

        $this->add_control(
            'pagination_fraction_total_color',
            [
                'label' => esc_html__('Text Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-fraction .swiper-pagination-total' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'pagination_fraction_total_typography',
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-fraction .swiper-pagination-total',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'pagination_fraction_total_text_stroke',
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-fraction .swiper-pagination-total',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'pagination_fraction_total_text_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-fraction .swiper-pagination-total',
            ]
        );

        $this->add_control(
            'pagination_fraction_total_blend_mode',
            [
                'label' => esc_html__('Blend Mode', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Normal', 'element-camp'),
                    'multiply' => 'Multiply',
                    'screen' => 'Screen',
                    'overlay' => 'Overlay',
                    'darken' => 'Darken',
                    'lighten' => 'Lighten',
                    'color-dodge' => 'Color Dodge',
                    'saturation' => 'Saturation',
                    'color' => 'Color',
                    'difference' => 'Difference',
                    'exclusion' => 'Exclusion',
                    'hue' => 'Hue',
                    'luminosity' => 'Luminosity',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-fraction .swiper-pagination-total' => 'mix-blend-mode: {{VALUE}}',
                ],
                'separator' => 'none',
            ]
        );

        $this->add_responsive_control(
            'pagination_fraction_total_margin',
            [
                'label' => esc_html__('Total Margin', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-pagination-fraction .swiper-pagination-total' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_scroll_bar_style',
            [
                'label' => __('Scroll Bar Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'scrollbar' => 'true',
                ]
            ]
        );

        $this->add_control(
            'pagination_scroll_bar_position',
            [
                'label' => esc_html__('Scroll Bar Position', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'pagination_scroll_bar_offset_orientation_h',
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
            'pagination_scroll_bar_offset_x',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-shop-categories-slider .swiper-scrollbar.scrollbar-vertical, body:not(.rtl) {{WRAPPER}} .tcgelements-shop-categories-slider .swiper-scrollbar.scrollbar-horizontal' => 'left: {{SIZE}}{{UNIT}}; transform: translateX(-50%);',
                    'body.rtl {{WRAPPER}} .tcgelements-shop-categories-slider .swiper-scrollbar.scrollbar-vertical, {{WRAPPER}} body.rtl .tcgelements-shop-categories-slider .swiper-scrollbar.scrollbar-horizontal' => 'right: {{SIZE}}{{UNIT}}; transform: translateX(50%);',
                ],
                'condition' => [
                    'pagination_scroll_bar_offset_orientation_h!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_scroll_bar_offset_x_end',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-shop-categories-slider .swiper-scrollbar.scrollbar-vertical, body:not(.rtl) {{WRAPPER}} .tcgelements-shop-categories-slider .swiper-scrollbar.scrollbar-horizontal' => 'right: {{SIZE}}{{UNIT}}; transform: translateX(50%);',
                    'body.rtl {{WRAPPER}} .tcgelements-shop-categories-slider .swiper-scrollbar.scrollbar-vertical, {{WRAPPER}} body.rtl .tcgelements-shop-categories-slider .swiper-scrollbar.scrollbar-horizontal' => 'left: {{SIZE}}{{UNIT}}; transform: translateX(-50%);',
                ],
                'condition' => [
                    'pagination_scroll_bar_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_control(
            'pagination_scroll_bar_offset_orientation_v',
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
            'pagination_scroll_bar_offset_y',
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-scrollbar.scrollbar-vertical, {{WRAPPER}} .tcgelements-shop-categories-slider .swiper-scrollbar.scrollbar-horizontal' => 'top: {{SIZE}}{{UNIT}}; bottom: unset; transform: translateY(50%);',
                ],
                'condition' => [
                    'pagination_scroll_bar_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_scroll_bar_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-scrollbar.scrollbar-vertical, {{WRAPPER}} .tcgelements-shop-categories-slider .swiper-scrollbar.scrollbar-horizontal' => 'bottom: {{SIZE}}{{UNIT}}; top: unset; transform: translateY(-50%);',
                ],
                'condition' => [
                    'pagination_scroll_bar_offset_orientation_v' => 'end',
                ],
            ]
        );

        $this->add_control(
            'pagination_scroll_bar_bg',
            [
                'label' => esc_html__('Scroll Bar Background', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'scroll_bar_progress',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-scrollbar .swiper-scrollbar-drag',
                'exclude' => ['image'],
                'fields_options' => [
                    'background' => [
                        'label' => esc_html_x('Progress Bar Color', 'Background Control', 'element-camp'),
                    ]
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'scroll_bar_background',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-scrollbar',
                'exclude' => ['image'],
                'fields_options' => [
                    'background' => [
                        'label' => esc_html_x('Progress Bar Background', 'Background Control', 'element-camp'),
                    ]
                ]
            ]
        );

        $this->add_control(
            'pagination_scroll_bar_styling',
            [
                'label' => esc_html__('Scroll Bar Styling', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'pagination_scroll_bar_width',
            [
                'label' => esc_html__('Width', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-scrollbar.scrollbar-horizontal, {{WRAPPER}} .tcgelements-shop-categories-slider .swiper-scrollbar.scrollbar-horizontal .swiper-scrollbar-drag' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-scrollbar.scrollbar-vertical, {{WRAPPER}} .tcgelements-shop-categories-slider .swiper-scrollbar.scrollbar-vertical .swiper-scrollbar-drag' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->add_responsive_control(
            'scroll_bar_margin',
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-scrollbar.swiper-scrollbar-horizontal' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; width: calc(100% - ({{RIGHT}}{{UNIT}} + {{LEFT}}{{UNIT}}));',
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-scrollbar.swiper-scrollbar-vertical' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; height: calc(100% - (({{TOP}}{{UNIT}} + {{BOTTOM}}{{UNIT}}) * 3));',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'scroll_bar_border',
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-scrollbar',
            ]
        );

        $this->add_responsive_control(
            'scroll_bar_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-scrollbar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .swiper-scrollbar .swiper-scrollbar-drag' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'arrows_pagination_container_style',
            [
                'label' => __('Arrows Pagination Container Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'arrows_pagination_container' => 'true',
                ]
            ]
        );

        $this->add_control(
            'arrows_pagination_container_bg',
            [
                'label' => esc_html__('Container Background', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'arrows_pagination_container_background',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .arrows-pagination-container',
                'exclude' => ['image']
            ]
        );

        $this->add_control(
            'arrows_pagination_container_blur_method',
            [
                'label' => esc_html__('Blur Method', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'backdrop-filter' => 'backdrop-filter',
                    'filter' => 'filter',
                ],
                'default' => 'backdrop-filter',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .arrows-pagination-container' => '{{VALUE}}: blur({{arrows_pagination_container_blur_value.SIZE}}px);',
                ],
            ]
        );
        $this->add_control(
            'arrows_pagination_container_blur_value',
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

        $this->add_control(
            'arrows_pagination_container_position',
            [
                'label' => esc_html__('Container Position', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'arrows_pagination_container_positioning',
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .arrows-pagination-container' => 'position: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrows_pagination_container_offset_orientation_h',
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
            'arrows_pagination_container_offset_x',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-shop-categories-slider .arrows-pagination-container' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-shop-categories-slider .arrows-pagination-container' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'arrows_pagination_container_offset_orientation_h!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrows_pagination_container_offset_x_end',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-shop-categories-slider .arrows-pagination-container' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-shop-categories-slider .arrows-pagination-container' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'arrows_pagination_container_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_control(
            'arrows_pagination_container_offset_orientation_v',
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
            'arrows_pagination_container_offset_y',
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .arrows-pagination-container' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'arrows_pagination_container_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrows_pagination_container_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .arrows-pagination-container' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'arrows_pagination_container_offset_orientation_v' => 'end',
                ],
            ]
        );

        $this->add_control(
            'arrows_pagination_container_transform_options',
            [
                'label' => esc_html__('Arrows Pagination Container Transform Options', 'element-camp'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'element-camp'),
                'label_on' => esc_html__('Custom', 'element-camp'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );
        $this->start_popover();
        $this->add_control(
            'arrows_pagination_container_translate_x',
            [
                'label' => esc_html__('Arrows Pagination Container Translate X', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .arrows-pagination-container' => '--e-transform-tcgelements-shop-categories-slider-arrows-pagination-container-translateX: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_control(
            'arrows_pagination_container_translate_y',
            [
                'label' => esc_html__('Arrows Pagination Container Translate Y', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .arrows-pagination-container' => '--e-transform-tcgelements-shop-categories-slider-arrows-pagination-container-translateY: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->end_popover();

        $this->add_control(
            'arrows_pagination_container_styling',
            [
                'label' => esc_html__('Container Styling', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'arrows_pagination_container_width',
            [
                'label' => esc_html__('Width', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .arrows-pagination-container' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrows_pagination_container_height',
            [
                'label' => esc_html__('Height', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .arrows-pagination-container' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'arrows_pagination_container_border',
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .arrows-pagination-container',
            ]
        );

        $this->add_responsive_control(
            'arrows_pagination_container_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .arrows-pagination-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'arrows_pagination_container_box_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-shop-categories-slider .arrows-pagination-container',
            ]
        );

        $this->add_responsive_control(
            'arrows_pagination_container_padding',
            [
                'label' => esc_html__('Padding', 'element-camp'),
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
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .arrows-pagination-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrows_pagination_container_margin',
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
                    '{{WRAPPER}} .tcgelements-shop-categories-slider .arrows-pagination-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render shop categories widget output on the frontend.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        // Check if WooCommerce is active
        if (!$this->is_woocommerce_active()) {
            echo '<div class="elementor-alert elementor-alert-warning">';
            echo esc_html__('WooCommerce is not active. Please install and activate WooCommerce to use this widget.', 'elementcamp_plg');
            echo '</div>';
            return;
        }

        $args = [
            'taxonomy'     => 'product_cat',
            'orderby'      => $settings['orderby'],
            'order'        => $settings['order'],
            'hide_empty'   => ('yes' === $settings['hide_empty']),
            'number'       => $settings['number'],
            'hierarchical' => true,
        ];

        if (!empty($settings['include_categories'])) {
            $args['include'] = $settings['include_categories'];
        }

        if (!empty($settings['exclude_categories'])) {
            $args['exclude'] = $settings['exclude_categories'];
        }

        $product_categories = get_terms($args);

        if (empty($product_categories) || is_wp_error($product_categories)) {
            echo '<div class="elementor-alert elementor-alert-info">';
            echo esc_html__('No product categories found.', 'elementcamp_plg');
            echo '</div>';
            return;
        }

        $display_mode = $settings['display_mode'];

        if ($display_mode === 'slider') {
            // Slider mode
            $responsive_settings = [];
            foreach ($settings['responsive_settings'] as $item) {
                $responsive_settings[$item['breakpoint']] = [
                    'slidesPerView' => $item['slidesPerView'],
                    'spaceBetween' => $item['spaceBetween'],
                ];
            }

            $slider_settings = [
                'breakpoints' => $responsive_settings,
                'speed' => $settings['speed'],
                'direction' => $settings['direction'],
                'effect' => $settings['effect'],
                'loop' => $settings['loop'],
                'centeredSlides' => $settings['centeredSlides'],
                'paginationType' => $settings['paginationType'],
            ];

            if ($settings['autoplay'] === 'true') {
                $slider_settings['autoplay'] = [
                    'delay' => $settings['autoplay_delay'],
                ];
            }

            $slider_id = uniqid('shop-categories-slider-', true);
            ?>
            <div id="<?php echo esc_attr($slider_id); ?>" class="tcgelements-shop-categories tcgelements-shop-categories-slider" data-slider-settings='<?php echo esc_attr(json_encode($slider_settings)); ?>'>
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <?php foreach ($product_categories as $category) :
                            $category_link = get_term_link($category, 'product_cat');
                            $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                            $image_url = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : wc_placeholder_img_src();
                            ?>
                            <div class="swiper-slide">
                                <a href="<?php echo esc_url($category_link); ?>" class="shop-category">
                                    <div class="img">
                                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($category->name); ?>">
                                    </div>
                                    <h6 class="title"><?php echo esc_html($category->name); ?></h6>
                                    <?php if ($settings['show_count'] === 'yes') : ?>
                                        <div class="count"><?php echo __($settings['count_before_text'],'element-camp'); ?><?php echo esc_html($category->count); ?> <?php echo __($settings['count_text'],'element-camp'); ?></div>
                                    <?php endif; ?>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php if ($settings['pagination'] === 'true') : ?>
                    <div class="swiper-pagination"></div>
                <?php endif; ?>

                <?php if ($settings['arrows'] === 'true') : ?>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                <?php endif; ?>
            </div>
            <?php
        } else {
            // Grid mode (default)
            ?>
            <div class="tcgelements-shop-categories">
                <div class="row">
                    <?php foreach ($product_categories as $category) :
                        $category_link = get_term_link($category, 'product_cat');
                        $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                        $image_url = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : wc_placeholder_img_src();
                        $column_class = $this->get_column_classes($settings);
                        ?>
                        <div class="<?php echo esc_attr($column_class); ?>">
                            <a href="<?php echo esc_url($category_link); ?>" class="shop-category">
                                <div class="img">
                                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($category->name); ?>">
                                </div>
                                <h6 class="title"><?php echo esc_html($category->name); ?></h6>
                                <?php if ($settings['show_count'] === 'yes') : ?>
                                    <div class="count"><?php echo __($settings['count_before_text'],'element-camp'); ?><?php echo esc_html($category->count); ?> <?php echo __($settings['count_text'],'element-camp'); ?></div>
                                <?php endif; ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php
        }
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