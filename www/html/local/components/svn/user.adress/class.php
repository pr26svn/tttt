<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\CurrentUser;
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
    public function getUserInfo()
    {

        $name = (CurrentUser::get()->getFirstName() != "") ? CurrentUser::get()->getFirstName() : "";
        $lastName = (CurrentUser::get()->getLastName() != "") ? CurrentUser::get()->getLastName() : "";
        //собираем инфу о пользователе
        $arResUser = "(" . CurrentUser::get()->getLogin() . ") " . $name . " " . $lastName;


        return $arResUser;
    }

    /**
     * @param $entityDataClass
     * @return array
     */
    public function getInfoAdressUser($entityDataClass)
    {
        $idUser = CurrentUser::get()->getId();
        $arFilter = ["UF_USER_ID" => $idUser];
        if ($this->arParams["GET_ALL"] != "Y")
            $arFilter = ["UF_AC" => "1", "UF_USER_ID" => $idUser];
        $rsData = $entityDataClass::getList(array(
            "select" => ["*"],
            "order" => ["ID" => "ASC"],
            "filter" => $arFilter,
            'cache' => ['ttl' => 360000],
        ));
        $arResData = [];
        $arUser = [];
        $userInfo = $this->getUserInfo();

        while ($arData = $rsData->Fetch()) {
            $arResData[] = [
                'data' => [ //Данные ячеек
                    "ID" => $arData["ID"],
                    "ADRESS" => $arData["UF_ADRESS"],
                    "USER" => $userInfo
                ],
                'actions' => [
                ]];
            $arUser[] = $arData['UF_USER_ID'];
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
