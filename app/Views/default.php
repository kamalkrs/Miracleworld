<!DOCTYPE html>

<head>

    <title>Secure Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="<?= base_url("assets/css/bootstrap.min.css"); ?>" />
    <link rel="stylesheet" type="text/css" href="<?= base_url("assets/fa/css/font-awesome.min.css"); ?>" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@mdi/font@6.6.96/css/materialdesignicons.min.css" />
    <link rel="stylesheet" type="text/css" href="<?= base_url("assets/css/datepicker.css"); ?>" />
    <link rel="stylesheet" type="text/css" href="<?= base_url("assets/css/style.css"); ?>" />
    <link rel="stylesheet" type="text/css" href="<?= base_url("assets/plugins/data-table/datatables.min.css"); ?>" />
    <link rel="stylesheet" type="text/css" href="<?= base_url("assets/plugins/select2/css/select2.css"); ?>" />
    <script type="text/javascript" src="<?= base_url("assets/js/jquery-3.2.1.min.js"); ?>"></script>
    <script src="<?= base_url("assets/js/vue.js"); ?>"></script>
    <script>
        var ApiUrl = '<?= site_url('api/admin/') ?>';
        $(document).ready(function() {
            $('.btn-menu').click(function() {
                $('.sidebar').toggle();
            });
        });
    </script>
    <style>
        .menu li .fa {
            width: 20px;
        }
    </style>
</head>

