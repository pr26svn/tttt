<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Errorable;
use Bitrix\Main\ErrorableImplementation;
use Bitrix\Main\Loader;
use Bitrix\Main\SystemException;


class UserAdress extends \CBitrixComponent implements Controllerable, Errorable
{
    use ErrorableImplementation;

    protected $httpClient;

    /**
     * @return mixed|void|null
     * @throws SystemException
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\ObjectPropertyException
     */
    public function executeComponent()
    {
        Loader::includeModule('highloadblock');
        $entityClass = $this->getEntityClass(USER_ADRESS_HL);
        $this->arResult["ITEMS"] = $this->getInfoAdressUser($entityClass);
        $this->includeComponentTemplate();

    }

    /**
     * @param int $idHL
     * @return void
     * @throws SystemException
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     */
    public function getEntityClass(int $idHL)
    {
        /**
         * ToDo вынести в отдельную сущность
         */
        $hlBlock = HighloadBlockTable::getById($idHL)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlBlock);
        return $entity->getDataClass();
    }

    /**
     * @param array $arUserId
     * @return array
     */
    public function getUserInfo(array $arUserId)
    {
        $rsUser = \CUser::getList(($by = "personal_country"), ($order = "desc"), ["ID" => $arUserId]);
        $arResUser = [];
        while ($arUser = $rsUser->Fetch()) {
            $name = ($arUser["LOGIN"] != "") ? $arUser["LOGIN"] : "";
            $lastName = ($arUser["LAST_NAME"] != "") ? $arUser["LAST_NAME"] : "";
            //собираем инфу о пользователе
            $arResUser[$arUser["ID"]] = "(" . $arUser["LOGIN"] . ") " . $name . " " . $lastName;
        }

        return $arResUser;
    }

    /**
     * @param $entityDataClass
     * @return array
     */
    public function getInfoAdressUser($entityDataClass)
    {
        $rsData = $entityDataClass::getList(array(
            "select" => ["*"],
            "order" => ["ID" => "ASC"],
            "filter" => ["UF_AC" => "1"],
            'cache' => ['ttl' => 360000],
        ));
        $arResData = [];
        $arUser = [];
        while ($arData = $rsData->Fetch()) {
            $arResData[] = [
                'data' => [ //Данные ячеек
                    "ID" => $arData["ID"],
                    "ADRESS" => $arData["UF_ADRESS"],
                    "USER" => $arData["UF_USER_ID"]
                ],
                'actions' => [
                ]];
            $arUser[] = $arData['UF_USER_ID'];
        }
        /**
         * если пустой массив, то и пользователей нет и записей нет
         */
        if (!empty($arUser)) {
            $arUser = $this->getUserInfo($arUser);
            foreach ($arResData as $key => $arDataItem) {
                $arResData[$key]["data"]["USER"] = $arUser[$arDataItem["data"]["USER"]];
            }
        }
        return $arResData;
    }

    /**
     * @return array
     */
    public function configureActions()
    {
        return [];
    }
}
