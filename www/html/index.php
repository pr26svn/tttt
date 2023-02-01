<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle("Интернет-магазин \"Одежда\"");

$APPLICATION->IncludeComponent(
    "svn:user.adress",
    ".default",
    array(
        "COMPONENT_TEMPLATE" => ".default",
        "GET_ALL" => "N"
    ),
    false
);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
