<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\ValorTotalEntregueController;
use App\Http\Controllers\AgrupamentoRemetenteController;
use App\Http\Controllers\ValorTotalController;
use App\Http\Controllers\ValorNotasAtrasadasController;
use App\Http\Controllers\ValorDeixouDeReceberController;

// Agrupado por remetente
Route::get('/agrupamento-remetente', [AgrupamentoRemetenteController::class, 'agrupamentoRemetente']);

// Pesquisa valor total das notas referentes ao remetente
Route::get('/valor-total-notas', [ValorTotalController::class, 'valorTotal']);

// Pesquisa valor total de entregas já realizadas
Route::get('/valor-total-entregue', [ValorTotalEntregueController::class, 'valorTotalEntregue']);

// Notas atrasadas
Route::get('/valor-notas-atrasadas', [ValorNotasAtrasadasController::class, 'valorNotasAtrasadas']);

// Valor que o remetente deixou de receber por entregas atrasadas
Route::get('/valor-deixou-de-receber', [ValorDeixouDeReceberController::class, 'deixouDeReceber']);

