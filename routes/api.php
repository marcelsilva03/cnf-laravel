<?php

use App\Http\Controllers\API\UsuariosAPIController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ObitoController;
use App\Http\Controllers\API\LocalidadesAPIController;
use App\Http\Controllers\API\CartoriosAPIController;
use App\Http\Controllers\API\PagamentoAPIController;
use App\Http\Controllers\API\ClienteApiController;
use App\Http\Controllers\EfiPayController;


Route::post('/obitos', [ObitoController::class, 'comunicarobito']);
Route::get('/cartorios/{uf}/{cidade}', [CartoriosAPIController::class, 'obterCartoriosPorCidade']);
Route::get('/localidades/uf', [LocalidadesAPIController::class, 'obterEstados']);
Route::get('/localidades/{uf}', [LocalidadesAPIController::class, 'obterCidades']);

Route::post('/pagamentos/efi/cartao', [PagamentoAPIController::class, 'PagamentoEFICartao'])->name('api-pagamentos.efi.recebe-cartao');
Route::post('/pagamentos/efi/boleto', [PagamentoAPIController::class, 'PagamentoEFIBoleto'])->name('api-pagamentos.efi.recebe-boleto');
Route::post('/pagamentos/efi/pix', [PagamentoAPIController::class, 'PagamentoEFIPix'])->name('api-pagamentos.efi.gera-qrcode');
Route::post('/pagamentos/bb', [PagamentoAPIController::class, 'PagamentoBB'])->name('api-pagamentos.bb.pix-ou-ted');
Route::post('/pagamentos/efi/notificacao-cartao', [PagamentoAPIController::class, 'NotificacaoCartao'])->name('efipay.notificacao-cartao');
Route::post('/pagamentos/efi/notificacao-boleto', [PagamentoAPIController::class, 'NotificacaoTransacao'])->name('efipay.notificacao-boleto');
Route::post('/pagamentos/efi/notificacao-cartao-boleto', [PagamentoAPIController::class, 'notificacaoCartaoBoleto'])->name('efipay.notificacao-boleto');
Route::get('/pagamentos/efi/stream/{code}', [PagamentoAPIController::class, 'streamPixStatus']);

Route::get('admin/efipay/register-webhook/{pixKey}', [EfiPayController::class, 'registerWebhook']);
Route::match(['get', 'post'], 'pagamentos/efi/notificacao-pix', [PagamentoAPIController::class, 'pixNotification']);
Route::match(['get', 'post'], 'pagamentos/efi/notificacao-pix/pix', [PagamentoAPIController::class, 'pixNotification']);

Route::post('/check-email',[UsuariosAPIController::class, 'checkEmail'])->name('api-usuarios.check-email');

Route::post('/falecido', [ClienteApiController::class, 'getClienteData']);

Route::middleware('auth.sanctum')->group(function () {});
