<?php

namespace ElementCampPlugin\Widgets;

use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Frontend;
use Elementor\Icons_Manager;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Image_Size;
use Elementor\Scheme_Base;
use ThemescampPlugin\Elementor\Controls\Helper as ControlsHelper;

if (!defined('ABSPATH')) exit; // Exit if accessed directly



/**
 * @since 1.0.0
 */
class elementcamp_Showcase_Interactive extends Widget_Base
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
        return 'tcgelements-showcase-interactive';
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
        return esc_html__('Showcase Interactive', 'element-camp');
    }

    public function get_script_depends()
    {
        return [ 'tcgelements-showcase-interactive' ];
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
        return 'eicon-image-before-after tce-widget-badge';
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
    protected function _register_controls()
    {
        $post_types = ControlsHelper::get_post_types();
        $post_types['by_id'] = __('Manual Selection', 'themescamp-plugin');
        $taxonomies = get_taxonomies([], 'objects');

        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Portfolio Settings.', 'element-camp'),
            ]
        );
//        $this->add_control(
//            'animation_style',
//            [
//                'label' => esc_html__( 'Animation Style', 'element-camp' ),
//                'description' => esc_html__( 'Enter Number From 1 To 23 Different Animations', 'element-camp' ),
//                'type' => \Elementor\Controls_Manager::NUMBER,
//                'default' => 1,
//                'range' => [
//                    'max' => 23,
//                    'min' => 1,
//                    'step' => 1,
//                ],
//            ]
//        );
        $this->add_control(
            'display_terms_type',
            [
                'label' => esc_html__('Display Terms Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'categories' => esc_html__('Categories', 'element-camp'),
                    'tags' => esc_html__('Tags', 'element-camp'),
                ],
                'default' => 'categories',
            ]
        );
        $this->add_control(
            'meta_separator',
            [
                'label' => esc_html__( 'Categories/tags Separator', 'element-camp' ),
                'type' => Controls_Manager::TEXT,
                'default' => ' / ',
            ]
        );

        $this->add_control(
            'post_type',
            [
                'label' => esc_html__('Source', 'themescamp-core'),
                'type' => Controls_Manager::SELECT,
                'options' => $post_types,
                'default' => key($post_types),
            ]
        );
        $this->add_control(
            'posts_ids',
            [
                'label' => __('Search & Select', 'themescamp-plugin'),
                'type' => 'tcg-select2',
                'options' => ControlsHelper::get_post_list(),
                'label_block' => true,
                'multiple'    => true,
                'source_name' => 'post_type',
                'source_type' => 'any',
                'condition' => [
                    'post_type' => 'by_id',
                ],
            ]
        );
        $this->add_control(
            'posts_per_page',
            [
                'label' => __('Posts Per Page', 'themescamp-plugin'),
                'type' => Controls_Manager::NUMBER,
                'default' => '4',
                'min' => '1',
            ]
        );

        $this->add_control(
            'offset',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'default' => '0',
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => __('Order', 'themescamp-plugin'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'asc' => 'Ascending',
                    'desc' => 'Descending',
                ],
                'default' => 'desc',

            ]
        );
        $this->add_control(
            'orderby',
            [
                'label' => __('Order By', 'themescamp-plugin'),
                'type' => Controls_Manager::SELECT,
                'options' => ControlsHelper::get_post_orderby_options(),
                'default' => 'date',

            ]
        );

        $this->add_control(
            'authors',
            [
                'label' => __('Author', 'themescamp-plugin'),
                'label_block' => true,
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'default' => [],
                'options' => ControlsHelper::get_authors_list(),
                'condition' => [
                    'post_type!' => ['by_id', 'source_dynamic'],
                ],
            ]
        );
        $this->add_control(
            'post__not_in',
            [
                'label'       => __('Exclude', 'themescamp-plugin'),
                'type'        => 'tcg-select2',
                'label_block' => true,
                'multiple'    => true,
                'source_name' => 'post_type',
                'source_type' => 'any',
                'condition'   => [
                    'post_type!' => ['by_id', 'source_dynamic'],
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
                    'use_taxonomy_slug' => true,
                    'condition' => [
                        'post_type' => $object->object_type,
                    ],
                ]
            );
        }

        $this->end_controls_section();
        $this->start_controls_section(
            'style',
            [
                'label' => esc_html__( 'Style','element-camp' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .tcgelements-showcase-interactive .title',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Title Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em','rem'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase-interactive .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'cat_typography',
                'selector' => '{{WRAPPER}} .tcgelements-showcase-interactive .taxonomy',
            ]
        );

        $this->add_responsive_control(
            'cat_margin',
            [
                'label' => esc_html__('Category Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase-interactive .taxonomy' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'num_typography',
                'selector' => '{{WRAPPER}} .tcgelements-showcase-interactive .num',
            ]
        );

        $this->add_responsive_control(
            'num_margin',
            [
                'label' => esc_html__('Number Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase-interactive .num' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
        $settings = $this->get_settings();
        $query_settings = $this->get_settings();
        $query_settings = ControlsHelper::fix_old_query($query_settings);
        $args = ControlsHelper::get_query_args($query_settings);
        $args = ControlsHelper::get_dynamic_args($query_settings, $args);
        $query = new \WP_Query($args);
        $selected_term_type = $settings['display_terms_type'];
        ?>

        <div class="tcgelements-showcase-interactive">
            <?php
            if ($query->have_posts()) :
                while ($query->have_posts()) :
                    $query->the_post();
                    global $post ;
                    $terms_display = '';
                    if($selected_term_type == 'categories'){
                        $taxonomy_names = get_post_taxonomies();
                        if($taxonomy_names){
                            $categories = get_the_terms($post->ID, $taxonomy_names[0]);
                            if($categories && !is_wp_error($categories)) {
                                $cat_names = wp_list_pluck($categories, 'name');
                                $terms_display = implode($settings['meta_separator'], $cat_names);
                            }
                        }
                    } elseif($selected_term_type == 'tags'){
                        $taxonomy_names = get_post_taxonomies();
                        if($taxonomy_names){
                            $tags = get_the_terms($post->ID, $taxonomy_names[1]);
                            if($tags && !is_wp_error($tags)) {
                                $tag_names = wp_list_pluck($tags, 'name');
                                $terms_display = implode($settings['meta_separator'], $tag_names);
                            }
                        }
                    }
                    ?>
                    <div class="item block" data-fx="1">
                        <a href="<?= esc_url(get_the_permalink()) ?>" class="block__link" data-img="<?= esc_url(get_the_post_thumbnail_url()) ?>">
                            <div class="cont">
                                <h4 class="title"><?= esc_html(get_the_title()) ?></h4>
                                <p class="taxonomy"><?= esc_html($terms_display) ?></p>
                            </div>
                        </a>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
        <?php
    }
}
