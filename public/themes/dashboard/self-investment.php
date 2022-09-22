<?php

use App\Models\Dashboard_model;

$db = db_connect();
$user_id = user_id();
?>
<div class="header-back">
    <a href="<?= site_url('dashboard') ?>">
        <i class="fa fa-chevron-left"></i> Self Investment
    </a>
</div>
<div class="px-3">
    <div id="campaign" class="bg-3 p-3 bg-white rounded mybox">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Sl No</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>End Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sl = 1;
                    foreach ($items as $item) {
                    ?>
                        <tr>
                            <td><?= $sl++; ?></td>
                            <td><?= usd_rs($item->amount) ?></td>
                            <td><?= date("M d, Y H:i", strtotime($item->created)) ?></td>
                            <td><?= $item->wallet_type == CAMPAIGN_WALLET ? 'Campaign' : 'Compounding'; ?></td>
                            <td><?= date("M d, Y H:i", strtotime($item->end_date)) ?></td>
                            <td>
                                <?php
                                if ($item->status == 1) echo '<span class="badge bg-success">Running</span>';
                                if ($item->status == 0) echo '<span class="badge bg-danger">Completed</span>';
                                ?>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>