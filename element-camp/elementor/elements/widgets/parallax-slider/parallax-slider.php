<?php

namespace ElementCampPlugin\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use ElementCampPlugin\Elementor\Controls\TCG_Helper as ControlsHelper;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class ElementCamp_Parallax_Slider extends Widget_Base
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
        return 'tcg-parallax-slider';
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
        return __('Parallax Slider', 'element-camp');
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
        return 'eicon-posts-ticker tce-widget-badge';
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
     * Retrieve the list of scripts the widget depended on.
     *
     * Used to set scripts dependencies required to run the widget.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return array Widget scripts dependencies.
     */
    public function get_script_depends()
    {
        return ['tcgelements-parallax-slider'];
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
        $start = is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');
        $end = !is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');

        $this->start_controls_section(
            'slides_section',
            [
                'label' => __('slides', 'element-camp'),
            ]
        );

        $this->add_control(
            'tcg_select_type',
            [
                'label' => esc_html__('Select Slides Type', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'blocks',
                'options' => [
                    'blocks' => esc_html__('Blocks', 'element-camp'),
                    'query'  => esc_html__('Query', 'element-camp'),
                ],
            ]
        );

        $this->add_control(
            'tcg_query_select_block',
            [
                'label' => esc_html__('Select Query Block', 'element-camp'),
                'type' => 'tcg-select2',
                'multiple' => false,
                'label_block' => true,
                'source_name' => 'block',
                'source_type' => 'tcg_teb',
                'meta_query' => [
                    [
                        'key' => 'template_type',
                        'value' => 'block',
                        'compare' => '='
                    ]
                ],
                'condition' => [
                    'tcg_select_type' => 'query'
                ]
            ]
        );

        $slides_repeater = new \Elementor\Repeater();

        $slides_repeater->add_control(
            'tcg_select_block',
            [
                'label' => esc_html__('Select Block', 'element-camp'),
                'type' => 'tcg-select2',
                'multiple' => false,
                'label_block' => true,
                'source_name' => 'block',
                'source_type' => 'tcg_teb',
                'meta_query' => [
                    [
                        'key' => 'template_type',
                        'value' => 'block',
                        'compare' => '='
                    ]
                ],
            ]
        );

        $slides_repeater->add_control(
            'edit_slider',
            [
                'label' => esc_html__('Edit Slide', 'element-camp'),
                'type' => \Elementor\Controls_Manager::BUTTON,
                'separator' => 'before',
                'button_type' => 'success',
                'text' => esc_html__('Edit', 'element-camp'),
                'event' => 'tcgDynamicSlideEditor',
                'condition' => [
                    'tcg_select_block!' => '',
                ]
            ]
        );

        $this->add_control(
            'tcg_dynamic_slides_repeater',
            [
                'label' => esc_html__('Slides', 'element-camp'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $slides_repeater->get_controls(),
                'default' => [
                    [
                        'project_title' => esc_html__('Hayden Co. Market Data Analysis', 'element-camp'),
                    ],
                    [
                        'project_title' => esc_html__('Hayden Co. Market Data Analysis', 'element-camp'),
                    ],
                    [
                        'project_title' => esc_html__('Hayden Co. Market Data Analysis', 'element-camp'),
                    ],
                ],
                'condition' => [
                    'tcg_select_type' => 'blocks'
                ]
            ]
        );

        $this->add_control(
            'add_slider',
            [
                'label' => esc_html__('Add Slide', 'element-camp'),
                'type' => \Elementor\Controls_Manager::BUTTON,
                'separator' => 'before',
                'button_type' => 'success',
                'text' => esc_html__('Add Slide', 'element-camp'),
                'event' => 'tcgAddDynamicBlock',
            ]
        );

        $post_types = ControlsHelper::get_post_types();
        $post_types['by_id'] = __('Manual Selection', 'element-camp');

        $taxonomies = get_taxonomies([], 'objects');

        $this->add_control(
            'post_type',
            [
                'label' => __('Source', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => $post_types,
                'default' => key($post_types),
                'condition' => [
                    'tcg_select_type' => 'query'
                ]
            ]
        );

        $this->add_control(
            'tcg_global_dynamic_source_warning_text',
            [
                'type' => \Elementor\Controls_Manager::ALERT,
                'alert_type' => 'warning',
                'heading' => esc_html__('Warning', 'themescamp-core'),
                'content' => esc_html__('This option will only affect in <strong>Archive page of Elementor Theme Builder</strong> dynamically.', 'themescamp-core'),
                'condition' => [
                    'post_type' => 'source_dynamic',
                    'tcg_select_type' => 'query'
                ]
            ]
        );

        $this->add_control(
            'posts_ids',
            [
                'label' => __('Search & Select', 'element-camp'),
                'type' => 'tcg-select2',
                'options' => ControlsHelper::get_post_list(),
                'label_block' => true,
                'multiple'    => true,
                'source_name' => 'post_type',
                'source_type' => 'any',
                'condition' => [
                    'post_type' => 'by_id',
                    'tcg_select_type' => 'query'
                ],
            ]
        );

        $this->add_control(
            'authors',
            [
                'label' => __('Author', 'element-camp'),
                'label_block' => true,
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'default' => [],
                'options' => ControlsHelper::get_authors_list(),
                'condition' => [
                    'post_type!' => ['by_id', 'source_dynamic'],
                    'tcg_select_type' => 'query'
                ],
            ]
        );

        foreach ($taxonomies as $taxonomy => $object) {
            if (!isset($object->object_type[0]) || !in_array($object->object_type[0], array_keys($post_types))) {
                continue;
            }

            $this->add_control(
                $taxonomy . '_ids',
                [
                    'label' => $object->label,
                    'type' => 'tcg-select2',
                    'label_block' => true,
                    'multiple' => true,
                    'source_name' => 'taxonomy',
                    'source_type' => $taxonomy,
                    'condition' => [
                        'post_type' => $object->object_type,
                        'tcg_select_type' => 'query'
                    ],
                ]
            );
        }

        $this->add_control(
            'post__not_in',
            [
                'label'       => __('Exclude', 'element-camp'),
                'type'        => 'tcg-select2',
                'label_block' => true,
                'multiple'    => true,
                'source_name' => 'post_type',
                'source_type' => 'any',
                'condition'   => [
                    'post_type!' => ['by_id', 'source_dynamic'],
                    'tcg_select_type' => 'query'
                ],
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => __('Posts Per Page', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'default' => '4',
                'min' => '1',
                'condition' => [
                    'tcg_select_type' => 'query'
                ]
            ]
        );

        $this->add_control(
            'offset',
            [
                'label' => __('Offset', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'default' => '0',
                'condition' => [
                    'orderby!' => 'rand',
                    'tcg_select_type' => 'query'
                ]
            ]
        );

        $this->add_control(
            'posts_per_slide',
            [
                'label' => esc_html__('Posts Per Slide', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '1',
                'options' => [
                    '1' => esc_html__('1 Post', 'element-camp'),
                    '2' => esc_html__('2 Posts', 'element-camp'),
                ],
                'condition' => [
                    'tcg_select_type' => 'query',
                ],
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label' => __('Order By', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => ControlsHelper::get_post_orderby_options(),
                'default' => 'date',
                'condition' => [
                    'tcg_select_type' => 'query'
                ]
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => __('Order', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'asc' => 'Ascending',
                    'desc' => 'Descending',
                ],
                'default' => 'desc',
                'condition' => [
                    'tcg_select_type' => 'query'
                ]

            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'slider_container_style',
            [
                'label' => __('Slider Container Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'slider_container_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcg-parallax-slider .parallax-sliders .parallax-slider-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_container_height',
            [
                'label' => esc_html__('Slider Container Height', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem', 'custom'],
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
                'default' => [
                    'unit' => 'px',
                    'size' => '20000',
                ],
                'selectors' => [
                    'body' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_height',
            [
                'label' => esc_html__('Slider Height', 'element-camp'),
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
                    '{{WRAPPER}} .tcg-parallax-slider .parallax-sliders .parallax-slider-inner' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_container_gap',
            [
                'label' => esc_html__('Space Between', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'vw'],
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                    '%' => [
                        'max' => 10,
                        'step' => 0.1,
                    ],
                    'vw' => [
                        'max' => 10,
                        'step' => 0.1,
                    ],
                    'em' => [
                        'max' => 10,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcg-parallax-slider .parallax-sliders .parallax-slider-inner' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'slider_container_offset_orientation_h',
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
            'slider_container_offset_x',
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
                    'body:not(.rtl) {{WRAPPER}} .tcg-parallax-slider .parallax-sliders .parallax-slider-inner' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcg-parallax-slider .parallax-sliders .parallax-slider-inner' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'slider_container_offset_orientation_h!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_container_offset_x_end',
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
                    'body:not(.rtl) {{WRAPPER}} .tcg-parallax-slider .parallax-sliders .parallax-slider-inner' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcg-parallax-slider .parallax-sliders .parallax-slider-inner' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'slider_container_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();


        $max_slider_id = uniqid('tcg-parallax-slider-');

        $query_settings = $this->get_settings();
        $query_settings = ControlsHelper::fix_old_query($query_settings);
        $args = ControlsHelper::get_query_args($query_settings);
        $args = ControlsHelper::get_dynamic_args($query_settings, $args);
        $query = new \WP_Query($args);

        $wrapper_classes = 'tcg-parallax-slider';

        ?>

        <div id="<?php echo esc_attr($max_slider_id); ?>" class="<?php echo esc_attr($wrapper_classes); ?>">
            <div class="parallax-sliders">
                <div class="parallax-slider-inner">
                    <?php if ($settings['tcg_select_type'] == 'blocks') : ?>
                        <?php foreach ($settings['tcg_dynamic_slides_repeater'] as $index => $slide) :
                            if (!empty($slide['tcg_select_block'])) :
                                $block_id = intval($slide['tcg_select_block']);

                                // Simple validation check
                                if (!$this->is_valid_block($block_id)) {
                                    if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                                        echo '<div class="parallax-item"><div class="tcg-invalid-block-error"><h4 class="tcg-error-title">Invalid Block</h4><p class="tcg-error-message">Block ID: ' . esc_html($block_id) . '</p></div></div>';
                                    }
                                    continue;
                                }
                                ?>
                                <div class="parallax-item">
                                    <div class="tcg-parallax-slide <?php echo esc_attr($slide['tcg_select_block']); ?> <?php echo 'elementor-repeater-item-' . esc_attr($slide['_id']) . ''; ?>">
                                        <?php
                                        $frontend = new \Elementor\Frontend();
                                        echo $frontend->get_builder_content_for_display($block_id);
                                        ?>
                                    </div>
                                </div>
                            <?php endif;
                        endforeach; ?>

                    <?php elseif ($settings['tcg_select_type'] == 'query') : ?>
                        <?php
                        $query_block_id = intval($settings['tcg_query_select_block']);

                        // Simple validation for query block
                        if (!$this->is_valid_block($query_block_id)) {
                            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                                echo '<div class="parallax-item"><div class="tcg-invalid-query-block-error"><h4 class="tcg-error-title">Invalid Query Block</h4><p class="tcg-error-message">Block ID: ' . esc_html($query_block_id) . '</p></div></div>';
                            }
                        } else {
                            if ($query->have_posts()) {
                                $posts_per_slide = isset($settings['posts_per_slide']) ? intval($settings['posts_per_slide']) : 2;
                                $posts_array = array();

                                while ($query->have_posts()) {
                                    $query->the_post();
                                    $posts_array[] = get_the_ID();
                                }

                                $slides = array_chunk($posts_array, $posts_per_slide);
                                $frontend = new \Elementor\Frontend();

                                foreach ($slides as $slide_posts) {
                                    ?>
                                    <div class="parallax-item">
                                        <?php
                                        foreach ($slide_posts as $post_id) {
                                            global $post;
                                            $post = get_post($post_id);
                                            setup_postdata($post);
                                            ?>
                                            <div class="tcg-parallax-slide <?php echo esc_attr($query_block_id); ?>">
                                                <?php echo $frontend->get_builder_content_for_display($query_block_id); ?>
                                            </div>
                                            <?php
                                        }
                                        wp_reset_postdata();
                                        ?>
                                    </div>
                                    <?php
                                }
                            } else {
                                echo '<div class="parallax-item"><div class="tcg-no-posts-found">No posts found!</div></div>';
                            }
                            wp_reset_postdata();
                        }
                        ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php
    }
    /**
     * Simple block validation
     */
    private function is_valid_block($block_id)
    {
        if (empty($block_id) || !is_numeric($block_id)) return false;
        $post = get_post($block_id);
        if (!$post || $post->post_status !== 'publish') return false;
        if ($post->post_type !== 'tcg_teb') return false;
        $template_type = get_post_meta($block_id, 'template_type', true);
        return ($template_type === 'block');
    }
}
