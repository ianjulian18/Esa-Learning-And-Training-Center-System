<?php
namespace App\Http\Controllers\Learner;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $certificates = Certificate::with('course')
            ->where('user_id', $request->user()->id)
            ->get();
            
        return Inertia::render('Learner/MyCertificates', [
            'certificates' => $certificates
        ]);
    }

    public function download(Request $request, Certificate $certificate)
    {
        if ($certificate->user_id !== $request->user()->id) {
            abort(403);
        }
        
        $path = $certificate->file_path;
        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'Certificate file not found.');
        }

        return Storage::disk('public')->download($path);
    }
}
