@extends('layouts/layoutMaster')

@section('title', isset($note) ? 'Edit Catatan' : 'Catatan Baru')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
@endsection

@section('page-script')
    <script>
        'use strict';

        (() => {
            const form = document.getElementById('fastcrud-note-form');
            if (!form) return;

            const title = document.getElementById('title');
            const content = document.getElementById('content');
            const status = document.getElementById('autosave-status');
            const toggle = document.getElementById('autosave-toggle');
            let autosave = true;
            let dirty = false;
            let timer;
            let saving = false;
            let saveAgain = false;

            const quill = new Quill('#note-editor', {
                theme: 'snow',
                placeholder: 'Tulis hasil rapat di sini...',
                modules: { toolbar: '#note-toolbar' }
            });

            function markDirty() {
                dirty = true;
                status.textContent = 'Belum tersimpan';
                clearTimeout(timer);
                if (autosave && title.value.trim()) timer = setTimeout(save, 2000);
            }

            async function save() {
                if (!dirty || !title.value.trim()) return;
                if (saving) {
                    saveAgain = true;
                    return;
                }

                saving = true;
                dirty = false;
                status.textContent = 'Menyimpan...';
                const body = new FormData();
                body.append('_token', form.querySelector('[name="_token"]').value);
                body.append('title', title.value);
                body.append('content', quill.root.innerHTML);

                try {
                    const response = await fetch(form.dataset.autosaveUrl, {
                        method: 'POST',
                        headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        body
                    });
                    if (!response.ok) throw new Error('Gagal menyimpan');
                    const result = await response.json();
                    if (!form.dataset.autosaveUrl.endsWith('/' + result.uuid)) {
                        form.dataset.autosaveUrl += '/' + result.uuid;
                        form.action = form.dataset.editUrl + '/' + result.uuid;
                        let method = form.querySelector('[name="_method"]');
                        if (!method) {
                            method = document.createElement('input');
                            method.type = 'hidden';
                            method.name = '_method';
                            method.value = 'PUT';
                            form.appendChild(method);
                        }
                        history.replaceState({}, '', form.dataset.editUrl + '/' + result.uuid + '/edit');
                    }
                    status.textContent = dirty ? 'Ada perubahan baru' : 'Tersimpan ' + result.updated_at;
                } catch (error) {
                    dirty = true;
                    status.textContent = 'Gagal menyimpan. Coba lagi.';
                } finally {
                    saving = false;
                    if (saveAgain || (dirty && autosave)) {
                        saveAgain = false;
                        clearTimeout(timer);
                        timer = setTimeout(save, 1000);
                    }
                }
            }

            function uploadImage() {
                const input = document.createElement('input');
                input.type = 'file';
                input.accept = 'image/jpeg,image/png,image/gif,image/webp';
                input.click();
                input.onchange = async () => {
                    if (!input.files[0]) return;
                    status.textContent = 'Mengunggah gambar...';
                    const body = new FormData();
                    body.append('_token', form.querySelector('[name="_token"]').value);
                    body.append('image', input.files[0]);
                    try {
                        const response = await fetch(form.dataset.uploadUrl, {
                            method: 'POST',
                            headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                            body
                        });
                        if (!response.ok) throw new Error('Gagal mengunggah');
                        const result = await response.json();
                        const range = quill.getSelection(true);
                        quill.insertEmbed(range.index, 'image', result.url, 'user');
                        quill.setSelection(range.index + 1, 0);
                    } catch (error) {
                        status.textContent = 'Upload gambar gagal. Maksimal 5 MB.';
                    }
                };
            }

            quill.getModule('toolbar').addHandler('image', uploadImage);
            quill.on('text-change', markDirty);
            title.addEventListener('input', markDirty);
            toggle.addEventListener('click', () => {
                autosave = !autosave;
                toggle.setAttribute('aria-pressed', String(autosave));
                toggle.classList.toggle('btn-outline-primary', autosave);
                toggle.classList.toggle('btn-outline-secondary', !autosave);
                toggle.innerHTML = '<i class="ti ti-device-floppy me-1"></i>Autosave ' + (autosave ? 'aktif' : 'nonaktif');
                if (autosave) markDirty();
            });
            form.addEventListener('submit', () => {
                content.value = quill.root.innerHTML;
                dirty = false;
            });
            window.addEventListener('beforeunload', event => {
                if (dirty) event.preventDefault();
            });
        })();
    </script>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">{{ isset($note) ? 'Edit Catatan Rapat' : 'Catatan Rapat Baru' }}</h4>
        <a href="{{ route('fastcrud_note.index') }}" class="btn btn-outline-secondary"><i class="ti ti-arrow-left me-1"></i>Kembali</a>
    </div>
    @include('partials.errors')
    <form id="fastcrud-note-form" method="post" action="{{ isset($note) ? route('fastcrud_note.update', $note->uuid) : route('fastcrud_note.store') }}"
        data-autosave-url="{{ isset($note) ? route('fastcrud_note.autosave', $note->uuid) : route('fastcrud_note.autosave') }}"
        data-edit-url="{{ url(config('fastcrud.route_prefix', 'admin') . '/fastcrud_note') }}"
        data-upload-url="{{ route('fastcrud_note.upload-image') }}">
        @csrf
        @isset($note) @method('PUT') @endisset
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <label for="title" class="form-label">Judul</label>
                    <input id="title" name="title" class="form-control" maxlength="255" required value="{{ old('title', $note->title ?? '') }}" placeholder="Contoh: Rapat proyek 16 Oktober">
                </div>
                <div class="mb-3">
                    <label class="form-label">Catatan</label>
                    <div id="note-toolbar">
                        <span class="ql-formats"><select class="ql-header"><option selected></option><option value="1"></option><option value="2"></option></select></span>
                        <span class="ql-formats"><button class="ql-bold"></button><button class="ql-italic"></button><button class="ql-underline"></button><button class="ql-strike"></button></span>
                        <span class="ql-formats"><button class="ql-list" value="ordered"></button><button class="ql-list" value="bullet"></button><button class="ql-blockquote"></button></span>
                        <span class="ql-formats"><button class="ql-link"></button><button class="ql-image"></button></span>
                        <span class="ql-formats"><button class="ql-clean"></button></span>
                    </div>
                    <div id="note-editor" style="min-height: 320px">{!! $note->content ?? '' !!}</div>
                    <input id="content" name="content" type="hidden">
                </div>
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <button class="btn btn-primary" type="submit"><i class="ti ti-device-floppy me-1"></i>Simpan sekarang</button>
                    <button id="autosave-toggle" class="btn btn-outline-primary" type="button" aria-pressed="true"><i class="ti ti-device-floppy me-1"></i>Autosave aktif</button>
                    <span id="autosave-status" class="text-muted small" aria-live="polite">Belum tersimpan</span>
                </div>
            </div>
        </div>
    </form>
@endsection
