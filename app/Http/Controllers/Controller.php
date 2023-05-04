<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    // ini adalah base controller dalam laravel, tidak boleh di utak atik karena merupakan parent dari controller lain.
    use AuthorizesRequests, ValidatesRequests;
}
