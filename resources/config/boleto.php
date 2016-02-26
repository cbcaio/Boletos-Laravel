<?php


return [
    'taxa' => 0.0985, /* % ao dia */
    'multa' => 2, /* em %, cobrança único após vencimento */

    'titulo_pagina'    => "Boleto CEF",


    /*--------- SEUS DADOS ---------*/
    'razao_social'     => "Empresa",
    'endereco'         => "",
    'cpf_cnpj'         => "",
    'cidade_estado'        => "Ouro Fino / Minas Gerais",

        /*---------  DADOS DA SUA CONTA - CEF ---------*/
            // Num da agencia, sem digito
            "agencia"          => "XXXX",
            // Num da conta, sem digito
            "conta"            => "XXXXXX",
            // Digito do Num da conta
            "conta_dv"         => "7",

        /*--------- DADOS PERSONALIZADOS - CEF ---------*/

            // ContaCedente do Cliente, sem digito (Somente Números)
            "conta_cedente"    => "",
            // Digito da ContaCedente do Cliente
            "conta_cedente_dv" => "",
            // Código da Carteira: pode ser SR (Sem Registro) ou CR (Com Registro) - (Confirmar com gerente qual usar)
            "carteira"         => "02",
];
