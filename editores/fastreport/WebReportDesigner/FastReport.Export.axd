<?php




if( $_GET["getConnectionTypes"] == "report9cjm3qox8xx") {

	header('Content-Type: application/json; charset=utf-8');


	$data =  '{
		"FastReport.Data.XmlDataConnection": "XML database",
		"FastReport.Data.CsvDataConnection": "CSV database",
		"FastReport.Data.JsonConnection.JsonDataSourceConnection": "JSON (JavaScript Object Notation)",
		"FastReport.Data.MsAccessDataConnection": "MS Access connection",
		"FastReport.Data.OleDbDataConnection": "OLE DB connection",
		"FastReport.Data.OdbcDataConnection": "ODBC connection",
		"FastReport.Data.MsSqlDataConnection": "MS SQL connection"
	}';

	echo json_encode($data);

}



?>
