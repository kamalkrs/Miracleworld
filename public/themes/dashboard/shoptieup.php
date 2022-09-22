<div class="d-flex justify-content-between mb-3">
    <h5>Shop Registration</h5>
    <div>
        <a href="<?= site_url('dashboard/add-shop'); ?>" class="btn btn-sm btn-primary"> <i class="fa fa-plus-circle"></i> Add New</a>
    </div>
</div>
<div class="box p-2">
    <table class="table data-table">
        <thead>
            <tr>
                <th>Sl No</th>
                <th>Shop name</th>
                <th>Owner name</th>
                <th>Address & City</th>
                <th>Pincode</th>
                <th>Mobile no</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sl = 1;
            foreach ($shops as $ob) {
            ?>
                <tr>
                    <td><?= $sl++; ?></td>
                    <td><?= $ob->shop_title; ?></td>
                    <td><?= $ob->first_name . ' ' . $ob->last_name; ?></td>
                    <td><?= $ob->address .  ' ' . $ob->city; ?></td>
                    <td><?= $ob->pincode; ?></td>
                    <td><?= $ob->mobile_no; ?></td>
                    <td><?= ($ob->approved) ? '<span class="badge badge-success">Approved</span>' : '<span class="badge badge-danger">Pending</span>'; ?></td>
                    <td>
                        <a href="<?= site_url('dashboard/shop-details/' . $ob->id); ?>" class="btn btn-xs btn-danger">Details</a>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>