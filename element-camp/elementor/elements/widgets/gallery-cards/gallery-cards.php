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
use Elementor\Repeater;
use ElementCampPlugin\Elementor\Controls\TCG_Helper as ControlsHelper;

if (!defined('ABSPATH')) exit;

class ElementCamp_Gallery_Cards extends Widget_Base
{
    public function get_name()
    {
        return 'tcgelements-gallery-cards';
    }

    public function get_title()
    {
        return esc_html__('Gallery Cards', 'element-camp');
    }

    public function get_icon()
    {
        return 'eicon-gallery-grid tce-widget-badge';
    }

    public function get_categories()
    {
        return ['elementcamp-elements'];
    }

    public function get_script_depends() {
        return ['gsap', 'gsap-scrolltrigger', 'tcgelements-gallery-cards'];
    }

    public function get_style_depends() {
        return ['tcgelements-gallery-cards'];
    }

    protected function register_controls() {

        $start = is_rtl() ? esc_html__('Right', 'themescamp-plugin') : esc_html__('Left', 'themescamp-plugin');
        $end = !is_rtl() ? esc_html__('Right', 'themescamp-plugin') : esc_html__('Left', 'themescamp-plugin');

        $this->start_controls_section(
            'section_content_source',
            [
                'label' => esc_html__('Content Source', 'element-camp'),
            ]
        );

        $this->add_control(
            'content_source',
            [
                'label' => esc_html__('Source', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'repeater' => esc_html__('Manual (Repeater)', 'element-camp'),
                    'post_type' => esc_html__('Post Type', 'element-camp'),
                ],
                'default' => 'repeater',
            ]
        );

        $this->end_controls_section();

        // Images Section
        $this->start_controls_section(
            'section_images',
            [
                'label' => esc_html__('Images', 'element-camp'),
                'condition' => [
                    'content_source' => 'repeater',
                ],
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
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

        $repeater->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Project Title', 'element-camp'),
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $repeater->add_control(
            'category',
            [
                'label' => esc_html__('Category', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Branding', 'element-camp'),
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => esc_html__('Link', 'element-camp'),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__('https://your-link.com', 'element-camp'),
            ]
        );

        $this->add_control(
            'images',
            [
                'label' => esc_html__('Images', 'element-camp'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'image' => ['url' => Utils::get_placeholder_image_src()],
                        'title' => esc_html__('Unerio Residential', 'element-camp'),
                        'category' => esc_html__('Branding', 'element-camp'),
                    ],
                    [
                        'image' => ['url' => Utils::get_placeholder_image_src()],
                        'title' => esc_html__('Jorger Clarkson', 'element-camp'),
                        'category' => esc_html__('Digital', 'element-camp'),
                    ],
                    [
                        'image' => ['url' => Utils::get_placeholder_image_src()],
                        'title' => esc_html__('Festonax Card', 'element-camp'),
                        'category' => esc_html__('Web Design', 'element-camp'),
                    ],
                    [
                        'image' => ['url' => Utils::get_placeholder_image_src()],
                        'title' => esc_html__('Jimmy Fermin', 'element-camp'),
                        'category' => esc_html__('Development', 'element-camp'),
                    ],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->end_controls_section();

        // Post Type Query Section
        $post_types = ControlsHelper::get_post_types();
        $post_types['by_id'] = __('Manual Selection', 'themescamp-plugin');
        $taxonomies = get_taxonomies([], 'objects');
        $this->start_controls_section(
            'section_post_query',
            [
                'label' => esc_html__('Query', 'element-camp'),
                'condition' => [
                    'content_source' => 'post_type',
                ],
            ]
        );

        $this->add_control(
            'post_type',
            [
                'label' => esc_html__('Post Type', 'element-camp'),
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
                'multiple' => true,
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
                    'post_type!' => ['by_id'],
                ],
            ]
        );

        $this->add_control(
            'post__not_in',
            [
                'label' => __('Exclude', 'themescamp-plugin'),
                'type' => 'tcg-select2',
                'label_block' => true,
                'multiple' => true,
                'source_name' => 'post_type',
                'source_type' => 'any',
                'condition' => [
                    'post_type!' => ['by_id'],
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
            'display_category',
            [
                'label' => esc_html__('Display Category', 'element-camp'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Animation Settings Section
        $this->start_controls_section(
            'section_animation_settings',
            [
                'label' => esc_html__('Animation Settings', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'initial_width',
            [
                'label' => esc_html__('Initial Width', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 200,
                        'step' => 5,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 80,
                ],
            ]
        );

        $this->add_responsive_control(
            'initial_height',
            [
                'label' => esc_html__('Initial Height', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 600,
                        'step' => 10,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 350,
                ],
            ]
        );

        $this->add_responsive_control(
            'wrapper_height',
            [
                'label' => esc_html__('Wrapper Min Height', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 600,
                        'step' => 10,
                    ],
                ],
                'selectors' => [
                    '{{wrapper}} .tcgelements-gallery-cards .cards-wrapper-desktop' => 'min-height: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'final_width',
            [
                'label' => esc_html__('Final Width', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 500,
                        'step' => 10,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 320,
                ],
            ]
        );

        $this->add_responsive_control(
            'final_height',
            [
                'label' => esc_html__('Final Height', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 300,
                        'max' => 700,
                        'step' => 10,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 450,
                ],
            ]
        );

        $this->add_responsive_control(
            'move_y',
            [
                'label' => esc_html__('Move Down (Y)', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 10,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 500,
                ],
            ]
        );

        $this->add_responsive_control(
            'move_x',
            [
                'label' => esc_html__('Move Left (X)', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -1500,
                        'max' => 0,
                        'step' => 10,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => -900,
                ],
            ]
        );

        $this->add_control(
            'stagger',
            [
                'label' => esc_html__('Stagger Delay', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.05,
                    ],
                ],
                'default' => [
                    'size' => 0.1,
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Cards
        $this->start_controls_section(
            'section_style_cards',
            [
                'label' => esc_html__('Cards', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'card_spacing',
            [
                'label' => esc_html__('Card Spacing', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}} .animated-image-card' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'initial_border_radius',
            [
                'label' => esc_html__('Initial Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => '60',
                    'right' => '60',
                    'bottom' => '60',
                    'left' => '60',
                    'unit' => 'px',
                ],
            ]
        );

        $this->add_control(
            'final_border_radius',
            [
                'label' => esc_html__('Final Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => '15',
                    'right' => '15',
                    'bottom' => '15',
                    'left' => '15',
                    'unit' => 'px',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_positioning_settings',
            [
                'label' => esc_html__('Positioning', 'element-camp'),
            ]
        );

        $this->add_responsive_control(
            'gallery_positioning',
            [
                'label' => esc_html__('Position', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'absolute' => esc_html__('Absolute', 'element-camp'),
                    'relative' => esc_html__('Relative', 'element-camp'),
                ],
                'label_block' => true,
                'default' => 'absolute',
                'selectors' => [
                    '{{WRAPPER}} .animated-images-container' => 'position: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'initial_col_width',
            [
                'label' => esc_html__('Initial Container Width', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'col-lg-3' => esc_html__('25% (col-lg-3)', 'element-camp'),
                    'col-lg-4' => esc_html__('33% (col-lg-4)', 'element-camp'),
                    'col-lg-5' => esc_html__('42% (col-lg-5)', 'element-camp'),
                    'col-lg-6' => esc_html__('50% (col-lg-6)', 'element-camp'),
                ],
                'default' => 'col-lg-4',
            ]
        );

        $this->add_control(
            'gallery_positioning_offset_orientation_h',
            [
                'label' => esc_html__('Horizontal Orientation', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'end',
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
            'gallery_positioning_offset_x',
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
                ],
                'size_units' => ['px', '%', 'vw'],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}} .animated-images-container' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .animated-images-container' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'gallery_positioning_offset_orientation_h!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'gallery_positioning_offset_x_end',
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
                ],
                'size_units' => ['px', '%', 'vw'],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}} .animated-images-container' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .animated-images-container' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'gallery_positioning_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_control(
            'gallery_positioning_offset_orientation_v',
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
            'gallery_positioning_offset_y',
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
                ],
                'size_units' => ['px', '%', 'vh'],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .animated-images-container' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'gallery_positioning_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'gallery_positioning_offset_y_end',
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
                ],
                'size_units' => ['px', '%', 'vh'],
                'selectors' => [
                    '{{WRAPPER}} .animated-images-container' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'gallery_positioning_offset_orientation_v' => 'end',
                ],
            ]
        );

        $this->end_controls_section();
        // Style Section - Info Overlay
        $this->start_controls_section(
            'section_style_info',
            [
                'label' => esc_html__('Info Overlay', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'info_background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .card-info',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'color' => [
                        'default' => 'rgba(0, 0, 0, 0.1)',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'info_padding',
            [
                'label' => esc_html__('Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'top' => '20',
                    'right' => '30',
                    'bottom' => '20',
                    'left' => '30',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .card-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'info_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => '10',
                    'right' => '10',
                    'bottom' => '10',
                    'left' => '10',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .card-info' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Title
        $this->start_controls_section(
            'section_style_title',
            [
                'label' => esc_html__('Title', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .card-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .card-title',
            ]
        );

        $this->end_controls_section();

        // Style Section - Category
        $this->start_controls_section(
            'section_style_category',
            [
                'label' => esc_html__('Category', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'category_color',
            [
                'label' => esc_html__('Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .card-category' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'category_typography',
                'selector' => '{{WRAPPER}} .card-category',
            ]
        );

        $this->add_control(
            'category_opacity',
            [
                'label' => esc_html__('Opacity', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 0.7,
                ],
                'selectors' => [
                    '{{WRAPPER}} .card-category' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        // Get images based on content source
        if ($settings['content_source'] === 'repeater') {
            $images = $settings['images'];
        } else {
            // Query posts
            $query_settings = $this->get_settings();
            $query_settings = ControlsHelper::fix_old_query($query_settings);
            $args = ControlsHelper::get_query_args($query_settings);
            $args = ControlsHelper::get_dynamic_args($query_settings, $args);
            $query = new \WP_Query($args);

            // Convert posts to images array format
            $images = [];
            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    global $post;

                    $item = [
                        'image' => [
                            'url' => get_the_post_thumbnail_url($post->ID, 'full'),
                        ],
                        'title' => get_the_title(),
                        'link' => [
                            'url' => get_permalink(),
                        ],
                    ];

                    // Get category if enabled
                    if ($settings['display_category'] === 'yes') {
                        $taxonomy_names = get_post_taxonomies($post->ID);
                        if (!empty($taxonomy_names)) {
                            $terms = get_the_terms($post->ID, $taxonomy_names[0]);
                            if ($terms && !is_wp_error($terms)) {
                                $item['category'] = $terms[0]->name;
                            }
                        }
                    }

                    $images[] = $item;
                }
                wp_reset_postdata();
            }
        }

        if (empty($images)) {
            return;
        }
        ?>
        <div class="tcgelements-gallery-cards"
             data-initial-width="<?php echo esc_attr($settings['initial_width']['size']); ?>"
             data-initial-height="<?php echo esc_attr($settings['initial_height']['size']); ?>"
             data-final-width="<?php echo esc_attr($settings['final_width']['size']); ?>"
             data-final-height="<?php echo esc_attr($settings['final_height']['size']); ?>"
             data-move-y="<?php echo esc_attr($settings['move_y']['size']); ?>"
             data-move-x="<?php echo esc_attr($settings['move_x']['size']); ?>"
             data-stagger="<?php echo esc_attr($settings['stagger']['size']); ?>">

            <!-- Mobile/Tablet Grid View -->
            <div class="cards-wrapper-mobile d-block d-lg-none">
                <div class="row">
                    <?php foreach ($images as $index => $item): ?>
                        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                            <div class="image-card-item">
                                <?php if (!empty($item['link']['url'])):
                                $link_key = 'link_' . $index;
                                $this->add_link_attributes($link_key, $item['link']);
                                ?>
                                <a <?php echo $this->get_render_attribute_string($link_key); ?>>
                                    <?php endif; ?>

                                    <div class="image-card-inner">
                                        <?php if (!empty($item['image']['url'])): ?>
                                            <img src="<?php echo esc_url($item['image']['url']); ?>"
                                                 alt="<?php echo esc_attr($item['title']); ?>">
                                        <?php endif; ?>

                                        <div class="card-info">
                                            <?php if (!empty($item['title'])): ?>
                                                <h6 class="card-title"><?php echo esc_html($item['title']); ?></h6>
                                            <?php endif; ?>

                                            <?php if (!empty($item['category'])): ?>
                                                <span class="card-category"><?php echo esc_html($item['category']); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <?php if (!empty($item['link']['url'])): ?>
                                </a>
                            <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Desktop Animated View -->
            <div class="cards-wrapper-desktop section-target-desktop d-none d-lg-block">
                <div class="animated-images-container">
                    <?php foreach ($images as $index => $item): ?>
                        <div class="animated-image-card" data-index="<?php echo esc_attr($index); ?>">
                            <?php if (!empty($item['link']['url'])):
                            $link_key = 'desktop_link_' . $index;
                            $this->add_link_attributes($link_key, $item['link']);
                            ?>
                            <a <?php echo $this->get_render_attribute_string($link_key); ?>>
                                <?php endif; ?>

                                <?php if (!empty($item['image']['url'])): ?>
                                    <img src="<?php echo esc_url($item['image']['url']); ?>"
                                         alt="<?php echo esc_attr($item['title']); ?>">
                                <?php endif; ?>

                                <div class="card-info">
                                    <?php if (!empty($item['title'])): ?>
                                        <h6 class="card-title"><?php echo esc_html($item['title']); ?></h6>
                                    <?php endif; ?>

                                    <?php if (!empty($item['category'])): ?>
                                        <span class="card-category"><?php echo esc_html($item['category']); ?></span>
                                    <?php endif; ?>
                                </div>

                                <?php if (!empty($item['link']['url'])): ?>
                            </a>
                        <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php
    }
}