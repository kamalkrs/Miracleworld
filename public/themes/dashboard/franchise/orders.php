<div class="mb-3 d-flex justify-content-between align-items-center">
    <a href="<?= site_url('franchise/new-order') ?>" class="btn btn-sm btn-primary"> <i class="fa fa-plus"></i> Create New Order</a>
    <a href="<?= site_url('franchise/summary') ?>" class="btn btn-sm btn-primary"> <i class="fa fa-shopping-cart"></i> View Cart</a>
</div>
<div class="box">
    <table class="table">
        <thead>
            <tr>
                <th>Orderid</th>
                <th>Order Date</th>
                <th>Customer Info</th>
                <th>Total Amount</th>
                <th>Order Status</th>
                <th>Comments</th>
                <th>Options</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($orders as $order) {
            ?>
                <tr>
                    <td>ORD-<?= $order->id; ?></td>
                    <td><?= date("d M, Y h:i A", strtotime($order->created)) ?></td>
                    <td><?= $order->first_name . ' ' . $order->last_name . '<br /><small class="text-muted">Username: ' . $order->username; ?></small></td>
                    <td><?= $order->total_amt; ?></td>
                    <td><?= $order->order_status == 0 ? '<span class="badge bg-warning">Pending</span>' : '<span class="badge bg-success">Completed</span>'; ?></td>
                    <td><?= $order->comments; ?></td>
                    <td>
                        <a title="View Order" href="<?= site_url('franchise/order-details/' . $order->id); ?>" class="btn btn-xs btn-primary"> <i class="fa fa-file-text"></i></a>
                        <a title="Print Invoice" target="_blank" href="<?= site_url('dashboard/print-order/' . $order->id); ?>" class="btn btn-xs btn-warning"> <i class="fa fa-print"></i> </a>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>