<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\Listing
 *
 * @property int                                                                                   $id
 * @property string                                                                                $title
 * @property string                                                                                $description
 * @property int                                                                                   $price
 * @property int                                                                                   $user_id
 * @property \Illuminate\Support\Carbon|null                                                       $created_at
 * @property \Illuminate\Support\Carbon|null                                                       $updated_at
 * @method static \Database\Factories\ListingFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Listing newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Listing newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Listing query()
 * @method static \Illuminate\Database\Eloquent\Builder|Listing whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Listing whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Listing whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Listing wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Listing whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Listing whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Listing whereUserId($value)
 * @mixin \Eloquent
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|Media[] $media
 * @property-read int|null                                                                         $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Category[]                  $categories
 * @property-read int|null                                                                         $categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Color[]                     $colors
 * @property-read int|null                                                                         $colors_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Size[]                      $sizes
 * @property-read int|null                                                                         $sizes_count
 * @property-read \App\Models\User                                                                 $user
 */
class Listing extends Model implements HasMedia
{
    use HasFactory;

    use InteractsWithMedia;

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->width(200)->height(200);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function colors()
    {
        return $this->belongsToMany(Color::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sizes()
    {
        return $this->belongsToMany(Size::class);
    }
}
