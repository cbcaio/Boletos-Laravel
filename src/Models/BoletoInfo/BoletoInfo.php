<?php
namespace CbCaio\Boletos\Models\BoletoInfo;

use CbCaio\Boletos\Calculators\Calculator;
use CbCaio\Boletos\Models\BoletoInfo\Contracts\BoletoInfoInterface;
use Carbon\Carbon;

class BoletoInfo implements BoletoInfoInterface
{
    /**
     * All of the user's attributes.
     *
     * @var array
     */
    protected $attributes;

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function getDataVencimentoRecebida()
    {
        if (isset($this->attributes['data_vencimento']))
        {
            return Carbon::createFromFormat('d/m/Y', $this->attributes['data_vencimento']);
        } else
        {
            return NULL;
        }
    }

    public function getNossoNumeroRecebido()
    {
        return $this->attributes['nosso_numero'];
    }

    public function getAceite()
    {
        return $this->attributes['aceite'];
    }

    public function getEspecieDoc()
    {
        return $this->attributes['especie_doc'];
    }

    public function getNomeSacado()
    {
        return $this->attributes['nome_sacado'];
    }

    public function getCpfCnpjSacado()
    {
        return $this->attributes['cpf_cnpj_sacado'];
    }

    public function getEspecieMoeda()
    {
        return $this->attributes['especie'];
    }

    public function getNumeroDocumento()
    {
        return $this->attributes['numero_documento'];
    }

    public function getDataDocumento()
    {
        if (isset($this->attributes['data_documento']))
        {
            return Carbon::createFromFormat('d/m/Y', $this->attributes['data_documento']);
        } else
        {
            return NULL;
        }
    }

    public function getDataProcessamento()
    {
        if (isset($this->attributes['data_processamento']))
        {
            return Carbon::createFromFormat('d/m/Y', $this->attributes['data_processamento']);
        } else
        {
            return NULL;
        }
    }

    public function getDiasParaPagar()
    {
        return $this->attributes['dias_para_pagar'];
    }

    public function getTaxaPercentual()
    {
        return $this->attributes['taxa'];
    }

    public function getMultaPercentual()
    {
        return $this->attributes['multa'];
    }

    public function getValorBase()
    {
        return $this->attributes['valor_base'];
    }

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
        if ($this->getDataVencimentoRecebida() instanceof Carbon)
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


}