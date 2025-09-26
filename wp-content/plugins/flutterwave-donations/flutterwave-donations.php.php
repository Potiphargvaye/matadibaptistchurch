<?php
/*
Plugin Name: Flutterwave Donations
Description: Handles Flutterwave webhook, logs donations, and provides a donation form.
Version: 1.1
Author: Potiphar
*/

if (!defined('ABSPATH')) exit; // Exit if accessed directly

// 1. Register Flutterwave webhook route
add_action('rest_api_init', function () {
    register_rest_route('flutterwave/v1', '/webhook', array(
        'methods' => 'POST',
        'callback' => 'fw_handle_webhook',
        'permission_callback' => '__return_true',
    ));
});

// 2. Handle webhook data
function fw_handle_webhook(WP_REST_Request $request) {
    $event = $request->get_json_params();

    // Optional: add secret hash validation here for security

    if ($event && isset($event['data']['status']) && $event['data']['status'] === 'successful') {
        global $wpdb;
        $table = $wpdb->prefix . 'flutterwave_donations';

        // Prevent duplicate tx_ref entries
        $exists = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table WHERE tx_ref = %s", $event['data']['tx_ref']));
        if (!$exists) {
            $wpdb->insert($table, [
                'tx_ref'   => sanitize_text_field($event['data']['tx_ref']),
                'name'     => sanitize_text_field($event['data']['customer']['name']),
                'email'    => sanitize_email($event['data']['customer']['email']),
                'phone'    => sanitize_text_field($event['data']['customer']['phone_number']),
                'amount'   => floatval($event['data']['amount']),
                'currency' => sanitize_text_field($event['data']['currency']),
                'created_at' => current_time('mysql'),
            ]);
        }
    }

    return new WP_REST_Response(['message' => 'Webhook received'], 200);
}

// 3. Create donations table on plugin activation
register_activation_hook(__FILE__, 'fw_create_donations_table');

function fw_create_donations_table() {
    global $wpdb;
    $table = $wpdb->prefix . 'flutterwave_donations';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table (
        id INT AUTO_INCREMENT PRIMARY KEY,
        tx_ref VARCHAR(100),
        name VARCHAR(100),
        email VARCHAR(100),
        phone VARCHAR(50),
        amount DECIMAL(10,2),
        currency VARCHAR(10),
        created_at DATETIME
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// 4. Add admin menu to view donations
add_action('admin_menu', function () {
    add_menu_page(
        'Donations',
        'Donations',
        'manage_options',
        'flutterwave-donations',
        'fw_render_donation_page',
        'dashicons-heart',
        6
    );
});

function fw_render_donation_page() {
    global $wpdb;
    $table = $wpdb->prefix . 'flutterwave_donations';
    $donations = $wpdb->get_results("SELECT * FROM $table ORDER BY created_at DESC");

    echo "<div class='wrap'><h1>Flutterwave Donations</h1>";
    echo "<table class='widefat fixed striped'>";
    echo "<thead><tr><th>Date</th><th>Name</th><th>Email</th><th>Phone</th><th>Amount</th><th>Currency</th></tr></thead><tbody>";
    foreach ($donations as $d) {
        echo "<tr>
                <td>" . esc_html($d->created_at) . "</td>
                <td>" . esc_html($d->name) . "</td>
                <td>" . esc_html($d->email) . "</td>
                <td>" . esc_html($d->phone) . "</td>
                <td>" . esc_html($d->amount) . "</td>
                <td>" . esc_html($d->currency) . "</td>
              </tr>";
    }
    echo "</tbody></table></div>";
}

// 5. Add shortcode to display donation form
add_shortcode('flutterwave_donate_form', 'fw_donation_form_shortcode');

function fw_donation_form_shortcode() {
    ob_start(); ?>
    <div style="max-width: 500px; margin: 0 auto; font-family: Arial, sans-serif; padding: 20px; border: 1px solid #ccc; border-radius: 10px;">
      <h5 style="font-family: Arial, sans-serif; font-size: 16px; margin-bottom: 20px;">Pay with Credit Card</h5>

      <form action="https://checkout.flutterwave.com/v3/hosted/pay" method="POST">
        <input type="hidden" name="public_key" value="YOUR_FLUTTERWAVE_PUBLIC_KEY">
        <input type="hidden" name="tx_ref" value="<?php echo uniqid('tx_'); ?>">
        <input type="hidden" name="payment_options" value="card">
        <input type="hidden" name="currency" value="USD">

        <label>Name</label><br>
        <input type="text" name="customer[name]" required placeholder="Enter full name"
          style="width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 5px; border: 1px solid #ccc;"><br>

        <label>Email</label><br>
        <input type="email" name="customer[email]" required placeholder="Enter email address"
          style="width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 5px; border: 1px solid #ccc;"><br>

        <label>Phone Number</label><br>
        <input type="text" name="customer[phone_number]" required placeholder="Enter phone number"
          style="width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 5px; border: 1px solid #ccc;"><br>

        <label>Amount (USD)</label><br>
        <input type="number" name="amount" required placeholder="Enter donation amount"
          style="width: 100%; padding: 10px; margin-bottom: 25px; border-radius: 5px; border: 1px solid #ccc;"><br>

        <button type="submit" style="
          width: 100%;
          padding: 12px;
          background-color: #f57c00;
          color: white;
          border: none;
          border-radius: 5px;
          font-size: 16px;
          font-weight: bold;
          cursor: pointer;
          transition: background-color 0.3s ease;
        " 
        onmouseover="this.style.backgroundColor='black'" 
        onmouseout="this.style.backgroundColor='#f57c00'">
          Donate Now
        </button>
      </form>
    </div>
    <?php
    return ob_get_clean();
}
