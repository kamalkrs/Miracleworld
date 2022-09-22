<div class="header-back">
    <a href="<?= site_url('dashboard/accounts') ?>">
        <i class="fa fa-chevron-left"></i> Deposit Records
    </a>
</div>
<div class="hgradiant p-3 position-relative logo-box footer-margin">
    <div class="bg-3 p-3 bg-white rounded mybox">
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Date</th>
                        <th>Amount </th>
                        <th>Address</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sl = 1;
                    foreach ($pending_list as $ob) {
                    ?>
                        <tr>
                            <td><?= $sl++; ?></td>
                            <td><?= date('d M Y h:i:s', strtotime($ob->created)); ?></td>
                            <td><?= $ob->amount; ?></td>
                            <td><?= $ob->payment_address; ?></td>
                            <td>
                                <?php
                                if ($ob->order_status == 0) echo '<span class="badge bg-warning">Pending</span>';
                                if ($ob->order_status == 1) echo '<span class="badge bg-success">Completed</span>';
                                if ($ob->order_status == -1) echo '<span class="badge bg-danger">Timeout</span>';
                                ?>
                            </td>
                            <td>
                                <a href="<?= site_url('dashboard/order-view/' . $ob->id); ?>" class="btn btn-xs btn-primary">View Details</a>
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