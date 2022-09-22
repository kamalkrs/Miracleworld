<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class AppConfig extends BaseConfig
{
    public $themes = "origin";
    public $siteName = "Miracle World";
    public $admin_view_folder = '';
    public $upload_dir = "public/uploads/";
    public $user_prefix = "MR";
    public $minWithdrawLimit = 5; //
    public $minDeposit = 27; //
    public $minTransfer = 1;
    public $maxDeposit = 50000;
    public $useRandomId = false;
    public $directIncome = 5; // In Percent
    public $matchingBonus = 5; // In Percent
    public $joiningAmount = 27;

    public $emailFromName = "Miracle World";
    public $emailFrom = "alert@miraccleworld.com";
    public $emailHost = "mail.miraccleworld.com";
    public $emailPort = "465";
    public $emailUsername = "alert@miraccleworld.com";
    public $emailPassword = "Hello@12321";


    public $contactEmail = "info@miraccleworld.com";
    public $contactMobile = "9090909090";

    public $levelIncomes = [];


    public $tronWallet = '';
    public const TRON_PRO_API_KEY = '';

    public const PAYKASSA_MRECHANT_ID = '18439';
    public const PAYKASSA_MERCHANT_PASSWORD = 'UiHL9VD6VURWFZzMSq0rp4FozHdNogiG';

    public const PAYKASSA_API_ID = '20109';
    public const PAYKASSA_API_PASSWORD = 'YHSeimB6MTm0i0SXOCnTXUF6LWjVmKW1';
}
