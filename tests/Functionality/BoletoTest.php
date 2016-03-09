<?php
namespace CbCaio\Boletos\Testing;

use Carbon\Carbon;
use CbCaio\Boletos\Models\Bancos\BancoCEF;
use CbCaio\Boletos\Models\Beneficiario\BeneficiarioCEF;
use CbCaio\Boletos\Models\BoletoInfo\BoletoInfo;
use CbCaio\Boletos\Models\Boletos\BoletoCEF;
use CbCaio\Boletos\Models\Pagador\Pagador;

class BoletoTest extends AbstractTestCase
{
    /** @var  BoletoCEF */
    protected $boleto;

    public function setUp()
    {
        parent::setUp();
        $beneficiario = new BeneficiarioCEF();
        $pagador      = new Pagador(
            [
                'nome'     => 'Tester',
                'endereco' => 'Endereco do Pagador',
                'cidade'   => 'Cidade',
                'estado'   => 'Estado',
                'cep'      => '37570-000',
                'cpf_cnpj' => '12.123.123/0001-12'
            ]
        );
        $info         = new BoletoInfo(
            [
                'nosso_numero'       => '900000000000007',
                'aceite'             => 'R$',
                'especie_doc'        => 'DM',
                'nome_sacado'        => '',
                'cpf_cnpj_sacado'    => '',
                'especie'            => 'NÃO',
                'numero_documento'   => '1581-7/001',
                'data_documento'     => '2015-06-10',
                'data_processamento' => '2015-06-10',
                'data_vencimento'    => '2015-07-05',
                //                'dias_para_pagar'    => '',
                'taxa'               => 0.0985,
                'multa'              => 2,
                'valor_base'         => 17500
            ]
        );

        $this->boleto = new BoletoCEF(
            new BancoCEF(),
            $beneficiario,
            $pagador,
            $info
        );
    }

    /** @test */
    public function fator_de_vencimento_calculado_corretamente_com_data_string()
    {
        $fator_vencimento = $this->boleto->calculaFatorVencimento('2000-07-03');
        $this->assertEquals('1000', $fator_vencimento);
        $fator_vencimento = $this->boleto->calculaFatorVencimento('2000-07-05');
        $this->assertEquals('1002', $fator_vencimento);
        $fator_vencimento = $this->boleto->calculaFatorVencimento('2002-05-01');
        $this->assertEquals('1667', $fator_vencimento);
        $fator_vencimento = $this->boleto->calculaFatorVencimento('2010-11-17');
        $this->assertEquals('4789', $fator_vencimento);
        $fator_vencimento = $this->boleto->calculaFatorVencimento('2025-02-21');
        $this->assertEquals('9999', $fator_vencimento);
    }

    /** @test */
    public function fator_de_vencimento_calculado_corretamente_com_data_carbon()
    {
        $data             = Carbon::createFromFormat('Y-m-d', '2000-07-03')->setTime(0, 0, 0);
        $fator_vencimento = $this->boleto->calculaFatorVencimento($data);
        $this->assertEquals('1000', $fator_vencimento);
        $data             = Carbon::createFromFormat('Y-m-d', '2000-07-05')->setTime(0, 0, 0);
        $fator_vencimento = $this->boleto->calculaFatorVencimento($data);
        $this->assertEquals('1002', $fator_vencimento);
        $data             = Carbon::createFromFormat('Y-m-d', '2002-05-01')->setTime(0, 0, 0);
        $fator_vencimento = $this->boleto->calculaFatorVencimento($data);
        $this->assertEquals('1667', $fator_vencimento);
        $data             = Carbon::createFromFormat('Y-m-d', '2010-11-17')->setTime(0, 0, 0);
        $fator_vencimento = $this->boleto->calculaFatorVencimento($data);
        $this->assertEquals('4789', $fator_vencimento);
        $data             = Carbon::createFromFormat('Y-m-d', '2025-02-21')->setTime(0, 0, 0);
        $fator_vencimento = $this->boleto->calculaFatorVencimento($data);
        $this->assertEquals('9999', $fator_vencimento);
    }

