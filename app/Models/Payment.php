<?php
namespace App\Models;

use App\Support\GeneratesCode;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use GeneratesCode;
    const METHOD_CASH = 'cash'; const METHOD_BANK_TRANSFER = 'bank_transfer'; const METHOD_CARD = 'card'; const STATUS_COMPLETED = 'completed';
    protected $fillable = ['payment_code', 'invoice_id', 'amount', 'payment_method', 'transaction_reference', 'payment_date', 'received_by', 'status', 'note'];
    protected $casts = ['payment_date' => 'datetime'];
    protected function getCodeColumn() { return 'payment_code'; }
    protected function getCodePrefix() { return 'TT'; }
    public function invoice() { return $this->belongsTo(Invoice::class); }
    public function receiver() { return $this->belongsTo(User::class, 'received_by'); }
}
