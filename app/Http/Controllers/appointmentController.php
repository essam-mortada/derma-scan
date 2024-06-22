<?php

// app/Http/Controllers/AppointmentController.php
namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function create()
    {
        $clinics = Clinic::all();
        return view('make-appointment', compact('clinics'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'clinic_id' => 'required|exists:clinics,id',
            'date' => 'required|date|after:now',
        ]);

        Appointment::create([
            'clinic_id' => $request->clinic_id,
            'user_id' => Auth::id(),
            'date' => $request->date,
        ]);

        return redirect()->back()->with('success', 'Appointment booked successfully!');
    }

    public function index()
    {
        $appointments = Auth::user()->appointments->with('clinic')->get();
        return view('appointments.index', compact('appointments'));
    }

    public function userAppointments($userId)
    {
        $user = User::findOrFail($userId);
        $appointments = $user->appointments()->with('clinic')->get();
        foreach($appointments as $appointment){
        $appointment_time = $appointment->date;
        $current_time = new DateTime();
        }
        return view('all-appointments', compact('user', 'appointments'));
    }
}

