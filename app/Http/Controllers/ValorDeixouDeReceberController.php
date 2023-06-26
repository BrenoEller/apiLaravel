<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use DateTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ValorDeixouDeReceberController extends Controller
{
    public function deixouDeReceber() {

        $cnpj = request('cnpj');

        $api = Http::get('http://homologacao3.azapfy.com.br/api/ps/notas');
        $apiArray = $api->json();

        $resultado = [];
        $remetenteDesconhecido = [
            'valor_deixou_receber' => 0,
            'quantidade_entregas' => 0,
        ];
        foreach ($apiArray as $item) {

            if ($cnpj && $item['cnpj_remete'] !== $cnpj) {
                continue;
            }


            if (isset($item['dt_emis']) && isset($item['dt_entrega'])) {

                $dataEmissao = DateTime::createFromFormat('d/m/Y H:i:s', $item['dt_emis'])->setTime(0, 0, 0);
                $dataEntrega = DateTime::createFromFormat('d/m/Y H:i:s', $item['dt_entrega'])->setTime(0, 0, 0);

                $diff = $dataEntrega->diff($dataEmissao)->days;


                if ($dataEmissao && $dataEntrega) {

                    if ($diff > 2) {
                        $remetente = $item['nome_remete'] ?? 'Remetente Desconhecido';

                        if (!isset($resultado[$remetente])) {
                            $resultado[$remetente] = $remetenteDesconhecido;
                        }

                        $resultado[$remetente]['valor_deixou_receber'] += floatval($item['valor']);
                        $resultado[$remetente]['quantidade_entregas']++;
                    }
                } else {
                    Log::error('Falha ao interpretar as datas:', ['item' => $item]);
                }
            } else {
                Log::warning('Item de API ausente ou incompleto:', ['item' => $item]);
            }
        }

        if (empty($resultado) && $cnpj) {
            return response()->json(['error' => 'Erro ao encontrar o CNPJ'], 404);
        }

        return response()->json($resultado);
    }
}
