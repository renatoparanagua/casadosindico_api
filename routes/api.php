<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */


Route::get('/site/parceiros', 'Api\MigradorSiteAppController@getParceiros');
Route::get('/site/afiliados', 'Api\MigradorSiteAppController@getAfiliados');

Route::get('/site/categorias', 'Api\MigradorSiteAppController@getCategorias');
Route::get('/site/subcategorias', 'Api\MigradorSiteAppController@getSubcategorias');
Route::get('/site/subcategoriasByCategoria', 'Api\MigradorSiteAppController@getSubcategoriasByCategoria');
Route::get('/site/subcategoriasByCategoria/{categoria_id}', 'Api\MigradorSiteAppController@getSubcategoriasByCategoria');
Route::get('/site/categoriaByChave/{chave_url}', 'Api\MigradorSiteAppController@getCategoriasByChaveUrl');
Route::post('/site/verificarExisteSindico', 'Api\MigradorSiteAppController@verificarExisteSindico');
Route::post('/site/cadastrarSolicitacaoSite', 'Api\MigradorSiteAppController@cadastrarSolicitacaoSite');
Route::get('/site/estados', 'Api\MigradorSiteAppController@getEstados');
Route::get('/site/estados/{estado_id}', 'Api\MigradorSiteAppController@getEstados');
Route::get('/site/estadosIdByUf/{uf}', 'Api\MigradorSiteAppController@getEstadoByUf');
Route::get('/site/cidades/{estado_id}', 'Api\MigradorSiteAppController@getCidades');
Route::get('/site/cidadesIdByCidade/{cidade}/{estado}', 'Api\MigradorSiteAppController@getCidadesIdByCidade');
Route::post('/site/verifica_cnpj', 'Api\MigradorSiteAppController@verifica_cnpj');
Route::post('/site/carregarCondominios', 'Api\MigradorSiteAppController@carregarCondominios');

Route::get('/cron/update_financeiro/{token}', 'Api\IntegradorController@atualizarAssinaturasAsaas');
Route::get('/cron/update_assinaturas_autentique/{token}', 'Api\IntegradorController@update_assinaturas_autentique');



Route::post('/init', 'Auth\DeviceController@init');
Route::post('/autentique/{id}', 'Api\WebHookController@webhoockautentique');



/**
 * Somente requisições do Aplicativo ou Site autorizado
 */
