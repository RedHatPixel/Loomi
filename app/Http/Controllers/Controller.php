<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\Category;
use App\Models\Product;

class Controller
{
    use AuthorizesRequests, ValidatesRequests;
}
