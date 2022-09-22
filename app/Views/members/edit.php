<div class="page-header">
    <h5>Edit Profile : <?= $m->username; ?></h5>
    <a class="btn btn-sm btn-dark" target="_blank" href="<?= site_url('home/autologin?user=' . $m->username . '&pass=' . $m->passwd); ?>">Auto Login</a>
</div>
<div class="row">
    <div class="col-sm-8">
        <div class="box">
            <div class="box-p">
                <p><em>Edit the details carefully !!</em></p>
                <hr>
                <div class="row">
                    <div class="col-sm-12">
                        <?php echo form_open(admin_url('members/edit/' . $m->id), array('class' => 'form-horizontal')); ?>
                        <div class="form-group row">
                            <label class="col-sm-2">First name</label>
                            <div class="col-sm-4">
                                <input type="text" name="frm[first_name]" value="<?php echo $m->first_name; ?>" class="form-control input-sm" />
                            </div>
                            <label class="col-sm-2">Last name</label>
                            <div class="col-sm-4">
                                <input type="text" name="frm[last_name]" value="<?php echo $m->last_name; ?>" class="form-control input-sm" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2">Mobile no</label>
                            <div class="col-sm-4">
                                <input type="text" name="frm[mobile]" value="<?php echo $m->mobile; ?>" class="form-control input-sm">
                            </div>
                            <label class="col-sm-2">Password</label>
                            <div class="col-sm-4">
                                <input type="text" name="frm[passwd]" value="<?php echo $m->passwd; ?>" class="form-control input-sm">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2">Email Id</label>
                            <div class="col-sm-4">
                                <input type="text" name="frm[email_id]" value="<?php echo $m->email_id; ?>" class="form-control input-sm">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2">State</label>
                            <div class="col-sm-4">
                                <select name="frm[state]" class="form-control">
                                    <?php
                                    $db = db_connect();
                                    $pack = $db->table("states")->get()->getResult();
                                    if (is_array($pack) and count($pack) > 0) {
                                        foreach ($pack as $k) {
                                    ?>
                                            <option value="<?= $k->id; ?>" <?= $m->state == $k->id ? 'selected' : '' ?>><?= $k->state_name; ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                                </select>
                            </div>
                            <label class="col-sm-2">Login Status</label>
                            <div class="col-sm-3">
                                <select class="form-control" name="frm[status]">
                                    <option value="0" <?= $m->status == 0 ? 'selected' : ''; ?>>Block</option>
                                    <option value="1" <?= $m->status == 1 ? 'selected' : ''; ?>>Unblock</option>
                                </select>
                            </div>

                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2">KYC Status</label>
                            <div class="col-sm-4">
                                <select name="frm[kyc_status]" class="form-control">
                                    <option value="1" <?= ($m->kyc_status == 1) ? 'selected' : ''; ?>>Approved</option>
                                    <option value="0" <?= ($m->kyc_status == 0) ? 'selected' : ''; ?>>Pending</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <h5>Wallet Details</h5>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <label class="col-sm-3 text-end">BTC Wallet Address <span class="required text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input class="form-control" type="text" value="<?= set_value('frm[btc_adrs]', $m->btc_adrs); ?>" name="frm[btc_adrs]">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 text-end">Doge Wallet Address <span class="required text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input class="form-control" type="text" value="<?= set_value('frm[doge_adrs]', $m->doge_adrs); ?>" name="frm[doge_adrs]">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 text-end">TRC20 Wallet Address <span class="required text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input class="form-control" type="text" value="<?= set_value('frm[trc20_adrs]', $m->trc20_adrs); ?>" name="frm[trc20_adrs]">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2">&nbsp;</label>
                            <div class="col-sm-9">
                                <input type="submit" name="submit" value="Save Details" class="btn btn-primary" />
                                <a href="<?= admin_url('members'); ?>" class="btn btn-dark">Cancel</a>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">

    </div>
</div>