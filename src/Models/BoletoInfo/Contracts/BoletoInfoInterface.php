<?php
namespace CbCaio\Boletos\Models\BoletoInfo\Contracts;

use Carbon\Carbon;

interface BoletoInfoInterface
{
    /**
     * @return Carbon
     */
    public function getDataVencimentoRecebida();

    /**
     * @return Carbon
     */
    public function getDataVencimentoCalculada();

    /**
     * @return mixed
     */
    public function getNossoNumeroRecebido();

    /**
     * @return string
     */
    public function getAceite();

    /**
     * @return string
     */
    public function getEspecieDoc();

    /**
     * @return string
     */
    public function getEspecieMoeda();

    /**
     * @return string
     */
    public function getNomeSacado();

    /**
     * @return string
     */
    public function getCpfCnpjSacado();

    /**
     * @return mixed
     */
    public function getNumeroDocumento();

    /**
     * @return Carbon
     */
    public function getDataDocumento();

    /**
     * @return Carbon
     */
    public function getDataProcessamento();

    /**
     * @return integer
     */
    public function getDiasParaPagar();

    /**
     * @return integer
     */
    public function getTaxaPercentual();

    /**
     * @return integer
     */
    public function getMultaPercentual();

    /**
     * @return integer
     */
    public function getValorBase();

    /**
     * @return mixed
     */
    public function getValorFinal();

    /**
     * @param bool|FALSE $valor_inteiro
     * @return string|integer
     */
    public function getValorTaxa($valor_inteiro = FALSE);

    /**
     * @param bool|FALSE $valor_inteiro
     * @return string|integer
     */
    public function getValorMulta($valor_inteiro = FALSE);

}