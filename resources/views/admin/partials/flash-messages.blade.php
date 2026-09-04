@if(session('success'))
    <div class="admin-alert admin-alert--success mb-4" data-auto-dismiss>
        <i class="bi bi-check-circle-fill"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="admin-alert admin-alert--danger mb-4" data-auto-dismiss>
        <i class="bi bi-exclamation-triangle-fill"></i>
        <span>{{ session('error') }}</span>
    </div>
@endif

@if($errors->any())
    <div class="admin-alert admin-alert--danger mb-4">
        <i class="bi bi-exclamation-triangle-fill mt-1"></i>
        <div>
            <strong class="d-block mb-1">Please fix the following:</strong>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
