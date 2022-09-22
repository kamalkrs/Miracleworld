<div class="page-header">
    <h5>Plan Details</h5>
    <a href="<?= admin_url('plans/add'); ?>" class="btn btn-sm btn-primary "><i class="fa fa-plus-circle"></i> Add New Plan</a>
</div>
<div class="row">
    <div class="col-sm-8">
        <div class="card card-info p-3">
            <form method="POST" action="<?= admin_url('plans/add/' . $m->id) ?>">
                <div class="form-group row">
                    <label class="col-sm-2">Plan name</label>
                    <div class="col-sm-8">
                        <input type="text" name="frm[plan_title]" value="<?= set_value('frm[plan_title]', $m->plan_title); ?>" class="form-control input-sm" />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2">Plan Amt ($)</label>
                    <div class="col-sm-3">
                        <input type="text" name="frm[amount]" value="<?= set_value('frm[amount]', $m->amount) ?>" class="form-control input-sm">
                    </div>
                    <label class="col-sm-2">Capping Amt ($)</label>
                    <div class="col-sm-3">
                        <input type="text" name="frm[capping]" value="<?= set_value('frm[capping]', $m->capping) ?>" class="form-control input-sm">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2">ROI (%)</label>
                    <div class="col-sm-3">
                        <input type="text" name="frm[roi]" value="<?= set_value('frm[roi]', $m->roi) ?>" class="form-control input-sm">
                    </div>
                    <label class="col-sm-2">Validity (Days)</label>
                    <div class="col-sm-3">
                        <input type="text" name="frm[validity]" value="<?= set_value('frm[validity]', $m->validity) ?>" class="form-control input-sm">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2">Status</label>
                    <div class="col-sm-8">
                        <label class="radio radio-inline"><input type="radio" name="frm[status]" value="1" <?php if ($m->status == 1) echo 'checked'; ?> /> Active</label>
                        <label class="radio radio-inline"><input type="radio" name="frm[status]" value="0" <?php if ($m->status == 0) echo 'checked'; ?> /> Deactive</label>

                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2">&nbsp;</label>
                    <div class="col-sm-8">
                        <input type="submit" name="submit" value="Save Details" class="btn btn-primary" />
                        <a href="<?= admin_url('plans'); ?>" class="btn btn-dark">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>