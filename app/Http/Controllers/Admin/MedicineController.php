<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index() { $query = Medicine::query(); if (request('q')) { $query->where('name', 'like', '%' . request('q') . '%')->orWhere('medicine_code', 'like', '%' . request('q') . '%'); } return view('admin.medicines', ['items' => $query->paginate(15)->withQueryString()]); }
    public function store(Request $request) { Medicine::create($this->data($request)); return back()->with('success', 'Đã thêm thuốc.'); }
    public function update(Request $request, Medicine $medicine) { $medicine->update($this->data($request, $medicine->id)); return back()->with('success', 'Đã cập nhật thuốc.'); }
    private function data(Request $request, $id = null) { return $request->validate(['medicine_code' => 'required|max:20|unique:medicines,medicine_code,' . ($id ?: 'NULL'), 'name' => 'required|max:150', 'active_ingredient' => 'nullable|max:255', 'dosage_form' => 'nullable|max:100', 'strength' => 'nullable|max:100', 'unit' => 'required|max:30', 'manufacturer' => 'nullable|max:255', 'purchase_price' => 'required|numeric|min:0', 'selling_price' => 'required|numeric|min:0', 'stock_quantity' => 'required|integer|min:0', 'minimum_stock' => 'required|integer|min:0', 'usage_instruction' => 'nullable|string', 'contraindication' => 'nullable|string', 'status' => 'required|in:active,inactive']); }
}
