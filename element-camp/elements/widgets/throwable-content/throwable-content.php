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

class ElementCamp_Throwable_Content extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'tcgelements-throwable-content';
    }

    public function get_title()
    {
        return esc_html__('Throwable Content', 'element-camp');
    }

    public function get_icon()
    {
        return 'eicon-counter-circle tce-widget-badge';
    }

    public function get_categories()
    {
        return ['elementcamp-elements'];
    }

    public function get_script_depends() {
        return ['matter.min','tcgelements-throwable'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Settings', 'element-camp'),
            ]
        );
        $repeater = new \Elementor\Repeater();
        $repeater->add_control(
            'text',
            [
                'label' => esc_html__('Text', 'element-camp'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__('Customer Engagement', 'element-camp'),
            ]
        );
        $repeater->add_control(
            'image',
            [
                'label' => esc_html__('Choose Image', 'element-camp'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );
        $repeater->add_control(
            'separator_panel_style',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );
        $repeater->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'text_background',
                'label' => esc_html__( 'Background', 'element-camp' ),
                'types' => [ 'classic','gradient' ],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .tcgelements-throwable-content {{CURRENT_ITEM}}.item .txt',
            ]
        );
        $repeater->add_control(
            'text_color',
            [
                'label' => esc_html__('Text Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-throwable-content {{CURRENT_ITEM}}.item .txt' => 'color: {{VALUE}};',
                ],
            ]
        );
        $repeater->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'text_border',
                'label' => esc_html__( 'Text Border', 'element-camp' ),
                'selector' => '{{WRAPPER}} .tcgelements-throwable-content {{CURRENT_ITEM}}.item .txt',
            ]
        );
        $repeater->add_control(
            'separator_panel_style2',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );
        $repeater->add_responsive_control(
            'image_width',
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
                    '{{WRAPPER}} .tcgelements-throwable-content {{CURRENT_ITEM}}.item .img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $repeater->add_responsive_control(
            'image_height',
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
                    '{{WRAPPER}} .tcgelements-throwable-content {{CURRENT_ITEM}}.item .img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'items',
            [
                'label' => esc_html__('Items', 'element-camp'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{text}}}',
                'default' => [
                    [
                        'text' => esc_html__('Text #1', 'element-camp'),
                    ],
                    [
                        'text' => esc_html__('Text #2', 'element-camp'),
                    ],
                    [
                        'text' => esc_html__('Text #3', 'element-camp'),
                    ],
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_item_style',
            [
                'label' => esc_html__( 'Style', 'element-camp' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'throwable_container_overflow',
            [
                'label' => esc_html__( 'Throwable Container Overflow Hidden', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'yes', 'element-camp' ),
                'label_off' => esc_html__( 'No', 'element-camp' ),
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-throwable-content' => 'overflow: hidden;',
                ],
            ]
        );
        $this->add_responsive_control(
            'div_height',
            [
                'label' => esc_html__('Height', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                ],
                'size_units' => ['px', '%', 'vh', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-throwable-content' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'item_user_select',
            [
                'label' => esc_html__('Item User Select', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'auto' => esc_html__('Auto', 'element-camp'),
                    'none' => esc_html__('None', 'element-camp'),
                    'text' => esc_html__('Text', 'element-camp'),
                    'all' => esc_html__('All', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-throwable-content .item' => 'user-select: {{VALUE}};'
                ]
            ]
        );
        $this->add_control(
            'position_for_item',
            [
                'label' => esc_html__( 'Item Position', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'absolute' => esc_html__( 'Absolute', 'element-camp' ),
                    'relative' => esc_html__( 'Relative', 'element-camp' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-throwable-content .item' => 'position: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'item_offset_orientation_h',
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
                    'position_for_item!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'item_offset_x',
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
                    '{{WRAPPER}} .tcgelements-throwable-content .item' => 'left: {{SIZE}}{{UNIT}};right:auto',
                ],
                'condition' => [
                    'item_offset_orientation_h' => 'start',
                    'position_for_item!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'item_offset_x_end',
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
                    '{{WRAPPER}} .tcgelements-throwable-content .item' => 'right: {{SIZE}}{{UNIT}};left:auto',
                ],
                'condition' => [
                    'item_offset_orientation_h' => 'end',
                    'position_for_item!' => '',
                ],
            ]
        );
        $this->add_control(
            'item_offset_orientation_v',
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
                    'position_for_item!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'item_offset_y',
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
                    '{{WRAPPER}} .tcgelements-throwable-content .item' => 'top: {{SIZE}}{{UNIT}};bottom:auto',
                ],
                'condition' => [
                    'item_offset_orientation_v' => 'start',
                    'position_for_item!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'item_offset_y_end',
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
                    '{{WRAPPER}} .tcgelements-throwable-content .item' => 'bottom: {{SIZE}}{{UNIT}};top:auto;',
                ],
                'condition' => [
                    'item_offset_orientation_v' => 'end',
                    'position_for_item!' => '',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__( 'Style', 'element-camp' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'label' => esc_html__('Text', 'element-camp'),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_ACCENT,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-throwable-content .item .txt',
            ]
        );
        $this->add_control(
            'text_color',
            [
                'label' => esc_html__('Text Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-throwable-content .item .txt' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'text_padding',
            [
                'label' => esc_html__('Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-throwable-content .item .txt' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'text_background',
                'label' => esc_html__( 'Background', 'element-camp' ),
                'types' => [ 'classic','gradient' ],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .tcgelements-throwable-content .item .txt',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'text_border',
                'label' => esc_html__( 'Text Border', 'element-camp' ),
                'selector' => '{{WRAPPER}} .tcgelements-throwable-content .item .txt',
            ]
        );
        $this->add_responsive_control(
            'text_border_radius',
            [
                'label' => esc_html__( 'Text Border Radius', 'element-camp' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-throwable-content .item .txt' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
    }
    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="tcgelements-throwable-content" data-tp-throwable-scene="true">
            <?php foreach($settings['items'] as $item) : ?>
                <div class="item <?php echo 'elementor-repeater-item-' . esc_attr( $item['_id'] ) . ''; ?>" data-tp-throwable-el="">
                    <div class="">
                        <?php if (!empty($item['text'])) : ?>
                            <span class="txt"> <?=esc_html($item['text'])?> </span>
                        <?php endif;?>
                        <?php if (!empty($item['image']['url'])) : ?>
                            <img class="img" src="<?= esc_url($item['image']['url']); ?>" alt="<?php if (!empty($item['image']['alt'])) echo esc_attr($item['image']['alt']); ?>" >
                        <?php endif;?>
                    </div>
                </div>
            <?php endforeach;?>
        </div>
        <?php
    }

}
