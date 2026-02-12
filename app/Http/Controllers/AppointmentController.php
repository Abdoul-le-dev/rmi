<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Role;
use App\Notifications\AppointmentRequested;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
 
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'full_name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'appointment_date' => 'required|date|after:now',
            'duration_minutes' => 'required|integer|min:15|max:180',
        ], [
            'email.required' => 'L\'email est obligatoire',
            'email.email' => 'L\'email doit être valide',
            'full_name.required' => 'Le nom complet est obligatoire',
            'subject.required' => 'Le sujet est obligatoire',
            'message.required' => 'Le message est obligatoire',
            'appointment_date.required' => 'La date du rendez-vous est obligatoire',
            'appointment_date.after' => 'La date doit être dans le futur',
            'duration_minutes.required' => 'La durée est obligatoire',
            'duration_minutes.min' => 'La durée minimum est de 15 minutes',
            'duration_minutes.max' => 'La durée maximum est de 3 heures',
        ]);

        $appointment = Appointment::create([
            'email' => $validated['email'],
            'full_name' => $validated['full_name'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'appointment_date' => $validated['appointment_date'],
            'duration_minutes' => $validated['duration_minutes'],
            'status' => 'pending',
        ]);

        // Notifier les administrateurs
        // $admins = User::where('role_name', Role::$admin)->get();
        // foreach ($admins as $admin) {
        //     $admin->notify(new AppointmentRequested($appointment));
        // }

        return back()->with('success', 'Votre demande de rendez-vous a été envoyée avec succès. Vous recevrez une confirmation par email.');
    }

}