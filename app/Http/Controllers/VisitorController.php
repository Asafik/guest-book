<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visitor;

class VisitorController extends Controller
{
    public function index()
    {
        return view('visitor');
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name'    => 'required|string|max:255',
            'institution'  => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'purpose'      => 'required|in:coordination,audience,monitoring,meeting,visit,other',
            'meet_with'    => 'nullable|string|max:255',
            'notes'        => 'nullable|string',
            'photo'        => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $data = $request->only([
            'full_name', 'institution', 'phone_number',
            'purpose', 'meet_with', 'notes'
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('visitors/photos', 'public');
        }

        Visitor::create($data);

        return response()->json(['success' => true]);
    }
}
