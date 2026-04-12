<?php

namespace App\Http\Controllers;

use App\Models\Reunion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            $stats = [
                'total_reunions' => Reunion::count(),
                'pending_reunions' => Reunion::where('status', 'planifiee')->count(),
                'total_users' => User::count(),
            ];
            $recent_reunions = Reunion::latest()->take(5)->get();
        } else {
            $stats = [
                'my_reunions' => $user->attendedReunions()->count(),
                'pending_invitations' => $user->attendedReunions()->wherePivot('response_status', 'pending')->count(),
            ];
            $recent_reunions = $user->attendedReunions()->latest()->take(5)->get();
        }

        return view('dashboard', compact('stats', 'recent_reunions'));
    }
}
