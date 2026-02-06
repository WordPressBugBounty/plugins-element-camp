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

if (!defined('ABSPATH')) exit; // Exit if accessed directly



/**
 * @since 1.0.0
 */
class ElementCamp_Button extends Widget_Base
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
        return 'tcgelements-button';
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
        return esc_html__('Button', 'element-camp');
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
        return 'eicon-button tce-widget-badge';
    }
    public function get_script_depends()
    {
        return ['tcgelements-button', 'lity'];
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
        $start = is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');
        $end = !is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');

        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Button Settings', 'element-camp'),
            ]
        );
        $this->add_control(
            'btn_text',
            [
                'label' => esc_html__('Button Text', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'description' => esc_html__('Note: use <small>...</small> to apply another text style', 'element-camp'),
                'label_block' => true,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __('Click me', 'element-camp'),
            ]
        );
        $this->add_control(
            'link_to',
            [
                'label' => esc_html__('Link To', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'element-camp'),
                    'custom' => esc_html__('Custom URL', 'element-camp'),
                    'post-url' => esc_html__('Post URL', 'element-camp'),
                    'site-url' => esc_html__('Site URL', 'element-camp'),
                    'woo-product' => esc_html__('Add to Cart (Woocommerce Query)', 'element-camp'),
                    'scroll-top' => esc_html__('Scroll to Top', 'element-camp'),
                ],
                'default' => 'custom',
            ]
        );
        $this->add_control(
            'custom_panel_alert',
            [
                'type' => \Elementor\Controls_Manager::ALERT,
                'alert_type' => 'warning',
                'heading' => esc_html__('You are using Link Type Product', 'element-camp'),
                'content' => esc_html__('This type can only be used inside a dynamic query block.', 'element-camp'),
                'condition' => [
                    'link_to' => 'woo-product'
                ]
            ]
        );
        $this->add_control(
            'adding_text',
            [
                'label' => esc_html__('Adding to Cart Text', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Loading',
                'separator' => 'before',
                'condition' => [
                    'link_to' => 'woo-product'
                ]
            ]
        );
        $this->add_control(
            'added_text',
            [
                'label' => esc_html__('Added to Cart Text', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Added',
                'separator' => 'before',
                'condition' => [
                    'link_to' => 'woo-product'
                ]
            ]
        );
        $this->add_control(
            'link',
            [
                'label' => esc_html__('Button Link', 'element-camp'),
                'type' => Controls_Manager::URL,
                'placeholder' => 'Leave Link here',
                'condition' => ['link_to' => 'custom'],
            ]
        );
        $this->add_responsive_control(
            'button_alignment',
            [
                'label' => esc_html__('Button Alignment', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'element-camp'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'element-camp'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'element-camp'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'prefix_class' => 'elementor-align-',
                'default' => 'left',
            ]
        );

        if (class_exists('WooCommerce')) {
            $this->add_control(
                'show_products_count',
                [
                    'label' => esc_html__('Show Products Count', 'element-camp'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => esc_html__('On', 'element-camp'),
                    'label_off' => esc_html__('Off', 'element-camp'),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );
            $this->add_control(
                'before_count_text',
                [
                    'label' => esc_html__('Before Count Text', 'element-camp'),
                    'default' => esc_html__('(', 'element-camp'),
                    'type' => Controls_Manager::TEXT,
                    'condition' => [
                        'show_products_count' => 'yes',
                    ]
                ]
            );
            $this->add_control(
                'after_count_text',
                [
                    'label' => esc_html__('After Count Text', 'element-camp'),
                    'default' => esc_html__(')', 'element-camp'),
                    'type' => Controls_Manager::TEXT,
                    'condition' => [
                        'show_products_count' => 'yes',
                    ]
                ]
            );
        }

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

        $this->add_control(
            'icon_indent',
            [
                'label' => esc_html__('Icon Spacing', 'element-camp'),
                'size_units' => ['px', '%', 'rem', 'em', 'custom'],
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-button .tcgelements-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_switcher',
            [
                'label' => esc_html__('Image', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'element-camp'),
                'label_off' => esc_html__('Off', 'element-camp'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'image_position',
            [
                'label' => esc_html__('Image Position', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'element-camp'),
                        'icon' => 'eicon-order-start',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'element-camp'),
                        'icon' => 'eicon-order-end',
                    ],
                ],
                'default' => 'left',
                'condition' => ['image_switcher' => 'yes']
            ]
        );
        $this->add_control(
            'image',
            [
                'label' => esc_html__('Choose Image', 'element-camp'),
                'type' => Controls_Manager::MEDIA,
                'condition' => ['image_switcher' => 'yes'],
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'play_button',
            [
                'label' => esc_html__('Play Video In Pop Up', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'element-camp'),
                'label_off' => esc_html__('Off', 'element-camp'),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => ['link_to' => 'custom']
            ]
        );

        $this->add_control(
            'view',
            [
                'label' => esc_html__('View', 'element-camp'),
                'type' => Controls_Manager::HIDDEN,
                'default' => 'traditional',
            ]
        );

        $this->add_control(
            'button_transition_switcher',
            [
                'label' => esc_html__('Turn On Button Transition', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'element-camp'),
                'label_off' => esc_html__('Off', 'element-camp'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'button_transition',
            [
                'label' => esc_html__('Button Transition', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button' => 'transition: all {{SIZE}}s ease;',
                ],
                'condition' => ['button_transition_switcher' => 'yes']
            ]
        );
        $this->add_control(
            'button_css_id',
            [
                'label' => esc_html__('Button ID', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => '',
                'title' => esc_html__('Add your custom id WITHOUT the Pound key. e.g: my-id', 'element-camp'),
                'description' => sprintf(
                    /* translators: %1$s Code open tag, %2$s: Code close tag. */
                    esc_html__('Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows %1$sA-z 0-9%2$s & underscore chars without spaces.', 'element-camp'),
                    '<code>',
                    '</code>'
                ),
                'separator' => 'before',

            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__('Button', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'button_style_heading',
            [
                'label' => esc_html__('Button Options', 'element-camp'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'button_overflow',
            [
                'label' => esc_html__('Button Overflow', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'hidden' => esc_html__('Hidden', 'element-camp'),
                    'visible' => esc_html__('Visible', 'element-camp'),
                ],
                'label_block' => true,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button' => 'overflow: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_width',
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
                    '{{WRAPPER}} .tcgelements-button' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_max_width',
            [
                'label' => esc_html__('Max Width', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-button' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_max_height',
            [
                'label' => esc_html__('Max Height', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-button' => 'max-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_width_hover',
            [
                'label' => esc_html__('Width Hover', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-button:hover' => 'width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_height',
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
                'size_units' => ['px', 'vh', '%', 'custom'],
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
                    '{{WRAPPER}} .tcgelements-button' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'text_padding',
            [
                'label' => esc_html__('Button Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'button_display',
            [
                'label' => esc_html__('Button Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'inline-block',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button' => 'display: {{VALUE}};'
                ]
            ]
        );

        $this->add_responsive_control(
            'justify_content',
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
                    '{{WRAPPER}} .tcgelements-button' => 'justify-content: {{VALUE}};',
                ],
                'condition' => ['button_display' => 'flex'],
                'responsive' => true,
            ]
        );

        $this->add_responsive_control(
            'align_items',
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
                    '{{WRAPPER}} .tcgelements-button' => 'align-items: {{VALUE}};',
                ],
                'condition' => ['button_display' => 'flex'],
                'responsive' => true,
            ]
        );

        $this->add_control(
            'separator_border2',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_control(
            'button_additional_heading',
            [
                'label' => esc_html__('Button Additional Options', 'element-camp'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'button_content_wrapper_display',
            [
                'label' => esc_html__('Button Content Wrapper Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'inline-block',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                    'inline-flex' => esc_html__('Inline Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-content-wrapper' => 'display: {{VALUE}};'
                ]
            ]
        );

        $this->add_responsive_control(
            'content_justify_content',
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
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-content-wrapper' => 'justify-content: {{VALUE}};',
                ],
                'condition' => ['button_content_wrapper_display' => ['flex', 'inline-flex']],
                'responsive' => true,
            ]
        );

        $this->add_responsive_control(
            'content_align_items',
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
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-content-wrapper' => 'align-items: {{VALUE}};',
                ],
                'condition' => ['button_content_wrapper_display' => ['flex', 'inline-flex']],
                'responsive' => true,
            ]
        );

        $this->add_responsive_control(
            'button_content_width',
            [
                'label' => esc_html__('Button Content Wrapper Width', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-content-wrapper' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_content_height',
            [
                'label' => esc_html__('Button Content Wrapper Height', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-content-wrapper' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_content_rotate',
            [
                'label' => esc_html__('Content Rotate', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['deg', 'custom'],
                'default' => [
                    'unit' => 'deg',
                    'size' => 0,
                ],
                'range' => [
                    'deg' => [
                        'min' => -360,
                        'max' => 360,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-content-wrapper' => 'rotate: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'separator_border',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'text_stroke',
                'selector' => '{{WRAPPER}} .tcgelements-button',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'text_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-button',
            ]
        );
        $this->add_control(
            'text_break_line',
            [
                'label' => esc_html__('Responsive Break Line', 'element-camp'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'default' => esc_html__('yes', 'element-camp'),
            ]
        );
        $this->start_controls_tabs('tabs_button_style');

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => esc_html__('Normal', 'element-camp'),
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_ACCENT,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-button',
            ]
        );

        $this->add_control(
            'button_text_color_type',
            [
                'label' => esc_html__('Text color type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'solid' => 'Solid',
                    'gradient' => 'Gradient',
                ],
                'default' => 'solid',
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => esc_html__('Text Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
                'condition' => [
                    'button_text_color_type' => 'solid'
                ]
            ]
        );

        $this->add_control(
            'button_text_gradient_bg_color1',
            [
                'label' => _x('First Color', 'Background Control', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'title' => _x('First Color', 'Background Control', 'element-camp'),
                'render_type' => 'ui',
                'condition' => [
                    'button_text_color_type' => ['gradient'],
                ],
                'of_type' => 'gradient',
            ]
        );


        $this->add_control(
            'button_text_gradient_bg_color1_stop',
            [
                'label' => _x('Location', 'Background Control', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'default' => [
                    'unit' => '%',
                    'size' => 0,
                ],
                'render_type' => 'ui',
                'condition' => [
                    'button_text_color_type' => ['gradient'],
                ],
                'of_type' => 'gradient',
            ]
        );

        $this->add_control(
            'button_text_gradient_bg_color2',
            [
                'label' => _x('Second Color', 'Background Control', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f2295b',
                'render_type' => 'ui',
                'condition' => [
                    'button_text_color_type' => ['gradient'],
                ],
                'of_type' => 'gradient',
            ]
        );

        $this->add_control(
            'button_text_gradient_bg_color2_stop',
            [
                'label' => _x('Location', 'Background Control', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'render_type' => 'ui',
                'condition' => [
                    'button_text_color_type' => ['gradient'],
                ],
                'of_type' => 'gradient',
            ]
        );

        $this->add_control(
            'button_text_gradient_type',
            [
                'label' => _x('Type', 'Background Control', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'linear' => _x('Linear', 'Background Control', 'element-camp'),
                    'radial' => _x('Radial', 'Background Control', 'element-camp'),
                ],
                'default' => 'linear',
                'render_type' => 'ui',
                'condition' => [
                    'button_text_color_type' => ['gradient'],
                ],
                'of_type' => 'gradient',
            ]
        );

        $this->add_control(
            'button_text_gradient_angle',
            [
                'label' => _x('Angle', 'Background Control', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['deg'],
                'default' => [
                    'unit' => 'deg',
                    'size' => 180,
                ],
                'range' => [
                    'deg' => [
                        'step' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-text' => 'background-image: linear-gradient({{SIZE}}{{UNIT}}, {{button_text_gradient_bg_color1.VALUE}} {{button_text_gradient_bg_color1_stop.SIZE}}{{button_text_gradient_bg_color1_stop.UNIT}},{{button_text_gradient_bg_color2.VALUE}} {{button_text_gradient_bg_color2_stop.SIZE}}{{button_text_gradient_bg_color2_stop.UNIT}}); -webkit-background-clip: text; -webkit-text-fill-color: transparent;',
                ],

                'condition' => [
                    'button_text_color_type' => ['gradient'],
                    'button_text_gradient_type' => 'linear',
                ],
                'of_type' => 'gradient',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'background',
                'label' => esc_html__('Background', 'element-camp'),
                'types' => ['classic', 'gradient','tcg_gradient','tcg_gradient_4'],
                'selector' => '{{WRAPPER}} .tcgelements-button, {{WRAPPER}} .tcgelements-button.reverse .btn-animated-gr',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'color' => [
                        'selectors' => [
                            '{{SELECTOR}}' => 'background: {{color.VALUE}}; background-image: none;',
                        ],
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-button',
            ]
        );

        $this->add_responsive_control(
            'button_opacity',
            [
                'label' => esc_html__('Button Opacity', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'selector' => '{{WRAPPER}} .tcgelements-button',
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'border_gradient',
                'label' => esc_html__('Border Gradient Color', 'element-camp'),
                'types' => ['gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-button',
                'exclude' => ['image'],
                'fields_options' => [
                    'gradient_type' => [
                        'default' => 'radial',
                        'type' => Controls_Manager::HIDDEN,
                    ],
                    'color' => [
                        'selectors' => [
                            '{{SELECTOR}}' => 'background-color: none;',
                        ],
                    ],
                    'gradient_angle' => [
                        'selectors' => [
                            '{{SELECTOR}}' => 'border-image-source: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
                        ],
                        'type' => Controls_Manager::HIDDEN,
                    ],
                    'gradient_position' => [
                        'selectors' => [
                            '{{SELECTOR}}' => 'border-image-slice: 1; border-image-source: radial-gradient(circle farthest-corner at 10% 20%, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-button .btn-animated-gr' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'outline',
            [
                'label' => esc_html__('Outline', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'element-camp'),
                    'solid' => esc_html__('Solid', 'element-camp'),
                    'dashed' => esc_html__('Dashed', 'element-camp'),
                    'dotted' => esc_html__('Dotted', 'element-camp'),
                ],
                'default' => 'none',
            ]
        );

        $this->add_control(
            'outline_color',
            [
                'label' => esc_html__('Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'outline!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'outline_width',
            [
                'label' => esc_html__('Outline Width', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button' => 'outline: {{outline_width.SIZE}}{{outline_width.UNIT}} {{outline.VALUE}} {{outline_color.VALUE}}',
                ],
                'condition' => [
                    'outline!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'outline_offset',
            [
                'label' => esc_html__('Outline Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 20,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button' => 'outline-offset: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'outline!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_translate_y',
            [
                'label' => esc_html__('Translate Y', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
            ]
        );
        $this->add_responsive_control(
            'button_translate_x',
            [
                'label' => esc_html__('Translate X', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button' => 'transform: translate({{button_translate_x.SIZE}}{{button_translate_x.UNIT}},{{button_translate_y.SIZE}}{{button_translate_y.UNIT}}) scale({{button_scale.SIZE||1}})',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_scale',
            [
                'label' => esc_html__('Scale', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default' => [
                    'size' => 1,
                    'unit' => '',
                ],
                'range' => [
                    'px' => [
                        'min' => 0.1,
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
            ]
        );
        $this->add_control(
            'button_blur_method',
            [
                'label' => esc_html__('Blur Method', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'backdrop-filter' => 'backdrop-filter',
                    'filter' => 'filter',
                ],
                'default' => 'backdrop-filter',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button' => '{{VALUE}}: blur({{blur_value.SIZE}}px);',
                ],
            ]
        );
        $this->add_control(
            'blur_value',
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
            'button_style_dark_mode',
            [
                'label' => esc_html__('Button Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'button_border_color_dark_mode',
            [
                'label' => esc_html__('Border Color ( Dark Mode )', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-button' => 'border-color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-button' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'button_text_color_type' => 'solid'
                ]
            ]
        );

        $this->add_control(
            'button_text_color_dark_mode',
            [
                'label' => esc_html__('Text Color (Dark Mode)', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-button' => 'color: {{VALUE}};fill: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-button' => 'color: {{VALUE}};fill: {{VALUE}};',
                ],
                'condition' => [
                    'button_text_color_type' => 'solid'
                ]
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'background_dark_mode',
                'selector' => '{{WRAPPER}} .tcgelements-button, {{WRAPPER}} .tcgelements-button.reverse .btn-animated-gr',
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
            'tab_button_hover',
            [
                'label' => esc_html__('Advanced Hover', 'element-camp'),
            ]
        );

        $this->add_control(
            'button_hover_selector',
            [
                'label' => esc_html__('Choose Selector', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'button',
                'options' => [
                    'button' => esc_html__('Button', 'element-camp'),
                    'container'  => esc_html__('Parent Container', 'element-camp'),
                    'parent-container'  => esc_html__('Parent of Parent Container', 'element-camp'),
                    'parent-n'  => esc_html__('Parent N', 'element-camp'),
                ],
            ]
        );

        $this->add_control(
            'button_hover_selector_important_note',
            [
                'type' => \Elementor\Controls_Manager::ALERT,
                'alert_type' => 'info',
                'heading' => esc_html__('Selector Note', 'element-camp'),
                'content' => esc_html__('When "Parent N" is selected, set the number of parent elements to target.', 'element-camp'),
                'condition' => [
                    'button_hover_selector' => 'parent-n',
                ],
            ]
        );

        $this->add_control(
            'parent_level',
            [
                'label' => esc_html__('Set Parent Levels', 'element-camp'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'default' => 1,
                'condition' => [
                    'button_hover_selector' => 'parent-n',
                ],
            ]
        );

        $this->add_control(
            'hover_color',
            [
                'label' => esc_html__('Text Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button:hover, {{WRAPPER}} .tcgelements-button:focus' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-button:hover svg, {{WRAPPER}} .tcgelements-button:focus svg' => 'fill: {{VALUE}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active' => 'color: {{VALUE}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'button_background_hover',
                'label' => esc_html__('Background', 'element-camp'),
                'types' => ['classic', 'gradient','tcg_gradient','tcg_gradient_4'],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .tcgelements-button:hover, {{WRAPPER}} .tcgelements-button:focus, {{WRAPPER}} .tcgelements-button .btn-animated-gr, {{WRAPPER}} .tcgelements-button:focus .btn-animated-gr, .e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active, .e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active .btn-animated-gr',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'color' => [
                        'selectors' => [
                            '{{SELECTOR}}' => 'background: {{color.VALUE}}; background-image: none;',
                        ],
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'border_hover',
                'selector' => '{{WRAPPER}} .tcgelements-button:hover, .e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active',
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'border_gradient_hover',
                'label' => esc_html__('Border Gradient Color', 'element-camp'),
                'types' => ['gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-button:hover, .e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active',
                'exclude' => ['image'],
                'fields_options' => [
                    'gradient_type' => [
                        'default' => 'radial',
                        'type' => Controls_Manager::HIDDEN,
                    ],
                    'color' => [
                        'selectors' => [
                            '{{SELECTOR}}' => 'background-color: none;',
                        ],
                    ],
                    'gradient_angle' => [
                        'selectors' => [
                            '{{SELECTOR}}' => 'border-image-source: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
                        ],
                        'type' => Controls_Manager::HIDDEN,
                    ],
                    'gradient_position' => [
                        'selectors' => [
                            '{{SELECTOR}}' => 'border-image-slice: 1; border-image-source: radial-gradient(circle farthest-corner at 10% 20%, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-button:hover .btn-animated-gr' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active .btn-animated-gr' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow_hover',
                'selector' => '{{WRAPPER}} .tcgelements-button:hover, .e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active',
            ]
        );

        $this->add_responsive_control(
            'button_opacity_hover',
            [
                'label' => esc_html__('Button Opacity', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button:hover' => 'opacity: {{SIZE}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_control(
            'outline_hover',
            [
                'label' => esc_html__('Outline', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'element-camp'),
                    'solid' => esc_html__('Solid', 'element-camp'),
                    'dashed' => esc_html__('Dashed', 'element-camp'),
                    'dotted' => esc_html__('Dotted', 'element-camp'),
                ],
                'default' => 'none',
            ]
        );

        $this->add_control(
            'outline_color_hover',
            [
                'label' => esc_html__('Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'outline_hover!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'outline_width_hover',
            [
                'label' => esc_html__('Outline Width', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button:hover' => 'outline: {{outline_width_hover.SIZE}}{{outline_width_hover.UNIT}} {{outline_hover.VALUE}} {{outline_color_hover.VALUE}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active' => 'outline: {{outline_width_hover.SIZE}}{{outline_width_hover.UNIT}} {{outline_hover.VALUE}} {{outline_color_hover.VALUE}};',
                ],
                'condition' => [
                    'outline_hover!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'outline_offset_hover',
            [
                'label' => esc_html__('Outline Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 20,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button:hover' => 'outline-offset: {{SIZE}}{{UNIT}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active' => 'outline-offset: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'outline_hover!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'button_translate_y_hover',
            [
                'label' => esc_html__('Translate Y', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
            ]
        );
        $this->add_control(
            'button_translate_x_hover',
            [
                'label' => esc_html__('Translate X', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button:hover' => 'transform: translate({{button_translate_x_hover.SIZE}}{{button_translate_x_hover.UNIT}},{{button_translate_y_hover.SIZE}}{{button_translate_y_hover.UNIT}}) scale({{button_scale_hover.SIZE||1}})',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active' => 'transform: translate({{button_translate_x_hover.SIZE}}{{button_translate_x_hover.UNIT}},{{button_translate_y_hover.SIZE}}{{button_translate_y_hover.UNIT}}) scale({{button_scale_hover.SIZE||1}})',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_scale_hover',
            [
                'label' => esc_html__('Scale', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default' => [
                    'size' => 1,
                    'unit' => '',
                ],
                'range' => [
                    'px' => [
                        'min' => 0.1,
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
            ]
        );
        $this->add_control(
            'button_style_dark_mode_hover',
            [
                'label' => esc_html__('Button Hover Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'button_border_color_dark_mode_hover',
            [
                'label' => esc_html__('Border Color ( Dark Mode )', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-button:hover' => 'border-color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-button:hover' => 'border-color: {{VALUE}};',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode .e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active' => 'border-color: {{VALUE}};',
                    '} body.tcg-dark-mode .e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'button_text_color_type' => 'solid'
                ]
            ]
        );
        $this->add_control(
            'button_text_color_dark_mode_hover',
            [
                'label' => esc_html__('Text Color (Dark Mode)', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-button:hover' => 'color: {{VALUE}};fill: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-button:hover' => 'color: {{VALUE}};fill: {{VALUE}};',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode .e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active' => 'color: {{VALUE}};fill: {{VALUE}};',
                    '} body.tcg-dark-mode .e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active' => 'color: {{VALUE}};fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'button_background_dark_mode_hover',
                'selector' => '{{WRAPPER}} .tcgelements-button:hover,.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active',
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
            'tab_only_button_hover',
            [
                'label' => esc_html__('Button Hover', 'element-camp'),
            ]
        );
        $this->add_control(
            'button_hover_color',
            [
                'label' => esc_html__('Text Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button:hover, {{WRAPPER}} .tcgelements-button:focus' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-button:hover svg, {{WRAPPER}} .tcgelements-button:focus svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'button_only_background_hover',
                'label' => esc_html__('Background', 'element-camp'),
                'types' => ['classic', 'gradient','tcg_gradient','tcg_gradient_4'],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .tcgelements-button:hover, {{WRAPPER}} .tcgelements-button:focus, {{WRAPPER}} .tcgelements-button .btn-animated-gr, {{WRAPPER}} .tcgelements-button:focus .btn-animated-gr',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'color' => [
                        'selectors' => [
                            '{{SELECTOR}}' => 'background: {{color.VALUE}}; background-image: none;',
                        ],
                    ],
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'button_text_heading',
            [
                'label' => esc_html__('Button Text Options', 'element-camp'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'white_space',
            [
                'label' => esc_html__('White Space', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'normal' => esc_html__('Normal', 'element-camp'),
                    'nowrap' => esc_html__('No Wrap', 'element-camp'),
                    'break-spaces' => esc_html__('Break Spaces', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-text' => 'white-space: {{VALUE}};'
                ]
            ]
        );

        $this->add_responsive_control(
            'text_margin',
            [
                'label' => esc_html__('Text Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'text_span_padding',
            [
                'label' => esc_html__('Text Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'button_text_background',
                'label' => esc_html__('Button Text Background', 'element-camp'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-button .tcgelements-button-text',
            ]
        );
        $this->add_responsive_control(
            'text_border_radius',
            [
                'label' => esc_html__('Text Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_text_display',
            [
                'label' => esc_html__('Text Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'inline-block',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'inline' => esc_html__('Inline', 'element-camp'),
                    'none' => esc_html__('None', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-text' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_control(
            'button_text_transition',
            [
                'label' => esc_html__('Button Text Transition', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-text' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );
        $this->start_controls_tabs(
            'button_text_tabs',
        );

        $this->start_controls_tab(
            'button_text_normal_tab',
            [
                'label'   => esc_html__('Normal', 'element-camp'),
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_ACCENT,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-button .tcgelements-button-text',
            ]
        );
        $this->add_control(
            'button_text_opacity',
            [
                'label' => esc_html__('Text Opacity', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-text' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_text_indent',
            [
                'label' => esc_html__('Text Indent', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-text' => 'text-indent: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_text_rotate',
            [
                'label' => esc_html__('Rotate', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0,
                    'unit' => '',
                ],
                'size_units' => ['deg'],
            ]
        );
        $this->add_responsive_control(
            'button_text_translate_y',
            [
                'label' => esc_html__('Translate Y', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
            ]
        );
        $this->add_responsive_control(
            'button_text_translate_x',
            [
                'label' => esc_html__('Translate X', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-text' => 'transform: rotate({{button_text_rotate.SIZE}}deg) translate({{button_text_translate_x.SIZE}}{{button_text_translate_x.UNIT}},{{button_text_translate_y.SIZE}}{{button_text_translate_y.UNIT}})',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_text_transform_origin',
            [
                'label' => esc_html__('Button Text Transform Origin', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'unset' => esc_html__('None', 'element-camp'),
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
                'default' => 'unset',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-text' => 'transform-origin: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_text_hover_tab',
            [
                'label'   => esc_html__('Hover', 'element-camp'),
            ]
        );

        $this->add_control(
            'button_text_hover_selector',
            [
                'label' => esc_html__('Choose Selector', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'button',
                'options' => [
                    'button' => esc_html__('Button', 'element-camp'),
                    'container'  => esc_html__('Parent Container', 'element-camp'),
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography_hover',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_ACCENT,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-button:hover .tcgelements-button-text',
                '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-text-container-active .tcgelements-button-text',
            ],
        );

        $this->add_control(
            'button_text_opacity_hover',
            [
                'label' => esc_html__('Text Opacity', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button:hover .tcgelements-button-text' => 'opacity: {{SIZE}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-text-container-active .tcgelements-button-text' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_text_indent_hover',
            [
                'label' => esc_html__('Text Indent', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button:hover .tcgelements-button-text' => 'text-indent: {{SIZE}}{{UNIT}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-text-container-active .tcgelements-button-text' => 'text-indent: {{SIZE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_text_translate_y_hover',
            [
                'label' => esc_html__('Translate Y', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
            ]
        );
        $this->add_responsive_control(
            'button_text_translate_x_hover',
            [
                'label' => esc_html__('Translate X', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button:hover .tcgelements-button-text' => 'transform: translate({{button_text_translate_x_hover.SIZE}}{{button_text_translate_x_hover.UNIT}},{{button_text_translate_y_hover.SIZE}}{{button_text_translate_y_hover.UNIT}})',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-text-container-active .tcgelements-button-text' => 'transform: translate({{button_text_translate_x_hover.SIZE}}{{button_text_translate_x_hover.UNIT}},{{button_text_translate_y_hover.SIZE}}{{button_text_translate_y_hover.UNIT}})',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
        $this->start_controls_section(
            'section_icon_style',
            [
                'label' => esc_html__('Button Icon', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'selected_icon!' => '',
                ]
            ]
        );

        $this->add_control(
            'button_icon_display',
            [
                'label' => esc_html__('Button Icon Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'inline-block',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'inline' => esc_html__('Inline', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                    'inline-flex' => esc_html__('Inline Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon' => 'display: {{VALUE}};'
                ]
            ]
        );

        $this->add_responsive_control(
            'button_icon_justify_content',
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
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon' => 'justify-content: {{VALUE}};',
                ],
                'condition' => ['button_icon_display' => ['flex', 'inline-flex']],
                'responsive' => true,
            ]
        );

        $this->add_responsive_control(
            'button_icon_align_items',
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
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon' => 'align-items: {{VALUE}};',
                ],
                'condition' => ['button_icon_display' => ['flex', 'inline-flex']],
                'responsive' => true,
            ]
        );

        $this->add_responsive_control(
            'button_icon_size_options',
            [
                'label' => esc_html__('Size', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'default' => '',
                'options' => [
                    'none' => [
                        'title' => esc_html__('None', 'element-camp'),
                        'icon' => 'eicon-ban',
                    ],
                    'grow' => [
                        'title' => esc_html__('Grow', 'element-camp'),
                        'icon' => 'eicon-grow',
                    ],
                    'shrink' => [
                        'title' => esc_html__('Shrink', 'element-camp'),
                        'icon' => 'eicon-shrink',
                    ],
                    'custom' => [
                        'title' => esc_html__('Custom', 'element-camp'),
                        'icon' => 'eicon-ellipsis-v',
                    ],
                ],
                'selectors_dictionary' => [
                    'grow' => 'flex-grow: 1; flex-shrink: 0;',
                    'shrink' => 'flex-grow: 0; flex-shrink: 1;',
                    'custom' => '',
                    'none' => 'flex-grow: 0; flex-shrink: 0;',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon' => '{{VALUE}};',
                ],
                'responsive' => true,
            ]
        );

        $this->add_responsive_control(
            'button_icon_flex_grow',
            [
                'label' => esc_html__('Flex Grow', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon' => 'flex-grow: {{VALUE}};',
                ],
                'default' => 1,
                'placeholder' => 1,
                'responsive' => true,
                'condition' => [
                    'button_icon_size_options' => 'custom',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_icon_flex_shrink',
            [
                'label' => esc_html__('Flex Shrink', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon' => 'flex-shrink: {{VALUE}};',
                ],
                'default' => 1,
                'placeholder' => 1,
                'responsive' => true,
                'condition' => [
                    'button_icon_size_options' => 'custom',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_icon_wrapper_width',
            [
                'label' => esc_html__('Icon Wrapper Width', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'rem', 'em', '%', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_icon_wrapper_height',
            [
                'label' => esc_html__('Icon Wrapper Height', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'rem', 'em', '%', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_icon_size',
            [
                'label' => esc_html__('Icon size', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'rem', 'em', '%', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'button_icon_padding',
            [
                'label' => esc_html__('Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_button_icon_style');

        $this->start_controls_tab(
            'tab_button_icon',
            [
                'label' => esc_html__('Normal', 'element-camp'),
            ]
        );

        $this->add_control(
            'button_icon_position',
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
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon' => 'position: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_icon_offset_orientation_h',
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
                'condition' => ['button_icon_position!' => 'unset']
            ]
        );

        $this->add_responsive_control(
            'button_icon_offset_x',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-button .tcgelements-button-icon' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-button .tcgelements-button-icon' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'button_icon_offset_orientation_h!' => 'end',
                    'button_icon_position!' => 'unset'
                ],
            ]
        );

        $this->add_responsive_control(
            'button_icon_offset_x_end',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-button .tcgelements-button-icon' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-button .tcgelements-button-icon' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'button_icon_offset_orientation_h' => 'end',
                    'button_icon_position!' => 'unset'
                ],
            ]
        );

        $this->add_control(
            'button_icon_offset_orientation_v',
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
                'condition' => ['button_icon_position!' => 'unset']
            ]
        );

        $this->add_responsive_control(
            'button_icon_offset_y',
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
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'button_icon_offset_orientation_v!' => 'end',
                    'button_icon_position!' => 'unset'
                ],
            ]
        );

        $this->add_responsive_control(
            'button_icon_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'button_icon_offset_orientation_v' => 'end',
                    'button_icon_position!' => 'unset'
                ],
            ]
        );

        $this->add_control(
            'button_icon_color',
            [
                'label' => __('Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_icon_stroke_color',
            [
                'label' => esc_html__('SVG Path Stroke Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon svg path' => 'stroke: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_icon_filter_invert',
            [
                'label' => esc_html__('Icon Filter Invert', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon' => 'filter: invert({{SIZE}});',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'button_icon_background',
                'label' => esc_html__('Button Icon Background', 'element-camp'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'icon_border',
                'selector' => '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon',
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'icon_border_popover-toggle',
            [
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label' => esc_html__('Custom Border Settings', 'element-camp'),
                'label_off' => esc_html__('Default', 'element-camp'),
                'label_on' => esc_html__('Custom', 'element-camp'),
                'return_value' => 'yes',
            ]
        );

        $this->start_popover();
        $this->add_control(
            'icon_border_top_style',
            [
                'label' => esc_html__('Border Top Style', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'none' => esc_html__('None', 'element-camp'),
                    'solid' => esc_html__('Solid', 'element-camp'),
                    'double' => esc_html__('Double', 'element-camp'),
                    'dashed' => esc_html__('Dashed', 'element-camp'),
                    'dotted' => esc_html__('Dotted', 'element-camp'),
                    'groove' => esc_html__('Groove', 'element-camp'),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon' => 'border-top-style: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_border_top_color',
            [
                'label' => esc_html__('Border Top Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon' => 'border-top-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_border_top_width',
            [
                'label' => esc_html__('Border Top Width', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon' => 'border-top-width: {{SIZE}}{{UNIT}};',
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
            ]
        );
        $this->add_control(
            'border_bottom_separator_border',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );
        $this->add_control(
            'icon_border_bottom_style',
            [
                'label' => esc_html__('Border Bottom Style', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'none' => esc_html__('None', 'element-camp'),
                    'solid' => esc_html__('Solid', 'element-camp'),
                    'double' => esc_html__('Double', 'element-camp'),
                    'dashed' => esc_html__('Dashed', 'element-camp'),
                    'dotted' => esc_html__('Dotted', 'element-camp'),
                    'groove' => esc_html__('Groove', 'element-camp'),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon' => 'border-bottom-style: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_border_bottom_color',
            [
                'label' => esc_html__('Border Bottom Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon' => 'border-bottom-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_border_bottom_width',
            [
                'label' => esc_html__('Border Bottom Width', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
            ]
        );
        $this->add_control(
            'border_right_separator_border',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );
        $this->add_control(
            'icon_border_right_style',
            [
                'label' => esc_html__('Border Right Style', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'none' => esc_html__('None', 'element-camp'),
                    'solid' => esc_html__('Solid', 'element-camp'),
                    'double' => esc_html__('Double', 'element-camp'),
                    'dashed' => esc_html__('Dashed', 'element-camp'),
                    'dotted' => esc_html__('Dotted', 'element-camp'),
                    'groove' => esc_html__('Groove', 'element-camp'),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon' => 'border-right-style: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_border_right_color',
            [
                'label' => esc_html__('Border Right Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon' => 'border-right-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_border_right_width',
            [
                'label' => esc_html__('Border Right Width', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon' => 'border-right-width: {{SIZE}}{{UNIT}};',
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
            ]
        );
        $this->add_control(
            'border_left_separator_border',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );
        $this->add_control(
            'icon_border_left_style',
            [
                'label' => esc_html__('Border Left Style', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'none' => esc_html__('None', 'element-camp'),
                    'solid' => esc_html__('Solid', 'element-camp'),
                    'double' => esc_html__('Double', 'element-camp'),
                    'dashed' => esc_html__('Dashed', 'element-camp'),
                    'dotted' => esc_html__('Dotted', 'element-camp'),
                    'groove' => esc_html__('Groove', 'element-camp'),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon' => 'border-left-style: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_border_left_color',
            [
                'label' => esc_html__('Border Left Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon' => 'border-left-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_border_left_width',
            [
                'label' => esc_html__('Border Left Width', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon' => 'border-left-width: {{SIZE}}{{UNIT}};',
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
            ]
        );

        $this->end_popover();

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'icon_border_gradient',
                'label' => esc_html__('Border Gradient Color', 'element-camp'),
                'types' => ['gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon',
                'exclude' => ['image'],
                'fields_options' => [
                    'gradient_type' => [
                        'default' => 'radial',
                        'type' => Controls_Manager::HIDDEN,
                    ],
                    'color' => [
                        'selectors' => [
                            '{{SELECTOR}}' => 'background-color: none;',
                        ],
                    ],
                    'gradient_angle' => [
                        'selectors' => [
                            '{{SELECTOR}}' => 'border-image-source: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
                        ],
                        'type' => Controls_Manager::HIDDEN,
                    ],
                    'gradient_position' => [
                        'selectors' => [
                            '{{SELECTOR}}' => 'border-image-slice: 1; border-image-source: radial-gradient(circle farthest-corner at 10% 20%, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
                        ],
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_icon_box_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon',
            ]
        );

        $this->add_responsive_control(
            'button_icon_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'button_icon_outline',
            [
                'label' => esc_html__('Outline', 'themescamp-plugin'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'themescamp-plugin'),
                'label_on' => esc_html__('Custom', 'themescamp-plugin'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->start_popover();
        $this->add_control(
            'button_icon_outline_type',
            [
                'label' => esc_html__('Border Type', 'themescamp-plugin'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'themescamp-plugin'),
                    'none' => esc_html__('None', 'themescamp-plugin'),
                    'solid' => esc_html__('Solid', 'themescamp-plugin'),
                    'double' => esc_html__('Double', 'themescamp-plugin'),
                    'dotted' => esc_html__('Dotted', 'themescamp-plugin'),
                    'dashed' => esc_html__('Dashed', 'themescamp-plugin'),
                    'groove' => esc_html__('Groove', 'themescamp-plugin'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon' => 'outline-style: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_icon_outline_width',
            [
                'label' => esc_html__('Bullet Width', 'themescamp-plugin'),
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
                    'button_icon_outline_type!' => ['', 'none'],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon' => 'outline-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_icon_outline_color',
            [
                'label' => esc_html__('Border Color', 'themescamp-plugin'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon' => 'outline-color: {{VALUE}};',
                ],
                'condition' => [
                    'button_icon_outline_type!' => ['', 'none'],
                ],
            ]
        );
        $this->add_responsive_control(
            'button_icon_outline_offset',
            [
                'label' => esc_html__('Bullet Offset', 'themescamp-plugin'),
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
                    'button_icon_outline_type!' => ['', 'none'],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon' => 'outline-offset: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_popover();
        $this->add_control(
            'button_icon_transition',
            [
                'label' => esc_html__('Button Icon Transition', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon' => 'transition: all {{SIZE}}s ease;',
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon svg' => 'transition: all {{SIZE}}s ease;'
                ],
            ]
        );
        $this->add_control(
            'button_icon_translate_y',
            [
                'label' => esc_html__('Button Icon Wrapper Translate Y', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
            ]
        );
        $this->add_control(
            'button_icon_rotate',
            [
                'label' => esc_html__('Button Icon Wrapper Rotate', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['deg', 'custom'],
                'default' => [
                    'unit' => 'deg',
                    'size' => '0',
                ],
            ]
        );
        $this->add_control(
            'button_icon_translate_x',
            [
                'label' => esc_html__('Button Icon Wrapper Translate X', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon' => 'transform:  rotate({{button_icon_rotate.SIZE}}{{button_icon_rotate.UNIT}}) translate({{button_icon_translate_x.SIZE}}{{button_icon_translate_x.UNIT}},{{button_icon_translate_y.SIZE}}{{button_icon_translate_y.UNIT}})',
                ],
            ]
        );
        $this->add_control(
            'button_the_icon_transform_options',
            [
                'label' => esc_html__('Icon Transform', 'themescamp-plugin'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'themescamp-plugin'),
                'label_on' => esc_html__('Custom', 'themescamp-plugin'),
            ]
        );
        $this->start_popover();
        $this->add_control(
            'button_the_icon_translate_y_hover',
            [
                'label' => esc_html__('Button Icon Translate Y', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button:hover .tcgelements-button-icon svg' => '--e-transform-tcgelements-button-icon-translateY: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .tcgelements-button:hover .tcgelements-button-icon i' => '--e-transform-tcgelements-button-icon-translateY: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_control(
            'button_the_icon_translate_x',
            [
                'label' => esc_html__('Button Icon Translate X', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon svg' => '--e-transform-tcgelements-button-icon-translateX: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon i' => '--e-transform-tcgelements-button-icon-translateX: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_control(
            'button_the_icon_scale',
            [
                'label' => esc_html__('Button Icon Scale', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon svg' => '--e-transform-tcgelements-button-icon-scale: {{SIZE}}',
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon i' => '--e-transform-tcgelements-button-icon-scale: {{SIZE}}',
                ],
            ]
        );
        $this->end_popover();
        $this->add_control(
            'button_icon_style_dark_mode',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'button_icon_border_dark_mode',
            [
                'label' => esc_html__('Border Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-button .tcgelements-button-icon' => 'border-color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-button .tcgelements-button-icon' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'button_icon_background_dark_mode',
                'selector' => '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon',
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
            'tab_button_icon_hover',
            [
                'label' => esc_html__('Hover', 'element-camp'),
            ]
        );

        $this->add_control(
            'button_icon_position_hover',
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
                    '{{WRAPPER}} .tcgelements-button:hover .tcgelements-button-icon' => 'position: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_icon_hover_offset_orientation_h',
            [
                'label' => esc_html__('Horizontal Orientation', 'themescamp-plugin'),
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
                'condition' => ['button_icon_position_hover!' => 'unset'],
            ]
        );

        $this->add_responsive_control(
            'button_icon_hover_offset_x',
            [
                'label' => esc_html__('Offset', 'themescamp-plugin'),
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-button:hover .tcgelements-button-icon' => 'left: {{SIZE}}{{UNIT}};',
                    'body.rtl {{WRAPPER}} .tcgelements-button:hover .tcgelements-button-icon' => 'right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'button_icon_hover_offset_orientation_h!' => 'end',
                    'button_icon_position_hover!' => 'unset',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_icon_hover_offset_x_end',
            [
                'label' => esc_html__('Offset', 'themescamp-plugin'),
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-button:hover .tcgelements-button-icon' => 'right: {{SIZE}}{{UNIT}};',
                    'body.rtl {{WRAPPER}} .tcgelements-button:hover .tcgelements-button-icon' => 'left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'button_icon_hover_offset_orientation_h' => 'end',
                    'button_icon_position_hover!' => 'unset',
                ],
            ]
        );

        $this->add_control(
            'button_icon_hover_offset_orientation_v',
            [
                'label' => esc_html__('Vertical Orientation', 'themescamp-plugin'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => esc_html__('Top', 'themescamp-plugin'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'end' => [
                        'title' => esc_html__('Bottom', 'themescamp-plugin'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'render_type' => 'ui',
                'condition' => ['button_icon_position_hover!' => 'unset']
            ]
        );

        $this->add_responsive_control(
            'button_icon_hover_offset_y',
            [
                'label' => esc_html__('Offset', 'themescamp-plugin'),
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
                    '{{WRAPPER}} .tcgelements-button:hover .tcgelements-button-icon' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'button_icon_hover_offset_orientation_v!' => 'end',
                    'button_icon_position_hover!' => 'unset'
                ],
            ]
        );

        $this->add_responsive_control(
            'button_icon_hover_offset_y_end',
            [
                'label' => esc_html__('Offset', 'themescamp-plugin'),
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
                    '{{WRAPPER}} .tcgelements-button:hover .tcgelements-button-icon' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'button_icon_hover_offset_orientation_v' => 'end',
                    'button_icon_position_hover!' => 'unset'
                ],
            ]
        );

        $this->add_control(
            'button_icon_color_hover',
            [
                'label' => __('Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button:hover .tcgelements-button-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-button:hover .tcgelements-button-icon svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-button:focus .tcgelements-button-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-button:focus .tcgelements-button-icon svg' => 'fill: {{VALUE}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active .tcgelements-button-icon i' => 'color: {{VALUE}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active .tcgelements-button-icon  svg' => 'fill: {{VALUE}};',
                    '.e-con:focus .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active .tcgelements-button-icon i' => 'color: {{VALUE}};',
                    '.e-con:focus .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active .tcgelements-button-icon  svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_icon_stroke_color_hover',
            [
                'label' => esc_html__('SVG Path Stroke Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button:hover .tcgelements-button-icon svg path' => 'stroke: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_icon_filter_invert_hover',
            [
                'label' => esc_html__('Icon Filter Invert', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button:hover .tcgelements-button-icon' => 'filter: invert({{SIZE}});',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active .tcgelements-button-icon' => 'filter: invert({{SIZE}});',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'button_icon_background_hover',
                'label' => esc_html__('Button Icon Background', 'element-camp'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-button:hover .tcgelements-button-icon,.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active .tcgelements-button-icon',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'icon_border_hover',
                'selector' => '{{WRAPPER}} .tcgelements-button:hover .tcgelements-button-icon,.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active .tcgelements-button-icon',
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'icon_border_gradient_hover',
                'label' => esc_html__('Border Gradient Color', 'element-camp'),
                'types' => ['gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-button:hover .tcgelements-button-icon,.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active .tcgelements-button-icon',
                'exclude' => ['image'],
                'fields_options' => [
                    'gradient_type' => [
                        'default' => 'radial',
                        'type' => Controls_Manager::HIDDEN,
                    ],
                    'color' => [
                        'selectors' => [
                            '{{SELECTOR}}' => 'background-color: none;',
                        ],
                    ],
                    'gradient_angle' => [
                        'selectors' => [
                            '{{SELECTOR}}' => 'border-image-source: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
                        ],
                        'type' => Controls_Manager::HIDDEN,
                    ],
                    'gradient_position' => [
                        'selectors' => [
                            '{{SELECTOR}}' => 'border-image-slice: 1; border-image-source: radial-gradient(circle farthest-corner at 10% 20%, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
                        ],
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button:hover .tcgelements-button-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active .tcgelements-button-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_icon_box_shadow_hover',
                'selector' => '{{WRAPPER}} .tcgelements-button:hover .tcgelements-button-icon,.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active .tcgelements-button-icon',
            ]
        );
        $this->add_control(
            'button_icon_outline_hover',
            [
                'label' => esc_html__('Outline', 'themescamp-plugin'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'themescamp-plugin'),
                'label_on' => esc_html__('Custom', 'themescamp-plugin'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->start_popover();
        $this->add_control(
            'button_icon_outline_type_hover',
            [
                'label' => esc_html__('Border Type', 'themescamp-plugin'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'themescamp-plugin'),
                    'none' => esc_html__('None', 'themescamp-plugin'),
                    'solid' => esc_html__('Solid', 'themescamp-plugin'),
                    'double' => esc_html__('Double', 'themescamp-plugin'),
                    'dotted' => esc_html__('Dotted', 'themescamp-plugin'),
                    'dashed' => esc_html__('Dashed', 'themescamp-plugin'),
                    'groove' => esc_html__('Groove', 'themescamp-plugin'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button:hover .tcgelements-button-icon' => 'outline-style: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_icon_outline_width_hover',
            [
                'label' => esc_html__('Bullet Width', 'themescamp-plugin'),
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
                    'button_icon_outline_type_hover!' => ['', 'none'],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button:hover .tcgelements-button-icon' => 'outline-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_icon_outline_color_hover',
            [
                'label' => esc_html__('Border Color', 'themescamp-plugin'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button:hover .tcgelements-button-icon' => 'outline-color: {{VALUE}};',
                ],
                'condition' => [
                    'button_icon_outline_type_hover!' => ['', 'none'],
                ],
            ]
        );
        $this->add_responsive_control(
            'button_icon_outline_offset_hover',
            [
                'label' => esc_html__('Bullet Offset', 'themescamp-plugin'),
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
                    'button_icon_outline_type_hover!' => ['', 'none'],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button:hover .tcgelements-button-icon' => 'outline-offset: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_popover();
        $this->add_control(
            'button_icon_animation_hover',
            [
                'label' => esc_html__('Icon Animation', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__('None', 'element-camp'),
                    'right-to-left' => esc_html__('Right to Left', 'element-camp'),
                ],
            ]
        );

        $this->add_responsive_control(
            'button_icon_margin_hover',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button:hover .tcgelements-button-icon,.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active .tcgelements-button-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'button_icon_translate_y_hover',
            [
                'label' => esc_html__('Button Icon Wrapper Translate Y', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
            ]
        );
        $this->add_control(
            'button_icon_rotate_hover',
            [
                'label' => esc_html__('Button Icon Wrapper Rotate', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['deg', 'custom'],
                'default' => [
                    'unit' => 'deg',
                    'size' => '0',
                ],
            ]
        );
        $this->add_control(
            'button_icon_translate_x_hover',
            [
                'label' => esc_html__('Button Icon Wrapper Translate X', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button:hover .tcgelements-button-icon' => 'transform:  rotate({{button_icon_rotate_hover.SIZE}}{{button_icon_rotate_hover.UNIT}}) translate({{button_icon_translate_x_hover.SIZE}}{{button_icon_translate_x_hover.UNIT}},{{button_icon_translate_y_hover.SIZE}}{{button_icon_translate_y_hover.UNIT}})',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active .tcgelements-button-icon' => 'transform:  rotate({{button_icon_rotate_hover.SIZE}}{{button_icon_rotate_hover.UNIT}}) translate({{button_icon_translate_x_hover.SIZE}}{{button_icon_translate_x_hover.UNIT}},{{button_icon_translate_y_hover.SIZE}}{{button_icon_translate_y_hover.UNIT}})',
                ],
            ]
        );
        $this->add_control(
            'button_the_icon_transform_options_hover',
            [
                'label' => esc_html__('Icon Transform', 'themescamp-plugin'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'themescamp-plugin'),
                'label_on' => esc_html__('Custom', 'themescamp-plugin'),
            ]
        );
        $this->start_popover();
        $this->add_control(
            'button_the_icon_transition',
            [
                'label' => esc_html__('Transition', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon svg' => 'transition: all {{SIZE}}s ease;',
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-icon i' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );
        $this->add_control(
            'button_the_icon_translate_y_hoverr',
            [
                'label' => esc_html__('Button Icon Translate Y', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
                '{{WRAPPER}} .tcgelements-button:hover .tcgelements-button-icon svg' => '--e-transform-tcgelements-button-icon-translateY: {{SIZE}}{{UNIT}}',
                '{{WRAPPER}} .tcgelements-button:hover .tcgelements-button-icon i' => '--e-transform-tcgelements-button-icon-translateY: {{SIZE}}{{UNIT}}',
                '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active .tcgelements-button-icon svg' => '--e-transform-tcgelements-button-icon-translateY: {{SIZE}}{{UNIT}}',
                '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active .tcgelements-button-icon i' => '--e-transform-tcgelements-button-icon-translateY: {{SIZE}}{{UNIT}}',
            ]
        );
        $this->add_control(
            'button_the_icon_translate_x_hover',
            [
                'label' => esc_html__('Button Icon Translate X', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button:hover .tcgelements-button-icon svg' => '--e-transform-tcgelements-button-icon-translateX: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .tcgelements-button:hover .tcgelements-button-icon i' => '--e-transform-tcgelements-button-icon-translateX: {{SIZE}}{{UNIT}}',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active .tcgelements-button-icon svg' => '--e-transform-tcgelements-button-icon-translateX: {{SIZE}}{{UNIT}}',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active .tcgelements-button-icon i' => '--e-transform-tcgelements-button-icon-translateX: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_control(
            'button_the_icon_scale_hover',
            [
                'label' => esc_html__('Button Icon Scale', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button:hover .tcgelements-button-icon svg' => '--e-transform-tcgelements-button-icon-scale: {{SIZE}}',
                    '{{WRAPPER}} .tcgelements-button:hover .tcgelements-button-icon i' => '--e-transform-tcgelements-button-icon-scale: {{SIZE}}',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active .tcgelements-button-icon svg' => '--e-transform-tcgelements-button-icon-scale: {{SIZE}}',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active .tcgelements-button-icon i' => '--e-transform-tcgelements-button-icon-scale: {{SIZE}}',
                ],
            ]
        );
        $this->end_popover();
        $this->add_control(
            'button_icon_style_dark_mode_hover',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'button_icon_background_dark_mode_hover',
                'selector' => '{{WRAPPER}} .tcgelements-button:hover .tcgelements-button-icon,.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active .tcgelements-button-icon',
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

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'image_style',
            [
                'label' => esc_html__('Image', 'element-camp'),
                'condition' => ['image_switcher' => 'yes'],
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_positioning',
            [
                'label' => esc_html__('Position', 'themescamp-plugin'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'themescamp-plugin'),
                    'absolute' => esc_html__('absolute', 'themescamp-plugin'),
                    'relative' => esc_html__('relative', 'themescamp-plugin'),
                ],
                'label_block' => true,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .image' => 'position: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'image_offset_orientation_h',
            [
                'label' => esc_html__('Horizontal Orientation', 'themescamp-plugin'),
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
                'condition' => ['image_positioning!' => '']
            ]
        );

        $this->add_responsive_control(
            'image_offset_x',
            [
                'label' => esc_html__('Offset', 'themescamp-plugin'),
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-button .image' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-button .image' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'image_offset_orientation_h!' => 'end',
                    'image_positioning!' => ''
                ],
            ]
        );

        $this->add_responsive_control(
            'image_offset_x_end',
            [
                'label' => esc_html__('Offset', 'themescamp-plugin'),
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-button .image' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-button .image' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'image_offset_orientation_h' => 'end',
                    'image_positioning!' => ''
                ],
            ]
        );

        $this->add_control(
            'image_offset_orientation_v',
            [
                'label' => esc_html__('Vertical Orientation', 'themescamp-plugin'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => esc_html__('Top', 'themescamp-plugin'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'end' => [
                        'title' => esc_html__('Bottom', 'themescamp-plugin'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'render_type' => 'ui',
                'condition' => ['image_positioning!' => '']
            ]
        );

        $this->add_responsive_control(
            'image_offset_y',
            [
                'label' => esc_html__('Offset', 'themescamp-plugin'),
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
                    '{{WRAPPER}} .tcgelements-button .image' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'image_offset_orientation_v!' => 'end',
                    'image_positioning!' => ''
                ],
            ]
        );

        $this->add_responsive_control(
            'image_offset_y_end',
            [
                'label' => esc_html__('Offset', 'themescamp-plugin'),
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
                    '{{WRAPPER}} .tcgelements-button .image' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'image_offset_orientation_v' => 'end',
                    'image_positioning!' => ''
                ],
            ]
        );

        $this->add_control(
            'image_display',
            [
                'label' => esc_html__('Image Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'inline-block',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .image' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_control(
            'image_opacity',
            [
                'label' => esc_html__('Image Opacity', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .image' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_width',
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
                    '{{WRAPPER}} .tcgelements-button .image' => 'width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
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
                'size_units' => ['px', 'vh', '%', 'custom'],
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
                    '{{WRAPPER}} .tcgelements-button .image' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_rotate',
            [
                'label' => esc_html__('Rotate', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'custom'],
                'range' => [
                    'px' => [
                        'min' => -360,
                        'max' => 360,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .image' => 'transform: rotate({{SIZE}}deg);',
                ],
            ]
        );
        $this->start_controls_tabs(
            'image_tabs',
        );

        $this->start_controls_tab(
            'image_normal_tab',
            [
                'label'   => esc_html__('Normal', 'element-camp'),
            ]
        );
        $this->add_control(
            'image_filter_invert',
            [
                'label' => esc_html__('Image Invert', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .image' => 'filter: invert({{SIZE}});',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'image_css_filters',
                'selector' => '{{WRAPPER}} .tcgelements-button .image',
            ]
        );
        $this->add_control(
            'image_style_dark_mode',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'image_invert_dark_mode',
            [
                'label' => esc_html__('Image Invert', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-button .image' => 'filter: invert({{SIZE}});',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-button .image' => 'filter: invert({{SIZE}});',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'image_hover_tab',
            [
                'label'   => esc_html__('Hover', 'element-camp'),
            ]
        );
        $this->add_control(
            'image_filter_invert_hover',
            [
                'label' => esc_html__('Image Invert', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button:hover .image' => 'filter: invert({{SIZE}});',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active .image' => 'filter: invert({{SIZE}});',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'image_css_filters_hover',
                'selector' => '{{WRAPPER}} .tcgelements-button:hover .image,.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active .image',
            ]
        );
        $this->add_control(
            'image_style_dark_mode_hover',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'image_invert_dark_mode_hover',
            [
                'label' => esc_html__('Image Invert', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-button:hover .image' => 'filter: invert({{SIZE}});',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-button:hover .image' => 'filter: invert({{SIZE}});',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active .image' => 'filter: invert({{SIZE}});',
                    '} body.tcg-dark-mode {{WRAPPER}} .e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-button-container-active .image' => 'filter: invert({{SIZE}});',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();

        $this->start_controls_section(
            'drop_shadow',
            [
                'label' => esc_html__('Drop Shadow', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'drop_shadow_offset_x',
            [
                'label' => esc_html__('Offset x', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 200,
                        'min' => 0,
                        'step' => 1,
                    ],
                ],
                'render_type' => 'ui',
            ]
        );

        $this->add_control(
            'drop_shadow_offset_y',
            [
                'label' => esc_html__('Offset y', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 200,
                        'min' => 0,
                        'step' => 1,
                    ],
                ],
                'render_type' => 'ui',
            ]
        );

        $this->add_control(
            'drop_shadow_blur_radius',
            [
                'label' => esc_html__('Blur Radius', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 200,
                        'min' => 0,
                        'step' => 1,
                    ],
                ],
                'render_type' => 'ui',
            ]
        );

        $this->add_control(
            'drop_shadow_color',
            [
                'label' => __('Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button' => 'filter: drop-shadow({{drop_shadow_offset_x.SIZE}}px {{drop_shadow_offset_y.SIZE}}px {{drop_shadow_blur_radius.SIZE}}px {{VALUE}});',
                ],
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'section_small_text',
            [
                'label' => esc_html__('Small Text', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'small_display',
            [
                'label' => esc_html__('Small Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'inline-block',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-text small' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'small_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-button .tcgelements-button-text small',
            ]
        );
        $this->add_responsive_control(
            'small_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-text small' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'small_opacity',
            [
                'label' => esc_html__('Small Opacity', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-text small' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->start_controls_tabs(
            'small_tabs',
        );

        $this->start_controls_tab(
            'small_normal_tab',
            [
                'label'   => esc_html__('Normal', 'element-camp'),
            ]
        );

        $this->add_control(
            'small_text_color',
            [
                'label' => esc_html__('Text Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .tcgelements-button-text small' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'small_hover_tab',
            [
                'label'   => esc_html__('Hover', 'element-camp'),
            ]
        );
        $this->add_control(
            'small_text_color_hover',
            [
                'label' => esc_html__('Text Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button:hover .tcgelements-button-text small' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
        $this->start_controls_section(
            'button_before_style',
            [
                'label' => esc_html__('Before Button Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'before_index',
            [
                'label' => esc_html__('Before Z-index', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button::before' => 'z-index: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'position_for_before_button',
            [
                'label' => esc_html__('Position', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'absolute' => esc_html__('Absolute', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button::before' => 'position: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'button_before_offset_orientation_h',
            [
                'label' => esc_html__('Horizontal Orientation', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => esc_html__('Start', 'element-camp'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'end' => [
                        'title' => esc_html__('End', 'element-camp'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'condition' => [
                    'position_for_before_button!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_before_offset_x',
            [
                'label' => esc_html__('Offset', 'element-camp'),
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
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button::before' => 'left: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'button_before_offset_orientation_h' => 'start',
                    'position_for_before_button!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_before_offset_x_end',
            [
                'label' => esc_html__('Offset', 'element-camp'),
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
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button::before' => 'right: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'button_before_offset_orientation_h' => 'end',
                    'position_for_before_button!' => '',
                ],
            ]
        );
        $this->add_control(
            'button_before_offset_orientation_v',
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
                'condition' => [
                    'position_for_before_button!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_before_offset_y',
            [
                'label' => esc_html__('Offset', 'element-camp'),
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
                'size_units' => ['px', '%', 'em', 'rem', 'vh', 'vw', 'custom'],
                'default' => [
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button::before' => 'top: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'button_before_offset_orientation_v' => 'start',
                    'position_for_before_button!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_before_offset_y_end',
            [
                'label' => esc_html__('Offset', 'element-camp'),
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
                'size_units' => ['px', '%', 'em', 'rem', 'vh', 'vw', 'custom'],
                'default' => [
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button::before' => 'bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'button_before_offset_orientation_v' => 'end',
                    'position_for_before_button!' => '',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'before_border',
                'selector' => '{{WRAPPER}} .tcgelements-button::before',
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'before_border_radius',
            [
                'label' => esc_html__('Before Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button::before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'before_button_advanced_border_popover_toggle',
            [
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label' => esc_html__('Custom Border Settings', 'themescamp-elements'),
                'label_off' => esc_html__('Default', 'themescamp-elements'),
                'label_on' => esc_html__('Custom', 'themescamp-elements'),
                'return_value' => 'yes',
            ]
        );

        $this->start_popover();
        // Top Border
        $this->add_control(
            'before_button_advanced_border_top_style',
            [
                'label' => esc_html__('Border Top Style', 'themescamp-elements'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'themescamp-elements'),
                    'none' => esc_html__('None', 'themescamp-elements'),
                    'solid' => esc_html__('Solid', 'themescamp-elements'),
                    'double' => esc_html__('Double', 'themescamp-elements'),
                    'dashed' => esc_html__('Dashed', 'themescamp-elements'),
                    'dotted' => esc_html__('Dotted', 'themescamp-elements'),
                    'groove' => esc_html__('Groove', 'themescamp-elements'),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button::before' => 'border-top-style: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'before_button_advanced_border_top_color',
            [
                'label' => esc_html__('Border Top Color', 'themescamp-elements'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button::before' => 'border-top-color: {{VALUE}};',
                ],
                'condition' => [
                    'before_button_advanced_border_top_style!' => 'none',
                ],
            ]
        );
        $this->add_control(
            'before_button_advanced_border_top_width',
            [
                'label' => esc_html__('Border Top Width', 'themescamp-elements'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button::before' => 'border-top-width: {{SIZE}}{{UNIT}};',
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'condition' => [
                    'before_button_advanced_border_top_style!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'before_button_advanced_border_bottom_style',
            [
                'label' => esc_html__('Border Bottom Style', 'themescamp-elements'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'themescamp-elements'),
                    'none' => esc_html__('None', 'themescamp-elements'),
                    'solid' => esc_html__('Solid', 'themescamp-elements'),
                    'double' => esc_html__('Double', 'themescamp-elements'),
                    'dashed' => esc_html__('Dashed', 'themescamp-elements'),
                    'dotted' => esc_html__('Dotted', 'themescamp-elements'),
                    'groove' => esc_html__('Groove', 'themescamp-elements'),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button::before' => 'border-bottom-style: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'before_button_advanced_border_bottom_color',
            [
                'label' => esc_html__('Border Bottom Color', 'themescamp-elements'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button::before' => 'border-bottom-color: {{VALUE}};',
                ],
                'condition' => [
                    'before_button_advanced_border_bottom_style!' => 'none',
                ],
            ]
        );
        $this->add_control(
            'before_button_advanced_border_bottom_width',
            [
                'label' => esc_html__('Border Bottom Width', 'themescamp-elements'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button::before' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'condition' => [
                    'before_button_advanced_border_bottom_style!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'before_button_advanced_border_right_style',
            [
                'label' => esc_html__('Border Right Style', 'themescamp-elements'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'themescamp-elements'),
                    'none' => esc_html__('None', 'themescamp-elements'),
                    'solid' => esc_html__('Solid', 'themescamp-elements'),
                    'double' => esc_html__('Double', 'themescamp-elements'),
                    'dashed' => esc_html__('Dashed', 'themescamp-elements'),
                    'dotted' => esc_html__('Dotted', 'themescamp-elements'),
                    'groove' => esc_html__('Groove', 'themescamp-elements'),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button::before' => 'border-right-style: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'before_button_advanced_border_right_color',
            [
                'label' => esc_html__('Border Right Color', 'themescamp-elements'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button::before' => 'border-right-color: {{VALUE}};',
                ],
                'condition' => [
                    'before_button_advanced_border_right_style!' => 'none',
                ],
            ]
        );
        $this->add_control(
            'before_button_advanced_border_right_width',
            [
                'label' => esc_html__('Border Right Width', 'themescamp-elements'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button::before' => 'border-right-width: {{SIZE}}{{UNIT}};',
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'condition' => [
                    'before_button_advanced_border_right_style!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'before_button_advanced_border_left_style',
            [
                'label' => esc_html__('Border Left Style', 'themescamp-elements'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'themescamp-elements'),
                    'none' => esc_html__('None', 'themescamp-elements'),
                    'solid' => esc_html__('Solid', 'themescamp-elements'),
                    'double' => esc_html__('Double', 'themescamp-elements'),
                    'dashed' => esc_html__('Dashed', 'themescamp-elements'),
                    'dotted' => esc_html__('Dotted', 'themescamp-elements'),
                    'groove' => esc_html__('Groove', 'themescamp-elements'),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button::before' => 'border-left-style: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'before_button_advanced_border_left_color',
            [
                'label' => esc_html__('Border Left Color', 'themescamp-elements'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button::before' => 'border-left-color: {{VALUE}};',
                ],
                'condition' => [
                    'before_button_advanced_border_left_style!' => 'none',
                ],
            ]
        );
        $this->add_control(
            'before_button_advanced_border_left_width',
            [
                'label' => esc_html__('Border Left Width', 'themescamp-elements'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button::before' => 'border-left-width: {{SIZE}}{{UNIT}};',
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'condition' => [
                    'before_button_advanced_border_left_style!' => 'none',
                ],
            ]
        );

        $this->end_popover();
        $this->add_control(
            'before_button_transform_rotate_popover',
            [
                'label' => esc_html__('Rotate', 'tcgelements'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'tcgelements'),
                'label_on' => esc_html__('Custom', 'tcgelements'),
            ]
        );
        $this->start_popover();
        $this->add_control(
            'before_button_transform_rotate',
            [
                'label' => esc_html__('Rotate', 'tcgelements'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['deg'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button::before' => '--e-transform-tcgelements-button-before-rotateZ: {{SIZE}}deg',
                ],
            ]
        );
        $this->end_popover();
        $this->add_control(
            'before_button_opacity',
            [
                'label' => esc_html__('Before Button Opacity', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button::before' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'before_button_blur_method',
            [
                'label' => esc_html__('Blur Method', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'backdrop-filter' => 'backdrop-filter',
                    'filter' => 'filter',
                ],
                'default' => 'backdrop-filter',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button::before' => '{{VALUE}}: blur({{before_button_blur_value.SIZE}}px);',
                ],
            ]
        );
        $this->add_control(
            'before_button_blur_value',
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
        $this->start_controls_tabs(
            'before_button_tabs',
        );

        $this->start_controls_tab(
            'before_normal_tab',
            [
                'label'   => esc_html__('Normal', 'element-camp'),
            ]
        );
        $this->add_responsive_control(
            'before_width',
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
                    '{{WRAPPER}} .tcgelements-button::before' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'before_height',
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
                    '{{WRAPPER}} .tcgelements-button::before' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'before_background_color',
                'types' => ['classic', 'gradient', 'tcg_gradient', 'tcg_gradient_4'],
                'selector' => '{{WRAPPER}} .tcgelements-button::before',
            ]
        );
        $this->add_control(
            "before_button_transform_popover",
            [
                'label' => esc_html__('Transform', 'element-camp'),
                'type' => Controls_Manager::POPOVER_TOGGLE,
            ]
        );
        $this->start_popover();
        $this->add_control(
            'before_button_scale',
            [
                'label' => esc_html__('Button Scale', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button::before' => '--e-transform-tcgelements-button-before-scale: {{SIZE}}',
                ],
            ]
        );
        $this->end_popover();
        $this->end_controls_tab();

        $this->start_controls_tab(
            'before_hover_tab',
            [
                'label'   => esc_html__('Hover', 'element-camp'),
            ]
        );
        // Add this NEW control right after the tab starts
        $this->add_control(
            'before_button_hover_selector',
            [
                'label' => esc_html__('Choose Hover Selector', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'button',
                'options' => [
                    'button' => esc_html__('Button', 'element-camp'),
                    'container'  => esc_html__('Parent Container', 'element-camp'),
                    'parent-container'  => esc_html__('Parent of Parent Container', 'element-camp'),
                    'parent-n'  => esc_html__('Parent N', 'element-camp'),
                ],
            ]
        );

        $this->add_control(
            'before_button_hover_selector_note',
            [
                'type' => \Elementor\Controls_Manager::ALERT,
                'alert_type' => 'info',
                'heading' => esc_html__('Selector Note', 'element-camp'),
                'content' => esc_html__('When "Parent N" is selected, set the number of parent elements to target.', 'element-camp'),
                'condition' => [
                    'before_button_hover_selector' => 'parent-n',
                ],
            ]
        );

        $this->add_control(
            'before_parent_level',
            [
                'label' => esc_html__('Set Parent Levels', 'element-camp'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'default' => 1,
                'condition' => [
                    'before_button_hover_selector' => 'parent-n',
                ],
            ]
        );
        $this->add_responsive_control(
            'hover_before_width',
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
                    '{{WRAPPER}} .tcgelements-button:hover::before' => 'width: {{SIZE}}{{UNIT}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-before-container-active::before' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'hover_before_height',
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
                    '{{WRAPPER}} .tcgelements-button:hover::before' => 'height: {{SIZE}}{{UNIT}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-before-container-active::before' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'hover_before_background_color',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-button:hover::before,.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-before-container-active::before',
            ]
        );
        $this->add_control(
            "before_button_transform_popover_hover",
            [
                'label' => esc_html__('Transform', 'element-camp'),
                'type' => Controls_Manager::POPOVER_TOGGLE,
            ]
        );
        $this->start_popover();
        $this->add_control(
            'before_button_scale_hover',
            [
                'label' => esc_html__('Button Scale', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button:hover::before' => '--e-transform-tcgelements-button-before-scale: {{SIZE}}',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-button.tc-before-container-active::before' => '--e-transform-tcgelements-button-before-scale: {{SIZE}}',
                ],
            ]
        );
        $this->end_popover();
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control(
            'before_transition',
            [
                'label' => esc_html__('Before Transition', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button::before' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );
        $this->add_control(
            'before_button_animations',
            [
                'label' => esc_html__('Before Button Animations', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'infinite-scale' => esc_html__('Infinite Scale', 'element-camp'),
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'button_clip_path_style',
            [
                'label' => esc_html__('Clip Path', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'button_clip_path',
            [
                'label' => esc_html__('Button Clip Path', 'element-camp'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'description' => esc_html__('example: polygon(0 0, 100% 0, 100% 100%, 27% 100%)'),
                'placeholder' => esc_html__('example: polygon(0 0, 100% 0, 100% 100%, 27% 100%)'),
                'language' => 'css',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button' => 'clip-path: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_button_animations',
            [
                'label' => esc_html__('Animations', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'button_animations',
            [
                'label' => esc_html__('Animations', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('None', 'element-camp'),
                    'text-transform' => esc_html__('Text Transform', 'element-camp'),
                    'mouse-parallax' => esc_html__('Mouse Parallax', 'element-camp'),
                    'glow-effect' => esc_html__('Glow Effect', 'element-camp'),
                ],
            ]
        );

        $this->add_control(
            'glow_effect_heading',
            [
                'label' => esc_html__('Glow Effect Settings', 'element-camp'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'button_animations' => 'glow-effect',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'glow_color',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-button .glow',
                'condition' => [
                    'button_animations' => 'glow-effect',
                ],
            ]
        );

        $this->add_responsive_control(
            'glow_size',
            [
                'label' => esc_html__('Glow Size', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%', 'px'],
                'range' => [
                    '%' => [
                        'min' => 10,
                        'max' => 200,
                    ],
                    'px' => [
                        'min' => 10,
                        'max' => 500,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 70,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .glow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'button_animations' => 'glow-effect',
                ],
            ]
        );

        $this->add_control(
            'glow_blur_method',
            [
                'label' => esc_html__('Blur Method', 'themescamp-plugin'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'backdrop-filter' => 'backdrop-filter',
                    'filter' => 'filter',
                ],
                'default' => 'backdrop-filter',
                'selectors' => [
                    '{{WRAPPER}} .tcg-float-cursor' => '{{VALUE}}: blur({{glow_blur_value.SIZE}}px);',
                ],
                'condition' => [
                    'button_animations' => 'glow-effect',
                ],
            ]
        );
        $this->add_control(
            'glow_blur_value',
            [
                'label' => esc_html__('Blur', 'themescamp-plugin'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 250,
                    ],
                ],
                'condition' => [
                    'button_animations' => 'glow-effect',
                ],
            ]
        );

        $this->add_control(
            'glow_opacity',
            [
                'label' => esc_html__('Glow Opacity', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 0.8,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .glow' => 'opacity: {{SIZE}};',
                ],
                'condition' => [
                    'button_animations' => 'glow-effect',
                ],
            ]
        );

        $this->add_control(
            'glow_animation_speed',
            [
                'label' => esc_html__('Animation Speed', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0.1,
                        'max' => 2,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 0.3,
                ],
                'condition' => [
                    'button_animations' => 'glow-effect',
                ],
            ]
        );

        $this->add_responsive_control(
            'glow_border_radius',
            [
                'label' => esc_html__( 'Media Icon Border Radius', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'condition' => [
                    'button_animations' => 'glow-effect',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-button .glow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'text_transform_type',
            [
                'label' => esc_html__('Text Transform Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'same',
                'options' => [
                    'same' => esc_html__('Same as Button Text', 'element-camp'),
                    'custom' => esc_html__('Custom Text', 'element-camp'),
                ],
                'condition' => [
                    'button_animations' => 'text-transform',
                ],
            ]
        );

        $this->add_control(
            'text_transform_custom',
            [
                'label' => esc_html__('Custom Transform Text', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'condition' => [
                    'button_animations' => 'text-transform',
                    'text_transform_type' => 'custom',
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
    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $migrated = isset($settings['__fa4_migrated']['selected_icon']);
        $is_new = empty($settings['icon']) && Icons_Manager::is_migration_allowed();

        if (class_exists('WooCommerce') && $settings['show_products_count'] === 'yes') {
            $product_count = wp_count_posts('product')->publish;
            $product_count = $settings['before_count_text'] . $product_count . $settings['after_count_text'];
        }

        if (!$is_new && empty($settings['icon_align'])) {
            // @todo: remove when deprecated
            // added as bc in 2.6
            //old default
            $settings['icon_align'] = $this->get_settings('icon_align');
        }
        if (!empty($settings['button_css_id'])) {
            $btn_id = $settings['button_css_id'];
        }
        $animated_class = '';

        if ($settings['text_break_line'] == 'yes') $animated_class .= ' tcgelements-responsive-break-line';
        if ($settings['button_animations'] == 'text-transform') $animated_class .= ' tce-hvr-txt-trans';
        if ($settings['button_animations'] == 'mouse-parallax') $animated_class .= ' tce-btn-mouse-parallax';
        if ($settings['button_animations'] == 'glow-effect') $animated_class .= ' tce-btn-glow-effect';
        if ($settings['before_button_animations'] == 'infinite-scale') $animated_class .= ' tce-infinite-scale';

        $glow_element = '';
        if ($settings['button_animations'] == 'glow-effect') {
            $glow_element = '<div class="glow" data-speed="' . esc_attr($settings['glow_animation_speed']['size'] ?? 0.3) . '"></div>';
        }

        $this->add_render_attribute([
            'content-wrapper' => [
                'class' => ['tcgelements-button-content-wrapper'],
            ],
            'icon-align' => [
                'class' => [
                    'tcgelements-button-icon',
                    'tcgelements-align-icon-' . $settings['icon_align'],
                    'hover-animation-' . $settings['button_icon_animation_hover']
                ],
            ],
            'btn_text' => [
                'class' => ['tcgelements-button-text'],
            ],
        ]);
        $this->add_inline_editing_attributes('btn_text', 'none');

        $url = '';
        $woo_button_class = '';
        $scroll_top_class = ''; // Add this variable to handle scroll-top class

        // Handle different link types
        if ($settings['link_to'] == 'post-url') {
            $url = esc_url(get_the_permalink());
        } elseif ($settings['link_to'] == 'site-url') {
            $url = esc_url(get_site_url());
        } elseif ($settings['link_to'] == 'woo-product') {
            global $product;
            if (isset($product)) {
                $url = esc_url($product->add_to_cart_url(array('redirect' => false)));
                $woo_button_class = ' single_add_to_cart_link';
            }
        } elseif ($settings['link_to'] == 'scroll-top') {
            $url = '#';
            $scroll_top_class = ' tcgelements-scroll-top'; // Add the class here
        } elseif ($settings['link_to'] == 'custom') {
            $url = esc_url($settings['link']['url']);
        }

        if ($settings['link_to'] == 'woo-product' && isset($product)) {
            $icon_html = '';
            if (isset($is_new) || $migrated) {
                ob_start();
                Icons_Manager::render_icon($settings['selected_icon'], ['aria-hidden' => 'true']);
                $icon_html = ob_get_clean();
            } else {
                $icon_html = '<i class="' . esc_attr($settings['icon']) . '" aria-hidden="true"></i>';
            }

            $content_wrapper_attributes = '';
            ob_start();
            $this->print_render_attribute_string('content-wrapper');
            $content_wrapper_attributes = ob_get_clean();

            $icon_align_attributes = '';
            ob_start();
            $this->print_render_attribute_string('icon-align');
            $icon_align_attributes = ob_get_clean();

            $btn_text_attributes = '';
            ob_start();
            $this->print_render_attribute_string('btn_text');
            $btn_text_attributes = ob_get_clean() . ' ';

            $selector_classes = 'btn-selector-type-' . $settings['button_hover_selector'] . ' ' . 'btn-text-selector-type-' . $settings['button_text_hover_selector'];

            echo apply_filters(
                'woocommerce_loop_add_to_cart_link',
                '<a href="' . $product->add_to_cart_url() . '" rel="nofollow" data-product_id="' . esc_attr($product->get_id()) . '" data-product_sku="' . esc_attr($product->get_sku()) . '"' .
                (isset($settings['link_to']) && $settings['link_to'] == 'custom' && $settings['link']['is_external'] ? 'target="_blank"' : '') .
                ' class="tcgelements-button product_type_' . esc_attr($product->get_type()) . ' ' . esc_attr($selector_classes) . ' ' . esc_attr($animated_class) .  ($settings['button_transition_switcher'] != 'yes' ? ' transition-none' : '') . ' add_to_cart_button ajax_add_to_cart" ' .
                (isset($settings['play_button']) && $settings['play_button'] == 'yes' ? 'data-lity="video"' : '') .
                (($settings['button_hover_selector'] == 'parent-n' && !empty($settings['parent_level'])) ? ' data-parent-level="' . esc_attr($settings['parent_level']) . '"' : '') .
                '>
                ' . $glow_element . '
                <span class="button__loader h-rotatingNeuron"><span class="loading_popup">' . $settings['adding_text'] . ' <i class="fa fa-refresh"></i></span></span>
                <span class="button__added"><span class="added_popup">' . $settings['added_text'] . ' <i class="fa fa-check"></i></span></span>
                <span ' . $content_wrapper_attributes . '>
                    ' . (isset($settings['icon']) || (!empty($settings['selected_icon']['value']) && $settings['icon_align'] == 'left') ?
                    '<span ' . $icon_align_attributes . '>' .
                    $icon_html .
                    '</span>' : '') .
                (isset($settings['image_switcher']) && $settings['image_switcher'] == 'yes' && $settings['image_position'] == 'left' ?
                    '<img src="' . esc_url($settings['image']['url']) . '" alt="' . (isset($settings['image']['alt']) ? esc_attr($settings['image']['alt']) : '') . '" class="image">' : '') .
                (isset($settings['button_animations']) && $settings['button_animations'] == 'text-transform' ?
                    '<span class="hvr-txt" data-text="' . esc_attr($settings['text_transform_type'] === 'custom' ? $settings['text_transform_custom'] : $settings['btn_text']) . '">' : '') .
                '<span ' . $btn_text_attributes . '>' .
                $settings['btn_text'] .
                '</span>' .
                (isset($settings['button_animations']) && $settings['button_animations'] == 'text-transform' ?
                    '</span>' : '') .
                (isset($settings['image_switcher']) && $settings['image_switcher'] == 'yes' && $settings['image_position'] == 'right' ?
                    '<img src="' . esc_url($settings['image']['url']) . '" alt="' . (isset($settings['image']['alt']) ? esc_attr($settings['image']['alt']) : '') . '" class="image">' : '') .
                (isset($settings['icon']) || (!empty($settings['selected_icon']['value']) && $settings['icon_align'] == 'right') ?
                    '<span ' . $icon_align_attributes . '>' .
                    $icon_html .
                    '</span>' : '') .
                '</span>
            </a>',
                $product
            );
        } else {
            ?>
            <?php if ($settings['link_to'] == 'none') : ?>
                <div <?php if (!empty($btn_id)) echo "id=$btn_id" ?> class="tcgelements-button <?php echo 'btn-selector-type-' . $settings['button_hover_selector'] . ' ' . 'btn-text-selector-type-' . $settings['button_text_hover_selector'] . ' ' . esc_attr($animated_class) . esc_attr($scroll_top_class);
                if ($settings['button_transition_switcher'] != 'yes') echo esc_attr(' transition-none'); ?>"
                <?php if (!empty($settings['before_button_hover_selector'])) {
                    echo 'data-before-selector="' . esc_attr($settings['before_button_hover_selector']) . '"';
                }
                if ($settings['before_button_hover_selector'] == 'parent-n' && !empty($settings['before_parent_level'])) {
                    echo ' data-before-parent-level="' . esc_attr($settings['before_parent_level']) . '"';
                }?>>
                <?php echo $glow_element; ?>
            <?php else: ?>
                <a <?php if (!empty($btn_id)) echo "id=$btn_id" ?> href="<?= $url; ?>" <?php if ($settings['link_to'] == 'custom' && $settings['link']['is_external']) {
                    echo 'target="_blank"';
                } ?> class="tcgelements-button <?php echo 'btn-selector-type-' . $settings['button_hover_selector'] . ' ' . 'btn-text-selector-type-' . $settings['button_text_hover_selector'] . ' ' . esc_attr($animated_class) . esc_attr($scroll_top_class);
                if ($settings['button_transition_switcher'] != 'yes') echo esc_attr(' transition-none'); ?>" <?php if ($settings['play_button'] == 'yes') echo esc_attr('data-lity="video"') ?>    <?php
                if (!empty($settings['before_button_hover_selector'])) {
                    echo 'data-before-selector="' . esc_attr($settings['before_button_hover_selector']) . '"';
                }
                if ($settings['before_button_hover_selector'] == 'parent-n' && !empty($settings['before_parent_level'])) {
                    echo ' data-before-parent-level="' . esc_attr($settings['before_parent_level']) . '"';
                }
                ?>>
                <?php echo $glow_element; ?>
            <?php endif; ?>
            <span <?php $this->print_render_attribute_string('content-wrapper'); ?>>
                    <?php if (!empty($settings['icon']) or !empty($settings['selected_icon']['value']) and ($settings['icon_align'] == 'left')) : ?>
                        <span <?php $this->print_render_attribute_string('icon-align'); ?>>
                            <?php if ($is_new || $migrated) :
                                Icons_Manager::render_icon($settings['selected_icon'], ['aria-hidden' => 'true']);
                            else : ?>
                                <i class="<?php echo esc_attr($settings['icon']); ?>" aria-hidden="true"></i>
                            <?php endif; ?>
                        </span>
                    <?php endif; ?>
                <?php if ($settings['image_switcher'] == 'yes' && $settings['image_position'] == 'left') : ?>
                    <img src="<?= esc_url($settings['image']['url']) ?>" alt="<?php if (!empty($settings['image']['alt'])) echo esc_attr($settings['image']['alt']); ?>" class="image">
                <?php endif; ?>
                <?php if ($settings['button_animations'] == 'text-transform') : ?>
                        <span class="hvr-txt" data-text="<?= esc_attr($settings['text_transform_type'] === 'custom' ? $settings['text_transform_custom'] : $settings['btn_text']) ?>">
                        <?php endif; ?>
                        <span <?php $this->print_render_attribute_string('btn_text'); ?>>
                            <?php $this->print_unescaped_setting('btn_text'); ?>
                        </span>
                        <?php if (class_exists('WooCommerce') && $settings['show_products_count'] === 'yes') : ?>
                            <span class="products-count">
                                <?php echo esc_html($product_count); ?>
                            </span>
                        <?php endif; ?>
                            <?php if ($settings['button_animations'] == 'text-transform') : ?>
                        </span>
                    <?php endif; ?>
                <?php if ($settings['image_switcher'] == 'yes' && $settings['image_position'] == 'right') : ?>
                    <img src="<?= esc_url($settings['image']['url']) ?>" alt="<?php if (!empty($settings['image']['alt'])) echo esc_attr($settings['image']['alt']); ?>" class="image">
                <?php endif; ?>
                <?php if (!empty($settings['icon']) or !empty($settings['selected_icon']['value'])  and ($settings['icon_align'] == 'right')) : ?>
                    <span <?php $this->print_render_attribute_string('icon-align'); ?>>
                            <?php if ($is_new || $migrated) :
                                Icons_Manager::render_icon($settings['selected_icon'], ['aria-hidden' => 'true']);
                            else : ?>
                                <i class="<?php echo esc_attr($settings['icon']); ?>" aria-hidden="true"></i>
                            <?php endif; ?>
                        </span>
                <?php endif; ?>
                </span>
            <?php if ($settings['link_to'] == 'none') : ?>
                </div>
            <?php else: ?>
                </a>
            <?php endif; ?>
            <?php
        };
    }
}
