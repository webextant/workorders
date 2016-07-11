<?php
$element = "Domains";
$element_function = "Updated";
//Define Variables for the form

	
$limit_domains =$_POST['limit_domains'];//$conn->real_escape_string($_POST['limit_domains']);
$explode_domains = explode(",",$limit_domains);
$domain_list = '';
foreach($explode_domains as $domain_val){
	if (strpos($domain_val, '@') !== false) {

		$domain_list .= '{domain='.$domain_val.'}';	
	}
}

//echo $domain_list;

$QUERY_PROCESS = $appInfoDbAdapter->Set("RegDomain", $domain_list);


?>
