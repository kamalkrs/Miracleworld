<div class="header-back">
    <a href="<?= site_url('dashboard') ?>">
        <i class="fa fa-chevron-left"></i> Refer & Earn
    </a>
</div>
<div class="hgradiant p-3 position-relative logo-box footer-margin">
    <div class="bg-3 p-3 bg-white rounded mybox">
        <h3>Refer & Earn</h3>
        <div class="says mb-0">
            <div class="hgradiant sic">
                <img src="<?= theme_url('dashboard/icons/ui.png'); ?>" width="30" />
            </div>
            <div class="px-0" style="flex: 1;">
                <input type="text" value="https://www.cryptoads.uk/signup/?ref=<?= $me->username; ?>" class="form-control input-invite">
            </div>
            <div class="hgradiant sic">
                <img class="btn-copy" data-copy="https://www.cryptoads.uk/signup/?ref=<?= $me->username; ?>" src="<?= theme_url('dashboard/icons/cc.png'); ?>" width="30" />
            </div>
        </div>
        <div id="target" class="text-end small"></div>
    </div>
</div>