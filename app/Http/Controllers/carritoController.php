<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AdminImport;
use App\Exports\AdministradoresExport;

class carritoController extends Controller
{
    
  public function carrito()
    {    
        return view('carrito.carrito');
    }
    


    }


   




    

