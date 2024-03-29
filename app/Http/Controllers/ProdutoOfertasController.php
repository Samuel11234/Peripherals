<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Marcas;
use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\ProdutoView;
use Illuminate\Support\Facades\Session;

class ProdutoOfertasController extends Controller
{
    public function allOfertas()
    {
        $produtosTest = Produto::all()->where('status', 1)
            ->where('is_promocao', 1)->toArray();
        $categorias = Categoria::all()->toArray();
        $marcas = Marcas::all()->toArray();
        if (count($produtosTest) > 0) {
            $produtos = ProdutoView::all()->where('status', 1)
                ->where('is_promocao', 1)->toQuery()->paginate(10);
            return view('client.ofertas')->with([
                'produtos' => $produtos,
                'categorias' => $categorias,
                'marcas' => $marcas
            ]);
        }
        return redirect()->route('falhaOfertas-produtoClient');
    }

    public function fallback()
    {
        $erro = 'Nenhum item foi encontrado!';
        $categorias = Categoria::all()->toArray();
        $marcas = Marcas::all()->toArray();
        return view('client.ofertas')->with([
            'erro' => $erro,
            'categorias' => $categorias,
            'marcas' => $marcas
        ]);
    }

    private static function changeName($filterName)
    {
        switch ($filterName) {
            case 'select-ordem':
                return 'ordem';
                break;
            case 'select-categoria':
                return 'categoria';
                break;
            case 'select-avaliacao':
                return 'avaliacao';
                break;
            case 'select-marca':
                return 'marca';
                break;
        }
    }

    public function resetFiltersAll(Request $request)
    {
        if ($request->session()->has('filtro-produto')) {
            $request->session()->forget('filtro-produto');
        }
        return redirect()->route('produtoOfertas-pesquisaAll');
    }

    public function maximumValue(Request $request)
    {
        $produtosTest = Produto::all()->where('status', 1)->where('is_promocao', 1)
            ->where('preco', '<=', $request->input('max-value'))->toArray();
        $categorias = Categoria::all()->toArray();
        $marcas = Marcas::all()->toArray();
        if (count($produtosTest) > 0) {
            $produtos = ProdutoView::all()->where('is_promocao', 1)->where('status', 1)
                ->where('preco', '<=', $request->input('max-value'))->toQuery()->paginate(10);
            return view('client.ofertas')->with([
                'produtos' => $produtos,
                'categorias' => $categorias,
                'marcas' => $marcas
            ]);
        }
        return redirect()->route('falhaOfertas-produtoClient');
    }


    public function produtoFilterClient(Request $request)
    {
        $campoBd = ProdutoOfertasController::changeName($request->get('selectName'));
        Session::put("filtro-produto.$campoBd", $request->get('selectValue'));


        $campoSearch = array_keys($request->session()->get('filtro-produto'));
        $valueSearch = array_values($request->session()->get('filtro-produto'));
        $orderQuery = false;

        foreach ($campoSearch as $item) {
            if ($item === 'ordem') {
                $orderQuery = true;
            }
        }

        if ($orderQuery) {
            return (new ProdutoOfertasController)->orderProdutoClient($campoSearch, $valueSearch);
        } else {
            return (new ProdutoOfertasController)->commonFilterClient($campoSearch, $valueSearch);
        }


        return redirect()->route('produto-pesquisaAll');
    }

    public static function sendData($produtos)
    {

        $categorias = Categoria::all()->toArray();
        $marcas = Marcas::all()->toArray();

        if (!empty($produtos)) {
            return view('client.ofertas')->with([
                'produtos' => $produtos,
                'categorias' => $categorias,
                'marcas' => $marcas
            ]);
        } else {
            return redirect()->route('falhaOfertas-produtoClient');
        }
    }


