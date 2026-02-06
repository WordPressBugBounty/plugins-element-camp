<?php
namespace Elementor;

class ElementCamp_Extend_section {

    public function __construct() {

        /**
         * Section Controls
         */
        add_action( 'elementor/element/section/section_advanced/after_section_end', [$this, 'register_section_controls'] );
    }

    /**
     * Section Controls
     */
    public function register_section_controls( Controls_Stack $element ) {
        $element->start_controls_section(
            'elementcamp_onepagescroll_section',
            [
                'label'         => esc_html__( 'Tcgelements Sticky Settings', 'element-camp' ),
                'tab'           => Controls_Manager::TAB_ADVANCED,
                'hide_in_inner' => false,
            ]
        );

		$element->add_control(
			'sticky',
			[
				'label' => __( 'Sticky', 'element-camp' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'None', 'element-camp' ),
					'top' => __( 'Top', 'element-camp' ),
				],
				'separator' => 'before',
				'render_type' => 'none',
				'frontend_available' => true,
				'prefix_class'          => 'tcgelements-sticky-',
			]
		);

        $element->add_control(
            'sticky_background',
            [
                'label'     => __( 'Background Scroll', 'element-camp' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}.elementor-section.is-stuck' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'sticky' => 'top',
                ],
            ]
        );

        // $element->add_control(
        //     'sticky_background4',
        //     [
        //         'label'     => __( 'Background Scroll', 'element-camp' ),
        //         'type'      => Controls_Manager::COLOR,
        //         'selectors' => [
        //             '{{WRAPPER}}.elementor-section' => 'background: {{VALUE}};',
        //             '{{WRAPPER}}.elementor-section' => 'background: linear-gradient( #12c2e9, #c471ed, #f64f59);',
        //         ],
        //         'condition' => [
        //             'sticky' => 'top',
        //         ],
        //     ]
        // );
        
        $element->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'scroll_box_shadow',
                'label'     => __( 'Scroll Shadow', 'element-camp' ),
                'selector' => '{{WRAPPER}} .elementor-section.is-stuck',
            ]
        );


        $element->add_responsive_control(
            'offset_space',
            [
                'label' => __( 'Offset', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.is-stuck' => 'top: {{SIZE}}{{UNIT}};',
                    '.admin-bar {{WRAPPER}}.is-stuck' => 'top: calc({{SIZE}}{{UNIT}} + 32px);', 
                ],
                'condition' => [
                    'sticky' => 'top',
                ],
            ]
        );

        $element->add_control(
            'separator_panel_style',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $element->add_control(
            'enable_gradient',
            [
                'label' => __( 'Enable Gradient (3rd)', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'element-camp' ),
                'label_off' => __( 'No', 'element-camp' ),
                'return_value' => 'yes',
                'default' => false,
            ]
        );

        $element->add_control(
            'color',
            [
                'label' => _x( 'Gradient Color', 'Background Control', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'title' => _x( 'Background Color', 'Background Control', 'element-camp' ),
                'condition' => [
                    'enable_gradient' => [ 'yes'],
                ],
            ]
        );


        $element->add_control(
            'color_stop', 
            [
                'label' => _x( 'Location', 'Background Control', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'default' => [
                    'unit' => '%',
                    'size' => 0,
                ],
                'render_type' => 'ui',
                'condition' => [
                    'enable_gradient' => [ 'yes'],
                ],
                'of_type' => 'gradient',
            ]
        );
        $element->add_control(
            'color_a',
            [
                'label' => _x( 'Second Color', 'Background Control', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#f2295b',
                'render_type' => 'ui',
                'condition' => [
                    'enable_gradient' => [ 'yes'],
                ],
                'of_type' => 'gradient',
            ]
        );

        $element->add_control(
            'color_a_stop', 
            [
                'label' => _x( 'Location', 'Background Control', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'render_type' => 'ui',
                'condition' => [
                    'enable_gradient' => [ 'yes'],
                ],
                'of_type' => 'gradient',
            ]
        );

        $element->add_control(
            'color_b',
            [
                'label' => _x( 'Second Color', 'Background Control', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#f2295b',
                'render_type' => 'ui',
                'condition' => [
                    'enable_gradient' => [ 'yes'],
                ],
                'of_type' => 'gradient',
            ]
        );

        $element->add_control(
            'color_b_stop', 
            [
                'label' => _x( 'Location', 'Background Control', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'render_type' => 'ui',
                'condition' => [
                    'enable_gradient' => [ 'yes'],
                ],
                'of_type' => 'gradient',
            ]
        );

        $element->add_control(
            'gradient_type', 
            [
                'label' => _x( 'Type', 'Background Control', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'linear' => _x( 'Linear', 'Background Control', 'element-camp' ),
                    'radial' => _x( 'Radial', 'Background Control', 'element-camp' ),
                ],
                'default' => 'linear',
                'render_type' => 'ui',
                'condition' => [
                    'enable_gradient' => [ 'yes'],
                ],
                'of_type' => 'gradient',
            ]
        );

        $element->add_control(
            'gradient_angle', 
            [
                'label' => _x( 'Angle', 'Background Control', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'deg' ],
                'default' => [
                    'unit' => 'deg',
                    'size' => 180,
                ],
                'range' => [
                    'deg' => [
                        'step' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.elementor-section' => 'background: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}},{{color_a.VALUE}} {{color_a_stop.SIZE}}{{color_a_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}});',
                ],

                'condition' => [
                    'enable_gradient' => [ 'yes'],
                    'gradient_type' => 'linear',
                ],
                'of_type' => 'gradient',
            ]
        );


        $element->end_controls_section();
    }
}
new ElementCamp_Extend_section();
?>