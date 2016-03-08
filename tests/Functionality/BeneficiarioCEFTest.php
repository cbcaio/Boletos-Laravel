<?php
namespace CbCaio\Boletos\Testing;

use CbCaio\Boletos\Models\Beneficiario\BeneficiarioCEF;

class BeneficiarioCEFTest extends AbstractTestCase
{
    /** @var BeneficiarioCEF $cef */
    protected $beneficiario;

    public function setUp()
    {
        parent::setUp();
        $this->beneficiario = new BeneficiarioCEF();
    }

    /** @test */
    public function verifica_se_codigo_beneficiario_esta_formatado()
    {
        $this->assertEquals("123456", $this->beneficiario->getConta());
    }
    /** @test */
    public function verifica_informacoes_inicializadas_do_config()
    {
        $this->assertEquals("123456", $this->beneficiario->getCodigoBeneficiario());
        $this->assertEquals("1234", $this->beneficiario->getAgencia());
        $this->assertEquals("02", $this->beneficiario->getCarteira());
        $this->assertEquals("Ouro Fino / Minas Gerais", $this->beneficiario->getCidadeEstado());
        $this->assertEquals("12.123.123/0001-23", $this->beneficiario->getCpfCnpj());
        $this->assertEquals("Endereço da Empresa", $this->beneficiario->getEndereco());
        $this->assertEquals("Razão Social da Empresa", $this->beneficiario->getRazaoSocial());
    }


}