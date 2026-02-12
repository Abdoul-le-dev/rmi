<?php

namespace App\Http\Controllers;


use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class InstructorAppointmentController extends Controller
{
    /**
     * Afficher les rendez-vous de l'instructeur
     */
    public function index()
    {
        $upcomingAppointments = Appointment::where('instructor_id', Auth::id())
            ->upcoming()
            ->orderBy('appointment_date', 'asc')
            ->get();

        $pastAppointments = Appointment::where('instructor_id', Auth::id())
            ->orderBy('appointment_date', 'desc')
            ->paginate(20);

        return view('web.default.panel.appointment.index', compact('upcomingAppointments', 'pastAppointments'));
    }

    /**
     * Afficher les détails d'un rendez-vous
     */
    public function show($id)
    {
        $appointment = Appointment::where('instructor_id', Auth::id())
            ->findOrFail($id);

        return view('web.default.panel.appointment.show', compact('appointment'));
    }
}