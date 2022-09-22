<div class="page-header">
    <h5>Create New</h5>
</div>
<div class="box p-3">
    <p>Pleae fill the details carefully. </p>
    <form enctype="multipart/form-data" action="<?= site_url('dashboard/create-new'); ?>" method="post">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label control-label">Subject</label>
            <div class="col-sm-8">
                <input type="text" required name="frm[subject]" class="form-control" />
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label control-label">Details</label>
            <div class="col-sm-8">
                <textarea name="frm[description]" required rows="6" class="form-control ckeditor"></textarea>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label control-label">Attachment</label>
            <div class="col-sm-8">
                <input type="file" name="attach" />
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label control-label"></label>
            <div class="col-sm-8">
                <input type="submit" name="btnsubmit" class="btn btn-primary" />
                <a href="<?= site_url('dashboard/supports'); ?>" class="btn btn-dark">Cancel</a>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript" src="<?= base_url("assets/js/editors/ckeditor.js"); ?>"></script>
<script>
    CKEDITOR.replace('.ckeditor');
</script>