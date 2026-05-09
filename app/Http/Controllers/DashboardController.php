<?php

namespace App\Http\Controllers;

use App\Models\ClinicRecord;
use App\Models\Medicine;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Unique patient record identities: distinct name + birthday
        $totalPatientRecords = ClinicRecord::select('first_name', 'last_name', 'birthday')
            ->groupBy('first_name', 'last_name', 'birthday')
            ->get()
            ->count();

        // 2. TODAY'S VISITS: Counts every consultation that happened today
        $todayConsultations = ClinicRecord::whereDate('consultation_date', today())->count();

        // 3. LOW STOCK: Checks for medicines with low inventory
        $lowStockCount = Medicine::where('stock', '<', 10)->count();

        // 4. RECENT ACTIVITY: Gets the latest unique consultations
        $recentRecords = ClinicRecord::query()
            ->orderBy('consultation_date', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->unique(function ($item) {
                return $item->first_name . $item->last_name . $item->birthday;
            })
            ->take(5);
return view('dashboard', [
        'totalPatientRecords' => $totalPatientRecords,
        'todayConsultations' => $todayConsultations,
        'lowStockCount'      => $lowStockCount,
        'recentRecords'      => $recentRecords,
    ]);
    }
}