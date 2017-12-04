<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title class="I18N">headerTitle</title>
    <link rel="stylesheet" href="./src/css/base.css">
    <link rel="stylesheet" href="./src/css/jqpagination.css">
    <!--[if lt IE 9]>
    <script type="text/javascript" src="./src/components/html5shiv/dist/html5shiv.min.js"></script>
    <script type="text/javascript" src="./src/components/respond/dest/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="container">
    <div class="header">
        <div class="box-title">
            <img src="./src/img/logo.png" alt="POCKET" class="logo">
            <img src="./src/img/logo(1).png" class="logo">
            <p class="title I18N">headerTitle</p>
        </div>
    </div>
    <div class="content">
        <div class="content-left">
            <div class="box-menu">
                <h1>
                    <img src="./src/img/icon-chargetype.png">
                    <p class="I18N">public.chooseType</p>
                </h1>
                <ul class="recharge-type">
                    <li class="I18N" onclick="javascript:void(0);" style="display: none;">public.bankAuto</li>
                    <li class="I18N" onclick="javascript:void(0);" style="display: none;">public.bankHuman</li>
                    <li class="active I18N" onclick="javascript:window.location.href='#/card'">public.card</li>
                    <li class="I18N" onclick="javascript:window.location.href='#/bankonline'">public.bankOnline</li>
                    <li class="I18N" onclick="javascript:void(0);" style="display: none;">public.sms</li>
                </ul>
            </div>

<div class="box-card  page">
                <input type="hidden" name="extern" value="<?=isset($_REQUEST['extern']) ? $_REQUEST['extern'] : ''; ?>">
                <div class="title-line">
                    <div><img src="./src/img/icon-bank.png"></div>
                    <div><p class="I18N">public.card</p></div>
                </div>

                <div class="box-select">
                    <div class="zoneId">
                        <h4 class="I18N">public.serverName</h3>
                        <button class="sel-zoneId">
                            <span class="target"><?=isset($_REQUEST['server_name']) ? $_REQUEST['server_name'] : ''; ?></span>
                        </button>
                    </div>

                    <div class="playId">
                        <h4 class="I18N">public.playerName</h3>
                        <button class="sel-playId">
                            <span class="target"><?=isset($_REQUEST['player_name']) ? $_REQUEST['player_name'] : ''; ?></span>
                        </button>
                    </div>
                </div>

                <div class="providers">
                    <h3 class="I18N">public.chooseServer</h3>
                    <ul class="list-provider">
                        <li class="">
                            <img src="./src/img/provider-1.png" alt="" data-id="0" data-exinfo="mobifone">
                        </li>
                        <li>
                            <img src="./src/img/provider-2.png" alt="" data-id="1" data-exinfo="vinaphone">
                        </li>
                        <li class="active">
                            <img src="./src/img/provider-3.png" alt="" data-id="2" data-exinfo="viettel">
                        </li>

                        <!--<li>-->
                        <!--<img src="./src/img/provider-4.png" alt="" data-id="3">-->
                        <!--</li>-->
                        <!--<li>-->
                        <!--<img src="./src/img/provider-5.png" alt="" data-id="4">-->
                        <!--</li>-->
                        <!--<li>-->
                        <!--<img src="./src/img/provider-6.png" alt="" data-id="5">-->
                        <!--</li>-->
                    </ul>
                </div>
                <div class="icon-tab">
                    <div>
                        <img src="./src/img/icon-question.png">
                    </div>
                    <div>
                        <a href="javascript:void(0)" class="scale">*<span class="I18N">public.scale</span></a>
                    </div>
                </div>
                <div class="line-tab">
                    <div><hr/></div>
                    <div class="tab-title">
                        <p class="I18N">others.cardCharge</p>
                    </div>
                    <div><hr/></div>
                </div>
                <div class="box-card-charge">
                    <input type="text" placeholder="Seri thẻ" class="card-number">
                    <input type="text" placeholder="Mã thẻ" class="card-password">
                </div>

                <button class="btn-card-charge I18N">public.charge</button>
            </div>

            <div class="bank-online page">
                <div class="title-line">
                    <div><img src="./src/img/icon-atm.png"></div>
                    <div><p class="I18N">public.bankOnline</p></div>
                </div>

                <div class="box-select">
                    <div class="zoneId">
                    <h4 class="I18N">public.serverName</h3>
                        <button class="sel-zoneId">
                            <span class="target"><?=isset($_REQUEST['server_name']) ? $_REQUEST['server_name'] : ''; ?></span>
                        </button>
                    </div>

                    <div class="playId">
                    <h4 class="I18N">public.playerName</h3>
                        <button class="sel-playId">
                            <span class="target"><?=isset($_REQUEST['player_name']) ? $_REQUEST['player_name'] : ''; ?></span>
                        </button>
                    </div>
                </div>

                <div class="banks">
                    <div class="charge-list">
                        <h3 class="I18N">public.chooseServer</h3>
                        <ul class="list-bank">
                            <li class="active">
                                <img src="./src/img/bank-1.png" data-id="30" data-exinfo="0729">
                            </li>
                            <li>
                                <img src="./src/img/bank-2.png" data-id="34" data-exinfo="0729">
                            </li>
                            <li>
                                <img src="./src/img/bank-3.png" data-id="36" data-exinfo="0729">
                            </li>
                            <li>
                                <img src="./src/img/bank-4.png" data-id="37" data-exinfo="0729">
                            </li>
                            <li>
                                <img src="./src/img/bank-5.png" data-id="38" data-exinfo="0729">
                            </li>
                            <li>
                                <img src="./src/img/bank-6.png" data-id="39" data-exinfo="0729">
                            </li>
                            <li>
                                <img src="./src/img/bank-7.png" data-id="41" data-exinfo="0729">
                            </li>
                            <li>
                                <img src="./src/img/bank-8.png" data-id="43" data-exinfo="0729">
                            </li>
                            <li>
                                <img src="./src/img/bank-9.png" data-id="44" data-exinfo="0729">
                            </li>
                            <li>
                                <img src="./src/img/bank-10.png" data-id="45" data-exinfo="0729">
                            </li>
                            <li>
                                <img src="./src/img/bank-11.png" data-id="50" data-exinfo="0729">
                            </li>
                            <li>
                                <img src="./src/img/bank-12.png" data-id="51" data-exinfo="0729">
                            </li>
                        </ul>
                    </div>

                    <div class="charge-amount">
                        <h3 class="I18N">others.chooseCount</h3>
                        <ul class="amount-tab">
                            <li class="active">10k-50k</li>
                            <li>100k-500k</li>
                            <li>1000k-5000k</li>
                        </ul>
                        <div>
                            <span class="triangle tab1">
                                <span class="triangle-inner"></span>
                            </span>
                        </div>

                        <ul class="list-amount-tab">
                            <li class="active">
