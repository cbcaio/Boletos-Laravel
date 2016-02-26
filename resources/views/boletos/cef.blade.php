<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.0 Transitional//EN'>
<html>
<head>
    <title>{{ $boleto->beneficiario['titulo_pagina'] }}</title>
    <meta http-equiv=Content-Type content=text/html charset=UTF-8>
    <meta name="Gerador Boletos" content="Projeto Boletos Laravel"/>

    <style type=text/css>
        .cp {
            font: bold 10px Arial;
            color: black
        }

        .ti {
            font: 9px Arial, Helvetica, sans-serif
        }

        .ld {
            font: bold 15px Arial;
            color: #000000
        }

        .ct {
            FONT: 9px "Arial Narrow";
            COLOR: #000033
        }

        .cn {
            FONT: 9px Arial;
            COLOR: black
        }

        .bc {
            font: bold 20px Arial;
            color: #000000
        }

        .ld2 {
            font: bold 12px Arial;
            color: #000000
        }
    </style>
</head>

<body text=#000000 bgColor=#ffffff topMargin=0 rightMargin=0>

<table width=666 cellspacing=0 cellpadding=0 border=0>
    <tr>
        <td valign=top class=cp>
            <div ALIGN="CENTER">
                Instruções de Impressão
            </div>
        </td>
    </tr>
    <tr>
        <td valign=top class=cp>
            <DIV ALIGN="left">
                <p>
                <li>Imprima em impressora jato de tinta (ink jet) ou laser em qualidade normal ou alta (Não use
                    modo econômico).<br></li>
                <li>Utilize folha A4 (210 x 297 mm) ou Carta (216 x 279 mm) e margens mínimas à esquerda e à direita do
                    formulário.<br></li>
                <li>Corte na linha indicada. Não rasure, risque, fure ou dobre a região onde se encontra o código de
                    barras.<br></li>
                <li>Caso não apareça o código de barras no final, clique em F5 para atualizar esta tela.</li>
                <li>Caso tenha problemas ao imprimir, copie a seqüencia numérica abaixo e pague no caixa eletrônico ou
                    no internet banking:<br><br></li>
                    <span class="ld2">
                    &nbsp;&nbsp;&nbsp;&nbsp;Linha Digitável: &nbsp;{{ $boleto->processed["linha_digitavel"] }}<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;Valor: &nbsp;&nbsp;R$ {{ $boleto->processed["valor_documento"] }}<br>
                    </span>
                </p>
            </DIV>
        </td>
    </tr>
</table>

<br>

<table cellspacing=0 cellpadding=0 width=666 border=0>
    <tbody>
    <tr>
        <td class=ct width=666><img height=1 src="{{ asset('imagens/6.png') }}" width=665 border=0>
        </td>
    </tr>
    <tr>
        <td class=ct width=666>
            <div align=right>
                <b class=cp>
                    Recibo do Sacado
                </b>
            </div>
        </td>
    </tr>
    </tbody>
</table>

<table width=666 cellspacing=5 cellpadding=0 border=0>
    <tr>
        <td width=41>

        </td>
    </tr>
</table>

<table cellspacing=0 cellpadding=0 width=666 border=0>
    <tr>
        <td class=cp width=150>
              <span class="campo">
                  <IMG src="{{ asset('imagens/logocaixa.jpg') }}" width="150" height="40" border=0>
              </span>
        </td>
        <td width=3 valign=bottom>
            <img height=22 src="{{ asset('imagens/3.png') }}" width=2 border=0>
        </td>
        <td class=cpt width=58 valign=bottom>
            <div align=center>
                <span class=bc>
                    {{ $boleto->processed["codigo_banco_compensacao"] }}
                </span>
            </div>
        </td>
        <td width=3 valign=bottom>
            <img height=22 src="{{ asset('imagens/3.png') }}" width=2 border=0>
        </td>
        <td class=ld align=right width=453 valign=bottom>
                <span class=ld>
                    <span class="campotitulo">
                        {{ $boleto->processed["linha_digitavel"] }}
                    </span>
                </span>
        </td>
    </tr>
    <tbody>
    <tr>
        <td colspan=5>
            <img height=2 src="{{ asset('imagens/2.png') }}" width=666 border=0>
        </td>
    </tr>
    </tbody>
</table>

