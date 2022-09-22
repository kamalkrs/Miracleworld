<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $setting->get_option_value('title'); ?> - Grow your business</title>
    <link rel="shortcut icon" type="image/png" href="<?= $setting->get_option_value('favi_icon'); ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <link href="<?= theme_url() ?>/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= theme_url() ?>/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Oxygen:400,300,700' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Rufina:400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?= base_url("assets/plugins/select2/css/select2.css"); ?>">
    <link rel="stylesheet" href="<?= base_url("assets/plugins/select2/select2-bootstrap.min.css"); ?>">
    <link rel="stylesheet" href="<?= theme_url() ?>/style.css">
    <script src="<?= theme_url('js/jquery-3.6.0.min.js'); ?>"></script>
    <script src="<?= theme_url('js/vue.min.js'); ?>"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        var ApiUrl = '<?= site_url('api/call/') ?>';
    </script>

    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v14.0&appId=527577081990974&autoLogAppEvents=1" nonce="iYTYtBGn"></script>

</head>

<body>
    <div class="mview">
        <div class="mview-body">
            <?php
            echo front_view("$main");
            ?>
        </div>
        <?php
        // echo view("toast");
        ?>
    </div>
</body>


</html>