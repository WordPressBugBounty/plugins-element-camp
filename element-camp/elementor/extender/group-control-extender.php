<?php

namespace ElementCampPlugin\Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Plugin;
use Elementor\Group_Control_Base;

defined('ABSPATH') || exit(); // Exit if accessed directly

/**
 *  Elementor extra features
 */

class TCG_Pro_Group_Control_Extender {

    public function __construct()
    {
        // extend Group_Control_Background
        add_action('elementor/controls/controls_registered', function () {

            $controls_manager = \Elementor\Plugin::$instance->controls_manager;
            if (class_exists('ElementCampPlugin\Elementor\TCG_Pro_Group_Background')) {
                $controls_manager->add_group_control('background', new TCG_Pro_Group_Background());
            }
        });
    }
}

