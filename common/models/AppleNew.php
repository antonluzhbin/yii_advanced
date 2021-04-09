<?php

namespace common\models;

use yii\db\ActiveRecord;
use Exception;

/**
 * Apple model
 *
 * @property integer $id
 * @property string $color
 * @property integer $state
 * @property integer $date_appearance
 * @property integer $date_fall
 * @property float $size
 */
class AppleNew extends ActiveRecord
{
    const STATE_ON_TREE = 0;
    const STATE_FALL = 1;
    const STATE_ROTTEN = 2;

    const COLORS = [
        'red',
        'green',
        'yellow'
    ];

    const STATES = [
        'На дереве',
        'Упало',
        'Гнилое'
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%apple}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['state', 'in', 'range' => [self::STATE_ON_TREE, self::STATE_FALL, self::STATE_ROTTEN]],
        ];
    }

    public function __construct($color = false)
    {
        $this->color = !empty($color) ? $color : self::COLORS[rand(0, 2)];
        $this->size = 1;
        $this->date_appearance = time();
        $this->date_fall = 0;
        $this->state = 0;

        parent::__construct();
    }

    public function fallToGround()
    {
        if ($this->state == self::STATE_ON_TREE) {
            $this->state = self::STATE_FALL;
            $this->date_fall = time();
            $this->save();
        }
    }

    public function eat($size)
    {
        $this->is_rotten();

        // Когда висит на дереве - съесть не получится.
        if ($this->state == self::STATE_FALL) {
            if ($size / 100 > $this->size) {
                throw new Exception('Съесть нельзя, нет такого куска!');
            } else {
                $this->size -= $size / 100;
                $this->save();
            }
        } else {
            if ($this->state == self::STATE_ON_TREE) {
                throw new Exception('Съесть нельзя, яблоко на дереве!');
            } else {
                throw new Exception('Съесть нельзя, яблоко испортилось!');
            }
        }

        // Когда съедено - удаляется из массива яблок.
        if ($this->size < 0.0001) {
            $this->delete();
        }
    }

    public function is_rotten()
    {
        // После лежания 5 часов - портится
        if ($this->date_fall > 0 && $this->state == self::STATE_FALL && (time() - $this->date_fall) > 5 * 60 * 60) {
            $this->state = self::STATE_ROTTEN;
            $this->save();
        }
    }

    public function delete()
    {
        if ($this->size < 0.0001) {
            parent::delete();
        } else {
            throw new Exception('Нельзя удалить не съеденное яблоко!');
        }
    }

    protected function addNewApple()
    {
        $apple = new AppleNew();
        $apple->size = rand(1, 100) / 100;
        $this->state = rand(0, 2);
        $apple->save();
    }

    public function addNewApples($cnt)
    {
        if ($cnt > 0 && $cnt < 10) {
            for ($i = 0; $i < $cnt; $i++) {
                $this->addNewApple();
            }
        } else {
            throw new Exception('Неправильное количество!');
        }
    }
}
