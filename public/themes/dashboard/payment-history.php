<div class="d-flex justify-content-between align-items-center">
    <h5><?= $title; ?></h5>
    <?php
    if ($showButtons) {
    ?>
        <div>
            <a href="<?= site_url('dashboard/payment-history') ?>" class="btn btn-sm btn-primary">All Report</a>
            <a href="<?= site_url('dashboard/payment-history/?tab=sponsor') ?>" class="btn btn-sm btn-primary">Direct Income</a>
            <a href="<?= site_url('dashboard/payment-history/?tab=club') ?>" class="btn btn-sm btn-primary">Club Income</a>
        </div>
    <?php
    }
    ?>

</div>
<hr>
<div class="bg-white p-3 mb-2">
    <form action="<?= current_url(); ?>" method="get">
        <input type="hidden" name="tab" value="<?= $tab; ?>">
        <div class="row">
            <label class="col-sm-1">From</label>
            <div class="col-sm-2">
                <input type="date" name="from" value="<?= @$_GET['from'] ?>" class="form-control form-control-sm" />
            </div>
            <label class="col-sm-1">To</label>
            <div class="col-sm-2">
                <input type="date" name="to" value="<?= @$_GET['to'] ?>" class="form-control form-control-sm" />
            </div>
            <div class="col-sm-2">
                <input type="submit" value="Filter" class="btn btn-sm btn-primary">
            </div>
        </div>
    </form>
</div>
<div class="bg-white p-3">
    <div class="box-p table-responsive">
        <table class="table data-table">
            <thead>
                <tr>
                    <th>Sl No</th>
                    <th>Date</th>
                    <th>Notes</th>
                    <th>Amount</th>
                    <th>CR/DR</th>
                    <th>TXN</th>
                    <th>Comment</th>
                    <th>Balance</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (is_array($arrdata) && count($arrdata) > 0) {
                    $sl = 1;
                    foreach ($arrdata as $ob) {
                ?>
                        <tr>
                            <td><?= $sl++; ?></td>
                            <td><?= date('jS M, Y h:i:s A', strtotime($ob->created)); ?></td>
                            <td><?= $ob->notes; ?></td>
                            <td><?= ($ob->cr_dr == 'cr') ? '<span class="text-success">' : '<span class="text-danger">'; ?><?= $ob->amount; ?></span></td>
                            <td class="text-uppercase"><?= $ob->cr_dr; ?></td>
                            <td><?= $ob->ref_id; ?></td>
                            <td><?= $ob->comments; ?></td>
                            <td> $<?= $ob->total_bal; ?></td>
                        </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>