<h5>Fund Request</h5>
<hr />

<div class="row">
    <div class="col-sm-5">
        <div class="box">
            <div class="box-p">
                <form enctype="multipart/form-data" class="form-horizontal" method="POST" action="<?= site_url('dashboard/fund-request'); ?>">
                    <div class="form-group row">
                        <label class="col-sm-4 control-label">Request Amount <span class="required">*</span> </label>
                        <div class="col-md-8">
                            <input class="form-control" type="number" placeholder="0.00" name="form[amount]">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 control-label">Transaction Date <span class="required">*</span></label>
                        <div class="col-sm-7">
                            <input class="form-control" type="date" max="<?= date("Y-m-d"); ?>" placeholder="Date" name="form[fdate]">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 control-label">Screenshot <span class="required">*</span></label>
                        <div class="col-sm-7">
                            <input name="screenshot" type="file">
                            <small>Only JPG/JPEG/PNG Supported</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 control-label">Extra Notes</label>
                        <div class="col-sm-7">
                            <input class="form-control" type="text" placeholder="Write small optional notes" name="form[notes]">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4"></div>
                        <div class="col-md-8">
                            <button class="btn btn-success" type="submit" value="Submit" name="submit">Submit</button>
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
                                    <td><?= $ob->fdate . ' ' . $ob->ftime; ?></td>
                                    <td> <i class="fa fa-inr"></i> <?= $ob->amount; ?></td>
                                    <td><?php
                                        if ($ob->status == 0) {
                                            echo '<span class="badge bg-info">Pending</span>';
                                        } else if ($ob->status == 1) {
                                            echo '<span class="badge bg-success">Approved</span>';
                                        } else if ($ob->status == 2) {
                                            echo '<span class="badge bg-danger">Rejected</span>';
                                        }
                                        ?>
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