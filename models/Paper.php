<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\mongodb\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * ContactForm is the model behind the contact form.
 */
class Paper extends ActiveRecord
{


	public static function CollectionName()
	{
		return 'test';
	}

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function attributes()
	{
		return [
			"_id", "created_at", "date", "codbdi", "codneg", "tpmerc", "nomres",
			"especi", "prazot", "modref", "preab", "premax", "premin", "premed", "preult", "preofc", "preofv", "totneg", "quatot", "voltot", "preexe", "indopc", "datven", "fatcot", "ptoexe", "codisi", "dismes", "state"
		];
	}

	public function toIsoDate($timestamp)
	{
		return new \MongoDB\BSON\UTCDateTime($timestamp * 1000);
	}

	public function toDate($date)
	{
		$date = \DateTime::createFromFormat('U', $date);
		$date = intval($date->format('U'));
		$date /= 1000;
		$date = \DateTime::createFromFormat('U', (string) $date);
		$date = \DateTime::createFromFormat('d/m/Y H:i:s', $date->format('d/m/Y H:i:s'));

		return $date;
	}

	public function movingAverage($paper, $size)
	{
		$average = [];
		$sum =  0;

		for ($i = 0; $i < count($paper) - ($size - 1); $i++) {
			for ($j = 0; $j < $size; $j++) {
				$sum += $paper[$i + $j]['preult'];
			}
			$average[$i] = $sum / $size;
			$sum = 0;
		}

		return $average;
	}


	public function getState($price, $premin, $premax, $interval, $states_number)
	{
		for ($i = 0; $i < $states_number; $i++) {
			if ($price >= ($premin + ($interval * $i)) && $price < $premin + ($interval * ($i + 1))) {
				return $i + 1;
			}
		}

		return 0;
	}
}
