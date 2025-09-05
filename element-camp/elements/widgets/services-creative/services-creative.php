<?php
namespace ElementCampPlugin\Widgets;

use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\repeater;
use Elementor\Frontend;
use Elementor\Icons_Manager;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Image_Size;
use Elementor\Scheme_Base;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;

if (!defined('ABSPATH')) exit;

class ElementCamp_Services_Creative extends Widget_Base
{
    public function get_name()
    {
        return 'tcgelements-services-creative';
    }

    public function get_title()
    {
        return esc_html__('Services Creative', 'element-camp');
    }

    public function get_icon()
    {
        return 'eicon-accordion tce-widget-badge';
    }
    public function get_script_depends() {
        return ['tcgelements-services-creative'];
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
        $this->add_control(
            'exp_section',
            [
                'label' => esc_html__( 'Exp Section', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'yes', 'element-camp' ),
                'label_off' => esc_html__( 'No', 'element-camp' ),
                'separator' => 'after'
            ]
        );
        $this->add_responsive_control(
            'exp_number',
            [
                'label' => esc_html__( 'EXP Section Number', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => esc_html__( '7+', 'element-camp' ),
                'condition' => [ 'exp_section' => 'yes' ],
            ]
        );
        $this->add_responsive_control(
            'exp_text',
            [
                'label' => esc_html__( 'EXP Section Text', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'Experience', 'element-camp' ),
                'condition' => [ 'exp_section' => 'yes' ],
            ]
        );
        $this->add_control(
            'images_media',
            [
                'label' => esc_html__('Images Media Type', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'none' => [
                        'title' => esc_html__('None', 'element-camp'),
                        'icon' => 'eicon-ban',
                    ],
                    'image' => [
                        'title' => esc_html__('Image', 'element-camp'),
                        'icon' => 'eicon-image',
                    ],
                ],
                'default' => 'none',
            ]
        );
        $this->add_control(
            'images_media_image',
            [
                'label' =>esc_html__('Media Image', 'element-camp'),
                'type' => Controls_Manager::MEDIA,
                'condition' => [ 'images_media' => 'image' ],
            ]
        );
        $repeater = new \Elementor\Repeater();
        $repeater->add_control(
            'card_link',
            [
                'label' => esc_html__('Card Link', 'element-camp'),
                'type' => Controls_Manager::URL,
                'placeholder' => 'Leave Link here',
            ]
        );
        $repeater->add_control(
            'item_count',
            [
                'label' =>esc_html__('Item Count', 'element-camp'),
                'type' => Controls_Manager::TEXT,
            ]
        );
        $repeater->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'element-camp'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => esc_html__('How can i make an order from themescamp?', 'element-camp'),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'title_image',
            [
                'label' =>esc_html__('Title Image', 'element-camp'),
                'type' => Controls_Manager::MEDIA,
            ]
        );
        $repeater->add_control(
            'content_image',
            [
                'label' =>esc_html__('Content Image', 'element-camp'),
                'type' => Controls_Manager::MEDIA,
            ]
        );

        $this->add_control(
            'services_items',
            [
                'label' => esc_html__('Services Items', 'element-camp'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ title }}}',
                'default' => [
                    [
                        'item_count' => esc_html__('01', 'element-camp'),
                        'title' => esc_html__('Digital Marketing', 'element-camp'),
                    ],
                    [
                        'item_count' => esc_html__('02', 'element-camp'),
                        'title' => esc_html__('Reporting Analysis', 'element-camp'),
                    ],
                    [
                        'item_count' => esc_html__('03', 'element-camp'),
                        'title' => esc_html__('SEO Marketing', 'element-camp'),
                    ],
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__( 'Style', 'element-camp' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'display',
            [
                'label' => esc_html__('Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                ],
                'default' => 'flex',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'display_position',
            [
                'label' => esc_html__( 'Display Position', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-services-creative' => '{{VALUE}}',
                ],
                'default' => 'start',
                'condition' => [ 'display' => 'flex' ],
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
                    '{{WRAPPER}} .tcgelements-services-creative' => 'justify-content: {{VALUE}};',
                ],
                'condition' => ['display'=> 'flex'],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'align_items',
            [
                'label' => esc_html__( 'Align Items', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
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
                    '{{WRAPPER}} .tcgelements-services-creative' => 'align-items: {{VALUE}};',
                ],
                'condition' => ['display'=> 'flex'],
                'default' => 'center',
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'flex_wrap',
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
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative' => 'flex-wrap: {{VALUE}};',
                ],
                'default' => 'wrap',
                'condition' => ['display'=> 'flex'],
            ]
        );
        $this->add_responsive_control(
            'card_column_width',
            [
                'label' => esc_html__( 'Card Column Width', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'size' => '50',
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'size' => '100',
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'size' => '100',
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'card_column_padding',
            [
                'label' => esc_html__( 'Card Column Padding', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'card_column_margin',
            [
                'label' => esc_html__( 'Card Column Margin', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'images_column_width',
            [
                'label' => esc_html__( 'Images Column Width', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'size' => '33.33333333',
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'size' => '100',
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'size' => '100',
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .images-column' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'images_column_padding',
            [
                'label' => esc_html__( 'Images Column Padding', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .images-column' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'images_column_margin',
            [
                'label' => esc_html__( 'Images Column Margin', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .images-column' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'card_style_section',
            [
                'label' => esc_html__( 'Card Style', 'element-camp' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'service_card_padding',
            [
                'label' => esc_html__( 'Card Padding', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .service-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'card_display',
            [
                'label' => esc_html__('Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                ],
                'default' => 'flex',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .service-card-row' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'card_display_position',
            [
                'label' => esc_html__( 'Display Position', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-services-creative .service-card-row' => '{{VALUE}}',
                ],
                'default' => ['start'],
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
                    '{{WRAPPER}} .tcgelements-services-creative .service-card-row' => 'justify-content: {{VALUE}};',
                ],
                'condition' => ['card_display'=> 'flex'],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'card_align_items',
            [
                'label' => esc_html__( 'Align Items', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
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
                    '{{WRAPPER}} .tcgelements-services-creative .service-card-row' => 'align-items: {{VALUE}};',
                ],
                'condition' => ['card_display'=> 'flex'],
                'default' => 'center',
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
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .service-card-row' => 'flex-wrap: {{VALUE}};',
                ],
                'default' => 'wrap',
                'condition' => ['card_display'=> 'flex'],
            ]
        );
        $this->add_responsive_control(
            'card_count_column_width',
            [
                'label' => esc_html__( 'Count Column Width', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'size' => '8.33333333',
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card-row .count-column' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'card_title_column_width',
            [
                'label' => esc_html__( 'Title Column Width', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'size' => '66.66666667',
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card-row .title-column' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'card_image_column_width',
            [
                'label' => esc_html__( 'Image Column Width', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'size' => '25',
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card-row .image-column' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs(
            'service_card_tabs',
        );
        
        $this->start_controls_tab(
            'service_card_normal_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'service_card_border',
                'selector' => '{{WRAPPER}} .tcgelements-services-creative .service-card',
                'separator' => 'before',
            ]
        );
        $this->end_controls_tab();
        
        $this->start_controls_tab(
            'service_card_hover_tab',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'service_card_border_hover',
                'selector' => '{{WRAPPER}} .tcgelements-services-creative .service-card:hover',
                'separator' => 'before',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        
        $this->end_controls_section();
        $this->start_controls_section(
            'section_card_content_style',
            [
                'label' => esc_html__('Card Content Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'service_card_count_typography',
                'selector' => '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .count-column .num',
            ]
        );
        $this->add_control(
            'service_card_count_color',
            [
                'label' => esc_html__( 'Count Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .count-column .num' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->start_controls_tabs(
            'service_card_title_tabs',
        );

        $this->start_controls_tab(
            'service_card_title_normal_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'service_card_title_typography',
                'label' => esc_html__( 'Title', 'element-camp' ),
                'selector' => '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .title-column .title',
            ]
        );
        $this->add_control(
            'service_card_title_color',
            [
                'label' => esc_html__( 'Title Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .title-column .title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'service_card_title_hover_tab',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'service_card_title_typography_hover',
                'label' => esc_html__( 'Title', 'element-camp' ),
                'selector' => '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card:hover .service-card-row .title-column .title',
            ]
        );
        $this->add_control(
            'service_card_title_color_hover',
            [
                'label' => esc_html__( 'Title Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card:hover .service-card-row .title-column .title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        $this->start_controls_section(
            'section_exp_style',
            [
                'label' => esc_html__('EXP Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'exp_section' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'exp_section_number_color',
            [
                'label' => esc_html__( 'Number Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .exp-wrapper .exp-number' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'exp_section_number_color_stroke',
                'selector' => '{{WRAPPER}} .tcgelements-services-creative .card-column .exp-wrapper .exp-number',
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'exp_section_number_typography',
                'label' => esc_html__( 'Number', 'element-camp' ),
                'selector' => '{{WRAPPER}} .tcgelements-services-creative .card-column .exp-wrapper .exp-number',
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
            'exp_section_text_color',
            [
                'label' => esc_html__( 'Text Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .exp-wrapper .exp-text' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'exp_section_text_typography',
                'label' => esc_html__( 'Text', 'element-camp' ),
                'selector' => '{{WRAPPER}} .tcgelements-services-creative .card-column .exp-wrapper .exp-text',
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_images_style',
            [
                'label' => esc_html__('Images Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'images_container_alignment',
            [
                'label' => esc_html__('Images Container Alignment', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-services-creative .images-column' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $start = is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');
        $end = !is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');

        $this->add_control(
            'images_container_offset_orientation_h',
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
            'images_container_offset_x',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-services-creative .images-column .imgs' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-services-creative .images-column .imgs' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'images_container_offset_orientation_h!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'images_container_offset_x_end',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-services-creative .images-column .imgs' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-services-creative .images-column .imgs' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'images_container_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_control(
            'images_container_offset_orientation_v',
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
            'images_container_offset_y',
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
                    '{{WRAPPER}} .tcgelements-services-creative .images-column .imgs' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'images_container_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'images_container_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-services-creative .images-column .imgs' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'images_container_offset_orientation_v' => 'end',
                ],
            ]
        );
        $this->add_control(
            'images_container_overflow_hidden',
            [
                'label' => esc_html__( 'Images Container Overflow Hidden', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'yes', 'element-camp' ),
                'label_off' => esc_html__( 'No', 'element-camp' ),
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .images-column .imgs' => 'overflow:hidden;',
                ],
                'separator' => 'before'
            ]
        );
        $this->add_responsive_control(
            'images_container_height',
            [
                'label' => esc_html__( 'Images Container Height', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-services-creative .images-column .imgs' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'images_container_width',
            [
                'label' => esc_html__( 'Images Container Width', 'element-camp' ),
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
                'size_units' => [ 'px', 'vw', '%', 'custom' ],
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
                    '{{WRAPPER}} .tcgelements-services-creative .images-column .imgs' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'images_container_border_radius',
            [
                'label' => esc_html__( 'Images Container Border Radius', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .images-column .imgs' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after'
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
        $this->add_control(
            'images_image_transform_options',
            [
                'label' => esc_html__('Image Transform', 'themescamp-plugin'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'themescamp-plugin'),
                'label_on' => esc_html__('Custom', 'themescamp-plugin'),
            ]
        );
        $this->start_popover();
        $this->add_control(
            'images_image_rotate',
            [
                'label' => esc_html__( 'Image Rotate', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'deg' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .images-column .imgs img' => '--e-transform-tcgelements-services-creative-images-image-rotateZ: {{SIZE}}deg',
                ],
            ]
        );
        $this->add_control(
            'images_image_scale',
            [
                'label' => esc_html__( 'Image Scale', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .images-column .imgs img' => '--e-transform-tcgelements-services-creative-images-image-scale: {{SIZE}}',
                ],
            ]
        );
        $this->end_popover();
        $this->end_controls_tab();
        $this->start_controls_tab(
            'image_show_tab',
            [
                'label'   => esc_html__( 'Show', 'element-camp' ),
            ]
        );
        $this->add_control(
            'images_image_transform_options_show',
            [
                'label' => esc_html__('Image Transform', 'themescamp-plugin'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'themescamp-plugin'),
                'label_on' => esc_html__('Custom', 'themescamp-plugin'),
            ]
        );
        $this->start_popover();
        $this->add_control(
            'images_image_rotate_show',
            [
                'label' => esc_html__( 'Image Rotate', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'deg' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .images-column .imgs img.show' => '--e-transform-tcgelements-services-creative-images-image-rotateZ: {{SIZE}}deg',
                ],
            ]
        );
        $this->add_control(
            'images_image_scale_show',
            [
                'label' => esc_html__( 'Image Scale', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .images-column .imgs img.show' => '--e-transform-tcgelements-services-creative-images-image-scale: {{SIZE}}',
                ],
            ]
        );
        $this->end_popover();
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="tcgelements-services-creative">
            <div class="card-column">
                <?php $itemCount=1; foreach ($settings['services_items'] as $item) : ?>
                    <a href="<?=esc_url($item['card_link']['url'])?>" class="service-card" data-serv="<?=esc_attr($itemCount)?>" <?php if ( $item['card_link']['is_external'] ) {echo'target="_blank"';} ?>>
                        <div class="service-card-row">
                            <div class="count-column">
                                <span class="num"> <?=esc_html($item['item_count'])?> </span>
                            </div>
                            <div class="title-column">
                                <h3 class="title"> <?=esc_html($item['title'])?> </h3>
                            </div>
                            <?php if (!empty($item['title_image']['url'])) :?>
                                <div class="image-column">
                                    <img src="<?= esc_url($item['title_image']['url']); ?>" alt="<?php if (!empty($item['title_image']['alt'])) echo esc_attr($item['title_image']['alt']); ?>">
                                </div>
                            <?php endif?>
                        </div>
                    </a>
                <?php $itemCount++; endforeach;?>
                <?php if ($settings['exp_section']) : ?>
                    <div class="exp-wrapper">
                        <span class="exp-number"><?=esc_html($settings['exp_number'])?></span> <span class="exp-text"><?=esc_html($settings['exp_text'])?></span>
                    </div>
                <?php endif;?>
            </div>
            <div class="images-column">
                <?php if ($settings['images_media']=='image' && !empty($settings['images_media_image']['url'])) : ?>
                    <img src="<?= esc_url($settings['images_media_image']['url']); ?>" alt="<?php if (!empty($settings['images_media_image']['alt'])) echo esc_attr($settings['images_media_image']['alt']); ?>" class="media-image">
                <?php endif;?>
                <div class="imgs">
                    <?php $itemCount=1; foreach ($settings['services_items'] as $item) : ?>
                        <img src="<?= esc_url($item['content_image']['url']); ?>" alt="<?php if (!empty($item['content_image']['alt'])) echo esc_attr($item['content_image']['alt']); ?>" data-serv="<?=esc_attr($itemCount)?>" class="<?php if ($itemCount===1) echo "show" ?>">
                    <?php $itemCount++; endforeach;?>
                </div>
            </div>
        </div>
        <?php
    }
}
