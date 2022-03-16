<?php

declare(strict_types=1);

namespace Dotsquares\ShopbyBase\Setup;

use Dotsquares\ShopbyBase\Api\Data\FilterSettingRepositoryInterface;
use Dotsquares\ShopbyBase\Api\Data\OptionSettingRepositoryInterface;
use Dotsquares\ShopbyBase\Model\OptionSetting;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UninstallInterface;

/**
 * Delete tables manually, because Dotsquares_Base restricts to delete Dotsquares tables by Declarative Scheme.
 *
 * @see \Dotsquares\Base\Plugin\Framework\Setup\Declaration\Schema\Diff\Diff\RestrictDropTables
 */
class Uninstall implements UninstallInterface
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context): void
    {
        $this->deleteImages();

        $defaultConnection = $setup->getConnection();
        $defaultConnection->dropTable(
            $setup->getTable(FilterSettingRepositoryInterface::TABLE)
        );
        $defaultConnection->dropTable(
            $setup->getTable(OptionSettingRepositoryInterface::TABLE)
        );
    }

    /**
     * Delete stored images
     */
    private function deleteImages(): void
    {
        $mediaDir = $this->filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $mediaDir->delete(OptionSetting::IMAGES_DIR);
    }
}
