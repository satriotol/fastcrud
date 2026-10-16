<?php

namespace Satriotol\Fastcrud\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Satriotol\Fastcrud\Models\FastcrudNote;

class FastcrudNoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:fastcrud_note-index')->only('index');
        $this->middleware('permission:fastcrud_note-create')->only('create', 'store');
        $this->middleware('permission:fastcrud_note-update')->only('edit', 'update');
        $this->middleware('permission:fastcrud_note-delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $notes = FastcrudNote::with('user:id,name')
            ->when($request->filled('search'), fn ($query) => $query->where('title', 'like', '%' . $request->string('search') . '%'))
            ->latest('updated_at')->paginate(15);

        return view('fastcrud::fastcrud_note.index', compact('notes'));
    }

    public function create()
    {
        return view('fastcrud::fastcrud_note.editor');
    }

    public function edit(string $uuid)
    {
        $note = FastcrudNote::where('uuid', $uuid)->firstOrFail();
        return view('fastcrud::fastcrud_note.editor', compact('note'));
    }

    public function store(Request $request)
    {
        $note = FastcrudNote::create($this->validated($request) + ['user_id' => $request->user()->id]);
        return redirect()->route('fastcrud_note.edit', $note->uuid)->with('success', 'Catatan berhasil disimpan.');
    }

    public function update(Request $request, string $uuid)
    {
        $note = FastcrudNote::where('uuid', $uuid)->firstOrFail();
        $note->update($this->validated($request));
        return redirect()->route('fastcrud_note.edit', $note->uuid)->with('success', 'Catatan berhasil diperbarui.');
    }

    public function autosave(Request $request, ?string $uuid = null)
    {
        abort_unless($request->user()->can($uuid ? 'fastcrud_note-update' : 'fastcrud_note-create'), 403);
        $data = $this->validated($request);
        $note = $uuid
            ? FastcrudNote::where('uuid', $uuid)->firstOrFail()
            : FastcrudNote::create($data + ['user_id' => $request->user()->id]);

        if ($uuid) {
            $note->update($data);
        }

        return response()->json(['uuid' => $note->uuid, 'updated_at' => $note->updated_at->format('d M Y H:i:s')]);
    }

    public function uploadImage(Request $request)
    {
        abort_unless($request->user()->can('fastcrud_note-create') || $request->user()->can('fastcrud_note-update'), 403);
        $request->validate(['image' => ['required', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120']]);
        $file = $request->file('image');
        $path = $file->storeAs('fastcrud-notes/' . $request->user()->id, Str::uuid() . '.' . $file->extension(), 'public');

        return response()->json(['url' => Storage::disk('public')->url($path)]);
    }

    public function destroy(string $uuid)
    {
        FastcrudNote::where('uuid', $uuid)->firstOrFail()->delete();
        return back()->with('success', 'Catatan berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string', 'max:1000000'],
        ]);
        $data['content'] = $this->sanitizeHtml($data['content'] ?? '');

        return $data;
    }

    private function sanitizeHtml(string $html): string
    {
        if (trim($html) === '') {
            return '';
        }

        $previous = libxml_use_internal_errors(true);
        $document = new \DOMDocument();
        $document->loadHTML('<?xml encoding="utf-8" ?><div>' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $allowed = ['div', 'p', 'br', 'strong', 'b', 'em', 'i', 'u', 's', 'span', 'h1', 'h2', 'h3', 'blockquote', 'pre', 'code', 'ol', 'ul', 'li', 'a', 'img'];
        $xpath = new \DOMXPath($document);
        foreach (iterator_to_array($xpath->query('//*')) as $node) {
            if (!in_array(strtolower($node->nodeName), $allowed, true)) {
                $node->parentNode->removeChild($node);
                continue;
            }
            foreach (iterator_to_array($node->attributes) as $attribute) {
                $name = strtolower($attribute->name);
                $value = trim($attribute->value);
                $valid = ($node->nodeName === 'a' && $name === 'href' && preg_match('#^(https?://|mailto:)#i', $value))
                    || ($node->nodeName === 'img' && $name === 'src' && preg_match('#^(https?://|/)#i', $value))
                    || ($node->nodeName === 'img' && $name === 'alt')
                    || ($node->nodeName === 'span' && $name === 'class' && preg_match('/^ql-/', $value));
                if (!$valid) {
                    $node->removeAttributeNode($attribute);
                }
            }
        }

        $wrapper = $document->documentElement;
        $html = '';
        foreach ($wrapper->childNodes as $child) {
            $html .= $document->saveHTML($child);
        }

        return $html;
    }
}
