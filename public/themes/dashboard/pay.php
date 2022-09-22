<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<div class="d-flex justify-content-between align-items-center">
    <h5>Pay to Shop</h5>
    <h6>Repurchase Balance: <?= $balance; ?></h6>
</div>
<script>
    $(document).ready(function() {
        $('.form-select').select2();
    });
</script>
<div class="row">
    <div class="col-sm-6">
        <div class="bg-white p-4 border">
            <form action="<?= site_url('dashboard/pay'); ?>" method="post">
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Select Shop</label>
                    <div class="col-sm-8">
                        <select name="form[shop_id]" class="form-control form-select">
                            <option value="">Select Shop</option>
                            <?php
                            foreach ($shops as $ob) {
                            ?>
                                <option value="<?= $ob->id; ?>"><?= ucwords($ob->shop_title); ?> - (<?= $ob->mobile_no; ?>)</option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label">Transfer Amount</label>
                    <div class="col-sm-8">
                        <input type="text" name="form[amount]" class="form-control">
                        <span class="text-muted small">Repurchase Balance: <i class="fa fa-inr"></i> <?= $balance; ?></span>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label"></label>
                    <div class="col-sm-8">
                        <input type="submit" class="btn btn-primary" name="submit" value="Send now">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>