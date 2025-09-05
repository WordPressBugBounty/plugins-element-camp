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
class ElementCamp_Services_Slider extends Widget_Base
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
        return 'tcgelements-services-slider';
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
        return esc_html__('Advanced Slider', 'element-camp');
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
        return 'eicon-posts-ticker tce-widget-badge';
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
        return ['swiper-gl.min','simplex-noise','ogl.min','shaders','getGeometry','displacement','swiper-gl.esm','tcgelements-services-slider'];
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
            'service_image',
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

        $slides_repeater->add_control(
            'link',
            [
                'label' => esc_html__( 'Link', 'element-camp' ),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => '',
                ],
                'separator' => 'before',
            ]
        );

        $slides_repeater->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'element-camp'),
                'default' => esc_html__('Business Strategy Development', 'element-camp'),
                'type' => Controls_Manager::TEXTAREA,
            ]
        );

        $slides_repeater->add_control(
            'description_switcher',
            [
                'label' => esc_html__( 'Description Switcher', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'yes', 'element-camp' ),
                'label_off' => esc_html__( 'No', 'element-camp' ),
            ]
        );

        $slides_repeater->add_control(
            'description',
            [
                'label' => esc_html__('Description', 'element-camp'),
                'default' => esc_html__('Add Text Here.', 'element-camp'),
                'type' => Controls_Manager::TEXTAREA,
                'condition' => ['description_switcher'=>'yes'],
            ]
        );

        $slides_repeater->add_control(
            'button_switcher',
            [
                'label' => esc_html__( 'Button Switcher', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'yes', 'element-camp' ),
                'label_off' => esc_html__( 'No', 'element-camp' ),
            ]
        );

        $slides_repeater->add_control(
            'btn_text',
            [
                'label' => esc_html__('Button Text', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__('Click me', 'element-camp'),
                'condition' => ['button_switcher'=>'yes'],
            ]
        );

        $slides_repeater->add_control(
            'selected_icon',
            [
                'label' => esc_html__('Icon', 'element-camp'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'skin' => 'inline',
                'label_block' => false,
                'condition' => ['button_switcher'=>'yes'],
            ]
        );

        $slides_repeater->add_control(
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
            'tcg_dynamic_slides_repeater',
            [
                'label' => esc_html__('Slides', 'element-camp'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $slides_repeater->get_controls(),
                'default' => [
                    [
                        'title' => esc_html__('Title #1', 'element-camp'),
                    ],
                    [
                        'title' => esc_html__('Title #2', 'element-camp'),
                    ],
                    [
                        'title' => esc_html__('Title #3', 'element-camp'),
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
                    'gl' => esc_html__('GL', 'element-camp'),
                ],
                'label_block' => true,
                'default' => 'slide',
            ]
        );

        $this->add_control(
            'shader_picker',
            [
                'label' => esc_html__( 'Choose Shader', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'random',
                'options' => [
                    'random' => esc_html__( 'Random', 'element-camp' ),
                    'dots' => esc_html__( 'dots', 'element-camp' ),
                    'flyeye' => esc_html__( 'flyeye', 'element-camp' ),
                    'morph-x' => esc_html__( 'morph-x', 'element-camp' ),
                    'morph-y' => esc_html__( 'morph-y', 'element-camp' ),
                    'page-curl' => esc_html__( 'page-curl', 'element-camp' ),
                    'peel-x' => esc_html__( 'peel-x', 'element-camp' ),
                    'peel-y' => esc_html__( 'peel-y', 'element-camp' ),
                    'peel-y' => esc_html__( 'peel-y', 'element-camp' ),
                    'polygons-fall' => esc_html__( 'polygons-fall', 'element-camp' ),
                    'polygons-morph' => esc_html__( 'polygons-morph', 'element-camp' ),
                    'polygons-wind' => esc_html__( 'polygons-wind', 'element-camp' ),
                    'pixelize' => esc_html__( 'pixelize', 'element-camp' ),
                    'ripple' => esc_html__( 'ripple', 'element-camp' ),
                    'slices' => esc_html__( 'slices', 'element-camp' ),
                    'shutters' => esc_html__( 'shutters', 'element-camp' ),
                    'squares' => esc_html__( 'squares', 'element-camp' ),
                    'stretch' => esc_html__( 'stretch', 'element-camp' ),
                    'wave-x' => esc_html__( 'wave-x', 'element-camp' ),
                    'wind' => esc_html__( 'wind', 'element-camp' ),
                ],
                'condition' => [
                    'effect' => 'gl',
                ],
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-container' => 'overflow: {{VALUE}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'slider_height',
            [
                'label' => esc_html__( 'Slider Height', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-services-slider' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_container_style',
            [
                'label' => esc_html__('Swiper Container Padding', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'swiper_container_height',
            [
                'label' => esc_html__( 'Swiper Container Height', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-container' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
//      start

        $this->start_controls_section(
            'style',
            [
                'label' => esc_html__('Card Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );
        $this->add_control(
            'card_positioning',
            [
                'label' => esc_html__('Card Position', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'unset' => esc_html__('unset', 'element-camp'),
                    'absolute' => esc_html__('absolute', 'element-camp'),
                    'relative' => esc_html__('relative', 'element-camp'),
                ],
                'label_block' => true,
                'default' => 'relative',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card' => 'position: {{VALUE}};',
                ],
            ]
        );

        $start = is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');
        $end = !is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');

        $this->add_control(
            'card_offset_orientation_h',
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
                    'card_positioning' => 'absolute',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_offset_x',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-services-slider .card' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-services-slider .card' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'card_offset_orientation_h!' => 'end',
                    'card_positioning' => 'absolute',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_offset_x_end',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-services-slider .card' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-services-slider .card' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'card_offset_orientation_h' => 'end',
                    'card_positioning' => 'absolute',
                ],
            ]
        );

        $this->add_control(
            'card_offset_orientation_v',
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
                    'card_positioning' => 'absolute',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_offset_y',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'card_offset_orientation_v!' => 'end',
                    'card_positioning' => 'absolute',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'card_offset_orientation_v' => 'end',
                    'card_positioning' => 'absolute',
                ],
            ]
        );


        $this->add_control(
            'card_overflow_hidden',
            [
                'label' => esc_html__( 'Overflow Hidden', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'yes', 'element-camp' ),
                'label_off' => esc_html__( 'No', 'element-camp' ),
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card' => 'overflow:hidden;',
                ],
            ]
        );
        $this->add_responsive_control(
            'card_display',
            [
                'label' => esc_html__('Card Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'flex',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'inline-flex' => esc_html__('Inline Flex', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'card_display_position',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card' => '{{VALUE}}',
                ],
                'condition' => [ 'card_display' => 'flex' ],
            ]
        );
        $this->add_responsive_control(
            'card_justify_content',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card' => 'justify-content: {{VALUE}};',
                ],
                'condition'=>['card_display'=> ['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'card_align_items',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card' => 'align-items: {{VALUE}};',
                ],
                'condition'=>['card_display'=> ['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'card_flex_wrap',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card' => 'flex-wrap: {{VALUE}};',
                ],
                'condition'=>['card_display'=> ['flex','inline-flex']],
            ]
        );
        $this->add_responsive_control(
            'card_margin',
            [
                'label' => esc_html__('Card Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label' => esc_html__('Card Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_width',
            [
                'label' => esc_html__( 'Card Width', 'element-camp' ),
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
                'size_units' => [ 'px', 'vh', 'vw', '%', 'vw', 'rem', 'custom'],
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
                    '{{WRAPPER}} .tcgelements-services-slider .card' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_max_width',
            [
                'label' => esc_html__( 'Card Max Width', 'element-camp' ),
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
                'size_units' => [ 'px', 'vh', 'vw', '%', 'vw', 'rem', 'custom'],
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
                    '{{WRAPPER}} .tcgelements-services-slider .card' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_height',
            [
                'label' => esc_html__( 'Card Height', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-services-slider .card' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_align_text',
            [
                'label' => esc_html__( 'Card Alignment', 'element-camp' ),
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
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label' => esc_html__( 'Card Border Radius', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );
        $this->start_controls_tabs(
            'card_tabs',
        );
        $this->add_control(
            'card_transition',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );
        $this->start_controls_tab(
            'normal_card_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'card_border',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'card_background_color',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_box_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card',
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'hover_card_tab',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'hover_card_border',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card:hover',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'card_background_color_hover',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card:hover',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_box_shadow_hover',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card:hover',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        $this->start_controls_section(
            'card_overlay_section',
            [
                'label' => esc_html__('Card Overlay', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'card_overlay_index',
            [
                'label' => esc_html__( 'Card Overlay z-index', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card::after' => 'z-index: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'card_overlay_offset_orientation_h',
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
            'card_overlay_offset_x',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card::after' => 'left: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'card_overlay_offset_orientation_h' => 'start',
                ],
            ]
        );
        $this->add_responsive_control(
            'card_overlay_offset_x_end',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card::after' => 'right: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'card_overlay_offset_orientation_h' => 'end',
                ],
            ]
        );
        $this->add_control(
            'card_overlay_offset_orientation_v',
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
            'card_overlay_offset_y',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card::after' => 'top: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'card_overlay_offset_orientation_v' => 'start',
                ],
            ]
        );
        $this->add_responsive_control(
            'card_overlay_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card::after' => 'bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'card_overlay_offset_orientation_v' => 'end',
                ],
            ]
        );
        $this->add_control(
            'card_overlay_transition',
            [
                'label' => esc_html__( 'Card Overlay Transition', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card::after' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );
        $this->start_controls_tabs(
            'card_overlay_tabs',
        );
        $this->start_controls_tab(
            'normal_card_overlay_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_responsive_control(
            'card_overlay_width',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card::after' => 'position:absolute;content:"";width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'card_overlay_height',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card::after' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'card_overlay_bg',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card::after',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
            ]
        );
        $this->add_control(
            'card_overlay_opacity',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card::after' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'hover_card_overlay_tab',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
            ]
        );
        $this->add_responsive_control(
            'card_overlay_width_hover',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card::after' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'card_overlay_height_hover',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card:hover::after' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'card_overlay_bg_hover',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card:hover::after',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
            ]
        );
        $this->add_control(
            'card_overlay_opacity_hover',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card:hover::after' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
        $this->start_controls_section(
            'info_style',
            [
                'label' => esc_html__('Info Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );
        $this->add_control(
            'info_section_animation',
            [
                'label' => esc_html__( 'Info Section Animation', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'yes', 'element-camp' ),
                'label_off' => esc_html__( 'No', 'element-camp' ),
            ]
        );
        $this->add_control(
            'info_index',
            [
                'label' => esc_html__( 'Info z-index', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .inf' => 'z-index: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'info_container_heading',
            [
                'label' => esc_html__( 'Info Container Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );
        $this->add_responsive_control(
            'info_container_text_align',
            [
                'label' => esc_html__( 'Info Text Alignment', 'element-camp' ),
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
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .inf' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'info_container_width',
            [
                'label' => esc_html__( 'Info Container Width', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .inf' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'info_container_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .inf' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'info_section_padding',
            [
                'label' => esc_html__('Info Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .inf' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'position_for_info_container',
            [
                'label' => esc_html__( 'Info Container Position', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'absolute' => esc_html__( 'Absolute', 'element-camp' ),
                    'relative' => esc_html__( 'Relative', 'element-camp' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .inf' => 'position: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'info_container_offset_orientation_h',
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
                    'position_for_info_container!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'info_container_offset_x',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .inf' => 'left: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'info_container_offset_orientation_h' => 'start',
                    'position_for_info_container!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'info_container_offset_x_end',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .inf' => 'right: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'info_container_offset_orientation_h' => 'end',
                    'position_for_info_container!' => '',
                ],
            ]
        );
        $this->add_control(
            'info_container_offset_orientation_v',
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
                    'position_for_info_container!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'info_container_offset_y',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .inf' => 'top: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'info_container_offset_orientation_v' => 'start',
                    'position_for_info_container!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'info_container_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .inf' => 'bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'info_container_offset_orientation_v' => 'end',
                    'position_for_info_container!' => '',
                ],
            ]
        );
        $this->start_controls_tabs(
            'info_container_tabs',
        );

        $this->start_controls_tab(
            'info_normal_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_responsive_control(
            'info_section_margin',
            [
                'label' => esc_html__('Info Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .inf' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'info_container_translate_y',
            [
                'label' => esc_html__( 'Translate Y', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
            ]
        );
        $this->add_control(
            'info_container_translate_x',
            [
                'label' => esc_html__( 'Translate X', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .inf' => 'transform: translate({{info_container_translate_x.SIZE}}{{info_container_translate_x.UNIT}},{{info_container_translate_y.SIZE}}{{info_container_translate_y.UNIT}})',
                ],
            ]
        );
        $this->add_control(
            'info_display',
            [
                'label' => esc_html__('Info Display Type', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .inf' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'info_justify_content',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .inf' => 'justify-content: {{VALUE}};',
                ],
                'condition'=>['info_display'=>['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'info_align_items',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .inf' => 'align-items: {{VALUE}};',
                ],
                'condition'=>['info_display'=>['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'info_container_flex_wrap',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .inf' => 'flex-wrap: {{VALUE}};',
                ],
                'condition'=>['info_display'=>['flex','inline-flex']],
            ]
        );
        $this->add_control(
            'info_container_opacity',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .inf' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'info_transition',
            [
                'label' => esc_html__( 'Info Transition', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .inf' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'info_section_border',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card .inf',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'info_container_background_color',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card .inf',
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'info_hover_tab',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
            ]
        );
        $this->add_responsive_control(
            'info_section_margin_hover',
            [
                'label' => esc_html__('Info Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card:hover .inf' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'info_container_translate_y_hover',
            [
                'label' => esc_html__( 'Translate Y', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
            ]
        );
        $this->add_control(
            'info_container_translate_x_hover',
            [
                'label' => esc_html__( 'Translate X', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card:hover .inf' => 'transform: translate({{info_container_translate_x_hover.SIZE}}{{info_container_translate_x_hover.UNIT}},{{info_container_translate_y_hover.SIZE}}{{info_container_translate_y_hover.UNIT}})',
                ],
            ]
        );
        $this->add_control(
            'info_display_hover',
            [
                'label' => esc_html__('Info Display Type', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-services-slider .card:hover .inf' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'info_justify_content_hover',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card:hover .inf' => 'justify-content: {{VALUE}};',
                ],
                'condition'=>['info_display_hover'=>['flex','inline-flex']],
                'responsive' => true,
            ]);
        $this->add_responsive_control(
            'info_align_items_hover',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card:hover .inf' => 'align-items: {{VALUE}};',
                ],
                'condition'=>['info_display_hover'=>['flex','inline-flex']],
                'responsive' => true,
            ]);
        $this->add_control(
            'info_container_opacity_hover',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card:hover .inf' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'info_section_border_hover',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card:hover .inf',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'info_container_background_color_hover',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card:hover .inf',
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->add_control(
            'title_heading',
            [
                'label' => esc_html__( 'Title Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );
        $this->add_control(
            'title_white_space',
            [
                'label' => esc_html__('White Space', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'normal' => esc_html__('Normal', 'element-camp'),
                    'nowrap' => esc_html__('No Wrap', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .title' => 'white-space: {{VALUE}};'
                ]
            ]
        );
        $this->add_control(
            'title_transition',
            [
                'label' => esc_html__( 'Title Transition', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .title' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_width',
            [
                'label' => esc_html__( 'Title Width', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .title' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'after_title_style',
            [
                'label' => esc_html__( 'After Title Style', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'yes', 'element-camp' ),
                'label_off' => esc_html__( 'No', 'element-camp' ),
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .title::after' => 'content:"";',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_padding',
            [
                'label' => esc_html__('Title Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'position_for_title',
            [
                'label' => esc_html__( 'Title Position', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'absolute' => esc_html__( 'Absolute', 'element-camp' ),
                    'relative' => esc_html__( 'Relative', 'element-camp' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .title' => 'position: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'title_offset_orientation_h',
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
                    'position_for_title!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_offset_x',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .title' => 'left: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'title_offset_orientation_h' => 'start',
                    'position_for_title!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_offset_x_end',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .title' => 'right: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'title_offset_orientation_h' => 'end',
                    'position_for_title!' => '',
                ],
            ]
        );
        $this->add_control(
            'title_offset_orientation_v',
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
                    'position_for_title!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_offset_y',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .title' => 'top: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'title_offset_orientation_v' => 'start',
                    'position_for_title!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .title' => 'bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'title_offset_orientation_v' => 'end',
                    'position_for_title!' => '',
                ],
            ]
        );
        $this->start_controls_tabs(
            'title_tabs',
        );

        $this->start_controls_tab(
            'normal',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Title Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__('Title', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card .title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'title_border',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card .inf .title',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'title_background_color',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card .inf .title',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'hover',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
            ]
        );
        $this->add_responsive_control(
            'title_margin_hover',
            [
                'label' => esc_html__('Title Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card:hover .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_hover_typography',
                'label' => esc_html__('Title Hover', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card:hover .title',
            ]
        );

        $this->add_control(
            'title_color_hover',
            [
                'label' => esc_html__( 'Title Color Hover', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card:hover .title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'title_border_hover',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card:hover .inf .title',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'title_background_color_hover',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card:hover .inf .title',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
        $this->start_controls_section(
            'section_info_animations',
            [
                'label' => esc_html__( 'Info Section Animations', 'element-camp' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'info_section_animation' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'info_section_animations',
            [
                'label' => esc_html__('Animations', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'tc-anim-lines' => esc_html__('Split Text Lines Animation', 'element-camp'),
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'title_after_style',
            [
                'label' => esc_html__('After Title Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'after_title_style' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'position_for_after_title',
            [
                'label' => esc_html__( 'Position', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'absolute',
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'absolute' => esc_html__( 'Absolute', 'element-camp' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .title::after' => 'position: {{VALUE}};',
                ],
                'separator' => 'after',
            ]
        );
        $this->add_control(
            'title_after_offset_orientation_h',
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
                    'position_for_after_title!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_after_offset_x',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .title::after' => 'left: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'title_after_offset_orientation_h' => 'start',
                    'position_for_after_title!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_after_offset_x_end',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .title::after' => 'right: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'title_after_offset_orientation_h' => 'end',
                    'position_for_after_title!' => '',
                ],
            ]
        );
        $this->add_control(
            'title_after_offset_orientation_v',
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
                    'position_for_after_title!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_after_offset_y',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .title::after' => 'top: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'title_after_offset_orientation_v' => 'start',
                    'position_for_after_title!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_after_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .title::after' => 'bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'title_after_offset_orientation_v' => 'end',
                    'position_for_after_title!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'after_width',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .title::after' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'after_height',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .title::after' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'after_title_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .title::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after'
            ]
        );
        $this->start_controls_tabs(
            'after_title_tabs',
        );
        
        $this->start_controls_tab(
            'after_title_normal_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'after_background_color',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card .title::after',
            ]
        );
        $this->end_controls_tab();
        
        $this->start_controls_tab(
            'after_title_hover_tab',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'hover_after_background_color',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card:hover .title::after',
            ]
        );
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        $this->end_controls_section();
        $this->start_controls_section(
            'description_style',
            [
                'label' => esc_html__('Description Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'position_for_description',
            [
                'label' => esc_html__( 'Description Position', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'absolute' => esc_html__( 'Absolute', 'element-camp' ),
                    'relative' => esc_html__( 'Relative', 'element-camp' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .description' => 'position: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'description_offset_orientation_h',
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
                    'position_for_description!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'description_offset_x',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .description' => 'left: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'description_offset_orientation_h' => 'start',
                    'position_for_description!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'description_offset_x_end',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .description' => 'right: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'description_offset_orientation_h' => 'end',
                    'position_for_description!' => '',
                ],
            ]
        );
        $this->add_control(
            'description_offset_orientation_v',
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
                    'position_for_description!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'description_offset_y',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .description' => 'top: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'description_offset_orientation_v' => 'start',
                    'position_for_description!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'description_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .description' => 'bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'description_offset_orientation_v' => 'end',
                    'position_for_description!' => '',
                ],
            ]
        );
        $this->add_control(
            'description_transition',
            [
                'label' => esc_html__( 'Description Transition', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .description' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'label' => esc_html__('Description', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card .description',
            ]
        );
        $this->start_controls_tabs(
            'description_tabs',
        );
        $this->add_responsive_control(
            'description_margin',
            [
                'label' => esc_html__('Description Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tab(
            'description_normal_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_responsive_control(
            'description_display',
            [
                'label' => esc_html__('Description Display Type', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .description' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'description_justify_content',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .description' => 'justify-content: {{VALUE}};',
                ],
                'condition'=>['description_display'=> ['flex','inline-flex']],
                'responsive' => true,
            ]);
        $this->add_responsive_control(
            'description_align_items',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .description' => 'align-items: {{VALUE}};',
                ],
                'condition'=>['description_display'=> ['flex','inline-flex']],
                'responsive' => true,
            ]);
        $this->add_control(
            'description_color',
            [
                'label' => esc_html__( 'Description Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .description' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'description_opacity',
            [
                'label' => esc_html__( 'Description Opacity', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .description' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'description_hover_tab',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
            ]
        );
        $this->add_responsive_control(
            'description_margin_hover',
            [
                'label' => esc_html__('Description Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card:hover .description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'description_display_hover',
            [
                'label' => esc_html__('Description Display Type', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-services-slider .card:hover .description' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'description_justify_content_hover',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card:hover .description' => 'justify-content: {{VALUE}};',
                ],
                'condition'=>['description_display_hover'=> ['flex','inline-flex']],
                'responsive' => true,
            ]);
        $this->add_responsive_control(
            'description_align_items_hover',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card:hover .description' => 'align-items: {{VALUE}};',
                ],
                'condition'=>['description_display_hover'=> ['flex','inline-flex']],
                'responsive' => true,
            ]);
        $this->add_control(
            'description_color_hover',
            [
                'label' => esc_html__( 'Description Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card:hover .description' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'description_opacity_hover',
            [
                'label' => esc_html__( 'Description Opacity', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card:hover .description' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();

        $this->start_controls_section(
            'icon_style',
            [
                'label' => esc_html__('Image Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );
        $this->add_control(
            'before_image_switcher',
            [
                'label' => esc_html__( 'Before Image Switcher', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'yes', 'element-camp' ),
                'label_off' => esc_html__( 'No', 'element-camp' ),
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .icon:before' => 'content:"";',
                ],
            ]
        );
        $this->add_control(
            'image_overflow_hidden',
            [
                'label' => esc_html__( 'Overflow Hidden', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'yes', 'element-camp' ),
                'label_off' => esc_html__( 'No', 'element-camp' ),
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .icon' => 'overflow:hidden;',
                ],
            ]
        );
        $this->add_control(
            'image_index',
            [
                'label' => esc_html__( 'Image z-index', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .icon' => 'z-index: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'image_transition',
            [
                'label' => esc_html__( 'Image Transition', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .icon' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );
        $this->add_control(
            'image_container_heading',
            [
                'label' => esc_html__( 'Image Container Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'after'
            ]
        );
        $this->add_responsive_control(
            'icon_display',
            [
                'label' => esc_html__('Image Container Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'flex',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'inline-flex' => esc_html__('Inline Flex', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .icon' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'icon_justify_content',
            [
                'label' => esc_html__( 'Image Container Justify Content', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .icon' => 'justify-content: {{VALUE}};',
                ],
                'condition'=>['icon_display'=> ['flex','inline-flex']],
                'responsive' => true,
            ]);
        $this->add_responsive_control(
            'icon_align_items',
            [
                'label' => esc_html__( 'Image Container Align Items', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .icon' => 'align-items: {{VALUE}};',
                ],
                'condition'=>['icon_display'=> ['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'icon_size_options',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .icon' => '{{VALUE}};',
                ],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'icon_flex_grow',
            [
                'label' => esc_html__( 'Flex Grow', 'element-camp' ),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .icon' => 'flex-grow: {{VALUE}};',
                ],
                'default' => 1,
                'placeholder' => 1,
                'responsive' => true,
                'condition' => [
                    'icon_size_options' => 'custom',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_flex_shrink',
            [
                'label' => esc_html__( 'Flex Shrink', 'element-camp' ),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .icon' => 'flex-shrink: {{VALUE}};',
                ],
                'default' => 1,
                'placeholder' => 1,
                'responsive' => true,
                'condition' => [
                    'icon_size_options' => 'custom',
                ],
            ]
        );
        $this->add_control(
            'image_div_order',
            [
                'label' => esc_html__('Image Order', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '0',
                'options' => [
                    '0' => esc_html__('First', 'element-camp'),
                    '1' => esc_html__('Second', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .icon' => 'order: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_align_text',
            [
                'label' => esc_html__( 'Image Container Alignment', 'element-camp' ),
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
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .icon' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_margin',
            [
                'label' => esc_html__('Image Container Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_padding',
            [
                'label' => esc_html__('Image Container Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'container_width',
            [
                'label' => esc_html__( 'Image Container Width', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .icon' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'container_height',
            [
                'label' => esc_html__( 'Image Container Height', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .icon' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs(
            'image_container_tabs',
        );

        $this->start_controls_tab(
            'image_container_normal_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'container_background_color',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card .icon',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card .icon',
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'image_container_hover_tab',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'container_background_color_hover',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card:hover .icon',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'container_border_hover',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card:hover .icon',
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->add_responsive_control(
            'container_border_radius',
            [
                'label' => esc_html__( 'Image Container Border Radius', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after'
            ]
        );
        $this->add_responsive_control(
            'position_for_image',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .icon' => 'position: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'image_offset_orientation_h',
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
                    'position_for_image!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_offset_x',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .icon' => 'left: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'image_offset_orientation_h' => 'start',
                    'position_for_image!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_offset_x_end',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .icon' => 'right: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'image_offset_orientation_h' => 'end',
                    'position_for_image!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_offset_orientation_v',
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
                    'position_for_image!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_offset_y',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .icon' => 'top: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'image_offset_orientation_v' => 'start',
                    'position_for_image!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .icon' => 'bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'image_offset_orientation_v' => 'end',
                    'position_for_image!' => '',
                ],
            ]
        );
        $this->add_control(
            'image_heading',
            [
                'label' => esc_html__( 'Image Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'after'
            ]
        );
        $this->add_responsive_control(
            'width',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .icon img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'max_width',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .icon img' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'height',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .icon img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'object-fit',
            [
                'label' => esc_html__( 'Object Fit', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'height[size]!' => '',
                ],
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'fill' => esc_html__( 'Fill', 'element-camp' ),
                    'cover' => esc_html__( 'Cover', 'element-camp' ),
                    'contain' => esc_html__( 'Contain', 'element-camp' ),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .icon img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'object-position',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .icon img' => 'object-position: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'img_border_radius',
            [
                'label' => esc_html__( 'Image Border Radius', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .icon img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );
        $this->start_controls_tabs(
            'image_tabs',
        );

        $this->start_controls_tab(
            'image_normal_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'image_css_filters',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card .icon img',
            ]
        );
        $this->add_control(
            'custom_css_filter',
            [
                'label' => esc_html__('Custom CSS Filter', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
            ]
        );
        $this->add_control(
            'filter_invert',
            [
                'label' => esc_html__( 'Invert', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 0,
                ],
                'condition' => [
                    'custom_css_filter' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'filter_sepia',
            [
                'label' => esc_html__( 'Sepia', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 0,
                ],
                'condition' => [
                    'custom_css_filter' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'filter_saturate',
            [
                'label' => esc_html__( 'Saturate', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 200,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'condition' => [
                    'custom_css_filter' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'filter_hue_rotate',
            [
                'label' => esc_html__( 'Hue Rotate', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'deg' ],
                'range' => [
                    'deg' => [
                        'min' => 0,
                        'max' => 360,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'deg',
                    'size' => 0,
                ],
                'condition' => [
                    'custom_css_filter' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'filter_brightness',
            [
                'label' => esc_html__( 'Brightness', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'condition' => [
                    'custom_css_filter' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'filter_contrast',
            [
                'label' => esc_html__( 'Contrast', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 200,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .icon img' => 'filter: invert({{filter_invert.SIZE}}{{filter_invert.UNIT}}) sepia({{filter_sepia.SIZE}}{{filter_sepia.UNIT}}) saturate({{filter_saturate.SIZE}}{{filter_saturate.UNIT}}) hue-rotate({{filter_hue_rotate.SIZE}}{{filter_hue_rotate.UNIT}}) brightness({{filter_brightness.SIZE}}{{filter_brightness.UNIT}}) contrast({{filter_contrast.SIZE}}{{filter_contrast.UNIT}});',
                ],
                'condition' => [
                    'custom_css_filter' => 'yes',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'image_hover_tab',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'image_css_filters_hover',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card:hover .icon img',
            ]
        );
        $this->add_control(
            'filter_invert_hover',
            [
                'label' => esc_html__( 'Invert', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 0,
                ],
                'condition' => [
                    'custom_css_filter' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'filter_sepia_hover',
            [
                'label' => esc_html__( 'Sepia', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 0,
                ],
                'condition' => [
                    'custom_css_filter' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'filter_saturate_hover',
            [
                'label' => esc_html__( 'Saturate', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 200,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'condition' => [
                    'custom_css_filter' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'filter_hue_rotate_hover',
            [
                'label' => esc_html__( 'Hue Rotate', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'deg' ],
                'range' => [
                    'deg' => [
                        'min' => 0,
                        'max' => 360,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'deg',
                    'size' => 0,
                ],
                'condition' => [
                    'custom_css_filter' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'filter_brightness_hover',
            [
                'label' => esc_html__( 'Brightness', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'condition' => [
                    'custom_css_filter' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'filter_contrast_hover',
            [
                'label' => esc_html__( 'Contrast', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 200,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card:hover .icon img' => 'filter: invert({{filter_invert_hover.SIZE}}{{filter_invert_hover.UNIT}}) sepia({{filter_sepia_hover.SIZE}}{{filter_sepia_hover.UNIT}}) saturate({{filter_saturate_hover.SIZE}}{{filter_saturate_hover.UNIT}}) hue-rotate({{filter_hue_rotate_hover.SIZE}}{{filter_hue_rotate_hover.UNIT}}) brightness({{filter_brightness_hover.SIZE}}{{filter_brightness_hover.UNIT}}) contrast({{filter_contrast_hover.SIZE}}{{filter_contrast_hover.UNIT}});',
                ],
                'condition' => [
                    'custom_css_filter' => 'yes',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
        $this->start_controls_section(
            'before_image_style',
            [
                'label' => esc_html__('Before Image Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'before_image_switcher' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'before_image_transition',
            [
                'label' => esc_html__( 'Before Image Transition', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .icon:before' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );
        $this->add_control(
            'position_for_before_image',
            [
                'label' => esc_html__( 'Position', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'absolute',
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'absolute' => esc_html__( 'Absolute', 'element-camp' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .icon:before' => 'position: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'image_before_offset_orientation_h',
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
                    'position_for_before_image!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_before_offset_x',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .icon:before' => 'left: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'image_before_offset_orientation_h' => 'start',
                    'position_for_before_image!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_before_offset_x_end',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .icon:before' => 'right: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'image_before_offset_orientation_h' => 'end',
                    'position_for_before_image!' => '',
                ],
            ]
        );
        $this->add_control(
            'image_before_offset_orientation_v',
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
                    'position_for_before_image!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_before_offset_y',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .icon:before' => 'top: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'image_before_offset_orientation_v' => 'start',
                    'position_for_before_image!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_before_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .icon:before' => 'bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'image_before_offset_orientation_v' => 'end',
                    'position_for_before_image!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'before_image_width',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .icon:before' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'before_image_height',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .icon:before' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs(
            'image_before_tabs',
        );
        $this->start_controls_tab(
            'image_before_normal',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'before_image_background_color',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card .icon:before',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'image_before_hover',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'hover_before_image_background_color',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card:hover .icon:before',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        $this->start_controls_section(
            'button_style',
            [
                'label' => esc_html__('Button Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'button_transition',
            [
                'label' => esc_html__( 'Button Transition', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .butn' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );
        $this->add_control(
            'butn_index',
            [
                'label' => esc_html__( 'Button z-index', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .butn' => 'z-index: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'button_position_options',
            [
                'label' => esc_html__( 'Button Position Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'before'
            ]
        );
        $this->add_responsive_control(
            'position_for_button',
            [
                'label' => esc_html__( 'Position', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'unset' => esc_html__( 'unset', 'element-camp' ),
                    'absolute' => esc_html__( 'Absolute', 'element-camp' ),
                    'relative' => esc_html__( 'Relative', 'element-camp' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .butn' => 'position: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'button_offset_orientation_h',
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
                    'position_for_button!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_offset_x',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .butn' => 'left: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'button_offset_orientation_h' => 'start',
                    'position_for_button!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_offset_x_end',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .butn' => 'right: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'button_offset_orientation_h' => 'end',
                    'position_for_button!' => '',
                ],
            ]
        );
        $this->add_control(
            'button_offset_orientation_v',
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
                    'position_for_button!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_offset_y',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .butn' => 'top: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'button_offset_orientation_v' => 'start',
                    'position_for_button!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .butn' => 'bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'button_offset_orientation_v' => 'end',
                    'position_for_button!' => '',
                ],
            ]
        );
        $this->add_control(
            'button_translate_y',
            [
                'label' => esc_html__( 'Translate Y', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
            ]
        );
        $this->add_control(
            'button_translate_x',
            [
                'label' => esc_html__( 'Translate X', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .butn' => 'transform: translate({{button_translate_x.SIZE}}{{button_translate_x.UNIT}},{{button_translate_y.SIZE}}{{button_translate_y.UNIT}})',
                ],
            ]
        );
        $this->add_control(
            'button_style_options',
            [
                'label' => esc_html__( 'Button Style Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'before'
            ]
        );
        $this->add_responsive_control(
            'button_width',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .butn' => 'width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_height',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .butn' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'butn_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_ACCENT,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card .butn',
            ]
        );
        $this->start_controls_tabs('tabs_button_style');

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => esc_html__('Normal', 'element-camp'),
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
                    'inline-flex' => esc_html__('Inline Flex', 'element-camp'),
                    'none' => esc_html__('None', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .butn' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'justify_content',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .butn' => 'justify-content: {{VALUE}};',
                ],
                'condition'=>['button_display'=>['flex','inline-flex']],
                'responsive' => true,
            ]);
        $this->add_responsive_control(
            'align_items',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .butn' => 'align-items: {{VALUE}};',
                ],
                'condition'=>['button_display'=>['flex','inline-flex']],
                'responsive' => true,
            ]);
        $this->add_control(
            'button_text_color',
            [
                'label' => esc_html__( 'Text Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .butn' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'butn_background',
                'label' => esc_html__('Background', 'element-camp'),
                'types' => ['classic', 'gradient'],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card .butn',
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

        $this->add_control(
            'button_opacity',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .butn' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card .butn',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card .butn',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .butn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'outline',
            [
                'label' => esc_html__( 'Outline', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__( 'None', 'element-camp' ),
                    'solid' => esc_html__( 'Solid', 'element-camp' ),
                    'dashed' => esc_html__( 'Dashed', 'element-camp' ),
                    'dotted' => esc_html__( 'Dotted', 'element-camp' ),
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
                'label' => esc_html__( 'Outline Width', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .butn' => 'outline: {{outline_width.SIZE}}{{outline_width.UNIT}} {{outline.VALUE}} {{outline_color.VALUE}}',
                ],
                'condition' => [
                    'outline!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'outline_offset',
            [
                'label' => esc_html__( 'Outline Offset', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 20,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .butn' => 'outline-offset: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'outline!' => 'none',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => esc_html__('Hover', 'element-camp'),
            ]
        );
        $this->add_control(
            'button_display_hover',
            [
                'label' => esc_html__('Button Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => esc_html__('Default', 'element-camp'),
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                    'inline-flex' => esc_html__('Inline Flex', 'element-camp'),
                    'none' => esc_html__('None', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card:hover .butn' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'button_justify_content_hover',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card:hover .butn' => 'justify-content: {{VALUE}};',
                ],
                'condition'=>['button_display_hover'=>['flex','inline-flex']],
                'responsive' => true,
            ]);
        $this->add_responsive_control(
            'button_align_items_hover',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card:hover .butn' => 'align-items: {{VALUE}};',
                ],
                'condition'=>['button_display_hover'=>['flex','inline-flex']],
                'responsive' => true,
            ]);
        $this->add_control(
            'hover_color',
            [
                'label' => esc_html__('Text Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card:hover .butn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'button_background_hover',
                'label' => esc_html__('Background', 'element-camp'),
                'types' => ['classic', 'gradient'],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card:hover .butn',
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
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card:hover .butn',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card:hover .butn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'hover_button_opacity',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card:hover .butn' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow_hover',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card:hover .butn',
            ]
        );

        $this->add_control(
            'outline_hover',
            [
                'label' => esc_html__( 'Outline', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__( 'None', 'element-camp' ),
                    'solid' => esc_html__( 'Solid', 'element-camp' ),
                    'dashed' => esc_html__( 'Dashed', 'element-camp' ),
                    'dotted' => esc_html__( 'Dotted', 'element-camp' ),
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
                'label' => esc_html__( 'Outline Width', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card:hover .butn' => 'outline: {{outline_width_hover.SIZE}}{{outline_width_hover.UNIT}} {{outline_hover.VALUE}} {{outline_color_hover.VALUE}}',
                ],
                'condition' => [
                    'outline_hover!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'outline_offset_hover',
            [
                'label' => esc_html__( 'Outline Offset', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 20,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card:hover .butn' => 'outline-offset: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'outline_hover!' => 'none',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'button_margin',
            [
                'label' => esc_html__('Button Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .butn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => esc_html__('Button Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .butn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );
        $this->add_control(
            'button_icon_style_heading',
            [
                'label' => esc_html__( 'Button Icon Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
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
                    'flex' => esc_html__('Flex', 'element-camp'),
                    'inline-flex' => esc_html__('Inline Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .butn .butn-icon' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'button_icon_justify_content',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .butn .butn-icon' => 'justify-content: {{VALUE}};',
                ],
                'condition'=>['button_icon_display'=>['flex','inline-flex']],
                'responsive' => true,
            ]);
        $this->add_responsive_control(
            'button_icon_align_items',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .butn .butn-icon' => 'align-items: {{VALUE}};',
                ],
                'condition'=>['button_icon_display'=>['flex','inline-flex']],
                'responsive' => true,
            ]);
        $this->add_responsive_control(
            'button_icon_size',
            [
                'label' => esc_html__( 'Icon size', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .butn .butn-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-services-slider .card .butn .butn-icon svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_icon_wrapper_width',
            [
                'label' => esc_html__( 'Icon Wrapper Width', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px','custom' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .butn .butn-icon' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_icon_wrapper_height',
            [
                'label' => esc_html__( 'Icon Wrapper Height', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px','custom' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .butn .butn-icon' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_icon_margin',
            [
                'label' => esc_html__('Button Icon Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .butn .butn-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_icon_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .butn .butn-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
            'button_icon_color',
            [
                'label' => __('Icon Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .butn .butn-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-services-slider .card .butn .butn-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'button_icon_background',
                'label' => esc_html__('Button Icon Background', 'element-camp'),
                'types' => [ 'classic','gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card .butn .butn-icon',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'button_icon_border',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card .butn .butn-icon',
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
            'hover_button_icon_color',
            [
                'label' => __('Icon Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card:hover .butn .butn-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-services-slider .card:hover .butn .butn-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'button_icon_background_hover',
                'label' => esc_html__('Button Icon Background', 'element-camp'),
                'types' => [ 'classic','gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card:hover .butn .butn-icon',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'button_icon_border_hover',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card:hover .butn .butn-icon',
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
            'position_for_before_button',
            [
                'label' => esc_html__( 'Position', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'absolute',
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'absolute' => esc_html__( 'Absolute', 'element-camp' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-slider .card .butn:before' => 'position: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'button_before_offset_orientation_h',
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
                    'position_for_before_button!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_before_offset_x',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .butn:before' => 'left: {{SIZE}}{{UNIT}}',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .butn:before' => 'right: {{SIZE}}{{UNIT}}',
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
                    'position_for_before_button!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_before_offset_y',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .butn:before' => 'top: {{SIZE}}{{UNIT}}',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .butn:before' => 'bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'button_before_offset_orientation_v' => 'end',
                    'position_for_before_button!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'before_width',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .butn:before' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'before_height',
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
                    '{{WRAPPER}} .tcgelements-services-slider .card .butn:before' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'before_background_color',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card .butn:before',
            ]
        );
        $this->add_control(
            'before_hover_background_heading',
            [
                'label' => esc_html__( 'Hover Before Background Color', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'before'
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'hover_before_background_color',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .card:hover .butn:before',
            ]
        );
        $this->end_controls_section();







//        end
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
                    '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows' => 'position: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows' => 'height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows' => 'color: {{VALUE}}; fill: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'arrows_arrow_typography',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows .tcgelements-services-slider-arrow-text,{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'arrows_background',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows',
                'exclude' => ['image']
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'arrows_border',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows',
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
                    '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'arrows_box_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows',
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
                    '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows:hover' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'arrows_arrow_typography_hover',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows:hover .tcgelements-services-slider-arrow-text',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'arrows_background_hover',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows:hover',
                'exclude' => ['image']
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'arrows_border_hover',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows:hover',
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
                    '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'arrows_box_shadow_hover',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows:hover',
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
                    '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
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
                    '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
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
                    '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows' => 'z-index: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-next' => 'position: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-next' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-next' => 'height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-next' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'next_arrow_typography',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-next .tcgelements-services-slider-arrow-text',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'next_arrow_background',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-next',
                'exclude' => ['image']
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'next_arrow_border',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-next',
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
                    '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'next_arrow_box_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-next',
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
                    '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-next:hover' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'next_arrow_typography_hover',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-next:hover .tcgelements-services-slider-arrow-text',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'next_arrow_background_hover',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-next:hover',
                'exclude' => ['image']
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'next_arrow_border_hover',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-next:hover',
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
                    '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-next:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'next_arrow_box_shadow_hover',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-next:hover',
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
                    '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-next' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-next' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-next' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-next' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-next' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-next' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
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
                    '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-next' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
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
                    '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-next' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
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
                    '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-next' => 'z-index: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-prev' => 'position: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-prev' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-prev' => 'height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-prev' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'prev_arrow_typography',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-prev .tcgelements-services-slider-arrow-text',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'prev_arrow_background',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-prev',
                'exclude' => ['image']
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'prev_arrow_border',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-prev',
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
                    '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'prev_arrow_box_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-prev',
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
                    '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-prev:hover' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'prev_arrow_typography_hover',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-prev:hover .tcgelements-services-slider-arrow-text',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'prev_arrow_background_hover',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-prev:hover',
                'exclude' => ['image']
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'prev_arrow_border_hover',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-prev:hover',
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
                    '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-prev:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'prev_arrow_box_shadow_hover',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-prev:hover',
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
                    '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-prev' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-prev' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-prev' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-prev' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-prev' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-prev' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
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
                    '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-prev' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
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
                    '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-prev' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
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
                    '{{WRAPPER}} .tcgelements-services-slider .tcgelements-services-slider-arrows.swiper-button-prev' => 'z-index: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-progressbar.swiper-pagination-horizontal' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-progressbar.swiper-pagination-vertical' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-progressbar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; width: calc(100% - ({{RIGHT}}{{UNIT}} + {{LEFT}}{{UNIT}}));',
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-progressbar.swiper-progressbar-vertical' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; height: calc(100% - (({{TOP}}{{UNIT}} + {{BOTTOM}}{{UNIT}}) * 3));',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'pagination_progress_bar_progress',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-progressbar .swiper-pagination-progressbar-fill',
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
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-progressbar',
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
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-progressbar',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-progressbar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-progressbar .swiper-pagination-progressbar-fill' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-bullets' => 'position: {{VALUE}};',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-services-slider .swiper-pagination-bullets.swiper-pagination-horizontal' => 'left: {{SIZE}}{{UNIT}}; right: unset; transform: translateX(50%);',
                    'body.rtl {{WRAPPER}} .tcgelements-services-slider .swiper-pagination-bullets.swiper-pagination-horizontal' => 'right: {{SIZE}}{{UNIT}}; left: unset; transform: translateX(-50%);',
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-services-slider .swiper-pagination-bullets.swiper-pagination-vertical' => 'left: {{SIZE}}{{UNIT}}; right: unset; transform: translateY(50%);',
                    'body.rtl {{WRAPPER}} .tcgelements-services-slider .swiper-pagination-bullets.swiper-pagination-vertical' => 'right: {{SIZE}}{{UNIT}}; left: unset; transform: translateY(50%);',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-services-slider .swiper-pagination-bullets.swiper-pagination-horizontal' => 'right: {{SIZE}}{{UNIT}}; left: unset; transform: translateX(-50%);',
                    'body.rtl {{WRAPPER}} .tcgelements-services-slider .swiper-pagination-bullets.swiper-pagination-horizontal' => 'left: {{SIZE}}{{UNIT}}; right: unset; transform: translateX(50%);',
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-services-slider .swiper-pagination-bullets.swiper-pagination-vertical' => 'right: {{SIZE}}{{UNIT}}; left: unset; transform: translateY(50%);',
                    'body.rtl {{WRAPPER}} .tcgelements-services-slider .swiper-pagination-bullets.swiper-pagination-vertical' => 'left: {{SIZE}}{{UNIT}}; right: unset; transform: translateY(50%);',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-bullets' => 'top: {{SIZE}}{{UNIT}}; bottom: unset; transform: translateY(50%) translateX(50%);',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-bullets' => 'bottom: {{SIZE}}{{UNIT}}; top: unset; transform: translateY(-50%) translateX(50%);',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-bullets' => 'z-index: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-bullets .swiper-pagination-bullet' => 'margin: 0 {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-bullets .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-bullets .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'pagination_bullet_background',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-bullets .swiper-pagination-bullet',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-bullets .swiper-pagination-bullet' => 'opacity: {{SIZE}};',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-bullets .swiper-pagination-bullet' => 'outline-style: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-bullets .swiper-pagination-bullet' => 'outline-width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-bullets .swiper-pagination-bullet' => 'outline-color: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-bullets .swiper-pagination-bullet' => 'outline-offset: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_popover();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'pagination_bullet_border',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-bullets .swiper-pagination-bullet',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-bullets .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'pagination_active_bullet_background',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'opacity: {{SIZE}};',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'outline-style: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'outline-width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'outline-color: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'outline-offset: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_popover();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'pagination_active_bullet_border',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-bullets' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-bullets' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-services-slider .swiper-pagination' => 'left: {{SIZE}}{{UNIT}}; transform: translateX(-50%);',
                    'body.rtl {{WRAPPER}} .tcgelements-services-slider .swiper-pagination' => 'right: {{SIZE}}{{UNIT}}; transform: translateX(50%);',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-services-slider .swiper-pagination' => 'right: {{SIZE}}{{UNIT}}; transform: translateX(50%);',
                    'body.rtl {{WRAPPER}} .tcgelements-services-slider .swiper-pagination' => 'left: {{SIZE}}{{UNIT}}; transform: translateX(-50%);',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination' => 'top: {{SIZE}}{{UNIT}}; bottom: unset; transform: translateY(50%);',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination' => 'bottom: {{SIZE}}{{UNIT}}; top: unset; transform: translateY(-50%);',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination' => 'z-index: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-fraction' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'pagination_fraction_typography',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-fraction',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'pagination_fraction_text_stroke',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-fraction',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'pagination_fraction_text_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-fraction',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-fraction' => 'mix-blend-mode: {{VALUE}}',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-fraction .swiper-pagination-current' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'pagination_fraction_current_typography',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-fraction .swiper-pagination-current',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'pagination_fraction_current_text_stroke',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-fraction .swiper-pagination-current',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'pagination_fraction_current_text_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-fraction .swiper-pagination-current',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-fraction .swiper-pagination-current' => 'mix-blend-mode: {{VALUE}}',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-fraction .swiper-pagination-current' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-fraction .swiper-pagination-total' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'pagination_fraction_total_typography',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-fraction .swiper-pagination-total',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'pagination_fraction_total_text_stroke',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-fraction .swiper-pagination-total',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'pagination_fraction_total_text_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-fraction .swiper-pagination-total',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-fraction .swiper-pagination-total' => 'mix-blend-mode: {{VALUE}}',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-pagination-fraction .swiper-pagination-total' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-services-slider .swiper-scrollbar.scrollbar-vertical, body:not(.rtl) {{WRAPPER}} .tcgelements-services-slider .swiper-scrollbar.scrollbar-horizontal' => 'left: {{SIZE}}{{UNIT}}; transform: translateX(-50%);',
                    'body.rtl {{WRAPPER}} .tcgelements-services-slider .swiper-scrollbar.scrollbar-vertical, {{WRAPPER}} body.rtl .tcgelements-services-slider .swiper-scrollbar.scrollbar-horizontal' => 'right: {{SIZE}}{{UNIT}}; transform: translateX(50%);',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-services-slider .swiper-scrollbar.scrollbar-vertical, body:not(.rtl) {{WRAPPER}} .tcgelements-services-slider .swiper-scrollbar.scrollbar-horizontal' => 'right: {{SIZE}}{{UNIT}}; transform: translateX(50%);',
                    'body.rtl {{WRAPPER}} .tcgelements-services-slider .swiper-scrollbar.scrollbar-vertical, {{WRAPPER}} body.rtl .tcgelements-services-slider .swiper-scrollbar.scrollbar-horizontal' => 'left: {{SIZE}}{{UNIT}}; transform: translateX(-50%);',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-scrollbar.scrollbar-vertical, {{WRAPPER}} .tcgelements-services-slider .swiper-scrollbar.scrollbar-horizontal' => 'top: {{SIZE}}{{UNIT}}; bottom: unset; transform: translateY(50%);',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-scrollbar.scrollbar-vertical, {{WRAPPER}} .tcgelements-services-slider .swiper-scrollbar.scrollbar-horizontal' => 'bottom: {{SIZE}}{{UNIT}}; top: unset; transform: translateY(-50%);',
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
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .swiper-scrollbar .swiper-scrollbar-drag',
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
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .swiper-scrollbar',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-scrollbar.scrollbar-horizontal, {{WRAPPER}} .tcgelements-services-slider .swiper-scrollbar.scrollbar-horizontal .swiper-scrollbar-drag' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-scrollbar.scrollbar-vertical, {{WRAPPER}} .tcgelements-services-slider .swiper-scrollbar.scrollbar-vertical .swiper-scrollbar-drag' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-scrollbar.swiper-scrollbar-horizontal' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; width: calc(100% - ({{RIGHT}}{{UNIT}} + {{LEFT}}{{UNIT}}));',
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-scrollbar.swiper-scrollbar-vertical' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; height: calc(100% - (({{TOP}}{{UNIT}} + {{BOTTOM}}{{UNIT}}) * 3));',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'scroll_bar_border',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .swiper-scrollbar',
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
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-scrollbar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-services-slider .swiper-scrollbar .swiper-scrollbar-drag' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .arrows-pagination-container',
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
                    '{{WRAPPER}} .tcgelements-services-slider .arrows-pagination-container' => 'position: {{VALUE}};',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-services-slider .arrows-pagination-container' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-services-slider .arrows-pagination-container' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-services-slider .arrows-pagination-container' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-services-slider .arrows-pagination-container' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
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
                    '{{WRAPPER}} .tcgelements-services-slider .arrows-pagination-container' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
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
                    '{{WRAPPER}} .tcgelements-services-slider .arrows-pagination-container' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
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
                    '{{WRAPPER}} .tcgelements-services-slider .arrows-pagination-container' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-services-slider .arrows-pagination-container' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'arrows_pagination_container_border',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .arrows-pagination-container',
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
                    '{{WRAPPER}} .tcgelements-services-slider .arrows-pagination-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'arrows_pagination_container_box_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-services-slider .arrows-pagination-container',
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
                    '{{WRAPPER}} .tcgelements-services-slider .arrows-pagination-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-services-slider .arrows-pagination-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

        $max_slider_id = uniqid('tcgelements-services-slider-');

        ?>

        <div id="<?php echo esc_attr($max_slider_id) ?>" class="tcgelements-services-slider" data-tcgelements-services-slider='<?php echo esc_attr(json_encode($slider_settings)); ?>' <?php if($settings['effect']=='gl'):?> data-shader-option="<?=$settings['shader_picker']?>" <?php endif;?>>
            <div class="swiper-container <?php if ($settings['effect'] == 'parallax') echo esc_attr('tcg-dynamic-parallax-slider'); ?>">
                <div class="swiper-wrapper">
                    <?php foreach ($settings['tcg_dynamic_slides_repeater'] as $index => $slide) : ?>
                        <?php
                        $element_class = 'card';
                        $link_url = $slide['link']['url'];
                        $is_external = $slide['link']['is_external'] ? 'target="_blank"' : '';
                        $tag = !empty($link_url) ? 'a' : 'div';
                        $href_attr = !empty($link_url) ? 'href="' . esc_url($link_url) . '"' : '';
                        ?>
                        <div class="swiper-slide">
                            <<?= $tag ?> class="<?= esc_attr($element_class) ?>" <?= $href_attr ?> <?= $is_external ?>>
                            <?php if (!empty($slide['service_image']['url'])) : ?>
                                <div class="icon">
                                    <img <?php if ($settings['effect']=='gl') :?> class="swiper-gl-image" <?php endif;?> src="<?= esc_url($slide['service_image']['url']); ?>" alt="<?php if (!empty($slide['service_image']['alt'])) echo esc_attr($slide['service_image']['alt']); ?>" >
                                </div>
                            <?php endif;?>
                            <div class="inf">
                                <h5 class="title"><?= __($slide['title'],'element-camp'); ?></h5>
                                <?php if ($slide['description_switcher']=='yes') : ?>
                                    <p class="description"><?=$slide['description']?></p>
                                <?php endif;?>
                            </div>
                            <?php if ($slide['button_switcher']=='yes') : ?>
                                <div class="butn">
                                    <?php if (!empty($slide['selected_icon']['value']) and ($slide['icon_align'] == 'left')) : ?>
                                        <span class="butn-icon">
                                            <?php Icons_Manager::render_icon($slide['selected_icon'], ['aria-hidden' => 'true']);?>
                                        </span>
                                    <?php endif; ?>
                                    <span class="text">
                                        <?= $slide['btn_text']; ?>
                                    </span>
                                    <?php if (!empty($slide['selected_icon']['value'])  and ($slide['icon_align'] == 'right')) : ?>
                                        <span class="butn-icon">
                                            <?php Icons_Manager::render_icon($slide['selected_icon'], ['aria-hidden' => 'true']);?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php endif;?>
                        </<?= $tag ?>>
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
                <div class="swiper-button-prev tcgelements-services-slider-arrows">
                    <?php if ($settings['arrows_style'] === 'icon') :
                        Icons_Manager::render_icon($settings['prev_arrow_icon'], ['aria-hidden' => 'true']);
                    elseif ($settings['arrows_style'] === 'text') :
                        echo wp_kses_post($settings['prev_arrow_text']);
                    elseif ($settings['arrows_style'] === 'text-icon') :
                        Icons_Manager::render_icon($settings['prev_arrow_icon'], ['aria-hidden' => 'true']);
                        echo '<span class="tcgelements-services-slider-arrow-text">' . wp_kses_post($settings['prev_arrow_text']) . '</span>';
                    endif; ?>
                </div>
                <div class="swiper-button-next tcgelements-services-slider-arrows">
                    <?php if ($settings['arrows_style'] === 'icon') :
                        Icons_Manager::render_icon($settings['next_arrow_icon'], ['aria-hidden' => 'true']);
                    elseif ($settings['arrows_style'] === 'text') :
                        echo wp_kses_post($settings['next_arrow_text']);
                    elseif ($settings['arrows_style'] === 'text-icon') :
                        echo '<span class="tcgelements-services-slider-arrow-text">' . wp_kses_post($settings['next_arrow_text']) . '</span>';
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
