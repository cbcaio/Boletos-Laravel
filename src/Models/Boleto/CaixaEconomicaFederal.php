<?php
namespace CbCaio\Boletos\Models\Boletos;

use CbCaio\Boletos\Calculators\Calculator;
use CbCaio\Boletos\Generators\Barcode;
use CbCaio\Boletos\Models\Bancos\BancoCEF;
use CbCaio\Boletos\Models\Beneficiario\BeneficiarioCEF;
use CbCaio\Boletos\Models\BoletoInfo\BoletoInfo;
use CbCaio\Boletos\Models\Boletos\Base\Boleto;
use CbCaio\Boletos\Models\Pagador\Base\Pagador;
use Carbon\Carbon;

class  BoletoCEF extends Boleto
{
    public function __construct(
        BancoCEF $banco,
        BeneficiarioCEF $beneficiario,
        Pagador $pagador,
        BoletoInfo $info,
        Barcode $barcodeGenerator
    )

    {
        parent::__construct(
            $banco, $beneficiario, $pagador, $info, $barcodeGenerator
        );
    }

    private function getCodigoBarrasInicio()
    {
        $banco = $this->banco;

        return $banco->getCodigoBanco() . $banco->getCodigoMoeda();
    }

    private function getCodigoBarrasFinal()
    {
        $codigo_barras_final = $this->calculaFatorVencimento() .
            $this->info->getValorFinal(TRUE, TRUE) .
            $this->beneficiario->getCodigoBeneficiario() .
            $this->calculaDVCodigoBeneficiario() .
            $this->getNossoNumeroSeq1() .
            $this->getNossoNumeroConst1() .
            $this->getNossoNumeroSeq2() .
            $this->getNossoNumeroConst2() .
            $this->getNossoNumeroSeq3() .
            $this->calculaDVCampoLivre();

        return "$codigo_barras_final";
    }

    public function getCodigoDeBarrasSemDV()
    {
        $codigo_barras_sem_dv = $this->getCodigoBarrasInicio() . $this->getCodigoBarrasFinal();

        return $codigo_barras_sem_dv;
    }

    public function getCodigoBarras()
    {
        $codigo_barras =
            $this->getCodigoBarrasInicio() .
            $this->calculaDVGeralCodigoBarras() .
            $this->getCodigoBarrasFinal();

        return "$codigo_barras";
    }

    private function calculaLinhaDigitavel()
    {
        $codigo_barras = $this->getCodigoBarras();

        $campo_1_sem_dv =
            substr($codigo_barras, 0, 3) .
            substr($codigo_barras, 3, 1) .
            substr($codigo_barras, 19, 5);

        $campo_1 =
            $campo_1_sem_dv .
            $this->calculaDVLinhaDigitavel($campo_1_sem_dv);

        $campo_2_sem_dv = substr($codigo_barras, 24, 10);
        $campo_2        =
            $campo_2_sem_dv .
            $this->calculaDVLinhaDigitavel($campo_2_sem_dv);

        $campo_3_sem_dv = substr($codigo_barras, 34, 10);
        $campo_3        =
            $campo_3_sem_dv .
            $this->calculaDVLinhaDigitavel($campo_3_sem_dv);

        $campo_4 = substr($codigo_barras, 4, 1);
        $campo_5 =
            substr($codigo_barras, 5, 4) .
            $this->formataNumero(substr($codigo_barras, 9, 10), 10, 0);

        $linha_digitavel = $campo_1 . $campo_2 . $campo_3 . $campo_4 . $campo_5;

        return $linha_digitavel;
    }

    public function getLinhaDigitavelFormatada()
    {
        $linha_digitavel = $this->calculaLinhaDigitavel();

        $campo_1 = substr($linha_digitavel, 0, 5) . '.' . substr($linha_digitavel, 5, 5) . ' ';
        $campo_2 = substr($linha_digitavel, 10, 5) . '.' . substr($linha_digitavel, 15, 6) . ' ';
        $campo_3 = substr($linha_digitavel, 21, 5) . '.' . substr($linha_digitavel, 26, 6) . ' ';
        $campo_4 = substr($linha_digitavel, 32, 1) . ' ';
        $campo_5 = substr($linha_digitavel, 33, 14);

        return $campo_1 . $campo_2 . $campo_3 . $campo_4 . $campo_5;
    }

