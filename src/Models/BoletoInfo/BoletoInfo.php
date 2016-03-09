<?php
namespace CbCaio\Boletos\Models\BoletoInfo;

use Carbon\Carbon;
use CbCaio\Boletos\Calculators\Calculator;
use CbCaio\Boletos\Models\BoletoInfo\Base\Boleto;

class BoletoInfo extends Boleto
{
    protected $date_format = 'Y-m-d';

    public function getValorFinal($formatado10digitos = FALSE, $inteiro = FALSE)
    {
        $valor_cobrado = $this->getValorBase();
        $data_base     = Carbon::create(2016, 1, 0, 0, 0, 0);

        $data_vencimento = $this->getDataVencimentoCalculada();
        $data_hoje       = $this->getDataProcessamento();
        $vencido         = !$data_hoje->between($data_base, $data_vencimento, TRUE);

        if ($vencido)
        {
            $valor_cobrado += $this->getValorTaxa(TRUE) + $this->getValorMulta(TRUE);
        }

        if ($formatado10digitos == TRUE)
        {
            return Calculator::formataNumero($valor_cobrado, 10, 0);
        }

        if ($inteiro == TRUE)
        {
            return $valor_cobrado;
        }

        return Calculator::formataValor($valor_cobrado);
    }

    public function getDataVencimentoCalculada()
    {

        if ($this->getDataVencimentoRecebida())
        {
            return $this->getDataVencimentoRecebida();
        } else
        {
            $dias_para_pagar = $this->getDiasParaPagar();
            if ($dias_para_pagar === NULL)
            {
                return NULL;
            } else
            {
                $data_documento  = $this->getDataDocumento();
                $data_vencimento = $data_documento->addDay($dias_para_pagar);

                return $data_vencimento;
            }
        }
    }

    public function getValorTaxa($valor_inteiro = FALSE)
    {
        $valor_taxa = Calculator::calculaPercentual($this->getTaxaPercentual(), $this->getValorBase());
        if ($valor_inteiro)
        {
            return $valor_taxa;
        } else
        {
            return Calculator::formataValor($valor_taxa);
        }
    }

    public function getValorMulta($valor_inteiro = FALSE)
    {
        $valor_multa = Calculator::calculaPercentual($this->getMultaPercentual(), $this->getValorBase());
        if ($valor_inteiro)
        {
            return $valor_multa;
        } else
        {
            return Calculator::formataValor($valor_multa);
        }
    }

    /**
     * @return Carbon|null
     */
    public function getDataVencimentoRecebida()
    {
        if (isset($this->attributes['data_vencimento']))
        {
            return Carbon::createFromFormat($this->date_format, $this->attributes['data_vencimento'])
                         ->setTime(0, 0, 0);
        } else
        {
            return NULL;
        }
    }

    /**
     * @return Carbon|null
     */
    public function getDataDocumento()
    {
        if (isset($this->attributes['data_documento']))
        {
            return Carbon::createFromFormat($this->date_format, $this->attributes['data_documento'])
                         ->setTime(0, 0, 0);
        } else
        {
            return NULL;
        }

        /**
         * @return Carbon|null
         */
    }

    public function getDataProcessamento()
    {
        if (isset($this->attributes['data_processamento']))
        {
            return Carbon::createFromFormat($this->date_format, $this->attributes['data_processamento'])
                         ->setTime(0, 0, 0);
        } else
        {
            return NULL;
        }
    }
}