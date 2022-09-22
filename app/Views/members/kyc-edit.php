<div class="page-header">
    <h5>KYC Edit</h5>
</div>
<div class="row">
    <div class="col-sm-6">
        <form action="<?= admin_url('members/kyc-edit/' . $user->id) ?>" enctype="multipart/form-data" method="post">
            <div class="card card-info">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-3 col-form-label"><b>ID Proof</b></div>
                        <div class="col-sm-9">
                            <input type="file" name="id_proof" class="form-control" />
                            <?php
                            if ($user->id_proof != '') {
                            ?>
                                <a href="<?= base_url(upload_dir($user->id_proof)) ?>" target="_blank">
                                    <img class="my-2" src="<?= base_url(upload_dir($user->id_proof)) ?>" width="100" />
                                </a>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3 col-form-label"><b>Address Proof</b></div>
                        <div class="col-sm-9">
                            <input type="file" name="address_proof" class="form-control" />
                            <?php
                            if ($user->address_proof != '') {
                            ?>
                                <a href="<?= base_url(upload_dir($user->address_proof)) ?>" target="_blank">
                                    <img class="my-2" src="<?= base_url(upload_dir($user->address_proof)) ?>" width="100" />
                                </a>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3 col-form-label"></div>
                        <div class="col-sm-9">
                            <input type="submit" name="button" value="Update KYC" class="btn btn-sm btn-primary" />

                            <a href="<?= admin_url('members/edit/' . $user->id); ?>" class="btn btn-dark">Verify KYC Status</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>