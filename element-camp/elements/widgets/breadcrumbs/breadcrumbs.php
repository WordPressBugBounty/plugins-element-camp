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


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function elementcamp_breadcrumbs($separator = ''){

    # breadcrumbs ----------
    $home_text = (is_rtl()) ? esc_html__( 'الصفحات', 'element-camp' ) : esc_html__( 'Pages', 'element-camp' );
    $before = "<span><a class='current'>";
    $after      = '</a></span>';

    $breadcrumbs = array();


    # WordPress breadcrumbs ----------
    if ( ! is_home() && ! is_front_page() || is_paged() ){

        $post     = get_post();
        $home_url = esc_url(home_url( '/' ));

        # Home ----------
        $breadcrumbs[] = array(
            'url'   => $home_url,
            'name'  => $home_text,
        );

        # Category ----------
        if ( is_category() ){

            $category = get_query_var( 'cat' );
            $category = get_category( $category );

            if( $category->parent !== 0 ){

                $parent_categories = array_reverse( get_ancestors( $category->cat_ID, 'category' ) );

                foreach ( $parent_categories as $parent_category ) {
                    $breadcrumbs[] = array(
                        'url'  => cached_get_term_link( $parent_category, 'category' ),
                        'name' => get_cat_name( $parent_category ),
                    );
                }
            }

            $breadcrumbs[] = array(
                'name' => get_cat_name( $category->cat_ID ),
            );
        }

        # Day ----------
        elseif ( is_day() ){

            $breadcrumbs[] = array(
                'url'  => get_year_link( get_the_time( 'Y' ) ),
                'name' => get_the_time( 'Y' ),
            );

            $breadcrumbs[] = array(
                'url'  => get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ),
                'name' => get_the_time( 'F' ),
            );

            $breadcrumbs[] = array(
                'name' => get_the_time( 'd' ),
            );
        }


        # Month ----------
        elseif ( is_month() ){

            $breadcrumbs[] = array(
                'url'  => get_year_link( get_the_time( 'Y' ) ),
                'name' => get_the_time( 'Y' ),
            );

            $breadcrumbs[] = array(
                'name' => get_the_time( 'F' ),
            );
        }

        # Year ----------
        elseif ( is_year() ){

            $breadcrumbs[] = array(
                'name' => get_the_time( 'Y' ),
            );
        }

        # Tag ----------
        elseif ( is_tag() ){

            $breadcrumbs[] = array(
                'name' => get_the_archive_title(),
            );
        }

        # Author ----------
        elseif ( is_author() ){

            $author = get_query_var( 'author' );
            $author = get_userdata($author);

            $breadcrumbs[] = array(
                'name' => $author->display_name,
            );
        }

        # 404 ----------
        elseif ( is_404() ){

            $breadcrumbs[] = array(
                'name' => esc_html_e( ' ' , 'element-camp'  ),
            );
        }

        # Pages ----------
        elseif ( is_page() ){

            if ( $post->post_parent ){

                $parent_id   = $post->post_parent;
                $page_parents = array();

                while ( $parent_id ){
                    $get_page  = get_page( $parent_id );
                    $parent_id = $get_page->post_parent;

                    $page_parents[] = array(
                        'url'  => get_permalink( $get_page->ID ),
                        'name' => get_the_title( $get_page->ID ),
                    );
                }

                $page_parents = array_reverse( $page_parents );

                foreach( $page_parents as $single_page ){

                    $breadcrumbs[] = array(
                        'url'  => $single_page['url'],
                        'name' => $single_page['name'],
                    );
                }
            }

            $breadcrumbs[] = array(
                'name' => get_the_title(),
            );
        }

        # Attachment ----------
        elseif ( is_attachment() ){

            if( ! empty( $post->post_parent ) ){
                $parent = get_post( $post->post_parent );

                $breadcrumbs[] = array(
                    'url'  => get_permalink( $parent ),
                    'name' => $parent->post_title,
                );
            }

            $breadcrumbs[] = array(
                'name' => get_the_title(),
            );
        }

        # Single Posts ----------
        elseif ( is_singular() ){

            # Single Post ----------
            if ( get_post_type() == 'post' ){

                $category = elementcamp_get_primary_category_id();
                $category = get_category( $category );

                if( ! empty( $category ) ){

                    if( $category->parent !== 0 ){
                        $parent_categories = array_reverse( get_ancestors( $category->term_id, 'category' ) );

                        foreach ( $parent_categories as $parent_category ) {
                            $breadcrumbs[] = array(
                                'url'  => cached_get_term_link( $parent_category, 'category' ),
                                'name' => get_cat_name( $parent_category ),
                            );
                        }
                    }

                    $breadcrumbs[] = array(
                        'url'  => cached_get_term_link( $category->term_id, 'category' ),
                        'name' => get_cat_name( $category->term_id ),
                    );
                }
            }

            # Custom Post type ----------
            else{
                $post_type = get_post_type_object( get_post_type() );
                $slug      = $post_type->rewrite;
                $slug = !empty($slug) ? '/' . $slug['slug'] : '';

                $breadcrumbs[] = array(
                    'url'  => $home_url . $slug,
                    'name' => esc_html__($post_type->labels->singular_name,'element-camp'),
                );
            }

            $breadcrumbs[] = array(
                'name' => get_the_title(),
            );
        }

        # Print the BreadCrumb
        if( ! empty( $breadcrumbs ) ){
            $cunter = 0;
            $breadcrumbs_schema = array(
                '@context'        => 'http://schema.org',
                '@type'           => 'BreadcrumbList',
                '@id'             => '#Breadcrumb',
                'itemListElement' => array(),
            );
            $sep='';
            if(!empty($separator)){
                $sep = "<span class='sep'> $separator </span>";
            }
            foreach( $breadcrumbs as $item ) {

                $cunter++;

                if( ! empty( $item['url'] )){
                    echo '<span class=" '. $cunter .' breadcrumb"><a href="'. esc_url( $item['url'] ) .'">' .' '. $item['name'] .'</a></span>'. $sep .' ';
                } else {
                    echo wp_kses_post(  $before . $item['name'] . $after );
                    global $wp;
                    $item['url'] = esc_url( home_url( add_query_arg( array(),$wp->request ) ) );
                }

                $breadcrumbs_schema['itemListElement'][] = array(
                    '@type'    => 'ListItem',
                    'position' => $cunter,
                    'item'     => array(
                        'name' => str_replace( '<span class="fa fa-home" aria-hidden="true"></span> ', '', $item['name']),
                        '@id'  => $item['url'],
                    )
                );

            }
        }
    }
    wp_reset_postdata();
}
		
