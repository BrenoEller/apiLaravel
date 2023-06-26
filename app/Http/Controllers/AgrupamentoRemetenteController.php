<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AgrupamentoRemetenteController extends Controller
{
    public function agrupamentoRemetente() {

        $cnpj = request('cnpj');

        $api = Http::get('http://homologacao3.azapfy.com.br/api/ps/notas');
        $apiArray = $api->json();

        $remetentes = [];

        if($cnpj) {

            foreach ($apiArray as $item) {
                if ($item['cnpj_remete'] === $cnpj) {
                    $remetente = $item['nome_remete'];

                    if (!isset($remetentes[$remetente])) {
                        $remetentes[$remetente] = [
                            'cnpj' => $item['cnpj_remete'],
                            'notas' => [],
                        ];
                    }

                    $remetentes[$remetente]['notas'][] = $item;
                }
            }

            if (empty($remetentes)) {
                return response()->json(['error' => 'Erro ao encontrar CPNJ'], 404);
            }

        }else{

            foreach ($apiArray as $item) {
                $remetente = $item['nome_remete'];

                if (!isset($remetentes[$remetente])) {
                    $remetentes[$remetente] = [
                        'cnpj' => $item['cnpj_remete'],
                        'notas' => [],
                    ];
                }

                $remetentes[$remetente]['notas'][] = $item;
            }

            if (empty($remetentes)) {
                return response()->json(['error' => 'Erro ao buscar as informações'], 404);
            }
        }

        return $remetentes;
    }
}
