<?php

namespace ElementCampPlugin\Widgets;

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

use Elementor\Widget_Base;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Core\Schemes\Typography;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Frontend;
use Elementor\Icons_Manager;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Image_Size;
use Elementor\Scheme_Base;
use Elementor\Group_Control_Background;

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}
/**
 * @since 1.0.0
 */


class ElementCamp_Social_Share extends Widget_Base
{

	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name()
	{
		return 'tcgelements-social-share';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve social share widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title()
	{
		return esc_html__('Social Share', 'element-camp');
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve social share widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon()
	{
		return 'eicon-share tce-widget-badge';
	}
	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords()
	{
		return ['social', 'share', 'link'];
	}
	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories()
	{
		return ['elementcamp-elements'];
	}
	/**
	 * Register social share widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 3.1.0
	 * @access protected
	 */

	/**
	 * Elementor icon widget.
	 *
	 * Elementor widget that displays an icon from over 600+ icons.
	 *
	 * @since 1.0.0
	 */
	protected function register_controls()
	{
		$this->start_controls_section(
			'section_settings',
			[
				'label' => esc_html__('Settings', 'element-camp'),
			]
		);

		$this->add_control(
			'share_text',
			[
				'label' => __('Share Text', 'element-camp'),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __('Share On:', 'element-camp'),
			]
		);

		$this->add_control(
			'facebook_share_button',
			[
				'label' => esc_html__('Facebook Share Button', 'element-camp'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return' => 'yes',
			]
		);

		$this->add_control(
			'twitter_share_button',
			[
				'label' => esc_html__('Twitter Share Button', 'element-camp'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return' => 'yes',
			]
		);

		$this->add_control(
			'pinterest_share_button',
			[
				'label' => esc_html__('Pinterest Share Button', 'element-camp'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return' => 'yes',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_share_text_style',
			[
				'label' => esc_html__('Text Style', 'element-camp'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'text_margin',
			[
				'label' => esc_html__('Margin', 'element-camp'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', 'rem', '%', 'custom'],
				'default' => [
					'unit' => 'em',
				],
				'tablet_default' => [
					'unit' => 'em',
				],
				'mobile_default' => [
					'unit' => 'em',
				],
				'range' => [
					'px' => [
						'max' => 50,
					],
					'em' => [
						'min' => 0,
						'max' => 5,
					],
					'rem' => [
						'min' => 0,
						'max' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tcgelements-social-share .share-text' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'selector' => '{{WRAPPER}} .tcgelements-social-share .share-text',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => esc_html__('Color', 'element-camp'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tcgelements-social-share .share-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'text_opacity',
			[
				'label' => esc_html__('Text Opacity', 'element-camp'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'step' => 0.1,
						'max' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tcgelements-social-share .share-text' => 'opacity: {{SIZE}};',
				],
			]
		);

        $this->add_control(
            'social_share_text_style_dark_mode',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'social_share_text_color_dark_mode',
            [
                'label' => esc_html__('Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-social-share .share-text' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-social-share .share-text' => 'color: {{VALUE}};',
                ],
            ]
        );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_share_icons_style',
			[
				'label' => esc_html__('Icons Style', 'element-camp'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs('tabs_social_share_style');

		$this->start_controls_tab(
			'tab_social_share_normal',
			[
				'label' => esc_html__('Normal', 'element-camp'),
			]
		);

		$this->add_control(
			'icon_primary_color',
			[
				'label' => esc_html__('Primary Color', 'element-camp'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tcgelements-social-share a' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_secondary_color',
			[
				'label' => esc_html__('Secondary Color', 'element-camp'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tcgelements-social-share a' => 'color: {{VALUE}};',
				],
			]
		);

        $this->add_control(
            'social_share_icon_style_dark_mode',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'social_share_icon_primary_color_dark_mode',
            [
                'label' => esc_html__('Primary Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-social-share a' => 'background-color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-social-share a' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'social_share_icon_secondary_color_dark_mode',
            [
                'label' => esc_html__('Secondary Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-social-share a' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-social-share a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

		$this->start_controls_tab(
			'tab_social_share_hover',
			[
				'label' => esc_html__('Hover', 'element-camp'),
			]
		);

		$this->add_control(
			'icon_primary_color_hover',
			[
				'label' => esc_html__('Primary Color', 'element-camp'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tcgelements-social-share a:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_secondary_color_hover',
			[
				'label' => esc_html__('Secondary Color', 'element-camp'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tcgelements-social-share a:hover' => 'color: {{VALUE}};',
				],
			]
		);

        $this->add_control(
            'social_share_icon_style_dark_mode_hover',
            [
                'label' => esc_html__('Dark Mode', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'social_share_icon_primary_color_dark_mode_hover',
            [
                'label' => esc_html__('Primary Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-social-share a:hover' => 'background-color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-social-share a:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'social_share_icon_secondary_color_dark_mode_hover',
            [
                'label' => esc_html__('Secondary Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-social-share a:hover' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-social-share a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__('Icon Size', 'element-camp'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tcgelements-social-share a' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'icon_container_size',
			[
				'label' => esc_html__('Icon Container Size', 'element-camp'),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .tcgelements-social-share a' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],
				'default' => [
					'unit' => 'px',
				],
				'tablet_default' => [
					'unit' => 'px',
				],
				'mobile_default' => [
					'unit' => 'px',
				],
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 150,
					],
				],
			]
		);

		$this->add_responsive_control(
			'icon_margin',
			[
				'label' => __('Margin', 'element-camp'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .tcgelements-social-share a' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'icon_border',
				'selector' => '{{WRAPPER}} .tcgelements-social-share a',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_border_radius',
			[
				'label' => esc_html__('Border Radius', 'element-camp'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .tcgelements-social-share a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render icon widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render()
	{
		$settings = $this->get_settings_for_display(); ?>
		<div class="tcgelements-social-share">
			<?php if (!empty($settings['share_text'])): ?>
				<span class="share-text"><?php esc_html_e($settings['share_text'], "element-camp"); ?> </span>
			<?php endif; ?>

			<?php if ($settings['facebook_share_button'] == 'yes'): ?>
				<a class="fb-share f-bg" href="http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>&amp;t=<?php echo rawurlencode(get_the_title());  ?>"
					title="<?php esc_html_e("Share on Facebook", "element-camp"); ?>">
					<i class="fa fa-facebook"></i>
				</a>
			<?php endif; ?>

			<?php if ($settings['twitter_share_button'] == 'yes'): ?>
				<a class="tw-share tw-bg" href="http://twitter.com/home/?status=<?php echo rawurlencode(get_the_title());  ?>%20-%20<?php the_permalink(); ?>"
					title="<?php esc_html_e("Tweet this", "element-camp"); ?>">
					<i class="fab fa-x-twitter"></i>
				</a>
			<?php endif; ?>



			<?php if ($settings['pinterest_share_button'] == 'yes'): ?>
				<a class="pin-bg" href="http://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&media=<?php
																													global $post;
																													$url = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
																													echo $url; ?>"
					title="<?php esc_html_e("Pin This", "element-camp"); ?>">
					<i class="fa fa-pinterest-p"></i>
				</a>
			<?php endif; ?>

		</div>
<?php
	}
}
