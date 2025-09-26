<?php

add_submenu_page('wp-forms-db-list.php', __( 'Extensions', 'database-for-wpforms' ), __( 'Extensions', 'database-for-wpforms' ), 'manage_options', 'wpfdb-database-extensions',  'wpformsdb_extensions' );

/**
 * Extensions page
 */
function wpformsdb_extensions(){
    ?>
    <div class="wrap">
        <h2><?php _e( 'Extensions for WPFormsDB', 'database-for-wpforms' ); ?>
            <span>
                <a class="button-primary" target="_blank" href="https://wpdebuglog.com"><?php _e( 'Browse All Extensions', 'database-for-wpforms' ); ?></a>
            </span>
        </h2>
        <p><?php _e( 'These extensions <strong>add functionality</strong> to WPFormDB', 'database-for-wpforms' ); ?></p>
        <?php echo wpformsdb_add_ons_get_feed(); ?>
    </div>
    <?php
}

/**
 * Add-ons Get Feed
 *
 * Gets the add-ons page feed.
 *
 * @since 1.0.6
 * @return void
 */
function wpformsdb_add_ons_get_feed(){
	$cache = get_transient( 'wpformsdb_add_ons_feed' );
	if ( false === $cache ) {
		$url = 'https://wpdebuglog.com/wpformsdb/?feed=true';
		$feed = wp_remote_get( esc_url_raw( $url ), array(
					'sslverify' => false,
					'timeout'     => 30,
				) );
		
		if ( ! is_wp_error( $feed ) ) {
			if ( isset( $feed['body'] ) && strlen( $feed['body'] ) > 0 ) {
				$cache = wp_remote_retrieve_body( $feed );
				set_transient( 'wpformsdb_add_ons_feed', $cache, 3600 );
			}
		} else {
			$cache = '<div class="error"><p>' . __( 'There was an error retrieving the extensions list from the server. Please try again later.', 'database-for-wpforms' ) . '</div>';
		}
	}
	return $cache;
}
// delete_transient('wpformsdb_add_ons_feed');