<table cellspacing=0 cellpadding=0 border=0>
    <tbody>
    <tr>
        <td class=ct valign=top width=7 height=13>
            <img height=13 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=ct valign=top width=268 height=13>
            Cedente
        </td>
        <td class=ct valign=top width=7 height=13>
            <img height=13 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=ct valign=top width=156 height=13>
            Agência/Código do Cedente
        </td>
        <td class=ct valign=top width=7 height=13>
            <img height=13 src="{{ asset('imagens/1.png')}}" width=1 border=0>
        </td>
        <td class=ct valign=top width=34 height=13>
            Espécie
        </td>
        <td class=ct valign=top width=7 height=13>
            <img height=13 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=ct valign=top width=53 height=13>
            Quantidade
        </td>
        <td class=ct valign=top width=7 height=13>
            <img height=13 src="{{ asset('imagens/1.png')}}" width=1 border=0>
        </td>
        <td class=ct valign=top width=120 height=13>
            Nosso número
        </td>
    </tr>
    <tr>
        <td class=cp valign=top width=7 height=12>
            <img height=12 src="{{ asset('imagens/1.png')}}" width=1 border=0>
        </td>
        <td class=cp valign=top width=268 height=12>
                <span class="campo">
                    {{ $boleto->processed['beneficiario']['razao_social'] }}
                </span>
        </td>
        <td class=cp valign=top width=7 height=12>
            <img height=12 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=cp valign=top width=156 height=12>
                <span class="campo">
                    {{ $boleto->processed['codigo_banco_compensacao'] }}
                </span>
        </td>
        <td class=cp valign=top width=7 height=12>
            <img height=12 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=cp valign=top width=34 height=12>
                <span class="campo">
                    {{ $boleto->processed['especie_moeda'] }}
                </span>
        </td>
        <td class=cp valign=top width=7 height=12>
            <img height=12 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=cp valign=top width=53 height=12>
                <span class="campo">
                    {{ $boleto->processed["qtde_moeda"] }}
                </span>
        </td>
        <td class=cp valign=top width=7 height=12>
            <img height=12 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=cp valign=top align=right width=120 height=12>
                <span class="campo">
                    {{ $boleto->processed["nosso_numero"] }}
                </span>
        </td>
    </tr>
    <tr>
        <td valign=top width=7 height=1>
            <img height=1 src="{{ asset('imagens/2.png') }}" width=7 border=0>
        </td>
        <td valign=top width=268 height=1>
            <img height=1 src="{{ asset('imagens/2.png')}}" width=268 border=0>
        </td>
        <td valign=top width=7 height=1>
            <img height=1 src="{{ asset('imagens/2.png')}}" width=7 border=0>
        </td>
        <td valign=top width=156 height=1>
            <img height=1 src="{{ asset('imagens/2.png') }}" width=156 border=0>
        </td>
        <td valign=top width=7 height=1>
            <img height=1 src="{{ asset('imagens/2.png') }}" width=7 border=0>
        </td>
        <td valign=top width=34 height=1>
            <img height=1 src="{{ asset('imagens/2.png') }}" width=34 border=0>
        </td>
        <td valign=top width=7 height=1>
            <img height=1 src="{{ asset('imagens/2.png') }}" width=7 border=0>
        </td>
        <td valign=top width=53 height=1>
            <img height=1 src="{{ asset('imagens/2.png') }}" width=53 border=0>
        </td>
        <td valign=top width=7 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=7 border=0>
        </td>
        <td valign=top width=120 height=1>
            <img height=1 src="{{ asset('imagens/2.png') }}" width=120 border=0>
        </td>
    </tr>
    </tbody>
