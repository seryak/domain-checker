<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Domain;
use App\Models\Enum\DomainStatus;
use App\Models\SslCertificate;
use App\Service\SslService;
use App\Service\DomainNameConverter;

class DomainController extends Controller
{
    /**
     * Display the form for creating a new domain.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('domains.create');
    }

    /**
     * Store a newly created domain in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $domainName = $request->input('name');

            // Валидация и нормализация входных данных
            $domainConverter = app(DomainNameConverter::class);

            if (empty($domainName)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', __('error.domain_empty'));
            }

            if (strlen($domainName) > 255) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', __('error.domain_too_long'));
            }

            if (!$domainConverter->isValidDomain($domainName)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', __('error.domain_invalid'));
            }

            // Конвертировать домен в punycode для поиска и сохранения
            $storageName = $domainConverter->toPunycode($domainName);

            // Проверка, существует ли уже такой домен
            $existingDomain = Domain::where('name', $storageName)->first();
            if ($existingDomain) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', __('message.domain_exists'));
            }

            // Создание нового домена
            $domain = Domain::create([
                'name' => $storageName,
                'status' => DomainStatus::ERROR->value,
            ]);

            // Проверка SSL для нового домена
            $sslService = app(SslService::class);
            $sslService->checkSslForDomain($domain);

            return redirect()->route('ssl.report')
                ->with('success', __('message.domain_added'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', __('error.domain_add') . $e->getMessage());
        }
    }

    /**
     * Remove the specified domain from storage.
     *
     * @param  \App\Models\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function destroy(Domain $domain)
    {
        try {
            // Удаление домена автоматически удалит все связанные SSL-сертификаты
            // благодаря каскадному удалению в базе данных или через события
            $domain->delete();
            
            return redirect()->back()->with('success', __('message.domain_deleted'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('error.domain_delete') . $e->getMessage());
        }
    }
}
