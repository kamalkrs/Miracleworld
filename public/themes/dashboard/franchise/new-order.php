<div class="d-flex justify-content-between align-items-center">
    <h5>Latest Products</h5>
    <a href="<?= site_url('dashboard/summary') ?>" class="btn btn-sm btn-primary"> <i class="fa fa-shopping-cart"></i> View Cart</a>
</div>
<hr>
<div class="row">
    <?php
    foreach ($products as $ob) {
        if ($ob->image == '') {
            $img_src = 'https://placeimg.com/400/300';
        } else {
            $img_src = base_url(upload_dir($ob->image));
        }
    ?>
        <div class="col-sm-4">
            <div class="box">
                <img src="<?= $img_src; ?>" class="img-fluid" />
                <hr />
                <div class="p-2">
                    <h6><?= $ob->ptitle; ?></h6>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div style="font-weight: bold;">MRP: <i class="fa fa-inr"></i> <?= $ob->price; ?></div>
                            <div>DP: <i class="fa fa-inr"></i> <?= $ob->dp; ?></div>
                            <div>BV: <?= $ob->bvp; ?></div>
                            <div>Offer Price: <i class="fa fa-inr"></i> <?= $ob->offer; ?></div>
                        </div>
                        <div>

                            <div class="btn-group btn-group-sm" role="group">
                                <button id="btnGroupDrop<?= $ob->id; ?>" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    Add To Cart
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="btnGroupDrop<?= $ob->id; ?>">
                                    <li><a class="dropdown-item" href="<?= site_url('dashboard/add-cart/' . $ob->id . '/?type=dp'); ?>">With DP</a></li>
                                    <li><a class="dropdown-item" href="<?= site_url('dashboard/add-cart/' . $ob->id) . '/?type=offer'; ?>">With Offer Price</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
    ?>
</div>
<?php // print_r($products);
?>