<?php

namespace ElementCampPlugin\Widgets;
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Text_Shadow;
use Elementor\POPOVER_TOGGLE;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;
use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Frontend;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Image_Size;
use Elementor\Scheme_Base;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Css_Filter;

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class ElementCamp_Testimonials_Slider extends Widget_Base
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
        return 'tcgelements-testimonials-slider';
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
        return __('Testimonials Slider', 'element-camp');
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
        return 'eicon-testimonial-carousel tce-widget-badge';
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
     * Retrieve the list of scripts the widget depended on.
     *
     * Used to set scripts dependencies required to run the widget.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return array Widget scripts dependencies.
     */
    public function get_script_depends()
    {
        return ['tcgelements-testimonials-slider'];
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
            'slides_section',
            [
                'label' => __('slides', 'element-camp'),
            ]
        );

        $slides_repeater = new \Elementor\Repeater();

        $slides_repeater->add_control(
            'date_switcher',
            [
                'label' => esc_html__( 'Date Switcher', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'yes', 'element-camp' ),
                'label_off' => esc_html__( 'No', 'element-camp' ),
            ]
        );

        $slides_repeater->add_control(
            'quote_date',
            [
                'label' => esc_html__('Quote Date', 'element-camp'),
                'default' => esc_html__('Enter Quote Date Here', 'element-camp'),
                'type' => Controls_Manager::TEXTAREA,
                'condition' => ['date_switcher'=>'yes'],
            ]
        );

        $slides_repeater->add_control(
            'quote_date_image',
            [
                'label' => esc_html__('Quote Date Image', 'element-camp'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => ['date_switcher'=>'yes'],
            ]
        );

        $slides_repeater->add_control(
            'quote_text_image_switcher',
            [
                'label' => esc_html__( 'Quote Text Image Switcher', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'yes', 'element-camp' ),
                'label_off' => esc_html__( 'No', 'element-camp' ),
            ]
        );

        $slides_repeater->add_control(
            'rate_switcher',
            [
                'label' => esc_html__( 'Rate Switcher', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'yes', 'element-camp' ),
                'label_off' => esc_html__( 'No', 'element-camp' ),
            ]
        );

        $slides_repeater->add_control(
            'rate_text',
            [
                'label' => esc_html__('Rate Text', 'element-camp'),
                'default' => esc_html__('Enter Rate Here', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'condition' => ['rate_switcher'=>'yes'],
            ]
        );

        $slides_repeater->add_control(
            'rate_number',
            [
                'label' => esc_html__( 'Rate Number', 'element-camp' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 5,
                'min' => 0,
                'max' => 0,
                'step' => 1,
                'condition' => ['rate_switcher'=>'yes'],
            ]
        );

        $slides_repeater->add_control(
            'rate_icon',
            [
                'label' => esc_html__( 'Rate Icon', 'element-camp' ),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fa fa-star',
                    'library' => 'fa-solid',
                ],
                'condition' => ['rate_switcher'=>'yes'],
            ]
        );

        $slides_repeater->add_control(
            'quote_text_image',
            [
                'label' => esc_html__('Quote Text Image', 'element-camp'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => ['quote_text_image_switcher'=>'yes'],
            ]
        );

        $slides_repeater->add_control(
            'quote_text',
            [
                'label' => esc_html__('Quote Text', 'element-camp'),
                'default' => esc_html__('Enter Quote Text Here', 'element-camp'),
                'type' => Controls_Manager::TEXTAREA,
            ]
        );

        $slides_repeater->add_control(
            'author_name',
            [
                'label' => esc_html__('Author Name', 'element-camp'),
                'default' => esc_html__('Enter Author Name Here', 'element-camp'),
                'type' => Controls_Manager::TEXTAREA,
            ]
        );

        $slides_repeater->add_control(
            'author_position',
            [
                'label' => esc_html__('Author Position', 'element-camp'),
                'default' => esc_html__('Enter Author Position Here', 'element-camp'),
                'type' => Controls_Manager::TEXTAREA,
            ]
        );

        $slides_repeater->add_control(
            'author_image',
            [
                'label' => esc_html__('Author Image', 'element-camp'),
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
            'tcg_dynamic_slides_repeater',
            [
                'label' => esc_html__('Slides', 'element-camp'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $slides_repeater->get_controls(),
                'default' => [
                    [
                        'author_name' => esc_html__('Tawam Rahman', 'element-camp'),
                    ],
                    [
                        'author_name' => esc_html__('Tawam Rahman', 'element-camp'),
                    ],
                    [
                        'author_name' => esc_html__('Tawam Rahman', 'element-camp'),
                    ],
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'slider_settings',
            [
                'label' => __('Slider Settings', 'element-camp'),
            ]
        );

        $this->add_control(
            'speed',
            [
                'label' => esc_html__('Speed', 'element-camp'),
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
                'label' => esc_html__('Direction', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'horizontal' => esc_html__('horizontal', 'element-camp'),
                    'vertical' => esc_html__('vertical', 'element-camp'),
                ],
                'label_block' => true,
                'default' => 'horizontal',
            ]
        );

        $this->add_control(
            'effect',
            [
                'label' => esc_html__('Effect', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'slide' => esc_html__('Slide', 'element-camp'),
                    'fade' => esc_html__('Fade', 'element-camp'),
                    'parallax' => esc_html__('Parallax', 'element-camp'),
                    'cube' => esc_html__('Cube', 'element-camp'),
                    'coverflow' => esc_html__('Cover flow', 'element-camp'),
                    'flip' => esc_html__('Flip', 'element-camp'),
                    'cards' => esc_html__('Cards', 'element-camp'),
                    'material' => esc_html__('Material', 'element-camp'),
                ],
                'label_block' => true,
                'default' => 'slide',
            ]
        );

        $this->add_control(
            'effect_important_note',
            [
                'type' => \Elementor\Controls_Manager::ALERT,
                'alert_type' => 'warning',
                'heading' => esc_html__('Effect Important Note', 'element-camp'),
                'content' => esc_html__('Only Slide and Cover flow effects are support more than one slide per view.', 'element-camp'),
                'condition' => [
                    'effect!' => ['slide', 'coverflow']
                ]
            ]
        );

        $this->add_control(
            'cardsRotate',
            [
                'label' => esc_html__('Cards Rotate', 'element-camp'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 50000,
                'step' => 1,
                'default' => 2,
                'condition' => [
                    'effect' => 'cards',
                ]
            ]
        );

        $this->add_control(
            'cardsOffset',
            [
                'label' => esc_html__('Cards Offset', 'element-camp'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 50000,
                'step' => 1,
                'default' => 8,
                'condition' => [
                    'effect' => 'cards',
                ]
            ]
        );

        $this->add_control(
            'loop',
            [
                'label' => esc_html__('Loop', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'element-camp'),
                'label_off' => esc_html__('Off', 'element-camp'),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $this->add_control(
            'loop_important_note',
            [
                'type' => \Elementor\Controls_Manager::ALERT,
                'alert_type' => 'warning',
                'heading' => esc_html__('Loop Important Note', 'element-camp'),
                'content' => esc_html__('The number of slides must be more than the slides per view.', 'element-camp'),
                'condition' => [
                    'loop' => 'true'
                ]
            ]
        );

        $this->add_control(
            'autoplay_delay',
            [
                'label' => esc_html__('Autoplay Delay', 'element-camp'),
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
            'autoplay',
            [
                'label' => esc_html__('Autoplay', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'element-camp'),
                'label_off' => esc_html__('Off', 'element-camp'),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $this->add_control(
            'reverseDirection',
            [
                'label' => esc_html__('Reverse Direction', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'element-camp'),
                'label_off' => esc_html__('Off', 'element-camp'),
                'return_value' => 'true',
                'default' => 'false',
                'condition' => [
                    'autoplay' => 'true',
                ]
            ]
        );

        $this->add_control(
            'disableOnInteraction',
            [
                'label' => esc_html__('Disable On Interaction', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'element-camp'),
                'label_off' => esc_html__('Off', 'element-camp'),
                'return_value' => 'true',
                'default' => 'false',
                'condition' => [
                    'autoplay' => 'true',
                ]
            ]
        );

        $this->add_control(
            'centeredSlides',
            [
                'label' => esc_html__('Centered Slides', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'element-camp'),
                'label_off' => esc_html__('Off', 'element-camp'),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $this->add_control(
            'oneWayMovement',
            [
                'label' => esc_html__('One Way Movement', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'element-camp'),
                'label_off' => esc_html__('Off', 'element-camp'),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $this->add_control(
            'autoHeight',
            [
                'label' => esc_html__('Auto Height', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'element-camp'),
                'label_off' => esc_html__('Off', 'element-camp'),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $this->add_control(
            'mousewheel',
            [
                'label' => esc_html__('Slide With Mouse Wheel', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'element-camp'),
                'label_off' => esc_html__('Off', 'element-camp'),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $this->add_control(
            'keyboard',
            [
                'label' => esc_html__('Slide With Keyboard', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'element-camp'),
                'label_off' => esc_html__('Off', 'element-camp'),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $this->add_control(
            'arrows',
            [
                'label' => esc_html__('Slider Arrows', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'element-camp'),
                'label_off' => esc_html__('Off', 'element-camp'),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $this->add_control(
            'pagination',
            [
                'label' => esc_html__('Slider Pagination', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'element-camp'),
                'label_off' => esc_html__('Off', 'element-camp'),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $this->add_control(
            'paginationType',
            [
                'label' => esc_html__('Pagination Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'bullets' => esc_html__('Bullets', 'element-camp'),
                    'fraction' => esc_html__('Fraction', 'element-camp'),
                    'progressbar' => esc_html__('Progress Bar', 'element-camp'),
                ],
                'label_block' => true,
                'default' => 'bullets',
                'condition' => [
                    'pagination' => 'true'
                ]
            ]
        );

        $this->add_control(
            'arrows_pagination_container',
            [
                'label' => esc_html__('Arrow & Pagination Container', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'element-camp'),
                'label_off' => esc_html__('Off', 'element-camp'),
                'return_value' => 'true',
                'default' => 'false',
                'condition' => [
                    'pagination' => 'true',
                    'arrows' => 'true',
                    'paginationType' => 'bullets'
                ]
            ]
        );

        $this->add_control(
            'scrollbar',
            [
                'label' => esc_html__('Slider Scrollbar', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'element-camp'),
                'label_off' => esc_html__('Off', 'element-camp'),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_slider_arrows',
            [
                'label' => __('Slider Arrows', 'element-camp'),
                'condition' => [
                    'arrows' => 'true',
                ]
            ]
        );

        $this->add_control(
            'arrows_style',
            [
                'label' => esc_html__('Arrows Style', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'icon' => esc_html__('Icon', 'element-camp'),
                    'text' => esc_html__('Text', 'element-camp'),
                    'text-icon' => esc_html__('Text With Icon', 'element-camp'),
                ],
                'label_block' => true,
                'default' => 'icon',
            ]
        );

        $this->add_control(
            'next_arrow_icon',
            [
                'label' => __('Next Arrow Icon', 'element-camp'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-arrow-right',
                    'library' => 'solid',
                ],
                'condition' => [
                    'arrows_style' => ['icon', 'text-icon'],
                ]
            ]
        );

        $this->add_control(
            'prev_arrow_icon',
            [
                'label' => __('Prev Arrow Icon', 'element-camp'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-arrow-left',
                    'library' => 'solid',
                ],
                'condition' => [
                    'arrows_style' => ['icon', 'text-icon'],
                ]
            ]
        );

        $this->add_control(
            'next_arrow_text',
            [
                'label' => esc_html__('Next Arrow Text', 'element-camp'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Next', 'element-camp'),
                'placeholder' => esc_html__('Type your text here', 'element-camp'),
                'condition' => [
                    'arrows_style' => ['text', 'text-icon'],
                ]
            ]
        );

        $this->add_control(
            'prev_arrow_text',
            [
                'label' => esc_html__('Prev Arrow Text', 'element-camp'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Prev', 'element-camp'),
                'placeholder' => esc_html__('Type your text here', 'element-camp'),
                'condition' => [
                    'arrows_style' => ['text', 'text-icon'],
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_slider_responsive',
            [
                'label' => __('Slider Responsive', 'element-camp'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'breakpoint',
            [
                'label' => esc_html__('Break Point', 'element-camp'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 992,
            ]
        );

        $repeater->add_control(
            'spaceBetween',
            [
                'label' => esc_html__('Space Between', 'element-camp'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 550,
                'step' => 1,
                'default' => 30,
            ]
        );

        $repeater->add_control(
            'slidesPerView_auto',
            [
                'label' => esc_html__('Auto Slides Per View', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'element-camp'),
                'label_off' => esc_html__('Off', 'element-camp'),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $repeater->add_control(
            'slidesPerView',
            [
                'label' => esc_html__('Slides Per View', 'element-camp'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 100,
                'step' => 1,
                'default' => 1,
                'condition' => [
                    'slidesPerView_auto!' => 'true'
                ]
            ]
        );

        $this->add_control(
            'responsive_settings',
            [
                'label' => esc_html__('Custom Break Points', 'element-camp'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'breakpoint' => 1200,
                        'slidesPerView' => 1,
                        'spaceBetween' => 0
                    ],
                    [
                        'breakpoint' => 992,
                        'slidesPerView' => 1,
                        'spaceBetween' => 0
                    ],
                    [
                        'breakpoint' => 600,
                        'slidesPerView' => 1,
                        'spaceBetween' => 0
                    ],
                ],
                'title_field' => 'screen size {{{ breakpoint }}}px',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_container_style',
            [
                'label' => __('Slider Container Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-container' => 'overflow: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_swiper_slide_style',
            [
                'label' => __('Swiper Slide Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'swiper_slide_height',
            [
                'label' => esc_html__( 'Height', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-slide' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'swiper_slide_width',
            [
                'label' => esc_html__( 'Width', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-slide' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'section_testimonials_card_style',
            [
                'label' => esc_html__('Testimonials Card Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'separate_container_for_author',
            [
                'label' => esc_html__( 'Separate Container For Author', 'element-camp' ),
                'description' => esc_html__( 'If enabled, wraps all content except the author in a single div container.', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'yes', 'element-camp' ),
                'label_off' => esc_html__( 'No', 'element-camp' ),
            ]
        );
        $this->add_responsive_control(
            'testimonials_card_align',
            [
                'label' => esc_html__( 'Testimonials Card Text Alignment', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'testimonials_card_height',
            [
                'label' => esc_html__( 'Height', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'testimonials_card_display',
            [
                'label' => esc_html__('Card Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'block',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'testimonials_card_display_position',
            [
                'label' => esc_html__( 'Card Display Position', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card' => '{{VALUE}}',
                ],
                'condition'=>['testimonials_card_display'=> ['flex','inline-flex']],
            ]
        );
        $this->add_responsive_control(
            'testimonials_card_justify_content',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card' => 'justify-content: {{VALUE}};',
                ],
                'condition'=>['testimonials_card_display'=> ['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'testimonials_card_align_items',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card' => 'align-items: {{VALUE}};',
                ],
                'condition'=>['testimonials_card_display'=> ['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'testimonials_card_flex_wrap',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card' => 'flex-wrap: {{VALUE}};',
                ],
                'condition'=>['testimonials_card_display'=> ['flex','inline-flex']],
            ]
        );

        $this->add_control(
            'testimonials_card_transition',
            [
                'label' => esc_html__( 'Card Transition', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );
        $this->add_responsive_control(
            'testimonials_card_border_radius',
            [
                'label' => esc_html__( 'Card Border Radius', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'testimonials_card_padding',
            [
                'label' => esc_html__('Card Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );
        $this->start_controls_tabs(
            'testimonials_card_tabs',
        );
        
        $this->start_controls_tab(
            'testimonials_card_normal_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'testimonials_card_background_color',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'testimonials_card_border',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card',
            ]
        );
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'testimonials_card_hover_tab',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'testimonials_card_background_color_hover',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card:hover',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'testimonials_card_border_hover',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card:hover',
            ]
        );
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'testimonials_card_active_tab',
            [
                'label'   => esc_html__( 'Active', 'element-camp' ),
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'testimonials_card_background_color_active',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-slide-active .testi-card',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'testimonials_card_border_active',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-slide-active .testi-card',
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_testimonials_card_overlay_style',
            [
                'label' => esc_html__('Overlay Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'testimonials_card_overlay_index',
            [
                'label' => esc_html__( 'Item Overlay z-index', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card::after' => 'z-index: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'testimonials_card_overlay_offset_orientation_h',
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
            ]
        );
        $this->add_responsive_control(
            'testimonials_card_overlay_offset_x',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card::after' => 'left: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'testimonials_card_overlay_offset_orientation_h' => 'start',
                ],
            ]
        );
        $this->add_responsive_control(
            'testimonials_card_overlay_offset_x_end',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card::after' => 'right: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'testimonials_card_overlay_offset_orientation_h' => 'end',
                ],
            ]
        );
        $this->add_control(
            'testimonials_card_overlay_offset_orientation_v',
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
            ]
        );
        $this->add_responsive_control(
            'testimonials_card_overlay_offset_y',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card::after' => 'top: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'testimonials_card_overlay_offset_orientation_v' => 'start',
                ],
            ]
        );
        $this->add_responsive_control(
            'testimonials_card_overlay_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card::after' => 'bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'testimonials_card_overlay_offset_orientation_v' => 'end',
                ],
            ]
        );
        $this->add_control(
            'testimonials_card_overlay_transition',
            [
                'label' => esc_html__( 'Item Overlay Transition', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card::after' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );
        $this->start_controls_tabs(
            'testimonials_card_overlay_tabs',
        );
        $this->start_controls_tab(
            'normal_testimonials_card_overlay_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_responsive_control(
            'testimonials_card_overlay_width',
            [
                'label' => esc_html__( 'Width', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
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
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card::after' => 'position:absolute;content:"";width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'testimonials_card_overlay_height',
            [
                'label' => esc_html__( 'Height', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
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
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card::after' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'testimonials_card_overlay_bg',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card::after',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
            ]
        );
        $this->add_control(
            'testimonials_card_overlay_opacity',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card::after' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'hover_testimonials_card_overlay_tab',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
            ]
        );
        $this->add_responsive_control(
            'testimonials_card_overlay_width_hover',
            [
                'label' => esc_html__( 'Width', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card:hover::after' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'testimonials_card_overlay_height_hover',
            [
                'label' => esc_html__( 'Height', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card:hover::after' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'testimonials_card_overlay_bg_hover',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card:hover::after',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
            ]
        );
        $this->add_control(
            'testimonials_card_overlay_opacity_hover',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card:hover::after' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();

        $this->start_controls_section(
            'section_rate_style',
            [
                'label' => esc_html__('Rate Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'order_rate',
            [
                'label' => esc_html__( 'Order Rate', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => '',
                'options' => [
                    'start' => [
                        'title' => esc_html__( 'Start', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-order-start',
                    ],
                    'end' => [
                        'title' => esc_html__( 'End', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-order-end',
                    ],
                    'custom' => [
                        'title' => esc_html__( 'Custom', 'element-camp' ),
                        'icon' => 'eicon-ellipsis-v',
                    ],
                ],
                'selectors_dictionary' => [
                    // Hacks to set the order to start / end.
                    // For example, if the user has 10 widgets, but wants to set the 5th one to be first,
                    // this hack should do the trick while taking in account elements with `order: 0` or less.
                    'start' => '-99999 /* order start hack */',
                    'end' => '99999 /* order end hack */',
                    'custom' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .rate' => 'order: {{VALUE}};',
                ],
                'condition' => [ 'testimonials_card_display' => 'flex' ],
                'responsive' => true,
            ]
        );

        $this->add_responsive_control(
            'order_rate_custom',
            [
                'label' => esc_html__( 'Custom Order', 'element-camp' ),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .rate' => 'order: {{VALUE}};',
                ],
                'responsive' => true,
                'condition' => [
                    'order_rate' => 'custom',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'rate_typography',
                'label' => esc_html__('Rate', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .rate',
            ]
        );
        $this->add_control(
            'rate_color',
            [
                'label' => esc_html__( 'Rate Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .rate' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'rate_border',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .rate',
            ]
        );
        $this->add_responsive_control(
            'rate_margin',
            [
                'label' => esc_html__('Rate Container Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .rate' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'rate_icons_options',
            [
                'label' => esc_html__( 'Rate Icons Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );
        $this->add_responsive_control(
            'rate_icons_margin',
            [
                'label' => esc_html__('Rate Icon Container Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .rate .stars' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'rate_icons_space',
            [
                'label' => esc_html__('Space Between Icons', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .rate .stars i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .rate .stars svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'rate_icon_size',
            [
                'label' => esc_html__('Icon Size', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .rate i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .rate svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'rate_icon_color',
            [
                'label' => esc_html__( 'Rate Icon Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .rate i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .rate svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_date_style',
            [
                'label' => esc_html__('Date Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
             'order_date',
             [
                'label' => esc_html__( 'Order Date', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => '',
                'options' => [
                    'start' => [
                        'title' => esc_html__( 'Start', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-order-start',
                    ],
                    'end' => [
                        'title' => esc_html__( 'End', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-order-end',
                    ],
                    'custom' => [
                        'title' => esc_html__( 'Custom', 'element-camp' ),
                        'icon' => 'eicon-ellipsis-v',
                    ],
                ],
                'selectors_dictionary' => [
                    // Hacks to set the order to start / end.
                    // For example, if the user has 10 widgets, but wants to set the 5th one to be first,
                    // this hack should do the trick while taking in account elements with `order: 0` or less.
                    'start' => '-99999 /* order start hack */',
                    'end' => '99999 /* order end hack */',
                    'custom' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .date' => 'order: {{VALUE}};',
                ],
                 'condition' => [ 'testimonials_card_display' => 'flex' ],
                 'responsive' => true,
            ]
        );

        $this->add_responsive_control(
            'order_date_custom',
                [
                'label' => esc_html__( 'Custom Order', 'element-camp' ),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .date' => 'order: {{VALUE}};',
                ],
                'responsive' => true,
                'condition' => [
                    'order_date' => 'custom',
                ],
            ]
        );

        $this->add_control(
            'quote_date_options',
            [
                'label' => esc_html__( 'Quote Date Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'quote_date_typography',
                'label' => esc_html__('Quote Date', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .date',
            ]
        );
        $this->add_control(
            'quote_date_color',
            [
                'label' => esc_html__( 'Quote Date Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .date' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'quote_date_border',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .date',
            ]
        );
        $this->add_responsive_control(
            'quote_date_margin',
            [
                'label' => esc_html__('Quote Date Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .date' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'quote_date_padding',
            [
                'label' => esc_html__('Quote Date Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .date' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'quote_date_image_options',
            [
                'label' => esc_html__( 'Quote Date Image Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'before'
            ]
        );

        $this->add_responsive_control(
            'quote_date_image_width',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .date img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'quote_date_image_max_width',
            [
                'label' => esc_html__( 'Max Width', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .date img' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'quote_date_image_max_height',
            [
                'label' => esc_html__( 'Max Height', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .date img' => 'max-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'quote_date_image_height',
            [
                'label' => esc_html__( 'Height', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .date img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'quote_date_image_object_fit',
            [
                'label' => esc_html__( 'Object Fit', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'quote_date_image_height[size]!' => '',
                ],
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'fill' => esc_html__( 'Fill', 'element-camp' ),
                    'cover' => esc_html__( 'Cover', 'element-camp' ),
                    'contain' => esc_html__( 'Contain', 'element-camp' ),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .date img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'quote_date_image_object_position',
            [
                'label' => esc_html__( 'Object Position', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'center center' => esc_html__( 'Center Center', 'element-camp' ),
                    'center left' => esc_html__( 'Center Left', 'element-camp' ),
                    'center right' => esc_html__( 'Center Right', 'element-camp' ),
                    'top center' => esc_html__( 'Top Center', 'element-camp' ),
                    'top left' => esc_html__( 'Top Left', 'element-camp' ),
                    'top right' => esc_html__( 'Top Right', 'element-camp' ),
                    'bottom center' => esc_html__( 'Bottom Center', 'element-camp' ),
                    'bottom left' => esc_html__( 'Bottom Left', 'element-camp' ),
                    'bottom right' => esc_html__( 'Bottom Right', 'element-camp' ),
                ],
                'default' => 'center center',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .date img' => 'object-position: {{VALUE}};',
                ],
                'condition' => [
                    'quote_date_image_height[size]!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'quote_date_image_margin',
            [
                'label' => esc_html__('Quote Date Image Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .date img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'quote_date_image_padding',
            [
                'label' => esc_html__('Quote Date Image Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .date img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_quote_text_style',
            [
                'label' => esc_html__('Quote Text Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'order_text',
            [
                'label' => esc_html__( 'Order Text', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => '',
                'options' => [
                    'start' => [
                        'title' => esc_html__( 'Start', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-order-start',
                    ],
                    'end' => [
                        'title' => esc_html__( 'End', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-order-end',
                    ],
                    'custom' => [
                        'title' => esc_html__( 'Custom', 'element-camp' ),
                        'icon' => 'eicon-ellipsis-v',
                    ],
                ],
                'selectors_dictionary' => [
                    // Hacks to set the order to start / end.
                    // For example, if the user has 10 widgets, but wants to set the 5th one to be first,
                    // this hack should do the trick while taking in account elements with `order: 0` or less.
                    'start' => '-99999 /* order start hack */',
                    'end' => '99999 /* order end hack */',
                    'custom' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .quote-text' => 'order: {{VALUE}};',
                ],
                'condition' => [ 'testimonials_card_display' => 'flex' ],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'order_text_custom',
            [
                'label' => esc_html__( 'Custom Order', 'element-camp' ),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .quote-text' => 'order: {{VALUE}};',
                ],
                'responsive' => true,
                'condition' => [
                    'order_text' => 'custom',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'quote_text_typography',
                'label' => esc_html__('Quote Text', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .quote-text',
            ]
        );
        $this->add_control(
            'quote_text_color',
            [
                'label' => esc_html__( 'Quote Text Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .quote-text' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'quote_text_border',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .quote-text',
            ]
        );
        $this->add_responsive_control(
            'quote_text_margin',
            [
                'label' => esc_html__('Quote Text Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .quote-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'quote_text_padding',
            [
                'label' => esc_html__('Quote Text Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .quote-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'quote_text_image_options',
            [
                'label' => esc_html__( 'Quote Text Image Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'before'
            ]
        );

        $this->add_responsive_control(
            'quote_text_image_width',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .quote-text img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'quote_text_image_max_width',
            [
                'label' => esc_html__( 'Max Width', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .quote-text img' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'quote_text_image_max_height',
            [
                'label' => esc_html__( 'Max Height', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .quote-text img' => 'max-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'quote_text_image_height',
            [
                'label' => esc_html__( 'Height', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .quote-text img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'quote_text_image_object_fit',
            [
                'label' => esc_html__( 'Object Fit', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'quote_text_image_height[size]!' => '',
                ],
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'fill' => esc_html__( 'Fill', 'element-camp' ),
                    'cover' => esc_html__( 'Cover', 'element-camp' ),
                    'contain' => esc_html__( 'Contain', 'element-camp' ),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .quote-text img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'quote_text_image_object_position',
            [
                'label' => esc_html__( 'Object Position', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'center center' => esc_html__( 'Center Center', 'element-camp' ),
                    'center left' => esc_html__( 'Center Left', 'element-camp' ),
                    'center right' => esc_html__( 'Center Right', 'element-camp' ),
                    'top center' => esc_html__( 'Top Center', 'element-camp' ),
                    'top left' => esc_html__( 'Top Left', 'element-camp' ),
                    'top right' => esc_html__( 'Top Right', 'element-camp' ),
                    'bottom center' => esc_html__( 'Bottom Center', 'element-camp' ),
                    'bottom left' => esc_html__( 'Bottom Left', 'element-camp' ),
                    'bottom right' => esc_html__( 'Bottom Right', 'element-camp' ),
                ],
                'default' => 'center center',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .quote-text img' => 'object-position: {{VALUE}};',
                ],
                'condition' => [
                    'quote_text_image_height[size]!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'quote_text_image_margin',
            [
                'label' => esc_html__('Quote Text Image Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .quote-text img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'quote_text_image_padding',
            [
                'label' => esc_html__('Quote Text Image Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .quote-text img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_author_style',
            [
                'label' => esc_html__('Author Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'author_container_overlay_switcher',
            [
                'label' => esc_html__( 'Author Container Overlay Switcher', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'yes', 'element-camp' ),
                'label_off' => esc_html__( 'No', 'element-camp' ),
            ]
        );
        $this->add_responsive_control(
            'author_container_card_align',
            [
                'label' => esc_html__( 'Author Container Text Alignment', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'order_author_container',
            [
                'label' => esc_html__( 'Author Container', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => '',
                'options' => [
                    'start' => [
                        'title' => esc_html__( 'Start', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-order-start',
                    ],
                    'end' => [
                        'title' => esc_html__( 'End', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-order-end',
                    ],
                    'custom' => [
                        'title' => esc_html__( 'Custom', 'element-camp' ),
                        'icon' => 'eicon-ellipsis-v',
                    ],
                ],
                'selectors_dictionary' => [
                    // Hacks to set the order to start / end.
                    // For example, if the user has 10 widgets, but wants to set the 5th one to be first,
                    // this hack should do the trick while taking in account elements with `order: 0` or less.
                    'start' => '-99999 /* order start hack */',
                    'end' => '99999 /* order end hack */',
                    'custom' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper' => 'order: {{VALUE}};',
                ],
                'condition' => [ 'testimonials_card_display' => 'flex' ],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'order_author_container_custom',
            [
                'label' => esc_html__( 'Custom Order', 'element-camp' ),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper' => 'order: {{VALUE}};',
                ],
                'responsive' => true,
                'condition' => [
                    'order_author_container' => 'custom',
                ],
            ]
        );
        $this->add_control(
            'author_container_style',
            [
                'label' => esc_html__( 'Author Container Style', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );
        $this->add_responsive_control(
            'author_container_display',
            [
                'label' => esc_html__('Author Container Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'block',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'inline-flex' => esc_html__('Inline Flex', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                    'none' => esc_html__('None', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'author_container_justify_content',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper' => 'justify-content: {{VALUE}};',
                ],
                'condition'=>['author_container_display'=> ['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'author_container_align_items',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper' => 'align-items: {{VALUE}};',
                ],
                'condition'=>['author_container_display'=> ['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'author_container_border',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper',
            ]
        );
        $this->add_responsive_control(
            'author_container_margin',
            [
                'label' => esc_html__('Author Container Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'author_container_padding',
            [
                'label' => esc_html__('Author Container Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'author_name_style',
            [
                'label' => esc_html__('Author Name Style', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'author_name_typography',
                'label' => esc_html__('Author Name', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper .author-name',
            ]
        );
        $this->add_control(
            'author_name_color',
            [
                'label' => esc_html__( 'Author Name Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper .author-name' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'author_name_border',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper .author-name',
            ]
        );
        $this->add_responsive_control(
            'author_name_margin',
            [
                'label' => esc_html__('Author Name Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper .author-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'author_name_padding',
            [
                'label' => esc_html__('Author Name Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper .author-name' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'author_position_style',
            [
                'label' => esc_html__('Author Position Style', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'author_position_typography',
                'label' => esc_html__('Author Position', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper .author-position',
            ]
        );
        $this->add_control(
            'author_position_color',
            [
                'label' => esc_html__( 'Author Position Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper .author-position' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'author_position_border',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper .author-position',
            ]
        );
        $this->add_responsive_control(
            'author_position_margin',
            [
                'label' => esc_html__('Author Position Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper .author-position' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'author_position_padding',
            [
                'label' => esc_html__('Author Position Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper .author-position' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'author_container_image_style',
            [
                'label' => esc_html__('Author Container Image Style', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'author_image_container_size_options',
            [
                'label' => esc_html__( 'Size', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => '',
                'options' => [
                    'none' => [
                        'title' => esc_html__( 'None', 'element-camp' ),
                        'icon' => 'eicon-ban',
                    ],
                    'grow' => [
                        'title' => esc_html__( 'Grow', 'element-camp' ),
                        'icon' => 'eicon-grow',
                    ],
                    'shrink' => [
                        'title' => esc_html__( 'Shrink', 'element-camp' ),
                        'icon' => 'eicon-shrink',
                    ],
                    'custom' => [
                        'title' => esc_html__( 'Custom', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper .author-image' => '{{VALUE}};',
                ],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'author_image_container_flex_grow',
            [
                'label' => esc_html__( 'Flex Grow', 'element-camp' ),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper .author-image' => 'flex-grow: {{VALUE}};',
                ],
                'default' => 1,
                'placeholder' => 1,
                'responsive' => true,
                'condition' => [
                    'author_image_container_size_options' => 'custom',
                ],
            ]
        );
        $this->add_responsive_control(
            'author_image_container_flex_shrink',
            [
                'label' => esc_html__( 'Flex Shrink', 'element-camp' ),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper .author-image' => 'flex-shrink: {{VALUE}};',
                ],
                'default' => 1,
                'placeholder' => 1,
                'responsive' => true,
                'condition' => [
                    'author_image_container_size_options' => 'custom',
                ],
            ]
        );
        $this->add_control(
            'author_image_container_overflow',
            [
                'label' => esc_html__('Author Image Container Overflow', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'hidden' => esc_html__('Hidden', 'element-camp'),
                    'visible' => esc_html__('Visible', 'element-camp'),
                ],
                'label_block' => true,
                'default' => 'hidden',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper .author-image' => 'overflow: {{VALUE}};',
                ],

            ]
        );
        $this->add_responsive_control(
            'author_image_container_width',
            [
                'label' => esc_html__( 'Author Image Container Width', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper .author-image' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'author_image_container_height',
            [
                'label' => esc_html__( 'Author Image Container Height', 'element-camp' ),
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
                'size_units' => [ 'px', 'vh', '%', 'vw', 'rem', 'custom'],
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper .author-image' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'author_image_container_border_radius',
            [
                'label' => esc_html__( 'Author Image Container Border Radius', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper .author-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'author_image_container_margin',
            [
                'label' => esc_html__('Author Image Container Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper .author-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'author_image_container_padding',
            [
                'label' => esc_html__('Author Image Container Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper .author-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'author_image_style',
            [
                'label' => esc_html__('Author Image Style', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'author_image_width',
            [
                'label' => esc_html__( 'Author Image Width', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper .author-image img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'author_image_height',
            [
                'label' => esc_html__( 'Author Image Height', 'element-camp' ),
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
                'size_units' => [ 'px', 'vh', '%', 'vw', 'rem', 'custom'],
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper .author-image img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'author_image_object_fit',
            [
                'label' => esc_html__( 'Object Fit', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'author_image_height[size]!' => '',
                ],
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'fill' => esc_html__( 'Fill', 'element-camp' ),
                    'cover' => esc_html__( 'Cover', 'element-camp' ),
                    'contain' => esc_html__( 'Contain', 'element-camp' ),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper .author-image img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'author_image_object_position',
            [
                'label' => esc_html__( 'Object Position', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'center center' => esc_html__( 'Center Center', 'element-camp' ),
                    'center left' => esc_html__( 'Center Left', 'element-camp' ),
                    'center right' => esc_html__( 'Center Right', 'element-camp' ),
                    'top center' => esc_html__( 'Top Center', 'element-camp' ),
                    'top left' => esc_html__( 'Top Left', 'element-camp' ),
                    'top right' => esc_html__( 'Top Right', 'element-camp' ),
                    'bottom center' => esc_html__( 'Bottom Center', 'element-camp' ),
                    'bottom left' => esc_html__( 'Bottom Left', 'element-camp' ),
                    'bottom right' => esc_html__( 'Bottom Right', 'element-camp' ),
                ],
                'default' => 'center center',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper .author-image img' => 'object-position: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_author_container_overlay_style',
            [
                'label' => esc_html__('Author Container Overlay Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['author_container_overlay_switcher'=>'yes']
            ]
        );

        $this->add_control(
            'author_container_overlay_index',
            [
                'label' => esc_html__( 'Author Container Overlay z-index', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper::after' => 'z-index: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'author_container_overlay_offset_orientation_h',
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
            ]
        );
        $this->add_responsive_control(
            'author_container_overlay_offset_x',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper::after' => 'left: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'author_container_overlay_offset_orientation_h' => 'start',
                ],
            ]
        );
        $this->add_responsive_control(
            'author_container_overlay_offset_x_end',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper::after' => 'right: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'author_container_overlay_offset_orientation_h' => 'end',
                ],
            ]
        );
        $this->add_control(
            'author_container_overlay_offset_orientation_v',
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
            ]
        );
        $this->add_responsive_control(
            'author_container_overlay_offset_y',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper::after' => 'top: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'author_container_overlay_offset_orientation_v' => 'start',
                ],
            ]
        );
        $this->add_responsive_control(
            'author_container_overlay_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper::after' => 'bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'author_container_overlay_offset_orientation_v' => 'end',
                ],
            ]
        );
        $this->add_control(
            'author_container_overlay_transition',
            [
                'label' => esc_html__( 'Author Container Overlay Transition', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper::after' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );
        $this->start_controls_tabs(
            'author_container_overlay_tabs',
        );
        $this->start_controls_tab(
            'normal_author_container_overlay_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_responsive_control(
            'author_container_overlay_width',
            [
                'label' => esc_html__( 'Width', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
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
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper::after' => 'position:absolute;content:"";width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'author_container_overlay_height',
            [
                'label' => esc_html__( 'Height', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
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
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper::after' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'author_container_overlay_bg',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper::after',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
            ]
        );
        $this->add_control(
            'author_container_overlay_opacity',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper::after' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'hover_author_container_overlay_tab',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
            ]
        );
        $this->add_responsive_control(
            'author_container_overlay_width_hover',
            [
                'label' => esc_html__( 'Width', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper:hover::after' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'author_container_overlay_height_hover',
            [
                'label' => esc_html__( 'Height', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper:hover::after' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'author_container_overlay_bg_hover',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper .author-wrapper:hover::after',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
            ]
        );
        $this->add_control(
            'author_container_overlay_opacity_hover',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .testi-card .author-wrapper .author-wrapper:hover::after' => 'opacity: {{SIZE}};',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows' => 'position: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows' => 'height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows' => 'color: {{VALUE}}; fill: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'arrows_arrow_typography',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows .tcgelements-testimonials-slider-arrow-text',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'arrows_background',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows',
                'exclude' => ['image']
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'arrows_border',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'arrows_box_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows:hover' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'arrows_arrow_typography_hover',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows:hover .tcgelements-testimonials-slider-arrow-text',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'arrows_background_hover',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows:hover',
                'exclude' => ['image']
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'arrows_border_hover',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows:hover',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'arrows_box_shadow_hover',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows:hover',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

        $this->add_control(
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'arrows_offset_orientation_v' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrows_z_index',
            [
                'label' => esc_html__('Z-Index', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows' => 'z-index: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-next' => 'position: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-next' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-next' => 'height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-next' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'next_arrow_typography',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-next .tcgelements-testimonials-slider-arrow-text',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'next_arrow_background',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-next',
                'exclude' => ['image']
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'next_arrow_border',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-next',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'next_arrow_box_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-next',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-next:hover' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'next_arrow_typography_hover',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-next:hover .tcgelements-testimonials-slider-arrow-text',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'next_arrow_background_hover',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-next:hover',
                'exclude' => ['image']
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'next_arrow_border_hover',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-next:hover',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-next:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'next_arrow_box_shadow_hover',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-next:hover',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-next' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-next' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

        $this->add_control(
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-next' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-next' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-next' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-next' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'next_arrow_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_control(
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-next' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-next' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-next' => 'z-index: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-prev' => 'position: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-prev' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-prev' => 'height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-prev' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'prev_arrow_typography',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-prev .tcgelements-testimonials-slider-arrow-text',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'prev_arrow_background',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-prev',
                'exclude' => ['image']
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'prev_arrow_border',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-prev',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'prev_arrow_box_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-prev',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-prev:hover' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'prev_arrow_typography_hover',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-prev:hover .tcgelements-testimonials-slider-arrow-text',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'prev_arrow_background_hover',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-prev:hover',
                'exclude' => ['image']
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'prev_arrow_border_hover',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-prev:hover',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-prev:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'prev_arrow_box_shadow_hover',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-prev:hover',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-prev' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-prev' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

        $this->add_control(
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-prev' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-prev' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-prev' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-prev' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'prev_arrow_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_control(
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-prev' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-prev' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .tcgelements-testimonials-slider-arrows.swiper-button-prev' => 'z-index: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-progressbar.swiper-pagination-horizontal' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-progressbar.swiper-pagination-vertical' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-progressbar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; width: calc(100% - ({{RIGHT}}{{UNIT}} + {{LEFT}}{{UNIT}}));',
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-progressbar.swiper-progressbar-vertical' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; height: calc(100% - (({{TOP}}{{UNIT}} + {{BOTTOM}}{{UNIT}}) * 3));',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'pagination_progress_bar_progress',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-progressbar .swiper-pagination-progressbar-fill',
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
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-progressbar',
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
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-progressbar',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-progressbar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-progressbar .swiper-pagination-progressbar-fill' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-bullets',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-bullets' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-bullets' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-bullets' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-bullets' => 'position: {{VALUE}};',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-bullets.swiper-pagination-horizontal' => 'left: {{SIZE}}{{UNIT}}; right: unset; transform: translateX(50%);',
                    'body.rtl {{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-bullets.swiper-pagination-horizontal' => 'right: {{SIZE}}{{UNIT}}; left: unset; transform: translateX(-50%);',
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-bullets.swiper-pagination-vertical' => 'left: {{SIZE}}{{UNIT}}; right: unset; transform: translateY(50%);',
                    'body.rtl {{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-bullets.swiper-pagination-vertical' => 'right: {{SIZE}}{{UNIT}}; left: unset; transform: translateY(50%);',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-bullets.swiper-pagination-horizontal' => 'right: {{SIZE}}{{UNIT}}; left: unset; transform: translateX(-50%);',
                    'body.rtl {{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-bullets.swiper-pagination-horizontal' => 'left: {{SIZE}}{{UNIT}}; right: unset; transform: translateX(50%);',
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-bullets.swiper-pagination-vertical' => 'right: {{SIZE}}{{UNIT}}; left: unset; transform: translateY(50%);',
                    'body.rtl {{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-bullets.swiper-pagination-vertical' => 'left: {{SIZE}}{{UNIT}}; right: unset; transform: translateY(50%);',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-bullets' => 'top: {{SIZE}}{{UNIT}}; bottom: unset; transform: translateY(50%) translateX(50%);',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-bullets' => 'bottom: {{SIZE}}{{UNIT}}; top: unset; transform: translateY(-50%) translateX(50%);',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-bullets' => 'z-index: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-bullets .swiper-pagination-bullet' => 'margin: 0 {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-bullets .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-bullets .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'pagination_bullet_background',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-bullets .swiper-pagination-bullet',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-bullets .swiper-pagination-bullet' => 'opacity: {{SIZE}};',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-bullets .swiper-pagination-bullet' => 'outline-style: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-bullets .swiper-pagination-bullet' => 'outline-width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-bullets .swiper-pagination-bullet' => 'outline-color: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-bullets .swiper-pagination-bullet' => 'outline-offset: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_popover();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'pagination_bullet_border',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-bullets .swiper-pagination-bullet',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-bullets .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'pagination_active_bullet_background',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'opacity: {{SIZE}};',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'outline-style: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'outline-width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'outline-color: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'outline-offset: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_popover();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'pagination_active_bullet_border',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination' => 'left: {{SIZE}}{{UNIT}}; transform: translateX(-50%);',
                    'body.rtl {{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination' => 'right: {{SIZE}}{{UNIT}}; transform: translateX(50%);',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination' => 'right: {{SIZE}}{{UNIT}}; transform: translateX(50%);',
                    'body.rtl {{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination' => 'left: {{SIZE}}{{UNIT}}; transform: translateX(-50%);',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination' => 'top: {{SIZE}}{{UNIT}}; bottom: unset; transform: translateY(50%);',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination' => 'bottom: {{SIZE}}{{UNIT}}; top: unset; transform: translateY(-50%);',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination' => 'z-index: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-fraction' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'pagination_fraction_typography',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-fraction',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'pagination_fraction_text_stroke',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-fraction',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'pagination_fraction_text_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-fraction',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-fraction' => 'mix-blend-mode: {{VALUE}}',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-fraction .swiper-pagination-current' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'pagination_fraction_current_typography',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-fraction .swiper-pagination-current',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'pagination_fraction_current_text_stroke',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-fraction .swiper-pagination-current',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'pagination_fraction_current_text_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-fraction .swiper-pagination-current',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-fraction .swiper-pagination-current' => 'mix-blend-mode: {{VALUE}}',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-fraction .swiper-pagination-current' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-fraction .swiper-pagination-total' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'pagination_fraction_total_typography',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-fraction .swiper-pagination-total',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'pagination_fraction_total_text_stroke',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-fraction .swiper-pagination-total',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'pagination_fraction_total_text_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-fraction .swiper-pagination-total',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-fraction .swiper-pagination-total' => 'mix-blend-mode: {{VALUE}}',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-pagination-fraction .swiper-pagination-total' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-testimonials-slider .swiper-scrollbar.scrollbar-vertical, body:not(.rtl) {{WRAPPER}} .tcgelements-testimonials-slider .swiper-scrollbar.scrollbar-horizontal' => 'left: {{SIZE}}{{UNIT}}; transform: translateX(-50%);',
                    'body.rtl {{WRAPPER}} .tcgelements-testimonials-slider .swiper-scrollbar.scrollbar-vertical, {{WRAPPER}} body.rtl .tcgelements-testimonials-slider .swiper-scrollbar.scrollbar-horizontal' => 'right: {{SIZE}}{{UNIT}}; transform: translateX(50%);',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-testimonials-slider .swiper-scrollbar.scrollbar-vertical, body:not(.rtl) {{WRAPPER}} .tcgelements-testimonials-slider .swiper-scrollbar.scrollbar-horizontal' => 'right: {{SIZE}}{{UNIT}}; transform: translateX(50%);',
                    'body.rtl {{WRAPPER}} .tcgelements-testimonials-slider .swiper-scrollbar.scrollbar-vertical, {{WRAPPER}} body.rtl .tcgelements-testimonials-slider .swiper-scrollbar.scrollbar-horizontal' => 'left: {{SIZE}}{{UNIT}}; transform: translateX(-50%);',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-scrollbar.scrollbar-vertical, {{WRAPPER}} .tcgelements-testimonials-slider .swiper-scrollbar.scrollbar-horizontal' => 'top: {{SIZE}}{{UNIT}}; bottom: unset; transform: translateY(50%);',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-scrollbar.scrollbar-vertical, {{WRAPPER}} .tcgelements-testimonials-slider .swiper-scrollbar.scrollbar-horizontal' => 'bottom: {{SIZE}}{{UNIT}}; top: unset; transform: translateY(-50%);',
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
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-scrollbar .swiper-scrollbar-drag',
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
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-scrollbar',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-scrollbar.scrollbar-horizontal, {{WRAPPER}} .tcgelements-testimonials-slider .swiper-scrollbar.scrollbar-horizontal .swiper-scrollbar-drag' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-scrollbar.scrollbar-vertical, {{WRAPPER}} .tcgelements-testimonials-slider .swiper-scrollbar.scrollbar-vertical .swiper-scrollbar-drag' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-scrollbar.swiper-scrollbar-horizontal' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; width: calc(100% - ({{RIGHT}}{{UNIT}} + {{LEFT}}{{UNIT}}));',
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-scrollbar.swiper-scrollbar-vertical' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; height: calc(100% - (({{TOP}}{{UNIT}} + {{BOTTOM}}{{UNIT}}) * 3));',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'scroll_bar_border',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-scrollbar',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-scrollbar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-testimonials-slider .swiper-scrollbar .swiper-scrollbar-drag' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .arrows-pagination-container',
                'exclude' => ['image']
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .arrows-pagination-container' => 'position: {{VALUE}};',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-testimonials-slider .arrows-pagination-container' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-testimonials-slider .arrows-pagination-container' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-testimonials-slider .arrows-pagination-container' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-testimonials-slider .arrows-pagination-container' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .arrows-pagination-container' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .arrows-pagination-container' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'arrows_pagination_container_offset_orientation_v' => 'end',
                ],
            ]
        );

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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .arrows-pagination-container' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .arrows-pagination-container' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'arrows_pagination_container_border',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .arrows-pagination-container',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .arrows-pagination-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'arrows_pagination_container_box_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-testimonials-slider .arrows-pagination-container',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .arrows-pagination-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-testimonials-slider .arrows-pagination-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

        //resposive setttings
        $resposive_settings = [];
        $slider_settings = [];

        foreach ($settings['responsive_settings'] as $index => $item) {
            $resposive_settings[$item['breakpoint']] = [
                'slidesPerView' => $item['slidesPerView_auto'] === 'true' ? 'auto' : $item['slidesPerView'],
                'spaceBetween' => $item['spaceBetween'],
            ];
        };

        $slider_settings = [
            'breakpoints' => $resposive_settings,
            'speed' => $settings['speed'],
            'direction' => $settings['direction'],
            'autoHeight' => $settings['autoHeight'],
            'effect' => $settings['effect'],
            'loop' => $settings['loop'],
            'centeredSlides' => $settings['centeredSlides'],
            'oneWayMovement' => $settings['oneWayMovement'],
            'keyboard' => $settings['keyboard'],
            'mousewheel' => $settings['mousewheel'],
            'paginationType' => $settings['paginationType'],
            'cardsOffset' => $settings['cardsOffset'],
            'cardsRotate' => $settings['cardsRotate']
        ];

        if ($settings['autoplay'] === 'true') {
            $slider_settings['autoplay'] = [
                'delay' => $settings['autoplay_delay'],
                'reverseDirection' => $settings['reverseDirection'],
                'disableOnInteraction' => $settings['disableOnInteraction']
            ];
        }

        $max_slider_id = uniqid('tcgelements-testimonials-slider-');

        ?>

        <div id="<?php echo esc_attr($max_slider_id) ?>" class="tcgelements-testimonials-slider" data-tcgelements-testimonials-slider='<?php echo esc_attr(json_encode($slider_settings)); ?>'>
            <div class="swiper-container <?php if ($settings['effect'] == 'parallax') echo esc_attr('tcg-dynamic-parallax-slider'); ?>">
                <div class="swiper-wrapper">
                    <?php foreach ($settings['tcg_dynamic_slides_repeater'] as $index => $slide) : ?>
                        <div class="swiper-slide">
                            <div class="testi-card">
                                <?php if ($settings['separate_container_for_author']=='yes') : ?>
                                <div class="separate-content-container">
                                <?php endif;?>
                                    <?php if($slide['rate_switcher']=='yes') : ?>
                                        <div class="rate">
                                            <?php echo esc_html($slide['rate_text']);?>
                                            <span class="stars">
                                                <?php for($i = 0; $i < $slide['rate_number']; $i++)
                                                { Icons_Manager::render_icon($slide['rate_icon']); }
                                                ?>
                                            </span>
                                        </div>
                                    <?php endif;?>
                                    <?php if($slide['date_switcher']=='yes') : ?>
                                        <div class="date">
                                            <img src="<?= esc_url($slide['quote_date_image']['url']); ?>" alt="<?php if (!empty($slide['quote_date_image']['alt'])) echo esc_attr($slide['quote_date_image']['alt']); ?>" >
                                            <span class="txt"> <?=esc_html($slide['quote_date'])?> </span>
                                        </div>
                                    <?php endif;?>
                                    <div class="quote-text">
                                        <?php if (!empty($slide['quote_text_image']['url'])) : ?>
                                            <img src="<?= esc_url($slide['quote_text_image']['url']); ?>" alt="<?php if (!empty($slide['quote_text_image']['alt'])) echo esc_attr($slide['quote_text_image']['alt']); ?>" >
                                        <?php endif;?>
                                        <div><?=esc_html($slide['quote_text'])?></div>
                                    </div>
                                <?php if ($settings['separate_container_for_author']=='yes') : ?>
                                </div>
                                <?php endif;?>
                                <div class="author-wrapper">
                                    <?php if (!empty($slide['author_image']['url'])) : ?>
                                        <div class="author-image">
                                            <img src="<?= esc_url($slide['author_image']['url']); ?>" alt="<?php if (!empty($slide['author_image']['alt'])) echo esc_attr($slide['author_image']['alt']); ?>" >
                                        </div>
                                    <?php endif;?>
                                    <div class="cont">
                                        <h6 class="author-name"> <?=esc_html($slide['author_name'])?> </h6>
                                        <p class="author-position"> <?=esc_html($slide['author_position'])?> </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php if ($settings['arrows_pagination_container'] === 'true') : echo '<div class="arrows-pagination-container">';
            endif; ?>

            <?php if ($settings['pagination'] === 'true') : ?>
                <!-- If we need pagination -->
                <div class="swiper-pagination swiper-pagination-<?= $settings['direction'] ?>"></div>
            <?php endif; ?>

            <?php if ($settings['arrows'] === 'true') : ?>
                <!-- If we need navigation buttons -->
                <div class="swiper-button-prev tcgelements-testimonials-slider-arrows">
                    <?php if ($settings['arrows_style'] === 'icon') :
                        Icons_Manager::render_icon($settings['prev_arrow_icon'], ['aria-hidden' => 'true']);
                    elseif ($settings['arrows_style'] === 'text') :
                        echo wp_kses_post($settings['prev_arrow_text']);
                    elseif ($settings['arrows_style'] === 'text-icon') :
                        Icons_Manager::render_icon($settings['prev_arrow_icon'], ['aria-hidden' => 'true']);
                        echo '<span class="tcgelements-testimonials-slider-arrow-text">' . wp_kses_post($settings['prev_arrow_text']) . '</span>';
                    endif; ?>
                </div>
                <div class="swiper-button-next tcgelements-testimonials-slider-arrows">
                    <?php if ($settings['arrows_style'] === 'icon') :
                        Icons_Manager::render_icon($settings['next_arrow_icon'], ['aria-hidden' => 'true']);
                    elseif ($settings['arrows_style'] === 'text') :
                        echo wp_kses_post($settings['next_arrow_text']);
                    elseif ($settings['arrows_style'] === 'text-icon') :
                        echo '<span class="tcgelements-testimonials-slider-arrow-text">' . wp_kses_post($settings['next_arrow_text']) . '</span>';
                        Icons_Manager::render_icon($settings['next_arrow_icon'], ['aria-hidden' => 'true']);
                    endif; ?>
                </div>
            <?php endif; ?>

            <?php if ($settings['arrows_pagination_container'] === 'true') : echo '</div>';
            endif; ?>

            <?php if ($settings['scrollbar'] === 'true') : ?>
                <!-- If we need scrollbar -->
                <div class="swiper-scrollbar scrollbar-<?= $settings['direction'] ?>"></div>
            <?php endif; ?>

        </div>

        <?php
    }
}
