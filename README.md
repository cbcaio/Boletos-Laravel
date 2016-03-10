# Boletos Laravel

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
    
### 3 - Configuração

Para gerar o arquivo de configuração basta executar o comando artisan a seguir em seu terminal 

``` bash
$ php artisan vendor:publish
```
    
Este comando criará 1 arquivo :
 
 1. `config/boletos.php` : Este arquivo é onde, opcionalmente, você pode configurar dados do beneficiario.

## Usage

Para começar a utilizar o pacote é preciso entender a composição de um boleto. Cada boleto carrega as seguintes 
informações:

  1. Informações do Banco
  2. Informações do Beneficiario
  3. Informações do Pagador
  4. Informações do Boleto (valor, data vencimento, etc)
  
  Portanto, inicialmente você precisará fornecer esses dados. Segue um exemplo real de utilização em um controller:
  
  ```
     public function generate($boleto)
    {
        $beneficiario = new BeneficiarioCEF();
        $owner        = $boleto->owner; // Relação com o usuário pagador
        $pagador      = new Pagador(
            [
                'nome'     => $owner->codigo_cliente .' - ' . $owner->nome . ' - ' .$owner->cpf_cnpj,
                'endereco' => $owner->endereco,
                'cidade'   => $owner->cidade,
                'estado'   => $owner->estado,
                'cep'      => $owner->cep,
                'cpf_cnpj' => $owner->cpf_cnpj
            ]
        );

        $info = new BoletoInfo(
            [
                "numero_documento"   => $boleto->numero_documento,
                "nosso_numero"       => $boleto->nosso_numero,
                "valor_base"      => $boleto->valor_cobrado,
                "data_documento"     => Carbon::parse($boleto->data_documento),
                "data_processamento" => Carbon::parse($boleto->data_processamento),
                "data_vencimento"    => Carbon::parse($boleto->data_vencimento),
                'taxa'               => config('boleto')['taxa'],
                'multa'              => config('boleto')['multa'],
                'aceite'            => 'NÃO',
                'especie_doc'       => 'DM',
                'especie'           => 'R$',
                'nome_sacado'       => '',
                'cpf_cnpj_sacado'   => ''

            ]
        );
        $boleto = new BoletoCEF(new BancoCEF(), $beneficiario, $pagador, $info);
        $boleto
            ->adicionaDemonstrativo('MULTA DE R$: :multa APOS: :vencimento')
            ->adicionaDemonstrativo("JUROS DE R$: :taxa AO DIA")
            ->adicionaInstrucao("- NÃO RECEBER APÓS 30 DIAS DO VENCIMENTO");
        $boleto->processaDadosBoleto();
        return view('admin.boletos.layouts._cef', compact('boleto'));
    }
  ```

## Change log

Por favor veja [CHANGELOG](CHANGELOG.md) para mais informações sobre as ultimas mudanças.

## Contribuindo

Por favor veja [CONTRIBUTING](CONTRIBUTING.md) e [CONDUCT](CONDUCT.md) para mais detalhes.

## Segurança

Se você encontrar algum problema relacionada a segurança do pacote, por favor relate o problema encontrado, me 
mande um e-mail caio.bolognani@gmail.com ou abra uma issue.

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