</table>
<table cellspacing=0 cellpadding=0 border=0>
    <tbody>
    <tr>
        <td class=ct valign=top width=7 height=13>
            <img height=13 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=ct valign=top colspan=3 height=13>
            Número do documento
        </td>
        <td class=ct valign=top width=7 height=13>
            <img height=13 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=ct valign=top width=132 height=13>
            CPF/CNPJ
        </td>
        <td class=ct valign=top width=7 height=13>
            <img height=13 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=ct valign=top width=134 height=13>
            Vencimento
        </td>
        <td class=ct valign=top width=7 height=13>
            <img height=13 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=ct valign=top width=180 height=13>
            Valor documento
        </td>
    </tr>
    <tr>
        <td class=cp valign=top width=7 height=12>
            <img height=12 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=cp valign=top colspan=3 height=12>
                <span class="campo">
                    {{ $boleto->processed["nr_do_documento"] }}
                </span>
        </td>
        <td class=cp valign=top width=7 height=12>
            <img height=12 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=cp valign=top width=132 height=12>
                <span class="campo">
                    {{ $boleto->processed['beneficiario']["cpf_cnpj"] }}
                </span>
        </td>
        <td class=cp valign=top width=7 height=12>
            <img height=12 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=cp valign=top width=134 height=12>
                <span class="campo">
                    @if( array_has($boleto->processed,'vencimento') )
                        {{ $boleto->processed['vencimento'] }}
                    @else
                        "Contra Apresentação"
                    @endif
                </span>
        </td>
        <td class=cp valign=top width=7 height=12>
            <img height=12 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=cp valign=top align=right width=180 height=12>
                <span class="campo">
                    {{  $boleto->processed['valor_documento']}}
                </span>
        </td>
    </tr>
    <tr>
        <td valign=top width=7 height=1>
            <img height=1 src="{{ asset('imagens/2.png') }}" width=7 border=0></td>
        <td valign=top width=113 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=113 border=0></td>
        <td valign=top width=7 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=7 border=0></td>
        <td valign=top width=72 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=72 border=0></td>
        <td valign=top width=7 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=7 border=0></td>
        <td valign=top width=132 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=132 border=0></td>
        <td valign=top width=7 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=7 border=0></td>
        <td valign=top width=134 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=134 border=0></td>
        <td valign=top width=7 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=7 border=0></td>
        <td valign=top width=180 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=180 border=0></td>
    </tr>
    </tbody>
</table>
<table cellspacing=0 cellpadding=0 border=0>
    <tbody>
    <tr>
        <td class=ct valign=top width=7 height=13><img height=13 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=ct valign=top width=113 height=13>(-)
            Desconto / Abatimentos
        </td>
        <td class=ct valign=top width=7 height=13><img height=13 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=ct valign=top width=112 height=13>(-)
            Outras deduções
        </td>
        <td class=ct valign=top width=7 height=13><img height=13 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=ct valign=top width=113 height=13>(+)
            Mora / Multa
        </td>
        <td class=ct valign=top width=7 height=13><img height=13 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=ct valign=top width=113 height=13>(+)
            Outros acréscimos
        </td>
        <td class=ct valign=top width=7 height=13><img height=13 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=ct valign=top width=180 height=13>(=)
            Valor cobrado
        </td>
    </tr>
    <tr>
        <td class=cp valign=top width=7 height=12><img height=12 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=cp valign=top align=right width=113 height=12></td>
        <td class=cp valign=top width=7 height=12><img height=12 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=cp valign=top align=right width=112 height=12></td>
        <td class=cp valign=top width=7 height=12><img height=12 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=cp valign=top align=right width=113 height=12></td>
        <td class=cp valign=top width=7 height=12><img height=12 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=cp valign=top align=right width=113 height=12></td>
        <td class=cp valign=top width=7 height=12><img height=12 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=cp valign=top align=right width=180 height=12></td>
    </tr>
    <tr>
        <td valign=top width=7 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=7 border=0></td>
        <td valign=top width=113 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=113 border=0></td>
        <td valign=top width=7 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=7 border=0></td>
        <td valign=top width=112 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=112 border=0></td>
        <td valign=top width=7 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=7 border=0></td>
        <td valign=top width=113 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=113 border=0></td>
        <td valign=top width=7 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=7 border=0></td>
        <td valign=top width=113 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=113 border=0></td>
        <td valign=top width=7 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=7 border=0></td>
        <td valign=top width=180 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=180 border=0></td>
    </tr>
    </tbody>
</table>
<table cellspacing=0 cellpadding=0 border=0>
    <tbody>
    <tr>
        <td class=ct valign=top width=7 height=13><img height=13 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=ct valign=top width=659 height=13>Sacado</td>
    </tr>
    <tr>
        <td class=cp valign=top width=7 height=12><img height=12 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=cp valign=top width=659 height=12>
      <span class="campo">
      {{ $boleto->processed['pagador']['nome'] }}
      </span></td>
    </tr>
    <tr>
        <td valign=top width=7 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=7 border=0></td>
        <td valign=top width=659 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=659 border=0></td>
    </tr>
    </tbody>
</table>
<table cellspacing=0 cellpadding=0 border=0>
    <tbody>
    <tr>
        <td class=ct width=7 height=12></td>
        <td class=ct width=564>Demonstrativo</td>
        <td class=ct width=7 height=12></td>
        <td class=ct width=88>Autenticação mecânica
        </td>
    </tr>
    <tr>
        <td width=7></td>
        <td class=cp width=564>
    <span class="campo">
        @foreach($boleto->demonstrativo_array as $demonstrativo)
            {!! $demonstrativo !!}<br>
        @endforeach
      </span>
        </td>
        <td width=7></td>
        <td width=88></td>
    </tr>
    </tbody>
