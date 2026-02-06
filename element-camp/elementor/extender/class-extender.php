<?php

namespace ElementCampPlugin\Elementor;

defined('ABSPATH') || exit(); // Exit if accessed directly

include('container-extender.php');
include('elements-extender.php');
include('group-background-extender.php');
include('group-control-extender.php');
include('tcg-select2.php');
include('theme-builder-controls.php');
include('video-widget-extender.php');
include('page-settings-extender.php');
include('motion-effects-extender.php');


/**
 *  Elementor extra features
 */
class Themescamp_pro_Extender{

    private $TCG_Container_Extender;
    private $TCG_Elements_Extender;
    private $TCG_Group_Control_Extender;
    private $TCG_Select2;
    private $TCG_Theme_Builder_Controls;
    private $TCG_Video_Widget_Extender;
    private $TCG_Page_Settings_Extender;
    private $TCG_Motion_Effects_Extender;


    public function __construct()
    {

        $this->TCG_Container_Extender = new TCG_Pro_Container_Extender();

        $this->TCG_Elements_Extender = new TCG_Pro_Elements_Extender();

        if (class_exists('ElementCampPlugin\Elementor\TCG_Pro_Group_Control_Extender')) {
            //while return if Elementor\Group_Control_Background not exist
            $this->TCG_Group_Control_Extender = new TCG_Pro_Group_Control_Extender();
        }


        $this->TCG_Select2 = new TCG_Pro_Select2();

        $this->TCG_Theme_Builder_Controls = new TCG_Pro_Theme_Builder_Controls();

        $this->TCG_Video_Widget_Extender = new TCG_Pro_Video_Widget_Extender();

        $this->TCG_Page_Settings_Extender = new TCG_Pro_Page_Settings_Extender();

        // Initialize Motion Effects Extender
        $this->TCG_Motion_Effects_Extender = new TCG_Pro_Motion_Effects_Extender();


        // required assets for extending
        add_action('elementor/frontend/section/before_render', [$this, 'should_script_enqueue']);
        add_action('elementor/frontend/container/before_render', [$this, 'should_script_enqueue']);
        add_action('elementor/frontend/widget/before_render', [$this, 'should_script_enqueue']);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    public function enqueue_scripts($type)
    {
    }


    public function should_script_enqueue($widget)
    {
    }
}
new Themescamp_pro_Extender;