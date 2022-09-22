<div class="page-header">
    <h5>Scan and Pay</h5>
</div>
<script>
    function resizeIframe(obj) {
        obj.style.height = obj.contentWindow.document.documentElement.scrollHeight + 'px';
    }
</script>
<div class="hgradiant position-relative logo-box footer-margin">
    <div class="bg-3 p-3 bg-white rounded mybox text-center">
        <?php // print_r($data); 
        ?>
        <iframe src="<?= $data->status_url; ?>" height="600" style="width: 100%;" frameborder="0" scrolling="no" onload="resizeIframe(this)">
    </div>
</div>