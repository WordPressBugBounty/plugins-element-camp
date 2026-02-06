<?php
namespace ElementCampPlugin\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Utils;
use Elementor\Core\Schemes\Typography;
use Elementor\Icons_Manager;

if (!defined('ABSPATH')) exit;

class ElementCamp_Process_Card extends Widget_Base
{
    public function get_name()
    {
        return 'tcgelements-process-card';
    }

    public function get_title()
    {
        return esc_html__('Process Card', 'elementcamp_plg');
    }

    public function get_icon()
    {
        return 'eicon-menu-card tce-widget-badge';
    }

    public function get_script_depends()
    {
        return ['tcgelements-process-card'];
    }

    public function get_categories()
    {
        return ['elementcamp-elements'];
    }

    protected function _register_controls()
    {
        // Content Section
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Content', 'elementcamp_plg'),
            ]
        );

        $this->add_control(
            'number',
            [
                'label' => esc_html__('Number', 'elementcamp_plg'),
                'default' => esc_html__('01', 'elementcamp_plg'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'elementcamp_plg'),
                'default' => esc_html__('Examine Brief', 'elementcamp_plg'),
                'description' => esc_html__( 'You can use <small></small> to set different style', 'element-camp' ),
                'type' => Controls_Manager::TEXTAREA,
            ]
        );

        $this->add_control(
            'text',
            [
                'label' => esc_html__('Text', 'elementcamp_plg'),
                'default' => esc_html__('Examine and research the Brief. Define the problem and solutions', 'elementcamp_plg'),
                'type' => Controls_Manager::TEXTAREA,
            ]
        );

        $this->add_control(
            'float_text',
            [
                'label' => esc_html__('Float Text', 'elementcamp_plg'),
                'default' => esc_html__('Brainstorming', 'elementcamp_plg'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $this->end_controls_section();

        // Number Styling
        $this->start_controls_section(
            'section_number_style',
            [
                'label' => esc_html__('Number Style', 'elementcamp_plg'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'number_typography',
                'label' => esc_html__('Typography', 'elementcamp_plg'),
                'selector' => '{{WRAPPER}} .tcgelements-process-card .num .txt',
            ]
        );

        $this->add_control(
            'number_color',
            [
                'label' => esc_html__('Color', 'elementcamp_plg'),
                'type' => Controls_Manager::COLOR,
                'default' => '#999',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-process-card .num .txt' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'number_margin',
            [
                'label' => esc_html__('Margin', 'elementcamp_plg'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 5,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-process-card .num .txt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Line Styling
        $this->start_controls_section(
            'section_line_style',
            [
                'label' => esc_html__('Line Style', 'elementcamp_plg'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'line_color',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-process-card .num::after',
            ]
        );

        $this->add_responsive_control(
            'line_width',
            [
                'label' => esc_html__('Line Width', 'elementcamp_plg'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 10,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-process-card .num::after' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'line_height',
            [
                'label' => esc_html__('Line Height', 'elementcamp_plg'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 800,
                        'step' => 10,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 400,
                ],
                'tablet_default' => [
                    'unit' => 'px',
                    'size' => 200,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-process-card .num::after' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Card Styling
        $this->start_controls_section(
            'section_card_info_style',
            [
                'label' => esc_html__('Info Card Style', 'elementcamp_plg'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'card_info_style',
            [
                'label' => esc_html__('Info Style', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'card_info_border',
                'label' => esc_html__('Border', 'elementcamp_plg'),
                'selector' => '{{WRAPPER}} .tcgelements-process-card .cont .info',
            ]
        );

        $this->add_responsive_control(
            'card_info_border_radius',
            [
                'label' => esc_html__('Info Border Radius', 'elementcamp_plg'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 15,
                    'right' => 15,
                    'bottom' => 15,
                    'left' => 15,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-process-card .cont .info' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_info_padding',
            [
                'label' => esc_html__('Info Padding', 'elementcamp_plg'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'top' => 20,
                    'right' => 20,
                    'bottom' => 20,
                    'left' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-process-card .cont .info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_info_card');

        $this->start_controls_tab(
            'info_card_normal_tab',
            [
                'label' => esc_html__('Normal', 'element-camp'),
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'card_info_background',
                'label' => esc_html__('Background', 'elementcamp_plg'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-process-card .cont .info',
                'fields_options' => [
                    'background' => [
                        'label' => esc_html_x('Info Card Background', 'Background Control', 'element-camp'),
                        'default' => 'classic',
                    ],
                    'color' => [
                        'default' => '#f3f3f3',
                    ],
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'info_card_hover_tab',
            [
                'label' => esc_html__('Hover', 'element-camp'),
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'card_info_hover_background',
                'label' => esc_html__('Hover Background', 'elementcamp_plg'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-process-card:hover .cont .info',
                'fields_options' => [
                    'background' => [
                        'label' => esc_html_x('Info Card Background', 'Background Control', 'element-camp'),
                    ]
                ]
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'content_style',
            [
                'label' => esc_html__('Content Style', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'card_content_padding',
            [
                'label' => esc_html__('Content Padding', 'elementcamp_plg'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'top' => 0,
                    'right' => 5,
                    'bottom' => 0,
                    'left' => 5,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-process-card .cont' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_content_margin',
            [
                'label' => esc_html__('Content Margin', 'elementcamp_plg'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-process-card .cont' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Title Styling
        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__('Title Style', 'elementcamp_plg'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__('Typography', 'elementcamp_plg'),
                'selector' => '{{WRAPPER}} .tcgelements-process-card .cont .info .title',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Margin', 'elementcamp_plg'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-process-card .cont .info .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_title');
        $this->start_controls_tab(
            'title_normal_tab',
            [
                'label' => esc_html__('Normal', 'element-camp'),
            ]
        );
        $this->add_responsive_control(
            'title_color',
            [
                'label' => esc_html__('Color', 'elementcamp_plg'),
                'type' => Controls_Manager::COLOR,
                'default' => '#888',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-process-card .cont .info .title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'title_hover_tab',
            [
                'label' => esc_html__('Hover', 'element-camp'),
            ]
        );
        $this->add_control(
            'card_hover_title_color',
            [
                'label' => esc_html__('Color', 'elementcamp_plg'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-process-card:hover .cont .info .title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'small_style',
            [
                'label' => esc_html__( 'Small Tag Style', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'before'
            ]
        );
        $this->add_control(
            'small_color',
            [
                'label' => esc_html__( 'Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-process-card .cont .info .title small' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'small_typography',
                'selector' => '{{WRAPPER}} .tcgelements-process-card .cont .info .title small',
            ]
        );
        $this->add_responsive_control(
            'small_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em' ,'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-process-card .cont .info .title small' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();

        // Text Styling
        $this->start_controls_section(
            'section_text_style',
            [
                'label' => esc_html__('Text Style', 'elementcamp_plg'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'label' => esc_html__('Typography', 'elementcamp_plg'),
                'selector' => '{{WRAPPER}} .tcgelements-process-card .cont .info .text',
            ]
        );

        $this->add_responsive_control(
            'text_margin',
            [
                'label' => esc_html__('Margin', 'elementcamp_plg'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'top' => 10,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-process-card .cont .info .text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'text_padding',
            [
                'label' => esc_html__('Padding', 'elementcamp_plg'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'top' => 10,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-process-card .cont .info .text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'text_border',
                'label' => esc_html__('Border', 'elementcamp_plg'),
                'selector' => '{{WRAPPER}} .tcgelements-process-card .cont .info .text',
            ]
        );


        $this->start_controls_tabs('tabs_text');
        $this->start_controls_tab(
            'text_normal_tab',
            [
                'label' => esc_html__('Normal', 'element-camp'),
            ]
        );
        $this->add_responsive_control(
            'text_color',
            [
                'label' => esc_html__('Color', 'elementcamp_plg'),
                'type' => Controls_Manager::COLOR,
                'default' => '#888',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-process-card .cont .info .text' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'text_hover_tab',
            [
                'label' => esc_html__('Hover', 'element-camp'),
            ]
        );
        $this->add_control(
            'card_hover_text_color',
            [
                'label' => esc_html__('Color', 'elementcamp_plg'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-process-card:hover .cont .info .text' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        // Float Text Styling
        $this->start_controls_section(
            'section_float_text_style',
            [
                'label' => esc_html__('Float Text Style', 'elementcamp_plg'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'float_text_typography',
                'label' => esc_html__('Typography', 'elementcamp_plg'),
                'selector' => '{{WRAPPER}} .tcgelements-process-card .float-txt',
                'fields_options' => [
                    'font_size' => [
                        'default' => [
                            'unit' => 'px',
                            'size' => 12,
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'float_text_color',
            [
                'label' => esc_html__('Color', 'elementcamp_plg'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-process-card .float-txt' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'float_text_background',
                'label' => esc_html__('Background', 'elementcamp_plg'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-process-card .float-txt',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'color' => [
                        'default' => '#007cba',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'float_text_padding',
            [
                'label' => esc_html__('Padding', 'elementcamp_plg'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'top' => 5,
                    'right' => 15,
                    'bottom' => 5,
                    'left' => 15,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-process-card .float-txt' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'float_text_border_radius',
            [
                'label' => esc_html__('Border Radius', 'elementcamp_plg'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 30,
                    'right' => 30,
                    'bottom' => 30,
                    'left' => 30,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-process-card .float-txt' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="tcgelements-process-card">
            <div class="num">
                <span class="txt"><?php echo esc_html($settings['number']); ?></span>
            </div>
            <div class="cont">
                <div class="info">
                    <h6 class="title"><?php echo wp_kses_post($settings['title']); ?></h6>
                    <div class="text"><?php echo wp_kses_post($settings['text']); ?></div>
                </div>
            </div>
            <div class="float-txt"><?php echo esc_html($settings['float_text']); ?></div>
        </div>
        <?php
    }
}