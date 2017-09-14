<style>
    .my-box{
        width: 100%;
        float: left;
        border-bottom: 1px dashed #ddd;
        padding-top: 10px;
        padding-bottom: 10px;
        overflow-x: auto;
        overflow-y: auto;
        margin-right: 17px;
    }
    .box-title{
        font-size: 14px;
        font-weight: bold;
        padding: 8px 0;
    }
    h3.title{
        font-weight: bold;
        font-size: 40px;
        float: left;
        background-color: #eee;
        width: 100%;
        padding: 16px;
    }
    .print{
        text-align: center;
        font-weight: bold;
    }
    .print button{
        width: 100px;
        height: 40px;
        line-height: 40px;
    }
    @media print{
        .print,.panel-heading {
            display: none;
        }
    }
</style>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <!-- /.panel-heading -->
            <div class="panel-heading">
                <form class="form-inline" role="form" method="get">
                    <input name="user_id" type="hidden" value="<?=$user_id?>"/>
                    <input name="server_id" type="hidden" value="<?=$server_id?>"/>
                    <div class="form-group">
                        <label>Time</label>
                        <input name="bt" type="text" class="form-control" size="18" value="<?=$bt?>" >
                        ~
                        <input name="et" type="text" class="form-control" size="18" value="<?=$et?>" >
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="button" onclick="history.go(-1);" class="btn btn-primary">Back</button>
                </form>
            </div>
            <div class="panel-body">
                <div class="print">
                    <button type="button" onclick="window.print();"> Print ALL </button>
                </div>
                <h3 class="title">현재 시점의 유저 정보 조회에 필요한 내역들/目前调查玩家情报时必须要的项目 </h3>
                <!-- 1.	현재 장착하고 있는 아이템 및 강화 레벨 （装备的道具及强化等级） -->
                <div class="my-box">
                    <div class="box-title">현재장착하고있는아이템및강화레벨（装备的道具及强化等级）</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Item Name</th>
                                <th>Level</th>
                            </tr>
                            </thead>
                            <?php if(count($data['equip'])):?>
                            <?php foreach($data['equip'] as $equip) :?>
                            <tr>
                                <td><?=$equip['itemname']?></td>
                                <td><?=$equip['level']?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="3"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>
                <!-- 2.	장착하고 있는 아이템에 장착되어 있는 보석 종류, 보석 레벨 （装备的道具里的宝石种类及宝石等级） -->
                <div class="my-box">
                    <div class="box-title">장착하고있는아이템에장착되어있는보석종류, 보석레벨</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Item Name</th>
                                <th>Gem1</th>
                                <th>Gem1 Level</th>
                                <th>Gem2</th>
                                <th>Gem2 Level</th>
                                <th>Gem3</th>
                                <th>Gem3 Level</th>
                            </tr>
                            </thead>
                            <?php if(count($data['gem'])):?>
                            <?php foreach($data['gem'] as $equip) :?>
                            <tr>
                                <td><?=$equip['itemname']?></td>
                                <td><?=$equip['gem1_name']?></td>
                                <td><?=intval(substr($equip['gem1'],-2)) % 100?></td>
                                <td><?=$equip['gem2_name']?></td>
                                <td><?=intval(substr($equip['gem2'],-2)) % 100?></td>
                                <td><?=$equip['gem3_name']?></td>
                                <td><?=intval(substr($equip['gem3'],-2)) % 100?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="13"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>
                <!-- 3.장착하고있는깃털의종류및강화수치 （装备的翅的种类及强化的等级）-->
                <div class="my-box">
                    <div class="box-title">장착하고있는깃털의종류및강화수치（装备的翅的种类及强化的等级）</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Item Name</th>
                                <th>Item ID</th>
                                <th>Level</th>
                                <th>Gem1</th>
                                <th>Gem Name</th>
                                <th>Gem2</th>
                                <th>Gem2  Name</th>
                                <th>Gem3</th>
                                <th>Gem3  Name</th>
                            </tr>
                            </thead>
                            <?php if(count($data['wing'])):?>
                            <?php foreach($data['wing'] as $row) :?>
                            <tr>
                                <td><?=$row['itemname']?></td>
                                <td><?=$row['iditem']?></td>
                                <td><?=$row['level']?></td>
                                <td><?=$row['gem1']?></td>
                                <td><?=$row['gem1_name']?></td>
                                <td><?=$row['gem2']?></td>
                                <td><?=$row['gem2_name']?></td>
                                <td><?=$row['gem3']?></td>
                                <td><?=$row['gem_name']?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="3"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>
                <!-- 4.	장착하고 있는 무장의 종류 (上阵的武将的种类) -->
                <div class="my-box">
                    <div class="box-title">장착하고있는무장의종류(上阵的武将的种类)</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Day Time</th>
                                <th>Achieve Time</th>
                                <th>Name</th>
                                <th>Level</th>
                                <th>weapon</th>
                                <th>helmet</th>
                                <th>armor</th>
                                <th>belt</th>
                                <th>shoes</th>
                                <th>cap_overcoat</th>
                            </tr>
                            </thead>
                            <?php if(count($edmActiveList)):?>
                            <?php foreach($edmActiveList as $row) :?>
                            <tr>
                                <td><?=date('Y-m-d H:i', strtotime('20'.$row['daytime']))?></td>
                                <td><?=$row['achieve_time']?></td>
                                <td><?=$row['name']?></td>
                                <td><?=$row['level']?></td>
                                <td><?=$row['weapon']?></td>
                                <td><?=$row['helmet']?></td>
                                <td><?=$row['armor']?></td>
                                <td><?=$row['belt']?></td>
                                <td><?=$row['shoes']?></td>
                                <td><?=$row['cap_overcoat']?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="12"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>
                <!-- 5.	소지하고 있는 무장 내역 （所有的武装列表）-->
                <div class="my-box">
                    <div class="box-title">소지하고있는무장내역(所有的武装列表)</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Day Time</th>
                                <th>Name</th>
                                <th>Level</th>
                                <th>weapon</th>
                                <th>helmet</th>
                                <th>armor</th>
                                <th>belt</th>
                                <th>shoes</th>
                                <th>cap_overcoat</th>
                            </tr>
                            </thead>
                            <?php if(count($edmAllList)):?>
                            <?php foreach($edmAllList as $row) :?>
                            <tr>
                                <td><?=date('Y-m-d H:i', strtotime('20'.$row['daytime']))?></td>
                                <td><?=$row['name']?></td>
                                <td><?=$row['level']?></td>
                                <td><?=$row['weapon']?></td>
                                <td><?=$row['helmet']?></td>
                                <td><?=$row['armor']?></td>
                                <td><?=$row['belt']?></td>
                                <td><?=$row['shoes']?></td>
                                <td><?=$row['cap_overcoat']?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="3"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>
                <!-- 8.	인벤토리에 소지하고 있는 아이템 목록, 갯 수 （背包里道具的种类及数量） -->
                <div class="my-box">
                    <div class="box-title">인벤토리에소지하고있는아이템목록,갯수（背包里道具的种类及数量）</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Day Time</th>
                                <th>Item Name</th>
                                <th>Item Type</th>
                                <th>Item ID</th>
                                <th>Amount</th>
                            </tr>
                            </thead>
                            <?php if(count($itemList)):?>
                            <?php foreach($itemList as $row) :?>
                            <tr>
                                <td><?=date('Y-m-d H:i', strtotime('20'.$row['daytime']))?></td>
                                <td><?=$row['itemname']?></td>
                                <td><?=$row['itemtype']?></td>
                                <td><?=$row['iditem']?></td>
                                <td><?=$row['amount']?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="12"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>
                <!--9. 카카오톡, 게임 친구 목록（KAKAO的朋友目录） -->
                <div class="my-box">
                    <div class="box-title">카카오톡, 게임 친구 목록（KAKAO的朋友目录）</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Day Time</th>
                                <th>Friend Name</th>
                                <th>Friend ID</th>
                            </tr>
                            </thead>
                            <?php if(count($kakaoFriends)):?>
                            <?php foreach($kakaoFriends as $row) :?>
                            <tr>
                                <td><?=$row['logtime']?></td>
                                <td><?=$row['kakao_name']?></td>
                                <td><?=$row['idKakaofriend']?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="3"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>
                <!--10.	가지고 있는 무장이 장착한 아이템 및 강화 레벨 （上阵的武将装备道具及强化等级）-->
                <div class="my-box">
                    <div class="box-title">가지고있는무장이장착한아이템및강화레벨/上阵的武将装备道具及强化等级</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Item Name</th>
                                <th>Level</th>
                            </tr>
                            </thead>
                            <?php if(count($data['equip_active'])):?>
                            <?php foreach($data['equip_active'] as $equip) :?>
                            <tr>
                                <td><?=$equip['itemname']?></td>
                                <td><?=$equip['level']?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="3"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>
                <!--11.	가지고 있는 무장이 장착한 아이템의 보석 종류, 보석 레벨 (上阵武将装备的宝石种类, 宝石等级) -->
                <div class="my-box">
                    <div class="box-title">가지고있는무장이장착한아이템의보석종류,보석레벨/上阵武将装备的宝石种类, 宝石等级</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Item Name</th>
                                <th>Gem1</th>
                                <th>Gem1 Level</th>
                                <th>Gem2</th>
                                <th>Gem2 Level</th>
                                <th>Gem3</th>
                                <th>Gem3 Level</th>
                            </tr>
                            </thead>
                            <?php if(count($data['gem_active'])):?>
                            <?php foreach($data['gem_active'] as $equip) :?>
                            <tr>
                                <td><?=$equip['itemname']?></td>
                                <td><?=$equip['gem1_name']?></td>
                                <td><?=intval(substr($equip['gem1'],-2)) % 100?></td>
                                <td><?=$equip['gem2_name']?></td>
                                <td><?=intval(substr($equip['gem2'],-2)) % 100?></td>
                                <td><?=$equip['gem3_name']?></td>
                                <td><?=intval(substr($equip['gem3'],-2)) % 100?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="12"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>
                <!--12.레벨 및 HP, MP 등 능력치 및 체력 (等级, HP , MP 等 能力值 及 体力) -->
                <div class="my-box">
                    <div class="box-title">레벨 및 HP, MP 등 능력치 및 체력 (等级, HP , MP 等 能力值 及 体力)</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Tired</th>
                                <th>Lev</th>
                                <th>HP</th>
                                <th>SP</th>
                                <th>god exp</th>
                                <th>Reputation</th>
                                <th>DefencePoint</th>
                                <th>SpeedPoint</th>
                                <th>Attack</th>
                                <th>Defence</th>
                                <th>MagicAtk</th>
                                <th>MagicDef</th>
                                <th>HitRateAlter</th>
                                <th>DodgeAlter</th>
                                <th>CritRate</th>
                                <th>ResilienceRate</th>
                                <th>UnBlock</th>
                                <th>Block</th>
                                <th>CritDmgRat</th>
                            </tr>
                            </thead>
                            <?php if(count($playerInfo)):?>
                            <?php foreach($playerInfo as $row) :?>
                            <tr>
                                <td><?=$row['tired']?></td>
                                <td><?=$row['lev']?></td>
                                <td><?=$row['hp']?></td>
                                <td><?=$row['sp']?></td>
                                <td><?=$row['god_exp']?></td>
                                <td><?=$row['Reputation']?></td>
                                <td><?=$row['StrengthPoint']?></td>
                                <td><?=$row['DefencePoint']?></td>
                                <td><?=$row['SpeedPoint']?></td>
                                <td><?=$row['Attack']?></td>
                                <td><?=$row['Defence']?></td>
                                <td><?=$row['MagicAtk']?></td>
                                <td><?=$row['MagicDef']?></td>
                                <td><?=$row['HitRateAlter']?></td>
                                <td><?=$row['DodgeAlter']?></td>
                                <td><?=$row['CritRate']?></td>
                                <td><?=$row['ResilienceRate']?></td>
                                <td><?=$row['UnBlock']?></td>
                                <td><?=$row['Block']?></td>
                                <td><?=$row['CritDmgRat']?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="8"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>
                <!--13.	보유하고 있는 메일의 목록의 내용, 첨부된 아이템 (保有的邮件的目录内容, 附带道具) -->
                <div class="my-box">
                    <div class="box-title">보유하고 있는 메일의 목록의 내용, 첨부된 아이템 (保有的邮件的目录内容, 附带道具)</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Mail ID</th>
                                <th>Sender Name</th>
                                <th>sender id</th>
                                <th>Mail Contents</th>
                                <th>Item Name</th>
                                <th>Item Num</th>
                                <th>Item ID</th>
                                <th>Item Type</th>
                                <th>money</th>
                                <th>is read</th>
                            </tr>
                            </thead>
                            <?php if(count($mailList)):?>
                            <?php foreach($mailList as $row) :?>
                            <tr>
                                <td><?=$row['idmail']?></td>
                                <td><?=$row['sendername']?></td>
                                <td><?=$row['senderid']?></td>
                                <td><?=$row['words']?></td>
                                <td><?='['.$row['title'] .']'. $row['itemname']?></td>
                                <td><?=$row['itemnum']?></td>
                                <td><?=$row['iditem']?></td>
                                <td><?=$row['itemtype']?></td>
                                <td><?=$row['money']?></td>
                                <td><?=$row['isread']==1 ? 'Readed' : 'Unread';?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="8"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>
                <!--14.	현재 보물(진보각) 강화 현황 및 소지 현황 (现在宝物(珍宝阁)强化现状及保有情况) -->
                <div class="my-box">
                    <div class="box-title">현재보물(진보각)강화현황및소지현황 (现在宝物(珍宝阁)强化现状及保有情况)</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Layer</th>
                                <th>Flag</th>
                            </tr>
                            </thead>
                            <?php if(count($treasureInfo)):?>
                            <?php foreach($treasureInfo as $row) :?>
                            <tr>
                                <td><?=$row['layer']?></td>
                                <td><?=$row['flag'];?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="2"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>
                <!--15.	도전 보물 등 모든 컨텐츠에 대한 남은 횟 수 (挑战宝物等所有内容剩余次数) -->
                <div class="my-box">
                    <div class="box-title">도전보물등모든컨텐츠에대한남은횟수 (挑战宝物等所有内容剩余次数)</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Day Time</th>
                                <th>Left Times</th>
                            </tr>
                            </thead>
                            <?php if(count($dailyStatusList)):?>
                            <?php foreach($dailyStatusList as $row) :?>
                            <tr>
                                <td><?=$row['daytime']?></td>
                                <td><?=$row['arena']?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="2"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>
                <!--17.	보유하고 있는 퀘스트 내역 (진행 완료, 실패 현황 등)(保有的任务内容(已经完成 , 失败情况 等)-->
                <div class="my-box">
                    <div class="box-title">보유하고있는퀘스트내역(진행완료, 실패현황등)(保有的任务内容(已经完成,失败情况等)</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Day Time</th>
                                <th>Create Time</th>
                                <th>Task Name</th>
                                <th>Task ID</th>
                                <th>Sataus</th>
                            </tr>
                            </thead>
                            <?php if(count($taskList)):?>
                            <?php foreach($taskList as $row) :?>
                            <tr>
                                <td><?=$row['time']?></td>
                                <td><?=$row['create_time']?></td>
                                <td><?=$row['szQuest']?></td>
                                <td><?=$row['idQuest']?></td>
                                <td><?=$row['time']>0 ? 'Completed' : 'Uncomplete'?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="3"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>


                <!--19.가입한군단이름및군단정보（加入的军团名称 及 军团情报）-->
                <div class="my-box">
                    <div class="box-title">가입한군단이름및군단정보（加入的军团名称及军团情报）</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>tribe name</th>
                                <th>tribe level</th>
                                <th>boss level</th>
                                <th>tribe exp</th>
                                <th>creator</th>
                                <th>leader</th>
                            </tr>
                            </thead>
                            <?php if(count($tribeInfo)):?>
                            <?php foreach($tribeInfo as $row) :?>
                            <tr>
                                <td><?=$row['tribe_name']?></td>
                                <td><?=$row['tribe_level']?></td>
                                <td><?=$row['boss_level']?></td>
                                <td><?=$row['tribe_exp']?></td>
                                <td><?=$row['creator']?></td>
                                <td><?=$row['leader']?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="7"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>
                <!--20.	참장 현황 （站占领情况） -->
                <div class="my-box">
                    <div class="box-title">참장 현황 （站占领情况）</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Day Time</th>
                                <th>low cut will times </th>
                                <th>high cut will times</th>
                            </tr>
                            </thead>
                            <?php if(count($dailyStatusList)):?>
                            <?php foreach($dailyStatusList as $row) :?>
                            <tr>
                                <td><?=$row['daytime']?></td>
                                <td><?=$row['low_cut_will_times']? $row['low_cut_will_times']: 0 ?></td>
                                <td><?=$row['high_cut_will_times'] ?$row['high_cut_will_times'] :0?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="3"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>
                <!--21.	투기장 순위, 연승상태 （经济产排名，连胜状况） -->
                <div class="my-box">
                    <div class="box-title">투기장순위, 연승상태(经济产排名，连胜状况）</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Day Time</th>
                                <th>Arena Rank</th>
                                <th>Continue Win Times</th>
                            </tr>
                            </thead>
                            <?php if(count($dailyStatusList)):?>
                            <?php foreach($dailyStatusList as $row) :?>
                            <tr>
                                <td><?=$row['daytime']?></td>
                                <td><?=$row['arena_rank']?></td>
                                <td><?=$row['arena_rank_continue_win']?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="3"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>

                <!--22.	배틀 정보 （挑战情况 -->
                <div class="my-box">
                    <div class="box-title">배틀정보（挑战情况）</div>

                </div>

                <!--23.	각종 이벤트 (호송, 행운나무, 동탁, 퀴즈 등) 진행 현황 정보 （各种活动（护送，摇钱树，董卓，任务 等）进行现状情报）
-->
                <div class="my-box">
                    <div class="box-title">각종이벤트(호송,행운나무,동탁,퀴즈등)진행현황정보（各种活动（护送，摇钱树，董卓，任务 等）进行现状情报）</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Day Time</th>
                                <th>escort</th>
                                <th>escort battle</th>
                                <th>money tree</th>
                                <th>Active Time</th>
                                <th>Active Points</th>
                            </tr>
                            </thead>
                            <?php if(count($dailyStatusList)):?>
                            <?php foreach($dailyStatusList as $row) :?>
                            <tr>
                                <td><?=$row['daytime']?></td>
                                <td><?=$row['escort']?></td>
                                <td><?=$row['escort_battle']?></td>
                                <td><?=$row['money_tree']?></td>
                                <td><?=$active_time?></td>
                                <td><?=$active_points?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="6"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>
                <!--log begin-->
                <h3 class="title">유저진행한로그내역(玩家的LOG内容)</h3>
                <div class="my-box">
                    <div class="box-title">아이템 획득/구매/파기 내역 （获得道具/ 购买/ 卖出内容）</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Day Time</th>
                                <th>Create Time</th>
                                <th>Message</th>
                            </tr>
                            </thead>
                            <?php if(count($logs[1])):?>
                            <?php foreach($logs[1] as $row) :?>
                            <tr>
                                <td><?=date('Y-m-d H:i', strtotime('20'.$row['logtime']));?></td>
                                <td><?=$row['create_time']?></td>
                                <td><?=$row['message']?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="3"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>
                <!--2.	퀘스트 진행 시 획득한 아이템 내역 (进行任务的时候获得的道具内容) -->
                <div class="my-box">
                    <div class="box-title">퀘스트 진행 시 획득한 아이템 내역 (进行任务的时候获得的道具内容)</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Day Time</th>
                                <th>Create Time</th>
                                <th>Message</th>
                            </tr>
                            </thead>
                            <?php if(count($logs[2])):?>
                            <?php foreach($logs[2] as $row) :?>
                            <tr>
                                <td><?=date('Y-m-d H:i', strtotime('20'.$row['logtime']));?></td>
                                <td><?=$row['create_time']?></td>
                                <td><?=$row['message']?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="3"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>
                <!--3.	진행한 파티 내역 (누구와 어느 던전을 돌았는지) (组队内容(跟谁去了那个副本)) -->
                <div class="my-box">
                    <div class="box-title">진행한 파티 내역 (누구와 어느 던전을 돌았는지) (组队内容(跟谁去了那个副本))</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Day Time</th>
                                <th>Create Time</th>
                                <th>Message</th>
                            </tr>
                            </thead>
                            <?php if(count($logs[3])):?>
                            <?php foreach($logs[3] as $row) :?>
                            <tr>
                                <td><?=date('Y-m-d H:i', strtotime('20'.$row['logtime']));?></td>
                                <td><?=$row['create_time']?></td>
                                <td><?=$row['message']?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="3"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>
                <!--4.	소모한 전공 수치 (消耗的战功) -->
                <div class="my-box">
                    <div class="box-title">소모한 전공 수치 (消耗的战功)</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Day Time</th>
                                <th>Create Time</th>
                                <th>Message</th>
                            </tr>
                            </thead>
                            <?php if(count($logs[4])):?>
                            <?php foreach($logs[4] as $row) :?>
                            <tr>
                                <td><?=date('Y-m-d H:i', strtotime('20'.$row['logtime']));?></td>
                                <td><?=$row['create_time']?></td>
                                <td><?=$row['message']?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="3"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>
                <!--5.	소모한 명성 수치 (消耗的名声) -->
                <div class="my-box" style="display: none;">
                    <div class="box-title">소모한 명성 수치 (消耗的名声) </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Day Time</th>
                                <th>Create Time</th>
                                <th>Message</th>
                            </tr>
                            </thead>
                            <?php if(count($logs[5])):?>
                            <?php foreach($logs[5] as $row) :?>
                            <tr>
                                <td><?=date('Y-m-d H:i', strtotime('20'.$row['logtime']));?></td>
                                <td><?=$row['create_time']?></td>
                                <td><?=$row['message']?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="3"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>
                <!--8.	퀘스트를 진행한 내역 （进行任务的内容） -->
                <div class="my-box">
                    <div class="box-title">퀘스트를 진행한 내역 （进行任务的内容）</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Day Time</th>
                                <th>Create Time</th>
                                <th>Message</th>
                            </tr>
                            </thead>
                            <?php if(count($logs[6])):?>
                            <?php foreach($logs[6] as $row) :?>
                            <tr>
                                <td><?=date('Y-m-d H:i', strtotime('20'.$row['logtime']));?></td>
                                <td><?=$row['create_time']?></td>
                                <td><?=$row['message']?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="3"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>
                <!--9.	보물(진보각)을 구매한 내역 （宝物（珍宝阁）购买内容） -->
                <div class="my-box">
                    <div class="box-title">보물(진보각)을 구매한 내역 （宝物（珍宝阁）购买内容）</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Day Time</th>
                                <th>Create Time</th>
                                <th>Message</th>
                            </tr>
                            </thead>
                            <?php if(count($logs[7])):?>
                            <?php foreach($logs[7] as $row) :?>
                            <tr>
                                <td><?=date('Y-m-d H:i', strtotime('20'.$row['logtime']));?></td>
                                <td><?=$row['create_time']?></td>
                                <td><?=$row['message']?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="3"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>
                <!--10.	무장을 획득한 내역 （获得武将的内容） -->
                <div class="my-box">
                    <div class="box-title">무장을 획득한 내역 （获得武将的内容）</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Day Time</th>
                                <th>Create Time</th>
                                <th>Message</th>
                            </tr>
                            </thead>
                            <?php if(count($logs[8])):?>
                            <?php foreach($logs[8] as $row) :?>
                            <tr>
                                <td><?=date('Y-m-d H:i', strtotime('20'.$row['logtime']));?></td>
                                <td><?=$row['create_time']?></td>
                                <td><?=$row['message']?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="3"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>
                <!--11.	재물, 도전 등의 컨텐츠를 진행한 모든 내역 （赚钱，挑战 等 内容进行的所有内容） -->
                <div class="my-box">
                    <div class="box-title">재물, 도전 등의 컨텐츠를 진행한 모든 내역 （赚钱，挑战 等 内容进行的所有内容）</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Day Time</th>
                                <th>Create Time</th>
                                <th>Message</th>
                            </tr>
                            </thead>
                            <?php if(count($logs[9])):?>
                                <?php $f_9 = true;?>
                                <?php foreach($logs[9] as $row) :?>
                                <tr>
                                    <td><?=date('Y-m-d H:i', strtotime('20'.$row['logtime']));?></td>
                                    <td><?=$row['create_time']?></td>
                                    <td><?=$row['message']?></td>
                                </tr>
                                <?php endforeach;?>
                            <?php endif;?>
                            <?php if(count($logs[10])):?>
                                <?php $f_9 = true;?>
                                <?php foreach($logs[10] as $row) :?>
                                <tr>
                                    <td><?=date('Y-m-d H:i', strtotime('20'.$row['logtime']));?></td>
                                    <td><?=$row['create_time']?></td>
                                    <td><?=$row['message']?></td>
                                </tr>
                                <?php endforeach;?>
                            <?php endif;?>
                            <?php if(!$f_9):?>
                            <tr>
                                <td colspan="3"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>

                <!--12.	메일의 아이템을 획득한 내역 （获取邮件的道具内容） -->
                <div class="my-box">
                    <div class="box-title">메일의 아이템을 획득한 내역 （获取邮件的道具内容）</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Day Time</th>
                                <th>Create Time</th>
                                <th>Message</th>
                            </tr>
                            </thead>
                            <?php if(count($logs[11])):?>
                            <?php foreach($logs[11] as $row) :?>
                            <tr>
                                <td><?=date('Y-m-d H:i', strtotime('20'.$row['logtime']));?></td>
                                <td><?=$row['create_time']?></td>
                                <td><?=$row['message']?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="3"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>
                <!--13.메일을 삭제한 내역 (删除邮件的内容) -->
                <div class="my-box">
                    <div class="box-title">메일을삭제한내역(删除邮件的内容)</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Day Time</th>
                                <th>Create Time</th>
                                <th>Message</th>
                            </tr>
                            </thead>
                            <?php if(count($logs[12])):?>
                            <?php foreach($logs[12] as $row) :?>
                            <tr>
                                <td><?=date('Y-m-d H:i', strtotime('20'.$row['logtime']));?></td>
                                <td><?=$row['create_time']?></td>
                                <td><?=$row['message']?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="3"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>
                <!--14 .벤트 참여 및 결과 내역 （参与活动及结果内容） -->
                <div class="my-box">
                    <div class="box-title">벤트 참여 및 결과 내역 （参与活动及结果内容）</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Day Time</th>
                                <th>Create Time</th>
                                <th>Message</th>
                            </tr>
                            </thead>
                            <?php if(count($logs[13])):?>
                            <?php foreach($logs[13] as $row) :?>
                            <tr>
                                <td><?=date('Y-m-d H:i', strtotime('20'.$row['logtime']));?></td>
                                <td><?=$row['create_time']?></td>
                                <td><?=$row['message']?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="3"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>

                <!--15.	깃털 내역 （翅膀内容） 表 equip_made （翅膀强化) 条件 itempos = 100 -->
                <div class="my-box">
                    <div class="box-title">깃털 내역 （翅膀内容）</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Day Time</th>
                                <th>Item name</th>
                                <th>Item ID</th>
                                <th>Cost</th>
                                <th>Old Level</th>
                                <th>New Level</th>
                            </tr>
                            </thead>
                            <?php if(count($log_wing)):?>
                            <?php foreach($log_wing as $row) :?>
                            <tr>
                                <td><?=date('Y-m-d H:i', strtotime('20'.$row['logtime']));?></td>
                                <td><?=$row['itemname']?></td>
                                <td><?=$row['item_id']?></td>
                                <td><?=$row['emoney_cost']?></td>
                                <td><?=$row['old_level']?></td>
                                <td><?=$row['new_level']?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="5"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>

                <!--16.	원보를 이용하여 구매한 내역 （使用元宝购买的内容） -->
                <div class="my-box">
                    <div class="box-title">원보를이용하여구매한내역 （使用元宝购买的内容）</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Day Time</th>
                                <th>Item Type</th>
                                <th>Type</th>
                                <th>Emoney Cost</th>
                            </tr>
                            </thead>
                            <?php if(count($log_rmb)):?>
                            <?php foreach($log_rmb as $row) :?>
                            <tr>
                                <td><?=date('Y-m-d H:i', strtotime('20'.$row['daytime']));?></td>
                                <td><?=$row['itemtype']?></td>
                                <td><?=$row['type']?></td>
                                <td><?=$row['emoney']?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="4"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>

                <!--17.	획득한 은화 내역 （获得游戏币的内容） -->
                <div class="my-box">
                    <div class="box-title">획득한 은화 내역 （获得游戏币的内容)</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Day Time</th>
                                <th>Create Time</th>
                                <th>Message</th>
                            </tr>
                            </thead>
                            <?php if(count($logs[20])):?>
                            <?php foreach($logs[20] as $row) :?>
                            <tr>
                                <td><?=date('Y-m-d H:i', strtotime('20'.$row['logtime']));?></td>
                                <td><?=$row['create_time']?></td>
                                <td><?=$row['message']?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="3"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>

                <div class="my-box">
                    <div class="box-title">인 게임의 “보상” 목록에 있는 보상 현황(游戏内 “奖励” 目录里的奖励情况)</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Day Time</th>
                                <th>Create Time</th>
                                <th>Message</th>
                            </tr>
                            </thead>
                            <?php if(count($logs[18])):?>
                            <?php foreach($logs[18] as $row) :?>
                            <tr>
                                <td><?=date('Y-m-d H:i', strtotime('20'.$row['logtime']));?></td>
                                <td><?=$row['create_time']?></td>
                                <td><?=$row['message']?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="3"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>
                <!--18.	획득한 무료 원보 내역 （获得的免费元宝内容） -->
                <div class="my-box">
                    <div class="box-title">획득한 무료 원보 내역 （获得的免费元宝内容）</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Day Time</th>
                                <th>Create Time</th>
                                <th>Message</th>
                            </tr>
                            </thead>
                            <?php if(count($logs[19])):?>
                            <?php foreach($logs[19] as $row) :?>
                            <tr>
                                <td><?=date('Y-m-d H:i', strtotime('20'.$row['logtime']));?></td>
                                <td><?=$row['create_time']?></td>
                                <td><?=$row['message']?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="3"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>
                <!--19.	레벨업 내역 （升级内容介绍） -->
                <div class="my-box">
                    <div class="box-title">레벨업 내역 （升级内容介绍）</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Day Time</th>
                                <th>Create Time</th>
                                <th>Message</th>
                            </tr>
                            </thead>
                            <?php if(count($logs[14])):?>
                            <?php foreach($logs[14] as $row) :?>
                            <tr>
                                <td><?=date('Y-m-d H:i', strtotime('20'.$row['logtime']));?></td>
                                <td><?=$row['create_time']?></td>
                                <td><?=$row['message']?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="3"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>
                <!--20.	체력 소비 내역 （消耗体力内容 -->
                <div class="my-box">
                    <div class="box-title">체력소비내역(消耗体力内容)</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Day Time</th>
                                <th>Create Time</th>
                                <th>Message</th>
                            </tr>
                            </thead>
                            <?php if(count($logs[15])):?>
                            <?php foreach($logs[15] as $row) :?>
                            <tr>
                                <td><?=date('Y-m-d H:i', strtotime('20'.$row['logtime']));?></td>
                                <td><?=$row['create_time']?></td>
                                <td><?=$row['message']?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="3"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>
                 <!--21.	VIP 달성 내역 （获得VIP内容） -->
                <div class="my-box">
                    <div class="box-title">VIP 달성 내역 （获得VIP内容）</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Day Time</th>
                                <th>Create Time</th>
                                <th>Message</th>
                            </tr>
                            </thead>
                            <?php if(count($logs[16])):?>
                            <?php foreach($logs[16] as $row) :?>
                            <tr>
                                <td><?=date('Y-m-d H:i', strtotime('20'.$row['logtime']));?></td>
                                <td><?=$row['create_time']?></td>
                                <td><?=$row['message']?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="3"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>
                <!--22.	수련으로 얻은 경험치 내역 （通过修炼获得的经验值） -->
                <div class="my-box">
                    <div class="box-title">수련으로 얻은 경험치 내역 （通过修炼获得的经验值）</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Day Time</th>
                                <th>Create Time</th>
                                <th>Message</th>
                            </tr>
                            </thead>
                            <?php if(count($logs[17])):?>
                            <?php foreach($logs[17] as $row) :?>
                            <tr>
                                <td><?=date('Y-m-d H:i', strtotime('20'.$row['logtime']));?></td>
                                <td><?=$row['create_time']?></td>
                                <td><?=$row['message']?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="3"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>

                <div class="my-box">
                    <div class="box-title">Routine Work（日常任务）</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Day Time</th>
                                <th>Create Time</th>
                                <th>Message</th>
                            </tr>
                            </thead>
                            <?php if(count($logs[22])):?>
                            <?php foreach($logs[22] as $row) :?>
                            <tr>
                                <td><?=date('Y-m-d H:i', strtotime('20'.$row['logtime']));?></td>
                                <td><?=$row['create_time']?></td>
                                <td><?=$row['message']?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="3"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>
                <div class="my-box">
                    <div class="box-title">Tips（锦囊）</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Day Time</th>
                                <th>Create Time</th>
                                <th>Message</th>
                            </tr>
                            </thead>
                            <?php if(count($logs[23])):?>
                            <?php foreach($logs[23] as $row) :?>
                            <tr>
                                <td><?=date('Y-m-d H:i', strtotime('20'.$row['logtime']));?></td>
                                <td><?=$row['create_time']?></td>
                                <td><?=$row['message']?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="3"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>
                <!--23.	친구 선물 내역(누구한테 보내고 누구한테 받았는지) （好友礼物内容（给那个好友 给了什么 收到的内容是那个好友给的） -->
                <div class="my-box">
                    <div class="box-title">친구선물내역(누구한테 보내고 누구한테 받았는지)(好友礼物内容)</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Day Time</th>
                                <th>Gift Name</th>
                                <th>Sender</th>
                                <th>User Id</th>
                            </tr>
                            </thead>
                            <?php if(count($log_kfa)):?>
                            <?php foreach($log_kfa as $row) :?>
                            <tr>
                                <td><?=date('Y-m-d H:i', strtotime('20'.$row['logtime']));?></td>
                                <td><?=$row['awarditem_name']?></td>
                                <td><?=$row['sender']?></td>
                                <td><?=$row['userid']?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                                <td colspan="4"><?=$lang['no_data']?></td>
                            </tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>
                <div class="print">
                    <button type="button" onclick="window.print();"> Print ALL </button>
                </div>
                <!--log end-->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>