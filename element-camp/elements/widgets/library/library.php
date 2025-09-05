<?php
namespace ElementCampPlugin\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Repeater;

if (!defined('ABSPATH')) exit;

class ElementCamp_Library extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'tcgelements-library';
    }

    public function get_title()
    {
        return esc_html__('Library', 'element-camp');
    }

    public function get_icon()
    {
        return 'eicon-gallery-grid tce-widget-badge';
    }

    public function get_categories()
    {
        return ['elementcamp-elements'];
    }

    public function get_script_depends()
    {
        return ['tcgelements-library'];
    }

    protected function _register_controls()
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Settings', 'element-camp'),
            ]
        );
        $repeater_left = new Repeater();
        $repeater_left->add_control(
            'left_image',
            [
                'label' => esc_html__('Image', 'element-camp'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );
        $repeater_center = new Repeater();
        $repeater_center->add_control(
            'center_image',
            [
                'label' => esc_html__('Image', 'element-camp'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );
        $repeater_right = new Repeater();
        $repeater_right->add_control(
            'right_image',
            [
                'label' => esc_html__('Image', 'element-camp'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );
        $this->add_control(
            'left_items',
            [
                'label' => esc_html__('Left Items', 'element-camp'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater_left->get_controls(),
            ]
        );
        $this->add_control(
            'center_items',
            [
                'label' => esc_html__('Center Items', 'element-camp'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater_center->get_controls(),
            ]
        );
        $this->add_control(
            'right_items',
            [
                'label' => esc_html__('Right Items', 'element-camp'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater_right->get_controls(),
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="tcgelements-library">
            <div class="library-column">
                <?php foreach($settings['left_items'] as $item) : ?>
                    <div class="item">
                        <img src="<?= esc_url($item['left_image']['url']); ?>" alt="<?php if (!empty($item['left_image']['alt'])) echo esc_attr($item['left_image']['alt']); ?>">
                    </div>
                <?php endforeach;?>
            </div>
            <div class="library-column center">
                <?php foreach($settings['center_items'] as $item) : ?>
                    <div class="item">
                        <img src="<?= esc_url($item['center_image']['url']); ?>" alt="<?php if (!empty($item['center_image']['alt'])) echo esc_attr($item['center_image']['alt']); ?>">
                    </div>
                <?php endforeach;?>
            </div>
            <div class="library-column">
                <?php foreach($settings['right_items'] as $item) : ?>
                    <div class="item">
                        <img src="<?= esc_url($item['right_image']['url']); ?>" alt="<?php if (!empty($item['right_image']['alt'])) echo esc_attr($item['right_image']['alt']); ?>">
                    </div>
                <?php endforeach;?>
            </div>
        </div>
        <?php
    }
}
