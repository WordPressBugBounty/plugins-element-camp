<?php
namespace ElementCampPlugin\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Utils;
use Elementor\Icons_Manager;
use Elementor\Repeater;

if (!defined('ABSPATH')) exit;

class ElementCamp_Flow_Network extends Widget_Base
{
    public function get_name()
    {
        return 'tcgelements-flow-network';
    }

    public function get_title()
    {
        return esc_html__('Flow Network', 'element-camp');
    }

    public function get_icon()
    {
        return 'eicon-flow tce-widget-badge';
    }

    public function get_script_depends()
    {
        return ['gsap', 'motion-path.min', 'tcgelements-flow-network'];
    }

    public function get_style_depends()
    {
        return ['tcgelements-flow-network'];
    }

    public function get_categories()
    {
        return ['elementcamp-elements'];
    }

    protected function _register_controls()
    {
        // ─── CENTER NODE ────────────────────────────────────────────────────────
        $this->start_controls_section(
            'section_center_node',
            [
                'label' => esc_html__('Center Node', 'element-camp'),
            ]
        );

        $this->add_control(
            'center_icon_type',
            [
                'label'   => esc_html__('Icon Type', 'element-camp'),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'icon'  => ['title' => esc_html__('Icon', 'element-camp'),  'icon' => 'eicon-star'],
                    'image' => ['title' => esc_html__('Image', 'element-camp'), 'icon' => 'eicon-image'],
                ],
                'default' => 'image',
                'toggle'  => false,
            ]
        );

        $this->add_control(
            'center_icon',
            [
                'label'     => esc_html__('Icon', 'element-camp'),
                'type'      => Controls_Manager::ICONS,
                'default'   => ['value' => 'fas fa-brain', 'library' => 'fa-solid'],
                'condition' => ['center_icon_type' => 'icon'],
            ]
        );

        $this->add_control(
            'center_image',
            [
                'label'     => esc_html__('Image', 'element-camp'),
                'type'      => Controls_Manager::MEDIA,
                'default'   => ['url' => Utils::get_placeholder_image_src()],
                'condition' => ['center_icon_type' => 'image'],
            ]
        );

        $this->end_controls_section();

