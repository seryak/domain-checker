<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SslCertificate;

class SslCertificateController extends Controller
{
    /**
     * Remove the specified SSL certificate from storage.
     *
     * @param  \App\Models\SslCertificate  $certificate
     * @return \Illuminate\Http\Response
     */
    public function destroy(SslCertificate $certificate)
    {
        try {
            // Удаление только сертификата, домен остается
            $certificate->delete();
            
            return redirect()->back()->with('success', __('message.cert_deleted'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('error.cert_delete') . $e->getMessage());
        }
    }
}