<body>
    <div class="topbar bg-primary">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col col-sm-4">
                    <button class="btn btn-menu btn-outline-light">
                        <i class="fa fa-navicon"></i>
                        Dashboard
                    </button>
                </div>

                <div class="col col-sm-8">
                    <ul class="qmenu">
                        <li><a class="text-white" target="_blank" href="<?= site_url(); ?>"><i class="fa fa-desktop"></i> Company </a></li>
                        <li><a class="text-white" href="<?= admin_url("users/logout"); ?>"><i class="fa fa-sign-out"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?php
    $db = db_connect();
    $bc  = $db->table("admin");
    $u = $bc->getWhere(['id' => 1])->getRow();
    ?>
    <div class="main-outer">
        <div class="sidebar">
            <div class="userinfo bg-white">
                <?php
                $file = base_url('assets/img/avg.png');
                if ($u->avatar != '') {
                    $file = base_url(upload_dir($u->avatar));
                }
                ?>
                <img src="<?= $file; ?>" class="img-fluid circle" />
                <div class="user-details text-dark">
                    Welcome <b><?= $u->first_name; ?></b> <br />
                    <small><?php echo date("jS M, h:i A"); ?></small><br />
                    <a href="<?= admin_url("users/logout"); ?>" class="btn btn-light btn-logout">Logout <span class="fa fa-sign-out"></span></a>
                </div>
            </div>
            <ul class="menu">
                <li><a href="<?= admin_url(); ?>"><i class="fa fa-home"></i> Dashboard </a></li>
                <!-- <li class="has-submenu <?= $menu == 'catalog' ? 'active' : null; ?>"><a href="#"><i class="fa fa-list"></i> Catalog<span class="fa fa-angle-right"></span></a></a>
                    <ul>
                        <li><a href="<?= admin_url('categories'); ?>"><span class="fa fa-angle-right"></span>Categories</a></li>
                        <li><a href="<?= admin_url('products'); ?>"><span class="fa fa-angle-right"></span>Products</a></li>
                    </ul>
                </li> -->
                <li class="has-submenu <?= $menu == 'members' ? 'active' : null; ?>"><a href="#"><i class="fa fa-youtube"></i> Member History<span class="fa fa-angle-right"></span></a></a>
                    <ul>
                        <li><a href="<?= admin_url('members'); ?>"><span class="fa fa-angle-right"></span>All Members</a></li>
                        <li><a href="<?= admin_url('members/?filter=today'); ?>"><span class="fa fa-angle-right"></span>Today's Registered User</a></li>
                    </ul>
                </li>
                <li class="has-submenu <?= $menu == 'fund' ? 'active' : null; ?>"><a href="#"><i class="fa fa-dashboard"></i>Fund Management<span class="fa fa-angle-right"></span></a>
                    <ul>
                        <li><a href="<?= admin_url("payments/payment-history/?status=0"); ?>"><span class="fa fa-angle-right"></span>Deposit Pending</a></li>
                        <li><a href="<?= admin_url("payments/payment-history"); ?>"><span class="fa fa-angle-right"></span>Deposit History</a></li>
                        <li><a href="<?= admin_url('payout/manage'); ?>"><span class="fa fa-angle-right"></span>Debit/Credit Balance</a></li>
                        <li><a href="<?= admin_url('payout/drcr-report'); ?>"><span class="fa fa-angle-right"></span>Debit/Credit Report</a></li>
                    </ul>
                </li>

                <li class="has-submenu <?= $menu == 'payout' ? 'active' : null; ?>"><a href="#"><i class="fa fa-youtube"></i> Withdrawal Report <span class="fa fa-angle-right"></span></a></a>
                    <ul>
                        <li><a href="<?= admin_url('payout/withdrawal/?new=yes'); ?>"><span class="fa fa-angle-right"></span>Withdrawal Request</a></li>
                        <li><a href="<?= admin_url('payout/withdrawal'); ?>"><span class="fa fa-angle-right"></span>Withdrawal History</a></li>
                    </ul>
                </li>
                <!-- <li class="has-submenu <?= $menu == 'plans' ? 'active' : null; ?>"><a href="#"><i class="fa fa-dashboard"></i>Plans<span class="fa fa-angle-right"></span></a>
                    <ul>
                        <li><a href="<?= admin_url("plans"); ?>"><span class="fa fa-angle-right"></span>All Plans</a></li>
                        <li><a href="<?= admin_url("plans/add"); ?>"><span class="fa fa-angle-right"></span>Add New Plan</a></li>
                    </ul>
                </li> -->
                <li class="has-submenu <?= $menu == 'cms' ? 'active' : null; ?>"><a href="#"><i class="fa fa-dashboard"></i>CMS<span class="fa fa-angle-right"></span></a>
                    <ul>
                        <li><a href="<?= admin_url("posts/?type=announcement"); ?>"><span class="fa fa-angle-right"></span>Announcement </a></li>
                    </ul>
                </li>
                <li class="has-submenu <?= $menu == 'supports' ? 'active' : null; ?>"><a href="#"><i class="fa fa-dashboard"></i>Support<span class="fa fa-angle-right"></span></a>
                    <ul>
                        <li><a href="<?= admin_url("supports/?new=yes"); ?>"><span class="fa fa-angle-right"></span>Pending Tickets</a></li>
                        <li><a href="<?= admin_url("supports"); ?>"><span class="fa fa-angle-right"></span>Ticket History</a></li>
                    </ul>
                </li>
                <li class="has-submenu <?= $menu == 'settings' ? 'active' : null; ?>"><a href="#"><i class="fa fa-wrench"></i> Settings<span class="fa fa-angle-right"></span></a>
                    <ul>
                        <li><a href="<?= admin_url("settings/edit-profile"); ?>"><span class="fa fa-angle-right"></span>Edit Profile</a></li>
                        <li><a href="<?= admin_url("settings"); ?>"><span class="fa fa-angle-right"></span>General Settings</a></li>
                        <li><a href="<?= admin_url("settings/changepass"); ?>"><span class="fa fa-angle-right"></span>Change Password </a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="main">
            <?= view("alert"); ?>
            <?= view($main); ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script type="text/javascript" src="<?= base_url("assets/js/datepicker.js"); ?>"></script>
    <script type="text/javascript" src="<?= base_url("assets/js/editors/ckeditor.js"); ?>"></script>
    <script type="text/javascript" src="<?= base_url("assets/plugins/data-table/datatables.min.js"); ?>"></script>
    <script type="text/javascript" src="<?= base_url("assets/plugins/select2/js/select2.min.js"); ?>"></script>
    <script type="text/javascript" src="<?= base_url("assets/js/custom.js"); ?>"></script>
    <script>
        CKEDITOR.replace('.ckeditor');
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $(".has-submenu > a").click(function(e) {
                e.preventDefault();
                $(this).parent().children("ul").slideToggle("slow");
            });
            $('.data-table').DataTable({
                "order": [],
                "pageLength": 50
            });

            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd'
            });
            $('a.delete, .btn-delete').click(function() {
                if (!confirm('Are you sure to delete?'))
                    return false;
            });

            $('.btn-confirm').click(function() {
                var msg = $(this).data('msg');
                if (!confirm(msg))
                    return false;
            });
            $('.select2').select2();
            $(".btn-copy").click(function() {
                var metxt = $(this).data('copy');
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val(metxt).select();
                document.execCommand("copy");
                $temp.remove();
                $(this).html('<i class="fa fa-copy"></i> COPIED');
            });
        });
    </script>
</body>

</html>