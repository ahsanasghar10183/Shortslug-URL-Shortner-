
    <?php 
    $baseurl = "/admin/"; 
    ?>
    <!-- plugins:js -->
    <script src="<?php echo $baseurl?>assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="<?php echo $baseurl?>assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="<?php echo $baseurl?>assets/vendors/chart.js/chart.umd.js"></script>
    <script src="<?php echo $baseurl?>assets/vendors/progressbar.js/progressbar.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="<?php echo $baseurl?>assets/js/off-canvas.js"></script>
    <script src="<?php echo $baseurl?>assets/js/template.js"></script>
    <script src="<?php echo $baseurl?>assets/js/settings.js"></script>
    <script src="<?php echo $baseurl?>assets/js/hoverable-collapse.js"></script>
    <script src="<?php echo $baseurl?>assets/js/todolist.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="<?php echo $baseurl?>assets/js/jquery.cookie.js" type="text/javascript"></script>
    <script src="<?php echo $baseurl?>assets/js/dashboard.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css" integrity="sha512-9xKTRVabjVeZmc+GUW8GgSmcREDunMM+Dt/GrzchfN8tkwHizc5RP4Ok/MXFFy5rIjJjzhndFScTceq5e6GvVQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

   

    <!-- <script src="assets/js/Chart.roundedBarCharts.js"></script> -->
    <script>
    setTimeout(() => {
    const alert = document.querySelector('.alert');
    if (alert) {
        alert.classList.remove('show');
        alert.classList.add('fade');
    }
}, 5000);
    function copyToClipboard(text) {
        try {
            // Create a temporary input element
            const tempInput = document.createElement('input');
            tempInput.type = 'text';
            tempInput.value = text;
            document.body.appendChild(tempInput);
            tempInput.select(); // Select the text in the input
            // Copy the text inside the text field
            document.execCommand('copy'); // Copy the text
            document.body.removeChild(tempInput); // Remove the input element

            // Notify user of successful copy
            alert('Short URL copied to clipboard!');
        } catch (err) {
            console.error('Failed to copy:', err);
            alert('Failed to copy the URL.');
        }

      }
</script>