<?php

namespace App\Http\Controllers;

use PDF;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    //
    public function index() {
        $pdf = PDF::loadView('sample',[]);
        return $pdf->download('sample.pdf');
    }
}