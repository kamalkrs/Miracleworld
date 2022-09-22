<style>
    .img-fluid {
        max-width: 100%;
        height: auto;
    }

    .img-size {
        height: 50px;
        width: 50;
    }
</style>

<div class="page-header">
    <h5>KYC Status :: <?= $title; ?> </h5>
</div>
<div class="card card-info">
    <div class="card-body">
        <table class="table data-table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>ID Proof</th>
                    <th>Address Proof</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

                <?php
                foreach ($users as $d) {
                ?>
                    <tr>

                        <td><?php echo  $d->username; ?></td>
                        <td><img onerror="this.src='<?= base_url(upload_dir('default.png')) ?>'" class="img-fluid img-size" src="<?= site_url(upload_dir($d->id_proof)); ?>"></td>
                        <td><img onerror="this.src='<?= base_url(upload_dir('default.png')) ?>'" class="img-fluid img-size" src="<?= site_url(upload_dir($d->address_proof)) ?>"></td>
                        <td>
                            <a href="<?= admin_url('members/kyc-edit/' . $d->id); ?>" class="btn btn-xs btn-primary btn-sm"><i class="fa fa-pencil"></i> Update</a>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>