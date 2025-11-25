<?php
/*
Template Name: event-ticket
Template Description: Event ticket PDF template
Version: 1.0
Group: Simpler
Template Author: mtk
*/

if (!defined('ABSPATH')) { exit; }
if (!isset($entry) || !function_exists('rgar')) { exit('This template must be loaded by Gravity PDF.'); }

// Map Gravity Forms entry fields into $form_data
$form_data = array(
        'name' => array(
                'first' => rgar($entry, '1.3'), // First Name
                'last'  => rgar($entry, '1.6'), // Last Name
        ),
        'email' => rgar($entry, '3'),
        'phone' => rgar($entry, '4'),
        'event_selection' => rgar($entry, '5'),
        'number_of_attendees' => rgar($entry, '6'),
        'add_ons' => array_filter(array(
                rgar($entry, '7.1') ? 'VIP Access' : null,
                rgar($entry, '7.2') ? 'Meal Package' : null,
                rgar($entry, '7.3') ? 'Printed Materials' : null,
        )),
        'total_cost' => rgar($entry, '14'),
        'id' => rgar($entry, 'id'), // Gravity Forms entry ID
);

// Fallbacks for WordPress functions
$logo_url = function_exists('get_template_directory_uri') ? get_template_directory_uri() . '/logo.png' : 'logo.png';
$site_url = function_exists('get_site_url') ? get_site_url() : 'https://example.com';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Event Ticket</title>
    <style>
        body { font-family:'Segoe UI','Helvetica Neue',sans-serif; background:#f0f0f0; padding:40px; }
        .ticket { display:flex; border-radius:12px; overflow:hidden; width:750px; margin:0 auto; background:#fff; box-shadow:0 6px 18px rgba(0,0,0,0.12); }
        .main { flex:3; padding:30px; background:#fafafa; border-right:2px dashed #ccc; }
        .stub { flex:1; padding:20px; background:#263238; color:#fff; text-align:center; }
        .header img { max-height:60px; margin-bottom:10px; }
        h1 { font-size:24px; margin:10px 0 20px; color:#37474f; text-align:center; }
        p { font-size:15px; margin:10px 0; color:#333; }
        .label { font-weight:bold; color:#000; }
        .stub h2 { font-size:18px; margin:10px 0; color:#fff; }
        .stub p { font-size:13px; margin:5px 0; color:#cfd8dc; }
        .section-title { font-size:16px; font-weight:bold; margin-top:20px; color:#555; border-bottom:1px solid #ddd; padding-bottom:5px; }
        .barcodecell { text-align:center; vertical-align:middle; margin-top:15px; }
        .barcode { padding:1.5mm; margin:0; vertical-align:top; color:#000044; }
    </style>
</head>
<body>

<div class="ticket">

    <!-- Main ticket body -->
    <div class="main">
        <div class="header" style="text-align:center;">
            <img src="<?php echo $logo_url; ?>" alt="Company Logo">
            <h1><?php echo htmlspecialchars($form_data['event_selection'] ? $form_data['event_selection'] : 'Event', ENT_QUOTES, 'UTF-8'); ?> Ticket</h1>
        </div>

        <div class="section-title">Registrant Details</div>
        <p><span class="label">Name: </span><?php echo htmlspecialchars($form_data['name']['first'].' '.$form_data['name']['last'], ENT_QUOTES, 'UTF-8'); ?></p>
        <p><span class="label">Email: </span><?php echo htmlspecialchars($form_data['email'] ? $form_data['email'] : 'N/A', ENT_QUOTES, 'UTF-8'); ?></p>
        <p><span class="label">Phone: </span><?php echo htmlspecialchars($form_data['phone'] ? $form_data['phone'] : 'N/A', ENT_QUOTES, 'UTF-8'); ?></p>

        <div class="section-title">Event Details</div>
        <p><span class="label">Number of Attendees: </span><?php echo htmlspecialchars($form_data['number_of_attendees'] ? $form_data['number_of_attendees'] : '0', ENT_QUOTES, 'UTF-8'); ?></p>
        <p><span class="label">Add-ons Selected: </span>
            <?php echo !empty($form_data['add_ons']) ? htmlspecialchars(implode(', ', $form_data['add_ons']), ENT_QUOTES, 'UTF-8') : 'None'; ?>
        </p>
        <p><span class="label">Total Cost: </span><?php echo '$'.number_format($form_data['total_cost'] ? $form_data['total_cost'] : 0, 2); ?></p>
    </div>

    <!-- Tear-off stub -->
    <div class="stub">
        <h2><?php echo htmlspecialchars($form_data['event_selection'] ? $form_data['event_selection'] : 'Event', ENT_QUOTES, 'UTF-8'); ?></h2>
        <p><?php echo htmlspecialchars($form_data['name']['first'] ? $form_data['name']['first'] : '', ENT_QUOTES, 'UTF-8'); ?></p>
        <div class="barcodecell">
            <barcode code="<?php echo $site_url . '/?entry=' . $form_data['id']; ?>" type="QR" class="barcode" size="0.8" error="M" />
        </div>
        <p>Scan for entry</p>
    </div>

</div>

</body>
</html>