</table>
<table cellspacing=0 cellpadding=0 width=666 border=0>
    <tbody>
    <tr>
        <td width=7></td>
        <td width=500 class=cp>
            <br><br><br>
        </td>
        <td width=159></td>
    </tr>
    </tbody>
</table>
<table cellspacing=0 cellpadding=0 width=666 border=0>
    <tr>
        <td class=ct width=666></td>
    </tr>
    <tbody>
    <tr>
        <td class=ct width=666>
            <div align=right>Corte na linha pontilhada</div>
        </td>
    </tr>
    <tr>
        <td class=ct width=666><img height=1 src="{{ asset('imagens/6.png') }}" width=665 border=0></td>
    </tr>
    </tbody>
</table>
<br>
<table cellspacing=0 cellpadding=0 width=666 border=0>
    <tr>
        <td class=cp width=150>
          <span class="campo">
              <IMG src="{{ asset( 'imagens/logocaixa.jpg') }}" width="150" height="40" border=0>
          </span>
        </td>
        <td width=3 valign=bottom>
            <img height=22 src="{{ asset('imagens/3.png') }}" width=2 border=0>
        </td>
        <td class=cpt width=58 valign=bottom>
            <div align=center>
                <span class=bc>
                    {{ $boleto->processed['codigo_banco_compensacao'] }}
                </span>
            </div>
        </td>
        <td width=3 valign=bottom>
            <img height=22 src="{{ asset('imagens/3.png') }}" width=2 border=0>
        </td>
        <td class=ld align=right width=453 valign=bottom>
            <span class=ld>
                <span class="campotitulo">
                    {{  $boleto->processed['linha_digitavel'] }}
                </span>
            </span>
        </td>
    </tr>
    <tbody>
    <tr>
        <td colspan=5><img height=2 src="{{ asset('imagens/2.png') }}" width=666 border=0></td>
    </tr>
    </tbody>
</table>
<table cellspacing=0 cellpadding=0 border=0>
    <tbody>
    <tr>
        <td class=ct valign=top width=7 height=13><img height=13 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=ct valign=top width=472 height=13>Local de pagamento</td>
        <td class=ct valign=top width=7 height=13><img height=13 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=ct valign=top width=180 height=13>Vencimento</td>
    </tr>
    <tr>
        <td class=cp valign=top width=7 height=12><img height=12 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=cp valign=top width=472 height=12>{{ $boleto->processed['local_de_pagamento'] }}</td>
        <td class=cp valign=top width=7 height=12><img height=12 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=cp valign=top align=right width=180 height=12>
      <span class="campo">
       @if( array_has($boleto->processed,'vencimento') )
              {{ $boleto->processed['vencimento'] }}
          @else
              "Contra Apresentação"
          @endif
      </span></td>
    </tr>
    <tr>
        <td valign=top width=7 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=7 border=0></td>
        <td valign=top width=472 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=472 border=0></td>
        <td valign=top width=7 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=7 border=0></td>
        <td valign=top width=180 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=180 border=0></td>
    </tr>
    </tbody>
</table>
<table cellspacing=0 cellpadding=0 border=0>
    <tbody>
    <tr>
        <td class=ct valign=top width=7 height=13><img height=13 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=ct valign=top width=472 height=13>Cedente</td>
        <td class=ct valign=top width=7 height=13><img height=13 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=ct valign=top width=180 height=13>Agência/Código cedente</td>
    </tr>
    <tr>
        <td class=cp valign=top width=7 height=12><img height=12 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=cp valign=top width=472 height=12>
      <span class="campo">
        {{ $boleto->processed['beneficiario']['razao_social'] }}
      </span></td>
        <td class=cp valign=top width=7 height=12><img height=12 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=cp valign=top align=right width=180 height=12>
            <span class="campo">{{ $boleto->processed['beneficiario']['agencia'] }} </span></td>
    </tr>
    <tr>
        <td valign=top width=7 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=7 border=0></td>
        <td valign=top width=472 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=472 border=0></td>
        <td valign=top width=7 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=7 border=0></td>
        <td valign=top width=180 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=180 border=0></td>
    </tr>
    </tbody>
