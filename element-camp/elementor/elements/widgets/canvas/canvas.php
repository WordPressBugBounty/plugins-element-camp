<?php

namespace ElementCampPlugin\Widgets;

if (! defined('ABSPATH')) {
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


class ElementCamp_Canvas extends Widget_Base
{

    public function get_name()
    {
        return 'tcgelements-canvas';
    }

    public function get_title()
    {
        return __('Canvas', 'element-camp');
    }

    public function get_icon()
    {
        return 'eicon-canvas';
    }

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
        return ['three.min', 'ogl-bundle.min', 'tcgelements-canvas'];
    }

    protected function _register_controls()
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'element-camp'),
            ]
        );

        $this->add_responsive_control(
            'canvas_type',
            [
                'label' => esc_html__('Canvas Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'crystal',
                'options' => [
                    'crystal' => esc_html__('Crystal', 'element-camp'),
                    'sacred-geometry' => esc_html__('Sacred Geometry', 'element-camp'),
                    'image-hover' => esc_html__('Image Hover', 'element-camp'),
                    'circles' => esc_html__('Circles', 'element-camp'),
                    'globe-dots' => esc_html__('Globe Dots', 'element-camp'),
                    'brush' => esc_html__('Brush Effect', 'element-camp'),
                    'noise' => esc_html__('Noise', 'element-camp'),
                    'blur' => esc_html__('Blur', 'element-camp'),
                    'video' => esc_html__('Video Frames', 'element-camp'),
                ],
            ]
        );

        // Add video-specific controls after the existing image controls
        $this->add_control(
            'video_frames_heading',
            [
                'label' => esc_html__('Video Frame Settings', 'element-camp'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'canvas_type' => 'video',
                ],
            ]
        );

        $this->add_control(
            'video_frame_count',
            [
                'label' => __('Total Frames', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'default' => 100,
                'min' => 10,
                'max' => 500,
                'step' => 1,
                'description' => __('Total number of frame images in your sequence', 'element-camp'),
                'condition' => [
                    'canvas_type' => 'video',
                ],
            ]
        );

        $this->add_control(
            'video_frame_prefix',
            [
                'label' => __('Frame File Prefix', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'default' => 'frame_',
                'description' => __('Prefix for your frame files (e.g., "frame_" for frame_0001.jpg)', 'element-camp'),
                'condition' => [
                    'canvas_type' => 'video',
                ],
            ]
        );

        $this->add_control(
            'video_frame_extension',
            [
                'label' => __('File Extension', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'jpg',
                'options' => [
                    'jpg' => 'JPG',
                    'jpeg' => 'JPEG',
                    'png' => 'PNG',
                    'webp' => 'WebP',
                ],
                'condition' => [
                    'canvas_type' => 'video',
                ],
            ]
        );

        $this->add_control(
            'video_frames_folder',
            [
                'label' => __('Frames Folder URL', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __('https://yoursite.com/wp-content/uploads/frames/', 'element-camp'),
                'description' => __('Full URL path to the folder containing your frame images', 'element-camp'),
                'condition' => [
                    'canvas_type' => 'video',
                ],
            ]
        );

        $this->add_control(
            'video_background_color',
            [
                'label' => __('Background Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '#F0F2EF',
                'description' => __('Canvas background color shown before frames load', 'element-camp'),
                'condition' => [
                    'canvas_type' => 'video',
                ],
            ]
        );

        $this->add_control(
            'video_scroll_speed',
            [
                'label' => __('Scroll Speed', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1000,
                ],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 5000,
                        'step' => 50,
                    ],
                ],
                'description' => __('Pixels to scroll for complete animation (lower = faster)', 'element-camp'),
                'condition' => [
                    'canvas_type' => 'video',
                ],
            ]
        );

        $this->add_control(
            'video_animation_ease',
            [
                'label' => __('Animation Smoothness', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0.3,
                ],
                'range' => [
                    'px' => [
                        'min' => 0.1,
                        'max' => 1,
                        'step' => 0.05,
                    ],
                ],
                'description' => __('Frame transition smoothness (higher = smoother)', 'element-camp'),
                'condition' => [
                    'canvas_type' => 'video',
                ],
            ]
        );

        $this->add_control(
            'video_scale',
            [
                'label' => __('Image Scale', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1.02,
                ],
                'range' => [
                    'px' => [
                        'min' => 0.8,
                        'max' => 2,
                        'step' => 0.01,
                    ],
                ],
                'description' => __('Scale factor for frame images (1 = original size)', 'element-camp'),
                'condition' => [
                    'canvas_type' => 'video',
                ],
            ]
        );

        $this->add_control(
            'brush_overlay_image',
            [
                'label' => __('Overlay Image', 'element-camp'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'description' => __('Image that will be revealed when brushing', 'element-camp'),
                'condition' => [
                    'canvas_type' => 'brush',
                ],
            ]
        );

        $this->add_control(
            'image_hover_image',
            [
                'label' => __('Image', 'element-camp'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'canvas_type' => 'image-hover',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'noise_background',
                'selector' => '{{WRAPPER}} .tcgelements-canvas .noise-background',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'condition' => [
                    'canvas_type' => 'noise',
                ],
            ]
        );

        $this->end_controls_section();

        // Animation Settings Section
        $this->start_controls_section(
            'section_animation_settings',
            [
                'label' => __('Animation Settings', 'element-camp'),
                'condition' => [
                    'canvas_type' => 'circles',
                ],
            ]
        );

        $this->add_control(
            'circles_count',
            [
                'label' => __('Number of Circles', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => '4',
                'options' => [
                    '1' => __('1 Circle', 'element-camp'),
                    '2' => __('2 Circles', 'element-camp'),
                    '3' => __('3 Circles', 'element-camp'),
                    '4' => __('4 Circles', 'element-camp'),
                    '5' => __('5 Circles', 'element-camp'),
                ],
                'description' => __('Select the number of animated circles.', 'element-camp'),
            ]
        );

        $this->add_control(
            'animation_speed_multiplier',
            [
                'label' => __('Speed Multiplier', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'min' => 0.1,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                ],
                'description' => __('Controls how fast the circles move.', 'element-camp'),
            ]
        );

        $this->add_control(
            'boundary_multiplier',
            [
                'label' => __('Boundary Multiplier', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 2,
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 8,
                        'step' => 0.5,
                    ],
                ],
                'description' => __('Controls bounce boundaries', 'element-camp'),
            ]
        );

        $this->add_control(
            'base_speed',
            [
                'label' => __('Base Speed', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 4,
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 10,
                        'step' => 0.5,
                    ],
                ],
                'description' => __('Base velocity for circles movement.', 'element-camp'),
            ]
        );

        $this->add_control(
            'animation_direction',
            [
                'label' => __('Animation Direction', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'both',
                'options' => [
                    'both' => __('Both (X & Y)', 'element-camp'),
                    'horizontal' => __('Horizontal Only', 'element-camp'),
                ],
                'description' => __('Controls the direction of circle movement.', 'element-camp'),
            ]
        );

        $this->add_control(
            'keep_original_y',
            [
                'label' => __('Keep Original Y Position', 'element-camp'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'element-camp'),
                'label_off' => __('No', 'element-camp'),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'animation_direction' => 'horizontal',
                ],
                'description' => __('Keep circles at their CSS Y position when using horizontal-only animation.', 'element-camp'),
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'section_video_style',
            [
                'label' => __('Video Canvas Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'canvas_type' => 'video',
                ],
            ]
        );


        $this->add_control(
            'video_canvas_overflow',
            [
                'label' => esc_html__( 'Video Container Overflow Hidden', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'yes', 'element-camp' ),
                'label_off' => esc_html__( 'No', 'element-camp' ),
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-canvas .video-canvas-container' => 'overflow: hidden;',
                ],
            ]
        );

        $this->add_responsive_control(
            'video_canvas_border_radius',
            [
                'label' => esc_html__('Video Container Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-canvas .video-canvas-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'video_loading_indicator',
            [
                'label' => esc_html__('Show Loading Indicator', 'element-camp'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => __('Show loading indicator while frames are loading', 'element-camp'),
            ]
        );

        $this->add_control(
            'video_loading_color',
            [
                'label' => __('Loading Indicator Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'condition' => [
                    'video_loading_indicator' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-canvas .video-loading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_canvas',
            [
                'label' => __('Canvas Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'canvas_positioning',
            [
                'label' => esc_html__('Position', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'unset' => esc_html__('unset', 'element-camp'),
                    'absolute' => esc_html__('absolute', 'element-camp'),
                    'fixed' => esc_html__('Fixed', 'element-camp'),
                    'relative' => esc_html__('relative', 'element-camp'),
                ],
                'label_block' => true,
                'default' => 'absolute',
                'selectors' => [
                    '{{WRAPPER}}' => 'position: {{VALUE}};',
                ],
            ]
        );

        $start = is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');
        $end = !is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');

        $this->add_control(
            'canvas_offset_orientation_h',
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
            'canvas_offset_x',
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
                'default' => [
                    'size' => 0,
                    'unit' => 'px',
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh', 'custom'],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}}' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}}' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'canvas_offset_orientation_h!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'canvas_offset_x_end',
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
                    'body:not(.rtl) {{WRAPPER}}' => 'right: {{SIZE}}{{UNIT}};',
                    'body.rtl {{WRAPPER}}' => 'left: {{SIZE}}{{UNIT}};'
                ],
                'condition' => [
                    'canvas_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_control(
            'canvas_offset_orientation_v',
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
            'canvas_offset_y',
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
                    '{{WRAPPER}}' => 'top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'canvas_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'canvas_offset_y_end',
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
                    '{{WRAPPER}}' => 'bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'canvas_offset_orientation_v' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'canvas_height',
            [
                'label' => __('Height', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 80,
                    'unit' => '%',
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}}' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'canvas_max_width',
            [
                'label' => __('Max Width', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}}' => 'max-width: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'canvas_width',
            [
                'label' => __('Width', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 100,
                    'unit' => '%',
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}}' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'canvas_height_same_as_width',
            [
                'label' => esc_html__( 'Height Same as Width', 'element-camp' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'element-camp' ),
                'label_off' => esc_html__( 'No', 'element-camp' ),
                'return_value' => 'yes',
                'default' => 'no',
                'description' => esc_html__( 'Make canvas height equal to its width (square aspect ratio). Note: This will override the Height controller above.', 'element-camp' ),
                'prefix_class' => 'canvas-h-w-',
            ]
        );

        $this->add_control(
            'canvas_opacity',
            [
                'label' => __('Opacity', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0.5,
                ],
                'range' => [
                    'min' => 0,
                    'max' => 1,
                    'step' => 0.01,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-canvas .crystal-container' => 'opacity: {{SIZE}};',
                    '{{WRAPPER}} .tcgelements-canvas .circles' => 'opacity: {{SIZE}};',
                    '{{WRAPPER}} .tcgelements-canvas .globe-dots' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_control(
            'canvas_z_index',
            [
                'label' => esc_html__('Z-index', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}}' => 'z-index: {{SIZE}};',
                ],
            ]
        );

        $this->add_control(
            'canvas_pointer_events',
            [
                'label' => esc_html__('Pointer Events', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'auto' => esc_html__('Auto', 'element-camp'),
                    'none' => esc_html__('None', 'element-camp'),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}' => 'pointer-events:{{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_circles_style',
            [
                'label' => __('Circles Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'canvas_type' => 'circles',
                ],
            ]
        );
        $this->add_control(
            "circles_common_settings_heading",
            [
                'label' => esc_html__('Common Circles', 'element-camp'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'circles_blur_method',
            [
                'label' => esc_html__('Blur Method', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'backdrop-filter' => 'backdrop-filter',
                    'filter' => 'filter',
                ],
                'default' => 'backdrop-filter',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-canvas .circles > *' => '{{VALUE}}: blur({{circles_blur_value.SIZE}}px);',
                ],
            ]
        );
        $this->add_control(
            'circles_blur_value',
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
        // Generate controls for circles 1-5
        for ($i = 1; $i <= 5; $i++) {
            // Define which circle counts show this circle
            $show_for_counts = [];
            for ($j = $i; $j <= 5; $j++) {
                $show_for_counts[] = (string)$j;
            }

            $this->add_control(
                "circle{$i}_heading",
                [
                    'label' => sprintf(esc_html__('Circle %d', 'element-camp'), $i),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'circles_count' => $show_for_counts,
                    ],
                ]
            );

            $this->add_responsive_control(
                "circle{$i}_width",
                [
                    'label' => esc_html__('Width', 'element-camp'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh'],
                    'range' => [
                        'px' => ['min' => 10, 'max' => 500],
                        '%' => ['min' => 1, 'max' => 100],
                    ],
                    'selectors' => [
                        "{{WRAPPER}} .tcgelements-canvas .circles .circle{$i}" => 'width: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'circles_count' => $show_for_counts,
                    ],
                ]
            );

            $this->add_responsive_control(
                "circle{$i}_height",
                [
                    'label' => esc_html__('Height', 'element-camp'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh'],
                    'range' => [
                        'px' => ['min' => 10, 'max' => 500],
                        '%' => ['min' => 1, 'max' => 100],
                    ],
                    'selectors' => [
                        "{{WRAPPER}} .tcgelements-canvas .circles .circle{$i}" => 'height: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'circles_count' => $show_for_counts,
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => "circle{$i}_background_color",
                    'types' => ['classic', 'gradient', 'tcg_gradient'],
                    'selector' => "{{WRAPPER}} .tcgelements-canvas .circles .circle{$i}",
                    'condition' => [
                        'circles_count' => $show_for_counts,
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => "circle{$i}_border",
                    'selector' => "{{WRAPPER}} .tcgelements-canvas .circles .circle{$i}",
                    'condition' => [
                        'circles_count' => $show_for_counts,
                    ],
                ]
            );

            $this->add_responsive_control(
                "circle{$i}_border_radius",
                [
                    'label' => esc_html__('Border Radius', 'element-camp'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'em', 'rem'],
                    'selectors' => [
                        "{{WRAPPER}} .tcgelements-canvas .circles .circle{$i}" => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'circles_count' => $show_for_counts,
                    ],
                ]
            );

            $this->add_control(
                "circle{$i}_opacity",
                [
                    'label' => __('Opacity', 'element-camp'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.01,
                    ],
                    'selectors' => [
                        "{{WRAPPER}} .tcgelements-canvas .circles .circle{$i}" => 'opacity: {{SIZE}};',
                    ],
                    'condition' => [
                        'circles_count' => $show_for_counts,
                    ],
                ]
            );
        } // End of for loop

        $this->end_controls_section();

        // Image Hover Style Section
        $this->start_controls_section(
            'section_image_hover_style',
            [
                'label' => __('Image Hover Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'canvas_type' => 'image-hover',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_hover_width',
            [
                'label' => __('Width', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 100,
                    'unit' => '%',
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'size_units' => ['px', '%', 'vw'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-canvas .image-hover-container' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_hover_height',
            [
                'label' => __('Height', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 400,
                    'unit' => 'px',
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'size_units' => ['px', '%', 'vh'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-canvas .image-hover-container' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_blur_style',
            [
                'label' => __('Blur Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'canvas_type' => 'blur',
                ],
            ]
        );
        $this->add_control(
            'canvas_blur_method',
            [
                'label' => esc_html__('Blur Method', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'backdrop-filter' => 'backdrop-filter',
                    'filter' => 'filter',
                ],
                'default' => 'backdrop-filter',
                'selectors' => [
                    '{{WRAPPER}}' => '{{VALUE}}: blur({{canvas_blur_value.SIZE}}px);',
                ],
            ]
        );
        $this->add_control(
            'canvas_blur_value',
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
            'mask_color_heading',
            [
                'label' => esc_html__('Mask Color', 'element-camp'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'mask_color1',
            [
                'label' => _x('First Color', 'Background Control', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'title' => _x('First Color', 'Background Control', 'element-camp'),
                'render_type' => 'ui',
            ]
        );


        $this->add_control(
            'mask_color1_stop',
            [
                'label' => _x('Location', 'Background Control', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'default' => [
                    'unit' => '%',
                    'size' => 0,
                ],
                'render_type' => 'ui',
            ]
        );

        $this->add_control(
            'mask_color2',
            [
                'label' => _x('Second Color', 'Background Control', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f2295b',
                'render_type' => 'ui',
            ]
        );

        $this->add_control(
            'mask_color2_stop',
            [
                'label' => _x('Location', 'Background Control', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'render_type' => 'ui',
            ]
        );

        $this->add_control(
            'mask_color_angle',
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
                    '{{WRAPPER}}' => 'mask-image: linear-gradient({{SIZE}}{{UNIT}}, {{mask_color1.VALUE}} {{mask_color1_stop.SIZE}}{{mask_color1_stop.UNIT}},{{mask_color2.VALUE}} {{mask_color2_stop.SIZE}}{{mask_color2_stop.UNIT}}) {{mask_composite.VALUE}}; -webkit-mask: linear-gradient({{SIZE}}{{UNIT}}, {{mask_color1.VALUE}} {{mask_color1_stop.SIZE}}{{mask_color1_stop.UNIT}},{{mask_color2.VALUE}} {{mask_color2_stop.SIZE}}{{mask_color2_stop.UNIT}}) {{mask_composite.VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'mask_composite',
            [
                'label' => esc_html__('Mask Composite', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'add' => 'add',
                    'subtract' => 'subtract',
                    'intersect' => 'intersect',
                    'exclude' => 'exclude',
                ],
                'default' => 'add',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_globe_dots_style',
            [
                'label' => __('Globe Dots Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'canvas_type' => 'globe-dots',
                ],
            ]
        );

        $this->add_control(
            'globe_dots_color',
            [
                'label' => __('Dots Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '#2a51e6',
                'description' => __('Main color of the dots', 'element-camp'),
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'section_sacred_geometry_style',
            [
                'label' => __('Sacred Geometry Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'canvas_type' => 'sacred-geometry',
                ],
            ]
        );

        $this->add_responsive_control(
            'sacred_geometry_canvas_height',
            [
                'label' => __('Height', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-canvas' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'sacred_geometry_canvas_width',
            [
                'label' => __('Width', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-canvas' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'sacred_geometry_color1',
            [
                'label' => __('Primary Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '#00ccff',
                'description' => __('Primary gradient color for vertices and edges', 'element-camp'),
            ]
        );

        $this->add_control(
            'sacred_geometry_color2',
            [
                'label' => __('Secondary Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '#8000ff',
                'description' => __('Secondary gradient color for vertices and edges', 'element-camp'),
            ]
        );

        $this->add_control(
            'canvas_filter_brightness',
            [
                'label' => __('Filter Brightness', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%', 'custom'],
                'range' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 5,
                ],
                'default' => [
                    'unit' => '%'
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-canvas .sacred-geometry-container' => 'filter: brightness({{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'section_brush_style',
            [
                'label' => __('Brush Effect Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'canvas_type' => 'brush',
                ],
            ]
        );

        $this->add_control(
            'brush_radius',
            [
                'label' => __('Brush Size', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 80,
                ],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 200,
                        'step' => 5,
                    ],
                ],
                'description' => __('Size of the brush for the reveal effect', 'element-camp'),
            ]
        );

        $this->add_control(
            'brush_feather',
            [
                'label' => __('Brush Feather', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'min' => 0.1,
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'description' => __('Feathering/softness of the brush edges', 'element-camp'),
            ]
        );

        $this->add_control(
            'brush_lerp',
            [
                'label' => __('Smoothness', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0.22,
                ],
                'range' => [
                    'px' => [
                        'min' => 0.05,
                        'max' => 1,
                        'step' => 0.01,
                    ],
                ],
                'description' => __('How smoothly the brush follows the cursor', 'element-camp'),
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        // Prepare animation data attributes for all canvas types
        $animation_data = '';
        if ($settings['canvas_type'] === 'circles') {
            $speed_multiplier = $settings['animation_speed_multiplier']['size'] ?? 1;
            $boundary_multiplier = $settings['boundary_multiplier']['size'] ?? 2;
            $base_speed = $settings['base_speed']['size'] ?? 4;
            $circles_count = (int)$settings['circles_count'];

            $animation_direction = $settings['animation_direction'] ?? 'both';
            $keep_original_y = $settings['keep_original_y'] === 'yes' ? 'true' : 'false';

            $animation_data = sprintf(
                'data-speed-multiplier="%s" data-boundary-multiplier="%s" data-base-speed="%s" data-circles-count="%s" data-animation-direction="%s" data-keep-original-y="%s"',
                esc_attr($speed_multiplier),
                esc_attr($boundary_multiplier),
                esc_attr($base_speed),
                esc_attr($circles_count),
                esc_attr($animation_direction),
                esc_attr($keep_original_y)
            );
        } elseif ($settings['canvas_type'] === 'sacred-geometry') {
            // Sacred Geometry data attributes
            $color1 = $settings['sacred_geometry_color1'] ?: '#00ccff';
            $color2 = $settings['sacred_geometry_color2'] ?: '#8000ff';

            $animation_data = sprintf(
                'data-sg-color1="%s" data-sg-color2="%s"',
                esc_attr($color1),
                esc_attr($color2),
            );
        } elseif ($settings['canvas_type'] === 'brush') {
            // Brush effect data attributes
            $brush_radius = $settings['brush_radius']['size'] ?? 80;
            $brush_feather = $settings['brush_feather']['size'] ?? 1;
            $brush_lerp = $settings['brush_lerp']['size'] ?? 0.22;
            $overlay_url = $settings['brush_overlay_image']['url'] ?? '';

            $animation_data = sprintf(
                'data-brush-radius="%s" data-brush-feather="%s" data-brush-lerp="%s" data-overlay="%s"',
                esc_attr($brush_radius),
                esc_attr($brush_feather),
                esc_attr($brush_lerp),
                esc_url($overlay_url)
            );
        }
        ?>
        <div class="tcgelements-canvas" <?php echo $animation_data; ?>>
            <?php switch ($settings['canvas_type']) {
                case 'crystal':
                    ?>
                    <div class="crystal-container"></div>
                    <?php
                break;
                case 'sacred-geometry':
                    ?>
                    <div class="sacred-geometry-container"></div>
                    <?php
                break;
                case 'image-hover':
                    ?>
                    <div class="image-hover-container" data-image="<?= esc_url($settings['image_hover_image']['url']) ?>"></div>
                    <?php
                break;
                case 'circles':
                    $circles_count = (int)$settings['circles_count'];
                    ?>
                    <div class="circles">
                        <?php for ($i = 1; $i <= $circles_count; $i++): ?>
                            <span class="circle<?php echo $i; ?>"></span>
                        <?php endfor; ?>
                    </div>
                    <?php
                break;
                case 'globe-dots':
                    // Get color settings
                    $dots_color = $settings['globe_dots_color'] ?: '#2a51e6';
                    $dots_rgb = $this->hex_to_rgb($dots_color);
                    ?>
                    <div class="globe-dots" data-dots-color="<?php echo esc_attr($dots_rgb); ?>"></div>

                    <?php
                break;
                case 'noise':
                    ?>
                    <div class="noise-background"></div>
                    <?php
                break;
                case 'brush':
                    ?>
                    <div class="brush-container">
                        <canvas class="maskCanvas" data-overlay="<?= esc_url($settings['brush_overlay_image']['url']) ?>"></canvas>
                    </div>
                    <?php
                break;
                case 'video':
                    // Video canvas data attributes
                    $frame_count = $settings['video_frame_count'] ?? 100;
                    $frame_prefix = $settings['video_frame_prefix'] ?? 'frame_';
                    $frame_extension = $settings['video_frame_extension'] ?? 'jpg';
                    $frames_folder = $settings['video_frames_folder'] ?? '';

                    $background_color = $settings['video_background_color'] ?? '#F0F2EF';
                    $scroll_speed = $settings['video_scroll_speed']['size'] ?? 1000;
                    $animation_ease = $settings['video_animation_ease']['size'] ?? 0.3;
                    $image_scale = $settings['video_scale']['size'] ?? 1.02;
                    $canvas_width = $settings['video_canvas_width']['size'] ?? 630;
                    $canvas_height = $settings['video_canvas_height']['size'] ?? 730;
                    $show_loading = $settings['video_loading_indicator'] === 'yes';

                    $video_animation_data = sprintf(
                        'data-frame-count="%s" data-frame-prefix="%s" data-frame-extension="%s" data-frames-folder="%s" data-bg-color="%s" data-scroll-speed="%s" data-animation-ease="%s" data-image-scale="%s" data-canvas-width="%s" data-canvas-height="%s" data-show-loading="%s"',
                        esc_attr($frame_count),
                        esc_attr($frame_prefix),
                        esc_attr($frame_extension),
                        esc_url($frames_folder),
                        esc_attr($background_color),
                        esc_attr($scroll_speed),
                        esc_attr($animation_ease),
                        esc_attr($image_scale),
                        esc_attr($canvas_width),
                        esc_attr($canvas_height),
                        $show_loading ? 'true' : 'false'
                    );

                    // Use the video animation data directly on the container
                    ?>
                    <div class="video-canvas-container" <?php echo $video_animation_data; ?>>
                        <?php if ($show_loading): ?>
                            <div class="video-loading"><?php echo esc_html__('Loading frames...', 'element-camp'); ?></div>
                        <?php endif; ?>
                        <canvas class="video-canvas"></canvas>
                    </div>
                    <?php
                    break;
            } ?>
        </div>
        <?php
    }
    /**
     * Convert hex color to RGB string for JavaScript
     */
    private function hex_to_rgb($hex) {
        $hex = ltrim($hex, '#');

        if (strlen($hex) === 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }

        return "$r,$g,$b";
    }
}
