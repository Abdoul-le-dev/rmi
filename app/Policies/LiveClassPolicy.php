<?php

namespace App\Policies;

use App\Models\LiveClass;
use App\User;

class LiveClassPolicy
{
    /**
     * Voir un live class
     */
    public function view(User $user, LiveClass $liveClass): bool
    {
        // L'instructeur peut toujours voir
        if ($liveClass->instructor_id === $user->id) {
            return true;
        }

        // Si public, tout le monde peut voir
        if ($liveClass->is_public) {
            return true;
        }

        // Sinon, seulement si inscrit
        return $liveClass->isEnrolled($user);
    }

    /**
     * Modifier un live class
     */
    public function update(User $user, LiveClass $liveClass): bool
    {
        return $liveClass->instructor_id === $user->id;
    }

    /**
     * Supprimer un live class
     */
    public function delete(User $user, LiveClass $liveClass): bool
    {
        return $liveClass->instructor_id === $user->id;
    }

    /**
     * Démarrer un live class
     */
    public function start(User $user, LiveClass $liveClass): bool
    {
        return $liveClass->instructor_id === $user->id;
    }

    /**
     * Terminer un live class
     */
    public function end(User $user, LiveClass $liveClass): bool
    {
        return $liveClass->instructor_id === $user->id;
    }
}