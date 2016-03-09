<?php
namespace CbCaio\Boletos\Models\BoletoInfo\Base;

use CbCaio\Boletos\Models\BoletoInfo\Contracts\BoletoInfoInterface;

abstract class Boleto implements BoletoInfoInterface
{
    /**
     * All of the user's attributes.
     *
     * @var array
     */
    protected $attributes;
    protected $date_format = 'Y-m-d';

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    abstract function getDataVencimentoRecebida();

    abstract function getDataDocumento();

    abstract function getDataProcessamento();

    abstract function getValorFinal($formatado10digitos = FALSE, $inteiro = FALSE);

    abstract function getDataVencimentoCalculada();

    abstract function getValorTaxa($valor_inteiro = FALSE);

    abstract function getValorMulta($valor_inteiro = FALSE);

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

}