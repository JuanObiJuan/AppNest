<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttributeList extends Model
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
        'language_key',
        'json_schema',
        'json_ui_schema',
        'json_data',
        'attribute_collection_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'json_schema' => 'array',
        'json_ui_schema' => 'array',
        'json_data' => 'array',
        'attribute_collection_id' => 'integer',
    ];

    public function attributeCollection(): BelongsTo
    {
        return $this->belongsTo(AttributeCollection::class);
    }
}
