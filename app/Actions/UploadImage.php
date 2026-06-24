<?php

namespace App\Actions;

use App\Models\Property;

class UploadImage
{
    public function handle(Property $property, array $files): void
    {
        $folder = 'images/properties';

        foreach ($files as $imageFile) {
            $extension = $imageFile->getClientOriginalExtension();
            $filename = uniqid('prop_', true).'.'.$extension;
            $path = $imageFile->storeAs($folder, $filename, 'public');

            $property->images()->create([
                'image_url' => $path,
                'cover_image' => false,
            ]);
        }
    }
}
