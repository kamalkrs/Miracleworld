<div class="d-flex justify-content-between align-items-center">
    <h5><?= $title; ?></h5>
    <div>
        <a href="<?= site_url('dashboard/club-income') ?>" class="btn btn-sm btn-primary">All Club Income</a>
        <a href="<?= site_url('dashboard/club-income/?tab=silver') ?>" class="btn btn-sm btn-primary">Silver Club</a>
        <a href="<?= site_url('dashboard/club-income/?tab=gold') ?>" class="btn btn-sm btn-primary">Gold Club</a>
        <a href="<?= site_url('dashboard/club-income/?tab=platinum') ?>" class="btn btn-sm btn-primary">Platinum Club</a>
        <a href="<?= site_url('dashboard/club-income/?tab=emrald') ?>" class="btn btn-sm btn-primary">Emrald Club</a>
        <a href="<?= site_url('dashboard/club-income/?tab=diamond') ?>" class="btn btn-sm btn-primary">Diamond Club</a>
    </div>
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
        <table class="table table-sm data-table">
            <thead>
                <tr>
                    <th>Sl No</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>TXN</th>
                    <th>Club</th>
                    <th>Balance</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sl = 1;
                foreach ($arrdata as $ob) {
                ?>
                    <tr>
                        <td><?= $sl++; ?></td>
                        <td><?= date('d-M-Y', strtotime($ob->created)); ?></td>

                        <td><?= $ob->amount; ?></td>
                        <td><?= $ob->ref_id; ?></td>
                        <td><?= $ob->paylevel; ?></td>
                        <td><?= $ob->total_bal; ?></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>