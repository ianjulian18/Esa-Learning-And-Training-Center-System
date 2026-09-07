<?php
namespace App\Services;

use App\Models\Enrollment;
use App\Models\Certificate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CertificateService
{
    public static function issueCertificate(Enrollment $enrollment)
    {
        if ($enrollment->status !== 'COMPLETED') return null;

        $exists = Certificate::where('user_id', $enrollment->user_id)
            ->where('course_id', $enrollment->course_id)
            ->exists();
            
        if ($exists) return null;

        $certNumber = 'CERT-' . strtoupper(Str::random(10));

        // Generate PDF
        // In a real app, you'd load the template design. We use a simple view here.
        $pdf = Pdf::loadView('certificates.template', [
            'user' => $enrollment->user->name,
            'course' => $enrollment->course->title,
            'date' => now()->format('F j, Y'),
            'number' => $certNumber
        ])->setPaper('a4', 'landscape');

        $fileName = 'certificates/' . $certNumber . '.pdf';
        Storage::disk('public')->put($fileName, $pdf->output());

        return Certificate::create([
            'user_id' => $enrollment->user_id,
            'course_id' => $enrollment->course_id,
            'certificate_number' => $certNumber,
            'file_path' => $fileName,
            'issued_at' => now(),
            'snapshot_data' => [
                'user_name' => $enrollment->user->name,
                'course_title' => $enrollment->course->title
            ]
        ]);
    }
}
