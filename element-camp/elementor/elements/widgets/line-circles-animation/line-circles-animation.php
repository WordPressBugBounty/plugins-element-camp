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
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

if (!defined('ABSPATH')) exit;

class ElementCamp_Line_Circles_Animation extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'tcgelements-line-circles-animation';
    }

    public function get_title()
    {
        return esc_html__('Line Circles Animation', 'element-camp');
    }

    public function get_icon()
    {
        return 'eicon-circle tce-widget-badge';
    }

    public function get_categories()
    {
        return ['elementcamp-elements'];
    }
    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Settings', 'element-camp'),
            ]
        );
        $this->add_control(
            'line_circle_animation_direction',
            [
                'label' => esc_html__( 'Animation Direction', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'horizontal',
                'options' => [
                    'horizontal' => esc_html__( 'Horizontal', 'element-camp' ),
                    'vertical' => esc_html__( 'Vertical', 'element-camp' ),
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'line_circles_animation_style',
            [
                'label' => esc_html__('Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'line_style_options',
            [
                'label' => esc_html__('Line Style Options', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'line_background_color',
                'selector' => '{{WRAPPER}} .tcgelements-line-circles-animation .line',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
            ]
        );
        $this->add_control(
            'separator_line_style',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );
        $this->add_control(
            'circle1_style_options',
            [
                'label' => esc_html__('Circle1 Style Options', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'circle1_background_color',
                'selector' => '{{WRAPPER}} .tcgelements-line-circles-animation .circle1',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'circle1_border',
                'selector' => '{{WRAPPER}} .tcgelements-line-circles-animation .circle1',
            ]
        );
        $this->add_control(
            'separator_circle1_style',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );
        $this->add_control(
            'circle2_style_options',
            [
                'label' => esc_html__('Circle2 Style Options', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'circle2_background_color',
                'selector' => '{{WRAPPER}} .tcgelements-line-circles-animation .circle2',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'circle2_border',
                'selector' => '{{WRAPPER}} .tcgelements-line-circles-animation .circle2',
            ]
        );
        $this->end_controls_section();
    }
    protected function render() {
        $settings = $this->get_settings_for_display();
        $class = '';
        $direction = $settings['line_circle_animation_direction'];
        $class .= $direction;
        ?>
        <div class="tcgelements-line-circles-animation <?=esc_attr($class)?>">
            <span class="line"></span>
            <span class="circle1"></span>
            <span class="circle2"></span>
        </div>
        <?php
    }

}
