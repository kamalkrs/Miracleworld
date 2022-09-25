<div class="page-header">
    <h5>Re-birth Auto Pool</h5>
    <div>
        <form action="" class="d-flex" method="get">
            <input type="date" name="date" class="form-control" />
            <button type="submit" class="btn btn-primary">View</button>
        </form>
    </div>
</div>
<div class="box">
    <div class="box-p">
        <table class="table">
            <thead>
                <tr>
                    <th>Sl No</th>
                    <th>Userid</th>
                    <th>Amount</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (count($items) == 0) {
                ?>
                    <tr>
                        <td colspan="5" class="text-center">NO RECORD FOUND</td>
                    </tr>
                <?php
                }
                $sl = 1;
                foreach ($items as $item) {
                ?>
                    <tr>
                        <td><?= $sl++; ?></td>
                        <td><?= id2userid($item->user_id) ?></td>
                        <td><?= $item->amount; ?></td>
                        <td><?= date("d M Y", strtotime($item->created)) ?></td>
                        <td>
                            <?php
                            if ($item->status == 1) {
                            ?>
                                <a href="<?= admin_url('payments/rebirth-pay-single/' . $item->id) ?>" class="btn btn-xs btn-primary btn-confirm" data-msg="Are you sure to Pay?">Pay Now</a>
                            <?php
                            } else {
                            ?>
                                <button class="btn btn-xs btn-light">Paid</button>
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
    <?php
    if (count($items) > 0) {
    ?>
        <div class="card-footer text-end">
            <a href="<?= admin_url('payments/rebirth-pay-all') ?>" class="btn btn-sm btn-primary btn-confirm" data-msg="Are you sure to Pay to All?">PAY TO ALL</a>
        </div>
    <?php
    }
    ?>
</div>