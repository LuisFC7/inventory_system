<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'items';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'item_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_activo_fijo',
        'item_nombre',
        'item_tag',
        'item_descripcion',
        'item_size',
        'item_origen',
        'item_destino',
        'item_fecha_entrada',
        'item_fecha_salida',
        'item_observaciones',
        'item_status',
        'item_user_id',
        'item_fecha_modificacion',
        'item_activity',
        'item_user_modifica_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'item_fecha_entrada' => 'datetime',
        'item_fecha_salida' => 'datetime',
        'item_fecha_modificacion' => 'datetime'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * Get the user that owns the item.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'item_user_id', 'user_id');
    }

    public function userModifier(){
        return $this->belongsTo(User::class, 'item_user_modifica_id', 'user_id');
    }

    /**
     * Scope a query to only include active items.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('item_status', 'activo');
    }

    /**
     * Get the item's status in a readable format.
     *
     * @return string
     */
    public function getStatusAttribute()
    {
        return ucfirst($this->item_status);
    }
}