/**
 * @since 1.0.0
 */
class ElementCamp_Breadcrumbs extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'tcgelements-breadcrumbs';
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
	public function get_title() {
		return esc_html__( 'Breadcrumbs', 'element-camp' );
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
	public function get_icon() {
		return 'eicon-button tce-widget-badge';
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
	public function get_categories() {
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
	protected function _register_controls() {
	
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Breadcrumbs Settings', 'element-camp' ),
			]
		);

        $this->add_responsive_control(
            'separator_style',
            [
                'label' => esc_html__('Separator', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'text',
                'options' => [
                    'text' => esc_html__('Text', 'element-camp'),
                    'icon' => esc_html__('Icon', 'element-camp'),
                ],
            ]
        );

        $this->add_control(
            'breadcrumbs_separator_icon',
            [
                'label' => esc_html__('Separator', 'element-camp'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'skin' => 'inline',
                'label_block' => false,
                'condition' => ['separator_style' => 'icon'],
            ]
        );

        $this->add_control(
            'breadcrumbs_separator',
            [
                'label' => esc_html__('Separator', 'element-camp'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__(' / ', 'element-camp'),
                'label_block' => true,
                'condition' => ['separator_style' => 'text'],
            ]
        );

		$this->add_responsive_control(
			'breadcrumbs_align',
			[
				'label' => esc_html__( 'Alignment', 'element-camp' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'element-camp' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'element-camp' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'element-camp'),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .tcgelements-breadcrumbs' => 'text-align: {{VALUE}};',
				],
			]
		);
		

		$this->end_controls_section();

		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Content Style', 'element-camp' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_responsive_control(
            'breadcrumb_margin',
            [
                'label' => esc_html__('Breadcrumb Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-breadcrumbs .breadcrumb' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'breadcrumbs_display',
            [
                'label' => esc_html__('Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'block',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'inline-flex' => esc_html__('Inline Flex', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-breadcrumbs .path' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'breadcrumbs_display_position',
            [
                'label' => esc_html__( 'Display Position', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'before' => [
                        'title' => esc_html__( 'Before', 'element-camp' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'after' => [
                        'title' => esc_html__( 'After', 'element-camp' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                    'start' => [
                        'title' => esc_html__( 'Start', 'element-camp' ),
                        'icon' => "eicon-h-align-right",
                    ],
                    'end' => [
                        'title' => esc_html__( 'End', 'element-camp' ),
                        'icon' => "eicon-h-align-left",
                    ],
                ],
                'selectors_dictionary' => [
                    'before' => 'flex-direction: column;',
                    'after' => 'flex-direction: column-reverse;',
                    'start' => 'flex-direction: row;',
                    'end' => 'flex-direction: row-reverse;',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-breadcrumbs .path' => '{{VALUE}}',
                ],
                'condition' => [ 'breadcrumbs_display' => ['flex','inline-flex'] ],
            ]
        );
        $this->add_responsive_control(
            'breadcrumbs_justify_content',
            [
                'label' => esc_html__( 'Justify Content', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'default' => '',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Start','element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-start-h',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-center-h',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'End', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-end-h',
                    ],
                    'space-between' => [
                        'title' => esc_html__( 'Space Between', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-space-between-h',
                    ],
                    'space-around' => [
                        'title' => esc_html__( 'Space Around', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-space-around-h',
                    ],
                    'space-evenly' => [
                        'title' => esc_html__( 'Space Evenly', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-space-evenly-h',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-breadcrumbs .path' => 'justify-content: {{VALUE}};',
                ],
                'condition'=> ['breadcrumbs_display'=> ['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'breadcrumbs_align_items',
            [
                'label' => esc_html__( 'Align Items', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => '',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Start', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-align-start-v',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-align-center-v',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'End', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-align-end-v',
                    ],
                    'stretch' => [
                        'title' => esc_html__( 'Stretch', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-align-stretch-v',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-breadcrumbs .path' => 'align-items: {{VALUE}};',
                ],
                'condition'=> ['breadcrumbs_display'=> ['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'breadcrumbs_flex_wrap',
            [
                'label' => esc_html__( 'Wrap', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'nowrap' => [
                        'title' => esc_html__( 'No Wrap', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-nowrap',
                    ],
                    'wrap' => [
                        'title' => esc_html__( 'Wrap', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-wrap',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-breadcrumbs .path' => 'flex-wrap: {{VALUE}};',
                ],
                'condition'=> ['breadcrumbs_display'=> ['flex','inline-flex']],
            ]
        );

        $this->start_controls_tabs(
            'text_tabs',
        );
        
        $this->start_controls_tab(
            'normal_text_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'breadcrumbs_text_typography',
				'label' => esc_html__( 'Typography', 'element-camp' ),
				//'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .tcgelements-breadcrumbs a, {{WRAPPER}} .tcgelements-breadcrumbs span',
			]
		);

		$this->add_control(
			'breadcrumbs_text_color',
			[
				'label' => esc_html__( 'Text Color', 'element-camp' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tcgelements-breadcrumbs span' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-breadcrumbs a' => 'color: {{VALUE}};',
				],
			]
        );

        $this->add_control(
            'breadcrumbs_text_opacity',
            [
                'label' => esc_html__( 'Text Opacity', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-breadcrumbs a' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_control(
            'dark_mode_style_heading',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'breadcrumbs_text_color_dark_mode',
            [
                'label' => esc_html__( 'Text Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-breadcrumbs span' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-breadcrumbs span' => 'color: {{VALUE}};',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-breadcrumbs a' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-breadcrumbs a' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'current_text_tab',
            [
                'label'   => esc_html__( 'Current', 'element-camp' ),
            ]
        );

        $this->add_responsive_control(
            'breadcrumb_margin_current',
            [
                'label' => esc_html__('Breadcrumb Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-breadcrumbs .current' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'breadcrumbs_current_typography',
                'label' => esc_html__( 'Typography', 'element-camp' ),
                //'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .tcgelements-breadcrumbs .current',
            ]
        );

        $this->add_control(
			'breadcrumbs_current_color',
			[
				'label' => esc_html__( 'Color', 'element-camp' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tcgelements-breadcrumbs .current' => 'color: {{VALUE}};',
				],
			]
        );

        $this->add_control(
            'breadcrumbs_current_opacity',
            [
                'label' => esc_html__( 'Opacity', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-breadcrumbs .current' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_control(
            'dark_mode_style_heading_current',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'breadcrumbs_text_color_current_dark_mode',
            [
                'label' => esc_html__( 'Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-breadcrumbs .current' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-breadcrumbs .current' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        
        $this->end_controls_tabs();

        $this->end_controls_section();
        $this->start_controls_section(
            'separator_style_section',
            [
                'label' => esc_html__( 'Separator Style', 'element-camp' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'sep_margin',
            [
                'label' => esc_html__('Separator Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-breadcrumbs .sep' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'separator_opacity',
            [
                'label' => esc_html__( 'Separator Opacity', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-breadcrumbs .sep' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'separator_icon_size',
            [
                'label' => esc_html__( 'Separator Icon size', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' , 'custom' , 'rem' , 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-breadcrumbs .sep i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-breadcrumbs .sep svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => ['separator_style' => 'icon'],
            ]
        );
        $this->add_control(
            'separator_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-breadcrumbs .sep i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-breadcrumbs .sep svg' => 'fill: {{VALUE}};',
                ],
                'condition' => ['separator_style' => 'icon'],
            ]
        );
        $this->add_control(
            'dark_mode_style_heading_icon',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => ['separator_style' => 'icon'],
            ]
        );

        $this->add_control(
            'breadcrumbs_icon_color_current_dark_mode',
            [
                'label' => esc_html__( 'Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-breadcrumbs .sep i' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-breadcrumbs .sep i' => 'color: {{VALUE}};',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-breadcrumbs .sep svg' => 'fill: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-breadcrumbs .sep svg' => 'fill: {{VALUE}};',
                ],
                'condition' => ['separator_style' => 'icon'],
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
	protected function render() {
		$settings = $this->get_settings();
        if ($settings['separator_style'] === 'icon' && !empty($settings['breadcrumbs_separator_icon']['value'])) {
            ob_start();
            Icons_Manager::render_icon($settings['breadcrumbs_separator_icon'], ['aria-hidden' => 'true']);
            $separator = ob_get_clean();
        } else {
            $separator = $settings['breadcrumbs_separator'];
        }
		?>

		<div class="tcgelements-breadcrumbs">
			<div class="path">
			    <?php elementcamp_breadcrumbs($separator); ?>
			</div>
		</div>

		<?php
	
		
	 
		}
}


