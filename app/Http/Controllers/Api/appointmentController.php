<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DateTime;

class AppointmentController extends Controller
{
    public function getClinics()
    {
        $clinics = Clinic::all();
        return response()->json(['clinics' => $clinics], 200);
    }

    public function storeAppointment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'clinic_id' => 'required|exists:clinics,id',
            'date' => 'required|date|after:now',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $appointment = Appointment::create([
            'clinic_id' => $request->clinic_id,
            'user_id' => Auth::guard('api')->user()->id,
            'date' => $request->date,
        ]);

        return response()->json(['message' => 'Appointment booked successfully!', 'appointment' => $appointment], 201);
    }



    public function getUserAppointments($userId)
    {
        $user = User::findOrFail($userId);
        $appointments = $user->appointments()->with('clinic')->get();
        $current_time = new DateTime();

        foreach ($appointments as $appointment) {
            $appointment->hasPassed = $appointment->date < $current_time;
        }

        return response()->json(['user' => $user, 'appointments' => $appointments], 200);
    }
}
