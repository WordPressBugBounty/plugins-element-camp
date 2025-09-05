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
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

if (!defined('ABSPATH')) exit;

class ElementCamp_Stage_Animation extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'tcgelements-stage-animation';
    }

    public function get_title()
    {
        return esc_html__('Stage Animation', 'element-camp');
    }

    public function get_icon()
    {
        return 'eicon-square tce-widget-badge';
    }

    public function get_categories()
    {
        return ['elementcamp-elements'];
    }
    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Settings', 'element-camp'),
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'style_tab',
            [
                'label' => esc_html__('Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->end_controls_section();

    }
    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="tcgelements-stage-animation">
            <div class="rotate">
                <div class="cube">
                    <figure class="back"></figure>
                    <figure class="top"></figure>
                    <figure class="bottom"></figure>
                    <figure class="left"></figure>
                    <figure class="right"></figure>
                    <figure class="front"></figure>
                </div>
            </div>
        </div>
        <?php
    }

}
