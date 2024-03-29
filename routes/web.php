<?php

use App\Models\AdmUsers;
use App\Models\User;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdmUsersController;
use App\Http\Controllers\AvaliacaoController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CarrinhoSystemController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CEPController;
use App\Http\Controllers\ClientProdutoController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\ContatoController;
use App\Http\Controllers\CupomController;
use App\Http\Controllers\EnderecoController;
use App\Http\Controllers\FavoritoController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\ProdutoOfertasController;
use App\Http\Controllers\SubmenuController;
use App\Http\Controllers\UsersController;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\RedirectIfNotAuthenticated;
use App\Http\Middleware\CheckNotAuthUser;
use App\Http\Middleware\CheckAuthUser;
use App\Http\Controllers\SocialLoginController;
use App\Http\Controllers\VendaController;
use App\Mail\Contato;
use App\Http\Controllers\getProdutoFreteController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\RelatoriosController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::prefix('adm')->group(function () {

    Route::view('homepage', 'admin.list.homepage')->name('page-homepageAdmin');

    Route::prefix('auth')->controller(LoginController::class)->group(function () {
        Route::view('entrar', 'admin.index')->name('page-login');
        Route::get('logout', 'logout')->name('auth-sair')->middleware('guest:6,7,8,9');
        Route::post('login', 'login')->name('auth-entrar')->middleware(RedirectIfNotAuthenticated::class);
    });
    Route::prefix('log')->controller(LogController::class)->group(function () {
        Route::get('lista/registros', 'readFile')->name('lista-log')->middleware('guest:9');
        Route::get('lista/registros/download', 'downloadLog')->name('baixar-log')->middleware('guest:9');
    });

    Route::prefix('user')->controller(AdmUsersController::class)->middleware('guest:8,9')->group(function () {
        Route::get('lista', 'all')->name('page-listAdm');
        Route::get('falha', 'fallback')->name('falha-listAdm');
        Route::view('inserir', 'admin.forms.InsertAdm')->name('page-inserirAdm');
        Route::post('register', 'register')->name('post-userAdm');
        Route::get('pesquisar', 'search')->name('search-adm');
        Route::get('get/{id}', 'getUpdate')->name('get-userAdm')->middleware('guest:9');
        Route::delete('delete/{id}', 'delete')->name('delete-userAdm')->middleware('guest:9');
        Route::patch('update/{id}', 'update')->name('update-userAdm')->middleware('guest:6,8,9');
    });

    Route::prefix('produto')->controller(ProdutoController::class)->middleware('guest:6,8,9')->group(function () {
        Route::get('lista', 'all')->name('page-listProdutos');
        Route::get('inserir', 'getInsert')->name('page-inserirProduto');
        Route::get('falha', 'fallback')->name('falha-listProdutos');
        Route::post('register', 'register')->name('post-produto');
        Route::get('get/{id}', 'getUpdate')->name('get-produto');
        Route::get('pesquisar', 'search')->name('search-produto');
        Route::delete('delete/{id}', 'delete')->name('delete-produto');
        Route::patch('update/{id}', 'update')->name('update-produto');
    });

    Route::prefix('banners')->controller(BannerController::class)->middleware('guest:8,9')->group(function () {
        Route::get('lista', 'all')->name('page-listCarrossel');
        Route::view('inserir', 'admin.forms.InsertBanner')->name('page-inserirBanner');
        Route::post('register', 'register')->name('post-banner');
        Route::get('falha', 'fallback')->name('falha-listBanner');
        Route::get('get/{id}', 'getUpdate')->name('get-banner')->middleware('guest:9');
        Route::delete('delete/{id}', 'delete')->name('delete-banner')->middleware('guest:9');
        Route::patch('update/{id}', 'update')->name('update-banner')->middleware('guest:9');
    });

    Route::prefix('clientes')->controller(UsersController::class)->middleware('guest:7,8,9')->group(function () {
        Route::get('lista', 'allAdmin')->name('page-listClientes');
        Route::get('lista/pesquisa', 'searchUser')->name('search-users');
        Route::get('/{idCliente}', 'getClienteAdmin')->name('page-getCliente');
        Route::get('falha', 'adminFallback')->name('page-falhaClientes');
        Route::get('/delete/{idCliente}', 'clientDeleteAdmin')->name('client-delete');
        Route::get('/active/{idCliente}', 'clientActiveAdmin')->name('client-active');
    });

    Route::prefix('comentarios')->controller(AvaliacaoController::class)->middleware('guest:7,8,9')->group(function () {
        Route::get('lista', 'getFeedbacks')->name('page-listComentarios');
        Route::get('deletar/{idComentario}', 'delete')->name('page-deleteComentario');
        Route::get('get/comentario/{idComentario}', 'getComentarioAdmin')->name('get-comentario');
        Route::get('falha', 'fallbackAdmin')->name('fallback-listComentario');
        Route::get('filter/date', 'dateFilterAdmin')->name('filter-feedbackDate');
    });

    Route::prefix('cupons')->controller(CupomController::class)->middleware('guest:8,9')->group(function () {
        Route::get('lista', 'all')->name('page-listCupons');
        Route::get('inserir', 'retrieveInfo')->name('page-inserirCupom');
        Route::get('falha', 'fallback')->name('falha-listCupons');
        Route::post('register', 'register')->name('post-cupom');
        Route::get('pesquisar', 'search')->name('search-cupom');
        Route::get('get/{id}', 'getUpdate')->name('get-cupom');
        Route::delete('delete/{id}', 'delete')->name('delete-cupom');
        Route::patch('update/{id}', 'update')->name('update-cupom');
    });

    Route::prefix('menus')->controller(MenuController::class)->middleware('guest:9')->group(function () {
        Route::get('lista', 'all')->name('page-listMenus');
        Route::get('falha', 'fallback')->name('falha-listMenus');

        Route::prefix('menu')->controller(MenuController::class)->middleware('guest:9')->group(function () {
            Route::view('inserir', 'admin.forms.InsertMenu')->name('page-inserirMenu');
            Route::post('register', 'register')->name('post-menu');
            Route::get('get/{id}', 'getUpdate')->name('get-menu');
            Route::delete('delete/{id}', 'delete')->name('delete-menu');
            Route::patch('update/{id}', 'update')->name('update-menu');
        });

        Route::prefix('submenu')->controller(SubmenuController::class)->middleware('guest:9')->group(function () {
            Route::get('inserir', 'getSubmenu')->name('page-getSubmenu');
            Route::post('register', 'register')->name('post-submenu');
            Route::get('get/{id}', 'getUpdate')->name('get-Updatesubmenu');
            Route::delete('delete/{id}', 'delete')->name('delete-submenu');
            Route::patch('update/{id}', 'update')->name('update-submenu');
        });
    });

    Route::prefix('pedidos')->controller(VendaController::class)->middleware('guest:7,8,9')->group(function () {
        Route::get('lista', 'getPedidoAdmin')->name('page-listPedidos');
        Route::get('falha', 'fallback')->name('fallback-pedidoAdmin');
        Route::get('pedido/{idVenda}', 'detailsPedidoAdmin')->name('get-detailsPedidos');
        Route::get('pedido/status/update', 'updateStatusPedido')->name('update-statusPedido');
        Route::post('pedido/pesquisa-pedido', 'searchPedidosAdmin')->name('search-pedidos');
        Route::get('pedido/filter/date', 'dateFilterAdmin')->name('filter-pedidosDate');
        Route::get('pedido/filter/ordem', 'orderFilterAdmin')->name('filter-pedidosOrdem');
    });

    Route::prefix('relatorios')->controller(RelatoriosController::class)->middleware('guest:8,9')->group(function () {
        Route::get('/', 'getRelatorios')->name('page-relatorios');
    });

    Route::prefix('config')->controller(ConfigController::class)->middleware('guest:8,9')->group(function () {
        Route::get('/', 'all')->name('page-listConfig');
        Route::get('falha', 'fallback')->name('falha-listConfig');

        //! Categoria
        Route::view('inserir/categoria', 'admin.forms.InsertCategoria')->name('page-inserirCategoria');
        Route::post('register/categoria', 'registerCategoria')->name('post-categoria');
        Route::delete('delete/categoria/{id}', 'deleteCategoria')->name('delete-categoria');
        Route::get('get/categoria/{id}', 'getUpdateCategoria')->name('get-categoria');
        Route::patch('update/categoria/{id}', 'updateCategoria')->name('update-categoria');

        //! Marcas
        Route::view('inserir/marca', 'admin.forms.InsertMarca')->name('page-inserirMarca');
        Route::post('register/marca', 'registerMarca')->name('post-marca');
        Route::delete('delete/marca/{id}', 'deleteMarca')->name('delete-marca');
        Route::get('get/marca/{id}', 'getUpdateMarca')->name('get-marca');
        Route::patch('update/marca/{id}', 'updateMarca')->name('update-marca');
    });
});


