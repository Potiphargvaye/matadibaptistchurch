<?php

namespace Better_Payment\Lite\Traits;

use Better_Payment\Lite\Classes\Plugin_Usage_Tracker;

/**
 * Exit if accessed directly
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Helper trait
 * 
 * @since 0.0.1
 */
trait Helper
{
    use Elements, ElementorHelper, WordPressHelper;

    public function bp_template_render($filePath, $variables = array(), $print = true)
    {
        $output = NULL;

        if (file_exists($filePath)) {
            // Extract the variables to a local namespace
            extract($variables);

            // Start output buffering
            ob_start();

            // Include the template file
            include $filePath;

            // End buffering and return its contents
            $output = ob_get_clean();
        }

        if ( $print ) {
			print $output;
        }
        
        return $output;
    }

	/**
     * List of allowed html tag for wp_kses
     *
	 * bp_allowed_tags
	 * @return array
	 */
	public function bp_allowed_tags() {
		return [
			'a'       => [
				'href'   => [],
				'title'  => [],
				'class'  => [],
				'rel'    => [],
				'id'     => [],
				'style'  => [],
				'target' => [],
			],
			'q'       => [
				'cite'  => [],
				'class' => [],
				'id'    => [],
			],
			'img'     => [
				'src'    => [],
				'alt'    => [],
				'height' => [],
				'width'  => [],
				'class'  => [],
				'id'     => [],
				'style'  => []
			],
			'span'    => [
				'class' => [],
				'id'    => [],
				'style' => []
			],
			'dfn'     => [
				'class' => [],
				'id'    => [],
				'style' => []
			],
			'time'    => [
				'datetime' => [],
				'class'    => [],
				'id'       => [],
				'style'    => [],
			],
			'cite'    => [
				'title' => [],
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'hr'      => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'b'       => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'p'       => [
				'class' => [],
				'id'    => [],
				'style' => []
			],
			'i'       => [
				'class' => [],
				'id'    => [],
				'style' => []
			],
			'u'       => [
				'class' => [],
				'id'    => [],
				'style' => []
			],
			's'       => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'br'      => [],
			'em'      => [
				'class' => [],
				'id'    => [],
				'style' => []
			],
			'code'    => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'mark'    => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'small'   => [
				'class' => [],
				'id'    => [],
				'style' => []
			],
			'abbr'    => [
				'title' => [],
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'strong'  => [
				'class' => [],
				'id'    => [],
				'style' => []
			],
			'del'     => [
				'class' => [],
				'id'    => [],
				'style' => []
			],
			'ins'     => [
				'class' => [],
				'id'    => [],
				'style' => []
			],
			'sub'     => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'sup'     => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'div'     => [
				'class' => [],
				'id'    => [],
				'style' => []
			],
			'strike'  => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'acronym' => [],
			'h1'      => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'h2'      => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'h3'      => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'h4'      => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'h5'      => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'h6'      => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'button'  => [
				'class' => [],
				'id'    => [],
				'style' => [],
				'data-paypal-info' => [],
			],
			'center'  => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'ul'      => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'ol'      => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'li'      => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'table'   => [
				'class' => [],
				'id'    => [],
				'style' => [],
				'dir'   => [],
				'align' => [],
			],
			'thead'   => [
				'class' => [],
				'id'    => [],
				'style' => [],
				'align' => [],
			],
			'tbody'   => [
				'class' => [],
				'id'    => [],
				'style' => [],
				'align' => [],
			],
			'tfoot'   => [
				'class' => [],
				'id'    => [],
				'style' => [],
				'align' => [],
			],
			'th'      => [
				'class'   => [],
				'id'      => [],
				'style'   => [],
				'align'   => [],
				'colspan' => [],
				'rowspan' => [],
			],
			'tr'      => [
				'class' => [],
				'id'    => [],
				'style' => [],
				'align' => [],
			],
			'td'      => [
				'class'   => [],
				'id'      => [],
				'style'   => [],
				'align'   => [],
				'colspan' => [],
				'rowspan' => [],
			],
		];
	}

	/**
     * Optional usage tracker
     *
     * @since v1.1.1
     */
    public function start_plugin_tracking()
    {
        $tracker = Plugin_Usage_Tracker::get_instance( BETTER_PAYMENT_FILE, [
            'opt_in'       => true,
            'goodbye_form' => true,
            'item_id'      => '64e1f724b5e14edb343e'
        ] );
        $tracker->set_notice_options(array(
            'notice' => 'Want to help make <strong>Better Payment</strong> even more awesome? You can get a <strong>10% discount coupon</strong> for Pro upgrade if you allow.',
            'extra_notice' => 'We collect non-sensitive diagnostic data and plugin usage information.
            Your site URL, WordPress & PHP version, plugins & themes and email address to send you the
            discount coupon. This data lets us make sure this plugin always stays compatible with the most
            popular plugins and themes. No spam, I promise.',
        ));
        $tracker->init();
    }

	/**
	 * List of currencies not supported by paypal or stripe
	 *
	 * @since v1.3.1
	 */
	public function bp_unsupported_currencies( $method = '' ) {
		$method = sanitize_text_field( strtolower( $method ) );
		$all_currencies = [
			'paypal' => [
				'AED',
				'BGN',
				'KES',
				'NGN',
				'RON',
				'ZAR'
			],
			'stripe' => [],
		];

		return !empty( $all_currencies[ $method ] ) ? $all_currencies[ $method ] : [];
	}

	/**
	 * List of currencies supported by paystack
	 *
	 * @since v1.3.1
	 */
	public function bp_supported_currencies( $method = '' ) {
		$method = sanitize_text_field( strtolower( $method ) );
		$supported_currencies = [
			'paystack' => [
				'GHS',
				'KES',
				'NGN',
				'USD',
				'ZAR'
			],
		];

		return !empty( $supported_currencies[ $method ] ) ? $supported_currencies[ $method ] : [];
	}

	/**
     * Get comprehensive currency data
     *
     * @since 0.0.2
     * @return array Array of currency data with codes, names, and symbols
     */
    private function get_currency_data() {
        return [
            'USD' => ['name' => 'US Dollar', 'symbol' => '$'],
            'EUR' => ['name' => 'Euro', 'symbol' => '€'],
            'GBP' => ['name' => 'British Pound', 'symbol' => '£'],
            'AED' => ['name' => 'UAE Dirham', 'symbol' => 'د.إ'],
            'AUD' => ['name' => 'Australian Dollar', 'symbol' => '$'],
            'BGN' => ['name' => 'Bulgarian Lev', 'symbol' => 'лв'],
            'CAD' => ['name' => 'Canadian Dollar', 'symbol' => '$'],
            'CHF' => ['name' => 'Swiss Franc', 'symbol' => 'CHF'],
            'CZK' => ['name' => 'Czech Koruna', 'symbol' => 'Kč'],
            'DKK' => ['name' => 'Danish Krone', 'symbol' => 'kr'],
            'HKD' => ['name' => 'Hong Kong Dollar', 'symbol' => '$'],
            'HUF' => ['name' => 'Hungarian Forint', 'symbol' => 'ft'],
            'ILS' => ['name' => 'Israeli Shekel', 'symbol' => '₪'],
            'JPY' => ['name' => 'Japanese Yen', 'symbol' => '¥'],
            'KES' => ['name' => 'Kenyan Shilling', 'symbol' => 'Ksh.'],
            'MXN' => ['name' => 'Mexican Peso', 'symbol' => '$'],
            'MYR' => ['name' => 'Malaysian Ringgit', 'symbol' => 'MYR'],
            'NGN' => ['name' => 'Nigerian Naira', 'symbol' => '₦'],
            'NOK' => ['name' => 'Norwegian Krone', 'symbol' => 'kr'],
            'NZD' => ['name' => 'New Zealand Dollar', 'symbol' => '$'],
            'PHP' => ['name' => 'Philippine Peso', 'symbol' => '₱'],
            'PLN' => ['name' => 'Polish Zloty', 'symbol' => 'zł'],
            'RON' => ['name' => 'Romanian Leu', 'symbol' => 'lei'],
            'RUB' => ['name' => 'Russian Ruble', 'symbol' => '₽'],
            'SEK' => ['name' => 'Swedish Krona', 'symbol' => 'kr'],
            'SGD' => ['name' => 'Singapore Dollar', 'symbol' => '$'],
            'THB' => ['name' => 'Thai Baht', 'symbol' => '฿'],
            'TRY' => ['name' => 'Turkish Lira', 'symbol' => '₺'],
            'TWD' => ['name' => 'Taiwan Dollar', 'symbol' => '$'],
            'ZAR' => ['name' => 'South African Rand', 'symbol' => 'R'],
        ];
    }

    /**
     * Get currency symbols list
     *
     * @since 0.0.2
     * @return array Array of currency codes and their symbols
     */
	public function get_currency_symbols_list() {
        $currency_data = $this->get_currency_data();
        $symbols = [];

        foreach ($currency_data as $code => $data) {
            $symbols[$code] = $data['symbol'];
        }

		return $symbols;
	}

	/**
     * Get currency list
     *
     * @since 0.0.2
     * @return array Array of currency codes as key-value pairs
     */
    public function get_currency_list() {
        $currency_data = $this->get_currency_data();
        $currency_list = [];

        foreach ($currency_data as $code => $data) {
            $currency_list[$code] = $code;
        }

        return $currency_list;
    }

    /**
     * Get currency names list
     *
     * @since 0.0.2
     * @return array Array of currency codes and their full names
     */
    public function get_currency_names_list() {
        $currency_data = $this->get_currency_data();
        $names = [];

        foreach ($currency_data as $code => $data) {
            $names[$code] = $data['name'];
        }

        return $names;
    }

    /**
     * Get currency symbol by code
     *
     * @since 0.0.2
     * @param string $currency_code The currency code (e.g., 'USD', 'EUR')
     * @return string The currency symbol or the code if symbol not found
     */
    public function get_currency_symbol($currency_code) {
        $currency_data = $this->get_currency_data();

        if (isset($currency_data[$currency_code])) {
            return $currency_data[$currency_code]['symbol'];
        }

        return $currency_code; // Fallback to currency code
    }

    /**
     * Get currency name by code
     *
     * @since 0.0.2
     * @param string $currency_code The currency code (e.g., 'USD', 'EUR')
     * @return string The currency name or the code if name not found
     */
    public function get_currency_name($currency_code) {
        $currency_data = $this->get_currency_data();

        if (isset($currency_data[$currency_code])) {
            return $currency_data[$currency_code]['name'];
        }

        return $currency_code; // Fallback to currency code
    }

	//
}
