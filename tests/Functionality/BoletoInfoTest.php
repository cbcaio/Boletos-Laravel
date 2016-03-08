<?php
namespace CbCaio\Boletos\Testing;

use Carbon\Carbon;
use CbCaio\Boletos\Models\BoletoInfo\BoletoInfo;

class BoletoInfoTest extends AbstractTestCase
{
    /** @var BoletoInfo $cef */
    protected $info;

    public function setUp()
    {
        parent::setUp();
        $this->info = new BoletoInfo(
            [
                'data_documento'     => '12/02/2016',
                'data_processamento' => '05/03/2016',
                'dias_para_pagar'    => '1',
                'taxa'               => 0.0985,
                'multa'              => 2,
                'data_vencimento'    => '05/03/2016',
                'valor_base'         => 25800,

            ]);
    }

    /** @test */
    public function verifica_valor_final_para_boleto()
    {
        // Default , formato10digitos false e formato inteiro false
        $valor_esperado = '258,00';
        $this->assertEquals($valor_esperado, $this->info->getValorFinal());
    }

    /** @test */
    public function verifica_valor_final_inteiro()
    {
        // Default , formato10digitos false e formato inteiro true
        $valor_esperado = 25800;
        $this->assertEquals($valor_esperado, $this->info->getValorFinal(FALSE, TRUE));
    }

    /** @test */
    public function verifica_valor_final_inteiro_10_digitos()
    {
        // Default , formato10digitos true e formato inteiro true
        $valor_esperado = '0000025800';
        $this->assertEquals($valor_esperado, $this->info->getValorFinal(TRUE, TRUE));

        // Default , formato10digitos true e formato inteiro true
        $valor_esperado = '0000025800';
        $this->assertEquals($valor_esperado, $this->info->getValorFinal(TRUE, FALSE));
    }

    /** @test */
    public function verifica_valor_final_antes_vencimento()
    {
        $this->info = new BoletoInfo(
            [
                'data_documento'     => '12/02/2016',
                'data_processamento' => '04/03/2016',
                'data_vencimento'    => '05/03/2016',
                'dias_para_pagar'    => '1',
                'taxa'               => 0.0985,
                'multa'              => 2,
                'valor_base'         => 25800,

            ]);
        $this->assertTrue($this->info->getValorFinal(FALSE, TRUE) == 25800);
    }

    /** @test */
    public function verifica_valor_final_dia_vencimento()
    {
        $this->info = new BoletoInfo(
            [
                'data_documento'     => '12/02/2016',
                'data_processamento' => '05/03/2016',
                'data_vencimento'    => '05/03/2016',
                'dias_para_pagar'    => '1',
                'taxa'               => 0.0985,
                'multa'              => 2,
                'valor_base'         => 25800,

            ]);
        $this->assertTrue($this->info->getValorFinal(FALSE, TRUE) == 25800);
    }

    /** @test */
    public function verifica_valor_final_apos_vencimento()
    {
        $this->info = new BoletoInfo(
            [
                'data_documento'     => '12/02/2016',
                'data_processamento' => '08/03/2016',
                'data_vencimento'    => '05/03/2016',
                'dias_para_pagar'    => '1',
                'taxa'               => 0.0985,
                'multa'              => 2,
                'valor_base'         => 25800,

            ]);
        $this->assertTrue($this->info->getValorFinal(FALSE, TRUE) > 25800);
    }

    /** @test */
    public function verifica_data_vencimento_recebida()
    {
        $valor_esperado = Carbon::createFromFormat('d/m/Y', '05/03/2016');
        $this->assertEquals($valor_esperado, $this->info->getDataVencimentoCalculada());
    }

    /** @test */
    public function verifica_data_vencimento_calculada_por_dias()
    {
        $this->info     = new BoletoInfo(
            [
                'data_documento'     => '12/02/2016',
                'data_processamento' => '04/03/2016',
                'dias_para_pagar'    => '1',
                'taxa'               => 0.0985,
                'multa'              => 2,
                'valor_base'         => 25800,

            ]);
        $valor_esperado = Carbon::createFromFormat('d/m/Y', '13/02/2016');
        $this->assertEquals($valor_esperado, $this->info->getDataVencimentoCalculada());

        $this->info     = new BoletoInfo(
            [
                'data_documento'     => '08/03/2016',
                'data_processamento' => '04/03/2016',
                'dias_para_pagar'    => '20',
                'taxa'               => 0.0985,
                'multa'              => 2,
                'valor_base'         => 25800,

            ]);
        $valor_esperado = Carbon::createFromFormat('d/m/Y', '28/03/2016');
        $this->assertEquals($valor_esperado, $this->info->getDataVencimentoCalculada());

        $this->info     = new BoletoInfo(
            [
                'data_documento'     => '08/03/2016',
                'data_processamento' => '04/03/2016',
                'dias_para_pagar'    => '28',
                'taxa'               => 0.0985,
                'multa'              => 2,
                'valor_base'         => 25800,

            ]);
        $valor_esperado = Carbon::createFromFormat('d/m/Y', '05/04/2016');
        $this->assertEquals($valor_esperado, $this->info->getDataVencimentoCalculada());
    }

    /** @test */
    public function verifica_getValorTaxa_retorna_valor_esperado_passando_percentual_formatado()
    {
        $this->info     = new BoletoInfo(
            [
                'taxa'       => 2,
                'valor_base' => 25800,
            ]);
        $valor_esperado = '5,16';
        $this->assertEquals($valor_esperado, $this->info->getValorTaxa());
    }

    /** @test */
    public function verifica_getValorTaxa_retorna_valor_esperado_passando_percentual_inteiro()
    {
        $this->info     = new BoletoInfo(
            [
                'taxa'       => 2,
                'valor_base' => 25800,
            ]);
        $valor_esperado = 516;
        $this->assertEquals($valor_esperado, $this->info->getValorTaxa(TRUE));
    }

    /** @test */
    public function verifica_getValorTaxa_retorna_valor_esperado_passando_percentual_calculado_com_retorno_inteiro()
    {
        $this->info     = new BoletoInfo(
            [
                'taxa'       => 0.02,
                'valor_base' => 25800,
            ]);
        $valor_esperado = 516;
        $this->assertEquals($valor_esperado, $this->info->getValorTaxa(TRUE));
    }

    /** @test */
    public function verifica_getValorTaxa_retorna_valor_esperado_passando_percentual_calculado_com_retorno_formatado()
    {
        $this->info     = new BoletoInfo(
            [
                'taxa'       => 0.02,
                'valor_base' => 25800,
            ]);
        $valor_esperado = '5,16';
        $this->assertEquals($valor_esperado, $this->info->getValorTaxa());
    }

     /** @test */
    public function verifica_getValorMulta_retorna_valor_esperado_passando_percentual_formatado()
    {
        $this->info     = new BoletoInfo(
            [
                'multa'       => 2,
                'valor_base' => 25800,
            ]);
        $valor_esperado = '5,16';
        $this->assertEquals($valor_esperado, $this->info->getValorMulta());
    }

    /** @test */
    public function verifica_getValorMulta_retorna_valor_esperado_passando_percentual_inteiro()
    {
        $this->info     = new BoletoInfo(
            [
                'multa'       => 2,
                'valor_base' => 25800,
            ]);
        $valor_esperado = 516;
        $this->assertEquals($valor_esperado, $this->info->getValorMulta(TRUE));
    }

}