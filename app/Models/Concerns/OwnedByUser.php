<?php

// app/Models/Concerns/OwnedByUser.php
namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait OwnedByUser
{
    protected static function bootOwnedByUser(): void
    {
        // Assigner automatiquement le propriétaire à la création
        static::creating(function ($model) {
            if (auth()->check() && empty($model->user_id)) {
                $model->user_id = auth()->id();
            }
        });

        // Filtrer toutes les requêtes vers "seulement mes ressources" pour les admins non-superadmins
        if (!app()->runningInConsole()) {
            static::addGlobalScope('owned_by_user', function (Builder $query) {
                $user = auth()->user();
                if (!$user) return;

                // Laisse les étudiants tranquilles, et donne un “passe-droit” éventuel au superadmin
                if ($user->role === 'student') return;
                if (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) return;

                // Admin classique : ne voit QUE ses ressources
                $table = $query->getModel()->getTable();
                $query->where($table.'.user_id', $user->id);
            });
        }
    }

    public function owner()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}

