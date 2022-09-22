<div class="page-header">
    <h5><?= ucwords($type) ?> Form</h5>
</div>
<div class="card card-info p-3">
    <?php echo form_open_multipart(admin_url('posts/add-post/' . $p->id . '/?type=' . $type), array('class' => 'form-horizontal')); ?><div class="row">
        <div class="col-sm-12">
            <input type="hidden" name="form[parent_id]" value="0"><input type="hidden" name="form[slug]" value="0">
            <div class="form-group row">
                <label class="col-sm-2 control-label">Post Title</label>
                <div class="col-sm-8"><input type="text" name="form[post_title]" value="<?php echo set_value('form[post_title]', $p->post_title); ?>" class="form-control input-sm" placeholder="Post Title"></div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 control-label">Description</label>
                <div class="col-sm-10"><textarea class="ckeditor" name="form[description]"><?php echo set_value('form[description]', $p->description); ?></textarea></div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 control-label">Featured Image</label>
                <div class="col-sm-8"><input type="file" name="image"><?php if ($p->image != '') { ?><div style="text-align:center; padding:5px; border:1px solid #ddd;"><img src="<?php echo base_url(upload_dir($p->image)); ?>" alt="current" class="img-fluid" /><br />Current File<br /></div><label class="checkbox-inline"><input type="hidden" name="hid_img" value="<?php echo $p->image; ?>" /><input type="checkbox" name="del_img" value="1" />Delete this image </label><?php } ?></div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 control-label">Status</label>
                <div class="col-sm-2"><?php echo form_dropdown('form[status]', array(1 => 'Active', 0 => 'Deactive'), $p->status, 'class="form-control input-sm"'); ?></div>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-10 offset-sm-2">
            <button type="submit" name="submit" value="Submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Save </button>
            <a href="<?php echo admin_url('posts/?type=' . $type); ?>" class="btn btn-secondary btn-sm"><i class="fa fa-remove"></i> Cancel</a>
        </div>
    </div>
    </form>
</div>