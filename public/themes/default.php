<!doctype html>
<html lang="en" class="no-js">

<head>
    <title>Miracle World - Business crowdfunding platform</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="<?= theme_url('img/logo.png'); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&amp;family=Libre+Franklin:ital,wght@0,400;0,500;0,700;1,400" rel="stylesheet">
    <link rel="stylesheet" href="<?= theme_url() ?>/assets/css/materialdesignicons.css">
    <link rel="stylesheet" href="<?= theme_url() ?>/assets/css/bootstrap-grid.css">
    <link rel="stylesheet" href="<?= theme_url() ?>/assets/css/fancybox.css">
    <link rel="stylesheet" href="<?= theme_url() ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= theme_url() ?>/style.css">

    <script src="<?= theme_url('js/jquery-3.6.0.min.js'); ?>"></script>
    <script src="<?= theme_url('js/vue.min.js'); ?>"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        var ApiUrl = '<?= site_url('api/call/') ?>';
    </script>

</head>

<body class="home navbar-sticky">
    <a href="#content" class="skip-link screen-reader-text">Skip to the content</a>
    <header id="top-header" class="site-header">
        <div class="container">
            <div class="site-identity">
                <a href="<?= site_url() ?>" class="site-title"><img src="<?= theme_url('img/logo.png') ?>" class="custom-logo" width="80" height="80" alt="CrowdInvest"></a>
                <p class="site-description screen-reader-text">Business crowdfunding platform</p>
            </div>

            <nav class="main-navigation">
                <ul id="menu-main" class="menu">
                    <li><a href="<?= site_url() ?>">Home</a></li>
                    <?php

                    use App\Models\User_model;

                    if (is_login()) {
                        $us = User_model::create(user_id());
                        $name = $us->first_name . ' ' . $us->last_name;
                        $bal = $us->getFundBalance();
                    ?>
                        <li class="menu-account menu-item-has-children">
                            <a href="#"><em class="mdi mdi-account-outline"></em> <span class="account-details"><span class="account-name"><?= $name; ?></span> <span class="account-balance"><em class="screen-reader-text">Total balance: </em><strong class="account-balance-amount"><?= $bal; ?></strong></span></span><em class="mdi mdi-chevron-down"></em></a>
                            <ul class="sub-menu">
                                <li><a href="<?= site_url('dashboard') ?>">Overview</a></li>
                                <li><a href="<?= site_url('dashboard/members') ?>">Teams</a></li>
                                <li><a href="<?= site_url('dashboard/edit-profile') ?>">Profile</a></li>
                                <li><a href="<?= site_url('logout') ?>">Logout</a></li>
                            </ul>
                        </li>
                    <?php
                    } else {
                    ?>
                        <li><a class="button button-primary button-filled" href="<?= site_url('login') ?>">Login</a></li>
                    <?php
                    }
                    ?>

                </ul>
            </nav>
            <div id="menu-toggle">
                <a href="#menu-main" title="Toggle menu">
                    <em class="mdi mdi-menu"></em><em class="mdi mdi-close"></em>
                    <span class="screen-reader-text">Menu</span>
                </a>
            </div>
        </div>
    </header>

    <?= front_view($main) ?>

    <footer class="site-footer">
        <a class="to-the-top" href="#top-header" title="To the top"><em class="mdi mdi-chevron-double-up"></em><svg width="150" height="50" viewBox="0 0 39.687499 13.229167">
                <path d="M-.0035 296.999c9.007 0 11.8302-13.1833 19.8324-13.1852C27.8372 283.8118 30.636 297 39.683 297c9.047 0-48.6935-.001-39.6864-.001z" transform="translate(0,-283.77081)" />
            </svg></a>
        <div class="container">
            <div class="widget-area">
                <div class="widget widget_text widget_logo">
                    <h4 class="widget-title"><img src="<?= theme_url() ?>/assets/images/logo-footer.svg" width="209" height="18" alt="CrowdInvest"><span class="sr-only">Miracle World</span></h4>
                    <div class="textwidget">
                        <p>We offer an extensive range of professional services and a high degree of specialization. We serves both private &amp; public companies and bring over 35 years of experience.</p>
                        <nav class="social-navigation">
                            <ul id="social-menu" class="menu">
                                <li><a href="https://linkedin.com/"><span class="screen-reader-text">Instagram profile</span></a></li>
                                <li><a href="https://facebook.com/"><span class="screen-reader-text">Facebook profile</span></a></li>
                                <li><a href="https://twitter.com/"><span class="screen-reader-text">Twitter profile</span></a></li>
                                <li><a href="https://youtube.com/"><span class="screen-reader-text">YouTube page</span></a></li>
                                <li><a href="https://instagram.com/"><span class="screen-reader-text">Instagram profile</span></a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="widget widget_text">
                    <h4 class="widget-title">Contact <span class="low-opacity">Us</span></h4>
                    <div class="textwidget">
                        <p>
                            <img class="office-country" src="<?= theme_url() ?>/assets/images/lv.svg" width="24" alt=""> <strong>HQ, Latvia:</strong> Str. 13, Rīga, +371 6891 1199
                            <br>
                            <img class="office-country" src="<?= theme_url() ?>/assets/images/ee.svg" width="24" alt=""> <strong>Estonia:</strong> Str. 2, Tallinn, +372 6891 1199
                            <br>
                            <img class="office-country" src="<?= theme_url() ?>/assets/images/lt.svg" width="24" alt=""> <strong>Lithuania:</strong> Str. 4, Vilnius, +370 6891 1199
                        </p>
                        <p>
                            <strong>Company:</strong> CrowdInvest OÜ
                            <br>
                            <strong>Registration Nr.:</strong> 13472495
                            <br>
                            <strong>Contact:</strong> <a href="mailto:contact@crowdinvest.com">contact@crowdinvest.com</a>
                        </p>
                    </div>
                </div>
                <div class="widget widget_nav_menu">
                    <h4 class="widget-title">Explore <span class="low-opacity">Site</span></h4>
                    <nav class="footer-useful-links">
                        <ul class="menu">
                            <li><a href="projects.html">Invest</a></li>
                            <li><a href="about.html">About Us</a></li>
                            <li><a href="careers.html">Careers</a></li>
                            <li><a href="news.html">News</a></li>
                            <li><a href="statistics.html">Statistics</a></li>
                            <li><a href="styleguide.html">Styleguide</a></li>
                        </ul>
                        <ul class="menu">
                            <li><a href="help.html">How To Invest</a></li>
                            <li><a href="help.html">Get Funding</a></li>
                            <li><a href="statistics.html">Reports</a></li>
                            <li><a href="affiliate.html">Affiliate Program</a></li>
                            <li><a href="contact.html">Contact Us</a></li>
                            <li><a href="help.html">Privacy Policy</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
            <aside class="site-footer-bottom">
                <div class="site-footer-credits">&copy; 2022 MiracleWorld. All Rights Reserved.</div>
                <div class="site-footer-cookies">By using this website you agree to the <a href="#">use of cookies</a> in accordance with the cookies policy.</div>
            </aside>
        </div>
    </footer>

    <script src="<?= theme_url() ?>/assets/js/modernizr-custom.js"></script>
    <script src="<?= theme_url() ?>/assets/js/jquery-3.6.0.min.js"></script>
    <script src="<?= theme_url() ?>/assets/js/fancybox.min.js"></script>
    <script src="<?= theme_url() ?>/assets/js/functions.js"></script>
</body>

</html>