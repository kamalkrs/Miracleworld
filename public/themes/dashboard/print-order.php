<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Invoice #<?= $order->id; ?></title>
    <link href="<?= theme_url('dashboard/css/bootstrap.min.css'); ?>" rel="stylesheet" media="all" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Lato', sans-serif;
            box-sizing: border-box;
        }
    </style>
</head>

<body>
    <div class="container p-0 my-5 border">
        <div class="py-3 text-center">
            #INV<?= $order->id; ?>
        </div>
        <div class="border-bottom border-top d-flex justify-content-between p-2 small">
            <div>ORDER DATE: <?= date('d-m-Y', strtotime($order->created)); ?></div>
            <div>ORDER STATUS: <?= $order->order_status == 0 ? '<span class="badge text-dark bg-warning">PENDING</span>' : '<span class="badge bg-success">COMPLETED</span>'; ?></div>
        </div>
        <div class="row g-0">
            <div class="col">
                <div class="p-3">
                    <h5><strong>BUYER: </strong></h5>
                    <h6><?= $user->first_name . ' ' . $user->last_name; ?> (<?= $user->username; ?>)</h6>
                    <h6><?= ' Mobile: ' . $user->mobile; ?></h6>
                    <p>Address: <?= $user->address; ?></p>
                    <hr />
                    <h6><strong>FULFILLED BY: </strong></h6>
                    <h6><?= $fuser->first_name . ' ' . $fuser->last_name; ?> (<?= $fuser->username; ?>)</h6>
                    <h6><?= ' Mobile: ' . $fuser->mobile; ?></h6>
                    <p>Address: <?= $fuser->address; ?></p>
                </div>
            </div>
            <div class="col border-start">
                <div class="p-3">
                    <h5><strong>SELLER: </strong></h5>
                    <h6>ASBBrightWay</h6>
                    <p>Address: Ramgarh, JH</p>
                    <p>Email: support@asbbrightway.in</p>
                </div>
            </div>
        </div>
        <div class="border-bottom border-top p-1">
        </div>
        <div class="p-2">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Sl No</th>
                        <th>Product Info</th>
                        <th>MRP</th>
                        <th>BV</th>
                        <th>Qty</th>
                        <th>Sub Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sl = 1;
                    foreach ($items as $item) {
                        $sum = $item->price * $item->qty;
                    ?>
                        <tr>
                            <td><?= $sl++; ?></td>
                            <td><?= $item->ptitle . '<br />' . $item->product_code; ?></td>
                            <td><?= $item->price; ?></td>
                            <td><?= $item->bv; ?></td>
                            <td><?= $item->qty; ?></td>
                            <td><?= $sum; ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td colspan="5" class="text-end">Total</td>
                        <td><?= $order->total_amt; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="text-center small text-muted pb-2">
            This is computer generated invoice.
        </div>
    </div>
    <div class="container mb-4 p-0">
        <button class="btn btn-sm btn-outline-dark" style="border-radius: 2px;" onclick="window.print()">PRINT</button>
    </div>
</body>

</html>