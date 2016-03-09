<?php
namespace CbCaio\Boletos\Models\Boletos;

use CbCaio\Boletos\Calculators\Calculator;
use CbCaio\Boletos\Models\Bancos\BancoCEF;
use CbCaio\Boletos\Models\Beneficiario\BeneficiarioCEF;
use CbCaio\Boletos\Models\BoletoInfo\BoletoInfo;
use CbCaio\Boletos\Models\Boletos\Base\Boleto;
use Carbon\Carbon;
use CbCaio\Boletos\Models\Pagador\Pagador;

class BoletoCEF extends Boleto
{
    protected $date_format = 'Y-m-d';

    /**
     * @param BancoCEF        $banco
     * @param BeneficiarioCEF $beneficiario
     * @param Pagador         $pagador
     * @param BoletoInfo      $info
     */
    public function __construct(
        BancoCEF $banco,
        BeneficiarioCEF $beneficiario,
        Pagador $pagador,
        BoletoInfo $info
    )
    {
        parent::__construct(
            $banco, $beneficiario, $pagador, $info
        );
    }

    /**
     * @return string
     */
    public function getCodigoBarras()
    {
        $codigo_barras =
            $this->getCodigoBarrasInicio() .
            $this->calculaDVGeralCodigoBarras() .
            $this->getCodigoBarrasFinal();

        return "$codigo_barras";
    }

    /**
     * @return string
     */
    public function getLinhaDigitavelFormatada()
    {
        return $this->formataLinhaDigitavel($this->calculaLinhaDigitavel());
    }

    /**
     * @return string
     */
    public function getNossoNumeroFormatado()
    {
        $nosso_numero_sem_dv = $this->getNossoNumeroSemDV();
        $parte_1             = substr($nosso_numero_sem_dv, 0, 2);
        $parte_2             = substr($nosso_numero_sem_dv, 2, 15);
        $parte_3             = $this->calculaDVNossoNumero($this->getNossoNumeroSemDV());

        return $parte_1 . '/' . $parte_2 . '-' . $parte_3;
    }

    /**
     * @param string $linha_digitavel
     * @return string
     */
    public function formataLinhaDigitavel($linha_digitavel)
    {
        $campo_1 = substr($linha_digitavel, 0, 5) . '.' . substr($linha_digitavel, 5, 5) . ' ';
        $campo_2 = substr($linha_digitavel, 10, 5) . '.' . substr($linha_digitavel, 15, 6) . ' ';
        $campo_3 = substr($linha_digitavel, 21, 5) . '.' . substr($linha_digitavel, 26, 6) . ' ';
        $campo_4 = substr($linha_digitavel, 32, 1) . ' ';
        $campo_5 = substr($linha_digitavel, 33, 14);

        return $campo_1 . $campo_2 . $campo_3 . $campo_4 . $campo_5;
    }

    /**
     * @return string
     */
    protected function getCodigoBarrasInicio()
    {
        $banco = $this->banco;

        return $banco->getCodigoBanco() . $banco->getCodigoMoeda();
    }

