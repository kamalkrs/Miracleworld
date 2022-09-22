<div class="mb-3">
    <h5>All Kit Issued</h5>
</div>
<div class="box box-p">
    <table class="table table-sm data-table">
        <thead>
            <tr>
                <th>Sl No</th>
                <th>Date</th>
                <th>Customer Info</th>
                <th>Order Amount</th>
                <th>Order Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sl = 1;
            foreach ($orders as $ob) {
                $sb = $this->User_model->get_user($ob->user_id);
            ?>
                <tr>
                    <td><?= $sl++; ?></td>
                    <td><?= date("d M Y", strtotime($ob->created)); ?></td>
                    <td><?= $sb->first_name . ' ' . $sb->last_name . '<br />Username: ' . $sb->username; ?></td>
                    <td><?= $ob->total_amt; ?></td>
                    <td><?= $ob->order_status == 0 ? 'Pending' : 'Completed'; ?></td>
                    <td>
                        <a href="<?= site_url('franchise/order-details/' . $ob->id) ?>" class="btn btn-sm btn-primary">View Order</a>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>