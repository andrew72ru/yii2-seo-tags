<?php

namespace andrew72ru\seotag\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "{{%seotag_keyworgs}}".
 *
 * @property integer $id
 * @property string $word
 * @property Seotag[] $tags
 */
class SeotagKeywords extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%seotag_keywords}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['word', 'required'],
            ['word', 'string', 'max' => 255],
            ['word', 'filter', 'filter' => 'trim'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'word' => Yii::t('app.seotag', 'Word'),
            'tags' => Yii::t('app.seotag', 'Meta-tags')
        ];
    }

    /**
     * @return \yii\db\Query
     */
    public function getTags()
    {
        return $this->hasMany(Seotag::className(), ['id' => 'tag_id'])
            ->viaTable('{{%tag_to_keyword}}', ['word_id' => 'id'])->inverseOf('keywords');
    }

    /**
     * @return array
     */
    public static function fullList()
    {
        return (new Query())->from(self::tableName())->distinct()->select('word')->column();
    }
}
