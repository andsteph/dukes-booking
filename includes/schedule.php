<?php
global $wp;
if ( empty($_GET) ) {
    $_GET = $wp->query_vars;
}
if (array_key_exists('date', $_GET)) {
    $date = $_GET['date'];
    if ( $date == 'today' ) {
        $date = date('Y-m-d');
    }
}
if ( !is_admin() ) {
    $frontend = 'frontend';
} else {
    $frontend = '';
}
?>

<div class="dbs-schedule <?php echo $frontend; ?>">

    <div class="dbs-schedule-errors"></div>

    <form method="post">

        <div class="dbs-schedule-fields">
            <div>
                <label for="dbs-schedule-date">Date:</label>
                <input type='date' name='date' id='dbs-schedule-date' value='<?php echo $date; ?>'>
            </div>
            <div>
                <label for="dbs-schedule-email">Email:</label>
                <input type='email' name='email' id='dbs-schedule-email' value=''>
            </div>
            <div>
                <label for="dbs-schedule-price">Price:</label>
                <input type="number" step="0.01" id="dbs-schedule-price" value="0.00" readonly>
            </div>
            <input id="dbs-schedule-book" type="submit" class="button-primary" value="Book" disabled>
        </div>

        <?php $providers = DBS_Provider::get_all(); ?>
        <?php if (count($providers) > 0) : ?>
            <div class="dbs-schedule-providers">
                <?php foreach (DBS_Provider::get_all() as $provider) : ?>
                    <div class='dbs-schedule-provider' id='<?php echo $provider->ID; ?>'>
                        <div class='dbs-schedule-provider-heading'><?php echo $provider->name; ?></div>
                        <input type="hidden" name="provider_id" value="<?php echo $provider->ID; ?>" readonly>
                        <div class="dbs-schedule-provider-content">
                            <?php $provider->generate_entries($date); ?>
                        </div> <!-- .dbs-schedule-provider-content -->
                    </div> <!-- .dbs-schedule-provider -->
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <?php $new_provider_url = site_url() . '/wp-admin/post-new.php?post_type=dbs_provider'; ?>
            <p>You don't have any providers set up. You can do that <a href='$new_provider_url'>here</a>.</p>
        <?php endif; ?>

    </form>

</div> <!-- .dbs-schedule -->