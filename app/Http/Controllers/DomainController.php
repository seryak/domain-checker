<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Domain;
use App\Models\Enum\DomainStatus;
use App\Models\SslCertificate;
use App\Service\SslService;

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
            // Валидация входных данных
            $request->validate([
                'name' => 'required|string|regex:/^[a-zA-Z0-9.-]+$/|max:255',
            ]);

            $domainName = $request->input('name');

            // Проверка, существует ли уже такой домен
            $existingDomain = Domain::where('name', $domainName)->first();
            if ($existingDomain) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Домен уже существует в системе.');
            }

            // Создание нового домена
            $domain = Domain::create([
                'name' => $domainName,
                'status' => DomainStatus::ERROR->value,
            ]);

            // Проверка SSL для нового домена
            $sslService = app(SslService::class);
            $sslService->checkSslForDomain($domain);

            return redirect()->route('ssl.report')
                ->with('success', 'Домен успешно добавлен и проверен.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ошибка при добавлении домена: ' . $e->getMessage());
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
            
            return redirect()->back()->with('success', 'Домен и все связанные SSL-сертификаты успешно удалены.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ошибка при удалении домена: ' . $e->getMessage());
        }
    }
}
