<div id="toastid" class="toast align-items-center text-white bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
        <div class="toast-body">
            Hello, world! This is a toast message.
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
</div>
<script>
    var myToastEl = document.getElementById('toastid')
    var myToast = bootstrap.Toast.getInstance(myToastEl) // Returns a Bootstrap toast instance
    myToast.show();
</script>