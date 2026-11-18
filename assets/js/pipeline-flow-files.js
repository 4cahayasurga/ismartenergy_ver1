/* Unggah per file tanpa reload: pilihan dokumen lain dan lampiran tersimpan tetap utuh. */
(() => {
    'use strict';
    if (!window.fetch || !window.FormData) return;
    let queue = Promise.resolve(), pending = 0;
    const status = document.getElementById('flow-upload-status');
    if (!status) return;
    function report(message, bad) {
        status.className = 'alert alert-' + (bad ? 'warning' : 'info');
        status.textContent = message;
    }
    function submitLock() {
        document.querySelectorAll('button[name="action"][value="submit"]').forEach(button => {button.disabled = pending > 0;});
    }
    function enqueue(form, action) {
        if (form.dataset.saving === '1') return;
        const file = form.querySelector('input[type="file"]');
        if (action === 'upload' && (!file || !file.files.length)) {form.reportValidity(); return;}
        const card = form.closest('[data-flow-file-kind]');
        const kind = form.elements.Kind.value;
        // Capture this file before disabling inputs or replacing only its own card.
        const data = new FormData(form); data.set('action', action);
        form.dataset.saving = '1'; pending++; submitLock();
        const controls = Array.from(card.querySelectorAll('button,input[type="file"]'));
        controls.forEach(control => {control.disabled = true;});
        const feedback = card.querySelector('[data-flow-file-status]');
        feedback.className = 'text-info';
        feedback.textContent = 'Menyimpan dan memeriksa dokumen…';
        queue = queue.then(async () => {
            try {
                // Use the current CSRF value after the previous queued request.
                document.querySelectorAll('input[type="hidden"]').forEach(input => {
                    if (data.has(input.name) && /csrf/i.test(input.name)) data.set(input.name, input.value);
                });
                // A submit button named "action" shadows form.action in the DOM.
                const response = await fetch(form.getAttribute('action'), {method: 'POST', credentials: 'same-origin', headers: {'X-Requested-With': 'XMLHttpRequest'}, body: data});
                const result = await response.json();
                if (result.csrf) document.querySelectorAll('input[type="hidden"]').forEach(input => {if (input.name === result.csrf.name) input.value = result.csrf.hash;});
                if (!response.ok || !result.ok) throw new Error(result.message || 'File belum tersimpan. Coba unggah kembali.');
                const template = document.createElement('template'); template.innerHTML = result.html.trim();
                const replacement = template.content.firstElementChild;
                if (!replacement || replacement.dataset.flowFileKind !== kind) throw new Error('Status dokumen belum dapat ditampilkan. Refresh untuk memeriksa file yang tersimpan.');
                card.replaceWith(replacement);
                report(result.message, false);
            } catch (error) {
                feedback.className = 'text-danger';
                feedback.textContent = error instanceof SyntaxError
                    ? 'Unggah belum dapat dikonfirmasi. Pilihan file tetap ada. Coba unggah kembali atau login ulang bila sesi berakhir.'
                    : error.message;
                controls.forEach(control => {control.disabled = false;});
            } finally {
                delete form.dataset.saving; pending--; submitLock();
            }
        });
    }
    document.addEventListener('change', event => {
        if (event.target.matches('[data-flow-upload] input[type="file"]') && event.target.files.length) enqueue(event.target.form, 'upload');
    });
    document.addEventListener('submit', event => {
        const form = event.target;
        if (form.matches('[data-flow-upload], [data-flow-recheck]')) {event.preventDefault(); enqueue(form, form.matches('[data-flow-upload]') ? 'upload' : 'recheck');}
        else if (pending > 0) {event.preventDefault(); report('Tunggu sampai unggah dan pemeriksaan file selesai. Semua lampiran yang sudah tersimpan tetap ada.', false);}
    });
    window.addEventListener('beforeunload', event => {if (pending > 0) {event.preventDefault(); event.returnValue = '';}});
})();
