<div class="d-flex mb-3 justify-content-between align-items-center">
    <h5>Add New Stock</h5>
    <a href="<?= site_url('franchise/products') ?>" class="btn btn-sm btn-primary"> <i class="fa fa-close"></i> </a>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="bg-white border-rounded p-2">
            <table class="table m-0">
                <thead>
                    <tr>
                        <th>Sl</th>
                        <th>Product</th>
                        <th>Add</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sl = 1;
                    foreach ($products as $ob) {
                    ?>
                        <tr>
                            <td> <?= $sl++; ?> </td>
                            <td> <?= $ob->ptitle; ?> </td>
                            <td> <button type="button" data-product="<?= $ob->id; ?>" class="btn btn-add btn-sm btn-primary"> <i class="fa fa-plus-circle"></i> ADD </button> </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="bg-white border-rounded p-2">
            <h5>Order Summary</h5>
            <hr />
            <div id="orders"></div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $(document).on("click", ".btn-add", function() {
            let pid = $(this).data("product");
            $.ajax({
                url: '<?= site_url('franchise/add_to_order') ?>',
                data: {
                    id: pid
                },
                dataType: 'json',
                success: function(a) {
                    $('#orders').load('<?= site_url('franchise/stock-order'); ?>');
                },
                error: function(s) {
                    console.log(s);
                }
            })
        });
        $(document).on("click", ".btn-remove", function() {
            let pid = $(this).data("product");
            $.ajax({
                url: '<?= site_url('franchise/remove_item') ?>',
                data: {
                    id: pid
                },
                dataType: 'json',
                success: function(a) {
                    $('#orders').load('<?= site_url('franchise/stock-order'); ?>');
                },
                error: function(s) {
                    console.log(s);
                }
            })
        });
        $(document).on("click", ".btn-clear", function() {
            if (!confirm("Are you sure to remove all items?"))
                return false;
            $.ajax({
                url: '<?= site_url('franchise/remove_all') ?>',
                dataType: 'json',
                success: function(a) {
                    $('#orders').load('<?= site_url('franchise/stock-order'); ?>');
                },
                error: function(s) {
                    console.log(s);
                }
            })
        });
        $(document).on("click", ".btn-confirm", function() {
            if (!confirm("Are you sure to submit purchase order?"))
                return false;
            $(this).html("Saving...");
            $.ajax({
                url: '<?= site_url('franchise/place-order') ?>',
                dataType: 'json',
                success: function(a) {
                    window.location.href = "<?= site_url('franchise'); ?>"
                },
                error: function(s) {
                    console.log(s);
                }
            })
        });
        $('#orders').load("<?= site_url('franchise/stock-order') ?>");
    });
</script>