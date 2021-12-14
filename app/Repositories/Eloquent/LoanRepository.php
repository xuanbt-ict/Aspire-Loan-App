<?php

namespace App\Repositories\Eloquent;

use App\Models\Loan;
use App\Models\User;
use App\Repositories\Eloquent\Repository;
use App\Repositories\LoanRepositoryInterface;

class LoanRepository extends Repository implements LoanRepositoryInterface
{
    /**
     * @return string
     */
    public function getModel() : string
    {
        return Loan::class;
    }

    /**
     * @param User $user
     * @param array $filter
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllPaginate(User $user, $filter = [])
    {
        return Loan::where('user_id', $user->id)->where($filter)->paginate($this->perPage);
    }
}
