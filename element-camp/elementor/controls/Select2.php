<?php

namespace ElementCampPlugin\Elementor\Controls;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

use \Elementor\Base_Data_Control;

add_action('elementor/init', function() {

    if (!class_exists('\Elementor\Base_Data_Control')) {
        return;
    }
    
    class Themescamp_Select2 extends Base_Data_Control {
        public function get_type()
        {
            return 'tcg-select2';
        }

    	public function enqueue() {
    		wp_register_script( 'tcg-select2', ELEMENTCAMP_URL . 'elementor/assets/js/tcg-select2.js',
    			[ 'jquery-elementor-select2' ], '2.2.6', true );
    		wp_localize_script(
    			'tcg-select2',
    			'tcg_select2_localize',
    			[
    				'ajaxurl'         => esc_url( admin_url( 'admin-ajax.php' ) ),
    				'security'        => wp_create_nonce( 'tcg_select2_nonce' ),
    				'search_text'     => esc_html__( 'Search', 'themescamp-plugin' ),
    				'remove'          => __( 'Remove', 'themescamp-plugin' ),
    				'thumbnail'       => __( 'Image', 'themescamp-plugin' ),
    				'name'            => __( 'Title', 'themescamp-plugin' ),
    				'price'           => __( 'Price', 'themescamp-plugin' ),
    				'quantity'        => __( 'Quantity', 'themescamp-plugin' ),
    				'subtotal'        => __( 'Subtotal', 'themescamp-plugin' ),
    				'cl_login_status' => __( 'User Status', 'themescamp-plugin' ),
    				'cl_post_type'    => __( 'Post Type', 'themescamp-plugin' ),
    				'cl_browser'      => __( 'Browser', 'themescamp-plugin' ),
    				'cl_date_time'    => __( 'Date & Time', 'themescamp-plugin' ),
    				'cl_recurring_day'=> __( 'Recurring Day', 'themescamp-plugin' ),
    				'cl_dynamic'      => __( 'Dynamic Field', 'themescamp-plugin' ),
    				'cl_query_string' => __( 'Query String', 'themescamp-plugin' ),
    				'cl_visit_count'  => __( 'Visit Count', 'themescamp-plugin' ),
    			]
    		);
    		wp_enqueue_script( 'tcg-select2' );
    	}

        protected function get_default_settings()
        {
            return [
                'multiple' => false,
                'source_name' => 'post_type',
                'source_type' => 'post',
                'use_taxonomy_slug' => false,
            ];
        }

        public function get_value($control, $settings) {
            $value = parent::get_value($control, $settings);
            if (is_array($value)) {
                // If $value is an array, use array_map to modify it
                $value = array_map(function($item) {
                    // Perform your custom modification here
                    // For example, let's uppercase each value
                    return strtoupper($item);
                }, $value);
            } elseif (is_string($value)) {
                // If $value is a string, you can't use array_map on it
                // You could modify it directly, for example:
                $value = strtoupper($value);
            }

            // Check if $control['use_taxonomy_slug'] is true
            if (isset($control['use_taxonomy_slug'])) {
                if (is_array($value)) {
                    // If $value is an array, add the item
                    $value['use_taxonomy_slug'] = $control['use_taxonomy_slug'];
                }
            }

            return $value;
        }

        public function content_template()
        {
            $control_uid = $this->get_control_uid();
            ?>
            <# var controlUID = '<?php echo esc_html( $control_uid ); ?>'; #>
            <# var currentID = elementor.panel.currentView.currentPageView.model.attributes.settings.attributes[data.name]; #>
            <div class="elementor-control-field">
                <# if ( data.label ) { #>
                <label for="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-title">{{{data.label }}}</label>
                <# } #>
                <div class="elementor-control-input-wrapper elementor-control-unit-5">
                    <# var multiple = ( data.multiple ) ? 'multiple' : ''; #>
                    <select id="<?php echo esc_attr( $control_uid ); ?>" {{ multiple }} class="tcg-select2" data-setting="{{ data.name }}"></select>
                </div>
            </div>
            <#
            ( function( $ ) {
            $( document.body ).trigger( 'tcg_select2_init',{currentID:data.controlValue,data:data,controlUID:controlUID,multiple:data.multiple} );
            }( jQuery ) );
            #>
            <?php
        }
    }
});

