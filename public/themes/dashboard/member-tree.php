<?php

use App\Models\User_model;

$db = db_connect();
$session = session();
$user = new User_model();

$user_id = isset($_GET['user']) ? $_GET['user'] : user_id();
if (intval($user_id) == 0) {
    $user_id = user_id();
}

$mychild = $user->getDownloadLineIds(user_id());
if (isset($_GET['user'])) {
    if (!in_array($_GET['user'], $mychild)) {
        $session->markAsFlashdata('error', 'You are not authorized to see the tree');
        header('Location:dashboard/member-tree');
    }
}

$tree = $user->getDirectChilds($user_id);

$left = 0;
$middle = 0;
$right = 0;
$active_left = 0;
$active_middle = 0;
$active_right = 0;

if ($tree->left) {
    $left = $user->getDownloadLineIds($tree->left);
    $builder = $db->table('users');
    foreach ($left as $li) {
        $ch = $builder->getWhere(array('id' => $li))->getRow();
        if ($ch->ac_status) {
            $active_left++;
        }
    }
    $left = count($left) + 1;
}

if ($tree->right) {
    $right = $user->getDownloadLineIds($tree->right);
    $builder = $db->table('users');
    foreach ($right as $li) {
        $ch = $builder->getWhere(array('id' => $li))->getRow();
        if ($ch->ac_status) {
            $active_right++;
        }
    }
    $right = count($right) + 1;
}
$builder = $db->table("users");
if ($tree->left) {
    $chk_left = $builder->getWhere(array('id' => $tree->left))->getRow();
    if ($chk_left->ac_status == 1) {
        $active_left = $active_left + 1;
    }
}

if ($tree->right) {
    $chk_right = $builder->getWhere(array('id' => $tree->right))->getRow();
    if ($chk_right->ac_status == 1) {
        $active_right = $active_right + 1;
    }
}

$ids = $user->getTree($user_id);
function user_box($user)
{
?>
    <div class="userinfo">
        <?php
        $link = $user->id != null ? site_url('dashboard/member-tree/?user=' . $user->id) : '#';
        ?>
        <a href="<?= $link; ?>"><img class="img-profile rounded-circle" src="<?= theme_url('img/' . $user->image); ?>" title=""></a>
        <div class="text-center img-name" style="height: 65px;"><span class="small"><?= $user->name; ?> <br />(<?= $user->username; ?>)</span></div>
        <div class="userdetails">
            <div class="us-row">
                <span>Sponsor ID: </span>
                <span><?= $user->sponsor_id; ?></span>
            </div>
            <div class="us-row">
                <span>Placement ID: </span>
                <span><?= $user->placement_id; ?></span>
            </div>
            <div class="us-row">
                <span>Username: </span>
                <span><?= $user->username; ?></span>
            </div>
            <div class="us-row">
                <span>Name: </span>
                <span><?= $user->name; ?></span>
            </div>
            <div class="us-row">
                <span>Designation: </span>
                <span><?= $user->designation; ?></span>
            </div>
            <div class="us-row">
                <span>DOJ: </span>
                <span><?= $user->doj; ?></span>
            </div>
            <!-- <div class="us-row">
                <span>Topup: </span>
                <span><?= $user->dot; ?></span>
            </div> -->
            <!-- <div class="us-row">
                <span>Package: </span>
                <span><?= $user->plan; ?></span>
            </div> -->
            <!-- <div class="us-row">
                <span>Matching: </span>
                <span><?= $user->matching; ?></span>
            </div> -->
        </div>
    </div>
<?php
}
?>
<style>
    .userinfo {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
    }

    .userinfo img {
        cursor: pointer;
    }

    .userdetails {
        background: #60B81E;
        color: #fff;
        box-shadow: 2px 2px 2px #DDD;
        position: absolute;
        width: 200px;
        font-size: 11px;
        top: 60px;
        z-index: 99;
        display: none;
    }

    .userdetails-sm .userdetails {
        width: 140px;
    }

    .userdetails::before {
        content: '';
        position: absolute;
        top: -10px;
        border-left: solid 10px transparent;
        border-right: solid 10px transparent;
        border-bottom: solid 10px #444;
        width: 10px;
        height: 10px;
        left: 45%;

    }

    .userinfo:hover .userdetails {
        display: block;
    }

    .us-row {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        padding: 5px;
        border-bottom: dotted 1px #666;
    }

    .userdetails .us-row:last-child {
        border: none;
    }

    .divhr {
        height: 30px;
        width: 2px;
        margin: 5px auto 15px;
        background-color: #222;
    }

    .last-row .divhr {
        display: none;
    }

    .divr {
        height: 2px;
        background-color: #222;
        margin: 0 auto 10px;
    }

    .box-1 {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }

    .img-profile {
        width: 50px;
        height: 50px;
    }

    @media screen and (max-width: 768px) {
        .img-profile {
            width: 30px;
            height: 30px;
        }

        .img-name .small {
            font-size: 12px;
        }
    }
