 <?php
// created by Alexander Quiroz at 20190819 00:32.
// Email: Alexanderquiroz@info-mat.com.
// Web: info-mat.com.


/**modo del script */
$mode = "test";

/**valores a recibir del comercio */
$private_key = "key_azul";
$merchant_name = 'Nombre del Comercio';
$merchant_id = "ID del comercio";
$total_impuestos = "0";
$notify_url = 'url_notify';
$CancelUrl = 'url_cancel';
$decline_url = 'url_declined';
$merchantType = 'ecommerce';
$currencyCode = '$';
$Totalventa = '0.00';

/**modo de ejecucion */
if ($mode == 'test') {
    $azul_url_post = 'https://pruebas.azul.com.do/PaymentPage/default.aspx';
} elseif ($mode == 'live') {
    $azul_url_post = 'https://pagos.azul.com.do/PaymentPage/default.aspx';

}

/** calculo de impuestos*/
$impuestos = floatval(str_replace(",", "", $Totalventa)) * floatval($total_impuestos);
$impuestos = number_format((float) $impuestos, 2, '.', '');
$impuestos = str_replace('.', '', $impuestos);

/**calculo y desglose de monto */
$Totalventa = str_replace('.', '', $Totalventa);
$Totalventa = str_replace(',', '', $Totalventa);

/**Parametros de envio en el formulario */
$azul_params['MerchantId'] = $merchant_id;
$azul_params['MerchantName'] = $merchant_name;
$azul_params['MerchantType'] = $merchantType;
$azul_params['CurrencyCode'] = $currencyCode;
$azul_params['OrderNumber'] = $order_id;
$azul_params['Amount'] = $totalAzulGet;
$azul_params['ITBIS'] = $impuestos;
$azul_params['ApprovedUrl'] = $notify_url;
$azul_params['DeclinedUrl'] = $decline_url;
$azul_params['CancelUrl'] = $CancelUrl;
$azul_params['ResponsePostUrl'] = $notify_url;
$azul_params['UseCustomField1'] = '0';
$azul_params['CustomField1Label'] = 'Custom1';
$azul_params['CustomField1Value'] = 'Value1';
$azul_params['UseCustomField2'] = '0';
$azul_params['CustomField2Label'] = 'Custom2';
$azul_params['CustomField2Value'] = 'Value2';

$azul_values = "";
foreach ($azul_params as $key => $value) {
    $azul_values .= $value;
}
$azul_values .= $private_key;

$azul_params['ShowTransactionResult'] = 0;
//Adding to the form params the AuthHash
$azul_params['AuthHash'] = AuthHashCrypt($azul_values);

$azul_params_array = array();
foreach ($azul_params as $key => $value) {
    $azul_params_array[] = '<input type="hidden" name="' . $key . '" value="' . $value . '" />';
}

echo '<form action="' . $azul_url_post . '" method="post" id="azul_payment_form">
					' . implode('', $azul_params_array) . '
					<input type="submit" class="btn btn-success procesarbtn" id="btnPaymentPost" value="Pagar Ahora" />
				</form>';

function AuthHashCrypt($result)
{
    $result = mb_convert_encoding($result, 'UTF-8', 'ASCII');
    $result = hash_hmac('sha512', $result, 'key_azul');
    return $result;
}