// CLIENTE

Route::prefix('filtro')->controller(CategoriaController::class)->group(function () {
    Route::get('admin/filtrar', 'produtoFilterAdmin')->name('produto-filter');
    Route::get('admin/filtro/reset', 'resetFilters')->name('produto-resetFilter');
});



Route::prefix('usuario')->controller(UsersController::class)->group(function () {

    Route::view('/entrar', 'client.login')->name('client-login');

    Route::view('/cadastro', 'client.cadastro')->name('client-cadastro');

    Route::view('/confirmar/cadastro', 'client.confirm-cadastro')->name('client-confirmarCadastro');

    // Rotas Actions
    Route::post('/registro', 'registerUser')->middleware(CheckNotAuthUser::class)->name('register-user');
    Route::post('/login', 'LoginUser')->middleware(CheckNotAuthUser::class)->name('login-user');
    Route::get('/sair', 'logoutUser')->middleware(CheckAuthUser::class)->name('logout-user');

    //! LOGINS SOCIAIS

    Route::controller(SocialLoginController::class)->group(function () {
        // Google
        Route::get('auth/google', 'redirectToGoogle')->name('auth.google');
        Route::get('auth/google/callback', 'handleGoogleCallback');

        // Linkedin
        Route::get('auth/linkedin', 'redirectToLinkedin')->name('auth.linkedin');
        Route::get('auth/linkedin/callback', 'handleLinkedinCallback');
    });

    //! MINHA CONTA

    Route::prefix('minha-conta')->middleware(CheckAuthUser::class)->group(function () {
        Route::get('/', 'getAllInfoConta')->name('client-info');
        Route::view('edit/info/form/{type}', 'client.edit-info')->name('page-infoEdit');
        Route::get('editar/informacoes', 'editCommonInfo')->name('client-infoEdit');
        Route::get('editar/email', 'editEmailInfo')->name('client-emailEdit');
        Route::get('meus/enderecos', 'getAllEnderecos')->name('list-meusEnderecos');
    });
});

