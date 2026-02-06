<?php

namespace ElementCampPlugin\Widgets;

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
use Elementor\Repeater;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Css_Filter;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
/**
 * @since 1.0.0
 */
class ElementCamp_Social_Icons extends Widget_Base {

    /**
     * Get widget name.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'tcgelements-social-icons';
    }

    /**
     * Get widget title.
     *
     * Retrieve social icons widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__( 'Social Icons', 'element-camp' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve social icons widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-social-icons tce-widget-badge';
    }
    public function get_script_depends()
    {
        return ['tcgelements-social-icons'];
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
        return [ 'social', 'icon', 'link' ];
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
     * Register social icons widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 3.1.0
     * @access protected
     */
    protected function register_controls() {

        $start = is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');
        $end = !is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');

        $this->start_controls_section(
            'section_social_icon',
            [
                'label' => esc_html__( 'Social Icons', 'element-camp' ),
            ]
        );

        $this->add_control(
            'display_list_on_hover',
            [
                'label' => esc_html__( 'Display List On Hover', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'yes', 'element-camp' ),
                'label_off' => esc_html__( 'No', 'element-camp' ),
            ]
        );

        $this->add_control(
            'display_list_on_hover_animation',
            [
                'label' => esc_html__('Display List On Hover Animation', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'animation1',
                'options' => [
                    'animation1' => esc_html__('Slide Reveal', 'element-camp'),
                    'animation2' => esc_html__('Stacked Dropdown', 'element-camp'),
                    'animation3' => esc_html__('Scale Out', 'element-camp'),
                    'animation4' => esc_html__('Staggered Slide Up', 'element-camp'),
                ],
                'condition' => [
                    'display_list_on_hover' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_icon',
            [
                'label' => esc_html__( 'Show Icon', 'element-camp' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'social',
                'default' => [
                    'value' => 'fa fa-plus',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'display_list_on_hover' => 'yes',
                    'display_list_on_hover_animation' => ['animation1','animation3','animation4']
                ],
            ]
        );

        $this->add_control(
            'show_icon_link',
            [
                'label' => esc_html__( 'Link', 'element-camp' ),
                'type' => Controls_Manager::URL,
                'default' => [
                    'is_external' => 'true',
                ],
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'display_list_on_hover' => 'yes',
                    'display_list_on_hover_animation' => ['animation1','animation3','animation4']
                ],
            ]
        );

        $this->add_control(
            'animation_hover_selector',
            [
                'label' => esc_html__('Animation Hover Selector', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'widget',
                'options' => [
                    'widget' => esc_html__('Widget (Self)', 'element-camp'),
                    'parent' => esc_html__('Parent Container', 'element-camp'),
                    'parent-parent' => esc_html__('Parent Parent Container', 'element-camp'),
                    'parent-n' => esc_html__('Parent N Container', 'element-camp'),
                ],
                'description' => esc_html__('Choose which container hover should trigger the animation', 'element-camp'),
                'condition' => [
                    'display_list_on_hover' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'animation_parent_level',
            [
                'label' => esc_html__('Animation Parent Level', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'default' => 1,
                'min' => 1,
                'max' => 10,
                'step' => 1,
                'description' => esc_html__('How many parent levels to go up (1 = direct parent, 2 = grandparent, etc.)', 'element-camp'),
                'condition' => [
                    'display_list_on_hover' => 'yes',
                    'animation_hover_selector' => 'parent-n',
                ],
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'social_icon',
            [
                'label' => esc_html__( 'Icon', 'element-camp' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'social',
                'default' => [
                    'value' => 'fab fa-wordpress',
                    'library' => 'fa-brands',
                ],
                'recommended' => [
                    'fa-brands' => [
                        'android',
                        'apple',
                        'behance',
                        'bitbucket',
                        'codepen',
                        'delicious',
                        'deviantart',
                        'digg',
                        'dribbble',
                        'element-camp',
                        'facebook',
                        'flickr',
                        'foursquare',
                        'free-code-camp',
                        'github',
                        'gitlab',
                        'globe',
                        'houzz',
                        'instagram',
                        'jsfiddle',
                        'linkedin',
                        'medium',
                        'meetup',
                        'mix',
                        'mixcloud',
                        'odnoklassniki',
                        'pinterest',
                        'product-hunt',
                        'reddit',
                        'shopping-cart',
                        'skype',
                        'slideshare',
                        'snapchat',
                        'soundcloud',
                        'spotify',
                        'stack-overflow',
                        'steam',
                        'telegram',
                        'thumb-tack',
                        'tripadvisor',
                        'tumblr',
                        'twitch',
                        'twitter',
                        'viber',
                        'vimeo',
                        'vk',
                        'weibo',
                        'weixin',
                        'whatsapp',
                        'wordpress',
                        'xing',
                        'yelp',
                        'youtube',
                        '500px',
                    ],
                    'fa-solid' => [
                        'envelope',
                        'link',
                        'rss',
                    ],
                ],
            ]
        );

        $repeater->add_control(
            'link_type',
            [
            'label' => esc_html__('Link Type', 'element-camp'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'author' => esc_html__('Author Links', 'element-camp'),
                'custom' => esc_html__('Custom Link', 'element-camp'),
            ],
            'default' => 'custom',
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => esc_html__( 'Link', 'element-camp' ),
                'type' => Controls_Manager::URL,
                'default' => [
                    'is_external' => 'true',
                ],
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'link_type' => 'custom',
                ],
            ]
        );

        $repeater->add_control(
            'link_author',
            [
                'label' => esc_html__( 'Author Link', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'facebook' => esc_html__('Facebook', 'element-camp'),
                    'twitter' => esc_html__('Twitter', 'element-camp'),
                    'instagram' => esc_html__('Instagram', 'element-camp'),
                    'pinterest' => esc_html__('Pinterest', 'element-camp'),
                    'website' => esc_html__('Website', 'element-camp'),
                ],
                'default' => 'twitter',
                'condition' => [
                    'link_type' => 'author',
                ],
            ]
        );

        $repeater->add_control(
            'link_author_in_new_window',
            [
                'label' => esc_html__('Open in New Window', 'element-camp'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'default' => false,
                'return_value' => 'yes',
                'condition' => [
                    'link_type' => 'author',
                ],
            ]
        );

        $repeater->add_control(
            'link_author_add_nofollow',
            [
                'label' => esc_html__('Add Nofollow', 'element-camp'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'default' => false,
                'return_value' => 'yes',
                'condition' => [
                    'link_type' => 'author',
                ],
            ]
        );

        $repeater->add_control(
            'link_author_custom_attributes',
            [
                'label' => esc_html__('Custom Attributes', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__('key|value', 'element-camp'),
                'default' => '',
                'condition' => [
                    'link_type' => 'author',
                ],
            ]
        );

        $repeater->add_control(
            'social_text',
            [
                'label' => esc_html__('Social Text', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => esc_html__('Enter your Social', 'element-camp'),
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $repeater->add_control(
            'item_icon_background_color',
            [
                'label' => esc_html__( 'Background Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.tcgelements-social-icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_control(
            'item_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.tcgelements-social-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} {{CURRENT_ITEM}}.tcgelements-social-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'social_icon_list',
            [
                'label' => esc_html__( 'Social Icons', 'element-camp' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'social_icon' => [
                            'value' => 'fab fa-facebook',
                            'library' => 'fa-brands',
                        ],
                    ],
                    [
                        'social_icon' => [
                            'value' => 'fab fa-twitter',
                            'library' => 'fa-brands',
                        ],
                    ],
                    [
                        'social_icon' => [
                            'value' => 'fab fa-youtube',
                            'library' => 'fa-brands',
                        ],
                    ],
                ],
                'title_field' => '<# 
                                    var migrated = "undefined" !== typeof __fa4_migrated, 
                                        social = ( "undefined" === typeof social ) ? false : social, 
                                        socialText = ( "undefined" !== typeof social_text && social_text ) ? social_text : elementor.helpers.getSocialNetworkNameFromIcon( social_icon, social, true, migrated, true ); 
                                #>{{{ socialText }}}',
            ]
        );

        $this->add_responsive_control(
            'social_icons_display',
            [
                'label' => esc_html__('Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'block',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-social-icons' => 'display: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-social-icons .share-icons' => 'display: {{VALUE}};'
                ]
            ]
        );

        $this->add_responsive_control(
            'flex_direction',
            [
                'label' => esc_html__( 'Flex Direction', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'column',
                'options' => [
                    'column' => [
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
                    'column' => 'flex-direction: column;',
                    'after' => 'flex-direction: column-reverse;',
                    'start' => 'flex-direction: row;',
                    'end' => 'flex-direction: row-reverse;',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-social-icons' => '{{VALUE}}',
                    '{{WRAPPER}} .tcgelements-social-icons .share-icons' => '{{VALUE}}',
                ],
                'condition'=> ['social_icons_display'=> ['flex','inline-flex']],
            ]
        );
        $this->add_responsive_control(
            'social_icons_justify_content',
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
                    '{{WRAPPER}} .tcgelements-social-icons' => 'justify-content: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-social-icons .share-icons' => 'justify-content: {{VALUE}};',
                ],
                'condition'=> ['social_icons_display'=> ['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'social_icons_align_items',
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
                    '{{WRAPPER}} .tcgelements-social-icons' => 'align-items: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-social-icons .share-icons' => 'align-items: {{VALUE}};',
                ],
                'condition'=> ['social_icons_display'=> ['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'social_icons_flex_wrap',
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
                    '{{WRAPPER}} .tcgelements-social-icons' => 'flex-wrap: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-social-icons .share-icons' => 'flex-wrap: {{VALUE}};',
                ],
                'condition'=> ['social_icons_display'=> ['flex','inline-flex']],
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'section_social_style',
            [
                'label' => esc_html__( 'Icon', 'element-camp' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'social_icon_wrapper_display',
            [
                'label' => esc_html__('Icon Wrapper Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'inline-block',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon' => 'display: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-social-icons .show-icon' => 'display: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'social_icon_wrapper_flex_direction',
            [
                'label' => esc_html__( 'Flex Direction', 'element-camp' ),
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
                    '{{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon' => '{{VALUE}}',
                    '{{WRAPPER}} .tcgelements-social-icons .show-icon' => '{{VALUE}}',
                ],
                'condition'=> ['social_icon_wrapper_display'=> ['flex','inline-flex']],
            ]
        );
        $this->add_responsive_control(
            'social_icon_wrapper_justify_content',
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
                    '{{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon' => 'justify-content: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-social-icons .show-icon' => 'justify-content: {{VALUE}};',
                ],
                'condition'=> ['social_icon_wrapper_display'=> ['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'social_icon_wrapper_align_items',
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
                    '{{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon' => 'align-items: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-social-icons .show-icon' => 'align-items: {{VALUE}};',
                ],
                'condition'=> ['social_icon_wrapper_display'=> ['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'social_icon_wrapper_flex_wrap',
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
                    '{{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon' => 'flex-wrap: {{VALUE}};',
                ],
                'condition'=> ['social_icon_wrapper_display'=> ['flex','inline-flex']],
            ]
        );
        $this->add_responsive_control(
            'icon_wrapper_width',
            [
                'label' => esc_html__( 'Icon Wrapper Width', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem', 'vw', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-social-icons .show-icon' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_wrapper_height',
            [
                'label' => esc_html__( 'Icon Wrapper Height', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem', 'vw', 'vh', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-social-icons .show-icon' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__( 'Size', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem', 'vw', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon i' => 'font-size: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-social-icons .show-icon i' => 'font-size: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .tcgelements-social-icons .show-icon svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_padding',
            [
                'label' => esc_html__( 'Icon Padding', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
                'default' => [
                    'unit' => 'em',
                ],
                'tablet_default' => [
                    'unit' => 'em',
                ],
                'mobile_default' => [
                    'unit' => 'em',
                ],
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 5,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-social-icons .show-icon' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_margin',
            [
                'label' => esc_html__( 'Icon Margin', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
                'default' => [
                    'unit' => 'em',
                ],
                'tablet_default' => [
                    'unit' => 'em',
                ],
                'mobile_default' => [
                    'unit' => 'em',
                ],
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 5,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-social-icons .show-icon' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-social-icons .show-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'text_padding',
            [
                'label' => esc_html__( 'Text Padding', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
                'default' => [
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 5,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-social-icons .social-text' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'position_for_share_icon',
            [
                'label' => esc_html__( 'Position', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'absolute' => esc_html__( 'Absolute', 'element-camp' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-social-icons .share-icons' => 'position: {{VALUE}};',
                ],
                'condition' => [
                    'display_list_on_hover' => 'yes',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'share_icons_offset_orientation_h',
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
                    'position_for_share_icon!' => '',
                    'display_list_on_hover' => 'yes',
                ],
            ]
        );
        $this->add_responsive_control(
            'share_icons_offset_x',
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
                    '{{WRAPPER}} .tcgelements-social-icons .share-icons' => 'left: {{SIZE}}{{UNIT}};right:unset;',
                ],
                'condition' => [
                    'share_icons_offset_orientation_h' => 'start',
                    'position_for_share_icon!' => '',
                    'display_list_on_hover' => 'yes',
                ],
            ]
        );
        $this->add_responsive_control(
            'share_icons_offset_x_end',
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
                    '{{WRAPPER}} .tcgelements-social-icons .share-icons' => 'right: {{SIZE}}{{UNIT}};left:unset;',
                ],
                'condition' => [
                    'share_icons_offset_orientation_h' => 'end',
                    'position_for_share_icon!' => '',
                    'display_list_on_hover' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'share_icons_offset_orientation_v',
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
                    'position_for_share_icon!' => '',
                    'display_list_on_hover' => 'yes',
                ],
            ]
        );
        $this->add_responsive_control(
            'share_icons_offset_y',
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
                    '{{WRAPPER}} .tcgelements-social-icons .share-icons' => 'top: {{SIZE}}{{UNIT}};bottom:unset;',
                ],
                'condition' => [
                    'share_icons_offset_orientation_v' => 'start',
                    'position_for_share_icon!' => '',
                    'display_list_on_hover' => 'yes',
                ],
            ]
        );
        $this->add_responsive_control(
            'share_icons_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-social-icons .share-icons' => 'bottom: {{SIZE}}{{UNIT}};top:unset;',
                ],
                'condition' => [
                    'share_icons_offset_orientation_v' => 'end',
                    'position_for_share_icon!' => '',
                    'display_list_on_hover' => 'yes',
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
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'icon_text_typography',
                'selector' => '{{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon,{{WRAPPER}} .tcgelements-social-icons .show-icon',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'icon_background_color',
            [
                'label' => esc_html__( 'Background Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-social-icons .show-icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'icon_background_advanced',
                'selector' => '{{WRAPPER}} .tcgelements-social-icons .show-icon,{{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon',
                'types' => ['gradient', 'tcg_gradient','tcg_gradient_4'],
                'fields_options' => [
                    'background' => [
                        'label' => esc_html_x('Gradient Background', 'Background Control', 'themescamp-plugin'),
                    ]
                ]
            ]
        );
        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-social-icons .show-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-social-icons .show-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_wrapper_opacity',
            [
                'label' => esc_html__( 'Icon Wrapper Opacity', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'step' => 0.1,
                        'max' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon' => 'opacity: {{SIZE}};',
                    '{{WRAPPER}} .tcgelements-social-icons .show-icon' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'social_text_color',
            [
                'label' => esc_html__( 'Social Text Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon .social-text' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_dark_mode_heading',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'icon_background_color_dark_mode',
            [
                'label' => esc_html__( 'Background Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon' => 'background-color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon' => 'background-color: {{VALUE}};',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-social-icons .show-icon' => 'background-color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-social-icons .show-icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_color_dark_mode',
            [
                'label' => esc_html__( 'Icon Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon i' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon i' => 'color: {{VALUE}};',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon svg' => 'fill: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon svg' => 'fill: {{VALUE}};',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-social-icons .show-icon i' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-social-icons .show-icon i' => 'color: {{VALUE}};',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-social-icons .show-icon svg' => 'fill: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-social-icons .show-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'social_text_color_dark_mode',
            [
                'label' => esc_html__( 'Social Text Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon .social-text' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon .social-text' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_border_color_dark_mode',
            [
                'label' => esc_html__( 'Border Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon' => 'border-color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon' => 'border-color: {{VALUE}};',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-social-icons .show-icon' => 'border-color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-social-icons .show-icon' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'icon_hover_tab',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'icon_text_typography_hover',
                'selector' => '{{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon:hover',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
            ]
        );

        $this->add_control(
            'hover_background_color',
            [
                'label' => esc_html__( 'Background Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-social-icons .show-icon:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'icon_hover_background_advanced',
                'selector' => '{{WRAPPER}} .tcgelements-social-icons .show-icon:hover,{{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon:hover',
                'types' => ['gradient', 'tcg_gradient','tcg_gradient_4'],
                'fields_options' => [
                    'background' => [
                        'label' => esc_html_x('Gradient Background', 'Background Control', 'themescamp-plugin'),
                    ]
                ]
            ]
        );

        $this->add_control(
            'hover_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon:hover i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon:hover svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-social-icons .show-icon:hover i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-social-icons .show-icon:hover svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hover_border_color',
            [
                'label' => esc_html__( 'Border Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'condition' => [
                    'image_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon:hover' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-social-icons .show-icon:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'social_text_color_hover',
            [
                'label' => esc_html__( 'Social Text Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon:hover .social-text' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_wrapper_opacity_hover',
            [
                'label' => esc_html__( 'Icon Wrapper Opacity', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'step' => 0.1,
                        'max' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon:hover' => 'opacity: {{SIZE}};',
                    '{{WRAPPER}} .tcgelements-social-icons .show-icon:hover' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_transition',
            [
                'label' => esc_html__( 'Icon Transition (s)', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon' => 'transition: all {{SIZE}}s ease;',
                    '{{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon svg' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );
        $this->add_control(
            'icon_dark_mode_heading_hover',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'icon_background_color_dark_mode_hover',
            [
                'label' => esc_html__( 'Background Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon:hover' => 'background-color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon:hover' => 'background-color: {{VALUE}};',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-social-icons .show-icon:hover' => 'background-color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-social-icons .show-icon:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_color_dark_mode_hover',
            [
                'label' => esc_html__( 'Icon Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon:hover i' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon:hover i' => 'color: {{VALUE}};',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon:hover svg' => 'fill: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon:hover svg' => 'fill: {{VALUE}};',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-social-icons .show-icon:hover i' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-social-icons .show-icon:hover i' => 'color: {{VALUE}};',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-social-icons .show-icon:hover svg' => 'fill: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-social-icons .show-icon:hover svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_border_color_dark_mode_hover',
            [
                'label' => esc_html__( 'Border Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon:hover' => 'border-color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon:hover' => 'border-color: {{VALUE}};',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-social-icons .show-icon:hover' => 'border-color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-social-icons .show-icon:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        $this->end_controls_section();
        $this->start_controls_section(
            'section_show_icon_style',
            [
                'label' => esc_html__( 'Show Icon', 'element-camp' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'display_list_on_hover_animation' => ['animation1','animation3','animation4'],
            ]
        );
        $this->add_control(
            'show_icon_positioning',
            [
                'label' => esc_html__('Position', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'absolute' => esc_html__('absolute', 'element-camp'),
                    'relative' => esc_html__('relative', 'element-camp'),
                ],
                'label_block' => true,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-social-icons .show-icon' => 'position: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'show_icon_offset_orientation_h',
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
            'show_icon_offset_x',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-social-icons .show-icon' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-social-icons .show-icon' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'show_icon_offset_orientation_h!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'show_icon_offset_x_end',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-social-icons .show-icon' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-social-icons .show-icon' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'show_icon_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_control(
            'show_icon_offset_orientation_v',
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
            'show_icon_offset_y',
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
                    '{{WRAPPER}} .tcgelements-social-icons .show-icon' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'show_icon_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'show_icon_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-social-icons .show-icon' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'show_icon_offset_orientation_v' => 'end',
                ],
            ]
        );
        $this->add_responsive_control(
            'show_icon_width',
            [
                'label' => esc_html__( 'Icon Wrapper Width', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem', 'vw', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-social-icons .show-icon' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'show_icon_height',
            [
                'label' => esc_html__( 'Icon Wrapper Height', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem', 'vw', 'vh', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-social-icons .show-icon' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'show_icon_size',
            [
                'label' => esc_html__( 'Size', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem', 'vw', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-social-icons .show-icon i' => 'font-size: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .tcgelements-social-icons .show-icon svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'show_icon_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-social-icons .show-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'show_icon_blur_method',
            [
                'label' => esc_html__('Blur Method', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'backdrop-filter' => 'backdrop-filter',
                    'filter' => 'filter',
                ],
                'default' => 'backdrop-filter',
                'selectors' => [
                    '{{WRAPPER}}  .tcgelements-social-icons .show-icon' => '{{VALUE}}: blur({{show_icon_blur_value.SIZE}}px);',
                ],
            ]
        );
        $this->add_control(
            'show_icon_blur_value',
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
            'show_icon_background_color',
            [
                'label' => esc_html__( 'Background Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-social-icons .show-icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_container_hover',
            [
                'label' => esc_html__( 'Container Hover', 'element-camp' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'container_hover_selector',
            [
                'label' => esc_html__('Container Hover Selector', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'container',
                'options' => [
                    'container' => esc_html__('Container (Self)', 'element-camp'),
                    'parent' => esc_html__('Parent Container', 'element-camp'),
                    'parent-parent' => esc_html__('Parent Parent Container', 'element-camp'),
                    'parent-n' => esc_html__('Parent N Container', 'element-camp'),
                ],
                'description' => esc_html__('Choose which container should trigger the social icons hover effect', 'element-camp'),
            ]
        );
        $this->add_control(
            'parent_level',
            [
                'label' => esc_html__('Parent Level', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'default' => 1,
                'min' => 1,
                'max' => 10,
                'step' => 1,
                'description' => esc_html__('How many parent levels to go up (1 = direct parent, 2 = grandparent, etc.)', 'element-camp'),
                'condition' => [
                    'container_hover_selector' => 'parent-n',
                ],
            ]
        );
        $this->add_responsive_control(
            'container_hover_icon_wrapper_opacity',
            [
                'label' => esc_html__( 'Icon Wrapper Opacity', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'step' => 0.1,
                        'max' => 1,
                    ],
                ],
                'selectors' => [
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-social-icons.tc-social-icons-container-active .tcgelements-social-icon' => 'opacity: {{SIZE}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-social-icons.tc-social-icons-container-active .show-icon' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'container_hover_background_color',
            [
                'label' => esc_html__( 'Background Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-social-icons.tc-social-icons-container-active .show-icon' => 'background-color: {{VALUE}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-social-icons.tc-social-icons-container-active .tcgelements-social-icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'container_hover_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-social-icons.tc-social-icons-container-active .tcgelements-social-icon i' => 'color: {{VALUE}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-social-icons.tc-social-icons-container-active .tcgelements-social-icon svg' => 'fill: {{VALUE}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-social-icons.tc-social-icons-container-active .show-icon i' => 'color: {{VALUE}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-social-icons.tc-social-icons-container-active .show-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'container_hover_border_color',
            [
                'label' => esc_html__( 'Border Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'condition' => [
                    'image_border_border!' => '',
                ],
                'selectors' => [
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-social-icons.tc-social-icons-container-active .tcgelements-social-icon' => 'border-color: {{VALUE}};',
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-social-icons.tc-social-icons-container-active .show-icon' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'container_hover_social_text_color',
            [
                'label' => esc_html__( 'Social Text Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '.e-con:hover .elementor-element-{{ID}}>.elementor-widget-container>.tcgelements-social-icons.tc-social-icons-container-active .tcgelements-social-icon:hover .social-text' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_social_icons_animations',
            [
                'label' => esc_html__( 'Animations', 'element-camp' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'social_icons_animations',
            [
                'label' => esc_html__('Animations', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'parallax-mouse' => esc_html__('Parallax Mouse', 'element-camp'),
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_social_icons_overlay',
            [
                'label' => esc_html__( 'Overlay', 'element-camp' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'social_icons_overlay_background',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon::before',
                'fields_options' => [
                    'background' => [
                        'label' => esc_html_x('Overlay Background', 'Background Control', 'element-camp'),
                    ]
                ]
            ]
        );

        $this->add_control(
            'social_icons_overlay_positioning',
            [
                'label' => esc_html__('Position', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'absolute' => esc_html__('absolute', 'element-camp'),
                    'relative' => esc_html__('relative', 'element-camp'),
                ],
                'label_block' => true,
                'default' => 'absolute',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon::before' => 'position: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'social_icons_overlay_offset_orientation_h',
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
            'social_icons_overlay_offset_x',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon::before' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon::before' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'social_icons_overlay_offset_orientation_h!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'social_icons_overlay_offset_x_end',
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
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon::before' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon::before' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'social_icons_overlay_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_control(
            'social_icons_overlay_offset_orientation_v',
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
            'social_icons_overlay_offset_y',
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
                    '{{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon::before' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'social_icons_overlay_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'social_icons_overlay_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon::before' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'social_icons_overlay_offset_orientation_v' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'social_icons_overlay_z_index',
            [
                'label' => esc_html__('Z-Index', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon::before' => 'z-index: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'social_icons_overlay_width',
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
                    '{{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon::before' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'social_icons_overlay_height',
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
                    '{{WRAPPER}} .tcgelements-social-icons .tcgelements-social-icon::before' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
    }

    /**
     * Render social icons widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $fallback_defaults = [
            'fa fa-facebook',
            'fa fa-twitter',
            'fa fa-google-plus',
        ];

        $class_animation = '';

        if (( $settings['social_icons_animations']) === 'parallax-mouse' ) {
            $class_animation = 'tcgelements-hover-anim';
        }

        $migration_allowed = Icons_Manager::is_migration_allowed();

        $data_attributes = '';
        if ($settings['container_hover_selector'] === 'parent-n' && !empty($settings['parent_level'])) {
            $data_attributes .= ' data-parent-level="' . esc_attr($settings['parent_level']) . '"';
        }

        // Add animation hover selector data attributes
        if ($settings['display_list_on_hover'] === 'yes') {
            $animation_hover_selector = $settings['animation_hover_selector'] ?? 'widget';
            $data_attributes .= ' data-animation-hover-selector="' . esc_attr($animation_hover_selector) . '"';

            if ($animation_hover_selector === 'parent-n' && !empty($settings['animation_parent_level'])) {
                $data_attributes .= ' data-animation-parent-level="' . esc_attr($settings['animation_parent_level']) . '"';
            }
        }

        $animation_class = isset($settings['animation_hover_selector']) ? 'animation-hover-type-' . $settings['animation_hover_selector'] : 'animation-hover-type-widget';
        ?>
        <div class="tcgelements-social-icons <?php echo esc_attr('social-icons-selector-type-'.$settings['container_hover_selector']); ?> <?php echo esc_attr($animation_class); ?>" <?php echo $data_attributes; ?>>
            <?php if (
                $settings['display_list_on_hover'] === 'yes' &&
                in_array($settings['display_list_on_hover_animation'], ['animation1', 'animation3','animation4'], true)
            ) : ?>
                <a class="show-icon" href="<?= esc_url($settings['show_icon_link']['url']); ?>" <?php if ($settings['show_icon_link']['is_external']) echo 'target="_blank"'; ?>>
                    <?php Icons_Manager::render_icon($settings['show_icon']); ?>
                </a>
            <?php endif; ?>
            <?php if ($settings['display_list_on_hover'] === 'yes') : ?>
            <div class="share-icons <?=esc_attr($settings['display_list_on_hover_animation'])?>">
                <?php endif; ?>
                <?php foreach ($settings['social_icon_list'] as $index => $item) {
                    $migrated = isset($item['__fa4_migrated']['social_icon']);
                    $is_new = empty($item['social']) && $migration_allowed;
                    $social = '';

                    if (empty($item['social']) && !$migration_allowed) {
                        $item['social'] = $fallback_defaults[$index] ?? 'fa fa-wordpress';
                    }

                    if (!empty($item['social'])) {
                        $social = str_replace('fa fa-', '', $item['social']);
                    }

                    if (($is_new || $migrated) && 'svg' !== $item['social_icon']['library']) {
                        $social = explode(' ', $item['social_icon']['value'], 2);
                        if (empty($social[1])) {
                            $social = '';
                        } else {
                            $social = str_replace('fa-', '', $social[1]);
                        }
                    }

                    if ('svg' === $item['social_icon']['library']) {
                        $social = get_post_meta($item['social_icon']['value']['id'], '_wp_attachment_image_alt', true);
                    }

                    $link_key = 'link_' . $index;

                    $this->add_render_attribute($link_key, 'class', [
                        'elementor-icon',
                        'tcgelements-social-icon',
                        'tcgelements-social-icon-' . $social,
                        $class_animation,
                        'elementor-repeater-item-' . $item['_id'],
                    ]);
                    if($item['link_type'] === 'custom') {
                        $this->add_link_attributes($link_key, $item['link']);
                    } else {

                        global $post;
                        $author_id = $post->post_author;

                        $author_link = [
                            'url' => get_the_author_meta($item['link_author'], $author_id),
                            'is_external' => $item['link_author_in_new_window'] === 'yes',
                            'nofollow' => $item['link_author_add_nofollow'] === 'yes',
                            'custom_attributes' => $item['link_author_custom_attributes'],
                        ];

                        if(!empty(get_the_author_meta($item['link_author'], $author_id))) {
                            $this->add_link_attributes($link_key, $author_link);
                        };
                    }
                    ?>
                    <?php if ($settings['social_icons_animations'] === 'parallax-mouse') : ?>
                        <div class="social-icons-wrapper tcgelements-hover-this">
                    <?php endif;?>
                    <a <?php $this->print_render_attribute_string($link_key); ?>>
                        <span class="elementor-screen-only"><?php echo esc_html(ucwords($social)); ?></span>
                        <?php if (!empty($item['social_text'])) : ?>
                            <span class="social-text"><?= esc_html($item['social_text']) ?></span>
                        <?php endif; ?>
                        <?php
                        if ($is_new || $migrated) {
                            Icons_Manager::render_icon($item['social_icon']);
                        } else { ?>
                            <i class="<?php echo esc_attr($item['social']); ?>"></i>
                        <?php } ?>
                    </a>
                    <?php if ($settings['social_icons_animations'] === 'parallax-mouse') : ?>
                        </div>
                    <?php endif;?>
                <?php } ?>
                <?php if ($settings['display_list_on_hover'] === 'yes') : ?>
            </div>
        <?php endif; ?>
        </div>
        <?php
    }
}
