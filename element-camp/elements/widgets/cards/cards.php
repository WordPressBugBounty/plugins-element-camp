<?php

namespace ElementCampPlugin\Widgets;
if ( ! defined( 'ABSPATH' ) ) {
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

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class ElementCamp_Cards extends Widget_Base
{

    /**
     * Retrieve the widget name.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'tcgelements-cards';
    }

    /**
     * Retrieve the widget title.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return esc_html__('Cards', 'element-camp');
    }

    /**
     * Retrieve the widget icon.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'eicon-posts-ticker tce-widget-badge';
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
        return ['tcgelements-cards'];
    }

    /**
     * Register the widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    protected function register_controls()
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'element-camp'),
            ]
        );

        $cards_repeater = new \Elementor\Repeater();

        $cards_repeater->add_control(
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

        $cards_repeater->add_control(
            'title_switcher',
            [
                'label' => esc_html__( 'Title', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'yes', 'element-camp' ),
                'label_off' => esc_html__( 'No', 'element-camp' ),
                'default' => 'yes',
            ]
        );

        $cards_repeater->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'element-camp'),
                'default' => esc_html__('Business Strategy Development', 'element-camp'),
                'type' => Controls_Manager::TEXTAREA,
                'condition' => [
                    'title_switcher' => 'yes',
                ],
            ]
        );

        $cards_repeater->add_control(
            'description_switcher',
            [
                'label' => esc_html__( 'Description', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'yes', 'element-camp' ),
                'label_off' => esc_html__( 'No', 'element-camp' ),
                'default' => 'yes',
            ]
        );

        $cards_repeater->add_control(
            'description',
            [
                'label' => esc_html__('Description', 'element-camp'),
                'default' => esc_html__('Add Text Here.', 'element-camp'),
                'type' => Controls_Manager::TEXTAREA,
                'condition' => [
                    'description_switcher' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'cards',
            [
                'label' => esc_html__('Slides', 'element-camp'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $cards_repeater->get_controls(),
                'default' => [
                    [
                        'title' => esc_html__('Title #1', 'element-camp'),
                    ],
                    [
                        'title' => esc_html__('Title #2', 'element-camp'),
                    ],
                    [
                        'title' => esc_html__('Title #3', 'element-camp'),
                    ],
                ],
                'title_field' => '{{{title}}}',
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'section_cards_animations',
            [
                'label' => esc_html__( 'Animations', 'element-camp' ),
            ]
        );
        $this->add_control(
            'cards_animations',
            [
                'label' => esc_html__('Animations', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'scroll-animation' => esc_html__('Horizontal Scroll', 'element-camp'),
                    'preserve-3d' => esc_html__('Preserve 3D', 'element-camp'),
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'cards_wrapper_style',
            [
                'label' => esc_html__('Cards Wrapper Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_responsive_control(
            'cards_wrapper_display',
            [
                'label' => esc_html__('Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'inline-flex' => esc_html__('Inline Flex', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-cards' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'cards_wrapper_display_position',
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
                    '{{WRAPPER}} .tcgelements-cards' => '{{VALUE}}',
                ],
                'condition' => [ 'cards_wrapper_display' => ['flex','inline-flex'] ],
            ]
        );
        $this->add_responsive_control(
            'cards_wrapper_justify_content',
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
                    '{{WRAPPER}} .tcgelements-cards' => 'justify-content: {{VALUE}};',
                ],
                'condition'=> ['cards_wrapper_display'=> ['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'cards_wrapper_align_items',
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
                    '{{WRAPPER}} .tcgelements-cards' => 'align-items: {{VALUE}};',
                ],
                'condition'=> ['cards_wrapper_display'=> ['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'cards_wrapper_flex_wrap',
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
                    '{{WRAPPER}} .tcgelements-cards' => 'flex-wrap: {{VALUE}};',
                ],
                'condition'=> ['cards_wrapper_display'=> ['flex','inline-flex']],
            ]
        );
        $this->add_responsive_control(
            'cards_wrapper_flex_gaps_x',
            [
                'label' => esc_html__( 'Gaps (X)', 'element-camp' ),
                'condition' => [ 'cards_wrapper_display' => 'flex' ],
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%', 'px', 'vw', 'custom' ],
            ]
        );
        $this->add_responsive_control(
            'cards_wrapper_flex_gaps_y',
            [
                'label' => esc_html__( 'Gaps (Y)', 'element-camp' ),
                'condition' => [ 'cards_wrapper_display' => 'flex' ],
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%', 'px', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-cards' => 'gap: {{cards_wrapper_flex_gaps_x.SIZE}}{{cards_wrapper_flex_gaps_x.UNIT}} {{cards_wrapper_flex_gaps_y.SIZE}}{{cards_wrapper_flex_gaps_y.UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'style',
            [
                'label' => esc_html__('Card Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_responsive_control(
            'card_size_options',
            [
                'label' => esc_html__( 'Size', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-cards .card-item' => '{{VALUE}};',
                ],
                'condition' => [ 'cards_wrapper_display' => ['inline-flex','flex'] ],
                'responsive' => true,
            ]
        );

        $this->add_responsive_control(
            'card_flex_grow',
            [
                'label' => esc_html__( 'Flex Grow', 'element-camp' ),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-cards .card-item' => 'flex-grow: {{VALUE}};',
                ],
                'default' => 1,
                'placeholder' => 1,
                'responsive' => true,
                'condition' => [
                    'card_size_options' => 'custom',
                    'cards_wrapper_display' => ['inline-flex','flex']
                ],
            ]
        );

        $this->add_responsive_control(
            'card_flex_shrink',
            [
                'label' => esc_html__( 'Flex Shrink', 'element-camp' ),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-cards .card-item' => 'flex-shrink: {{VALUE}};',
                ],
                'default' => 1,
                'placeholder' => 1,
                'responsive' => true,
                'condition' => [
                    'card_size_options' => 'custom',
                    'cards_wrapper_display' => ['inline-flex','flex']
                ],
            ]
        );

        $this->add_responsive_control(
            'cards_width',
            [
                'label' => esc_html__( 'Card Width', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-cards .card-item' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_margin',
            [
                'label' => esc_html__('Card Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-cards .card-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label' => esc_html__('Card Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-cards .card-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'card_tabs',
        );
        
        $this->start_controls_tab(
            'normal_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_control(
            'card_transition',
            [
                'label' => esc_html__( 'Card Transition', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-cards .card-item' => 'transition: all {{SIZE}}s ease;',
                    '{{WRAPPER}} .tcgelements-cards .card-item' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'card_css_filters',
                'selector' => '{{WRAPPER}} .tcgelements-cards .card-item',
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'hover_tab',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'card_css_filters_hover',
                'selector' => '{{WRAPPER}} .tcgelements-cards .card-item:hover',
            ]
        );
        $this->end_controls_tab();
        
        $this->end_controls_tabs();

        $this->end_controls_section();
        $this->start_controls_section(
            'info_style',
            [
                'label' => esc_html__('Info Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );
        $this->add_control(
            'info_container_heading',
            [
                'label' => esc_html__( 'Info Container Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );
        $this->add_responsive_control(
            'info_section_padding',
            [
                'label' => esc_html__('Info Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-cards .card-item .cont' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'info_display',
            [
                'label' => esc_html__('Info Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'block',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'inline-flex' => esc_html__('Inline Flex', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                    'none' => esc_html__('None', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-cards .card-item .cont' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'info_justify_content',
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
                    '{{WRAPPER}} .tcgelements-cards .card-item .cont' => 'justify-content: {{VALUE}};',
                ],
                'condition'=>['info_display'=>['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'info_align_items',
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
                    '{{WRAPPER}} .tcgelements-cards .card-item .cont' => 'align-items: {{VALUE}};',
                ],
                'condition'=>['info_display'=>['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'info_container_flex_wrap',
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
                    '{{WRAPPER}} .tcgelements-cards .card-item .cont' => 'flex-wrap: {{VALUE}};',
                ],
                'condition'=>['info_display'=>['flex','inline-flex']],
            ]
        );
        $this->add_control(
            'title_heading',
            [
                'label' => esc_html__( 'Title Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );
        $this->add_responsive_control(
            'title_padding',
            [
                'label' => esc_html__('Title Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-cards .card-item .title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Title Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-cards .card-item .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__('Title', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-cards .card-item .title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-cards .card-item .title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'description_style',
            [
                'label' => esc_html__('Description Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'label' => esc_html__('Description', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-cards .card-item .description',
            ]
        );
        $this->add_responsive_control(
            'description_margin',
            [
                'label' => esc_html__('Description Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-cards .card-item .description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'description_color',
            [
                'label' => esc_html__( 'Description Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-cards .card-item .description' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'description_opacity',
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
                    '{{WRAPPER}} .tcgelements-cards .card-item .description' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'image_style',
            [
                'label' => esc_html__('Image Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );
        $this->add_control(
            'image_overflow_hidden',
            [
                'label' => esc_html__( 'Overflow Hidden', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'yes', 'element-camp' ),
                'label_off' => esc_html__( 'No', 'element-camp' ),
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-cards .card-item .img' => 'overflow:hidden;',
                ],
            ]
        );
        $this->add_control(
            'image_transition',
            [
                'label' => esc_html__( 'Image Transition', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-cards .card-item .img' => 'transition: all {{SIZE}}s ease;',
                    '{{WRAPPER}} .tcgelements-cards .card-item .img img' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );
        $this->add_control(
            'image_container_heading',
            [
                'label' => esc_html__( 'Image Container Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'after'
            ]
        );
        $this->add_responsive_control(
            'container_width',
            [
                'label' => esc_html__( 'Image Container Width', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-cards .card-item .img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'container_height',
            [
                'label' => esc_html__( 'Image Container Height', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-cards .card-item .img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs(
            'image_container_tabs',
        );

        $this->start_controls_tab(
            'image_container_normal_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'selector' => '{{WRAPPER}} .tcgelements-cards .card-item .img',
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'image_container_hover_tab',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'container_border_hover',
                'selector' => '{{WRAPPER}} .tcgelements-cards .card-item:hover .img',
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->add_responsive_control(
            'container_border_radius',
            [
                'label' => esc_html__( 'Image Container Border Radius', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-cards .card-item .img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after'
            ]
        );
        $this->add_control(
            'image_heading',
            [
                'label' => esc_html__( 'Image Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'after'
            ]
        );
        $this->add_responsive_control(
            'width',
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
                    '{{WRAPPER}} .tcgelements-cards .card-item .img img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'height',
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
                    '{{WRAPPER}} .tcgelements-cards .card-item .img img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'object-fit',
            [
                'label' => esc_html__( 'Object Fit', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'height[size]!' => '',
                ],
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'fill' => esc_html__( 'Fill', 'element-camp' ),
                    'cover' => esc_html__( 'Cover', 'element-camp' ),
                    'contain' => esc_html__( 'Contain', 'element-camp' ),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-cards .card-item .img img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'object-position',
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
                    '{{WRAPPER}} .tcgelements-cards .card-item .img img' => 'object-position: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-cards .card-item .img img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before'
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
                'selector' => '{{WRAPPER}} .tcgelements-cards .card-item .img img',
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'image_hover_tab',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'image_css_filters_hover',
                'selector' => '{{WRAPPER}} .tcgelements-cards .card-item:hover .img img',
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        ?>

        <div class="tcgelements-cards <?php if (!empty($settings['cards_animations']))echo esc_attr($settings['cards_animations'])?>">
            <?php foreach ($settings['cards'] as $card ) : ?>
                <?php if ($settings['cards_animations']=='scroll-animation') : ?>
                    <div class="panel">
                <?php endif;?>
                    <div class="card-item">
                        <div class="img">
                            <img src="<?=esc_url($card['image']['url'])?>" alt="<?php if (!empty($card['image']['alt'])) echo esc_attr($card['image']['alt']); ?>">
                        </div>
                        <div class="cont">
                            <h5 class="title"><?=esc_html($card['title'])?></h5>
                            <span class="description"><?=esc_html($card['description'])?></span>
                        </div>
                    </div>
                <?php if ($settings['cards_animations']=='scroll-animation') : ?>
                    </div>
                <?php endif;?>
            <?php endforeach; ?>
        </div>
        <?php
    }
}
