<?php

namespace ElementCampPlugin\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Utils;
use Elementor\Group_Control_Border;
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

if (!defined('ABSPATH')) exit;

class ElementCamp_Services_Tabs extends Widget_Base
{
    public function get_name()
    {
        return 'tcgelements-services-tabs';
    }

    public function get_title()
    {
        return esc_html__('Services Tabs', 'element-camp');
    }

    public function get_icon()
    {
        return 'eicon-tabs tce-widget-badge';
    }
    public function get_script_depends()
    {
        return ['bootstrap.bundle.min', 'tcgelements-services-tabs'];
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
    protected function _register_controls()
    {
        $this->start_controls_section(
            'section_items',
            [
                'label' => esc_html__('Tabs Items', 'element-camp'),
            ]
        );
        $this->add_control(
            'services_tabs_controls',
            [
                'label' => esc_html__('Works On : ', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'click',
                'options' => [
                    'click' => esc_html__('On Click', 'element-camp'),
                    'hover' => esc_html__('On Hover', 'element-camp'),
                ],
            ]
        );

        $repeater = new \Elementor\Repeater();
        $repeater->add_control(
            'service_title',
            [
                'label' => esc_html__('Service Title', 'element-camp'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Security Design', 'element-camp'),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'service_description_switcher',
            [
                'label' => esc_html__('Service Description Switcher', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'default' => esc_html__('yes', 'element-camp'),
            ]
        );
        $repeater->add_control(
            'service_description',
            [
                'label' => esc_html__('Service Description', 'element-camp'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'condition' => ['service_description_switcher' => 'yes'],
                'default' => esc_html__('Quis autem vel eums reprehe voluptate velit esse quamnihy molestiae consequature', 'element-camp'),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'service_icon',
            [
                'label' => esc_html__('Service Icon', 'element-camp'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ]
            ]
        );
        $this->add_control(
            'nav_tabs',
            [
                'label' => esc_html__('Nav Tabs', 'element-camp'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ service_title }}}',
                'default' => [
                    [
                        'service_title' => esc_html__('Security Design', 'element-camp'),
                        'service_description' => esc_html__('Quis autem vel eums reprehe voluptate velit esse quamnihy molestiae consequature', 'element-camp'),
                    ],
                    [
                        'service_title' => esc_html__('Security Design', 'element-camp'),
                        'service_description' => esc_html__('Quis autem vel eums reprehe voluptate velit esse quamnihy molestiae consequature', 'element-camp'),
                    ],
                    [
                        'service_title' => esc_html__('Security Design', 'element-camp'),
                        'service_description' => esc_html__('Quis autem vel eums reprehe voluptate velit esse quamnihy molestiae consequature', 'element-camp'),
                    ],
                ],
            ]
        );
        $repeater2 = new \Elementor\Repeater();
        $repeater2->add_control(
            'service_image',
            [
                'label' => esc_html__('Service Image', 'element-camp'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ]
            ]
        );
        $repeater2->add_control(
            'button_switcher',
            [
                'label' => esc_html__('Button Switcher', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
            ]
        );
        $repeater2->add_control(
            'btn_text',
            [
                'label' => esc_html__('Button Text', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__('Click me', 'element-camp'),
                'condition' => ['button_switcher' => 'yes'],
            ]
        );
        $repeater2->add_control(
            'btn_link',
            [
                'label' => esc_html__('Button Link', 'element-camp'),
                'type' => Controls_Manager::URL,
                'condition' => ['button_switcher' => 'yes'],
            ]
        );
        $repeater2->add_control(
            'selected_icon',
            [
                'label' => esc_html__('Icon', 'element-camp'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'skin' => 'inline',
                'label_block' => false,
                'condition' => ['button_switcher' => 'yes'],
            ]
        );
        $repeater2->add_control(
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
        $repeater2->add_control(
            'show_title_content',
            [
                'label' => esc_html__('Show Title Content', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'default' => 'no',
            ]
        );
        $repeater2->add_control(
            'title_content',
            [
                'label' => esc_html__('Title Content', 'element-camp'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'label_block' => true,
                'condition' => [
                    'show_title_content' => 'yes',
                ]
            ]
        );
        $repeater2->add_control(
            'show_description_content',
            [
                'label' => esc_html__('Show Description Content', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'default' => 'no',
            ]
        );
        $repeater2->add_control(
            'description_content',
            [
                'label' => esc_html__('Description Content', 'element-camp'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'label_block' => true,
                'condition' => [
                    'show_description_content' => 'yes',
                ]
            ]
        );
        $repeater2->add_control(
            'right_left_content',
            [
                'label' => esc_html__('Right Left Content', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
            ]
        );
        $repeater2->add_control(
            'left_content',
            [
                'label' => esc_html__('Left Content', 'element-camp'),
                'type' => Controls_Manager::WYSIWYG,
                'condition' => ['right_left_content' => 'yes'],
                'default' => '<p>' . esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'element-camp') . '</p>',
            ]
        );
        $repeater2->add_control(
            'right_content',
            [
                'label' => esc_html__('Right Content', 'element-camp'),
                'type' => Controls_Manager::WYSIWYG,
                'condition' => ['right_left_content' => 'yes'],
                'default' => '<p>' . esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'element-camp') . '</p>',
            ]
        );
        $this->add_control(
            'nav_content',
            [
                'label' => esc_html__('Nav Content', 'element-camp'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater2->get_controls(),
                'default' => [
                    [
                        'button_switcher' => esc_html__('yes', 'element-camp'),
                        'btn_text' => esc_html__('Click Me', 'element-camp'),
                        'btn_link' => esc_html__('#0', 'element-camp'),
                    ],
                    [
                        'button_switcher' => esc_html__('yes', 'element-camp'),
                        'btn_text' => esc_html__('Click Me', 'element-camp'),
                        'btn_link' => esc_html__('#0', 'element-camp'),
                    ],
                    [
                        'button_switcher' => esc_html__('yes', 'element-camp'),
                        'btn_text' => esc_html__('Click Me', 'element-camp'),
                        'btn_link' => esc_html__('#0', 'element-camp'),
                    ],
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'row_style',
            [
                'label' => esc_html__('Row & Columns Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'row_display_direction',
            [
                'label' => esc_html__( 'Row Direction', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
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
                    'start' => 'flex-direction: row;',
                    'end' => 'flex-direction: row-reverse;',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-builder-tabs' => '{{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'row_margin',
            [
                'label' => esc_html__('Row Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .row' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );
        $this->add_responsive_control(
            'column_padding',
            [
                'label' => esc_html__('Column Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs div[class^="col-"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );
        $this->add_responsive_control(
            'tab_column_width',
            [
                'label' => esc_html__('Tabs Column Width', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-services-tabs .row .tab-column' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'tab_content_column_width',
            [
                'label' => esc_html__('Tab Content Column Width', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-services-tabs .row .content-column' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'tab_style',
            [
                'label' => esc_html__('Tab', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'tabs_column_padding',
            [
                'label' => esc_html__('Tabs Column Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-column' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );
        $this->add_responsive_control(
            'tab_item_column_width',
            [
                'label' => esc_html__('Tab Item Width', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'tab_item_column_height',
            [
                'label' => esc_html__('Tab Item Height', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'tab_item_column_max_height',
            [
                'label' => esc_html__('Tab Item Max Height', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item' => 'max-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'tab_item_column_index',
            [
                'label' => esc_html__('Z-index', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-column' => 'z-index: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'nav_heading',
            [
                'label' => esc_html__('Nav Pills', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );
        $this->add_responsive_control(
            'nav_display',
            [
                'label' => esc_html__('Nav Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'flex',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'inline-flex' => esc_html__('Inline Flex', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .nav' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'nav_display_position',
            [
                'label' => esc_html__('Nav Display Position', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'before' => [
                        'title' => esc_html__('Before', 'element-camp'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'after' => [
                        'title' => esc_html__('After', 'element-camp'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                    'start' => [
                        'title' => esc_html__('Start', 'element-camp'),
                        'icon' => "eicon-h-align-right",
                    ],
                    'end' => [
                        'title' => esc_html__('End', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-services-tabs .nav' => '{{VALUE}}',
                ],
                'condition' => ['nav_display' => ['flex', 'inline-flex']],
            ]
        );
        $this->add_responsive_control(
            'nav_justify_content',
            [
                'label' => esc_html__('Nav Justify Content', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'default' => '',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Start', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-start-h',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-center-h',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('End', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-end-h',
                    ],
                    'space-between' => [
                        'title' => esc_html__('Space Between', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-space-between-h',
                    ],
                    'space-around' => [
                        'title' => esc_html__('Space Around', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-space-around-h',
                    ],
                    'space-evenly' => [
                        'title' => esc_html__('Space Evenly', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-space-evenly-h',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .nav' => 'justify-content: {{VALUE}};',
                ],
                'condition' => ['nav_display' => ['flex', 'inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'nav_align_items',
            [
                'label' => esc_html__('Nav Align Items', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'default' => '',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Start', 'element-camp'),
                        'icon' => 'eicon-flex eicon-align-start-v',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'element-camp'),
                        'icon' => 'eicon-flex eicon-align-center-v',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('End', 'element-camp'),
                        'icon' => 'eicon-flex eicon-align-end-v',
                    ],
                    'stretch' => [
                        'title' => esc_html__('Stretch', 'element-camp'),
                        'icon' => 'eicon-flex eicon-align-stretch-v',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .nav' => 'align-items: {{VALUE}};',
                ],
                'condition' => ['nav_display' => ['flex', 'inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'nav_margin',
            [
                'label' => esc_html__('Nav Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'nav_padding',
            [
                'label' => esc_html__('Nav Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'nav_background',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-services-tabs .nav-pills',
            ]
        );
        $this->add_control(
            'nav_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'nav_pills_border',
                'selector' => '{{WRAPPER}} .tcgelements-services-tabs .tab-content',
            ]
        );
        $this->add_control(
            'nav_item_heading',
            [
                'label' => esc_html__('Nav Item Options', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'nav_item_display',
            [
                'label' => esc_html__('Nav Item Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'flex',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'inline-flex' => esc_html__('Inline Flex', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'nav_item_display_position',
            [
                'label' => esc_html__('Nav Item Display Position', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'before' => [
                        'title' => esc_html__('Before', 'element-camp'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'after' => [
                        'title' => esc_html__('After', 'element-camp'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                    'start' => [
                        'title' => esc_html__('Start', 'element-camp'),
                        'icon' => "eicon-h-align-right",
                    ],
                    'end' => [
                        'title' => esc_html__('End', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link' => '{{VALUE}}',
                ],
                'condition' => ['nav_item_display' => ['flex', 'inline-flex']],
            ]
        );
        $this->add_responsive_control(
            'nav_item_justify_content',
            [
                'label' => esc_html__('Nav Item Justify Content', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'default' => '',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Start', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-start-h',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-center-h',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('End', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-end-h',
                    ],
                    'space-between' => [
                        'title' => esc_html__('Space Between', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-space-between-h',
                    ],
                    'space-around' => [
                        'title' => esc_html__('Space Around', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-space-around-h',
                    ],
                    'space-evenly' => [
                        'title' => esc_html__('Space Evenly', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-space-evenly-h',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link' => 'justify-content: {{VALUE}};',
                ],
                'condition' => ['nav_item_display' => ['flex', 'inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'nav_item_align_items',
            [
                'label' => esc_html__('Nav Item Align Items', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'default' => '',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Start', 'element-camp'),
                        'icon' => 'eicon-flex eicon-align-start-v',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'element-camp'),
                        'icon' => 'eicon-flex eicon-align-center-v',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('End', 'element-camp'),
                        'icon' => 'eicon-flex eicon-align-end-v',
                    ],
                    'stretch' => [
                        'title' => esc_html__('Stretch', 'element-camp'),
                        'icon' => 'eicon-flex eicon-align-stretch-v',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link' => 'align-items: {{VALUE}};',
                ],
                'condition' => ['nav_item_display' => ['flex', 'inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'nav_item_align_text',
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
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'tab_link_margin',
            [
                'label' => esc_html__('Tab Item Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'tab_link_padding',
            [
                'label' => esc_html__('Tab Item Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'tab_link_width',
            [
                'label' => esc_html__('Tab Link Width', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'tab_link_height',
            [
                'label' => esc_html__('Tab Link Height', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tab_link_typography',
                'label' => esc_html__('Tab Item', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link',
            ]
        );
        $this->add_control(
            'writing_mode',
            [
                'label' => esc_html__('Writing Mode', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'horizontal-tb' => esc_html__('Horizontal tb', 'element-camp'),
                    'vertical-lr' => esc_html__('Vertical lr', 'element-camp'),
                    'vertical-rl' => esc_html__('Vertical rl', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link' => 'writing-mode: {{VALUE}};'
                ]
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'tab_item_border',
                'label' => esc_html__('Tab Border', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item',
            ]
        );
        $this->add_control(
            'tab_link_border_radius',
            [
                'label' => esc_html__('Tab Item Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'separator' => 'after',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'tab_item_tabs',
        );

        $this->start_controls_tab(
            'Normal',
            [
                'label'   => esc_html__('Normal', 'element-camp'),
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'tab_link_border',
                'label' => esc_html__('Tab Border', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link',
            ]
        );

        $this->add_control(
            'tab_link_color',
            [
                'label' => esc_html__('Tab Item Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'tab_link_background',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link',
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'Active',
            [
                'label'   => esc_html__('Active', 'element-camp'),
            ]
        );
        $this->add_control(
            'active_tab_link_color',
            [
                'label' => esc_html__('Active Tab Item Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link.active' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'active_tab_link_background',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link.active',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'active_tab_link_border',
                'selector' => '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link.active',
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'first_item',
            [
                'label'   => esc_html__('First Item', 'element-camp'),
            ]
        );

        $this->add_responsive_control(
            'first_item_tab_link_padding',
            [
                'label' => esc_html__('First Item Tab Item Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item:first-of-type .nav-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'first_tab_link_border',
                'selector' => '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item:first-of-type .nav-link',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'last_item',
            [
                'label'   => esc_html__('Last Item', 'element-camp'),
            ]
        );

        $this->add_responsive_control(
            'last_item_tab_link_padding',
            [
                'label' => esc_html__('Last Item Tab Item Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item:last-of-type .nav-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'last_item_tab_link_border',
                'label' => esc_html__('Last Item Border', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item:last-of-type .nav-link',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'border_tabs_style',
            [
                'label' => esc_html__('Border Style', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__('None', 'textdomain'),
                    'circles-line'  => esc_html__('Circles Line', 'element-camp'),
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'lines_background_color',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-services-tabs.circles-line ul:before',
                'condition' => [
                    'border_tabs_style' => 'circles-line',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'circles_background_color',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-services-tabs.circles-line ul .nav-link:before',
                'condition' => [
                    'border_tabs_style' => 'circles-line',
                ],
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'service_style',
            [
                'label' => esc_html__('Service Info', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'service_column_padding',
            [
                'label' => esc_html__('Service Column Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .content-column' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );
        $this->add_control(
            'service_content_position',
            [
                'label' => esc_html__('Service Content Position', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'absolute' => esc_html__('Absolute', 'element-camp'),
                    'relative' => esc_html__('Relative', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .content-column' => 'position: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'service_content_offset_orientation_h',
            [
                'label' => esc_html__('Horizontal Orientation', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => esc_html__('Start', 'element-camp'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'end' => [
                        'title' => esc_html__('End', 'element-camp'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'condition' => [
                    'service_content_position!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'service_content_offset_x',
            [
                'label' => esc_html__('Offset', 'element-camp'),
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
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .content-column' => 'left: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'service_content_offset_orientation_h' => 'start',
                    'service_content_position!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'service_content_offset_x_end',
            [
                'label' => esc_html__('Offset', 'element-camp'),
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
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .content-column' => 'right: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'service_content_offset_orientation_h' => 'end',
                    'service_content_position!' => '',
                ],
            ]
        );
        $this->add_control(
            'service_content_offset_orientation_v',
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
                'condition' => [
                    'service_content_position!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'service_content_offset_y',
            [
                'label' => esc_html__('Offset', 'element-camp'),
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
                'size_units' => ['px', '%', 'em', 'rem', 'vh', 'vw', 'custom'],
                'default' => [
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .content-column' => 'top: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'service_content_offset_orientation_v' => 'start',
                    'service_content_position!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'service_content_offset_y_end',
            [
                'label' => esc_html__('Offset', 'element-camp'),
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
                'size_units' => ['px', '%', 'em', 'rem', 'vh', 'vw', 'custom'],
                'default' => [
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .content-column' => 'bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'service_content_offset_orientation_v' => 'end',
                    'service_content_position!' => '',
                ],
            ]
        );
        $this->add_control(
            'service_column_index',
            [
                'label' => esc_html__('Z-index', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .content-column' => 'z-index: {{SIZE}};',
                ],
            ]
        );
        $this->start_controls_tabs(
            'title_style_tabs'
        );

        $this->start_controls_tab(
            'title_style_normal_tab',
            [
                'label' => esc_html__('Normal', 'element-camp'),
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__('Title', 'element-camp'),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_ACCENT,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link .service-title',
            ]
        );
        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Title Color', 'element-camp'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link .service-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'title_style_active_tab',
            [
                'label' => esc_html__('Active', 'element-camp'),
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_active_typography',
                'label' => esc_html__('Title', 'element-camp'),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_ACCENT,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link.active .service-title',
            ]
        );
        $this->add_control(
            'title_active_color',
            [
                'label' => esc_html__('Title Color', 'element-camp'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link.active .service-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Title Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'separator' => 'after',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link .service-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'label' => esc_html__('Description', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link .service-description',
            ]
        );
        $this->add_control(
            'description_color',
            [
                'label' => esc_html__('Description Color', 'element-camp'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link .service-description' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'desc_margin',
            [
                'label' => esc_html__('Description Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'separator' => 'after',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link .service-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'desc_width',
            [
                'label' => esc_html__( 'Description Width', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link .service-description' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'icon_style',
            [
                'label' => esc_html__('Icon Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );
        $this->add_responsive_control(
            'icon_display',
            [
                'label' => esc_html__('Icon Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'flex',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'inline-flex' => esc_html__('Inline Flex', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link .icon' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'icon_justify_content',
            [
                'label' => esc_html__('Icon Justify Content', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'default' => '',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Start', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-start-h',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-center-h',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('End', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-end-h',
                    ],
                    'space-between' => [
                        'title' => esc_html__('Space Between', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-space-between-h',
                    ],
                    'space-around' => [
                        'title' => esc_html__('Space Around', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-space-around-h',
                    ],
                    'space-evenly' => [
                        'title' => esc_html__('Space Evenly', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-space-evenly-h',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link .icon' => 'justify-content: {{VALUE}};',
                ],
                'condition' => ['icon_display' => ['flex', 'inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'icon_align_items',
            [
                'label' => esc_html__('Icon Align Items', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'default' => '',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Start', 'element-camp'),
                        'icon' => 'eicon-flex eicon-align-start-v',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'element-camp'),
                        'icon' => 'eicon-flex eicon-align-center-v',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('End', 'element-camp'),
                        'icon' => 'eicon-flex eicon-align-end-v',
                    ],
                    'stretch' => [
                        'title' => esc_html__('Stretch', 'element-camp'),
                        'icon' => 'eicon-flex eicon-align-stretch-v',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link .icon' => 'align-items: {{VALUE}};',
                ],
                'condition' => ['icon_display' => ['flex', 'inline-flex']],
                'responsive' => true,
            ]
        );

        $this->add_responsive_control(
            'icon_size_options',
            [
                'label' => esc_html__('Size', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'default' => '',
                'options' => [
                    'none' => [
                        'title' => esc_html__('None', 'element-camp'),
                        'icon' => 'eicon-ban',
                    ],
                    'grow' => [
                        'title' => esc_html__('Grow', 'element-camp'),
                        'icon' => 'eicon-grow',
                    ],
                    'shrink' => [
                        'title' => esc_html__('Shrink', 'element-camp'),
                        'icon' => 'eicon-shrink',
                    ],
                    'custom' => [
                        'title' => esc_html__('Custom', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link .icon' => '{{VALUE}};',
                ],
                'responsive' => true,
            ]
        );

        $this->add_responsive_control(
            'icon_flex_grow',
            [
                'label' => esc_html__('Flex Grow', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link .icon' => 'flex-grow: {{VALUE}};',
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
                'label' => esc_html__('Flex Shrink', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link .icon' => 'flex-shrink: {{VALUE}};',
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
            'icon_align_text',
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
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link .icon' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_margin',
            [
                'label' => esc_html__('Icon Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link .icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_padding',
            [
                'label' => esc_html__('Icon Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link .icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_container_width',
            [
                'label' => esc_html__('Container Width', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link .icon' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_width',
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
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link .icon img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_container_height',
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
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link .icon' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_height',
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
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link .icon img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_object-fit',
            [
                'label' => esc_html__('Object Fit', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'icon_height[size]!' => '',
                ],
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'fill' => esc_html__('Fill', 'element-camp'),
                    'cover' => esc_html__('Cover', 'element-camp'),
                    'contain' => esc_html__('Contain', 'element-camp'),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link .icon img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_object-position',
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
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link .icon img' => 'object-position: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'icon_container_background_color',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link .icon',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'icon_container_border',
                'selector' => '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link .icon',
            ]
        );

        $this->add_responsive_control(
            'icon_container_border_radius',
            [
                'label' => esc_html__('Container Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link .icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'icon_border_radius',
            [
                'label' => esc_html__('Image Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .nav-pills .nav-item .nav-link .icon img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'image_style',
            [
                'label' => esc_html__('image Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );
        $this->add_responsive_control(
            'image_display',
            [
                'label' => esc_html__('image Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'flex',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'inline-flex' => esc_html__('Inline Flex', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .service-image' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'image_justify_content',
            [
                'label' => esc_html__('image Justify Content', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'default' => '',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Start', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-start-h',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-center-h',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('End', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-end-h',
                    ],
                    'space-between' => [
                        'title' => esc_html__('Space Between', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-space-between-h',
                    ],
                    'space-around' => [
                        'title' => esc_html__('Space Around', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-space-around-h',
                    ],
                    'space-evenly' => [
                        'title' => esc_html__('Space Evenly', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-space-evenly-h',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .service-image' => 'justify-content: {{VALUE}};',
                ],
                'condition' => ['image_display' => ['flex', 'inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'image_align_items',
            [
                'label' => esc_html__('image Align Items', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'default' => '',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Start', 'element-camp'),
                        'icon' => 'eicon-flex eicon-align-start-v',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'element-camp'),
                        'icon' => 'eicon-flex eicon-align-center-v',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('End', 'element-camp'),
                        'icon' => 'eicon-flex eicon-align-end-v',
                    ],
                    'stretch' => [
                        'title' => esc_html__('Stretch', 'element-camp'),
                        'icon' => 'eicon-flex eicon-align-stretch-v',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .service-image' => 'align-items: {{VALUE}};',
                ],
                'condition' => ['image_display' => ['flex', 'inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'image_align_text',
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
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .service-image' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_margin',
            [
                'label' => esc_html__('image Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .service-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_padding',
            [
                'label' => esc_html__('image Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .service-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'container_width',
            [
                'label' => esc_html__('Container Width', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .service-image' => 'width: {{SIZE}}{{UNIT}};',
                ],
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
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .service-image img' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .service-image' => 'height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .service-image img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'object-fit',
            [
                'label' => esc_html__('Object Fit', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'height[size]!' => '',
                ],
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'fill' => esc_html__('Fill', 'element-camp'),
                    'cover' => esc_html__('Cover', 'element-camp'),
                    'contain' => esc_html__('Contain', 'element-camp'),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .service-image img' => 'object-fit: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .service-image img' => 'object-position: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'container_background_color',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-services-tabs .tab-content .service-image',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'selector' => '{{WRAPPER}} .tcgelements-services-tabs .tab-content .service-image',
            ]
        );

        $this->add_responsive_control(
            'container_border_radius',
            [
                'label' => esc_html__('Container Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .service-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'img_border_radius',
            [
                'label' => esc_html__('Image Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .service-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'section_services_overlay_style',
            [
                'label' => __('Services Overlay Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'services_overlay_enable',
            [
                'label' => esc_html__('Enable Overlay', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'return_value' => 'yes',
                'default' => 'no',
                'description' => esc_html__('Enable overlay effects for Services', 'element-camp'),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'normal_services_overlay_background',
                'label' => esc_html__('Overlay Background', 'element-camp'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-services-tabs .tab-content.overlay-enabled .service-image::before',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'color' => [
                        'default' => 'rgba(0,0,0,0.3)',
                    ],
                ],
                'condition' => [
                    'services_overlay_enable' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'normal_services_overlay_opacity',
            [
                'label' => esc_html__('Overlay Opacity', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'default' => [
                    'size' => 0.3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content.overlay-enabled .service-image::before' => 'opacity: {{SIZE}};',
                ],
                'condition' => [
                    'services_overlay_enable' => 'yes',
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
            'position_for_button',
            [
                'label' => esc_html__('Position', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'absolute' => esc_html__('Absolute', 'element-camp'),
                    'relative' => esc_html__('Relative', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .butn' => 'position: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'button_offset_orientation_h',
            [
                'label' => esc_html__('Horizontal Orientation', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => esc_html__('Start', 'element-camp'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'end' => [
                        'title' => esc_html__('End', 'element-camp'),
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
                'label' => esc_html__('Offset', 'element-camp'),
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
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .butn' => 'left: {{SIZE}}{{UNIT}}',
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
                'label' => esc_html__('Offset', 'element-camp'),
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
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .butn' => 'right: {{SIZE}}{{UNIT}}',
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
                'condition' => [
                    'position_for_button!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_offset_y',
            [
                'label' => esc_html__('Offset', 'element-camp'),
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
                'size_units' => ['px', '%', 'em', 'rem', 'vh', 'vw', 'custom'],
                'default' => [
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .butn' => 'top: {{SIZE}}{{UNIT}}',
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
                'label' => esc_html__('Offset', 'element-camp'),
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
                'size_units' => ['px', '%', 'em', 'rem', 'vh', 'vw', 'custom'],
                'default' => [
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .butn' => 'bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'button_offset_orientation_v' => 'end',
                    'position_for_button!' => '',
                ],
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
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .butn' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'justify_content',
            [
                'label' => esc_html__('Justify Content', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'default' => '',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Start', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-start-h',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-center-h',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('End', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-end-h',
                    ],
                    'space-between' => [
                        'title' => esc_html__('Space Between', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-space-between-h',
                    ],
                    'space-around' => [
                        'title' => esc_html__('Space Around', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-space-around-h',
                    ],
                    'space-evenly' => [
                        'title' => esc_html__('Space Evenly', 'element-camp'),
                        'icon' => 'eicon-flex eicon-justify-space-evenly-h',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .butn' => 'justify-content: {{VALUE}};',
                ],
                'condition' => ['button_display' => ['flex', 'inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'align_items',
            [
                'label' => esc_html__('Align Items', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'default' => '',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Start', 'element-camp'),
                        'icon' => 'eicon-flex eicon-align-start-v',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'element-camp'),
                        'icon' => 'eicon-flex eicon-align-center-v',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('End', 'element-camp'),
                        'icon' => 'eicon-flex eicon-align-end-v',
                    ],
                    'stretch' => [
                        'title' => esc_html__('Stretch', 'element-camp'),
                        'icon' => 'eicon-flex eicon-align-stretch-v',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .butn' => 'align-items: {{VALUE}};',
                ],
                'condition' => ['button_display' => ['flex', 'inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'button_width',
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
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .butn' => 'width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_height',
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
                'size_units' => ['px', 'vh', '%', 'custom'],
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
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .butn' => 'height: {{SIZE}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .tcgelements-services-tabs .tab-content .butn',
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
            'button_text_color',
            [
                'label' => esc_html__('Text Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .butn' => 'color: {{VALUE}}; fill: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .butn .butn-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .butn .butn-icon svg' => 'fill: {{VALUE}};',
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
                'selector' => '{{WRAPPER}} .tcgelements-services-tabs .tab-content .butn',
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
                'label' => esc_html__('Opacity', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .butn' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-services-tabs .tab-content .butn',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'selector' => '{{WRAPPER}} .tcgelements-services-tabs .tab-content .butn',
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
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .butn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'outline',
            [
                'label' => esc_html__('Outline', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'element-camp'),
                    'solid' => esc_html__('Solid', 'element-camp'),
                    'dashed' => esc_html__('Dashed', 'element-camp'),
                    'dotted' => esc_html__('Dotted', 'element-camp'),
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
                'label' => esc_html__('Outline Width', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .butn' => 'outline: {{outline_width.SIZE}}{{outline_width.UNIT}} {{outline.VALUE}} {{outline_color.VALUE}}',
                ],
                'condition' => [
                    'outline!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'outline_offset',
            [
                'label' => esc_html__('Outline Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 20,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .butn' => 'outline-offset: {{SIZE}}{{UNIT}};',
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
            'hover_color',
            [
                'label' => esc_html__('Text Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content:hover .butn' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content:hover .butn svg' => 'fill: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content:hover .butn .butn-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content:hover .butn .butn-icon svg' => 'fill: {{VALUE}};',
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
                'selector' => '{{WRAPPER}} .tcgelements-services-tabs .tab-content:hover .butn',
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
                'selector' => '{{WRAPPER}} .tcgelements-services-tabs .tab-content:hover .butn',
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
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content:hover .butn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'hover_button_opacity',
            [
                'label' => esc_html__('Opacity', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content:hover .butn' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow_hover',
                'selector' => '{{WRAPPER}} .tcgelements-services-tabs .tab-content:hover .butn',
            ]
        );

        $this->add_control(
            'outline_hover',
            [
                'label' => esc_html__('Outline', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'element-camp'),
                    'solid' => esc_html__('Solid', 'element-camp'),
                    'dashed' => esc_html__('Dashed', 'element-camp'),
                    'dotted' => esc_html__('Dotted', 'element-camp'),
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
                'label' => esc_html__('Outline Width', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content:hover .butn' => 'outline: {{outline_width_hover.SIZE}}{{outline_width_hover.UNIT}} {{outline_hover.VALUE}} {{outline_color_hover.VALUE}}',
                ],
                'condition' => [
                    'outline_hover!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'outline_offset_hover',
            [
                'label' => esc_html__('Outline Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 20,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content:hover .butn' => 'outline-offset: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'outline_hover!' => 'none',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => esc_html__('Button Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .butn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_responsive_control(
            'button_icon_size',
            [
                'label' => esc_html__('Icon size', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'custom', 'rem', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .butn .butn-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .butn .butn-icon svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'right_left_content_style',
            [
                'label' => esc_html__('Right Left Content Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );
        $this->add_responsive_control(
            'right_left_content_wrapper_margin',
            [
                'label' => esc_html__('Right Left Content Wrapper Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .right-left-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'right_left_content_padding',
            [
                'label' => esc_html__('Right Left Content Text Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .right-left-content .right-content > *' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .right-left-content .left-content > *' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'right_left_content_margin',
            [
                'label' => esc_html__('Right Left Content Text Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .right-left-content .right-content > *' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .right-left-content .left-content > *' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'right_left_content_typography',
                'label' => esc_html__('Right Left Content Text Typography', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-services-tabs .tab-content .right-left-content .right-content > * ,{{WRAPPER}} .tcgelements-services-tabs .tab-content .right-left-content .left-content > *',
            ]
        );
        $this->add_control(
            'right_left_content_color',
            [
                'label' => esc_html__('Right Left Content Text Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .right-left-content .right-content > *' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .right-left-content .left-content > *' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'info_content_style',
            [
                'label' => esc_html__('Content Info', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs(
            'info_content_style_tabs'
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_content_typography',
                'label' => esc_html__('Title', 'element-camp'),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_ACCENT,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-services-tabs .tab-content .title-content',
            ]
        );
        $this->add_control(
            'title_content_color',
            [
                'label' => esc_html__('Title Color', 'element-camp'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .title-content' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_content_margin',
            [
                'label' => esc_html__('Title Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'separator' => 'after',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .title-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_content_typography',
                'label' => esc_html__('Description', 'element-camp'),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_ACCENT,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-services-tabs .tab-content .content-description',
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'description_content_color',
            [
                'label' => esc_html__('Description Color', 'element-camp'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .content-description' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'description_content_margin',
            [
                'label' => esc_html__('Description Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'separator' => 'after',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-tabs .tab-content .content-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'animation_section',
            [
                'label' => esc_html__('Animations', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'animation_option',
            [
                'label' => __( 'Animation', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'fade-in-up' => __( 'Fade In Up', 'element-camp' ),
                    'fade-in' => __( 'Fade In', 'element-camp' ),
                ],
                'default' => 'in-up',
            ]
        );
        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $id = uniqid();
        $interaction = $settings['services_tabs_controls'];
?>
        <div class="tcgelements-services-tabs <?= esc_attr($interaction); ?><?= ($settings["border_tabs_style"] == "circles-line") ? ' circles-line' : ''; ?>">
            <div class="row justify-content-between">
                <div class="tab-column col-lg-5">
                    <ul class="nav nav-pills" id="pills-tab-<?= esc_attr($id) ?>" role="tablist">
                        <?php foreach ($settings['nav_tabs'] as $index => $item) : ?>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php if ($index == 0) echo 'active' ?>" id="pills-tab<?= $index ?>-tab" data-bs-toggle="pill" data-bs-target="#pills-tab<?= $index ?>">
                                    <?php if (!empty($item['service_icon']['url'])) : ?>
                                        <span class="icon">
                                            <img src="<?= esc_url($item['service_icon']['url']) ?>" alt="<?php if (!empty($item['service_icon']['alt'])) echo esc_attr($item['service_icon']['alt']); ?>">
                                        </span>
                                    <?php endif; ?>
                                    <span class="cont">
                                        <div class="service-title"> <?= esc_html($item['service_title']) ?> </div>
                                        <?php if ($item['service_description_switcher'] == 'yes') : ?>
                                            <div class="service-description"> <?= esc_html($item['service_description']) ?> </div>
                                        <?php endif; ?>
                                    </span>
                                </button>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="content-column col-lg-6">
                    <div class="tab-content <?= $settings['services_overlay_enable'] == 'yes' ? 'overlay-enabled' : ''  ?>" id="pills-tabContent-<?= esc_attr($id) ?>">
                        <?php foreach ($settings['nav_content'] as $index => $item) : ?>
                            <div class="tab-pane fade <?php if ($index == 0) echo 'active show' ?>" id="pills-tab<?= $index ?>">
                                <div class="service-image <?php echo $settings['animation_option']; ?>">
                                    <img src="<?= esc_url($item['service_image']['url']) ?>" alt="<?php if (!empty($item['service_image']['alt'])) echo esc_attr($item['service_image']['alt']); ?>">
                                    <?php if ($item['button_switcher'] == 'yes') : ?>
                                        <a class="butn" href="<?= esc_url($item['btn_link']['url']) ?>" <?php if ($item['btn_link']['is_external']) echo 'target="_blank"'; ?>>
                                            <?php if (!empty($item['selected_icon']['value']) and ($item['icon_align'] == 'left')) : ?>
                                                <span class="butn-icon">
                                                    <?php Icons_Manager::render_icon($item['selected_icon'], ['aria-hidden' => 'true']); ?>
                                                </span>
                                            <?php endif; ?>
                                            <span class="text">
                                                <?php $this->print_unescaped_setting('btn_text'); ?>
                                            </span>
                                            <?php if (!empty($item['selected_icon']['value'])  and ($item['icon_align'] == 'right')) : ?>
                                                <span class="butn-icon">
                                                    <?php Icons_Manager::render_icon($item['selected_icon'], ['aria-hidden' => 'true']); ?>
                                                </span>
                                            <?php endif; ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <?php if ($item['show_title_content'] == "yes" && !empty($item['title_content'])) { ?>
                                    <span class="title-content d-block"> <?= esc_html($item['title_content']) ?> </span>
                                <?php }
                                if ($item['show_description_content'] == "yes" && !empty($item['description_content'])) { ?>
                                    <span class="content-description d-block"> <?= esc_html($item['description_content']) ?> </span>
                                <?php }
                                if ($item['right_left_content'] == 'yes') : ?>
                                    <div class="right-left-content">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="left-content">
                                                    <?= $item['left_content']; ?>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="right-content">
                                                    <?= $item['right_content']; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
}
