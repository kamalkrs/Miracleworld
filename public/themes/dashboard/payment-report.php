<?php

use App\Models\Dashboard_model;
use App\Models\Setting_model;
use App\Models\User_model;

$config = new \Config\AppConfig;
$user_id = user_id();

$us = new User_model(user_id());
$fv = $us->getFundBalance(user_id());
$cmpd_inc = $us->getCompoudingIncome(user_id());
$camp_inc = $us->getIncomeByType(user_id(), Dashboard_model::INCOME_ADS);
$spincome = $us->getIncomeByType($user_id, Dashboard_model::INCOME_SPONSOR);
$lvlincome = $us->getIncomeByType($user_id, Dashboard_model::INCOME_LEVEL);
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<div class="header-back">
    <a href="<?= site_url('dashboard') ?>">
        <i class="fa fa-chevron-left"></i> Income
    </a>
</div>
<div class="hgradiant p-3 position-relative logo-box" style="margin-bottom: 50px;">
    <div class="home-slider bg-white p-2 mb-3  rounded position-relative">
        <div class="d-flex justify-content-around align-items-center">
            <div class="im1">
                <img src="<?= theme_url('dashboard/icons/k.png'); ?>" alt="" width="160">
            </div>
            <div class="im2">
                <img src="<?= theme_url('dashboard/icons/logo-dark.png'); ?>" alt="" width="100">
            </div>
        </div>
    </div>
</div>
<div class="px-3">
    <div class="bg-3 p-3 bg-white rounded mybox">
        <h3>Income Details</h3>
        <div class="d-flex justify-content-center align-items-center">
            <canvas id="myChart" width="240" height="240"></canvas>
            <script>
                const ctx = document.getElementById('myChart').getContext('2d');
                const myChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: ['Campaign Income', 'Direct Sponsor', 'Level Income', 'Compounding'],
                        datasets: [{
                            label: '# of Votes',
                            data: [<?= $camp_inc; ?>, <?= $spincome; ?>, <?= $lvlincome; ?>, <?= $cmpd_inc; ?>],
                            backgroundColor: [
                                '#03c3f6',
                                '#e15d2f',
                                '#4c6fc5',
                                '#b8a8d2'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: false,
                                text: ''
                            }
                        }
                    },
                });
            </script>
        </div>
    </div>
    <div class="bg-white income-report-home p-3 rounded mybox footer-margin">
        <h3>Income Report</h3>
        <div class="row g-1">
            <div class="col-6">
                <a href="<?= site_url('dashboard/income-report/?tab=campaign') ?>">
                    <div class="p-3 text-center widget-box rounded-1 box-grad1 text-white">
                        <img src="<?= theme_url('dashboard/icons/campaign-income.png'); ?>" width="30" />
                        <h6>Campaign Income</h6>
                        <div>$ <?= $campaign_income; ?></div>
                    </div>
                </a>
            </div>
            <div class="col-6">
                <a href="<?= site_url('dashboard/income-report/?tab=sponsor') ?>">
                    <div class="p-3 text-center widget-box rounded-1 box-grad1 text-white">
                        <img src="<?= theme_url('dashboard/icons/direct-sponsor.png'); ?>" width="30" />
                        <h6>Direct Sponsor</h6>
                        <div>$ <?= $sponsor_income; ?></div>
                    </div>
                </a>
            </div>
            <div class="col-6">
                <a href="<?= site_url('dashboard/income-report/?tab=compounding') ?>">
                    <div class="p-3 text-center widget-box box-grad1 rounded-1 text-white">
                        <img src="<?= theme_url('dashboard/icons/compounding.png'); ?>" width="30" />
                        <h6>Compounding Income</h6>
                        <div>$ <?= $cmpd_inc; ?></div>
                    </div>
                </a>
            </div>
            <div class="col-6">
                <a href="<?= site_url('dashboard/income-report/?tab=level') ?>">
                    <div class="p-3 text-center widget-box box-grad1 rounded-1 text-white">
                        <img src="<?= theme_url('dashboard/icons/level-income.png'); ?>" width="30" />
                        <h6>Level Income</h6>
                        <div>$ <?= $level_income; ?></div>
                    </div>
                </a>
            </div>
            <div class="col-6">
                <div class="p-3 text-center box-grad1 text-white widget-box rounded-1">
                    <img src="<?= theme_url('dashboard/icons/total-income.png'); ?>" width="30" />
                    <h6>Total Income</h6>
                    <div>$ <?= $total_income; ?></div>
                </div>
            </div>
            <div class="col-6">
                <div class="p-3 text-center box-grad1 widget-box text-white rounded-1">
                    <img src="<?= theme_url('dashboard/icons/total-withdrawal.png'); ?>" width="30" />
                    <h6>Total Withdrawal</h6>
                    <div>$ <?= $total_withdrawal; ?></div>
                </div>
            </div>
        </div>
    </div>
</div>