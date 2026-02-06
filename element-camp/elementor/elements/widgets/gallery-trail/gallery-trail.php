<?php

namespace ElementCampPlugin\Widgets;

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
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
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Scheme_Base;
use Elementor\POPOVER_TOGGLE;
use Elementor\Group_Control_Background;
use Elementor\Repeater;

if (!defined('ABSPATH')) exit;

class ElementCamp_Gallery_Trail extends Widget_Base
{
    public function get_name()
    {
        return 'tcgelements-gallery-trail';
    }

    public function get_title()
    {
        return esc_html__('Gallery Trail', 'element-camp');
    }

    public function get_icon()
    {
        return 'eicon-gallery-grid tce-widget-badge';
    }

    public function get_categories()
    {
        return ['elementcamp-elements'];
    }

    public function get_script_depends() {
        return ['gsap','tcgelements-background-image','tcgelements-gallery-trail'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_images',
            [
                'label' => esc_html__('Images', 'element-camp'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'image',
            [
                'label' => esc_html__('Choose Image', 'element-camp'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'images',
            [
                'label' => esc_html__('Images', 'element-camp'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'image' => ['url' => Utils::get_placeholder_image_src()],
                    ],
                    [
                        'image' => ['url' => Utils::get_placeholder_image_src()],
                    ],
                    [
                        'image' => ['url' => Utils::get_placeholder_image_src()],
                    ],
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="tcgelements-gallery-trail">
            <?php foreach ($settings['images'] as $image) : ?>
                <div class="tcgelements-gallery-trail__img">
                    <div class="tcgelements-gallery-trail__img-inner bg-img" data-background="<?php echo esc_url($image['image']['url']); ?>"></div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}