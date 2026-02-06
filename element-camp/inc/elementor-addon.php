<?php
//Elementor Editor view

//display menu-list list
if (! function_exists('elementcamp_navmenu_navbar_menu_choices')) {
    function elementcamp_navmenu_navbar_menu_choices()
    {
        $menus = wp_get_nav_menus();
        $items = array();
        $i     = 0;
        foreach ($menus as $menu) {
            if ($i == 0) {
                $default = $menu->slug;
                $i++;
            }
            $items[$menu->slug] = $menu->name;
        }

        return $items;
    }
}

//display Side panel list
if (! function_exists('elementcamp_side_panel_choices')) {
    function elementcamp_side_panel_choices()
    {
        $elementcamp_custom_sidepanels = new WP_Query(array('post_type' => 'sidepanel'));
        $posts = $elementcamp_custom_sidepanels->posts;
        $items = array();
        $i     = 0;
        foreach ($posts as $sidepanel) {
            if ($i == 0) {
                $default = $sidepanel->slug;
                $i++;
            }
            $items[$sidepanel->slug] = $sidepanel->post_name;
        }

        return $items;
    }
}

//display category blog list
if (! function_exists('elementcamp_category_choice')) {
    function elementcamp_category_choice()
    {
        $categories = get_categories();
        $blogs = array();
        $i     = 0;
        foreach ($categories as $category) {
            if ($i == 0) {
                $default = $category->name;
                $i++;
            }
            $blogs[$category->term_id] = $category->name;
        }
        return $blogs;
    }
}

//display portfolio categories
if (! function_exists('elementcamp_tax_choice')) {
    function elementcamp_tax_choice()
    {
        $categories = get_terms('portfolio_category');
        $blogs = array();
        $i     = 0;
        foreach ($categories as $category) {
            if ($i == 0) {
                $default = $category->name;
                $i++;
            }
            $blogs[$category->term_id] = $category->name;
        }
        return $blogs;
    }
}

//display products categories
if (! function_exists('elementcamp_products_choice')) {
    function elementcamp_products_choice()
    {
        $categories = get_terms('product_cat');
        $blogs = array();
        $i     = 0;
        foreach ($categories as $category) {
            if ($i == 0) {
                $default = $category->name;
                $i++;
            }
            $blogs[$category->term_id] = $category->name;
        }
        return $blogs;
    }
}

if (! function_exists('themescamp_post_category_choice')) {
    function elementcamp_cart_count_fragment($fragments)
    {

        ob_start();
        $cart_count = WC()->cart->cart_contents_count;
?>
        <span class="cart-count-number"><?= esc_html($cart_count); ?></span>
<?php

        $fragments['span.cart-count-number'] = ob_get_clean();

        return $fragments;
    }
    add_filter('woocommerce_add_to_cart_fragments', 'elementcamp_cart_count_fragment');
}

//display menu list
if (! function_exists('themescamp_navmenu_navbar_menu_choices')) {
    function themescamp_navmenu_navbar_menu_choices()
    {
        $menus = wp_get_nav_menus();
        $items = array();
        $i     = 0;
        foreach ($menus as $menu) {
            if ($i == 0) {
                $default = $menu->slug;
                $i++;
            }
            $items[$menu->slug] = $menu->name;
        }

        return $items;
    }
}

// display notification list
if (! function_exists('themescamp_notification_choices')) {
    function themescamp_notification_choices()
    {
        // Query for all posts of type 'tcg_teb' regardless of status (optional)
        $notification_query = new WP_Query(array(
            'post_type' => 'tcg_teb',
            'post_status' => 'any',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => 'template_type',
                    'value' => 'notification',
                    'compare' => '='
                )
            )
        ));

        $posts = $notification_query->posts;
        $items = array();

        // Check if we have posts to loop through
        if ($posts) {
            foreach ($posts as $notification) {
                // Use the ID as the key and post_title as the value
                $items[$notification->ID] = $notification->post_title;
            }
        }

        return $items;
    }
}


//display offcanvas list
if (! function_exists('themescamp_offcanvas_choices')) {
    function themescamp_offcanvas_choices()
    {
        // Query for all posts of type 'tcg_teb' regardless of status (optional)
        $offcanvas_query = new WP_Query(array(
            'post_type' => 'tcg_teb',
            'post_status' => 'any',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => 'template_type',
                    'value' => 'offcanvas',
                    'compare' => '='
                )
            )


        ));

        $posts = $offcanvas_query->posts;
        $items = array();

        // Check if we have posts to loop through
        if ($posts) {
            foreach ($posts as $offcanvas) {
                // Use the ID as the key and post_title as the value
                $items[$offcanvas->ID] = $offcanvas->post_title;
            }
        }

        return $items;
    }
}

