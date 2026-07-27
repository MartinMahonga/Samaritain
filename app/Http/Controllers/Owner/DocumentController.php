<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\StoreDocumentRequest;
use App\Models\Document;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $propertyIds = Property::where('created_by', auth()->id())->pluck('id');
        $properties = Property::where('created_by', auth()->id())->get();

        $query = Document::where('created_by', auth()->id())->with('property');

        if ($request->filled('property_id')) {
            $query->where('property_id', $request->property_id);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $documents = $query->latest()->paginate(20)->withQueryString();

        $totalSize = Document::where('created_by', auth()->id())->sum('file_size');
        $totalDocuments = Document::where('created_by', auth()->id())->count();

        return view('pages.owner.documents.index', compact(
            'documents', 'properties', 'totalSize', 'totalDocuments'
        ));
    }

    public function store(StoreDocumentRequest $request)
    {
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
        if ($document->created_by !== auth()->id()) {
            abort(403);
        }

        if (! Storage::exists($document->file_path)) {
            abort(404, 'Fichier introuvable.');
        }

        return Storage::download($document->file_path, $document->name);
    }

    public function destroy(Document $document)
    {
        if ($document->created_by !== auth()->id()) {
            abort(403);
        }

        Storage::delete($document->file_path);
        $document->delete();

        return redirect()->route('owner.documents.index')
            ->with('success', 'Document supprimé.');
    }
}
