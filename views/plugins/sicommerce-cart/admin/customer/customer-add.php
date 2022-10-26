<?php
if( !function_exists('customer_created_primary_content') ) {

	function customer_created_primary_content() {

	    $ci = &get_instance();

        $fields = customer_fields();

        include 'views/created/html-content.php';
	}

	add_action('user_created_sections_primary', 'customer_created_primary_content', 10, 1);
}

if( !function_exists('customer_created_secondary_info') ) {

	function customer_created_secondary_info( $customer ) {

		include 'views/created/html-note.php';
	}

	add_action('customer_created_sections_secondary', 'customer_created_secondary_info', 20, 1);
}