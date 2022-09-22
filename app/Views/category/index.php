<?php
$db = db_connect();
?>
<div class="d-flex justify-content-between align-items-center">
    <h4 class="h5">Categories </h4>
    <a class="btn btn-sm btn-primary" href="<?php echo admin_url('categories/add'); ?>"><i class="fa fa-plus-circle"></i> Add Category</a>
</div>
<hr>
<div class="box">
    <div class="box-p">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $builder = $db->table("categories");

                if (is_array($categories) && count($categories) > 0) {
                    $sl = 1;
                    foreach ($categories as $cat) {
                ?>
                        <tr>
                            <td><?= $sl++; ?></td>
                            <td><a href="#" target="_blank"><?= $cat->name; ?></a></td>
                            <td>
                                <?php if ($cat->status == 1) { ?>
                                    <a href="<?= admin_url('categories/deactivate/' . $cat->id, TRUE); ?>" class="badge badge-success">Active</a>
                                <?php } else { ?>
                                    <a href="<?= admin_url('categories/activate/' . $cat->id, TRUE); ?>" class="badge badge-danger">Deactive</a>
                                <?php } ?>
                            </td>
                            <td>
                                <div class="btn-group pull-right">
                                    <a href="<?= admin_url('categories/add/' . $cat->id); ?>" title="Edit" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i> </a>
                                    <a href="<?= admin_url('categories/delete/' . $cat->id); ?>" title="Delete" class="btn btn-xs btn-danger delete"><i class="fa fa-trash"></i> </a>
                                </div>
                            </td>
                        </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>