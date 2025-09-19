    <!-- Javascripts -->
    <script src="<?= ADMIN_ASSETS ?>plugins/jquery/jquery-3.5.1.min.js"></script>
    <script src="<?= ADMIN_ASSETS ?>plugins/bootstrap/js/popper.min.js"></script>
    <script src="<?= ADMIN_ASSETS ?>plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?= ADMIN_ASSETS ?>plugins/perfectscroll/perfect-scrollbar.min.js"></script>
    <script src="<?= ADMIN_ASSETS ?>plugins/pace/pace.min.js"></script>
    <script src="<?= ADMIN_ASSETS ?>plugins/highlight/highlight.pack.js"></script>
    <script src="<?= ADMIN_ASSETS ?>plugins/dropzone/min/dropzone.min.js"></script>
    <script src="<?= ADMIN_ASSETS ?>js/main.min.js"></script>
    <script src="<?= ADMIN_ASSETS ?>js/custom.js"></script>
    <script src="<?= ADMIN_ASSETS ?>plugins/apexcharts/apexcharts.min.js"></script>
    <script src="<?= ADMIN_ASSETS ?>js/pages/dashboard.js"></script>

    <script src="<?= ADMIN_ASSETS ?>plugins/datatables/datatables.min.js"></script>
    <script src="<?= ADMIN_ASSETS ?>js/pages/datatables.js"></script>


    <script src="<?= ADMIN_ASSETS ?>plugins/select2/js/select2.full.min.js"></script>
    <script src="<?= ADMIN_ASSETS ?>js/pages/select2.js"></script>

    <script src="<?= ADMIN_ASSETS ?>plugins/summernote/summernote-lite.min.js"></script>
    <script src="<?= ADMIN_ASSETS ?>js/pages/text-editor.js"></script>


    <script>
        // Wait for the DOM to fully load
        document.addEventListener("DOMContentLoaded", function() {
            // Get the alert element by ID
            var alert = document.getElementById('alert-message');

            // Check if the alert element exists
            if (alert) {
                // Set a timer to remove the alert after 5 seconds (5000 milliseconds)
                setTimeout(function() {
                    alert.style.display = 'none';
                }, 2000); // 5 seconds
            }
        });
    </script>



    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function showAlert(type, message) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: type,
                title: message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        }
    </script>

    </div>
    </body>

    </html>