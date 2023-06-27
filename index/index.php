<?php

use Bitrix\Main\Application;
use Bitrix\Main\DB\SqlQueryException;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\SystemException;
use Samson\Discount\Entity;

class samson_discount extends CModule
{
    /**
     * Настройки передачи параметров
     * в конструкторе
     */
    public function __construct()
    {
        $this->MODULE_ID = 'samson.discount';
        $this->MODULE_GROUP_RIGHTS = 'Y';
        $this->PARTNER_NAME = '';
        $this->PARTNER_URI = '';

        if( file_exists(__DIR__.'/version.php') ) {
            $arModuleVersion = [];
            include_once(__DIR__.'/version.php');
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }

        $this->MODULE_NAME = Loc::getMessage('SAMSON_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('SAMSON_MODULE_DESCRIPTION');
    }

    /**
     * @throws SystemException
     */
    public function DoInstall(): void
    {
        global $APPLICATION;

        /** Регистрируем модуль */
        ModuleManager::registerModule($this->MODULE_ID);

        $this->installDB();
        $this->installFiles();

        $APPLICATION->IncludeAdminFile(
            Loc::getMessage('SAMSON_MODULE_INSTALL'),
            __DIR__.'/step.php'
        );
    }

    /**
     * Удаления модуля
     *
     * @return void
     * @throws Exception
     */
    public function DoUninstall(): void
    {
        global $APPLICATION;

        $request = Application::getInstance()->getContext()->getRequest();

        if($request['step'] < 2) {
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage('KORUS_MODULE_UNINSTALL'),
                __DIR__.'/unstep1.php'
            );
        }

        if($request['deleteTable'] !== 'Y') {
            $this->unInstallDB();
        }

        $this->unInstallFiles();

        /** Удаляем модуль */
        ModuleManager::unRegisterModule($this->MODULE_ID);

        $APPLICATION->IncludeAdminFile(
            Loc::getMessage('KORUS_MODULE_UNINSTALL'),
            __DIR__.'/unstep2.php'
        );
    }

    /**
     * Устанавливаем БД
     *
     * @return void
     * @throws SystemException
     */
    public function installDB(): void
    {
        $tables = [
            Entity\DiscountUser::class
        ];

        $connection = Application::getConnection();
        /** @var DataManager $table */
        foreach ($tables as $table) {
            if ($connection->isTableExists($table::getTableName())) {
                continue;
            }

            $table::getEntity()->createDbTable();
        }
    }

    /**
     * Удаляем БД
     *
     * @return void
     * @throws SqlQueryException
     */
    public function unInstallDB(): void
    {
        $connection = Application::getConnection();
        $connection->dropTable(Entity\DiscountUser::getTableName());
    }

    /**
     * Копируем файлы
     *
     * @return void
     */
    public function installFiles(): void
    {
        $root = Application::getDocumentRoot();

        /** Копируем компонент работы модуля */
        CopyDirFiles(
            __DIR__ . '/bitrix/components',
            $root . '/bitrix/components',
            true,
            true,
        );

        /** Копируем компонент работы модуля */
        CopyDirFiles(
            __DIR__ . '/discount',
            $root . '/discount',
            true,
            true,
        );
    }

    /**
     * Удаляем файлы
     *
     * @return void
     */
    public function unInstallFiles(): void
    {
        $root = Application::getDocumentRoot();

        /** Удаляем файлы */
        static::deleteDirFiles(__DIR__ . '/bitrix', $root . '/bitrix');
        static::deleteDirFiles(__DIR__ . '/discount', $root . '/discount');
    }

    /**
     * Удаляем файлы
     *
     * @param string $fromDir
     * @param string $toDir
     * @return void
     */
    public static function deleteDirFiles(string $fromDir, string $toDir): void
    {
        if (!is_dir($fromDir)) {
            return;
        }

        foreach (static::getDirFiles($fromDir) as $entry) {
            if (is_dir($fromDir . '/' . $entry)) {
                static::deleteDirFiles($fromDir . '/' . $entry, $toDir . '/' . $entry);
            } else {
                @unlink($toDir . '/' . $entry);
            }
        }
    }

    /**
     * Вычисляем расхождения и удаляем
     *
     * @param string $dir
     * @return array
     */
    public static function getDirFiles(string $dir) : array
    {
        return (array)array_diff(scandir($dir), ['.', '..']);
    }
}