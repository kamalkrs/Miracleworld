<div class="d-flex justify-content-between">
    <div>
        <h3>Welcome <span class="text-primary"><?= $me->first_name . ' ' . $me->last_name; ?></span></h3>
        <p class="text-muted"><?= date('d M, Y'); ?>, Time: <?= date("h:i A") ?></p>
    </div>
    <div>
        <a href="<?= site_url('franchise/kit-issue') ?>" class="btn btn-sm btn-warning">Issue Joining Kit</a>
        <a href="<?= site_url('franchise/new-order') ?>" class="btn btn-sm btn-primary">Create New Order</a>
    </div>
</div>
<div class="row">
    <div class="col-sm-3">
        <div class="bg-primary card-box">
            <h6>Today's Order</h6>
            <div><?= $todays; ?></div>
            <a href="#" class="btn btn-xs btn-outline-light">View List</a>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="bg-success card-box">
            <h6>Total Orders</h6>
            <div><?= $total; ?></div>
            <a href="<?= site_url('franchise/orders') ?>" class="btn btn-xs btn-outline-light">View List</a>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="bg-warning text-dark card-box">
            <h6>Total Kit Issueed</h6>
            <div><?= $kit_issued; ?></div>
            <div>
                <a href="<?= site_url('franchise/kit-issued') ?>" class="btn btn-xs btn-outline-light">View List</a>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="bg-info text-dark card-box">
            <h6>Customer Orders</h6>
            <div><?= $cust_orders; ?></div>
            <div>
                <a href="<?= site_url('franchise/customer-orders') ?>" class="btn btn-xs btn-outline-light">View List</a>
            </div>
        </div>
    </div>
</div>
<h6 class="my-3">Recent Orders</h6>
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
                    <td><?= $order->order_status == 0 ? 'Pending' : 'Completed'; ?></td>
                    <td><?= $order->comments; ?></td>
                    <td>
                        <a href="<?= site_url('franchise/order-details/' . $order->id); ?>" class="btn btn-sm btn-primary">View Order</a>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>