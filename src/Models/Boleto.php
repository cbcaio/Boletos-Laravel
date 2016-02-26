<?php
namespace DBSoft\packages\src\Models;

use Boletos\Models\Bancos\Base\Banco;
use Boletos\Models\Beneficiario\Contracts\BeneficiarioInterface;
use Carbon\Carbon;
use Boletos\Generators\Barcode;

class  Boleto
{
    public $banco;
    public $processed =
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

    private $atributos_parser = [
        ':taxa',
        ':multa',
        ':vencimento'
    ];

    public $info_recebida       = [];
    public $demonstrativo_array = [];
    public $instrucoes_array    = [];
    public $bars                = [];

    public function __construct(Banco $banco, BeneficiarioInterface $beneficiario, array $info_recebida)
    {
        $this->beneficiario  = $beneficiario;
        $this->banco         = $banco;
        $this->info_recebida = $info_recebida;
        $this->processaDadosBoleto();

        $generator = new Barcode();

        $this->bars = $generator->getBarcode(
            $this->getCodigoBarras(),
            $generator::TYPE_INTERLEAVED_2_5
        )['bars'];
    }

    public function processaDadosBoleto()
    {
        $this->processed['codigo_banco_compensacao'] = $this->banco->getCodigoBancoCompensacao();
        $this->processed['linha_digitavel']          = $this->getLinhaDigitavelFormatada();
        $this->processed['beneficiario']             =
            [
                'razao_social' => $this->getRazaoSocialBeneficiario(),
                'agencia'      => $this->getAgencia(),
                'cpf_cnpj'     => $this->getCpfCnpjBeneficiario(),
                'endereco'     => $this->getEnderecoBeneficiario(),
                'cidade'       => $this->getCidadeEstadoBeneficiario()
            ];
        $this->processed['nosso_numero']             = $this->getNossoNumeroFormatado();
        $this->processed['nr_do_documento']          = $this->getNumeroDocumento();
        $this->processed['valor_documento']          = $this->getValorDocumento();
        $this->processed['especie_doc']              = $this->getEspecieDoc();
        $this->processed['carteira']                 = $this->getCarteiraBeneficiario();
        $this->processed['aceite']                   = $this->getAceite();

        $this->processed['vencimento']                  = $this->getDataVencimento();
        $this->processed['agencia_codigo_beneficiario'] = $this->getAgencia();
        $this->processed['data_do_documento']           = $this->getDataDocumento();
        $this->processed['data_do_processamento']       = $this->getDataProcessamento();
        $this->processed['carteira']                    = $this->getCarteiraBeneficiario();
        $this->processed['especie_moeda']               = $this->getEspecieMoeda();
        $this->processed['pagador']                     =
            [
                'nome'              => $this->getNomePagador(),
                'endereco'          => $this->getEnderecoPagador(),
                'cidade_estado_cep' => $this->getCidadeEstadoCepPagador(),
                'cpf_cnpj'          => $this->getCpfCnpjPagador(),
            ];
        $this->processed['sacador']                     =
            [
                'nome'     => $this->getNomeSacado(),
                'cpf_cnpj' => $this->getCpfCnpjSacado()
            ];

        $this->processed['codigo_de_barras'] = $this->getCodigoBarras();
    }

    private function getCodigoBarrasInicio()
    {
        $banco = $this->banco;

        return $banco->getCodigoBanco() . $banco->getCodigoMoeda();
    }

