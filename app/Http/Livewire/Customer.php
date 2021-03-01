<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Customers;
use Illuminate\Http\Request;

class Customer extends Component
{
    use WithPagination;

    public $customerId, $name ,$email, $phone ,$address, $status;
    public $search;
    protected $updatesQueryString = ['search'];


    public function mount(): void
    {
        $this->search = request()->query('search', $this->search);
    }

    public function render()
    {
     ///   $this->customers = Customers::query()
        /// ->where('user_id', Auth::id());

        $this->customers = Customers::all();
        return view('livewire.customer',[
            'customers' => $this->search === null ?
            Customers::paginate() :
            Customers::where('name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%')
                ->orderBy('created_at', 'desc')
        ]);
    }

    public function store()
    {
        $validatedDate = $this->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
        ]);
        Customers::create($validatedDate);
        return back()->with('message', 'Customers Created Successfully.');
       
    }

    public function edit($id)
    {
        $customers = Customers::findOrFail($id);
        $validatedDate = $this->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
        ]);
        Customers::create($validatedDate);
        return back()->with('message', 'Customers Updated Successfully.');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function delete($id)
    {
        Customers::find($id)->delete();
        return back()->with('message', 'Intervention Deleted Successfully.');
    }

}