<div id="deleteModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title text-light" id="deleteModalLabel">
                    <i class="fas fa-exclamation-circle"></i> Confirm Deletion
                </h5>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-trash-alt fa-4x text-danger mb-3"></i>
                <p class="lead font-weight-bold">Are you sure?</p>
                <p class="text-muted">This action cannot be undone. Once deleted, the data cannot be recovered.</p>
            </div>
            <div class="modal-footer d-flex justify-content-around">
                <button type="button" class="btn btn-danger" id="deleteConfirmBtn">
                    <i class="fas fa-check-circle"></i> Yes, Delete
                </button>
                <!-- Updated Cancel Button -->
                <button type="button" class="btn btn-secondary" id="cancelBtn">
                    <i class="fas fa-times-circle"></i> No, Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Manually hide the modal on cancel button click
    document.getElementById('cancelBtn').addEventListener('click', function() {
        $('#deleteModal').modal('hide'); // This will close the modal
    });
</script>