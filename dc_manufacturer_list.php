<?php
session_start();

require_once __DIR__ . '/../../../beheer/includes/php/dc_session.php';

// Required includes
require_once __DIR__ . '/../../../includes/php/dc_connect.php';
require_once __DIR__ . '/../../../_classes/class.database.php';
$objDB = new DB();
require_once __DIR__ . '/../../includes/php/dc_config.php';

// Page specific includes
require_once __DIR__ . '/../../includes/php/dc_functions.php';

// Start API
require_once __DIR__ . '/../../../libraries/Api_Inktweb/API.class.php';

$_GET = sanitize($_GET);

$strShow = (isset($_GET['show'])) ? strtolower($_GET['show']) : null;
$strWhere = null;
$strSortColumn = (isset($_GET['sort_column'])) ? $_GET["sort_column"] : null;
$strSortOrder = (isset($_GET['sort_order'])) ? strtoupper($_GET["sort_order"]) : null;
$strQuery = (isset($_GET['query'])) ? $_GET["query"] : null;
$intOffset = (isset($_GET['offset'])) ? (int) $_GET["offset"] : 0;
$intLimit = (isset($_GET['limit'])) ? (int) $_GET["limit"] : 15;

$strSort = ($strSortColumn != '') ? " ORDER BY `" . $strSortColumn . "` " . $strSortOrder : '';
$strWhere .= ($strQuery != '') ? " AND (m.manufacturer_id LIKE '%" . $strQuery . "%' OR m.name LIKE '%" . $strQuery . "%')" : '';
$strLimit = " LIMIT " . $intOffset . "," . $intLimit;

$strSQL =
"SELECT m.manufacturer_id, m.name
        FROM manufacturer m
        WHERE 1
        " . $strWhere .
$strSort;
$resultCount = $objDB->sqlExecute($strSQL);
$count = $objDB->getNumRows($resultCount);

$strSQL = $strSQL . $strLimit;
$result = $objDB->sqlExecute($strSQL);

$arrJson['totalRows'] = $count;
$i = 0;

while ($objPage = $objDB->getObject($result)) {

    $arrJson['details'][$i][] = $objPage->manufacturer_id;
    $arrJson['details'][$i][] = ($objPage->name;

    $i++;
}

header('Content-type: application/json');
echo json_encode($arrJson);

//close db
$objDB->closeDB();
?>