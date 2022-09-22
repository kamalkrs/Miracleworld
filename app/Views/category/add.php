<div class="page-header">
    <h5>Category Form</h5>
</div>
<form action="<?= admin_url('categories/add/' . $cat->id) ?>" enctype="multipart/form-data" method="post">
    <div class="box p-4">
        <div class="row">
            <div class="col-sm-12">
                <div class="tab-pane active" id="description_tab">
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-4">
                            <input type="text" name="cat[name]" value="<?= set_value('cat[name]', $cat->name); ?>" class="form-control" />
                        </div>
                        <input type="hidden" name="cat[parent_id]" value="0" />
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-3">
                            <?php
                            $st = array(
                                1 => 'Active',
                                0 => 'Deactive'
                            );
                            echo form_dropdown('cat[status]', $st, $cat->status, 'class="form-control input-sm"');
                            ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 control-label">&nbsp;</label>
                        <div class="col-sm-8">
                            <button name="button" value="Save" type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button> <a href="<?php echo admin_url('categories'); ?>" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</form>