<!--                                <div class="amount active" data-id="10000">-->
<!--                                    <label><div class="icon-check"></div><input type="radio" name="amount-1">10</label>k-->
<!--                                </div>-->
                                <div class="amount active" data-id="20000">
                                    <label><div class="icon-check"></div><input type="radio" name="amount-1">20</label>k
                                </div>
                                <div class="amount" data-id="30000">
                                    <label><div class="icon-check"></div><input type="radio" name="amount-1">30</label>k
                                </div>
                                <!--<div class="amount">-->
                                <!--<label><div class="icon-check"></div><input type="radio" name="amount-1">40k</label>-->
                                <!--</div>-->
                                <div class="amount" data-id="50000">
                                    <label><div class="icon-check"></div><input type="radio" name="amount-1">50</label>k
                                </div>
<!--                                <div class="amount" data-id="60000">-->
<!--                                    <label><div class="icon-check"></div><input type="radio" name="amount-1">60</label>k-->
<!--                                </div>-->
                            </li>

                            <li>
                                <div class="amount active" data-id="100000">
                                    <label><div class="icon-check"></div><input type="radio" name="amount-2">100</label>k
                                </div>
                                <div class="amount" data-id="200000">
                                    <label><div class="icon-check"></div><input type="radio" name="amount-2">200</label>k
                                </div>
                                <div class="amount" data-id="300000">
                                    <label><div class="icon-check"></div><input type="radio" name="amount-2">300</label>k
                                </div>
                                <!--<div class="amount">-->
                                <!--<label><div class="icon-check"></div><input type="radio" name="amount-2">400k</label>-->
                                <!--</div>-->
                                <div class="amount" data-id="500000">
                                    <label><div class="icon-check"></div><input type="radio" name="amount-2">500</label>k
                                </div>
                            </li>

                            <li>
                                <div class="amount active" data-id="1000000">
                                    <label><div class="icon-check"></div><input type="radio" name="amount-3">1000</label>k
                                </div>
                                <div class="amount" data-id="2000000">
                                    <label><div class="icon-check"></div><input type="radio" name="amount-3">2000</label>k
                                </div>
