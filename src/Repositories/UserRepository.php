<?php
/**
 * Created by tanel @8.01.18 12:44
 */

namespace ActualReports\PDFGeneratorAPILaravel\Repositories;


use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;

class UserRepository implements \ActualReports\PDFGeneratorAPILaravel\Contracts\UserRepository
{
    /**
     * @param \Illuminate\Foundation\Auth\User $user
     *
     * @return string
     */
    public function getWorkspaceIdentifier(User $user = null)
    {
        return md5($user ? $user->id : Auth::id());
    }
}