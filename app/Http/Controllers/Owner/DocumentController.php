<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\StoreDocumentRequest;
use App\Models\Document;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Document::class);

        $properties = Property::where('created_by', auth()->id())->get(['id', 'title']);

        $query = Document::where('created_by', auth()->id())->with('property:id,title');

        if ($request->filled('property_id')) {
            $query->where('property_id', $request->property_id);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $documents = $query->latest()->paginate(20)->withQueryString();

        $stats = Document::where('created_by', auth()->id())
            ->selectRaw('COUNT(*) as total_count, COALESCE(SUM(file_size), 0) as total_size')
            ->first();

        $totalDocuments = $stats->total_count ?? 0;
        $totalSize = $stats->total_size ?? 0;

        return view('pages.owner.documents.index', compact(
            'documents', 'properties', 'totalSize', 'totalDocuments'
        ));
    }

    public function store(StoreDocumentRequest $request)
    {
        Gate::authorize('create', Document::class);

        $data = $request->validated();
        $data['created_by'] = auth()->id();

        $file = $request->file('document_file');
        $data['file_path'] = $file->store('documents/owner');
        $data['file_size'] = $file->getSize();

        unset($data['document_file']);

        Document::create($data);

        return redirect()->route('owner.documents.index')
            ->with('success', 'Document uploadé avec succès.');
    }

    public function download(Document $document)
    {
        Gate::authorize('view', $document);

        if (! Storage::exists($document->file_path)) {
            abort(404, 'Fichier introuvable.');
        }

        $filename = str_replace(['/', '\\'], '-', $document->name);

        return Storage::download($document->file_path, $filename);
    }

    public function destroy(Document $document)
    {
        Gate::authorize('delete', $document);

        Storage::delete($document->file_path);
        $document->delete();

        return redirect()->route('owner.documents.index')
            ->with('success', 'Document supprimé.');
    }
}
