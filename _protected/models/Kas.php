<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kas".
 *
 * @property int $id
 * @property string $kwitansi
 * @property string $penanggung_jawab
 * @property string $keterangan
 * @property string $tanggal
 * @property int $jenis_kas
 * @property double $kas_keluar
 * @property double $kas_masuk
 * @property string $created
 */
class Kas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kas';
    }

    public function behaviors()
    {
        return [
            [
                'class' => 'mdm\autonumber\Behavior',
                'attribute' => 'kwitansi', // required
                // 'group' => $this->id_branch, // optional
                'value' => 'KW.'.date('Y-m-d').'.?' , // format auto number. '?' will be replaced with generated number
                'digit' => 4 // optional, default to null. 
            ],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['penanggung_jawab', 'keterangan', 'tanggal','perkiraan_id'], 'required'],
            [['keterangan'], 'string'],
            [['tanggal', 'created'], 'safe'],
            [['jenis_kas'], 'integer'],
            [['kas_keluar', 'kas_masuk'], 'number'],
            [['kwitansi'], 'string', 'max' => 50],
            [['kwitansi'], 'autonumber', 'format'=>'KW.'.date('Y-m-d').'.?'],
            [['penanggung_jawab'], 'string', 'max' => 255],
            [['perkiraan_id'], 'string', 'max' => 20],
            [['perkiraan_id'], 'exist', 'skipOnError' => true, 'targetClass' => Perkiraan::className(), 'targetAttribute' => ['perkiraan_id' => 'kode']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kwitansi' => 'Kwitansi',
            'penanggung_jawab' => 'Penanggung Jawab',
             'perkiraan_id' => 'Perkiraan',
            'keterangan' => 'Keterangan',
            'tanggal' => 'Tanggal',
            'jenis_kas' => 'Jenis Kas',
            'kas_keluar' => 'Kas Keluar',
            'kas_masuk' => 'Kas Masuk',
            'saldo' => 'Saldo',
            'created' => 'Created',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerkiraan()
    {
        return $this->hasOne(Perkiraan::className(), ['kode' => 'perkiraan_id']);
    }

    public static function getTotal($provider, $columnName)
    {
        $total = 0;
        foreach ($provider as $item) {
          $total += $item[$columnName];
      }
      return $total;  
    }

   
}
