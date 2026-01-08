<?php

namespace App\Http\Controllers;


abstract class Controller 
{
    
}


// Old ways, I comment it in case we fallback to old ways to protect functions in controller with middleware
// which using __construct() function.

// <?php

// namespace App\Http\Controllers;

// use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
// use Illuminate\Foundation\Validation\ValidatesRequests;
// use Illuminate\Routing\Controller as BaseController;

// class Controller extends BaseController
// {
//     use AuthorizesRequests, ValidatesRequests;
// }

