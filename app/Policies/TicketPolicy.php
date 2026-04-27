<?php
namespace App\Policies;

use App\Enum\RolesEnum;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketPolicy
{
    use HandlesAuthorization;

    public function before(User $user, string $ability): ?bool
    {
        if ($this->isAdmin($user)) {
            return true;
        }

        return null;
    }

    public function isAdmin(User $user): bool
    {
        return $user->hasRole(RolesEnum::ADMIN);
    }

    public function view(?User $user): bool
    {
        return $user->can(['view tickets']);
    }

    public function create(User $user): bool
    {
        return $user->can(['create tickets']);
    }

    public function update(User $user): bool
    {
        return $user->can(['update tickets']);
    }

    public function delete(User $user): bool
    {
        return $user->can(['delete tickets']);
    }

    public function modifyStatistic(User $user): bool
    {
        return $user->can(['modify-view tickets statistic']);
    }

    public function editStatus(User $user): bool
    {
        return $user->can(['edit tickets status']);
    }
}
