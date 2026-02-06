<?php

namespace ElementCampPlugin\Widgets;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Widget_Base;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Core\Schemes\Typography;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Frontend;
use Elementor\Icons_Manager;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Image_Size;
use Elementor\Scheme_Base;
use Elementor\Group_Control_Background;


/**
 * Elementor icon widget.
 *
 * Elementor widget that displays an icon from over 600+ icons.
 *
 * @since 1.0.0
 */
class ElementCamp_Search extends Widget_Base
{

    /**
     * Get widget name.
     *
     * Retrieve icon widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'tcgelements-search';
    }

    public function get_script_depends()
    {
        return ['tcgelements-search'];
    }

    /**
     * Get widget title.
     *
     * Retrieve icon widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return esc_html__('Search', 'element-camp');
    }

    /**
     * Get widget icon.
     *
     * Retrieve icon widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'eicon-search tce-widget-badge';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the icon widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * @since 2.0.0
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories()
    {
        return ['elementcamp-elements'];
    }

    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the widget belongs to.
     *
     * @since 2.1.0
     * @access public
     *
     * @return array Widget keywords.
     */
    public function get_keywords()
    {
        return ['search', 'icon', 'link'];
    }

    /**
     * Register icon widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 3.1.0
     * @access protected
     */
    protected function register_controls()
    {
        $this->start_controls_section(
            'section_icon',
            [
                'label' => esc_html__('Icon', 'element-camp'),
            ]
        );
        $this->add_control(
            'search_style',
            [
                'label'   => esc_html__('Search Form Style', 'element-camp'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'field' => esc_html__('Field', 'element-camp'),
                    'icon' =>  esc_html__('Icon', 'element-camp'),
                ],
                'default' => 'field',
            ]
        );
        $this->add_control(
            'selected_icon',
            [
                'label' => esc_html__('Icon', 'element-camp'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'fa-solid',
                ],
            ]
        );
        $this->add_control(
            'open_icon',
            [
                'label' => esc_html__('Open Icon', 'element-camp'),
                'type' => Controls_Manager::ICONS,
                'condition' => ['search_style' => 'icon'],
                'fa4compatibility' => 'icon',
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'fa-solid',
                ],
            ]
        );
        $this->add_control(
            'close_icon',
            [
                'label' => esc_html__('Close Icon', 'element-camp'),
                'type' => Controls_Manager::ICONS,
                'condition' => ['search_style' => 'icon'],
                'fa4compatibility' => 'icon',
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_control(
            'place_holder_text',
            [
                'label' => esc_html__('Place Holder Text', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Search', 'element-camp'),
            ]
        );
        if (class_exists('WooCommerce')) {
            $this->add_control(
                'show_categories',
                [
                    'label' => esc_html__('Show Categories Dropdown', 'element-camp'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__('Show', 'element-camp'),
                    'label_off' => esc_html__('Hide', 'element-camp'),
                    'return_value' => 'yes',
                    'default' => 'no',
                    'condition' => [
                        'search_style' => 'field',
                    ],
                ]
            );
            $this->add_control(
                'categories_placeholder',
                [
                    'label' => esc_html__('Categories Placeholder', 'element-camp'),
                    'type' => Controls_Manager::TEXT,
                    'default' => esc_html__('All Categories', 'element-camp'),
                    'condition' => [
                        'show_categories' => 'yes',
                        'search_style' => 'field',
                    ],
                ]
            );
        }

        $this->end_controls_section();

        $this->start_controls_section(
            'form_style',
            [
                'label' => esc_html__('Form Container Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'form_display',
            [
                'label' => esc_html__('Form Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'block',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'inline-flex' => esc_html__('Inline Flex', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-field .searchform' => 'display: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-search-icon .searchform' => 'display: {{VALUE}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'form_display_position',
            [
                'label' => esc_html__('Form Display Position', 'element-camp'),
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
                        'icon' => 'eicon-h-align-right',
                    ],
                    'end' => [
                        'title' => esc_html__('End', 'element-camp'),
                        'icon' => 'eicon-h-align-left',
                    ],
                ],
                'selectors_dictionary' => [
                    'before' => 'flex-direction: column;',
                    'after' => 'flex-direction: column-reverse;',
                    'start' => 'flex-direction: row;',
                    'end' => 'flex-direction: row-reverse;',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-field .searchform' => '{{VALUE}}',
                    '{{WRAPPER}} .tcgelements-search-icon .searchform' => '{{VALUE}}',
                ],
                'condition' => ['form_display' => ['flex', 'inline-flex']],
            ]
        );

        $this->add_responsive_control(
            'form_justify_content',
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
                    '{{WRAPPER}} .tcgelements-search-field .searchform' => 'justify-content: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-search-icon .searchform' => 'justify-content: {{VALUE}};',
                ],
                'condition' => ['form_display' => ['flex', 'inline-flex']],
            ]
        );

        $this->add_responsive_control(
            'form_align_items',
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
                    '{{WRAPPER}} .tcgelements-search-field .searchform' => 'align-items: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-search-icon .searchform' => 'align-items: {{VALUE}};',
                ],
                'condition' => ['form_display' => ['flex', 'inline-flex']],
            ]
        );

        $this->add_responsive_control(
            'form_flex_wrap',
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
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-field .searchform' => 'flex-wrap: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-search-icon .searchform' => 'flex-wrap: {{VALUE}};',
                ],
                'condition' => ['form_display' => ['flex', 'inline-flex']],
            ]
        );

