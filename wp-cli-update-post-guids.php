<?php

/***
 * Plugin Name: Update all wp_posts GUIDs with full permalink
 * Plugin Author: Shawn M. Hooper
 * Author URL: https://shawnhooper.ca/
 * Plugin Version: 1.0
 ****/

class SMH_Update_GUIDs {

    function __construct() {
        WP_CLI::add_command( 'update-all-guids', array( $this, 'update_guids' ) );
    }

    function update_guids( $args, $assoc_args ) {

        $results = get_posts(array( 'post_type' => get_post_types(), 'posts_per_page' => -1, 'post_status' => 'any' ));

        global $wpdb;
        foreach ( $results as $post ) {
            WP_CLI::success('Updating Post ID # ' . $post->ID);
            $sql = $wpdb->prepare("UPDATE {$wpdb->prefix}posts SET guid = %s WHERE ID = %d", get_permalink($post), $post->ID);
            $wpdb->query($sql);
        }

    }

}

if ( defined( 'WP_CLI' ) && WP_CLI ) {
    new SMH_Update_GUIDs();
}