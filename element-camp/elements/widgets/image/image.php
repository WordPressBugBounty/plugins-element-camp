<?php
namespace ElementCampPlugin\Widgets;

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
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
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Scheme_Base;
use Elementor\POPOVER_TOGGLE;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Elementor image widget.
 *
 * Elementor widget that displays an image into the page.
 *
 * @since 1.0.0
 */
class ElementCamp_Image extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve image widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'tcgelements-image';
    }
    /**
     * Get widget title.
     *
     * Retrieve image widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__( 'Image', 'element-camp' );
    }
    /**
     * Get widget icon.
     *
     * Retrieve image widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-image tce-widget-badge';
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
    public function get_keywords() {
        return [ 'image', 'photo', 'visual' ];
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
        return ['tcgelements-image','tcgelements-image-parallax','tcgelements-float-cursor-animation','tcgelements-image-scroll-trigger'];
    }

    /**
     * Register image widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 3.1.0
     * @access protected
     */
    protected function _register_controls()
    {
        $this->start_controls_section(
            'section_image',
            [
                'label' => esc_html__('Image', 'element-camp'),
            ]
        );

        $this->add_control(
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

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
                'default' => 'large',
                'separator' => 'none',
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label' => esc_html__('Alignment', 'element-camp'),
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
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'caption_source',
            [
                'label' => esc_html__('Caption', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'element-camp'),
                    'attachment' => esc_html__('Attachment Caption', 'element-camp'),
                    'custom' => esc_html__('Custom Caption', 'element-camp'),
                ],
                'default' => 'none',
            ]
        );

        $this->add_control(
            'caption',
            [
                'label' => esc_html__('Custom Caption', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => esc_html__('Enter your image caption', 'element-camp'),
                'condition' => [
                    'caption_source' => 'custom',
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'link_to',
            [
                'label' => esc_html__('Link', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__('None', 'element-camp'),
                    'file' => esc_html__('Media File', 'element-camp'),
                    'custom' => esc_html__('Custom URL', 'element-camp'),
                    'pop-up' => esc_html__('Video Pop-Up', 'element-camp'),
                    'video-popup' => esc_html__('Video Pop-Up With Icon', 'element-camp'),
                    'site-url' => esc_html__('Site URL', 'element-camp'),
                    'post-url' => esc_html__( 'Post URL', 'element-camp' ),
                ],
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => esc_html__('Link', 'element-camp'),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__('https://your-link.com', 'element-camp'),
                'condition' => [
                    'link_to' => ['custom', 'video-popup','pop-up'],
                ],
                'show_label' => false,
            ]
        );

        $this->add_control(
            'link_text',
            [
                'label' => __('Link Text', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'link_to' => 'custom',
                ],
            ]
        );

        $this->add_control(
            'open_lightbox',
            [
                'label' => esc_html__('Lightbox', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => esc_html__('Default', 'element-camp'),
                    'yes' => esc_html__('Yes', 'element-camp'),
                    'no' => esc_html__('No', 'element-camp'),
                ],
                'condition' => [
                    'link_to' => 'file',
                ]
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
            'pointer-events',
            [
                'label' => esc_html__('Pointer Events', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'auto' => esc_html__('Auto', 'element-camp'),
                    'none' => esc_html__('None', 'element-camp'),
                ],
                'default' => 'auto',
                'selectors' => [
//                    '{{WRAPPER}} .tcgelements-image img' => 'pointer-events:{{VALUE}};',
                    '{{WRAPPER}}' => 'pointer-events:{{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_switcher',
            [
                'label' => esc_html__( 'Button Switcher', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'yes', 'element-camp' ),
                'label_off' => esc_html__( 'No', 'element-camp' ),
            ]
        );
        $this->add_control(
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
        $this->add_control(
            'btn_icon',
            [
                'label' => esc_html__('Icon', 'element-camp'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'skin' => 'inline',
                'label_block' => false,
                'condition' => ['button_switcher'=>'yes'],
            ]
        );
        $this->add_control(
            'icon_align',
            [
                'label' => esc_html__('Icon Position', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'right',
                'options' => [
                    'left' => esc_html__('Before', 'element-camp'),
                    'right' => esc_html__('After', 'element-camp'),
                ],
                'condition' => [
                    'btn_icon[value]!' => '',
                ],
            ]
        );
        $this->add_control(
            'shapes',
            [
                'label' => esc_html__( 'Button Shapes', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'yes', 'element-camp' ),
                'label_off' => esc_html__( 'No', 'element-camp' ),
            ]
        );
        $this->add_control(
            'shape_icon',
            [
                'label' => esc_html__('Shape', 'element-camp'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'skin' => 'inline',
                'label_block' => false,
                'condition' => [
                        'button_switcher'=>'yes',
                        'shapes'=>'yes'
                ],
            ]
        );
        $this->end_controls_section();
        // container style tab
        $this->start_controls_section(
            'image_container_style',
            [
                'label' => esc_html__('Container', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'wrapper_margin',
            [
                'label' => esc_html__('Widget Wrapper Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}}' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'img_container_overflow_hidden',
            [
                'label' => esc_html__( 'Container Overflow Hidden', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'yes', 'element-camp' ),
                'label_off' => esc_html__( 'No', 'element-camp' ),
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image' => 'overflow:hidden;',
                ],
            ]
        );
        $this->add_responsive_control(
            'container_height',
            [
                'label' => esc_html__('Container Height', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-image' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'container_width',
            [
                'label' => esc_html__('Container width', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-image' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'container_padding',
            [
                'label' => esc_html__('Container Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );
        $this->add_responsive_control(
            'container_border_radius',
            [
                'label' => esc_html__('Container Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'image_container_background_color',
                'types' => [ 'classic','gradient','tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-image',
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_style_image',
            [
                'label' => esc_html__('Image', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'image_heading',
            [
                'label' => esc_html__('Image Options', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->add_responsive_control(
            'width',
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
                    '{{WRAPPER}} img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'max_width',
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
                    '{{WRAPPER}} img' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'max_height',
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
                    '{{WRAPPER}} img' => 'max-height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}' => 'max-height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-widget-container' => 'max-height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-image' => 'max-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'min_height',
            [
                'label' => esc_html__('Min Height', 'element-camp'),
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
                    '{{WRAPPER}} img' => 'min-height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}' => 'min-height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-widget-container' => 'min-height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-image' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'height',
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
                    '{{WRAPPER}} img' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-widget-container' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-image' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'object-fit',
            [
                'label' => esc_html__('Object Fit', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'fill' => esc_html__('Fill', 'element-camp'),
                    'cover' => esc_html__('Cover', 'element-camp'),
                    'contain' => esc_html__('Contain', 'element-camp'),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'object-position',
            [
                'label' => esc_html__('Object Position', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
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
                'default' => 'center center',
                'selectors' => [
                    '{{WRAPPER}} img' => 'object-position: {{VALUE}};',
                ],
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
            'filter_invert_dark_mode',
            [
                'label' => esc_html__( 'Image Invert (Dark Mode)', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-image img' => 'filter: invert({{SIZE}});',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-image img' => 'filter: invert({{SIZE}});',
                ],
            ]
        );
        $this->add_control(
            'separator_panel_style',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->start_controls_tabs('image_effects');

        $this->start_controls_tab('normal',
            [
                'label' => esc_html__('Normal', 'element-camp'),
            ]
        );

        $this->add_responsive_control(
            'opacity',
            [
                'label' => esc_html__('Opacity', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image img' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'image_gray_scale',
            [
                'label' => esc_html__( 'Image Gray Scale', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image img' => 'filter: grayscale({{SIZE}});',
                ],
            ]
        );
        $this->add_control(
            'image_filter_invert',
            [
                'label' => esc_html__( 'Image Filter Invert', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image img' => 'filter: invert({{SIZE}});',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'image_background_color',
                'types' => [ 'classic','gradient','tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-image img',
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'css_filters',
                'selector' => '{{WRAPPER}} img',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} img',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('hover',
            [
                'label' => esc_html__('Hover', 'element-camp'),
            ]
        );

        $this->add_control(
            'opacity_hover',
            [
                'label' => esc_html__('Opacity', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image img:hover' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_control(
            'image_gray_scale_hover',
            [
                'label' => esc_html__( 'Image Gray Scale', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image img:hover' => 'filter: grayscale({{SIZE}});',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'css_filters_hover',
                'selector' => '{{WRAPPER}}:hover img',
            ]
        );

        $this->add_control(
            'background_hover_transition',
            [
                'label' => esc_html__('Transition Duration', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} img' => 'transition-duration: {{SIZE}}s',
                    '{{WRAPPER}} .tcgelements-image' => 'transition-duration: {{SIZE}}s',
                ],
            ]
        );

        $this->add_control(
            'hover_animation',
            [
                'label' => esc_html__('Hover Animation', 'element-camp'),
                'type' => Controls_Manager::HOVER_ANIMATION,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border_hover',
                'selector' => '{{WRAPPER}}:hover img',
            ]
        );


        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'image_padding',
            [
                'label' => __('Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} img' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} img',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_caption',
            [
                'label' => esc_html__('Caption', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'caption_source!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'caption_align',
            [
                'label' => esc_html__('Alignment', 'element-camp'),
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
                    'justify' => [
                        'title' => esc_html__('Justified', 'element-camp'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .widget-image-caption' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => esc_html__('Text Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .widget-image-caption' => 'color: {{VALUE}};',
                ],
                'global' => [
                    'default' => Global_Colors::COLOR_TEXT,
                ],
            ]
        );

        $this->add_control(
            'caption_background_color',
            [
                'label' => esc_html__('Background Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .widget-image-caption' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'caption_typography',
                'selector' => '{{WRAPPER}} .widget-image-caption',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'caption_text_shadow',
                'selector' => '{{WRAPPER}} .widget-image-caption',
            ]
        );

        $this->add_responsive_control(
            'caption_space',
            [
                'label' => esc_html__('Spacing', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .widget-image-caption' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

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
                    '{{WRAPPER}} img' => 'filter: drop-shadow({{drop_shadow_offset_x.SIZE}}px {{drop_shadow_offset_y.SIZE}}px {{drop_shadow_blur_radius.SIZE}}px {{VALUE}});',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'image_hover_section',
            [
                'label' => __('Image Hover', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'image_hover_overlay',
            [
                'label' => esc_html__('Hover Overlay', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'element-camp'),
                'label_off' => esc_html__('Hide', 'element-camp'),
                'return_value' => 'yes',
                'default' => 'no',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'image_hover_overlay_selector',
            [
                'label' => esc_html__( 'Choose Selector', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'image',
                'options' => [
                    'image' => esc_html__( 'Image', 'element-camp' ),
                    'container'  => esc_html__( 'Container', 'element-camp' ),
                    'parent-container'  => esc_html__( 'Parent  Container', 'element-camp' ),
                ],
            ]
        );

        $this->add_control(
            "image_hover_transform_translate_popover",
            [
                'label' => esc_html__( 'Offset', 'element-camp' ),
                'type' => Controls_Manager::POPOVER_TOGGLE,
            ]
        );

        $this->start_popover();

        $this->add_responsive_control(
            "image_hover_transform_translateX_effect",
            [
                'label' => esc_html__( 'Offset X', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'range' => [
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                ],
                'selectors' => [
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.selector-type-container.tcgelements-image-container-active' => 'transform: translateX({{SIZE}}{{UNIT}});',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.tcgelements-image-container-active' => 'transform: translateX({{SIZE}}{{UNIT}});',
                    '{{WRAPPER}} .tcgelements-image.selector-type-image:hover' => 'transform: translateX({{SIZE}}{{UNIT}});',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_responsive_control(
            "image_hover_transform_translateY_effect",
            [
                'label' => esc_html__( 'Offset Y', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
                'range' => [
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                ],
                'selectors' => [
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.selector-type-container.tcgelements-image-container-active' => 'transform: translateY({{SIZE}}{{UNIT}});',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.tcgelements-image-container-active' => 'transform: translateY({{SIZE}}{{UNIT}});',
                    '{{WRAPPER}} .tcgelements-image.selector-type-image:hover' => 'transform: translateY({{SIZE}}{{UNIT}});',
                ],
                'frontend_available' => true,
            ]
        );

        $this->end_popover();

        $this->add_control(
            'image_hover_overlay_index',
            [
                'label' => esc_html__( 'Overlay z-index', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image::after' => 'z-index: {{SIZE}};',
                ],
                'condition' => [
                    'image_hover_overlay' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'image_hover_overlay_backdrop_filter',
            [
                'label' => esc_html__( 'Backdrop Filter', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image::after' => 'backdrop-filter: blur({{SIZE}}px);',
                ],
                'condition' => [
                    'image_hover_overlay' => 'yes'
                ]
            ]
        );

        $this->add_responsive_control(
            'image_hover_overlay_width',
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
                    '{{WRAPPER}} .tcgelements-image::after' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'image_hover_overlay' => 'yes'
                ]
            ]
        );

        $this->add_responsive_control(
            'image_hover_overlay_height',
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
                    '{{WRAPPER}} .tcgelements-image::after' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'image_hover_overlay' => 'yes'
                ]
            ]
        );

        $this->add_responsive_control(
            'image_hover_overlay_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'image_hover_overlay' => 'yes'
                ]
            ]
        );
        $this->add_control(
            'image_hover_overlay_transition',
            [
                'label' => esc_html__( 'Transition', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'default' => [
                    'size' => 0.3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image::after' => 'transition: all {{SIZE}}s ease;',
                    '{{WRAPPER}} .tcgelements-image' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );

        $this->start_controls_tabs(
            'image_hover_overlay_tabs',
        );

        $this->start_controls_tab(
            'image_hover_overlay_normal_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
                'condition' => [
                    'image_hover_overlay' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'image_hover_overlay_offset_orientation_h',
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
                    'image_hover_overlay' => 'yes'
                ],
            ]
        );
        $this->add_responsive_control(
            'image_hover_overlay_offset_x',
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
                    '{{WRAPPER}} .tcgelements-image::after' => 'left: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'image_hover_overlay_offset_orientation_h' => 'start',
                    'image_hover_overlay' => 'yes'
                ],
            ]
        );
        $this->add_responsive_control(
            'image_hover_overlay_offset_x_end',
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
                    '{{WRAPPER}} .tcgelements-image::after' => 'right: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'image_hover_overlay_offset_orientation_h' => 'end',
                    'image_hover_overlay' => 'yes'
                ],
            ]
        );
        $this->add_control(
            'image_hover_overlay_offset_orientation_v',
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
                    'image_hover_overlay' => 'yes'
                ]
            ]
        );
        $this->add_responsive_control(
            'image_hover_overlay_offset_y',
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
                    '{{WRAPPER}} .tcgelements-image::after' => 'top: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'image_hover_overlay_offset_orientation_v' => 'start',
                    'image_hover_overlay' => 'yes'
                ],
            ]
        );
        $this->add_responsive_control(
            'image_hover_overlay_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-image::after' => 'bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'image_hover_overlay_offset_orientation_v' => 'end',
                    'image_hover_overlay' => 'yes'
                ],
            ]
        );

        $this->add_control(
            'image_hover_overlay_display',
            [
                'label' => esc_html__('Image Hover Overlay Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'none' => esc_html__('none', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image::after' => 'display: {{VALUE}};',
                ],
                'condition' => [
                    'image_hover_overlay' => 'yes'
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_hover_overlay_box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} .tcgelements-image::after',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'image_hover_overlay_bg',
                'selector' => '{{WRAPPER}} .tcgelements-image::after',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'condition' => [
                    'image_hover_overlay' => 'yes'
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'image_hover_overlay_border',
                'selector' => '{{WRAPPER}} .tcgelements-image::after',
                'condition' => [
                    'image_hover_overlay' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'image_hover_overlay_opacity',
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
                    '{{WRAPPER}} .tcgelements-image::after' => 'opacity: {{SIZE}};',
                ],
                'condition' => [
                    'image_hover_overlay' => 'yes'
                ]
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'image_hover_overlay_hover_tab',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
                'condition' => [
                    'image_hover_overlay' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'image_hover_overlay_display_hover',
            [
                'label' => esc_html__('Image Hover Overlay Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'block',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'none' => esc_html__('none', 'element-camp'),
                ],
                'selectors' => [
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.selector-type-container.tcgelements-image-container-active:after' => 'display: {{VALUE}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.tcgelements-image-container-active:after' => 'display: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-image.selector-type-image:hover:after' => 'display: {{VALUE}};',
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'image_hover_overlay_bg_hover',
                'selector' => '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.selector-type-container.tcgelements-image-container-active:after ,{{WRAPPER}} .tcgelements-image.selector-type-image:hover:after,.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.tcgelements-image-container-active:after',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'condition' => [
                    'image_hover_overlay' => 'yes'
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'image_hover_overlay_border_hover',
                'selector' => '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.selector-type-container.tcgelements-image-container-active:after , {{WRAPPER}} .tcgelements-image.selector-type-image:hover:after,.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.tcgelements-image-container-active:after',
            ]
        );

        $this->add_control(
            'image_hover_overlay_opacity_hover',
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
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.selector-type-container.tcgelements-image-container-active:after' => 'opacity: {{SIZE}};',
                    '{{WRAPPER}} .tcgelements-image.selector-type-image:hover:after' => 'opacity: {{SIZE}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.tcgelements-image-container-active:after' => 'opacity: {{SIZE}};',
                ],
                'condition' => [
                    'image_hover_overlay' => 'yes'
                ]
            ]
        );


        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control(
            'image_hover_overlay_container_divider',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'image_hover_overlay_content',
            [
                'label' => esc_html__( 'Select Content', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__( 'None', 'element-camp' ),
                    'social'  => esc_html__( 'Social Media', 'element-camp' ),
                    'media'  => esc_html__( 'Media', 'element-camp' ),
                    'float-cursor'  => esc_html__( 'Float Cursor', 'element-camp' ),
                ],
            ]
        );

        $this->add_control(
            'float_cursor_icon',
            [
                'label' => esc_html__('Icon', 'element-camp'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'skin' => 'inline',
                'label_block' => false,
                'condition' => [
                    'image_hover_overlay_content' => 'float-cursor',
                ],
            ]
        );

        $this->add_control(
            'float_cursor_text',
            [
                'label' => esc_html__( 'Text', 'element-camp' ),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'image_hover_overlay_content' => 'float-cursor',
                ],
            ]
        );

        $this->add_control(
            'media_type',
            [
                'label' => esc_html__('Media Type', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'icon' => [
                        'title' => esc_html__('Icon', 'element-camp'),
                        'icon' => 'eicon-favorite',
                    ],
                    'image' => [
                        'title' => esc_html__('Image', 'element-camp'),
                        'icon' => 'eicon-image',
                    ],
                ],
                'default' => 'icon',
                'condition' => ['image_hover_overlay_content'=>'media'],
            ]
        );

        $this->add_control(
            'media_type_icon',
            [
                'label' => esc_html__('Icon', 'element-camp'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'skin' => 'inline',
                'label_block' => false,
                'condition' => [
                    'image_hover_overlay_content'=>'media',
                    'media_type'=>'icon',
                ],
            ]
        );

        $this->add_control(
            'media_type_link',
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
                'condition' => [
                    'image_hover_overlay_content'=>'media',
                    'media_type'=>'icon',
                ],
            ]
        );

        $this->add_control(
            'media_type_text',
            [
                'label' => esc_html__( 'Text', 'element-camp' ),
                'type' => Controls_Manager::TEXT,
                'separator' => 'before',
                'condition' => [
                    'image_hover_overlay_content'=>'media',
                    'media_type'=>'icon',
                ],
            ]
        );

        $this->add_control(
            'media_type_image',
            [
                'label' => esc_html__( 'Choose Image', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'image_hover_overlay_content'=>'media',
                    'media_type'=>'image',
                ],
            ]
        );

        $social_icons = new \Elementor\Repeater();

        $social_icons->add_control(
            'icon',
            [
                'label' => esc_html__('Icon', 'element-camp'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'old_icon',
            ]
        );

        $social_icons->add_control(
            'link',
            [
                'label' => esc_html__('Social Link', 'element-camp'),
                'type' => Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-social-link.com', 'element-camp'),
                'label_block' => true,
            ]
        );

        $social_icons->add_control(
            'text',
            [
                'label' => esc_html__('Social Text', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__('Facebook', 'element-camp'),
                'label_block' => true,
            ]
        );
        $social_icons->add_responsive_control(
            'social_repeater_icon_size',
            [
                'label' => esc_html__( 'Social Icon size', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-image .image-hover-container {{CURRENT_ITEM}}.link i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-image .image-hover-container {{CURRENT_ITEM}}.link svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $social_icons->add_responsive_control(
            'social_link_repeater_margin',
            [
                'label' => esc_html__('Social Link Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .image-hover-container {{CURRENT_ITEM}}.link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'social_icons_list',
            [
                'label' => esc_html__('Social Icons List', 'element-camp'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $social_icons->get_controls(),
                'default' => [
                    [
                        'icon' => [
                            'value' => 'fab fa-facebook',
                            'library' => 'fa-brands',
                        ],
                    ],
                    [
                        'icon' => [
                            'value' => 'fab fa-x-twitter',
                            'library' => 'fa-brands',
                        ],
                    ],
                    [
                        'icon' => [
                            'value' => 'fab fa-instagram',
                            'library' => 'fa-brands',
                        ],
                    ],
                ],
                'condition' => ['image_hover_overlay_content'=>'social'],
                'title_field' => '{{{ elementor.helpers.renderIcon( this, icon, {}, "i", "panel" ) || \'<i class="{{ icon }}" aria-hidden="true"></i>\' }}}',
            ]
        );

        $this->add_responsive_control(
            'social_content_align',
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
                    '{{WRAPPER}} .tcgelements-image .image-hover-container' => 'text-align: {{VALUE}};',
                ],
                'condition' => ['image_hover_overlay_content'=>'social'],
            ]
        );

        $this->add_responsive_control(
            'icon_container_height',
            [
                'label' => esc_html__( 'Icon Container Height', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-image .image-hover-container .link' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-image .image-hover-container .tc-hover-media' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-image .image-hover-container .tc-hover-media img' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-image .image-hover-container .tcgelements-float-cursor .icon' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => ['image_hover_overlay_content!'=>'none'],
            ]
        );

        $this->add_responsive_control(
            'icon_container_width',
            [
                'label' => esc_html__( 'Icon Container Width', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-image .image-hover-container .link' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-image .image-hover-container .tc-hover-media' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-image .image-hover-container .tc-hover-media img' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-image .image-hover-container .tcgelements-float-cursor .icon' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => ['image_hover_overlay_content!'=>'none'],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'icon_container_border',
                'selector' => '{{WRAPPER}} .tcgelements-image .image-hover-container .link,{{WRAPPER}} .tcgelements-image .tc-hover-media,{{WRAPPER}} .tcgelements-image .image-hover-container .tcgelements-float-cursor .icon',
                'condition' => ['image_hover_overlay_content!'=>'none'],
            ]
        );

        $this->add_responsive_control(
            'icon_container_border_radius',
            [
                'label' => esc_html__( 'Icon Container Border Radius', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .image-hover-container .link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-image .tc-hover-media' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-image .image-hover-container .tcgelements-float-cursor .icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
                'condition' => ['image_hover_overlay_content!'=>'none'],
            ]
        );

        $this->add_responsive_control(
            'icon_container_padding',
            [
                'label' => esc_html__( 'Icon Container Padding', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .image-hover-container .link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => ['image_hover_overlay_content'=>'social'],
            ]
        );

        $this->add_responsive_control(
            'icon_container_margin',
            [
                'label' => esc_html__( 'Icon Container Margin', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'separator' => 'after',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .image-hover-container .link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => ['image_hover_overlay_content'=>'social'],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
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
                    '{{WRAPPER}} .tcgelements-image .image-hover-container i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-image .image-hover-container svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-image .tc-hover-media i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-image .tc-hover-media svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-image .image-hover-container .tcgelements-float-cursor .icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-image .image-hover-container .tcgelements-float-cursor .icon svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => ['image_hover_overlay_content!'=>'none'],
            ]
        );
        $this->add_control(
            'media_transition',
            [
                'label' => esc_html__( 'Media Transition', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'default' => [
                    'size' => 0.3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .tc-hover-media' => 'transition: all {{SIZE}}s ease;',
                ],
                'condition' => [
                    'image_hover_overlay_content'=>'media',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'media_icon_text_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-image .tc-hover-media .media-icon-text',
                'condition' => [
                    'image_hover_overlay_content'=>'media',
                    'media_type'=>'icon',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'float_cursor_text_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-image .tcgelements-float-cursor .text',
                'condition' => [
                    'image_hover_overlay_content'=>'float-cursor',
                ],
            ]
        );
        $this->add_control(
            'media_icon_text_color',
            [
                'label' => esc_html__( 'Text Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .tc-hover-media .media-icon-text' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'image_hover_overlay_content'=>'media',
                    'media_type'=>'icon',
                ],
            ]
        );
        $this->add_control(
            'float_cursor_text_color',
            [
                'label' => esc_html__( 'Text Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .tcgelements-float-cursor .text' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'image_hover_overlay_content'=>'float-cursor',
                ],
            ]
        );
        $this->add_responsive_control(
            'float_cursor_text_margin',
            [
                'label' => esc_html__('Text Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .tcgelements-float-cursor .text' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'image_hover_overlay_content'=>'float-cursor',
                ],
            ]
        );
        $this->add_responsive_control(
            'media_icon_rotate',
            [
                'label' => esc_html__( 'Media Icon Rotate', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'deg' => [
                        'min' => -360,
                        'max' => 360,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .tc-hover-media' => 'rotate: {{SIZE}}deg;',
                ],
                'condition' => [
                    'image_hover_overlay_content'=>'media',
                    'media_type'=>'icon',
                ],
            ]
        );
        $this->add_responsive_control(
            'media_icon_text_margin',
            [
                'label' => esc_html__('Media Icon Text Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .tc-hover-media .media-icon-text' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'image_hover_overlay_content'=>'media',
                    'media_type'=>'icon',
                ],
            ]
        );
        $this->add_responsive_control(
            'media_icon_container_padding',
            [
                'label' => esc_html__('Media Icon Container Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .tc-hover-media' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'image_hover_overlay_content'=>'media',
                    'media_type'=>'icon',
                ],
            ]
        );
        $this->add_responsive_control(
            'media_icon_container_margin',
            [
                'label' => esc_html__('Media Icon Container Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .tc-hover-media' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'image_hover_overlay_content'=>'media',
                    'media_type'=>'icon',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'social_icon_text_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-image .image-hover-container .social-text',
                'condition' => ['image_hover_overlay_content'=>'social'],
            ]
        );
        $this->add_responsive_control(
            'social_icon_text_margin',
            [
                'label' => esc_html__( 'Icon Text Margin', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'separator' => 'after',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .image-hover-container .social-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => ['image_hover_overlay_content'=>'social'],
            ]
        );
        $this->start_controls_tabs(
            'icons_tabs',
        );

        $this->start_controls_tab(
            'normal_icons_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
                'condition' => ['image_hover_overlay_content!'=>'none'],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__('Icon Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .image-hover-container i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-image .tc-hover-media i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-image .image-hover-container .tcgelements-float-cursor .icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-image .image-hover-container svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-image .tc-hover-media svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-image .image-hover-container .tcgelements-float-cursor .icon svg' => 'fill: {{VALUE}};',
                ],
                'condition' => ['image_hover_overlay_content!'=>'none'],
            ]
        );

        $this->add_control(
            'scale_media',
            [
                'label' => esc_html__( 'Scale Media', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .tc-hover-media' => 'transform: scale({{SIZE}});',
                ],
                'condition' => ['image_hover_overlay_content' => 'media'],
            ]
        );

        $this->add_control(
            'social_icon_text_color',
            [
                'label' => esc_html__('Icon Text Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .image-hover-container .social-text' => 'color: {{VALUE}};',
                ],
                'condition' => ['image_hover_overlay_content'=>'social'],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'icon_background_color',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-image .image-hover-container a ,{{WRAPPER}} .tcgelements-image .tc-hover-media,{{WRAPPER}} .tcgelements-image .image-hover-container .tcgelements-float-cursor .icon',
                'condition' => ['image_hover_overlay_content!'=>'none'],
            ]
        );

        $this->add_control(
            'icon_background_clip_text',
            [
                'label' => esc_html__('Background Clip Text', 'element-camp'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .image-hover-container a' => 'background-clip: text;-webkit-background-clip: text;',
                ],
                'condition' => ['image_hover_overlay_content'=>'social'],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'hover_icons_tab',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
                'condition' => ['image_hover_overlay_content!'=>'none'],
            ]
        );

        $this->add_control(
            'icon_color_hover',
            [
                'label' => esc_html__('Icon Color Hover', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .image-hover-container a:hover i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-image .tc-hover-media:hover i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-image .image-hover-container .tcgelements-float-cursor:hover .icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-image .image-hover-container a:hover svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-image .tc-hover-media:hover svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-image .image-hover-container .tcgelements-float-cursor:hover .icon svg' => 'fill: {{VALUE}};',
                ],
                'condition' => ['image_hover_overlay_content!'=>'none'],
            ]
        );

        $this->add_control(
            'scale_media_hover',
            [
                'label' => esc_html__( 'Scale Media', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.selector-type-container.tcgelements-image-container-active .tc-hover-media' => 'transform: scale({{SIZE}})',
                    '{{WRAPPER}} .tcgelements-image.selector-type-image:hover .image-hover-container .tc-hover-media' => 'transform: scale({{SIZE}})',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.tcgelements-image-container-active .tc-hover-media' => 'transform: scale({{SIZE}})',
                ],
                'condition' => ['image_hover_overlay_content' => 'media'],
            ]
        );

        $this->add_control(
            'social_icon_text_color_hover',
            [
                'label' => esc_html__('Icon Text Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .image-hover-container a:hover .social-text' => 'color: {{VALUE}};',
                ],
                'condition' => ['image_hover_overlay_content'=>'social'],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'icon_background_color_hover',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-image .image-hover-container a:hover,{{WRAPPER}} .tcgelements-image .tc-hover-media:hover,{{WRAPPER}} .tcgelements-image .image-hover-container .tcgelements-float-cursor:hover .icon',
                'condition' => ['image_hover_overlay_content!'=>'none'],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control(
            'social_icon_transition',
            [
                'label' => esc_html__('Social Icon Transition Duration', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .image-hover-container a' => 'transition-duration: {{SIZE}}s',
                ],
                'condition' => ['image_hover_overlay_content'=>'social'],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_image_hover_container_options',
            [
                'label' => esc_html__( 'Image Hover Container Options', 'element-camp' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['image_hover_overlay_content!'=>'none'],
            ]
        );
        $this->add_responsive_control(
            'image_hover_container_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'rem', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .image-hover-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => ['image_hover_overlay_content!'=>'none'],
            ]
        );
        $this->add_responsive_control(
            'image_hover_container_padding',
            [
                'label' => esc_html__('Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'rem', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .image-hover-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => ['image_hover_overlay_content!'=>'none'],
                'separator' => 'after',
            ]
        );

        $this->add_responsive_control(
            'image_hover_container_height',
            [
                'label' => esc_html__( 'Image Hover Container Height', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-image .image-hover-container' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => ['image_hover_overlay_content!'=>'none'],
            ]
        );

        $this->add_responsive_control(
            'image_hover_container_width',
            [
                'label' => esc_html__( 'Image Hover Container Width', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-image .image-hover-container' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => ['image_hover_overlay_content!'=>'none'],
            ]
        );
        $this->add_control(
            'image_hover_container_enable_backdrop_filter',
            [
                'label' => esc_html__('Enable Backdrop Filter', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );
        $this->add_control(
            'image_hover_container_backdrop_filter',
            [
                'label' => esc_html__('Backdrop Filter', 'element-camp'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_block' => true,
                'condition' => [
                    'image_hover_container_enable_backdrop_filter' => 'yes',
                ],
            ]
        );

        $this->start_popover();

        $this->add_control(
            'blur',
            [
                'label' => esc_html__('Blur', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px','%','custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 0,
                    'unit' => 'px',
                ],
            ]
        );

        $this->add_control(
            'brightness',
            [
                'label' => esc_html__('Brightness', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['%','custom'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'size' => 100,
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .image-hover-container' => 'backdrop-filter: brightness({{SIZE}}{{UNIT}}) blur({{blur.SIZE}}{{blur.UNIT}});',
                ],
            ]
        );

        $this->end_popover();

        $this->start_controls_tabs(
            'image_hover_container_tabs',
        );
        $this->start_controls_tab(
            'image_hover_container_normal_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
                'condition' => ['image_hover_overlay_content!'=>'none'],
            ]
        );
        $this->add_control(
            'position_for_image_hover_container',
            [
                'label' => esc_html__( 'Image Hover Container Position', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'absolute',
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'absolute' => esc_html__( 'Absolute', 'element-camp' ),
                    'relative' => esc_html__( 'Relative', 'element-camp' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .image-hover-container' => 'position: {{VALUE}};',
                ],
                'condition' => ['image_hover_overlay_content!'=>'none'],
            ]
        );
        $this->add_control(
            'image_hover_container_offset_orientation_h',
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
                    'image_hover_overlay_content!'=>'none',
                    'position_for_image_hover_container!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_hover_container_offset_x',
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
                    'size' => 50,
                    'unit' => '%',
                ],
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .image-hover-container' => 'left: {{SIZE}}{{UNIT}};right:unset;',
                ],
                'condition' => [
                    'image_hover_container_offset_orientation_h' => 'start',
                    'position_for_image_hover_container!' => '',
                    'image_hover_overlay_content!'=>'none'
                ],
            ]
        );
        $this->add_responsive_control(
            'image_hover_container_offset_x_end',
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
                    'size' => 50,
                    'unit' => '%',
                ],
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .image-hover-container' => 'right: {{SIZE}}{{UNIT}};left:unset;',
                ],
                'condition' => [
                    'image_hover_container_offset_orientation_h' => 'end',
                    'position_for_image_hover_container!' => '',
                    'image_hover_overlay_content!'=>'none'
                ],
            ]
        );
        $this->add_control(
            'image_hover_container_offset_orientation_v',
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
                    'position_for_image_hover_container!' => '',
                    'image_hover_overlay_content!'=>'none'
                ],
            ]
        );
        $this->add_responsive_control(
            'image_hover_container_offset_y',
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
                'default' => [
                    'size' => 50,
                    'unit' => '%',
                ],
                'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .image-hover-container' => 'top: {{SIZE}}{{UNIT}};bottom:unset;',
                ],
                'condition' => [
                    'image_hover_container_offset_orientation_v' => 'start',
                    'position_for_image_hover_container!' => '',
                    'image_hover_overlay_content!'=>'none'
                ],
            ]
        );
        $this->add_responsive_control(
            'image_hover_container_offset_y_end',
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
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .image-hover-container' => 'bottom: {{SIZE}}{{UNIT}};top:unset;',
                ],
                'condition' => [
                    'image_hover_container_offset_orientation_v' => 'end',
                    'position_for_image_hover_container!' => '',
                    'image_hover_overlay_content!'=>'none'
                ],
            ]
        );
        $this->add_control(
            'separator_border_container_position',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'image_hover_container_background_color',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-image .image-hover-container',
                'condition' => ['image_hover_overlay_content!'=>'none'],
            ]
        );
        $this->add_control(
            'content_color',
            [
                'label' => esc_html__('Content Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .image-hover-container > *' => 'color: {{VALUE}};fill: {{VALUE}};',
                ],
                'condition' => ['image_hover_overlay_content!'=>'none'],
            ]
        );
        $this->add_responsive_control(
            'image_hover_container_display',
            [
                'label' => esc_html__('Image Hover Container Display', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__('None', 'element-camp'),
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'inline-flex' => esc_html__('Inline Flex', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .image-hover-container' => 'display: {{VALUE}};'
                ],
                'condition' => ['image_hover_overlay_content!'=>'none'],
            ]
        );
        $this->add_responsive_control(
            'image_hover_container_display_direction',
            [
                'label' => esc_html__( 'Flex Direction', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-image .image-hover-container' => '{{VALUE}};'
                ],
                'condition'=>[
                    'image_hover_overlay_content!'=>'none',
                    'image_hover_container_display'=>['flex','inline-flex']
                ],
            ]
        );
        $this->add_responsive_control(
            'image_hover_container_justify_content',
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
                    '{{WRAPPER}} .tcgelements-image .image-hover-container' => 'justify-content: {{VALUE}};',
                ],
                'condition'=>[
                    'image_hover_overlay_content!'=>'none',
                    'image_hover_container_display'=>['flex','inline-flex']
                ],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'image_hover_container_align_items',
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
                    '{{WRAPPER}} .tcgelements-image .image-hover-container' => 'align-items: {{VALUE}};',
                ],
                'condition'=>[
                    'image_hover_overlay_content!'=>'none',
                    'image_hover_container_display'=>['flex','inline-flex']
                ],
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
                    '{{WRAPPER}} .tcgelements-image .image-hover-container' => 'flex-wrap: {{VALUE}};',
                ],
                'condition'=>[
                    'image_hover_overlay_content!'=>'none',
                    'image_hover_container_display'=>['flex','inline-flex']
                ],
            ]
        );
        $this->add_responsive_control(
            'image_hover_container_opacity',
            [
                'label' => esc_html__( 'Image Hover Container Opacity', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .image-hover-container' => 'opacity: {{SIZE}};',
                ],
                'condition' => ['image_hover_overlay_content!'=>'none'],
            ]
        );
        $this->add_responsive_control(
            'translate_image_hover_container_y',
            [
                'label' => esc_html__( 'Translate Y', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'condition' => ['image_hover_overlay_content!'=>'none'],
            ]
        );
        $this->add_responsive_control(
            'rotate_image_hover_container',
            [
                'label' => esc_html__( 'Rotate', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'deg','custom'],
                'default' => [
                    'size' => 0,
                    'unit' => 'deg',
                ],
                'tablet_default' => [
                    'unit' => 'deg',
                ],
                'mobile_default' => [
                    'unit' => 'deg',
                ],
                'condition' => ['image_hover_overlay_content!'=>'none'],
            ]
        );
        $this->add_responsive_control(
            'translate_image_hover_container_x',
            [
                'label' => esc_html__( 'Translate X', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .image-hover-container' => 'transform: rotate({{rotate_image_hover_container.SIZE}}{{rotate_image_hover_container.UNIT}}) translate({{translate_image_hover_container_x.SIZE}}{{translate_image_hover_container_x.UNIT}},{{translate_image_hover_container_y.SIZE}}{{translate_image_hover_container_y.UNIT}})',
                ],
                'condition' => ['image_hover_overlay_content!'=>'none'],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'image_hover_container_hover_tab',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
                'condition' => ['image_hover_overlay_content!'=>'none'],
            ]
        );
        $this->add_control(
            'position_for_image_hover_container_hover',
            [
                'label' => esc_html__( 'Image Hover Container Position', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'absolute',
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'absolute' => esc_html__( 'Absolute', 'element-camp' ),
                    'relative' => esc_html__( 'Relative', 'element-camp' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image.selector-type-image:hover .image-hover-container' => 'position: {{VALUE}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.selector-type-container.tcgelements-image-container-active .image-hover-container' => 'position: {{VALUE}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.tcgelements-image-container-active .image-hover-container' => 'position: {{VALUE}};',
                ],
                'condition' => ['image_hover_overlay_content!'=>'none'],
            ]
        );
        $this->add_control(
            'image_hover_container_offset_orientation_h_hover',
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
                    'image_hover_overlay_content!'=>'none',
                    'position_for_image_hover_container_hover!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_hover_container_offset_x_hover',
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
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image.selector-type-image:hover .image-hover-container' => 'left: {{SIZE}}{{UNIT}};right:unset;',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.selector-type-container.tcgelements-image-container-active .image-hover-container' => 'left: {{SIZE}}{{UNIT}};right:unset;',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.tcgelements-image-container-active .image-hover-container' => 'left: {{SIZE}}{{UNIT}};right:unset;',
                ],
                'condition' => [
                    'image_hover_container_offset_orientation_h_hover' => 'start',
                    'position_for_image_hover_container_hover!' => '',
                    'image_hover_overlay_content!'=>'none'
                ],
            ]
        );
        $this->add_responsive_control(
            'image_hover_container_offset_x_end_hover',
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
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image.selector-type-image:hover .image-hover-container' => 'right: {{SIZE}}{{UNIT}};left:unset;',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.selector-type-container.tcgelements-image-container-active .image-hover-container' => 'right: {{SIZE}}{{UNIT}};left:unset;',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.tcgelements-image-container-active .image-hover-container' => 'right: {{SIZE}}{{UNIT}};left:unset;',
                ],
                'condition' => [
                    'image_hover_container_offset_orientation_h_hover' => 'end',
                    'position_for_image_hover_container_hover!' => '',
                    'image_hover_overlay_content!'=>'none'
                ],
            ]
        );
        $this->add_control(
            'image_hover_container_offset_orientation_v_hover',
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
                    'position_for_image_hover_container_hover!' => '',
                    'image_hover_overlay_content!'=>'none'
                ],
            ]
        );
        $this->add_responsive_control(
            'image_hover_container_offset_y_hover',
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
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image.selector-type-image:hover .image-hover-container' => 'top: {{SIZE}}{{UNIT}};bottom:unset;',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.selector-type-container.tcgelements-image-container-active .image-hover-container' => 'top: {{SIZE}}{{UNIT}};bottom:unset;',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.tcgelements-image-container-active .image-hover-container' => 'top: {{SIZE}}{{UNIT}};bottom:unset;',
                ],
                'condition' => [
                    'image_hover_container_offset_orientation_v_hover' => 'start',
                    'position_for_image_hover_container_hover!' => '',
                    'image_hover_overlay_content!'=>'none'
                ],
            ]
        );
        $this->add_responsive_control(
            'image_hover_container_offset_y_end_hover',
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
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image.selector-type-image:hover .image-hover-container' => 'bottom: {{SIZE}}{{UNIT}};top:unset;',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.selector-type-container.tcgelements-image-container-active .image-hover-container' => 'bottom: {{SIZE}}{{UNIT}};top:unset;',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.tcgelements-image-container-active .image-hover-container' => 'bottom: {{SIZE}}{{UNIT}};top:unset;',
                ],
                'condition' => [
                    'image_hover_container_offset_orientation_v_hover' => 'end',
                    'position_for_image_hover_container_hover!' => '',
                    'image_hover_overlay_content!'=>'none'
                ],
            ]
        );
        $this->add_control(
            'separator_border_container_position_hover',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'image_hover_container_background_color_hover',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.selector-type-container.tcgelements-image-container-active .image-hover-container,.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.tcgelements-image-container-active .image-hover-container,{{WRAPPER}} .tcgelements-image.selector-type-image:hover .image-hover-container',
                'condition' => ['image_hover_overlay_content!'=>'none'],
            ]
        );
        $this->add_control(
            'content_color_hover',
            [
                'label' => esc_html__('Content Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image.selector-type-image:hover .image-hover-container > *' => 'color: {{VALUE}};fill: {{VALUE}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.selector-type-container.tcgelements-image-container-active .image-hover-container > *' => 'color: {{VALUE}};fill: {{VALUE}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.tcgelements-image-container-active .image-hover-container > *' => 'color: {{VALUE}};fill: {{VALUE}};',
                ],
                'condition' => ['image_hover_overlay_content!'=>'none'],
            ]
        );
        $this->add_responsive_control(
            'image_hover_container_display_hover',
            [
                'label' => esc_html__('Image Hover Container Display', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'block',
                'options' => [
                    'none' => esc_html__('None', 'element-camp'),
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'inline-flex' => esc_html__('Inline Flex', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                ],
                'selectors' => [
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.selector-type-container.tcgelements-image-container-active .image-hover-container' => 'display: {{SIZE}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.tcgelements-image-container-active .image-hover-container' => 'display: {{SIZE}};',
                    '{{WRAPPER}} .tcgelements-image.selector-type-image:hover .image-hover-container' => 'display: {{SIZE}};',
                ],
                'condition' => ['image_hover_overlay_content!'=>'none'],
            ]
        );
        $this->add_responsive_control(
            'image_hover_container_display_direction_hover',
            [
                'label' => esc_html__( 'Flex Direction', 'element-camp' ),
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
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.selector-type-container.tcgelements-image-container-active .image-hover-container' => '{{VALUE}}',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.tcgelements-image-container-active .image-hover-container' => '{{VALUE}}',
                    '{{WRAPPER}} .tcgelements-image.selector-type-image:hover .image-hover-container' => '{{VALUE}}',
                ],
                'condition'=>['image_hover_container_display_hover'=>['flex','inline-flex']],
            ]
        );
        $this->add_responsive_control(
            'image_hover_container_justify_content_hover',
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
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.selector-type-container.tcgelements-image-container-active .image-hover-container' => 'justify-content: {{VALUE}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.tcgelements-image-container-active .image-hover-container' => 'justify-content: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-image.selector-type-image:hover .image-hover-container' => 'justify-content: {{VALUE}};',
                ],
                'condition'=>['image_hover_container_display_hover'=>['flex','inline-flex']],
                'responsive' => true,
            ]);
        $this->add_responsive_control(
            'image_hover_container_align_items_hover',
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
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.selector-type-container.tcgelements-image-container-active .image-hover-container' => 'align-items: {{VALUE}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.tcgelements-image-container-active .image-hover-container' => 'align-items: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-image.selector-type-image:hover .image-hover-container' => 'align-items: {{VALUE}};',
                ],
                'condition'=>['image_hover_container_display_hover'=>['flex','inline-flex']],
                'responsive' => true,
            ]);
        $this->add_responsive_control(
            'image_hover_container_opacity_hover',
            [
                'label' => esc_html__( 'Image Hover Container Opacity', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.selector-type-container.tcgelements-image-container-active .image-hover-container' => 'opacity: {{SIZE}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.tcgelements-image-container-active .image-hover-container' => 'opacity: {{SIZE}};',
                    '{{WRAPPER}} .tcgelements-image.selector-type-image:hover .image-hover-container' => 'opacity: {{SIZE}};',
                ],
                'condition' => ['image_hover_overlay_content!'=>'none'],
            ]
        );
        $this->add_responsive_control(
            'translate_image_hover_container_y_hover',
            [
                'label' => esc_html__( 'Translate Y', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'condition' => ['image_hover_overlay_content!'=>'none'],
            ]
        );
        $this->add_responsive_control(
            'rotate_image_hover_container_hover',
            [
                'label' => esc_html__( 'Rotate', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'deg','custom'],
                'default' => [
                    'size' => 0,
                    'unit' => 'deg',
                ],
                'tablet_default' => [
                    'unit' => 'deg',
                ],
                'mobile_default' => [
                    'unit' => 'deg',
                ],
                'condition' => ['image_hover_overlay_content!'=>'none'],
            ]
        );
        $this->add_responsive_control(
            'translate_image_hover_container_x_hover',
            [
                'label' => esc_html__( 'Translate X', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'selectors' => [
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.selector-type-container.tcgelements-image-container-active .image-hover-container' => 'transform: rotate({{rotate_image_hover_container_hover.SIZE}}{{rotate_image_hover_container_hover.UNIT}}) translate({{translate_image_hover_container_x_hover.SIZE}}{{translate_image_hover_container_x_hover.UNIT}},{{translate_image_hover_container_y_hover.SIZE}}{{translate_image_hover_container_y_hover.UNIT}})',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.tcgelements-image-container-active .image-hover-container' => 'transform: rotate({{rotate_image_hover_container_hover.SIZE}}{{rotate_image_hover_container_hover.UNIT}}) translate({{translate_image_hover_container_x_hover.SIZE}}{{translate_image_hover_container_x_hover.UNIT}},{{translate_image_hover_container_y_hover.SIZE}}{{translate_image_hover_container_y_hover.UNIT}})',
                    '{{WRAPPER}} .tcgelements-image.selector-type-image:hover .image-hover-container' => 'transform: rotate({{rotate_image_hover_container_hover.SIZE}}{{rotate_image_hover_container_hover.UNIT}}) translate({{translate_image_hover_container_x_hover.SIZE}}{{translate_image_hover_container_x_hover.UNIT}},{{translate_image_hover_container_y_hover.SIZE}}{{translate_image_hover_container_y_hover.UNIT}})',
                ],
                'condition' => ['image_hover_overlay_content!'=>'none'],
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
        $this->start_controls_section(
            'section_container_hover',
            [
                'label' => esc_html__( 'On Container Hover', 'element-camp' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'image_hover_overlay_selector!' => 'image'
                ]
            ]
        );
        $this->start_controls_tabs(
            'container_hover_tabs',
        );

        $this->start_controls_tab(
            'container_hover_normal_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_control(
            'image_on_container_hover_grayscale',
            [
                'label' => esc_html__( 'Image Gray Scale', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image img' => 'filter: grayscale({{SIZE}});',
                ],
            ]
        );
        $this->add_control(
            'image_on_container_hover_blur',
            [
                'label' => esc_html__( 'Blur', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image' => 'filter: blur({{SIZE}}{{UNIT}});',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_on_container_hover_opacity',
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
                    '{{WRAPPER}} .tcgelements-image' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_on_container_hover_border',
                'selector' => '{{WRAPPER}} .tcgelements-image',
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'image_hover_translate_y',
            [
                'label' => esc_html__( 'Translate Y', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
            ]
        );
        $this->add_control(
            'image_hover_rotate',
            [
                'label' => esc_html__( 'Rotate', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'deg', 'custom'],
                'default' => [
                    'unit' => 'deg',
                    'size' => '0',
                ],
            ]
        );
        $this->add_control(
            'image_hover_translate_x',
            [
                'label' => esc_html__( 'Translate X', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image' => 'transform:  rotate({{image_hover_rotate.SIZE}}{{image_hover_rotate.UNIT}}) translate({{image_hover_translate_x.SIZE}}{{image_hover_translate_x.UNIT}},{{image_hover_translate_y.SIZE}}{{image_hover_translate_y.UNIT}})',
                ],
            ]
        );
        $this->add_control(
            'image_mask_switch',
            [
                'label' => esc_html__( 'Mask', 'elementor' ),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'image' => esc_html__('Image', 'element-camp'),
                    'none' => esc_html__('none', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image img' => 'mask-image: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'image_mask_image',
            [
                'label' => esc_html__( 'Image', 'elementor' ),
                'type' => Controls_Manager::MEDIA,
                'media_types' => [ 'image' ],
                'should_include_svg_inline_option' => true,
                'library_type' => 'image/svg+xml',
                'dynamic' => [
                    'active' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image img' => '-webkit-mask-image: url( {{URL}} );',
                ],
                'condition' => [
                    'image_mask_switch' => 'image',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_mask_position',
            [
                'label' => esc_html__( 'Position', 'elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'center center' => esc_html__( 'Center Center', 'elementor' ),
                    'center left' => esc_html__( 'Center Left', 'elementor' ),
                    'center right' => esc_html__( 'Center Right', 'elementor' ),
                    'top center' => esc_html__( 'Top Center', 'elementor' ),
                    'top left' => esc_html__( 'Top Left', 'elementor' ),
                    'top right' => esc_html__( 'Top Right', 'elementor' ),
                    'bottom center' => esc_html__( 'Bottom Center', 'elementor' ),
                    'bottom left' => esc_html__( 'Bottom Left', 'elementor' ),
                    'bottom right' => esc_html__( 'Bottom Right', 'elementor' ),
                    'custom' => esc_html__( 'Custom', 'elementor' ),
                ],
                'default' => 'center center',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image img' => '-webkit-mask-position: {{VALUE}};',
                ],
                'condition' => [
                    'image_mask_switch!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_mask_repeat',
            [
                'label' => esc_html__( 'Repeat', 'elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'no-repeat' => esc_html__( 'No-repeat', 'elementor' ),
                    'repeat' => esc_html__( 'Repeat', 'elementor' ),
                    'repeat-x' => esc_html__( 'Repeat-x', 'elementor' ),
                    'repeat-Y' => esc_html__( 'Repeat-y', 'elementor' ),
                    'round' => esc_html__( 'Round', 'elementor' ),
                    'space' => esc_html__( 'Space', 'elementor' ),
                ],
                'default' => 'no-repeat',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image img' => '-webkit-mask-repeat: {{VALUE}};',
                ],
                'condition' => [
                    'image_mask_switch' => 'image',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'container_hover_hover_tab',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
            ]
        );
        $this->add_responsive_control(
            'image_on_container_hover_padding',
            [
                'label' => esc_html__('Image Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'rem', 'custom'],
                'selectors' => [
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.selector-type-container.tcgelements-image-container-active img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.tcgelements-image-container-active img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );
        $this->add_responsive_control(
            'img_width_on_container_hover',
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
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.selector-type-container.tcgelements-image-container-active img' => 'width: {{SIZE}}{{UNIT}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.tcgelements-image-container-active img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'image_on_container_hover_grayscale_hover',
            [
                'label' => esc_html__( 'Image Gray Scale', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.selector-type-container.tcgelements-image-container-active img' => 'filter: grayscale({{SIZE}});',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.tcgelements-image-container-active img' => 'filter: grayscale({{SIZE}});',
                ],
            ]
        );
        $this->add_control(
            'image_on_container_hover_blur_hover',
            [
                'label' => esc_html__( 'Blur', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.selector-type-container.tcgelements-image-container-active' => 'filter: blur({{SIZE}}{{UNIT}});',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.tcgelements-image-container-active' => 'filter: blur({{SIZE}}{{UNIT}});',
                ],
            ]
        );
        $this->add_control(
            'image_on_container_hover_opacity_hover',
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
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.selector-type-container.tcgelements-image-container-active' => 'opacity: {{SIZE}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.tcgelements-image-container-active' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_on_container_hover_border_hover',
                'selector' => '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.selector-type-container.tcgelements-image-container-active,.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.tcgelements-image-container-active',
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'image_on_container_hover_translate_y_hover',
            [
                'label' => esc_html__( 'Translate Y', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
            ]
        );
        $this->add_control(
            'image_on_container_hover_rotate_hover',
            [
                'label' => esc_html__( 'Rotate', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'deg', 'custom'],
                'default' => [
                    'unit' => 'deg',
                    'size' => '0',
                ],
            ]
        );
        $this->add_control(
            'image_on_container_hover_translate_x_hover',
            [
                'label' => esc_html__( 'Translate X', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'selectors' => [
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.selector-type-container.tcgelements-image-container-active' => 'transform:  rotate({{image_on_container_hover_rotate_hover.SIZE}}{{image_on_container_hover_rotate_hover.UNIT}}) translate({{image_on_container_hover_translate_x_hover.SIZE}}{{image_on_container_hover_translate_x_hover.UNIT}},{{image_on_container_hover_translate_y_hover.SIZE}}{{image_on_container_hover_translate_y_hover.UNIT}})',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.tcgelements-image-container-active' => 'transform:  rotate({{image_on_container_hover_rotate_hover.SIZE}}{{image_on_container_hover_rotate_hover.UNIT}}) translate({{image_on_container_hover_translate_x_hover.SIZE}}{{image_on_container_hover_translate_x_hover.UNIT}},{{image_on_container_hover_translate_y_hover.SIZE}}{{image_on_container_hover_translate_y_hover.UNIT}})',
                ],
            ]
        );
        $this->add_control(
            'image_mask_switch_hover',
            [
                'label' => esc_html__( 'Mask', 'elementor' ),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'image' => esc_html__('Image', 'element-camp'),
                    'none' => esc_html__('none', 'element-camp'),
                ],
                'selectors' => [
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.selector-type-container.tcgelements-image-container-active img' => 'mask-image: {{VALUE}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.tcgelements-image-container-active img' => 'mask-image: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'image_mask_image_hover',
            [
                'label' => esc_html__( 'Image', 'elementor' ),
                'type' => Controls_Manager::MEDIA,
                'media_types' => [ 'image' ],
                'should_include_svg_inline_option' => true,
                'library_type' => 'image/svg+xml',
                'dynamic' => [
                    'active' => true,
                ],
                'selectors' => [
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.selector-type-container.tcgelements-image-container-active img' => '-webkit-mask-image: url( {{URL}} );',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-image.tcgelements-image-container-active img' => '-webkit-mask-image: url( {{URL}} );',
                ],
                'condition' => [
                    'image_mask_switch_hover' => 'image',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
        $this->start_controls_section(
            'section_image_animations',
            [
                'label' => esc_html__( 'Animations', 'element-camp' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'image_animations',
            [
                'label' => esc_html__('Animations', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'tce-rotate-center-animation' => esc_html__('Rotate', 'element-camp'),
                    'tce-mouse-parallax' => esc_html__('Mouse Parallax', 'element-camp'),
                    'scroll-parallax' => esc_html__('Scroll Parallax', 'element-camp'),
                    'line wow' => esc_html__('Line', 'element-camp'),
                    'clippy-img wow' => esc_html__('Clippy Image', 'element-camp'),
                    'tce-scroll-trigger-scale' => esc_html__('Scroll Scale', 'element-camp'),
                ],
            ]
        );
        $this->add_control(
            'rotate_animation_speed',
            [
                'label' => esc_html__('Rotate Animation Speed', 'element-camp'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'condition'=>['image_animations'=>'tce-rotate-center-animation'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image.tce-rotate-center-animation' => 'animation-duration: {{SIZE}}s;',
                ],
            ]
        );
        $this->add_control(
            'rotate_animation_reverse',
            [
                'label' => esc_html__( 'Rotate Animation Reverse', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'yes', 'element-camp' ),
                'label_off' => esc_html__( 'No', 'element-camp' ),
                'condition'=>['image_animations'=>'tce-rotate-center-animation'],
            ]
        );
        $this->add_control(
            'parallax_speed',
            [
                'label' => esc_html__( 'Speed', 'element-camp' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 1,
                'min' => 0,
                'step' => 0.1,
                'condition' => [
                    'image_animations' => 'scroll-parallax',
                ],
            ]
        );
        $this->add_control(
            'parallax_lag',
            [
                'label' => esc_html__( 'Lag', 'element-camp' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.7,
                'min' => 0,
                'step' => 0.1,
                'condition' => [
                    'image_animations' => 'scroll-parallax',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'image_clip_path_style',
            [
                'label' => esc_html__('Clip Path', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'image_clip_path',
            [
                'label' => esc_html__( 'Clip Path', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'description' => esc_html__('example: polygon(0 0, 100% 5%, 100% 95%, 0% 100%)'),
                'placeholder' => esc_html__('example: polygon(0 0, 100% 5%, 100% 95%, 0% 100%)'),
                'language' => 'css',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image' => 'clip-path: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'button_style',
            [
                'label' => esc_html__('Button Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'button_switcher' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'button_overflow_hidden',
            [
                'label' => esc_html__( 'Overflow Hidden', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'yes', 'element-camp' ),
                'label_off' => esc_html__( 'No', 'element-camp' ),
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .butn' => 'overflow:hidden;',
                ],
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
                    '{{WRAPPER}} .tcgelements-image .butn' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );
        $this->add_control(
            'butn_index',
            [
                'label' => esc_html__( 'Button z-index', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .butn' => 'z-index: {{SIZE}};',
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
                    '{{WRAPPER}} .tcgelements-image .butn' => 'position: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-image .butn' => 'left: {{SIZE}}{{UNIT}}',
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
                    '{{WRAPPER}} .tcgelements-image .butn' => 'right: {{SIZE}}{{UNIT}}',
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
                    '{{WRAPPER}} .tcgelements-image .butn' => 'top: {{SIZE}}{{UNIT}}',
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
                    '{{WRAPPER}} .tcgelements-image .butn' => 'bottom: {{SIZE}}{{UNIT}}',
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
                    '{{WRAPPER}} .tcgelements-image .butn' => 'transform: translate({{button_translate_x.SIZE}}{{button_translate_x.UNIT}},{{button_translate_y.SIZE}}{{button_translate_y.UNIT}})',
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
                    '{{WRAPPER}} .tcgelements-image .butn' => 'width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-image .butn' => 'height: {{SIZE}}{{UNIT}};',
                ],
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
                    '{{WRAPPER}} .tcgelements-image .butn' => 'display: {{VALUE}};'
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
                    '{{WRAPPER}} .tcgelements-image .butn' => 'justify-content: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-image .butn' => 'align-items: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-image .butn' => 'color: {{VALUE}}; fill: {{VALUE}};',
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
                'selector' => '{{WRAPPER}} .tcgelements-image .butn',
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
                    '{{WRAPPER}} .tcgelements-image .butn' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-image .butn',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'selector' => '{{WRAPPER}} .tcgelements-image .butn',
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
                    '{{WRAPPER}} .tcgelements-image .butn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-image .butn' => 'outline: {{outline_width.SIZE}}{{outline_width.UNIT}} {{outline.VALUE}} {{outline_color.VALUE}}',
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
                    '{{WRAPPER}} .tcgelements-image .butn' => 'outline-offset: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-service-card:hover .butn' => 'display: {{VALUE}};'
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
                    '{{WRAPPER}} .tcgelements-service-card:hover .butn' => 'justify-content: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-service-card:hover .butn' => 'align-items: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-service-card:hover .butn' => 'color: {{VALUE}};',
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
                'selector' => '{{WRAPPER}} .tcgelements-service-card:hover .butn',
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
                'selector' => '{{WRAPPER}} .tcgelements-service-card:hover .butn',
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
                    '{{WRAPPER}} .tcgelements-service-card:hover .butn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-service-card:hover .butn' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow_hover',
                'selector' => '{{WRAPPER}} .tcgelements-service-card:hover .butn',
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
                    '{{WRAPPER}} .tcgelements-service-card:hover .butn' => 'outline: {{outline_width_hover.SIZE}}{{outline_width_hover.UNIT}} {{outline_hover.VALUE}} {{outline_color_hover.VALUE}}',
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
                    '{{WRAPPER}} .tcgelements-service-card:hover .butn' => 'outline-offset: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-image .butn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-image .butn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'button_content_style',
            [
                'label' => esc_html__('Button Content Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'button_switcher' => 'yes',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_content_padding',
            [
                'label' => esc_html__('Button Content Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .butn .butn-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'button_text_style_heading',
            [
                'label' => esc_html__( 'Button Text Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );
        $this->add_responsive_control(
            'button_text_padding',
            [
                'label' => esc_html__('Button Text Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .butn .butn-content .text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_text_border_radius',
            [
                'label' => esc_html__('Button Text Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .butn .butn-content .text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'butn_content_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_ACCENT,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-image .butn .butn-content .text',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'button_text_background',
                'label' => esc_html__('Button Text Background', 'element-camp'),
                'types' => [ 'classic','gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-image .butn .butn-content .text',
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
            'button_icon_transition',
            [
                'label' => esc_html__( 'Button Icon Transition', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .butn .butn-icon' => 'transition: all {{SIZE}}s ease;',
                ],
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
                    '{{WRAPPER}} .tcgelements-image .butn .butn-icon' => 'display: {{VALUE}};'
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
                    '{{WRAPPER}} .tcgelements-image .butn .butn-icon' => 'justify-content: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-image .butn .butn-icon' => 'align-items: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-image .butn .butn-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-image .butn .butn-icon svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-image .butn .butn-icon' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-image .butn .butn-icon' => 'height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-image .butn .butn-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
        $this->add_responsive_control(
            'button_icon_margin',
            [
                'label' => esc_html__('Button Icon Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .butn .butn-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'button_icon_color',
            [
                'label' => __('Icon Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .butn .butn-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-image .butn .butn-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_icon_svg_path_stroke_color',
            [
                'label' => __('SVG Path Stroke Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .butn .butn-icon svg path' => 'stroke: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'button_icon_background',
                'label' => esc_html__('Button Icon Background', 'element-camp'),
                'types' => [ 'classic','gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-image .butn .butn-icon',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'button_icon_border',
                'selector' => '{{WRAPPER}} .tcgelements-image .butn .butn-icon',
            ]
        );
        $this->add_control(
            'translate_button_icon_y',
            [
                'label' => esc_html__( 'Translate Y', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
            ]
        );
        $this->add_control(
            'translate_button_icon_x',
            [
                'label' => esc_html__( 'Translate X', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .butn .butn-icon' => 'transform: translate({{translate_button_icon_x.SIZE}}{{translate_button_icon_x.UNIT}},{{translate_button_icon_y.SIZE}}{{translate_button_icon_y.UNIT}})',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_button_icon_hover',
            [
                'label' => esc_html__('Hover', 'element-camp'),
            ]
        );
        $this->add_responsive_control(
            'button_icon_margin_hober',
            [
                'label' => esc_html__('Button Icon Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-image .butn:hover .butn-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'hover_button_icon_color',
            [
                'label' => __('Icon Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-service-card:hover .butn .butn-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-service-card:hover .butn .butn-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'hover_button_icon_svg_path_stroke_color',
            [
                'label' => __('SVG Path Stroke Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-service-card:hover .butn .butn-icon svg path' => 'stroke: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'button_icon_background_hover',
                'label' => esc_html__('Button Icon Background', 'element-camp'),
                'types' => [ 'classic','gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-service-card:hover .butn .butn-icon',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'button_icon_border_hover',
                'selector' => '{{WRAPPER}} .tcgelements-service-card:hover .butn .butn-icon',
            ]
        );
        $this->add_control(
            'translate_button_icon_y_hover',
            [
                'label' => esc_html__( 'Translate Y', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
            ]
        );
        $this->add_control(
            'translate_button_icon_x_hover',
            [
                'label' => esc_html__( 'Translate X', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-service-card:hover .butn .butn-icon' => 'transform: translate({{translate_button_icon_x_hover.SIZE}}{{translate_button_icon_x_hover.UNIT}},{{translate_button_icon_y_hover.SIZE}}{{translate_button_icon_y_hover.UNIT}})',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

    }

    /**
     * Check if the current widget has caption
     *
     * @access private
     * @since 2.3.0
     *
     * @param array $settings
     *
     * @return boolean
     */
    private function has_caption( $settings ) {
        return ( ! empty( $settings['caption_source'] ) && 'none' !== $settings['caption_source'] );
    }

    /**
     * Get the caption for current widget.
     *
     * @access private
     * @since 2.3.0
     * @param $settings
     *
     * @return string
     */
    private function get_caption( $settings ) {
        $caption = '';
        if ( ! empty( $settings['caption_source'] ) ) {
            switch ( $settings['caption_source'] ) {
                case 'attachment':
                    $caption = wp_get_attachment_caption( $settings['image']['id'] );
                    break;
                case 'custom':
                    $caption = ! Utils::is_empty( $settings['caption'] ) ? $settings['caption'] : '';
            }
        }
        return $caption;
    }

    /**
     * Render image widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        if ( empty( $settings['image']['url'] ) ) {
            return;
        }

        if ( ! Plugin::$instance->experiments->is_feature_active( 'e_dom_optimization' ) ) {
            $this->add_render_attribute( 'wrapper', 'class', 'elementor-image' );
        }

        $has_caption = $this->has_caption( $settings );

        $link = $this->get_link_url( $settings );
        if ( 'site-url' == $settings['link_to'] ) {
            $link['url'] = esc_url(get_site_url());
        }
        if ($settings['link_to'] == 'post-url') {
            $link['url'] = esc_url(get_the_permalink());
        }
        ?>

        <div class="tcgelements-image <?php echo 'selector-type-' . esc_attr($settings['image_hover_overlay_selector']) ?> <?php if (!empty($settings['image_animations'])) echo esc_attr($settings['image_animations'])?> <?php if ($settings['rotate_animation_reverse'] == 'yes') echo esc_attr("reverse"); ?><?php if ($settings['image_hover_overlay_content'] == 'float-cursor') echo ' '.esc_attr("tcgelements-float-cursor-container"); ?>">

            <?php
            if ( $link ) {
                $this->add_link_attributes( 'link', $link );

                if ( Plugin::$instance->editor->is_edit_mode() ) {
                    $this->add_render_attribute( 'link', [
                        'class' => 'elementor-clickable',
                    ] );
                }

                if ( 'custom' !== $settings['link_to'] && 'video-popup' !== $settings['link_to' ] && 'pop-up' !== $settings['link_to'] ) {
                    $this->add_lightbox_data_attributes( 'link', $settings['image']['id'], $settings['open_lightbox'] );
                }
            } ?>

            <?php if ( ! Plugin::$instance->experiments->is_feature_active( 'e_dom_optimization' ) ) { ?>
            <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
                <?php } ?>

                <?php if ( $has_caption ) : ?>
                <figure class="wp-caption">
                    <?php endif; ?>

                    <?php if ( $link && $settings['link_to'] != 'video-popup' ) : ?>
                        <a href="<?=esc_url($settings['link']['url'])?>" <?php if ($settings['link_to']=='pop-up')echo esc_attr('data-lity=video')?> <?php if ($settings['link_to']=='custom' && $settings['link']['is_external'] ) {echo'target="_blank"';} ?>>
                    <?php endif; ?>

                        <?php
                        // Print the image tag with dynamic attributes
                        if ($settings['image_animations'] == 'scroll-parallax') : ?>
                            <img src="<?php echo esc_url( $settings['image']['url'] ); ?>"
                                 alt="<?php if (!empty($settings['image']['alt'])) echo esc_attr($settings['image']['alt']); ?>"
                                 data-lag="<?php echo esc_attr( $settings['parallax_lag'] ); ?>"
                                 data-speed="<?php echo esc_attr( $settings['parallax_speed'] ); ?>">
                        <?php else: ?>
                            <img src="<?php echo esc_url( $settings['image']['url'] ); ?>"
                                 alt="<?php if (!empty($settings['image']['alt'])) echo esc_attr($settings['image']['alt']); ?>">
                        <?php endif; ?>

                        <?php if ($settings['link_to'] == 'video-popup'): ?>
                            <a <?php $this->print_render_attribute_string( 'link' ); ?> data-lity="" class="vid_icon"><i class="fas fa-play"></i></a>
                        <?php endif; ?>

                        <?php if ($settings['button_switcher'] == 'yes'): ?>
                            <div class="butn">
                                <div class="butn-content">
                                    <?php if (!empty($settings['btn_icon']['value']) and ($settings['icon_align'] == 'left')) : ?>
                                        <span class="butn-icon">
                                            <?php Icons_Manager::render_icon($settings['btn_icon'], ['aria-hidden' => 'true']);?>
                                        </span>
                                    <?php endif; ?>
                                    <span class="text">
                                        <?php $this->print_unescaped_setting('btn_text'); ?>
                                    </span>
                                    <?php if (!empty($settings['btn_icon']['value'])  and ($settings['icon_align'] == 'right')) : ?>
                                        <span class="butn-icon">
                                            <?php Icons_Manager::render_icon($settings['btn_icon'], ['aria-hidden' => 'true']);?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <?php if ($settings['shapes']=='yes') : ?>
                                    <div class="shap-left-bottom">
                                        <?php Icons_Manager::render_icon($settings['shape_icon'], ['aria-hidden' => 'true']);?>
                                    </div>
                                    <div class="shap-right-top">
                                        <?php Icons_Manager::render_icon($settings['shape_icon'], ['aria-hidden' => 'true']);?>
                                    </div>
                                <?php endif;?>
                            </div>
                        <?php endif; ?>

                        <?php if ( $link && $settings['link_to'] != 'video-popup' ) : ?>
                            </a>
                        <?php endif; ?>

                    <?php if ( $has_caption ) : ?>
                        <figcaption class="widget-image-caption wp-caption-text">
                            <?php echo wp_kses_post( $this->get_caption( $settings ) ); ?>
                        </figcaption>
                    <?php endif; ?>

                    <?php if ( $has_caption ) : ?>
                </figure>
            <?php endif; ?>

                <?php if ( ! Plugin::$instance->experiments->is_feature_active( 'e_dom_optimization' ) ) { ?>
            </div>
        <?php } ?>

            <?php if ($settings['image_hover_overlay_content'] != 'none') : ?>
                <div class="image-hover-container">
                    <?php if ($settings['image_hover_overlay_content'] == 'media' && $settings['media_type'] == 'icon') : ?>
                        <a class="tc-hover-media" href="<?=$settings['media_type_link']['url']?>" <?php if ( $settings['media_type_link']['is_external'] ) echo 'target="_blank"'; ?>>
                            <?php \Elementor\Icons_Manager::render_icon( $settings['media_type_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                            <?php if (!empty($settings['media_type_text'])) : ?>
                                <span class="media-icon-text"><?=$settings['media_type_text']?></span>
                            <?php endif;?>
                        </a>
                    <?php endif;?>
                    <?php if ($settings['image_hover_overlay_content'] == 'media' && $settings['media_type'] == 'image') : ?>
                        <div class="tc-hover-media">
                            <?php echo '<img src="' . $settings['media_type_image']['url'] . '">'; ?>
                        </div>
                    <?php endif;?>
                    <?php if ($settings['image_hover_overlay_content'] == 'social') : ?>
                        <?php foreach ( $settings['social_icons_list'] as $icon ) : ?>
                            <a class="link <?php echo 'elementor-repeater-item-' . esc_attr( $icon['_id'] ); ?>" href="<?php echo esc_url( $icon['link']['url'] ); ?>" <?php if ( $icon['link']['is_external'] ) echo 'target="_blank"'; ?>>
                                <?php \Elementor\Icons_Manager::render_icon( $icon['icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                <?php if ( ! empty( $icon['text'] ) ) : ?>
                                    <span class="social-text"><?php echo esc_html( $icon['text'] ); ?></span>
                                <?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                    <?php endif;?>
                    <?php if ($settings['image_hover_overlay_content'] == 'float-cursor') : ?>
                        <div class="tcgelements-float-cursor">
                            <div class="cont">
                                <?php if (!empty($settings['float_cursor_icon']['value'])) : ?>
                                    <span class="icon">
                                        <?php \Elementor\Icons_Manager::render_icon( $settings['float_cursor_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                    </span>
                                <?php endif;?>
                                <?php if (!empty($settings['float_cursor_text'])) : ?>
                                    <span class="text"><?=$settings['float_cursor_text']?></span>
                                <?php endif;?>
                            </div>
                        </div>
                    <?php endif;?>
                </div>
            <?php endif; ?>
        </div>

        <?php
    }

    /**
     * Retrieve image widget link URL.
     *
     * @since 1.0.0
     * @access private
     *
     * @param array $settings
     *
     * @return array|string|false An array/string containing the link URL, or false if no link.
     */
    private function get_link_url( $settings ) {
        if ( 'none' === $settings['link_to'] ) {
            return false;
        }

        if ( 'custom' === $settings['link_to'] || 'video-popup' === $settings['link_to'] ) {
            if ( empty( $settings['link']['url'] ) ) {
                return false;
            }

            return $settings['link'];
        }

        return [
            'url' => $settings['image']['url'],
        ];
    }
}