    /** @test */
    public function formata_nosso_numero_sem_dv_calculado_corretamente()
    {
        $nosso_numero = $this->boleto->formataNossoNumeroSemDV('900000000000007');
        $this->assertEquals('900000000000007', $nosso_numero);
        $nosso_numero = $this->boleto->formataNossoNumeroSemDV('24/900000000000497');
        $this->assertEquals('24900000000000497', $nosso_numero);
        $nosso_numero = $this->boleto->formataNossoNumeroSemDV('14/222333777777777-3');
        $this->assertEquals('14222333777777777', $nosso_numero);
    }

    /** @test */
    public function dv_da_linha_digitavel_esta_sendo_calculado_corretamente()
    {
        $dv = $this->boleto->calculaDVLinhaDigitavel('104900550');
        $this->assertEquals(5, $dv);
        $dv = $this->boleto->calculaDVLinhaDigitavel('7722213334');
        $this->assertEquals(8, $dv);
        $dv = $this->boleto->calculaDVLinhaDigitavel('7777777771');
        $this->assertEquals(3, $dv);
    }

    /** @test */
    public function dv_do_codigo_de_barras_esta_sendo_calculado_corretamente()
    {
        $dv = $this->boleto->calculaDVGeralCodigoBarras('1049324200000321120055077222133347777777771');
        $this->assertEquals(4, $dv);
    }

    /** @test */
    public function dv_do_nosso_numero_esta_sendo_calculado_corretamente()
    {
        $dv = $this->boleto->calculaDVNossoNumero('14000000000000019');
        $this->assertEquals(7, $dv);
    }

    /** @test */
    public function dv_do_beneficiario_calculado_corretamente()
    {
        $codigo_beneficiario = $this->boleto->calculaDVCodigoBeneficiario('005507');
        $this->assertEquals(7, $codigo_beneficiario);
    }

    /** @test */
    public function calcula_dv_do_campo_livre_corretamente()
    {
        $dv = $this->boleto->calculaDVCampoLivre('005507722213334777777777');
        $this->assertEquals(1, $dv);
    }

    /** @test */
    public function linha_digitavel_esta_retornando_formatada()
    {
        $linha_digitavel_esperada = '10490.05505 77222.133348 77777.777713 4 32420000032112';
        $this->assertEquals($linha_digitavel_esperada,
                            $this->boleto->formataLinhaDigitavel('10490055057722213334877777777713432420000032112'));
    }

    /** @test */
    public function linha_digitavel_esta_sendo_calculada_corretamente()
    {
        $beneficiario = new BeneficiarioCEF(FALSE,
                                            [
                                                'razao_social'  => "Razão Social da Empresa",
                                                "agencia"       => "1234",
                                                'cpf_cnpj'      => "12.123.123/0001-23",
                                                'endereco'      => "Endereço da Empresa",
                                                'cidade_estado' => "Ouro Fino / Minas Gerais",
                                                'conta'         => '005507'
                                            ]);
        $pagador      = new Pagador(
            [
                'nome'     => 'Tester',
                'endereco' => 'Endereco do Pagador',
                'cidade'   => 'Cidade',
                'estado'   => 'Estado',
                'cep'      => '37570-000',
                'cpf_cnpj' => '12.123.123/0001-12'
            ]
        );
        $info         = new BoletoInfo(
            [
                'nosso_numero'       => '222333777777777',
                'aceite'             => 'NÃO',
                'especie_doc'        => 'R$',
                //                'nome_sacado'        => '',
                //                'cpf_cnpj_sacado'    => '',
                //                'especie'            => '',
                'numero_documento'   => '1581-7/001',
                'data_documento'     => '2015-06-10',
                'data_processamento' => '2015-06-10',
                'data_vencimento'    => '2006-08-23',
                //                'dias_para_pagar'    => '',
                'taxa'               => 0.0985,
                'multa'              => 2,
                'valor_base'         => 32112
            ]
        );

        $boleto = new BoletoCEF(
            new BancoCEF(1),
            $beneficiario,
            $pagador,
            $info
        );

        $this->assertEquals('10490.05505 77222.133348 77777.777713 4 32420000032112',
                            $boleto->getLinhaDigitavelFormatada());
    }

