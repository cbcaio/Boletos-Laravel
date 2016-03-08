<?php
namespace CbCaio\Boletos\Models\Boletos\Base;

use CbCaio\Boletos\Generators\Barcode;
use CbCaio\Boletos\Models\Bancos\Contracts\BancoInterface;
use CbCaio\Boletos\Models\Beneficiario\Contracts\BeneficiarioInterface;
use CbCaio\Boletos\Models\BoletoInfo\Contracts\BoletoInfoInterface;
use CbCaio\Boletos\Models\Boletos\Contracts\BoletoInterface;
use CbCaio\Boletos\Models\Pagador\Contracts\PagadorInterface;
use Carbon\Carbon;

abstract class Boleto implements BoletoInterface
{
    protected $banco;
    protected $beneficiario;
    protected $pagador;
    protected $info;
    private   $atributos_parser    = [
        ':taxa',
        ':multa',
        ':vencimento'
    ];
    public    $demonstrativo_array = [];
    public    $instrucoes_array    = [];
    public    $bars                = [];
    public    $processed           =
        [
            /* --------[A]------- */
            'codigo_banco_compensacao'    => '',
            'linha_digitavel'             => '',
            /* --------[B]------- */
            'local_de_pagamento'          => "PREFERENCIALMENTE NAS CASAS LOTÉRICAS ATÉ O VALOR LIMITE",
            'vencimento'                  => 'DD/MM/AAAA',
            /* --------[C]------- */

            'beneficiario'                =>
                [
                    'razao_social' => 'Razão Social ou Nome Fantasia do Beneficiário',
                    'cpf_cnpj'     => 'CPF/CNPJ*',
                    'endereco'     => 'endereco',
                    'cidade'       => 'cidade'
                ],
            /*
             * Formato AAAA / XXXXXX-DV, onde:
             * AAAA: Código da Agência do Beneficiário
             * XXXXXX: Código do Beneficiário
             * DV: Dígito Verificador do Código do Beneficiário (Módulo 11), conforme Anexo VI
             */
            'agencia_codigo_beneficiario' => 'AAAA / XXXXXX-DV',
            /* --------[D]------- */

            'data_do_documento'           => 'DD/MM/AAAA',
            /*
             * Também chamado de “Seu Número”, é o número utilizado
             * e controlado pelo Beneficiário para identificar o título de cobrança
             */
            'nr_do_documento'             => '',
            'especie_doc'                 => '',
            'aceite'                      => '',
            'data_do_processamento'       => 'DD/MM/AAAA',
            /*
             * - Formato: XYNNNNNNNNNNNNNNN-D, onde:
             *  X Modalidade/Carteira de Cobrança (1-Registrada/2-Sem Registro)
             *  Y Emissão do boleto (4-Beneficiário)
             *  NNNNNNNNNNNNNNN Nosso Número (15 posições livres do Beneficiário)
             *  D *Dígito Verificador
             */
            'nosso_numero'                => 'XYNNNNNNNNNNNNNNN-D',
            /* --------[E]------- */
            'carteira'                    => 'SR ou RG',
            'especie_moeda'               => 'R$',
            'valor_documento'             => '< R$ 9.999.999,99',
            'uso_do_banco'                => NULL,//'não preencher',
            'qtde_moeda'                  => NULL,//'não preencher',
            'xValor'                      => NULL,//'não preencher',

            /* --------[F]------- */
            //             'instrucoes'               => 'Preenchido com array',
            'desconto'                    => NULL, //'não preencher',

            /* --------[G]------- */
            /*'juros'                       => NULL,'não preencher',*/

            /* --------[H]------- */
            /*'valor_cobrado'               => NULL,'não preencher',*/

            /* --------[I]------- */
            'pagador'                     =>
                [
                    'nome'              => NULL,
                    'endereco'          => NULL,
                    'cidade_estado_cep' => NULL,
                    'cpf_cnpj'          => NULL//'Obrigatório na Cobrança Registrada.'
                ]
            ,
            'sacador'                     =>
                [
                    'nome'     => 'emitente original do documento que originou o boleto de cobrança',
                    'cpf_cnpj' => ''
                ]
            ,
            /* --------[J]------- */

            'codigo_de_barras'            => ''
        ];

