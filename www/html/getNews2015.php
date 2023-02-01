<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Loader;

try {
    Loader::includeModule("iblock");
} catch (\Bitrix\Main\LoaderException $e) {
    json_encode(["error" => 1, "message" => $e->getMessage()]);
    die();
}
$rsNews = \CIBlockElement::GetList(
    ['SORT' => 'ASC', 'ID' => 'ASC'],
    ['IBLOCK_ID' => NEWS_IBLOCK_ID, 'ACTIVE' => 'Y'],
    false,
    false,
    ['ID', 'IBLOCK_ID', 'CODE', 'NAME', 'PROPERTY_AUTHOR.NAME', 'TAGS', 'ACTIVE_FROM', 'DETAIL_PAGE_URL', 'PREVIEW_PICTURE', 'IBLOCK_SECTION_ID']
);
$arResultNews = [];
while ($arNews = $rsNews->Fetch()) {
    $arResultNews[] = [
        "id" => $arNews["ID"],
        "url" => $arNews["DETAIL_PAGE_URL"],
        "image" => \CFile::GetFileArray($arNews["PREVIEW_PICTURE"])["SRC"],
        "name" => $arNews["NAME"],
        "sectionName" => $arNews["IBLOCK_SECTION_ID"],
        "date" => FormatDateFromDB($arNews["ACTIVE_FROM"]),
        "author" => $arNews["PROPERTY_AUTHOR_NAME"],
        "tags" => $arNews["TAGS"]
    ];

}
echo json_encode($arResultNews);


/**
 * Ошибка в скрипте из второго задания в необработке POST
 * и в параметрах редиректа header
 */