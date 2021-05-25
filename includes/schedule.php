<?php
global $wp;
if (empty($_GET)) {
    $_GET = $wp->query_vars;
}
$date = date('Y-m-d');
if (array_key_exists('date', $_GET) && $_GET['date'] !== 'today') {
    $date = $_GET['date'];
}
if (!is_admin()) {
    $frontend = 'frontend';
    $origin = 'frontend';
} else {
    $frontend = '';
    $origin = 'admin';
}
?>

<div class="dbs-schedule <?php echo $frontend; ?>">

    <form action='<?php echo admin_url('admin-post.php'); ?>' method='post'>

        <p class="dbs-schedule-date-time">Fetching current date/time...</p>

        <p class="dbs-schedule-notes">Note: Sessions are <?php echo DukesBookingSystem::$block_time / 60; ?> minutes each.</p>

        <div class="dbs-schedule-errors">
            <?php if (array_key_exists('errors', $_GET)) : ?>
                <p>
                    <?php foreach ($_GET['errors'] as $error) : ?>
                        <?php echo $error; ?><br>
                    <?php endforeach; ?>
                </p>
            <?php endif; ?>
        </div>

        <input type="hidden" name="action" value="booking_submit">
        <input type="hidden" name="origin" value="<?php echo $origin; ?>">

        <div class="dbs-form-row">
            <div class="dbs-form-row-column">
                <label for="dbs-schedule-date">Booking Date:</label>
                <input type='date' name='date' id='dbs-schedule-date' value='<?php echo $date; ?>'>
            </div>
            <div class="dbs-form-row-column">
                <label for="dbs-schedule-price">Price:</label>
                <input type="number" step="0.01" id="dbs-schedule-price" value="0.00" readonly>
            </div>
            <div class="dbs-form-row-column">
                <label for="dbs-schedule-email">Email:</label>
                <input type='email' name='email' id='dbs-schedule-email'>
            </div>
        </div>

        <?php $providers = DBS_Provider::get_all(); ?>
        <?php if (count($providers) > 0) : ?>
            <div class="dbs-schedule-providers">
                <?php foreach (DBS_Provider::get_all() as $provider) : ?>
                    <div class='dbs-schedule-provider' id='<?php echo $provider->ID; ?>'>
                        <div class='dbs-schedule-provider-heading'><?php echo $provider->name; ?></div>
                        <div class="dbs-schedule-provider-content">
                            <?php $provider->generate_entries($date); ?>
                        </div> <!-- .dbs-schedule-provider-content -->
                    </div> <!-- .dbs-schedule-provider -->
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <?php $new_provider_url = admin_url('post-new.php?post_type=dbs_provider'); ?>
            <p>You don't have any providers set up. You can do that <a href='<?php echo $new_provider_url; ?>'>here</a>.</p>
        <?php endif; ?>

        <h2>Credit Card</h2>

        <div class="dbs-form-row">
            <div class="dbs-form-row-column">
                <label for="dbs-name">Name:</label>
                <input name="name" id="dbs-name">
            </div>
            <div class="dbs-form-row-column">
                <label for="dbs-number">Card #:</label>
                <input name="number" id="dbs-number" placeholder="#### #### #### ####">
            </div>
            <div class="dbs-form-row-column">
                <label for="dbs-expiration">Expiration:</label>
                <input name="expiration" id="dbs-expiration" placeholder="MM/YY">
                <label for="dbs-ccv">CCV:</label>
                <input name="ccv" id="dbs-ccv" placeholder="###">
            </div>
        </div>

        <div class="dbs-form-row">
            <input type="submit" class="button-primary" value="Confirm">
        </div>

    </form>

</div> <!-- .dbs-schedule -->