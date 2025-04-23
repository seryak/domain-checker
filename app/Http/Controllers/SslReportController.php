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
//            ->when($request->filled('search'), fn($q) => $q->whereHas('domain', fn($q) => $q->where('name', 'like', "%{$request->search}%")))
//            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->orderBy($request->input('sort', 'valid_to'), $request->input('direction', 'asc'))
            ->paginate(25);

        return view('ssl-report', compact('certificates'));
    }
}
