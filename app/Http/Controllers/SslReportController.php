<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SslCertificate;

class SslReportController extends Controller
{
    public function index(Request $request)
    {
        $certificates = SslCertificate::query()
            ->with('domain')
            ->when($request->filled('search'), function($q) use ($request) {
                $q->whereHas('domain', fn($q) => $q->where('original_name', 'like', "%{$request->search}%"));
            })
            ->when($request->filled('status') && $request->status != '-', fn($q) => $q->where('status', $request->status))
            ->orderBy('expired')
            ->paginate(25);

        return view('ssl-report', compact('certificates'));
    }

    public function triggerCheck()
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('domains:check-all');
            return response()->json([
                'success' => true,
                'message' => 'Domains checked successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error executing domain check: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function checkSingle(Request $request)
    {
        try {
            // Валидация входных данных с поддержкой Unicode доменов
            $request->validate([
                'domain' => 'required|string|regex:/^[а-яёa-z0-9.-]+$/iu'
            ]);
            
            $domainName = $request->input('domain');
            
            // Проверка, существует ли домен
            $domain = \App\Models\Domain::firstOrCreate(
                ['name' => $domainName],
                ['status' => \App\Models\Enum\DomainStatus::OK->value]
            );
            
            // Проверка SSL для домена
            $sslService = app(\App\Service\SslService::class);
            $sslService->checkSslForDomain($domain);
            
            // Получение обновленного сертификата
            $certificate = $domain->sslCertificates()->first();
            
            return response()->json([
                'success' => true,
                'message' => 'Domain checked successfully',
                'certificate' => $certificate
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking domain: ' . $e->getMessage()
            ], 500);
        }
    }
}
