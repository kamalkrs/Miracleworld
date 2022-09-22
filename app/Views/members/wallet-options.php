<div class="page-header">
    <h5>Wallet Options</h5>
</div>
<div class="row mb-3">
    <div class="col-sm-6">
        <form action="<?= admin_url('members/update-wallet-options') ?>" method="post">
            <input type="hidden" name="user_id" value="<?= $user_id; ?>" />
            <input type="hidden" name="action" value="add-fund" />
            <div class="card card-info">
                <div class="card-body border-bottom">
                    <b>Add Funds to Wallet</b>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-3 col-form-label"><b>Amount</b></div>
                        <div class="col-9">
                            <input type="text" name="amount" placeholder="Amount" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <input type="submit" name="button" class="btn btn-primary" value="Add Funds" />
                </div>
            </div>
        </form>
    </div>
    <div class="col-sm-6">
        <form action="<?= admin_url('members/update-wallet-options') ?>" method="post">
            <input type="hidden" name="user_id" value="<?= $user_id; ?>" />
            <input type="hidden" name="action" value="deduct-fund" />
            <div class="card card-info">
                <div class="card-body border-bottom">
                    <b>Deduct Funds from Wallet</b>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-3 col-form-label"><b>Amount</b></div>
                        <div class="col-9">
                            <input type="text" name="amount" placeholder="Amount" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <input type="submit" name="button" class="btn btn-primary" value="Deduct Funds" />
                </div>
            </div>
        </form>
    </div>
</div>
<div class="row">
    <div class="col-sm-6 d-none">
        <form action="<?= admin_url('members/update-wallet-options') ?>" method="post">
            <input type="hidden" name="user_id" value="<?= $user_id; ?>" />
            <input type="hidden" name="action" value="disable-bonus" />
            <div class="card card-info">
                <div class="card-body border-bottom">
                    <b>Disable Bonus</b>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-3"> <b>Disable Bonus</b> </div>
                        <div class="col-9">
                            <div class="mb-2">
                                <label>
                                    <input type="checkbox" <?= $bm == 1 ? 'Checked' : ''; ?> name="binary_matching" />
                                    <b>Binary Matching</b>
                                </label>
                            </div>
                            <div class="mb-2">
                                <label>
                                    <input type="checkbox" <?= $rb == 1 ? 'Checked' : ''; ?> name="roi_bonus" />
                                    <b>Return on Investment</b>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <input type="submit" name="button" class="btn btn-primary" value="Update Bonus Status" />
                </div>
            </div>
        </form>
    </div>
    <div class="col-sm-6">
        <form action="<?= admin_url('members/update-wallet-options') ?>" method="post">
            <input type="hidden" name="user_id" value="<?= $user_id; ?>" />
            <input type="hidden" name="action" value="disable-wallet" />
            <div class="card card-info">
                <div class="card-body border-bottom">
                    <b>Disable Wallet</b>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-3"> <b>Disable Wallet</b> </div>
                        <div class="col-9">
                            <div class="mb-2">
                                <label>
                                    <input type="checkbox" <?= $dw == 1 ? 'Checked' : ''; ?> name="disable_withdraw" />
                                    <b>Disable Withdraw</b>
                                </label>
                            </div>
                            <div class="mb-2">
                                <label>
                                    <input type="checkbox" <?= $dt == 1 ? 'Checked' : ''; ?> name="disable_transfer" />
                                    <b>Disable Transfer</b>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <input type="submit" name="button" class="btn btn-primary" value="Update Wallet Status" />
                </div>
            </div>
        </form>
    </div>
</div>