<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\pdf;

class PdfController extends Controller
{
    public function index()
    {
        return view('pages.pdf.index');
    }

    public function view()
    {
        return view('pages.pdf.sertif');
    }

    public function portrait()
    {
        $pdf = pdf::loadView('pages.pdf.surat')
                    ->setPaper('a4', 'portrait');

        return $pdf->download('Surat.pdf');
    }

    public function landscape()
    {
        $pdf = pdf::loadView('pages.pdf.sertif')
                    ->setPaper('a4', 'landscape');

        return $pdf->download('Sertifikat.pdf');
    }
}
