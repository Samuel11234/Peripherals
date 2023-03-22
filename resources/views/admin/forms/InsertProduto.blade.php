@extends('layouts.admin')
@section('css', 'admin/InsertProduto')
@section('js', 'admin/InsertProduto')
@section('title')@parent Inserir produto @stop


@section('content')

    <main class="content-product">

        <form action="#">
            <h2 class="title">Inclusão de Produtos</h2>

            <label class="label-field">Código de Produto</label>
            <input type="text" class="input-field" placeholder="Código">

            <label class="label-field">Nome do Produto</label>
            <input type="text" class="input-field" placeholder="Nome do produto">

            <label class="label-field">Preço do Produto</label>
            <input type="text" class="input-field" placeholder="R$ 00,00">

            <label class="label-field">Quantidade em Estoque</label>
            <input type="text" class="input-field" placeholder="Qtd 0.">

            <label class="label-field">Marca</label>
            <select class="select-field" name="#">
                <option value="#">Selecione</option>
            </select>

            <label class="label-field">Categoria</label>
            <select class="select-field" name="#" id="selectCategoria">
                <option value="#">Selecione</option>
                <option value="#">Monitor</option>
                <option value="#">Mouse</option>

            </select>

            <label class="label-field">Descrição do Produto</label>
            <textarea type="text" class="input-field textarea-field" placeholder="Descrição"></textarea>

            {{-- FORMULARIO DE INFORMAÇÕES ADICIONAIS --}}
            <div class="container-more-info">
                <h2>Informações Adicionais</h2>

                {{-- Informações padrões, que precisam ter em todos os produtos --}}
                <div class="box-inputs" id="boxPadrao">
                    <div class="box-row">
                        <label class="label-field">Fonte de Energia
                            <input type="text" class="input-field" placeholder="Energia">
                        </label>
                        <label class="label-field">Tamanho
                            <input type="text" class="input-field" placeholder="Tamanho">
                        </label>
                    </div>

                    <div class="box-row">
                        <label class="label-field">Conexões
                            <input type="text" class="input-field" placeholder="Conexões">
                        </label>
                        <label class="label-field">Cor
                            <input type="text" class="input-field" placeholder="Cor">
                        </label>
                    </div>

                    <div class="box-row">
                        <label class="label-field">Material
                            <input type="text" class="input-field" placeholder="Material">
                        </label>
                        <label class="label-field">Peso
                            <input type="text" class="input-field" placeholder="Peso">
                        </label>
                    </div>

                    <label class="label-field" id="inputGarantia">Garantia
                        <input type="text" class="input-field" placeholder="Garantia">
                    </label>
                </div>

                {{-- Informações para monitores --}}
                <div class="box-inputs options" id="boxMonitor">
                    <label class="label-field">Tipo de áudio
                        <input type="text" class="input-field" placeholder="Áudio">
                    </label>
                    <label class="label-field">Tipo de Tela
                        <input type="text" class="input-field" placeholder="Tela">
                    </label>
                    <label class="label-field">Resolução
                        <input type="text" class="input-field" placeholder="Resolução">
                    </label>
                </div>
                {{-- Informações para headset --}}
                <div class="box-inputs options" id="boxAudio">
                    <label class="label-field">Tipo de áudio
                        <input type="text" class="input-field" placeholder="Áudio">
                    </label>
                    <label class="label-field">Microfone
                        <input type="text" class="input-field" placeholder="Microfone">
                    </label>
                    <label class="label-field">Tecnologia
                        <input type="text" class="input-field" placeholder="Tecnologia">
                    </label>
                </div>
                {{-- Informações para teclados --}}
                <div class="box-inputs options" id="boxTeclado">
                    <label class="label-field">Tipo de Teclados
                        <input type="text" class="input-field" placeholder="Tipo">
                    </label>
                </div>
                {{-- Informações para mouse --}}
                <div class="box-inputs options" id="boxMouse">
                    <label class="label-field">Frequência
                        <input type="text" class="input-field" placeholder="Frequência">
                    </label>
                    <label class="label-field">DPI
                        <input type="text" class="input-field" placeholder="DPI">
                    </label>
                </div>
                <div class="box-inputs">
                    <label class="label-field">Informações Adicionais
                        <textarea type="text" class="input-field textarea-field" placeholder="Descrição"></textarea>
                    </label>
                </div>
            </div>

            <label class="label-field">Imagem Principal</label>
            <input type="file" accept="image/*" id="imageInput" class="input-field">

            <label for="img_principal">Outras Imagens </label>
            <input type="file" name="link_img[]" multiple onchange="newInput(this)" id="inputImgMultiple">
            <button style="width: 50%; padding: 1rem !important;" type="button" onclick="removeAllImages()"
                id="btnRemoveFile">
                Remover todas as imagens</button>
            <ul id="dpFiles"></ul>

            <div class="box-buttons">
                <button type="submit" class="btn-submit">Cadastrar</button>
                <button type="button" class="btn-cancel">Cancelar</button>
            </div>
        </form>
    </main>

@endsection