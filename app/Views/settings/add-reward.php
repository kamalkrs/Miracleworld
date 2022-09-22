<div class="page-header">
    <h5>Add/Edit Reward</h5>
</div>
<div class="box p-4">
    <div class="row">
        <div class="col-sm-8">
            <form action="" method="post">
                <input type="hidden" name="rank_id" value="<?= $rank->id; ?>">
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Rank Title</label>
                    <div class="col-sm-8">
                        <input type="text" name="form[reward_title]" placeholder="Reward Title" class="form-control" value="<?= set_value('form[reward_title]', $rank->reward_title) ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Rank Order</label>
                    <div class="col-sm-4">
                        <input type="number" name="form[reward_order]" placeholder="Higher value, Higher rank" class="form-control" value="<?= set_value('form[reward_order]', $rank->reward_order) ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Business</label>
                    <div class="col-sm-4">
                        <input type="number" name="form[left_count]" placeholder="Left Business Value" class="form-control" value="<?= set_value('form[left_count]', $rank->left_count) ?>">
                    </div>
                    <div class="col-sm-4">
                        <input type="number" name="form[right_count]" placeholder="Right Business Value" class="form-control" value="<?= set_value('form[right_count]', $rank->right_count) ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Reward Items</label>
                    <div class="col-sm-4">
                        <input type="number" placeholder="Reward Amount" name="form[gift_item]" class="form-control" value="<?= set_value('form[gift_item]', $rank->gift_item) ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label"></label>
                    <div class="col-sm-9">
                        <input type="submit" name="button" value="Save Reward" class="btn btn-primary">
                        <a href="<?= admin_url('settings/rewards') ?>" class="btn btn-dark">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>