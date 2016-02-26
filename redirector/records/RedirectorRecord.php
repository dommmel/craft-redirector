<?php
namespace Craft;

class RedirectorRecord extends BaseRecord
{
    /**
     * Returns the name of the associated database table.
     *
     * @return string
     */
    public function getTableName()
    {
        return 'redirector';
    }

    /**
     * @return array
     */
    public function defineRelations()
    {
        return array(
            'targetEntry' => array(static::BELONGS_TO, 'EntryRecord', 'required' => true, 'onDelete' => static::CASCADE),
            'locale'  => array(static::BELONGS_TO, 'LocaleRecord', 'locale', 'required' => true, 'onDelete' => static::CASCADE, 'onUpdate' => static::CASCADE)
            );
    }

    public function defineIndexes()
    {
        return array(
            array('columns' => array('locale', 'targetEntryId')),
            array('columns' => array('urlPattern'), 'unique' => true)
        );
    }

    /**
     * @inheritDoc BaseRecord::defineAttributes()
     *
     * @return array
     */
    protected function defineAttributes()
    {
        return array(
            'locale' => array(AttributeType::Locale, 'required' => true),
            'urlPattern' => array(AttributeType::String, 'required' => true)
        );
    }
}
