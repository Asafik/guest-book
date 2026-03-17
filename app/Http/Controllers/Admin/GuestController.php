<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Visitor;

class GuestController extends Controller
{
    public function index(Request $request)
    {
        $perPage   = $request->input('per_page', 10);
        $search    = $request->input('search');
        $sort      = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');

        $allowedSorts = ['full_name', 'institution', 'purpose', 'created_at'];
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }

        $direction = $direction === 'asc' ? 'asc' : 'desc';

        $guests = Visitor::query()
            ->when($search, function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%");
            })
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->appends($request->only(['search', 'per_page', 'sort', 'direction']));

        return view('admin.guest', compact('guests'));
    }

    public function destroy($id)
    {
        $guest = Visitor::findOrFail($id);

        if ($guest->photo) {
            \Storage::disk('public')->delete($guest->photo);
        }

        $guest->delete();

        return response()->json(['success' => true]);
    }
}
