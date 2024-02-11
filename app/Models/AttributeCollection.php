<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttributeCollection extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'languages',
        'json_schema',
        'json_ui_schema',
        'application_id',
        'scene_id',
        'voice_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'languages' => 'array',
        'json_schema' => 'array',
        'json_ui_schema' => 'array',
        'application_id' => 'integer',
        'scene_id' => 'integer',
        'voice_id' => 'integer',
    ];

    public function attributeLists(): HasMany
    {
        return $this->hasMany(AttributeList::class);
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function scene(): BelongsTo
    {
        return $this->belongsTo(Scene::class);
    }

    public function voice(): BelongsTo
    {
        return $this->belongsTo(Voice::class);
    }
}