</table>
<table cellspacing=0 cellpadding=0 border=0>
    <tbody>
    <tr>
        <td class=ct valign=top width=7 height=13>
            <img height=13 src="{{ asset('imagens/1.png') }}" width=1 border=0></td>
        <td class=ct valign=top width=113 height=13>Data do documento
        </td>
        <td class=ct valign=top width=7 height=13><img height=13 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=ct valign=top width=133 height=13>N<span style="text-decoration: underline;">o</span> documento
        </td>
        <td class=ct valign=top width=7 height=13><img height=13 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=ct valign=top width=62 height=13>Espécie doc.
        </td>
        <td class=ct valign=top width=7 height=13><img height=13 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=ct valign=top width=34 height=13>Aceite</td>
        <td class=ct valign=top width=7 height=13>
            <img height=13 src="{{ asset('imagens/1.png') }}" width=1 border=0></td>
        <td class=ct valign=top width=102 height=13>Data processamento
        </td>
        <td class=ct valign=top width=7 height=13><img height=13 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=ct valign=top width=180 height=13>Nosso número
        </td>
    </tr>
    <tr>
        <td class=cp valign=top width=7 height=12><img height=12 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=cp valign=top width=113 height=12>
            <div align=left>
      <span class="campo">
        {{ $boleto->processed['data_do_documento'] }}
      </span></div>
        </td>
        <td class=cp valign=top width=7 height=12><img height=12 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=cp valign=top width=133 height=12>
        <span class="campo">
        {{ $boleto->processed['nr_do_documento'] }}
        </span></td>
        <td class=cp valign=top width=7 height=12><img height=12 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=cp valign=top width=62 height=12>
            <div align=left><span class="campo">
        {{ $boleto->processed['especie_doc'] }}
      </span>
            </div>
        </td>
        <td class=cp valign=top width=7 height=12><img height=12 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=cp valign=top width=34 height=12>
            <div align=left><span class="campo">
     {{ $boleto->processed['aceite'] }}
     </span>
            </div>
        </td>
        <td class=cp valign=top width=7 height=12><img height=12 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=cp valign=top width=102 height=12>
            <div align=left>
       <span class="campo">
       {{ $boleto->processed['data_do_processamento'] }}
       </span></div>
        </td>
        <td class=cp valign=top width=7 height=12><img height=12 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=cp valign=top align=right width=180 height=12>
         <span class="campo">
         {{ $boleto->processed['nosso_numero'] }}
         </span></td>
    </tr>
    <tr>
        <td valign=top width=7 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=7 border=0></td>
        <td valign=top width=113 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=113 border=0></td>
        <td valign=top width=7 height=1>
            <img height=1 src="{{ asset('imagens/2.png') }}" width=7 border=0></td>
        <td valign=top width=133 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=133 border=0></td>
        <td valign=top width=7 height=1>
            <img height=1 src="{{ asset('imagens/2.png') }}" width=7 border=0></td>
        <td valign=top width=62 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=62 border=0></td>
        <td valign=top width=7 height=1>
            <img height=1 src="{{ asset('imagens/2.png') }}" width=7 border=0></td>
        <td valign=top width=34 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=34 border=0></td>
        <td valign=top width=7 height=1>
            <img height=1 src="{{ asset('imagens/2.png') }}" width=7 border=0></td>
        <td valign=top width=102 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=102 border=0></td>
        <td valign=top width=7 height=1>
            <img height=1 src="{{ asset('imagens/2.png') }}" width=7 border=0></td>
        <td valign=top width=180 height=1>
            <img height=1 src="{{ asset('imagens/2.png') }}" width=180 border=0></td>
    </tr>
    </tbody>