    public function getCampoLivreDoCodigoDeBarras()
    {
        $campo_livre_sem_dv = $this->getCampoLivreSemDV();
        $dv_campo_livre     = $this->calculaDVCampoLivre();

        return "$campo_livre_sem_dv$dv_campo_livre";
    }

    public function adicionaDemonstrativo($string)
    {
        $this->demonstrativo_array[] = $this->parseAttributes($string);

        return $this;
    }

    public function adicionaInstrucao($string)
    {
        $this->instrucoes_array[] = $this->parseAttributes($string);

        return $this;
    }

    protected function calculaFatorVencimento()
    {
        $data = $this->getDataVencimento();

        if (is_null($data))
        {
            return "0000";
        } else
        {
            $data_base       = Carbon::create(1997, 10, 7, 0);
            $data_vencimento = Carbon::createFromFormat("d/m/Y", $data)->setTime(0, 0);
            $diferenca_dias  = $data_base->diffInDays($data_vencimento);

            return "$diferenca_dias";
        }
    }

    protected function calculaDVNossoNumero($peso_inferior = 2, $peso_superior = 9)
    {
        $nosso_numero         = $this->getNossoNumeroSemDV();
        $numero_array         = str_split($nosso_numero, 1);
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
        $soma_resultados = array_sum($resultado_multiplicacao_array);
        $resto_divisao   = $soma_resultados % 11;
        $valor_final     = 11 - $resto_divisao;

        if ($valor_final > 9)
        {
            return 0;
        } else
        {
            return $valor_final;
        }
    }

    protected function calculaDVCodigoBeneficiario()
    {
        return Calculator::calculaModulo11($this->beneficiario->getCodigoBeneficiario());
    }

    protected function calculaDVLinhaDigitavel($campo)
    {
        return Calculator::calculaModulo10("$campo");
    }

    protected function calculaDVGeralCodigoBarras()
    {
        $codigo_barras_sem_dv = $this->getCodigoDeBarrasSemDV();

        return Calculator::calculaModulo11SemDV0($codigo_barras_sem_dv);
    }

    protected function getNossoNumeroConst1()
    {
        return substr($this->getNossoNumeroSemDV(), 0, 1);
    }

    protected function getNossoNumeroConst2()
    {
        return substr($this->getNossoNumeroSemDV(), 1, 1);
    }

    protected function getNossoNumeroSeq1()
    {
        return substr($this->getNossoNumeroSemDV(), 2, 3);
    }

    protected function getNossoNumeroSeq2()
    {
        return substr($this->getNossoNumeroSemDV(), 5, 3);
    }

    protected function getNossoNumeroSeq3()
    {
        return substr($this->getNossoNumeroSemDV(), 8, 9);
    }

    protected function getCampoLivreSemDV()
    {
        $campo_livre_parcial =
            $this->beneficiario->getCodigoBeneficiario() .
            $this->calculaDVCodigoBeneficiario() .
            $this->getNossoNumeroSeq1() .
            $this->getNossoNumeroConst1() .
            $this->getNossoNumeroSeq2() .
            $this->getNossoNumeroConst2() .
            $this->getNossoNumeroSeq3();

        return "$campo_livre_parcial";
    }

    protected function calculaDVCampoLivre()
    {
        return Calculator::calculaModulo11($this->getCampoLivreSemDV());
    }

    private function getNossoNumeroFormatado()
    {
        return $this->getNossoNumeroRecebido();
    }

    private function getNossoNumeroSemDV()
    {
        $nosso_numero_completo = $this->info->getNossoNumeroRecebido();
        $nosso_numero_sem_dv   = str_replace('/', "", $nosso_numero_completo);
        $nosso_numero_sem_dv   = preg_replace('/(\-.\b)/', "", $nosso_numero_sem_dv);

        return $nosso_numero_sem_dv;
    }

    private function getAgenciaFormatada()
    {
        $agencia_do_beneficiario = Calculator::formataNumero($this->beneficiario->getAgencia(), 4, 0);
        $codigo_do_beneficiario  = $this->beneficiario->getCodigoBeneficiario();
        $dv_beneficiario         = $this->calculaDVCodigoBeneficiario();

        return $agencia_do_beneficiario . ' / ' . $codigo_do_beneficiario . '-' . $dv_beneficiario;
    }






}