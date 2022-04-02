<?php

namespace App\Http\Controllers;

use PDF;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    //
    public function index() {
        $pdf = PDF::loadView('sample',[
            'title' => 'CodeAndDeploy.com Laravel pdf tutorials',
            'description' => 'This is an example of converting html template to pdf in laravel 8.0',
            'footer' => 'by <a href="https://google.com">Ansh Sarkar</a>'
        ]);
        return $pdf->download('sample.pdf');
    }
}