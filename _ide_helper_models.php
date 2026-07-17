<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $contactable_type
 * @property int $contactable_id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string $subject
 * @property string $message
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property bool $is_read
 * @property \Illuminate\Support\Carbon|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $contactable
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AgencyContact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AgencyContact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AgencyContact query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AgencyContact whereContactableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AgencyContact whereContactableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AgencyContact whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AgencyContact whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AgencyContact whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AgencyContact whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AgencyContact whereIsRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AgencyContact whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AgencyContact whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AgencyContact wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AgencyContact whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AgencyContact whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AgencyContact whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AgencyContact whereUserAgent($value)
 */
	class AgencyContact extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $email
 * @property int $role_id
 * @property string $token
 * @property \Illuminate\Support\Carbon $expires_at
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $accepted_at
 * @property \Illuminate\Support\Carbon|null $cancelled_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $creator
 * @property-read \Spatie\Permission\Models\Role $role
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AgencyInvitation accepted()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AgencyInvitation expired()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AgencyInvitation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AgencyInvitation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AgencyInvitation pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AgencyInvitation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AgencyInvitation whereAcceptedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AgencyInvitation whereCancelledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AgencyInvitation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AgencyInvitation whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AgencyInvitation whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AgencyInvitation whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AgencyInvitation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AgencyInvitation whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AgencyInvitation whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AgencyInvitation whereUpdatedAt($value)
 */
	class AgencyInvitation extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Amenity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Amenity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Amenity query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Amenity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Amenity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Amenity whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Amenity whereUpdatedAt($value)
 */
	class Amenity extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property int $city_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\City $city
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Arrondissement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Arrondissement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Arrondissement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Arrondissement whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Arrondissement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Arrondissement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Arrondissement whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Arrondissement whereUpdatedAt($value)
 */
	class Arrondissement extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $user_id
 * @property string $business_name
 * @property string $slug
 * @property string $profession
 * @property string|null $bio
 * @property string $phone
 * @property string|null $whatsapp
 * @property string|null $website
 * @property string|null $avatar
 * @property string|null $cover
 * @property string|null $city
 * @property int|null $experience
 * @property bool|null $verified
 * @property bool|null $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $created_by
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ArtisanCategory> $categories
 * @property-read int|null $categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ArtisanContact> $contacts
 * @property-read int|null $contacts_count
 * @property-read \App\Models\User|null $createdBy
 * @property-read float $average_rating
 * @property-read int|null $reviews_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ArtisanProject> $projects
 * @property-read int|null $projects_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ArtisanReview> $reviews
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Artisan active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Artisan byCategory(int $categoryId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Artisan byCity(string $city)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Artisan byMinRating(int $rating)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Artisan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Artisan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Artisan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Artisan search($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Artisan verified()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Artisan whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Artisan whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Artisan whereBusinessName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Artisan whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Artisan whereCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Artisan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Artisan whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Artisan whereExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Artisan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Artisan whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Artisan wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Artisan whereProfession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Artisan whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Artisan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Artisan whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Artisan whereVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Artisan whereWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Artisan whereWhatsapp($value)
 */
	class Artisan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Artisan> $artisans
 * @property-read int|null $artisans_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanCategory whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanCategory whereUpdatedAt($value)
 */
	class ArtisanCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $artisan_id
 * @property string $name
 * @property string|null $email
 * @property string $phone
 * @property string $message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Artisan $artisan
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanContact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanContact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanContact query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanContact whereArtisanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanContact whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanContact whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanContact whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanContact whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanContact whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanContact wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanContact whereUpdatedAt($value)
 */
	class ArtisanContact extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $artisan_id
 * @property string $title
 * @property string|null $description
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Artisan $artisan
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ArtisanProjectImage> $images
 * @property-read int|null $images_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanProject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanProject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanProject query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanProject whereArtisanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanProject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanProject whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanProject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanProject whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanProject whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanProject whereUpdatedAt($value)
 */
	class ArtisanProject extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $artisan_project_id
 * @property string $image_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $image_url
 * @property-read \App\Models\ArtisanProject $project
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanProjectImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanProjectImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanProjectImage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanProjectImage whereArtisanProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanProjectImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanProjectImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanProjectImage whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanProjectImage whereUpdatedAt($value)
 */
	class ArtisanProjectImage extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $artisan_id
 * @property int $user_id
 * @property int $rating
 * @property string|null $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Artisan $artisan
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanReview newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanReview newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanReview query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanReview whereArtisanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanReview whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanReview whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanReview whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanReview whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanReview whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtisanReview whereUserId($value)
 */
	class ArtisanReview extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $commentaire
 * @property int $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Avis newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Avis newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Avis query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Avis whereCommentaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Avis whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Avis whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Avis whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Avis whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Avis whereUserId($value)
 */
	class Avis extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereUpdatedAt($value)
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Arrondissement> $arrondissements
 * @property-read int|null $arrondissements_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereUpdatedAt($value)
 */
	class City extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $property_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite wherePropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereUserId($value)
 */
	class Favorite extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $parcel_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Parcelle $parcel
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParcelFavorite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParcelFavorite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParcelFavorite query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParcelFavorite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParcelFavorite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParcelFavorite whereParcelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParcelFavorite whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParcelFavorite whereUserId($value)
 */
	class ParcelFavorite extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $titre
 * @property string $slug
 * @property string|null $description
 * @property string $localisation
 * @property string $quartier
 * @property string $ville
 * @property float $superficie
 * @property float $prix
 * @property string $statut
 * @property int $views
 * @property string $reference
 * @property bool $viabilisee
 * @property string|null $titre_foncier
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $created_by
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AgencyContact> $agencyContacts
 * @property-read int|null $agency_contacts_count
 * @property-read \App\Models\User $creator
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $favoritedBy
 * @property-read int|null $favorited_by_count
 * @property-read \App\Models\ParcelleImage|null $imagePrincipale
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ParcelleImage> $images
 * @property-read int|null $images_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ParcelFavorite> $parcelFavorites
 * @property-read int|null $parcel_favorites_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parcelle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parcelle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parcelle query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parcelle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parcelle whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parcelle whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parcelle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parcelle whereLocalisation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parcelle wherePrix($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parcelle whereQuartier($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parcelle whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parcelle whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parcelle whereStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parcelle whereSuperficie($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parcelle whereTitre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parcelle whereTitreFoncier($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parcelle whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parcelle whereViabilisee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parcelle whereViews($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parcelle whereVille($value)
 */
	class Parcelle extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $parcelle_id
 * @property string $path
 * @property string $url
 * @property bool $principale
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Parcelle $parcelle
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParcelleImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParcelleImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParcelleImage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParcelleImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParcelleImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParcelleImage whereParcelleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParcelleImage wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParcelleImage wherePrincipale($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParcelleImage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParcelleImage whereUrl($value)
 */
	class ParcelleImage extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $uuid
 * @property string $holder_name
 * @property string $phone
 * @property string|null $email
 * @property int $allowed_visits
 * @property int $remaining_visits
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $expiration_date
 * @property string|null $qr_code_path
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PassScan> $scans
 * @property-read int|null $scans_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pass newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pass newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pass onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pass query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pass whereAllowedVisits($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pass whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pass whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pass whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pass whereExpirationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pass whereHolderName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pass whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pass wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pass whereQrCodePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pass whereRemainingVisits($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pass whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pass whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pass whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pass whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pass withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pass withoutTrashed()
 */
	class Pass extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $pass_id
 * @property int|null $visit_pass_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon $scanned_at
 * @property string $ip_address
 * @property string $user_agent
 * @property string|null $device_info
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Pass|null $pass
 * @property-read \App\Models\User $user
 * @property-read \App\Models\VisitPass|null $visitPass
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PassScan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PassScan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PassScan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PassScan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PassScan whereDeviceInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PassScan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PassScan whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PassScan wherePassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PassScan whereScannedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PassScan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PassScan whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PassScan whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PassScan whereVisitPassId($value)
 */
	class PassScan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $created_by
 * @property string $title
 * @property string $slug
 * @property string|null $description
 * @property int $price
 * @property int $surface
 * @property int $rooms
 * @property int $bedrooms
 * @property int|null $bathrooms
 * @property int $floor
 * @property int $furnished
 * @property string $address
 * @property numeric|null $latitude
 * @property numeric|null $longitude
 * @property \App\Enums\PropertyStatus $status
 * @property int $views
 * @property int $is_verify
 * @property int $is_active
 * @property int $verified
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $category_id
 * @property int|null $city_id
 * @property int|null $arrondissement_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AgencyContact> $agencyContacts
 * @property-read int|null $agency_contacts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Amenity> $amenities
 * @property-read int|null $amenities_count
 * @property-read \App\Models\Arrondissement|null $arrondissement
 * @property-read \App\Models\Category|null $category
 * @property-read \App\Models\City|null $city
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PropertyContact> $contacts
 * @property-read int|null $contacts_count
 * @property-read \App\Models\User $creator
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $favoritedBy
 * @property-read int|null $favorited_by_count
 * @property-read mixed $cover_image_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PropertyImage> $images
 * @property-read int|null $images_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VisitRequest> $visitRequests
 * @property-read int|null $visit_requests_count
 * @method static \Database\Factories\PropertyFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereArrondissementId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereBathrooms($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereBedrooms($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereFloor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereFurnished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereIsVerify($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereRooms($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereSurface($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereViews($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property withoutTrashed()
 */
	class Property extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \App\Models\User|null $creator
 * @property-read \App\Models\Property|null $property
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyContact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyContact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyContact query()
 */
	class PropertyContact extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $image_url
 * @property int $cover_image
 * @property int $property_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Property|null $property
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyImage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyImage whereCoverImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyImage whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyImage wherePropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyImage whereUpdatedAt($value)
 */
	class PropertyImage extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $transaction_id
 * @property int $user_id
 * @property string $status
 * @property int $amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $visit_pass_id
 * @property-read \App\Models\User $user
 * @property-read \App\Models\VisitPass|null $visitPass
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereVisitPassId($value)
 */
	class Transaction extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property bool $is_staff
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $password
 * @property string|null $provider_id
 * @property string|null $provider_name
 * @property string|null $provider_token
 * @property string|null $provider_refresh_token
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $profile_image
 * @property-read \App\Models\Artisan|null $artisan
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Avis> $avis
 * @property-read int|null $avis_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Property> $favorites
 * @property-read int|null $favorites_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Parcelle> $favoritesParcels
 * @property-read int|null $favorites_parcels_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ParcelFavorite> $parcelFavorites
 * @property-read int|null $parcel_favorites_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AgencyInvitation> $sentInvitations
 * @property-read int|null $sent_invitations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $teams
 * @property-read int|null $teams_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VisitPass> $visitPasses
 * @property-read int|null $visit_passes_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, bool $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, ?string $guard = null, bool $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User team($teams, bool $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsStaff($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereProfileImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereProviderName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereProviderRefreshToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereProviderToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, ?string $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTeam($teams)
 */
	class User extends \Eloquent implements \Illuminate\Contracts\Auth\MustVerifyEmail {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $uuid
 * @property int $user_id
 * @property int $property_id
 * @property string|null $transaction_id
 * @property string $holder_name
 * @property string $phone
 * @property string|null $email
 * @property string|null $comment
 * @property string $reference
 * @property int $amount
 * @property int $allowed_visits
 * @property int $remaining_visits
 * @property string $payment_status
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $paid_at
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property string|null $qr_code_path
 * @property string|null $pdf_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $expiration_date
 * @property-read \App\Models\Property|null $property
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PassScan> $scans
 * @property-read int|null $scans_count
 * @property-read \App\Models\Transaction|null $transaction
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitPass newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitPass newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitPass query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitPass whereAllowedVisits($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitPass whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitPass whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitPass whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitPass whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitPass whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitPass whereHolderName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitPass whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitPass wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitPass wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitPass wherePdfPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitPass wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitPass wherePropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitPass whereQrCodePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitPass whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitPass whereRemainingVisits($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitPass whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitPass whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitPass whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitPass whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitPass whereUuid($value)
 */
	class VisitPass extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $full_name
 * @property string $phone
 * @property string|null $city
 * @property string|null $property_category
 * @property int|null $property_id
 * @property string $preferred_date
 * @property string|null $message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Property|null $property
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitRequest whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitRequest whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitRequest whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitRequest wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitRequest wherePreferredDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitRequest wherePropertyCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitRequest wherePropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitRequest whereUpdatedAt($value)
 */
	class VisitRequest extends \Eloquent {}
}

