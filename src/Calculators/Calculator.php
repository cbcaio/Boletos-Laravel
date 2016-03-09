<?php
namespace CbCaio\Boletos\Calculators;

abstract class Calculator
{
    /**
     * Calcula o modulo de 11, porem em alguns casos o DV Geral nao pode ser 0, entao esta funcao trata isso da
     * seguinte forma:
     *  SE RESULTADO = 0  OU RESULTADO > 9 ENTAO DV = 1
     *
     * @param $numero
     * @return int
     */
    public static function calculaModulo11SemDV0($numero)
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

    /**
     * Calcula o modulo de 11, neste caso fazendo:
     * SE RESULTADO > 9 ENTÃO DV = 0
     *
     * @param $numero
     * @return int
     */
    public static function calculaModulo11($numero)
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

    /**
     * * Calcula o modulo de 10, neste caso fazendo:
     * Quando o resultado da multiplica��o for um n�mero com 2 d�gitos, somar os 2 algarismos
     * Se o Total da Soma for inferior a 10, o DV corresponde � diferen�a entre 10 e o Total da Soma
     * Se o resto da divis�o for 0 (zero), o DV ser� 0 (zero)
     *
     * @param $numero
     * @return int
     */
    public static function calculaModulo10($numero)
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

    /**
     * Formata um número inserindo o valor desejado a esquerda até que o tamanho seja igual a quantidade informada
     *
     * @param string $numero
     * @param string $tamanho
     * @param string $insere
     * @return string
     */
    public static function formataNumero($numero, $tamanho, $insere)
    {
        while (strlen($numero) < $tamanho)
        {
            $numero = $insere . $numero;
        }

        return $numero;
    }

    /**
     * Formata um número colocando virgulas nos decimais e zeros.
     *
     * @param $numero
     * @return string
     */
    public static function formataValor($numero)
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

    /**
     * @param integer $percentual Valor em % ou já divido por 100
     * @param integer $base
     * @return int
     */
    public static function calculaPercentual($percentual, $base)
    {
        if ($percentual > 1)
        {
            $percentual = $percentual / 100;
        }
        $valor = intval($percentual * $base);

        return $valor;
    }

    /**
     * Funcaoo auxiliar para calculo do modulo de 10, faz a soma dos digitos dado os pesos.
     *
     * @param string $numero
     * @param int    $peso_superior
     * @param int    $peso_inferior
     * @return number
     */
    public static function getResultadoSomaModulo10($numero, $peso_superior = 2, $peso_inferior = 1)
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

    /**
     * Funcaoo auxiliar para calculo do modulo de 11, faz a soma dos digitos dado os pesos.
     *
     * @param string $numero
     * @param int    $peso_inferior
     * @param int    $peso_superior
     * @return number
     */
    public static function getResultadoSomaModulo11($numero, $peso_inferior = 2, $peso_superior = 9)
    {
        $numero_array         = str_split($numero, 1);
        $tamanho_numero_array = count($numero_array);

        $resultado_multiplicacao_array = [];

        $multiplicador = $peso_inferior;
        for ($i = $tamanho_numero_array - 1; $i >= 0; $i--)
        {
            $res_multiplicacao                   = $numero_array[ $i ] * $multiplicador;
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
}