        // ─── SATELLITE NODES ────────────────────────────────────────────────────
        $this->start_controls_section(
            'section_satellites',
            [
                'label' => esc_html__('Satellite Nodes', 'element-camp'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'node_icon_type',
            [
                'label'   => esc_html__('Content Type', 'element-camp'),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'icon'  => ['title' => esc_html__('Icon', 'element-camp'),  'icon' => 'eicon-star'],
                    'image' => ['title' => esc_html__('Image', 'element-camp'), 'icon' => 'eicon-image'],
                    'dot'   => ['title' => esc_html__('Dot', 'element-camp'),   'icon' => 'eicon-circle'],
                ],
                'default' => 'image',
                'toggle'  => false,
            ]
        );

        $repeater->add_control(
            'node_icon',
            [
                'label'     => esc_html__('Icon', 'element-camp'),
                'type'      => Controls_Manager::ICONS,
                'default'   => ['value' => 'fas fa-robot', 'library' => 'fa-solid'],
                'condition' => ['node_icon_type' => 'icon'],
            ]
        );

        $repeater->add_control(
            'node_image',
            [
                'label'     => esc_html__('Image', 'element-camp'),
                'type'      => Controls_Manager::MEDIA,
                'default'   => ['url' => Utils::get_placeholder_image_src()],
                'condition' => ['node_icon_type' => 'image'],
            ]
        );

        $repeater->add_control(
            'node_gx',
            [
                'label'   => esc_html__('Grid X (-1, 0, 1)', 'element-camp'),
                'type'    => Controls_Manager::NUMBER,
                'default' => -1,
                'min'     => -1,
                'max'     => 1,
                'step'    => 1,
            ]
        );

        $repeater->add_control(
            'node_gy',
            [
                'label'   => esc_html__('Grid Y (-1, 0, 1)', 'element-camp'),
                'type'    => Controls_Manager::NUMBER,
                'default' => -1,
                'min'     => -1,
                'max'     => 1,
                'step'    => 1,
            ]
        );

        $repeater->add_control(
            'node_dist',
            [
                'label'   => esc_html__('Distance (px)', 'element-camp'),
                'type'    => Controls_Manager::NUMBER,
                'default' => 280,
                'min'     => 50,
                'max'     => 800,
                'step'    => 10,
            ]
        );

        $repeater->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'node_background',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .flow-wrapper .node.satellite{{CURRENT_ITEM}}',
            ]
        );

        $repeater->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'node_border',
                'selector' => '{{WRAPPER}} .flow-wrapper .node.satellite{{CURRENT_ITEM}}',
            ]
        );


        $this->add_control(
            'satellites',
            [
                'label'       => esc_html__('Nodes', 'element-camp'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    ['node_icon_type' => 'image', 'node_image' => ['url' => Utils::get_placeholder_image_src()], 'node_gx' => -1, 'node_gy' => -1, 'node_dist' => 280],
                    ['node_icon_type' => 'image', 'node_image' => ['url' => Utils::get_placeholder_image_src()], 'node_gx' =>  0, 'node_gy' => -1, 'node_dist' => 220],
                    ['node_icon_type' => 'image', 'node_image' => ['url' => Utils::get_placeholder_image_src()], 'node_gx' =>  1, 'node_gy' => -1, 'node_dist' => 280],
                    ['node_icon_type' => 'dot',  'node_gx' => -1, 'node_gy' =>  0, 'node_dist' => 250],
                    ['node_icon_type' => 'dot',  'node_gx' =>  1, 'node_gy' =>  0, 'node_dist' => 250],
                    ['node_icon_type' => 'image', 'node_image' => ['url' => Utils::get_placeholder_image_src()], 'node_gx' => -1, 'node_gy' =>  1, 'node_dist' => 280],
                    ['node_icon_type' => 'image', 'node_image' => ['url' => Utils::get_placeholder_image_src()], 'node_gx' =>  0, 'node_gy' =>  1, 'node_dist' => 220],
                    ['node_icon_type' => 'image', 'node_image' => ['url' => Utils::get_placeholder_image_src()], 'node_gx' =>  1, 'node_gy' =>  1, 'node_dist' => 280],
                ],
                'title_field' => 'Node ({{{ node_gx }}}, {{{ node_gy }}})',
            ]
        );

        $this->end_controls_section();

        // ─── LAYOUT SETTINGS ────────────────────────────────────────────────────
        $this->start_controls_section(
            'section_layout',
            [
                'label' => esc_html__('Layout', 'element-camp'),
            ]
        );

        $this->add_responsive_control(
            'aspect_ratio',
            [
                'label'   => esc_html__('Aspect Ratio', 'element-camp'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    '16/9'  => '16:9',
                    '16/10' => '16:10',
                    '16/13' => '16:13',
                    '4/3'   => '4:3',
                    '1/1'   => '1:1',
                ],
                'default'  => '16/13',
                'selectors' => [
                    '{{WRAPPER}} .flow-wrapper' => 'aspect-ratio: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'main_color',
            [
                'label'     => esc_html__('Main Color', 'element-camp'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0055ff',
                'selectors' => [
                    '{{WRAPPER}} .flow-wrapper' => '--cr-main: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ─── CENTER NODE STYLE ──────────────────────────────────────────────────
        $this->start_controls_section(
            'section_center_style',
            [
                'label' => esc_html__('Center Node Style', 'element-camp'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'center_size',
            [
                'label'      => esc_html__('Size', 'element-camp'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 60, 'max' => 300]],
                'default'    => ['unit' => 'px', 'size' => 120],
                'selectors'  => [
                    '{{WRAPPER}} .flow-wrapper .node.central' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'center_bg_color',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .flow-wrapper .node.central',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'center_border',
                'selector' => '{{WRAPPER}} .flow-wrapper .node.central',
            ]
        );
        $this->add_control(
            'center_icon_color',
            [
                'label'     => esc_html__('Icon Color', 'element-camp'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .flow-wrapper .node.central i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .flow-wrapper .node.central svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'center_icon_size',
            [
                'label'      => esc_html__('Icon / Image Size', 'element-camp'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => ['px' => ['min' => 10, 'max' => 200]],
                'default'    => ['unit' => 'px', 'size' => 60],
                'selectors'  => [
                    '{{WRAPPER}} .flow-wrapper .node.central i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .flow-wrapper .node.central svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .flow-wrapper .node.central img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ─── SATELLITE NODE STYLE ───────────────────────────────────────────────
        $this->start_controls_section(
            'section_satellite_style',
            [
                'label' => esc_html__('Satellite Nodes Style', 'element-camp'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'satellite_size',
            [
                'label'      => esc_html__('Node Size', 'element-camp'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 30, 'max' => 200]],
                'default'    => ['unit' => 'px', 'size' => 60],
                'selectors'  => [
                    '{{WRAPPER}} .flow-wrapper .node.satellite' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'satellite_bg',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .flow-wrapper .node.satellite',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'satellite_border',
                'selector' => '{{WRAPPER}} .flow-wrapper .node.satellite',
                'fields_options' => [
                    'border' => ['default' => 'solid'],
                    'width'  => ['default' => ['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1, 'unit' => 'px']],
                    'color'  => ['default' => 'rgba(74,70,81,0.23)'],
                ],
            ]
        );
        $this->add_control(
            'satellite_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .flow-wrapper .node.satellite' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'satellite_icon_color',
            [
                'label'     => esc_html__('Icon Color', 'element-camp'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#aaa',
                'selectors' => [
                    '{{WRAPPER}} .flow-wrapper .node.satellite i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'satellite_icon_size',
            [
                'label'      => esc_html__('Icon / Image Size', 'element-camp'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => ['px' => ['min' => 10, 'max' => 100]],
                'default'    => ['unit' => 'px', 'size' => 21],
                'selectors'  => [
                    '{{WRAPPER}} .flow-wrapper .node.satellite i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .flow-wrapper .node.satellite .ico img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

        $this->end_controls_section();

        // ─── DOT STYLE ──────────────────────────────────────────────────────────
        $this->start_controls_section(
            'section_dot_style',
            [
                'label' => esc_html__('Dot Style', 'element-camp'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'dot_size',
            [
                'label'      => esc_html__('Dot Size', 'element-camp'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 4, 'max' => 40]],
                'default'    => ['unit' => 'px', 'size' => 10],
                'selectors'  => [
                    '{{WRAPPER}} .flow-wrapper .node.satellite .dot' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();

        // ─── PATH STYLE ─────────────────────────────────────────────────────────
        $this->start_controls_section(
            'section_path_style',
            [
                'label' => esc_html__('Connection Paths', 'element-camp'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'path_color',
            [
                'label'     => esc_html__('Path Color', 'element-camp'),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(0,85,255,0.2)',
                'selectors' => [
                    '{{WRAPPER}} .flow-wrapper .connection-path' => 'stroke: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'path_hover_color',
            [
                'label'   => esc_html__('Path Hover Color', 'element-camp'),
                'type'    => Controls_Manager::COLOR,
                'default' => '#0055ff',
            ]
        );

        $this->add_responsive_control(
            'path_width',
            [
                'label'      => esc_html__('Stroke Width', 'element-camp'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0.5, 'max' => 10, 'step' => 0.5]],
                'default'    => ['unit' => 'px', 'size' => 1.5],
                'selectors'  => [
                    '{{WRAPPER}} .flow-wrapper .connection-path' => 'stroke-width: {{SIZE}};',
                ],
            ]
        );

        $this->add_control(
            'particle_color',
            [
                'label'     => esc_html__('Particle Color', 'element-camp'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .flow-wrapper .light-particle' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings   = $this->get_settings_for_display();
        $satellites = $settings['satellites'];
        $unique_id = 'paths-' . $this->get_id();

        $path_hover  = !empty($settings['path_hover_color']) ? $settings['path_hover_color'] : '#0055ff';
        ?>
        <div class="flow-wrapper position-relative"
             data-path-hover="<?php echo esc_attr($path_hover); ?>">

            <!-- SVG for connection lines -->
            <svg class="flow-lines position-absolute w-100 h-100 top-0 left-0"
                 viewBox="0 0 1000 600"
                 preserveAspectRatio="none">
                <g id="<?php echo esc_attr($unique_id); ?>"></g>
            </svg>

            <div class="nodes-container position-relative">
                <!-- Satellite Nodes -->
                <?php foreach ($satellites as $index => $node) :
                    $gx      = isset($node['node_gx'])        ? (float)$node['node_gx'] : 0;
                    $gy      = isset($node['node_gy'])        ? (float)$node['node_gy'] : 0;
                    $dist    = isset($node['node_dist'])      ? (float)$node['node_dist'] : 280;
                    $type    = $node['node_icon_type'] ?? 'dot';
                    ?>
                    <div class="node satellite elementor-repeater-item-<?php echo esc_attr($node['_id']); ?>"
                         data-gx="<?php echo esc_attr($gx); ?>"
                         data-gy="<?php echo esc_attr($gy); ?>"
                         data-dist="<?php echo esc_attr($dist); ?>">

                        <?php if ($type === 'icon' && !empty($node['node_icon']['value'])) : ?>
                            <div class="ico">
                                <?php Icons_Manager::render_icon($node['node_icon'], ['aria-hidden' => 'true']); ?>
                            </div>
                        <?php elseif ($type === 'image' && !empty($node['node_image']['url'])) : ?>
                            <div class="ico">
                                <img src="<?php echo esc_url($node['node_image']['url']); ?>"
                                     alt="<?php echo esc_attr($node['node_image']['alt'] ?? ''); ?>">
                            </div>
                        <?php else : ?>
                            <div class="dot"></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <!-- Central node -->
                <div class="node central">
                    <div class="hub-icon">
                        <?php
                        if ($settings['center_icon_type'] === 'image' && !empty($settings['center_image']['url'])) {
                            echo '<img src="' . esc_url($settings['center_image']['url']) . '" alt="">';
                        } else {
                            Icons_Manager::render_icon($settings['center_icon'], ['aria-hidden' => 'true']);
                        }
                        ?>
                    </div>
                </div>

            </div><!-- .nodes-container -->
        </div><!-- .flow-wrapper -->
        <?php
    }
}