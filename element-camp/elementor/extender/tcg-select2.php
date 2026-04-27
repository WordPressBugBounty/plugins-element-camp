<?php

namespace ElementCampPlugin\Elementor;

defined('ABSPATH') || exit(); // Exit if accessed directly

/**
 *  Elementor extra features
 */

class TCG_Pro_Select2
{

    public function __construct()
    {
        // TCG select2
        add_action('elementor/controls/register', array($this, 'register_controls'));
        add_action('wp_ajax_tcg_select2_search_post', [$this, 'select2_ajax_posts_filter_autocomplete']);
        add_action('wp_ajax_tcg_select2_get_title', [$this, 'select2_ajax_get_posts_value_titles']);
    }

    public function register_controls($controls_manager)
    {
        if ( class_exists( 'ElementCampPlugin\Elementor\Controls\Themescamp_Select2' ) ) {
            $controls_manager->register_control('tcg-select2', new \ElementCampPlugin\Elementor\Controls\Themescamp_Select2());
        }
    }

    public static function get_query_post_list($post_type = 'any', $limit = -1, $search = '', $meta_query = [])
    {
        $args = [
            'post_type'      => $post_type,
            'posts_per_page' => $limit,
            's'              => $search,
            'post_status'    => 'publish',
            'orderby'        => 'title',
            'order'          => 'ASC',
        ];

        if (!empty($meta_query) && is_array($meta_query)) {
            $args['meta_query'] = $meta_query;
            if (count($meta_query) > 1) {
                $args['meta_query']['relation'] = 'OR';
            }
        }

        $posts = get_posts($args);

        return wp_list_pluck($posts, 'post_title', 'ID');
    }


    public function select2_ajax_posts_filter_autocomplete()
    {
        // Allow authorized users even if nonce is missing (handles external widgets)
        if ( ! current_user_can( 'edit_posts' ) ) {
            check_ajax_referer('tcg_select2_nonce', 'nonce');
        }

        $post_type   = 'post';
        $source_name = 'post_type';

        if (!empty($_POST['post_type'])) {
            $post_type = sanitize_text_field(wp_unslash($_POST['post_type']));
        }

        if (!empty($_POST['source_name'])) {
            $source_name = sanitize_text_field(wp_unslash($_POST['source_name']));
        }

        $search  = !empty($_POST['term']) ? sanitize_text_field(wp_unslash($_POST['term'])) : '';
        $results = $post_list = [];
        $meta_query = !empty($_POST['meta_query']) ? map_deep(wp_unslash($_POST['meta_query']), 'sanitize_text_field') : '';
        switch ($source_name) {
            case 'taxonomy':
                $args = [
                    'hide_empty' => false,
                    'orderby'    => 'name',
                    'order'      => 'ASC',
                    'search'     => $search,
                    'number'     => '5',
                ];

                if ($post_type !== 'all') {
                    $args['taxonomy'] = $post_type;
                }

                if(isset($_POST['use_taxonomy_slug']) && $_POST['use_taxonomy_slug'] == 'true'){
                    $post_list = wp_list_pluck(get_terms($args), 'name', 'slug');
                } else {
                    $post_list = wp_list_pluck(get_terms($args), 'name', 'term_id');
                }
                break;
            case 'user':
                if (!current_user_can('list_users')) {
                    $post_list = [];
                    break;
                }

                $users = [];

                foreach (get_users(['search' => "*{$search}*"]) as $user) {
                    $user_id           = $user->ID;
                    $user_name         = $user->display_name;
                    $users[$user_id] = $user_name;
                }

                $post_list = $users;
                break;
            default:
                $post_list = $this->get_query_post_list($post_type, 10, $search, $meta_query);
        }

        if (!empty($post_list)) {
            foreach ($post_list as $key => $item) {
                $results[] = ['text' => $item, 'id' => $key];
            }
        }

        wp_send_json(['results' => $results]);
    }

    /**
     * Select2 Ajax Get Posts Value Titles
     * get selected value to show elementor editor panel in select2 ajax search box
     *
     * @access public
     * @return void
     * @since 4.0.0
     */
    public function select2_ajax_get_posts_value_titles()
    {
        // Allow authorized users even if nonce is missing (handles external widgets)
        if ( ! current_user_can( 'edit_posts' ) ) {
            check_ajax_referer('tcg_select2_nonce', 'nonce');
        }

        $raw_id = isset( $_POST['id'] ) ? map_deep( wp_unslash( $_POST['id'] ), 'sanitize_text_field' ) : [];

        if (empty($raw_id) || !is_array($raw_id)) {
            wp_send_json_error([]);
        }

        if (empty(array_filter($raw_id))) {
            wp_send_json_error([]);
        }
        $ids         = array_map('sanitize_text_field', $raw_id);
        $source_name = !empty($_POST['source_name']) ? sanitize_text_field(wp_unslash($_POST['source_name'])) : '';

        switch ($source_name) {
            case 'taxonomy':
                $args = [
                    'hide_empty' => false,
                    'orderby'    => 'name',
                    'order'      => 'ASC',
                    'include'    => implode(',', $ids),
                ];

                if (isset($_POST['post_type']) && $_POST['post_type'] !== 'all') {
                    $args['taxonomy'] = sanitize_text_field(wp_unslash($_POST['post_type']));
                }

                if(isset($_POST['use_taxonomy_slug']) && $_POST['use_taxonomy_slug'] == 'true'){
                    $response = wp_list_pluck(get_terms($args), 'name', 'slug');
                } else {
                    $response = wp_list_pluck(get_terms($args), 'name', 'term_id');
                }

                break;
            case 'user':
                $users = [];

                foreach (get_users(['include' => $ids]) as $user) {
                    $user_id           = $user->ID;
                    $user_name         = $user->display_name;
                    $users[$user_id] = $user_name;
                }

                $response = $users;
                break;
            default:
                $post_info = get_posts([
                    'post_type' => isset($_POST['post_type']) ? sanitize_text_field(wp_unslash($_POST['post_type'])) : 'post',
                    'include'   => implode(',', $ids)
                ]);
                $response  = wp_list_pluck($post_info, 'post_title', 'ID');
        }

        if (!empty($response)) {
            wp_send_json_success(['results' => $response]);
        } else {
            wp_send_json_error([]);
        }
    }

}

