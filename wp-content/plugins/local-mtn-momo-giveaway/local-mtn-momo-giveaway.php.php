<?php
/*
Plugin Name: Local MTN MoMo Giveaway Payment
Description: Accept MTN MoMo donations for various departments with USD and LRD support.
Version: 1.1
Author: Potiphar
*/

// 1. Function to get access token from MoMo API
function momo_get_access_token() {
    $apiUser = 'b8bebc13-5f23-436d-8257-fa41a26fddb1';
    $apiKey = '7c8162a42f7b4be39a13fe2297afd25f';
    $subscriptionKey = '9970b5605e534555a722def4e81e99d5';

    $credentials = base64_encode("$apiUser:$apiKey");

    $response = wp_remote_post('https://sandbox.momodeveloper.mtn.com/collection/token/', [
        'headers' => [
            'Authorization' => "Basic $credentials",
            'Ocp-Apim-Subscription-Key' => $subscriptionKey
        ]
    ]);

    if (is_wp_error($response)) {
        return null;
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);
    return $body['access_token'] ?? null;
}

// 2. Create database table on plugin activation to store payment entries
register_activation_hook(__FILE__, function () {
    global $wpdb;
    $table = $wpdb->prefix . 'momo_payments';
    $charset = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100),
        phone VARCHAR(20),
        amount FLOAT,
        currency VARCHAR(10),
        department VARCHAR(50),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) $charset;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
});

// 3. REST API endpoint to initiate payment with MTN MoMo
add_action('rest_api_init', function () {
    register_rest_route('momo/v1', '/pay', [
        'methods' => 'POST',
        'callback' => 'momo_trigger_payment',
        'permission_callback' => '__return_true'
    ]);
});

function momo_trigger_payment(WP_REST_Request $request) {
    $data = $request->get_json_params();
    $name = sanitize_text_field($data['name']);
    $phone = sanitize_text_field($data['phone']);
    $amount = floatval($data['amount']);
    $currency = sanitize_text_field($data['currency']);
    $department = sanitize_text_field($data['department']);

    $access_token = momo_get_access_token();
    if (!$access_token) return rest_ensure_response(['error' => 'Unable to get access token']);

    $subscriptionKey = '9970b5605e534555a722def4e81e99d5';
    $targetEnvironment = 'sandbox';
    $referenceId = wp_generate_uuid4();

    $body = [
        'amount' => strval($amount),
        'currency' => $currency,
        'externalId' => uniqid('donate_'),
        'payer' => [
            'partyIdType' => 'MSISDN',
            'partyId' => $phone
        ],
        'payerMessage' => 'Thank you for donating',
        'payeeNote' => 'Donation received'
    ];

    $response = wp_remote_post("https://sandbox.momodeveloper.mtn.com/collection/v1_0/requesttopay", [
        'headers' => [
            'Authorization' => "Bearer $access_token",
            'X-Reference-Id' => $referenceId,
            'X-Target-Environment' => $targetEnvironment,
            'Ocp-Apim-Subscription-Key' => $subscriptionKey,
            'Content-Type' => 'application/json'
        ],
        'body' => json_encode($body)
    ]);

    // Save entry regardless of response (for logs)
    global $wpdb;
    $wpdb->insert($wpdb->prefix . 'momo_payments', [
        'name' => $name,
        'phone' => $phone,
        'amount' => $amount,
        'currency' => $currency,
        'department' => $department,
        'created_at' => current_time('mysql')
    ]);

    return rest_ensure_response([
        'message' => 'Payment request sent to MTN MoMo API',
        'reference_id' => $referenceId,
        'api_response' => json_decode(wp_remote_retrieve_body($response), true)
    ]);
}

// 4. Shortcode to render donation form on front-end
add_shortcode('momo_giveaway_form', function () {
    ob_start();
    ?>
    <div style="max-width: 400px; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); font-family: Arial, sans-serif;">
      <h5 style="font-family: Arial, sans-serif;">Pay with MTN Account</h5>

      <label><strong>Full Name</strong></label><br>
      <input type="text" id="donor-name" placeholder="Your full name" style="width: 100%; padding: 10px; margin: 10px 0;"><br>

      <label><strong>Phone Number</strong></label><br>
      <input type="tel" id="donor-phone" placeholder="e.g. 0880000000" style="width: 100%; padding: 10px; margin: 10px 0;"><br>

      <label><strong>Amount</strong></label><br>
      <input type="number" id="donation-amount" placeholder="Enter amount" style="width: 100%; padding: 10px; margin: 10px 0;"><br>

      <label><strong>Select Currency</strong></label><br>
      <select id="currency" style="width: 100%; padding: 10px; margin: 10px 0;">
        <option value="" disabled selected>Select currency</option>
        <option value="USD">USD</option>
        <option value="LRD">LRD</option>
      </select><br>

      <label><strong>Department</strong></label><br>
      <select id="department" style="width: 100%; padding: 10px; margin: 10px 0;">
        <option value="" disabled selected>Select department</option>
        <option value="Men's Department">Men's Department</option>
        <option value="Women's Department">Women's Department</option>
        <option value="Youth Department">Youth Department</option>
        <option value="Children Ministry">Children Ministry</option>
        <option value="Care Ministry">Care Ministry</option>
      </select><br>

      <button type="button" onclick="submitMoMoForm()" id="momo-submit-btn" style="width: 100%; padding: 12px; background-color: #1a73e8; color: white; border: none; border-radius: 4px; font-size: 16px;">
        DONATE NOW
      </button>
    </div>

    <style>
      #momo-submit-btn:hover {
        background-color: #000 !important;
      }
    </style>

    <script>
      function submitMoMoForm() {
        const name = document.getElementById('donor-name').value;
        const phone = document.getElementById('donor-phone').value;
        const amount = document.getElementById('donation-amount').value;
        const currency = document.getElementById('currency').value;
        const department = document.getElementById('department').value;

        if (!name || !phone || !amount || !currency || !department) {
          alert('Please fill all fields!.');
          return;
        }

        fetch('/wp-json/momo/v1/pay', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ name, phone, amount, currency, department })
        })
        .then(res => res.json())
        .then(data => {
          console.log(data);
          alert('Payment request sent!');
        });
      }
    </script>
    <?php
    return ob_get_clean();
});

// 5. Admin dashboard page to view all payment logs
add_action('admin_menu', function () {
    add_menu_page('MoMo Payments', 'MoMo Payments', 'manage_options', 'momo-payments', 'momo_render_admin_page');
});

function momo_render_admin_page() {
    global $wpdb;
    $table = $wpdb->prefix . 'momo_payments';
    $entries = $wpdb->get_results("SELECT * FROM $table ORDER BY created_at DESC");

    echo '<div class="wrap"><h1>MoMo Payment Entries</h1><table class="widefat fixed"><thead><tr><th>Name</th><th>Phone</th><th>Amount</th><th>Currency</th><th>Department</th><th>Date</th></tr></thead><tbody>';
    foreach ($entries as $entry) {
        echo "<tr>
            <td>{$entry->name}</td>
            <td>{$entry->phone}</td>
            <td>{$entry->amount}</td>
            <td>{$entry->currency}</td>
            <td>{$entry->department}</td>
            <td>{$entry->created_at}</td>
        </tr>";
    }
    echo '</tbody></table></div>';
}