</table>
<table cellspacing=0 cellpadding=0 border=0>
    <tbody>
    <tr>
        <td class=ct valign=top width=7 height=13><img height=13 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=ct valign=top COLSPAN="3" height=13>Uso
            do banco
        </td>
        <td class=ct valign=top height=13 width=7><img height=13 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=ct valign=top width=83 height=13>Carteira</td>
        <td class=ct valign=top height=13 width=7>
            <img height=13 src="{{ asset('imagens/1.png') }}" width=1 border=0></td>
        <td class=ct valign=top width=43 height=13>Espécie</td>
        <td class=ct valign=top height=13 width=7>
            <img height=13 src="{{ asset('imagens/1.png') }}" width=1 border=0></td>
        <td class=ct valign=top width=103 height=13>Quantidade</td>
        <td class=ct valign=top height=13 width=7>
            <img height=13 src="{{ asset('imagens/1.png') }}" width=1 border=0></td>
        <td class=ct valign=top width=102 height=13>
            Valor Documento
        </td>
        <td class=ct valign=top width=7 height=13><img height=13 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=ct valign=top width=180 height=13>(=)
            Valor documento
        </td>
    </tr>
    <tr>
        <td class=cp valign=top width=7 height=12><img height=12 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td valign=top class=cp height=12 COLSPAN="3">
            <div align=left>
            </div>
        </td>
        <td class=cp valign=top width=7 height=12><img height=12 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=cp valign=top width=83>
            <div align=left> <span class="campo">
      {{ $boleto->processed['carteira'] }}
    </span></div>
        </td>
        <td class=cp valign=top width=7 height=12><img height=12 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=cp valign=top width=43>
            <div align=left><span class="campo">
    {{ $boleto->processed['especie_moeda'] }}
    </span>
            </div>
        </td>
        <td class=cp valign=top width=7 height=12><img height=12 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=cp valign=top width=103><span class="campo">
     {{ $boleto->processed['qtde_moeda'] }}
     </span>
        </td>
        <td class=cp valign=top width=7 height=12><img height=12 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=cp valign=top width=102>
       <span class="campo">
       {{ $boleto->processed['xValor'] }}
       </span></td>
        <td class=cp valign=top width=7 height=12><img height=12 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=cp valign=top align=right width=180 height=12>
       <span class="campo">
       {{  $boleto->processed['valor_documento']}}
       </span></td>
    </tr>
    <tr>
        <td valign=top width=7 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=7 border=0></td>
        <td valign=top width=7 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=75 border=0></td>
        <td valign=top width=7 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=7 border=0></td>
        <td valign=top width=31 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=31 border=0></td>
        <td valign=top width=7 height=1>
            <img height=1 src="{{ asset('imagens/2.png') }}" width=7 border=0></td>
        <td valign=top width=83 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=83 border=0></td>
        <td valign=top width=7 height=1>
            <img height=1 src="{{ asset('imagens/2.png') }}" width=7 border=0></td>
        <td valign=top width=43 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=43 border=0></td>
        <td valign=top width=7 height=1>
            <img height=1 src="{{ asset('imagens/2.png') }}" width=7 border=0></td>
        <td valign=top width=103 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=103 border=0></td>
        <td valign=top width=7 height=1>
            <img height=1 src="{{ asset('imagens/2.png') }}" width=7 border=0></td>
        <td valign=top width=102 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=102 border=0></td>
        <td valign=top width=7 height=1>
            <img height=1 src="{{ asset('imagens/2.png') }}" width=7 border=0></td>
        <td valign=top width=180 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=180 border=0></td>
    </tr>
    </tbody>
