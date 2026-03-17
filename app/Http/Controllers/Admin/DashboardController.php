<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Visitor;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalVisitors     = Visitor::count();
        $todayVisitors     = Visitor::whereDate('created_at', Carbon::today())->count();
        $monthVisitors     = Visitor::whereMonth('created_at', Carbon::now()->month)
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->count();
        $totalInstitutions = Visitor::whereNotNull('institution')
                                    ->distinct('institution')
                                    ->count('institution');
        $latestVisitors    = Visitor::latest()->take(5)->get();

        $weeklyData = collect(range(6, 0))->map(function ($day) {
            $date = Carbon::now()->subDays($day);
            return [
                'label' => $date->translatedFormat('D'),
                'count' => Visitor::whereDate('created_at', $date)->count(),
            ];
        });

        $purposeData = Visitor::selectRaw('purpose, count(*) as total')
                              ->groupBy('purpose')
                              ->get();

        return view('admin.dashboard', compact(
            'totalVisitors',
            'todayVisitors',
            'monthVisitors',
            'totalInstitutions',
            'latestVisitors',
            'weeklyData',
            'purposeData'
        ));
    }
}