    private function getCodigoBarrasFinal()
    {
        $codigo_barras_final = $this->calculaFatorVencimento() .
            $this->getValorDocumento(TRUE, TRUE) .
            $this->getCodigoBeneficiario() .
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


    public function calculaLinhaDigitavel()
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

    protected function getValorCobrado()
    {
        return array_get($this->info_recebida, 'valor_cobrado');
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

    protected function getAtributosParser()
    {
        return $this->atributos_parser;
    }

    private function parseAttributes($string)
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
                        $string = preg_replace("/$attribute" . '\b/', $this->getDataVencimento(), $string);
                        break;
                }
            }
        }

        return $string;
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

    public function formataNumero($numero, $tamanho, $insere)
    {
        while (strlen($numero) < $tamanho)
        {
            $numero = $insere . $numero;
        }

        return $numero;
    }

    public function formataValor($numero)
    {
        $tamanho       = strlen($numero);
        $parte_decimal = substr($numero, $tamanho - 2, 2);
        $parte_inteira = substr($numero, 0, $tamanho - 2);

        if ($parte_inteira == '')
        {
            $parte_inteira = '0';
        }

        return $parte_inteira . ',' . $parte_decimal;
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
        return $this->banco->calculaModulo11($this->getCodigoBeneficiario());
    }

    protected function calculaDVLinhaDigitavel($campo)
    {
        return $this->banco->calculaModulo10("$campo");
    }

    protected function calculaDVGeralCodigoBarras()
    {
        $codigo_barras_sem_dv = $this->getCodigoDeBarrasSemDV();

        return $this->banco->calculaModulo11SemDV0($codigo_barras_sem_dv);
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
            $this->getCodigoBeneficiario() .
            $this->calculaDVCodigoBeneficiario() .
            $this->getNossoNumeroSeq1() .
            $this->getNossoNumeroConst1() .
            $this->getNossoNumeroSeq2() .
            $this->getNossoNumeroConst2() .
            $this->getNossoNumeroSeq3();

        return "$campo_livre_parcial";
    }

    private function getDataVencimento()
    {
        if ($this->getDataVencimentoRecebida() != NULL)
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
                $data_documento  = \Carbon\Carbon::createFromFormat('d/m/Y', $this->getDataDocumento());
                $data_vencimento = $data_documento->addDay();

                return $data_vencimento->format('d/m/Y');
            }
        }
    }

    protected function calculaDVCampoLivre()
    {
        return $this->banco->calculaModulo11($this->getCampoLivreSemDV());
    }

    private function getNossoNumeroFormatado()
    {
        return $this->getNossoNumeroRecebido();
    }

    private function getNossoNumeroSemDV()
    {
        $nosso_numero_completo = $this->getNossoNumeroRecebido();
        $nosso_numero_sem_dv   = str_replace('/', "", $nosso_numero_completo);
        $nosso_numero_sem_dv   = preg_replace('/(\-.\b)/', "", $nosso_numero_sem_dv);

        return $nosso_numero_sem_dv;
    }

    private function getAgencia()
    {
        $agencia_do_beneficiario = $this->formataNumero($this->getAgenciaBeneficiario(), 4, 0);
        $codigo_do_beneficiario  = $this->getCodigoBeneficiario();
        $dv_beneficiario         = $this->calculaDVCodigoBeneficiario();

        return $agencia_do_beneficiario . ' / ' . $codigo_do_beneficiario . '-' . $dv_beneficiario;

    }

    private function getValorDocumento($formatado10digitos = FALSE, $inteiro = FALSE)
    {
        $valor_cobrado = $this->getValorCobrado();
        $data_base     = Carbon::create(2016, 1, 0, 0, 0, 0);

        $data_vencimento = Carbon::createFromFormat('d/m/Y', $this->getDataVencimento())->setTime(0, 0, 0);
        $data_hoje       = $this->getDataProcessamento();
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

    /* ------------- INFORMAÇÕES DO RECEBIDAS ------------- */

    private function getNomeSacado()
    {
        return array_get($this->info_recebida, 'nome_sacado');
    }

    private function getCpfCnpjSacado()
    {
        return array_get($this->info_recebida, 'cpf_cnpj_sacado');
    }

    private function getEspecieMoeda()
    {
        if (!array_has($this->info_recebida, 'especie'))
        {
            return "R$";
        }

        return array_get($this->info_recebida, 'especie');
    }

    private function getNumeroDocumento()
    {
        return array_get($this->info_recebida, 'numero_documento');
    }

    private function getDataDocumento()
    {
        return array_get($this->info_recebida, 'data_documento');
    }

    private function getDataProcessamento()
    {
        return array_get($this->info_recebida, 'data_processamento');
    }

    private function getDiasParaPagar()
    {
        if (array_has($this->info_recebida, 'dias_para_pagar'))
        {
            return array_get($this->info_recebida, 'dias_para_pagar');
        } else
        {
            return NULL;
        }
    }

    private function getTaxaPercentual()
    {
        return array_get($this->info_recebida, 'taxa');
    }

    private function getValorTaxa($valor_inteiro = FALSE)
    {
        $taxa       = $this->getTaxaPercentual() / 100;
        $valor_taxa = intval($taxa * $this->getValorCobrado());

        if ($valor_inteiro)
        {
            return $valor_taxa;
        } else
        {
            return $this->formataValor($valor_taxa);
        }
    }

    private function getMultaPencentual()
    {
        return array_get($this->info_recebida, 'multa');
    }

    private function getValorMulta($valor_inteiro = FALSE)
    {
        $multa = $this->getMultaPencentual() / 100;

        $valor_multa = intval($multa * $this->getValorCobrado());

        if ($valor_inteiro)
        {
            return $valor_multa;
        } else
        {
            return $this->formataValor($valor_multa);
        }
    }


    private function getDataVencimentoRecebida()
    {
        return array_get($this->info_recebida, 'data_vencimento');
    }

    private function getNossoNumeroRecebido()
    {
        return array_get($this->info_recebida, 'nosso_numero');
    }

    private function getAceite()
    {
        return array_get($this->info_recebida, 'aceite');
    }

    private function getEspecieDoc()
    {
        return array_get($this->info_recebida, 'especie_doc');
    }

    /* ------------- REFERENTES AO PAGADOR ------------- */

    private function getNomePagador()
    {
        return array_get($this->info_recebida, 'nome_pagador');
    }

    private function getEnderecoPagador()
    {
        return array_get($this->info_recebida, 'endereco_pagador');
    }

    private function getCidadeEstadoCepPagador()
    {
        return array_get($this->info_recebida, 'cidade_estado_cep_pagador');
    }

    private function getCpfCnpjPagador()
    {
        return array_get($this->info_recebida, 'cpf_cnpj_pagador');
    }
}