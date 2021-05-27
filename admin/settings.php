<form action="<?php echo admin_url('admin-post.php'); ?>" method="post">
    <input type="hidden" name="action" value="settings_submit">
    <table class="form-table" role="presentation">
        <tbody>
            <tr>
                <th scope="row"><label for="dbs-start-time">Start Time</label></th>
                <td><input type="time" name="start_time" id="dbs-start-time" value="<?php echo $start_time; ?>"></td>
            </tr>
            <tr>
                <th scope="row"><label for="dbs-end-time">End Time</label></th>
                <td><input type="time" name="end_time" id="dbs-end-time" value="<?php echo $end_time; ?>"></td>
            </tr>
            <tr>
                <th scope="row"><label for="dbs-block-time">Block Time (minutes)</label></th>
                <td><input type="number" step="1" name="block_time" id="dbs-block-time" value="<?php echo $block_time; ?>"></td>
            </tr>
            <tr>
                <th scope="row"><label for="dbs-buffer-time">Buffer Time (minutes)</label></th>
                <td><input type="number" step="1" name="buffer_time" id="dbs-buffer-time" value="<?php echo $buffer_time; ?>"></td>
            </tr>
            <tr>
                <th scope="row"><label for="dbs-block-price">Block Price ($)</label></th>
                <td><input type="number" step="0.01" name="block_price" id="dbs-block-price" value="<?php echo $block_price; ?>"></td>
            </tr>
            <tr>
                <th scope="row"><label for="dbs-extra-block-discount">Extra Block Discount (%)</label></th>
                <td><input type="number" step="1" min="0" max="100" name="extra_block_discount" id="dbs-extra-block-discount" value="<?php echo $extra_block_discount; ?>"></td>
            </tr>
            <tr>
                <th scope="row"><label for="dbs-tax">Tax (%)</label></th>
                <td><input type="number" step="1" min="0" max="100" name="tax" id="dbs-tax" value="<?php echo $tax; ?>"></td>
            </tr>
        </tbody>
    </table>
    <p class="submit">
        <input type="submit" name="submit" id="submit" class="button-primary" value="Save Settings">
    </p>
</form>