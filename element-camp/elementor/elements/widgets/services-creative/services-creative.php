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
    public function get_script_depends()
    {
        return ['tcgelements-services-creative'];
    }
    public function get_categories()
    {
        return ['elementcamp-elements'];
    }
    protected function _register_controls()
    {
        $start = is_rtl() ? esc_html__('Right', 'themescamp-plugin') : esc_html__('Left', 'themescamp-plugin');
        $end = !is_rtl() ? esc_html__('Right', 'themescamp-plugin') : esc_html__('Left', 'themescamp-plugin');

        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Content', 'element-camp'),
            ]
        );
        $this->add_control(
            'exp_section',
            [
                'label' => esc_html__('Exp Section', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'separator' => 'after'
            ]
        );
        $this->add_responsive_control(
            'exp_number',
            [
                'label' => esc_html__('EXP Section Number', 'element-camp'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => esc_html__('7+', 'element-camp'),
                'condition' => ['exp_section' => 'yes'],
            ]
        );
        $this->add_responsive_control(
            'exp_text',
            [
                'label' => esc_html__('EXP Section Text', 'element-camp'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => esc_html__('Experience', 'element-camp'),
                'condition' => ['exp_section' => 'yes'],
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
                'label' => esc_html__('Media Image', 'element-camp'),
                'type' => Controls_Manager::MEDIA,
                'condition' => ['images_media' => 'image'],
            ]
        );
        $this->add_control(
            'use_tab_container',
            [
                'label' => esc_html__('Use Tab Container (for Progress Features)', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'default' => '',
                'description' => esc_html__('Enable this to use new tab container structure with progress bars. Leave disabled to maintain legacy behavior.', 'element-camp'),
            ]
        );
        $this->add_control(
            'first_card_active',
            [
                'label' => esc_html__('First Card Active by Default', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'default' => 'yes',
                'description' => esc_html__('Make the first service card active on page load', 'element-camp'),
            ]
        );
        $this->add_control(
            'remove_active_on_leave',
            [
                'label' => esc_html__('Remove Active State on Mouse Leave', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'default' => '',
                'description' => esc_html__('Remove active card styling when mouse leaves the widget area', 'element-camp'),
            ]
        );
        $this->add_control(
            'show_step_connector',
            [
                'label' => esc_html__('Show Step Connector Line', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'default' => '',
                'description' => esc_html__('Show vertical dashed line connecting step numbers', 'element-camp'),
            ]
        );
        $this->add_control(
            'progress_feature_notice',
            [
                'type' => \Elementor\Controls_Manager::NOTICE,
                'notice_type' => 'warning',
                'dismissible' => true,
                'heading' => esc_html__( 'Notice', 'element-camp' ),
                'content' => esc_html__( 'Progress bars in the repeater items below will only work when "Use Tab Container" is enabled above.', 'element-camp' ),
                'condition' => [
                    'use_tab_container!' => 'yes',
                ],
            ]
        );
        $repeater = new \Elementor\Repeater();
        $repeater->add_control(
            'card_tag_type',
            [
                'label' => esc_html__('Card Tag Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'a' => esc_html__('Link (a tag)', 'element-camp'),
                    'div' => esc_html__('Div (div tag)', 'element-camp'),
                ],
                'default' => 'a',
                'description' => esc_html__('Choose whether the card should be a clickable link or a regular div.', 'element-camp'),
            ]
        );
        $repeater->add_control(
            'card_link',
            [
                'label' => esc_html__('Card Link', 'element-camp'),
                'type' => Controls_Manager::URL,
                'placeholder' => 'Leave Link here',
                'condition' => ['card_tag_type' => 'a']
            ]
        );
        $repeater->add_control(
            'item_count',
            [
                'label' => esc_html__('Item Count', 'element-camp'),
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
            'item_description',
            [
                'label' => esc_html__('Item Description', 'element-camp'),
                'type' => Controls_Manager::TEXTAREA,
            ]
        );
        $repeater->add_control(
            'enable_item_social_icons',
            [
                'label' => esc_html__('Enable Social Icons', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'default' => '',
                'separator' => 'before',
            ]
        );
        $repeater->add_control(
            'item_social_icons',
            [
                'label' => esc_html__('Social Icons', 'element-camp'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'social_icon',
                        'label' => esc_html__('Icon', 'element-camp'),
                        'type' => \Elementor\Controls_Manager::ICONS,
                        'default' => [
                            'value' => 'fab fa-facebook-f',
                            'library' => 'fa-brands',
                        ],
                        'recommended' => [
                            'fa-brands' => [
                                'facebook-f',
                                'x-twitter',
                                'linkedin-in',
                                'instagram',
                                'youtube',
                                'tiktok',
                                'pinterest',
                                'github',
                            ],
                        ],
                    ],
                    [
                        'name' => 'social_link',
                        'label' => esc_html__('Link', 'element-camp'),
                        'type' => \Elementor\Controls_Manager::URL,
                        'placeholder' => esc_html__('https://your-link.com', 'element-camp'),
                        'default' => [
                            'url' => '#',
                            'is_external' => true,
                        ],
                    ],
                ],
                'default' => [
                    [
                        'social_icon' => [
                            'value' => 'fab fa-facebook-f',
                            'library' => 'fa-brands',
                        ],
                        'social_link' => ['url' => '#'],
                    ],
                    [
                        'social_icon' => [
                            'value' => 'fab fa-x-twitter',
                            'library' => 'fa-brands',
                        ],
                        'social_link' => ['url' => '#'],
                    ],
                    [
                        'social_icon' => [
                            'value' => 'fab fa-linkedin-in',
                            'library' => 'fa-brands',
                        ],
                        'social_link' => ['url' => '#'],
                    ],
                ],
                'title_field' => '<# print(social_icon.value ? social_icon.value.split(" ")[1] : "Icon") #>',
                'condition' => [
                    'enable_item_social_icons' => 'yes',
                ],
            ]
        );
        $repeater->add_control(
            'expandable_section',
            [
                'label' => esc_html__( 'Expendable Section', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'yes', 'element-camp' ),
                'label_off' => esc_html__( 'No', 'element-camp' ),
            ]
        );
        $repeater->add_control(
            'item_expandable_description',
            [
                'label' => esc_html__('Item Description', 'element-camp'),
                'type' => Controls_Manager::TEXTAREA,
                'condition' => ['expandable_section' => 'yes']
            ]
        );
        $repeater->add_control(
            'expandable_tags',
            [
                'label' => esc_html__( 'Tags', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'yes', 'element-camp' ),
                'label_off' => esc_html__( 'No', 'element-camp' ),
            ]
        );
        $repeater->add_control(
            'item_expandable_tags',
            [
                'label' => esc_html__('Tags', 'element-camp'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'expandable_tag_text',
                        'label' => esc_html__('Tag Text', 'element-camp'),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => esc_html__('Tag', 'element-camp'),
                    ]
                ],
                'title_field' => '{{{ expandable_tag_text }}}',
                'condition' => [
                    'expandable_section' => 'yes',
                    'expandable_tags' => 'yes'
                ],
                'default' => [
                    [
                        'expandable_tag_text' => esc_html__('Optimum', 'element-camp'),
                    ],
                    [
                        'expandable_tag_text' => esc_html__('Thinking', 'element-camp'),
                    ],
                    [
                        'expandable_tag_text' => esc_html__('Execution', 'element-camp'),
                    ],
                ],
            ]
        );
        $repeater->add_control(
            'title_image',
            [
                'label' => esc_html__('Title Image', 'element-camp'),
                'type' => Controls_Manager::MEDIA,
            ]
        );
        $repeater->add_control(
            'content_image',
            [
                'label' => esc_html__('Content Image', 'element-camp'),
                'type' => Controls_Manager::MEDIA,
            ]
        );
        $repeater->add_control(
            'progress_section',
            [
                'label' => esc_html__('Progress Section', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'default' => '',
                'separator' => 'before',
                'description' => esc_html__('Note: Progress section only works when "Use Tab Container" is enabled in main settings.', 'element-camp'),
            ]
        );

        $repeater->add_control(
            'progress_title',
            [
                'label' => esc_html__('Progress Title', 'element-camp'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Skill 97%', 'element-camp'),
                'condition' => ['progress_section' => 'yes'],
            ]
        );

        $repeater->add_control(
            'progress_value',
            [
                'label' => esc_html__('Progress Value (%)', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'size' => 97,
                    'unit' => '%',
                ],
                'condition' => ['progress_section' => 'yes'],
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
                'label' => esc_html__('Style', 'element-camp'),
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
                'label' => esc_html__('Display Position', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-services-creative' => '{{VALUE}}',
                ],
                'default' => 'start',
                'condition' => ['display' => 'flex'],
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
                    '{{WRAPPER}} .tcgelements-services-creative' => 'justify-content: {{VALUE}};',
                ],
                'condition' => ['display' => 'flex'],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'align_items',
            [
                'label' => esc_html__('Align Items', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
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
                    '{{WRAPPER}} .tcgelements-services-creative' => 'align-items: {{VALUE}};',
                ],
                'condition' => ['display' => 'flex'],
                'default' => 'center',
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'flex_wrap',
            [
                'label' => esc_html__('Wrap', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'nowrap' => [
                        'title' => esc_html__('No Wrap', 'element-camp'),
                        'icon' => 'eicon-flex eicon-nowrap',
                    ],
                    'wrap' => [
                        'title' => esc_html__('Wrap', 'element-camp'),
                        'icon' => 'eicon-flex eicon-wrap',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative' => 'flex-wrap: {{VALUE}};',
                ],
                'default' => 'wrap',
                'condition' => ['display' => 'flex'],
            ]
        );
        $this->add_responsive_control(
            'card_column_width',
            [
                'label' => esc_html__('Card Column Width', 'element-camp'),
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
                'label' => esc_html__('Card Column Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'card_column_margin',
            [
                'label' => esc_html__('Card Column Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'images_column_width',
            [
                'label' => esc_html__('Images Column Width', 'element-camp'),
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
                'label' => esc_html__('Images Column Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .images-column' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'images_column_margin',
            [
                'label' => esc_html__('Images Column Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .images-column' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'card_style_section',
            [
                'label' => esc_html__('Card Style', 'element-camp'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'card_positioning',
            [
                'label' => esc_html__('Position', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'static' => esc_html__('static', 'element-camp'),
                    'relative' => esc_html__('relative', 'element-camp'),
                ],
                'default' => 'relative',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card' => 'position: {{VALUE}};',
                ],
            ]
        );
        $this->start_controls_tabs(
            'card_style_tabs'
        );
        $this->start_controls_tab(
            'card_normal_tab',
            [
                'label' => esc_html__('Normal', 'element-camp'),
            ]
        );
        $this->add_control(
            'card_normal_opacity',
            [
                'label' => esc_html__('Card Opacity', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'card_active_tab',
            [
                'label' => esc_html__('Active', 'element-camp'),
            ]
        );
        $this->add_control(
            'card_active_opacity',
            [
                'label' => esc_html__('Card Opacity', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card:hover' => 'opacity: {{SIZE}};',
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card.active-card' => 'opacity: {{SIZE}};'
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'card_prev_tab',
            [
                'label' => esc_html__('Prev', 'element-camp'),
            ]
        );
        $this->add_control(
            'card_prev_opacity',
            [
                'label' => esc_html__('Card Opacity', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card:has(+ .service-card:hover)' => 'opacity: {{SIZE}};',
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card:has(+ .service-card.active-card)' => 'opacity: {{SIZE}};'
                ],
            ]
        );
        $this->add_control(
            'card_prev_of_prev_opacity',
            [
                'label' => esc_html__('Previous Of Previous Card Opacity', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card:has(+ .service-card + .service-card:hover)' => 'opacity: {{SIZE}};',
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card:has(+ .service-card + .service-card.active-card)' => 'opacity: {{SIZE}};'
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'card_next_tab',
            [
                'label' => esc_html__('Next', 'element-camp'),
            ]
        );
        $this->add_control(
            'card_next_opacity',
            [
                'label' => esc_html__('Card Opacity', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card:hover + .service-card' => 'opacity: {{SIZE}};',
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card.active-card + .service-card' => 'opacity: {{SIZE}};'
                ],
            ]
        );
        $this->add_control(
            'card_next_of_next_opacity',
            [
                'label' => esc_html__('Card Opacity', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card:hover + .service-card + .service-card' => 'opacity: {{SIZE}};',
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card.active-card + .service-card + .service-card' => 'opacity: {{SIZE}};'
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control(
            'card_transition',
            [
                'label' => esc_html__('Card Transition', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card' => 'transition: all {{SIZE}}s ease;',
                ],
                'separator' => 'before',
            ]
        );
        $this->start_controls_tabs(
            'service_card_tabs',
        );

        $this->start_controls_tab(
            'service_card_normal_tab',
            [
                'label'   => esc_html__('Normal', 'element-camp'),
            ]
        );
        $this->add_responsive_control(
            'service_card_padding',
            [
                'label' => esc_html__('Card Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .service-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'service_card_border',
                'selector' => '{{WRAPPER}} .tcgelements-services-creative .service-card',
                'separator' => 'after',
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'service_card_hover_tab',
            [
                'label'   => esc_html__('Hover', 'element-camp'),
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'service_card_border_hover',
                'selector' => '{{WRAPPER}} .tcgelements-services-creative .service-card:hover, {{WRAPPER}} .tcgelements-services-creative .service-card.active-card',
                'separator' => 'after',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'service_card_first_item_tab',
            [
                'label'   => esc_html__('First', 'element-camp'),
            ]
        );
        $this->add_responsive_control(
            'service_card_first',
            [
                'label' => esc_html__('Card Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card:first-of-type' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'service_card_border_first',
                'selector' => '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card:first-of-type',
                'separator' => 'after',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'service_card_last_item_tab',
            [
                'label'   => esc_html__('Last', 'element-camp'),
            ]
        );
        $this->add_responsive_control(
            'service_card_last',
            [
                'label' => esc_html__('Card Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card:last-of-type' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'service_card_border_last',
                'selector' => '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card:last-of-type',
                'separator' => 'after',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_responsive_control(
            'card_row_width',
            [
                'label' => esc_html__('Card Row Width', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-services-creative .service-card-row' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'card_display',
            [
                'label' => esc_html__('Card Row Display Type', 'element-camp'),
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
                'label' => esc_html__('Display Position', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-services-creative .service-card-row' => '{{VALUE}}',
                ],
                'default' => 'start',
                'condition' => ['card_display' => 'flex'],
            ]
        );
        $this->add_responsive_control(
            'card_justify_content',
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
                    '{{WRAPPER}} .tcgelements-services-creative .service-card-row' => 'justify-content: {{VALUE}};',
                ],
                'condition' => ['card_display' => 'flex'],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'card_align_items',
            [
                'label' => esc_html__('Align Items', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
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
                    '{{WRAPPER}} .tcgelements-services-creative .service-card-row' => 'align-items: {{VALUE}};',
                ],
                'condition' => ['card_display' => 'flex'],
                'default' => 'center',
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'card_flex_wrap',
            [
                'label' => esc_html__('Wrap', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'nowrap' => [
                        'title' => esc_html__('No Wrap', 'element-camp'),
                        'icon' => 'eicon-flex eicon-nowrap',
                    ],
                    'wrap' => [
                        'title' => esc_html__('Wrap', 'element-camp'),
                        'icon' => 'eicon-flex eicon-wrap',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .service-card-row' => 'flex-wrap: {{VALUE}};',
                ],
                'default' => 'wrap',
                'condition' => ['card_display' => 'flex'],
            ]
        );
        $this->add_control(
            'Count_Columns_options',
            [
                'label' => esc_html__('Count Column Options', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_responsive_control(
            'count_column_display',
            [
                'label' => esc_html__('Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                ],
                'default' => 'block',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card-row .count-column' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'count_column_justify_content',
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
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card-row .count-column' => 'justify-content: {{VALUE}};',
                ],
                'condition' => ['count_column_display' => 'flex'],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'count_column_align_items',
            [
                'label' => esc_html__('Align Items', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
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
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card-row .count-column' => 'align-items: {{VALUE}};',
                ],
                'condition' => ['count_column_display' => 'flex'],
                'default' => 'center',
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'card_count_column_width',
            [
                'label' => esc_html__('Count Column Width', 'element-camp'),
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
            'card_count_column_height',
            [
                'label' => esc_html__('Count Column Height', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card-row .count-column' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'card_count_column_size_options',
            [
                'label' => esc_html__( 'Count Column Size', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => '',
                'options' => [
                    'none' => [
                        'title' => esc_html__( 'None', 'element-camp' ),
                        'icon' => 'eicon-ban',
                    ],
                    'grow' => [
                        'title' => esc_html__( 'Grow', 'element-camp' ),
                        'icon' => 'eicon-grow',
                    ],
                    'shrink' => [
                        'title' => esc_html__( 'Shrink', 'element-camp' ),
                        'icon' => 'eicon-shrink',
                    ],
                    'custom' => [
                        'title' => esc_html__( 'Custom', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card-row .count-column' => '{{VALUE}};',
                ],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'card_count_column_flex_grow',
            [
                'label' => esc_html__( 'Flex Grow', 'element-camp' ),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card-row .count-column' => 'flex-grow: {{VALUE}};',
                ],
                'default' => 1,
                'placeholder' => 1,
                'responsive' => true,
                'condition' => [
                    'card_count_column_size_options' => 'custom',
                ],
            ]
        );
        $this->add_responsive_control(
            'card_count_column_flex_shrink',
            [
                'label' => esc_html__( 'Flex Shrink', 'element-camp' ),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card-row .count-column' => 'flex-shrink: {{VALUE}};',
                ],
                'default' => 1,
                'placeholder' => 1,
                'responsive' => true,
                'condition' => [
                    'card_count_column_size_options' => 'custom',
                ],
            ]
        );
        $this->add_responsive_control(
            'card_count_column_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card-row .count-column' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'service_card_count_column_border_radius',
            [
                'label' => esc_html__('Count Column Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .count-column' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );
        $this->start_controls_tabs(
            'service_card_count_column_tabs',
        );

        $this->start_controls_tab(
            'service_card_count_column_normal_tab',
            [
                'label'   => esc_html__('Normal', 'element-camp'),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'service_card_count_column_background_color',
                'label' => esc_html__('Background', 'element-camp'),
                'types' => [ 'classic','gradient' ],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .count-column',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'service_card_count_column_border',
                'selector' => '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .count-column',
            ]
        );
        $this->add_responsive_control(
            'service_card_count_column_opacity',
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
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .count-column' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'service_card_count_column_hover_tab',
            [
                'label'   => esc_html__('Hover', 'element-camp'),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'service_card_count_column_background_color_hover',
                'label' => esc_html__('Background', 'element-camp'),
                'types' => [ 'classic','gradient' ],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card:hover .service-card-row .count-column',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'service_card_count_column_border_hover',
                'selector' => '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card:hover .service-card-row .count-column',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'service_card_count_column_active_tab',
            [
                'label'   => esc_html__('Active', 'element-camp'),
            ]
        );
        $this->add_responsive_control(
            'service_card_count_column_active_opacity',
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
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card.active-card .service-card-row .count-column' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control(
            'Title_Columns_options',
            [
                'label' => esc_html__('Title Column Options', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_responsive_control(
            'card_title_column_width',
            [
                'label' => esc_html__('Title Column Width', 'element-camp'),
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
        $this->add_control(
            'Card_Image_Columns_options',
            [
                'label' => esc_html__('Image Column Options', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_responsive_control(
            'card_image_column_width',
            [
                'label' => esc_html__('Image Column Width', 'element-camp'),
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
        $this->add_control(
            'description_Columns_options',
            [
                'label' => esc_html__('Description Column Options', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_responsive_control(
            'card_description_column_width',
            [
                'label' => esc_html__('Description Column Width', 'element-camp'),
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
                    'size' => '100',
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .description-column' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'card_description_column_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .description-column' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'card_description_column_padding',
            [
                'label' => esc_html__('Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .description-column' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs(
            'service_card_description_column_tabs',
        );
        $this->start_controls_tab(
            'service_card_description_column_normal_tab',
            [
                'label'   => esc_html__('Normal', 'element-camp'),
            ]
        );
        $this->add_responsive_control(
            'service_card_description_column_opacity',
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
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .description-column' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'service_card_description_column_transition',
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
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .description-column' => 'transition:all {{SIZE}}s ease',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'service_card_description_column_active_tab',
            [
                'label'   => esc_html__('Active', 'element-camp'),
            ]
        );
        $this->add_responsive_control(
            'service_card_description_active_column_opacity',
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
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card.active-card .description-column' => 'opacity: {{SIZE}};',
                ],
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
        $this->add_control(
            'count_heading',
            [
                'label' => esc_html__('Count Options', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );
        $this->add_responsive_control(
            'count_order',
            [
                'label' => esc_html__('Order Count', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'default' => '',
                'options' => [
                    'start' => [
                        'title' => esc_html__('Start', 'element-camp'),
                        'icon' => 'eicon-flex eicon-order-start',
                    ],
                    'end' => [
                        'title' => esc_html__('End', 'element-camp'),
                        'icon' => 'eicon-flex eicon-order-end',
                    ],
                    'custom' => [
                        'title' => esc_html__('Custom', 'element-camp'),
                        'icon' => 'eicon-ellipsis-v',
                    ],
                ],
                'selectors_dictionary' => [
                    // Hacks to set the order to start / end.
                    // For example, if the user has 10 widgets, but wants to set the 5th one to be first,
                    // this hack should do the trick while taking in account elements with `order: 0` or less.
                    'start' => '-99999 /* order start hack */',
                    'end' => '99999 /* order end hack */',
                    'custom' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .count-column' => 'order: {{VALUE}};',
                ],
                'condition' => ['card_display' => 'flex'],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'order_count_custom',
            [
                'label' => esc_html__('Custom Order', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .count-column' => 'order: {{VALUE}};',
                ],
                'responsive' => true,
                'condition' => [
                    'count_order' => 'custom',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'service_card_count_typography',
                'selector' => '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .count-column .num',
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'service_card_count_small_typography',
                'label'    => esc_html__( 'Count Small Typography', 'element-camp' ),
                'selector' => '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .count-column .num small',
            ]
        );
        $this->start_controls_tabs(
            'service_card_count_tabs',
        );

        $this->start_controls_tab(
            'service_card_count_normal_tab',
            [
                'label'   => esc_html__('Normal', 'element-camp'),
            ]
        );
        $this->add_control(
            'service_card_count_color',
            [
                'label' => esc_html__('Count Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .count-column .num' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'service_card_count_small_color',
            [
                'label' => esc_html__('Count Small Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .count-column .num small' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'service_card_count_hover_tab',
            [
                'label'   => esc_html__('Hover', 'element-camp'),
            ]
        );
        $this->add_control(
            'service_card_count_color_hover',
            [
                'label' => esc_html__('Count Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card:hover .service-card-row .count-column .num' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control(
            'item_description_heading',
            [
                'label' => esc_html__('Description Options', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'service_card_item_description_typography',
                'selector' => '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .description-column .item-description',
            ]
        );
        $this->add_control(
            'service_card_item_description_color',
            [
                'label' => esc_html__('Description Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}  .tcgelements-services-creative .card-column .service-card .description-column .item-description' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'item_description_line_clamp_enable',
            [
                'label' => esc_html__('Enable Line Clamp', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'default' => '',
                'description' => esc_html__('Limit the number of visible lines and add ellipsis', 'element-camp'),
            ]
        );

        $this->add_responsive_control(
            'item_description_line_clamp_value',
            [
                'label' => esc_html__('Number of Lines', 'element-camp'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'step' => 1,
                'default' => 2,
                'condition' => [
                    'item_description_line_clamp_enable' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .description-column .item-description' =>
                        'display: -webkit-box; ' .
                        '-webkit-line-clamp: {{VALUE}}; ' .
                        '-webkit-box-orient: vertical; ' .
                        'overflow: hidden;',
                ],
            ]
        );

        $this->add_control(
            'title_heading',
            [
                'label' => esc_html__('Title Options', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'title_order',
            [
                'label' => esc_html__('Order Title', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'default' => '',
                'options' => [
                    'start' => [
                        'title' => esc_html__('Start', 'element-camp'),
                        'icon' => 'eicon-flex eicon-order-start',
                    ],
                    'end' => [
                        'title' => esc_html__('End', 'element-camp'),
                        'icon' => 'eicon-flex eicon-order-end',
                    ],
                    'custom' => [
                        'title' => esc_html__('Custom', 'element-camp'),
                        'icon' => 'eicon-ellipsis-v',
                    ],
                ],
                'selectors_dictionary' => [
                    // Hacks to set the order to start / end.
                    // For example, if the user has 10 widgets, but wants to set the 5th one to be first,
                    // this hack should do the trick while taking in account elements with `order: 0` or less.
                    'start' => '-99999 /* order start hack */',
                    'end' => '99999 /* order end hack */',
                    'custom' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .title-column' => 'order: {{VALUE}};',
                ],
                'condition' => ['card_display' => 'flex'],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'order_title_custom',
            [
                'label' => esc_html__('Custom Order', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .title-column' => 'order: {{VALUE}};',
                ],
                'responsive' => true,
                'condition' => [
                    'title_order' => 'custom',
                ],
            ]
        );
        $this->add_control(
            'title_transition',
            [
                'label' => esc_html__('Title Transition', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .title-column .title' => 'transition: all {{SIZE}}s ease;',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'title_align',
            [
                'label' => esc_html__('Title Alignment', 'element-camp'),
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
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .title-column .title' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->start_controls_tabs(
            'service_card_title_tabs',
        );

        $this->start_controls_tab(
            'service_card_title_normal_tab',
            [
                'label'   => esc_html__('Normal', 'element-camp'),
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'service_card_title_typography',
                'label' => esc_html__('Title', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .title-column .title',
            ]
        );
        $this->add_control(
            'service_card_title_color',
            [
                'label' => esc_html__('Title Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .title-column .title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'service_card_title_border',
                'selector' => '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .title-column .title',
            ]
        );
        $this->add_responsive_control(
            'service_card_title_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em' , 'rem' , 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .title-column .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'service_card_title_hover_tab',
            [
                'label'   => esc_html__('Hover', 'element-camp'),
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'service_card_title_typography_hover',
                'label' => esc_html__('Title', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card:hover .service-card-row .title-column .title, {{WRAPPER}} .tcgelements-services-creative .service-card.active-card .service-card-row .title-column .title',
            ]
        );
        $this->add_control(
            'service_card_title_color_hover',
            [
                'label' => esc_html__('Title Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card:hover .service-card-row .title-column .title, {{WRAPPER}} .tcgelements-services-creative .card-column .service-card.active-card .service-card-row .title-column .title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'service_card_title_border_hover',
                'selector' => '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card:hover .service-card-row .title-column .title, {{WRAPPER}} .tcgelements-services-creative .card-column .service-card.active-card .service-card-row .title-column .title',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'service_card_title_active_tab',
            [
                'label'   => esc_html__('Active', 'element-camp'),
            ]
        );
        $this->add_responsive_control(
            'service_card_title_margin_active',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em' , 'rem' , 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card.active-card .service-card-row .title-column .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control(
            'title_z_index',
            [
                'label' => esc_html__('Title Z-index', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .title-column' => 'z-index: {{SIZE}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'content_image_heading',
            [
                'label' => esc_html__('Content Image Options', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'content_column_image_z_index',
            [
                'label' => esc_html__('Image Column Z-index', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .image-column' => 'z-index: {{SIZE}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'content_image_width',
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
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .image-column' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .image-column img' => 'width: {{SIZE}}{{UNIT}};'
                ],
            ]
        );
        $this->add_responsive_control(
            'content_image_height',
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
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .image-column' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .image-column img' => 'height: {{SIZE}}{{UNIT}};'
                ],
            ]
        );
        $this->add_responsive_control(
            'content_image_display',
            [
                'label' => esc_html__('Image Display Type', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'block',
                'tablet_default' => 'none',
                'mobile_default' => 'none',
                'options' => [
                    'none'  => esc_html__('None', 'element-camp'),
                    'block' => esc_html__('Block', 'element-camp'),
                    'flex'  => esc_html__('Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .image-column' => 'display: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'content_image_positioning',
            [
                'label' => esc_html__('Position', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'unset' => esc_html__('Unset', 'element-camp'),
                    'absolute' => esc_html__('Absolute', 'element-camp'),
                    'relative' => esc_html__('Relative', 'element-camp'),
                ],
                'default' => 'relative',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .image-column' => 'position: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'content_image_offset_orientation_h',
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
                'condition' => [
                    'content_image_positioning!' => 'unset',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_image_offset_x',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .image-column' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .image-column' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'content_image_positioning!' => 'unset',
                    'content_image_offset_orientation_h!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_image_offset_x_end',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .image-column' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .image-column' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'content_image_positioning!' => 'unset',
                    'content_image_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_control(
            'content_image_offset_orientation_v',
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
                'condition' => [
                    'content_image_positioning!' => 'unset',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_image_offset_y',
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
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .image-column' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'content_image_positioning!' => 'unset',
                    'content_image_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_image_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .image-column' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'content_image_positioning!' => 'unset',
                    'content_image_offset_orientation_v' => 'end',
                ],
            ]
        );
        $this->start_controls_tabs(
            'content_image_style_tabs'
        );
        $this->start_controls_tab(
            'content_image_normal_tab',
            [
                'label'   => esc_html__('Normal', 'element-camp'),
            ]
        );
        $this->add_control(
            'content_image_transform_options',
            [
                'label' => esc_html__('Image Transform', 'element-camp'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'element-camp'),
                'label_on' => esc_html__('Custom', 'element-camp'),
            ]
        );
        $this->start_popover();
        $this->add_control(
            'content_image_translate_x',
            [
                'label' => esc_html__('Image Translate X', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .image-column' => '--e-transform-tcgelements-services-creative-image-column-translateX: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_control(
            'content_image_translate_y',
            [
                'label' => esc_html__('Image Translate Y', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .image-column' => '--e-transform-tcgelements-services-creative-image-column-translateY: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_control(
            'content_image_rotate',
            [
                'label' => esc_html__('Image Rotate', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['deg'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .image-column' => '--e-transform-tcgelements-services-creative-image-column-rotateZ: {{SIZE}}deg',
                ],
            ]
        );
        $this->add_control(
            'content_image_scale',
            [
                'label' => esc_html__('Image Scale', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .image-column' => '--e-transform-tcgelements-services-creative-image-column-scale: {{SIZE}}',
                ],
            ]
        );
        $this->end_popover();
        $this->end_controls_tab();
        $this->start_controls_tab(
            'content_image_show_tab',
            [
                'label'   => esc_html__('Show', 'element-camp'),
            ]
        );
        $this->add_control(
            'content_image_show_transform_options',
            [
                'label' => esc_html__('Image Transform', 'element-camp'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'element-camp'),
                'label_on' => esc_html__('Custom', 'element-camp'),
            ]
        );
        $this->start_popover();
        $this->add_control(
            'content_image_show_translate_x',
            [
                'label' => esc_html__('Image Translate X', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row:hover .image-column' => '--e-transform-tcgelements-services-creative-image-column-translateX: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_control(
            'content_image_show_translate_y',
            [
                'label' => esc_html__('Image Translate Y', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row:hover .image-column' => '--e-transform-tcgelements-services-creative-image-column-translateY: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_control(
            'content_image_show_rotate',
            [
                'label' => esc_html__('Image Rotate', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['deg'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row:hover .image-column' => '--e-transform-tcgelements-services-creative-image-column-rotateZ: {{SIZE}}deg',
                ],
            ]
        );
        $this->add_control(
            'content_image_show_scale',
            [
                'label' => esc_html__('Image Scale', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row:hover .image-column' => '--e-transform-tcgelements-services-creative-image-column-scale: {{SIZE}}',
                ],
            ]
        );
        $this->end_popover();
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control(
            'content_image_transition',
            [
                'label' => esc_html__('Image Transition', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .service-card-row .image-column' => 'transition: all {{SIZE}}s ease;',
                ],
                'separator' => 'before',
            ]
        );
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
                'label' => esc_html__('Number Color', 'element-camp'),
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
                'label' => esc_html__('Number', 'element-camp'),
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
                'label' => esc_html__('Text Color', 'element-camp'),
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
                'label' => esc_html__('Text', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-services-creative .card-column .exp-wrapper .exp-text',
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_progress_style',
            [
                'label' => esc_html__('Progress Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['use_tab_container' => 'yes']
            ]
        );
        $this->add_responsive_control(
            'progress_wrapper_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em' , 'rem' , 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative[data-use-tabs=yes] .images-column .imgs .tab-card .progress-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'progress_title_typography',
                'label' => esc_html__('Progress Text', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-services-creative[data-use-tabs=yes] .images-column .imgs .tab-card .progress-wrapper .txt',
            ]
        );
        $this->add_control(
            'progress_title_color',
            [
                'label' => esc_html__('Progress Text Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative[data-use-tabs=yes] .images-column .imgs .tab-card .progress-wrapper .txt' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'progress_title_margin',
            [
                'label' => esc_html__('Progress Text Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em' , 'rem' , 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative[data-use-tabs=yes] .images-column .imgs .tab-card .progress-wrapper .txt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
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
        $this->add_responsive_control(
            'images_container_display',
            [
                'label' => esc_html__('Images Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'block',
                'options' => [
                    'none' => esc_html__('None', 'element-camp'),
                    'block' => esc_html__('Block', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .images-column' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'images_container_background',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-services-creative .images-column',
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
                'label' => esc_html__('Images Container Overflow Hidden', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .images-column .imgs' => 'overflow:hidden;',
                ],
                'separator' => 'before'
            ]
        );
        $this->add_responsive_control(
            'images_container_width',
            [
                'label' => esc_html__('Images Container Width', 'element-camp'),
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
                'size_units' => ['px', 'vw', '%', 'custom'],
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
            'images_container_height',
            [
                'label' => esc_html__('Images Container Height', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-services-creative .images-column .imgs' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'images_container_margin',
            [
                'label' => esc_html__('Images Container Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .images-column .imgs' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'images_container_border_radius',
            [
                'label' => esc_html__('Images Container Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .images-column .imgs' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'images_img_options',
            [
                'label' => esc_html__('Images img Options', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'images_image_positioning',
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
                    '{{WRAPPER}} .tcgelements-services-creative .images-column .imgs img' => 'position: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'images_image_offset_orientation_h',
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
            'images_image_offset_x',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-services-creative .images-column .imgs img' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-services-creative .images-column .imgs img' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'images_image_offset_orientation_h!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'images_image_offset_x_end',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-services-creative .images-column .imgs img' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-services-creative .images-column .imgs img' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'images_image_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_control(
            'images_image_offset_orientation_v',
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
            'images_image_offset_y',
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
                    '{{WRAPPER}} .tcgelements-services-creative .images-column .imgs img' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'images_image_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'images_image_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-services-creative .images-column .imgs img' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'images_image_offset_orientation_v' => 'end',
                ],
            ]
        );
        $this->add_responsive_control(
            'images_image_width',
            [
                'label' => esc_html__('Images img Width', 'element-camp'),
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
                'size_units' => ['px', 'vw', '%', 'custom'],
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
                    '{{WRAPPER}} .tcgelements-services-creative .images-column .imgs img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'images_img_height',
            [
                'label' => esc_html__('Images img Height', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-services-creative .images-column .imgs img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'images_image_max_width',
            [
                'label' => esc_html__('Images img Max Width', 'element-camp'),
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
                'size_units' => ['px', 'vw', '%', 'custom'],
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
                    '{{WRAPPER}} .tcgelements-services-creative .images-column .imgs img' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'images_img_max_height',
            [
                'label' => esc_html__('Images img Max Height', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-services-creative .images-column .imgs img' => 'max-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'images_img_object_fit',
            [
                'label' => esc_html__('Object Fit', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'images_img_height[size]!' => '',
                ],
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'fill' => esc_html__('Fill', 'element-camp'),
                    'cover' => esc_html__('Cover', 'element-camp'),
                    'contain' => esc_html__('Contain', 'element-camp'),
                ],
                'default' => 'cover',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .images-column .imgs img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'images_img_object_position',
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
                    '{{WRAPPER}} .tcgelements-services-creative .images-column .imgs img' => 'object-position: {{VALUE}};',
                ],
                'condition' => [
                    'images_img_height[size]!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'img_border_radius',
            [
                'label' => esc_html__( 'Image Border Radius', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .images-column .imgs img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'image_transition',
            [
                'label' => esc_html__('Image Transition (S)', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .images-column .imgs img' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );
        $this->start_controls_tabs(
            'image_tabs',
        );

        $this->start_controls_tab(
            'image_normal_tab',
            [
                'label'   => esc_html__('Normal', 'element-camp'),
            ]
        );

        $this->add_control(
            'images_image_clip_path_popover_toggle',
            [
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label' => esc_html__('Clip Path', 'element-camp'),
                'label_off' => esc_html__('Default', 'element-camp'),
                'label_on' => esc_html__('Custom', 'element-camp'),
                'return_value' => 'yes',
            ]
        );

        $this->start_popover();

        $this->add_control(
            'images_image_clip_path',
            [
                'label' => esc_html__('Clip Path', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'element-camp'),
                    'circle' => esc_html__('Circle', 'element-camp'),
                    'ellipse' => esc_html__('Ellipse', 'element-camp'),
                    'inset' => esc_html__('Inset', 'element-camp'),
                    'polygon' => esc_html__('Polygon', 'element-camp'),
                    'custom' => esc_html__('Custom', 'element-camp'),
                ],
                'default' => 'none',
                'frontend_available' => true,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .images-column .imgs img' => 'clip-path: {{VALUE}}({{images_image_clip_path_value.VALUE}});',
                ],
            ]
        );

        $this->add_control(
            'images_image_clip_path_value',
            [
                'label' => esc_html__('Clip Path Value', 'element-camp'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'frontend_available' => true,
                'condition' => [
                    'images_image_clip_path' => ['circle', 'ellipse', 'inset', 'polygon'],
                ],
            ]
        );

        $this->add_responsive_control(
            'images_image_clip_path_custom',
            [
                'label' => esc_html__('Clip Path', 'element-camp'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'description' => esc_html__('example: polygon(0 0, 100% 0, 100% 100%, 27% 100%) OR you can use unset to disable it'),
                'placeholder' => esc_html__('example: polygon(0 0, 100% 0, 100% 100%, 27% 100%)'),
                'condition' => ['images_image_clip_path' => 'custom'],
                'language' => 'css',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .images-column .imgs img' => 'clip-path: {{VALUE}};',
                ],
            ]
        );
        $this->end_popover();

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
                'label' => esc_html__('Image Rotate', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['deg'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .images-column .imgs img' => '--e-transform-tcgelements-services-creative-images-image-rotateZ: {{SIZE}}deg',
                ],
            ]
        );
        $this->add_control(
            'images_image_scale',
            [
                'label' => esc_html__('Image Scale', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .images-column .imgs img' => '--e-transform-tcgelements-services-creative-images-image-scale: {{SIZE}}',
                ],
            ]
        );
        $this->end_popover();
        $this->add_responsive_control(
            'images_image_transform_origin',
            [
                'label' => esc_html__('Transform Origin', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'unset' => esc_html__('None', 'element-camp'),
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
                'default' => 'unset',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .images-column .imgs img' => 'transform-origin: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'images_image_blur_method',
            [
                'label' => esc_html__('Blur Method', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'backdrop-filter' => 'backdrop-filter',
                    'filter' => 'filter',
                ],
                'default' => 'backdrop-filter',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .images-column .imgs img' => '{{VALUE}}: blur({{images_image_blur_value.SIZE}}px);',
                ],
            ]
        );
        $this->add_control(
            'images_image_blur_value',
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
        $this->end_controls_tab();
        $this->start_controls_tab(
            'image_show_tab',
            [
                'label'   => esc_html__('Show', 'element-camp'),
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
                'label' => esc_html__('Image Rotate', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['deg'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .images-column .imgs img.show' => '--e-transform-tcgelements-services-creative-images-image-rotateZ: {{SIZE}}deg',
                ],
            ]
        );
        $this->add_control(
            'images_image_scale_show',
            [
                'label' => esc_html__('Image Scale', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .images-column .imgs img.show' => '--e-transform-tcgelements-services-creative-images-image-scale: {{SIZE}}',
                ],
            ]
        );
        $this->end_popover();
         $this->add_responsive_control(
            'images_image_transform_origin_show',
            [
                'label' => esc_html__('Transform Origin', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'unset' => esc_html__('None', 'element-camp'),
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
                'default' => 'unset',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .images-column .imgs img.show' => 'transform-origin: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'images_image_blur_method_active',
            [
                'label' => esc_html__('Blur Method', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'backdrop-filter' => 'backdrop-filter',
                    'filter' => 'filter',
                ],
                'default' => 'backdrop-filter',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .images-column .imgs img.show' => '{{VALUE}}: blur({{images_image_blur_value_active.SIZE}}px);',
                ],
            ]
        );
        $this->add_control(
            'images_image_blur_value_active',
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
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        $this->start_controls_section(
            'section_expandable_content_style',
            [
                'label' => esc_html__('Expandable Section Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'expandable_content_heading',
            [
                'label' => esc_html__('Expandable Content Options', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'expandable_transition_speed',
            [
                'label' => esc_html__('Transition Speed', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 0.3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .expandable-content' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );
        $this->start_controls_tabs(
            'tab_item_tabs',
        );

        $this->start_controls_tab(
            'section_expandable_content_normal',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_responsive_control(
            'section_expandable_content_height',
            [
                'label' => esc_html__('Height', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card .expandable-content' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'section_expandable_content_active',
            [
                'label'   => esc_html__( 'Active', 'element-camp' ),
            ]
        );
        $this->add_responsive_control(
            'section_expandable_content_height_active',
            [
                'label' => esc_html__('Height', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-services-creative .card-column .service-card.active-card .expandable-content' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'expandable_description_heading',
            [
                'label' => esc_html__('Description Options', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'expandable_description',
            [
                'label' => esc_html__( 'Description Width', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
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
                    '{{WRAPPER}} .tcgelements-services-creative .expandable-content .description' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'expandable_description_typography',
                'selector' => '{{WRAPPER}} .tcgelements-services-creative .expandable-content .description',
            ]
        );

        $this->add_control(
            'expandable_description_color',
            [
                'label' => esc_html__('Description Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .expandable-content .description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'expandable_description_margin',
            [
                'label' => esc_html__('Description Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .expandable-content .description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'expandable_tags_heading',
            [
                'label' => esc_html__('Tags Options', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'expandable_tags_margin',
            [
                'label' => esc_html__('Tags Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .expandable-content .tags' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after'
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'expandable_tag_typography',
                'selector' => '{{WRAPPER}} .tcgelements-services-creative .expandable-content .tags .tag',
            ]
        );

        $this->add_control(
            'expandable_tag_color',
            [
                'label' => esc_html__('Tag Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .expandable-content .tags .tag' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'expandable_tag_padding',
            [
                'label' => esc_html__('Tag Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .expandable-content .tags .tag' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'expandable_tag_margin',
            [
                'label' => esc_html__('Tag Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .expandable-content .tags .tag' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'expandable_tag_border_radius',
            [
                'label' => esc_html__('Tag Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .expandable-content .tags .tag' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'expandable_tag_border',
                'selector' => '{{WRAPPER}} .tcgelements-services-creative .expandable-content .tags .tag',
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'connectors_style_section',
            [
                'label' => esc_html__('Connectors Style', 'element-camp'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => ['show_step_connector' => 'yes']
            ]
        );
        $this->add_control(
            'step_connector_color',
            [
                'label' => esc_html__('Connector Line Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'condition' => ['show_step_connector' => 'yes'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative.show-connector .card-column .service-card::after' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'step_connector_style',
            [
                'label' => esc_html__('Connector Line Style', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'dashed' => esc_html__('Dashed', 'element-camp'),
                    'solid' => esc_html__('Solid', 'element-camp'),
                    'dotted' => esc_html__('Dotted', 'element-camp'),
                ],
                'default' => 'dashed',
                'condition' => ['show_step_connector' => 'yes'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative.show-connector .card-column .service-card::after' => 'border-left-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'step_connector_width',
            [
                'label' => esc_html__('Connector Line Width', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 10,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'size' => 1,
                    'unit' => 'px',
                ],
                'condition' => ['show_step_connector' => 'yes'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative.show-connector .card-column .service-card::after' => 'border-left-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'step_dot_size',
            [
                'label' => esc_html__('Step Dot Size', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 30,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'size' => 10,
                    'unit' => 'px',
                ],
                'condition' => ['show_step_connector' => 'yes'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative.show-connector .card-column .service-card .service-card-row .count-column::after' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'step_dot_color',
            [
                'label' => esc_html__('Step Dot Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'condition' => ['show_step_connector' => 'yes'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative.show-connector .card-column .service-card .service-card-row::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_social_icons_style',
            [
                'label' => esc_html__('Social Icons Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'social_icons_align',
            [
                'label' => esc_html__('Social Icons Alignment', 'element-camp'),
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
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .social-icons' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'social_icons_order',
            [
                'label' => esc_html__('Order Title', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'default' => '',
                'options' => [
                    'start' => [
                        'title' => esc_html__('Start', 'element-camp'),
                        'icon' => 'eicon-flex eicon-order-start',
                    ],
                    'end' => [
                        'title' => esc_html__('End', 'element-camp'),
                        'icon' => 'eicon-flex eicon-order-end',
                    ],
                    'custom' => [
                        'title' => esc_html__('Custom', 'element-camp'),
                        'icon' => 'eicon-ellipsis-v',
                    ],
                ],
                'selectors_dictionary' => [
                    // Hacks to set the order to start / end.
                    // For example, if the user has 10 widgets, but wants to set the 5th one to be first,
                    // this hack should do the trick while taking in account elements with `order: 0` or less.
                    'start' => '-99999 /* order start hack */',
                    'end' => '99999 /* order end hack */',
                    'custom' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .social-icons' => 'order: {{VALUE}};',
                ],
                'condition' => ['card_display' => 'flex'],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'order_social_icons_custom',
            [
                'label' => esc_html__('Custom Order', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .social-icons' => 'order: {{VALUE}};',
                ],
                'responsive' => true,
                'condition' => [
                    'social_icons_order' => 'custom',
                ],
            ]
        );
        $this->add_responsive_control(
            'social_icons_wrapper_width',
            [
                'label' => esc_html__('Social Icons Wrapper Width', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-services-creative .social-icons' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'social_icons_icon_display',
            [
                'label' => esc_html__('Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'inline-flex' => esc_html__('Inline Flex', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                ],
                'default' => 'inline-flex',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .social-icons a' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'social_icons_icon_justify_content',
            [
                'label' => esc_html__('Justify Content', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
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
                    '{{WRAPPER}} .tcgelements-services-creative .social-icons a' => 'justify-content: {{VALUE}};',
                ],
                'condition' => ['social_icons_icon_display' => 'inline-flex'],
                'default' => 'center',
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'social_icons_icon_align_items',
            [
                'label' => esc_html__('Align Items', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
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
                    '{{WRAPPER}} .tcgelements-services-creative .social-icons a' => 'align-items: {{VALUE}};',
                ],
                'condition' => ['social_icons_icon_display' => 'inline-flex'],
                'default' => 'center',
                'responsive' => true,
            ]
        );

        $this->add_responsive_control(
            'social_icons_icon_width',
            [
                'label' => esc_html__('Icon Width', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .social-icons a' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'social_icons_icon_height',
            [
                'label' => esc_html__('Icon Height', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .social-icons a' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'social_icons_size',
            [
                'label' => esc_html__('Icon Size', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 16,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .social-icons a i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-services-creative .social-icons a svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'social_icons_padding',
            [
                'label' => esc_html__('Icon Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .social-icons a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'social_icons_margin',
            [
                'label' => esc_html__('Container Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .social-icons a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('social_icons_tabs');

        $this->start_controls_tab(
            'social_icons_normal',
            [
                'label' => esc_html__('Normal', 'element-camp'),
            ]
        );

        $this->add_control(
            'social_icon_color',
            [
                'label' => esc_html__('Icon Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .social-icons a i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-services-creative .social-icons a svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'social_icon_background',
                'types' => ['classic', 'gradient'],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .tcgelements-services-creative .social-icons a',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'social_icon_border',
                'selector' => '{{WRAPPER}} .tcgelements-services-creative .social-icons a',
            ]
        );

        $this->add_responsive_control(
            'social_icon_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .social-icons a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'social_icons_hover',
            [
                'label' => esc_html__('Hover', 'element-camp'),
            ]
        );

        $this->add_control(
            'social_icon_color_hover',
            [
                'label' => esc_html__('Icon Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .social-icons a:hover i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-services-creative .social-icons a:hover svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'social_icon_background_hover',
                'types' => ['classic', 'gradient'],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .tcgelements-services-creative .social-icons a:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'social_icon_border_hover',
                'selector' => '{{WRAPPER}} .tcgelements-services-creative .social-icons a:hover',
            ]
        );

        $this->add_control(
            'social_icon_transition',
            [
                'label' => esc_html__('Transition Duration (s)', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 0.3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-creative .social-icons a' => 'transition: all {{SIZE}}s ease;',
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
        $useTabContainer = !empty($settings['use_tab_container']) && $settings['use_tab_container'] === 'yes';
        $showConnector = !empty($settings['show_step_connector']) && $settings['show_step_connector'] === 'yes';
        ?>
    <div class="tcgelements-services-creative <?= $showConnector ? 'show-connector' : '' ?>"
         data-use-tabs="<?= $useTabContainer ? 'yes' : 'no' ?>"
         data-remove-on-leave="<?= !empty($settings['remove_active_on_leave']) && $settings['remove_active_on_leave'] === 'yes' ? 'yes' : 'no' ?>">
        <div class="card-column">
        <?php $itemCount = 1;
        foreach ($settings['services_items'] as $item) : ?>
            <?php
            // Validate card tag type
            $cardTagType = isset($item['card_tag_type']) ? $item['card_tag_type'] : 'div';
            $cardTagType = in_array($cardTagType, ['a', 'div']) ? $cardTagType : 'div';

            // Prepare link attributes only if card is an anchor tag and has a URL
            $link_attrs = '';
            if ($cardTagType === 'a') {
                $cardLink = !empty($item['card_link']['url']) ? $item['card_link']['url'] : '#';
                $cardTarget = !empty($item['card_link']['is_external']) ? '_blank' : '_self';
                $cardNofollow = !empty($item['card_link']['nofollow']) ? 'nofollow' : '';

                // If no URL provided, fall back to div
                if (empty($item['card_link']['url'])) {
                    $cardTagType = 'div';
                    $link_attrs = '';
                } else {
                    $rel_attrs = [];
                    if ($cardNofollow) {
                        $rel_attrs[] = 'nofollow';
                    }
                    if (!empty($item['card_link']['is_external']) && !in_array('noopener', $rel_attrs)) {
                        $rel_attrs[] = 'noopener';
                    }

                    $link_attrs = sprintf(
                        'href="%s" target="%s"%s',
                        esc_url($cardLink),
                        esc_attr($cardTarget),
                        !empty($rel_attrs) ? ' rel="' . esc_attr(implode(' ', $rel_attrs)) . '"' : ''
                    );
                }
            }
            ?>
            <<?php echo $cardTagType; ?> <?php echo $link_attrs; ?>
            class="service-card <?php if (!empty($settings['first_card_active']) && $settings['first_card_active'] === 'yes' && $itemCount === 1) echo 'active-card'; ?>"
            data-serv="<?= esc_attr($itemCount) ?>">
            <div class="service-card-row">
                <?php if (!empty($item['item_count'])) : ?>
                    <div class="count-column">
                        <span class="num"> <?= esc_html__($item['item_count'], 'element-camp') ?> </span>
                    </div>
                <?php endif;?>
                <div class="title-column">
                    <h3 class="title"> <?= esc_html($item['title']) ?> </h3>
                </div>
                <?php if ( $item['expandable_section'] === 'yes' && (!empty($item['item_expandable_description']) || !empty($item['item_expandable_tags']))) : ?>
                    <div class="expandable-content">
                        <?php if (!empty($item['item_expandable_description'])) : ?>
                            <div class="description"> <?= wp_kses_post($item['item_expandable_description']) ?> </div>
                        <?php endif; ?>
                        <?php if ($item['expandable_tags']==='yes' && !empty($item['item_expandable_tags'])) : ?>
                            <div class="tags">
                                <?php foreach ($item['item_expandable_tags'] as $tag) : ?>
                                    <span class="tag"> <?= esc_html($tag['expandable_tag_text']) ?> </span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($item['title_image']['url'])) : ?>
                    <div class="image-column">
                        <img src="<?= esc_url($item['title_image']['url']); ?>" alt="<?= !empty($item['title_image']['alt']) ? esc_attr($item['title_image']['alt']) : '' ?>">
                    </div>
                <?php endif ?>
            <?php if (!empty($item['enable_item_social_icons']) && $item['enable_item_social_icons'] === 'yes' && !empty($item['item_social_icons'])) : ?>
                <div class="social-icons">
                <?php foreach ($item['item_social_icons'] as $social) :
                    $target = !empty($social['social_link']['is_external']) ? '_blank' : '_self';
                    $nofollow = !empty($social['social_link']['nofollow']) ? 'nofollow' : '';

                    // Determine if social icon should be a link
                    $socialTag = 'span';
                    $socialLinkAttrs = '';

                    if ($cardTagType === 'a') {
                        // If main card is already a link, social icons should be spans (no nested links)
                        $socialTag = 'span';
                    } else {
                        // If main card is not a link, social icons can be links
                        if (!empty($social['social_link']['url'])) {
                            $socialTag = 'a';
                            $rel_attrs = [];
                            if ($nofollow) {
                                $rel_attrs[] = 'nofollow';
                            }
                            if (!empty($social['social_link']['is_external']) && !in_array('noopener', $rel_attrs)) {
                                $rel_attrs[] = 'noopener';
                            }

                            $socialLinkAttrs = sprintf(
                                'href="%s" target="%s"%s',
                                esc_url($social['social_link']['url']),
                                esc_attr($target),
                                !empty($rel_attrs) ? ' rel="' . esc_attr(implode(' ', $rel_attrs)) . '"' : ''
                            );
                        }
                    }
                    ?>
                    <<?php echo $socialTag; ?> <?php echo $socialLinkAttrs; ?>>
                    <?php \Elementor\Icons_Manager::render_icon($social['social_icon'], ['aria-hidden' => 'true']); ?>
                    </<?php echo $socialTag; ?>>
                <?php endforeach; ?>
                </div>
            <?php endif; ?>
            </div>
            <?php if (!empty($item['item_description'])) : ?>
                <div class="description-column">
                    <div class="item-description"> <?= wp_kses_post($item['item_description']) ?> </div>
                </div>
            <?php endif; ?>
            </<?php echo $cardTagType; ?>>
            <?php $itemCount++;
        endforeach; ?>
        <?php if ($settings['exp_section']) : ?>
        <div class="exp-wrapper">
            <span class="exp-number"><?= esc_html($settings['exp_number']) ?></span> <span class="exp-text"><?= esc_html($settings['exp_text']) ?></span>
        </div>
    <?php endif; ?>
        </div>
        <?php
        // Check if there are any images to display
        $hasImages = false;
        if ($settings['images_media'] == 'image' && !empty($settings['images_media_image']['url'])) {
            $hasImages = true;
        } else {
            // Check if any service items have content images
            foreach ($settings['services_items'] as $item) {
                if (!empty($item['content_image']['url'])) {
                    $hasImages = true;
                    break;
                }
            }
        }
        ?>
        <?php if ($hasImages) : ?>
        <div class="images-column">
            <?php if ($settings['images_media'] == 'image' && !empty($settings['images_media_image']['url'])) : ?>
                <img src="<?= esc_url($settings['images_media_image']['url']); ?>" alt="<?= !empty($settings['images_media_image']['alt']) ? esc_attr($settings['images_media_image']['alt']) : '' ?>" class="media-image">
            <?php endif; ?>

            <?php
            // Check if there are any service item images
            $hasServiceImages = false;
            foreach ($settings['services_items'] as $item) {
                if (!empty($item['content_image']['url'])) {
                    $hasServiceImages = true;
                    break;
                }
            }
            ?>

            <?php if ($hasServiceImages) : ?>
                <div class="imgs">
                    <?php if ($useTabContainer) : ?>
                        <?php // NEW TAB CONTAINER STRUCTURE ?>
                        <?php $itemCount = 1;
                        foreach ($settings['services_items'] as $item) : ?>
                            <?php if (!empty($item['content_image']['url'])) : ?>
                                <div class="tab-card <?php if ($itemCount === 1) echo "active" ?>" data-tab-item="<?= esc_attr($itemCount) ?>">
                                    <div class="img">
                                        <img src="<?= esc_url($item['content_image']['url']); ?>" alt="<?= !empty($item['content_image']['alt']) ? esc_attr($item['content_image']['alt']) : '' ?>" data-serv="<?= esc_attr($itemCount) ?>" class="<?php if ($itemCount === 1) echo "show" ?>">
                                    </div>
                                    <?php if (!empty($item['progress_section']) && $item['progress_section'] === 'yes') : ?>
                                        <div class="progress-wrapper">
                                            <div class="txt"><?= esc_html($item['progress_title']) ?></div>
                                            <div class="progress progress-st1" role="progressbar" aria-label="<?= esc_attr($item['progress_title']) ?>" aria-valuenow="<?= esc_attr($item['progress_value']['size']) ?>" aria-valuemin="0" aria-valuemax="100">
                                                <div class="progress-bar" data-progress="<?= esc_attr($item['progress_value']['size']) ?>"></div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <?php $itemCount++;
                        endforeach; ?>
                    <?php else : ?>
                        <?php // LEGACY STRUCTURE - WITH VALIDATION ?>
                        <?php $itemCount = 1;
                        foreach ($settings['services_items'] as $item) : ?>
                            <?php if (!empty($item['content_image']['url'])) : ?>
                                <img src="<?= esc_url($item['content_image']['url']); ?>" alt="<?= !empty($item['content_image']['alt']) ? esc_attr($item['content_image']['alt']) : '' ?>" data-serv="<?= esc_attr($itemCount) ?>" class="<?php if ($itemCount === 1) echo "show" ?>">
                            <?php endif; ?>
                            <?php $itemCount++;
                        endforeach; ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
        </div>
        <?php
    }
}
