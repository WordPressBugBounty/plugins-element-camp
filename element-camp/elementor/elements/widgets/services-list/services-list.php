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
use Elementor\Group_Control_Css_Filter;

if (!defined('ABSPATH')) exit;

class ElementCamp_Services_List extends Widget_Base
{
    public function get_name()
    {
        return 'tcgelements-services-list';
    }

    public function get_title()
    {
        return esc_html__('Services List', 'element-camp');
    }

    public function get_icon()
    {
        return 'eicon-accordion tce-widget-badge';
    }
    public function get_script_depends() {
        return ['tcgelements-services-list'];
    }

    public function get_categories()
    {
        return ['elementcamp-elements'];
    }
    protected function _register_controls()
    {
        $this->start_controls_section(
            'section_items',
            [
                'label' => esc_html__('Accordion Items', 'element-camp'),
            ]
        );
        $repeater = new \Elementor\Repeater();
        $repeater->add_control(
            'item_count',
            [
                'label' =>esc_html__('Item Count', 'element-camp'),
                'type' => Controls_Manager::TEXT,
            ]
        );
        $repeater->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'element-camp'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => esc_html__('How can i make an order from themescamp?', 'element-camp'),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'content',
            [
                'label' => esc_html__('Content', 'element-camp'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => '<p>' . esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'element-camp' ) . '</p>',
            ]
        );
        $repeater->add_control(
            'btn_icon',
            [
                'label' => esc_html__('Icon', 'element-camp'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'skin' => 'inline',
                'label_block' => false,
            ]
        );
        $repeater->add_control(
            'btn_link',
            [
                'label' => esc_html__('Button Link', 'element-camp'),
                'type' => Controls_Manager::URL,
                'placeholder' => 'Leave Link here',
            ]
        );
        $this->add_control(
            'list_items',
            [
                'label' => esc_html__('Items', 'element-camp'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ title }}}',
                'default' => [
                    [
                        'title' => esc_html__('Accordion #1', 'element-camp'),
                        'content' => esc_html__('Accordion Content #1', 'element-camp'),
                    ],
                    [
                        'title' => esc_html__('Accordion #2', 'element-camp'),
                        'content' => esc_html__('Accordion Content #2', 'element-camp'),
                    ],
                    [
                        'title' => esc_html__('Accordion #3', 'element-camp'),
                        'content' => esc_html__('Accordion Content #3', 'element-camp'),
                    ],
                ],
            ]
        );
        $this->add_control(
            'active_item',
            [
                'label' => esc_html__('Active Item Number', 'element-camp'),
                'default'=>1,
                'type' => \Elementor\Controls_Manager::NUMBER,
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'style_item_section',
            [
                'label' => esc_html__( 'Item Style', 'element-camp' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'item_overlay_background_active',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-services-list .item:after',
                'fields_options' => [
                    'background' => [
                        'label' => esc_html_x('Item Background Overlay Active', 'Background Control', 'element-camp'),
                    ]
                ]
            ]
        );
        $this->add_control(
            'count_style_options',
            [
                'label' => esc_html__( 'Count Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'before'
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'count_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-services-list .item .count',
            ]
        );
        $this->start_controls_tabs(
            'count_tabs',
        );

        $this->start_controls_tab(
            'count_normal_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_control(
            'count_color',
            [
                'label' => esc_html__( 'Count Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-list .item .count' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'count_active_tab',
            [
                'label'   => esc_html__( 'Active', 'element-camp' ),
            ]
        );
        $this->add_control(
            'count_color_active',
            [
                'label' => esc_html__( 'Count Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-list .item.active .count' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->add_control(
            'title_style_options',
            [
                'label' => esc_html__( 'Title Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'before'
            ]
        );
        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Title Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-list .item .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_padding',
            [
                'label' => esc_html__('Title Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-list .item .title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-services-list .item .title',
            ]
        );
        $this->start_controls_tabs(
            'title_tabs',
        );
        
        $this->start_controls_tab(
            'title_normal_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-list .item .title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'title_active_tab',
            [
                'label'   => esc_html__( 'Active', 'element-camp' ),
            ]
        );
        $this->add_control(
            'title_color_active',
            [
                'label' => esc_html__( 'Title Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-list .item.active .title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        $this->add_control(
            'content_style_options',
            [
                'label' => esc_html__( 'Content Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'before'
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-services-list .item .text',
            ]
        );
        $this->start_controls_tabs(
            'content_tabs',
        );

        $this->start_controls_tab(
            'content_normal_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_control(
            'content_color',
            [
                'label' => esc_html__( 'Content Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-list .item .text' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'content_active_tab',
            [
                'label'   => esc_html__( 'Active', 'element-camp' ),
            ]
        );
        $this->add_control(
            'content_color_active',
            [
                'label' => esc_html__( 'Content Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-services-list .item.active .text' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $itemCount = 1;
        ?>
        <div class="tcgelements-services-list">
            <?php foreach ($settings['list_items'] as $item) : ?>
                <div class="item <?php if ($settings['active_item'] == $itemCount) echo esc_attr('active') ?>">
                    <div class="row d-flex align-items-center">
                        <?php if ($itemCount % 2 == 0) : ?>
                            <!-- Layout for even items -->
                            <div class="col-lg-6">
                                <div class="row d-flex align-items-center">
                                    <div class="col-md-4">
                                        <span class="count"><?= $item['item_count']; ?></span>
                                    </div>
                                    <div class="col-md-8">
                                        <h2 class="title"><?php echo esc_html($item['title']); ?></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="row d-flex align-items-center">
                                    <div class="col-md-8">
                                        <div class="text"><?php echo wp_kses_post($item['content']); ?></div>
                                    </div>
                                    <div class="col-md-4 d-flex">
                                        <a href="<?php echo esc_url($item['btn_link']['url']); ?>" class="butn"  <?php if ( $item['btn_link']['is_external'] ) echo'target="_blank"'; ?>>
                                        <span class="icon">
                                            <?php \Elementor\Icons_Manager::render_icon( $item['btn_icon'], [ 'aria-hidden' => 'true' ] );?>
                                        </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php else : ?>
                            <!-- Layout for odd items -->
                            <div class="col-lg-6">
                                <div class="row d-flex align-items-center">
                                    <div class="col-md-4">
                                        <span class="count"><?= esc_html($item['item_count']); ?></span>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="text"><?= wp_kses_post($item['content']); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="row d-flex align-items-center">
                                    <div class="col-md-8">
                                        <h2 class="title"><?= esc_html($item['title']); ?></h2>
                                    </div>
                                    <div class="col-md-4 d-flex">
                                        <a href="<?= esc_url($item['btn_link']['url']); ?>" class="butn" <?php if ( $item['btn_link']['is_external'] ) echo'target="_blank"'; ?>>
                                        <span class="icon">
                                            <?php \Elementor\Icons_Manager::render_icon( $item['btn_icon'], [ 'aria-hidden' => 'true' ] );?>
                                        </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php $itemCount++; endforeach; ?>
        </div>
        <?php
    }
}
