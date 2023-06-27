<?php

global $APPLICATION;

use Bitrix\Main\Localization\Loc;

if (!check_bitrix_sessid()) {
    return;
}
?>

<form action='<?= $APPLICATION->GetCurPage() ?>'>
    <?= bitrix_sessid_post() ?>
    <input type='hidden' name='lang' value='<?= LANG ?>'>
    <input type='hidden' name='id' value='samson.feedback'>
    <input type='hidden' name='uninstall' value='Y'>
    <input type='hidden' name='step' value='2'>
    <p><?= Loc::getMessage('SAMSON_MODULE_SAVE_TEXT') ?></p>
    <p>
        <input type='checkbox' name='deleteTable' id='deleteTable' value='Y'>
        <label for="deleteTable">
            <?= Loc::getMessage('SAMSON_MODULE_SAVE_TABLE') ?>
        </label>
    </p>

    <input type='submit' name='' value='<?= Loc::getMessage('SAMSON_MODULE_SUBMIT_DEL') ?>'>
</form>