<?php

namespace Better_Payment\Lite\Traits;

if (!defined('ABSPATH')) {
    exit();
} // Exit if accessed directly

trait Elements {
    /**
     * Register custom controls
     *
     * @since v4.4.2
     */
    public function register_controls($controls_manager)
    {
        $controls_manager->register_control('better-payment-select2', new \Better_Payment\Lite\Admin\Elementor\Controls\Select2());
    }
}
