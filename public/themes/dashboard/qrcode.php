<div class="page-header">
    <h5>Scan and Pay</h5>
</div>
<div class="hgradiant position-relative logo-box footer-margin">
    <div class="bg-3 p-3 bg-white rounded mybox text-center d-flex flex-column align-items-center">
        <h5>Invoice Amount: <?= $data->amount; ?> TRC20 USDT</h5>
        <h6>Txn No: <?= $data->txnid; ?></h6>
        <img src="<?= base_url(upload_dir('qr-' . $data->id . ".png")) ?>" width="240" class="mb-2" />

        <div class="mb-1 btn-group">
            <span class="bg-light p-2 small rounded border"><?= $data->payment_address; ?></span>
            <button data-target="#msgshow" data-copy="<?= $data->payment_address; ?>" class="btn btn-dark btn-copy"> <i class="fa fa-copy"></i> </button>
        </div>
        <div class="text-center mb-2">
            <span id="msgshow"></span>
        </div>
        <b>Status:
            <?php
            if ($data->payment_status == 0) echo '<span class="badge bg-warning">Pending</span>';
            if ($data->payment_status == 1) echo '<span class="badge bg-success">Confirmed</span>';
            if ($data->payment_status == 2) echo '<span class="badge bg-danger">Timeout</span>';
            ?>
        </b>
    </div>
</div>