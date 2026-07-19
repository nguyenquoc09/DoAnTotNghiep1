<?php
namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules() { return ['amount' => 'required|numeric|min:1', 'payment_method' => 'required|in:cash,bank_transfer,card', 'transaction_reference' => 'nullable|string|max:100', 'note' => 'nullable|string|max:500']; }
}
