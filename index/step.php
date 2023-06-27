<?php

global $APPLICATION;

use Bitrix\Main\Localization\Loc;

if (!check_bitrix_sessid()) {
    return;
}

if ($APPLICATION->GetException()) {
    echo CAdminMessage::ShowMessage([
        'TYPE' => 'ERROR',
        'MESSAGE' => Loc::getMessage('SAMSON_MODULE_INSTALL_ERROR'),
        'DETAILS' => $APPLICATION->GetException()->GetString(),
        'HTML' => true,
    ]);
} else {
    echo CAdminMessage::ShowNote(GetMessage('SAMSON_MODULE_INSTALLED'));
}
?>

<form action='<?= $APPLICATION->GetCurPage() ?>'>
    <input type='hidden' name='lang' value='<?= LANG ?>'>
    <input type='submit' name='' value='<?= Loc::getMessage('SAMSON_MODULE_SUBMIT_BACK') ?>'>
</form>