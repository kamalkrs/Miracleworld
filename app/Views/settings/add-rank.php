<div class="page-header">
    <h5>Add/Edit Rank</h5>
</div>
<div class="box p-4">
    <div class="row">
        <div class="col-sm-8">
            <form action="" method="post">
                <input type="hidden" name="rank_id" value="<?= $rank->id; ?>">
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Rank Title</label>
                    <div class="col-sm-8">
                        <input type="text" name="form[rank_title]" placeholder="Rank Title" class="form-control" value="<?= set_value('form[rank_title]', $rank->rank_title) ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Rank Order</label>
                    <div class="col-sm-4">
                        <input type="number" name="form[rank_order]" placeholder="Higher value, Higher rank" class="form-control" value="<?= set_value('form[rank_order]', $rank->rank_order) ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Self Business</label>
                    <div class="col-sm-4">
                        <input type="number" name="form[self_business_min]" placeholder="Minimum Value" class="form-control" value="<?= set_value('form[self_business_min]', $rank->self_business_min) ?>">
                    </div>
                    <div class="col-sm-4">
                        <input type="number" name="form[self_business_max]" placeholder="Maximum Value" class="form-control" value="<?= set_value('form[self_business_max]', $rank->self_business_max) ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Team Business</label>
                    <div class="col-sm-4">
                        <input type="number" placeholder="Minimum Value" name="form[team_business]" class="form-control" value="<?= set_value('form[team_business]', $rank->team_business) ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label"></label>
                    <div class="col-sm-9">
                        <input type="submit" name="button" value="Save Rank" class="btn btn-primary">
                        <a href="<?= admin_url('settings/ranks') ?>" class="btn btn-dark">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>