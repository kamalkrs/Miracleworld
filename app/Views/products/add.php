<style>
    #cust1 {
        display: none;
    }
</style>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="h5">Product Form </h4>
    <a class="btn btn-sm btn-primary" href="<?php echo admin_url('products/add'); ?>"><i class="fa fa-plus-circle"></i> Add Product</a>
</div>
<hr>

<?php echo form_open_multipart(admin_url('products/add/' . $p->id), array('class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-sm-9">
        <div class="box box-p">
            <div class="form-group row mt-2">
                <label class="col-sm-2 control-label">Product Title</label>

                <div class="col-sm-10"><input type="text" name="frm[ptitle]" value="<?= set_value('frm[ptitle]', $p->ptitle); ?>" class="form-control input-sm" />
                </div>
            </div>
            <div class="form-group row mt-2">
                <label class="col-sm-2 control-label">Category</label>

                <div class="col-sm-5">
                    <?php
                    echo form_dropdown('frm[category]', $category, $p->category, 'class="form-control input-sm" required');
                    ?>
                </div>
            </div>
            <div class="form-group row mt-2">
                <label class="col-sm-2 control-label">Keywords</label>

                <div class="col-sm-10">
                    <textarea rows="8" cols="" class="form-control input-sm" name="frm[keywords]"><?= set_value('frm[keywords]', $p->keywords); ?></textarea>
                    <div class="text-muted small">Hint: One Keyword per line</div>
                </div>
            </div>
            <div class="form-group row mt-2">
                <label class="col-sm-2 control-label">Price</label>
                <div class="col-sm-3">
                    <input type="text" name="frm[price]" value="<?= set_value('frm[price]', $p->price); ?>" class="form-control input-sm" />
                </div>
                <label class="col-sm-3 control-label">Offer Price</label>
                <div class="col-sm-3">
                    <input type="text" name="frm[offer]" value="<?= set_value('frm[offer]', $p->offer); ?>" class="form-control input-sm" />
                </div>
            </div>
            <div class="col-sm-12 row">
                <label class="col-sm-2 control-label">Image</label>
                <div class="col-sm-3">
                    <input type="file" name="cover_image" id="file_name">
                    <?php
                    if ($p->image != '') {
                    ?>
                        <img src="<?= base_url(upload_dir($p->image)); ?>" class="img-fluid">
                    <?php
                    }
                    ?>
                </div>
            </div>

            <div class="form-group row mt-2">
                <label class="col-sm-2 control-label">Status</label>
                <div class="col-sm-3">
                    <?php $st = array(1 => 'Active', 0 => 'Deactive');
                    echo form_dropdown('frm[status]', $st, $p->status, 'class="form-control input-sm"'); ?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 control-label">&nbsp;</label>
                <div class="col-sm-10">
                    <input type="submit" name="button" value="Save Details" class="btn btn-sm btn-primary" />
                    <a href="<?php echo admin_url('products'); ?>" class="btn btn-sm btn-danger">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>