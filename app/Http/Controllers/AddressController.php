<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Address;
use App\Models\Barangay;
use App\Models\Municipality;
use App\Models\Province;

class AddressController extends Controller
{
    public function create()
    {
        $barangays = Barangay::all();
        $municipalities = Municipality::all();
        $provinces = Province::all();

        return view(
            'user.address_create',
            compact('barangays', 'provinces', 'municipalities')
        );
    }

    public function store(Request $request)
    {
        $this->authorize('store', Address::class);

        $request->validate([
            'barangay_id' => 'required|exists:barangays,id',
            'house_number' =>  'nullable|string|min:2|max:255',
            'subdivision' =>  'nullable|string|min:2|max:255',
            'street' => 'nullable|string|min:2|max:255',
            'zip_code' => 'required|string|min:3|max:50'
        ]);

        Address::create([
            'user_id' => Auth::id(),
            'barangay_id' => $request->barangay_id,
            'house_number' => $request->house_number,
            'subdivision' => $request->subdivision,
            'street' => $request->street,
            'zip_code' => $request->zip_code,
        ]);

        $redirectUrl = session('after_address_redirect', route('user.index'));
        if ($redirectUrl) {
            session()->forget('after_address_redirect');
            return redirect($redirectUrl)
                ->with('success', 'Address added successfully.');
        }

        return redirect()->route('user.index')
            ->with('success', 'Address added successfully.');
    }

    public function destroy(Address $address)
    {
        $this->authorize('destroy', $address);

        $address->delete();
        return redirect()->route('user.index')
            ->with('success', 'Address deleted successfully.');
    }
}
