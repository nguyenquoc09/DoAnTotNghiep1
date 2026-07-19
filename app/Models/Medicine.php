<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medicine extends Model
{
    use SoftDeletes;
    const STATUS_ACTIVE = 'active'; const STATUS_INACTIVE = 'inactive';
    protected $fillable = ['medicine_code', 'name', 'active_ingredient', 'dosage_form', 'strength', 'unit', 'manufacturer', 'purchase_price', 'selling_price', 'stock_quantity', 'minimum_stock', 'usage_instruction', 'contraindication', 'status'];
    protected $casts = ['purchase_price' => 'decimal:2', 'selling_price' => 'decimal:2'];
    public function prescriptionItems() { return $this->hasMany(PrescriptionItem::class); }
}
