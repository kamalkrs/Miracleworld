<div class="page-header">
    <h5>User Wallets</h5>
</div>
<div class="card card-info p-3">
    <table class="table">
        <thead>
            <tr>
                <th>Username</th>
                <th>Amount</th>
                <th>Type</th>
                <th>Updated</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($users as $user) {
                $balance = rand(122, 124);
            ?>
                <tr>
                    <td><?= $user->username; ?></td>
                    <td>$ <?= $user->balance ?></td>
                    <td>Wallet</td>
                    <td><?= $user->update; ?></td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="<?= admin_url('members/details/' . $user->id) ?>#history">History</a></li>
                                <li><a class="dropdown-item" href="<?= admin_url('members/wallet-options/' . $user->id) ?>">Wallet Option</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>