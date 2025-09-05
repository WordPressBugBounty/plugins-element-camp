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

        $repeater = new Repeater();

        $repeater->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'element-camp'),
                'type' => Controls_Manager::TEXTAREA,
                'placeholder' => esc_html__('Type Text Here', 'element-camp'),
            ]
        );

        $repeater->add_control(
            'description',
            [
                'label' => esc_html__('Description', 'element-camp'),
                'type' => Controls_Manager::TEXTAREA,
                'placeholder' => esc_html__('Type Text Here', 'element-camp'),
            ]
        );

        $repeater->add_responsive_control(
            'right_coordinate',
            [
                'label' => esc_html__( 'Right Coordinate', 'element-camp' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 1,
                'placeholder' => 1,
                'responsive' => true,
            ]
        );

        $repeater->add_responsive_control(
            'bottom_coordinate',
            [
                'label' => esc_html__( 'Bottom Coordinate', 'element-camp' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 1,
                'placeholder' => 1,
                'responsive' => true,
            ]
        );

        $this->add_control(
            'map_items',
            [
                'label' => esc_html__('Items', 'element-camp'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'title' => esc_html__('Item 1', 'element-camp'),
                        'description' => esc_html__('Description 1', 'element-camp'),
                        'right_coordinate' => esc_html__('61', 'element-camp'),
                        'bottom_coordinate' => esc_html__('19', 'element-camp'),
                    ],
                    [
                        'title' => esc_html__('Item 2', 'element-camp'),
                        'description' => esc_html__('Description 2', 'element-camp'),
                        'right_coordinate' => esc_html__('58', 'element-camp'),
                        'bottom_coordinate' => esc_html__('50', 'element-camp'),
                    ],
                    [
                        'title' => esc_html__('Item 3', 'element-camp'),
                        'description' => esc_html__('Description 3', 'element-camp'),
                        'right_coordinate' => esc_html__('80', 'element-camp'),
                        'bottom_coordinate' => esc_html__('65', 'element-camp'),
                    ],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__('Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'map_item_dot_background',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-map-items .map-main .map-item .dot',
                'fields_options' => [
                    'background' => [
                        'label' => esc_html_x('Dot Background Color', 'Background Control', 'themescamp-plugin'),
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
                    '{{WRAPPER}} .tcgelements-map-items .map-main .map-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .tcgelements-map-items .map-main .map-item .info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'map_item_background',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-map-items .map-main .map-item .info',
                'fields_options' => [
                    'background' => [
                        'label' => esc_html_x('Info Background Color', 'Background Control', 'themescamp-plugin'),
                    ]
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'info_after_border',
                'selector' => '{{WRAPPER}} .tcgelements-map-items .map-main .map-item::after',
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
                'selector' => '{{WRAPPER}} .tcgelements-map-items .map-main .map-item .info .title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-map-items .map-main .map-item .info .title' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-map-items .map-main .map-item .info .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .tcgelements-map-items .map-main .map-item .info .description',
            ]
        );

        $this->add_control(
            'desc_color',
            [
                'label' => esc_html__( 'Description Color', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-map-items .map-main .map-item .info .description' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .tcgelements-map-items .map-main .map-item .info .description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="tcgelements-map-items">
            <div class="map-main">
                <img src="<?=esc_url($settings['map_image']['url'])?>"  alt="<?php if (!empty($settings['map_image']['alt'])) echo esc_attr($settings['map_image']['alt']); ?>" class="img">
                <?php foreach($settings['map_items'] as $item) : ?>
                <div class="map-item" data-r="<?=esc_attr($item['right_coordinate'])?>" data-b="<?=esc_attr($item['bottom_coordinate'])?>">
                    <div class="info">
                        <h6 class="title"><?=esc_html($item['title'])?></h6>
                        <p class="description"><?=esc_html($item['description'])?></p>
                    </div>
                    <span class="dot"></span>
                </div>
                <?php endforeach;?>
            </div>
        </div>
        <?php
    }
}