Route::prefix('favoritos')->controller(FavoritoController::class)->middleware(CheckAuthUser::class)->group(function () {
    Route::get('lista', 'allFavoritos')->name('client-favoritos');
    Route::get('favoritar/{idProduto}', 'register')->name('favoritar-produto');
    Route::get('apagar/{idProduto}', 'delete')->name('desfavoritar-produto');
    Route::get('falha', 'fallback')->name('falha-listFavoritos');
});


Route::prefix('avaliacao')->controller(AvaliacaoController::class)->middleware(CheckAuthUser::class)->group(function () {
    Route::get('avaliar/{idProduto}', 'getProdutoAvaliar')->name('feedback-produto');
    Route::get('registrar', 'register')->name('avaliar-produto');
    Route::get('like/avaliacao/{idComentario}', 'likeAvaliacao')->name('like-avaliacao');

    // Route::get('apagar/{idProduto}', 'delete')->name('desfavoritar-produto');
});

// Página Geral
Route::prefix('produtos')->controller(ClientProdutoController::class)->group(function () {
    Route::get('produto/{idProduto}', 'getProduto')->name('produto-get');
    Route::get('lista/categorias', 'getProdutoCategoria')->name('produto-getCategoria');
    Route::get('lista', 'allProdutos')->name('produto-pesquisaAll');
    Route::get('filtrar/produto', 'produtoFilterClient')->name('produto-filterClient');
    Route::get('preco/max', 'maximumValue')->name('produto-maxValue');
    Route::get('categoria/{categoria}', 'filterCategoria')->name('produtoCategoria-maxValue');
    Route::get('pesquisar', 'searchProdutoClient')->name('produtoSearch-client');
    Route::get('falha/produto', 'fallback')->name('falha-produtoClient');
    Route::get('produto/filtro/reset', 'resetFiltersAll')->name('produtoClient-resetFilter');
    Route::post('/get/produto/{cep}', [getProdutoFreteController::class, 'getProdutoFrete'])->name('produto-cep');
});

