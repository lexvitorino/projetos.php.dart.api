<?php
global $routes;
$routes = array();

$routes['/auth/login'] = '/auth/login';

$routes['/report'] = '/report';
$routes['/report/{id}'] = '/report/view/:id';
$routes['/report/{table}/{field}/{id}'] = '/report/getImage/:table/:field/:id';

$routes['/usuarios/create'] = '/usuarios/create';
$routes['/usuarios/{id}'] = '/usuarios/view/:id';
$routes['/usuarios/{id}/update'] = '/usuarios/update/:id';
$routes['/usuarios/{id}/delete'] = '/usuarios/delete/:id';

$routes['/clientes/create'] = '/clientes/create';
$routes['/clientes/{id}'] = '/clientes/view/:id';
$routes['/clientes/{id}/update'] = '/clientes/update/:id';
$routes['/clientes/{id}/delete'] = '/clientes/delete/:id';

$routes['/produtos/create'] = '/produtos/create';
$routes['/produtos/{id}'] = '/produtos/view/:id';
$routes['/produtos/{id}/update'] = '/produtos/update/:id';
$routes['/produtos/{id}/delete'] = '/produtos/delete/:id';

$routes['/fornecedores/create'] = '/fornecedores/create';
$routes['/fornecedores/{id}'] = '/fornecedores/view/:id';
$routes['/fornecedores/{id}/update'] = '/fornecedores/update/:id';
$routes['/fornecedores/{id}/delete'] = '/fornecedores/delete/:id';

$routes['/pedidos/create'] = '/pedidos/create';
$routes['/pedidos/{id}'] = '/pedidos/view/:id';
$routes['/pedidos/{id}/update'] = '/pedidos/update/:id';
$routes['/pedidos/{id}/delete'] = '/pedidos/delete/:id';

$routes['/contasReceber/create'] = '/contasReceber/create';
$routes['/contasReceber/{id}'] = '/contasReceber/view/:id';
$routes['/contasReceber/{id}/update'] = '/contasReceber/update/:id';
$routes['/contasReceber/{id}/delete'] = '/contasReceber/delete/:id';

$routes['/contasPagar/create'] = '/contasPagar/create';
$routes['/contasPagar/{id}'] = '/contasPagar/view/:id';
$routes['/contasPagar/{id}/update'] = '/contasPagar/update/:id';
$routes['/contasPagar/{id}/delete'] = '/contasPagar/delete/:id';