</table>
<table cellspacing=0 cellpadding=0 width=666 border=0>
    <tbody>
    <tr>
        <td align=right width=10>
            <table cellspacing=0 cellpadding=0 border=0 align=left>
                <tbody>
                <tr>
                    <td class=ct valign=top width=7 height=13><img height=13 src="{{ asset('imagens/1.png') }}" width=1
                                                                   border=0></td>
                </tr>
                <tr>
                    <td class=cp valign=top width=7 height=12><img height=12 src="{{ asset('imagens/1.png') }}" width=1
                                                                   border=0></td>
                </tr>
                <tr>
                    <td valign=top width=7 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=1 border=0>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
        <td valign=top width=468 rowspan=5><span class=ct>
                Instruções (Texto de Responsabilidade do Beneficiário)
            </span><br><br>
            <span class=cp>
                <span class=campo>
                    @foreach($boleto->instrucoes_array as $instrucao)
                        {!! $instrucao !!}<br>
                    @endforeach
                </span><br><br>
            </span>
        </td>
        <td align=right width=188>
            <table cellspacing=0 cellpadding=0 border=0>
                <tbody>
                <tr>
                    <td class=ct valign=top width=7 height=13>
                        <img height=13 src="{{ asset('imagens/1.png') }}" width=1 border=0></td>
                    <td class=ct valign=top width=180 height=13>(-)
                        Desconto / Abatimentos
                    </td>
                </tr>
                <tr>
                    <td class=cp valign=top width=7 height=12>
                        <img height=12 src="{{ asset('imagens/1.png') }}" width=1 border=0></td>
                    <td class=cp valign=top align=right width=180 height=12></td>
                </tr>
                <tr>
                    <td valign=top width=7 height=1>
                        <img height=1 src="{{ asset('imagens/2.png') }}" width=7 border=0>
                    </td>
                    <td valign=top width=180 height=1>
                        <img height=1 src="{{ asset('imagens/2.png') }}" width=180 border=0>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td align=right width=10>
            <table cellspacing=0 cellpadding=0 border=0 align=left>
                <tbody>
                <tr>
                    <td class=ct valign=top width=7 height=13>
                        <img height=13 src="{{ asset('imagens/1.png') }}" width=1 border=0>
                    </td>
                </tr>
                <tr>
                    <td class=cp valign=top width=7 height=12>
                        <img height=12 src="{{ asset('imagens/1.png') }}" width=1 border=0>
                    </td>
                </tr>
                <tr>
                    <td valign=top width=7 height=1>
                        <img height=1 src="{{ asset('imagens/2.png') }}" width=1 border=0>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
        <td align=right width=188>
            <table cellspacing=0 cellpadding=0 border=0>
                <tbody>
                <tr>
                    <td class=ct valign=top width=7 height=13>
                        <img height=13 src="{{ asset('imagens/1.png') }}" width=1 border=0>
                    </td>
                    <td class=ct valign=top width=180 height=13>(-)
                        Outras deduções
                    </td>
                </tr>
                <tr>
                    <td class=cp valign=top width=7 height=12>
                        <img height=12 src="{{ asset('imagens/1.png') }}" width=1 border=0>
                    </td>
                    <td class=cp valign=top align=right width=180 height=12></td>
                </tr>
                <tr>
                    <td valign=top width=7 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=7 border=0>
                    </td>
                    <td valign=top width=180 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=180
                                                           border=0>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td align=right width=10>
            <table cellspacing=0 cellpadding=0 border=0 align=left>
                <tbody>
                <tr>
                    <td class=ct valign=top width=7 height=13>
                        <img height=13 src="{{ asset('imagens/1.png') }}" width=1 border=0>
                    </td>
                </tr>
                <tr>
                    <td class=cp valign=top width=7 height=12>
                        <img height=12 src="{{ asset('imagens/1.png') }}" width=1 border=0>
                    </td>
                </tr>
                <tr>
                    <td valign=top width=7 height=1>
                        <img height=1 src="{{ asset('imagens/2.png') }}" width=1 border=0>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
        <td align=right width=188>
            <table cellspacing=0 cellpadding=0 border=0>
                <tbody>
                <tr>
                    <td class=ct valign=top width=7 height=13><img height=13 src="{{ asset('imagens/1.png') }}" width=1
                                                                   border=0></td>
                    <td class=ct valign=top width=180 height=13>(+)
                        Mora / Multa
                    </td>
                </tr>
                <tr>
                    <td class=cp valign=top width=7 height=12><img height=12 src="{{ asset('imagens/1.png') }}" width=1
                                                                   border=0></td>
                    <td class=cp valign=top align=right width=180 height=12></td>
                </tr>
                <tr>
                    <td valign=top width=7 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=7 border=0>
                    </td>
                    <td valign=top width=180 height=1>
                        <img height=1 src="{{ asset('imagens/2.png') }}" width=180 border=0></td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td align=right width=10>
            <table cellspacing=0 cellpadding=0 border=0 align=left>
                <tbody>
                <tr>
                    <td class=ct valign=top width=7 height=13><img height=13 src="{{ asset('imagens/1.png') }}" width=1
                                                                   border=0></td>
                </tr>
                <tr>
                    <td class=cp valign=top width=7 height=12><img height=12 src="{{ asset('imagens/1.png') }}" width=1
                                                                   border=0></td>
                </tr>
                <tr>
                    <td valign=top width=7 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=1 border=0>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
        <td align=right width=188>
            <table cellspacing=0 cellpadding=0 border=0>
                <tbody>
                <tr>
                    <td class=ct valign=top width=7 height=13><img height=13 src="{{ asset('imagens/1.png') }}" width=1
                                                                   border=0></td>
                    <td class=ct valign=top width=180 height=13>(+)
                        Outros acréscimos
                    </td>
                </tr>
                <tr>
                    <td class=cp valign=top width=7 height=12><img height=12 src="{{ asset('imagens/1.png') }}" width=1
                                                                   border=0></td>
                    <td class=cp valign=top align=right width=180 height=12></td>
                </tr>
                <tr>
                    <td valign=top width=7 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=7 border=0>
                    </td>
                    <td valign=top width=180 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=180
                                                           border=0>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td align=right width=10>
            <table cellspacing=0 cellpadding=0 border=0 align=left>
                <tbody>
                <tr>
                    <td class=ct valign=top width=7 height=13><img height=13 src="{{ asset('imagens/1.png') }}" width=1
                                                                   border=0></td>
                </tr>
                <tr>
                    <td class=cp valign=top width=7 height=12><img height=12 src="{{ asset('imagens/1.png') }}" width=1
                                                                   border=0></td>
                </tr>
                </tbody>
            </table>
        </td>
        <td align=right width=188>
            <table cellspacing=0 cellpadding=0 border=0>
                <tbody>
                <tr>
                    <td class=ct valign=top width=7 height=13><img height=13 src="{{ asset('imagens/1.png') }}" width=1
                                                                   border=0></td>
                    <td class=ct valign=top width=180 height=13>(=)
                        Valor cobrado
                    </td>
                </tr>
                <tr>
                    <td class=cp valign=top width=7 height=12><img height=12 src="{{ asset('imagens/1.png') }}" width=1
                                                                   border=0></td>
                    <td class=cp valign=top align=right width=180 height=12></td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
