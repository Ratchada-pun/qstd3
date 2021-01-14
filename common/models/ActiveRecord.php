<?php
namespace common\models;

use yii\db\ActiveRecord as BaseActiveRecord;

class ActiveRecord extends BaseActiveRecord
{
    public function beforeSave($insert)
    {

        foreach($this->getTableSchema()->columns as $column) //loop column ทั้งหมด
        {
            $attribute = $this->getAttribute($column->name);
            if(empty($attribute)){ //หากได้ค่าว่าง
                $column->allowNull == 1 ? $this->setAttribute($column->name, null) : null;//ได้ตั้งค่า allow null ไหม ถ้าใช่ให้ใส่ null
            }else if($column->dbType == 'datetime' && preg_match('/(\d{1,2}\s?\/\s?\d{1,2}\s?\/\s?\d{4}\s?\w*)/', $attribute)){ //ถ้ารูปแบบเป็น datetime
                if(stripos($attribute,':') !== false){//ค้นหาตำแหน่ง : case-insensitive //ถ้ามีเวลาด้วย
                    $date_time = explode(' ', $attribute); //แยกวันที่กับเวลาด้วย space
                    $time = ' ' . trim($date_time[1]);
                }else{//ถ้าไม่มีเวลา
                    $date_time[0] = $attribute;
                    $time = '';//เวลาว่าง
                }

                $dmy = preg_split('/(\s?\/\s?)/', $date_time[0]); //แยก วัน/เดือน/ปี
                $year = (int) $dmy[2];//กำหนดเป็น int เพื่อการคำนวณ
                $year = $year - 543;//ปี พ.ศ.-543
                $result = $year . '-' . $dmy[1] . '-' . $dmy[0] . $time;//ได้รูปแบบ 2016-05-20
                $this->setAttribute($column->name, $result);//กำหนดค่าใหม่
            }
        }
        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        foreach($this->attributes as $column_name => $value){
            if(preg_match('/(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/', $value)){ //ถ้ามีค่าในรูปแบบ 2016-05-20 13:30:45

                if($value == '0000-00-00 00:00:00'){ //ถ้าไม่มีข้อมูล
                    $this->setAttribute($column_name, null); //กำหนดให้เป็นค่าว่าง
                }else{
                    $date_and_time = explode('.', $value);
                    $date_time = explode(' ', $date_and_time[0]); //แยกวันและเวลา
                    if($date_time[1] == '00:00:00'){ //ถ้าเวลาว่าง
                        $date_time[1] = ''; //ไม่มีเวลา
                    }else{// ถ้ามีเวลา
                        $date_time[1] = ' ' . $date_time[1];//กำหนดเวลา
                    }

                    $ymd = explode('-', $date_time[0]);//แยก ปี-เดือน-วัน
                    $year = (int) $ymd[0];//กำหนดให้เป็น int เพื่อการคำนวณ
                    $year = $year + 543;// นำปี +543
                    $result = $ymd[2] . '/' . $ymd[1] . '/' . $year . $date_time[1];//ได้รูปแบบ วัน/เดือน/ปี ชั่วโมง:นาที:วินาทีี
                    $this->setAttribute($column_name, $result);//กำหนดค่าใหม่
                }
            }

        }
        return parent::afterFind();
    }
}