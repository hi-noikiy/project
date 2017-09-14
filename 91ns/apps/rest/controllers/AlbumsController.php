<?php

namespace Micro\Controllers;

use Phalcon\Mvc\Controller;
use Micro\Models\Users;

class AlbumsController extends ControllerBase
{
    //获取用户的相片列表
    //offset=xxx&limit=xxx
    public function getList($uid) {
        $returnStatusCode = $this->status->getStatus('OK');

        if ($this->request->isGet()) {
            $offset = $this->request->getQuery('offset');
            $limit = $this->request->getQuery('limit');

            try {
                $cond = "";
                if ($offset > 0) {
                    $cond = "OFFSET ".$offset;
                }
                if ($limit > 0) {
                    $cond = "LIMIT ".$limit." ".$cond;
                }

                $phql = "SELECT count(*) AS number FROM Micro\Models\Albums where uid = '".$uid."'";
                $query = $this->modelsManager->createQuery($phql);
                $rets = $query->execute();
                $number = 0;
                foreach ($rets as $ret) {
                    $number = $ret->number;
                    break;
                }

                $phql = "SELECT * FROM Micro\Models\Albums WHERE uid = '".$uid."' ORDER BY createtime desc ".$cond;
                $query = $this->modelsManager->createQuery($phql);
                $photos = $query->execute();
                $photolist = array();
                if ($photos->valid()) {
                    foreach ($photos as $photo) {
                        $photodata['id'] = $photo->id;
                        $photodata['createtime'] = $photo->createtime;
                        $photodata['file'] = $this->pathgenerator->getFullAlbumPath($photo->uid, $photo->file);
                        array_push($photolist, $photodata);
                    }
                }
                $result['count'] = $number;
                $result['value'] = $photolist;

                return $this->status->generate($returnStatusCode, $this->status->getCode('OK'), $result);
            }
            catch (\Exception $e) {
                return $this->status->generate($returnStatusCode, $this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
            }
        }

        return $this->proxyError($returnStatusCode);
    }

    //添加相片
    public function add() {
        $returnStatusCode = $this->status->getStatus('CREATED');

        if ($this->request->isPost()) {
            if($this->request->hasFiles()){
                $userdata = $this->session->get($this->config->websiteinfo->authkey);
                $uid = $userdata['uid'];

                try {
                    foreach ($this->request->getUploadedFiles() as $file) {                          
                        $fileExt = substr($file->getName(),-4);
                        $filePath = $this->pathgenerator->getAlbumPath($uid);
                        $time = time();
                        $fileName = $time.$fileExt;
                        $this->storage->upload($filePath.$fileName, $file->getTempName(), true);
                        try {
                            $phql = "INSERT INTO Micro\Models\Albums (uid, createtime, file) 
                                     VALUES (:uid:, :createtime:, :file:)";
                            $this->modelsManager->executeQuery($phql,
                                array(
                                    'uid'           => $uid,
                                    'createtime'    => $time,
                                    'file'          => $fileName
                                )
                            );

                            $phql = "SELECT * FROM Micro\Models\Albums WHERE uid = '".$uid."' AND createtime = ".$time;
                            $query = $this->modelsManager->createQuery($phql);
                            $photos = $query->execute();
                            $result = array();
                            if ($photos->valid()) {
                                foreach ($photos as $photo) {
                                    $result['id'] = $photo->id;
                                    $result['uid'] = $photo->uid;
                                    $result['createtime'] = $photo->createtime;
                                    $result['file'] = $this->pathgenerator->getFullAlbumPath($photo->uid, $photo->file);
                                    break;
                                }
                            }
                            return $this->status->generate($returnStatusCode, $this->status->getCode('OK'), $result);
                        }
                        catch (\Exception $e) {
                            return $this->status->generate($returnStatusCode, $this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
                        }
                    }
                }
                catch (\Exception $e) {
                    return $this->status->generate($returnStatusCode, $this->status->getCode('FILESYS_OPER_ERROR'), $e->getMessage());
                }
            }
            else {
                return $this->status->generate($returnStatusCode, $this->status->getCode('UPLOADFILE_ERROR'));
            }
        }

        return $this->proxyError($returnStatusCode);
    }

    //删除相片
    public function del() {
        
    }
}