Route::group(['middleware' => ['auth:app']], function () {
    Route::post('/usuario/verificar_email', 'Api\UsuarioAppController@validarEmail');
    Route::get('/planos/regiao/{regiao_id}', 'Api\PlanoDisponivelFranqueadoController@planosByRegiao'); //Update parcial, valida apenas os campos enviados.
    Route::get('/configuracao', 'Api\ConfiguracaoController@index'); //Update parcial, valida apenas os campos enviados.

    Route::post('/usuario_facebook', 'Api\UsuarioAppController@usuario_facebook'); //Update parcial, valida apenas os campos enviados.


    //Novo usuário
    Route::post('/usuarios_app', 'Api\UsuarioAppController@store');

    Route::get('/politicas', 'Api\PoliticasController@termosApp');

    Route::post('/novasenha', 'Api\UsuarioAppController@novasenha');


    //Estado
    Route::get('/categorias', 'Api\CategoriaController@index');
    Route::get('/categorias/{afiliado_id}', 'Api\CategoriaController@indexAfiliado');
    //Estado
    Route::get('/estados', 'Api\EstadoController@index');

    //Cidade
    Route::get('/cidades', 'Api\CidadeController@index');
    Route::get('/cidades/estado/{uf}', 'Api\CidadeController@estado');
    Route::get('/cidades/regiao/{id}', 'Api\CidadeController@regiao');
    //Bairro
    Route::get('/bairros/cidade/{id}', 'Api\BairroController@cidade');
    Route::get('/bairros/regiao/{id}', 'Api\BairroController@regiao');
    //Rua
    Route::get('/rua/bairro/cidade/{id}', 'Api\RuaController@cidade');
    Route::get('/rua/bairro/{id}', 'Api\RuaController@bairro');
    //Regiao
    Route::get('/regioes', 'Api\RegiaoController@index');
    Route::get('/regioes/estados/{uf}', 'Api\RegiaoController@estado');
    Route::get('/regioes/cidades/{cidade}', 'Api\RegiaoController@cidade');


    Route::get('/plano_assinatura_afiliado_regiao/regiao/{id}', 'Api\PlanoAssinaturaAfiliadoRegiaoController@regiao');

    Route::get('/afiliados/{id}/perfil', 'Api\AfiliadoController@perfil'); //público
    Route::get('/login', 'Auth\LoginController@login')->name('login');
    Route::post('/login', 'Auth\LoginController@login')->name('login');
});
Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::get('/login', 'Auth\LoginController@login')->name('login');
//Route::post('/login', 'Auth\LoginController@login')->name('login');


Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/notificacoes', 'Api\NotificacaoController@index');
    Route::delete('/notificacoes/{notificacao_id}', 'Api\NotificacaoController@destroy');
    Route::delete('/notificacoes', 'Api\NotificacaoController@destroyAll');
    Route::get('/notificacoes/naolidas', 'Api\NotificacaoController@naolidas');


    Route::get('/afiliado/inadimplencia', 'Api\AfiliadoController@inadimplencia');


    Route::post('/usuario_app/aceite_termos', 'Api\UsuarioAppController@aceite_termos'); //Perfil do Afiliado logado.

    Route::get('/usuario_app/verificar_assinatura_termos', 'Api\UsuarioAppController@verificar_assinatura_termos'); //Perfil do Afiliado logado.


    Route::get('/vistoriador/perfil', 'Api\VistoriadorController@dados_usuario_tipo'); //Perfil do Afiliado logado.


    Route::post('/novasenhaPerfil', 'Api\UsuarioAppController@novasenhaPerfil');

    Route::post('/addTokenFCM', 'Api\UsuarioAppController@addTokenFCM');

    Route::get('/vistoria/{id}', 'Api\VistoriaController@show');
    Route::get('/rua/{cep}', 'Api\RuaController@buscaPorCep');
    Route::get('/usuario_dados', 'Api\UsuarioAppController@show'); //Perfil do Afiliado logado.

    #AFILIADO
    Route::get('/orcamentos/{id}', 'Api\OrcamentoController@show');
    Route::post('/enviar_valor_orcamento', 'Api\OrcamentoController@enviar_valor_orcamento'); //Cadastro de Afiliado.
    #Route::get('/afiliados', 'Api\AfiliadoController@index');                                      //Listagem dos afiliados de um usuário.
    #Route::get('/afiliados/{id}', 'Api\AfiliadoController@show');                                  //Ler um Afiliado pelo seu ID. Somente do usuário logado.
    Route::get('/afiliados/perfil', 'Api\AfiliadoController@dados_usuario_tipo'); //Perfil do Afiliado logado.
    Route::post('/afiliados', 'Api\AfiliadoController@store'); //Cadastro de Afiliado.
    Route::put('/afiliados', 'Api\AfiliadoController@update'); //Update com validação em todos os campos.
    Route::patch('/afiliados', 'Api\AfiliadoController@partial_update'); //Update parcial, valida apenas os campos enviados.

    Route::get('/afiliados/documentos', 'Api\ResponsavelAfiliadoController@documentos');
    Route::get('/afiliados/responsavel', 'Api\ResponsavelAfiliadoController@show');
    Route::post('/afiliados/responsavel', 'Api\ResponsavelAfiliadoController@store'); //Realiza o cadastro do responsável afiliado
    Route::put('/afiliados/responsavel', 'Api\ResponsavelAfiliadoController@update'); //Realiza o update do responsável afiliado
    Route::patch('/afiliados/responsavel', 'Api\ResponsavelAfiliadoController@partial_update'); //Realiza o update parcial do responsável afiliado
    Route::delete('/afiliados/responsavel', 'Api\ResponsavelAfiliadoController@destroy'); //Realiza a exclusão

    Route::get('/afiliados/pendencias', 'Api\AfiliadoController@pendencias'); //Update parcial, valida apenas os campos enviados.

    #SINDICO
    Route::get('/sindicos/pendencias', 'Api\SindicoController@pendencias'); //Update parcial, valida apenas os campos enviados.

    Route::post('/sendEmailConfirmacao', 'Api\UsuarioAppController@sendEmailConfirmacao'); //Update parcial, valida apenas os campos enviados.


    Route::get('/sindicos/perfil', 'Api\SindicoController@dados_usuario_tipo'); //Perfil do sindicos logado.
    Route::post('/sindicos', 'Api\SindicoController@store'); //Cadastro de sindicos.
    Route::put('/sindicos', 'Api\SindicoController@update'); //Update com validação em todos os campos.
    Route::patch('/sindicos', 'Api\SindicoController@partial_update'); //Update parcial, valida apenas os campos enviados.

    Route::post('/sindicos/orcamentos/nova_avaliacao', 'Api\OrcamentoController@nova_avaliacao'); //Cadastro de sindicos.
    Route::post('/sindicos/orcamentos/nova_vistoria', 'Api\OrcamentoController@nova_vistoria'); //Cadastro de sindicos.


    Route::get('sindicos/condominios', 'Api\CondominioController@index');
    Route::get('sindicos/condominios/{id}', 'Api\CondominioController@show');
    Route::delete('sindicos/condominios/{id}', 'Api\CondominioController@destroy');
    Route::put('sindicos/condominios/{id}', 'Api\CondominioController@update');
    Route::patch('sindicos/condominios/{id}', 'Api\CondominioController@partial_update');
    Route::post('sindicos/condominios', 'Api\CondominioController@store');
    Route::get('sindicos/condominios/arquivar/{id}', 'Api\CondominioController@arquivar');

    #Orçamento do síndico
    Route::get('sindicos/orcamentos', 'Api\OrcamentoController@index');

    Route::get('sindicos/orcamentos/page/{page}', 'Api\OrcamentoController@indexPaginate');

    Route::get('sindicos/orcamentos/{id}', 'Api\OrcamentoController@show');
    // Route::delete('sindicos/orcamentos/{id}', 'Api\OrcamentoController@destroy');
    Route::patch('sindicos/orcamentos/{id}', 'Api\OrcamentoController@partial_update');
    Route::patch('afiliados/orcamentos/{id}', 'Api\OrcamentoController@partial_update');
    Route::post('sindicos/orcamentos', 'Api\OrcamentoController@store');

    Route::get('sindicos/orcamentos/{id}/interessados', 'Api\AfiliadoOrcamentoInteresseController@index');



    Route::get('afiliados/orcamentos', 'Api\OrcamentoControllerAfiliado@index');
    Route::get('afiliados/orcamentos/page/{page}', 'Api\OrcamentoControllerAfiliado@indexPaginate');

    Route::get('afiliados/categorias', 'Api\AfiliadoController@categorias');
    Route::post('afiliados/categorias', 'Api\AfiliadoController@categoriasAdd');
    Route::post('afiliados/categorias/remover', 'Api\AfiliadoController@categoriasRemove');


    //Route::get('sindicos/afiliado_interesse_orcamento/orcamento/{id}', 'Api\SindicoController@afiliadoInteresse');
    Route::get('sindicos/{id}/condominios/{id_condominio}', 'Api\SindicoController@condominio');
    Route::get('/condominios/{id}/orcamentos', 'Api\CondominioController@orcamentos');
    Route::get('/orcamentos/status/{status}', 'Api\OrcamentoController@status');
    Route::get('/orcamentos/status_afiliado/{status}', 'Api\OrcamentoController@statusAfiliado');
    Route::get('/orcamentos/status_sindico/{status}', 'Api\OrcamentoController@statusSindico');
    Route::put('/vistorias/{id}/checkin', 'Api\VistoriaController@checkin');
    Route::put('/vistorias/{id}/checkout', 'Api\VistoriaController@checkout');

    #VISTORIADOR
    Route::get('/vistorias', 'Api\VistoriaController@index');
    Route::get('/vistoriadores/vistorias/data_vistoria/{data}', 'Api\VistoriaController@data');
    Route::get('/vistorias/condominio/{id}', 'Api\VistoriaController@condominio');
    Route::post('/vistoriadores/vistorias', 'Api\VistoriaController@store');
    Route::put('/vistoriadores/vistorias/{id}', 'Api\VistoriaController@update');
    Route::get('/vistoriadores/vistorias/rejeitar/{vistoria_id}', 'Api\VistoriaController@rejeitar');
    Route::get('/vistoriadores/vistorias/aceitar/{vistoria_id}', 'Api\VistoriaController@aceitar');
    Route::put('/vistorias/{id}/agendar', 'Api\VistoriaController@agendar');
    Route::patch('/vistoriadores', 'Api\VistoriadorController@partial_update'); //Update parcial, valida apenas os campos enviados.



    //Afiliado_regiao
    Route::get('/afiliado_regiao', 'Api\AfiliadoRegiaoController@index');
    Route::post('/afiliado_regiao', 'Api\AfiliadoRegiaoController@store');
    Route::patch('/afiliado_regiao', 'Api\AfiliadoRegiaoController@partial_update');
    Route::get('/afiliado_regiao/assinar/{id}', 'Api\AfiliadoRegiaoController@assinar');


    Route::get('/afiliado_regiao/update_assinatura_asaas/{id}', 'Api\AfiliadoRegiaoController@updateAssinaturaAsaasPlanoById');
    Route::get('/afiliado_regiao/update_assinatura/{id}', 'Api\AfiliadoRegiaoController@updateAssinaturaPlanoById');
    Route::get('/afiliado_regiao/update_assinatura_servico/{id}', 'Api\AfiliadoRegiaoController@updateAssinaturaServicoById');


    Route::post('/usuario/upload', 'Api\UsuarioAppController@uploadBase64');
    Route::post('/usuario/uploadBase64', 'Api\UsuarioAppController@uploadBase64');
    #AFILIADO, SINDICO OU VISTORIADOR
    Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
    Route::post('/usuario/alterar_email', 'Api\UsuarioAppController@alterar_email')->name('alterar_email');
    Route::post('afiliado_interesse_orcamento/{id}', 'Api\AfiliadoOrcamentoInteresseController@store');
    Route::patch('afiliado_interesse_orcamento/{id}', 'Api\AfiliadoOrcamentoInteresseController@partial_update');
    Route::patch('sindicos/escolha_afiliado/{id}', 'Api\OrcamentoController@escolha_afiliado_orcamento');
});
