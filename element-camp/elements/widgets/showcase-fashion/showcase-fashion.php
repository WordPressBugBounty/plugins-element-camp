<?php

namespace ElementCampPlugin\Widgets;
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Text_Shadow;
use Elementor\POPOVER_TOGGLE;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;
use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Frontend;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Image_Size;
use Elementor\Scheme_Base;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Css_Filter;
use ThemescampPlugin\Elementor\Controls\Helper as ControlsHelper;

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class ElementCamp_Showcase_Fashion extends Widget_Base
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
        return 'tcgelements-showcase-fashion';
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
        return esc_html__('Showcase Fashion', 'element-camp');
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
        return ['tcgelements-showcase-fashion'];
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
        $post_types = ControlsHelper::get_post_types();
        $post_types['by_id'] = __('Manual Selection', 'themescamp-plugin');
        $taxonomies = get_taxonomies([], 'objects');

        $this->start_controls_section(
            'content',
            [
                'label' => esc_html__('Content', 'element-camp'),
            ]
        );
        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'slide_bg_color',
            [
                'label' => esc_html__('Slide Background Color', 'element-camp'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#855f31', // Default color
            ]
        );
        $this->add_control(
            'slide_colors',
            [
                'label' => esc_html__('Slide Background Colors', 'element-camp'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ slide_bg_color }}}', // Shows the color in the repeater item
                'default' => [
                    [ 'slide_bg_color' => '#855f31' ],
                    [ 'slide_bg_color' => '#ed8e8a' ],
                    [ 'slide_bg_color' => '#3fb0f5' ],
                    [ 'slide_bg_color' => '#a64547' ],
                    [ 'slide_bg_color' => '#82a0f5' ],
                    [ 'slide_bg_color' => '#e77453' ],
                ],
                'description' => esc_html__('Important: Ensure the number of colors matches the "Posts Per Page" setting to avoid color mismatches.', 'element-camp'),
            ]
        );
        $this->add_control(
            'effect_important_note',
            [
                'type' => \Elementor\Controls_Manager::ALERT,
                'alert_type' => 'warning',
                'heading' => esc_html__('Effect Important Note', 'element-camp'),
                'content' => esc_html__('Important: Ensure the number of colors matches the "Posts Per Page" setting to avoid color mismatches.', 'element-camp'),
            ]
        );
        $this->add_control(
            'display_terms',
            [
                'label' => esc_html__('Show Terms', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'element-camp'),
                'label_off' => esc_html__('Off', 'element-camp'),
            ]
        );

        $this->add_control(
            'display_terms_type',
            [
                'label' => esc_html__('Display Terms Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'categories' => esc_html__('Categories', 'element-camp'),
                    'tags' => esc_html__('Tags', 'element-camp'),
                ],
                'condition' => ['display_terms' => 'yes'],
                'default' => 'categories',
            ]
        );
        $this->add_control(
            'meta_separator',
            [
                'label' => esc_html__( 'Categories/tags Separator', 'element-camp' ),
                'type' => Controls_Manager::TEXT,
                'default' => ' / ',
                'condition' => ['display_terms' => 'yes']
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
                'default' => '6',
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
            'section_title_style',
            [
                'label' => esc_html__('Title Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'title_padding',
            [
                'label' => esc_html__('Title Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase-fashion .swiper-container .swiper-slide .item .info .title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-showcase-fashion .swiper-container .swiper-slide .item .copy-info .title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Title Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase-fashion .swiper-container .swiper-slide .item .info .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-showcase-fashion .swiper-container .swiper-slide .item .copy-info .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__('Title', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-showcase-fashion .swiper-container .swiper-slide .item .info .title,{{WRAPPER}} .tcgelements-showcase-fashion .swiper-container .swiper-slide .item .copy-info .title',
            ]
        );
        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase-fashion .swiper-container .swiper-slide .item .info .title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-showcase-fashion .swiper-container .swiper-slide .item .copy-info .title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_taxonomy_style',
            [
                'label' => esc_html__('Taxonomy Wrapper Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'taxonomy_padding',
            [
                'label' => esc_html__('Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase-fashion .swiper-container .swiper-slide .item .info .taxonomy' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-showcase-fashion .swiper-container .swiper-slide .item .copy-info .taxonomy' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'taxonomy_margin',
            [
                'label' => esc_html__('Taxonomy Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase-fashion .swiper-container .swiper-slide .item .info .taxonomy' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-showcase-fashion .swiper-container .swiper-slide .item .copy-info .taxonomy' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'taxonomy_typography',
                'label' => esc_html__('Taxonomy', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-showcase-fashion .swiper-container .swiper-slide .item .info .taxonomy,{{WRAPPER}} .tcgelements-showcase-fashion .swiper-container .swiper-slide .item .copy-info .taxonomy',
            ]
        );
        $this->add_control(
            'taxonomy_color',
            [
                'label' => esc_html__( 'Taxonomy Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase-fashion .swiper-container .swiper-slide .item .info .taxonomy' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-showcase-fashion .swiper-container .swiper-slide .item .copy-info .taxonomy' => 'color: {{VALUE}};',
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
//        portfolio settings
        $query_settings = $this->get_settings();
        $query_settings = ControlsHelper::fix_old_query($query_settings);
        $args = ControlsHelper::get_query_args($query_settings);
        $args = ControlsHelper::get_dynamic_args($query_settings, $args);
        $query = new \WP_Query($args);
        $selected_term_type = $settings['display_terms_type'];
//        end portfolio settings
        $colors = $settings['slide_colors'];

        ?>
        <div class="tcgelements-showcase-fashion">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <?php
                    $color_count = count($colors);
                    $i = 0; // Counter for colors
                    ?>
                    <?php if ($query->have_posts()) : while  ($query->have_posts()) : $query->the_post(); global $post ;
                        if($settings['display_terms']=='yes' && $selected_term_type == 'categories'){
                            $taxonomy_names = get_post_taxonomies();
                            if($taxonomy_names){
                                $category = get_the_terms($post->ID, $taxonomy_names[0]);
                            }
                        }elseif($settings['display_terms']=='yes' && $selected_term_type == 'tags'){
                            $taxonomy_names = get_post_taxonomies();
                            if($taxonomy_names){
                                $tags = get_the_terms($post->ID, $taxonomy_names[1]);
                            };
                        }
                        // Cycle through colors
                        $slide_bg_color = $colors[$i % $color_count]['slide_bg_color'];
                        $i++; // Increment color index
                        ?>
                            <div class="swiper-slide" data-slide-bg-color="<?= esc_attr($slide_bg_color); ?>">
                                <div class="showcase-fashion-title" data-swiper-parallax="-130%" >
                                    <div class="showcase-fashion-title-text"><?=esc_html(get_the_title())?></div>
                                </div>
                                <?php if ($settings['display_terms']=='yes') : ?>
                                    <div class="taxonomy-text">
                                        <?php
                                        $cat_counter = 0;
                                        $tag_counter = 0;
                                        if($selected_term_type == 'categories' && $category){
                                            foreach ($category as $cat) {
                                                if ($cat_counter >= 1) echo $settings['meta_separator'];
                                                echo '<span>' . $cat->name . '</span>';
                                                $cat_counter++;
                                            };
                                        }
                                        if($selected_term_type == 'tags' && $tags){
                                            foreach ($tags as $tag) {
                                                if ($tag_counter >= 1) echo $settings['meta_separator'];
                                                echo '<span>' . $tag->name . '</span>';
                                                $tag_counter++;
                                            }
                                        }
                                        ?>
                                    </div>
                                <?php endif;?>
                                <div class="showcase-fashion-scale">
                                    <img src="<?=esc_url(get_the_post_thumbnail_url())?>" alt="<?=esc_html(get_the_title())?>">
                                </div>
                            </div>
                        <?php endwhile;  wp_reset_postdata(); endif; ?>
                </div>
                <!--       Arrows       -->
                <div class="showcase-fashion-button-prev showcase-fashion-button">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 350 160 90">
                        <g class="showcase-fashion-svg-wrap">
                            <g class="showcase-fashion-svg-circle-wrap">
                                <circle cx="42" cy="42" r="40"></circle>
                            </g>
                            <path class="showcase-fashion-svg-arrow"
                                  d="M.983,6.929,4.447,3.464.983,0,0,.983,2.482,3.464,0,5.946Z">
                            </path>
                            <path class="showcase-fashion-svg-line" d="M80,0H0"></path>
                        </g>
                    </svg>
                </div>
                <div class="showcase-fashion-button-next showcase-fashion-button">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 350 160 90">
                        <g class="showcase-fashion-svg-wrap">
                            <g class="showcase-fashion-svg-circle-wrap">
                                <circle cx="42" cy="42" r="40"></circle>
                            </g>
                            <path class="showcase-fashion-svg-arrow"
                                  d="M.983,6.929,4.447,3.464.983,0,0,.983,2.482,3.464,0,5.946Z">
                            </path>
                            <path class="showcase-fashion-svg-line" d="M80,0H0"></path>
                        </g>
                    </svg>
                </div>
            </div>
        </div>
        <?php
    }
}
