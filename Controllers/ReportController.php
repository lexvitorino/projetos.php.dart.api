<?php

namespace Controllers;

use \Core\Controller;
use \Models\Pedidos;
use \Mpdf\Mpdf;
use Mpdf\HTMLParserMode;

class ReportController extends Controller
{
    private function getHeader()
    {
        $img = BASE_URL . 'media/reports/logo.png';
        return '<header class="clearfix">
                    <div id="logo">
                        <img src="' . $img . '" width="48" height="48">
                    </div>
                    <div id="company">
                        <h2 class="name">' . EMPRESA . '</h2>
                        <div>' . EMPRESA_ENDERECO . '</div>
                        <div>' . EMPRESA_TEL . '</div>
                        <div>' . EMPRESA_EMAIL . '</div>
                    </div>
                </header>';
    }

    private function getPedido($pedidos)
    {
        $pedido = $pedidos->getResult();
        return '<div id="details" class="clearfix">
                    <div id="client">
                        <h2 class="name">' . $pedido['cliente'] . '</h2>
                        <div class="email">' . (empty($pedido['email']) ? '' : $pedido['email']) . '</div>
                    </div>
                    <div id="client">
                        <div class="to">Endereço para entrega:</div>
                        <div class="address">' . $pedidos->getEndFat() . '</div>
                    </div>
                    <div id="client">
                        <div class="to">Endereço para faturamento:</div>
                        <div class="address">' . $pedidos->getEndent() . '</div>
                    </div>                    
                    <div id="invoice">
                        <h1> Pedido nº #' . $pedido['id'] . '</h1>
                        <div class="date">Data: ' . date("d/m/Y", strtotime($pedido['data'])) . '</div>
                        <div class="date">Entrega: ' . $pedido['prazoEntrega'] . '</div>
                        <div class="date">Montagem: ' . $pedido['prazoColocacao'] . '</div>
                    </div>
                </div>';
    }

    public function getItens($pedidos)
    {
        $pedido = $pedidos->getResult();
        $html = '<table border="0" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <th class="no">#</th>
                            <th class="qty">Qtde</th>
                            <th class="desc">Ambiente</th>
                            <th class="desc">Descrição</th>
                            <th class="unit">Unitário</th>
                            <th class="total">Total</th>
                        </tr>
                    </thead>
                    <tbody>';

        $i = 1;            
        foreach ($pedido['pedItens'] as $item) {
            $html .= '      <tr>
                            <td class="no">' . $i++ . '</td>
                            <td class="qty">' . $item['qtde'] . '</td>
                            <td class="desc">' . $item['ambiente'] . '</td>
                            <td class="desc">' . $item['produto'] . '</td>
                            <td class="unit">' . $item['valorUnitario'] . '</td>
                            <td class="total">' . $item['valorTotal'] . '</td>
                        </tr>';
        }

        $html .= '  </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4"></td>
                            <td class="border-td-top">Desconto</td>
                            <td class="border-td-top">' . $pedido['descontoTotal'] . '</td>
                        </tr>
                        <tr>
                            <td colspan="4"></td>
                            <td class="border-td-top">Total geral</td>
                            <td class="border-td-top">' . $pedido['valorTotal'] . '</td>
                        </tr>
                    </tfoot>
                </table>';
        return $html;
    }

    public function getPedConds($pedidos)
    {
        $pedido = $pedidos->getResult();
        $html = '<div class="notices">
                    <div class="notice">Condição de Pagamento</div>
                </div>
                 <table border="0" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <th class="no">#</th>
                            <th class="td">Tipo Documento</th>
                            <th class="data">Vencimento</th>
                            <th class="td">Nº Cheque</th>
                            <th class="td">Banco</th>
                            <th class="td">Agência</th>
                            <th class="total">Valor</th>
                        </tr>
                    </thead>
                    <tbody>';

        $i = 1;            
        foreach ($pedido['pedCondsPagto'] as $item) {
            $html .= '      <tr>
                            <td class="no">' . $i++ . '</td>
                            <td class="td">' . $item['tipoDocto'] . '</td>
                            <td class="data">' . date("d/m/Y", strtotime($item['dataVencimento'])) . '</td>
                            <td class="td">' . $item['numCheque'] . '</td>
                            <td class="td">' . $item['banco'] . '</td>
                            <td class="td">' . $item['agencia'] . '</td>
                            <td class="total">' . $item['valor'] . '</td>
                        </tr>';
        }

        $html .= '  </tbody>
                </table>';
        return $html;
    }

    public function getAdiantamento($pedidos)
    {

        if (empty($pedido['assAdiant'])) {
            return;
        }

        $pedido = $pedidos->getResult();
        $tipoAdiant = $pedido['tipoAdiant'];
        if ($tipoAdiant == 'C') {
            $tipoAdiant = 'Cheque';
        } else {
            $tipoAdiant = 'Dinheiro';
        }

        $html = '<div class="notices">
                    <div class="notice">Recibo de Sinal</div>
                    <div class="aviso">Recebemos por conta deste pedido a importância a titulo de sinal e principio de pagamento</div>
                </div>
                <table border="0" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <th class="td">Tipo</th>
                            <th class="td">Banco</th>
                            <th class="td">Agência</th>
                            <th class="total">Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="td">' . $tipoAdiant . '</td>
                            <td class="td">' . $pedido['bancoAdiant'] . '</td>
                            <td class="td">' . $pedido['agenciaAdiant'] . '</td>
                            <td class="total">' . $pedido['valorAdiant'] . '</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4"><img src="' . $pedido['assAdiant'] . ' width="80" height="80""></td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td class="ass">Assinatura do recebedor</td>
                        </tr>
                    </tfoot>                    
                </table>';
        return $html;
    }

    public function getFooter($pedidos)
    {
        $pedido = $pedidos->getResult();

        $html = '<footer>
                    <table border="0" cellspacing="0" cellpadding="0">
                        <thead>
                            <tr>
                                <th><img style="text-align: center" src="' . $pedido['assinatura'] . ' width="150" height="150""></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Assinatura do Cliente</td>
                            </tr>
                        </tbody>
                    </table>
                </footer>';
        return $html;
    }

    public function index()
    {

    }

    public function view($id)
    {
        $pedidos = new Pedidos(0);

        $pedidos->getById($id);
        if (empty($pedidos->getResult())) {
            echo 'Não existem dados para esse pedido';
        }

        $html = '';
        $html .= '<body>
                    ' . $this->getHeader() . '
                    <main>
                        ' . $this->getPedido($pedidos) . '
                        <div class="notices">
                            <div class="notice">Produtos</div>
                        </div>
                        ' . $this->getItens($pedidos) . '
                        ' . ( empty($pedidos->getResult()['pedCondsPagto']) ? '' : $this->getPedConds($pedidos)) . '
                        ' . $this->getAdiantamento($pedidos) . '
                    </main>
                    ' . $this->getFooter($pedidos) . '
                </body>';

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P'
        ]);

        $css = BASE_URL . 'media/reports/report.css';
        $stylesheet = file_get_contents($css);

        $mpdf->WriteHTML($stylesheet, HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($html, HTMLParserMode::HTML_BODY);
        $mpdf->Output("pedido.pdf", "I");
    }
}