    private function commonFilterClient($campoSearch, $valueSearch)
    {

        if (count(Session::get('filtro-produto')) === 1) {
            $produtos = ProdutoView::all()->where('is_promocao', 1)->where('status', 1)->where($campoSearch[0], $valueSearch[0])->toArray();
            if (count($produtos) > 0) {
                $produtos = ProdutoView::all()->where('is_promocao', 1)->where('status', 1)->where($campoSearch[0], $valueSearch[0])->toQuery()->paginate(10);
            }
            //! VERIFICAR SE ESTÁ RETORNANDO ALGO
            return ProdutoOfertasController::sendData($produtos);
        } else if (count(Session::get('filtro-produto')) === 2) {
            $produtos = ProdutoView::all()->where('is_promocao', 1)->where('status', 1)->where($campoSearch[0], $valueSearch[0])
                ->where($campoSearch[1], $valueSearch[1])->toArray();
            if (count($produtos) > 0) {
                $produtos = ProdutoView::all()->where('is_promocao', 1)->where('status', 1)->where($campoSearch[0], $valueSearch[0])
                    ->where($campoSearch[1], $valueSearch[1])->toQuery()->paginate(10);
            }

            return ProdutoOfertasController::sendData($produtos);
        } else if (count(Session::get('filtro-produto')) === 3) {
            $produtos = ProdutoView::all()->where('is_promocao', 1)->where('status', 1)->where($campoSearch[0], $valueSearch[0])
                ->where($campoSearch[1], $valueSearch[1])
                ->where($campoSearch[2], $valueSearch[2])->toArray();
            if (count($produtos) > 0) {
                $produtos
                    = ProdutoView::all()->where('is_promocao', 1)->where('status', 1)->where($campoSearch[0], $valueSearch[0])
                    ->where($campoSearch[1], $valueSearch[1])
                    ->where($campoSearch[2], $valueSearch[2])->toQuery()->paginate(10);
            }
            return ProdutoOfertasController::sendData($produtos);
        } else {
            return redirect()->route('produto-pesquisaAll');
        }
    }


