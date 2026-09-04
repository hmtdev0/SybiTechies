<div class="admin-card">
    <div class="admin-card__header"><h3 class="admin-card__title">FAQ Details</h3></div>
    <div class="admin-card__body row g-3">
        <div class="col-12">
            <label class="admin-form-label">Question</label>
            <input type="text" name="question" class="form-control" value="{{ old('question', $faq->question ?? '') }}" required>
        </div>
        <div class="col-12">
            <label class="admin-form-label">Answer</label>
            <textarea name="answer" rows="5" class="form-control" maxlength="2000" data-char-counter="2000">{{ old('answer', $faq->answer ?? '') }}</textarea>
        </div>
        <div class="col-md-6">
            <label class="admin-form-label">Display Order</label>
            <input type="number" name="display_order" class="form-control" value="{{ old('display_order', $faq->display_order ?? 0) }}">
        </div>
        <div class="col-md-6 d-flex align-items-end">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="status" value="1" id="statusSwitch" @checked(old('status', $faq->status ?? true))>
                <label class="form-check-label" for="statusSwitch">Active (visible on website)</label>
            </div>
        </div>
    </div>
</div>

<button type="submit" class="btn btn-admin-gradient px-5 mt-4">
    <i class="bi bi-check2-circle me-2"></i>{{ isset($faq) ? 'Update' : 'Save' }} FAQ
</button>
