<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Dashboard</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Custom fonts for this template-->
    <link href="<?= theme_url('dashboard/fa/css/font-awesome.css'); ?>" rel="stylesheet" />
    <link href="<?= theme_url('dashboard/css/bootstrap.min.css'); ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/js/datatables/datatables.css'); ?>" rel="stylesheet" />
    <link href="<?= theme_url('dashboard/css/style.css'); ?>" rel="stylesheet" />
    <link href="<?= theme_url('dashboard/css/responsive.css'); ?>" rel="stylesheet" />
    <link href="<?= base_url("assets/plugins/select2/css/select2.css"); ?>" rel="stylesheet" />
    <script src="<?= theme_url('dashboard/js/jquery-3.5.1.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/datatables/datatables.min.js'); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        var ApiUrl = '<?= site_url('api/call/') ?>';
        var SiteUrl = '<?= site_url(); ?>';
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.data-table').DataTable({
                order: [],
                pageLength: 50
            });
        });

        function openNav() {
            $('#sidebar').css({
                width: 230,
            });
        }

        function closeNav() {
            $('#sidebar').css({
                width: 0,
            });
        }
    </script>
</head>

<?php

use App\Models\Setting_model;

$s = new Setting_model();
$logo = $s->get_option_value('logo');
if ($logo == '') {
    $logo = base_url('assets/img/logo.png');
}
?>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->

        <div id="sidebar" class='animated bounceInDown navbar-nav sidebar accordion side' style="background: #f77947; overflow: auto;">
            <div>
                <div style="background-color: #f77947;" class="d-flex align-items-center justify-content-center p-2" target="_blank" href="<?= site_url(); ?>">
                    <img src="<?= $logo; ?>" class="img-fluid rounded-3" width="80" />
                    <button type="button" onclick="closeNav()" class="btn-close d-block d-md-none" aria-label="Close"></button>
                </div>
                <ul class="sidebar-menu">
                    <li>
                        <a href="<?= site_url('dashboard'); ?>">
                            <i class="fa fa-dashboard"></i>
                            <span> Dashboard </span>
                        </a>
                    </li>
                    <li>
                        <a target="_blank" href="<?= site_url('signup/?ref=' . $me->username); ?>">
                            <i class="fa fa-plus-circle"></i>
                            <span> Add New Joining </span>
                        </a>
                    </li>
                    <li class='sub-menu <?= $menu == 'topup' ? 'active' : null; ?>'><a href='#settings'>
                            <i class="fa fa-user"></i>
                            Account Topup<div class='fa fa-caret-down float-right'></div></a>
                        <ul>
                            <li><a href='<?= site_url('dashboard/topup'); ?>'>Topup Account </a></li>
                            <li><a href='<?= site_url('dashboard/retopup-self'); ?>'>Retopup Self</a></li>
                            <li><a href='<?= site_url('dashboard/retopup-others'); ?>'>Retopup Others</a></li>
                            <li><a href='<?= site_url('dashboard/auto-pool'); ?>'>Auto Pool Purchase</a></li>
                            <li><a href='<?= site_url('dashboard/topup-history'); ?>'>Topup History</a></li>
                        </ul>
                    </li>
                    <li class='sub-menu <?= $menu == 'profile' ? 'active' : null; ?>'><a href=' #settings'>
                            <i class="fa fa-user"></i>
                            Profile<div class='fa fa-caret-down float-right'></div></a>
                        <ul>
                            <li><a href='<?= site_url('dashboard/edit-profile'); ?>'>Edit Profile</a></li>
                            <li><a href='<?= site_url('dashboard/change-password'); ?>'>Change Password</a></li>
                            <!-- <li><a target="_blank" href='<?= site_url('dashboard/welcome'); ?>'>Welcome Letter</a></li> 
                            <li><a href='<?= site_url('dashboard/kyc'); ?>'>KYC Edit</a></li>-->
                        </ul>
                    </li>
                    <li class="sub-menu <?= $menu == 'fund' ? 'active' : null; ?>"><a href='#settings'>
                            <i class="fa fa-code"></i>
                            Fund Management<div class='fa fa-caret-down float-right'></div></a>
                        <ul>
                            <li><a href='<?= site_url('dashboard/add-funds'); ?>'>Deposit Fund</a></li>
                            <li><a href='<?= site_url('dashboard/fund-transfer'); ?>'>Fund Transfer</a></li>
                            <li><a href='<?= site_url('dashboard/fund-transfer-history'); ?>'>Fund Transaction History</a></li>

                        </ul>
                    </li>

                    <li class="sub-menu <?= $menu == 'members' ? 'active' : null; ?>"><a href='#message'>
                            <i class="fa fa-sitemap"></i>
                            Team<div class='fa fa-caret-down float-right'></div></a>
                        <ul>
                            <li><a href='<?= site_url('dashboard/members'); ?>'>My Direct Members</a></li>
                            <li><a href='<?= site_url('dashboard/downline-members'); ?>'>My Downline </a></li>
                        </ul>
                    </li>
                    <li class="sub-menu <?= $menu == 'report' ? 'active' : null; ?>"><a href='#message'>
                            <i class="fa fa-inr"></i>
                            Income Report<div class='fa fa-caret-down float-right'></div></a>
                        <ul>
                            <li><a href="<?= site_url('dashboard/payment-history/?tab=sponsor') ?>">Direct Income</a></li>
                            <li><a href="<?= site_url('dashboard/club-income') ?>">Club Income</a></li>
                            <li><a href="<?= site_url('dashboard/auto-pool') ?>">Autopool Income</a></li>
                            <li><a href="<?= site_url('dashboard/payment-history') ?>">Transaction History</a></li>

                        </ul>
                    </li>

                    <li class="sub-menu <?= $menu == 'withdraw' ? 'active' : null; ?>"><a href='#message'>
                            <i class="fa fa-print"></i>
                            Withdrawal<div class='fa fa-caret-down float-right'></div></a>
                        <ul>
                            <li><a href='<?= site_url('dashboard/withdraw'); ?>'>Withdraw Request</a></li>
                            <li><a href='<?= site_url('dashboard/withdraw-history'); ?>'>Withdraw History</a></li>
                        </ul>
                    </li>

                    <li class="sub-menu d-none <?= $menu == 'orders' ? 'active' : null; ?>"><a href='#message'>
                            <i class="fa fa-shopping-cart"></i>
                            Orders<div class='fa fa-caret-down float-right'></div></a>
                        <ul>
                            <li><a href='<?= site_url('dashboard/order-history'); ?>'>My Orders</a></li>
                            <li><a href="<?= site_url('dashboard/products'); ?>"> View Products</a></li>
                        </ul>
                    </li>

                    <li> <a href="<?= site_url('dashboard/supports'); ?>"> <i class="fa fa-life-ring"></i> Help & Support</a> </li>

                    <li> <a href="#" target="_blank"> <i class="fa fa-life-ring"></i> Telegram </a> </li>

                    <li> <a href="<?= site_url('logout') ?>"> <i class="fa fa-sign-out"></i> Logout </a>
                </ul>
            </div>
            <div class="p-2 small text-center">

            </div>
        </div>

        <!-- End of Sidebar -->
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <?php
                $arn = array();
                ?>
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light topbar px-2 mb-4 static-top shadow" style="background: #3295CB;">
                    <div class="d-flex justify-content-between align-items-center" style="flex: 1;">
                        <div class="d-flex align-items-center">
                            <button onclick="openNav()" class="btn btn-menu btn-outline-light"> <i class="fa fa-bars"></i> </button>
                            <div class="ps-2">
                                <h5 class="text-white m-0"><?= $me->first_name . ' ' . $me->last_name; ?> </h5>
                                <div style="color: #DDD;">Userid: <?= $me->username; ?></div>
                            </div>
                        </div>
                        <div class="pe-2">
                            <?php if ($me->image != '') { ?>
                                <img width="50" height="50" class="rounded-circle" src="<?= site_url(upload_dir($me->image)); ?>" title="<?= $me->first_name . ' ' . $me->last_name ?>">
                            <?php } else { ?>
                                <img width="50" height="50" class="rounded-circle" src="<?= site_url('assets/img/avg.png'); ?>" title="<?= $me->first_name . ' ' . $me->last_name ?>">
                            <?php } ?>
                        </div>
                    </div>
                </nav>
                <div class="container-fluid">
                    <?php
                    echo view("alert");
                    echo front_view("dashboard/$main");
                    ?>
                </div>
            </div>
            <!-- End of Main Content -->
        </div>

        <!-- End of Content Wrapper -->
    </div>
    <?php
    // echo view("toast");
    ?>
    <!-- End of Page Wrapper -->
    <script src="<?= base_url('assets/js/bootstrap.min.js'); ?>"></script>
    <script type="text/javascript" src="<?= base_url("assets/plugins/select2/js/select2.min.js"); ?>"></script>
    <script>
        $(".mov-menu").click(function() {
            $(".side").slideToggle();
        });
        $(document).ready(function() {
            $('.select2').select2();
            $(".btn-copy").click(function() {
                var metxt = $(this).data('copy');
                let target = $(this).data('target');
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val(metxt).select();
                document.execCommand("copy");
                $temp.remove();
                if (target !== undefined) {
                    let html = '<span class="badge bg-dark text-white">Copy Successful</span>';
                    $(target).html(html).fadeIn(500).show().fadeOut(3000);
                } else {
                    $('.copied-msg').html("Copy Successful");
                    $('#copyid').fadeIn(500).show().fadeOut(3000);
                }

            });

            // $('.sub-menu ul').hide();
            $(".sub-menu a").click(function() {
                $(this).parent(".sub-menu").children("ul").slideToggle("100");
                $(this).find(".right").toggleClass("fa-caret-up fa-caret-down");
            });

        });
    </script>

</body>

</html>