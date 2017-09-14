<?php

namespace Micro\Frameworks\LBS;

use Phalcon\DI\FactoryDefault;

use League\Monga\Query\Indexes;
use GeoJson\GeoJson;
use GeoJson\Geometry\Point;

class Manager
{
    protected $di;
    protected $mongo;
    protected $collection;

    public static $EARTH_RADIUS =  6371.0;
    public static $RADIAN_LENGTH = 111.2;

    public function __construct() 
    {
        $this->di = FactoryDefault::getDefault();
        $this->mongo = $this->di->get('mongo');
        $this->collection = $this->mongo->collection('user_coordinates');

        $indexes = $this->collection->listIndexes();
        if(!$this->isIndexExist($indexes))
        {
            $this->collection->indexes(function($index){
                $index->create(array('userid' => 1));
                $index->create(array('coordinate' => '2dsphere'));
            });               
        }
    }

    protected function isIndexExist($indexes)
    {
        foreach ($indexes as $key => $value) {
            foreach ($value as $k => $v) {
                if( $k == 'name' && ($v == 'userid_1' || $v == 'coordinate_2dsphere') )
                    return true;
            }
        }
        return false;
    }

    public function updateCoordinate($userid, $longtitude, $latitude)
    {
        $point = new Point(array(floatval($longtitude), floatval($latitude)));
        $result = $this->collection->update(function($query) use($userid, $point){
            $query->upsert();
            $query->where('userid', $userid)
                  ->set( array('userid'        => $userid,
                               'coordinate'    => $point->jsonSerialize() ) );                            
        });
        return $result;     
    }

    public function getCoordinate($userid)
    {
        $user = $this->collection->findOne(function($query) use($userid) {
            $query->where('userid', $userid);
        });

        if(!empty($user)){
            return $user['coordinate']['coordinates'];
//           return GeoJson::jsonUnserialize($user['coordinate']);
        }
        return null;
    }


    public function getNearby($coordinate, $maxDistance, $maxNumber=100)
    {
        $geoPoint = new Point([$coordinate[0], $coordinate[1]]);
        $collections =  $this->mongo->command(array('geoNear'             =>  'user_coordinates',
                                                    'near'                =>  $geoPoint->jsonSerialize(), 
                                                    'spherical'           =>  true,
                                                    'maxDistance'         =>  $maxDistance ,
                                                    'num'                 =>  $maxNumber));

        return $collections['results'];
    }   

    //左下到右上
    /*
    public function getWithBox($lbLongtitude, $lbLatitude, $rtLongtitude, $rtLatitude)
    {
        $result = $this->mongo->whereWithin('coordinate', array(
                                            '$box' => array(array($lbLongtitude, $lbLatitude), array($rtLongtitude, $rtLatitude))
                                            ))
                              ->get('user_coordinates');
  
        return $result;
    }

    //半径的单位为km
    public function getWithCircle($ctLongtitude, $ctLatitude, $radius)
    {
        $result = $this->mongo->whereWithin('coordinate', array(
                                            '$center' => array(array($ctLongtitude, $ctLatitude), $radius/Manager::$RADIAN_LENGTH))
                                            )
                             ->get('user_coordinates');
        return $result;
    }
    */
}
