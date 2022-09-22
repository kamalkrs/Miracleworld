<h5>Wallet to Fund Transfer</h5>
<hr />

<div class="row">
    <div class="col-sm-5">
        <div class="box">
            <div class="box-p" id="funds">
                <form class="form-horizontal" method="POST" action="<?= site_url('dashboard/wallet-transfer'); ?>">
                    <div class="form-group row">
                        <label class="col-sm-4 control-label">Main Balance </label>
                        <div class="col-md-8">
                            $ <?= $balance; ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 control-label">Move To</label>
                        <div class="col-md-8">
                            <select required name="wallet" class="form-select">
                                <option value="">Select</option>
                                <option value="<?= CAMPAIGN_WALLET; ?>">Campaign Wallet</option>
                                <option value="<?= COMPOUNDING_WALLET; ?>">Componding Wallet</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 control-label">Amount <span class="required">*</span> </label>
                        <div class="col-md-8">
                            <input required class="form-control" min="0" type="number" v-model="amount" placeholder="0.00" name="amount">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-4"></div>
                        <div class="col-md-8">
                            <button class="btn btn-success" type="submit" name="save" value="Submit">Submit</button>
                            <button type="reset" class="btn btn-danger">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-sm-7">
        <div class="box">
            <div class="box-p">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Sl No</th>
                            <th>Date</th>
                            <th>Amout</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sl = 1;
                        if (is_array($arorders) && count($arorders) > 0) {
                            foreach ($arorders as $ob) {
                        ?>
                                <tr>
                                    <td><?= $sl++; ?></td>
                                    <td><?= date('d-M-Y', strtotime($ob->created)); ?></td>
                                    <td> <i class="fa fa-inr"></i> <?= $ob->amount; ?></td>
                                    <td><span class="badge badge-success">Completed</span>
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
    </div>
</div>