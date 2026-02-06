<?php
namespace ElementCampPlugin\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
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
use ElementCampPlugin\Elementor\Controls\TCG_Helper as ControlsHelper;

class ElementCamp_Gallery_Text_Sliders extends Widget_Base
{
    public function get_name()
    {
        return 'tcgelements-gallery-text-sliders';
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

    public function get_script_depends() {
        return ['swiper','tcgelements-background-image','tcgelements-gallery-text-sliders'];
    }


    public function get_title()
    {
        return esc_html__('Gallery Text Sliders', 'element-camp');
    }

    public function get_icon()
    {
        return 'eicon-slider-album tce-widget-badge';
    }

    public function get_categories()
    {
        return ['elementcamp-elements'];
    }

    protected function _register_controls()
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Content', 'element-camp'),
            ]
        );

        $slides_repeater = new \Elementor\Repeater();

        $slides_repeater->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'element-camp'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'placeholder' => esc_html__('Type your text here', 'element-camp'),
            ]
        );

        $slides_repeater->add_control(
            'sub_title',
            [
                'label' => esc_html__('Sub Title', 'element-camp'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'placeholder' => esc_html__('Type your text here', 'element-camp'),
            ]
        );

        $slides_repeater->add_control(
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

        $this->add_control(
            'slides_repeater',
            [
                'label' => esc_html__('Slides', 'element-camp'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $slides_repeater->get_controls(),
                'title_field' => '{{{ title }}}',
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

        $this->add_control(
            'arrows',
            [
                'label' => esc_html__( 'Arrows Controls', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'On', 'element-camp' ),
                'label_off' => esc_html__( 'Off', 'element-camp' ),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'arrows_content',
            [
                'label' => esc_html__('Arrows', 'element-camp'),
                'condition' => [
                    'arrows' => 'true',
                ],
            ]
        );
        $this->add_control(
            'prev_btn_text',
            [
                'label' => esc_html__('Previous Button Text', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Prev Slide', 'element-camp'),
            ]
        );
        $this->add_control(
            'prev_btn_icon',
            [
                'label' => esc_html__('Previous Icon', 'element-camp'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'skin' => 'inline',
                'label_block' => false,
            ]
        );
        $this->add_control(
            'next_btn_text',
            [
                'label' => esc_html__('Next Button Text', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Next Slide', 'element-camp'),
            ]
        );
        $this->add_control(
            'next_btn_icon',
            [
                'label' => esc_html__('Next Icon', 'element-camp'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'skin' => 'inline',
                'label_block' => false,
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'slider_style',
            [
                'label' => esc_html__('Slider Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'gallery_text_sliders_padding',
            [
                'label' => esc_html__('Gallery Text Sliders Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-gallery-text-sliders ' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'image_style',
            [
                'label' => esc_html__('Image Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'image_container_style_options',
            [
                'label' => esc_html__( 'Image Container Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'before'
            ]
        );
        $this->add_control(
            'image_container_overflow_hidden',
            [
                'label' => esc_html__( 'Overflow Hidden', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'yes', 'element-camp' ),
                'label_off' => esc_html__( 'No', 'element-camp' ),
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-gallery-text-sliders .gallery-img .img' => 'overflow:hidden;',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_container_height',
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
                    '{{WRAPPER}} .tcgelements-gallery-text-sliders .gallery-img .img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_container_width',
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
                    '{{WRAPPER}} .tcgelements-gallery-text-sliders .gallery-img .img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_container_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-gallery-text-sliders .gallery-img .img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'image_container_background_color',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-gallery-text-sliders .gallery-img .img',
            ]
        );
        $this->add_responsive_control(
            'image_container_padding',
            [
                'label' => esc_html__('Image Container Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-gallery-text-sliders .gallery-img .img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'image_style_options',
            [
                'label' => esc_html__( 'Image Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'before'
            ]
        );
        $this->add_responsive_control(
            'image_height',
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
                    '{{WRAPPER}} .tcgelements-gallery-text-sliders .gallery-img .img img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_width',
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
                    '{{WRAPPER}} .tcgelements-gallery-text-sliders .gallery-img .img img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'object-fit',
            [
                'label' => esc_html__( 'Object Fit', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'image_height[size]!' => '',
                ],
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'fill' => esc_html__( 'Fill', 'element-camp' ),
                    'cover' => esc_html__( 'Cover', 'element-camp' ),
                    'contain' => esc_html__( 'Contain', 'element-camp' ),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-gallery-text-sliders .gallery-img .img img' => 'object-fit: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-gallery-text-sliders .gallery-img .img img' => 'object-position: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-gallery-text-sliders .gallery-img .img img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );
        $this->start_controls_tabs(
            'image_style_tabs',
        );
        
        $this->start_controls_tab(
            'normal_image_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_control(
            'image_blur',
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
                    '{{WRAPPER}} .tcgelements-gallery-text-sliders .gallery-img .img img' => 'filter: blur({{SIZE}}{{UNIT}});',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'hover_image_tab',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
            ]
        );
        $this->add_control(
            'image_blur_hover',
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
                    '{{WRAPPER}} .tcgelements-gallery-text-sliders .gallery-img .swiper-slide:hover .img img' => 'filter: blur({{SIZE}}{{UNIT}});',
                ],
            ]
        );
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        $this->end_controls_section();
        $this->start_controls_section(
            'text_style',
            [
                'label' => esc_html__('Text Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'text_gallery_align',
            [
                'label' => esc_html__( 'Text Gallery Alignment', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-gallery-text-sliders .gallery-text' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'text_gallery_margin',
            [
                'label' => esc_html__('Text Gallery Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em' , 'rem' , 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-gallery-text-sliders .gallery-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'text_gallery_slide_height',
            [
                'label' => esc_html__( 'Text Gallery Swiper Height', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => '100',
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
                'size_units' => [ 'px', 'vh', '%' ],
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
                    '{{WRAPPER}} .tcgelements-gallery-text-sliders .gallery-text .swiper-container' => 'height: {{SIZE}}{{UNIT}} !important;',
                    '{{WRAPPER}} .tcgelements-gallery-text-sliders .gallery-text .swiper-container .swiper-slide' => 'height: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );
        $this->add_control(
            'title_style_options',
            [
                'label' => esc_html__( 'Title Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'before'
            ]
        );
        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-gallery-text-sliders .gallery-text .text .title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-gallery-text-sliders .gallery-text .text .title',
            ]
        );
        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Title Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}}  .tcgelements-gallery-text-sliders .gallery-text .text .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'sub-title_style_options',
            [
                'label' => esc_html__( 'sub-title Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'before'
            ]
        );
        $this->add_control(
            'sub-title_color',
            [
                'label' => esc_html__( 'sub-title Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-gallery-text-sliders .gallery-text .text .sub-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'sub-title_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-gallery-text-sliders .gallery-text .text .sub-title',
            ]
        );
        $this->add_responsive_control(
            'sub-title_margin',
            [
                'label' => esc_html__('sub-title Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-gallery-text-sliders .gallery-text .text .sub-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'arrows_style',
            [
                'label' => esc_html__('Arrows Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'arrows' => 'true',
                ],
            ]
        );
        $this->add_responsive_control(
            'arrows_icon_size',
            [
                'label' => esc_html__('Arrows Icon Size', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-gallery-text-sliders .swiper-controls i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-gallery-text-sliders .swiper-controls svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'arrows_icon_color',
            [
                'label' => esc_html__( 'Arrows Icon Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-gallery-text-sliders .swiper-controls i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-gallery-text-sliders .swiper-controls svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'swiper_controls_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-gallery-text-sliders .swiper-controls > *',
            ]
        );
        $this->add_control(
            'swiper_controls_color',
            [
                'label' => esc_html__( 'Swiper Controls Text Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-gallery-text-sliders .swiper-controls' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-gallery-text-sliders .swiper-controls > *' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->start_controls_tabs(
            'arrows_tabs',
        );

        $this->start_controls_tab(
            'next_arrows_tab',
            [
                'label'   => esc_html__( 'Next', 'element-camp' ),
            ]
        );
        $this->add_responsive_control(
            'next_icon_margin',
            [
                'label' => esc_html__('Next Icon Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-gallery-text-sliders .swiper-controls .swiper-button-next i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-gallery-text-sliders .swiper-controls .swiper-button-next svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'prev_arrows_tab',
            [
                'label'   => esc_html__( 'Prev', 'element-camp' ),
            ]
        );

        $this->add_responsive_control(
            'prev_icon_margin',
            [
                'label' => esc_html__('Prev Icon Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-gallery-text-sliders .swiper-controls .swiper-button-prev i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-gallery-text-sliders .swiper-controls .swiper-button-prev svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();

    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        ?>
        <div class="tcgelements-gallery-text-sliders">
            <div class="gallery-img">
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <?php foreach ($settings['slides_repeater'] as $slide) : ?>
                            <div class="swiper-slide">
                                <div class="img">
                                    <img src="<?= esc_url($slide['image']['url']); ?>" alt>
                                    <a href="<?= esc_url($slide['link']['url']); ?>" <?php if ( $slide['link']['is_external'] ) {echo'target="_blank"';} ?>></a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="gallery-text">
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <?php foreach ($settings['slides_repeater'] as $slide) : ?>
                            <div class="swiper-slide">
                                <div class="text">
                                    <h4 class="title"><?= esc_html($slide['title']); ?></h4>
                                    <h6 class="sub-title">
                                        <span>
                                            <?= esc_html($slide['sub_title']); ?>
                                        </span>
                                    </h6>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <!-- slider setting -->
            <?php if ($settings['arrows'] == 'true') : ?>
                <div class="swiper-controls">
                    <div class="swiper-button-next swiper-nav-ctrl cursor-pointer">
                        <div>
                            <span><?=esc_html($settings['next_btn_text'])?></span>
                        </div>
                        <div><?php \Elementor\Icons_Manager::render_icon( $settings['next_btn_icon'], [ 'aria-hidden' => 'true' ] );?></div>
                    </div>
                    <div class="swiper-button-prev swiper-nav-ctrl cursor-pointer">
                        <div><?php \Elementor\Icons_Manager::render_icon( $settings['prev_btn_icon'], [ 'aria-hidden' => 'true' ] );?></div>
                        <div>
                            <span><?=esc_html( $settings['prev_btn_text'])?></span>
                        </div>
                    </div>
                </div>
            <?php endif;?>
        </div>
        <?php
    }
}
