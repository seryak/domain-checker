<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Domain;

class DomainController extends Controller
{
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
