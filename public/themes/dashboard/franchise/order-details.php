<div class="box p-3">
    <div class="row">
        <div class="col-sm-4">
            <div class="d-flex justify-content-between">
                <h6>Orderid: ORD-<?= $order->id; ?></h6>
            </div>
            <hr />
            <form action="<?= site_url('franchise/order-details/' . $order->id) ?>" method="post">
                <table class="table border">
                    <tbody>
                        <tr>
                            <td>
                                <div class="d-flex justify-content-between">
                                    <div>Buyer Details</div>
                                    <div>Total: <?= $order->total_amt; ?></div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><?= $user->first_name . ' ' . $user->last_name; ?><br />
                                Username: <?= $user->username; ?> <br />
                                Mobile: <?= $user->mobile; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="form-label">Order status</label>
                                <?php
                                if ($order->order_status == 1) {
                                ?>
                                    <input type="hidden" name="order_status" value="1" />
                                    <label class="badge badge-success">Completed</label>
                                <?php
                                } else {
                                ?>
                                    <select name="order_status" class="form-select">
                                        <option value="0" <?= $order->order_status == 0 ? 'selected' : ''; ?>>Pending</option>
                                        <option value="1" <?= $order->order_status == 1 ? 'selected' : ''; ?>>Completed</option>
                                    </select>
                                <?php
                                }
                                ?>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="form-label">Comments</label>
                                <textarea rows="3" name="comments" class="form-control"><?= $order->comments; ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <button type="submit" name="button" value="update" class="btn btn-sm btn-primary">Update Order</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
        <div class="col-sm-8">
            <div class="d-flex justify-content-between align-items-center">
                <h6>Order Items:</h6>
                <a href="<?= site_url('franchise/orders') ?>" class="btn btn-xs btn-primary">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
            <hr />
            <table class="table">
                <thead>
                    <tr>
                        <th>Sl No</th>
                        <th>Product Code</th>
                        <th>Product name</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>BV</th>
                        <th>Sub Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sl = 1;
                    foreach ($items as $item) {
                    ?>
                        <tr>
                            <td><?= $sl++; ?></td>
                            <td><?= $item->product_code; ?></td>
                            <td><?= $item->ptitle; ?></td>
                            <td><?= $item->price; ?></td>
                            <td><?= $item->qty; ?></td>
                            <td><?= $item->qty * $item->bv; ?></td>
                            <td><?= $item->price * $item->qty; ?></td>
                        </tr>
                    <?php
                    }
                    ?>

                </tbody>
            </table>
        </div>
    </div>
</div>