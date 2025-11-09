<?php

namespace App\Policies;

use App\Models\Recommandation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RecommandationPolicy
{
    public function view(User $user, Recommandation $recommandation)
    {
        return $user->id === $recommandation->its_id
            ? Response::allow()
            : Response::deny('Vous n\'êtes pas autorisé à voir cette recommandation.');
    }

    public function update(User $user, Recommandation $recommandation)
    {
        return $user->id === $recommandation->its_id
            ? Response::allow()
            : Response::deny('Vous n\'êtes pas autorisé à modifier cette recommandation.');
    }

    public function delete(User $user, Recommandation $recommandation)
    {
        return $user->id === $recommandation->its_id && $recommandation->statut === 'brouillon'
            ? Response::allow()
            : Response::deny('Vous n\'êtes pas autorisé à supprimer cette recommandation.');
    }
}
