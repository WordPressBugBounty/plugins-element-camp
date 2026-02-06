<?php
namespace ElementCampPlugin\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Repeater;

if (!defined('ABSPATH')) exit;

class ElementCamp_Image_Scroll_Animation extends Widget_Base
{
    public function get_name()
    {
        return 'tcgelements-image-scroll-animation';
    }

    public function get_title()
    {
        return esc_html__('Image Scroll Animation', 'element-camp');
    }

    public function get_icon()
    {
        return 'eicon-slider-full-screen tce-widget-badge';
    }

    public function get_categories()
    {
        return ['elementcamp-elements'];
    }

    public function get_script_depends() {
        return ['gsap', 'scroll-trigger', 'tcgelements-image-scroll-animation'];
    }

    public function get_style_depends() {
        return ['tcgelements-image-scroll'];
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
            'rectangles',
            [
                'label' => esc_html__('Images', 'element-camp'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    ['image' => ['url' => Utils::get_placeholder_image_src()]],
                    ['image' => ['url' => Utils::get_placeholder_image_src()]],
                    ['image' => ['url' => Utils::get_placeholder_image_src()]],
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__('Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'height',
            [
                'label' => esc_html__('Height', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => ['min' => 300, 'max' => 1000],
                    'vh' => ['min' => 30, 'max' => 100],
                ],
                'default' => ['unit' => 'px', 'size' => 500],
                'selectors' => [
                    '{{WRAPPER}} .container-full' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .rectangle' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 50]],
                'default' => ['unit' => 'px', 'size' => 15],
                'selectors' => [
                    '{{WRAPPER}} .rectangle' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'spacing',
            [
                'label' => esc_html__('Spacing', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 20]],
                'default' => ['unit' => 'px', 'size' => 5],
                'selectors' => [
                    '{{WRAPPER}} .rectangle' => 'margin: 0 {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="tcgelements-image-scroll-animation">
            <?php foreach ($settings['rectangles'] as $index => $item) : ?>
                <div class="rectangle">
                    <?php if (!empty($item['image']['url'])) : ?>
                        <img src="<?php echo esc_url($item['image']['url']); ?>" alt="">
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}