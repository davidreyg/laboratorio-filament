<?php

namespace App\Actions;
use GuzzleHttp\Utils;
use Http;
use Illuminate\Database\RecordNotFoundException;
use Lorisleiva\Actions\Concerns\AsAction;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

class BuscarReniec
{
    use AsAction;
    public function handle(int $dni): array
    {
        $response = Http::withOptions([
            'verify' => false,
        ])
            ->withHeaders(['Authorization' => 'token eed63ab26117dacf4986f37ca1e61c4ccafc2aea'])
            ->get("https://38.43.129.230/api/web-service/person-complete/$dni");
        $content = Utils::jsonDecode($response, true);
        if (is_array($content)) {
            if (array_key_exists('id', $content)) {
                return $content;
            } else {
                throw new RecordNotFoundException("No se encontro al ciudadano");
            }
        } else {
            return throw new InternalErrorException("Servicio no disponible por el momento.");
        }
    }
}