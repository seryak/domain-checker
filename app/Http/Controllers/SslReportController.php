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
            ->when($request->filled('search'), fn($q) => $q->whereHas('domain', fn($q) => $q->where('name', 'like', "%{$request->search}%")))
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('sort'), function($q) use ($request) {
                $sort = $request->input('sort');
                if ($sort === 'expired') {
                    $q->orderBy('expired', $request->input('direction', 'asc'));
                } elseif ($sort === 'domain') {
                    $q->orderBy('domain.name', $request->input('direction', 'asc'));
                } else {
                    $q->orderBy('created_at', $request->input('direction', 'asc'));
                }
            })
            ->orderBy('expired', $request->input('direction', 'asc'))
            ->paginate(25);

        return view('ssl-report', compact('certificates'));
    }
}
