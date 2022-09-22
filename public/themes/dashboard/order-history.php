<h5>Order History</h5>
<hr>
<div class="box">
    <div class="box-p table-responsive">
        <table class="table data-table">
            <thead>
                <tr>
                    <th>Sl No</th>
                    <th>Orderid</th>
                    <th>Franchise</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Order Status</th>
                    <th>BV Earned</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (is_array($orders) && count($orders) > 0) {
                    $sl = 1;
                    foreach ($orders as $ob) {
                        $fu = $this->db->select('id, username, first_name, last_name, mobile, address')->get_where("users", ['id' => $ob->fuser_id])->row();
                ?>
                        <tr>
                            <td><?= $sl++; ?></td>
                            <td><?= $ob->id; ?></td>
                            <td>
                                <?php
                                echo $fu->first_name . ' ' . $fu->last_name  . '(' . $fu->username . ')' . '<br />Mobile: ' . $fu->mobile . '<br />' . $fu->address;
                                ?>
                            </td>
                            <td><?= date('jS M, Y', strtotime($ob->created)); ?></td>
                            <td><?= $ob->total_amt; ?></td>
                            <td><?php
                                if ($ob->order_status == 0) echo '<span class="badge bg-danger">Pending</span>';
                                if ($ob->order_status == 1) echo '<span class="badge bg-success">Completed</span>';
                                if ($ob->order_status == 2) echo '<span class="badge bg-dark">Cancelled</span>';
                                ?></td>
                            <td><?= $ob->pv; ?></td>
                            <td>
                                <a target="_blank" href="<?= site_url('dashboard/print-order/' . $ob->id); ?>" class="btn btn-sm btn-primary">Invoice</a>
                            </td>
                        </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>