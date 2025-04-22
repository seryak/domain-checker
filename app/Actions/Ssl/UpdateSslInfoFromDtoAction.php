<?php

namespace App\Actions\Ssl;

use App\Dto\Ssl\SslCheckDTO;
use App\Models\Enum\SslStatus;
use App\Models\SslCertificate;

class UpdateSslInfoFromDtoAction
{
    public function execute(SslCertificate $sslCertificate, SslCheckDTO $dto): void
    {
        $data = ['status' => $dto->status->value];

        if ($dto->status === SslStatus::ERROR) {
            // @TODO: Handle errors if needed
            // $sslCertificate->errorMessages()->create(['text' => $dto->errorMessage]);
        } else {
            $data['expired_at'] = $dto->expirationDate->startOfDay();
        }

        $sslCertificate->update($data);
    }
}