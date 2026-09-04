<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: var(--a-radius-lg); border: none;">
            <div class="modal-body text-center p-4">
                <div class="mx-auto mb-3 d-flex align-items-center justify-content-center"
                     style="width: 64px; height: 64px; border-radius: 50%; background: rgba(239,68,68,.12); color: #dc2626; font-size: 1.6rem;">
                    <i class="bi bi-trash3-fill"></i>
                </div>
                <h5 class="fw-bold mb-2">Delete this item?</h5>
                <p class="text-muted mb-4" id="confirmDeleteMessage">This action cannot be undone.</p>
                <form id="confirmDeleteForm" method="POST" action="#">
                    @csrf
                    @method('DELETE')
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-light px-4 rounded-pill" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 rounded-pill">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
