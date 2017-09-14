<?php

namespace Micro\Frameworks\Movement;

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
        $this->collection = $this->mongo->collection('user_movements');

        $indexes = $this->collection->listIndexes();
        if(!$this->isIndexExist($indexes))
        {   
            $this->collection->indexes(function($index){
                $index->create(array('userid' => 1));
            });               
        }
    }

    protected function isIndexExist($indexes)
    {
        foreach ($indexes as $key => $value) {
            foreach ($value as $k => $v) {
                if( $k == 'name' && $v == 'userid_1' )
                    return true;
            }
        }
        return false;
    }

    public function publish($userid, $title, $content, $uploadFiles)
    {
        $images = array();
        $thumbs = array();
        if(is_array($uploadFiles))
        {
            foreach ($uploadFiles as $file) {     
                $pathName = $this->di->get('pathGenerator')->getMovementPath($userid);
                $fileName = $this->di->get('uid')->fguid().'.'.$file->getExtension();      
                $imageName = $pathName.$fileName;

                //生成image
                $this->di->get('storage')->upload($imageName, $file->getTempName(), true);

                //生成thumb
                $thumbName = $this->di->get('thumbGenerator')->getThumbnail($imageName, 'feed-1x');

                //生成绝对的url
                $imageurl = $this->di->get('url')->getStatic($imageName);
                $thumburl = $this->di->get('url')->getStatic($thumbName);

                array_push($images, $imageurl);
                array_push($thumbs, $thumburl);
            }
        }

        $result = $this->collection->update( function($query) use($userid, $title, $content, $images, $thumbs){
            $query->upsert();
            $query->where('userid', $userid)
                  ->set('userid', $userid)
                  ->addToSet( 'movement', array('title'    =>$title,
                                                'content'  =>$content,
                                                'images'   =>$images,
                                                'thumbs'   =>$thumbs));                          
        } ); 
    }

    public function delete($userid, $index)
    {
        $userInfo = $this->collection->findOne(function ($query) use($userid){
            $query->where('userid', $userid);
        });

        if($userInfo != null)
        {
            if(isset($userInfo['movement'][$index]))  
            {
                $item = $userInfo['movement'][$index];
                $result = $this->collection->update( function($query) use($userid, $item){
                $query->where('userid', $userid)
                      ->pull('movement', $item);                          
                });     
            }
        }
    }

    public function getMovements($userid)
    {
        $userInfo = $this->collection->findOne(function ($query) use($userid){
            $query->where('userid', $userid);
        });

        if($userInfo != null)
        {
            return $userInfo['movement']; 
        }
        return null;
    }
   
}
