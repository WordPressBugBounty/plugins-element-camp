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

if (!defined('ABSPATH')) exit;

class ElementCamp_Map_Items extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'tcgelements-map-items';
    }

    public function get_title()
    {
        return esc_html__('Map Items', 'element-camp');
    }

    public function get_icon()
    {
        return 'eicon-map-pin tce-widget-badge';
    }

    public function get_categories()
    {
        return ['elementcamp-elements'];
    }

    public function get_script_depends()
    {
        return ['tcgelements-map-items'];
    }

    protected function _register_controls()
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Settings', 'element-camp'),
            ]
        );

        // Style Selection Control
        $this->add_control(
            'map_style',
            [
                'label' => esc_html__('Map Style', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => esc_html__('Default (Tooltip)', 'element-camp'),
                    'cards' => esc_html__('Cards Style', 'element-camp'),
                ],
                'description' => esc_html__('Choose the display style for map items', 'element-camp'),
            ]
        );

        $this->add_control(
            'map_image',
            [
                'label' => esc_html__('Background For Mapping', 'element-camp'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        // Style instructions
        $this->add_control(
            'style_notice',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => '<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px; border-radius: 8px; margin: 10px 0;">
                    <h4 style="margin: 0 0 10px 0; color: white;">📍 Map Styles Guide</h4>
                    <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 200px;">
                            <strong>🎯 Default Style:</strong><br>
                            <small>• Hover tooltips<br>• Right/Bottom positioning<br>• Lightweight & clean</small>
                        </div>
                        <div style="flex: 1; min-width: 200px;">
                            <strong>🎨 Cards Style:</strong><br>
                            <small>• Rich content cards<br>• Left/Top positioning<br>• Images & buttons</small>
                        </div>
                    </div>
                </div>',
            ]
        );

        // DEFAULT STYLE REPEATER
        $default_repeater = new Repeater();

        $default_repeater->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'element-camp'),
                'type' => Controls_Manager::TEXTAREA,
                'placeholder' => esc_html__('Type Text Here', 'element-camp'),
            ]
        );

        $default_repeater->add_control(
            'description',
            [
                'label' => esc_html__('Description', 'element-camp'),
                'type' => Controls_Manager::TEXTAREA,
                'placeholder' => esc_html__('Type Text Here', 'element-camp'),
            ]
        );

        $default_repeater->add_control(
            'position_heading',
            [
                'label' => esc_html__('Position Settings', 'element-camp'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $default_repeater->add_responsive_control(
            'right_coordinate',
            [
                'label' => esc_html__('Right Coordinate (%)', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'default' => 50,
                'min' => 0,
                'max' => 100,
                'responsive' => true,
            ]
        );

        $default_repeater->add_responsive_control(
            'bottom_coordinate',
            [
                'label' => esc_html__('Bottom Coordinate (%)', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'default' => 50,
                'min' => 0,
                'max' => 100,
                'responsive' => true,
            ]
        );

        // CARDS STYLE REPEATER
        $cards_repeater = new Repeater();

        $cards_repeater->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'element-camp'),
                'type' => Controls_Manager::TEXTAREA,
                'placeholder' => esc_html__('Type Title Here', 'element-camp'),
            ]
        );

        $cards_repeater->add_control(
            'description',
            [
                'label' => esc_html__('Description', 'element-camp'),
                'type' => Controls_Manager::TEXTAREA,
                'placeholder' => esc_html__('Type Description Here', 'element-camp'),
            ]
        );

        $cards_repeater->add_control(
            'location_text',
            [
                'label' => esc_html__('Location Text', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__('e.g., Warsaw, PL', 'element-camp'),
            ]
        );

        $cards_repeater->add_control(
            'content_heading',
            [
                'label' => esc_html__('Visual Content', 'element-camp'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $cards_repeater->add_control(
            'item_image',
            [
                'label' => esc_html__('Item Image', 'element-camp'),
                'type' => Controls_Manager::MEDIA,
            ]
        );

        $cards_repeater->add_control(
            'marker_image',
            [
                'label' => esc_html__('Marker Image', 'element-camp'),
                'type' => Controls_Manager::MEDIA,
            ]
        );

        $cards_repeater->add_control(
            'button_heading',
            [
                'label' => esc_html__('Button Settings', 'element-camp'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $cards_repeater->add_control(
            'button_text',
            [
                'label' => esc_html__('Button Text', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Join Team', 'element-camp'),
            ]
        );

        $cards_repeater->add_control(
            'button_animation',
            [
                'label' => esc_html__('Button Animation', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__('None', 'element-camp'),
                    'hvr_text' => esc_html__('Hover Text Transform', 'element-camp'),
                ],
            ]
        );

        $cards_repeater->add_control(
            'button_hover_text',
            [
                'label' => esc_html__('Hover Text', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Contact Us', 'element-camp'),
                'condition' => [
                    'button_animation' => 'hvr_text'
                ],
                'description' => esc_html__('Text that appears on hover', 'element-camp'),
            ]
        );

        $cards_repeater->add_control(
            'button_link',
            [
                'label' => esc_html__('Button Link', 'element-camp'),
                'type' => Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'element-camp'),
            ]
        );

        $cards_repeater->add_control(
            'position_heading',
            [
                'label' => esc_html__('Position Settings', 'element-camp'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $cards_repeater->add_responsive_control(
            'left_coordinate',
            [
                'label' => esc_html__('Left Position (%)', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'default' => 50,
                'min' => 0,
                'max' => 100,
                'responsive' => true,
            ]
        );

        $cards_repeater->add_responsive_control(
            'top_coordinate',
            [
                'label' => esc_html__('Top Position (%)', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'default' => 50,
                'min' => 0,
                'max' => 100,
                'responsive' => true,
            ]
        );

        // DEFAULT STYLE ITEMS
        $this->add_control(
            'default_map_items',
            [
                'label' => esc_html__('Map Items', 'element-camp'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $default_repeater->get_controls(),
                'condition' => [
                    'map_style' => 'default'
                ],
                'default' => [
                    [
                        'title' => esc_html__('Item 1', 'element-camp'),
                        'description' => esc_html__('Description 1', 'element-camp'),
                        'right_coordinate' => 61,
                        'bottom_coordinate' => 19,
                    ],
                    [
                        'title' => esc_html__('Item 2', 'element-camp'),
                        'description' => esc_html__('Description 2', 'element-camp'),
                        'right_coordinate' => 58,
                        'bottom_coordinate' => 50,
                    ],
                    [
                        'title' => esc_html__('Item 3', 'element-camp'),
                        'description' => esc_html__('Description 3', 'element-camp'),
                        'right_coordinate' => 80,
                        'bottom_coordinate' => 65,
                    ],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        // CARDS STYLE ITEMS
        $this->add_control(
            'cards_map_items',
            [
                'label' => esc_html__('Map Items', 'element-camp'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $cards_repeater->get_controls(),
                'condition' => [
                    'map_style' => 'cards'
                ],
                'default' => [
                    [
                        'title' => esc_html__('Warsaw', 'element-camp'),
                        'description' => esc_html__('Creative Team EU Location', 'element-camp'),
                        'location_text' => esc_html__('Warsaw, PL', 'element-camp'),
                        'button_text' => esc_html__('Join Team', 'element-camp'),
                        'left_coordinate' => 65,
                        'top_coordinate' => 5,
                    ],
                    [
                        'title' => esc_html__('Mexico', 'element-camp'),
                        'description' => esc_html__('Creative Team Americas Location', 'element-camp'),
                        'location_text' => esc_html__('Mexico City, MX', 'element-camp'),
                        'button_text' => esc_html__('Join Team', 'element-camp'),
                        'left_coordinate' => 15,
                        'top_coordinate' => 55,
                    ],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        // Legacy support notice
        $this->add_control(
            'legacy_notice',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => '<div style="background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 12px; border-radius: 4px; margin: 10px 0;">
                    <strong>💡 Note:</strong> If you have existing map items, they will continue to work. Switch between styles to access the appropriate item settings.
                </div>',
            ]
        );

        $this->end_controls_section();

        // Style Sections
        $this->register_default_style_controls();
        $this->register_cards_style_controls();
    }

    private function register_default_style_controls()
    {
        $this->start_controls_section(
            'section_default_style',
            [
                'label' => esc_html__('Default Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'map_style' => 'default'
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'map_item_dot_background',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-map-items.default .map-main .map-item .dot',
                'fields_options' => [
                    'background' => [
                        'label' => esc_html_x('Dot Background Color', 'Background Control', 'element-camp'),
                    ]
                ]
            ]
        );

        $this->add_responsive_control(
            'map_item_padding',
            [
                'label' => esc_html__('Map Item Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-map-items.default .map-main .map-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'info_padding',
            [
                'label' => esc_html__('Info Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-map-items.default .map-main .map-item .info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'map_item_background',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-map-items.default .map-main .map-item .info',
                'fields_options' => [
                    'background' => [
                        'label' => esc_html_x('Info Background Color', 'Background Control', 'element-camp'),
                    ]
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'info_after_border',
                'selector' => '{{WRAPPER}} .tcgelements-map-items.default .map-main .map-item::after',
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__('Title', 'element-camp'),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-map-items.default .map-main .map-item .info .title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-map-items.default .map-main .map-item .info .title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Title Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-map-items.default .map-main .map-item .info .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'separator_border1',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'desc_typography',
                'label' => esc_html__('Description', 'element-camp'),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-map-items.default .map-main .map-item .info .description',
            ]
        );

        $this->add_control(
            'desc_color',
            [
                'label' => esc_html__( 'Description Color', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-map-items.default .map-main .map-item .info .description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'desc_margin',
            [
                'label' => esc_html__('Description Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-map-items.default .map-main .map-item .info .description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function register_cards_style_controls()
    {
        // Cards Container Style
        $this->start_controls_section(
            'section_cards_container_style',
            [
                'label' => esc_html__('Container', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'map_style' => 'cards'
                ]
            ]
        );

        $this->add_responsive_control(
            'cards_container_padding',
            [
                'label' => esc_html__('Container Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-map-items.cards .map-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Cards Style
        $this->start_controls_section(
            'section_cards_style',
            [
                'label' => esc_html__('Cards Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'map_style' => 'cards'
                ]
            ]
        );

        $this->add_responsive_control(
            'card_width',
            [
                'label' => esc_html__('Card Width', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 400,
                        'step' => 10,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 330,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-map-items.cards .item-body' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'card_background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-map-items.cards .item-body',
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label' => esc_html__('Card Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-map-items.cards .item-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'card_border',
                'selector' => '{{WRAPPER}} .tcgelements-map-items.cards .item-body',
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-map-items.cards .item-body' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_box_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-map-items.cards .item-body',
            ]
        );

        $this->end_controls_section();

        // Cards Typography
        $this->start_controls_section(
            'section_cards_typography',
            [
                'label' => esc_html__('Typography', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'map_style' => 'cards'
                ]
            ]
        );

        // Head text (location)
        $this->add_control(
            'cards_head_text_heading',
            [
                'label' => esc_html__('Location Text', 'element-camp'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'cards_head_text_typography',
                'selector' => '{{WRAPPER}} .tcgelements-map-items.cards .item-head .title',
            ]
        );

        $this->add_control(
            'cards_head_text_color',
            [
                'label' => esc_html__('Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-map-items.cards .item-head .title' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Body location text
        $this->add_control(
            'cards_body_location_heading',
            [
                'label' => esc_html__('Body Location Text', 'element-camp'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'cards_body_location_typography',
                'selector' => '{{WRAPPER}} .tcgelements-map-items.cards .item-body .location-text',
            ]
        );

        $this->add_control(
            'cards_body_location_color',
            [
                'label' => esc_html__('Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-map-items.cards .item-body .location-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Title
        $this->add_control(
            'cards_title_heading',
            [
                'label' => esc_html__('Title', 'element-camp'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'cards_title_typography',
                'selector' => '{{WRAPPER}} .tcgelements-map-items.cards .item-body h6',
            ]
        );

        $this->add_control(
            'cards_title_color',
            [
                'label' => esc_html__('Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-map-items.cards .item-body h6' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Button Style for Cards
        $this->start_controls_section(
            'section_cards_button_style',
            [
                'label' => esc_html__('Button', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'map_style' => 'cards'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'cards_button_typography',
                'selector' => '{{WRAPPER}} .tcgelements-map-items.cards .butn',
            ]
        );

        $this->add_responsive_control(
            'cards_button_padding',
            [
                'label' => esc_html__('Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-map-items.cards .butn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'cards_button_border',
                'selector' => '{{WRAPPER}} .tcgelements-map-items.cards .butn',
            ]
        );

        $this->add_responsive_control(
            'cards_button_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-map-items.cards .butn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('cards_button_style_tabs');

        $this->start_controls_tab(
            'cards_button_normal_tab',
            [
                'label' => esc_html__('Normal', 'element-camp'),
            ]
        );

        $this->add_control(
            'cards_button_color',
            [
                'label' => esc_html__('Text Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-map-items.cards .butn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'cards_button_background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-map-items.cards .butn',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'cards_button_box_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-map-items.cards .butn',
            ]
        );

        $this->add_control(
            'cards_button_transform_options',
            [
                'label' => esc_html__('Transform', 'themescamp-plugin'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'themescamp-plugin'),
                'label_on' => esc_html__('Custom', 'themescamp-plugin'),
            ]
        );
        $this->start_popover();
        $this->add_control(
            'cards_button_translate_x',
            [
                'label' => esc_html__( 'Translate X', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-map-items.cards .butn' => '--e-transform-tcgelements-map-items-cards-button-translateX: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_control(
            'cards_button_translate_y',
            [
                'label' => esc_html__( 'Translate Y', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-map-items.cards .butn' => '--e-transform-tcgelements-map-items-cards-button-translateY: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->end_popover();
        $this->end_controls_tab();

        $this->start_controls_tab(
            'cards_button_hover_tab',
            [
                'label' => esc_html__('Hover', 'element-camp'),
            ]
        );

        $this->add_control(
            'cards_button_hover_color',
            [
                'label' => esc_html__('Text Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-map-items.cards .butn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'cards_button_hover_background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-map-items.cards .butn:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'cards_button_hover_box_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-map-items.cards .butn:hover',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $map_style = $settings['map_style'];

        // Get appropriate map items based on style
        $map_items = [];
        if ($map_style === 'cards') {
            $map_items = $settings['cards_map_items'] ?? [];
        } else {
            $map_items = $settings['default_map_items'] ?? [];
        }

        // Legacy support - if old single repeater data exists
        if (empty($map_items) && !empty($settings['map_items'])) {
            $map_items = $settings['map_items'];
        }
        ?>
        <div class="tcgelements-map-items <?php echo esc_attr($map_style === 'cards' ? 'cards' : 'default'); ?>">
            <?php if ($map_style === 'cards') : ?>
                <div class="map-wrapper">
                    <img src="<?php echo esc_url($settings['map_image']['url']); ?>"
                         alt="<?php echo !empty($settings['map_image']['alt']) ? esc_attr($settings['map_image']['alt']) : ''; ?>"
                         class="map">
                    <div class="places">
                        <?php foreach($map_items as $index => $item) :
                            $left = !empty($item['left_coordinate']) ? $item['left_coordinate'] : 50;
                            $top = !empty($item['top_coordinate']) ? $item['top_coordinate'] : 50;
                            ?>
                            <div class="place-item"
                                 data-left="<?php echo esc_attr($left); ?>%"
                                 data-top="<?php echo esc_attr($top); ?>%">
                                <div class="item-head">
                                    <?php if (!empty($item['title'])) : ?>
                                        <div class="title"><?php echo esc_html($item['title']); ?></div>
                                    <?php endif; ?>
                                    <div class="marker">
                                        <?php if (!empty($item['marker_image']['url'])) : ?>
                                            <img src="<?php echo esc_url($item['marker_image']['url']); ?>" alt="">
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="item-body">
                                    <?php if (!empty($item['item_image']['url'])) : ?>
                                        <div class="img">
                                            <img src="<?php echo esc_url($item['item_image']['url']); ?>" alt="">
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($item['location_text'])) : ?>
                                        <div class="location-text"><?php echo esc_html($item['location_text']); ?></div>
                                    <?php endif; ?>

                                    <?php if (!empty($item['description'])) : ?>
                                        <h6 class="description"><?php echo esc_html($item['description']); ?></h6>
                                    <?php endif; ?>

                                    <?php
                                    $link_key = $this->get_repeater_setting_key('link', 'cards_map_items', $index);
                                    $this->add_render_attribute($link_key, 'href', $item['button_link']['url']);
                                    $button_classes = 'butn';
                                    $hover_text = !empty($item['button_hover_text']) ? $item['button_hover_text'] : 'Contact Us';
                                    if (!empty($item['button_link']['is_external'])) {
                                        $this->add_render_attribute($link_key, 'target', '_blank');
                                    }
                                    if (!empty($item['button_animation']) && $item['button_animation'] === 'hvr_text') {
                                        $button_classes .= ' tce-hvr-txt-trans';
                                    }
                                    $this->add_render_attribute($link_key, 'class', $button_classes);
                                    ?>
                                    <a <?php echo $this->get_render_attribute_string($link_key); ?>>
                                        <?php if (!empty($item['button_animation']) && $item['button_animation'] === 'hvr_text') : ?>
                                            <div class="hvr-txt" data-text="<?php echo esc_attr($hover_text); ?>">
                                                <span><?php echo esc_html($item['button_text']); ?></span>
                                            </div>
                                        <?php else : ?>
                                            <span><?php echo esc_html($item['button_text']); ?></span>
                                        <?php endif; ?>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else : ?>
                <!-- Default Style Layout -->
                <div class="map-main">
                    <img src="<?php echo esc_url($settings['map_image']['url']); ?>"
                         alt="<?php echo !empty($settings['map_image']['alt']) ? esc_attr($settings['map_image']['alt']) : ''; ?>"
                         class="img">
                    <?php foreach($map_items as $item) : ?>
                        <div class="map-item"
                             data-r="<?php echo esc_attr($item['right_coordinate'] ?? 50); ?>"
                             data-b="<?php echo esc_attr($item['bottom_coordinate'] ?? 50); ?>">
                            <div class="info">
                                <h6 class="title"><?php echo esc_html($item['title']); ?></h6>
                                <p class="description"><?php echo esc_html($item['description']); ?></p>
                            </div>
                            <span class="dot"></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}