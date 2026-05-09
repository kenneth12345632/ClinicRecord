<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\ClinicRecord;
use App\Models\Medicine;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $role = strtolower((string) (Auth::user()->role ?? 'doctor'));
        if ($role !== 'doctor') {
            abort(403);
        }

        $totalPatientRecords = ClinicRecord::select('first_name', 'last_name', 'birthday')
            ->groupBy('first_name', 'last_name', 'birthday')
            ->get()
            ->count();

        $todayConsultations = ClinicRecord::whereDate('consultation_date', today())->count();
        $lowStockCount = Medicine::where('stock', '<', 10)->count();

        $recentRecords = ClinicRecord::query()
            ->forDoctorNurseDashboard()
            ->get()
            ->unique(fn ($item) => $item->first_name . $item->last_name . $item->birthday)
            ->take(5);

        $weeklyPatientRecords = ClinicRecord::query()
            ->whereDate('consultation_date', '>=', now()->subDays(6))
            ->selectRaw('DATE(consultation_date) as day, COUNT(*) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        return view('doctor.dashboard.index', [
            'totalPatientRecords' => $totalPatientRecords,
            'todayConsultations' => $todayConsultations,
            'lowStockCount' => $lowStockCount,
            'recentRecords' => $recentRecords,
            'weeklyPatientRecords' => $weeklyPatientRecords,
            'isDoctorAvailable' => (bool) (Auth::user()?->is_doctor_available),
        ]);
    }
}

