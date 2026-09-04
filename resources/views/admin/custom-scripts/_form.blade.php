@php $script ??= null; @endphp

<div class="admin-card mb-4">
    <div class="admin-card__header"><h3 class="admin-card__title">Script Details</h3></div>
    <div class="admin-card__body row g-3">
        <div class="col-md-8">
            <label class="admin-form-label">Name</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name', $script->name ?? '') }}" placeholder="e.g. Google Analytics" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <div class="form-text">Internal label only — not shown on the public site.</div>
        </div>
        <div class="col-md-4">
            <label class="admin-form-label">Placement</label>
            <select name="placement" class="form-select @error('placement') is-invalid @enderror" required>
                @php $placement = old('placement', $script->placement ?? 'head'); @endphp
                <option value="head" @selected($placement === 'head')>Head (before &lt;/head&gt;)</option>
                <option value="footer" @selected($placement === 'footer')>Footer (before &lt;/body&gt;)</option>
            </select>
            @error('placement')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-12">
            <label class="admin-form-label">Code</label>
            <textarea name="code" rows="10" class="form-control @error('code') is-invalid @enderror"
                      style="font-family: 'Courier New', monospace; font-size: .85rem;"
                      placeholder="<script>...</script> or <meta .../> etc." required>{{ old('code', $script->code ?? '') }}</textarea>
            @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <div class="form-text">
                Paste the full snippet exactly as given to you (including its own <code>&lt;script&gt;</code> tags) —
                it is inserted on every public page, unescaped, at the placement chosen above. Only people with
                Website Settings access can reach this page, so treat it the same as SMTP credentials.
            </div>
        </div>
        <div class="col-12">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="status" value="1" id="statusSwitch"
                       @checked(old('status', $script->status ?? true))>
                <label class="form-check-label" for="statusSwitch">Active (inject on the public site)</label>
            </div>
        </div>
    </div>
</div>

<button type="submit" class="btn btn-admin-gradient px-5">
    <i class="bi bi-check2-circle me-2"></i>{{ $script ? 'Update' : 'Save' }} Script
</button>
