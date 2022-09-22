<div class="page-header">
    <h5>Edit Profile</h5>
</div>
<div class="px-3">
    <form enctype="multipart/form-data" action="" method="post">
        <div class="row">
            <div class="col-sm-3">
                <div class="card card-info mb-1 p-3">
                    <?php
                    $file = base_url('assets/img/avg.png');
                    if ($user->avatar != '') {
                        $file = base_url(upload_dir($user->avatar));
                    }
                    ?>
                    <img src="<?= $file; ?>" class="img-fluid circle" />
                </div>
                <div class="d-grid">
                    <input type="file" name="avatar" id="avatar" class="form-control">
                </div>
            </div>
            <div class="col-sm-9">
                <div class="card card-info p-3">
                    <div class="form-group row">
                        <label class="col-sm-3 control-label">Username</label>
                        <div class="col-sm-6">
                            <input type="text" value="<?= $user->username; ?>" disabled class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 control-label">Name</label>
                        <div class="col-sm-6">
                            <input type="text" name="form[first_name]" value="<?= $user->first_name; ?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 control-label">Email Id</label>
                        <div class="col-sm-6">
                            <input type="text" name="form[email_id]" value="<?= $user->email_id; ?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 control-label">Country </label>
                        <div class="col-sm-6">
                            <input type="text" name="form[country]" value="<?= $user->country; ?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 control-label">Phone Number</label>
                        <div class="col-sm-6">
                            <input type="text" name="form[phone_no]" value="<?= $user->phone_no; ?>" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-3 control-label"> </label>
                        <div class="col-sm-6">
                            <input type="submit" name="submit" value="Submit" class="btn btn-primary" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>