<?php

namespace ElementCampPlugin\Elementor;

use Elementor\Controls_Manager;

defined('ABSPATH') || exit(); // Exit if accessed directly

/**
 *  Elementor extra features
 */

class TCG_Pro_Video_Widget_Extender
{

    public function __construct()
    {

        // theme builder controls
        add_action('elementor/element/video/section_video_style/after_section_end', [$this, 'register_tc_video_custom_size_controls'], 10, 3);
    }

    function register_tc_video_custom_size_controls($widget, $args)
    {

        $widget->start_controls_section(
            'tc_video_custom_size_settings',
            [
                'label' => __('TCG Video Custom Size', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $widget->add_responsive_control(
            'tc_video_container_custom_width',
            [
                'label' => esc_html__( 'Container Width', 'element-camp' ),
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
                'size_units' => [ 'px', 'vw', '%' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-wrapper' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $widget->add_responsive_control(
            'tc_video_container_custom_height',
            [
                'label' => esc_html__( 'Container Height', 'element-camp' ),
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
                'size_units' => [ 'px', 'vw', '%' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-wrapper' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $widget->add_responsive_control(
			'tc_video_custom_width',
			[
				'label' => esc_html__( 'Width', 'element-camp' ),
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
				'size_units' => [ 'px', 'vw', '%' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
					'%' => [
						'min' => 1,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-video ' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $widget->add_responsive_control(
			'tc_video_custom_height',
			[
				'label' => esc_html__( 'Height', 'element-camp' ),
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
				'size_units' => [ 'px', 'vw', '%' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
					'%' => [
						'min' => 1,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-video ' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$widget->add_responsive_control(
			'tc_video_custom_max_height',
			[
				'label' => esc_html__( 'Max Height', 'element-camp' ),
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
				'size_units' => [ 'px', 'vw', '%' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
					'%' => [
						'min' => 1,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-video ' => 'max-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $widget->end_controls_section();

        $widget->start_controls_section(
            'tc_video_style_settings',
            [
                'label' => __('TCG Video Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $widget->add_responsive_control(
            'tc_video_opacity',
            [
                'label' => esc_html__('Opacity', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1,
                    'unit' => '',
                ],
                'range' => [
                    '' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-video' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $widget->end_controls_section();
    }
}