    /**
     * @param BancoInterface        $banco
     * @param BeneficiarioInterface $beneficiario
     * @param PagadorInterface      $pagador
     * @param BoletoInfoInterface   $info
     * @param Barcode               $barcodeGenerator
     */
    public function __construct(
        BancoInterface $banco,
        BeneficiarioInterface $beneficiario,
        PagadorInterface $pagador,
        BoletoInfoInterface $info,
        Barcode $barcodeGenerator
    )

    {
        $this->beneficiario = $beneficiario;
        $this->banco        = $banco;
        $this->info         = $info;
        $this->pagador      = $pagador;

        $this->processaDadosBoleto();

        $this->bars =
            $barcodeGenerator->getBarcode($this->getCodigoBarras(), $barcodeGenerator::TYPE_INTERLEAVED_2_5)['bars'];
    }

    public function processaDadosBoleto()
    {
        $this->processed['codigo_banco_compensacao'] = $this->banco->getCodigoCompensacao();
        $this->processed['linha_digitavel']          = $this->getLinhaDigitavelFormatada();
        $this->processed['beneficiario']             =
            [
                'razao_social' => $this->beneficiario->getRazaoSocial(),
                'agencia'      => $this->beneficiario->getAgencia(),
                'cpf_cnpj'     => $this->beneficiario->getCpfCnpj(),
                'endereco'     => $this->beneficiario->getEndereco(),
                'cidade'       => $this->beneficiario->getCidadeEstado()
            ];
        $this->processed['nosso_numero']             = $this->getNossoNumeroFormatado();
        $this->processed['nr_do_documento']          = $this->info->getNumeroDocumento();
        $this->processed['valor_documento']          = $this->info->getValorFinal();
        $this->processed['especie_doc']              = $this->info->getEspecieDoc();
        $this->processed['aceite']                   = $this->info->getAceite();
        $this->processed['carteira']                 = $this->beneficiario->getCarteira();

        $this->processed['vencimento']                  = $this->info->getDataVencimentoCalculada();
        $this->processed['agencia_codigo_beneficiario'] = $this->beneficiario->getCodigoBeneficiario();
        $this->processed['data_do_documento']           = $this->info->getDataDocumento();
        $this->processed['data_do_processamento']       = $this->info->getDataProcessamento();
        $this->processed['carteira']                    = $this->beneficiario->getCarteira();
        $this->processed['especie_moeda']               = $this->info->getEspecieMoeda();
        $this->processed['pagador']                     =
            [
                'nome'              => $this->pagador->getNome(),
                'endereco'          => $this->pagador->getEndereco(),
                'cidade_estado_cep' => $this->pagador->getCidadeEstadoCep(),
                'cpf_cnpj'          => $this->pagador->getCpfCnpj(),
            ];
        $this->processed['sacador']                     =
            [
                'nome'     => $this->info->getNomeSacado(),
                'cpf_cnpj' => $this->info->getCpfCnpjSacado()
            ];

        $this->processed['codigo_de_barras'] = $this->getCodigoBarras();
    }

    private function getAtributosParser()
    {
        return $this->atributos_parser;
    }

    protected function parseAttributes($string)
    {
        foreach ($this->getAtributosParser() as $attribute)
        {
            if (strpos($string, $attribute) !== FALSE)
            {
                switch ($attribute)
                {
                    case ":taxa":
                        $string = preg_replace("/$attribute" . '\b/', $this->getValorTaxa(), $string);
                        break;
                    case ":multa":
                        $string = preg_replace("/$attribute" . '\b/', $this->getValorMulta(), $string);
                        break;
                    case ":vencimento":
                        $string = preg_replace("/$attribute" . '\b/', $this->info->getDataVencimentoCalculada(),
                                               $string);
                        break;
                }
            }
        }

        return $string;
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
}