    /**
     * @return string
     */
    protected function getCodigoBarrasFinal()
    {
        $codigo_barras_final = $this->calculaFatorVencimento($this->info->getDataVencimentoCalculada()) .
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

    /**
     * @return string
     */
    protected function getCodigoDeBarrasSemDV()
    {
        $codigo_barras_sem_dv = $this->getCodigoBarrasInicio() . $this->getCodigoBarrasFinal();

        return $codigo_barras_sem_dv;
    }

    /**
     * @return string
     */
    protected function calculaLinhaDigitavel()
    {
        $codigo_barras  = $this->getCodigoBarras();
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
            Calculator::formataNumero(substr($codigo_barras, 9, 10), 10, 0);

        $linha_digitavel = $campo_1 . $campo_2 . $campo_3 . $campo_4 . $campo_5;

        return $linha_digitavel;
    }

    /**
     * @param string $date_format
     */
    public function setDateFormat($date_format)
    {
        $this->date_format = $date_format;
    }

    /**
     * @return string
     */
    protected function getCampoLivreDoCodigoDeBarras()
    {
        $campo_livre_sem_dv = $this->getCampoLivreSemDV();
        $dv_campo_livre     = $this->calculaDVCampoLivre($campo_livre_sem_dv);

        return "$campo_livre_sem_dv$dv_campo_livre";
    }

    /**
     * @param string $nosso_numero
     * @return string
     */
    public function formataNossoNumeroSemDV($nosso_numero)
    {
        $nosso_numero_sem_dv = str_replace('/', "", $nosso_numero);
        $nosso_numero_sem_dv = preg_replace('/(\-.\b)/', "", $nosso_numero_sem_dv);

        return "$nosso_numero_sem_dv";
    }

    /**
     * @param Carbon $data_vencimento
     * @return string
     */
    public function calculaFatorVencimento($data_vencimento)
    {
        if ($data_vencimento == NULL)
        {
            return "0000";
        }

        if (!($data_vencimento instanceof Carbon))
        {
            $data_vencimento = Carbon::createFromFormat($this->date_format, $data_vencimento)->setTime(0, 0, 0);
        }

        $data_base      = Carbon::create(1997, 10, 7, 0);
        $diferenca_dias = $data_base->diffInDays($data_vencimento);

        return "$diferenca_dias";
    }

    /**
     * @param string $nosso_numero_sem_dv
     * @return int
     */
    public function calculaDVNossoNumero($nosso_numero_sem_dv = NULL)
    {
        $peso_inferior = 2;
        $peso_superior = 9;

        if ($nosso_numero_sem_dv === NULL)
        {
            $nosso_numero = $this->getNossoNumeroSemDV();
        } else
        {
            $nosso_numero = $nosso_numero_sem_dv;
        }
        $soma_resultados = Calculator::getResultadoSomaModulo11($nosso_numero);
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

    /**
     * @param string $codigo_beneficiario
     * @return int
     */
    public function calculaDVCodigoBeneficiario($codigo_beneficiario = NULL)
    {
        if ($codigo_beneficiario === NULL)
        {
            return Calculator::calculaModulo11($this->beneficiario->getCodigoBeneficiario());
        } else
        {
            return Calculator::calculaModulo11($codigo_beneficiario);
        }
    }

    /**
     * @param string $campo
     * @return int
     */
    public function calculaDVLinhaDigitavel($campo)
    {
        return Calculator::calculaModulo10("$campo");
    }

    /**
     * @param string $codigo_barras_sem_dv
     * @return int
     */
    public function calculaDVGeralCodigoBarras($codigo_barras_sem_dv = NULL)
    {
        if ($codigo_barras_sem_dv === NULL)
        {
            return Calculator::calculaModulo11SemDV0($this->getCodigoDeBarrasSemDV());
        } else
        {
            return Calculator::calculaModulo11SemDV0($codigo_barras_sem_dv);
        }
    }

    /**
     * @param string $campo_livre_sem_dv
     * @return int
     */
    public function calculaDVCampoLivre($campo_livre_sem_dv = NULL)
    {
        if ($campo_livre_sem_dv === NULL)
        {
            return Calculator::calculaModulo11($this->getCampoLivreSemDV());
        } else
        {
            return Calculator::calculaModulo11($campo_livre_sem_dv);
        }
    }

    /**
     * @return string
     */
    private function getNossoNumeroConst1()
    {
        return substr($this->getNossoNumeroSemDV(), 0, 1);
    }

    /**
     * @return string
     */
    private function getNossoNumeroConst2()
    {
        return substr($this->getNossoNumeroSemDV(), 1, 1);
    }

    /**
     * @return string
     */
    private function getNossoNumeroSeq1()
    {
        return substr($this->getNossoNumeroSemDV(), 2, 3);
    }

    /**
     * @return string
     */
    private function getNossoNumeroSeq2()
    {
        return substr($this->getNossoNumeroSemDV(), 5, 3);
    }

    /**
     * @return string
     */
    private function getNossoNumeroSeq3()
    {
        return substr($this->getNossoNumeroSemDV(), 8, 9);
    }

    /**
     * @return string
     */
    private function getCampoLivreSemDV()
    {
        $campo_livre_parcial =
            $this->beneficiario->getCodigoBeneficiario() .
            $this->calculaDVCodigoBeneficiario($this->beneficiario->getCodigoBeneficiario()) .
            $this->getNossoNumeroSeq1() .
            $this->getNossoNumeroConst1() .
            $this->getNossoNumeroSeq2() .
            $this->getNossoNumeroConst2() .
            $this->getNossoNumeroSeq3();

        return "$campo_livre_parcial";
    }

    /**
     * @return string
     */
    public function getNossoNumeroSemDV()
    {
        $nosso_numero_recebido = $this->info->getNossoNumeroRecebido();

        if (strlen($nosso_numero_recebido) == 15)
        {
            $nosso_numero_recebido = $this->banco->getInicioNossoNumero() . $nosso_numero_recebido;
        }

        return $this->formataNossoNumeroSemDV($nosso_numero_recebido);
    }

    public function getAgenciaCodigoBeneficiarioDv()
    {
        $codigo_beneficiario = $this->beneficiario->getCodigoBeneficiario();
        $agencia             = $this->beneficiario->getAgencia();
        $dv                  = $this->calculaDVCodigoBeneficiario($codigo_beneficiario);

        return $agencia . ' / ' . $codigo_beneficiario . '-' . $dv;
    }
}