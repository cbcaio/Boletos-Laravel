<?php
namespace CbCaio\Boletos\Models\BoletoInfo;

use Carbon\Carbon;
use CbCaio\Boletos\Calculators\Calculator;
use CbCaio\Boletos\Models\BoletoInfo\Base\Boleto;

class BoletoInfo extends Boleto
{
    protected $date_format = 'Y-m-d';

    /**
     * @param bool|FALSE $formatado10digitos
     * @param bool|FALSE $inteiro
     * @return int|null|string
     */
    public function getValorFinal($formatado10digitos = FALSE, $inteiro = FALSE)
    {
        $valor_cobrado = $this->getValorBase();
        $data_base     = Carbon::create(2016, 1, 0, 0, 0, 0);

        $data_hoje       = $this->getDataProcessamento();
        $data_vencimento = $this->getDataVencimentoRecebida();
        $vencido         = !$data_hoje->between($data_base, $data_vencimento, TRUE);

        if ($vencido)
        {
            $diferenca_dias = $data_hoje->diffInDays($data_vencimento);
            $valor_cobrado += $this->getValorTaxa(TRUE) * $diferenca_dias + $this->getValorMulta(TRUE);
        }

        if ($formatado10digitos === TRUE)
        {
            return Calculator::formataNumero($valor_cobrado, 10, 0);
        }

        if ($inteiro === TRUE)
        {
            return $valor_cobrado;
        }

        return Calculator::formataValor($valor_cobrado);
    }

    /**
     * @return Carbon
     */
    public function getDataVencimentoCalculada()
    {
        $data_hoje = Carbon::now()->setTime(0,0,0);
        if ($data_hoje->timestamp > $this->getDataVencimentoRecebida()->timestamp)
        {
            $dias_para_pagar = $this->getDiasParaPagar();
            if ($dias_para_pagar == NULL)
            {
                return $data_hoje;
            } else
            {
                $data_vencimento = $data_hoje->addDay($dias_para_pagar);

                return $data_vencimento;
            }
        } else
        {
            return $this->getDataVencimentoRecebida();
        }
    }

    /**
     * @param bool|FALSE $valor_inteiro
     * @return int|string
     */
    public function getValorTaxa($valor_inteiro = FALSE)
    {
        $valor_taxa = intval(($this->getTaxaPercentual() / 3000) * $this->getValorBase());

        if ($valor_inteiro)
        {
            return $valor_taxa;
        } else
        {
            return Calculator::formataValor($valor_taxa);
        }
    }

    /**
     * @param bool|FALSE $valor_inteiro
     * @return int|string
     */
    public function getValorMulta($valor_inteiro = FALSE)
    {
        $valor_multa = intval(($this->getMultaPercentual() / 100) * $this->getValorBase());

        if ($valor_inteiro)
        {
            return $valor_multa;
        } else
        {
            return Calculator::formataValor($valor_multa);
        }
    }

    /**
     * @return Carbon
     */
    public function getDataVencimentoRecebida()
    {
        if ($this->attributes['data_vencimento'] instanceof Carbon)
        {
            return $this->attributes['data_vencimento']->setTime(0, 0, 0);
        } else
        {
            return Carbon::createFromFormat($this->date_format, $this->attributes['data_vencimento'])
                         ->setTime(0, 0, 0);
        }
    }

    /**
     * @return Carbon
     */
    public function getDataDocumento()
    {
        return Carbon::createFromFormat(
            $this->date_format, $this->attributes['data_documento'])->setTime(0, 0, 0);
    }

    /**
     * @return Carbon
     */
    public function getDataProcessamento()
    {
        if (isset($this->attributes['data_processamento']))
        {
            if ($this->attributes['data_processamento'] instanceof Carbon)
            {
                return $this->attributes['data_processamento'];
            } else
            {
                return Carbon::createFromFormat($this->date_format, $this->attributes['data_processamento'])
                             ->setTime(0, 0, 0);
            }
        } else
        {
            return Carbon::now()->setTime(0, 0, 0);
        }
    }
}