//display category blog list
if (! function_exists('themescamp_category_choice')) {
    function themescamp_category_choice()
    {
        $categories = get_categories();
        $blogs = array();
        $i     = 0;
        foreach ($categories as $category) {
            if ($i == 0) {
                $default = $category->name;
                $i++;
            }
            $blogs[$category->term_id] = $category->name;
        }
        return $blogs;
    }
}


if (! function_exists('themescamp_portfolio_tag_choice')) {
    function themescamp_portfolio_tag_choice()
    {
        $tags = get_terms('porto_tag', array(
            'hide_empty' => true,
        ));
        $blogs = array();
        $i     = 0;
        foreach ($tags as $tag) {
            if ($i == 0) {
                $default = $tag->name;
                $i++;
            }
            $blogs[$tag->term_id] = $tag->name;
        }
        return $blogs;
    }
}


if (! function_exists('themescamp_post_tag_choice')) {
    function themescamp_post_tag_choice()
    {
        $tags = get_terms('post_tag', array(
            'hide_empty' => false,
        ));
        $blogs = array();
        $i     = 0;
        foreach ($tags as $tag) {
            if ($i == 0) {
                $default = $tag->name;
                $i++;
            }
            $blogs[$tag->slug] = $tag->name;
        }
        return $blogs;
    }
}


//display portfolio categories
if (! function_exists('themescamp_tax_choice')) {
    function themescamp_tax_choice()
    {
        $categories = get_terms('portfolio_category');
        $blogs = array();
        $i     = 0;
        foreach ($categories as $category) {
            if ($i == 0) {
                $default = $category->name;
                $i++;
            }
            $blogs[$category->term_id] = $category->name;
        }
        return $blogs;
    }
}



//display products categories
if (! function_exists('themescamp_products_choice')) {
    function themescamp_products_choice()
    {
        $categories = get_terms('product_cat');
        $blogs = array();
        $i     = 0;
        foreach ($categories as $category) {
            if ($i == 0) {
                $default = $category->name;
                $i++;
            }
            $blogs[$category->term_id] = $category->name;
        }
        return $blogs;
    }
}

//for imagesloaded 
add_action('elementor/editor/after_enqueue_scripts', function () {
    wp_enqueue_script('imagesloaded');
});

//add new category elementor
add_action('elementor/init', function () {
    $elementsManager = Elementor\Plugin::instance()->elements_manager;
    $elementsManager->add_category(
        'elementcamp-elements',
        array(
            'title' => 'ElementCamp',
            'icon'  => 'font',
        ),
        1
    );
});

//add new category elementor
add_action('elementor/init', function () {
    $elementsManager = Elementor\Plugin::instance()->elements_manager;
    $elementsManager->add_category(
        'tcgelements-menu-list-elements',
        array(
            'title' => 'Tcgelements Custom Menu Elements',
            'icon'  => 'font',
        ),
        2
    );
});

//add new category elementor
add_action('elementor/init', function () {
    $elementsManager = Elementor\Plugin::instance()->elements_manager;
    $elementsManager->add_category(
        'tcgelements-portfolio-elements',
        array(
            'title' => 'Tcgelements Single Portfolio Elements',
            'icon'  => 'font',
        ),
        3
    );
});

//add new category elementor
add_action('elementor/init', function () {
    $elementsManager = Elementor\Plugin::instance()->elements_manager;
    $elementsManager->add_category(
        'tcgelements-blog-elements',
        array(
            'title' => 'Tcgelements Blog Post Elements',
            'icon'  => 'font',
        ),
        4
    );
});

add_action('elementor/element/before_section_end', function ($section, $section_id, $args) {
    if ($section->get_name() == 'google_maps' && $section_id == 'section_map') {
        // we are at the end of the "section_image" area of the "image-box"
        $section->add_control(
            'map_style',
            [
                'label'        => 'Map Style',
                'type'         => Elementor\Controls_Manager::SELECT,
                'default'      => 'default',
                'options'      => array('default' => 'Default', 'gray' => 'Grayscale Map'),
                'prefix_class' => 'map-',
                'label_block'  => true,
            ]
        );
    }
}, 10, 3);