        $this->add_responsive_control(
            'form_gap',
            [
                'label' => esc_html__('Gap', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem', '%', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-field .searchform' => 'gap: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-search-icon .searchform' => 'gap: {{SIZE}}{{UNIT}};',
                ],
                'condition' => ['form_display' => ['flex', 'inline-flex']],
            ]
        );

        $this->add_responsive_control(
            'form_padding',
            [
                'label' => esc_html__('Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'rem', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-field .searchform' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-search-icon .searchform' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'form_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'rem', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-field .searchform' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-search-icon .searchform' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'form_border',
                'label' => esc_html__('Border', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-search-field .searchform, {{WRAPPER}} .tcgelements-search-icon .searchform',
            ]
        );

        $this->add_responsive_control(
            'form_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-field .searchform' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-search-icon .searchform' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'form_bg_advanced',
                'selector' => '{{WRAPPER}} .tcgelements-search-field .searchform, {{WRAPPER}} .tcgelements-search-icon .searchform',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'fields_options' => [
                    'background' => [
                        'label' => esc_html_x('Background', 'Background Control', 'element-camp'),
                    ]
                ]
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'field_style',
            [
                'label' => esc_html__('Field Setting', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'field_heading',
            [
                'label' => esc_html__('Field', 'element-camp'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_responsive_control(
            'field_size_options',
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
                    '{{WRAPPER}} .tcgelements-search-field .searchform input' => '{{VALUE}};',
                    '{{WRAPPER}} .tcgelements-search-icon input' => '{{VALUE}};',
                ],
                'condition' => ['form_display' => ['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'field_flex_grow',
            [
                'label' => esc_html__('Flex Grow', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-field .searchform input' => 'flex-grow: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-search-icon input' => 'flex-grow: {{VALUE}};',
                ],
                'default' => 1,
                'placeholder' => 1,
                'responsive' => true,
                'condition' => [
                    'field_size_options' => 'custom',
                ],
            ]
        );
        $this->add_responsive_control(
            'field_flex_shrink',
            [
                'label' => esc_html__('Flex Shrink', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-field .searchform input' => 'flex-shrink: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-search-icon input' => 'flex-shrink: {{VALUE}};',
                ],
                'default' => 1,
                'placeholder' => 1,
                'responsive' => true,
                'condition' => [
                    'field_size_options' => 'custom',
                ],
            ]
        );

        $this->add_responsive_control(
            'field_min_height',
            [
                'label' => esc_html__('Field Min Height', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-search-field .searchform input' => 'min-height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-search-icon input' => 'min-height: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_control(
            'field_text',
            [
                'label' => esc_html__('Text Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-field .searchform input' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-search-icon input' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'input_text_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_ACCENT,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-search-field .searchform input,{{WRAPPER}} .tcgelements-search-icon input',
            ]
        );

        $this->add_control(
            'field_text_placeholder',
            [
                'label' => esc_html__('Placeholder Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-field .searchform input::placeholder' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-search-icon input::placeholder' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'field_bg',
            [
                'label' => esc_html__('Solid Background Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-field input' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-search-icon input' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'field_bg_advanced',
                'selector' => '{{WRAPPER}} .tcgelements-search-field input,{{WRAPPER}} .tcgelements-search-icon input',
                'types' => ['gradient', 'tcg_gradient'],
                'fields_options' => [
                    'background' => [
                        'label' => esc_html_x('Gradient Background', 'Background Control', 'themescamp-plugin'),
                    ]
                ]
            ]
        );

        $this->add_responsive_control(
            'field_padding',
            [
                'label' => esc_html__('Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'rem', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-field input' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-search-icon input' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'field_border',
                'label' => esc_html__('Border', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-search-field input ,{{WRAPPER}} .tcgelements-search-icon input ',
            ]
        );

        $this->add_control(
            'field_focus_border_color',
            [
                'label' => esc_html__('Focus Border Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-field input:focus' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-search-icon input:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'field_border_radius',
            [
                'label' => __('Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-field input' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-search-icon input' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'field_style_dark_mode',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'field_text_dark_mode',
            [
                'label' => esc_html__('Text Color', 'elementcamp_plg'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-search-field .searchform input' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-search-field .searchform input' => 'color: {{VALUE}};',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-search-icon .searchform input' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-search-icon .searchform input' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'field_text_placeholder_dark_mode',
            [
                'label' => esc_html__('Placeholder Color', 'elementcamp_plg'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-search-field .searchform input::placeholder' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-search-field .searchform input::placeholder' => 'color: {{VALUE}};',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-search-icon .searchform input::placeholder' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-search-icon .searchform input::placeholder' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'field_border_color_dark_mode',
            [
                'label' => esc_html__('Focus Border Color', 'elementcamp_plg'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-search-field .searchform input' => 'border-color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-search-field .searchform input' => 'border-color: {{VALUE}};',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-search-icon .searchform input' => 'border-color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-search-icon .searchform input' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'field_focus_border_color_dark_mode',
            [
                'label' => esc_html__('Border Color', 'elementcamp_plg'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-search-field .searchform input:focus' => 'border-color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-search-field .searchform input:focus' => 'border-color: {{VALUE}};',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-search-icon .searchform input:focus' => 'border-color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-search-icon .searchform input:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'field_bg_dark_mode',
                'selector' => '{{WRAPPER}} .tcgelements-search-field input, {{WRAPPER}} .tcgelements-search-icon input',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'fields_options' => [
                    'color' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-color: {{VALUE}};',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-color: {{VALUE}};',
                        ],
                    ],
                    'gradient_angle' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
                        ],
                    ],
                    'gradient_position' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
                        ],
                    ],
                    'image' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-image: url("{{URL}}");',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-image: url("{{URL}}");',
                        ],
                    ],
                    'position' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-position: {{VALUE}};',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-position: {{VALUE}};',
                        ],
                    ],
                    'xpos' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-position: {{SIZE}}{{UNIT}} {{ypos.SIZE}}{{ypos.UNIT}}',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-position: {{SIZE}}{{UNIT}} {{ypos.SIZE}}{{ypos.UNIT}}',
                        ],
                    ],
                    'ypos' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-position: {{SIZE}}{{UNIT}} {{ypos.SIZE}}{{ypos.UNIT}}',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-position: {{SIZE}}{{UNIT}} {{ypos.SIZE}}{{ypos.UNIT}}',
                        ],
                    ],
                    'attachment' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode (desktop+){{SELECTOR}}' => 'background-attachment: {{VALUE}};',
                            '} body.tcg-dark-mode (desktop+){{SELECTOR}}' => 'background-attachment: {{VALUE}};',
                        ],
                    ],
                    'repeat' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-repeat: {{VALUE}};',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-repeat: {{VALUE}};',
                        ],
                    ],
                    'size' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-size: {{VALUE}};',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-size: {{VALUE}};',
                        ],
                    ],
                    'bg_width' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-size: {{SIZE}}{{UNIT}} auto',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-size: {{SIZE}}{{UNIT}} auto',
                        ],
                    ],
                    'video_fallback' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background: url("{{URL}}") 50% 50%; background-size: cover;',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background: url("{{URL}}") 50% 50%; background-size: cover;',
                        ],
                    ],
                ]
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'categories_style',
            [
                'label' => esc_html__('Categories Dropdown Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_categories' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'categories_width',
            [
                'label' => esc_html__('Width', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 500,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 200,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-field .product-categories' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'categories_min_width',
            [
                'label' => esc_html__('Min Width', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-search-field .product-categories' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'categories_min_height',
            [
                'label' => esc_html__('Min Height', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-search-field .product-categories' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'categories_text_color',
            [
                'label' => esc_html__('Text Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-field .product-categories' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'categories_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_ACCENT,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-search-field .product-categories',
            ]
        );

        $this->add_responsive_control(
            'categories_padding',
            [
                'label' => esc_html__('Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'rem', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-field .product-categories' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'categories_border',
                'label' => esc_html__('Border', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-search-field .product-categories',
            ]
        );

        $this->add_responsive_control(
            'categories_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-field .product-categories' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'categories_dark_mode_heading',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'categories_text_color_dark',
            [
                'label' => esc_html__('Text Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-search-field .product-categories' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-search-field .product-categories' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'field_button_style',
            [
                'label' => esc_html__('Button Setting', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'icon_heading',
            [
                'label' => esc_html__('Icon', 'element-camp'),
                'type' => Controls_Manager::HEADING,
            ]
        );
        $this->add_control(
            'field_icon_color',
            [
                'label' => esc_html__('Icon Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-field .searchform .searchsubmit svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-search-icon .searchsubmit svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-search-field .searchform .searchsubmit' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-search-icon .searchsubmit' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_icon_dark_mode',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'button_icon_color_dark_mode',
            [
                'label' => esc_html__('Icon Color', 'elementcamp_plg'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-search-field .searchform .searchsubmit svg' => 'fill: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-search-field .searchform .searchsubmit svg' => 'fill: {{VALUE}};',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-search-field .searchform .searchsubmit' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-search-field .searchform .searchsubmit' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'close_open_icon_color',
            [
                'label' => esc_html__('Close/Open Icon Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'condition' => ['search_style' => 'icon'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-icon .search-icon svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-search-icon .search-icon i' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__('Icon size', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-field .searchform .searchsubmit svg' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-search-icon .searchsubmit svg' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-search-field .searchform .searchsubmit i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-search-icon .searchsubmit i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'open_close_icon_size',
            [
                'label' => esc_html__('Open/Close Icon size', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'condition' => ['search_style' => 'icon'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-icon .search-icon svg' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-search-icon .search-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'search_icon_padding',
            [
                'label' => esc_html__('Search Icon Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'rem', 'em', '%', 'custom'],
                'condition' => ['search_style' => 'icon'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-icon .search-icon' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'close_icon_padding',
            [
                'label' => esc_html__('Close Icon Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'rem', 'em', '%', 'custom'],
                'condition' => ['search_style' => 'icon'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-icon .close-search' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'button_advanced_options_heading',
            [
                'label' => esc_html__('Button Advanced Options', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );
        $this->add_responsive_control(
            'button_size_options',
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
                    '{{WRAPPER}} .tcgelements-search-field .searchsubmit' => '{{VALUE}};',
                    '{{WRAPPER}} .tcgelements-search-icon .searchsubmit' => '{{VALUE}};',
                ],
                'condition' => ['form_display' => ['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'button_flex_grow',
            [
                'label' => esc_html__('Flex Grow', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-field .searchsubmit' => 'flex-grow: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-search-icon .searchsubmit' => 'flex-grow: {{VALUE}};',
                ],
                'default' => 1,
                'placeholder' => 1,
                'responsive' => true,
                'condition' => [
                    'button_size_options' => 'custom',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_flex_shrink',
            [
                'label' => esc_html__('Flex Shrink', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-field .searchsubmit' => 'flex-shrink: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-search-icon .searchsubmit' => 'flex-shrink: {{VALUE}};',
                ],
                'default' => 1,
                'placeholder' => 1,
                'responsive' => true,
                'condition' => [
                    'button_size_options' => 'custom',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'button_background',
                'label' => esc_html__('Background', 'element-camp'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-search-field .searchsubmit,{{WRAPPER}} .tcgelements-search-icon .searchsubmit',
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
                    '{{WRAPPER}} .tcgelements-search-field .searchsubmit' => 'width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-search-icon .searchsubmit' => 'width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-search-field .searchsubmit' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-search-icon .searchsubmit' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'button_positioning',
            [
                'label' => esc_html__('Submit Button Position', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'unset' => esc_html__('unset', 'element-camp'),
                    'absolute' => esc_html__('absolute', 'element-camp'),
                    'relative' => esc_html__('relative', 'element-camp'),
                ],
                'label_block' => true,
                'default' => 'absolute',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-field .searchsubmit' => 'position: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-search-icon .searchsubmit' => 'position: {{VALUE}};',
                ],
            ]
        );

        $start = is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');
        $end = !is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');

        $this->add_control(
            'button_offset_orientation_h',
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
                    'button_positioning' => 'absolute',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-search-field .searchsubmit' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-search-field .searchsubmit' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-search-icon .searchsubmit' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-search-icon .searchsubmit' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'button_offset_orientation_h!' => 'end',
                    'button_positioning' => 'absolute',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-search-field .searchsubmit' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-search-field .searchsubmit' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-search-icon .searchsubmit' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-search-icon .searchsubmit' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'button_offset_orientation_h' => 'end',
                    'button_positioning' => 'absolute',
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
                'render_type' => 'ui',
                'condition' => [
                    'button_positioning' => 'absolute',
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
                    '{{WRAPPER}} .tcgelements-search-field .searchsubmit' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                    '{{WRAPPER}} .tcgelements-search-icon .searchsubmit' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'button_offset_orientation_v!' => 'end',
                    'button_positioning' => 'absolute',
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
                    '{{WRAPPER}} .tcgelements-search-field .searchsubmit' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                    '{{WRAPPER}} .tcgelements-search-icon .searchsubmit' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'button_offset_orientation_v' => 'end',
                    'button_positioning' => 'absolute',
                ],
            ]
        );
        $this->add_control(
            'button_translate_y',
            [
                'label' => esc_html__('Translate Y', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
                'default' => [
                    'size' => -50,
                    'unit' => '%',
                ],
            ]
        );
        $this->add_control(
            'button_translate_x',
            [
                'label' => esc_html__('Translate X', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
                'default' => [
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-field .searchsubmit' => 'transform: translate({{button_translate_x.SIZE}}{{button_translate_x.UNIT}},{{button_translate_y.SIZE}}{{button_translate_y.UNIT}})',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-search-field .searchsubmit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
    }
    /**
     * Render icon widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $show_categories = (isset($settings['show_categories']) && $settings['show_categories'] === 'yes') && class_exists('WooCommerce');
        ?>
        <?php if ($settings['search_style'] == 'field'): ?>
        <div class="tcgelements-search-field <?php echo $show_categories ? 'has-categories' : ''; ?>">
            <?php $elementcamp_unique_id = uniqid('search-form-'); ?>
            <form role="search" method="get" id="<?php echo esc_attr($elementcamp_unique_id); ?>" class="searchform" action="<?php echo esc_url(home_url('/')); ?>">
                <?php if ($show_categories):
                    $categories = $this->get_product_categories();
                    if (!empty($categories)):
                        ?>
                        <select name="product_cat" class="product-categories form-select">
                            <option value=""><?php echo esc_html($settings['categories_placeholder']); ?></option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo esc_attr($category->slug); ?>" <?php selected(isset($_GET['product_cat']) ? $_GET['product_cat'] : '', $category->slug); ?>>
                                    <?php echo esc_html($category->name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php
                    endif;
                endif;
                ?>
                <input type="search" placeholder="<?php echo esc_attr__($settings['place_holder_text'], 'tcgelements'); ?>" value="<?php echo get_search_query(); ?>" name="s">
                <?php if ($show_categories): ?>
                    <input type="hidden" name="post_type" value="product">
                <?php endif; ?>
                <button type="submit" class="searchsubmit right">
                    <?php \Elementor\Icons_Manager::render_icon($settings['selected_icon'], ['aria-hidden' => 'true', 'class' => 'features-icon']); ?>
                </button>
            </form>
        </div>
    <?php else: ?>
        <div class="tcgelements-search-form">
            <div class="tcgelements-search-icon">
                <div class="form-group">
                    <?php $elementcamp_unique_id = uniqid('search-form-'); ?>
                    <form role="search" method="get" id="<?php echo esc_attr($elementcamp_unique_id); ?>" class="searchform" action="<?php echo esc_url(home_url('/')); ?>">
                        <input type="text" name="s" placeholder="<?php echo esc_attr__($settings['place_holder_text'], 'tcgelements'); ?>" value="<?php echo get_search_query(); ?>">
                        <button type="submit" class="searchsubmit">
                            <?php \Elementor\Icons_Manager::render_icon($settings['selected_icon'], ['aria-hidden' => 'true', 'class' => 'features-icon']); ?>
                        </button>
                    </form>
                </div>
                <div class="search-icon">
                    <span class="open-search">
                        <?php \Elementor\Icons_Manager::render_icon($settings['open_icon'], ['aria-hidden' => 'true']); ?>
                    </span>
                    <span class="close-search">
                        <?php \Elementor\Icons_Manager::render_icon($settings['close_icon'], ['aria-hidden' => 'true']); ?>
                    </span>
                </div>
            </div>
        </div>
    <?php endif; ?>
        <?php
    }
    /**
     * Get WooCommerce product categories
     *
     * @return array
     */
    private function get_product_categories() {
        if (!class_exists('WooCommerce')) {
            return [];
        }

        $categories = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
            'orderby' => 'name',
            'order' => 'ASC',
        ]);

        if (is_wp_error($categories) || empty($categories)) {
            return [];
        }

        return $categories;
    }
}
