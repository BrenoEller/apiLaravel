<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ValorTotalController extends Controller
{
    public function valorTotal() {

        $cnpj = request('cnpj');

        $api = Http::get('http://homologacao3.azapfy.com.br/api/ps/notas');
        $apiArray = $api->json();

        $resultado = [];

        foreach ($apiArray as $item) {
            if ($cnpj && $item['cnpj_remete'] !== $cnpj) {
                continue;
            }

            $remetente = $item['nome_remete'] ?? 'Remetente Desconhecido';

            if (!isset($resultado[$remetente])) {
                $resultado[$remetente] = [
                    'valor_total' => 0,
                    'quantidade_entregue' => 0,
                    'quantidade_nao_entregue' => 0,
                ];
            }

            $resultado[$remetente]['valor_total'] += floatval($item['valor']);

            if (isset($item['dt_entrega'])) {
                $resultado[$remetente]['quantidade_entregue']++;
            } else {
                $resultado[$remetente]['quantidade_nao_entregue']++;
            }
        }

        if (empty($resultado)) {
            return response()->json(['error' => 'Erro ao encontrar CNPJ ou URL incorreta'], 404);
        }

        return $resultado;
        }
}
