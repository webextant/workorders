<?php
/*
	Author: Raymond Brady (@thewizster)
	Created: 1449360337 GMT: Sun, 06 Dec 2015 00:05:37
	Description: Processes POST data. Verifies against an XML Schema.
	Required HTTP POST values:
		form-name: A friendly name for the form to process.
		form-description: General description of the form.
		form-xml-schema: XML schema definition of the form submitting the data. Based on XML schema generated using: https://github.com/kevinchappell/formBuilder
*/
	class Pacman {
		// Internal State
		protected $postData;
		protected $sterileData;
		protected $xmlData;
		protected $xmlValid = false;
		protected $inputIsValid = false;

		// Public properties
        public $formId;
		public $formName = "";
		public $formDescription = "";
		
		function __construct ($p){
			$this->postData = $p;
			$this->SanitizeStringValues();
			$this->ParseFormXML();
		}
		
		protected function SanitizeStringValues()
		{
			foreach($this->postData as $p => $p_value) {
				if ($p == "form-xml-schema") {
					$this->xmlData = $p_value;
				} elseif ($p == "form-name") {
					$this->formName = filter_var(trim($p_value), FILTER_SANITIZE_STRING);
				} elseif ($p == "form-description") {
					$this->formDescription = filter_var(trim($p_value), FILTER_SANITIZE_STRING);
				} elseif ($p == "form-id") {
					$this->formId = filter_var(trim($p_value), FILTER_SANITIZE_NUMBER_INT);
				} else {
					$this->sterileData[$p] = filter_var(trim($p_value), FILTER_SANITIZE_STRING);					
				}
			}
		}
		
		protected function ParseFormXML()
		{
			try {
				$this->xmlData = new SimpleXMLElement($this->xmlData);
				$this->xmlValid = true;            
			} catch (Exception $e) {
				$this->xmlValid = False;
			}
			return $this->xmlValid;
		}
		
		function asJSON()
		{
			return json_encode($this->sterileData);
		}
		
		function asFormXML()
		{
			if ($this->xmlValid){
				return $this->xmlData->asXML();
			} else {
				return "";
			}
		}
		
		function InputIsValid()
		{
			if ($this->xmlValid) {
				foreach ($this->xmlData->children()->children() as $item) {
					// Look through each field and test empty fields for required based on XML Schema.
					if(empty($this->sterileData[trim($item['name'])])){
						if ($item['required'] == "true"){
							return false;
						}
					};						
				}
				return true;
			} else {
				return false;
			}
		}
	}
?>