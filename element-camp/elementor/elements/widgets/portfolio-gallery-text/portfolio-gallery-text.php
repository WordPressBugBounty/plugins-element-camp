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
use Elementor\Repeater;
use ElementCampPlugin\Elementor\Controls\TCG_Helper as ControlsHelper;

class ElementCamp_Portfolio_Gallery_Text extends Widget_Base
{
    public function get_name()
    {
        return 'tcgelements-portfolio-gallery-text';
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
        return ['swiper','tcgelements-background-image','tcgelements-portfolio-gallery-text' ];
    }


    public function get_title()
    {
        return esc_html__('Query Gallery Text', 'element-camp');
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
        $post_types = ControlsHelper::get_post_types();
        $post_types['by_id'] = __('Manual Selection', 'themescamp-plugin');
        $taxonomies = get_taxonomies([], 'objects');

        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Content', 'element-camp'),
            ]
        );
        $this->add_control(
            'display_terms_type',
            [
                'label' => esc_html__('Display Terms Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'categories' => esc_html__('Categories', 'element-camp'),
                    'tags' => esc_html__('Tags', 'element-camp'),
                ],
                'default' => 'categories',
            ]
        );

        $this->add_control(
            'meta_separator',
            [
                'label' => esc_html__( 'Categories/tags Separator', 'element-camp' ),
                'type' => Controls_Manager::TEXT,
                'default' => ' / ',
            ]
        );

        $this->add_control(
            'post_type',
            [
                'label' => esc_html__('Source', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => $post_types,
                'default' => key($post_types),
            ]
        );
        $this->add_control(
            'posts_ids',
            [
                'label' => __('Search & Select', 'themescamp-plugin'),
                'type' => 'tcg-select2',
                'options' => ControlsHelper::get_post_list(),
                'label_block' => true,
                'multiple'    => true,
                'source_name' => 'post_type',
                'source_type' => 'any',
                'condition' => [
                    'post_type' => 'by_id',
                ],
            ]
        );
        $this->add_control(
            'posts_per_page',
            [
                'label' => __('Posts Per Page', 'themescamp-plugin'),
                'type' => Controls_Manager::NUMBER,
                'default' => '4',
                'min' => '1',
            ]
        );

        $this->add_control(
            'offset',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'default' => '0',
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => __('Order', 'themescamp-plugin'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'asc' => 'Ascending',
                    'desc' => 'Descending',
                ],
                'default' => 'desc',

            ]
        );
        $this->add_control(
            'orderby',
            [
                'label' => __('Order By', 'themescamp-plugin'),
                'type' => Controls_Manager::SELECT,
                'options' => ControlsHelper::get_post_orderby_options(),
                'default' => 'date',

            ]
        );

        $this->add_control(
            'authors',
            [
                'label' => __('Author', 'themescamp-plugin'),
                'label_block' => true,
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'default' => [],
                'options' => ControlsHelper::get_authors_list(),
                'condition' => [
                    'post_type!' => ['by_id', 'source_dynamic'],
                ],
            ]
        );
        $this->add_control(
            'post__not_in',
            [
                'label'       => __('Exclude', 'themescamp-plugin'),
                'type'        => 'tcg-select2',
                'label_block' => true,
                'multiple'    => true,
                'source_name' => 'post_type',
                'source_type' => 'any',
                'condition'   => [
                    'post_type!' => ['by_id', 'source_dynamic'],
                ],
            ]
        );
        foreach ($taxonomies as $taxonomy => $object) {
            if (!isset($object->object_type[0]) || !in_array($object->object_type[0], array_keys($post_types))) {
                continue;
            }

            $this->add_control(
                $taxonomy . '_ids',
                [
                    'label' => $object->label,
                    'type' => 'tcg-select2',
                    'label_block' => true,
                    'multiple' => true,
                    'source_name' => 'taxonomy',
                    'source_type' => $taxonomy,
                    'use_taxonomy_slug' => true,
                    'condition' => [
                        'post_type' => $object->object_type,
                    ],
                ]
            );
        }

        $this->add_control(
            'slider_controls',
            [
                'label' => esc_html__( 'Slider Controls', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'On', 'element-camp' ),
                'label_off' => esc_html__( 'Off', 'element-camp' ),
                'default' => 'true',
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
                'condition' => ['slider_controls' => 'true'],
            ]
        );

        $this->add_control(
            'pagination',
            [
                'label' => esc_html__( 'Pagination Controls', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'On', 'element-camp' ),
                'label_off' => esc_html__( 'Off', 'element-camp' ),
                'return_value' => 'true',
                'default' => 'false',
                'condition' => ['slider_controls' => 'true'],
            ]
        );

        $this->add_control(
            'custom_image_slider_settings',
            [
                'label' => esc_html__( 'Custom Image Slider Controls', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'On', 'element-camp' ),
                'label_off' => esc_html__( 'Off', 'element-camp' ),
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'slider_settings',
            [
                'label' => __('Slider Settings', 'element-camp'),
                'condition' => ['custom_image_slider_settings' => 'yes'],
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

        $this->end_controls_section();

        $this->start_controls_section(
            'section_slider_responsive',
            [
                'label' => __('Slider Responsive', 'element-camp'),
                'condition' => ['custom_image_slider_settings' => 'yes'],
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
            'portfolio_showcase_padding',
            [
                'label' => esc_html__('Query Gallery Text Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text ' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'portfolio_showcase_min_height',
            [
                'label' => esc_html__( 'Query Gallery Text Min Height', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
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
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text' => 'min-height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .gallery-img .bg-img' => 'height: {{SIZE}}{{UNIT}};',
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
                'size_units' => [ '%', 'px', 'vw' ],
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
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .gallery-img .bg-img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'img_background_position',
            [
                'label' => esc_html__('Background Position', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'center' => esc_html__('Center', 'element-camp'),
                    'top' => esc_html__('Top', 'element-camp'),
                    'bottom' => esc_html__('Bottom', 'element-camp'),
                    'left' => esc_html__('Left', 'element-camp'),
                    'right' => esc_html__('Right', 'element-camp'),
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .gallery-img .bg-img' => 'background-position: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .gallery-img .bg-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'image_overlay_section',
            [
                'label' => esc_html__('Image Overlay', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'image_overlay_color',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-portfolio-gallery-text .gallery-img .bg-img::before',
            ]
        );
        $this->add_responsive_control(
            'image_overlay_width',
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
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .gallery-img .bg-img::before' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_overlay_height',
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
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .gallery-img .bg-img::before' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $start = is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');
        $end = !is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');
        $this->add_control(
            'image_overlay_opacity',
            [
                'label' => esc_html__( 'Overlay Opacity', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .gallery-img .bg-img::before' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_control(
            'image_overlay_offset_orientation_h',
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
            'image_overlay_offset_x',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-portfolio-gallery-text .gallery-img .bg-img::before' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-portfolio-gallery-text .gallery-img .bg-img::before' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'image_overlay_offset_orientation_h!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_overlay_offset_x_end',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-portfolio-gallery-text .gallery-img .bg-img::before' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-portfolio-gallery-text .gallery-img .bg-img::before' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'image_overlay_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_control(
            'image_overlay_offset_orientation_v',
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
            'image_overlay_offset_y',
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
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .gallery-img .bg-img::before' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'image_overlay_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_overlay_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .gallery-img .bg-img::before' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'image_overlay_offset_orientation_v' => 'end',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'text_slider_style',
            [
                'label' => esc_html__('Text Slider Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'text_slider_width',
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
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .gallery-text' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'text_slider_height',
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
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .gallery-text' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'text_slider_controls_positioning',
            [
                'label' => esc_html__('Position', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'unset' => esc_html__('unset', 'element-camp'),
                    'absolute' => esc_html__('absolute', 'element-camp'),
                    'relative' => esc_html__('relative', 'element-camp'),
                ],
                'label_block' => true,
                'default' => 'relative',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .gallery-text' => 'position: {{VALUE}};',
                ],
            ]
        );

        $start = is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');
        $end = !is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');

        $this->add_control(
            'text_slider_controls_offset_orientation_h',
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
            'text_slider_controls_offset_x',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-portfolio-gallery-text .gallery-text' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-portfolio-gallery-text .gallery-text' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'text_slider_controls_offset_orientation_h!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'text_slider_controls_offset_x_end',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-portfolio-gallery-text .gallery-text' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-portfolio-gallery-text .gallery-text' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'text_slider_controls_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_control(
            'text_slider_controls_offset_orientation_v',
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
            'text_slider_controls_offset_y',
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
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .gallery-text' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'text_slider_controls_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'text_slider_controls_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .gallery-text' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'text_slider_controls_offset_orientation_v' => 'end',
                ],
            ]
        );
        $this->add_responsive_control(
            'text_slider_translate_y',
            [
                'label' => esc_html__( 'Translate Y', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'default' => [
                    'size' => 0,
                ],
            ]
        );
        $this->add_responsive_control(
            'text_slider_translate_x',
            [
                'label' => esc_html__( 'Translate X', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'default' => [
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .gallery-text' => 'transform: translate({{text_slider_translate_x.SIZE}}{{text_slider_translate_x.UNIT}},{{text_slider_translate_y.SIZE}}{{text_slider_translate_y.UNIT}})',
                ],
            ]
        );
        $this->add_control(
            'text_slider_z_index',
            [
                'label' => esc_html__( 'Text Slider z-index', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .gallery-text' => 'z-index: {{SIZE}};',
                ],
            ]
        );
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
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .gallery-text' => 'text-align: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .gallery-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .gallery-text .swiper-container' => 'height: {{SIZE}}{{UNIT}} !important;',
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .gallery-text .swiper-container .swiper-slide' => 'height: {{SIZE}}{{UNIT}} !important;',
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
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .gallery-text .text .title' => 'color: {{VALUE}};',
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
                'selector' => '{{WRAPPER}} .tcgelements-portfolio-gallery-text .gallery-text .text .title',
            ]
        );
        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Title Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}}  .tcgelements-portfolio-gallery-text .gallery-text .text .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'taxonomy_style_options',
            [
                'label' => esc_html__( 'Taxonomy Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'before'
            ]
        );
        $this->add_control(
            'taxonomy_color',
            [
                'label' => esc_html__( 'Taxonomy Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .gallery-text .text .taxonomy' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'taxonomy_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-portfolio-gallery-text .gallery-text .text .taxonomy',
            ]
        );
        $this->add_responsive_control(
            'taxonomy_margin',
            [
                'label' => esc_html__('Taxonomy Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .gallery-text .text .taxonomy' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'slider_controls_style',
            [
                'label' => esc_html__('Slider Controls Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'slider_controls' => 'true',
                ],
            ]
        );
        $this->add_control(
            'slider_controls_positioning',
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
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .swiper-controls' => 'position: {{VALUE}};',
                ],
            ]
        );

        $start = is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');
        $end = !is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');

        $this->add_control(
            'slider_controls_offset_orientation_h',
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
            'slider_controls_offset_x',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-portfolio-gallery-text .swiper-controls' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-portfolio-gallery-text .swiper-controls' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'slider_controls_offset_orientation_h!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_controls_offset_x_end',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-portfolio-gallery-text .swiper-controls' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-portfolio-gallery-text .swiper-controls' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'slider_controls_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_control(
            'slider_controls_offset_orientation_v',
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
            'slider_controls_offset_y',
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
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .swiper-controls' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'slider_controls_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_controls_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .swiper-controls' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'slider_controls_offset_orientation_v' => 'end',
                ],
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'pagination_buttons_style',
            [
                'label' => esc_html__('Pagination Buttons Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'pagination' => 'true',
                ],
            ]
        );
        $this->add_responsive_control(
            'pagination_buttons_display',
            [
                'label' => esc_html__('Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'block',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'none' => esc_html__('None', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .swiper-pagination' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_control(
            'pagination_buttons_positioning',
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
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .swiper-pagination' => 'position: {{VALUE}};',
                ],
            ]
        );

        $start = is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');
        $end = !is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');

        $this->add_control(
            'pagination_buttons_offset_orientation_h',
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
            'pagination_buttons_offset_x',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-portfolio-gallery-text .swiper-pagination' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-portfolio-gallery-text .swiper-pagination' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'pagination_buttons_offset_orientation_h!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_buttons_offset_x_end',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-portfolio-gallery-text .swiper-pagination' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-portfolio-gallery-text .swiper-pagination' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'pagination_buttons_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_control(
            'pagination_buttons_offset_orientation_v',
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
            'pagination_buttons_offset_y',
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
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .swiper-pagination' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'pagination_buttons_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_buttons_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .swiper-pagination' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'pagination_buttons_offset_orientation_v' => 'end',
                ],
            ]
        );
        $this->add_responsive_control(
            'pagination_buttons_translate_y',
            [
                'label' => esc_html__( 'Translate Y', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'default' => [
                    'size' => 0,
                ],
            ]
        );
        $this->add_responsive_control(
            'pagination_buttons_translate_x',
            [
                'label' => esc_html__( 'Translate X', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'default' => [
                    'unit' => '%',
                    'size' => -50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .swiper-pagination' => 'transform: translate({{pagination_buttons_translate_x.SIZE}}{{pagination_buttons_translate_x.UNIT}},{{pagination_buttons_translate_y.SIZE}}{{pagination_buttons_translate_y.UNIT}})',
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
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .swiper-controls i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .swiper-controls svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'arrows_icon_color',
            [
                'label' => esc_html__( 'Arrows Icon Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .swiper-controls i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .swiper-controls svg' => 'fill: {{VALUE}};',
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
                'selector' => '{{WRAPPER}} .tcgelements-portfolio-gallery-text .swiper-controls > *',
            ]
        );
        $this->add_control(
            'swiper_controls_color',
            [
                'label' => esc_html__( 'Swiper Controls Text Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .swiper-controls' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .swiper-controls > *' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .swiper-controls .swiper-button-next i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .swiper-controls .swiper-button-next svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $start = is_rtl() ? esc_html__('Right', 'themescamp-plugin') : esc_html__('Left', 'themescamp-plugin');
        $end = !is_rtl() ? esc_html__('Right', 'themescamp-plugin') : esc_html__('Left', 'themescamp-plugin');

        $this->add_control(
            'next_arrow_offset_orientation_h',
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
            ]
        );

        $this->add_responsive_control(
            'next_arrow_offset_x',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-portfolio-gallery-text .swiper-controls .swiper-button-next' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-portfolio-gallery-text .swiper-controls .swiper-button-next' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'next_arrow_offset_orientation_h!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'next_arrow_offset_x_end',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-portfolio-gallery-text .swiper-controls .swiper-button-next' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-portfolio-gallery-text .swiper-controls .swiper-button-next' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'next_arrow_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_control(
            'next_arrow_offset_orientation_v',
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
            ]
        );

        $this->add_responsive_control(
            'next_arrow_offset_y',
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
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .swiper-controls .swiper-button-next' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'next_arrow_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'next_arrow_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .swiper-controls .swiper-button-next' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'next_arrow_offset_orientation_v' => 'end',
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
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .swiper-controls .swiper-button-prev i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .swiper-controls .swiper-button-prev svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $start = is_rtl() ? esc_html__('Right', 'themescamp-plugin') : esc_html__('Left', 'themescamp-plugin');
        $end = !is_rtl() ? esc_html__('Right', 'themescamp-plugin') : esc_html__('Left', 'themescamp-plugin');

        $this->add_control(
            'prev_arrow_offset_orientation_h',
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
            ]
        );

        $this->add_responsive_control(
            'prev_arrow_offset_x',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-portfolio-gallery-text .swiper-controls .swiper-button-prev' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-portfolio-gallery-text .swiper-controls .swiper-button-prev' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'prev_arrow_offset_orientation_h!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'prev_arrow_offset_x_end',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-portfolio-gallery-text .swiper-controls .swiper-button-prev' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-portfolio-gallery-text .swiper-controls .swiper-button-prev' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'prev_arrow_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_control(
            'prev_arrow_offset_orientation_v',
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
            ]
        );

        $this->add_responsive_control(
            'prev_arrow_offset_y',
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
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .swiper-controls .swiper-button-prev' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'prev_arrow_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'prev_arrow_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-portfolio-gallery-text .swiper-controls .swiper-button-prev' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'prev_arrow_offset_orientation_v' => 'end',
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
        $query_settings = $this->get_settings();
        $query_settings = ControlsHelper::fix_old_query($query_settings);
        $args = ControlsHelper::get_query_args($query_settings);
        $args = ControlsHelper::get_dynamic_args($query_settings, $args);
        $query = new \WP_Query($args);
        $selected_term_type = $settings['display_terms_type'];
        //resposive setttings
        if ($settings['custom_image_slider_settings']=='yes') {
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
                'loop' => $settings['loop'],
                'centeredSlides' => $settings['centeredSlides'],
                'oneWayMovement' => $settings['oneWayMovement'],
                'keyboard' => $settings['keyboard'],
                'mousewheel' => $settings['mousewheel'],
            ];
            if ($settings['autoplay'] === 'true') {
                $slider_settings['autoplay'] = [
                    'delay' => $settings['autoplay_delay'],
                    'reverseDirection' => $settings['reverseDirection'],
                    'disableOnInteraction' => $settings['disableOnInteraction']
                ];
            }
        }
        ?>
        <div class="tcgelements-portfolio-gallery-text">
            <div class="gallery-img" <?php if ($settings['custom_image_slider_settings'] == 'yes') : ?>data-tcgelements-portfolio-gallery-image='<?php echo esc_attr(json_encode($slider_settings)); ?>' <?php endif; ?>>
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <?php if ($query->have_posts()) : while  ($query->have_posts()) : $query->the_post(); global $post ; ?>
                            <div class="swiper-slide">
                                <div class="bg-img" data-background="<?= esc_url(get_the_post_thumbnail_url()); ?>">
                                    <a href="<?= esc_url(get_the_permalink()); ?>"></a>
                                </div>
                            </div>
                        <?php endwhile;  wp_reset_postdata(); endif; ?>
                    </div>
                </div>
            </div>
            <div class="gallery-text">
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <?php if ($query->have_posts()) : while  ($query->have_posts()) : $query->the_post(); global $post ;
                            $terms_display = '';
                            if($selected_term_type == 'categories'){
                                $taxonomy_names = get_post_taxonomies();
                                if($taxonomy_names){
                                    $categories = get_the_terms($post->ID, $taxonomy_names[0]);
                                    if($categories && !is_wp_error($categories)) {
                                        $cat_names = wp_list_pluck($categories, 'name');
                                        $terms_display = implode($settings['meta_separator'], $cat_names);
                                    }
                                }
                            } elseif($selected_term_type == 'tags'){
                                $taxonomy_names = get_post_taxonomies();
                                if($taxonomy_names){
                                    $tags = get_the_terms($post->ID, $taxonomy_names[1]);
                                    if($tags && !is_wp_error($tags)) {
                                        $tag_names = wp_list_pluck($tags, 'name');
                                        $terms_display = implode($settings['meta_separator'], $tag_names);
                                    }
                                }
                            }
                        ?>
                            <div class="swiper-slide">
                                <div class="text">
                                    <h4 class="title"><?= get_the_title(); ?></h4>
                                    <h6 class="taxonomy">
                                        <span><?= esc_html($terms_display) ?></span>
                                    </h6>
                                </div>
                            </div>
                        <?php endwhile;  wp_reset_postdata(); endif; ?>
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
            <?php if ($settings['pagination'] == 'true') : ?>
                <div class="swiper-pagination"></div>
            <?php endif;?>
        </div>
        <?php
    }
}
