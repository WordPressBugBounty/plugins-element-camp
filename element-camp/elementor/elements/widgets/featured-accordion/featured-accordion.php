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

class ElementCamp_Featured_Accordion extends Widget_Base
{
    public function get_name()
    {
        return 'tcgelements-featured-accordion';
    }

    public function get_title()
    {
        return esc_html__('Featured Accordion', 'element-camp');
    }

    public function get_icon()
    {
        return 'eicon-accordion tce-widget-badge';
    }

    public function get_script_depends() {
        return ['bootstrap.bundle.min','tcgelements-background-image','tcgelements-featured-accordion'];
    }

    public function get_categories()
    {
        return ['elementcamp-elements'];
    }

    protected function _register_controls()
    {
        // Content Section
        $this->start_controls_section(
            'section_items',
            [
                'label' => esc_html__('Accordion Items', 'element-camp'),
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Accordion Title', 'element-camp'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'number',
            [
                'label' => esc_html__('Number', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'default' => '01',
                'label_block' => false,
            ]
        );

        $repeater->add_control(
            'image',
            [
                'label' => esc_html__('Image', 'element-camp'),
                'type' => Controls_Manager::MEDIA,
            ]
        );

        $repeater->add_control(
            'btn_icon',
            [
                'label' => esc_html__('Button Icon', 'element-camp'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'skin' => 'inline',
                'label_block' => false,
            ]
        );

        $repeater->add_control(
            'content',
            [
                'label' => esc_html__('Content', 'element-camp'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => esc_html__('Accordion content goes here...', 'element-camp'),
                'show_label' => false,
            ]
        );

        $repeater->add_control(
            'tags_list',
            [
                'label' => esc_html__('Tags', 'element-camp'),
                'type' => Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'tag_text',
                        'label' => esc_html__('Tag Text', 'element-camp'),
                        'type' => Controls_Manager::TEXT,
                        'default' => esc_html__('Tag Name', 'element-camp'),
                        'label_block' => true,
                    ],
                    [
                        'name' => 'tag_link',
                        'label' => esc_html__('Tag Link', 'element-camp'),
                        'type' => Controls_Manager::URL,
                        'placeholder' => esc_html__('https://your-link.com', 'element-camp'),
                        'show_external' => true,
                        'default' => [
                            'url' => '#',
                            'is_external' => false,
                            'nofollow' => false,
                        ],
                    ],
                ],
                'title_field' => '{{{ tag_text }}}',
                'default' => [
                    [
                        'tag_text' => esc_html__('Online Marketing', 'element-camp'),
                        'tag_link' => ['url' => '#'],
                    ],
                    [
                        'tag_text' => esc_html__('Strategy', 'element-camp'),
                        'tag_link' => ['url' => '#'],
                    ],
                    [
                        'tag_text' => esc_html__('Market Research', 'element-camp'),
                        'tag_link' => ['url' => '#'],
                    ],
                ],
            ]
        );

        $this->add_control(
            'accordion_items',
            [
                'label' => esc_html__('Accordion Items', 'element-camp'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ title }}}',
                'default' => [
                    [
                        'title' => esc_html__('Branding', 'element-camp'),
                        'number' => '01',
                        'content' => esc_html__('We measure our success by the success of our clients. With a focus on results and a dedication to quality, our mission is to deliver digital solutions.', 'element-camp'),
                        'tags_list' => [
                            [
                                'tag_text' => 'Online Marketing',
                                'tag_link' => ['url' => '#0'],
                            ],
                            [
                                'tag_text' => 'Strategy',
                                'tag_link' => ['url' => '#0'],
                            ],
                            [
                                'tag_text' => 'Market Research',
                                'tag_link' => ['url' => '#0'],
                            ],
                        ],
                    ],
                    [
                        'title' => esc_html__('Web Development', 'element-camp'),
                        'number' => '02',
                        'content' => esc_html__('Creating responsive and user-friendly websites that drive engagement and conversions for your business.', 'element-camp'),
                        'tags_list' => [
                            [
                                'tag_text' => 'Online Marketing',
                                'tag_link' => ['url' => '#0'],
                            ],
                            [
                                'tag_text' => 'Strategy',
                                'tag_link' => ['url' => '#0'],
                            ],
                            [
                                'tag_text' => 'Market Research',
                                'tag_link' => ['url' => '#0'],
                            ],
                        ],
                    ],
                    [
                        'title' => esc_html__('Digital Marketing', 'element-camp'),
                        'number' => '03',
                        'content' => esc_html__('Strategic digital marketing campaigns that boost your online presence and reach your target audience effectively.', 'element-camp'),
                        'tags_list' => [
                            [
                                'tag_text' => 'Online Marketing',
                                'tag_link' => ['url' => '#0'],
                            ],
                            [
                                'tag_text' => 'Strategy',
                                'tag_link' => ['url' => '#0'],
                            ],
                            [
                                'tag_text' => 'Market Research',
                                'tag_link' => ['url' => '#0'],
                            ],
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'active_item',
            [
                'label' => esc_html__('Active Item Number', 'element-camp'),
                'default' => 1,
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
            ]
        );

        $this->end_controls_section();

        // Layout Section
        $this->start_controls_section(
            'section_layout',
            [
                'label' => esc_html__('Layout', 'element-camp'),
            ]
        );

        $this->add_responsive_control(
            'space_between_x',
            [
                'label' => esc_html__('Space Between Columns (Horizontal)', 'themescamp-core'),
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
                    '{{WRAPPER}} .elementor-widget-container .row:not(.gx-0):not(.gx-1):not(.gx-2):not(.gx-3):not(.gx-4):not(.gx-5)' => 'margin-right: -{{SIZE}}{{UNIT}}; margin-left: -{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-widget-container .row:not(.gx-0):not(.gx-1):not(.gx-2):not(.gx-3):not(.gx-4):not(.gx-5) > *' => 'padding-right: {{SIZE}}{{UNIT}}; padding-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'number_width',
            [
                'label' => esc_html__('Number Column Width', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 25,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-featured-accordion .number-col' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_width',
            [
                'label' => esc_html__('Title Column Width', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 58.33333333,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-featured-accordion .title-col' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_width',
            [
                'label' => esc_html__('Arrow Column Width', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 16.66666667,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-featured-accordion .arrow-col' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tags_width',
            [
                'label' => esc_html__('Tags Column Width', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    '%' => [
                        'min' => 15,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 25,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-featured-accordion .tags-col' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_width',
            [
                'label' => esc_html__('Content Column Width', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    '%' => [
                        'min' => 25,
                        'max' => 85,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 75,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-featured-accordion .content-col' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_item_wrapper',
            [
                'label' => esc_html__('Item Wrapper Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'item_wrapper_margin',
            [
                'label' => esc_html__('Item Wrapper Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-featured-accordion .accordion-item-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_wrapper_padding',
            [
                'label' => esc_html__('Item Wrapper Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-featured-accordion .accordion-item-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_item_style',
            [
                'label' => esc_html__('Item Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label' => esc_html__('Item Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .accordion-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'selector' => '{{WRAPPER}} .tcgelements-featured-accordion .accordion-item',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => esc_html__('Content Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'top' => 30,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-featured-accordion .accordion-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Title Style Section
        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__('Title Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__('Typography', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-featured-accordion .accordion-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-featured-accordion .accordion-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-featured-accordion .accordion-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_style_dark_mode',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'title_color_dark_mode',
            [
                'label' => esc_html__('Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-featured-accordion .accordion-title' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-featured-accordion .accordion-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Number Style Section
        $this->start_controls_section(
            'section_number_style',
            [
                'label' => esc_html__('Number Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'number_typography',
                'label' => esc_html__('Typography', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-featured-accordion .accordion-number',
            ]
        );

        $this->add_control(
            'number_color',
            [
                'label' => esc_html__('Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-featured-accordion .accordion-number' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'number_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-featured-accordion .accordion-number' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Content Style Section
        $this->start_controls_section(
            'section_content_style',
            [
                'label' => esc_html__('Content Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'label' => esc_html__('Typography', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-featured-accordion .accordion-content',
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => esc_html__('Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-featured-accordion .accordion-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'content_opacity',
            [
                'label' => esc_html__('Opacity', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 0.7,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-featured-accordion .accordion-content' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-featured-accordion .accordion-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'content_style_dark_mode',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'content_color_dark_mode',
            [
                'label' => esc_html__('Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-featured-accordion .accordion-content' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-featured-accordion .accordion-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Tags Style Section
        $this->start_controls_section(
            'section_tags_style',
            [
                'label' => esc_html__('Tags Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tags_typography',
                'label' => esc_html__('Typography', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-featured-accordion .tags a',
            ]
        );

        $this->add_control(
            'tags_color',
            [
                'label' => esc_html__('Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-featured-accordion .tags a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tags_hover_color',
            [
                'label' => esc_html__('Hover Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-featured-accordion .tags a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tags_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-featured-accordion .tags' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tag_spacing',
            [
                'label' => esc_html__('Tag Spacing', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-featured-accordion .tags a' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->add_control(
            'tags_style_dark_mode',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'tag_color_dark_mode',
            [
                'label' => esc_html__('Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-featured-accordion .tags a' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-featured-accordion .tags a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
        // Image Style Section
        $this->start_controls_section(
            'section_image_style',
            [
                'label' => esc_html__('Image Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'image_container_heading',
            [
                'label' => esc_html__('Image Container', 'element-camp'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'image_container_display',
            [
                'label' => esc_html__('Display', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'none' => esc_html__('None', 'element-camp'),
                ],
                'default' => 'block',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-featured-accordion .accordion-item-wrapper .row .img' => 'display: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $accordion_id = 'featured-accordion-' . $this->get_id();
        $active_item = $settings['active_item'] - 1;
        ?>
        <div class="accordion tcgelements-featured-accordion" id="<?= esc_attr($accordion_id) ?>">
            <?php $itemCount = 0; foreach ($settings['accordion_items'] as $index => $item) :
                $is_active = ($index === $active_item);
                $collapse_id = 'collapse-' . $accordion_id . '-' . $index;
                $heading_id = 'heading-' . $accordion_id . '-' . $index;
                ?>
                <div class="accordion-item-wrapper">
                    <div class="row">
                        <?php if (!empty($item['image']['url'])): ?>
                            <div class="col-lg-2 bg-img" data-background="<?= esc_url($item['image']['url']); ?>" ></div>
                        <?php endif; ?>
                        <div class="col-lg-9 offset-lg-1">
                            <div class="accordion-item <?= $is_active ? 'active' : '' ?>">
                                <div class="accordion-header" id="<?= esc_attr($heading_id) ?>">
                                    <button class="accordion-button<?= !$is_active ? ' collapsed' : '' ?>" type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#<?= esc_attr($collapse_id) ?>"
                                            aria-expanded="<?= $is_active ? 'true' : 'false' ?>"
                                            aria-controls="<?= esc_attr($collapse_id) ?>">
                                    <span class="row align-items-center">
                                        <span class="number-col">
                                            <span class="accordion-number"><?= esc_html($item['number']) ?></span>
                                        </span>
                                        <span class="title-col">
                                            <span class="accordion-title text-uppercase"><?= esc_html($item['title']) ?></span>
                                        </span>
                                        <span class="arrow-col d-flex justify-content-end">
                                            <span class="btn">
                                                <?php Icons_Manager::render_icon($item['btn_icon'], ['aria-hidden' => 'true']); ?>
                                            </span>
                                        </span>
                                    </span>
                                    </button>
                                </div>
                                <div id="<?= esc_attr($collapse_id) ?>"
                                     class="accordion-collapse collapse<?= $is_active ? ' show' : '' ?>"
                                     aria-labelledby="<?= esc_attr($heading_id) ?>"
                                     data-bs-parent="#<?= esc_attr($accordion_id) ?>">
                                    <div class="row accordion-body">
                                        <?php if (!empty($item['tags_list'])):?>
                                            <div class="tags-col">
                                                <div class="tags">
                                                    <?php foreach ($item['tags_list'] as $tag):
                                                        $target = $tag['tag_link']['is_external'] ? ' target="_blank"' : '';
                                                        $nofollow = $tag['tag_link']['nofollow'] ? ' rel="nofollow"' : '';
                                                        $link_attributes = $target . $nofollow;
                                                        ?>
                                                        <a href="<?= esc_url($tag['tag_link']['url']) ?>"<?= $link_attributes ?>><?= esc_html($tag['tag_text']) ?></a>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <div class="content-col">
                                            <div class="accordion-content">
                                                <?= wp_kses_post($item['content']) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $itemCount++; endforeach; ?>
        </div>
        <?php
    }
}
?>