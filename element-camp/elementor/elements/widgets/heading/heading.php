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
/**
 * Elementor heading widget.
 *
 * Elementor widget that displays an eye-catching headlines.
 *
 * @since 1.0.0
 */
class ElementCamp_Heading extends \Elementor\Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve heading widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'tcgelements-heading';
    }

    /**
     * Get widget title.
     *
     * Retrieve heading widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return esc_html__('Heading', 'element-camp');
    }
    /**
     * Get widget icon.
     *
     * Retrieve heading widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'eicon-heading tce-widget-badge';
    }
     public function get_script_depends()
    {
        return ['tcgelements-heading','tcgelements-split-text','tcgelements-scroll-end', 'tcgelements-scroll-fill-text','tcgelements-letters-line','tcgelements-fade-text','tcgelements-funky-letters'];
    }
    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the heading widget belongs to.
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
    public function get_keywords() {
        return [ 'heading', 'title', 'text' ];
    }
    /**
     * Register heading widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 3.1.0
     * @access protected
     */
    protected function register_controls() {
        $start = is_rtl() ? esc_html__('Right', 'themescamp-plugin') : esc_html__('Left', 'themescamp-plugin');
        $end = !is_rtl() ? esc_html__('Right', 'themescamp-plugin') : esc_html__('Left', 'themescamp-plugin');

        $this->start_controls_section(
            'section_title',
            [
                'label' => esc_html__( 'Tcgelements Title', 'element-camp' ),
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__( 'Title', 'element-camp' ),
                'type' => Controls_Manager::TEXTAREA,
                'ai' => [
                    'type' => 'text',
                ],
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__( 'Enter your title', 'element-camp' ),
                'description' => esc_html__( 'You can use <span></span> and <small></small> to set different style', 'element-camp' ),
                'default' => __( 'Add Your Heading Text Here', 'element-camp' ),
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => esc_html__( 'Link', 'element-camp' ),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => '',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'size',
            [
                'label' => esc_html__( 'Size', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => esc_html__( 'Default', 'element-camp' ),
                    'small' => esc_html__( 'Small', 'element-camp' ),
                    'medium' => esc_html__( 'Medium', 'element-camp' ),
                    'large' => esc_html__( 'Large', 'element-camp' ),
                    'xl' => esc_html__( 'XL', 'element-camp' ),
                    'xxl' => esc_html__( 'XXL', 'element-camp' ),
                ],
            ]
        );

        $this->add_control(
            'header_size',
            [
                'label' => esc_html__( 'HTML Tag', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ],
                'default' => 'h2',
            ]
        );

        $this->add_responsive_control(
            'align',
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
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'text_wrap',
            [
                'label' => esc_html__('Text Wrap', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'wrap' => esc_html__('Wrap', 'element-camp'),
                    'nowrap' => esc_html__('No Wrap', 'element-camp'),
                    'balance' => esc_html__('Balance', 'element-camp'),
                    'pretty' => esc_html__('Pretty', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading' => 'text-wrap: {{VALUE}};'
                ]
            ]
        );
        $this->add_control(
            'word_wrap',
            [
                'label' => esc_html__('Word Wrap', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'break-word' => esc_html__('Break Word', 'element-camp'),
                    'normal' => esc_html__('Normal', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading' => 'word-wrap: {{VALUE}};'
                ]
            ]
        );
        $this->add_control(
            'white_space',
            [
                'label' => esc_html__('White Space', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'normal' => esc_html__('Normal', 'element-camp'),
                    'nowrap' => esc_html__('No Wrap', 'element-camp'),
                    'break-spaces' => esc_html__('Break Spaces', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text' => 'white-space: {{VALUE}};'
                ]
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
                    '{{WRAPPER}} .tcgelements-heading-text' => 'writing-mode: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'wrapper_heading_display',
            [
                'label' => esc_html__('Wrapper Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'block',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text' => 'display: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-heading-text a' => 'display: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-heading-text' => 'justify-content: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-heading-text a' => 'justify-content: {{VALUE}};',
                ],
                'condition'=>['wrapper_heading_display'=>'flex'],
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
                    '{{WRAPPER}} .tcgelements-heading-text' => 'align-items: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-heading-text a' => 'align-items: {{VALUE}};',
                ],
                'condition'=>['wrapper_heading_display'=>'flex'],
                'responsive' => true,
            ]);
        $this->add_control(
            'flex_wrap',
            [
                'label' => esc_html__('Flex Wrap', 'element-camp'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'condition'=>['wrapper_heading_display'=>'flex'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text' => 'flex-wrap: wrap;',
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
//        tcgelements style options
        $this->add_control(
            'view',
            [
                'label' => esc_html__( 'View', 'element-camp' ),
                'type' => Controls_Manager::HIDDEN,
                'default' => 'traditional',
            ]
        );
        $this->add_control(
            'after_icon',
            [
                'label'         => esc_html__( 'Icon', 'element-camp' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Yes', 'element-camp' ),
                'label_off'     => esc_html__( 'No', 'element-camp' ),
                'return_value'  => 'yes',
                'default'  		=> 'no',
            ]
        );

        $this->add_control(
            'icon_position',
            [
                'label' => esc_html__( 'Icon Position', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'element-camp' ),
                        'icon' => 'eicon-order-start',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'element-camp' ),
                        'icon' => 'eicon-order-end',
                    ],
                ],
                'default' => 'left',
                'condition'=>['after_icon'=>'yes']
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
                'condition' => [
                    'after_icon' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'after_image',
            [
                'label'         => esc_html__( 'Image', 'element-camp' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Yes', 'element-camp' ),
                'label_off'     => esc_html__( 'No', 'element-camp' ),
                'return_value'  => 'yes',
                'default'  		=> 'no',
            ]
        );

        $this->add_control(
            'image_position',
            [
                'label' => esc_html__( 'Image Position', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'element-camp' ),
                        'icon' => 'eicon-order-start',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'element-camp' ),
                        'icon' => 'eicon-order-end',
                    ],
                ],
                'default' => 'left',
                'condition'=>['after_image'=>'yes']
            ]
        );

        $this->add_control(
            'image',
            [
                'label' => esc_html__('Choose Image', 'element-camp'),
                'type' => Controls_Manager::MEDIA,
                'condition'=>['after_image'=>'yes'],
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );
        $this->add_control(
            'widget_transition',
            [
                'label' => esc_html__( 'Transition', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__( 'Title', 'element-camp' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'widget_heading_padding',
            [
                'label' => esc_html__('Wrapper Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );
        $this->start_controls_tabs( 'heading_color' );
        $this->start_controls_tab( 'normal',
            [
                'label' => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-heading',
            ]
        );
        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Text Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-heading' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'title_color_dark_mode',
            [
                'label' => esc_html__( 'Text Color (Dark Mode)', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-heading-text' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-heading-text' => 'color: {{VALUE}};',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-heading' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-heading' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'text_stroke',
                'selector' => '{{WRAPPER}} .tcgelements-heading',
            ]
        );

        $this->add_control(
            'text_stroke_dark_mode',
            [
                'label' => esc_html__( 'Stroke Color (Dark Mode)', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-heading' => '-webkit-text-stroke-color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-heading' => '-webkit-text-stroke-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'text_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-heading',
            ]
        );

        $this->add_responsive_control(
            'heading_opacity',
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
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'text_indent',
            [
                'label' => esc_html__('Text Indent', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em' ,'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading' => 'text-indent: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'heading_transition',
            [
                'label' => esc_html__( 'Heading Transition', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading' => 'transition: all {{SIZE}}s ease;',
                    '{{WRAPPER}} .tcgelements-heading-text' => 'transition: all {{SIZE}}s ease;',
                ],
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
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading' => 'background-clip: text;-webkit-background-clip: text;',
                    '{{WRAPPER}} .tcgelements-heading-text svg' => 'background-clip: text;-webkit-background-clip: text;',
                    '{{WRAPPER}} .tcgelements-heading-text i' => 'background-clip: text;-webkit-background-clip: text;',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'title_background_color',
                'label' => esc_html__('Text Background', 'element-camp'),
                'types' => [ 'classic','gradient','tcg_gradient', 'tcg_gradient_4' ],
                'selector' => '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading,{{WRAPPER}} .tcgelements-heading-text svg',
            ]
        );
        $this->add_control(
            'double_background',
            [
                'label' => esc_html__('Double Background', 'element-camp'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'description' => esc_html__('Combine two backgrounds for complex gradient effects. For best results, use gradients for both layers.', 'element-camp'),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'double_title_background_color',
                'label' => esc_html__('Text Background', 'element-camp'),
                'condition' => ['double_background' => 'yes'],
                'types' => [ 'classic','gradient','tcg_gradient', 'tcg_gradient_4' ],
                'selector' => '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading,{{WRAPPER}} .tcgelements-heading-text svg',
            ]
        );
        $this->add_control(
            'heading_blur_method',
            [
                'label' => esc_html__('Blur Method', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'backdrop-filter' => 'backdrop-filter',
                    'filter' => 'filter',
                ],
                'default' => 'backdrop-filter',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading' => '{{VALUE}}: blur({{blur_value.SIZE}}px);',
                ],
            ]
        );
        $this->add_control(
            'blur_value',
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
        $this->add_control(
            'clip_blur_on_text',
            [
                'label' => esc_html__('Clip Blur For Text', 'element-camp'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading' => '-webkit-mask: linear-gradient(#000000 0 0) text;',
                ],
                'condition' => [
                        'blur_value[size]!' => ''
                ]
            ]
        );
        $this->add_responsive_control(
            'translate_heading_y',
            [
                'label' => esc_html__( 'Heading Wrapper Translate Y', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
            ]
        );
        $this->add_responsive_control(
            'rotate_heading',
            [
                'label' => esc_html__( 'Heading Wrapper Rotate', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'deg','custom'],
                'default' => [
                    'size' => 0,
                    'unit' => 'deg',
                ],
                'tablet_default' => [
                    'unit' => 'deg',
                ],
                'mobile_default' => [
                    'unit' => 'deg',
                ],
            ]
        );
        $this->add_control(
            'scale_heading',
            [
                'label' => esc_html__('Heading Wrapper Scale', 'themescamp-plugin'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
            ]
        );
        $this->add_responsive_control(
            'translate_heading_x',
            [
                'label' => esc_html__( 'Heading Wrapper Translate X', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text' => 'transform: rotate({{rotate_heading.SIZE}}{{rotate_heading.UNIT}}) translate({{translate_heading_x.SIZE}}{{translate_heading_x.UNIT}},{{translate_heading_y.SIZE}}{{translate_heading_y.UNIT}}) scale({{scale_heading.SIZE || 1}})',
                ],
            ]
        );
        $this->add_responsive_control(
            'transform_origin_heading',
            [
                'label' => esc_html__('Heading Wrapper Transform Origin', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'center center' => esc_html__('Center Center', 'element-camp'),
                    'center left' => esc_html__('Center Left', 'element-camp'),
                    'center right' => esc_html__('Center Right', 'element-camp'),
                    'top center' => esc_html__('Top Center', 'element-camp'),
                    'top left' => esc_html__('Top Left', 'element-camp'),
                    'top right' => esc_html__('Top Right', 'element-camp'),
                    'bottom center' => esc_html__('Bottom Center', 'element-camp'),
                    'bottom left' => esc_html__('Bottom Left', 'element-camp'),
                    'bottom right' => esc_html__('Bottom Right', 'element-camp'),
                    'custom' => esc_html__('Custom', 'element-camp'),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text' => 'transform-origin: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'transform_origin_y',
            [
                'label' => esc_html__( 'Transform Origin Y', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'condition' => ['transform_origin_heading' => 'custom']
            ]
        );
        $this->add_responsive_control(
            'transform_origin_x',
            [
                'label' => esc_html__( 'Transform Origin X', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text' => 'transform-origin: {{transform_origin_x.SIZE}}{{transform_origin_x.UNIT}} {{transform_origin_y.SIZE}}{{transform_origin_y.UNIT}}',
                ],
                'condition' => ['transform_origin_heading' => 'custom']
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab( 'hover',
            [
                'label' => esc_html__( 'Hover', 'element-camp' ),
            ]
        );
        $this->add_control(
            'heading_hover_selector',
            [
                'label' => esc_html__('Choose Selector', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'heading',
                'options' => [
                    'heading' => esc_html__('Heading', 'element-camp'),
                    'container'  => esc_html__('Parent Container', 'element-camp'),
                    'parent-container'  => esc_html__('Parent of Parent Container', 'element-camp'),
                    'parent-n'  => esc_html__('Parent N', 'element-camp'),
                ],
            ]
        );
        $this->add_control(
            'heading_hover_selector_important_note',
            [
                'type' => \Elementor\Controls_Manager::ALERT,
                'alert_type' => 'info',
                'heading' => esc_html__('Selector Note', 'element-camp'),
                'content' => esc_html__('When "Parent N" is selected, set the number of parent elements to target.', 'element-camp'),
                'condition' => [
                    'heading_hover_selector' => 'parent-n',
                ],
            ]
        );

        $this->add_control(
            'parent_level',
            [
                'label' => esc_html__('Set Parent Levels', 'element-camp'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'default' => 1,
                'condition' => [
                    'heading_hover_selector' => 'parent-n',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'hover_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-heading-text:hover .tcgelements-heading,
                .e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-heading-text.tc-heading-container-active,
                .e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-heading-text.tc-heading-container-active > *',
            ]
        );
        $this->add_control(
            'hover_title_color',
            [
                'label' => esc_html__( 'Text Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text:hover .tcgelements-heading' => 'color: {{VALUE}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-heading-text.tc-heading-container-active' => 'color: {{VALUE}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-heading-text.tc-heading-container-active > *' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'hover_title_color_dark_mode',
            [
                'label' => esc_html__( 'Text Color (Dark Mode)', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-heading-text:hover .tcgelements-heading' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-heading-text:hover .tcgelements-heading' => 'color: {{VALUE}};',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-heading-text.tc-heading-container-active > *' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-heading-text.tc-heading-container-active > *' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'text_stroke_hover',
                'selector' => '{{WRAPPER}} .tcgelements-heading-text:hover .tcgelements-heading,.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-heading-text.tc-heading-container-active > *',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'text_shadow_hover',
                'selector' => '{{WRAPPER}} .tcgelements-heading-text:hover .tcgelements-heading,.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-heading-text.tc-heading-container-active > *',
            ]
        );
        $this->add_control(
            'heading_opacity_hover',
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
                    '{{WRAPPER}} .tcgelements-heading-text:hover .tcgelements-heading' => 'opacity: {{SIZE}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-heading-text.tc-heading-container-active > *' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'title_background_color_hover',
                'label' => esc_html__('Text Background Hover', 'element-camp'),
                'types' => [ 'classic','gradient','tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-heading-text:hover .tcgelements-heading,{{WRAPPER}} .tcgelements-heading-text:hover svg,.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-heading-text.tc-heading-container-active > *',
            ]
        );
        $this->add_responsive_control(
            'translate_heading_y_hover',
            [
                'label' => esc_html__( 'Heading Wrapper Translate Y', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
            ]
        );
        $this->add_responsive_control(
            'rotate_heading_hover',
            [
                'label' => esc_html__( 'Rotate', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'deg','custom'],
                'default' => [
                    'size' => 0,
                    'unit' => 'deg',
                ],
                'tablet_default' => [
                    'unit' => 'deg',
                ],
                'mobile_default' => [
                    'unit' => 'deg',
                ],
            ]
        );
        $this->add_control(
            'scale_heading_hover',
            [
                'label' => esc_html__('Heading Wrapper Scale', 'themescamp-plugin'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
            ]
        );
        $this->add_responsive_control(
            'translate_heading_x_hover',
            [
                'label' => esc_html__( 'Heading Wrapper Translate X', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text:hover' => 'transform: rotate({{rotate_heading_hover.SIZE}}{{rotate_heading_hover.UNIT}}) translate({{translate_heading_x_hover.SIZE}}{{translate_heading_x_hover.UNIT}},{{translate_heading_y_hover.SIZE}}{{translate_heading_y_hover.UNIT}}) scale({{scale_heading_hover.SIZE || 1}})',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-heading-text.tc-heading-container-active' => 'transform: rotate({{rotate_heading_hover.SIZE}}{{rotate_heading_hover.UNIT}}) translate({{translate_heading_x_hover.SIZE}}{{translate_heading_x_hover.UNIT}},{{translate_heading_y_hover.SIZE}}{{translate_heading_y_hover.UNIT}}) scale({{scale_heading_hover.SIZE || 1}})',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control(
            'separator_border2',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_control(
            'text_breakline',
            [
                'label' => esc_html__('Make Break Line Tag Hidden', 'element-camp'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'default' => esc_html__('yes', 'element-camp'),
            ]
        );

        $this->add_control(
            'blend_mode',
            [
                'label' => esc_html__( 'Blend Mode', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__( 'Normal', 'element-camp' ),
                    'multiply' => esc_html__( 'Multiply', 'element-camp' ),
                    'screen' => esc_html__( 'Screen', 'element-camp' ),
                    'overlay' => esc_html__( 'Overlay', 'element-camp' ),
                    'darken' => esc_html__( 'Darken', 'element-camp' ),
                    'lighten' => esc_html__( 'Lighten', 'element-camp' ),
                    'color-dodge' => esc_html__( 'Color Dodge', 'element-camp' ),
                    'saturation' => esc_html__( 'Saturation', 'element-camp' ),
                    'color' => esc_html__( 'Color', 'element-camp' ),
                    'difference' => esc_html__( 'Difference', 'element-camp' ),
                    'exclusion' => esc_html__( 'Exclusion', 'element-camp' ),
                    'hue' => esc_html__( 'Hue', 'element-camp' ),
                    'luminosity' => esc_html__( 'Luminosity', 'element-camp' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading' => 'mix-blend-mode: {{VALUE}}',
                ],
                'separator' => 'none',
            ]
        );

        $this->add_control(
            'additional_style_options',
            [
                'label' => esc_html__( 'Additional Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'before'
            ]
        );

        $this->add_responsive_control(
            'heading_display',
            [
                'label' => esc_html__('Heading Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'block',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'inline-flex' => esc_html__('Inline Flex', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                    'inline' => esc_html__('Inline', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'heading_justify_content',
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
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading' => 'justify-content: {{VALUE}};',
                ],
                'condition'=>['heading_display'=> ['flex','inline-flex']],
                'responsive' => true,
            ]);
        $this->add_responsive_control(
            'heading_align_items',
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
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading' => 'align-items: {{VALUE}};',
                ],
                'condition'=>['heading_display'=> ['flex','inline-flex']],
                'responsive' => true,
            ]);

        $this->add_responsive_control(
            'heading_padding',
            [
                'label' => esc_html__('Text Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_responsive_control(
            'heading_border_radius',
            [
                'label' => esc_html__('Text Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'heading_height',
            [
                'label' => esc_html__( 'Heading Height', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'heading_min_height',
            [
                'label' => esc_html__( 'Heading Min Height', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'heading_width',
            [
                'label' => esc_html__( 'Heading Width', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'heading_overflow',
            [
                'label' => esc_html__( 'Heading Overflow Hidden', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'yes', 'element-camp' ),
                'label_off' => esc_html__( 'No', 'element-camp' ),
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading' => 'overflow: hidden;',
                ],
            ]
        );

        $this->add_control(
            'heading_transform_heading',
            [
                'label' => esc_html__( 'Heading Transform', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'before'
            ]
        );

        $this->start_controls_tabs( 'heading_transform_tabs' );

        $this->start_controls_tab( 'heading_transform_normal',
            [
                'label' => esc_html__( 'Normal', 'element-camp' ),
            ]
        );

        $this->add_responsive_control(
            'translate_heading_element_y',
            [
                'label' => esc_html__( 'Heading Translate Y', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading' => '--e-transform-tcgelements-heading-translateY: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'scale_heading_element',
            [
                'label' => esc_html__('Heading Scale', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 3,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'default' => [
                    'size' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading' => '--e-transform-tcgelements-heading-scale: {{SIZE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'translate_heading_element_x',
            [
                'label' => esc_html__( 'Heading Translate X', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading' => '--e-transform-tcgelements-heading-translateX: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'heading_transform_hover',
            [
                'label' => esc_html__( 'Hover', 'element-camp' ),
            ]
        );

        $this->add_responsive_control(
            'translate_heading_element_y_hover',
            [
                'label' => esc_html__( 'Heading Translate Y', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text:hover .tcgelements-heading' => '--e-transform-tcgelements-heading-translateY: {{SIZE}}{{UNIT}}',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-heading-text.tc-heading-container-active .tcgelements-heading' => '--e-transform-tcgelements-heading-translateY: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'scale_heading_element_hover',
            [
                'label' => esc_html__('Heading Scale', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 3,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text:hover .tcgelements-heading' => '--e-transform-tcgelements-heading-scale: {{SIZE}}',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-heading-text.tc-heading-container-active .tcgelements-heading' => '--e-transform-tcgelements-heading-scale: {{SIZE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'translate_heading_element_x_hover',
            [
                'label' => esc_html__( 'Heading Translate X', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text:hover .tcgelements-heading' => '--e-transform-tcgelements-heading-translateX: {{SIZE}}{{UNIT}}',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-heading-text.tc-heading-container-active .tcgelements-heading' => '--e-transform-tcgelements-heading-translateX: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_responsive_control(
            'transform_origin_heading_element',
            [
                'label' => esc_html__('Heading Transform Origin', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'center center' => esc_html__('Center Center', 'element-camp'),
                    'center left' => esc_html__('Center Left', 'element-camp'),
                    'center right' => esc_html__('Center Right', 'element-camp'),
                    'top center' => esc_html__('Top Center', 'element-camp'),
                    'top left' => esc_html__('Top Left', 'element-camp'),
                    'top right' => esc_html__('Top Right', 'element-camp'),
                    'bottom center' => esc_html__('Bottom Center', 'element-camp'),
                    'bottom left' => esc_html__('Bottom Left', 'element-camp'),
                    'bottom right' => esc_html__('Bottom Right', 'element-camp'),
                    'custom' => esc_html__('Custom', 'element-camp'),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading' => 'transform-origin: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'transform_origin_heading_element_y',
            [
                'label' => esc_html__( 'Transform Origin Y', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'condition' => ['transform_origin_heading_element' => 'custom']
            ]
        );
        $this->add_responsive_control(
            'transform_origin_heading_element_x',
            [
                'label' => esc_html__( 'Transform Origin X', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading' => 'transform-origin: {{transform_origin_heading_element_x.SIZE}}{{transform_origin_heading_element_x.UNIT}} {{transform_origin_heading_element_y.SIZE}}{{transform_origin_heading_element_y.UNIT}}',
                ],
                'condition' => ['transform_origin_heading_element' => 'custom']
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'section_styled_text',
            [
                'label' => esc_html__( 'Span Style', 'element-camp' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'span_align',
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
                    '{{WRAPPER}} .tcgelements-heading span' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'styled_display',
            [
                'label' => esc_html__('Span Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'inline-block',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'inline' => esc_html__('Inline', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading span' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'span_float',
            [
                'label' => esc_html__('Float', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('None', 'element-camp'),
                    'left' => esc_html__('Left', 'element-camp'),
                    'right' => esc_html__('Right', 'element-camp'),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading span' => 'float: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'styled_color',
            [
                'label' => esc_html__( 'Text Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading span' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'styled_text_stroke',
                'selector' => '{{WRAPPER}} .tcgelements-heading span',
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'styled_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-heading span',
            ]
        );
        $this->add_control(
            'span_background_clip_text',
            [
                'label' => esc_html__('Background Clip Text', 'element-camp'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'description' => esc_html__( 'This will apply text-fill-color transparent to the text, creating a background-clip effect.', 'element-camp' ),
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading span' => 'background-clip: text;-webkit-text-fill-color: transparent;',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'span_background_color',
                'types' => [ 'classic','gradient','tcg_gradient', 'tcg_gradient_4' ],
                'selector' => '{{WRAPPER}} .tcgelements-heading span',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'styled_border',
                'selector' => '{{WRAPPER}} .tcgelements-heading span',
            ]
        );
        $this->add_control(
            'styled_opacity',
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
                    '{{WRAPPER}} .tcgelements-heading span' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'styled_margin',
            [
                'label' => esc_html__('Span Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'styled_padding',
            [
                'label' => esc_html__('Span Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'span_border_radius',
            [
                'label' => esc_html__('Span Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'span_transition',
            [
                'label' => esc_html__( 'Span Transition', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading span' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );
        $this->add_control(
            'span_dark_mode_style_heading',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control(
            'span_color_dark_mode',
            [
                'label' => esc_html__( 'Text Color (Dark Mode)', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-heading span' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-heading span' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->start_controls_tabs(
            'span_tabs',
        );
        
        $this->start_controls_tab(
            'span_normal_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );

        $this->add_control(
            'position_style_heading',
            [
                'label' => esc_html__( 'Position Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'before'
            ]
        );

        $this->add_control(
            'position_for_span',
            [
                'label' => esc_html__( 'Span Position', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'absolute' => esc_html__( 'Absolute', 'element-camp' ),
                    'relative' => esc_html__( 'Relative', 'element-camp' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading span' => 'position: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'span_offset_orientation_h',
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
                    'position_for_span!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'span_offset_x',
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
                    '{{WRAPPER}} .tcgelements-heading span' => 'left: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'span_offset_orientation_h' => 'start',
                    'position_for_span!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'span_offset_x_end',
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
                    '{{WRAPPER}} .tcgelements-heading span' => 'right: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'span_offset_orientation_h' => 'end',
                    'position_for_span!' => '',
                ],
            ]
        );
        $this->add_control(
            'span_offset_orientation_v',
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
                    'position_for_span!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'span_offset_y',
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
                    '{{WRAPPER}} .tcgelements-heading span' => 'top: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'span_offset_orientation_v' => 'start',
                    'position_for_span!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'span_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-heading span' => 'bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'span_offset_orientation_v' => 'end',
                    'position_for_span!' => '',
                ],
            ]
        );

        $this->add_control(
            'heading_span_transform_options',
            [
                'label' => esc_html__('Span Transform', 'themescamp-plugin'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'themescamp-plugin'),
                'label_on' => esc_html__('Custom', 'themescamp-plugin'),
            ]
        );
        $this->start_popover();
        $this->add_control(
            'heading_span_rotate',
            [
                'label' => esc_html__('Rotate', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['deg'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading span' => '--e-transform-tcgelements-heading-span-rotateZ: {{SIZE}}deg',
                ],
            ]
        );
        $this->end_popover();

        $this->end_controls_tab();

        $this->start_controls_tab(
            'span_hover_tab',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
            ]
        );
        $this->add_control(
            'position_style_heading_span_hover',
            [
                'label' => esc_html__( 'Position Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'before'
            ]
        );

        $this->add_control(
            'position_for_span_hover',
            [
                'label' => esc_html__( 'Span Position', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'absolute' => esc_html__( 'Absolute', 'element-camp' ),
                    'relative' => esc_html__( 'Relative', 'element-camp' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading:hover span' => 'position: {{VALUE}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-heading-text.tc-heading-container-active span' => 'position: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'span_hover_offset_orientation_h',
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
                    'position_for_span_hover!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'span_hover_offset_x',
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
                    '{{WRAPPER}} .tcgelements-heading:hover span' => 'left: {{SIZE}}{{UNIT}}',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-heading-text.tc-heading-container-active span' => 'left: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'span_hover_offset_orientation_h' => 'start',
                    'position_for_span_hover!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'span_hover_offset_x_end',
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
                    '{{WRAPPER}} .tcgelements-heading:hover span' => 'right: {{SIZE}}{{UNIT}}',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-heading-text.tc-heading-container-active span' => 'right: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'span_hover_offset_orientation_h' => 'end',
                    'position_for_span_hover!' => '',
                ],
            ]
        );
        $this->add_control(
            'span_hover_offset_orientation_v',
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
                    'position_for_span_hover!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'span_hover_offset_y',
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
                    '{{WRAPPER}} .tcgelements-heading:hover span' => 'top: {{SIZE}}{{UNIT}}',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-heading-text.tc-heading-container-active span' => 'top: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'span_hover_offset_orientation_v' => 'start',
                    'position_for_span_hover!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'span_hover_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-heading:hover span' => 'bottom: {{SIZE}}{{UNIT}}',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-heading-text.tc-heading-container-active span' => 'bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'span_hover_offset_orientation_v' => 'end',
                    'position_for_span_hover!' => '',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        $this->start_controls_section(
            'section_small_text',
            [
                'label' => esc_html__( 'Small Style', 'element-camp' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'small_display',
            [
                'label' => esc_html__('Small Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'inline-block',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'inline' => esc_html__('Inline', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading small' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_control(
            'small_color',
            [
                'label' => esc_html__( 'Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading small' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'small_text_stroke',
                'selector' => '{{WRAPPER}} .tcgelements-heading small',
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'small_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-heading small',
            ]
        );
        $this->add_responsive_control(
            'small_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em' ,'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading small' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'small_padding',
            [
                'label' => esc_html__('Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em' ,'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading small' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'small_opacity',
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
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading small' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'small_background_clip_text',
            [
                'label' => esc_html__('Background Clip Text', 'element-camp'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'description' => esc_html__( 'This will apply text-fill-color transparent to the text, creating a background-clip effect.', 'element-camp' ),
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading small' => 'background-clip: text;',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'small_background_color',
                'types' => [ 'classic','gradient','tcg_gradient', 'tcg_gradient_4' ],
                'selector' => '{{WRAPPER}} .tcgelements-heading small',
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_icon_style',
            [
                'label' => esc_html__( 'Icon', 'element-camp' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'after_icon' => 'yes'
                ]
            ]
        );
        $this->add_responsive_control(
            'icon_display',
            [
                'label' => esc_html__('Icon Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'block',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'inline' => esc_html__('Inline', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                    'inline-flex' => esc_html__('Inline Flex', 'element-camp'),
                    'none' => esc_html__('None', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text .heading-icon' => 'display: {{VALUE}};',
                ]
            ]
        );
        $this->add_responsive_control(
            'heading_icon_justify_content',
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
                    '{{WRAPPER}} .tcgelements-heading-text .heading-icon' => 'justify-content: {{VALUE}};',
                ],
                'condition'=>['icon_display'=> ['flex','inline-flex']],
                'responsive' => true,
            ]);
        $this->add_responsive_control(
            'heading_icon_align_items',
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
                    '{{WRAPPER}} .tcgelements-heading-text .heading-icon' => 'align-items: {{VALUE}};',
                ],
                'condition'=>['icon_display'=> ['flex','inline-flex']],
                'responsive' => true,
            ]
        );

        $this->add_control(
            'position_for_icon',
            [
                'label' => esc_html__( 'Position', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'absolute' => esc_html__( 'Absolute', 'element-camp' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text .heading-icon' => 'position: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'icon_offset_orientation_h',
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
                    'position_for_icon!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_offset_x',
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
                    '{{WRAPPER}} .tcgelements-heading-text .heading-icon' => 'left: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'icon_offset_orientation_h' => 'start',
                    'position_for_icon!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_offset_x_end',
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
                    '{{WRAPPER}} .tcgelements-heading-text .heading-icon' => 'right: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'icon_offset_orientation_h' => 'end',
                    'position_for_icon!' => '',
                ],
            ]
        );
        $this->add_control(
            'icon_offset_orientation_v',
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
                    'position_for_icon!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_offset_y',
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
                    '{{WRAPPER}} .tcgelements-heading-text .heading-icon' => 'top: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'icon_offset_orientation_v' => 'start',
                    'position_for_icon!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-heading-text .heading-icon' => 'bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'icon_offset_orientation_v' => 'end',
                    'position_for_icon!' => '',
                ],
            ]
        );
        $this->add_control(
            'heading_icon_transform_options',
            [
                'label' => esc_html__('Icon Transform Options', 'element-camp'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'element-camp'),
                'label_on' => esc_html__('Custom', 'element-camp'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );
        $this->start_popover();
        $this->add_control(
            'heading_icon_translate_x',
            [
                'label' => esc_html__( 'Icon Translate X', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text .heading-icon' => '--e-transform-tcgelements-heading-icon-translateX: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_control(
            'heading_icon_translate_y',
            [
                'label' => esc_html__( 'Icon Translate Y', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text .heading-icon' => '--e-transform-tcgelements-heading-icon-translateY: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->end_popover();
        $this->add_responsive_control(
            'icon_size_options',
            [
                'label' => esc_html__( 'Icon Size', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-heading-text .heading-icon' => '{{VALUE}};',
                ],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'icon_flex_grow',
            [
                'label' => esc_html__( 'Flex Grow', 'element-camp' ),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text .heading-icon' => 'flex-grow: {{VALUE}};',
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
                'label' => esc_html__( 'Flex Shrink', 'element-camp' ),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text .heading-icon' => 'flex-shrink: {{VALUE}};',
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
            'icon_indent',
            [
                'label' => esc_html__('Icon Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em' ,'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text .heading-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_padding',
            [
                'label' => esc_html__('Icon Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em' ,'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text .heading-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text .heading-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );
        $this->add_responsive_control(
            'heading_icon_wrapper_width',
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
                    '{{WRAPPER}} .tcgelements-heading-text .heading-icon' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'heading_icon_wrapper_height',
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
                    '{{WRAPPER}} .tcgelements-heading-text .heading-icon' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__('Icon Size', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text .heading-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-heading-text .heading-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'icon_transition',
            [
                'label' => esc_html__( 'Icon Transition', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text .heading-icon' => 'transition: all {{SIZE}}s ease;',
                    '{{WRAPPER}} .tcgelements-heading-text .heading-icon i' => 'transition: all {{SIZE}}s ease;',
                    '{{WRAPPER}} .tcgelements-heading-text .heading-icon svg' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );
        $this->start_controls_tabs(
            'icon_heading_tabs',
        );
        
        $this->start_controls_tab(
            'normal_icon_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text .heading-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-heading-text .heading-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'svg_path_stroke_color',
            [
                'label' => esc_html__( 'SVG Path Stroke Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text .heading-icon svg path' => 'stroke: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'icon_background_color',
                'label' => esc_html__('Icon Background', 'element-camp'),
                'types' => [ 'classic','gradient' ],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .tcgelements-heading-text .heading-icon',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'icon_border',
                'selector' => '{{WRAPPER}} .tcgelements-heading-text .heading-icon',
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'icon_opacity',
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
                    '{{WRAPPER}} .tcgelements-heading-text .heading-icon svg' => 'opacity: {{SIZE}};',
                    '{{WRAPPER}} .tcgelements-heading-text .heading-icon i' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'hover_icon_tab',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
            ]
        );
        $this->add_control(
            'icon_color_hover',
            [
                'label' => esc_html__( 'Icon Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text:hover .heading-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-heading-text:hover .heading-icon svg' => 'fill: {{VALUE}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-heading-text.tc-heading-container-active .heading-icon i' => 'color: {{VALUE}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-heading-text.tc-heading-container-active .heading-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'svg_path_stroke_color_hover',
            [
                'label' => esc_html__( 'SVG Path Stroke Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text:hover .heading-icon svg path' => 'stroke: {{VALUE}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-heading-text.tc-heading-container-active .heading-icon svg path' => 'stroke: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'icon_background_color_hover',
                'label' => esc_html__('Icon Background', 'element-camp'),
                'types' => [ 'classic','gradient' ],
                'exclude' => ['image'],
                'selector' =>
                    '{{WRAPPER}} .tcgelements-heading-text:hover .heading-icon
                    .e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-heading-text.tc-heading-container-active .heading-icon',
            ]
        );
        $this->add_control(
            'icon_opacity_hover',
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
                    '{{WRAPPER}} .tcgelements-heading-text:hover .heading-icon svg' => 'opacity: {{SIZE}};',
                    '{{WRAPPER}} .tcgelements-heading-text:hover .heading-icon i' => 'opacity: {{SIZE}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-heading-text.tc-heading-container-active .heading-icon svg' => 'opacity: {{SIZE}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-heading-text.tc-heading-container-active .heading-icon i' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        $this->end_controls_section();
        $this->start_controls_section(
            'section_image_style',
            [
                'label' => esc_html__( 'Image', 'element-camp' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'after_image' => 'yes'
                ]
            ]
        );
        $this->add_responsive_control(
            'heading_image_display',
            [
                'label' => esc_html__('Image Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'inline-block',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                    'inline-flex' => esc_html__('Inline Flex', 'element-camp'),
                    'inline' => esc_html__('Inline', 'element-camp'),
                    'none' => esc_html__('None', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text img' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'image_max_width',
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
                    '{{WRAPPER}} .tcgelements-heading-text img' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_max_height',
            [
                'label' => esc_html__( 'Max Height', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-heading-text img' => 'max-height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-heading-text img' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-heading-text img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'img_object_fit',
            [
                'label' => esc_html__('Object Fit', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'fill' => esc_html__('Fill', 'element-camp'),
                    'cover' => esc_html__('Cover', 'element-camp'),
                    'contain' => esc_html__('Contain', 'element-camp'),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'img_object_position',
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
                    '{{WRAPPER}} .tcgelements-heading-text img' => 'object-position: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-heading-text img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'image_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em' , 'rem' , 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'position_for_image',
            [
                'label' => esc_html__( 'Position', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'absolute' => esc_html__( 'Absolute', 'element-camp' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text img' => 'position: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'image_offset_orientation_h',
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
                    'position_for_image!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_offset_x',
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
                    '{{WRAPPER}} .tcgelements-heading-text img' => 'left: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'image_offset_orientation_h' => 'start',
                    'position_for_image!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_offset_x_end',
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
                    '{{WRAPPER}} .tcgelements-heading-text img' => 'right: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'image_offset_orientation_h' => 'end',
                    'position_for_image!' => '',
                ],
            ]
        );
        $this->add_control(
            'image_offset_orientation_v',
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
                    'position_for_image!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_offset_y',
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
                    '{{WRAPPER}} .tcgelements-heading-text img' => 'top: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'image_offset_orientation_v' => 'start',
                    'position_for_image!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-heading-text img' => 'bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'image_offset_orientation_v' => 'end',
                    'position_for_image!' => '',
                ],
            ]
        );
        $this->add_control(
            'translate_image_y',
            [
                'label' => esc_html__( 'Translate Y', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
            ]
        );
        $this->add_control(
            'translate_image_x',
            [
                'label' => esc_html__( 'Translate X', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text img' => 'transform: translate({{translate_image_x.SIZE}}{{translate_image_x.UNIT}},{{translate_image_y.SIZE}}{{translate_image_y.UNIT}})',
                ],
            ]
        );
        $this->add_control(
            'image_index',
            [
                'label' => esc_html__( 'Image z-index', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text img' => 'z-index: {{SIZE}};',
                ],
            ]
        );
        $this->start_controls_tabs(
            'image_style_tabs',
        );
        
        $this->start_controls_tab(
            'image_normal_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_control(
            'image_visibility',
            [
                'label' => esc_html__('Visibility', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'visible',
                'options' => [
                    'visible' => esc_html__('Visible', 'element-camp'),
                    'hidden' => esc_html__('Hidden', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text img' => 'visibility: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_opacity',
            [
                'label' => esc_html__( 'Opacity', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                    'size' => '1'
                ],
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'image_hover_tab',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-heading-text img' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_width_hover',
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
                    '{{WRAPPER}} .tcgelements-heading-text:hover img' => 'width: {{SIZE}}{{UNIT}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-heading-text.tc-heading-container-active img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'image_visibility_hover',
            [
                'label' => esc_html__('Visibility', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'visible',
                'options' => [
                    'visible' => esc_html__('Visible', 'element-camp'),
                    'hidden' => esc_html__('Hidden', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text:hover img' => 'visibility: {{VALUE}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-heading-text.tc-heading-container-active img' => 'visibility: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_opacity_hover',
            [
                'label' => esc_html__( 'Opacity', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                    'size' => '1'
                ],
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text:hover img' => 'opacity: {{SIZE}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-heading-text.tc-heading-container-active img' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        $this->end_controls_section();

        $this->start_controls_section(
            'section_before_background_style',
            [
                'label' => esc_html__( 'Before Heading', 'element-camp' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'heading_before_display',
            [
                'label' => esc_html__('Before Display', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'block',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'none' => esc_html__('none', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading:before' => 'display: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'before_background_color',
                'label' => esc_html__('Before Background', 'element-camp'),
                'types' => [ 'classic','gradient','tcg_gradient', 'tcg_gradient_4' ],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading:before',
            ]
        );
        $this->add_control(
            'position_for_overlay',
            [
                'label' => esc_html__( 'Overlay Position', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'absolute',
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'absolute' => esc_html__( 'Absolute', 'element-camp' ),
                    'relative' => esc_html__( 'Relative', 'element-camp' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading:before' => 'position: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'before_offset_orientation_h',
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
            ]
        );
        $this->add_responsive_control(
            'before_offset_x',
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
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading:before' => 'left: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'before_offset_orientation_h' => 'start',
                ],
            ]
        );
        $this->add_responsive_control(
            'before_offset_x_end',
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
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading:before' => 'right: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'before_offset_orientation_h' => 'end',
                ],
            ]
        );
        $this->add_control(
            'before_offset_orientation_v',
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
            ]
        );
        $this->add_responsive_control(
            'before_offset_y',
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
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading:before' => 'top: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'before_offset_orientation_v' => 'start',
                ],
            ]
        );
        $this->add_responsive_control(
            'before_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading:before' => 'bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'before_offset_orientation_v' => 'end',
                ],
            ]
        );
        $this->add_responsive_control(
            'before_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading:before' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );
        $this->add_responsive_control(
            'before_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after'
            ]
        );
        $this->add_control(
            'before_heading_opacity',
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
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading:before' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'before_heading_box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading:before',
            ]
        );
        $this->add_responsive_control(
            'rotate_before',
            [
                'label' => esc_html__( 'Rotate', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'deg','custom'],
                'default' => [
                    'size' => 0,
                    'unit' => 'deg',
                ],
                'tablet_default' => [
                    'unit' => 'deg',
                ],
                'mobile_default' => [
                    'unit' => 'deg',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading:before' => 'transform: rotate({{SIZE}}{{UNIT}});',
                ],
            ]
        );
        $this->add_control(
            'before_heading_transition',
            [
                'label' => esc_html__('Transition', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading:before' => 'transition: all {{SIZE}}s ease;',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'before_heading_index',
            [
                'label' => esc_html__( 'z-index', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading:before' => 'z-index: {{SIZE}};',
                ],
            ]
        );

        $this->add_control(
            'before_heading_clip_path_popover_toggle',
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
            'before_heading_clip_path',
            [
                'label' => esc_html__('TCG Clip Path', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'element-camp'),
                    'circle' => esc_html__('Circle', 'element-camp'),
                    'ellipse' => esc_html__('Ellipse', 'element-camp'),
                    'inset' => esc_html__('Inset', 'element-camp'),
                    'polygon' => esc_html__('Polygon', 'element-camp'),
                ],
                'default' => 'none',
                'render_type' => 'ui',
                'frontend_available' => true,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading:before' => 'clip-path: {{VALUE}}({{before_heading_clip_path_value.VALUE}});',
                ],
            ]
        );

        $this->add_control(
            'before_heading_clip_path_value',
            [
                'label' => esc_html__('TCG Clip Path Value', 'element-camp'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '20% 0, 100% 15%, 100% 100%, 0 100%, 0 15%',
                'render_type' => 'ui',
                'frontend_available' => true,
                'condition' => [
                    'before_heading_clip_path' => ['circle', 'ellipse', 'inset', 'polygon'],
                ],
            ]
        );

        $this->end_popover();

        $this->start_controls_tabs(
            'before_heading_tabs',
        );

        $this->start_controls_tab(
            'before_heading_normal_tab',
            [
                'label'   => esc_html__('Normal', 'element-camp'),
            ]
        );

        $this->add_responsive_control(
            'before_width',
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
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading:before' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'before_height',
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
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading:before' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab(
            'before_heading_hover_tab',
            [
                'label'   => esc_html__('Hover', 'element-camp'),
            ]
        );
        $this->add_responsive_control(
            'before_width_hover',
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
                    '{{WRAPPER}} .tcgelements-heading-text:hover .tcgelements-heading:before' => 'width: {{SIZE}}{{UNIT}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-heading-text.tc-heading-container-active .tcgelements-heading:before' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'before_height_hover',
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
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading:before' => 'height: {{SIZE}}{{UNIT}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-heading-text.tc-heading-container-active .tcgelements-heading:before' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'before_heading_dark_mode_style',
            [
                'label' => esc_html__( 'Dark Mode', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'before_heading_background_dark_mode',
                'selector' => '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading:before',
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
            'section_wrapper_before_style',
            [
                'label' => esc_html__( 'Wrapper Before Style', 'element-camp' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'wrapper_before_display',
            [
                'label' => esc_html__('Wrapper Display', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'block',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                    'inline-flex' => esc_html__('Inline Flex', 'element-camp'),
                    'none' => esc_html__('None', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text:before' => 'display: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'wrapper_before_background_color',
                'label' => esc_html__('Wrapper Background', 'element-camp'),
                'types' => [ 'classic','gradient','tcg_gradient', 'tcg_gradient_4' ],
                'selector' => '{{WRAPPER}} .tcgelements-heading-text:before',
            ]
        );
        $this->add_control(
            'wrapper_before_position',
            [
                'label' => esc_html__( 'Wrapper Position', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'absolute',
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'absolute' => esc_html__( 'Absolute', 'element-camp' ),
                    'relative' => esc_html__( 'Relative', 'element-camp' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text:before' => 'position: {{VALUE}};content:"";',
                ],
            ]
        );

        $this->add_control(
            'wrapper_before_orientation_h',
            [
                'label' => esc_html__('Horizontal Orientation', 'themescamp-plugin'),
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
            'wrapper_before_x',
            [
                'label' => esc_html__('Offset', 'themescamp-plugin'),
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-heading-text:before' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-heading-text:before' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'wrapper_before_orientation_h!' => 'end',
                ],
            ]
        );
        $this->add_responsive_control(
            'wrapper_before_x_end',
            [
                'label' => esc_html__('Offset', 'themescamp-plugin'),
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-heading-text:before' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-heading-text:before' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'wrapper_before_orientation_h' => 'end',
                ],
            ]
        );
        $this->add_control(
            'wrapper_before_orientation_v',
            [
                'label' => esc_html__('Vertical Orientation', 'themescamp-plugin'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => esc_html__('Top', 'themescamp-plugin'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'end' => [
                        'title' => esc_html__('Bottom', 'themescamp-plugin'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'render_type' => 'ui',
            ]
        );
        $this->add_responsive_control(
            'wrapper_before_y',
            [
                'label' => esc_html__('Offset', 'themescamp-plugin'),
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
                    '{{WRAPPER}} .tcgelements-heading-text:before' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'wrapper_before_orientation_v!' => 'end',
                ],
            ]
        );
        $this->add_responsive_control(
            'wrapper_before_y_end',
            [
                'label' => esc_html__('Offset', 'themescamp-plugin'),
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
                    '{{WRAPPER}} .tcgelements-heading-text:before' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'wrapper_before_orientation_v' => 'end',
                ],
            ]
        );
        $this->add_control(
            'wrapper_before_transition',
            [
                'label' => esc_html__('Transition', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text:before' => 'transition: all {{SIZE}}s ease;',
                ],
                'separator' => 'before',
            ]
        );
        $this->start_controls_tabs(
            'wrapper_before_tabs',
        );

        $this->start_controls_tab(
            'wrapper_before_normal_tab',
            [
                'label'   => esc_html__('Normal', 'element-camp'),
            ]
        );

        $this->add_responsive_control(
            'wrapper_before_width',
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
                    '{{WRAPPER}} .tcgelements-heading-text:before' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'wrapper_before_height',
            [
                'label' => esc_html__( 'Height', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'auto',
                ],
                'tablet_default' => [
                    'unit' => 'auto',
                ],
                'mobile_default' => [
                    'unit' => 'auto',
                ],
                'size_units' => [ 'px', 'vh', '%', 'auto', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
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
                    '{{WRAPPER}} .tcgelements-heading-text:before' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab(
            'wrapper_before_hover_tab',
            [
                'label'   => esc_html__('Hover', 'element-camp'),
            ]
        );
        $this->add_responsive_control(
            'wrapper_before_width_hover',
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
                    '{{WRAPPER}} .tcgelements-heading-text:hover:before' => 'width: {{SIZE}}{{UNIT}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-heading-text.tc-heading-container-active:before' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'wrapper_before_height_hover',
            [
                'label' => esc_html__( 'Height', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'auto',
                ],
                'tablet_default' => [
                    'unit' => 'auto',
                ],
                'mobile_default' => [
                    'unit' => 'auto',
                ],
                'size_units' => [ 'px', 'vh', '%', 'auto', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
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
                    '{{WRAPPER}} .tcgelements-heading-text:hover:before' => 'height: {{SIZE}}{{UNIT}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-heading-text.tc-heading-container-active:before' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
        $this->start_controls_section(
            'section_heading_animations',
            [
                'label' => esc_html__( 'Animations', 'element-camp' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'heading_animations',
            [
                'label' => esc_html__('Animations', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'scroll-parallax' => esc_html__('Scroll Parallax', 'element-camp'),
                    'tce-split-txt' => esc_html__('Split Text', 'element-camp'),
                    'tce-scroll-end' => esc_html__('Scroll Trigger', 'element-camp'),
                    'tce-scroll-fill' => esc_html__('Scroll Fill', 'element-camp'),
                    'tce-letters-line' => esc_html__('Letters Line', 'element-camp'),
                    'tce-fade-text' => esc_html__('Fade Text', 'element-camp'),
                    'tce-funky-letters' => esc_html__('Funky Letters', 'element-camp'),
                ],
            ]
        );
        $this->add_control(
            'fade_trigger_auto',
            [
                'label' => esc_html__('Auto Trigger on Load', 'element-camp'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'default' => 'yes',
                'condition' => [
                    'heading_animations' => 'tce-fade-text',
                ],
            ]
        );
        $this->add_control(
            'scroll_fill_active_color',
            [
                'label' => esc_html__('Active Text Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'condition' => [
                    'heading_animations' => 'tce-scroll-fill',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading.tce-scroll-fill div' => '--fill-color-active: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'scroll_fill_inactive_color',
            [
                'label' => esc_html__('Inactive Text Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'heading_animations' => 'tce-scroll-fill',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading.tce-scroll-fill div' => '--fill-color-inactive: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'parallax_speed',
            [
                'label' => esc_html__( 'Speed', 'element-camp' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 1,
                'min' => 0,
                'step' => 0.1,
                'condition' => [
                    'heading_animations' => 'scroll-parallax',
                ],
            ]
        );
        $this->add_control(
            'parallax_lag',
            [
                'label' => esc_html__( 'Lag', 'element-camp' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.7,
                'min' => 0,
                'step' => 0.1,
                'condition' => [
                    'heading_animations' => 'scroll-parallax',
                ],
            ]
        );
        $this->add_control(
            'split_duration',
            [
                'label' => esc_html__( 'Split Text Animation Duration', 'element-camp' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 2,
                'min' => -99,
                'step' => 1,
                'condition' => [
                    'heading_animations' => 'tce-split-txt',
                ],
            ]
        );
        $this->add_control(
            'split_trigger',
            [
                'label' => esc_html__( 'Split Trigger : ', 'element-camp' ),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'heading_animations' => 'tce-split-txt',
                ],
            ]
        );
        $this->add_control(
            'split_trigger_important_note',
            [
                'type' => \Elementor\Controls_Manager::ALERT,
                'alert_type' => 'warning',
                'heading' => esc_html__('Effect Important Note', 'element-camp'),
                'content' => esc_html__('Default Trigger is the Element. If you want to change it, type the class of the element you want to trigger. It must exist.', 'element-camp'),
                'condition' => [
                    'heading_animations' => 'tce-split-txt',
                    'split_trigger!' => '',
                ],
            ]
        );
        $this->add_control(
            'split_start',
            [
                'label' => esc_html__( 'Split Start : ', 'element-camp' ),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'heading_animations' => 'tce-split-txt',
                ],
            ]
        );
        $this->add_control(
            'split_end',
            [
                'label' => esc_html__( 'Split End : ', 'element-camp' ),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'heading_animations' => 'tce-split-txt',
                ],
            ]
        );
        $this->add_control(
            'split_color',
            [
                'label' => esc_html__( 'Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'condition' => [
                    'heading_animations' => 'tce-split-txt',
                ],
            ]
        );
        $this->add_control(
            'split_color_dark_mode',
            [
                'label' => esc_html__( 'Split Text Color (Dark Mode)', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'heading_animations' => 'tce-split-txt',
                ],
            ]
        );
        $this->add_control(
            'split_drop_shadow_popover_toggle',
            [
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label' => esc_html__( 'Drop Shadow', 'element-camp' ),
                'label_off' => esc_html__( 'Default', 'element-camp' ),
                'label_on' => esc_html__( 'Custom', 'element-camp' ),
                'return_value' => 'yes',
                'condition' => [
                    'heading_animations' => 'tce-split-txt',
                ],
            ]
        );
        $this->start_popover();

        $this->add_control(
            'split_drop_shadow_offset_x',
            [
                'label' => esc_html__('Offset x', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 200,
                        'min' => 0,
                        'step' => 1,
                    ],
                ],
                'render_type' => 'ui',
                'condition' => [
                    'heading_animations' => 'tce-split-txt',
                ],
            ]
        );
        $this->add_control(
            'split_drop_shadow_offset_y',
            [
                'label' => esc_html__('Offset y', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 200,
                        'min' => 0,
                        'step' => 1,
                    ],
                ],
                'render_type' => 'ui',
                'condition' => [
                    'heading_animations' => 'tce-split-txt',
                ],
            ]
        );
        $this->add_control(
            'split_drop_shadow_blur_radius',
            [
                'label' => esc_html__('Blur Radius', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 200,
                        'min' => 0,
                        'step' => 1,
                    ],
                ],
                'render_type' => 'ui',
                'condition' => [
                    'heading_animations' => 'tce-split-txt',
                ],
            ]
        );
        $this->add_control(
            'split_drop_shadow_color',
            [
                'label' => __('Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'condition' => [
                    'heading_animations' => 'tce-split-txt',
                ],
            ]
        );
        $this->add_control(
            'split_drop_shadow_color_dark_mode',
            [
                'label' => esc_html__( 'Drop Shadow Color (Dark Mode)', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'condition' => [
                    'heading_animations' => 'tce-split-txt',
                ],
            ]
        );

        $this->end_popover();
        $this->add_control(
            'scroll_trigger',
            [
                'label' => esc_html__( 'Scroll Trigger : ', 'element-camp' ),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'heading_animations' => 'tce-scroll-end',
                ],
            ]
        );
        $this->add_control(
            'scroll_trigger_important_note',
            [
                'type' => \Elementor\Controls_Manager::ALERT,
                'alert_type' => 'warning',
                'heading' => esc_html__('Effect Important Note', 'element-camp'),
                'content' => esc_html__('Default Trigger is the Element. If you want to change it, type the class of the element you want to trigger. It must exist.', 'element-camp'),
                'condition' => [
                    'heading_animations' => 'tce-scroll-end',
                    'scroll_trigger!' => '',
                ],
            ]
        );
        $this->add_control(
            'scroll_end_trigger',
            [
                'label' => esc_html__( 'Scroll End Trigger : ', 'element-camp' ),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'heading_animations' => 'tce-scroll-end',
                ],
            ]
        );
        $this->add_control(
            'scroll_end_trigger_important_note',
            [
                'type' => \Elementor\Controls_Manager::ALERT,
                'alert_type' => 'warning',
                'heading' => esc_html__('Effect Important Note', 'element-camp'),
                'content' => esc_html__('Default End Trigger is the Parent of Element. If you want to change it, type the class of the element you want to trigger. It must exist.', 'element-camp'),
                'condition' => [
                    'heading_animations' => 'tce-scroll-end',
                    'scroll_end_trigger!' => '',
                ],
            ]
        );
        $this->add_control(
            'scroll_start',
            [
                'label' => esc_html__( 'Scroll Start : ', 'element-camp' ),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'heading_animations' => 'tce-scroll-end',
                ],
            ]
        );
        $this->add_control(
            'scroll_end',
            [
                'label' => esc_html__( 'Scroll End : ', 'element-camp' ),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'heading_animations' => 'tce-scroll-end',
                ],
            ]
        );

        $this->add_control(
            'letters_line_heading',
            [
                'label' => esc_html__('Letters Line Settings', 'element-camp'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'heading_animations' => 'tce-letters-line',
                ],
            ]
        );

        $this->add_control(
            'letters_duration',
            [
                'label' => esc_html__('Animation Duration', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.6,
                'min' => 0.1,
                'max' => 5,
                'step' => 0.1,
                'condition' => [
                    'heading_animations' => 'tce-letters-line',
                ],
            ]
        );

        $this->add_control(
            'letters_stagger',
            [
                'label' => esc_html__('Stagger Delay', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.03,
                'min' => 0,
                'max' => 1,
                'step' => 0.01,
                'description' => esc_html__('Delay between each letter animation', 'element-camp'),
                'condition' => [
                    'heading_animations' => 'tce-letters-line',
                ],
            ]
        );

        $this->add_control(
            'letters_y_offset',
            [
                'label' => esc_html__('Y Offset', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'default' => 50,
                'min' => 0,
                'max' => 200,
                'step' => 5,
                'description' => esc_html__('Initial Y position offset for letters', 'element-camp'),
                'condition' => [
                    'heading_animations' => 'tce-letters-line',
                ],
            ]
        );

        $this->add_control(
            'letters_trigger',
            [
                'label' => esc_html__('Custom Trigger Element', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__('e.g., .my-trigger-class', 'element-camp'),
                'description' => esc_html__('Leave empty to use the heading element as trigger', 'element-camp'),
                'condition' => [
                    'heading_animations' => 'tce-letters-line',
                ],
            ]
        );

        $this->add_control(
            'letters_start_position',
            [
                'label' => esc_html__('Trigger Start Position', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'default' => 'top 80%',
                'placeholder' => esc_html__('e.g., top 80%, center center', 'element-camp'),
                'description' => esc_html__('ScrollTrigger start position', 'element-camp'),
                'condition' => [
                    'heading_animations' => 'tce-letters-line',
                ],
            ]
        );

        $this->add_control(
            'letters_blur_effect',
            [
                'label' => esc_html__('Enable Blur Effect', 'element-camp'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'default' => '',
                'description' => esc_html__('Add blur animation to letters', 'element-camp'),
                'condition' => [
                    'heading_animations' => 'tce-letters-line',
                ],
            ]
        );

        $this->add_control(
            'letters_blur_amount',
            [
                'label' => esc_html__('Initial Blur Amount', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'default' => 100,
                'min' => 0,
                'max' => 200,
                'step' => 5,
                'description' => esc_html__('Initial blur in pixels', 'element-camp'),
                'condition' => [
                    'heading_animations' => 'tce-letters-line',
                    'letters_blur_effect' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'letters_initial_scale',
            [
                'label' => esc_html__('Initial Scale', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'default' => 2,
                'min' => 0.1,
                'max' => 5,
                'step' => 0.1,
                'description' => esc_html__('Initial scale of letters', 'element-camp'),
                'condition' => [
                    'heading_animations' => 'tce-letters-line',
                    'letters_blur_effect' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'letters_trigger_note',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => esc_html__('Note: This animation requires GSAP and ScrollTrigger to be loaded on your site.', 'element-camp'),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition' => [
                    'heading_animations' => 'tce-letters-line',
                ],
            ]
        );

        $this->add_control(
            'gradient_shift_animation',
            [
                'label' => esc_html__('Gradient Shift Animation', 'element-camp'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'condition' => [
                    'background_clip_text' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'gradient_shift_speed',
            [
                'label' => esc_html__('Animation Speed (seconds)', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 20,
                        'step' => 0.5,
                    ],
                ],
                'default' => [
                    'size' => 8,
                ],
                'condition' => [
                    'background_clip_text' => 'yes',
                    'gradient_shift_animation' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading.tcg-gradient-shift' => 'animation-duration: {{SIZE}}s;',
                ],
            ]
        );
        $this->add_control(
            'move_background_animation',
            [
                'label' => esc_html__('Move Background Animation', 'element-camp'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'condition' => [
                    'background_clip_text' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'move_background_animation_speed',
            [
                'label' => esc_html__('Animation Speed (seconds)', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 20,
                        'step' => 0.5,
                    ],
                ],
                'default' => [
                    'size' => 5,
                ],
                'condition' => [
                    'background_clip_text' => 'yes',
                    'move_background_animation' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text .tcgelements-heading.tcg-moveBg' => 'animation-duration: {{SIZE}}s;',
                ],
            ]
        );
        $this->end_controls_section();
    }

    /**
     * Render heading widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
        protected function render() {
            $settings = $this->get_settings_for_display();

            if ( '' === $settings['title'] ) {
                return;
            }
            $this->add_render_attribute( 'title', 'class', 'tcgelements-heading' );

            if ( ! empty( $settings['size'] ) ) {
                $this->add_render_attribute( 'title', 'class', 'elementor-size-' . $settings['size'] );
            }
            if ($settings['text_breakline'] == 'yes') {
                $this->add_render_attribute( 'title', 'class', 'tcgelements-text-breakline' );
            }
            if (!empty($settings['background_clip_text']) && $settings['background_clip_text'] === 'yes' && !empty($settings['gradient_shift_animation']) && $settings['gradient_shift_animation'] === 'yes') {
                $this->add_render_attribute( 'title', 'class', 'tcg-gradient-shift' );
            }
            if (!empty($settings['background_clip_text']) && $settings['background_clip_text'] === 'yes' && !empty($settings['move_background_animation']) && $settings['move_background_animation'] === 'yes') {
                $this->add_render_attribute( 'title', 'class', 'tcg-moveBg' );
            }
            if ($settings['heading_animations'] == 'tce-split-txt') {
                $this->add_render_attribute( 'title', 'class', $settings['heading_animations'] );
                $this->add_render_attribute( 'title', 'data-split-duration', $settings['split_duration'] );
                if (!empty($settings['split_trigger'])){
                    $this->add_render_attribute( 'title', 'data-split-trigger', '.'.$settings['split_trigger'] );
                }
                if (!empty($settings['split_start'])){
                    $this->add_render_attribute( 'title', 'data-split-start', $settings['split_start'] );
                }
                if (!empty($settings['split_end'])){
                    $this->add_render_attribute( 'title', 'data-split-end', $settings['split_end'] );
                }
                if (!empty($settings['split_color_dark_mode'])){
                    $this->add_render_attribute('title', 'data-split-color-dark', $settings['split_color_dark_mode']);
                }
                $this->add_render_attribute('title', 'data-split-color', $settings['split_color']);
                if (
                    !empty($settings['split_drop_shadow_offset_x']['size']) &&
                    !empty($settings['split_drop_shadow_offset_y']['size']) &&
                    !empty($settings['split_drop_shadow_blur_radius']['size']) &&
                    !empty($settings['split_drop_shadow_color'])
                ) {
                    $offset_x = $settings['split_drop_shadow_offset_x']['size'] . 'px';
                    $offset_y = $settings['split_drop_shadow_offset_y']['size'] . 'px';
                    $radius   = $settings['split_drop_shadow_blur_radius']['size'] . 'px';
                    $color    = $settings['split_drop_shadow_color'];

                    $filter = "drop-shadow($offset_x $offset_y $radius $color)";
                    $this->add_render_attribute('title', 'data-split-filter', $filter);
                }

                // Dark mode drop shadow color
                $color_dark = $settings['split_drop_shadow_color_dark_mode'];
                if (!empty($offset_x) && !empty($offset_y) && !empty($radius) && !empty($color_dark)) {
                    $filter_dark = "drop-shadow($offset_x $offset_y $radius $color_dark)";
                    $this->add_render_attribute( 'title', 'data-split-filter-dark', $filter_dark );
                }
            }
            if ($settings['heading_animations'] == 'tce-fade-text') {
                $this->add_render_attribute('title', 'class', $settings['heading_animations']);

                if (!empty($settings['fade_trigger_auto'])) {
                    $this->add_render_attribute('title', 'data-fade-auto', $settings['fade_trigger_auto']);
                }
            }
            if ($settings['heading_animations'] == 'tce-scroll-end') {
                $this->add_render_attribute( 'title', 'class', $settings['heading_animations'] );
                if (!empty($settings['scroll_trigger'])){
                    $this->add_render_attribute( 'title', 'data-scroll-trigger', '.'.$settings['scroll_trigger'] );
                }
                if (!empty($settings['scroll_end_trigger'])){
                    $this->add_render_attribute( 'title', 'data-scroll-end-trigger', '.'.$settings['scroll_end_trigger'] );
                }
                if (!empty($settings['scroll_start'])){
                    $this->add_render_attribute( 'title', 'data-scroll-start', $settings['scroll_start'] );
                }
                if (!empty($settings['scroll_end'])){
                    $this->add_render_attribute( 'title', 'data-scroll-end', $settings['scroll_end'] );
                }
            }
            if ($settings['heading_animations'] == 'tce-scroll-fill') {
                $this->add_render_attribute('title', 'class', $settings['heading_animations']);
            }
            if ($settings['heading_animations'] == 'tce-funky-letters') {
                $this->add_render_attribute('title', 'class', $settings['heading_animations']);
            }
            if ($settings['heading_animations'] == 'tce-letters-line') {
                $this->add_render_attribute('title', 'class', $settings['heading_animations']);

                // Add data attributes for animation settings
                if (!empty($settings['letters_duration'])) {
                    $this->add_render_attribute('title', 'data-letters-duration', $settings['letters_duration']);
                }

                if (!empty($settings['letters_stagger'])) {
                    $this->add_render_attribute('title', 'data-letters-stagger', $settings['letters_stagger']);
                }

                if (!empty($settings['letters_y_offset'])) {
                    $this->add_render_attribute('title', 'data-letters-y-offset', $settings['letters_y_offset']);
                }

                if (!empty($settings['letters_trigger'])) {
                    $this->add_render_attribute('title', 'data-letters-trigger', $settings['letters_trigger']);
                }

                if (!empty($settings['letters_start_position'])) {
                    $this->add_render_attribute('title', 'data-letters-start', $settings['letters_start_position']);
                }

                if (!empty($settings['letters_blur_effect']) && $settings['letters_blur_effect'] === 'yes') {
                    $this->add_render_attribute('title', 'data-letters-blur', 'yes');

                    if (!empty($settings['letters_blur_amount'])) {
                        $this->add_render_attribute('title', 'data-letters-blur-amount', $settings['letters_blur_amount']);
                    }

                    if (!empty($settings['letters_initial_scale'])) {
                        $this->add_render_attribute('title', 'data-letters-initial-scale', $settings['letters_initial_scale']);
                    }
                }
            }
            $this->add_inline_editing_attributes( 'title' );
            if (!empty($settings['double_background']) && $settings['double_background'] === 'yes') {
                $this->add_render_attribute('title', 'class', 'double-bg');

                $first_bg_data = $this->extract_background_data($settings, 'title_background_color_');
                $second_bg_data = $this->extract_background_data($settings, 'double_title_background_color_');

                // Convert to JSON and add as data attributes
                $this->add_render_attribute('title', 'data-first-bg-full', json_encode($first_bg_data));
                $this->add_render_attribute('title', 'data-second-bg-full', json_encode($second_bg_data));
            }
            $title = $settings['title'];
            $title_html = sprintf( '<%1$s %2$s>%3$s</%1$s>', Utils::validate_html_tag( $settings['header_size'] ), $this->get_render_attribute_string( 'title' ), $title );
            // PHPCS - the variable $title_html holds safe data.
            ?>
            <div class="tcgelements-heading-text <?php echo 'heading-selector-type-' . $settings['heading_hover_selector']  ?>" <?php if ($settings['heading_animations']=='scroll-parallax') : ?> data-speed="<?=$settings['parallax_speed']?>" data-lag="<?=$settings['parallax_lag']?>" <?php endif?><?php if ($settings['heading_hover_selector']=='parent-n' && !empty($settings['parent_level'])) echo 'data-parent-level="' . esc_attr($settings['parent_level']) . '"'; ?>>
                <?php
                 if ( ! empty( $settings['link']['url'] ) ) : ?>
                    <a href="<?= esc_url($settings['link']['url']) ?>" <?php if ( $settings['link']['is_external'] ) echo'target="_blank"'; ?>>
                <?php endif;
                if(!empty($settings['selected_icon']['value']) && $settings['icon_position']=='left') {
                    ?>
                    <span class="heading-icon"><?php \Elementor\Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );?></span>
                    <?php
                }
                if ($settings['after_image']=='yes' && $settings['image_position']=='left'){
                    ?>
                    <img src="<?=esc_url($settings['image']['url'])?>" alt="<?php if (!empty($settings['image']['alt'])) echo esc_attr($settings['image']['alt']); ?>">
                    <?php
                }
                    echo $title_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                if ($settings['after_image']=='yes' && $settings['image_position']=='right'){
                    ?>
                    <img src="<?=esc_url($settings['image']['url'])?>" alt="<?php if (!empty($settings['image']['alt'])) echo esc_attr($settings['image']['alt']); ?>">
                    <?php
                }
                if(!empty($settings['selected_icon']['value']) && $settings['icon_position']=='right') {
                    ?>
                    <span class="heading-icon"><?php \Elementor\Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );?></span>
                    <?php
                }
                if ( ! empty( $settings['link']['url'] ) ) : ?>
                    </a>
                <?php endif; ?>
            </div>

            <?php
        }
    private function extract_background_data($settings, $prefix) {
        $bg_data = [];
        $bg_type_key = $prefix . 'background';

        if (isset($settings[$bg_type_key])) {
            $bg_data['background'] = $settings[$bg_type_key];

            switch ($settings[$bg_type_key]) {
                case 'classic':
                    $this->extract_classic_bg($settings, $prefix, $bg_data);
                    break;
                case 'gradient':
                    $this->extract_standard_gradient($settings, $prefix, $bg_data);
                    break;
                case 'tcg_gradient':
                case 'tcg_gradient_4':
                    $this->extract_tcg_gradient($settings, $prefix, $bg_data);
                    break;
            }
        }
        return $bg_data;
    }
    private function extract_classic_bg($settings, $prefix, &$bg_data) {
        $color_key = $prefix . 'background_color';
        if (isset($settings[$color_key]) && !empty($settings[$color_key])) {
            $bg_data['background_color'] = $settings[$color_key];
        }
    }
    private function extract_standard_gradient($settings, $prefix, &$bg_data) {
        $keys = ['gradient_angle', 'gradient_color', 'gradient_color_b', 'gradient_color_a_stop', 'gradient_color_b_stop'];

        foreach ($keys as $key) {
            $full_key = $prefix . $key;
            if (isset($settings[$full_key]) && !is_null($settings[$full_key]) && $settings[$full_key] !== '') {
                $bg_data[$key] = $settings[$full_key];
            }
        }

        // Fallback: extract any non-tcg background properties
        if (empty($bg_data['gradient_color'])) {
            foreach ($settings as $key => $value) {
                if (strpos($key, $prefix) === 0 && strpos($key, 'tcg_') === false && !is_null($value) && $value !== '') {
                    $clean_key = str_replace($prefix, '', $key);
                    $bg_data[$clean_key] = $value;
                }
            }
        }
    }

    private function extract_tcg_gradient($settings, $prefix, &$bg_data) {
        $keys = ['tcg_gradient_angle', 'color', 'color_b', 'color_c', 'color_d', 'color_stop', 'color_b_stop', 'color_c_stop', 'color_d_stop'];

        foreach ($keys as $key) {
            $full_key = $prefix . $key;
            if (isset($settings[$full_key]) && !is_null($settings[$full_key])) {
                $bg_data[$key] = $settings[$full_key];
            }
        }
    }
}
