<?php
$session = session();
if (session()->getFlashdata('toast')) {
    $msg = session()->getFlashdata('toast');
?>
    <div id="toast" class="toast-wrapper">
        <div class="my-toast">
            <?= $msg; ?>
        </div>
    </div>
    <script>
        document.getElementById('toast').style.display = 'flex';
        setTimeout(function() {
            document.getElementById('toast').style.display = 'none';
        }, 3000)
    </script>
<?php
}
?>