<div class="page-header">
    <h5>Withdraw Details</h5>
</div>
<div class="px-3">
    <div class="row">
        <div class="col-sm-10">
            <div class="card card-info mb-3 p-2">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>Create Date</td>
                            <td><?= date("d M Y h:i A", strtotime($order->created)) ?></td>
                        </tr>
                        <tr>
                            <td>Last Update</td>
                            <td><?php
                                if ($order->status != 0) {
                                    echo date("d M Y h:i A", strtotime($order->updated));
                                }
                                ?></td>
                        </tr>
                        <tr>
                            <td>Username</td>
                            <td><?= id2userid($order->user_id) ?></td>
                        </tr>
                        <tr>
                            <td>Amount</td>
                            <td>$ <?= $order->amount; ?></td>
                        </tr>
                        <tr>
                            <td>Wallet Type</td>
                            <td><?= strtoupper($order->wallet_type); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Wallet Address</td>
                            <td><?= $order->wallet_adrs; ?> &nbsp; &nbsp;
                                <button style="width: 100px;" data-copy="<?= $order->wallet_adrs; ?>" class="btn btn-dark btn-xs btn-copy"> COPY </button>
                            </td>
                        </tr>
                        <tr>
                            <td>Txn Id</td>
                            <td><?= $order->txn_id; ?></td>
                        </tr>
                        <tr>
                            <td>Paid Total</td>
                            <td>$ <?= $order->paid_total; ?></td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>

                                <?php
                                if ($order->status == 0) {
                                ?>
                                    <span class="badge bg-warning">Pending</span>
                                    <!-- <a href="<?= admin_url('payout/withdrawal-update/' . $order->id) ?>/?status=1" class="btn btn-xs btn-success btn-confirm" data-msg="Are you sure to Approve withdrawal?">Approve</a>
                            <a href="<?= admin_url('payout/withdrawal-update/' . $order->id) ?>/?status=2" class="btn btn-xs btn-danger btn-confirm" data-msg="Are you sure to Reject withdrawal?">Reject</a> -->
                                <?php
                                } else if ($order->status == 1) {
                                ?>
                                    <span class="badge bg-success">Approved</span>
                                <?php
                                } else if ($order->status == 2) {
                                ?>
                                    <span class="badge bg-danger">Rejected</span>
                                <?php
                                } else if ($order->status == 3) {
                                ?>
                                    <span class="badge bg-dark">Cancelled</span>
                                <?php
                                }
                                ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <b>Manual Payout</b>
                        </div>
                        <div class="card-body">
                            <form action="<?= admin_url('payout/confirm-withdraw') ?>" method="POST">
                                <input type="hidden" name="order_id" value="<?= $order->id; ?>">
                                <div class="mb-3">
                                    <b>Transaction ID</b>
                                    <input type="text" name="form[txn_id]" class="form-control" />
                                </div>
                                <div class="mb-3">
                                    <b>Payment Amount</b>
                                    <input type="text" name="form[paid_total]" class="form-control" />
                                </div>
                                <button name="button" value="manual" class="btn btn-sm btn-primary">Process Payment</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 d-none">
                    <div class="card">
                        <div class="card-header">
                            <b>Auto Process using Gateway</b>
                        </div>
                        <div class="card-body">
                            <form action="">
                                <div class="mb-3">
                                    <b>Select Gateway</b>
                                    <select class="form-select form-select-sm">
                                        <option value="">Select Payout</option>
                                    </select>
                                </div>
                                <button name="button" value="manual" class="btn btn-sm btn-primary">Process Auto Payment</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>