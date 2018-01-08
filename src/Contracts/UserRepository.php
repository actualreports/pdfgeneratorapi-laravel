<?php
/**
 * Created by tanel @8.01.18 12:44
 */

namespace ActualReports\PDFGeneratorAPILaravel\Contracts;


use Illuminate\Foundation\Auth\User;

interface UserRepository
{
    /**
     * Returns unique workspace identifier for user
     *
     * @param User $user
     * @return string
     */
    public function getWorkspaceIdentifier(User $user);
}