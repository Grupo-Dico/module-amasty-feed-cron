<?php

declare(strict_types=1);

namespace GDMexico\AmastyFeedCron\Model\Config\Backend;

use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\Config\Value;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

class CronConfig extends Value
{
    private const XML_PATH_ENABLED = 'gdmexico_amasty_feed_cron/general/enabled';
    private const XML_PATH_SCHEDULE = 'gdmexico_amasty_feed_cron/general/schedule';
    private const XML_PATH_ACTIVE_SCHEDULE = 'gdmexico_amasty_feed_cron/general/active_schedule';
    private const DISABLED_SCHEDULE = '0 0 31 2 *';

    /**
     * @var WriterInterface
     */
    private $configWriter;

    public function __construct(
        Context $context,
        Registry $registry,
        ScopeConfigInterface $config,
        TypeListInterface $cacheTypeList,
        WriterInterface $configWriter,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->configWriter = $configWriter;

        parent::__construct(
            $context,
            $registry,
            $config,
            $cacheTypeList,
            $resource,
            $resourceCollection,
            $data
        );
    }

    public function afterSave()
    {
        $sectionData = $this->getData('fieldset_data');

        $enabled = isset($sectionData['enabled'])
            ? (int)$sectionData['enabled']
            : (int)$this->_config->getValue(self::XML_PATH_ENABLED);

        $schedule = isset($sectionData['schedule'])
            ? trim((string)$sectionData['schedule'])
            : trim((string)$this->_config->getValue(self::XML_PATH_SCHEDULE));

        $activeSchedule = $enabled ? $schedule : self::DISABLED_SCHEDULE;

        $this->configWriter->save(
            self::XML_PATH_ACTIVE_SCHEDULE,
            $activeSchedule,
            'default',
            0
        );

        return parent::afterSave();
    }
}
