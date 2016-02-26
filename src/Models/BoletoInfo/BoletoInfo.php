<?php
namespace Boletos\Models\BoletoInfo;

use Boletos\Models\BoletoInfo\Contracts\BoletoInfoInterface;

class BoletoInfo implements BoletoInfoInterface
{
    protected $data_vencimento;
    protected $nosso_numero;
    protected $aceite;
    protected $especie_doc;
    protected $nome_sacado;
    protected $cpf_cnpj_sacado;
    protected $especie = "R$";
    protected $numero_documento;
    protected $data_documento;
    protected $data_processamento;
    protected $dias_para_pagar;
    protected $taxa;
    protected $multa;
    protected $valor_base;
    protected $valor_final;

    public function getDataVencimentoRecebida()
    {
        return $this->data_vencimento;
    }

    public function getNossoNumeroRecebido()
    {
        return $this->nosso_numero;
    }

    public function getAceite()
    {
        return $this->aceite;
    }

    public function getEspecieDoc()
    {
        return $this->especie_doc;
    }

    public function getNomeSacado()
    {
        return $this->nome_sacado;
    }

    public function getCpfCnpjSacado()
    {
        return $this->cpf_cnpj_sacado;
    }

    public function getEspecieMoeda()
    {
        return $this->especie;
    }

    public function getNumeroDocumento()
    {
        return $this->numero_documento;
    }

    public function getDataDocumento()
    {
        return $this->data_documento;
    }

    public function getDataProcessamento()
    {
        return $this->data_processamento;
    }

    public function getDiasParaPagar()
    {
        return $this->dias_para_pagar;
    }

    public function getTaxaPercentual()
    {
        return $this->taxa;
    }

    public function getMultaPercentual()
    {
        return $this->multa;
    }

    public function getValorBase()
    {
        return $this->valor_base;
    }

    public function getValorFinal($formatado10digitos = FALSE, $inteiro = FALSE)
    {
        $valor_cobrado = $this->info->getValorCobrado();
        $data_base     = Carbon::create(2016, 1, 0, 0, 0, 0);

        $data_vencimento = Carbon::createFromFormat('d/m/Y', $this->info->getDataVencimentoCalculada())
                                 ->setTime(0, 0, 0);
        $data_hoje       = $this->info  ->getDataProcessamento();
        $vencido         = $data_hoje->between($data_base, $data_vencimento, TRUE);

        if ($vencido)
        {
            $valor_cobrado += $this->getValorTaxa(TRUE) + $this->getValorMulta(TRUE);
        }

        if ($inteiro == TRUE)
        {
            if ($formatado10digitos == TRUE)
            {
                $valor_cobrado = $this->formataNumero($valor_cobrado, 10, 0);
            }

            return $valor_cobrado;
        }

        return $this->formataValor($valor_cobrado);
    }

    public function getDataVencimentoCalculada()
    {
        if ($this->getDataVencimentoRecebida() == NULL)
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

        } else
        {
            return $this->getDataVencimentoRecebida();
        }
    }
}