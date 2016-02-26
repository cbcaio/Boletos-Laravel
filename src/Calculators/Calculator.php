<?php
namespace  CbCaio\Boletos\Calculators;

abstract class Calculator
{
    static function calculaModulo11SemDV0($numero)
    {
        $soma_resultados = Calculator::getResultadoSomaModulo11($numero);
        $resto_divisao   = $soma_resultados % 11;
        $valor_final     = 11 - $resto_divisao;

        if ($valor_final <= 0 || $valor_final > 9)
        {
            return 1;
        } else
        {
            return $valor_final;
        }
    }

    static function calculaModulo11($numero)
    {
        $soma_resultados = Calculator::getResultadoSomaModulo11($numero);
        if ($soma_resultados < 11)
        {
            return 11 - $soma_resultados;
        } else
        {
            $resto_divisao = $soma_resultados % 11;
            $valor_final   = 11 - $resto_divisao;

            if ($valor_final > 9)
            {
                return 0;
            } else
            {
                return $valor_final;
            }
        }
    }

    static function calculaModulo10($numero)
    {
        $soma_resultados = Calculator::getResultadoSomaModulo10($numero);

        if ($soma_resultados < 10)
        {
            return 10 - $soma_resultados;
        } else
        {
            $resto_divisao = $soma_resultados % 10;

            if ($resto_divisao === 0)
            {
                return $resto_divisao;
            }

            return 10 - $resto_divisao;
        }
    }

    static function getResultadoSomaModulo10($numero, $peso_superior = 2, $peso_inferior = 1)
    {
        $numero_array                  = str_split($numero, 1);
        $tamanho_numero_array          = count($numero_array);
        $resultado_multiplicacao_array = [];

        $multiplicador = $peso_superior;
        for ($i = $tamanho_numero_array - 1; $i >= 0; $i--)
        {
            $res_multiplicacao = $numero_array[ $i ] * $multiplicador;

            if ($res_multiplicacao >= 10)
            {
                $resultado_array   = str_split($res_multiplicacao, 1);
                $digito_dezena     = $resultado_array[0];
                $digito_unidade    = $resultado_array[1];
                $res_multiplicacao = (int)$digito_dezena + (int)$digito_unidade;
            }
            $resultado_multiplicacao_array[ $i ] = $res_multiplicacao;

            if ($multiplicador <= $peso_inferior)
            {
                $multiplicador = $peso_superior;
            } else
            {
                $multiplicador--;
            }
        }

        return array_sum($resultado_multiplicacao_array);
    }

    static function getResultadoSomaModulo11($numero, $peso_inferior = 2, $peso_superior = 9)
    {
        $numero_array         = str_split($numero, 1);
        $tamanho_numero_array = count($numero_array);

        $resultado_multiplicacao_array = [];

        $multiplicador = $peso_inferior;
        for ($i = $tamanho_numero_array - 1; $i >= 0; $i--)
        {
            $res_multiplicacao = $numero_array[ $i ] * $multiplicador;

            $resultado_multiplicacao_array[ $i ] = $res_multiplicacao;

            if ($multiplicador >= $peso_superior)
            {
                $multiplicador = $peso_inferior;
            } else
            {
                $multiplicador++;
            }
        }

        return array_sum($resultado_multiplicacao_array);
    }

    static function formataNumero($numero, $tamanho, $insere)
    {
        while (strlen($numero) < $tamanho)
        {
            $numero = $insere . $numero;
        }

        return $numero;
    }

    static function formataValor($numero)
    {
        $tamanho       = strlen($numero);
        $parte_decimal = substr($numero, $tamanho - 2, 2);
        $parte_inteira = substr($numero, 0, $tamanho - 2);

        if ($parte_inteira == '')
        {
            $parte_inteira = '0';
        }

        return $parte_inteira . ',' . $parte_decimal;
    }
}