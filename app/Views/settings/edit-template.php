<div class="page-header">
    <h5>Edit Template</h5>
</div>

<div class="card card-info">
    <div class="card-body">
        <form action="" method="post">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Email Heading </label>
                <div class="col-sm-6">
                    <input type="text" disabled name="form[email_heading]" class="form-control" value="<?= $item->email_heading; ?>" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Email Subject </label>
                <div class="col-sm-6">
                    <input type="text" name="form[email_subject]" class="form-control" value="<?= $item->email_subject; ?>" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Email From </label>
                <div class="col-sm-6">
                    <input type="text" name="form[email_from]" class="form-control" value="<?= $item->email_from; ?>" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Template </label>
                <div class="col-sm-10">
                    <textarea name="form[email_body]" id="temp_editor" class="form-control ckeditor"><?= $item->email_body; ?></textarea>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Status</label>
                <div class="col-sm-4">
                    <select name="form[status]" class="form-control">
                        <option <?= $item->status == 1 ? 'Selected' : ''; ?> value="1">Enabled</option>
                        <option <?= $item->status == 0 ? 'Selected' : ''; ?> value="0">Disabled</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label"> </label>
                <div class="col-sm-8">
                    <input type="submit" name="submit" class="btn btn-sm btn-primary" value="Save Template" />
                    <a href="<?= admin_url('settings/email-templates') ?>" class="btn btn-dark">Cancel</a>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label"> </label>
                <div class="col-sm-10">
                    <kbd>{full_name}</kbd> <kbd>{username}</kbd>
                </div>
            </div>
        </form>
    </div>
</div>