<div class="d-flex justify-content-between mb-3 align-items-center">
    <h5>All Products</h5>
    <!-- <a href="<?= site_url('franchise/new-stock') ?>" class="btn btn-sm btn-primary"> <i class="fa fa-plus"></i> Add New Stock</a> -->
</div>

<div class="bg-white p-2">
    <table class="table data-table m-0">
        <thead>
            <tr>
                <th>Sl</th>
                <th>Product</th>
                <th>Price</th>
                <th>BV</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sl = 1;
            foreach ($products as $p) {
            ?>
                <tr>
                    <td><?= $sl++; ?></td>
                    <td><?= $p->ptitle; ?></td>
                    <td>Rs <?= $p->price; ?></td>
                    <td><?= $p->bv; ?></td>
                </tr>
            <?php
            }
            ?>

        </tbody>
    </table>
</div>