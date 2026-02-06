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
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

class ElementCamp_Creative_Cards extends Widget_Base
{

    /**
     * Retrieve the widget name.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'tcgelements-creative-cards';
    }

    /**
     * Retrieve the widget title.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget title.
     */
    public function get_title()
    {
        return esc_html__('Creative Cards', 'element-camp');
    }

    /**
     * Retrieve the widget icon.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'eicon-posts-ticker tce-widget-badge';
    }

    /**
     * Retrieve the list of categories the widget belongs to.
     *
     * @since 1.0.0
     * @access public
     * @return array Widget categories.
     */
    public function get_categories()
    {
        return ['elementcamp-elements'];
    }

    /**
     * Retrieve the list of scripts the widget depended on.
     *
     * @since 1.0.0
     * @access public
     * @return array Widget scripts dependencies.
     */
    public function get_script_depends()
    {
        return ['tcgelements-creative-cards'];
    }

    /**
     * Register the widget controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls()
    {
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'element-camp'),
            ]
        );

        $cards_repeater = new \Elementor\Repeater();

        $cards_repeater->add_control(
            'card_title',
            [
                'label' => esc_html__('Title', 'element-camp'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Digital Marketing', 'element-camp'),
                'placeholder' => esc_html__('Enter card title', 'element-camp'),
            ]
        );

        $cards_repeater->add_control(
            'card_link',
            [
                'label' => esc_html__('Link', 'element-camp'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'element-camp'),
                'default' => [
                    'url' => '#',
                    'is_external' => false,
                    'nofollow' => false,
                ],
            ]
        );

        $cards_repeater->add_control(
            'content_type',
            [
                'label' => esc_html__('Content Type', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'image',
                'options' => [
                    'image' => esc_html__('Demo Image', 'element-camp'),
                    'pages' => esc_html__('Inner Pages List', 'element-camp'),
                ],
            ]
        );

        $cards_repeater->add_control(
            'card_image',
            [
                'label' => esc_html__('Demo Image', 'element-camp'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'content_type' => 'image',
                ],
            ]
        );

        // Inner Pages List Repeater
        $pages_repeater = new \Elementor\Repeater();

        $pages_repeater->add_control(
            'page_title',
            [
                'label' => esc_html__('Page Title', 'element-camp'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('About Us', 'element-camp'),
                'placeholder' => esc_html__('Enter page title', 'element-camp'),
            ]
        );

        $pages_repeater->add_control(
            'page_link',
            [
                'label' => esc_html__('Page Link', 'element-camp'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'element-camp'),
                'default' => [
                    'url' => '#',
                    'is_external' => false,
                    'nofollow' => false,
                ],
            ]
        );

        $cards_repeater->add_control(
            'inner_pages',
            [
                'label' => esc_html__('Inner Pages', 'element-camp'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $pages_repeater->get_controls(),
                'default' => [
                    [
                        'page_title' => esc_html__('About Us', 'element-camp'),
                        'page_link' => ['url' => '#'],
                    ],
                    [
                        'page_title' => esc_html__('Services', 'element-camp'),
                        'page_link' => ['url' => '#'],
                    ],
                    [
                        'page_title' => esc_html__('Portfolio', 'element-camp'),
                        'page_link' => ['url' => '#'],
                    ],
                    [
                        'page_title' => esc_html__('Contact', 'element-camp'),
                        'page_link' => ['url' => '#'],
                    ],
                ],
                'title_field' => '{{{ page_title }}}',
                'condition' => [
                    'content_type' => 'pages',
                ],
            ]
        );

        $this->add_control(
            'cards_list',
            [
                'label' => esc_html__('Cards', 'element-camp'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $cards_repeater->get_controls(),
                'default' => [
                    [
                        'card_title' => esc_html__('Digital Marketing', 'element-camp'),
                        'card_link' => ['url' => '#'],
                        'card_image' => ['url' => \Elementor\Utils::get_placeholder_image_src()],
                    ],
                    [
                        'card_title' => esc_html__('Branding Agency', 'element-camp'),
                        'card_link' => ['url' => '#'],
                        'card_image' => ['url' => \Elementor\Utils::get_placeholder_image_src()],
                    ],
                    [
                        'card_title' => esc_html__('Creative Agency', 'element-camp'),
                        'card_link' => ['url' => '#'],
                        'card_image' => ['url' => \Elementor\Utils::get_placeholder_image_src()],
                    ],
                ],
                'title_field' => '{{{ card_title }}}',
            ]
        );

        $this->add_control(
            'card_background_image',
            [
                'label' => esc_html__('Card Background Image', 'element-camp'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
            ]
        );

        $this->end_controls_section();

        // Pages List Style Section
        $this->start_controls_section(
            'pages_list_style',
            [
                'label' => esc_html__('Pages List', 'element-camp'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'pages_list_background',
                'label' => esc_html__('Background', 'element-camp'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-creative-cards .pages-list',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'color' => [
                        'default' => '#000000',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'pages_list_border_radius',
            [
                'label' => esc_html__('Padding', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-creative-cards .pages-list' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => '30',
                    'right' => '30',
                    'bottom' => '30',
                    'left' => '30',
                    'unit' => 'px',
                ],
            ]
        );

        $this->add_responsive_control(
            'pages_list_padding',
            [
                'label' => esc_html__('Padding', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-creative-cards .pages-list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => '50',
                    'right' => '50',
                    'bottom' => '50',
                    'left' => '50',
                    'unit' => 'px',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'pages_list_typography',
                'label' => esc_html__('Typography', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-creative-cards .pages-list a',
                'fields_options' => [
                    'font_size' => [
                        'default' => [
                            'unit' => 'px',
                            'size' => 16,
                        ],
                    ],
                    'text_transform' => [
                        'default' => 'capitalize',
                    ],
                ],
            ]
        );

        $this->start_controls_tabs('pages_list_color_tabs');

        $this->start_controls_tab(
            'pages_list_normal_tab',
            [
                'label' => esc_html__('Normal', 'element-camp'),
            ]
        );

        $this->add_control(
            'pages_list_color',
            [
                'label' => esc_html__('Text Color', 'element-camp'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-creative-cards .pages-list a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'pages_list_hover_tab',
            [
                'label' => esc_html__('Hover', 'element-camp'),
            ]
        );

        $this->add_control(
            'pages_list_hover_color',
            [
                'label' => esc_html__('Text Color', 'element-camp'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-creative-cards .pages-list a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        // Links Card Style Section
        $this->start_controls_section(
            'links_card_style',
            [
                'label' => esc_html__('Links Card', 'element-camp'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'links_card_background',
                'label' => esc_html__('Background', 'element-camp'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-creative-cards .links-card',
            ]
        );

        $this->add_responsive_control(
            'links_card_padding',
            [
                'label' => esc_html__('Padding', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-creative-cards .links-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => '60',
                    'right' => '50',
                    'bottom' => '60',
                    'left' => '100',
                    'unit' => 'px',
                ],
            ]
        );

        $this->add_responsive_control(
            'links_card_border_radius',
            [
                'label' => esc_html__( 'Image Container Border Radius', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'rem', 'custom' ],
                'default' => [
                    'top' => '30',
                    'right' => '30',
                    'bottom' => '30',
                    'left' => '30',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-creative-cards .links-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'links_card_shadow',
                'label' => esc_html__('Box Shadow', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-creative-cards .links-card',
                'fields_options' => [
                    'box_shadow_type' => [
                        'default' => 'yes',
                    ],
                    'box_shadow' => [
                        'default' => [
                            'horizontal' => 0,
                            'vertical' => 0,
                            'blur' => 100,
                            'spread' => 0,
                            'color' => 'rgba(255, 83, 46, 0.2)',
                        ],
                    ],
                ],
            ]
        );

        $this->end_controls_section();

        // Links Style Section
        $this->start_controls_section(
            'links_style',
            [
                'label' => esc_html__('Links', 'element-camp'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'links_typography',
                'label' => esc_html__('Typography', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-creative-cards .links a',
                'fields_options' => [
                    'font_size' => [
                        'default' => [
                            'unit' => 'px',
                            'size' => 20,
                        ],
                    ],
                    'font_weight' => [
                        'default' => 'bold',
                    ],
                    'text_transform' => [
                        'default' => 'capitalize',
                    ],
                    'letter_spacing' => [
                        'default' => [
                            'unit' => 'px',
                            'size' => 8,
                        ],
                    ],
                ],
            ]
        );

        $this->start_controls_tabs('links_color_tabs');

        $this->start_controls_tab(
            'links_normal_tab',
            [
                'label' => esc_html__('Normal', 'element-camp'),
            ]
        );

        $this->add_control(
            'links_color',
            [
                'label' => esc_html__('Text Color', 'element-camp'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-creative-cards .links a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'links_hover_tab',
            [
                'label' => esc_html__('Hover', 'element-camp'),
            ]
        );

        $this->add_control(
            'links_hover_color',
            [
                'label' => esc_html__('Text Color', 'element-camp'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-creative-cards .links a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'links_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-creative-cards .links a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => '10',
                    'right' => '0',
                    'bottom' => '10',
                    'left' => '0',
                    'unit' => 'px',
                ],
            ]
        );

        $this->end_controls_section();

        // Demo Images Style Section
        $this->start_controls_section(
            'demo_images_style',
            [
                'label' => esc_html__('Demo Images', 'element-camp'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'demo_images_width',
            [
                'label' => esc_html__('Width', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['%', 'px'],
                'range' => [
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 40,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-creative-cards .demos-imgs' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'demo_images_height',
            [
                'label' => esc_html__('Height', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 800,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 480,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-creative-cards .demos-imgs .img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'demo_images_background',
                'label' => esc_html__('Background', 'element-camp'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-creative-cards .demos-imgs .img',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'color' => [
                        'default' => 'rgba(153, 153, 153, 0.4)',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'demo_images_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'rem', 'custom' ],
                'default' => [
                    'top' => '15',
                    'right' => '15',
                    'bottom' => '15',
                    'left' => '15',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-creative-cards .demos-imgs .img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'demo_images_padding',
            [
                'label' => esc_html__('Padding', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-creative-cards .demos-imgs .img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => '30',
                    'right' => '30',
                    'bottom' => '30',
                    'left' => '30',
                    'unit' => 'px',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'demo_images_img',
                'label' => esc_html__('Box Shadow', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-creative-cards .demos-imgs .img img',
                'fields_options' => [
                    'box_shadow_type' => [
                        'default' => 'yes',
                    ],
                    'box_shadow' => [
                        'default' => [
                            'horizontal' => -2,
                            'vertical' => 2,
                            'blur' => 0,
                            'spread' => 0,
                            'color' => 'rgba(255, 83, 46, 1)',
                        ],
                    ],
                ],
            ]
        );

        $this->end_controls_section();

        // Demo Image Titles Style Section
        $this->start_controls_section(
            'demo_titles_style',
            [
                'label' => esc_html__('Demo Titles', 'element-camp'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'demo_titles_typography',
                'label' => esc_html__('Typography', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-creative-cards .demos-imgs .img .title',
                'fields_options' => [
                    'font_size' => [
                        'default' => [
                            'unit' => 'px',
                            'size' => 20,
                        ],
                    ],
                    'text_transform' => [
                        'default' => 'uppercase',
                    ],
                    'letter_spacing' => [
                        'default' => [
                            'unit' => 'px',
                            'size' => 10,
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'demo_titles_color',
            [
                'label' => esc_html__('Text Color', 'element-camp'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-creative-cards .demos-imgs .img .title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'demo_titles_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-creative-cards .demos-imgs .img .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '10',
                    'left' => '0',
                    'unit' => 'px',
                ],
            ]
        );

        $this->end_controls_section();

        // Layout Section
        $this->start_controls_section(
            'layout_section',
            [
                'label' => esc_html__('Layout', 'element-camp'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'perspective_value',
            [
                'label' => esc_html__('Perspective', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 500,
                        'max' => 5000,
                    ],
                ],
                'default' => [
                    'size' => 2000,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-creative-cards .perspective' => 'perspective: {{SIZE}}px;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render the widget output on the frontend.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();

        if (empty($settings['cards_list'])) {
            return;
        }
        ?>
        <div class="tcgelements-creative-cards">
            <div class="row">
                <div class="col-lg-8">
                    <div class="perspective">
                        <div class="links-card">
                            <ul class="links">
                                <?php
                                $counter = 1;
                                foreach ($settings['cards_list'] as $index => $item) :
                                    $link_key = 'link_' . $index;
                                    $this->add_link_attributes($link_key, $item['card_link']);
                                    $demo_class = 'home' . $counter;
                                    ?>
                                    <li>
                                        <a <?php echo $this->get_render_attribute_string($link_key); ?>
                                                class="link-item"
                                                data-demo="<?php echo esc_attr($demo_class); ?>">
                                            <?php echo esc_html($item['card_title']); ?>
                                        </a>
                                    </li>
                                    <?php
                                    $counter++;
                                endforeach;
                                ?>
                            </ul>
                            <?php if (!empty($settings['card_background_image']['url'])) : ?>
                                <img src="<?php echo esc_url($settings['card_background_image']['url']); ?>" alt="" class="bg">
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="demos-imgs perspective">
                    <?php
                    $counter = 1;
                    foreach ($settings['cards_list'] as $index => $item) :
                        $demo_class = 'home' . $counter;
                        $active_class = ($counter === 1) ? 'active' : '';

                        if ($item['content_type'] === 'pages') {
                            // Render Inner Pages List
                            ?>
                            <div class="img pages <?php echo esc_attr($demo_class . ' ' . $active_class); ?>">
                                <h5 class="title">
                                    <?php echo esc_html($item['card_title']); ?>
                                </h5>
                                <ul class="pages-list">
                                    <?php
                                    if (!empty($item['inner_pages'])) {
                                        foreach ($item['inner_pages'] as $page_index => $page) :
                                            $page_link_key = 'page_link_' . $index . '_' . $page_index;
                                            $this->add_link_attributes($page_link_key, $page['page_link']);
                                            ?>
                                            <li>
                                                <a <?php echo $this->get_render_attribute_string($page_link_key); ?>>
                                                    <?php echo esc_html($page['page_title']); ?>
                                                </a>
                                            </li>
                                        <?php
                                        endforeach;
                                    }
                                    ?>
                                </ul>
                            </div>
                            <?php
                        } else {
                            // Render Demo Image
                            $link_key = 'demo_link_' . $index;
                            $this->add_link_attributes($link_key, $item['card_link']);
                            ?>
                            <a <?php echo $this->get_render_attribute_string($link_key); ?>
                                    class="img <?php echo esc_attr($demo_class . ' ' . $active_class); ?>">
                                <h5 class="title">
                                    <?php echo esc_html($item['card_title']); ?>
                                </h5>
                                <?php if (!empty($item['card_image']['url'])) : ?>
                                    <img src="<?php echo esc_url($item['card_image']['url']); ?>"
                                         alt="<?php echo esc_attr($item['card_title']); ?>"
                                         class="">
                                <?php endif; ?>
                            </a>
                            <?php
                        }
                        $counter++;
                    endforeach;
                    ?>
                </div>
            </div>
        </div>
        <?php
    }
}