<?php

use Bitrix\Main\Loader;

global $APPLICATION;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle('Страница получения скидки');

Loader::includeModule('samson.discount');

$APPLICATION->IncludeComponent(
    'aleks:discount',
    '',
);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");