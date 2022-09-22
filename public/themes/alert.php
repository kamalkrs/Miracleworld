<div class="row">
    <div class="col-sm-12">
        <?php
        $session = session();
        if ($session->get('success')) {
        ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $session->getFlashdata('success'); ?>
            </div>
        <?php
        }
        ?>
        <?php
        if ($session->get('info')) {
        ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?php echo $session->getFlashdata('info'); ?>
            </div>
        <?php
        }
        ?>
        <?php
        if ($session->get('error')) {
        ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $session->getFlashdata('error'); ?>
            </div>
        <?php
        }
        ?>
        <?php
        if ($session->get('warning')) {
        ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <?php echo $session->getFlashdata('warning'); ?>
            </div>
        <?php
        }
        $validation = \Config\Services::validation();
        if ($validation->getErrors()) {
        ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $validation->listErrors() ?>
            </div>
        <?php
        }
        ?>
    </div>
</div>