<!--                                <div class="amount" data-id="3000000">-->
<!--                                    <label><div class="icon-check"></div><input type="radio" name="amount-3">3000</label>k-->
<!--                                </div>-->
                                <!--<div class="amount">-->
                                <!--<label><div class="icon-check"></div><input type="radio" name="amount-3">4000k</label>-->
                                <!--</div>-->
                                <div class="amount" data-id="5000000">
                                    <label><div class="icon-check"></div><input type="radio" name="amount-3">5000</label>k
                                </div>
                            </li>
                        </ul>

                        <!--<h3 class="I18N">others.upperLimit</h3>-->

                        <p class="charge-result">
<!--                            <span class="I18N">others.sureCharge</span><span>10</span>k，-->
<!--                            <span class="I18N">others.chargeValue</span><span>50 </span>Xu-->
                            <span class="I18N">others.sureCharge</span>
                            (<span>20</span>K)
                            <span class="I18N">others.chargeValue</span>
                            (<span>80</span>) Ruby
                            <span class="I18N">others.muaValue</span>
                            (<span>8</span>) điểm
                        </p>

                        <button class="btn-bank-charge I18N">public.charge</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="win-wrap"></div>
<div class="win-alert">
    <div class="alert-exchange win">
        <button class="btn-close"></button>
        <h3 class="alert-title I18N">public.scale</h3>
        <div class="exchange">

        </div>

        <button class="btn-return I18N">public.return</button>
    </div>

    <div class="alert-result win">
        <button class="btn-close"></button>
        <h3 class="alert-title I18N">public.charge</h3>
        <div class="success">
            <p class="I18N">public.chargeSuccess</p>
        </div>

        <button class="btn-return I18N">public.return</button>
    </div>

    <div class="alert-rechanging win">
        <button class="btn-close"></button>
        <h3 class="alert-title I18N">public.charging</h3>
        <div class="success">
            <p class="I18N">public.chargingInfo</p>
        </div>

        <!--<button class="btn-contact I18N">public.contactService</button>-->
        <button class="btn-success I18N">public.chargeSuccess</button>
    </div>

    <div class="box-loading win">
        <h3 class="alert-title I18N">public.charge</h3>

        <div class="loader">
            <div class="loader-inner ball-triangle-path">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
        <p>loading...</p>
    </div>

    <div class="box-qrcode win">
        <button class="btn-close"></button>
        <h3 class="alert-title I18N">public.charging</h3>
        <p> Dùng điện thoại quét mã QR và thanh toán SMS
            <br/>
            Cùng 1 mã QR chỉ được giao dịch 1 lần, tạo mới mã QR nếu muốn thanh toán tiếp
        </p>
        <iframe class="qrcode"></iframe>
    </div>
</div>



<script src="./src/js/common.js"></script>
<script src="./src/js/lib/jquery.jqpagination.min.js"></script>
<script src="./src/js/kdyg3ds.js"></script>
<script src="./src/js/index.js"></script>
<script src="./src/js/require.js"></script>
<script type="text/javascript">
//网络银行
function chargeForwardWeb(){
    var data = getDataOnline();
    //var tempwindow=window.open();
    $.ajax({
        type: "POST",
        url: config.url + "/bank.php",
        dataType: 'json',
        async:false,
        data: data,
        beforeSend: function(){
            $(".win-wrap").show();
            $(".win-alert").show();
            $(".win.active").removeClass("active");
            $(".box-loading").addClass("active");
        },
        success: function(result){
            console.log(JSON.stringify(result));
            if(result.code == 200){
                $(".win-wrap").show();
                $(".win-alert").show();
                $(".win.active").removeClass("active");
                $(".alert-rechanging").addClass("active");
                //tempwindow.location=result.data.paymentUrl;
                location.href=result.data.paymentUrl;
            }else{
                $(".win-wrap").show();
                $(".win-alert").show();
                $(".win.active").removeClass("active");
                $(".alert-result .success p").text(i18N.get("status."+result.code));
                $(".alert-result").addClass("active");
                //tempwindow.close();
            }
        },
        error: function(err){
            console.log(JSON.stringify(err));
        }
    })
}
</script>
</body>
</html>