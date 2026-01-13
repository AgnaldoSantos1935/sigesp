<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\BaseDataTableController;
use Illuminate\Database\Eloquent\Builder;



class UserController extends BaseDataTableController
{
      public function index()
    {
        return view('profile.index');
    }
    protected function query(): Builder
    {
        return User::query()
            ->where('ativo', true)
            ->select('id', 'name', 'email');
    }

     protected function actions($user): string
    {
        return view('pessoas.partials.actions', compact('user'))->render();
    }
}
