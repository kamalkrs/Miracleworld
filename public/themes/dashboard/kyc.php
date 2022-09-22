<div class="d-flex justify-content-between align-items-center">
    <div>
        <h5>KYC Upload- You KYC Status: <?= $doc->kyc_status == 1 ? 'Approved' : 'Pending' ?></h5>
        <div class="text-muted small">Please upload clearly visible documents. Size max 2 MB</div>
    </div>
    <a href="<?= site_url('dashboard/edit-profile') ?>" class="btn btn-sm btn-primary">Go Back</a>
</div>
<hr>
<?php
if ($doc->kyc_updated == 1 && $doc->kyc_status == 0) {
?>
    <div class="alert alert-warning">
        KYC Details has been submitted for Review !!
    </div>
<?php
} else if ($doc->kyc_status == 1) {
?>
    <div class="alert alert-primary">
        Your KYC Status has been Approved. Contact admin if you want to update it.
    </div>
<?php
}
?>
<style>
    .browse {
        display: flex;
        justify-content: flex-start;
        align-items: center;
    }

    .preview {
        background: #F8f5f9;
        border: dotted 1px #DDD;
        width: 80px;
        border-radius: 5px;
        margin-right: 15px;
        min-height: 80px;
    }
</style>

<script>
    $(document).ready(() => {

        $('.fileupload').on('change', function(e) {
            $(this).parent().parent().submit();
        });
    });
</script>
<?php
$img = theme_url('images/default.png');
$doc->id_proof  = $doc->id_proof == '' ? $img : base_url(upload_dir($doc->id_proof));
$doc->address_proof  = $doc->address_proof == '' ? $img : base_url(upload_dir($doc->address_proof));
$doc->image  = $doc->image == '' ? $img : base_url(upload_dir($doc->image));
?>
<div class="row">
    <div class="col-sm-8">
        <div class="box">
            <table class="table">
                <tr>
                    <td>Govt Id Proof</td>
                    <td>
                        <form enctype="multipart/form-data" action="<?= site_url('dashboard/kyc'); ?>" method="post">
                            <div data-file="aadhar" class="browse">
                                <div class="preview">
                                    <img id="photo-image" src="<?= $doc->id_proof; ?>" class="img-fluid" alt="">
                                </div>
                                <?php
                                if ($doc->kyc_status == 0) {
                                ?>
                                    <input class="fileupload" type="file" name="id_proof">
                                    <?php
                                    if ($doc->id_proof != $img) {
                                    ?>
                                        <a href="<?= site_url('dashboard/remove-kyc/?field=id_proof'); ?>" class="btn btn-xs btn-danger">Remove</a>
                                    <?php
                                    }
                                    ?>
                                <?php
                                }
                                ?>

                            </div>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td>Address Proof</td>
                    <td>
                        <form enctype="multipart/form-data" action="<?= site_url('dashboard/kyc'); ?>" method="post">
                            <div data-file="aadhar" class="browse">
                                <div class="preview">
                                    <img id="photo-image" src="<?= $doc->address_proof; ?>" class="img-fluid" alt="">
                                </div>
                                <?php
                                if ($doc->kyc_status == 0) {
                                ?>
                                    <input class="fileupload" type="file" name="address_proof">
                                    <?php
                                    if ($doc->address_proof != $img) {
                                    ?>
                                        <a href="<?= site_url('dashboard/remove-kyc/?field=address_proof'); ?>" class="btn btn-xs btn-danger">Remove</a>
                                    <?php
                                    }
                                    ?>
                                <?php
                                }
                                ?>

                            </div>
                        </form>
                    </td>
                </tr>
            </table>
            <?php
            if ($doc->kyc_status == 0) {
            ?>
                <div class="p-2">
                    <a href="<?= site_url('dashboard/kyc_submitted'); ?>" class="btn btn-primary">SUBMIT FOR REVIEW</a>
                </div>
            <?php
            }
            ?>

        </div>
    </div>
</div>