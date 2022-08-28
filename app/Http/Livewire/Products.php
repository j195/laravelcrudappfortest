<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Product;

class Products extends Component
{
    use WithFileUploads;
	public $products, $name, $amount, $product_upc, $product_id, $image;
    public $isModalOpen = 0;
    public function render()
    {
        $this->products = Product::all();
        return view('livewire.product');
    }
    public function create()
    {
        $this->resetCreateForm();
        $this->openModalPopover();
    }

    public function openModalPopover()
    {
        $this->isModalOpen = true;
    }

    public function closeModalPopover()
    {
        $this->isModalOpen = false;
    }
 
    private function resetCreateForm(){
        $this->name = '';
        $this->amount = '';
        $this->product_upc = '';
        $this->image = '';
    }
    
    public function store()
    {
        $this->validate([
            'name' => 'required',
            'amount' => 'required',
            'product_upc' => 'required',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        $filename = $this->image->store('photos');
        Product::updateOrCreate(['id' => $this->product_id], [
            'name' => $this->name,
            'amount' => $this->amount,
            'product_upc' => $this->product_upc,
            'image' => $filename,
        ]);
 
        session()->flash('message', $this->product_id ? 'Product updated.' : 'Product created.');
 
        $this->closeModalPopover();
        $this->resetCreateForm();
    }
 
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $this->product_id = $id;
        $this->name = $product->name;
        $this->amount = $product->amount;
        $this->product_upc = $product->product_upc;
        $this->image = $product->image;
    
        $this->openModalPopover();
    }
    
    public function delete($id)
    {
        Student::find($id)->delete();
        session()->flash('message', 'Product deleted.');
    }
}
