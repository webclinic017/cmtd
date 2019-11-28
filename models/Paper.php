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


	public static function CollectionName(){
		return 'papers';
	}

	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	public function attributes() {
		return ["_id", "created_at", "date","codbdi", "codneg", "tpmerc", "nomres",
		"especi", "prazot", "modref", "preab", "premax", "premin", "premed", "preult", "preofc", "preofv", "totneg", "quatot", "voltot", "preexe", "indopc", "datven", "fatcot", "ptoexe", "codisi", "dismes"];
	}

	public function toIsoDate($timestamp){
        return new \MongoDB\BSON\UTCDateTime($timestamp * 1000);
    }
}