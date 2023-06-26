<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ValorDeixouDeReceberController extends Controller
{
    public function deixouDeReceber() {

        $cnpj = request('cnpj');

        $api = Http::get('http://homologacao3.azapfy.com.br/api/ps/notas');
        $apiArray = $api->json();

        $resultado = [];
        $remetenteDesconhecido = [
            'deixou_de_receber' => 0,
            'quantidade_nao_entregue' => 0,
        ];

        foreach ($apiArray as $item) {
            if ($cnpj && $item['cnpj_remete'] !== $cnpj) {
                continue;
            }

            if (isset($item['dt_entrega'])) {
                continue;
            }

            $remetente = $item['nome_remete'] ?? 'Remetente Desconhecido';

            if (!isset($resultado[$remetente])) {
                $resultado[$remetente] = $remetenteDesconhecido;
            }

            $resultado[$remetente]['deixou_de_receber'] += floatval($item['valor']);
            $resultado[$remetente]['quantidade_nao_entregue']++;
        }

        if (empty($resultado) && $cnpj) {
            return response()->json(['error' => 'Erro ao encontrar o CNPJ'], 404);
        }

        return response()->json($resultado);
    }
}
