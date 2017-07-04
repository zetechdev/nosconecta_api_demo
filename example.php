<?php
function peticion($url, $requestMethod = 'GET', $requestBody = null, $requestHeader = null) {
	$ch = curl_init();
	$url = 'https://api.nosconecta.com.ar/' . $url;
	curl_setopt($ch, CURLOPT_URL, $url);

	if ($requestHeader !== null) {
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeader);
	}

	if ($requestMethod === 'POST') {
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $requestBody);
	} else if ($requestMethod === 'PUT' || $requestMethod === 'DELETE') {
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $requestMethod);
	    if($requestBody) {
	        $bodyQuery = http_build_query($requestBody);
	        curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge($requestHeader,array('Content-Length: ' . strlen($bodyQuery))));
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $bodyQuery);
	    }
	}

	curl_setopt($ch, CURLOPT_TIMEOUT_MS, 4000); // Tiempo de espera máxima (en milisegundos).

	$response = curl_exec($ch);
	$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);

	return array('response' => $response,'status' => $http_status);
}

// Está activo?
function activo() {
	return peticion('saludo');
}

// Autenticar
function ejemploAuth($nombreDeUsuario,$password,$ip) {
	return peticion('auth','GET',null,array("user: {$nombreDeUsuario}","password: {$password}","ip: {$ip}"));
}

// Ejemplo, busqueda
function ejemploSearch($idaplicacion,$nombreCampo,$valorBuscar,$jwt) {
	return peticion("search/{$idaplicacion}?bypage=5&filter=[{\"key\":\"{$nombreCampo}\",\"value\":\"{$valorBuscar}\",\"operator\": \"=\"}]",'GET',null,array("Authorization: Bearer {$jwt}"));
}

// Ejemplo, busqueda por multiples campos
function ejemploSearchMulti($idaplicacion,$nombreCampo1,$valorBuscar1,$nombreCampo2,$valorBuscar2,$jwt) {
	return peticion("search/{$idaplicacion}?bypage=5&filter=[{\"key\":\"{$nombreCampo1}\",\"value\":\"{$valorBuscar1}\",\"operator\": \"=\"},{\"key\":\"{$nombreCampo2}\",\"value\":\"{$valorBuscar2}\",\"operator\": \"=\"}]",'GET',null,array("Authorization: Bearer {$jwt}"));
}

// Ejemplo, obtener metadata de un documento
function ejemploMetadata($idaplicacion,$iddocumento,$jwt) {
	return peticion("metadata/{$idaplicacion}/{$iddocumento}",'GET',null,array("Authorization: Bearer {$jwt}"));
}