<table cellspacing=0 cellpadding=0 width=666 border=0>
    <tbody>
    <tr>
        <td valign=top width=666 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=666 border=0></td>
    </tr>
    </tbody>
</table>
<table cellspacing=0 cellpadding=0 border=0>
    <tbody>
    <tr>
        <td class=ct valign=top width=7 height=13><img height=13 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=ct valign=top width=659 height=13>Sacado</td>
    </tr>
    <tr>
        <td class=cp valign=top width=7 height=12><img height=12 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=cp valign=top width=659 height=12><span class="campo">
    {{ $boleto->processed['pagador']['nome'] }}
    </span>
        </td>
    </tr>
    </tbody>
</table>
<table cellspacing=0 cellpadding=0 border=0>
    <tbody>
    <tr>
        <td class=cp valign=top width=7 height=12><img height=12 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=cp valign=top width=659 height=12><span class="campo">
    {{ $boleto->processed['pagador']["endereco"] }}
    </span>
        </td>
    </tr>
    </tbody>
</table>
<table cellspacing=0 cellpadding=0 border=0>
    <tbody>
    <tr>
        <td class=ct valign=top width=7 height=13><img height=13 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=cp valign=top width=472 height=13>
      <span class="campo">
      {{ $boleto->processed['pagador']['cidade_estado_cep'] }}
      </span></td>
        <td class=ct valign=top width=7 height=13><img height=13 src="{{ asset('imagens/1.png') }}" width=1 border=0>
        </td>
        <td class=ct valign=top width=180 height=13>Cód.baixa
        </td>
    </tr>
    <tr>
        <td valign=top width=7 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=7 border=0></td>
        <td valign=top width=472 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=472 border=0></td>
        <td valign=top width=7 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=7 border=0></td>
        <td valign=top width=180 height=1><img height=1 src="{{ asset('imagens/2.png') }}" width=180 border=0></td>
    </tr>
    </tbody>
</table>
<table cellSpacing=0 cellPadding=0 border=0 width=666>
    <tbody>
    <tr>
        <td class=ct width=7 height=12></td>
        <td class=ct width=409>Sacador/Avalista</td>
        <td class=ct width=250>
            <div align=right>Autenticação mecânica - <b class=cp>Ficha de Compensação</b></div>
        </td>
    </tr>
    <tr>
        <td class=ct colspan=3></td>
    </tr>
    </tbody>
</table>
<table cellSpacing=0 cellPadding=0 width=666 border=0 style="padding-left: 5px">
    <tbody>
        <tr>
            <td valign="bottom" align="left" height="50">
                @foreach($boleto->bars as $bar)
                    @if($bar['drawBar'] == TRUE)
                        <img src="{{ asset('p.gif') }}" width="{{$bar['width'] }}" height="50" style="border:0;margin:0;float:left;">
                    @elseif($bar['drawSpacing'] == TRUE)
                        <img src="{{ asset('b.gif') }}" width="{{ $bar['width'] }}" height="50" style="border:0;margin:0;float:left;">
                    @endif
                @endforeach
            </td>
        </tr>
    </tbody>
</table>
<table cellSpacing=0 cellPadding=0 width=666 border=0>
    <tr>
        <td class=ct width=666></td>
    </tr>
    <tbody>
    <tr>
        <td class=ct width=666>
            <div align=right>Corte na linha pontilhada
            </div>
        </td>
    </tr>
    <tr>
        <td class=ct width=666><img height=1 src="{{ asset('imagens/6.png') }}" width=665 border=0></td>
    </tr>
    </tbody>
</table>
</body>

</html>
