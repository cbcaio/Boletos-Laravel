# Image Attacher

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Pacote para geração de boletos, atualmente somente o boleto da Caixa Economica Federal está disponível. Documentação 
em desenvolvimento.

## Como instalar

### 1 - Via Composer

Basta adicionar ao `composer.json` a dependência:

``` bash
$ composer require cbcaio/boletos-laravel
```

### 2 - Provider

Após a instação é necessário inserir o provider no seu arquivo de configuração do laravel(`config/app.php`):

    'providers' => [
        // Other service providers...

        CbCaio\Boletos\Providers\BoletoServiceProvider::class,
    ],
    
### 3 - Configuration

Para gerar o arquivo de configuração basta executar o comando artisan a seguir em seu terminal 

``` bash
$ php artisan vendor:publish
```
    
Este comando criará 1 arquivo :
 
 1. `config/boletos.php` : Este arquivo é onde, opcionalmente, você pode configurar dados do beneficiario.

## Usage

Para se começar a utilizar o pacote é preciso entender a composição de um boleto. Cada boleto carrega as seguintes 
informações:

  1. Informações do Banco
  2. Informações do Beneficiario
  3. Informações do Pagador
  4. Informações do Boleto (valor, data vencimento, etc)
  
  Portando, você tem a opção de preencher essas opções ao criar um boleto:
  
  ```
     ...
     $beneficiario = new BeneficiarioCEF(FALSE, // true para carregar do arquivo de configuração
     [
         'razao_social'  => "Razão Social da Empresa",
         "agencia"       => "1234",
         'cpf_cnpj'      => "12.123.123/0001-23",
         'endereco'      => "Endereço da Empresa",
         'cidade_estado' => "Ouro Fino / Minas Gerais",
         'conta'         => '005507'
     ]);
     ...
     $pagador      = new Pagador([
         'nome'     => 'Tester',
         'endereco' => 'Endereco do Pagador',
         'cidade'   => 'Cidade',
         'estado'   => 'Estado',
         'cep'      => '37570-000',
         'cpf_cnpj' => '12.123.123/0001-12'
        ]
     );
     ...
      $info = new BoletoInfo([
             'nosso_numero'       => '222333777777777',
             'aceite'             => 'NÃO',
             'especie_doc'        => 'R$',
             'numero_documento'   => '1581-7/001',
             'data_documento'     => '2015-06-10',
             'data_processamento' => '2015-06-10',
             'data_vencimento'    => '2006-08-23',
             'taxa'               => 0.0985,
             'multa'              => 2,
             'valor_base'         => 32112
         ]
      );
     ... E finalmente
     $boleto = new BoletoCEF(
         new BancoCEF(1),
         $beneficiario,
         $pagador,
         $info
     );
     $boleto->processaDados();
     Neste ponto o boleto já está pronto para uso,
     ...
  ```

## Change log

Por favor veja [CHANGELOG](CHANGELOG.md) para mais informações sobre as ultimas mudanças.

## Contribuindo

Por favor veja [CONTRIBUTING](CONTRIBUTING.md) e [CONDUCT](CONDUCT.md) para mais detalhes.

## Segurança

Se você encontrar algum problema relacionada a segurança do pacote, por favor relate o problema encontrado, me 
mande um e-mail :author_email ou abra uma issue.

## Credits

- [CbCaio][link-author]
- [All Contributors][link-contributors]

## Licensa

The GPL License (GPL). Por favor veja [License File](LICENSE.md) para mais informações.

[ico-version]: https://img.shields.io/packagist/v/CbCaio/Boletos-Laravel.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-GPL-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/CbCaio/Boletos-Laravel/master.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/CbCaio/Boletos-Laravel.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/CbCaio/Boletos-Laravel.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/CbCaio/Boletos-Laravel
[link-travis]: https://travis-ci.org/CbCaio/Boletos-Laravel
[link-code-quality]: https://scrutinizer-ci.com/g/CbCaio/Boletos-Laravel
[link-downloads]: https://packagist.org/packages/CbCaio/Boletos-Laravel
[link-author]: https://github.com/CbCaio
[link-contributors]: ../../contributors
