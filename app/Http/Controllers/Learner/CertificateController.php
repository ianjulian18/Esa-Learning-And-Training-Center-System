<?php

namespace App\Http\Controllers\Learner;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $certificates = Certificate::with('course')->where('user_id', $user->id)->get();

        return Inertia::render('Learner/MyCertificates', [
            'certificates' => $certificates
        ]);
    }

    public function download(Certificate $certificate)
    {
        // Ensure user owns certificate
        if ($certificate->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $certificate->load('course', 'user');

        $pdf = Pdf::loadView('pdf.certificate', [
            'certificate' => $certificate
        ])->setPaper('a4', 'landscape');

        return $pdf->download('Certificate_' . $certificate->certificate_number . '.pdf');
    }
}
