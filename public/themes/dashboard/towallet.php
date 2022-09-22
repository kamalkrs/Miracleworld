<div id="withdraw">
    <div class="row">
        <div class="col-sm-5">
            <h5>Wallet to Fund Transfer</h5>
            <form action="<?= site_url('dashboard/sendtofund'); ?>" method="post">
                <div class="box">
                    <div class="box-p">
                        <h6>Wallet Balance: <?= $wallet_bal; ?></h6>
                        <hr />
                        <div class="from-group row">
                            <div class="col-sm-8">
                                <input type="text" name="amount" v-model="amount" required placeholder="e.g. <?= config_item('min_withdraw_limit'); ?>" class="form-control form-control-sm">
                            </div>
                            <div class="col-sm-4">
                                <input type="submit" name="btnsubmit" value="Submit" class="btn btn-sm btn-block btn-primary">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-sm-7" style="display: none;">
            <h5>Recent Withdrawal Request</h5>
            <div class="box box-p">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Sl No</th>
                            <th>Amount</th>
                            <th>Notes</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sl = 1;
                        foreach ($reqlist as $ob) {
                        ?>
                            <tr>
                                <td><?= $sl++; ?></td>
                                <td><?= $ob->amount; ?></td>
                                <td>
                                    <?php
                                    if ($ob->comments != '') echo '<span class="badge badge-warning">' . $ob->comments . '</span>';
                                    ?>
                                </td>
                                <td><?= date('jS M, Y H:i', strtotime($ob->created)); ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>