    /** @test */
    public function codigo_de_barras_esta_sendo_calculado_corretamente()
    {
        $beneficiario = new BeneficiarioCEF(FALSE,
        [
            'razao_social'  => "Razão Social da Empresa",
            "agencia"       => "1234",
            'cpf_cnpj'      => "12.123.123/0001-23",
            'endereco'      => "Endereço da Empresa",
            'cidade_estado' => "Ouro Fino / Minas Gerais",
            'conta'         => '005507'
        ]);
        $pagador      = new Pagador(
            [
                'nome'     => 'Tester',
                'endereco' => 'Endereco do Pagador',
                'cidade'   => 'Cidade',
                'estado'   => 'Estado',
                'cep'      => '37570-000',
                'cpf_cnpj' => '12.123.123/0001-12'
            ]
        );
        $info         = new BoletoInfo(
            [
                'nosso_numero'       => '222333777777777',
                'aceite'             => 'NÃO',
                'especie_doc'        => 'R$',
                //                'nome_sacado'        => '',
                //                'cpf_cnpj_sacado'    => '',
                //                'especie'            => '',
                'numero_documento'   => '1581-7/001',
                'data_documento'     => '2015-06-10',
                'data_processamento' => '2015-06-10',
                'data_vencimento'    => '2006-08-23',
                //                'dias_para_pagar'    => '',
                'taxa'               => 0.0985,
                'multa'              => 2,
                'valor_base'         => 32112
            ]
        );

        $boleto = new BoletoCEF(
            new BancoCEF(1),
            $beneficiario,
            $pagador,
            $info
        );

        $this->assertEquals('10494324200000321120055077222133347777777771',
                            $boleto->getCodigoBarras());
    }

    /** @test */
    public function processa_boleto_esta_funcional()
    {
        $this->boleto->processaDadosBoleto();
        $this->assertNotNull($this->boleto->processed['codigo_banco_compensacao']);
        $this->assertNotNull($this->boleto->processed['linha_digitavel']);
        $this->assertNotNull($this->boleto->processed['local_de_pagamento']);
        $this->assertInstanceOf(Carbon::class, $this->boleto->processed['vencimento']);
        $this->assertNotNull($this->boleto->processed['beneficiario']['razao_social']);
        $this->assertNotNull($this->boleto->processed['beneficiario']['agencia']);
        $this->assertNotNull($this->boleto->processed['beneficiario']['cpf_cnpj']);
        $this->assertNotNull($this->boleto->processed['beneficiario']['endereco']);
        $this->assertNotNull($this->boleto->processed['beneficiario']['cidade']);
        $this->assertEquals(15, strlen($this->boleto->processed['agencia_codigo_beneficiario']));
        $this->assertInstanceOf(Carbon::class, $this->boleto->processed['data_do_documento']);
        $this->assertEquals(20, strlen($this->boleto->processed['nosso_numero']));
        $this->assertNotNull($this->boleto->processed['carteira']);
        $this->assertNotNull($this->boleto->processed['especie_moeda']);
        $this->assertNotNull($this->boleto->processed['valor_documento']);
        $this->assertNull($this->boleto->processed['uso_do_banco']);
        $this->assertNull($this->boleto->processed['qtde_moeda']);
        $this->assertNull($this->boleto->processed['xValor']);
        $this->assertNull($this->boleto->processed['desconto']);
        $this->assertNotNull($this->boleto->processed['pagador']['nome']);
        $this->assertNotNull($this->boleto->processed['pagador']['endereco']);
        $this->assertNotNull($this->boleto->processed['pagador']['cidade_estado_cep']);
        $this->assertEquals(18, strlen($this->boleto->processed['pagador']['cpf_cnpj']));
        $this->assertEmpty($this->boleto->processed['sacador']['nome']);
        $this->assertEmpty($this->boleto->processed['sacador']['cpf_cnpj']);
        $this->assertEquals(44, strlen($this->boleto->processed['codigo_de_barras']));
    }
}