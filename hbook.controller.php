<?php
/**
* @class  hbookController
* @author CRA (developers@developers.com)
* Controller class of hbook module
**/

class hbookController extends hbook {

	function init() {
	}

	/**
	* @brief BOOK 입력
	**/
	function procHbookContentWrite() {
		// request 값을 모두 받음
		$obj = Context::getRequestVars();
		// 현재 모듈번호 확인
		$obj->module_srl = Context::get('module_srl');
		//trade_srl 확인
		$trade_srl = Context::get('trade_srl');

		// trade_srl에 따라 새로 입력하거나 수정하기 위해
		if($trade_srl) {
			// trade_srl이 있으면 update
			$output = executeQuery("hbook.updateBook", $obj);
			$this->setMessage('success_updated');
		} else {
			// trade_srl이 없으면 insert
			$output = executeQuery("hbook.insertSellinfo", $obj);
			$output = executeQuery("hbook.insertBook", $obj);
			$this->setMessage('success_registed');
		}
	}

	/**
	* @brief BOOK 삭제
	**/
	function procHbookContentDelete() {
		// 삭제를 원하는 trade_srl 값을 받음
		$obj->trade_srl = Context::get('trade_srl');
		// 삭제 실행
		$output = executeQuery("hbook.deleteBook", $obj);
		$output2 = executeQuery("hbook.deleteOrder", $obj);

		if(!$output->toBool() && !$ouput2->toBool())
			return $output;
		$this->setMessage('success_deleted');
	}

	/* Failed function
	function procHbookContentSearch() {
		// searchtype & searchterm 값을 받음
		$obj = Context::getRequestVars();

		// search 쿼리 날리기
		$output = executeQuery("hbook.getHbookContentSearch", $obj);

		// template_file을 list.html로 지정
		$this->setTemplateFile('list');
	}
	*/

	/**
	*  @로그인 정보를 받아내고 거래요청 함수
	**/
	function procHbookOrderinfo() {
		// request 값을 모두 받음
		$obj = Context::getRequestVars();
			//ting값 T/F 에 따라 쿼리 날려주기
		if(!$obj->ting){
			$obj->ting = !$obj->ting;
			
			$output = executeQuery("hbook.orderBook", $obj);
			$output2 = executeQuery("hbook.tradeBook", $obj);
			
			if(!$output->toBool() && !$output2->toBool() ) 
				return $output;
		} else {
			$obj->ting = !$obj->ting;
			$obj->customer_nick = "undefined";
			$output = executeQuery("hbook.orderBook", $obj);
			$output2 = executeQuery("hbook.tradeBook", $obj);

			if(!$output->toBool() && !$output2->toBool() ) 
				return $output;
		}
		//메시지 어딧는겨..$this->setMessage('success_ordered');
	}
}
?>