// Página de Ofertas
Route::prefix('produtos/ofertas')->controller(ProdutoOfertasController::class)->group(function () {
    Route::get('lista', 'allOfertas')->name('produtoOfertas-pesquisaAll');
    Route::get('filtrar/produto', 'produtoFilterClient')->name('produtoOfertas-filterClient');
    Route::get('preco/max', 'maximumValue')->name('produtoOfertas-maxValue');
    Route::get('falha/produto', 'fallback')->name('falhaOfertas-produtoClient');
    Route::get('produto/filtro/reset', 'resetFiltersAll')->name('produtoClientOfertas-resetFilter');
});


// Carrinho de Compras
Route::prefix('carrinho')->controller(CarrinhoSystemController::class)->middleware(CheckAuthUser::class)->group(function () {
    Route::get('/', 'getCarrinhoItens')->name('carrinho-all');
    Route::get('adicionar/{idProduto}', 'addProduto')->name('carrinho-insert');
    Route::get('comprar/{idProduto}', 'instaCompra')->name('carrinho-comprarAgora');
    // CARRINHO
    Route::get('remover/{idProduto}/{idCarrinho}', 'removeItem')->name('carrinho-delete');
    Route::get('atualizar/{idProduto}/{idCarrinho}', 'updateProduto')->name('carrinho-update');
    // Cupom
    Route::get('cupom/{idCarrinho}', 'cupomProduto')->name('carrinho-cupom');

    Route::get('vazio', 'fallback')->name('falha-carrinho');
});

// CONTATO

Route::prefix('contato')->controller(ContatoController::class)->group(function () {
    Route::view('/formulario', 'client.contato')->name('client-contato');
    Route::get('enviar/mensagem', 'sendMessage')->name('contato-message');
});

// Entrega
Route::prefix('entrega')->controller(EnderecoController::class)->middleware(CheckAuthUser::class)->group(function () {
    Route::get('/enderecos', 'getEnderecos')->name('get-endereco');
    Route::post('/registrar', 'register')->name('insert-endereco');
    Route::get('/endereco/vazio', 'fallback')->name('falha-endereco');
    Route::get('/endereco/selecionar', 'selectEndereco')->name('select-endereco');
    Route::get('/apagar/conta/endereco', 'deleteEnderecoInfo')->name('delete-enderecoInfo');
    Route::post('/registrar/conta/endereco', 'registerInfoEndereco')->name('register-infoEndereco');
});

Route::prefix('venda')->controller(VendaController::class)->middleware(CheckAuthUser::class)->group(function () {
    Route::get('/processa', 'processVenda')->name('processa-venda');
    Route::get('/sucesso', 'vendaSucess')->name('venda-sucess');
    Route::get('/falha', 'vendaSucess')->name('venda-falha');
    Route::get('pedido/detalhes/{idVenda}', 'getPedidoDetailClient')->name('pedido-detailsCliente');
});


// Homepage
Route::get('/', [ClientProdutoController::class, 'getInfoHomepage'])->name('client-homepage');
Route::view('/termos', 'client.termos&condicoes')->name('route-termos');



// RESET SENHA
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('user.not.auth')->name('password.request');

Route::post('/forgot-password', [UsersController::class, 'resetPasswordEmail'])->middleware('user.not.auth')->name('password.email');

Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('user.not.auth')->name('password.reset');


Route::post('/reset-password', [UsersController::class, 'resetPassword'])->middleware('user.not.auth')->name('password.update');




// Route::get('/mailable', function () {
//     return new App\Mail\Contato('asdasd', 'asdasd', 'asdasd', 'Lorem', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum');
// });



// CONFIG & TESTE


// Route::get('/adm/log', function () {
    // return dd((new LogController())->writeFile('Davisant6@gmail.com', 'Atualizou Produto', 'Produto'));
    // return dd((new LogController())->readFile());
// });


Route::get('/factory', function () {
    AdmUsers::factory()->create();
    User::factory()->create();
});

Route::get('/session', function () {
    return dd(session()->all());
});
