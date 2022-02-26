<?php

namespace App\Http\Controllers;

use App\Models\Olt;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class StatsController extends Controller
{
  public function olts() {
    return Olt::all()->count();
  }

  public function users() {
    return Role::all()->count();
  }

  public function roles() {
    return User::all()->count();
  }
}
