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

class ElementCamp_Blog_Modern extends Widget_Base
{
    public function get_name()
    {
        return 'tcgelements-blog-modern';
    }

    public function get_script_depends() {
        return ['tcgelements-background-image'];
    }

    public function get_title()
    {
        return esc_html__('Blog Modern', 'element-camp');
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
        $taxonomies = get_object_taxonomies('post', 'objects');
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Content', 'element-camp'),
            ]
        );

        $this->add_control(
            'number_format',
            [
                'label' => esc_html__('Number Format', 'elementcamp_plg'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'normal' => esc_html__('Normal', 'elementcamp_plg'),
                    'leading_zero' => esc_html__('01 Format', 'elementcamp_plg'),
                ],
                'default' => 'normal',
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
            'date_format',
            [
                'label' => esc_html__('Date Format', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => 'Default',
                    '0' => _x('March 6, 2018 (F j, Y)', 'Date Format', 'element-camp'),
                    '1' => '2018-03-06 (Y-m-d)',
                    '2' => '03/06/2018 (m/d/Y)',
                    '3' => '06/03/2018 (d/m/Y)',
                    'custom' => esc_html__('Custom', 'element-camp'),
                ],
                'condition' => [
                    'data_type' => 'date',
                ],
            ]
        );
        $this->add_control(
            'custom_date_format',
            [
                'label' => esc_html__('Custom Date Format', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'default' => 'F j, Y',
                'condition' => [
                    'data_type' => 'date',
                    'date_format' => 'custom',
                ],
                'description' => sprintf(
                /* translators: %s: Allowed data letters (see: http://php.net/manual/en/function.date.php). */
                    __('Use the letters: %s', 'element-camp'),
                    'l D d j S F m M n Y y'
                ),
            ]
        );
        $this->add_control(
            'display_terms_type',
            [
                'label' => esc_html__('Display Terms Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'category' => esc_html__('Categories', 'element-camp'),
                    'post_tag' => esc_html__('Tags', 'element-camp'),
                ],
                'default' => 'category',
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
            'post_item',
            [
                'label' => esc_html__( 'Item to display', 'element-camp' ),
                'type' => Controls_Manager::NUMBER,
                'default' => '3',
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
            'port_order',
            [
                'label' => esc_html__( 'Orders', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'DESC' => esc_html__( 'Descending', 'element-camp' ),
                    'ASC' => esc_html__( 'Ascending', 'element-camp' ),
                    'rand' => esc_html__( 'Random', 'element-camp' ),
                ],
                'default' => 'DESC',
            ]
        );

        $this->add_control(
            'order_by',
            [
                'label' => esc_html__('Order By', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'date' => esc_html__('Date', 'element-camp'),
                    'title' => esc_html__('Title', 'element-camp'),
                    'menu_order' => esc_html__('Menu Order', 'element-camp'),
                    'rand' => esc_html__('Random', 'element-camp'),
                ],
                'default' => 'date',
            ]
        );

        foreach ($taxonomies as $taxonomy => $object) {
            $this->add_control(
                $taxonomy . '_ids',
                [
                    'label' => $object->label,
                    'type' => 'tcg-select2',
                    'label_block' => true,
                    'multiple' => true,
                    'source_name' => 'taxonomy',
                    'source_type' => $taxonomy,
                ]
            );
        }

        $this->end_controls_section();

        $this->start_controls_section(
            'post_card_style',
            [
                'label' => esc_html__('Post Card Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'post_card_padding',
            [
                'label' => esc_html__('Post Card Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'post_card_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs(
            'post_card_tabs',
        );
        
        $this->start_controls_tab(
            'post_card_normal',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );

        $this->add_control(
            'post_card_content_color',
            [
                'label' => esc_html__( 'Post Card Content Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card *' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'post_card_content_color_warning',
            [
                'type' => \Elementor\Controls_Manager::ALERT,
                'alert_type' => 'warning',
                'heading' => esc_html__('Warning', 'element-camp'),
                'content' => __('<strong>This Will Override Any Color Choosen In Text Style</strong>', 'element-camp'),
                'condition' => [
                    'post_card_content_color!' => '',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'post_card_border',
                'selector' => '{{WRAPPER}} .tcgelements-blog-modern .post-card',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'post_card_background',
                'label' => esc_html__('Post Card Background', 'element-camp'),
                'types' => [ 'classic','gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-blog-modern .post-card',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'post_card_hover',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
            ]
        );

        $this->add_control(
            'post_card_content_color_hover',
            [
                'label' => esc_html__( 'Post Card Content Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card:hover *' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'post_card_content_color_warning_hover',
            [
                'type' => \Elementor\Controls_Manager::ALERT,
                'alert_type' => 'warning',
                'heading' => esc_html__('Warning', 'element-camp'),
                'content' => __('<strong>This Will Override Any Color Choosen In Text Style</strong>', 'element-camp'),
                'condition' => [
                    'post_card_content_color_hover!' => '',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'post_card_border_hover',
                'selector' => '{{WRAPPER}} .tcgelements-blog-modern .post-card:hover',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'post_card_background_hover',
                'label' => esc_html__('Post Card Background', 'element-camp'),
                'types' => [ 'classic','gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-blog-modern .post-card:hover',
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
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
                    '{{WRAPPER}} .tcgelements-blog-modern .bg-img' => 'height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-blog-modern .bg-img' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-blog-modern .bg-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before'
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
        $this->add_control(
            'number_style_options',
            [
                'label' => esc_html__( 'Number Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );
        $this->add_control(
            'number_color',
            [
                'label' => esc_html__( 'Number Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .number' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'number_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-blog-modern .post-card .number',
            ]
        );
        $this->add_responsive_control(
            'number_margin',
            [
                'label' => esc_html__('Number Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .number' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .title' => 'color: {{VALUE}};',
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
                'selector' => '{{WRAPPER}} .tcgelements-blog-modern .post-card .title',
            ]
        );
        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Title Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .taxonomy' => 'color: {{VALUE}};',
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
                'selector' => '{{WRAPPER}} .tcgelements-blog-modern .post-card .taxonomy',
            ]
        );
        $this->add_responsive_control(
            'taxonomy_margin',
            [
                'label' => esc_html__('Taxonomy Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .taxonomy' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'excerpt_style_options',
            [
                'label' => esc_html__( 'Excerpt Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'before'
            ]
        );
        $this->add_control(
            'excerpt_color',
            [
                'label' => esc_html__( 'Excerpt Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .excerpt' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'excerpt_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-blog-modern .post-card .excerpt',
            ]
        );
        $this->add_responsive_control(
            'excerpt_margin',
            [
                'label' => esc_html__('Excerpt Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .excerpt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'date_style_options',
            [
                'label' => esc_html__( 'Date Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'before'
            ]
        );
        $this->add_control(
            'date_color',
            [
                'label' => esc_html__( 'Date Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .date' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'date_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-blog-modern .post-card .date',
            ]
        );
        $this->add_responsive_control(
            'date_margin',
            [
                'label' => esc_html__('Date Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .date' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
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
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .butn' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );
        $this->add_control(
            'butn_index',
            [
                'label' => esc_html__( 'Button z-index', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .butn' => 'z-index: {{SIZE}};',
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
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .butn' => 'position: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .butn' => 'left: {{SIZE}}{{UNIT}}',
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
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .butn' => 'right: {{SIZE}}{{UNIT}}',
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
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .butn' => 'top: {{SIZE}}{{UNIT}}',
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
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .butn' => 'bottom: {{SIZE}}{{UNIT}}',
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
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .butn' => 'transform: translate({{button_translate_x.SIZE}}{{button_translate_x.UNIT}},{{button_translate_y.SIZE}}{{button_translate_y.UNIT}})',
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
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .butn' => 'width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .butn' => 'height: {{SIZE}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .tcgelements-blog-modern .post-card .butn',
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
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .butn' => 'display: {{VALUE}};'
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
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .butn' => 'justify-content: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .butn' => 'align-items: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .butn' => 'color: {{VALUE}}; fill: {{VALUE}};',
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
                'selector' => '{{WRAPPER}} .tcgelements-blog-modern .post-card .butn',
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
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .butn' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-blog-modern .post-card .butn',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'selector' => '{{WRAPPER}} .tcgelements-blog-modern .post-card .butn',
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
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .butn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .butn' => 'outline: {{outline_width.SIZE}}{{outline_width.UNIT}} {{outline.VALUE}} {{outline_color.VALUE}}',
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
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .butn' => 'outline-offset: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card:hover .butn' => 'display: {{VALUE}};'
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
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card:hover .butn' => 'justify-content: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card:hover .butn' => 'align-items: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card:hover .butn' => 'color: {{VALUE}};',
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
                'selector' => '{{WRAPPER}} .tcgelements-blog-modern .post-card:hover .butn',
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
                'selector' => '{{WRAPPER}} .tcgelements-blog-modern .post-card:hover .butn',
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
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card:hover .butn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card:hover .butn' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow_hover',
                'selector' => '{{WRAPPER}} .tcgelements-blog-modern .post-card:hover .butn',
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
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card:hover .butn' => 'outline: {{outline_width_hover.SIZE}}{{outline_width_hover.UNIT}} {{outline_hover.VALUE}} {{outline_color_hover.VALUE}}',
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
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card:hover .butn' => 'outline-offset: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .butn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .butn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .butn .butn-icon' => 'display: {{VALUE}};'
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
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .butn .butn-icon' => 'justify-content: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .butn .butn-icon' => 'align-items: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .butn .butn-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .butn .butn-icon svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .butn .butn-icon' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .butn .butn-icon' => 'height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .butn .butn-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .butn .butn-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .butn .butn-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card .butn .butn-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'button_icon_background',
                'label' => esc_html__('Button Icon Background', 'element-camp'),
                'types' => [ 'classic','gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-blog-modern .post-card .butn .butn-icon',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'button_icon_border',
                'selector' => '{{WRAPPER}} .tcgelements-blog-modern .post-card .butn .butn-icon',
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
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card:hover .butn .butn-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-blog-modern .post-card:hover .butn .butn-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'button_icon_background_hover',
                'label' => esc_html__('Button Icon Background', 'element-camp'),
                'types' => [ 'classic','gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-blog-modern .post-card:hover .butn .butn-icon',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'button_icon_border_hover',
                'selector' => '{{WRAPPER}} .tcgelements-blog-modern .post-card:hover .butn .butn-icon',
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $taxonomies = get_object_taxonomies('post', 'objects');
        $selected_term_type = $settings['display_terms_type'];
        $separator = $settings['meta_separator'];
        $query_args = [
            'post_type' => 'post',
            'posts_per_page' => $settings['post_item'],
            'order' => $settings['port_order'],
            'orderby' => $settings['order_by'],
            'offset' => $settings['offset'],
        ];
        foreach ($taxonomies as $taxonomy => $object) {
            if (isset($settings[$taxonomy . '_ids']) && !empty($settings[$taxonomy . '_ids'])) {
                $taxonomy_ids = $settings[$taxonomy . '_ids'];
                $query_args['tax_query'][] = [
                    'taxonomy' => $taxonomy,
                    'field' => 'term_id',
                    'terms' => $taxonomy_ids,
                ];
            }
        }
        if (empty($query_args['tax_query'])) {
            unset($query_args['tax_query']);
        } elseif (count($query_args['tax_query']) > 1) {
            $query_args['tax_query']['relation'] = 'AND';
        }
        $elementcamp_posts = new \WP_Query($query_args);
        $number_format = $settings['number_format'];
        ?>
        <div class="tcgelements-blog-modern">
            <?php $itemCount=1; if ($elementcamp_posts->have_posts()) : while  ($elementcamp_posts->have_posts()) : $elementcamp_posts->the_post(); global $post ;
                $terms = get_the_terms(get_the_ID(), $selected_term_type);
                $terms_display = '';
                if (!empty($terms) && !is_wp_error($terms)) {
                    $term_names = wp_list_pluck($terms, 'name');
                    $terms_display = implode($separator, $term_names);
                }
            ?>
                <a class="post-card" href="<?=esc_url(get_the_permalink())?>">
                    <div class="row gx-5">
                        <div class="col-lg-1 col-2">
                            <div class="number"> <?php
                                if ($number_format === 'leading_zero') {
                                    $formatted_count = sprintf('%02d', $itemCount);
                                } else {
                                    $formatted_count = $itemCount;
                                }
                                echo esc_html($formatted_count).'.' ?>
                            </div>
                        </div>
                        <div class="col-lg-4 col-10">
                            <h3 class="title"> <?= esc_html(get_the_title()) ?> </h3>
                        </div>
                        <div class="col-lg-4 col-12">
                            <div class="excerpt"> <?php the_excerpt()?> </div>
                        </div>
                        <div class="col-lg-3 col-12 d-flex align-items-center justify-content-lg-end">
                            <div class="date">
                                <span class="taxonomy"><?= esc_html($terms_display) ?> | </span>
                                <span class="date-text">
                                    <?php $custom_date_format = empty($settings['custom_date_format']) ? 'F j, Y' : $settings['custom_date_format']; echo get_the_time($custom_date_format)?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <?php if ($settings['button_switcher']=='yes') : ?>
                        <div class="butn">
                            <?php if (!empty($settings['selected_icon']['value']) and ($settings['icon_align'] == 'left')) : ?>
                                <span class="butn-icon">
                                    <?php Icons_Manager::render_icon($settings['selected_icon'], ['aria-hidden' => 'true']);?>
                                </span>
                            <?php endif; ?>
                            <span class="text">
                                <?= $settings['btn_text']; ?>
                            </span>
                            <?php if (!empty($settings['selected_icon']['value']) and ($settings['icon_align'] == 'right')) : ?>
                                <span class="butn-icon">
                                    <?php Icons_Manager::render_icon($settings['selected_icon'], ['aria-hidden' => 'true']);?>
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php endif;?>
                    <img src="<?= esc_url(get_the_post_thumbnail_url()); ?>" alt="" class="bg">
                </a>
            <?php $itemCount++; endwhile;  wp_reset_postdata(); endif; ?>
        </div>
        <?php
    }
}
