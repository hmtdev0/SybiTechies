/* =========================================================
   SysbiTechies — admin.js
   Sidebar toggle, delete-confirm modal, image preview,
   slug generator, character counter, loading buttons.
   ========================================================= */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {

        /* -------- Sidebar collapse (desktop) / slide (mobile) -------- */
        var toggleBtn = document.getElementById('sidebarToggle');
        var body = document.body;
        var isDesktop = function () { return window.matchMedia('(min-width: 992px)').matches; };

        if (localStorage.getItem('admin_sidebar_collapsed') === '1' && isDesktop()) {
            body.classList.add('sidebar-collapsed');
        }

        /* -------- Sidebar nav tooltips (icon-only collapsed mode) --------
           Bootstrap tooltips are only useful once labels are hidden, so
           they're created/destroyed alongside the collapsed state rather
           than left active (and redundant) while labels are visible. */
        var sidebarTooltips = [];
        function refreshSidebarTooltips() {
            sidebarTooltips.forEach(function (t) { t.dispose(); });
            sidebarTooltips = [];
            if (!window.bootstrap || !body.classList.contains('sidebar-collapsed')) { return; }
            document.querySelectorAll('.admin-nav-link[data-bs-toggle="tooltip"], .admin-sidebar__user[data-bs-toggle="tooltip"]').forEach(function (el) {
                sidebarTooltips.push(new bootstrap.Tooltip(el));
            });
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function () {
                if (isDesktop()) {
                    body.classList.toggle('sidebar-collapsed');
                    localStorage.setItem('admin_sidebar_collapsed', body.classList.contains('sidebar-collapsed') ? '1' : '0');
                    refreshSidebarTooltips();
                } else {
                    body.classList.toggle('sidebar-mobile-open');
                }
            });
        }
        refreshSidebarTooltips();

        var backdrop = document.querySelector('.admin-sidebar-backdrop');
        if (backdrop) {
            backdrop.addEventListener('click', function () {
                body.classList.remove('sidebar-mobile-open');
            });
        }

        /* -------- Dark mode toggle -------- */
        var themeToggle = document.getElementById('themeToggle');
        var themeToggleIcon = document.getElementById('themeToggleIcon');
        function applyThemeIcon() {
            var isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            if (themeToggleIcon) {
                themeToggleIcon.className = isDark ? 'bi bi-sun-fill' : 'bi bi-moon-stars-fill';
            }
        }
        applyThemeIcon();
        if (themeToggle) {
            themeToggle.addEventListener('click', function () {
                var next = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                document.documentElement.setAttribute('data-theme', next);
                localStorage.setItem('admin_theme', next);
                applyThemeIcon();
            });
        }

        /* -------- Count-up animation for stat cards -------- */
        document.querySelectorAll('.admin-stat-card__num[data-count]').forEach(function (el) {
            var end = parseInt(el.getAttribute('data-count'), 10) || 0;
            var duration = 900;
            var start = null;
            function step(timestamp) {
                if (!start) { start = timestamp; }
                var progress = Math.min((timestamp - start) / duration, 1);
                var eased = 1 - Math.pow(1 - progress, 3);
                el.textContent = Math.round(eased * end).toLocaleString();
                if (progress < 1) { requestAnimationFrame(step); }
            }
            requestAnimationFrame(step);
        });

        /* -------- Global admin search -------- */
        var searchWrap = document.getElementById('adminSearch');
        var searchInput = document.getElementById('adminSearchInput');
        var searchResults = document.getElementById('adminSearchResults');
        if (searchWrap && searchInput && searchResults) {
            var searchTimer = null;
            var searchUrl = searchWrap.getAttribute('data-search-url') || '/admin/search';

            var typeIcons = {
                Project: 'bi-briefcase-fill', Service: 'bi-code-square',
                Message: 'bi-envelope-fill', Testimonial: 'bi-chat-quote-fill'
            };

            function renderResults(results) {
                if (!results.length) {
                    searchResults.innerHTML = '<div class="admin-search__empty">No matches found.</div>';
                    return;
                }
                var groups = {};
                results.forEach(function (r) {
                    (groups[r.type] = groups[r.type] || []).push(r);
                });
                var html = '';
                Object.keys(groups).forEach(function (type) {
                    html += '<div class="admin-search__group-label">' + type + 's</div>';
                    groups[type].forEach(function (r) {
                        html += '<a href="' + r.url + '" class="admin-search__item">'
                            + '<i class="bi ' + (typeIcons[r.type] || 'bi-dot') + '"></i>'
                            + '<span>' + r.label + '</span></a>';
                    });
                });
                searchResults.innerHTML = html;
            }

            searchInput.addEventListener('input', function () {
                var q = searchInput.value.trim();
                clearTimeout(searchTimer);
                if (q.length < 2) {
                    searchResults.classList.remove('show');
                    return;
                }
                searchResults.innerHTML = [1, 2, 3].map(function () {
                    return '<div class="admin-search__item"><div class="admin-skeleton admin-skeleton-line" style="width:100%;"></div></div>';
                }).join('');
                searchResults.classList.add('show');

                searchTimer = setTimeout(function () {
                    fetch(searchUrl + '?q=' + encodeURIComponent(q), { headers: { 'Accept': 'application/json' } })
                        .then(function (res) { return res.json(); })
                        .then(function (data) {
                            renderResults(data.results || []);
                            searchResults.classList.add('show');
                        })
                        .catch(function () { searchResults.classList.remove('show'); });
                }, 300);
            });

            searchInput.addEventListener('focus', function () {
                if (!isDesktop()) { searchWrap.classList.add('is-active'); }
                if (searchResults.innerHTML && searchInput.value.trim().length >= 2) { searchResults.classList.add('show'); }
            });

            document.addEventListener('click', function (e) {
                if (!searchWrap.contains(e.target)) {
                    searchResults.classList.remove('show');
                    if (!isDesktop() && !searchInput.value.trim()) { searchWrap.classList.remove('is-active'); }
                }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    searchResults.classList.remove('show');
                    searchInput.blur();
                }
            });
        }

        /* -------- Delete confirmation modal -------- */
        var deleteModalEl = document.getElementById('confirmDeleteModal');
        if (deleteModalEl && window.bootstrap) {
            var deleteModal = new bootstrap.Modal(deleteModalEl);
            var deleteForm = document.getElementById('confirmDeleteForm');
            var deleteMessage = document.getElementById('confirmDeleteMessage');

            document.querySelectorAll('[data-confirm-delete]').forEach(function (trigger) {
                trigger.addEventListener('click', function (e) {
                    e.preventDefault();
                    var action = trigger.getAttribute('data-action') || trigger.getAttribute('href');
                    var label = trigger.getAttribute('data-confirm-delete') || 'this item';
                    if (deleteForm) { deleteForm.setAttribute('action', action); }
                    if (deleteMessage) { deleteMessage.textContent = 'Are you sure you want to delete ' + label + '? This action cannot be undone.'; }
                    deleteModal.show();
                });
            });
        }

        /* -------- Image preview on file input change -------- */
        document.querySelectorAll('input[type="file"][data-preview-target]').forEach(function (input) {
            input.addEventListener('change', function () {
                var targetSel = input.getAttribute('data-preview-target');
                var target = document.querySelector(targetSel);
                if (!target || !input.files || !input.files[0]) { return; }
                var reader = new FileReader();
                reader.onload = function (e) {
                    target.src = e.target.result;
                    target.classList.remove('d-none');
                };
                reader.readAsDataURL(input.files[0]);
            });
        });

        /* -------- Drag & drop styling for upload boxes -------- */
        document.querySelectorAll('.admin-upload-box').forEach(function (box) {
            ['dragenter', 'dragover'].forEach(function (evt) {
                box.addEventListener(evt, function (e) { e.preventDefault(); box.classList.add('dragover'); });
            });
            ['dragleave', 'drop'].forEach(function (evt) {
                box.addEventListener(evt, function (e) { e.preventDefault(); box.classList.remove('dragover'); });
            });
            box.addEventListener('drop', function (e) {
                var input = box.querySelector('input[type="file"]');
                if (input && e.dataTransfer.files.length) {
                    input.files = e.dataTransfer.files;
                    input.dispatchEvent(new Event('change'));
                }
            });
        });

        /* -------- Auto slug generator -------- */
        document.querySelectorAll('[data-slug-source]').forEach(function (source) {
            var targetSel = source.getAttribute('data-slug-source');
            var target = document.querySelector(targetSel);
            if (!target) { return; }
            var manuallyEdited = false;
            target.addEventListener('input', function () { manuallyEdited = true; });
            source.addEventListener('input', function () {
                if (manuallyEdited) { return; }
                target.value = source.value
                    .toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
            });
        });

        /* -------- Character counter -------- */
        document.querySelectorAll('[data-char-counter]').forEach(function (field) {
            var max = parseInt(field.getAttribute('maxlength') || field.getAttribute('data-char-counter'), 10);
            var counter = document.createElement('div');
            counter.className = 'admin-char-counter';
            field.insertAdjacentElement('afterend', counter);
            var update = function () {
                var len = field.value.length;
                counter.textContent = max ? (len + ' / ' + max) : (len + ' characters');
            };
            field.addEventListener('input', update);
            update();
        });

        /* -------- Rich text editor (Quill) with inline image upload --------
           Any <textarea data-quill-editor data-quill-upload-url="..."> is
           swapped for a Quill "snow" editor. The textarea itself is kept in
           the DOM (hidden) so the form still posts its value normally — its
           value is kept in sync with the editor's HTML on every change and
           right before submit. */
        document.querySelectorAll('textarea[data-quill-editor]').forEach(function (textarea) {
            if (!window.Quill) { return; }

            var uploadUrl = textarea.getAttribute('data-quill-upload-url');
            var form = textarea.closest('form');
            var tokenInput = form ? form.querySelector('input[name="_token"]') : null;
            var csrfToken = tokenInput ? tokenInput.value : '';

            // Quill inserts its generated toolbar as a *sibling before* the
            // element it's given, not nested inside it — so the editable area
            // goes in its own inner div, both wrapped by .admin-editor, so our
            // theming (border, toolbar colors, etc.) actually reaches both.
            var wrap = document.createElement('div');
            wrap.className = 'admin-editor';
            textarea.insertAdjacentElement('beforebegin', wrap);

            var editorEl = document.createElement('div');
            wrap.appendChild(editorEl);
            textarea.classList.add('d-none');

            var quill = new Quill(editorEl, {
                theme: 'snow',
                placeholder: textarea.getAttribute('placeholder') || 'Write something...',
                modules: {
                    toolbar: {
                        container: [
                            [{ header: [2, 3, false] }],
                            ['bold', 'italic', 'underline'],
                            [{ list: 'ordered' }, { list: 'bullet' }],
                            ['blockquote', 'link', 'image'],
                            ['clean']
                        ],
                        handlers: { image: uploadUrl ? insertImage : undefined }
                    }
                }
            });

            // Route existing HTML through Quill's own clipboard converter rather
            // than assigning innerHTML directly — a raw assignment leaves list
            // markup (<ul>/<li>) in a shape Quill's List blot doesn't recognize,
            // and it gets silently dropped on the next edit/save.
            if (textarea.value) { quill.clipboard.dangerouslyPasteHTML(textarea.value); }

            function syncTextarea() {
                var html = quill.root.innerHTML;
                textarea.value = (html === '<p><br></p>') ? '' : html;
            }

            function insertImage() {
                var input = document.createElement('input');
                input.type = 'file';
                input.accept = 'image/*';
                input.addEventListener('change', function () {
                    var file = input.files[0];
                    if (!file) { return; }

                    var range = quill.getSelection(true);
                    var placeholder = 'Uploading image…';
                    quill.insertText(range.index, placeholder, { italic: true });
                    quill.setSelection(range.index + placeholder.length);

                    var body = new FormData();
                    body.append('image', file);

                    fetch(uploadUrl, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                        body: body
                    })
                        .then(function (res) { return res.ok ? res.json() : Promise.reject(res); })
                        .then(function (data) {
                            quill.deleteText(range.index, placeholder.length);
                            if (data.url) {
                                quill.insertEmbed(range.index, 'image', data.url, 'user');
                                quill.setSelection(range.index + 1);
                            }
                        })
                        .catch(function () {
                            quill.deleteText(range.index, placeholder.length);
                            alert('Image upload failed. Please try again.');
                        })
                        .then(syncTextarea);
                });
                input.click();
            }

            quill.on('text-change', syncTextarea);
            if (form) { form.addEventListener('submit', syncTextarea); }
        });

        /* -------- Loading state on form submit -------- */
        document.querySelectorAll('form').forEach(function (form) {
            form.addEventListener('submit', function () {
                var btn = form.querySelector('button[type="submit"]');
                if (!btn || btn.classList.contains('btn-loading')) { return; }
                btn.classList.add('btn-loading');
                btn.disabled = true;
                var original = btn.innerHTML;
                btn.dataset.originalHtml = original;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Please wait...';
            });
        });

        /* -------- Auto-dismiss flash alerts -------- */
        document.querySelectorAll('.admin-alert[data-auto-dismiss]').forEach(function (alertEl) {
            setTimeout(function () {
                alertEl.style.transition = 'opacity .4s ease';
                alertEl.style.opacity = '0';
                setTimeout(function () { alertEl.remove(); }, 400);
            }, 4000);
        });

    });
})();
