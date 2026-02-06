<?php
namespace ElementCampPlugin\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Repeater;

if (!defined('ABSPATH')) exit;

class ElementCamp_Pages_Content extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'tcgelements-pages-content';
    }

    public function get_title()
    {
        return esc_html__('Pages Content', 'element-camp');
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
        return ['tcgelements-pages-content'];
    }

    protected function _register_controls()
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Settings', 'element-camp'),
            ]
        );

        $repeater = new Repeater();

        // Left Item
        $repeater->add_control(
            'left_image',
            [
                'label' => esc_html__('Left Item Image', 'element-camp'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );
        $repeater->add_control(
            'left_link',
            [
                'label' => esc_html__('Left Item Link', 'element-camp'),
                'type' => Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'element-camp'),
            ]
        );

        // Center Item
        $repeater->add_control(
            'center_image',
            [
                'label' => esc_html__('Center Item Image', 'element-camp'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );
        $repeater->add_control(
            'center_link',
            [
                'label' => esc_html__('Center Item Link', 'element-camp'),
                'type' => Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'element-camp'),
            ]
        );

        // Right Item
        $repeater->add_control(
            'right_image',
            [
                'label' => esc_html__('Right Item Image', 'element-camp'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );
        $repeater->add_control(
            'right_link',
            [
                'label' => esc_html__('Right Item Link', 'element-camp'),
                'type' => Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'element-camp'),
            ]
        );

        $this->add_control(
            'items',
            [
                'label' => esc_html__('Items', 'element-camp'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="tcgelements-pages-content">
            <?php foreach($settings['items'] as $item) : ?>
                <a href="<?php echo esc_url($item['left_link']['url']); ?>" class="img l-item" <?php if ( $item['left_link']['is_external'] ) echo'target="_blank"'; ?>>
                    <img src="<?php echo esc_url($item['left_image']['url']); ?>" alt="<?php if (!empty($item['left_image']['alt'])) echo esc_attr($item['left_image']['alt']); ?>">
                </a>
                <a href="<?php echo esc_url($item['center_link']['url']); ?>" class="img c-item" <?php if ( $item['center_link']['is_external'] ) echo'target="_blank"'; ?>>
                    <img src="<?php echo esc_url($item['center_image']['url']); ?>" alt="<?php if (!empty($item['center_image']['alt'])) echo esc_attr($item['center_image']['alt']); ?>">
                </a>
                <a href="<?php echo esc_url($item['right_link']['url']); ?>" class="img r-item" <?php if ( $item['right_link']['is_external'] ) echo'target="_blank"'; ?>>
                    <img src="<?php echo esc_url($item['right_image']['url']); ?>" alt="<?php if (!empty($item['right_image']['alt'])) echo esc_attr($item['right_image']['alt']); ?>">
                </a>
            <?php endforeach;?>
        </div>
        <?php
    }
}
