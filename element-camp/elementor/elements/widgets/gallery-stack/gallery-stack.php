<?php

namespace ElementCampPlugin\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

if (!defined('ABSPATH')) exit;

class ElementCamp_Gallery_Stack extends Widget_Base
{
    public function get_name()
    {
        return 'tcgelements-gallery-stack';
    }

    public function get_title()
    {
        return esc_html__('Gallery Stack', 'element-camp');
    }

    public function get_icon()
    {
        return 'eicon-gallery-grid';
    }

    public function get_categories()
    {
        return ['elementcamp-elements'];
    }

    public function get_script_depends() {
        return ['gsap', 'flip.min','tcgelements-background-image' ,'tcgelements-gallery-stack'];
    }

    protected function register_controls() {

        // Images Section
        $this->start_controls_section(
            'section_images',
            [
                'label' => esc_html__('Images', 'element-camp'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'image',
            [
                'label' => esc_html__('Choose Image', 'element-camp'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'image_link',
            [
                'label' => esc_html__('Link', 'element-camp'),
                'type' => Controls_Manager::URL,
                'placeholder' => 'https://your-link.com',
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'images',
            [
                'label' => esc_html__('Images', 'element-camp'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'image' => ['url' => Utils::get_placeholder_image_src()],
                    ],
                    [
                        'image' => ['url' => Utils::get_placeholder_image_src()],
                    ],
                    [
                        'image' => ['url' => Utils::get_placeholder_image_src()],
                    ],
                    [
                        'image' => ['url' => Utils::get_placeholder_image_src()],
                    ],
                    [
                        'image' => ['url' => Utils::get_placeholder_image_src()],
                    ],
                    [
                        'image' => ['url' => Utils::get_placeholder_image_src()],
                    ],
                ],
                'title_field' => '<# print("Image") #>',
            ]
        );

        $this->end_controls_section();

        // Content Section
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Content', 'element-camp'),
            ]
        );

        $this->add_control(
            'caption_text',
            [
                'label' => esc_html__('Caption Text', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Our Team', 'element-camp'),
                'placeholder' => esc_html__('Enter caption text', 'element-camp'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'stack_style',
            [
                'label' => esc_html__('Stack Style', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => esc_html__('Default', 'element-camp'),
                    'dark' => esc_html__('Dark', 'element-camp'),
                    'glass' => esc_html__('Glass', 'element-camp'),
                    'scale' => esc_html__('Scale', 'element-camp'),
                ],
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

        $this->add_control(
            'item_width',
            [
                'label' => esc_html__('Item Width', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['vw', 'px', '%'],
                'range' => [
                    'vw' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'vw',
                    'size' => 25,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-gallery-stack .gallery--stack .gallery__item' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'item_height',
            [
                'label' => esc_html__('Item Height', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 500,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-gallery-stack .gallery__item' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'gap',
            [
                'label' => esc_html__('Gap', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'default' => [
                    'unit' => 'rem',
                    'size' => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-gallery-stack .gallery--stack' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'offset',
            [
                'label' => esc_html__('Stack Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'default' => [
                    'unit' => 'rem',
                    'size' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--offset: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pin_spacing',
            [
                'label' => esc_html__('Pin Spacing (Scroll Distance)', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['vh'],
                'range' => [
                    'vh' => [
                        'min' => 100,
                        'max' => 500,
                    ],
                ],
                'default' => [
                    'unit' => 'vh',
                    'size' => 200,
                ],
            ]
        );

        $this->end_controls_section();

        // Style: Gallery
        $this->start_controls_section(
            'section_style_gallery',
            [
                'label' => esc_html__('Gallery', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'gallery_padding',
            [
                'label' => esc_html__('Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-gallery-stack .gallery--stack' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style: Caption
        $this->start_controls_section(
            'section_style_caption',
            [
                'label' => esc_html__('Caption', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'caption_color',
            [
                'label' => esc_html__('Text Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-gallery-stack .caption .title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'caption_typography',
                'selector' => '{{WRAPPER}} .tcgelements-gallery-stack .caption .title',
            ]
        );

        $this->add_control(
            'caption_padding',
            [
                'label' => esc_html__('Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'rem', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-gallery-stack .caption' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style: Images
        $this->start_controls_section(
            'section_style_images',
            [
                'label' => esc_html__('Images', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'image_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-gallery-stack .gallery__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_box_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-gallery-stack .gallery__item',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $unique_id = 'gallery-' . $this->get_id();
        $stack_style_class = $settings['stack_style'] !== 'default' ? ' gallery--stack-' . $settings['stack_style'] : '';
        $pin_spacing = !empty($settings['pin_spacing']['size']) ? $settings['pin_spacing']['size'] : 200;

        if (empty($settings['images'])) {
            return;
        }
        ?>
        <div class="tcgelements-gallery-stack" data-pin-spacing="<?php echo esc_attr($pin_spacing); ?>">
            <div class="tcgelements-gallery-stack-gallery gallery-wrap gallery-wrap--dense">
                <div class="gallery gallery--stack<?php echo esc_attr($stack_style_class); ?>" id="<?php echo esc_attr($unique_id); ?>" data-widget-id="<?php echo esc_attr($this->get_id()); ?>">
                    <?php foreach ($settings['images'] as $index => $item):
                        $image_url = $item['image']['url'];
                        $link_attr = '';

                        if (!empty($item['image_link']['url'])) {
                            $this->add_link_attributes('image_link_' . $index, $item['image_link']);
                            $link_attr = $this->get_render_attribute_string('image_link_' . $index);
                        }
                        ?>
                        <div class="gallery__item bg-img" data-background="<?= esc_url($image_url); ?>">
                            <?php if ($link_attr): ?>
                                <a <?php echo $link_attr; ?>></a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>

                    <?php if (!empty($settings['caption_text'])): ?>
                        <div class="caption">
                            <h2 class="title"><?php echo esc_html($settings['caption_text']); ?></h2>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }
}