<div class="header-back">
    <a href="<?= site_url('dashboard/accounts') ?>">
        <i class="fa fa-chevron-left"></i> My Ads Records
    </a>
</div>
<div class="hgradiant p-3 position-relative logo-box footer-margin">
    <div class="bg-3 p-3 bg-white rounded mybox">
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Ads Info</th>
                        <th>Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sl = 1;
                    $db = db_connect();
                    foreach ($list as $ad) {
                        $p = $db->table('products')->getWhere(['id' => $ad->product_id])->getRow();
                    ?>
                        <tr>
                            <td><?= $sl++; ?></td>
                            <td>
                                <?= $p->ptitle; ?>
                            </td>
                            <td><?= date("d M Y", strtotime($ad->start_time)); ?></td>
                            <td><?= date("h:i:s A", strtotime($ad->start_time)); ?></td>
                            <td><?= date("h:i:s A", strtotime($ad->end_time)); ?></td>
                            <td>
                                <?php
                                if ($ad->status == 1) {
                                ?>
                                    <span id="timeleft<?= $ad->id; ?>" data-target="timeleft<?= $ad->id; ?>" class="timeleft" data-et="<?= date("M d, Y H:i:s", strtotime($ad->end_time)); ?>"></span>
                                <?php
                                } else {
                                    echo '<span class="badge bg-dark">Completed</span>';
                                }
                                ?>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mb-5"></div>

<script>
    $(document).ready(function() {

        const LoadAll = function() {
            $('.timeleft').each(function(i, ob) {
                let dt = $(ob).data('et');
                let tr = $(ob).data("target");
                clockStart(dt, tr);
            })
        }

        const clockStart = (time, element) => {
            console.log(time);
            // let countDownDate = new Date("Jul 29, 2022 14:48:55").getTime();
            let countDownDate = new Date(time).getTime();

            // Update the count down every 1 second
            let x = setInterval(function() {

                // Get today's date and time
                let now = new Date().getTime();

                // Find the distance between now and the count down date
                let distance = countDownDate - now;

                // Time calculations for days, hours, minutes and seconds
                let days = Math.floor(distance / (1000 * 60 * 60 * 24));
                let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                let seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Output the result in an element with id="demo"
                document.getElementById(element).innerHTML = '<span class="badge bg-success">' + hours + "h " + minutes + "m " + seconds + "s " + '</span>';

                // If the count down is over, write some text 
                if (distance < 0) {
                    clearInterval(x);
                    document.getElementById(element).innerHTML = '<span class="badge bg-danger">Completed</span>';

                    let url = '<?= site_url('home/cron_payout') ?>';
                    fetch(url);
                }
            }, 1000);
        }
        LoadAll();
    })
</script>