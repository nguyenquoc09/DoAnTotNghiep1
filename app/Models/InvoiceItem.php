<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    const TYPE_SERVICE = 'service'; const TYPE_MEDICINE = 'medicine';
    protected $fillable = ['invoice_id', 'item_type', 'reference_id', 'description', 'quantity', 'unit_price', 'amount'];
    public function invoice() { return $this->belongsTo(Invoice::class); }
}
