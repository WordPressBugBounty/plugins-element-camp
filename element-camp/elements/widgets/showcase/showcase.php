<?php

namespace ElementCampPlugin\Widgets;
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Text_Shadow;
use Elementor\POPOVER_TOGGLE;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;
use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Frontend;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Image_Size;
use Elementor\Scheme_Base;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Css_Filter;
use ThemescampPlugin\Elementor\Controls\Helper as ControlsHelper;

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class ElementCamp_Showcase extends Widget_Base
{

    /**
     * Retrieve the widget name.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'tcgelements-showcase';
    }

    /**
     * Retrieve the widget title.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return esc_html__('Showcase', 'element-camp');
    }

    /**
     * Retrieve the widget icon.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'eicon-posts-ticker tce-widget-badge';
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
     * Retrieve the list of scripts the widget depended on.
     *
     * Used to set scripts dependencies required to run the widget.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return array Widget scripts dependencies.
     */
    public function get_script_depends()
    {
        return ['tcgelements-showcase','tcgelements-float-cursor-animation'];
    }

    /**
     * Register the widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    protected function register_controls()
    {
        $post_types = ControlsHelper::get_post_types();
        $post_types['by_id'] = __('Manual Selection', 'themescamp-plugin');
        $taxonomies = get_taxonomies([], 'objects');

        $this->start_controls_section(
            'content',
            [
                'label' => esc_html__('Content', 'element-camp'),
            ]
        );

        $this->add_control(
            'display_terms',
            [
                'label' => esc_html__('Show Terms', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'element-camp'),
                'label_off' => esc_html__('Off', 'element-camp'),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'display_terms_type',
            [
                'label' => esc_html__('Display Terms Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'categories' => esc_html__('Categories', 'element-camp'),
                    'tags' => esc_html__('Tags', 'element-camp'),
                ],
                'condition' => ['display_terms' => 'yes'],
                'default' => 'categories',
            ]
        );
        $this->add_control(
            'meta_separator',
            [
                'label' => esc_html__( 'Categories/tags Separator', 'element-camp' ),
                'type' => Controls_Manager::TEXT,
                'default' => ' / ',
                'condition' => ['display_terms' => 'yes']
            ]
        );

        $this->add_control(
            'show_count',
            [
                'label' => esc_html__('Show Count', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'element-camp'),
                'label_off' => esc_html__('Hide', 'element-camp'),
            ]
        );

        $this->add_control(
            'number_format',
            [
                'label' => esc_html__('Number Format', 'elementcamp_plg'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'normal' => esc_html__('Normal', 'elementcamp_plg'),
                    'leading_zero' => esc_html__('01 Format', 'elementcamp_plg'),
                ],
                'default' => 'normal',
                'condition' => ['show_count' => 'yes'],
            ]
        );

        $this->add_control(
            'show_date',
            [
                'label' => esc_html__('Show Date', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'element-camp'),
                'label_off' => esc_html__('Off', 'element-camp'),
            ]
        );

        $this->add_control(
            'date_position',
            [
                'label' => esc_html__('Date Position', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'after',
                'options' => [
                    'after' => esc_html__('After Taxonomy', 'element-camp'),
                    'before' => esc_html__('Before Taxonomy', 'element-camp'),
                ],
                'condition' => [
                    'show_date' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'date_format',
            [
                'label' => esc_html__('Date Format', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => 'Default',
                    '0' => _x('March 6, 2018 (F j, Y)', 'Date Format', 'element-camp'),
                    '1' => '2018-03-06 (Y-m-d)',
                    '2' => '03/06/2018 (m/d/Y)',
                    '3' => '06/03/2018 (d/m/Y)',
                    'custom' => esc_html__('Custom', 'element-camp'),
                ],
                'condition' => [
                    'show_date' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'custom_date_format',
            [
                'label' => esc_html__('Custom Date Format', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'default' => 'F j, Y',
                'condition' => [
                    'show_date' => 'yes',
                    'date_format' => 'custom',
                ],
                'description' => sprintf(
                /* translators: %s: Allowed data letters (see: http://php.net/manual/en/function.date.php). */
                    __('Use the letters: %s', 'element-camp'),
                    'l D d j S F m M n Y y'
                ),
            ]
        );

        $this->add_control(
            'button_switcher',
            [
                'label' => esc_html__( 'Button Switcher', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'yes', 'element-camp' ),
                'label_off' => esc_html__( 'No', 'element-camp' ),
            ]
        );
        $this->add_control(
            'btn_text',
            [
                'label' => esc_html__('Button Text', 'element-camp'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__('Click me', 'element-camp'),
                'condition' => ['button_switcher'=>'yes'],
            ]
        );
        $this->add_control(
            'selected_icon',
            [
                'label' => esc_html__('Icon', 'element-camp'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'skin' => 'inline',
                'label_block' => false,
                'condition' => ['button_switcher'=>'yes'],
            ]
        );
        $this->add_control(
            'post_type',
            [
                'label' => esc_html__('Source', 'themescamp-core'),
                'type' => Controls_Manager::SELECT,
                'options' => $post_types,
                'default' => key($post_types),
            ]
        );
        $this->add_control(
            'posts_ids',
            [
                'label' => __('Search & Select', 'themescamp-plugin'),
                'type' => 'tcg-select2',
                'options' => ControlsHelper::get_post_list(),
                'label_block' => true,
                'multiple'    => true,
                'source_name' => 'post_type',
                'source_type' => 'any',
                'condition' => [
                    'post_type' => 'by_id',
                ],
            ]
        );
        $this->add_control(
            'posts_per_page',
            [
                'label' => __('Posts Per Page', 'themescamp-plugin'),
                'type' => Controls_Manager::NUMBER,
                'default' => '4',
                'min' => '1',
            ]
        );
        $this->add_control(
            'offset',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'default' => '0',
            ]
        );
        $this->add_control(
            'order',
            [
                'label' => __('Order', 'themescamp-plugin'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'asc' => 'Ascending',
                    'desc' => 'Descending',
                ],
                'default' => 'desc',

            ]
        );
        $this->add_control(
            'orderby',
            [
                'label' => __('Order By', 'themescamp-plugin'),
                'type' => Controls_Manager::SELECT,
                'options' => ControlsHelper::get_post_orderby_options(),
                'default' => 'date',

            ]
        );
        $this->add_control(
            'authors',
            [
                'label' => __('Author', 'themescamp-plugin'),
                'label_block' => true,
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'default' => [],
                'options' => ControlsHelper::get_authors_list(),
                'condition' => [
                    'post_type!' => ['by_id', 'source_dynamic'],
                ],
            ]
        );
        $this->add_control(
            'post__not_in',
            [
                'label'       => __('Exclude', 'themescamp-plugin'),
                'type'        => 'tcg-select2',
                'label_block' => true,
                'multiple'    => true,
                'source_name' => 'post_type',
                'source_type' => 'any',
                'condition'   => [
                    'post_type!' => ['by_id', 'source_dynamic'],
                ],
            ]
        );
        foreach ($taxonomies as $taxonomy => $object) {
            if (!isset($object->object_type[0]) || !in_array($object->object_type[0], array_keys($post_types))) {
                continue;
            }

            $this->add_control(
                $taxonomy . '_ids',
                [
                    'label' => $object->label,
                    'type' => 'tcg-select2',
                    'label_block' => true,
                    'multiple' => true,
                    'source_name' => 'taxonomy',
                    'source_type' => $taxonomy,
                    'use_taxonomy_slug' => true,
                    'condition' => [
                        'post_type' => $object->object_type,
                    ],
                ]
            );
        }
        $this->add_control(
            'float_cursor_animation',
            [
                'label' => esc_html__('Float Cursor Animation', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'element-camp'),
                'label_off' => esc_html__('Off', 'element-camp'),
            ]
        );
        $this->add_control(
            'float_cursor_animation_text',
            [
                'label' => esc_html__('Float Cursor Text', 'element-camp'),
                'default' => esc_html__('Hold And Drag', 'element-camp'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'condition' => [
                    'float_cursor_animation' => 'yes',
                ]
            ]
        );
        $this->add_control(
            'info_icon',
            [
                'label'         => esc_html__( 'Info Icon', 'element-camp' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Yes', 'element-camp' ),
                'label_off'     => esc_html__( 'No', 'element-camp' ),
                'return_value'  => 'yes',
                'default'  		=> 'no',
            ]
        );
        $this->add_control(
            'info_selected_icon',
            [
                'label' => esc_html__('Icon', 'element-camp'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'skin' => 'inline',
                'label_block' => false,
                'condition' => [
                    'info_icon' => 'yes'
                ]
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'slider_settings',
            [
                'label' => __('Slider Settings', 'element-camp'),
            ]
        );

        $this->add_control(
            'speed',
            [
                'label' => esc_html__('Speed', 'element-camp'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 50000,
                'step' => 100,
                'default' => 500,
            ]
        );

        $this->add_control(
            'direction',
            [
                'label' => esc_html__('Direction', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'horizontal' => esc_html__('horizontal', 'element-camp'),
                    'vertical' => esc_html__('vertical', 'element-camp'),
                ],
                'label_block' => true,
                'default' => 'horizontal',
            ]
        );

        $this->add_control(
            'effect',
            [
                'label' => esc_html__('Effect', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'slide' => esc_html__('Slide', 'element-camp'),
                    'fade' => esc_html__('Fade', 'element-camp'),
                    'parallax' => esc_html__('Parallax', 'element-camp'),
                    'cube' => esc_html__('Cube', 'element-camp'),
                    'coverflow' => esc_html__('Cover flow', 'element-camp'),
                    'flip' => esc_html__('Flip', 'element-camp'),
                    'cards' => esc_html__('Cards', 'element-camp'),
                    'material' => esc_html__('Material', 'element-camp'),
                ],
                'label_block' => true,
                'default' => 'slide',
            ]
        );

        $this->add_control(
            'parallax_choose',
            [
                'label' => esc_html__('Parallax items', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'all' => esc_html__('Parallax All (Works Only 1 Slide Per View)', 'element-camp'),
                    'text' => esc_html__('Parallax Text', 'element-camp'),
                ],
                'label_block' => true,
                'default' => 'all',
                'condition' => [
                    'effect' => 'parallax'
                ]
            ]
        );

        $this->add_control(
            'parallax_title_speed',
            [
                'label' => esc_html__('Title Speed', 'element-camp'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => -500000,
                'max' => 500000,
                'step' => 100,
                'default' => -2000,
                'condition' => ['effect' => 'parallax'],
            ]
        );

        $this->add_control(
            'parallax_taxonomy_date_speed',
            [
                'label' => esc_html__('Taxonomy/Date Speed', 'element-camp'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => -500000,
                'max' => 500000,
                'step' => 100,
                'default' => -1000,
                'condition' => ['effect' => 'parallax'],
            ]
        );

        $this->add_control(
            'effect_important_note',
            [
                'type' => \Elementor\Controls_Manager::ALERT,
                'alert_type' => 'warning',
                'heading' => esc_html__('Effect Important Note', 'element-camp'),
                'content' => esc_html__('Only Slide, Cover flow, Parallax effects are support more than one slide per view.', 'element-camp'),
                'condition' => [
                    'effect!' => ['slide', 'coverflow']
                ]
            ]
        );

        $this->add_control(
            'cardsRotate',
            [
                'label' => esc_html__('Cards Rotate', 'element-camp'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 50000,
                'step' => 1,
                'default' => 2,
                'condition' => [
                    'effect' => 'cards',
                ]
            ]
        );

        $this->add_control(
            'cardsOffset',
            [
                'label' => esc_html__('Cards Offset', 'element-camp'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 50000,
                'step' => 1,
                'default' => 8,
                'condition' => [
                    'effect' => 'cards',
                ]
            ]
        );

        $this->add_control(
            'loop',
            [
                'label' => esc_html__('Loop', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'element-camp'),
                'label_off' => esc_html__('Off', 'element-camp'),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $this->add_control(
            'loop_important_note',
            [
                'type' => \Elementor\Controls_Manager::ALERT,
                'alert_type' => 'warning',
                'heading' => esc_html__('Loop Important Note', 'element-camp'),
                'content' => esc_html__('The number of slides must be more than the slides per view.', 'element-camp'),
                'condition' => [
                    'loop' => 'true'
                ]
            ]
        );

        $this->add_control(
            'autoplay_delay',
            [
                'label' => esc_html__('Autoplay Delay', 'element-camp'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 50000,
                'step' => 100,
                'default' => 5000,
                'condition' => [
                    'autoplay' => 'true',
                ]
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => esc_html__('Autoplay', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'element-camp'),
                'label_off' => esc_html__('Off', 'element-camp'),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $this->add_control(
            'reverseDirection',
            [
                'label' => esc_html__('Reverse Direction', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'element-camp'),
                'label_off' => esc_html__('Off', 'element-camp'),
                'return_value' => 'true',
                'default' => 'false',
                'condition' => [
                    'autoplay' => 'true',
                ]
            ]
        );

        $this->add_control(
            'disableOnInteraction',
            [
                'label' => esc_html__('Disable On Interaction', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'element-camp'),
                'label_off' => esc_html__('Off', 'element-camp'),
                'return_value' => 'true',
                'default' => 'false',
                'condition' => [
                    'autoplay' => 'true',
                ]
            ]
        );

        $this->add_control(
            'centeredSlides',
            [
                'label' => esc_html__('Centered Slides', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'element-camp'),
                'label_off' => esc_html__('Off', 'element-camp'),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $this->add_control(
            'oneWayMovement',
            [
                'label' => esc_html__('One Way Movement', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'element-camp'),
                'label_off' => esc_html__('Off', 'element-camp'),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $this->add_control(
            'autoHeight',
            [
                'label' => esc_html__('Auto Height', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'element-camp'),
                'label_off' => esc_html__('Off', 'element-camp'),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $this->add_control(
            'mousewheel',
            [
                'label' => esc_html__('Slide With Mouse Wheel', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'element-camp'),
                'label_off' => esc_html__('Off', 'element-camp'),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $this->add_control(
            'keyboard',
            [
                'label' => esc_html__('Slide With Keyboard', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'element-camp'),
                'label_off' => esc_html__('Off', 'element-camp'),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $this->add_control(
            'arrows',
            [
                'label' => esc_html__('Slider Arrows', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'element-camp'),
                'label_off' => esc_html__('Off', 'element-camp'),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $this->add_control(
            'pagination',
            [
                'label' => esc_html__('Slider Pagination', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'element-camp'),
                'label_off' => esc_html__('Off', 'element-camp'),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $this->add_control(
            'paginationType',
            [
                'label' => esc_html__('Pagination Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'bullets' => esc_html__('Bullets', 'element-camp'),
                    'fraction' => esc_html__('Fraction', 'element-camp'),
                    'progressbar' => esc_html__('Progress Bar', 'element-camp'),
                ],
                'label_block' => true,
                'default' => 'bullets',
                'condition' => [
                    'pagination' => 'true'
                ]
            ]
        );

        $this->add_control(
            'arrows_pagination_container',
            [
                'label' => esc_html__('Arrow & Pagination Container', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'element-camp'),
                'label_off' => esc_html__('Off', 'element-camp'),
                'return_value' => 'true',
                'default' => 'false',
                'condition' => [
                    'pagination' => 'true',
                    'arrows' => 'true',
                    'paginationType' => 'bullets'
                ]
            ]
        );

        $this->add_control(
            'scrollbar',
            [
                'label' => esc_html__('Slider Scrollbar', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'element-camp'),
                'label_off' => esc_html__('Off', 'element-camp'),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $this->add_control(
            'customFraction',
            [
                'label' => esc_html__('Custom Fraction', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'element-camp'),
                'label_off' => esc_html__('Off', 'element-camp'),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $this->add_control(
            'slide_separator',
            [
                'label' => esc_html__( 'Fraction Separator', 'element-camp' ),
                'type' => Controls_Manager::TEXT,
                'default' => ' / ',
                'condition' => ['customFraction' => 'true']
            ]
        );

        $this->add_control(
            'customProgress',
            [
                'label' => esc_html__('Custom Progress', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'element-camp'),
                'label_off' => esc_html__('Off', 'element-camp'),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_slider_arrows',
            [
                'label' => __('Slider Arrows', 'element-camp'),
                'condition' => [
                    'arrows' => 'true',
                ]
            ]
        );

        $this->add_control(
            'arrows_style',
            [
                'label' => esc_html__('Arrows Style', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'icon' => esc_html__('Icon', 'element-camp'),
                    'text' => esc_html__('Text', 'element-camp'),
                    'text-icon' => esc_html__('Text With Icon', 'element-camp'),
                ],
                'label_block' => true,
                'default' => 'icon',
            ]
        );

        $this->add_control(
            'next_arrow_icon',
            [
                'label' => __('Next Arrow Icon', 'element-camp'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-arrow-right',
                    'library' => 'solid',
                ],
                'condition' => [
                    'arrows_style' => ['icon', 'text-icon'],
                ]
            ]
        );

        $this->add_control(
            'prev_arrow_icon',
            [
                'label' => __('Prev Arrow Icon', 'element-camp'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-arrow-left',
                    'library' => 'solid',
                ],
                'condition' => [
                    'arrows_style' => ['icon', 'text-icon'],
                ]
            ]
        );

        $this->add_control(
            'next_arrow_text',
            [
                'label' => esc_html__('Next Arrow Text', 'element-camp'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Next', 'element-camp'),
                'placeholder' => esc_html__('Type your text here', 'element-camp'),
                'condition' => [
                    'arrows_style' => ['text', 'text-icon'],
                ]
            ]
        );

        $this->add_control(
            'prev_arrow_text',
            [
                'label' => esc_html__('Prev Arrow Text', 'element-camp'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Prev', 'element-camp'),
                'placeholder' => esc_html__('Type your text here', 'element-camp'),
                'condition' => [
                    'arrows_style' => ['text', 'text-icon'],
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_slider_responsive',
            [
                'label' => __('Slider Responsive', 'element-camp'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'breakpoint',
            [
                'label' => esc_html__('Break Point', 'element-camp'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 992,
            ]
        );

        $repeater->add_control(
            'spaceBetween',
            [
                'label' => esc_html__('Space Between', 'element-camp'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 550,
                'step' => 1,
                'default' => 30,
            ]
        );

        $repeater->add_control(
            'slidesPerView_auto',
            [
                'label' => esc_html__('Auto Slides Per View', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'element-camp'),
                'label_off' => esc_html__('Off', 'element-camp'),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $repeater->add_control(
            'slidesPerView',
            [
                'label' => esc_html__('Slides Per View', 'element-camp'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 100,
                'step' => 1,
                'default' => 1,
                'condition' => [
                    'slidesPerView_auto!' => 'true'
                ]
            ]
        );

        $this->add_control(
            'responsive_settings',
            [
                'label' => esc_html__('Custom Break Points', 'element-camp'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'breakpoint' => 1200,
                        'slidesPerView' => 1,
                        'spaceBetween' => 0
                    ],
                    [
                        'breakpoint' => 992,
                        'slidesPerView' => 1,
                        'spaceBetween' => 0
                    ],
                    [
                        'breakpoint' => 600,
                        'slidesPerView' => 1,
                        'spaceBetween' => 0
                    ],
                ],
                'title_field' => 'screen size {{{ breakpoint }}}px',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_container_style',
            [
                'label' => __('Slider Container Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'slider_container_overflow',
            [
                'label' => esc_html__('Slider Container Overflow', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'hidden' => esc_html__('Hidden', 'element-camp'),
                    'visible' => esc_html__('Visible', 'element-camp'),
                ],
                'label_block' => true,
                'default' => 'hidden',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container' => 'overflow: {{VALUE}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'swiper_container_height',
            [
                'label' => esc_html__( 'Height', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
                'size_units' => [ 'px', 'vh', '%' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 500,
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_container_padding',
            [
                'label' => esc_html__('Slider Container Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'swiper_slide_style',
            [
                'label' => esc_html__('Swiper Slide Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'swiper_slide_overflow',
            [
                'label' => esc_html__('Overflow', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'hidden' => esc_html__('Hidden', 'element-camp'),
                    'visible' => esc_html__('Visible', 'element-camp'),
                ],
                'label_block' => true,
                'default' => 'hidden',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide' => 'overflow: {{VALUE}};',
                ],

            ]
        );
        $this->add_responsive_control(
            'swiper_slide_padding',
            [
                'label' => esc_html__('Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'swiper_slide_width',
            [
                'label' => esc_html__( 'Width', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
                'size_units' => [ 'px', 'vh', '%' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 500,
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'swiper_slide_min_height',
            [
                'label' => esc_html__( 'Min Height', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
                'size_units' => [ 'px', 'vh', '%' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 500,
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'swiper_slide_height',
            [
                'label' => esc_html__( 'Height', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
                'size_units' => [ 'px', 'vh', '%' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 500,
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'swiper_slide_display',
            [
                'label' => esc_html__('Swiper Slide Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'block',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'inline-flex' => esc_html__('Inline Flex', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'swiper_slide_position',
            [
                'label' => esc_html__( 'Display Position', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'before' => [
                        'title' => esc_html__( 'Before', 'element-camp' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'after' => [
                        'title' => esc_html__( 'After', 'element-camp' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                    'start' => [
                        'title' => esc_html__( 'Start', 'element-camp' ),
                        'icon' => "eicon-h-align-right",
                    ],
                    'end' => [
                        'title' => esc_html__( 'End', 'element-camp' ),
                        'icon' => "eicon-h-align-left",
                    ],
                ],
                'selectors_dictionary' => [
                    'before' => 'flex-direction: column;',
                    'after' => 'flex-direction: column-reverse;',
                    'start' => 'flex-direction: row;',
                    'end' => 'flex-direction: row-reverse;',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide' => '{{VALUE}}',
                ],
                'condition' => [ 'swiper_slide_display' => ['flex','inline-flex'] ],
            ]
        );
        $this->add_responsive_control(
            'swiper_slide_justify_content',
            [
                'label' => esc_html__( 'Justify Content', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'default' => '',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Start','element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-start-h',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-center-h',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'End', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-end-h',
                    ],
                    'space-between' => [
                        'title' => esc_html__( 'Space Between', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-space-between-h',
                    ],
                    'space-around' => [
                        'title' => esc_html__( 'Space Around', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-space-around-h',
                    ],
                    'space-evenly' => [
                        'title' => esc_html__( 'Space Evenly', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-space-evenly-h',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide' => 'justify-content: {{VALUE}};',
                ],
                'condition'=> ['swiper_slide_display'=> ['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'swiper_slide_align_items',
            [
                'label' => esc_html__( 'Align Items', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => '',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Start', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-align-start-v',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-align-center-v',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'End', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-align-end-v',
                    ],
                    'stretch' => [
                        'title' => esc_html__( 'Stretch', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-align-stretch-v',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide' => 'align-items: {{VALUE}};',
                ],
                'condition'=> ['swiper_slide_display'=> ['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'swiper_slide_flex_wrap',
            [
                'label' => esc_html__( 'Wrap', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'nowrap' => [
                        'title' => esc_html__( 'No Wrap', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-nowrap',
                    ],
                    'wrap' => [
                        'title' => esc_html__( 'Wrap', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-wrap',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide' => 'flex-wrap: {{VALUE}};',
                ],
                'condition'=> ['swiper_slide_display'=> ['flex','inline-flex']],
            ]
        );
        $this->start_controls_tabs(
            'swiper_slide_tabs',
        );

        $this->start_controls_tab(
            'swiper_slide_normal',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_control(
            'swiper_slide_scale',
            [
                'label' => esc_html__( 'Scale', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide' => 'transform: scale({{SIZE}});',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'swiper_slide_active',
            [
                'label'   => esc_html__( 'Active', 'element-camp' ),
            ]
        );
        $this->add_control(
            'swiper_slide_scale_active',
            [
                'label' => esc_html__( 'Scale', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide.swiper-slide-active' => 'transform: scale({{SIZE}});',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
        $this->start_controls_section(
            'item_style',
            [
                'label' => esc_html__('Item Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'item_padding',
            [
                'label' => esc_html__('Item Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'item_margin',
            [
                'label' => esc_html__('Item Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'item_display',
            [
                'label' => esc_html__('Item Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'block',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'inline-flex' => esc_html__('Inline Flex', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'item_display_position',
            [
                'label' => esc_html__( 'Item Display Position', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'before' => [
                        'title' => esc_html__( 'Before', 'element-camp' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'after' => [
                        'title' => esc_html__( 'After', 'element-camp' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                    'start' => [
                        'title' => esc_html__( 'Start', 'element-camp' ),
                        'icon' => "eicon-h-align-right",
                    ],
                    'end' => [
                        'title' => esc_html__( 'End', 'element-camp' ),
                        'icon' => "eicon-h-align-left",
                    ],
                ],
                'selectors_dictionary' => [
                    'before' => 'flex-direction: column;',
                    'after' => 'flex-direction: column-reverse;',
                    'start' => 'flex-direction: row;',
                    'end' => 'flex-direction: row-reverse;',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item' => '{{VALUE}}',
                ],
                'condition' => [ 'item_display' => ['flex','inline-flex'] ],
            ]
        );
        $this->add_responsive_control(
            'item_justify_content',
            [
                'label' => esc_html__( 'Justify Content', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'default' => '',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Start','element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-start-h',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-center-h',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'End', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-end-h',
                    ],
                    'space-between' => [
                        'title' => esc_html__( 'Space Between', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-space-between-h',
                    ],
                    'space-around' => [
                        'title' => esc_html__( 'Space Around', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-space-around-h',
                    ],
                    'space-evenly' => [
                        'title' => esc_html__( 'Space Evenly', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-space-evenly-h',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item' => 'justify-content: {{VALUE}};',
                ],
                'condition'=> ['item_display'=> ['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'item_align_items',
            [
                'label' => esc_html__( 'Align Items', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => '',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Start', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-align-start-v',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-align-center-v',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'End', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-align-end-v',
                    ],
                    'stretch' => [
                        'title' => esc_html__( 'Stretch', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-align-stretch-v',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item' => 'align-items: {{VALUE}};',
                ],
                'condition'=> ['item_display'=> ['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'item_flex_wrap',
            [
                'label' => esc_html__( 'Wrap', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'nowrap' => [
                        'title' => esc_html__( 'No Wrap', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-nowrap',
                    ],
                    'wrap' => [
                        'title' => esc_html__( 'Wrap', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-wrap',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item' => 'flex-wrap: {{VALUE}};',
                ],
                'condition'=> ['item_display'=> ['flex','inline-flex']],
            ]
        );
        $this->add_responsive_control(
            'content_max_width',
            [
                'label' => esc_html__('Max Width', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem','vw','vh', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'content_width',
            [
                'label' => esc_html__('Width', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem','vw','vh', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'content_height',
            [
                'label' => esc_html__('Height', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem','vw','vh', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'item_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'item_transition',
            [
                'label' => esc_html__( 'Item Transition', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );

        $this->start_controls_tabs(
            'item_style_tabs',
        );

        $this->start_controls_tab(
            'item_normal_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_control(
            'item_scale',
            [
                'label' => esc_html__( 'Scale', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item' => 'transform: scale({{SIZE}});',
                ],
            ]
        );
        $this->add_responsive_control(
            'prev_slides_transform_origin',
            [
                'label' => esc_html__( 'Previous Content Transform', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'center center' => esc_html__( 'Center Center', 'element-camp' ),
                    'center left' => esc_html__( 'Center Left', 'element-camp' ),
                    'center right' => esc_html__( 'Center Right', 'element-camp' ),
                    'top center' => esc_html__( 'Top Center', 'element-camp' ),
                    'top left' => esc_html__( 'Top Left', 'element-camp' ),
                    'top right' => esc_html__( 'Top Right', 'element-camp' ),
                    'bottom center' => esc_html__( 'Bottom Center', 'element-camp' ),
                    'bottom left' => esc_html__( 'Bottom Left', 'element-camp' ),
                    'bottom right' => esc_html__( 'Bottom Right', 'element-camp' ),
                ],
                'default' => 'center center',
                'condition' => ['item_scale[size]!' => ''],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide.swiper-slide-prev .item' => 'transform-origin: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'next_slides_transform_origin',
            [
                'label' => esc_html__( 'Next Content Transform', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'center center' => esc_html__( 'Center Center', 'element-camp' ),
                    'center left' => esc_html__( 'Center Left', 'element-camp' ),
                    'center right' => esc_html__( 'Center Right', 'element-camp' ),
                    'top center' => esc_html__( 'Top Center', 'element-camp' ),
                    'top left' => esc_html__( 'Top Left', 'element-camp' ),
                    'top right' => esc_html__( 'Top Right', 'element-camp' ),
                    'bottom center' => esc_html__( 'Bottom Center', 'element-camp' ),
                    'bottom left' => esc_html__( 'Bottom Left', 'element-camp' ),
                    'bottom right' => esc_html__( 'Bottom Right', 'element-camp' ),
                ],
                'default' => 'center center',
                'condition' => ['item_scale[size]!' => ''],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide.swiper-slide-next .item' => 'transform-origin: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'content_positioning',
            [
                'label' => esc_html__('Position', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'unset' => esc_html__('unset', 'element-camp'),
                    'absolute' => esc_html__('absolute', 'element-camp'),
                    'relative' => esc_html__('relative', 'element-camp'),
                ],
                'label_block' => true,
                'default' => 'relative',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item' => 'position: {{VALUE}};',
                ],
            ]
        );
        $start = is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');
        $end = !is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');

        $this->add_control(
            'content_offset_orientation_h',
            [
                'label' => esc_html__('Horizontal Orientation', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => $start,
                        'icon' => 'eicon-h-align-left',
                    ],
                    'end' => [
                        'title' => $end,
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'classes' => 'elementor-control-start-end',
                'render_type' => 'ui',
            ]
        );

        $this->add_responsive_control(
            'content_offset_x',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh', 'custom'],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'content_offset_orientation_h!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_offset_x_end',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 0.1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh', 'custom'],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'content_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_control(
            'content_offset_orientation_v',
            [
                'label' => esc_html__('Vertical Orientation', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => esc_html__('Top', 'element-camp'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'end' => [
                        'title' => esc_html__('Bottom', 'element-camp'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'render_type' => 'ui',
            ]
        );

        $this->add_responsive_control(
            'content_offset_y',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vh', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'content_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_offset_y_end',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vh', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'content_offset_orientation_v' => 'end',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'item_hover_tab',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
            ]
        );
        $this->add_control(
            'item_scale_hover',
            [
                'label' => esc_html__( 'Scale', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item:hover' => 'transform: scale({{SIZE}});',
                ],
            ]
        );
        $this->add_control(
            'info_positioning_hover',
            [
                'label' => esc_html__('Position', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'unset' => esc_html__('unset', 'element-camp'),
                    'absolute' => esc_html__('absolute', 'element-camp'),
                    'relative' => esc_html__('relative', 'element-camp'),
                ],
                'label_block' => true,
                'default' => 'relative',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item:hover' => 'position: {{VALUE}};',
                ],
            ]
        );
        $start = is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');
        $end = !is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');

        $this->add_control(
            'info_offset_hover_orientation_h',
            [
                'label' => esc_html__('Horizontal Orientation', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => $start,
                        'icon' => 'eicon-h-align-left',
                    ],
                    'end' => [
                        'title' => $end,
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'classes' => 'elementor-control-start-end',
                'render_type' => 'ui',
            ]
        );

        $this->add_responsive_control(
            'info_offset_hover_x',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh', 'custom'],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item:hover' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item:hover' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'info_offset_hover_orientation_h!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'info_offset_hover_x_end',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 0.1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh', 'custom'],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item:hover' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item:hover' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'info_offset_hover_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_control(
            'info_offset_hover_orientation_v',
            [
                'label' => esc_html__('Vertical Orientation', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => esc_html__('Top', 'element-camp'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'end' => [
                        'title' => esc_html__('Bottom', 'element-camp'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'render_type' => 'ui',
            ]
        );

        $this->add_responsive_control(
            'info_offset_hover_y',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vh', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item:hover' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'info_offset_hover_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'info_offset_hover_y_end',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vh', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item:hover' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'info_offset_hover_orientation_v' => 'end',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'item_active_tab',
            [
                'label'   => esc_html__( 'Active', 'element-camp' ),
            ]
        );
        $this->add_control(
            'item_scale_active',
            [
                'label' => esc_html__( 'Scale', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide.swiper-slide-active .item' => 'transform: scale({{SIZE}});',
                ],
            ]
        );
        $this->add_control(
            'content_active_slide_positioning',
            [
                'label' => esc_html__('Position', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'unset' => esc_html__('unset', 'element-camp'),
                    'absolute' => esc_html__('absolute', 'element-camp'),
                    'relative' => esc_html__('relative', 'element-camp'),
                ],
                'label_block' => true,
                'default' => 'relative',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide.swiper-slide-active .item' => 'position: {{VALUE}};',
                ],
            ]
        );
        $start = is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');
        $end = !is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');

        $this->add_control(
            'content_active_slide_offset_orientation_h',
            [
                'label' => esc_html__('Horizontal Orientation', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => $start,
                        'icon' => 'eicon-h-align-left',
                    ],
                    'end' => [
                        'title' => $end,
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'classes' => 'elementor-control-start-end',
                'render_type' => 'ui',
            ]
        );

        $this->add_responsive_control(
            'content_active_slide_offset_x',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh', 'custom'],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide.swiper-slide-active .item' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide.swiper-slide-active .item' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'content_active_slide_offset_orientation_h!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_active_slide_offset_x_end',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 0.1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh', 'custom'],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide.swiper-slide-active .item' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide.swiper-slide-active .item' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'content_active_slide_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_control(
            'content_active_slide_offset_orientation_v',
            [
                'label' => esc_html__('Vertical Orientation', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => esc_html__('Top', 'element-camp'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'end' => [
                        'title' => esc_html__('Bottom', 'element-camp'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'render_type' => 'ui',
            ]
        );

        $this->add_responsive_control(
            'content_active_slide_offset_y',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vh', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide.swiper-slide-active .item' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'content_active_slide_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_active_slide_offset_y_end',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vh', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide.swiper-slide-active .item' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'content_active_slide_offset_orientation_v' => 'end',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
        $this->start_controls_section(
            'item_overlay_section',
            [
                'label' => esc_html__('Item Overlay', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'item_overlay_color',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item::before',
            ]
        );

        $this->add_control(
            'item_overlay_opacity',
            [
                'label' => esc_html__( 'Item Overlay Opacity', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item::before' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_overlay_width',
            [
                'label' => esc_html__('Width', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item::before' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_overlay_height',
            [
                'label' => esc_html__('Height', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item::before' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_overlay_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item::before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $start = is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');
        $end = !is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');

        $this->add_control(
            'item_overlay_offset_orientation_h',
            [
                'label' => esc_html__('Horizontal Orientation', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => $start,
                        'icon' => 'eicon-h-align-left',
                    ],
                    'end' => [
                        'title' => $end,
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'classes' => 'elementor-control-start-end',
                'render_type' => 'ui',
            ]
        );

        $this->add_responsive_control(
            'item_overlay_offset_x',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh', 'custom'],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item::before' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item::before' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'item_overlay_offset_orientation_h!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_overlay_offset_x_end',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 0.1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh', 'custom'],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item::before' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item::before' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'item_overlay_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_control(
            'item_overlay_offset_orientation_v',
            [
                'label' => esc_html__('Vertical Orientation', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => esc_html__('Top', 'element-camp'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'end' => [
                        'title' => esc_html__('Bottom', 'element-camp'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'render_type' => 'ui',
            ]
        );

        $this->add_responsive_control(
            'item_overlay_offset_y',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vh', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item::before' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'item_overlay_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_overlay_offset_y_end',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vh', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item::before' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'item_overlay_offset_orientation_v' => 'end',
                ],
            ]
        );
        $this->add_control(
            'item_overlay_index',
            [
                'label' => esc_html__( 'Item Overlay z-index', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => -1,
                    'unit' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item::before' => 'z-index: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'image_style',
            [
                'label' => esc_html__('Image Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );
        $this->add_control(
            'image_container',
            [
                'label' => esc_html__( 'Add Image Container For The Image', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'yes', 'element-camp' ),
                'label_off' => esc_html__( 'No', 'element-camp' ),
            ]
        );
        $this->add_control(
            'image_container_style_options',
            [
                'label' => esc_html__( 'Image Container Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'image_container' => 'yes',
                ],
            ]
        );
        $this->add_responsive_control(
            'img_container_width',
            [
                'label' => esc_html__( 'Image Container Width', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'size_units' => [ '%', 'px', 'vw', 'rem', 'custom' ],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 150,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 150,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .img' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'image_container' => 'yes',
                ],
            ]
        );
        $this->add_responsive_control(
            'img_container_height',
            [
                'label' => esc_html__( 'Image Container Height', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
                'size_units' => [ 'px', 'vh', '%', 'vw', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 500,
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .img' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'image_container' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'separator_panel_style',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
                'condition' => [
                    'image_container' => 'yes',
                ],
            ]
        );
        $this->add_responsive_control(
            'img_width',
            [
                'label' => esc_html__( 'Width', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'size_units' => [ '%', 'px', 'vw', 'rem', 'custom' ],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 150,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 150,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'img_height',
            [
                'label' => esc_html__( 'Height', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
                'size_units' => [ 'px', 'vh', '%', 'vw', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 500,
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'img_object-fit',
            [
                'label' => esc_html__( 'Object Fit', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'img_height[size]!' => '',
                ],
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'fill' => esc_html__( 'Fill', 'element-camp' ),
                    'cover' => esc_html__( 'Cover', 'element-camp' ),
                    'contain' => esc_html__( 'Contain', 'element-camp' ),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'img_object-position',
            [
                'label' => esc_html__( 'Object Position', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'center center' => esc_html__( 'Center Center', 'element-camp' ),
                    'center left' => esc_html__( 'Center Left', 'element-camp' ),
                    'center right' => esc_html__( 'Center Right', 'element-camp' ),
                    'top center' => esc_html__( 'Top Center', 'element-camp' ),
                    'top left' => esc_html__( 'Top Left', 'element-camp' ),
                    'top right' => esc_html__( 'Top Right', 'element-camp' ),
                    'bottom center' => esc_html__( 'Bottom Center', 'element-camp' ),
                    'bottom left' => esc_html__( 'Bottom Left', 'element-camp' ),
                    'bottom right' => esc_html__( 'Bottom Right', 'element-camp' ),
                ],
                'default' => 'center center',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item img' => 'object-position: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'img_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_margin',
            [
                'label' => esc_html__('Image Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'img_positioning',
            [
                'label' => esc_html__('Position', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'unset' => esc_html__('unset', 'element-camp'),
                    'absolute' => esc_html__('absolute', 'element-camp'),
                    'relative' => esc_html__('relative', 'element-camp'),
                ],
                'label_block' => true,
                'default' => 'relative',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item img' => 'position: {{VALUE}};',
                ],
            ]
        );
        $start = is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');
        $end = !is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');

        $this->add_control(
            'img_offset_orientation_h',
            [
                'label' => esc_html__('Horizontal Orientation', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => $start,
                        'icon' => 'eicon-h-align-left',
                    ],
                    'end' => [
                        'title' => $end,
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'classes' => 'elementor-control-start-end',
                'render_type' => 'ui',
            ]
        );

        $this->add_responsive_control(
            'img_offset_x',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh', 'custom'],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item img' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item img' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'img_offset_orientation_h!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'img_offset_x_end',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 0.1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh', 'custom'],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item img' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item img' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'img_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_control(
            'img_offset_orientation_v',
            [
                'label' => esc_html__('Vertical Orientation', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => esc_html__('Top', 'element-camp'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'end' => [
                        'title' => esc_html__('Bottom', 'element-camp'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'render_type' => 'ui',
            ]
        );

        $this->add_responsive_control(
            'img_offset_y',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vh', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item img' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'img_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'img_offset_y_end',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vh', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item img' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'img_offset_orientation_v' => 'end',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'img_overlay_section',
            [
                'label' => esc_html__('Image Overlay', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['image_container' => 'yes']
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'img_overlay_color',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .img::before',
            ]
        );

        $this->add_control(
            'img_overlay_opacity',
            [
                'label' => esc_html__( 'Overlay Opacity', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .img::before' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'img_overlay_width',
            [
                'label' => esc_html__('Width', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .img::before' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'img_overlay_height',
            [
                'label' => esc_html__('Height', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .img::before' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'img_overlay_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .img::before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $start = is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');
        $end = !is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');

        $this->add_control(
            'img_overlay_offset_orientation_h',
            [
                'label' => esc_html__('Horizontal Orientation', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => $start,
                        'icon' => 'eicon-h-align-left',
                    ],
                    'end' => [
                        'title' => $end,
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'classes' => 'elementor-control-start-end',
                'render_type' => 'ui',
            ]
        );

        $this->add_responsive_control(
            'img_overlay_offset_x',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh', 'custom'],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .img::before' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .img::before' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'img_overlay_offset_orientation_h!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'img_overlay_offset_x_end',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 0.1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh', 'custom'],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .img::before' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .img::before' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'img_overlay_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_control(
            'img_overlay_offset_orientation_v',
            [
                'label' => esc_html__('Vertical Orientation', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => esc_html__('Top', 'element-camp'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'end' => [
                        'title' => esc_html__('Bottom', 'element-camp'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'render_type' => 'ui',
            ]
        );

        $this->add_responsive_control(
            'img_overlay_offset_y',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vh', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .img::before' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'img_overlay_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'img_overlay_offset_y_end',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vh', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .img::before' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'img_overlay_offset_orientation_v' => 'end',
                ],
            ]
        );
        $this->add_control(
            'img_overlay_index',
            [
                'label' => esc_html__( 'img Overlay z-index', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => -1,
                    'unit' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .img::before' => 'z-index: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_info_style',
            [
                'label' => esc_html__('Info Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'info_display',
            [
                'label' => esc_html__('Info Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'block',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'inline-flex' => esc_html__('Inline Flex', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'info_display_position',
            [
                'label' => esc_html__( 'Display Position', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'before' => [
                        'title' => esc_html__( 'Before', 'element-camp' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'after' => [
                        'title' => esc_html__( 'After', 'element-camp' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                    'start' => [
                        'title' => esc_html__( 'Start', 'element-camp' ),
                        'icon' => "eicon-h-align-right",
                    ],
                    'end' => [
                        'title' => esc_html__( 'End', 'element-camp' ),
                        'icon' => "eicon-h-align-left",
                    ],
                ],
                'selectors_dictionary' => [
                    'before' => 'flex-direction: column;',
                    'after' => 'flex-direction: column-reverse;',
                    'start' => 'flex-direction: row;',
                    'end' => 'flex-direction: row-reverse;',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info' => '{{VALUE}}',
                ],
                'condition' => [ 'info_display' => ['flex','inline-flex'] ],
            ]
        );
        $this->add_responsive_control(
            'info_justify_content',
            [
                'label' => esc_html__( 'Justify Content', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'default' => '',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Start','element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-start-h',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-center-h',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'End', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-end-h',
                    ],
                    'space-between' => [
                        'title' => esc_html__( 'Space Between', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-space-between-h',
                    ],
                    'space-around' => [
                        'title' => esc_html__( 'Space Around', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-space-around-h',
                    ],
                    'space-evenly' => [
                        'title' => esc_html__( 'Space Evenly', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-space-evenly-h',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info' => 'justify-content: {{VALUE}};',
                ],
                'condition'=> ['info_display'=> ['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'info_align_items',
            [
                'label' => esc_html__( 'Align Items', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => '',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Start', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-align-start-v',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-align-center-v',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'End', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-align-end-v',
                    ],
                    'stretch' => [
                        'title' => esc_html__( 'Stretch', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-align-stretch-v',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info' => 'align-items: {{VALUE}};',
                ],
                'condition'=> ['info_display'=> ['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'info_flex_wrap',
            [
                'label' => esc_html__( 'Wrap', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'nowrap' => [
                        'title' => esc_html__( 'No Wrap', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-nowrap',
                    ],
                    'wrap' => [
                        'title' => esc_html__( 'Wrap', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-wrap',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info' => 'flex-wrap: {{VALUE}};',
                ],
                'condition'=> ['info_display'=> ['flex','inline-flex']],
            ]
        );

        $this->add_control(
            'info_animations',
            [
                'label' => esc_html__( 'Info Animation', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'duplicate' => esc_html__( 'Info Duplicate Stroke', 'element-camp' ),
                ],
            ]
        );
        $this->add_control(
            'info_index',
            [
                'label' => esc_html__( 'Info z-index', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info' => 'z-index: {{SIZE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'info_container_max_width',
            [
                'label' => esc_html__('Info Container Max Width', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem','vw','vh', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'info_container_min_height',
            [
                'label' => esc_html__( 'Info Container Min Height', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'size_units' => [ '%', 'px', 'vw', 'rem', 'custom' ],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 150,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 150,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'info_container_width',
            [
                'label' => esc_html__( 'Info Container Width', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'size_units' => [ '%', 'px', 'vw', 'rem', 'custom' ],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 150,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 150,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'info_container_height',
            [
                'label' => esc_html__( 'Info Container Height', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'size_units' => [ '%', 'px', 'vw', 'rem', 'custom' ],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 150,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 150,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'info_section_padding',
            [
                'label' => esc_html__('Info Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'info_section_margin',
            [
                'label' => esc_html__('Info Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'position_for_info_container',
            [
                'label' => esc_html__( 'Info Container Position', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'absolute' => esc_html__( 'Absolute', 'element-camp' ),
                    'relative' => esc_html__( 'Relative', 'element-camp' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info' => 'position: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'info_container_offset_orientation_h',
            [
                'label' => esc_html__( 'Horizontal Orientation', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => esc_html__( 'Start', 'element-camp' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'end' => [
                        'title' => esc_html__( 'End', 'element-camp' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'condition' => [
                    'position_for_info_container!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'info_container_offset_x',
            [
                'label' => esc_html__( 'Offset', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'size' => 0,
                ],
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info' => 'left: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'info_container_offset_orientation_h' => 'start',
                    'position_for_info_container!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'info_container_offset_x_end',
            [
                'label' => esc_html__( 'Offset', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'size' => 0,
                ],
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info' => 'right: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'info_container_offset_orientation_h' => 'end',
                    'position_for_info_container!' => '',
                ],
            ]
        );
        $this->add_control(
            'info_container_offset_orientation_v',
            [
                'label' => esc_html__( 'Vertical Orientation', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => esc_html__( 'Top', 'element-camp' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'end' => [
                        'title' => esc_html__( 'Bottom', 'element-camp' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'condition' => [
                    'position_for_info_container!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'info_container_offset_y',
            [
                'label' => esc_html__( 'Offset', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'vw', 'custom' ],
                'default' => [
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info' => 'top: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'info_container_offset_orientation_v' => 'start',
                    'position_for_info_container!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'info_container_offset_y_end',
            [
                'label' => esc_html__( 'Offset', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'vw', 'custom' ],
                'default' => [
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info' => 'bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'info_container_offset_orientation_v' => 'end',
                    'position_for_info_container!' => '',
                ],
            ]
        );
        $this->add_control(
            'info_transition',
            [
                'label' => esc_html__( 'Info Transition', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );

        $this->start_controls_tabs(
            'info_container_tabs',
        );

        $this->start_controls_tab(
            'info_normal_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'info_container_background_color',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info',
            ]
        );
        $this->add_control(
            'info_opacity',
            [
                'label' => esc_html__( 'Opacity', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'info_hover_tab',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'info_container_background_color_hover',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item:hover .info',
            ]
        );
        $this->add_control(
            'info_opacity_hover',
            [
                'label' => esc_html__( 'Opacity', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item:hover .info' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'info_active_tab',
            [
                'label'   => esc_html__( 'Active', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'info_container_background_color_active',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide.swiper-slide-active .item .info',
            ]
        );
        $this->add_control(
            'info_opacity_active',
            [
                'label' => esc_html__( 'Opacity', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide.swiper-slide-active .item .info' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        $this->start_controls_section(
            'section_copy_info_style',
            [
                'label' => esc_html__('Copy Info Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['info_animations' => 'duplicate']
            ]
        );
        $this->add_responsive_control(
            'info_copy_display',
            [
                'label' => esc_html__('Info Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'flex',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'inline-flex' => esc_html__('Inline Flex', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'info_copy_display_position',
            [
                'label' => esc_html__( 'Display Position', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'before' => [
                        'title' => esc_html__( 'Before', 'element-camp' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'after' => [
                        'title' => esc_html__( 'After', 'element-camp' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                    'start' => [
                        'title' => esc_html__( 'Start', 'element-camp' ),
                        'icon' => "eicon-h-align-right",
                    ],
                    'end' => [
                        'title' => esc_html__( 'End', 'element-camp' ),
                        'icon' => "eicon-h-align-left",
                    ],
                ],
                'selectors_dictionary' => [
                    'before' => 'flex-direction: column;',
                    'after' => 'flex-direction: column-reverse;',
                    'start' => 'flex-direction: row;',
                    'end' => 'flex-direction: row-reverse;',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info' => '{{VALUE}}',
                ],
                'condition' => [ 'info_copy_display' => ['flex','inline-flex'] ],
            ]
        );
        $this->add_responsive_control(
            'info_copy_justify_content',
            [
                'label' => esc_html__( 'Justify Content', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'default' => '',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Start','element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-start-h',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-center-h',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'End', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-end-h',
                    ],
                    'space-between' => [
                        'title' => esc_html__( 'Space Between', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-space-between-h',
                    ],
                    'space-around' => [
                        'title' => esc_html__( 'Space Around', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-space-around-h',
                    ],
                    'space-evenly' => [
                        'title' => esc_html__( 'Space Evenly', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-space-evenly-h',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info' => 'justify-content: {{VALUE}};',
                ],
                'condition'=> ['info_copy_display'=> ['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'info_copy_align_items',
            [
                'label' => esc_html__( 'Align Items', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Start', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-align-start-v',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-align-center-v',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'End', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-align-end-v',
                    ],
                    'stretch' => [
                        'title' => esc_html__( 'Stretch', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-align-stretch-v',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info' => 'align-items: {{VALUE}};',
                ],
                'condition'=> ['info_copy_display'=> ['flex','inline-flex']],
                'responsive' => true,
            ]
        );
        $this->add_responsive_control(
            'info_copy_flex_wrap',
            [
                'label' => esc_html__( 'Wrap', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'nowrap' => [
                        'title' => esc_html__( 'No Wrap', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-nowrap',
                    ],
                    'wrap' => [
                        'title' => esc_html__( 'Wrap', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-wrap',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info' => 'flex-wrap: {{VALUE}};',
                ],
                'condition'=> ['info_copy_display'=> ['flex','inline-flex']],
            ]
        );
        $this->add_control(
            'copy_info_index',
            [
                'label' => esc_html__( 'Copy Info z-index', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info' => 'z-index: {{SIZE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'copy_info_container_width',
            [
                'label' => esc_html__( 'Copy Info Container Width', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'size_units' => [ '%', 'px', 'vw', 'rem', 'custom' ],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 150,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 150,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'copy_info_section_padding',
            [
                'label' => esc_html__('Copy Info Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'copy_info_section_margin',
            [
                'label' => esc_html__('Copy Info Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'position_for_copy_info_container',
            [
                'label' => esc_html__( 'Copy Info Container Position', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'absolute' => esc_html__( 'Absolute', 'element-camp' ),
                    'relative' => esc_html__( 'Relative', 'element-camp' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info' => 'position: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'copy_info_container_offset_orientation_h',
            [
                'label' => esc_html__( 'Horizontal Orientation', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => esc_html__( 'Start', 'element-camp' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'end' => [
                        'title' => esc_html__( 'End', 'element-camp' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'condition' => [
                    'position_for_copy_info_container!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'copy_info_container_offset_x',
            [
                'label' => esc_html__( 'Offset', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'size' => 0,
                ],
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info' => 'left: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'copy_info_container_offset_orientation_h' => 'start',
                    'position_for_copy_info_container!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'copy_info_container_offset_x_end',
            [
                'label' => esc_html__( 'Offset', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'size' => 0,
                ],
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info' => 'right: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'copy_info_container_offset_orientation_h' => 'end',
                    'position_for_copy_info_container!' => '',
                ],
            ]
        );
        $this->add_control(
            'copy_info_container_offset_orientation_v',
            [
                'label' => esc_html__( 'Vertical Orientation', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => esc_html__( 'Top', 'element-camp' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'end' => [
                        'title' => esc_html__( 'Bottom', 'element-camp' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'condition' => [
                    'position_for_copy_info_container!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'copy_info_container_offset_y',
            [
                'label' => esc_html__( 'Offset', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'vw', 'custom' ],
                'default' => [
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info' => 'top: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'copy_info_container_offset_orientation_v' => 'start',
                    'position_for_copy_info_container!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'copy_info_container_offset_y_end',
            [
                'label' => esc_html__( 'Offset', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'vw', 'custom' ],
                'default' => [
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info' => 'bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'copy_info_container_offset_orientation_v' => 'end',
                    'position_for_copy_info_container!' => '',
                ],
            ]
        );
        $this->start_controls_tabs(
            'copy_info_container_tabs',
        );

        $this->start_controls_tab(
            'copy_info_normal_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'copy_info_container_background_color',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'copy_info_hover_tab',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'copy_info_container_background_color_hover',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item:hover .copy_info',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__('Title Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'title_text_align',
            [
                'label' => esc_html__( 'Text Alignment', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'element-camp' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'element-camp' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'element-camp' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__( 'Justified', 'element-camp' ),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .title' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .title' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'title_text_wrap',
            [
                'label' => esc_html__('Text Wrap', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'wrap' => esc_html__('Wrap', 'element-camp'),
                    'nowrap' => esc_html__('No Wrap', 'element-camp'),
                    'balance' => esc_html__('Balance', 'element-camp'),
                    'pretty' => esc_html__('Pretty', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .title' => 'text-wrap: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .title' => 'text-wrap: {{VALUE}};',
                ]
            ]
        );
        $this->add_control(
            'position_for_title',
            [
                'label' => esc_html__( 'Title Position', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'absolute' => esc_html__( 'Absolute', 'element-camp' ),
                    'relative' => esc_html__( 'Relative', 'element-camp' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .title' => 'position: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .title' => 'position: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'title_offset_orientation_h',
            [
                'label' => esc_html__( 'Horizontal Orientation', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => esc_html__( 'Start', 'element-camp' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'end' => [
                        'title' => esc_html__( 'End', 'element-camp' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'condition' => [
                    'position_for_title!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_offset_x',
            [
                'label' => esc_html__( 'Offset', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'size' => 0,
                ],
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .title' => 'left: {{SIZE}}{{UNIT}}',
                    '.tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .title' => 'left: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'title_offset_orientation_h' => 'start',
                    'position_for_title!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_offset_x_end',
            [
                'label' => esc_html__( 'Offset', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'size' => 0,
                ],
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .title' => 'right: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .title' => 'right: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'title_offset_orientation_h' => 'end',
                    'position_for_title!' => '',
                ],
            ]
        );
        $this->add_control(
            'title_offset_orientation_v',
            [
                'label' => esc_html__( 'Vertical Orientation', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => esc_html__( 'Top', 'element-camp' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'end' => [
                        'title' => esc_html__( 'Bottom', 'element-camp' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'condition' => [
                    'position_for_title!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_offset_y',
            [
                'label' => esc_html__( 'Offset', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'vw', 'custom' ],
                'default' => [
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .title' => 'top: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .title' => 'top: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'title_offset_orientation_v' => 'start',
                    'position_for_title!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_offset_y_end',
            [
                'label' => esc_html__( 'Offset', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'vw', 'custom' ],
                'default' => [
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .title' => 'bottom: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .title' => 'bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'title_offset_orientation_v' => 'end',
                    'position_for_title!' => '',
                ],
            ]
        );
        $this->add_control(
            'title_transition',
            [
                'label' => esc_html__( 'Title Transition', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .title' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );
        $this->add_control(
            'title_transition_delay',
            [
                'label' => esc_html__( 'Title Transition Delay', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .title' => 'transition-delay: {{SIZE}}s;',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_padding',
            [
                'label' => esc_html__('Title Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Title Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs(
            'title_tabs',
        );
        $this->start_controls_tab(
            'title_normal',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__('Title', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .title,{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .title',
            ]
        );
        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'title_color_dark_mode',
            [
                'label' => esc_html__( 'Title Color (Dark Mode)', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .title' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .title' => 'color: {{VALUE}};',
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .title' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'title_border',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .title,{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .title',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'title_background_color',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .title,{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .title',
            ]
        );
        $this->add_responsive_control(
            'title_translate_y',
            [
                'label' => esc_html__( 'Translate Y', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'default' => [
                    'size' => 0,
                ],
            ]
        );
        $this->add_responsive_control(
            'title_skew_x',
            [
                'label' => esc_html__( 'Skew X', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'deg','custom'],
                'default' => [
                    'unit' => 'deg',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_translate_x',
            [
                'label' => esc_html__( 'Translate X', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'default' => [
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .title' => 'transform: skewX({{title_skew_x.SIZE}}{{title_skew_x.UNIT}}) translate({{title_translate_x.SIZE}}{{title_translate_x.UNIT}},{{title_translate_y.SIZE}}{{title_translate_y.UNIT}})',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .title span' => 'transform: skewX({{title_skew_x.SIZE}}{{title_skew_x.UNIT}}) translate({{title_translate_x.SIZE}}{{title_translate_x.UNIT}},{{title_translate_y.SIZE}}{{title_translate_y.UNIT}})',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .title .stroke' => 'transform: skewX({{title_skew_x.SIZE}}{{title_skew_x.UNIT}}) translate({{title_translate_x.SIZE}}{{title_translate_x.UNIT}},{{title_translate_y.SIZE}}{{title_translate_y.UNIT}})',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .title' => 'transform: skewX({{title_skew_x.SIZE}}{{title_skew_x.UNIT}}) translate({{title_translate_x.SIZE}}{{title_translate_x.UNIT}},{{title_translate_y.SIZE}}{{title_translate_y.UNIT}})',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .title span' => 'transform: skewX({{title_skew_x.SIZE}}{{title_skew_x.UNIT}}) translate({{title_translate_x.SIZE}}{{title_translate_x.UNIT}},{{title_translate_y.SIZE}}{{title_translate_y.UNIT}})',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .title .stroke' => 'transform: skewX({{title_skew_x.SIZE}}{{title_skew_x.UNIT}}) translate({{title_translate_x.SIZE}}{{title_translate_x.UNIT}},{{title_translate_y.SIZE}}{{title_translate_y.UNIT}})',
                ],
            ]
        );
        $this->add_control(
            'title_opacity',
            [
                'label' => esc_html__( 'Title Opacity', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .title' => 'opacity: {{SIZE}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .title .stroke' => 'opacity: {{SIZE}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .title span' => 'opacity: {{SIZE}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .title' => 'opacity: {{SIZE}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .title .stroke' => 'opacity: {{SIZE}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .title span' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'title_active',
            [
                'label'   => esc_html__( 'Active', 'element-camp' ),
            ]
        );
        $this->add_responsive_control(
            'title_translate_y_active',
            [
                'label' => esc_html__( 'Title Translate Y', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'default' => [
                    'size' => 0,
                ],
            ]
        );
        $this->add_responsive_control(
            'title_skew_x_active',
            [
                'label' => esc_html__( 'Skew X', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'deg','custom'],
                'default' => [
                    'unit' => 'deg',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_translate_x_active',
            [
                'label' => esc_html__( 'Title Translate X', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'default' => [
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide-active .item .info .title' => 'transform: skewX({{title_skew_x_active.SIZE}}{{title_skew_x_active.UNIT}}) translate({{title_translate_x_active.SIZE}}{{title_translate_x_active.UNIT}},{{title_translate_y_active.SIZE}}{{title_translate_y_active.UNIT}})',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide-active .item .info .title .stroke' => 'transform: skewX({{title_skew_x_active.SIZE}}{{title_skew_x_active.UNIT}}) translate({{title_translate_x_active.SIZE}}{{title_translate_x_active.UNIT}},{{title_translate_y_active.SIZE}}{{title_translate_y_active.UNIT}})',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide-active .item .info .title span' => 'transform: skewX({{title_skew_x_active.SIZE}}{{title_skew_x_active.UNIT}}) translate({{title_translate_x_active.SIZE}}{{title_translate_x_active.UNIT}},{{title_translate_y_active.SIZE}}{{title_translate_y_active.UNIT}})',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide-active .item .copy-info .title' => 'transform: skewX({{title_skew_x_active.SIZE}}{{title_skew_x_active.UNIT}}) translate({{title_translate_x_active.SIZE}}{{title_translate_x_active.UNIT}},{{title_translate_y_active.SIZE}}{{title_translate_y_active.UNIT}})',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide-active .item .copy-info .title .stroke' => 'transform: skewX({{title_skew_x_active.SIZE}}{{title_skew_x_active.UNIT}}) translate({{title_translate_x_active.SIZE}}{{title_translate_x_active.UNIT}},{{title_translate_y_active.SIZE}}{{title_translate_y_active.UNIT}})',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide-active .item .copy-info .title span' => 'transform: skewX({{title_skew_x_active.SIZE}}{{title_skew_x_active.UNIT}}) translate({{title_translate_x_active.SIZE}}{{title_translate_x_active.UNIT}},{{title_translate_y_active.SIZE}}{{title_translate_y_active.UNIT}})',
                ],
            ]
        );
        $this->add_control(
            'title_opacity_active',
            [
                'label' => esc_html__( 'Title Opacity', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide-active .item .info .title' => 'opacity: {{SIZE}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide-active .item .copy-info .title' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
        $this->start_controls_section(
            'section_icon_style',
            [
                'label' => esc_html__( 'Icon', 'element-camp' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'info_icon' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'info_icon_positioning',
            [
                'label' => esc_html__('Position', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'unset' => esc_html__('unset', 'element-camp'),
                    'absolute' => esc_html__('absolute', 'element-camp'),
                    'relative' => esc_html__('relative', 'element-camp'),
                ],
                'label_block' => true,
                'default' => 'absolute',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .icon' => 'position: {{VALUE}};',
                ],
            ]
        );

        $start = is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');
        $end = !is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');

        $this->add_control(
            'info_icon_offset_orientation_h',
            [
                'label' => esc_html__('Horizontal Orientation', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => $start,
                        'icon' => 'eicon-h-align-left',
                    ],
                    'end' => [
                        'title' => $end,
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'classes' => 'elementor-control-start-end',
                'render_type' => 'ui',
            ]
        );

        $this->add_responsive_control(
            'info_icon_offset_x',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh', 'custom'],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .icon' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .icon' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'info_icon_offset_orientation_h!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'info_icon_offset_x_end',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 0.1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh', 'custom'],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .icon' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .icon' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'info_icon_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_control(
            'info_icon_offset_orientation_v',
            [
                'label' => esc_html__('Vertical Orientation', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => esc_html__('Top', 'element-camp'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'end' => [
                        'title' => esc_html__('Bottom', 'element-camp'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'render_type' => 'ui',
            ]
        );

        $this->add_responsive_control(
            'info_icon_offset_y',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vh', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .icon' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'info_icon_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'info_icon_offset_y_end',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vh', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .icon' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'info_icon_offset_orientation_v' => 'end',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_indent',
            [
                'label' => esc_html__('Icon Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em' ,'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'info_icon_wrapper_width',
            [
                'label' => esc_html__( 'Icon Wrapper Width', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px','custom' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .icon' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'info_icon_wrapper_height',
            [
                'label' => esc_html__( 'Icon Wrapper Height', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px','custom' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .icon' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__('Icon Size', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'icon_opacity',
            [
                'label' => esc_html__( 'Opacity', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .icon svg' => 'opacity: {{SIZE}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .icon i' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_transition',
            [
                'label' => esc_html__( 'Transition', 'themescamp-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .icon' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );
        $this->start_controls_tabs(
            'icon_heading_tabs',
        );
        $this->start_controls_tab(
            'normal_icon_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_transform_options',
            [
                'label' => esc_html__('Icon Transform', 'themescamp-core'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'themescamp-core'),
                'label_on' => esc_html__('Custom', 'themescamp-core'),
            ]
        );
        $this->start_popover();
        $this->add_control(
            'icon_translate_y',
            [
                'label' => esc_html__( 'Icon Translate Y', 'themescamp-core' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .icon' => '--e-transform-tcgelements-showcase-icon-translateY: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_control(
            'icon_translate_x',
            [
                'label' => esc_html__( 'Icon Translate X', 'themescamp-core' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .icon' => '--e-transform-tcgelements-showcase-icon-translateX: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->end_popover();
        $this->end_controls_tab();
        $this->start_controls_tab(
            'hover_icon_tab',
            [
                'label'   => esc_html__( 'Hover', 'element-camp' ),
            ]
        );
        $this->add_control(
            'icon_color_hover',
            [
                'label' => esc_html__( 'Icon Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-heading-text:hover .heading-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-heading-text:hover .heading-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_transform_options_hover',
            [
                'label' => esc_html__('Icon Transform', 'themescamp-core'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'themescamp-core'),
                'label_on' => esc_html__('Custom', 'themescamp-core'),
            ]
        );
        $this->start_popover();
        $this->add_control(
            'icon_translate_y_hover',
            [
                'label' => esc_html__( 'Icon Translate Y', 'themescamp-core' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item:hover .info .icon' => '--e-transform-tcgelements-showcase-icon-translateY: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_control(
            'icon_translate_x_hover',
            [
                'label' => esc_html__( 'Icon Translate X', 'themescamp-core' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item:hover .info .icon' => '--e-transform-tcgelements-showcase-icon-translateX: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->end_popover();
        $this->end_controls_tab();
        $this->start_controls_tab(
            'active_icon_tab',
            [
                'label'   => esc_html__( 'Active', 'element-camp' ),
            ]
        );
        $this->add_control(
            'icon_transform_options_active',
            [
                'label' => esc_html__('Icon Transform', 'themescamp-core'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'themescamp-core'),
                'label_on' => esc_html__('Custom', 'themescamp-core'),
            ]
        );
        $this->start_popover();
        $this->add_control(
            'icon_translate_y_active',
            [
                'label' => esc_html__( 'Icon Translate Y', 'themescamp-core' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide-active .item .info .icon' => '--e-transform-tcgelements-showcase-icon-translateY: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_control(
            'icon_translate_x_active',
            [
                'label' => esc_html__( 'Icon Translate X', 'themescamp-core' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide-active .item .info .icon' => '--e-transform-tcgelements-showcase-icon-translateX: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->end_popover();
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'duplicate_titles_style',
            [
                'label' => esc_html__('Duplicate Title Styles', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'info_animations' => 'duplicate',
                ]
            ]
        );
        $this->add_responsive_control(
            'stroke_title_display',
            [
                'label' => esc_html__('Stroke Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'block',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'inline' => esc_html__('Inline', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .title .stroke' => 'display: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .title .stroke' => 'display: {{VALUE}};'
                ]
            ]
        );

        $this->add_control(
            'first_info_color',
            [
                'label' => esc_html__( 'First Info Title Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .title span' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .title .stroke' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'first_info_color_stroke',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .title,{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .title .stroke,{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .title span',
            ]
        );

        $this->add_control(
            'second_info_color',
            [
                'label' => esc_html__( 'Second Info Title Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .title span' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .title .stroke' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'second_info_color_stroke',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .title,{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .title .stroke,{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .title span',
            ]
        );

        $this->add_responsive_control(
            'title_split_two_margin',
            [
                'label' => esc_html__('Title Split Second Span Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .title span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .title span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_taxonomy_style',
            [
                'label' => esc_html__('Taxonomy Wrapper Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'taxonomy_transition',
            [
                'label' => esc_html__( 'Transition', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .taxonomy' => 'transition: all {{SIZE}}s ease;',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .taxonomy' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );
        $this->add_responsive_control(
            'taxonomy_padding',
            [
                'label' => esc_html__('Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .taxonomy' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .taxonomy' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'taxonomy_margin',
            [
                'label' => esc_html__('Taxonomy Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .taxonomy' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .taxonomy' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'taxonomy_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .taxonomy' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .taxonomy' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',                ],
                'separator' => 'after'
            ]
        );
        $this->add_control(
            'taxonomy_text_options',
            [
                'label' => esc_html__( 'Taxonomy Text', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'before'
            ]
        );
        $this->add_responsive_control(
            'taxonomy_text_margin',
            [
                'label' => esc_html__('Taxonomy Text Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .taxonomy .taxonomy-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .taxonomy .taxonomy-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'taxonomy_text_padding',
            [
                'label' => esc_html__('Taxonomy Text Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .taxonomy .taxonomy-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .taxonony .taxonomy-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'taxonomy_text_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .taxonomy .taxonomy-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .taxonomy .taxonomy-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',                ],
                'separator' => 'before'
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'taxonomy_text_border',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .taxonomy .taxonomy-text,{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .taxonomy .taxonomy-text',
            ]
        );
        $this->start_controls_tabs(
            'taxonomy_tabs',
        );
        $this->start_controls_tab(
            'taxonomy_normal',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'taxonomy_typography',
                'label' => esc_html__('Taxonomy', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .taxonomy,{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .taxonomy',
            ]
        );
        $this->add_control(
            'taxonomy_color',
            [
                'label' => esc_html__( 'Taxonomy Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .taxonomy' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .taxonomy' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'taxonomy_border',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .taxonomy,{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .taxonomy',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'taxonomy_background_color',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .taxonomy,{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .taxonomy',
            ]
        );
        $this->add_control(
            'taxonomy_translate_y',
            [
                'label' => esc_html__( 'Translate Y', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'default' => [
                    'size' => 0,
                ],
            ]
        );
        $this->add_control(
            'taxonomy_translate_x',
            [
                'label' => esc_html__( 'Translate X', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'default' => [
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .taxonomy' => 'transform: translate({{taxonomy_translate_x.SIZE}}{{taxonomy_translate_x.UNIT}},{{taxonomy_translate_y.SIZE}}{{taxonomy_translate_y.UNIT}})',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .taxonomy' => 'transform: translate({{taxonomy_translate_x.SIZE}}{{taxonomy_translate_x.UNIT}},{{taxonomy_translate_y.SIZE}}{{taxonomy_translate_y.UNIT}})',
                ],
            ]
        );
        $this->add_control(
            'taxonomy_opacity',
            [
                'label' => esc_html__( 'Taxonomy Opacity', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .taxonomy' => 'opacity: {{SIZE}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .taxonomy' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'taxonomy_active',
            [
                'label'   => esc_html__( 'Active', 'element-camp' ),
            ]
        );
        $this->add_control(
            'taxonomy_translate_y_active',
            [
                'label' => esc_html__( 'Translate Y', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'default' => [
                    'size' => 0,
                ],
            ]
        );
        $this->add_control(
            'taxonomy_translate_x_active',
            [
                'label' => esc_html__( 'Translate X', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'default' => [
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide-active .item .info .taxonomy' => 'transform: translate({{taxonomy_translate_x_active.SIZE}}{{taxonomy_translate_x_active.UNIT}},{{taxonomy_translate_y_active.SIZE}}{{taxonomy_translate_y_active.UNIT}})',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide-active .item .copy-info .taxonomy' => 'transform: translate({{taxonomy_translate_x_active.SIZE}}{{taxonomy_translate_x_active.UNIT}},{{taxonomy_translate_y_active.SIZE}}{{taxonomy_translate_y_active.UNIT}})',
                ],
            ]
        );
        $this->add_control(
            'taxonomy_opacity_active',
            [
                'label' => esc_html__( 'Taxonomy Opacity', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide-active .item .info .taxonomy' => 'opacity: {{SIZE}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide-active .item .copy-info .taxonomy' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        $this->start_controls_section(
            'section_date_style',
            [
                'label' => esc_html__('Date Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['show_date' => 'yes']
            ]
        );
        $this->add_responsive_control(
            'date_display',
            [
                'label' => esc_html__('Date Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'block',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'inline' => esc_html__('Inline', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .date' => 'display: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .date' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'date_padding',
            [
                'label' => esc_html__('Date Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .date' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .date' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'date_margin',
            [
                'label' => esc_html__('Date Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .date' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .date' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'date_typography',
                'label' => esc_html__('Date', 'element-camp'),
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .date,{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .date',
            ]
        );
        $this->add_control(
            'date_color',
            [
                'label' => esc_html__( 'Date Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .date' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .date' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'date_border',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .date,{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .date',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'date_background_color',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .date,{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .date',
            ]
        );
        $this->add_control(
            'date_opacity',
            [
                'label' => esc_html__( 'Date Opacity', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .date' => 'opacity: {{SIZE}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .date' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'button_style',
            [
                'label' => esc_html__('Button Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'button_switcher' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'button_overflow_hidden',
            [
                'label' => esc_html__( 'Overflow Hidden', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'yes', 'element-camp' ),
                'label_off' => esc_html__( 'No', 'element-camp' ),
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn' => 'overflow:hidden;',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn' => 'overflow:hidden;',
                ],
            ]
        );
        $this->add_control(
            'button_transition',
            [
                'label' => esc_html__( 'Button Transition', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn' => 'transition: all {{SIZE}}s ease;',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );
        $this->add_control(
            'butn_index',
            [
                'label' => esc_html__( 'Button z-index', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn' => 'z-index: {{SIZE}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn' => 'z-index: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'button_position_options',
            [
                'label' => esc_html__( 'Button Position Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'before'
            ]
        );
        $this->add_responsive_control(
            'position_for_button',
            [
                'label' => esc_html__( 'Position', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__( 'Default', 'element-camp' ),
                    'unset' => esc_html__( 'unset', 'element-camp' ),
                    'absolute' => esc_html__( 'Absolute', 'element-camp' ),
                    'relative' => esc_html__( 'Relative', 'element-camp' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn' => 'position: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn' => 'position: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'button_offset_orientation_h',
            [
                'label' => esc_html__( 'Horizontal Orientation', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => esc_html__( 'Start', 'element-camp' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'end' => [
                        'title' => esc_html__( 'End', 'element-camp' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'condition' => [
                    'position_for_button!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_offset_x',
            [
                'label' => esc_html__( 'Offset', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'size' => 0,
                ],
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn' => 'left: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn' => 'left: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'button_offset_orientation_h' => 'start',
                    'position_for_button!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_offset_x_end',
            [
                'label' => esc_html__( 'Offset', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'size' => 0,
                ],
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn' => 'right: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn' => 'right: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'button_offset_orientation_h' => 'end',
                    'position_for_button!' => '',
                ],
            ]
        );
        $this->add_control(
            'button_offset_orientation_v',
            [
                'label' => esc_html__( 'Vertical Orientation', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => esc_html__( 'Top', 'element-camp' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'end' => [
                        'title' => esc_html__( 'Bottom', 'element-camp' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'condition' => [
                    'position_for_button!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_offset_y',
            [
                'label' => esc_html__( 'Offset', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'vw', 'custom' ],
                'default' => [
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn' => 'top: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn' => 'top: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'button_offset_orientation_v' => 'start',
                    'position_for_button!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_offset_y_end',
            [
                'label' => esc_html__( 'Offset', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'vw', 'custom' ],
                'default' => [
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn' => 'bottom: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn' => 'bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'button_offset_orientation_v' => 'end',
                    'position_for_button!' => '',
                ],
            ]
        );
        $this->add_control(
            'button_translate_y',
            [
                'label' => esc_html__( 'Translate Y', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
            ]
        );
        $this->add_control(
            'button_translate_x',
            [
                'label' => esc_html__( 'Translate X', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn' => 'transform: translate({{button_translate_x.SIZE}}{{button_translate_x.UNIT}},{{button_translate_y.SIZE}}{{button_translate_y.UNIT}})',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn' => 'transform: translate({{button_translate_x.SIZE}}{{button_translate_x.UNIT}},{{button_translate_y.SIZE}}{{button_translate_y.UNIT}})',
                ],
            ]
        );
        $this->add_control(
            'button_style_options',
            [
                'label' => esc_html__( 'Button Style Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator'=>'before'
            ]
        );
        $this->add_responsive_control(
            'button_width',
            [
                'label' => esc_html__( 'Width', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'size_units' => [ '%', 'px', 'vw','custom' ],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 150,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 150,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn' => 'width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn' => 'width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'butn_text_align',
            [
                'label' => esc_html__( 'Text Alignment', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'element-camp' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'element-camp' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'element-camp' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__( 'Justified', 'element-camp' ),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_height',
            [
                'label' => esc_html__( 'Height', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
                'size_units' => [ 'px', 'vh', '%','custom' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 500,
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'butn_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_ACCENT,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn,{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn',
            ]
        );
        $this->add_control(
            'button_display',
            [
                'label' => esc_html__('Button Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'inline-block',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                    'inline-flex' => esc_html__('Inline Flex', 'element-camp'),
                    'none' => esc_html__('None', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn' => 'display: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control(
            'justify_content',
            [
                'label' => esc_html__( 'Justify Content', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'default' => '',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Start','element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-start-h',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-center-h',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'End', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-end-h',
                    ],
                    'space-between' => [
                        'title' => esc_html__( 'Space Between', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-space-between-h',
                    ],
                    'space-around' => [
                        'title' => esc_html__( 'Space Around', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-space-around-h',
                    ],
                    'space-evenly' => [
                        'title' => esc_html__( 'Space Evenly', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-space-evenly-h',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn' => 'justify-content: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn' => 'justify-content: {{VALUE}};',
                ],
                'condition'=>['button_display'=>['flex','inline-flex']],
                'responsive' => true,
            ]);
        $this->add_responsive_control(
            'align_items',
            [
                'label' => esc_html__( 'Align Items', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => '',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Start', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-align-start-v',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-align-center-v',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'End', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-align-end-v',
                    ],
                    'stretch' => [
                        'title' => esc_html__( 'Stretch', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-align-stretch-v',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn' => 'align-items: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn' => 'align-items: {{VALUE}};',
                ],
                'condition'=>['button_display'=>['flex','inline-flex']],
                'responsive' => true,
            ]);
        $this->add_control(
            'button_text_color',
            [
                'label' => esc_html__( 'Text Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn' => 'color: {{VALUE}}; fill: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'butn_background',
                'label' => esc_html__('Background', 'element-camp'),
                'types' => ['classic', 'gradient'],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn,{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'color' => [
                        'selectors' => [
                            '{{SELECTOR}}' => 'background: {{color.VALUE}}; background-image: none;',
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'button_opacity',
            [
                'label' => esc_html__( 'Opacity', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn' => 'opacity: {{SIZE}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn,{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn,{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'outline',
            [
                'label' => esc_html__( 'Outline', 'element-camp' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__( 'None', 'element-camp' ),
                    'solid' => esc_html__( 'Solid', 'element-camp' ),
                    'dashed' => esc_html__( 'Dashed', 'element-camp' ),
                    'dotted' => esc_html__( 'Dotted', 'element-camp' ),
                ],
                'default' => 'none',
            ]
        );

        $this->add_control(
            'outline_color',
            [
                'label' => esc_html__('Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'outline!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'outline_width',
            [
                'label' => esc_html__( 'Outline Width', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn' => 'outline: {{outline_width.SIZE}}{{outline_width.UNIT}} {{outline.VALUE}} {{outline_color.VALUE}}',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn' => 'outline: {{outline_width.SIZE}}{{outline_width.UNIT}} {{outline.VALUE}} {{outline_color.VALUE}}',
                ],
                'condition' => [
                    'outline!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'outline_offset',
            [
                'label' => esc_html__( 'Outline Offset', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 20,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn' => 'outline-offset: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn' => 'outline-offset: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'outline!' => 'none',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_margin',
            [
                'label' => esc_html__('Button Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => esc_html__('Button Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );
        $this->add_control(
            'button_icon_style_heading',
            [
                'label' => esc_html__( 'Button Icon Options', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );
        $this->add_control(
            'button_icon_transition',
            [
                'label' => esc_html__( 'Button Icon Transition', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn .butn-icon' => 'transition: all {{SIZE}}s ease;',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn .butn-icon' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );
        $this->add_control(
            'button_icon_display',
            [
                'label' => esc_html__('Button Icon Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'inline-block',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                    'flex' => esc_html__('Flex', 'element-camp'),
                    'inline-flex' => esc_html__('Inline Flex', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn .butn-icon' => 'display: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn .butn-icon' => 'display: {{VALUE}};',
                ]
            ]
        );
        $this->add_responsive_control(
            'button_icon_justify_content',
            [
                'label' => esc_html__( 'Justify Content', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'default' => '',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Start','element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-start-h',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-center-h',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'End', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-end-h',
                    ],
                    'space-between' => [
                        'title' => esc_html__( 'Space Between', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-space-between-h',
                    ],
                    'space-around' => [
                        'title' => esc_html__( 'Space Around', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-space-around-h',
                    ],
                    'space-evenly' => [
                        'title' => esc_html__( 'Space Evenly', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-justify-space-evenly-h',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn .butn-icon' => 'justify-content: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn .butn-icon' => 'justify-content: {{VALUE}};',
                ],
                'condition'=>['button_icon_display'=>['flex','inline-flex']],
                'responsive' => true,
            ]);
        $this->add_responsive_control(
            'button_icon_align_items',
            [
                'label' => esc_html__( 'Align Items', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => '',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Start', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-align-start-v',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-align-center-v',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'End', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-align-end-v',
                    ],
                    'stretch' => [
                        'title' => esc_html__( 'Stretch', 'element-camp' ),
                        'icon' => 'eicon-flex eicon-align-stretch-v',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn .butn-icon' => 'align-items: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn .butn-icon' => 'align-items: {{VALUE}};',
                ],
                'condition'=>['button_icon_display'=>['flex','inline-flex']],
                'responsive' => true,
            ]);
        $this->add_responsive_control(
            'button_icon_size',
            [
                'label' => esc_html__( 'Icon size', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' , 'custom' , 'rem' , 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn .butn-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn .butn-icon svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn .butn-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn .butn-icon svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_icon_wrapper_width',
            [
                'label' => esc_html__( 'Icon Wrapper Width', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px','custom' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn .butn-icon' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn .butn-icon' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_icon_wrapper_height',
            [
                'label' => esc_html__( 'Icon Wrapper Height', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px','custom' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn .butn-icon' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn .butn-icon' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_icon_margin',
            [
                'label' => esc_html__('Button Icon Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn .butn-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn .butn-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_icon_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn .butn-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn .butn-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'button_icon_color',
            [
                'label' => __('Icon Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn .butn-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn .butn-icon svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn .butn-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn .butn-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'button_icon_background',
                'label' => esc_html__('Button Icon Background', 'element-camp'),
                'types' => [ 'classic','gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn .butn-icon,{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn .butn-icon',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'button_icon_border',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn .butn-icon,{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn .butn-icon',
            ]
        );
        $this->add_control(
            'translate_button_icon_y',
            [
                'label' => esc_html__( 'Translate Y', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
            ]
        );
        $this->add_control(
            'translate_button_icon_x',
            [
                'label' => esc_html__( 'Translate X', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .info .butn .butn-icon' => 'transform: translate({{translate_button_icon_x.SIZE}}{{translate_button_icon_x.UNIT}},{{translate_button_icon_y.SIZE}}{{translate_button_icon_y.UNIT}})',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .copy-info .butn .butn-icon' => 'transform: translate({{translate_button_icon_x.SIZE}}{{translate_button_icon_x.UNIT}},{{translate_button_icon_y.SIZE}}{{translate_button_icon_y.UNIT}})',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_arrows_style',
            [
                'label' => __('Arrows Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'arrows' => 'true',
                ]
            ]
        );

        $this->add_control(
            'arrows_positioning',
            [
                'label' => esc_html__('Position', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'unset' => esc_html__('unset', 'element-camp'),
                    'absolute' => esc_html__('absolute', 'element-camp'),
                    'relative' => esc_html__('relative', 'element-camp'),
                ],
                'label_block' => true,
                'default' => 'absolute',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows' => 'position: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrows_width',
            [
                'label' => esc_html__('Width', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrows_height',
            [
                'label' => esc_html__('Height', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'tabs_arrows_style'
        );

        $this->start_controls_tab(
            'style_arrows_normal_tab',
            [
                'label' => esc_html__('Normal', 'element-camp'),
            ]
        );

        $this->add_control(
            'arrows_arrow_color',
            [
                'label' => esc_html__('Arrow Color', 'element-camp'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrows_text_color',
            [
                'label' => esc_html__('Arrow Text Color', 'element-camp'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows .tcgelements-showcase-arrow-text' => 'color: {{VALUE}};',
                ],
            ]
        );


        $this->add_responsive_control(
            'arrows_icon_size',
            [
                'label' => esc_html__('Icon Size', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'arrows_arrow_typography',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows .tcgelements-showcase-arrow-text',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'arrows_background',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows',
                'exclude' => ['image']
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'arrows_border',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows',
            ]
        );

        $this->add_responsive_control(
            'arrows_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrows_icon_border_radius',
            [
                'label' => esc_html__('Icon Border Radius', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows svg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'arrows_box_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows',
            ]
        );

        $this->add_control(
            'arrow_icon_style',
            [
                'label' => esc_html__( 'Arrow Icon', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'arrows_icon_border',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows svg,{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows i',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'style_arrows_hover_tab',
            [
                'label' => esc_html__('Hover', 'element-camp'),
            ]
        );

        $this->add_control(
            'arrows_arrow_color_hover',
            [
                'label' => esc_html__('Arrow Color', 'element-camp'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows:hover' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'arrows_arrow_typography_hover',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows:hover .tcgelements-showcase-arrow-text',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'arrows_background_hover',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows:hover',
                'exclude' => ['image']
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'arrows_border_hover',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows:hover',
            ]
        );

        $this->add_responsive_control(
            'arrows_border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'arrows_box_shadow_hover',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows:hover',
            ]
        );

        $this->add_control(
            'arrow_icon_style_hover',
            [
                'label' => esc_html__( 'Arrow Icon', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'arrows_icon_border_hover',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows:hover svg,{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows:hover i',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'arrows_icon_padding',
            [
                'label' => esc_html__('Icon Padding', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrows_padding',
            [
                'label' => esc_html__('Padding', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrows_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'arrows_position',
            [
                'label' => esc_html__('Arrows Position', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'arrows_offset_orientation_v',
            [
                'label' => esc_html__('Vertical Orientation', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => esc_html__('Top', 'element-camp'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'end' => [
                        'title' => esc_html__('Bottom', 'element-camp'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'render_type' => 'ui',
            ]
        );

        $this->add_responsive_control(
            'arrows_offset_y',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vh', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'arrows_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrows_offset_y_end',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vh', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'arrows_offset_orientation_v' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrows_z_index',
            [
                'label' => esc_html__('Z-Index', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows' => 'z-index: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_each_arrow_style',
            [
                'label' => __('Each Arrow Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'arrows' => 'true',
                ]
            ]
        );

        $this->add_control(
            'next_arrow_options',
            [
                'label' => esc_html__('Next Arrow Options', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'next_arrow_positioning',
            [
                'label' => esc_html__('Position', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'unset' => esc_html__('unset', 'element-camp'),
                    'absolute' => esc_html__('absolute', 'element-camp'),
                    'relative' => esc_html__('relative', 'element-camp'),
                ],
                'label_block' => true,
                'default' => 'absolute',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-next' => 'position: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'next_arrow_width',
            [
                'label' => esc_html__('Width', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-next' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'next_arrow_height',
            [
                'label' => esc_html__('Height', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-next' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'tabs_next_arrow_style'
        );

        $this->start_controls_tab(
            'style_next_arrow_normal_tab',
            [
                'label' => esc_html__('Normal', 'element-camp'),
            ]
        );

        $this->add_control(
            'next_arrow_color',
            [
                'label' => esc_html__('Arrow Color', 'element-camp'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-next' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'next_arrow_typography',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-next .tcgelements-showcase-arrow-text',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'next_arrow_background',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-next',
                'exclude' => ['image']
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'next_arrow_border',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-next',
            ]
        );

        $this->add_responsive_control(
            'next_arrow_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'next_arrow_box_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-next',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'style_next_arrow_hover_tab',
            [
                'label' => esc_html__('Hover', 'element-camp'),
            ]
        );

        $this->add_control(
            'next_arrow_color_hover',
            [
                'label' => esc_html__('Arrow Color', 'element-camp'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-next:hover' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'next_arrow_typography_hover',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-next:hover .tcgelements-showcase-arrow-text',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'next_arrow_background_hover',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-next:hover',
                'exclude' => ['image']
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'next_arrow_border_hover',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-next:hover',
            ]
        );

        $this->add_responsive_control(
            'next_arrow_border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-next:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'next_arrow_box_shadow_hover',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-next:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'next_arrow_padding',
            [
                'label' => esc_html__('Padding', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-next' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'next_arrow_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-next' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'next_arrow_text_margin',
            [
                'label' => esc_html__('Text Margin', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-next .tcgelements-showcase-arrow-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'next_arrow_position',
            [
                'label' => esc_html__('Arrows Position', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $start = is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');
        $end = !is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');

        $this->add_control(
            'next_arrow_offset_orientation_h',
            [
                'label' => esc_html__('Horizontal Orientation', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => $start,
                        'icon' => 'eicon-h-align-left',
                    ],
                    'end' => [
                        'title' => $end,
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'classes' => 'elementor-control-start-end',
                'render_type' => 'ui',
            ]
        );

        $this->add_responsive_control(
            'next_arrow_offset_x',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh', 'custom'],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-next' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-next' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'next_arrow_offset_orientation_h!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'next_arrow_offset_x_end',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 0.1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh', 'custom'],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-next' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-next' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'next_arrow_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_control(
            'next_arrow_offset_orientation_v',
            [
                'label' => esc_html__('Vertical Orientation', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => esc_html__('Top', 'element-camp'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'end' => [
                        'title' => esc_html__('Bottom', 'element-camp'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'render_type' => 'ui',
            ]
        );

        $this->add_responsive_control(
            'next_arrow_offset_y',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vh', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-next' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'next_arrow_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'next_arrow_offset_y_end',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vh', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-next' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'next_arrow_offset_orientation_v' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'next_arrow_z_index',
            [
                'label' => esc_html__('Z-Index', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-next' => 'z-index: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'prev_arrow_options',
            [
                'label' => esc_html__('Prev Arrow Options', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'prev_arrow_positioning',
            [
                'label' => esc_html__('Position', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'unset' => esc_html__('unset', 'element-camp'),
                    'absolute' => esc_html__('absolute', 'element-camp'),
                    'relative' => esc_html__('relative', 'element-camp'),
                ],
                'label_block' => true,
                'default' => 'absolute',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-prev' => 'position: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'prev_arrow_width',
            [
                'label' => esc_html__('Width', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-prev' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'prev_arrow_height',
            [
                'label' => esc_html__('Height', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-prev' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'tabs_prev_arrow_style'
        );

        $this->start_controls_tab(
            'style_prev_arrow_normal_tab',
            [
                'label' => esc_html__('Normal', 'element-camp'),
            ]
        );

        $this->add_control(
            'prev_arrow_color',
            [
                'label' => esc_html__('Arrow Color', 'element-camp'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-prev' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'prev_arrow_typography',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-prev .tcgelements-showcase-arrow-text',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'prev_arrow_background',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-prev',
                'exclude' => ['image']
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'prev_arrow_border',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-prev',
            ]
        );

        $this->add_responsive_control(
            'prev_arrow_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'prev_arrow_box_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-prev',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'style_prev_arrow_hover_tab',
            [
                'label' => esc_html__('Hover', 'element-camp'),
            ]
        );

        $this->add_control(
            'prev_arrow_color_hover',
            [
                'label' => esc_html__('Arrow Color', 'element-camp'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-prev:hover' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'prev_arrow_typography_hover',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-prev:hover .tcgelements-showcase-arrow-text',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'prev_arrow_background_hover',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-prev:hover',
                'exclude' => ['image']
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'prev_arrow_border_hover',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-prev:hover',
            ]
        );

        $this->add_responsive_control(
            'prev_arrow_border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-prev:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'prev_arrow_box_shadow_hover',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-prev:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'prev_arrow_padding',
            [
                'label' => esc_html__('Padding', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-prev' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'prev_arrow_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-prev' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'prev_arrow_text_margin',
            [
                'label' => esc_html__('Text Margin', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-prev .tcgelements-showcase-arrow-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'prev_arrow_position',
            [
                'label' => esc_html__('Arrows Position', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $start = is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');
        $end = !is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');

        $this->add_control(
            'prev_arrow_offset_orientation_h',
            [
                'label' => esc_html__('Horizontal Orientation', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => $start,
                        'icon' => 'eicon-h-align-left',
                    ],
                    'end' => [
                        'title' => $end,
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'classes' => 'elementor-control-start-end',
                'render_type' => 'ui',
            ]
        );

        $this->add_responsive_control(
            'prev_arrow_offset_x',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh', 'custom'],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-prev' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-prev' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'prev_arrow_offset_orientation_h!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'prev_arrow_offset_x_end',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 0.1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh', 'custom'],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-prev' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-prev' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'prev_arrow_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_control(
            'prev_arrow_offset_orientation_v',
            [
                'label' => esc_html__('Vertical Orientation', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => esc_html__('Top', 'element-camp'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'end' => [
                        'title' => esc_html__('Bottom', 'element-camp'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'render_type' => 'ui',
            ]
        );

        $this->add_responsive_control(
            'prev_arrow_offset_y',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vh', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-prev' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'prev_arrow_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'prev_arrow_offset_y_end',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vh', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-prev' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'prev_arrow_offset_orientation_v' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'prev_arrow_z_index',
            [
                'label' => esc_html__('Z-Index', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-showcase-arrows.swiper-button-prev' => 'z-index: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_pagination_progress_bar_style',
            [
                'label' => __('Pagination Progress Bar Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'pagination' => 'true',
                    'paginationType' => 'progressbar'
                ]
            ]
        );

        $this->add_responsive_control(
            'pagination_progress_bar_size',
            [
                'label' => esc_html__('Size', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-progressbar.swiper-pagination-horizontal' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-progressbar.swiper-pagination-vertical' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'progress_bar_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-progressbar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; width: calc(100% - ({{RIGHT}}{{UNIT}} + {{LEFT}}{{UNIT}}));',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-progressbar.swiper-progressbar-vertical' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; height: calc(100% - (({{TOP}}{{UNIT}} + {{BOTTOM}}{{UNIT}}) * 3));',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'pagination_progress_bar_progress',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-progressbar .swiper-pagination-progressbar-fill',
                'exclude' => ['image'],
                'fields_options' => [
                    'background' => [
                        'label' => esc_html_x('Progress Bar Color', 'Background Control', 'element-camp'),
                    ]
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'pagination_progress_bar_background',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-progressbar',
                'exclude' => ['image'],
                'fields_options' => [
                    'background' => [
                        'label' => esc_html_x('Progress Bar Background', 'Background Control', 'element-camp'),
                    ]
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'pagination_progress_bar_border',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-progressbar',
            ]
        );

        $this->add_responsive_control(
            'pagination_progress_bar_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-progressbar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-progressbar .swiper-pagination-progressbar-fill' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_pagination_bullets_style',
            [
                'label' => __('Pagination Bullets Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'pagination' => 'true',
                    'paginationType' => 'bullets'
                ]
            ]
        );

        $this->add_control(
            'pagination_bullets_positioning',
            [
                'label' => esc_html__('Position', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'unset' => esc_html__('unset', 'element-camp'),
                    'absolute' => esc_html__('absolute', 'element-camp'),
                    'relative' => esc_html__('relative', 'element-camp'),
                ],
                'label_block' => true,
                'default' => 'absolute',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-bullets' => 'position: {{VALUE}};',
                ],
            ]
        );

        $start = is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');
        $end = !is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');

        $this->add_control(
            'pagination_bullets_offset_orientation_h',
            [
                'label' => esc_html__('Horizontal Orientation', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => $start,
                        'icon' => 'eicon-h-align-left',
                    ],
                    'end' => [
                        'title' => $end,
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'classes' => 'elementor-control-start-end',
                'render_type' => 'ui',
            ]
        );

        $this->add_responsive_control(
            'pagination_bullets_offset_x',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh', 'custom'],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-showcase .swiper-pagination-bullets.swiper-pagination-horizontal' => 'left: {{SIZE}}{{UNIT}}; right: unset; transform: translateX(50%);',
                    'body.rtl {{WRAPPER}} .tcgelements-showcase .swiper-pagination-bullets.swiper-pagination-horizontal' => 'right: {{SIZE}}{{UNIT}}; left: unset; transform: translateX(-50%);',
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-showcase .swiper-pagination-bullets.swiper-pagination-vertical' => 'left: {{SIZE}}{{UNIT}}; right: unset; transform: translateY(50%);',
                    'body.rtl {{WRAPPER}} .tcgelements-showcase .swiper-pagination-bullets.swiper-pagination-vertical' => 'right: {{SIZE}}{{UNIT}}; left: unset; transform: translateY(50%);',
                ],
                'condition' => [
                    'pagination_bullets_offset_orientation_h!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_bullets_offset_x_end',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 0.1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh', 'custom'],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-showcase .swiper-pagination-bullets.swiper-pagination-horizontal' => 'right: {{SIZE}}{{UNIT}}; left: unset; transform: translateX(-50%);',
                    'body.rtl {{WRAPPER}} .tcgelements-showcase .swiper-pagination-bullets.swiper-pagination-horizontal' => 'left: {{SIZE}}{{UNIT}}; right: unset; transform: translateX(50%);',
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-showcase .swiper-pagination-bullets.swiper-pagination-vertical' => 'right: {{SIZE}}{{UNIT}}; left: unset; transform: translateY(50%);',
                    'body.rtl {{WRAPPER}} .tcgelements-showcase .swiper-pagination-bullets.swiper-pagination-vertical' => 'left: {{SIZE}}{{UNIT}}; right: unset; transform: translateY(50%);',
                ],
                'condition' => [
                    'pagination_bullets_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_control(
            'pagination_bullets_offset_orientation_v',
            [
                'label' => esc_html__('Vertical Orientation', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => esc_html__('Top', 'element-camp'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'end' => [
                        'title' => esc_html__('Bottom', 'element-camp'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'render_type' => 'ui',
            ]
        );

        $this->add_responsive_control(
            'pagination_bullets_offset_y',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vh', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-bullets' => 'top: {{SIZE}}{{UNIT}}; bottom: unset; transform: translateY(50%) translateX(50%);',
                ],
                'condition' => [
                    'pagination_bullets_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_bullets_offset_y_end',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vh', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-bullets' => 'bottom: {{SIZE}}{{UNIT}}; top: unset; transform: translateY(-50%) translateX(50%);',
                ],
                'condition' => [
                    'pagination_bullets_offset_orientation_v' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_bullets_translate_y',
            [
                'label' => esc_html__( 'Translate Y', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
            ]
        );
        $this->add_responsive_control(
            'pagination_bullets_translate_x',
            [
                'label' => esc_html__( 'Translate X', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-bullets' => 'transform: translate({{pagination_bullets_translate_x.SIZE}}{{pagination_bullets_translate_x.UNIT}},{{pagination_bullets_translate_y.SIZE}}{{pagination_bullets_translate_y.UNIT}}) !important',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_bullets_z_index',
            [
                'label' => esc_html__('Z-Index', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-bullets' => 'z-index: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_bullet_space',
            [
                'label' => esc_html__('Space', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-bullets .swiper-pagination-bullet' => 'margin: 0 {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs(
            'tabs_pagination_bullets_style'
        );

        $this->start_controls_tab(
            'style_pagination_bullets_normal_tab',
            [
                'label' => esc_html__('Normal', 'element-camp'),
            ]
        );

        $this->add_responsive_control(
            'pagination_bullet_size',
            [
                'label' => esc_html__('Size', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-bullets .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_bullet_height',
            [
                'label' => esc_html__('Height', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-bullets .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'pagination_bullet_background',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-bullets .swiper-pagination-bullet',
                'exclude' => ['image'],
                'fields_options' => [
                    'background' => [
                        'label' => esc_html_x('Bullet Background', 'Background Control', 'element-camp'),
                    ]
                ]
            ]
        );

        $this->add_responsive_control(
            'pagination_bullet_opacity',
            [
                'label' => esc_html__('Bullet Opacity', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-bullets .swiper-pagination-bullet' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_bullet_outline',
            [
                'label' => esc_html__('Outline', 'element-camp'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'element-camp'),
                'label_on' => esc_html__('Custom', 'element-camp'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->start_popover();

        $this->add_control(
            'pagination_bullet_outline_type',
            [
                'label' => esc_html__('Border Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'none' => esc_html__('None', 'element-camp'),
                    'solid' => esc_html__('Solid', 'element-camp'),
                    'double' => esc_html__('Double', 'element-camp'),
                    'dotted' => esc_html__('Dotted', 'element-camp'),
                    'dashed' => esc_html__('Dashed', 'element-camp'),
                    'groove' => esc_html__('Groove', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-bullets .swiper-pagination-bullet' => 'outline-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_bullet_outline_width',
            [
                'label' => esc_html__('Bullet Width', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem', 'vw', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'condition' => [
                    'pagination_bullet_outline_type!' => ['', 'none'],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-bullets .swiper-pagination-bullet' => 'outline-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_bullet_outline_color',
            [
                'label' => esc_html__('Border Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-bullets .swiper-pagination-bullet' => 'outline-color: {{VALUE}};',
                ],
                'condition' => [
                    'pagination_bullet_outline_type!' => ['', 'none'],
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_bullet_outline_offset',
            [
                'label' => esc_html__('Bullet Offset', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'condition' => [
                    'pagination_bullet_outline_type!' => ['', 'none'],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-bullets .swiper-pagination-bullet' => 'outline-offset: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_popover();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'pagination_bullet_border',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-bullets .swiper-pagination-bullet',
            ]
        );

        $this->add_responsive_control(
            'pagination_bullet_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-bullets .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'style_pagination_bullets_active_tab',
            [
                'label' => esc_html__('Active', 'element-camp'),
            ]
        );

        $this->add_responsive_control(
            'pagination_active_bullet_size',
            [
                'label' => esc_html__('Size', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_active_bullet_height',
            [
                'label' => esc_html__('Height', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'pagination_active_bullet_background',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active',
                'exclude' => ['image'],
                'fields_options' => [
                    'background' => [
                        'label' => esc_html_x('Active Bullet Color', 'Background Control', 'element-camp'),
                    ]
                ]
            ]
        );

        $this->add_responsive_control(
            'pagination_active_bullet_opacity',
            [
                'label' => esc_html__('Bullet Opacity', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_active_bullet_outline',
            [
                'label' => esc_html__('Outline', 'element-camp'),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'element-camp'),
                'label_on' => esc_html__('Custom', 'element-camp'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->start_popover();

        $this->add_control(
            'pagination_active_bullet_outline_type',
            [
                'label' => esc_html__('Border Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'element-camp'),
                    'none' => esc_html__('None', 'element-camp'),
                    'solid' => esc_html__('Solid', 'element-camp'),
                    'double' => esc_html__('Double', 'element-camp'),
                    'dotted' => esc_html__('Dotted', 'element-camp'),
                    'dashed' => esc_html__('Dashed', 'element-camp'),
                    'groove' => esc_html__('Groove', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'outline-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_active_bullet_outline_width',
            [
                'label' => esc_html__('Bullet Width', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem', 'vw', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'condition' => [
                    'pagination_active_bullet_outline_type!' => ['', 'none'],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'outline-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_active_bullet_outline_color',
            [
                'label' => esc_html__('Border Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'outline-color: {{VALUE}};',
                ],
                'condition' => [
                    'pagination_active_bullet_outline_type!' => ['', 'none'],
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_active_bullet_outline_offset',
            [
                'label' => esc_html__('Bullet Offset', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'condition' => [
                    'pagination_active_bullet_outline_type!' => ['', 'none'],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'outline-offset: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_popover();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'pagination_active_bullet_border',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active',
            ]
        );

        $this->add_responsive_control(
            'pagination_active_bullet_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'pagination_bullets_padding',
            [
                'label' => esc_html__('Padding', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-bullets' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_bullets_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-bullets' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_pagination_fraction_style',
            [
                'label' => __('Pagination Fraction Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'pagination' => 'true',
                    'paginationType' => 'fraction'
                ]
            ]
        );
        $this->add_responsive_control(
            'fraction_text_align',
            [
                'label' => esc_html__( 'Text Alignment', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'element-camp' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'element-camp' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'element-camp' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__( 'Justified', 'element-camp' ),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-fraction' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'pagination_fraction_positioning',
            [
                'label' => esc_html__('Position', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'unset' => esc_html__('unset', 'element-camp'),
                    'absolute' => esc_html__('absolute', 'element-camp'),
                    'relative' => esc_html__('relative', 'element-camp'),
                ],
                'label_block' => true,
                'default' => 'absolute',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-fraction' => 'position: {{VALUE}};',
                ],
            ]
        );

        $start = is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');
        $end = !is_rtl() ? esc_html__('Right', 'element-camp') : esc_html__('Left', 'element-camp');

        $this->add_control(
            'pagination_fraction_offset_orientation_h',
            [
                'label' => esc_html__('Horizontal Orientation', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => $start,
                        'icon' => 'eicon-h-align-left',
                    ],
                    'end' => [
                        'title' => $end,
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'classes' => 'elementor-control-start-end',
                'render_type' => 'ui',
            ]
        );

        $this->add_responsive_control(
            'pagination_fraction_offset_x',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh', 'custom'],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-showcase .swiper-pagination-fraction' => 'left: {{SIZE}}{{UNIT}}; transform: translateX(-50%);',
                    'body.rtl {{WRAPPER}} .tcgelements-showcase .swiper-pagination-fraction' => 'right: {{SIZE}}{{UNIT}}; transform: translateX(50%);',
                ],
                'condition' => [
                    'pagination_fraction_offset_orientation_h!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_fraction_offset_x_end',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 0.1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh', 'custom'],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-showcase .swiper-pagination-fraction' => 'right: {{SIZE}}{{UNIT}}; transform: translateX(50%);',
                    'body.rtl {{WRAPPER}} .tcgelements-showcase .swiper-pagination-fraction' => 'left: {{SIZE}}{{UNIT}}; transform: translateX(-50%);',
                ],
                'condition' => [
                    'pagination_fraction_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_control(
            'pagination_fraction_offset_orientation_v',
            [
                'label' => esc_html__('Vertical Orientation', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => esc_html__('Top', 'element-camp'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'end' => [
                        'title' => esc_html__('Bottom', 'element-camp'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'render_type' => 'ui',
            ]
        );

        $this->add_responsive_control(
            'pagination_fraction_offset_y',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vh', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-fraction' => 'top: {{SIZE}}{{UNIT}}; bottom: unset; transform: translateY(50%);',
                ],
                'condition' => [
                    'pagination_fraction_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_fraction_offset_y_end',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vh', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-fraction' => 'bottom: {{SIZE}}{{UNIT}}; top: unset; transform: translateY(-50%);',
                ],
                'condition' => [
                    'pagination_fraction_offset_orientation_v' => 'end',
                ],
            ]
        );
        $this->add_control(
            'fraction_translate_y',
            [
                'label' => esc_html__( 'Translate Y', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
            ]
        );
        $this->add_control(
            'fraction_translate_x',
            [
                'label' => esc_html__( 'Translate X', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-fraction' => 'transform: translate({{fraction_translate_x.SIZE}}{{fraction_translate_x.UNIT}},{{fraction_translate_y.SIZE}}{{fraction_translate_y.UNIT}})',
                ],
            ]
        );
        $this->add_responsive_control(
            'pagination_fraction_z_index',
            [
                'label' => esc_html__('Z-Index', 'element-camp'),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-fraction' => 'z-index: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_fraction_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-fraction' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'tabs_pagination_fraction_style'
        );

        $this->start_controls_tab(
            'style_pagination_fraction_normal_tab',
            [
                'label' => esc_html__('Normal', 'element-camp'),
            ]
        );

        $this->add_control(
            'pagination_fraction_color',
            [
                'label' => esc_html__('Text Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-fraction' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'pagination_fraction_typography',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-fraction',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'pagination_fraction_text_stroke',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-fraction',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'pagination_fraction_text_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-fraction',
            ]
        );

        $this->add_control(
            'pagination_fraction_blend_mode',
            [
                'label' => esc_html__('Blend Mode', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Normal', 'element-camp'),
                    'multiply' => 'Multiply',
                    'screen' => 'Screen',
                    'overlay' => 'Overlay',
                    'darken' => 'Darken',
                    'lighten' => 'Lighten',
                    'color-dodge' => 'Color Dodge',
                    'saturation' => 'Saturation',
                    'color' => 'Color',
                    'difference' => 'Difference',
                    'exclusion' => 'Exclusion',
                    'hue' => 'Hue',
                    'luminosity' => 'Luminosity',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-fraction' => 'mix-blend-mode: {{VALUE}}',
                ],
                'separator' => 'none',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'style_pagination_fraction_current_tab',
            [
                'label' => esc_html__('Current', 'element-camp'),
            ]
        );

        $this->add_control(
            'pagination_fraction_current_color',
            [
                'label' => esc_html__('Text Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-fraction .swiper-pagination-current' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'pagination_fraction_current_typography',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-fraction .swiper-pagination-current',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'pagination_fraction_current_text_stroke',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-fraction .swiper-pagination-current',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'pagination_fraction_current_text_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-fraction .swiper-pagination-current',
            ]
        );

        $this->add_control(
            'pagination_fraction_current_blend_mode',
            [
                'label' => esc_html__('Blend Mode', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Normal', 'element-camp'),
                    'multiply' => 'Multiply',
                    'screen' => 'Screen',
                    'overlay' => 'Overlay',
                    'darken' => 'Darken',
                    'lighten' => 'Lighten',
                    'color-dodge' => 'Color Dodge',
                    'saturation' => 'Saturation',
                    'color' => 'Color',
                    'difference' => 'Difference',
                    'exclusion' => 'Exclusion',
                    'hue' => 'Hue',
                    'luminosity' => 'Luminosity',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-fraction .swiper-pagination-current' => 'mix-blend-mode: {{VALUE}}',
                ],
                'separator' => 'none',
            ]
        );

        $this->add_responsive_control(
            'pagination_fraction_current_margin',
            [
                'label' => esc_html__('Current Margin', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-fraction .swiper-pagination-current' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'style_pagination_fraction_total_tab',
            [
                'label' => esc_html__('Total', 'element-camp'),
            ]
        );

        $this->add_control(
            'pagination_fraction_total_color',
            [
                'label' => esc_html__('Text Color', 'element-camp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-fraction .swiper-pagination-total' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'pagination_fraction_total_typography',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-fraction .swiper-pagination-total',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'pagination_fraction_total_text_stroke',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-fraction .swiper-pagination-total',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'pagination_fraction_total_text_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-fraction .swiper-pagination-total',
            ]
        );

        $this->add_control(
            'pagination_fraction_total_blend_mode',
            [
                'label' => esc_html__('Blend Mode', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Normal', 'element-camp'),
                    'multiply' => 'Multiply',
                    'screen' => 'Screen',
                    'overlay' => 'Overlay',
                    'darken' => 'Darken',
                    'lighten' => 'Lighten',
                    'color-dodge' => 'Color Dodge',
                    'saturation' => 'Saturation',
                    'color' => 'Color',
                    'difference' => 'Difference',
                    'exclusion' => 'Exclusion',
                    'hue' => 'Hue',
                    'luminosity' => 'Luminosity',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-fraction .swiper-pagination-total' => 'mix-blend-mode: {{VALUE}}',
                ],
                'separator' => 'none',
            ]
        );

        $this->add_responsive_control(
            'pagination_fraction_total_margin',
            [
                'label' => esc_html__('Total Margin', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-pagination-fraction .swiper-pagination-total' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_scroll_bar_style',
            [
                'label' => __('Scroll Bar Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'scrollbar' => 'true',
                ]
            ]
        );

        $this->add_control(
            'pagination_scroll_bar_position',
            [
                'label' => esc_html__('Scroll Bar Position', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'pagination_scroll_bar_positioning',
            [
                'label' => esc_html__('Position', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'unset' => esc_html__('unset', 'element-camp'),
                    'absolute' => esc_html__('absolute', 'element-camp'),
                    'relative' => esc_html__('relative', 'element-camp'),
                ],
                'label_block' => true,
                'default' => 'absolute',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-scrollbar.scrollbar-vertical' => 'position: {{VALUE}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-scrollbar.scrollbar-horizontal' => 'position: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_scroll_bar_offset_orientation_h',
            [
                'label' => esc_html__('Horizontal Orientation', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => $start,
                        'icon' => 'eicon-h-align-left',
                    ],
                    'end' => [
                        'title' => $end,
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'classes' => 'elementor-control-start-end',
                'render_type' => 'ui',
            ]
        );

        $this->add_responsive_control(
            'pagination_scroll_bar_offset_x',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh', 'custom'],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-showcase .swiper-scrollbar.scrollbar-vertical, body:not(.rtl) {{WRAPPER}} .tcgelements-showcase .swiper-scrollbar.scrollbar-horizontal' => 'left: {{SIZE}}{{UNIT}}; transform: translateX(-50%);',
                    'body.rtl {{WRAPPER}} .tcgelements-showcase .swiper-scrollbar.scrollbar-vertical, {{WRAPPER}} body.rtl .tcgelements-showcase .swiper-scrollbar.scrollbar-horizontal' => 'right: {{SIZE}}{{UNIT}}; transform: translateX(50%);',
                ],
                'condition' => [
                    'pagination_scroll_bar_offset_orientation_h!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_scroll_bar_offset_x_end',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 0.1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh', 'custom'],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-showcase .swiper-scrollbar.scrollbar-vertical, body:not(.rtl) {{WRAPPER}} .tcgelements-showcase .swiper-scrollbar.scrollbar-horizontal' => 'right: {{SIZE}}{{UNIT}}; transform: translateX(50%);',
                    'body.rtl {{WRAPPER}} .tcgelements-showcase .swiper-scrollbar.scrollbar-vertical, {{WRAPPER}} body.rtl .tcgelements-showcase .swiper-scrollbar.scrollbar-horizontal' => 'left: {{SIZE}}{{UNIT}}; transform: translateX(-50%);',
                ],
                'condition' => [
                    'pagination_scroll_bar_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_control(
            'pagination_scroll_bar_offset_orientation_v',
            [
                'label' => esc_html__('Vertical Orientation', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => esc_html__('Top', 'element-camp'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'end' => [
                        'title' => esc_html__('Bottom', 'element-camp'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'render_type' => 'ui',
            ]
        );

        $this->add_responsive_control(
            'pagination_scroll_bar_offset_y',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vh', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-scrollbar.scrollbar-vertical, {{WRAPPER}} .tcgelements-showcase .swiper-scrollbar.scrollbar-horizontal' => 'top: {{SIZE}}{{UNIT}}; bottom: unset; transform: translateY(50%);',
                ],
                'condition' => [
                    'pagination_scroll_bar_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_scroll_bar_offset_y_end',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vh', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-scrollbar.scrollbar-vertical, {{WRAPPER}} .tcgelements-showcase .swiper-scrollbar.scrollbar-horizontal' => 'bottom: {{SIZE}}{{UNIT}}; top: unset; transform: translateY(-50%);',
                ],
                'condition' => [
                    'pagination_scroll_bar_offset_orientation_v' => 'end',
                ],
            ]
        );

        $this->add_control(
            'pagination_scroll_bar_bg',
            [
                'label' => esc_html__('Scroll Bar Background', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'scroll_bar_progress',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-scrollbar .swiper-scrollbar-drag',
                'exclude' => ['image'],
                'fields_options' => [
                    'background' => [
                        'label' => esc_html_x('Progress Bar Color', 'Background Control', 'element-camp'),
                    ]
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'scroll_bar_background',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-scrollbar',
                'exclude' => ['image'],
                'fields_options' => [
                    'background' => [
                        'label' => esc_html_x('Progress Bar Background', 'Background Control', 'element-camp'),
                    ]
                ]
            ]
        );

        $this->add_control(
            'pagination_scroll_bar_styling',
            [
                'label' => esc_html__('Scroll Bar Styling', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'pagination_scroll_bar_width',
            [
                'label' => esc_html__('Width', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-scrollbar.scrollbar-horizontal, {{WRAPPER}} .tcgelements-showcase .swiper-scrollbar.scrollbar-horizontal .swiper-scrollbar-drag' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-scrollbar.scrollbar-vertical, {{WRAPPER}} .tcgelements-showcase .swiper-scrollbar.scrollbar-vertical .swiper-scrollbar-drag' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_scroll_bar_height',
            [
                'label' => esc_html__('Height', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-scrollbar.scrollbar-horizontal, {{WRAPPER}} .tcgelements-showcase .swiper-scrollbar.scrollbar-horizontal .swiper-scrollbar-drag' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-scrollbar.scrollbar-vertical, {{WRAPPER}} .tcgelements-showcase .swiper-scrollbar.scrollbar-vertical .swiper-scrollbar-drag' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'scroll_bar_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-scrollbar.swiper-scrollbar-horizontal' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; width: calc(100% - ({{RIGHT}}{{UNIT}} + {{LEFT}}{{UNIT}}));',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-scrollbar.swiper-scrollbar-vertical' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; height: calc(100% - (({{TOP}}{{UNIT}} + {{BOTTOM}}{{UNIT}}) * 3));',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'scroll_bar_border',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-scrollbar',
            ]
        );

        $this->add_responsive_control(
            'scroll_bar_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-scrollbar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tcgelements-showcase .swiper-scrollbar .swiper-scrollbar-drag' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'arrows_pagination_container_style',
            [
                'label' => __('Arrows Pagination Container Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'arrows_pagination_container' => 'true',
                ]
            ]
        );

        $this->add_control(
            'arrows_pagination_container_bg',
            [
                'label' => esc_html__('Container Background', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'arrows_pagination_container_background',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'selector' => '{{WRAPPER}} .tcgelements-showcase .arrows-pagination-container',
                'exclude' => ['image']
            ]
        );

        $this->add_control(
            'arrows_pagination_container_position',
            [
                'label' => esc_html__('Container Position', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'arrows_pagination_container_positioning',
            [
                'label' => esc_html__('Position', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'unset' => esc_html__('unset', 'element-camp'),
                    'absolute' => esc_html__('absolute', 'element-camp'),
                    'relative' => esc_html__('relative', 'element-camp'),
                ],
                'label_block' => true,
                'default' => 'absolute',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .arrows-pagination-container' => 'position: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrows_pagination_container_offset_orientation_h',
            [
                'label' => esc_html__('Horizontal Orientation', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => $start,
                        'icon' => 'eicon-h-align-left',
                    ],
                    'end' => [
                        'title' => $end,
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'classes' => 'elementor-control-start-end',
                'render_type' => 'ui',
            ]
        );

        $this->add_responsive_control(
            'arrows_pagination_container_offset_x',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh', 'custom'],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-showcase .arrows-pagination-container' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-showcase .arrows-pagination-container' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                ],
                'condition' => [
                    'arrows_pagination_container_offset_orientation_h!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrows_pagination_container_offset_x_end',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 0.1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh', 'custom'],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}} .tcgelements-showcase .arrows-pagination-container' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
                    'body.rtl {{WRAPPER}} .tcgelements-showcase .arrows-pagination-container' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
                ],
                'condition' => [
                    'arrows_pagination_container_offset_orientation_h' => 'end',
                ],
            ]
        );

        $this->add_control(
            'arrows_pagination_container_offset_orientation_v',
            [
                'label' => esc_html__('Vertical Orientation', 'element-camp'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => esc_html__('Top', 'element-camp'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'end' => [
                        'title' => esc_html__('Bottom', 'element-camp'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'render_type' => 'ui',
            ]
        );

        $this->add_responsive_control(
            'arrows_pagination_container_offset_y',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vh', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .arrows-pagination-container' => 'top: {{SIZE}}{{UNIT}}; bottom: unset;',
                ],
                'condition' => [
                    'arrows_pagination_container_offset_orientation_v!' => 'end',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrows_pagination_container_offset_y_end',
            [
                'label' => esc_html__('Offset', 'element-camp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vh', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .arrows-pagination-container' => 'bottom: {{SIZE}}{{UNIT}}; top: unset;',
                ],
                'condition' => [
                    'arrows_pagination_container_offset_orientation_v' => 'end',
                ],
            ]
        );

        $this->add_control(
            'arrows_pagination_container_styling',
            [
                'label' => esc_html__('Container Styling', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'arrows_pagination_container_width',
            [
                'label' => esc_html__('Width', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .arrows-pagination-container' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrows_pagination_container_height',
            [
                'label' => esc_html__('Height', 'element-camp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .arrows-pagination-container' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'arrows_pagination_container_border',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .arrows-pagination-container',
            ]
        );

        $this->add_responsive_control(
            'arrows_pagination_container_border_radius',
            [
                'label' => esc_html__('Border Radius', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .arrows-pagination-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'arrows_pagination_container_box_shadow',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .arrows-pagination-container',
            ]
        );

        $this->add_responsive_control(
            'arrows_pagination_container_padding',
            [
                'label' => esc_html__('Padding', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .arrows-pagination-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrows_pagination_container_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .arrows-pagination-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'section_custom_pagination_fraction_style',
            [
                'label' => esc_html__('Custom Pagination Fraction Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'customFraction' => 'true',
                ]
            ]
        );
        $this->add_responsive_control(
            'custom_pagination_fraction_padding',
            [
                'label' => esc_html__('Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-custom-fraction' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'custom_pagination_fraction_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-custom-fraction' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'custom_pagination_fraction_typography',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .tcgelements-custom-fraction',
            ]
        );
        $this->add_control(
            'custom_pagination_fraction_color',
            [
                'label' => esc_html__( 'Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-custom-fraction' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'custom_pagination_fraction_color_dark_mode',
            [
                'label' => esc_html__( 'Color (Dark Mode)', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{WRAPPER}} .tcgelements-showcase .tcgelements-custom-fraction' => 'color: {{VALUE}};',
                    '} body.tcg-dark-mode {{WRAPPER}} .tcgelements-showcase .tcgelements-custom-fraction' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->start_controls_tabs(
            'custom_fraction_tabs',
        );

        $this->start_controls_tab(
            'custom_fraction_current_tab',
            [
                'label'   => esc_html__( 'Current', 'element-camp' ),
            ]
        );

        $this->add_control(
            'current_opacity',
            [
                'label' => esc_html__( 'Opacity', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-custom-fraction .slide-cont' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'custom_fraction_sep_tab',
            [
                'label'   => esc_html__( 'Mark', 'element-camp' ),
            ]
        );

        $this->add_control(
            'mark_opacity',
            [
                'label' => esc_html__( 'Opacity', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-custom-fraction .slide-mark' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'custom_fraction_total_tab',
            [
                'label'   => esc_html__( 'Total', 'element-camp' ),
            ]
        );

        $this->add_control(
            'total_opacity',
            [
                'label' => esc_html__( 'Opacity', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-custom-fraction .slide-all' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
        $this->start_controls_section(
            'section_custom_progress_style',
            [
                'label' => esc_html__('Custom Progress Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'customProgress' => 'true',
                ]
            ]
        );
        $this->add_responsive_control(
            'custom_progress_width',
            [
                'label' => esc_html__( 'Width', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 100,
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
                'size_units' => [ 'px', 'vw', 'vh', '%', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 500,
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-custom-progress' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'custom_progress_height',
            [
                'label' => esc_html__( 'Height', 'element-camp' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1,
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
                'size_units' => [ 'px', 'vw', 'vh', '%', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 500,
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-custom-progress' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'custom_progress_padding',
            [
                'label' => esc_html__('Padding', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-custom-progress' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'custom_progress_margin',
            [
                'label' => esc_html__('Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-custom-progress' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'custom_progress_color',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-showcase .tcgelements-custom-progress',
                'fields_options' => [
                    'background' => [
                        'label' => esc_html_x('Bar Color', 'Background Control', 'themescamp-plugin'),
                    ]
                ]
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'custom_progress_active_color',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
                'selector' => '{{WRAPPER}} .tcgelements-showcase .tcgelements-custom-progress .swiper-progress-bar',
                'fields_options' => [
                    'background' => [
                        'label' => esc_html_x('Progress Color', 'Background Control', 'themescamp-plugin'),
                    ]
                ]
            ]
        );
        $this->add_control(
            'custom_progress_color_dark_mode_heading',
            [
                'label' => esc_html__('Custom Progress Color (Dark Mode)', 'element-camp'),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'custom_progress_color_dark_mode',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .tcgelements-custom-progress',
                'types' => ['classic', 'gradient', 'tcg_gradient'],
                'fields_options' => [
                    'background' => [
                        'label' => esc_html_x('Bar Color', 'Background Control', 'themescamp-plugin'),
                    ],
                    'color' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-color: {{VALUE}};',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-color: {{VALUE}};',
                        ],
                    ],
                    'gradient_angle' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
                        ],
                    ],
                    'gradient_position' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
                        ],
                    ],
                    'image' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-image: url("{{URL}}");',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-image: url("{{URL}}");',
                        ],
                    ],
                    'position' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-position: {{VALUE}};',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-position: {{VALUE}};',
                        ],
                    ],
                    'xpos' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-position: {{SIZE}}{{UNIT}} {{ypos.SIZE}}{{ypos.UNIT}}',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-position: {{SIZE}}{{UNIT}} {{ypos.SIZE}}{{ypos.UNIT}}',
                        ],
                    ],
                    'ypos' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-position: {{SIZE}}{{UNIT}} {{ypos.SIZE}}{{ypos.UNIT}}',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-position: {{SIZE}}{{UNIT}} {{ypos.SIZE}}{{ypos.UNIT}}',
                        ],
                    ],
                    'attachment' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode (desktop+){{SELECTOR}}' => 'background-attachment: {{VALUE}};',
                            '} body.tcg-dark-mode (desktop+){{SELECTOR}}' => 'background-attachment: {{VALUE}};',
                        ],
                    ],
                    'repeat' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-repeat: {{VALUE}};',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-repeat: {{VALUE}};',
                        ],
                    ],
                    'size' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-size: {{VALUE}};',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-size: {{VALUE}};',
                        ],
                    ],
                    'bg_width' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background-size: {{SIZE}}{{UNIT}} auto',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background-size: {{SIZE}}{{UNIT}} auto',
                        ],
                    ],
                    'video_fallback' => [
                        'selectors' => [
                            '@media (prefers-color-scheme: dark){ body.tcg-auto-mode {{SELECTOR}}' => 'background: url("{{URL}}") 50% 50%; background-size: cover;',
                            '} body.tcg-dark-mode {{SELECTOR}}' => 'background: url("{{URL}}") 50% 50%; background-size: cover;',
                        ],
                    ],
                ]
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'section_float_cursor_style',
            [
                'label' => esc_html__('Float Cursor Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'float_cursor_animation' => 'yes',
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'float_cursor_text_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-showcase .tcgelements-float-cursor',
            ]
        );
        $this->add_control(
            'float_cursor_text_color',
            [
                'label' => esc_html__( 'Text Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .tcgelements-float-cursor' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'float_cursor_background',
                'selector' => '{{WRAPPER}} .tcgelements-showcase .tcgelements-float-cursor',
                'types' => [ 'classic', 'gradient', 'tcg_gradient' ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'count_style',
            [
                'label' => esc_html__('Count Style', 'element-camp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_count' => 'yes',
                ]
            ]
        );
        $this->add_responsive_control(
            'count_text_align',
            [
                'label' => esc_html__( 'Text Alignment', 'element-camp' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'element-camp' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'element-camp' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'element-camp' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__( 'Justified', 'element-camp' ),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .num' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'count_display',
            [
                'label' => esc_html__('Count Display Type', 'element-camp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'block',
                'options' => [
                    'block' => esc_html__('Block', 'element-camp'),
                    'inline-block' => esc_html__('Inline Block', 'element-camp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .num' => 'display: {{VALUE}};'
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'count_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .num',
            ]
        );
        $this->add_control(
            'count_color',
            [
                'label' => esc_html__( 'Color', 'element-camp' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .num' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->start_controls_tabs('count_tabs');
        $this->start_controls_tab(
            'count_normal_tab',
            [
                'label'   => esc_html__( 'Normal', 'element-camp' ),
            ]
        );
        $this->add_responsive_control(
            'count_margin',
            [
                'label' => esc_html__('Item Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .num' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'count_opacity',
            [
                'label' => esc_html__( 'Opacity', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide .item .num' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'count_active_tab',
            [
                'label'   => esc_html__( 'Active', 'element-camp' ),
            ]
        );
        $this->add_responsive_control(
            'count_margin_active',
            [
                'label' => esc_html__('Item Margin', 'element-camp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide.swiper-slide-active .item .num' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'count_opacity_active',
            [
                'label' => esc_html__( 'Opacity', 'element-camp' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tcgelements-showcase .swiper-container .swiper-slide.swiper-slide-active .item .num' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

    }

    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
//        slider settings
        //resposive setttings
        $resposive_settings = [];
        $slider_settings = [];

        foreach ($settings['responsive_settings'] as $index => $item) {
            $resposive_settings[$item['breakpoint']] = [
                'slidesPerView' => $item['slidesPerView_auto'] === 'true' ? 'auto' : $item['slidesPerView'],
                'spaceBetween' => $item['spaceBetween'],
            ];
        };

        $slider_settings = [
            'breakpoints' => $resposive_settings,
            'speed' => $settings['speed'],
            'direction' => $settings['direction'],
            'autoHeight' => $settings['autoHeight'],
            'effect' => $settings['effect'],
            'loop' => $settings['loop'],
            'centeredSlides' => $settings['centeredSlides'],
            'oneWayMovement' => $settings['oneWayMovement'],
            'keyboard' => $settings['keyboard'],
            'mousewheel' => $settings['mousewheel'],
            'paginationType' => $settings['paginationType'],
            'cardsOffset' => $settings['cardsOffset'],
            'cardsRotate' => $settings['cardsRotate'],
            'parallaxChoose' => $settings['parallax_choose'],
            'customFraction' => $settings['customFraction'],
            'customProgress' => $settings['customProgress'],
        ];
        if ($settings['customFraction']=='true'){
            $slider_settings['slideMark'] = $settings['slide_separator'];
        }

        if ($settings['autoplay'] === 'true') {
            $slider_settings['autoplay'] = [
                'delay' => $settings['autoplay_delay'],
                'reverseDirection' => $settings['reverseDirection'],
                'disableOnInteraction' => $settings['disableOnInteraction']
            ];
        }

        $max_slider_id = uniqid('tcgelements-showcase-');
//        end slider settings

//        portfolio settings
        $query_settings = $this->get_settings();
        $query_settings = ControlsHelper::fix_old_query($query_settings);
        $args = ControlsHelper::get_query_args($query_settings);
        $args = ControlsHelper::get_dynamic_args($query_settings, $args);
        $query = new \WP_Query($args);
        $selected_term_type = $settings['display_terms_type'];
        //        date format
        if ($settings['date_format']!='custom'){
            $format = $settings['date_format'];
        }else{
            $format = $settings['custom_date_format'];
        }
//        end portfolio settings

        ?>

        <div id="<?php echo esc_attr($max_slider_id) ?>" class="tcgelements-showcase" data-tcgelements-showcase='<?php echo esc_attr(json_encode($slider_settings)); ?>'>
            <?php if ($settings['float_cursor_animation']=='yes') : ?>
            <div class="tcgelements-float-cursor-container">
                <?php endif;?>
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <?php $itemCount=1; if ($query->have_posts()) : while  ($query->have_posts()) : $query->the_post(); global $post ;
                            if($settings['display_terms']=='yes' && $selected_term_type == 'categories'){
                                $taxonomy_names = get_post_taxonomies();
                                if($taxonomy_names){
                                    $category = get_the_terms($post->ID, $taxonomy_names[0]);
                                }
                            }elseif($settings['display_terms']=='yes' && $selected_term_type == 'tags'){
                                $taxonomy_names = get_post_taxonomies();
                                if($taxonomy_names){
                                    $tags = get_the_terms($post->ID, $taxonomy_names[1]);
                                };
                            }
                            ?>
                            <div class="swiper-slide">
                                <div class="item">
                                    <a class="overlay-link" href="<?= esc_url(get_the_permalink()); ?>"></a>
                                    <?php if ($settings['image_container']=='yes') : ?>
                                    <div class="img">
                                        <?php endif;?>
                                        <img src="<?=esc_url(get_the_post_thumbnail_url())?>" alt="">
                                        <?php if ($settings['image_container']=='yes') : ?>
                                    </div>
                                <?php endif;?>
                                    <?php if ($settings['show_count'] === 'yes') : ?>
                                        <?php
                                        $count = $settings['number_format'] === 'leading_zero' ? sprintf('%02d', $itemCount) : $itemCount;
                                        ?>
                                        <span class="num"><?= esc_html($count) ?></span>
                                    <?php endif; ?>
                                    <div class="info <?php if ($settings['info_animations']=='duplicate') echo esc_attr('info-duplicate')?>">
                                        <?php if ($settings['info_icon']=='yes') : ?>
                                        <span class="icon">
                                            <?php \Elementor\Icons_Manager::render_icon( $settings['info_selected_icon'], [ 'aria-hidden' => 'true' ] );?>
                                        </span>
                                        <?php endif;?>
                                        <?php if ($settings['display_terms'] == 'yes' || $settings['show_date']=='yes') : ?>
                                            <span class="taxonomy" <?php if ($settings['effect']=='parallax') : ?> data-swiper-parallax="<?=esc_attr($settings['parallax_taxonomy_date_speed'])?>" <?php endif;?>>
                                        <?php if ($settings['show_date']=='yes' && $settings['date_position']=='before') : ?>
                                            <span class="date"><?=get_the_date($format);?></span>
                                        <?php endif;?>
                                                <?php if ($settings['display_terms']=='yes') : ?>
                                                    <span class="taxonomy-text">
                                                <?php
                                                $cat_counter = 0;
                                                $tag_counter = 0;
                                                if($selected_term_type == 'categories' && $category){
                                                    foreach ($category as $cat) {
                                                        if ($cat_counter >= 1) echo $settings['meta_separator'];
                                                        echo '<span>' . $cat->name . '</span>';
                                                        $cat_counter++;
                                                    };
                                                }
                                                if($selected_term_type == 'tags' && $tags){
                                                    foreach ($tags as $tag) {
                                                        if ($tag_counter >= 1) echo $settings['meta_separator'];
                                                        echo '<span>' . $tag->name . '</span>';
                                                        $tag_counter++;
                                                    }
                                                }
                                                ?>
                                            </span>
                                                <?php endif;?>
                                                <?php if ($settings['show_date']=='yes' && $settings['date_position']=='after') : ?>
                                                    <span class="date"><?=get_the_date($format);?></span>
                                                <?php endif;?>
                                    </span>
                                        <?php endif;?>
                                        <h3 class="title" <?php if ($settings['effect']=='parallax') : ?> data-swiper-parallax="<?=esc_attr($settings['parallax_title_speed'])?>" <?php endif;?>>
                                            <?php if ($settings['info_animations']=='duplicate') : ?>
                                                <?php
                                                $title = get_the_title();
                                                $title_words = explode(' ', $title, 2);
                                                ?>
                                                <div class="stroke"><?= esc_html($title_words[0]) ?></div>
                                                <span><?= esc_html($title_words[1] ?? '') ?></span>
                                            <?php else : ?>
                                                <?= esc_html(get_the_title())?>
                                            <?php endif;?>
                                        </h3>
                                        <?php if ($settings['button_switcher']=='yes') : ?>
                                            <span class="butn">
                                            <?php if (!empty($settings['selected_icon']['value']) and ($settings['icon_align'] == 'left')) : ?>
                                                <span class="butn-icon">
                                                    <?php Icons_Manager::render_icon($settings['selected_icon'], ['aria-hidden' => 'true']);?>
                                                </span>
                                            <?php endif; ?>
                                            <span class="text">
                                                <?php $this->print_unescaped_setting('btn_text'); ?>
                                            </span>
                                            <?php if (!empty($settings['selected_icon']['value'])  and ($settings['icon_align'] == 'right')) : ?>
                                                <span class="butn-icon">
                                                    <?php Icons_Manager::render_icon($settings['selected_icon'], ['aria-hidden' => 'true']);?>
                                                </span>
                                            <?php endif; ?>
                                        </span>
                                        <?php endif;?>
                                    </div>
                                    <?php if ($settings['info_animations']=='duplicate') : ?>
                                        <div class="copy-info">
                                            <?php if ($settings['info_icon']=='yes') : ?>
                                                <span class="icon">
                                            <?php \Elementor\Icons_Manager::render_icon( $settings['info_selected_icon'], [ 'aria-hidden' => 'true' ] );?>
                                        </span>
                                            <?php endif;?>
                                            <?php if ($settings['display_terms'] == 'yes' || $settings['show_date']=='yes') : ?>
                                                <span class="taxonomy" <?php if ($settings['effect']=='parallax') : ?> data-swiper-parallax="<?=esc_attr($settings['parallax_taxonomy_date_speed'])?>" <?php endif;?>>
                                        <?php if ($settings['show_date']=='yes' && $settings['date_position']=='before') : ?>
                                            <span class="date"><?=get_the_date($format);?></span>
                                        <?php endif;?>
                                                    <?php if ($settings['display_terms']=='yes') : ?>
                                                        <span class="taxonomy-text">
                                                <?php
                                                $cat_counter = 0;
                                                $tag_counter = 0;
                                                if($selected_term_type == 'categories' && $category){
                                                    foreach ($category as $cat) {
                                                        if ($cat_counter >= 1) echo $settings['meta_separator'];
                                                        echo '<span>' . $cat->name . '</span>';
                                                        $cat_counter++;
                                                    };
                                                }
                                                if($selected_term_type == 'tags' && $tags){
                                                    foreach ($tags as $tag) {
                                                        if ($tag_counter >= 1) echo $settings['meta_separator'];
                                                        echo '<span>' . $tag->name . '</span>';
                                                        $tag_counter++;
                                                    }
                                                }
                                                ?>
                                            </span>
                                                    <?php endif;?>
                                                    <?php if ($settings['show_date']=='yes' && $settings['date_position']=='after') : ?>
                                                        <span class="date"><?=get_the_date($format);?></span>
                                                    <?php endif;?>
                                    </span>
                                            <?php endif;?>
                                            <h3 class="title" <?php if ($settings['effect']=='parallax') : ?> data-swiper-parallax="<?=esc_attr($settings['parallax_title_speed'])?>" <?php endif;?>>
                                                <?php if ($settings['info_animations']=='duplicate') : ?>
                                                    <?php
                                                    $title = get_the_title();
                                                    $title_words = explode(' ', $title, 2);
                                                    ?>
                                                    <div class="stroke"><?= esc_html($title_words[0]) ?></div>
                                                    <span><?= esc_html($title_words[1] ?? '') ?></span>
                                                <?php else : ?>
                                                    <?= esc_html(get_the_title())?>
                                                <?php endif;?>
                                            </h3>
                                            <?php if ($settings['button_switcher']=='yes') : ?>
                                                <span class="butn">
                                                <?php if (!empty($settings['selected_icon']['value']) and ($settings['icon_align'] == 'left')) : ?>
                                                    <span class="butn-icon">
                                                        <?php Icons_Manager::render_icon($settings['selected_icon'], ['aria-hidden' => 'true']);?>
                                                    </span>
                                                <?php endif; ?>
                                                <span class="text">
                                                    <?php $this->print_unescaped_setting('btn_text'); ?>
                                                </span>
                                                <?php if (!empty($settings['selected_icon']['value'])  and ($settings['icon_align'] == 'right')) : ?>
                                                    <span class="butn-icon">
                                                        <?php Icons_Manager::render_icon($settings['selected_icon'], ['aria-hidden' => 'true']);?>
                                                    </span>
                                                <?php endif; ?>
                                            </span>
                                            <?php endif;?>
                                        </div>
                                    <?php endif;?>
                                </div>
                            </div>
                        <?php $itemCount++; endwhile;  wp_reset_postdata(); endif; ?>
                    </div>
                    <?php if ($settings['float_cursor_animation']=='yes') : ?>
                        <div class="tcgelements-float-cursor"><?=esc_html($settings['float_cursor_animation_text'])?></div>
                    <?php endif;?>
                </div>

                <?php if ($settings['arrows_pagination_container'] === 'true') : echo '<div class="arrows-pagination-container">';
                endif; ?>

                <?php if ($settings['pagination'] === 'true') : ?>
                    <!-- If we need pagination -->
                    <div class="swiper-pagination swiper-pagination-<?= $settings['direction'] ?>"></div>
                <?php endif; ?>

                <?php if ($settings['arrows'] === 'true') : ?>
                    <!-- If we need navigation buttons -->
                    <div class="swiper-button-prev tcgelements-showcase-arrows">
                        <?php if ($settings['arrows_style'] === 'icon') :
                            Icons_Manager::render_icon($settings['prev_arrow_icon'], ['aria-hidden' => 'true']);
                        elseif ($settings['arrows_style'] === 'text') :
                            echo wp_kses_post($settings['prev_arrow_text']);
                        elseif ($settings['arrows_style'] === 'text-icon') :
                            Icons_Manager::render_icon($settings['prev_arrow_icon'], ['aria-hidden' => 'true']);
                            echo '<span class="tcgelements-showcase-arrow-text">' . wp_kses_post($settings['prev_arrow_text']) . '</span>';
                        endif; ?>
                    </div>
                    <div class="swiper-button-next tcgelements-showcase-arrows">
                        <?php if ($settings['arrows_style'] === 'icon') :
                            Icons_Manager::render_icon($settings['next_arrow_icon'], ['aria-hidden' => 'true']);
                        elseif ($settings['arrows_style'] === 'text') :
                            echo wp_kses_post($settings['next_arrow_text']);
                        elseif ($settings['arrows_style'] === 'text-icon') :
                            echo '<span class="tcgelements-showcase-arrow-text">' . wp_kses_post($settings['next_arrow_text']) . '</span>';
                            Icons_Manager::render_icon($settings['next_arrow_icon'], ['aria-hidden' => 'true']);
                        endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ($settings['arrows_pagination_container'] === 'true') : echo '</div>';
                endif; ?>

                <?php if ($settings['scrollbar'] === 'true') : ?>
                    <!-- If we need scrollbar -->
                    <div class="swiper-scrollbar scrollbar-<?= $settings['direction'] ?>"></div>
                <?php endif; ?>
                <?php if ($settings['float_cursor_animation']=='yes') : ?>
            </div>
        <?php endif;?>
            <?php if ($settings['customProgress'] === 'true') : ?>
                <div class="tcgelements-custom-progress"> <span class="swiper-progress-bar"></span> </div>
            <?php endif; ?>
            <?php if ($settings['customFraction'] === 'true') : ?>
                <div class="tcgelements-custom-fraction"></div>
            <?php endif; ?>


        </div>

        <?php
    }
}
