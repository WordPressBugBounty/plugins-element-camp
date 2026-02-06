<?php

namespace ElementCampPlugin\Elementor;

use Elementor\Controls_Manager;
use Elementor\Plugin;

defined('ABSPATH') || exit();

/**
 * Elementor Motion Effects Extender
 * Adds blur effect options to Elementor's motion effects
 */
class TCG_Pro_Motion_Effects_Extender
{
    public function __construct()
    {
        // Hook into container motion effects (different section for containers)
        add_action('elementor/element/container/section_effects/before_section_end', [$this, 'add_blur_controls_to_motion_effects_container'], 10, 2);

        // Hook into column motion effects
        add_action('elementor/element/column/section_effects/before_section_end', [$this, 'add_blur_controls_to_motion_effects'], 10, 2);

        // Hook into section motion effects
        add_action('elementor/element/section/section_effects/before_section_end', [$this, 'add_blur_controls_to_motion_effects'], 10, 2);

        // Hook into widget motion effects
        add_action('elementor/element/common/section_effects/before_section_end', [$this, 'add_blur_controls_to_motion_effects'], 10, 2);

        // Add custom classes to elements
        add_action('elementor/frontend/container/before_render', [$this, 'add_motion_blur_classes'], 10, 1);
        add_action('elementor/frontend/column/before_render', [$this, 'add_motion_blur_classes'], 10, 1);
        add_action('elementor/frontend/section/before_render', [$this, 'add_motion_blur_classes'], 10, 1);
        add_action('elementor/frontend/widget/before_render', [$this, 'add_motion_blur_classes'], 10, 1);
    }

    /**
     * Add blur controls to motion effects section for CONTAINERS
     * Containers have animation in a different control structure
     */
    public function add_blur_controls_to_motion_effects_container($element, $args)
    {
        // For containers, add after animation control (no underscore)
        $element->start_injection([
            'at' => 'after',
            'of' => 'animation', // Note: no underscore for containers
        ]);

        $element->add_control(
            'tcg_motion_blur_divider',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $element->add_control(
            'tcg_motion_blur_heading',
            [
                'label' => esc_html__('TCG Motion Blur Effects', 'element-camp'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'animation!' => '', // No underscore for containers
                ],
            ]
        );

        $element->add_control(
            'tcg_enable_motion_blur',
            [
                'label' => esc_html__('Enable Motion Blur', 'element-camp'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'return_value' => 'yes',
                'default' => 'no',
                'description' => esc_html__('Add blur effect to entrance animations (fadeInUp, zoomIn, etc.)', 'element-camp'),
                'condition' => [
                    'animation!' => '', // No underscore for containers
                ],
            ]
        );

        $element->add_control(
            'tcg_motion_blur_note',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => esc_html__('💡 Blur effect works best with fadeInUp and zoomIn animations. The element will blur during animation and become clear when animation completes.', 'element-camp'),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition' => [
                    'animation!' => '', // No underscore for containers
                    'tcg_enable_motion_blur' => 'yes',
                ],
            ]
        );

        $element->end_injection();
    }

    /**
     * Add blur controls to motion effects section for WIDGETS, COLUMNS, SECTIONS
     * These use _animation (with underscore)
     */
    public function add_blur_controls_to_motion_effects($element, $args)
    {
        // Add controls after entrance animation
        $element->start_injection([
            'at' => 'after',
            'of' => '_animation', // With underscore for widgets/columns/sections
        ]);

        $element->add_control(
            'tcg_motion_blur_divider',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $element->add_control(
            'tcg_motion_blur_heading',
            [
                'label' => esc_html__('TCG Motion Blur Effects', 'element-camp'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    '_animation!' => '', // With underscore
                ],
            ]
        );

        $element->add_control(
            'tcg_enable_motion_blur',
            [
                'label' => esc_html__('Enable Motion Blur', 'element-camp'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'element-camp'),
                'label_off' => esc_html__('No', 'element-camp'),
                'return_value' => 'yes',
                'default' => 'no',
                'description' => esc_html__('Add blur effect to entrance animations (fadeInUp, zoomIn, etc.)', 'element-camp'),
                'condition' => [
                    '_animation!' => '', // With underscore
                ],
            ]
        );

        $element->add_control(
            'tcg_motion_blur_note',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => esc_html__('💡 Blur effect works best with fadeInUp and zoomIn animations. The element will blur during animation and become clear when animation completes.', 'element-camp'),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition' => [
                    '_animation!' => '', // With underscore
                    'tcg_enable_motion_blur' => 'yes',
                ],
            ]
        );

        $element->end_injection();
    }

    /**
     * Add motion blur classes to elements
     */
    public function add_motion_blur_classes($element)
    {
        $settings = $element->get_settings_for_display();

        // Check for both 'animation' (containers) and '_animation' (widgets/columns/sections)
        $has_animation = !empty($settings['_animation']) || !empty($settings['animation']);

        // Only add classes if blur is enabled and animation exists
        if (!empty($settings['tcg_enable_motion_blur']) &&
            $settings['tcg_enable_motion_blur'] === 'yes' &&
            $has_animation) {

            $element->add_render_attribute('_wrapper', [
                'class' => 'tcg-motion-blur-enabled',
            ]);
        }
    }
}