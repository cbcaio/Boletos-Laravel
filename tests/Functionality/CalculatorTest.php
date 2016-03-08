<?php
namespace CbCaio\Boletos\Testing;

use CbCaio\Boletos\Calculators\Calculator;

class CalculatorTest extends AbstractTestCase
{
    /** @test */
    public function resultado_da_soma_modulo_10_esperado()
    {
        $valor_entrada  = "104900550";
        $resultado_esperado = 25;
        $this->assertEquals($resultado_esperado,Calculator::getResultadoSomaModulo10($valor_entrada));
    }

    /** @test */
    public function resultado_da_soma_modulo_11_esperado()
    {
        $valor_entrada  = "14000000000000019"; // 17 Posições (Nosso Numero)
        $resultado_esperado = 59;
        $this->assertEquals($resultado_esperado,Calculator::getResultadoSomaModulo11($valor_entrada));

        $valor_entrada  = "005507"; // 6 Posições (Código do Beneficiário)
        $resultado_esperado = 59;
        $this->assertEquals($resultado_esperado,Calculator::getResultadoSomaModulo11($valor_entrada));

        $valor_entrada  = "005507722213334777777777"; // 6 Posições (Código do Beneficiário)
        $resultado_esperado = 538;
        $this->assertEquals($resultado_esperado,Calculator::getResultadoSomaModulo11($valor_entrada));

        $valor_entrada  = "1049324200000321120055077222133347777777771";
        $resultado_esperado = 788;
        $this->assertEquals($resultado_esperado,Calculator::getResultadoSomaModulo11($valor_entrada));
    }

    /** @test */
    public function resultado_do_modulo_10_esperado()
    {
        $valor_entrada  = "104900550";
        $resultado_esperado = 5;
        $this->assertEquals($resultado_esperado,Calculator::calculaModulo10($valor_entrada));
    }

    /** @test */
    public function resultado_do_modulo_11_esperado()
    {
        $valor_entrada  = "14000000000000019";
        $resultado_esperado = 7;
        $this->assertEquals($resultado_esperado,Calculator::calculaModulo11($valor_entrada));

        $valor_entrada  = "005507722213334777777777";
        $resultado_esperado = 1;
        $this->assertEquals($resultado_esperado,Calculator::calculaModulo11($valor_entrada));
    }

    /** @test */
    public function resultado_do_modulo_11_sem_DV_0_esperado()
    {
        $valor_entrada  = "1049324200000321120055077222133347777777771";
        $resultado_esperado = 4;
        $this->assertEquals($resultado_esperado,Calculator::calculaModulo11SemDV0($valor_entrada));
    }
}