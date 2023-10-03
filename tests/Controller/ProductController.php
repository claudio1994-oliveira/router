<?php

namespace Router\Tests\Controller;


class ProductController
{

    public function index()
    {
        return 'ProductController@index';
    }

    public function show($id)
    {
        return 'Rota com parâmetro dinâmico ' . $id;
    }
};
