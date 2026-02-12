<?php

namespace App\Http\Controllers;


use App\Models\Appointment;
use App\User;
use App\Services\JitsiService;
use App\Mail\AppointmentApproved;
use App\Mail\AppointmentRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AppointmentAdminController extends Controller
{
    protected $jitsiService;

    public function __construct(JitsiService $jitsiService)
    {
        $this->jitsiService = $jitsiService;
    }

    /**
     * Liste des rendez-vous pour l'admin
     */
    public function index(Request $request)
    {
        $query = Appointment::with(['instructor', 'approvedBy']);

        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('appointment_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('appointment_date', '<=', $request->date_to);
        }

        $appointments = $query->orderBy('appointment_date', 'desc')->paginate(15);
        $pendingCount = Appointment::pending()->count();

        return view('admin.appointment.index', compact('appointments', 'pendingCount'));
    }

    /**
     * Afficher les détails d'un rendez-vous
     */
    public function show($id)
    {
        $appointment = Appointment::with(['instructor', 'approvedBy'])->findOrFail($id);
        $instructors = User::where('role_name', 'teacher')->get();

        return view('admin.appointment.show', compact('appointment', 'instructors'));
    }

    /**
     * Approuver un rendez-vous
     */
    public function approve(Request $request, $id)
    {
        $validated = $request->validate([
            'instructor_id' => 'required|exists:users,id',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $appointment = Appointment::findOrFail($id);

        if ($appointment->status !== 'pending') {
            return back()->with('error', 'Ce rendez-vous a déjà été traité.');
        }

        // Générer un nom de salle unique
        $roomName = 'rmi-appointment-' . $appointment->id . '-' . Str::random(8);

        // Générer les tokens Jitsi
        // $instructor = User::findOrFail($validated['instructor_id']);
        
        // $instructorToken = $this->jitsiService->generateInstructorToken(
        //     $roomName,
        //     $instructor->name,
        //     $instructor->email,
        //     $appointment->duration_minutes,
        //     false
        // );

        // $studentToken = $this->jitsiService->generateStudentToken(
        //     $roomName,
        //     $appointment->user->name,
        //     $appointment->user->email,
        //     $appointment->duration_minutes
        // );

        // Mettre à jour le rendez-vous
        $appointment->update([
            'instructor_id' => $validated['instructor_id'],
            'status' => 'approved',
            'admin_notes' => $validated['admin_notes'],
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'meeting_room' => $roomName,
            // 'participant_meeting_url' => $studentToken['url'],
            // 'participant_token' => $studentToken['token'],
            // 'moderator_meeting_url' => $instructorToken['url'],
            // 'moderator_token' => $instructorToken['token'],
        ]);

        // Envoyer l'email de confirmation
        Mail::to($appointment->email)
            ->send(new AppointmentApproved($appointment));

        $appointment->update(['confirmation_sent' => true]);

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Le rendez-vous a été approuvé et l\'utilisateur a reçu un email de confirmation.');
    }

    /**
     * Rejeter un rendez-vous
     */
    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'admin_notes' => 'required|string|max:500',
        ], [
            'admin_notes.required' => 'Veuillez indiquer la raison du rejet',
        ]);

        $appointment = Appointment::findOrFail($id);

        if ($appointment->status !== 'pending') {
            return back()->with('error', 'Ce rendez-vous a déjà été traité.');
        }

        $appointment->update([
            'status' => 'rejected',
            'admin_notes' => $validated['admin_notes'],
            'approved_by' => Auth::id(),
        ]);

        // Envoyer l'email de rejet
        Mail::to($appointment->email)
            ->send(new AppointmentRejected($appointment));

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Le rendez-vous a été rejeté.');
    }

    /**
     * Marquer comme terminé
     */
    public function markCompleted($id)
    {
        $appointment = Appointment::findOrFail($id);

        if ($appointment->status !== 'approved') {
            return back()->with('error', 'Seuls les rendez-vous approuvés peuvent être marqués comme terminés.');
        }

        $appointment->update(['status' => 'completed']);

        return back()->with('success', 'Le rendez-vous a été marqué comme terminé.');
    }
}