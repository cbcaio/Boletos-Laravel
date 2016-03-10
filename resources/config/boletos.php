<?php


return [
    'taxa'          => 0.0985, /* % ao dia */
    'multa'         => 2, /* em %, cobrança único após vencimento */

    'titulo_pagina' => "Boletos Laravel - CEF",
    /*--------- SEUS DADOS ---------*/
    'razao_social'  => "Razão Social da Empresa",
    'endereco'      => "Endereço da Empresa",
    'cpf_cnpj'      => "12.123.123/0001-23",
    'cidade_estado' => "Ouro Fino / Minas Gerais",
    /*---------  DADOS DA SUA CONTA - CEF ---------*/
    // Num da agencia, sem digito
    "agencia"       => "1234",
    // Num da conta, sem digito
    "conta"         => "123456",
    // Código da Carteira: pode ser SR (Sem Registro) ou CR (Com Registro) - (Confirmar com gerente qual usar)
    "carteira"      => "SR",
];
