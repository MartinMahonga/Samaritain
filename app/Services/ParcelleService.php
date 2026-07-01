<?php

namespace App\Services;

use App\Models\Parcelle;
use App\Models\ParcelleImage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ParcelleService
{
    public function getParcelles(array $filters = [], int $perPage = 12)
    {
        $query = Parcelle::with(['images', 'imagePrincipale']);

        if (!empty($filters['ville'])) {
            $query->where('ville', 'like', '%' . $filters['ville'] . '%');
        }

        if (!empty($filters['quartier'])) {
            $query->where('quartier', 'like', '%' . $filters['quartier'] . '%');
        }

        if (!empty($filters['statut'])) {
            $query->where('statut', $filters['statut']);
        }

        if (isset($filters['prix_min'])) {
            $query->where('prix', '>=', $filters['prix_min']);
        }

        if (isset($filters['prix_max'])) {
            $query->where('prix', '<=', $filters['prix_max']);
        }

        if (isset($filters['superficie_min'])) {
            $query->where('superficie', '>=', $filters['superficie_min']);
        }

        if (isset($filters['superficie_max'])) {
            $query->where('superficie', '<=', $filters['superficie_max']);
        }

        if (array_key_exists('viabilisee', $filters) && $filters['viabilisee'] !== '') {
            $query->where('viabilisee', (bool) $filters['viabilisee']);
        }

        return $query->latest()->paginate($perPage);
    }

    public function getParcelle(int $id): Parcelle
    {
        return Parcelle::with(['images', 'imagePrincipale'])->findOrFail($id);
    }

    public function createParcelle(array $data, array $images = []): Parcelle
    {
        $reference = 'PARC-' . date('Y') . '-' . strtoupper(bin2hex(random_bytes(3)));

        return DB::transaction(function () use ($data, $images, $reference) {
            $parcelle = Parcelle::create([
                ...$data,
                'created_by' => Auth::id(),
                'reference' => $reference,
            ]);

            $this->storeImages($parcelle, $images);

            return $parcelle->load('images');
        });
    }

    public function updateParcelle(Parcelle $parcelle, array $data, array $images = []): Parcelle
    {
        return DB::transaction(function () use ($parcelle, $data, $images) {
            $parcelle->update($data);
            $this->storeImages($parcelle, $images);
            return $parcelle->load('images');
        });
    }

    public function deleteParcelle(Parcelle $parcelle): void
    {
        $parcelle->delete();
    }

    public function deleteImage(int $imageId): void
    {
        $image = ParcelleImage::findOrFail($imageId);
        $wasPrincipale = $image->principale;
        $parcelleId = $image->parcelle_id;

        app('filesystem')->disk('public')->delete($image->path);
        $image->delete();

        if ($wasPrincipale) {
            ParcelleImage::where('parcelle_id', $parcelleId)
                ->first()
                ?->update(['principale' => true]);
        }
    }

    protected function storeImages(Parcelle $parcelle, array $images): void
    {
        foreach ($images as $index => $image) {
            if (! $image instanceof UploadedFile) {
                continue;
            }

            $path = $image->store('parcelles', 'public');
            ParcelleImage::create([
                'parcelle_id' => $parcelle->id,
                'path' => $path,
                'url' => asset('storage/' . $path),
                'principale' => $index === 0 && ! $parcelle->images()->where('principale', true)->exists(),
            ]);
        }
    }
}
