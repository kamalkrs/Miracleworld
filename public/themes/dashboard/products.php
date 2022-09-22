<div class="d-flex justify-content-between align-items-center">
    <h5>Latest Products</h5>
    <!-- <a href="<?= site_url('dashboard/summary') ?>" class="btn btn-sm btn-primary"> <i class="fa fa-shopping-cart"></i> View Cart</a> -->
</div>
<hr>
<div class="box box-p">
    <form id="frmsave" class="m-0" method="post" action="<?= admin_url('products/print_barcode'); ?>">
        <input type="hidden" name="frmall" value="Save All" />
        <input type="hidden" name="url" value="<?= current_url(); ?>" />
        <table class="table data-table table-bordered table-striped m-0">
            <thead>
                <tr>
                    <th class="center" width="5%">
                        <input type="checkbox" onclick="dgUI.checkAll(this)" id="select_all">
                    </th>
                    <th>Id #</th>
                    <th>Status</th>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Price</th>
                    <th>BVP</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (is_array($products) && count($products) > 0) {
                    $x = count($products);
                    foreach ($products as $p) {
                        $text = '';
                ?>
                        <tr>
                            <td class="center">
                                <input type="checkbox" class="checkb" value="<?php echo $p->id; ?>" name="ids[]" />
                            </td>
                            <td><?= $p->id; ?>.</td>
                            <td>
                                <?php
                                if ($p->status == 1) {
                                ?>
                                    <a href="<?= admin_url('products/deactivate/' . $p->id, true); ?>" class="badge badge-success">Active</a>
                                <?php
                                } else {
                                ?>
                                    <a href="<?= admin_url('products/activate/' . $p->id, true); ?>" class="badge badge-danger">Deactive</a>
                                <?php
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if ($p->image != '') {
                                ?>
                                    <img src="<?= base_url(upload_dir($p->image)); ?>" class="img-fluid" width="80">
                                <?php
                                }
                                ?>

                            </td>
                            <td><a href="#" target="_blank"><?= $p->ptitle; ?></a></td>
                            <td><?= $p->price; ?></td>
                            <td><?= $p->bvp; ?></dd>
                            <td>
                                <div class="btn-group pull-right">
                                    <a href="<?= admin_url('dashboard/add-product/' . $p->id); ?>" title="Edit" class="btn btn-sm btn-info"><i class="fa fa-pencil"></i> </a>
                                    <a href="<?= admin_url('dashboard/delete/' . $p->id); ?>" title="Delete" class="btn btn-sm btn-danger delete"><i class="fa fa-trash"></i> </a>
                                </div>
                            </td>
                        </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </form>
</div>