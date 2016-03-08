<?php
namespace CbCaio\Boletos\Testing;

use CbCaio\Boletos\Models\Bancos\BancoCEF;

class BancoCEFTest extends AbstractTestCase
{
    /** @var BancoCEF $cef */
    protected $cef;

    public function setUp()
    {
        parent::setUp();
        $this->cef = new BancoCEF();
    }

    /** @test */
    public function codigo_banco_igual_104()
    {
        $this->assertEquals(104,$this->cef->getCodigoBanco());
    }

    /** @test */
    public function getCodigoCompensacao_retorna_formatado()
    {
        $valor_esperado = "104-0";
        $this->assertEquals($valor_esperado,$this->cef->getCodigoCompensacao());
    }

    /** @test */
    public function getIniciNossoNumero_retorna_valor_esperado()
    {
        $banco_config_1 = new BancoCEF();
        $banco_config_2 = new BancoCEF(1,4);
        $banco_config_3 = new BancoCEF(2,3);
        $banco_config_4 = new BancoCEF(1,3);

        $this->assertEquals("24",$banco_config_1->getInicioNossoNumero());
        $this->assertEquals("14",$banco_config_2->getInicioNossoNumero());
        $this->assertEquals("23",$banco_config_3->getInicioNossoNumero());
        $this->assertEquals("13",$banco_config_4->getInicioNossoNumero());
    }



}