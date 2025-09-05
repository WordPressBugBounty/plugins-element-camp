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


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



/**
 * @since 1.1.0
 */
class ElementCamp_Marquee extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve icon list widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'tcg-marquee';
    }

    /**
     * Get widget title.
     *
     * Retrieve icon list widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__( 'Marquee', 'element-camp' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve icon list widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-slider-push tce-widget-badge';
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
    public function get_keywords() {
        return [ 'list' ];
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
     * Register icon list widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 3.1.0
     * @access protected
     */

    protected function register_controls(){
        $this->start_controls_section(
            'content',
            [
                'label' => __('Content', 'element-camp')
            ]
        );
        $this->add_control(
            'marque_option',
            [
                'label' => __( 'Option', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'text' => __( 'Text', 'element-camp' ),
                    'images' => __( 'Images', 'element-camp' ),
                ],
                'default' => 'text',
            ]
        );
        $this->add_control(
            'images_option',
            [
                'label' => __( 'Option', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'gallery' => esc_html__( 'Gallery', 'element-camp' ),
                    'repeater' => esc_html__( 'Repeater', 'element-camp' ),
                ],
                'condition' => ['marque_option' => 'images'],
                'default' => 'gallery',
            ]
        );
        $repeater = new Repeater();
        $repeater->add_control(
            'text',
            [
                'label' => __('Text', 'element-camp'),
                'type' => Controls_Manager::TEXT
            ]
        );
        $repeater->add_control(
            'separator',
            [
                'label' => __('Separator', 'element-camp'),
                'type' => Controls_Manager::TEXT
            ]
        );
        $repeater->add_control(
            'link',
            [
                'label' => __('Link', 'element-camp'),
                'type' => Controls_Manager::URL
            ]
        );

        $repeater->add_control(
            'icon',
            [
                'label' => __('Icon', 'element-camp'),
                'type' => Controls_Manager::ICONS,
            ]
        );

        $repeater->add_control(
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
                    'icon[value]!' => '',
                ],
            ]
        );

        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'item_typography',
                'selector' => '{{WRAPPER}} .tcg-marquee .main-marq {{CURRENT_ITEM}}.item h4 .text',
            ]
        );

        $repeater->add_control(
            'item_color',
            [
                'label' => __('Item Color', 'element-camp'),
                'separator' => 'before',
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcg-marquee .main-marq {{CURRENT_ITEM}}.item h4 .text , {{WRAPPER}} .tcg-marquee .main-marq {{CURRENT_ITEM}}.item h4 .separator' => 'color: {{VALUE}}'
                ]
            ]
        );

        $repeater->add_control(
            'item_icon_color',
            [
                'label' => esc_html__('Icon Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcg-marquee .main-marq {{CURRENT_ITEM}}.item h4 i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcg-marquee .main-marq {{CURRENT_ITEM}}.item h4 svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_control(
            'item_opacity',
            [
                'label' => esc_html__( 'Text Opacity', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcg-marquee .main-marq {{CURRENT_ITEM}}.item h4 .text , {{WRAPPER}} .tcg-marquee .main-marq {{CURRENT_ITEM}}.item h4 .separator' => 'opacity: {{SIZE}};'
                ],
            ]
        );
        $repeater->add_group_control(
            \Elementor\Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'item_text_stroke',
                'selector' => '{{WRAPPER}} .tcg-marquee .main-marq {{CURRENT_ITEM}}.item h4 .text , {{WRAPPER}} .tcg-marquee .main-marq {{CURRENT_ITEM}}.item h4 .separator',
            ]
        );
        $repeater->add_responsive_control(
            'item_separator_margin',
            [
                'label' => esc_html__( 'Margin', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcg-marquee .main-marq {{CURRENT_ITEM}}.item h4 .separator' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $repeater->add_control(
            'separator_options',
            [
                'label' => esc_html__( 'Separator', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'before',
            ]
        );
        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'sep_typography',
                'label' => esc_html__('Separator Typography', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcg-marquee .main-marq .item h4 .separator',
            ]
        );
        $repeater->add_control(
            'dark_mode_repeater_options',
            [
                'label' => esc_html__( 'Dark Mode', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'before',
            ]
        );
        $repeater->add_control(
            'item_color_dark',
            [
                'label' => esc_html__('Item Color', 'element-camp'),
                'separator' => 'before',
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcg-marquee .main-marq {{CURRENT_ITEM}}.item h4 .text' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcg-marquee .main-marq {{CURRENT_ITEM}}.item h4 .text' => 'color: {{VALUE}};',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcg-marquee .main-marq {{CURRENT_ITEM}}.item h4 .separator' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcg-marquee .main-marq {{CURRENT_ITEM}}.item h4 .separator' => 'color: {{VALUE}};',
                ]
            ]
        );
        $repeater->add_control(
            'item_icon_color_dark_mode',
            [
                'label' => esc_html__('Icon Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcg-marquee .main-marq {{CURRENT_ITEM}}.item h4 i' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcg-marquee .main-marq {{CURRENT_ITEM}}.item h4 i' => 'color: {{VALUE}};',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcg-marquee .main-marq {{CURRENT_ITEM}}.item h4 svg' => 'fill: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcg-marquee .main-marq {{CURRENT_ITEM}}.item h4 svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $repeater->add_control(
            'item_stroke_color_dark_mode',
            [
                'label' => esc_html__('Item Stroke Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcg-marquee .main-marq {{CURRENT_ITEM}}.item h4' => '-webkit-text-stroke-color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcg-marquee .main-marq {{CURRENT_ITEM}}.item h4' => '-webkit-text-stroke-color: {{VALUE}};',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcg-marquee .main-marq {{CURRENT_ITEM}}.item h4 .text' => '-webkit-text-stroke-color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcg-marquee .main-marq {{CURRENT_ITEM}}.item h4 .text' => '-webkit-text-stroke-color: {{VALUE}};',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcg-marquee .main-marq {{CURRENT_ITEM}}.item h4 .separator' => '-webkit-text-stroke-color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcg-marquee .main-marq {{CURRENT_ITEM}}.item h4 .separator' => '-webkit-text-stroke-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'marquee_rep',
            [
                'label' => esc_html__('Margquee Repeater', 'element-camp'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'condition' => ['marque_option'=>'text'],
                'field_title' => '{{{text}}}'
            ]
        );
        $this->add_control(
            'curves',
            [
                'label'         => __( 'Curves', 'element-camp' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Yes', 'element-camp' ),
                'label_off'     => __( 'No', 'element-camp' ),
                'condition'     => ['marque_option'=>'images'],
                'return_value'  => 'yes',
                'default'  		=> 'yes',
            ]
        );
        $this->add_control(
            'gallery',
            [
                'label' => esc_html__('Choose Images', 'element-camp'),
                'condition' => [
                    'marque_option'=>'images',
                    'images_option' => 'gallery'
                ],
                'type' => Controls_Manager::GALLERY
            ]
        );
        $repeater2 = new Repeater();
        $repeater2->add_control(
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
        $repeater2->add_control(
            'link',
            [
                'label' => __('Link', 'element-camp'),
                'type' => Controls_Manager::URL
            ]
        );
        $this->add_control(
            'images_rep',
            [
                'label' => __('Images Repeater', 'element-camp'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater2->get_controls(),
                'condition' => [
                    'marque_option'=>'images',
                    'images_option' => 'repeater'
                ],
            ]
        );
        $this->add_control(
            'animation_speed',
            [
                'label' => esc_html__( 'Speed', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 200,
                        'min' => 80,
                        'step' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcg-marquee .main-marq .slide-har.st1 .box' => ' animation-duration: {{SIZE}}s;',
                ],
            ]
        );
        $this->add_control(
            'overflow',
            [
                'label' => esc_html__('Overflow Hidden', 'element-camp'),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => esc_html__('Off', 'element-camp'),
                'label_on' => esc_html__('On', 'element-camp'),
            ]
        );
        $this->add_control(
            'slide_option',
            [
                'label' => __('Slide Option', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'st1' => __('Slide Left', 'element-camp'),
                    'st2' => __('Slide Right', 'element-camp'),
                ],
                'default' => 'st1',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'styles',
            [
                'label' => __('Styles', 'element-camp'),
                'condition' => ['marque_option'=>'text'],
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'opacity',
            [
                'label' => esc_html__( 'Marquee Opacity', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcg-marquee .main-marq' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_control(
			'rotate',
			[
				'label' => esc_html__( 'Marquee Rotate', 'element-camp' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -360,
						'max' => 360,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tcg-marquee .main-marq' => 'transform: rotate({{SIZE}}deg);',
				],
			]
		);
        
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'label' => esc_html__('Marquee Background', 'element-camp'),
				'types' => ['classic', 'gradient'],
				'exclude' => ['image'],
				'selector' => '{{WRAPPER}} .tcg-marquee .main-marq',
			]
		);
        
        $this->add_control(
            'background_dark_mode',
            [
                'label' => __('Marquee Background (Dark Mode)', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcg-marquee .main-marq' => 'background-color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcg-marquee .main-marq' => 'background-color: {{VALUE}};',
                ],
            ]
        );

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'item_border',
				'label' => __( 'Marquee Border', 'element-camp' ),
				'selector' => '{{WRAPPER}} .tcg-marquee .main-marq',
			]
		);

		$this->add_responsive_control(
			'item_border_radius',
			[
				'label' => esc_html__( 'Marquee Border Radius', 'element-camp' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .tcg-marquee .main-marq' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
            'item_border_color_dark',
            [
                'label' => __('Item Border Color ( Dark Mode )', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
					'@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcg-marquee .main-marq' => 'border-color: {{VALUE}};',
					'} body.tcg-dark-mode {{WRAPPER}} .tcg-marquee .main-marq' => 'border-color: {{VALUE}};',
				],
            ]
        );
        $this->add_control(
            'item_style_options',
            [
                'label' => esc_html__( 'Item Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'before'
            ]
        );
        $this->add_responsive_control(
            'item_padding',
            [
                'label' => esc_html__('Item Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcg-marquee .main-marq .box .item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'item_margin',
            [
                'label' => esc_html__('Item Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcg-marquee .main-marq .box .item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'item_background',
                'label' => esc_html__('Item Background', 'element-camp'),
                'types' => ['classic', 'gradient'],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .tcg-marquee .main-marq .box .item',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'sub_item_border',
                'label' => esc_html__( 'Item Border', 'element-camp' ),
                'selector' => '{{WRAPPER}} .tcg-marquee .main-marq .box .item',
            ]
        );

        $this->add_responsive_control(
            'sub_item_border_radius',
            [
                'label' => esc_html__( 'Item Border Radius', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .tcg-marquee .main-marq .box .item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_box_shadow',
                'selector' => '{{WRAPPER}} .tcg-marquee .main-marq .box .item',
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'text_styles',
            [
                'label' => esc_html__('Text Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['marque_option'=>'text'],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'label' => esc_html__('Text', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcg-marquee .main-marq .box .item h4',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'text_background_color',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcg-marquee .main-marq .box .item h4',
            ]
        );
        $this->add_control(
            'background_clip_text',
            [
                'label' => esc_html__('Background Clip Text', 'element-camp'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'selectors' => [
                    '{{WRAPPER}} .tcg-marquee .main-marq .box .item h4' => 'background-clip: text;-webkit-background-clip: text;',
                ],
            ]
        );
        $this->add_control(
            'text_opacity',
            [
                'label' => esc_html__( 'Text Opacity', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcg-marquee .main-marq .box .item h4' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'text_padding',
            [
                'label' => esc_html__('Item Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcg-marquee .main-marq .box .item h4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'text_border',
                'label' => esc_html__( 'Text Border', 'element-camp' ),
                'selector' => '{{WRAPPER}} .tcg-marquee .main-marq .box .item h4',
            ]
        );

        $this->add_responsive_control(
            'text_border_radius',
            [
                'label' => esc_html__( 'Text Border Radius', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .tcg-marquee .main-marq .box .item h4' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'text_box_shadow',
                'selector' => '{{WRAPPER}} .tcg-marquee .main-marq .box .item h4',
            ]
        );
        $this->start_controls_tabs(
            'text_style_tabs',
        );
        
        $this->start_controls_tab(
            'text_style_tab_normal',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'text_stroke',
                'selector' => '{{WRAPPER}} .tcg-marquee .main-marq .box .item h4 .text',
            ]
        );
        $this->add_control(
            'text_color',
            [
                'label' => esc_html__( 'Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcg-marquee .main-marq .box .item h4' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'text_style_tab_hover',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
            ]
        );
        $this->add_control(
            'item_transition',
            [
                'label' => esc_html__( 'Text Transition', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcg-marquee .main-marq .box .item' => 'transition: all {{SIZE}}s ease;',
                    '{{WRAPPER}} .tcg-marquee .main-marq .box .item a' => 'transition: all {{SIZE}}s ease;',
                    '{{WRAPPER}} .tcg-marquee .main-marq .box .item h4' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'text_stroke_hover',
                'selector' => '{{WRAPPER}} .tcg-marquee .main-marq .box .item:hover h4 .text',
            ]
        );
        $this->add_control(
            'text_color_hover',
            [
                'label' => esc_html__( 'Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcg-marquee .main-marq .box .item:hover h4' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        $this->add_control(
            'dark_mode_style_options',
            [
                'label' => esc_html__( 'Dark Mode', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'before'
            ]
        );
        $this->add_control(
            'text_color_dark_mode',
            [
                'label' => esc_html__( 'Text Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcg-marquee .main-marq .box .item h4' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcg-marquee .main-marq .box .item h4' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'stroke_color_dark_mode',
            [
                'label' => esc_html__( 'Stroke Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcg-marquee .main-marq .box .item h4' => '-webkit-text-stroke-color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcg-marquee .main-marq .box .item h4' => '-webkit-text-stroke-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'item_overlay_section',
            [
                'label' => esc_html__('Item Overlay', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['marque_option'=>'text'],
            ]
        );
        $this->add_control(
            'item_index',
            [
                'label' => esc_html__( 'z-index', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tcg-marquee .main-marq .item::after' => 'z-index: {{SIZE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'item_overlay_offset_x',
            [
                'label' => esc_html__( 'Left', 'element-camp' ),
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
                    '{{WRAPPER}} .tcg-marquee .main-marq .item::after' => 'left: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'item_overlay_offset_x_end',
            [
                'label' => esc_html__( 'Right', 'element-camp' ),
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
                    '{{WRAPPER}} .tcg-marquee .main-marq .item::after' => 'right: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'item_overlay_offset_y',
            [
                'label' => esc_html__( 'Top', 'element-camp' ),
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
                    '{{WRAPPER}} .tcg-marquee .main-marq .item::after' => 'top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'item_overlay_offset_y_end',
            [
                'label' => esc_html__( 'Bottom', 'element-camp' ),
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
                    '{{WRAPPER}} .tcg-marquee .main-marq .item::after' => 'bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'item_overlay_width',
            [
                'label' => esc_html__( 'Width', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
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
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcg-marquee .main-marq .item::after' => 'position:absolute;content:"";width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'item_overlay_height',
            [
                'label' => esc_html__( 'Height', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
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
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcg-marquee .main-marq .item::after' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'item_overlay_bg',
                'selector' => '{{WRAPPER}} .tcg-marquee .main-marq .item::after',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
            ]
        );
        $this->add_responsive_control(
            'item_overlay_border_radius',
            [
                'label' => esc_html__( 'Item Overlay Border Radius', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .tcg-marquee .main-marq .item::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'item_overlay_translate_y',
            [
                'label' => esc_html__( 'Translate Y', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'default' => [
                    'size' => 0,
                ],
            ]
        );
        $this->add_control(
            'item_overlay_translate_x',
            [
                'label' => esc_html__( 'Translate X', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'default' => [
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcg-marquee .main-marq .item::after' => 'transform: translate({{item_overlay_translate_x.SIZE}}{{item_overlay_translate_x.UNIT}},{{item_overlay_translate_y.SIZE}}{{item_overlay_translate_y.UNIT}})',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'icon_styles',
            [
                'label' => esc_html__('Icon Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['marque_option'=>'text'],
            ]
        );
        $this->add_control(
            'item_icon_style_options',
            [
                'label' => esc_html__( 'Item Icon Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'before'
            ]
        );
        $this->add_responsive_control(
            'item_icon_size',
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
                    '{{WRAPPER}} .tcg-marquee .main-marq .box .item i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcg-marquee .main-marq .box .item svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'icon_stroke',
                'selector' => '{{WRAPPER}} .tcg-marquee .main-marq .box .item i,{{WRAPPER}} .tcg-marquee .main-marq .box .item svg',
            ]
        );
        $this->add_control(
            'item_icon_margin',
            [
                'label' => esc_html__('Icon Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em' , 'rem' , 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcg-marquee .main-marq .box .item i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcg-marquee .main-marq .box .item svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'item_icon_color',
            [
                'label' => esc_html__('Icon Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcg-marquee .main-marq .box .item i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcg-marquee .main-marq .box .item svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'item_icon_color_dark_mode',
            [
                'label' => esc_html__('Icon Color (Dark Mode)', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcg-marquee .main-marq .box .item i' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcg-marquee .main-marq .box .item i' => 'color: {{VALUE}};',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcg-marquee .main-marq .box .item svg' => 'fill: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcg-marquee .main-marq .box .item svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_opacity',
            [
                'label' => esc_html__( 'Icon Opacity', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcg-marquee .main-marq .box .item i' => 'opacity: {{SIZE}};',
                    '{{WRAPPER}} .tcg-marquee .main-marq .box .item svg' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'images_styles',
            [
                'label' => __('Styles', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['marque_option'=>'images'],
            ]
        );
        $this->add_control(
            'item_style_image_options',
            [
                'label' => esc_html__( 'Item Style', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'before'
            ]
        );
        $this->add_responsive_control(
            'item_images_padding',
            [
                'label' => esc_html__('Item Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcg-marquee .main-marq .box .item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'item_images_margin',
            [
                'label' => esc_html__('Item Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcg-marquee .main-marq .box .item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'image_container_style_options',
            [
                'label' => esc_html__( 'Image Container Style', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'before'
            ]
        );
        $this->add_control(
            'image_container_overflow',
            [
                'label' => esc_html__('Image Container Overflow Hidden', 'element-camp'),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => esc_html__('Off', 'element-camp'),
                'label_on' => esc_html__('On', 'element-camp'),
                'selectors' => [
                    '{{WRAPPER}} .tcg-marquee .main-marq .box .item .img' => 'overflow: hidden;',
                ],
            ]
        );
        $this->add_responsive_control(
            'width',
            [
                'label' => esc_html__( 'Image Container Width', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcg-marquee .main-marq .box .item .img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'height',
            [
                'label' => esc_html__( 'Image Container Height', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcg-marquee .main-marq .box .item .img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'max_height',
            [
                'label' => esc_html__( 'Image Container Max Height', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcg-marquee .main-marq .box .item .img' => 'max-height: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );
        $this->add_control(
            'image_style_options',
            [
                'label' => esc_html__( 'Image Style', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'before'
            ]
        );
        $this->add_responsive_control(
            'img_width',
            [
                'label' => esc_html__( 'Image Width', 'element-camp' ),
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
                    '{{WRAPPER}} .tcg-marquee .main-marq .box .item .img img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'img_height',
            [
                'label' => esc_html__( 'Image Height', 'element-camp' ),
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
                    '{{WRAPPER}} .tcg-marquee .main-marq .box .item .img img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'img_max_height',
            [
                'label' => esc_html__( 'Image Max Height', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcg-marquee .main-marq .box .item .img img' => 'max-height: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );
        $this->add_responsive_control(
            'img_object-fit',
            [
                'label' => esc_html__( 'Object Fit', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'img_height[size]!' => '',
                ],
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'fill' => esc_html__( 'Fill', 'element-camp' ),
                    'cover' => esc_html__( 'Cover', 'element-camp' ),
                    'contain' => esc_html__( 'Contain', 'element-camp' ),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcg-marquee .main-marq .box .item .img img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'img_object-position',
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
                    '{{WRAPPER}} .tcg-marquee .main-marq .box .item .img img' => 'object-position: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_box_shadow',
                'selector' => '{{WRAPPER}} .tcg-marquee .main-marq .box .item .img img',
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'marquee_overlay_section',
            [
                'label' => esc_html__('Marquee Overlay', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'marquee_overlay_offset_x',
            [
                'label' => esc_html__( 'Left', 'element-camp' ),
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
                    '{{WRAPPER}} .tcg-marquee .main-marq::after' => 'left: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'marquee_overlay_offset_x_end',
            [
                'label' => esc_html__( 'Right', 'element-camp' ),
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
                    '{{WRAPPER}} .tcg-marquee .main-marq::after' => 'right: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'marquee_overlay_offset_y',
            [
                'label' => esc_html__( 'Top', 'element-camp' ),
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
                    '{{WRAPPER}} .tcg-marquee .main-marq::after' => 'top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'marquee_overlay_offset_y_end',
            [
                'label' => esc_html__( 'Bottom', 'element-camp' ),
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
                    '{{WRAPPER}} .tcg-marquee .main-marq::after' => 'bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'marquee_overlay_width',
            [
                'label' => esc_html__( 'Width', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
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
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcg-marquee .main-marq::after' => 'position:absolute;content:"";width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'marquee_overlay_height',
            [
                'label' => esc_html__( 'Height', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
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
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcg-marquee .main-marq::after' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'marquee_overlay_bg',
                'selector' => '{{WRAPPER}} .tcg-marquee .main-marq::after',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
            ]
        );
        $this->end_controls_section();
    }

    protected function render(){
        $settings = $this->get_settings();
        ?>
        <div class="tcg-marquee">
           <?php if ($settings['marque_option']=='images' && $settings['curves']=='yes') : ?>
               <div class="curvtop"></div>
               <div class="curvbotm"></div>
            <?php endif;?>
            <div class="main-marq <?=$settings['marque_option']?> <?php if ($settings['overflow']=='yes')echo 'o-hidden'?>">
                <div class="slide-har <?=$settings['slide_option']?>">
                    <?php for ($i=0;$i<2;$i++) :?>
                        <div class="box">
                            <?php if ($settings['marque_option']=='text'):?>
                            <?php foreach($settings['marquee_rep'] as $item) : ?>
                                <div class="item <?php echo 'elementor-repeater-item-' . esc_attr( $item['_id'] ) . ''; ?>">
                                    <?php if (!empty($item['link']['url'])) : ?>
                                    <a href="<?=esc_url($item['link']['url'])?>" <?php if ( $item['link']['is_external'] ) echo'target="_blank"'; ?>>
                                    <?php endif;?>
                                    <h4 class="d-flex align-items-center">
                                        <?php if ($item['icon_align']=='left') : ?>
                                            <?php Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true']); ?>
                                        <?php endif;?>
                                        <span class="text"><?= esc_html($item['text']); ?></span>
                                        <?php if ($item['icon_align']=='right') : ?>
                                            <?php Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true']); ?>
                                        <?php endif;?>
                                        <?php if(!empty($item['separator'])): ?>
                                            <span class="separator"><?= esc_html($item['separator']) ?></span>
                                        <?php endif; ?>
                                    </h4>
                                    <?php if (!empty($item['link']['url'])) : ?>
                                    </a>
                                    <?php endif;?>
                                </div>
                            <?php endforeach; ?>
                            <?php else:?>
                                <?php if ($settings['images_option']=='gallery') : ?>
                                    <?php foreach($settings['gallery'] as $image) : ?>
                                        <div class="item">
                                            <div class="img">
                                                <img src="<?= esc_url($image['url']); ?>" alt="<?php if (!empty($image['alt'])) echo esc_attr($image['alt']); ?>">
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif;?>
                                <?php if ($settings['images_option']=='repeater') : ?>
                                    <?php foreach($settings['images_rep'] as $item) : ?>
                                        <div class="item">
                                            <a class="img" href="<?= esc_url($item['link']['url']) ?>" <?php if ( $item['link']['is_external'] ) echo'target="_blank"'; ?>>
                                                <img src="<?= esc_url($item['image']['url']); ?>" alt="<?php if (!empty($item['image']['alt'])) echo esc_attr($item['image']['alt']); ?>">
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif;?>
                            <?php endif;?>
                        </div>
                    <?php endfor;?>
                </div>
            </div>
        </div>
        <?php
    }
}