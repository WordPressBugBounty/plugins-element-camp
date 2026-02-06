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

class ElementCamp_Services_Accordion extends Widget_Base
{
    public function get_name()
    {
        return 'tcgelements-services-accordion';
    }

    public function get_title()
    {
        return esc_html__('Services Accordion', 'element-camp');
    }

    public function get_icon()
    {
        return 'eicon-accordion tce-widget-badge';
    }
    public function get_script_depends() {
        return ['bootstrap.bundle.min'];
    }

    public function get_categories()
    {
        return ['elementcamp-elements'];
    }
    protected function _register_controls()
    {
        $this->start_controls_section(
            'section_items',
            [
                'label' => esc_html__('Accordion Items', 'element-camp'),
            ]
        );
        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'title_media',
            [
                'label' => esc_html__('Title Media Type', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'none' => [
                        'title' => esc_html__('None', 'element-camp'),
                        'icon' => 'eicon-ban',
                    ],
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
            ]
        );
        $repeater->add_control(
            'title_image',
            [
                'label' =>esc_html__('Title Image', 'element-camp'),
                'type' => Controls_Manager::MEDIA,
                'condition' => [ 'title_media' => 'image' ],
            ]
        );
        $repeater->add_control(
            'title_icon',
            [
                'label' => esc_html__('Title Icon', 'element-camp'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'skin' => 'inline',
                'label_block' => false,
                'condition' => [ 'title_media' => 'icon' ],
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
            'enable_tags',
            [
                'label' => esc_html__('Enable Tags', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
            ]
        );
        $repeater->add_control(
            'tags',
            [
                'label' => esc_html__('Tags', 'element-camp'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => esc_html__('Design, Mobile App', 'element-camp'),
                'description' => esc_html__('Enter tags separated by commas.', 'element-camp'),
                'condition' => [
                    'enable_tags' => 'yes',
                ],
            ]
        );
        $repeater->add_control(
            'content_media',
            [
                'label' => esc_html__('Content Media Type', 'element-camp'),
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
        $repeater->add_control(
            'content_image',
            [
                'label' =>esc_html__('Content Image', 'element-camp'),
                'type' => Controls_Manager::MEDIA,
                'condition' => [ 'content_media' => 'image' ],
            ]
        );
        $repeater->add_control(
            'content',
            [
                'label' => esc_html__('Content', 'element-camp'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => '<p>' . esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'element-camp' ) . '</p>',
            ]
        );
        $repeater->add_control(
            'btn_text',
            [
                'label' => esc_html__('Button Text', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__('Click me', 'element-camp'),
            ]
        );
        $repeater->add_control(
            'btn_type',
            [
                'label' => esc_html__('Button Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'custom' => esc_html__('Custom Link', 'element-camp'),
                    'none' => esc_html__('None', 'element-camp'),
                ],
                'default' => 'custom',
            ]
        );
        $repeater->add_control(
            'btn_link',
            [
                'label' => esc_html__('Button Link', 'element-camp'),
                'type' => Controls_Manager::URL,
                'placeholder' => 'Leave Link here',
                'condition' => [
                    'btn_type' => 'custom',
                ],
            ]
        );
        $repeater->add_control(
            'btn_media',
            [
                'label' => esc_html__('Button Media Type', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'none' => [
                        'title' => esc_html__('None', 'element-camp'),
                        'icon' => 'eicon-ban',
                    ],
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
            ]
        );
        $repeater->add_control(
            'btn_media_image',
            [
                'label' =>esc_html__('Button Image', 'element-camp'),
                'type' => Controls_Manager::MEDIA,
                'condition' => [ 'btn_media' => 'image' ],
            ]
        );
        $repeater->add_control(
            'btn_media_icon',
            [
                'label' => esc_html__('Button Icon', 'element-camp'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'skin' => 'inline',
                'label_block' => false,
                'condition' => [ 'btn_media' => 'icon' ],
            ]
        );
        $this->add_control(
            'accordion_items',
            [
                'label' => esc_html__('Accordion Items', 'element-camp'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ title }}}',
                'default' => [
                    [
                        'title' => esc_html__('Accordion #1', 'element-camp'),
                        'content' => esc_html__('Accordion Content #1', 'element-camp'),
                    ],
                    [
                        'title' => esc_html__('Accordion #2', 'element-camp'),
                        'content' => esc_html__('Accordion Content #2', 'element-camp'),
                    ],
                    [
                        'title' => esc_html__('Accordion #3', 'element-camp'),
                        'content' => esc_html__('Accordion Content #3', 'element-camp'),
                    ],
                ],
            ]
        );
        $this->add_control(
            'active_item',
            [
                'label' => esc_html__('Active Item Number', 'element-camp'),
                'default'=>1,
                'type' => \Elementor\Controls_Manager::NUMBER,
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'style_item_section',
            [
                'label' => esc_html__( 'Item Style', 'element-camp' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->start_controls_tabs(
            'accordion_item_tabs',
        );
        $this->start_controls_tab(
            'normal_item_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'background_item_color',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-services-accordion .accordion-item',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'selector' => '{{WRAPPER}} .tcgelements-services-accordion .accordion-item',
                'separator' => 'before',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'active_item_tab',
            [
                'label'   => esc_html__( 'Active', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border_active',
                'selector' => '{{WRAPPER}} .tcgelements-services-accordion .accordion-item.active',
                'separator' => 'before',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        $this->start_controls_section(
            'style_title_section',
            [
                'label' => esc_html__( 'Title Style', 'element-camp' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'accordion_title_hidden',
            [
                'label' => esc_html__( 'Overflow Hidden', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'yes', 'element-camp' ),
                'label_off' => esc_html__( 'No', 'element-camp' ),
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .accordion-title' => 'overflow:hidden;',
                ],
            ]
        );
        $this->add_responsive_control(
            'accordion_title_display',
            [
                'label' => esc_html__('Accordion Title Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'flex',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'inline-flex' => esc_html__('Inline Flex', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .accordion-title' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'accordion_title_display_position',
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
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .accordion-title' => '{{VALUE}}',
                ],
                'condition' => [ 'accordion_title_display' => ['flex','inline-flex'] ],
            ]
        );
        $this->add_responsive_control(
            'accordion_title_justify_content',
            [
                'label' => esc_html__( 'Accordion Title Justify Content', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .accordion-title' => 'justify-content: {{VALUE}};',
                ],
                'condition' => ['accordion_title_display'=> ['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'accordion_title_align_items',
            [
                'label' => esc_html__( 'Accordion Title Align Items', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .accordion-title' => 'align-items: {{VALUE}};',
                ],
                'condition' => ['accordion_title_display'=> ['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'accordion_title_flex_wrap',
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
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .accordion-title' => 'flex-wrap: {{VALUE}};',
                ],
                'condition' => ['accordion_title_display'=> ['flex','inline-flex']],
            ]
        );
        $this->add_responsive_control(
            'accordion_title_width',
            [
                'label' => esc_html__( 'Title Width', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .title' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'accordion_title_padding',
            [
                'label' => esc_html__( 'Accordion Title Padding', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .accordion-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'accordion_title_margin',
            [
                'label' => esc_html__( 'Accordion Title Margin', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .accordion-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_text_padding',
            [
                'label' => esc_html__( 'Title Padding', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .accordion-title .title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__('Title Typography', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .accordion-title .title',
            ]
        );
        $this->start_controls_tabs(
            'title_tabs',
        );
        $this->start_controls_tab(
            'title_normal_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_responsive_control(
            'title_text_margin',
            [
                'label' => esc_html__( 'Title Margin', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .accordion-title .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );
        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Title Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .accordion-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'title_border',
                'selector' => '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .accordion-title',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'title_active_tab',
            [
                'label'   => esc_html__( 'Active', 'element-camp' ),
            ]
        );
        $this->add_responsive_control(
            'title_text_margin_active',
            [
                'label' => esc_html__( 'Title Margin', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .accordion-title:not(.collapsed) .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );
        $this->add_control(
            'title_color_active',
            [
                'label' => esc_html__('Title Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .accordion-title:not(.collapsed)' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'title_border_active',
                'selector' => '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .accordion-title:not(.collapsed)',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        $this->start_controls_section(
            'style_media_section',
            [
                'label' => esc_html__( 'Media Style', 'element-camp' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'media_display',
            [
                'label' => esc_html__('Media Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'flex',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'inline-flex' => esc_html__('Inline Flex', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .media' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'media_justify_content',
            [
                'label' => esc_html__( 'Media Justify Content', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .media' => 'justify-content: {{VALUE}};',
                ],
                'condition'=>['media_display'=> ['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'media_align_items',
            [
                'label' => esc_html__( 'Media Align Items', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .media' => 'align-items: {{VALUE}};',
                ],
                'condition'=>['media_display'=> ['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'media_margin',
            [
                'label' => esc_html__('Media Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .media' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'media_padding',
            [
                'label' => esc_html__('Media Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .media' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'media_width',
            [
                'label' => esc_html__( 'Media Width', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .media' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'media_height',
            [
                'label' => esc_html__( 'Media Height', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .media' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'media_image_style',
            [
                'label' => esc_html__('Media Image', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
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
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .media img' => 'width: {{SIZE}}{{UNIT}};',
                ],
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
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .media img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_object-fit',
            [
                'label' => esc_html__( 'Object Fit', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'fill' => esc_html__( 'Fill', 'element-camp' ),
                    'cover' => esc_html__( 'Cover', 'element-camp' ),
                    'contain' => esc_html__( 'Contain', 'element-camp' ),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .media img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_object-position',
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
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .media img' => 'object-position: {{VALUE}};',
                ],
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
                'selector' => '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .media img',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'image_active_tab',
            [
                'label'   => esc_html__( 'Active', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'image_css_filters_active',
                'selector' => '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .accordion-title:not(.collapsed) .media img',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        $this->start_controls_section(
            'media_icon_style',
            [
                'label' => esc_html__('Icon', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'media_icon_size',
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
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .media i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .media svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'media_icon_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .media' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
         $this->add_responsive_control(
			'media_icon_border_radius',
			[
				'label' => esc_html__( 'Media Icon Border Radius', 'element-camp' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .media' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->start_controls_tabs(
            'icon_tabs',
        );
        $this->start_controls_tab(
            'icon_normal_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_control(
            'media_icon_color',
            [
                'label' => esc_html__('Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .media i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .media svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'media_icon_stroke_color',
            [
                'label' => esc_html__('SVG Path Stroke Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .media svg path' => 'stroke-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'title_icon_border',
				'label' => __( 'Title Icon Border', 'element-camp' ),
				'selector' => '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .media',
			]
		);
        $this->add_control(
            'title_icon_transform_options',
            [
                'label' => esc_html__('Icon Transform', 'element-camp'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'element-camp'),
                'label_on' => esc_html__('Custom', 'element-camp'),
            ]
        );
        $this->start_popover();
        $this->add_control(
            'title_icon_rotate',
            [
                'label' => esc_html__( 'Icon Rotate', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'deg' ],
                'default' => [
                    'unit' => 'deg',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .media svg' => '--e-transform-tcgelements-services-accordion-title-icon-rotate-rotateZ: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .media i' => '--e-transform-tcgelements-services-accordion-title-icon-rotate-rotateZ: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->end_popover();
        $this->end_controls_tab();
        $this->start_controls_tab(
            'icon_active_tab',
            [
                'label'   => esc_html__( 'Active', 'element-camp' ),
            ]
        );
        $this->add_control(
            'media_icon_color_active',
            [
                'label' => esc_html__('Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .accordion-title:not(.collapsed) .media i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .accordion-title:not(.collapsed) .media svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'media_icon_stroke_color_active',
            [
                'label' => esc_html__('SVG Path Stroke Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .accordion-title:not(.collapsed) .media svg path' => 'stroke: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'title_icon_border_active',
				'label' => __( 'Title Icon Border', 'element-camp' ),
                'selector' => '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .accordion-title:not(.collapsed) .media',
			]
		);
        $this->add_control(
            'title_icon_transform_options_active',
            [
                'label' => esc_html__('Icon Transform', 'element-camp'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'element-camp'),
                'label_on' => esc_html__('Custom', 'element-camp'),
            ]
        );
        $this->start_popover();
        $this->add_control(
            'title_icon_rotate_active',
            [
                'label' => esc_html__( 'Icon Rotate', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'deg' ],
                'default' => [
                    'unit' => 'deg',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .accordion-title:not(.collapsed) .media svg' => '--e-transform-tcgelements-services-accordion-title-icon-rotate-rotateZ: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-header .accordion-title:not(.collapsed) .media i' => '--e-transform-tcgelements-services-accordion-title-icon-rotate-rotateZ: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->end_popover();
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        $this->start_controls_section(
            'style_count_section',
            [
                'label' => esc_html__( 'Count Style', 'element-camp' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'accordion_count_width',
            [
                'label' => esc_html__( 'Count Width', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-title .count' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'count_padding',
            [
                'label' => esc_html__( 'Count Padding', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-title .count' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'count_margin',
            [
                'label' => esc_html__( 'Count Margin', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-title .count' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'count_typography',
                'label' => esc_html__('Count Typography', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-title .count',
            ]
        );
        $this->add_control(
            'count_color',
            [
                'label' => esc_html__('Count Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-title .count' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'style_tags_section',
            [
                'label' => esc_html__( 'Tags Style', 'element-camp' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'tags_style_options',
            [
                'label' => esc_html__( 'Tags Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'before'
            ]
        );
        $this->add_responsive_control(
            'accordion_tags_width',
            [
                'label' => esc_html__( 'Tags Width', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-title .tags' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'tags_padding',
            [
                'label' => esc_html__( 'Tags Padding', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-title .tags' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'tags_margin',
            [
                'label' => esc_html__( 'Tags Margin', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-title .tags' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tags_typography',
                'label' => esc_html__('Tags Typography', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-title .tags',
            ]
        );
        $this->add_control(
            'tags_color',
            [
                'label' => esc_html__('Tags Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-title .tags' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'tag_style_options',
            [
                'label' => esc_html__( 'Tag Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'before'
            ]
        );
        $this->add_responsive_control(
            'tag_border_radius',
            [
                'label' => esc_html__('Tag Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-title .tags .tag' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'tag_margin',
            [
                'label' => esc_html__( 'Tag Margin', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-title .tags .tag' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'tag_padding',
            [
                'label' => esc_html__('Tag Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-title .tags .tag' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );
        $this->start_controls_tabs(
            'tags_style_tabs'
        );
        
        $this->start_controls_tab(
            'tags_style_normal_tab',
            [
                'label' => esc_html__( 'Normal', 'element-camp' ),
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'tags_tag_color',
                'types' => [ 'classic','gradient','tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-title .tags .tag',
            ]
        );

        $this->add_control(
            'tags_tag_item_color',
            [
                'label' => esc_html__('Tag Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-title .tags .tag' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tags_style_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'element-camp' ),
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'hover_tags_tag_color',
                'types' => [ 'classic','gradient','tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-title .tags .tag:hover',
            ]
        );

        $this->add_control(
            'hover_tags_tag_item_color',
            [
                'label' => esc_html__('Tag Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-title .tags .tag:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'tags_tag_border',
				'label' => __( 'Marquee Border', 'element-camp' ),
				'selector' => '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-title .tags .tag',
			]
		);
        $this->end_controls_section();
        $this->start_controls_section(
            'style_body_section',
            [
                'label' => esc_html__( 'Body Style', 'element-camp' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'accordion_body_display_position',
            [
                'label' => esc_html__( 'Body Display Position', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .row' => '{{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'accordion_body_justify_content',
            [
                'label' => esc_html__( 'Accordion Title Justify Content', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .row' => 'justify-content: {{VALUE}};',
                ],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'accordion_body_align_items',
            [
                'label' => esc_html__( 'Accordion Body Align Items', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .row' => 'align-items: {{VALUE}};',
                ],
                'responsive' => true,
            ]
        );

        $this->add_responsive_control(
            'column_1_width',
            [
                'label' => __('Button Column Width', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .col-md-4' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_column_margin',
            [
                'label' => esc_html__('Button Column Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .col-md-4' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'column_2_width',
            [
                'label' => __('Text Column Width', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .col-md-8' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'column_2_margin',
            [
                'label' => esc_html__( 'Text Column Margin', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .col-md-8' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'text_width',
            [
                'label' => __('Text Width', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .col-md-8 .col-lg-7' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'accordion_body_padding',
            [
                'label' => esc_html__( 'Accordion Body Padding', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'accordion_body_border',
                'selector' => '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body',
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'accordion_body_text_typography',
                'label' => esc_html__('Accordion Body Text Typography', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .text',
            ]
        );
        $this->add_responsive_control(
            'accordion_body_text_margin',
            [
                'label' => esc_html__( 'Accordion Body Text Margin', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .text > *' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'accordion_body_text_color',
            [
                'label' => esc_html__('Accordion Body Text Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .text' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .text > *' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'accordion_body_text_list_color',
            [
                'label' => esc_html__('Accordion Body List Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .text .list' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'content_media_image_style',
            [
                'label' => esc_html__('Content Media Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'content_image_align',
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
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .content-image' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'content_image_max_width',
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
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .content-image img' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'content_image_max_height',
            [
                'label' => esc_html__( 'Max Height', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .content-image img' => 'max-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'content_image_width',
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
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .content-image img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'content_image_height',
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
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .content-image img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'content_image_object-fit',
            [
                'label' => esc_html__( 'Object Fit', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'fill' => esc_html__( 'Fill', 'element-camp' ),
                    'cover' => esc_html__( 'Cover', 'element-camp' ),
                    'contain' => esc_html__( 'Contain', 'element-camp' ),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .content-image img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'content_image_object-position',
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
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .content-image img' => 'object-position: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'content_media_image_margin',
            [
                'label' => esc_html__( 'Content Media Margin', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .content-image img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
        $start = is_rtl() ? esc_html__('Right', 'themescamp-plugin') : esc_html__('Left', 'themescamp-plugin');
        $end = !is_rtl() ? esc_html__('Right', 'themescamp-plugin') : esc_html__('Left', 'themescamp-plugin');

        $this->add_control(
            'button_column_position_positioning',
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
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .col-md-4' => 'position: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_column_position_offset_orientation_h',
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
            'button_column_position_offset_x',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .col-md-4' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .col-md-4' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'button_column_position_offset_orientation_h!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_column_position_offset_x_end',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .col-md-4' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .col-md-4' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'button_column_position_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_control(
            'button_column_position_offset_orientation_v',
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
            'button_column_position_offset_y',
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
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .col-md-4' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'button_column_position_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_column_position_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .col-md-4' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'button_column_position_offset_orientation_v' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_width',
            [
                'label' => __('Button Width', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .butn' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
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
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .butn' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'button_justify_content',
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
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .butn' => 'justify-content: {{VALUE}};',
                ],
                'condition'=>['button_display'=>['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'button_align_items',
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
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .butn' => 'align-items: {{VALUE}};',
                ],
                'condition'=>['button_display'=>['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'btn_typography',
                'label' => esc_html__('Button Typography', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .butn',
            ]
        );
        $this->add_control(
            'btn_color',
            [
                'label' => esc_html__('Button Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .butn' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .butn i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .butn svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_text_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .butn .btn-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'button_icon_style',
            [
                'label' => esc_html__( 'Button Icon Style', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
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
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .butn .btn-media i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .butn .btn-media svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'button_image_style',
            [
                'label' => esc_html__( 'Button Image Style', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'button_image_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .butn .btn-media img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_image_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .butn .btn-media img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_image_width',
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
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .butn .btn-media img' => 'width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_image_height',
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
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .butn .btn-media img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'btn_img_object-fit',
            [
                'label' => esc_html__( 'Object Fit', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'button_image_height[size]!' => '',
                ],
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'fill' => esc_html__( 'Fill', 'element-camp' ),
                    'cover' => esc_html__( 'Cover', 'element-camp' ),
                    'contain' => esc_html__( 'Contain', 'element-camp' ),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .butn .btn-media img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_img_object-position',
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
                    '{{WRAPPER}} .tcgelements-services-accordion .accordion-item .accordion-body .butn .btn-media img' => 'object-position: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $accordion_id = uniqid('accordion');
        ?>
        <div class="tcgelements-services-accordion"  id="<?=esc_attr($accordion_id)?>">
            <?php  $itemCount=1; foreach ($settings['accordion_items'] as $item) : ?>
                <div class="accordion-item <?php if ($settings['active_item']==$itemCount) echo esc_attr('active')?>">
                    <div class="accordion-header">
                        <div class="accordion-title <?php if ($settings['active_item']!=$itemCount) echo esc_attr('collapsed')?>" data-bs-toggle="collapse" data-bs-target="#collapse<?=$itemCount.$accordion_id?>">
                            <?php if (!empty($item['item_count'])) : ?>
                                <div class="count"><?=esc_html($item['item_count'])?></div>
                            <?php endif;?>
                            <?php if ($item['enable_tags'] === 'yes') : ?>
                                <div class="tags">
                                    <?php
                                    $tags = explode(',', $item['tags']);
                                    foreach ($tags as $tag) : ?>
                                        <span class="tag"><?= esc_html(trim($tag)); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <div class="title">
                                <?=__($item['title'],'element-camp')?>
                            </div>
                            <?php if ($item['title_media'] != 'none') : ?>
                                <div class="media">
                                    <?php if (!empty($item['title_image']['url'])) : ?>
                                        <img src="<?= esc_url($item['title_image']['url']); ?>" alt="<?php if (!empty($item['title_image']['alt'])) echo esc_attr($item['title_image']['alt']); ?>" >
                                    <?php endif;?>
                                    <?php
                                    if (!empty($item['title_icon']['value'])) {
                                        Icons_Manager::render_icon($item['title_icon'], ['aria-hidden' => 'true']);
                                    }
                                    ?>
                                </div>
                            <?php endif;?>
                        </div>
                    </div>
                    <div id="collapse<?=$itemCount.$accordion_id?>" class="accordion-collapse collapse <?php if ($settings['active_item']==$itemCount) echo 'show'?>" data-bs-parent="<?= esc_attr('#' . $accordion_id) ?>">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <?php if ($item['btn_type']=='custom') :?>
                                        <a href="<?=esc_url($item['btn_link']['url'])?>" class="butn" <?php if ( $item['btn_link']['is_external'] ) {echo'target="_blank"';} ?>>
                                    <?php elseif ($item['btn_type']=='none') : ?>
                                        <div class="butn">
                                    <?php endif;?>
                                        <div class="btn-media">
                                            <?php if (!empty($item['btn_media_image']['url'])) : ?>
                                                <img src="<?= esc_url($item['btn_media_image']['url']); ?>" alt="<?php if (!empty($item['btn_media_image']['alt'])) echo esc_attr($item['btn_media_image']['alt']); ?>" >
                                            <?php endif;?>
                                            <?php
                                            if (!empty($item['btn_media_icon']['value'])) {
                                                Icons_Manager::render_icon($item['btn_media_icon'], ['aria-hidden' => 'true']);
                                            }
                                            ?>
                                        </div>
                                        <span class="btn-text"><?=esc_html($item['btn_text'])?></span>
                                    <?php if ($item['btn_type']=='custom') :?>
                                        </a>
                                    <?php elseif ($item['btn_type']=='none') : ?>
                                        </div>
                                    <?php endif;?>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-lg-7">
                                            <?php if ($item['content_media'] != 'none') : ?>
                                                <div class="content-image">
                                                    <img src="<?=esc_url($item['content_image']['url'])?>" alt="<?php if (!empty($item['content_image']['alt'])) echo esc_attr($item['content_image']['alt']); ?>" />
                                                </div>
                                            <?php endif;?>
                                            <div class="text">
                                                <?=__($item['content'])?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php $itemCount++; endforeach;?>
        </div>
        <?php
    }
}
