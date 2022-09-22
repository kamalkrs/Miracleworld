<div class="page-header">
    <h5>Page Form</h5>
</div>
<div class="card card-info">
    <div class="card-body">
        <form action="<?= admin_url('posts/add-page/' . $p->id); ?>" method="post">
            <div class="form-group row">
                <label class="col-sm-2 control-label">Title</label>
                <div class="col-sm-8">
                    <input type="text" name="form[post_title]" value="<?= set_value('form[post_title]', $p->post_title); ?>" class="form-control input-sm" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 control-label">Description</label>
                <div class="col-sm-10">
                    <textarea rows="8" cols="" class="form-control input-sm ckeditor" name="form[description]"><?= set_value('form[description]', $p->description); ?></textarea>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 control-label">Image</label>
                <div class="col-sm-6">
                    <input type="file" name="image" class="form-control" />
                    <?php
                    if ($p->image <> '') {
                    ?>
                        <img src="<?= base_url(upload_dir($p->image)); ?>" class="img-thumbnail img-responsive" /><br />
                        <label class="checkbox checkbox-inline"><input type="checkbox" name="del_img" value="1" /> Delete Image</label>
                        <input type="hidden" name="hid_img" value="<?= $p->image; ?>" />
                    <?php
                    }
                    ?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 control-label">Status</label>
                <div class="col-sm-3">
                    <?php
                    $st = array(
                        1 => 'Active',
                        0 => 'Deactive'
                    );
                    echo form_dropdown('form[status]', $st, $p->status, 'class="form-control input-sm"');
                    ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-8 offset-sm-2">
                    <input type="submit" name="submit" value="Save" class="btn btn-primary" />
                    <a href="<?= admin_url('posts/pages'); ?>" class="btn btn-dark">Cancel</a>
                </div>
            </div>
        </form>

    </div>

</div>