    private function orderProdutoClient($campoSearch, $valueSearch)
    {
        $ordemFind = array_search('ordem', $campoSearch);
        $ordemEscolhida = $valueSearch[$ordemFind];

        // dd($valueSearch[$ordemFind]);
        // unset($campoSearch[$ordemFind]);
        // dd(array_values($campoSearch));



        // ORDEM POR PREÇO
        if ($valueSearch[$ordemFind] == 'DESC' || $valueSearch[$ordemFind] == 'ASC') {
            if (count(Session::get('filtro-produto')) === 1) {
                $produtos = ProdutoView::all()->where('is_promocao', 1)->where('status', 1)->toArray();
                if (count($produtos) > 0) {
                    $produtos = ProdutoView::all()->where('is_promocao', 1)->where('status', 1)->toQuery();
                    return ProdutoOfertasController::sendData($produtos->orderby('preco', $ordemEscolhida)->paginate(10));
                }

                return ProdutoOfertasController::sendData($produtos);
            } else if (count(Session::get('filtro-produto')) === 2) {
                unset($campoSearch[$ordemFind]);
                unset($valueSearch[$ordemFind]);
                $campoSearch = array_values($campoSearch);
                $valueSearch = array_values($valueSearch);

                $produtos = ProdutoView::all()->where('is_promocao', 1)->where('status', 1)->where($campoSearch[0], $valueSearch[0])->toArray();
                if (count($produtos) > 0) {
                    $produtos = ProdutoView::all()->where('is_promocao', 1)->where('status', 1)->where($campoSearch[0], $valueSearch[0])->toQuery();
                    return ProdutoOfertasController::sendData($produtos->orderby('preco', $ordemEscolhida)->paginate(10));
                }

                return ProdutoOfertasController::sendData($produtos);
            } else if (count(Session::get('filtro-produto')) === 3) {
                unset($campoSearch[$ordemFind]);
                unset($valueSearch[$ordemFind]);
                $campoSearch = array_values($campoSearch);
                $valueSearch = array_values($valueSearch);

                $produtos = ProdutoView::all()->where('is_promocao', 1)->where('status', 1)->where($campoSearch[0], $valueSearch[0])
                    ->where($campoSearch[1], $valueSearch[1])->toArray();

                if (count($produtos) > 0) {
                    $produtos = ProdutoView::all()->where('is_promocao', 1)->where('status', 1)->where($campoSearch[0], $valueSearch[0])
                        ->where($campoSearch[1], $valueSearch[1])->toQuery();
                    return ProdutoOfertasController::sendData($produtos->orderby('preco', $ordemEscolhida)->paginate(10));
                }

                return ProdutoOfertasController::sendData($produtos);
            } else if (count(Session::get('filtro-produto')) === 4) {
                unset($campoSearch[$ordemFind]);
                unset($valueSearch[$ordemFind]);
                $campoSearch = array_values($campoSearch);
                $valueSearch = array_values($valueSearch);

                $produtos = ProdutoView::all()->where('is_promocao', 1)->where('status', 1)->where($campoSearch[0], $valueSearch[0])
                    ->where($campoSearch[1], $valueSearch[1])->where($campoSearch[2], $valueSearch[2])->toArray();

                if (count($produtos) > 0) {
                    $produtos = ProdutoView::all()->where('is_promocao', 1)->where('status', 1)->where($campoSearch[0], $valueSearch[0])
                        ->where($campoSearch[1], $valueSearch[1])->where($campoSearch[2], $valueSearch[2])->toQuery();
                    return ProdutoOfertasController::sendData($produtos->orderby('preco', $ordemEscolhida)->paginate(10));
                }

                return ProdutoOfertasController::sendData($produtos);
            } else {
                return redirect()->route('produtoOfertas-pesquisaAll');
            }


            // ORDEM POR QUANTIDADE
        } else {
            if (count(Session::get('filtro-produto')) === 1) {
                $produtos = ProdutoView::all()->where('is_promocao', 1)->where('status', 1)->toArray();

                if (count($produtos) > 0) {
                    $produtos = ProdutoView::all()->where('is_promocao', 1)->where('status', 1)->toQuery();
                    return ProdutoOfertasController::sendData($produtos->orderby('quantidade', 'desc')->paginate(10));
                }
                return ProdutoOfertasController::sendData($produtos);
            } else if (count(Session::get('filtro-produto')) === 2) {
                unset($campoSearch[$ordemFind]);
                unset($valueSearch[$ordemFind]);
                $campoSearch = array_values($campoSearch);
                $valueSearch = array_values($valueSearch);

                $produtos = ProdutoView::all()->where('is_promocao', 1)->where('status', 1)->where($campoSearch[0], $valueSearch[0])->toArray();

                if (count($produtos) > 0) {
                    $produtos = ProdutoView::all()->where('is_promocao', 1)->where('status', 1)->where($campoSearch[0], $valueSearch[0])->toQuery();
                    return ProdutoOfertasController::sendData($produtos->orderby('quantidade', 'desc')->paginate(10));
                }

                return ProdutoOfertasController::sendData($produtos);
            } else if (count(Session::get('filtro-produto')) === 3) {
                unset($campoSearch[$ordemFind]);
                unset($valueSearch[$ordemFind]);
                $campoSearch = array_values($campoSearch);
                $valueSearch = array_values($valueSearch);

                $produtos = ProdutoView::all()->where('is_promocao', 1)->where('status', 1)->where($campoSearch[0], $valueSearch[0])
                    ->where($campoSearch[1], $valueSearch[1])->toArray();

                if (count($produtos) > 0) {
                    $produtos = ProdutoView::all()->where('is_promocao', 1)->where('status', 1)->where($campoSearch[0], $valueSearch[0])
                        ->where($campoSearch[1], $valueSearch[1])->toQuery();
                    return ProdutoOfertasController::sendData($produtos->orderby('quantidade', 'desc')->paginate(10));
                }

                return ProdutoOfertasController::sendData($produtos);
            } else if (count(Session::get('filtro-produto')) === 4) {
                unset($campoSearch[$ordemFind]);
                unset($valueSearch[$ordemFind]);
                $campoSearch = array_values($campoSearch);
                $valueSearch = array_values($valueSearch);

                $produtos = ProdutoView::all()->where('is_promocao', 1)->where('status', 1)->where($campoSearch[0], $valueSearch[0])
                    ->where($campoSearch[1], $valueSearch[1])
                    ->where($campoSearch[2], $valueSearch[2])->toArray();

                if (count($produtos) > 0) {
                    $produtos = ProdutoView::all()->where('is_promocao', 1)->where('status', 1)->where($campoSearch[0], $valueSearch[0])
                        ->where($campoSearch[1], $valueSearch[1])
                        ->where($campoSearch[2], $valueSearch[2])->toQuery();
                    return ProdutoOfertasController::sendData($produtos->orderby('quantidade', 'desc')->paginate(10));
                }

                return ProdutoOfertasController::sendData($produtos);
            } else {
                return redirect()->route('produtoOfertas-pesquisaAll');
            }
        }
    }
}
