<?php
/**
 * Smplfy Core
 *
 * @package SMP Core
 * @author  Simplify Small Biz
 * @since   1.0.1
 *
 * @wordpress-plugin
 * Plugin Name: Smplfy Core
 * Version: 1.0.1
 * Description: Core logic for a unified development approach across multiple plugins.
 * Author: Simplify Biz
 * Author URI: https://simplifybiz.com
 * Requires PHP: 7.4
 */

namespace SmplfyCore;

if (!defined('ABSPATH')) exit; // Security

define( 'SMP_CORE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SMP_CORE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include core files
require_once SMP_CORE_PLUGIN_DIR . 'includes/utilities/smplfy-security.php';
require_once SMP_CORE_PLUGIN_DIR . 'includes/utilities/SMPLFY_Require.php';
$require = new SMPLFY_Require( SMP_CORE_PLUGIN_DIR );

try {
    $require->directory( 'includes/hooks' );
    $require->directory( 'includes/entities' );
    $require->directory( 'includes/gravity-forms' );
    $require->directory( 'includes/repositories' );
    $require->directory( 'includes/utilities' );
    $require->directory( 'includes/settings' );
    $require->directory( 'includes/logger' );
    $require->directory( 'includes/wp-api' );
    $require->directory( 'includes/gravity-flow' );
} catch ( \Exception $e ) {
    error_log( $e->getMessage() );
}

register_activation_hook( __FILE__, 'SmplfyCore\smp_core_handle_plugin_activation' );

/**
 * =======================================================================
 * Task 4 Requirements – ADDED BELOW
 * =======================================================================
 */

/** 🔹 1. Enqueue Custom JS & CSS for Gravity Forms (From Task 1) **/
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script(
        'smplfy-core-intern-forms-js',
        SMP_CORE_PLUGIN_URL . 'assets/js/intern-forms.js',
        ['jquery'],
        '1.0.0',
        true
    );

    wp_enqueue_style(
        'smplfy-core-intern-forms-css',
        SMP_CORE_PLUGIN_URL . 'assets/css/intern-forms.css',
        [],
        '1.0.0',
        'all'
    );
});

/** 🔹 2. Message Above Gravity Forms **/
add_filter('gform_pre_render', __NAMESPACE__ . '\\add_smplfy_form_message');
add_filter('gform_pre_validation', __NAMESPACE__ . '\\add_smplfy_form_message');
add_filter('gform_pre_submission_filter', __NAMESPACE__ . '\\add_smplfy_form_message');
add_filter('gform_admin_pre_render', __NAMESPACE__ . '\\add_smplfy_form_message');

function add_smplfy_form_message($form) {
    echo '<div class="smplfy-note">Please fill this form carefully – powered by Smplfy Core Plugin</div>';
    return $form;
}

/** 🔹 3. Gravity Forms Webhook Integration (Task 1 & 3) **/
add_action('gform_after_submission', __NAMESPACE__ . '\\smplfy_send_to_webhook', 10, 2);

function smplfy_send_to_webhook($entry, $form) {

    // Change form title here if needed
    if ($form['title'] !== 'Event Registration Form') {
        return;
    }

    $url = 'https://webhook.site/75649330-dad8-4ee6-ab70-2bb0d5efc3b8'; // <-- Insert your real URL

    $body = [
        'name'    => rgar($entry, '1'),
        'email'   => rgar($entry, '3'),
        'phone'   => rgar($entry, '4'),
        'message' => rgar($entry, '5'),
    ];

    wp_remote_post($url, [
        'method'  => 'POST',
        'body'    => $body,
        'timeout' => 30,
    ]);
}

/** 🔹 4. GravityPDF Template Registration (Task 3 inside CORE plugin) **/
add_filter('gravitypdf_template_paths', __NAMESPACE__ . '\\smplfy_register_pdf_templates');

error_log("Core Plugin Updated via GitHub Actions");

