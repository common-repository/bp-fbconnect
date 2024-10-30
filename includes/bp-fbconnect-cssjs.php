<?php
function bp_fbconnect_add_structure_css() {
	/* Enqueue the structure CSS file to give basic positional formatting for components */
	wp_enqueue_style( 'bp-fbconnect-structure', WP_PLUGIN_URL . '/bp-fbconnect/includes/css/structure.css' );
	wp_print_styles();
}
add_action( 'wp_head', 'bp_fbconnect_add_structure_css' );
?>