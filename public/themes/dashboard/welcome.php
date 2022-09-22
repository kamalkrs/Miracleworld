<html>

<head>
    <title>Welcome Letter from </title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <style>
        body {
            font-size: 12px;
        }

        .text-warning {
            color: #ff9700 !important;
        }

        .welcome-latter {
            padding: 30px 0px;
            font-family: 'Roboto', sans-serif;
            font-weight: 300;
        }

        .letter {
            padding: 30px 20px;
            width: 80%;
            margin: 0px auto;
            height: auto;
            border: 10px solid transparent;
            border-image: url("<?= theme_url('dashboard/img/border.png') ?>") 30 round;
        }

        .letter h1 {
            font-size: 50px;
            margin-bottom: 30px;
        }

        .center_text1 .text_wrap1a {
            text-align: left;
            font-size: 26px;
            font-weight: 600;
        }

        .center_text1 .text_wrap1b {
            text-align: left;
            padding-left: 0px;
            font-size: 17px;
            line-height: 3px;

        }

        .center_text2 .text_wrap2a {
            font-size: 20px;
            padding-top: 20px;
            padding-left: 3px;
        }

        tr.colr_box1 {
            color: #0f0f0f;
            font-size: 17px;
        }

        .mem_det {
            background-color: #cccac8;

        }

        .botoom_text .btm_texta {
            font-size: 20px;
            margin-bottom: 20px;
        }

        .btm_textb {
            font-size: 20px;
        }

        .botoom_text h3 {
            font-size: 21px;
            padding-top: 16px;
        }

        .btm_textc {
            margin-top: 40px;
            font-size: 17px;
        }

        .web_text {
            margin-top: 15px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 0px;
        }

        .eml_text {
            text-align: justify;
            font-size: 14px;
            line-height: 1;
            font-weight: 400;
            font-family: 'Vollkorn', serif;
        }

        .logo-img img {
            width: 160px;
        }

        @media screen and (max-width: 768px) {

            .center_text2 .text_wrap2a,
            .botoom_text .btm_texta,
            .btm_textc,
            .btm_textb {
                font-size: 14px;
            }

            .logo-img img {
                width: 160px;
            }

            .letter {
                padding: 0;
                width: 100%;
            }

            tr.colr_box1 {
                font-size: 11px;
            }

            .table td {
                padding: 2px !important;
            }
        }
    </style>
</head>

<body>
    <section class="welcome-latter">
        <div class="container-fluid">
            <div class="letter">
                <div class="text-center logo-img"><img src="" width="140" class="img-fluid" /></div>
                <div class="center_text1">
                    <p class="text_wrap1a"> Congrats !</p>
                    <p class="text_wrap1b"><?= $user->first_name . ' ' . $user->last_name; ?></p>
                </div>

                <div class="center_text2">
                    <p class="text_wrap2a">On joining with {{ }} You have taken
                        a wise decision towards development and fulfillment of
                        your life's prosperity and dreams.Your joining details are as under:</p>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="border border-info mb-3">
                            <div class="text-center border-bottom border-info p-2">
                                <h6 class="p-0 m-0"><b class="text-success">Membership Details</b></h6>
                            </div>
                            <div class="p-2">
                                <div class="row">
                                    <div class="col-6 col-sm-4"><b class="text-warning">MEMBER ID</b></div>
                                    <div class="col-6 col-sm-8"><?= $user->username; ?></div>
                                </div>
                            </div>
                            <div class="p-2 border-top border-info">
                                <div class="row">
                                    <div class="col-6 col-sm-4"><b class="text-warning">SPONSOR ID</b></div>
                                    <div class="col-6 col-sm-8"><?= id2userid($user->spil_id); ?></div>
                                </div>
                            </div>
                            <div class="border-top border-info"></div>
                            <div class="p-2">
                                <div class="row">
                                    <div class="col-6 col-sm-4"><b class="text-warning">DATE OF JOINING</b></div>
                                    <div class="col-6 col-sm-8"><?= date('d-M-Y', strtotime($user->join_date)); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="botoom_text">
                    <p class="btm_texta">
                        Note:- Being our member,you accept all terms and conditions of membership and will abide by the same as a member,You bear all responsibilities of your information provide on <?= site_url(); ?>.
                    </p>
                    <p class="btm_textb">
                        Your little but dedicated effort will lead you towards
                        success.We wish you a great future ahead. </p>

                    <h3>Company name</h3>

                    <div class="botom2_text">
                        <p class="web_text">Web: <?= site_url(); ?></p>
                        <p class="eml_text">Email: Email id</p>
                        <p class="btm_textc">(The piece of information is computer generated
                            and does not require seal of the company.)</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

</body>

</html>