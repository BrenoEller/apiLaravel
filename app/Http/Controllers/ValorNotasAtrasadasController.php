<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ValorNotasAtrasadasController extends Controller
{
    public function valorNotasAtrasadas() {

        $cnpj = request('cnpj');

        $api = Http::get('http://homologacao3.azapfy.com.br/api/ps/notas');
        $apiArray = $api->json();

        $resultado = [];
        $remetenteDesconhecido = [
            'valor_total_nao_entregue' => 0,
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

            $resultado[$remetente]['valor_total_nao_entregue'] += floatval($item['valor']);
            $resultado[$remetente]['quantidade_nao_entregue']++;
        }

        if (empty($resultado) && $cnpj) {
            return response()->json(['error' => 'Erro ao encontrar o CNPJ'], 404);
        }

        return response()->json($resultado);
    }
}
