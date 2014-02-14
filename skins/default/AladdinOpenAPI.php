<?php
include('HTTPRequest.php');
include('AladdinXMLParser.php');

$url = 'http://www.aladdin.co.kr/ttb/api/ItemSearch.aspx'; //상품 list url
$url2 = 'http://www.aladin.co.kr/ttb/api/ItemLookUp.aspx'; //상품 api(개별출력) url

$searchTitle = $GLOBALS['title'];
$searchTitle = iconv('euc-kr', 'utf-8', $searchTitle);

debugPrint($searchTitle);

$querySet = array( 
	'ttbkey' => 'ttbhanbyul12131059001', //박한별 고유 알라딘TTBkey number
	'SearchTarget' => 'Book',
	'MaxResults' => '20',
	'start' => '1',
	'Query' => $searchTitle,
	'output' => 'xml',
	'QueryType' => 'Title',
	'Version' => '20131101',
	'type' => '1'
); 

$querySet2 = array(
	'ttbkey' => 'ttbhanbyul12131059001',
	'ItemIdType' => 'ISBN',
	'Cover' => 'Big',
	'Output' => 'xml',
	'ItemId' => $searchISBN,
	'type' => '1'
);

//상품 list
$query = http_build_query($querySet); //$querySet을 query문으로 바꿈,
$url = $url.'?'.$query; //요청 url에 맞게 설정
$req = new HTTPRequest($url);

//상품 개별
/*$query = http_build_query($querySet2);
$url2 = $url2.'?'.$query;
$req = new HTTPRequest($url2);*/

$body = $req->DownloadToString();

$errMsg = '';
$isError = strpos($body, "<error xml");

if ($isError){
	ereg ("\<errorMessage\>(.*)\<\/errorMessage", $body, $regs);
	$errMsg = $regs[0];
}  else {

	$xmlParser = new AladdinXMLParser();
	$parser = @xml_parser_create();
	if (!is_resource($parser)) {
		return array("ErrMsg"=>"PHP XML parser에러");
	} else {
		// SAX 이벤트 처리 함수를 등록합니다.
		xml_set_object($parser, $xmlParser); // Use XML Parser within an object
		xml_set_element_handler($parser, "startHandler", "endHandler"); // Set up start and end element handlers
		xml_set_character_data_handler($parser, "cdataHandler"); // Set up character data handler
	}

	// SAX 엔진으로 XML 파싱을 합니다.
	$checkParsing = xml_parse($parser, $body, true);
	if(!$checkParsing) {
		return array("ErrMsg"=>sprintf("XML error: %s at line %d\n",
			xml_error_string(xml_get_error_code($parser)),
			xml_get_current_line_number($parser)));
	}

	if (is_resource($parser)) {
		xml_parser_free($parser);
		unset($parser);
	}
}
?> 
