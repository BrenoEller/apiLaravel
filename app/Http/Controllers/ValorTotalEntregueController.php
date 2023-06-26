<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ValorTotalEntregueController extends Controller
{
    public function valorTotalEntregue() {

        $cnpj = request('cnpj');

        $api = Http::get('http://homologacao3.azapfy.com.br/api/ps/notas');
        $apiArray = $api->json();

        $resultado = [];
        $remetenteDesconhecido = [
            'valor_total_entregue' => 0,
            'quantidade_entregue' => 0,
        ];

        foreach ($apiArray as $item) {
            if ($cnpj && $item['cnpj_remete'] !== $cnpj) {
                continue;
            }

            $remetente = $item['nome_remete'] ?? 'Remetente Desconhecido';

            if (!isset($resultado[$remetente])) {
                $resultado[$remetente] = $remetenteDesconhecido;
            }

            if (isset($item['dt_entrega'])) {
                $resultado[$remetente]['valor_total_entregue'] += floatval($item['valor']);
                $resultado[$remetente]['quantidade_entregue']++;
            }
        }

        if (empty($resultado)) {
            return response()->json(['error' => 'Erro ao encontrar CNPJ ou URL incorreta'], 404);
        }

        return response()->json($resultado);
    }
}
