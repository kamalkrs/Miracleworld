<div class="page-header">
    <h5><?= $title; ?></h5>
</div>
<div class="box p-3">
    <table class="table table-sm data-table">
        <thead>
            <tr>
                <th>Sl No</th>
                <th>Userid</th>
                <th>Name</th>
                <th>Amount</th>
                <th>Address</th>
                <th>Mobile No</th>
                <th>Date</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sl = 1;
            $db = db_connect();


            foreach ($items as $item) {
                $p = $db->table("users")->limit(1)->getWhere(['id' => $item->user_id])->getRow();
            ?>
                <tr>
                    <td><?= $sl++; ?></td>
                    <td><?= id2userid($item->user_id) ?></td>
                    <td><?= $p->first_name . ' ' . $p->last_name; ?></td>
                    <td><?= $item->amount; ?></td>
                    <td><?= $item->payment_address; ?></td>
                    <td><?= $p->mobile; ?></td>
                    <td><?= date('d M Y h:i a', strtotime($item->created)); ?></td>
                    <td>
                        <?php
                        if ($item->order_status == 0) echo '<span class="badge bg-warning">PENDING</span>';
                        if ($item->order_status == 1) echo '<span class="badge bg-success">CONFIRMED</span>';
                        if ($item->order_status == -1) echo '<span class="badge bg-warning">CANCELLED/TIMEOUT</span>';
                        ?>
                    </td>
                    <td>
                        <a href="<?= $item->status_url; ?>" target="_blank" class="btn btn-xs btn-primary">
                            Track
                        </a>
                        <?php
                        if ($item->order_status == 0) {
                        ?>
                            <a href="<?= admin_url('payments/markreceived/' . $item->id); ?>" class="btn btn-xs btn-dark btn-confirm" data-msg="Are you sure to confirm it">
                                Mark Received
                            </a>
                        <?php
                        }
                        ?>

                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>