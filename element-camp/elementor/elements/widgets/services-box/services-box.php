<?php

namespace ElementCampPlugin\Widgets;

use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Widget_Base;
use Elementor\Repeater;
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
use Elementor\Group_Control_Css_Filter;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly



/**
 * @since 1.0.0
 */
class ElementCamp_Services_Box extends Widget_Base
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
        return 'tcgelements-services-box';
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
        return esc_html__('Services Box', 'element-camp');
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
        return 'eicon-slider-album tce-widget-badge';
    }

    public function get_script_depends()
    {
        return ['tcgelements-services-box'];
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
            'services_content_section',
            [
                'label' => __('Content', 'element-camp'),
            ]
        );

        $service_repeater = new \Elementor\Repeater();
        $service_repeater->add_control(
            'service_image_switcher',
            [
                'label' => esc_html__('Image Switcher', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
            ]
        );
        $service_repeater->add_control(
            'service_image',
            [
                'label' => esc_html__('Image', 'element-camp'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => ['service_image_switcher' => 'yes'],
            ]
        );
        $service_repeater->add_control(
            'service_title',
            [
                'label' => esc_html__('Title', 'element-camp'),
                'default' => esc_html__('Enter Title Here', 'element-camp'),
                'type' => Controls_Manager::TEXTAREA,
            ]
        );
        $service_repeater->add_control(
            'service_description',
            [
                'label' => esc_html__('Description', 'element-camp'),
                'type' => Controls_Manager::TEXTAREA,
            ]
        );
        $this->add_control(
            'tcg_services_repeater',
            [
                'label' => esc_html__('Services', 'element-camp'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $service_repeater->get_controls(),
                'default' => [
                    [
                        'service_title' => esc_html__('Enter Title Here', 'element-camp'),
                    ],
                    [
                        'service_title' => esc_html__('Enter Title Here', 'element-camp'),
                    ],
                    [
                        'service_title' => esc_html__('Enter Title Here', 'element-camp'),
                    ],
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'services_style_section',
            [
                'label' => __('Box Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'service_box_margin',
            [
                'label' => esc_html__('Service Box Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-box .item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'service_box_padding',
            [
                'label' => esc_html__('Service Box Padding', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
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
                    '{{WRAPPER}} .tcgelements-services-box .item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'service_box_display',
            [
                'label' => esc_html__('Service Box Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'block',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-box .item' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'service_box_display_position',
            [
                'label' => esc_html__('Service Box Display Position', 'element-camp'),
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
                    '{{WRAPPER}} .tcgelements-services-box .item' => '{{VALUE}}',
                ],
                'condition' => ['service_box_display' => ['flex']],
            ]
        );
        $this->add_responsive_control(
            'service_box_justify_content',
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
                    '{{WRAPPER}} .tcgelements-services-box .item' => 'justify-content: {{VALUE}};',
                ],
                'condition' => ['service_box_display' => ['flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'service_box_align_items',
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
                    '{{WRAPPER}} .tcgelements-services-box .item' => 'align-items: {{VALUE}};',
                ],
                'condition' => ['service_box_display' => ['flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'service_box_flex_wrap',
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
                    '{{WRAPPER}} .tcgelements-services-box .item' => 'flex-wrap: {{VALUE}};',
                ],
                'condition' => ['service_box_display' => ['flex']],
            ]
        );
        $this->add_responsive_control(
            'service_box_border_radius',
            [
                'label' => esc_html__('Service Box Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-box .item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs(
            'service_box_tabs',
        );
        $this->start_controls_tab(
            'service_box_normal_tab',
            [
                'label' => esc_html__('Normal', 'element-camp'),
            ]
        );
        $this->add_responsive_control(
            'service_box_width',
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
                    '{{WRAPPER}} .tcgelements-services-box .item' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'service_box_height',
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
                    '{{WRAPPER}} .tcgelements-services-box .item' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'service_box_border',
                'selector' => '{{WRAPPER}} .tcgelements-services-box .item',
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'service_box_active_tab',
            [
                'label' => esc_html__('Active', 'element-camp'),
            ]
        );
        $this->add_responsive_control(
            'service_box_width_active',
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
                    '{{WRAPPER}} .tcgelements-services-box .item.active' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'service_box_height_active',
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
                    '{{WRAPPER}} .tcgelements-services-box .item.active' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'service_box_border_active',
                'selector' => '{{WRAPPER}} .tcgelements-services-box .item.active',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control(
            'service_box_transition',
            [
                'label' => esc_html__('Service Box Transition', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-box .item' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'image_style_section',
            [
                'label' => __('Image Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'service_image_width',
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
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'custom'],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-box .item .service-image img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'service_image_max_width',
            [
                'label' => esc_html__('Max Width', 'element-camp'),
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
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'custom'],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-box .item .service-image img' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'service_image_max_height',
            [
                'label' => esc_html__('Max Height', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'vh', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 500,
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-box .item .service-image img' => 'max-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'service_image_height',
            [
                'label' => esc_html__('Height', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'vh', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 500,
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-box .item .service-image img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'service_image_object_fit',
            [
                'label' => esc_html__('Object Fit', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'service_image_height[size]!' => '',
                ],
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'fill' => esc_html__('Fill', 'element-camp'),
                    'cover' => esc_html__('Cover', 'element-camp'),
                    'contain' => esc_html__('Contain', 'element-camp'),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-box .item .service-image img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'service_image_object_position',
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
                    '{{WRAPPER}} .tcgelements-services-box .item .service-image img' => 'object-position: {{VALUE}};',
                ],
                'condition' => [
                    'service_image_height[size]!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'service_image_margin',
            [
                'label' => esc_html__('Quote Date Image Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-box .item .service-image img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'service_image_padding',
            [
                'label' => esc_html__('Quote Date Image Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-box .item .service-image img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'text_style_section',
            [
                'label' => __('Text Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'service_title_heading',
            [
                'label' => esc_html__('Service Title', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'service_title_typography',
                'label' => esc_html__('Service Title', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-services-box .item .service-title',
            ]
        );
        $this->add_control(
            'service_title_color',
            [
                'label' => esc_html__('Service Title Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-box .item .service-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'service_description_heading',
            [
                'label' => esc_html__('Service Description', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'service_description_typography',
                'label' => esc_html__('Service Description', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-services-box .item .service-description',
            ]
        );
        $this->add_control(
            'service_description_color',
            [
                'label' => esc_html__('Service Description Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-box .item .service-description' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();
    }
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="tcgelements-services-box">
            <?php
            foreach ($settings['tcg_services_repeater'] as $service):
                ?>
                <div class="item">
                    <div>
                        <?php if ($service['service_image_switcher'] === 'yes' && !empty($service['service_image']['url'])): ?>
                            <div class="service-image">
                                <img src="<?php echo esc_url($service['service_image']['url']); ?>"
                                    alt="<?php echo esc_attr($service['service_title']); ?>">
                            </div>
                        <?php endif; ?>
                        <div>
                            <?php if (!empty($service['service_title'])): ?>
                                <h5 class="service-title"><?php echo wp_kses_post($service['service_title']); ?></h5>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div>
                        <div class="text">
                            <?php if (!empty($service['service_description'])): ?>
                                <p class="service-description"><?php echo esc_html($service['service_description']); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}
