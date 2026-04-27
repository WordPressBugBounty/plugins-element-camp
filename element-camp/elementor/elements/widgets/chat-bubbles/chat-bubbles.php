<?php

namespace ElementCampPlugin\Widgets;
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;

/**
 * Elementor Chat Bubbles Widget
 *
 * @since 1.0.0
 */
class ElementCamp_Chat_Bubbles extends Widget_Base
{

    /**
     * Retrieve the widget name.
     */
    public function get_name()
    {
        return 'tcgelements-chat-bubbles';
    }

    /**
     * Retrieve the widget title.
     */
    public function get_title()
    {
        return esc_html__('Chat Bubbles', 'element-camp');
    }

    /**
     * Retrieve the widget icon.
     */
    public function get_icon()
    {
        return 'eicon-comments tce-widget-badge';
    }

    /**
     * Retrieve the list of categories the widget belongs to.
     */
    public function get_categories()
    {
        return ['elementcamp-elements'];
    }

    /**
     * Retrieve the list of scripts the widget depended on.
     */
    public function get_script_depends()
    {
        return ['tcgelements-chat-bubbles'];
    }

    /**
     * Register the widget controls.
     */
    protected function register_controls()
    {
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'element-camp'),
            ]
        );

        $conversation_repeater = new \Elementor\Repeater();

        $conversation_repeater->add_control(
            'sender_name',
            [
                'label' => esc_html__('Sender Name', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('User', 'element-camp'),
                'placeholder' => esc_html__('Enter sender name', 'element-camp'),
            ]
        );

        $conversation_repeater->add_control(
            'message',
            [
                'label' => esc_html__('Message', 'element-camp'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__('How can I help you?', 'element-camp'),
            ]
        );

        $conversation_repeater->add_control(
            'avatar_type',
            [
                'label' => esc_html__('Avatar Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'icon',
                'options' => [
                    'icon' => esc_html__('Icon', 'element-camp'),
                    'image' => esc_html__('Image', 'element-camp'),
                ],
            ]
        );

        $conversation_repeater->add_control(
            'avatar_icon',
            [
                'label' => esc_html__('Icon', 'element-camp'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-user',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'avatar_type' => 'icon',
                ],
            ]
        );

        $conversation_repeater->add_control(
            'avatar_image',
            [
                'label' => esc_html__('Image', 'element-camp'),
                'type' => Controls_Manager::MEDIA,
                'condition' => [
                    'avatar_type' => 'image',
                ],
            ]
        );

        $conversation_repeater->add_control(
            'custom_tab_style_switcher',
            [
                'label' => esc_html__('Custom Style', 'element-camp'),
                'description' => esc_html__('Add custom style for this bubble', 'element-camp'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $conversation_repeater->add_control(
            'custom_tab_style_heading',
            [
                'label' => __('Custom Style', 'element-camp'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition'=>['custom_tab_style_switcher'=>'yes']
            ]
        );

        $conversation_repeater->add_control(
            'item_avatar_icon_color',
            [
                'label' => __('Avatar Icon Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .avatar i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} {{CURRENT_ITEM}} .avatar svg' => 'fill: {{VALUE}}',
                ],
                'condition'=>['custom_tab_style_switcher'=>'yes']
            ]
        );

        $conversation_repeater->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'item_avatar_background',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .avatar',
                'condition'=>['custom_tab_style_switcher'=>'yes']
            ]
        );

        $conversation_repeater->add_control(
            'item_bubble_background_color',
            [
                'label' => __('Bubble Background', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .content' => 'background-color: {{VALUE}}',
                ],
                'condition'=>['custom_tab_style_switcher'=>'yes']
            ]
        );

        $this->add_control(
            'conversation',
            [
                'label' => esc_html__('Conversation', 'element-camp'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $conversation_repeater->get_controls(),
                'default' => [
                    [
                        'sender_name' => esc_html__('Ahmed', 'element-camp'),
                        'message' => esc_html__('Hey, how are you doing?', 'element-camp'),
                        'avatar_icon' => ['value' => 'fas fa-user', 'library' => 'fa-solid'],
                    ],
                    [
                        'sender_name' => esc_html__('Mohamed', 'element-camp'),
                        'message' => esc_html__('I\'m great! Just finished working on a new project. How about you?', 'element-camp'),
                        'avatar_icon' => ['value' => 'fas fa-user-circle', 'library' => 'fa-solid'],
                    ],
                    [
                        'sender_name' => esc_html__('Ahmed', 'element-camp'),
                        'message' => esc_html__('That sounds exciting! Tell me more about it.', 'element-camp'),
                        'avatar_icon' => ['value' => 'fas fa-user', 'library' => 'fa-solid'],
                    ],
                ],
                'title_field' => '{{{ sender_name }}}',
            ]
        );

        $this->end_controls_section();

        // Animation Settings
        $this->start_controls_section(
            'animation_settings',
            [
                'label' => esc_html__('Animation Settings', 'element-camp'),
            ]
        );

        $this->add_control(
            'display_mode',
            [
                'label' => esc_html__('Display Mode', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'all',
                'options' => [
                    'all' => esc_html__('Show All Messages', 'element-camp'),
                    'two' => esc_html__('Two Bubbles (Change Content)', 'element-camp'),
                ],
                'description' => esc_html__('Choose how to display messages: show all bubbles one by one, or use only 2 bubbles and change their content', 'element-camp'),
            ]
        );

        $this->add_control(
            'display_mode_notice',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => '<div style="background: #e3f2fd; padding: 10px; border-left: 3px solid #2196f3; margin-top: 10px;">
                    <strong>' . esc_html__('Two Bubbles Mode:', 'element-camp') . '</strong><br>
                    ' . esc_html__('Only the first 2 chat bubbles will be visible. The content will change dynamically between all conversation messages.', 'element-camp') . '
                </div>',
                'condition' => [
                    'display_mode' => 'two',
                ],
            ]
        );

        $this->add_control(
            'typing_speed',
            [
                'label' => esc_html__('Typing Speed (ms)', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'default' => 30,
                'min' => 10,
                'max' => 200,
            ]
        );

        $this->add_control(
            'pause_after_message',
            [
                'label' => esc_html__('Pause After Message (ms)', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'default' => 2000,
                'min' => 500,
                'max' => 10000,
            ]
        );

        $this->add_control(
            'pause_between_turns',
            [
                'label' => esc_html__('Pause Between Turns (ms)', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'default' => 1000,
                'min' => 0,
                'max' => 5000,
            ]
        );

        $this->end_controls_section();

        // Container Style
        $this->start_controls_section(
            'container_style',
            [
                'label' => esc_html__('Container Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label' => esc_html__('Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-chat-bubbles' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-chat-bubbles' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'container_background',
                'selector' => '{{WRAPPER}} .tcgelements-chat-bubbles',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'selector' => '{{WRAPPER}} .tcgelements-chat-bubbles',
            ]
        );

        $this->add_responsive_control(
            'container_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-chat-bubbles' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'container_box_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-chat-bubbles',
            ]
        );

        $this->end_controls_section();

        // Bubble Style
        $this->start_controls_section(
            'bubble_style',
            [
                'label' => esc_html__('Bubble Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'bubble_spacing',
            [
                'label' => esc_html__('Bubble Spacing', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 30,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .chat-bubble' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'bubble_gap',
            [
                'label' => esc_html__('Avatar Gap', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 15,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .chat-bubble' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'bubble_content_padding',
            [
                'label' => esc_html__('Content Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'default' => [
                    'top' => 15,
                    'right' => 20,
                    'bottom' => 15,
                    'left' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .chat-bubble .content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'bubble_max_width',
            [
                'label' => esc_html__('Max Width', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%', 'px'],
                'range' => [
                    '%' => [
                        'min' => 30,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 200,
                        'max' => 1000,
                    ],
                ],
                'default' => [
                    'size' => 80,
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .chat-bubble .content' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'bubble_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'default' => [
                    'top' => 20,
                    'right' => 20,
                    'bottom' => 20,
                    'left' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .chat-bubble .content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'bubble_background',
                'selector' => '{{WRAPPER}} .chat-bubble .content',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'bubble_border',
                'selector' => '{{WRAPPER}} .chat-bubble .content',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'bubble_box_shadow',
                'selector' => '{{WRAPPER}} .chat-bubble .content',
            ]
        );

        $this->end_controls_section();

        // Avatar Style
        $this->start_controls_section(
            'avatar_style',
            [
                'label' => esc_html__('Avatar Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'avatar_size',
            [
                'label' => esc_html__('Size', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 30,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 45,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .chat-bubble .avatar' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'avatar_icon_size',
            [
                'label' => esc_html__('Icon Size', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .chat-bubble .avatar i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .chat-bubble .avatar svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'avatar_color',
            [
                'label' => esc_html__('Icon Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .chat-bubble .avatar i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .chat-bubble .avatar svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'avatar_background',
                'selector' => '{{WRAPPER}} .chat-bubble .avatar',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'avatar_border',
                'selector' => '{{WRAPPER}} .chat-bubble .avatar',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'avatar_box_shadow',
                'selector' => '{{WRAPPER}} .chat-bubble .avatar',
            ]
        );

        $this->end_controls_section();

        // Sender Name Style
        $this->start_controls_section(
            'sender_style',
            [
                'label' => esc_html__('Sender Name Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'sender_typography',
                'selector' => '{{WRAPPER}} .chat-bubble .sender',
            ]
        );

        $this->add_responsive_control(
            'sender_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .chat-bubble .sender' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'sender_color',
            [
                'label' => esc_html__('Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .chat-bubble .sender' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Message Text Style
        $this->start_controls_section(
            'text_style',
            [
                'label' => esc_html__('Message Text Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .chat-bubble .text',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => esc_html__('Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .chat-bubble .text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'text_min_height',
            [
                'label' => esc_html__('Min Height', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .chat-bubble .text' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render the widget output on the frontend.
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();

        // Convert conversation to JSON for JavaScript
        $conversation_data = [];
        foreach ($settings['conversation'] as $item) {
            $conversation_data[] = [
                'sender' => $item['sender_name'],
                'text' => $item['message'],
            ];
        }

        // Determine how many bubbles to render based on display mode
        $display_mode = $settings['display_mode'];
        $bubbles_to_render = ($display_mode === 'two') ? array_slice($settings['conversation'], 0, 2) : $settings['conversation'];
        ?>

        <div class="tcgelements-chat-bubbles"
             data-typing-speed="<?php echo esc_attr($settings['typing_speed']); ?>"
             data-pause-after="<?php echo esc_attr($settings['pause_after_message']); ?>"
             data-pause-between="<?php echo esc_attr($settings['pause_between_turns']); ?>"
             data-display-mode="<?php echo esc_attr($display_mode); ?>"
             data-conversation='<?php echo esc_attr(json_encode($conversation_data)); ?>'>

            <?php foreach ($bubbles_to_render as $index => $item) :
                $bubble_id = 'bubble-' . $index;
                ?>

                <div class="chat-bubble <?php echo 'elementor-repeater-item-' . esc_attr($item['_id']); ?>"
                     id="<?php echo esc_attr($bubble_id); ?>"
                     data-index="<?php echo esc_attr($index); ?>">

                    <div class="avatar">
                        <?php if ($item['avatar_type'] === 'icon') : ?>
                            <?php Icons_Manager::render_icon($item['avatar_icon'], ['aria-hidden' => 'true']); ?>
                        <?php else : ?>
                            <img src="<?php echo esc_url($item['avatar_image']['url']); ?>"
                                 alt="<?php echo esc_attr($item['sender_name']); ?>">
                        <?php endif; ?>
                    </div>

                    <div class="content">
                        <div class="sender"><?php echo esc_html($item['sender_name']); ?></div>
                        <div class="text" data-full-text="<?php echo esc_attr($item['message']); ?>"></div>
                    </div>

                </div>

            <?php endforeach; ?>

        </div>
        <?php
    }
}