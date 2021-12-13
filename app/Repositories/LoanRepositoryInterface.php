<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\RepositoryInterface;

interface LoanRepositoryInterface extends RepositoryInterface
{
    /**
     * @param User $user
     * @param array $filter
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllPaginate(User $user, $filter = []);
}
