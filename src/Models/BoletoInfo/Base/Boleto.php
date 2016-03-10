<?php
namespace CbCaio\Boletos\Models\BoletoInfo\Base;

use Carbon\Carbon;
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

    /**
     * @return Carbon
     */
    public abstract function getDataVencimentoRecebida();

    /**
     * @return Carbon
     */
    public abstract function getDataDocumento();

    /**
     * @return Carbon
     */
    public abstract function getDataProcessamento();

    /**
     * @param bool|FALSE $formatado10digitos
     * @param bool|FALSE $inteiro
     * @return string|integer
     */
    public abstract function getValorFinal($formatado10digitos = FALSE, $inteiro = FALSE);

    /**
     * @return Carbon
     */
    public abstract function getDataVencimentoCalculada();

    /**
     * @param bool|FALSE $valor_inteiro
     * @return string|integer
     */
    public abstract function getValorTaxa($valor_inteiro = FALSE);

    /**
     * @param bool|FALSE $valor_inteiro
     * @return string|integer
     */
    public abstract function getValorMulta($valor_inteiro = FALSE);

    /**
     * @return null|string
     */
    public function getNossoNumeroRecebido()
    {
        return isset($this->attributes['nosso_numero']) ? $this->attributes['nosso_numero'] : NULL;
    }

    /**
     * @return null|string
     */
    public function getAceite()
    {
        return isset($this->attributes['aceite']) ? $this->attributes['aceite'] : NULL;
    }

    /**
     * @return null|string
     */
    public function getEspecieDoc()
    {
        return isset($this->attributes['especie_doc']) ? $this->attributes['especie_doc'] : NULL;
    }

    /**
     * @return null|string
     */
    public function getNomeSacado()
    {
        return isset($this->attributes['nome_sacado']) ? $this->attributes['nome_sacado'] : NULL;
    }

    /**
     * @return null|string
     */
    public function getCpfCnpjSacado()
    {
        return isset($this->attributes['cpf_cnpj_sacado']) ? $this->attributes['cpf_cnpj_sacado'] : NULL;
    }

    /**
     * @return null|string
     */
    public function getEspecieMoeda()
    {
        return isset($this->attributes['especie']) ? $this->attributes['especie'] : NULL;
    }

    /**
     * @return null|string
     */
    public function getNumeroDocumento()
    {
        return isset($this->attributes['numero_documento']) ? $this->attributes['numero_documento'] : NULL;
    }

    /**
     * @return null|integer
     */
    public function getDiasParaPagar()
    {
        return isset($this->attributes['dias_para_pagar']) ? $this->attributes['dias_para_pagar'] : NULL;
    }

    /**
     * @return null|string
     */
    public function getTaxaPercentual()
    {
        return isset($this->attributes['taxa']) ? $this->attributes['taxa'] : NULL;
    }

    /**
     * @return null|string
     */
    public function getMultaPercentual()
    {
        return isset($this->attributes['multa']) ? $this->attributes['multa'] : NULL;
    }

    /**
     * @return null|integer
     */
    public function getValorBase()
    {
        return isset($this->attributes['valor_base']) ? $this->attributes['valor_base'] : NULL;
    }

}