</style>
<div id="search" class="box box-p">
    <div class="row">
        <div class="col-9">
            <div class="d-flex align-items-center">
                <h5 class="m-0">Search: </h5>
                <input type="search" v-on:keyup.enter="doSearch" v-model="username" class="form-control col-sm-3 mx-2" style="width: 240px;">
                <button type="button" v-on:click="doSearch()" class="btn btn-primary">Search </button>
            </div>
        </div>
        <div class="col-3">
            <div>
                <div><span class="text-primary" style="width: 80px; display: inline-block;">LEFT</span> : &nbsp; <?= $active_left . '/' . $left; ?></div>
                <div><span class="text-primary" style="width: 80px; display: inline-block;">RIGHT</span> : &nbsp; <?= $active_right . '/' . $right; ?></div>
            </div>
        </div>
    </div>



</div>
<script>
    var vm = new Vue({
        el: '#search',
        data: {
            username: '<?= $user_id; ?>'
        },
        methods: {
            doSearch: function(e) {
                let url = '<?= site_url('dashboard/member-tree/?user=') ?>' + this.username;
                window.location = url;
            }
        }
    });
</script>

<div class="container" style="width: 1050px; overflow: auto;">
    <div class="d-flex justify-content-center">
        <?php user_box($ids[0]); ?>
    </div>
    <div class="divr" style="width: 50%;"></div>
    <div class="d-flex justify-content-around">
        <div><?php user_box($ids[1]); ?></div>
        <div><?php user_box($ids[2]); ?></div>
    </div>
    <div class="d-flex justify-content-around">
        <div class="divr" style="width: 25%;"></div>
        <div class="divr" style="width: 25%;"></div>
    </div>
    <div class="d-flex justify-content-around">
        <div><?php user_box($ids[3]); ?></div>
        <div><?php user_box($ids[4]); ?></div>
        <div><?php user_box($ids[5]); ?></div>
        <div><?php user_box($ids[6]); ?></div>
    </div>
    <div class="d-flex justify-content-around">
        <div class="divr" style="width: 12.5%;"></div>
        <div class="divr" style="width: 12.5%;"></div>
        <div class="divr" style="width: 12.5%;"></div>
        <div class="divr" style="width: 12.5%;"></div>
    </div>
    <div class="d-flex last-row justify-content-around">
        <div><?php user_box($ids[7]); ?></div>
        <div><?php user_box($ids[8]); ?></div>
        <div><?php user_box($ids[9]); ?></div>
        <div><?php user_box($ids[10]); ?></div>
        <div><?php user_box($ids[11]); ?></div>
        <div><?php user_box($ids[12]); ?></div>
        <div><?php user_box($ids[13]); ?></div>
        <div><?php user_box($ids[14]); ?></div>
    </div>
    <div style="margin-bottom: 160px;"></div>
</div>