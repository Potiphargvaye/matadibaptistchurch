<?php
/**
 * This class responsible for database work
 * using WordPress functionality
 * get_option and update_option.
 */
class EasyJobs_DB {
    /**
     * Get all default Settings value.
     *
     * @param string $name
     * @return array
     */
    public static function default_settings() {

        $option_default = array();

        return $option_default;
    }
    /**
     * Get all Settings value from options table.
     *
     * @param string $name
     * @param bool   $single @since 1.3.1
     * @return array
     */
    public static function get_settings( $name = '', $single = false ) {

        if ( $single ) {
            return get_option( $name );
        }
        $settings = get_option( 'easyjobs_settings', true );
        if ( ! empty( $name ) ) {
            if ( isset( $settings[ $name ] ) ) {
                return $settings[ $name ];
            }
        }

        return is_array( $settings ) ? $settings : array();
    }
    /**
     * Update Settings
     *
     * @param mixed $value
     * @param string $key
     * @return boolean
     */
    public static function update_settings( $value, $key = '' ) {
        if ( ! empty( $key ) ) {
            return update_option( $key, $value );
        }
        return update_option( 'easyjobs_settings', $value );
    }
}
