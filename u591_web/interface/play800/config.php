<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2016/12/9
 * Time: 上午11:24
 */
define('ROOT_PATH', str_replace('interface/play800/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH.'inc/function.php';
$fenbao_arr = array(
		'817001'=>'kdyg_ios2',
		'818001'=>'kdyg_ios3',
		'839001'=>'kdyg_ios22',
		'859008'=>'kdyg_ios63',
		'859014'=>'kdyg_ios68',
		'859015'=>'kdyg_ios72',
		'859022'=>'kdyg_ios77',
);
$menu_arr = array(
		['id'=>1,'name'=>'60钻石','price'=>'6','desc'=>'60钻石'],
		['id'=>2,'name'=>'25元月卡','price'=>'25','desc'=>'25元月卡'],
		['id'=>3,'name'=>'300钻石','price'=>'30','desc'=>'300钻石'],
		//['id'=>4,'name'=>'980钻石','price'=>'98','desc'=>'980钻石'],
		['id'=>5,'name'=>'1280钻石','price'=>'128','desc'=>'1280钻石'],
		['id'=>6,'name'=>'3280钻石','price'=>'328','desc'=>'3280钻石'],
		['id'=>7,'name'=>'6480钻石','price'=>'648','desc'=>'6480钻石'],
		['id'=>8,'name'=>'32800钻石','price'=>'3280','desc'=>'32800钻石'],
);
$key_arr = array(
    8=>array(
        'ios'=>array(
            'gid'       =>'9101451504011581',
            'site'      =>'kdyg_ios',
            'key'       =>'56fe1b1a595582cdcb8c4eb2d81343f0',
        ),
        'ios2'=>array(
            'gid'       =>'9100771509551581',
            'site'      =>'kdyg_ios1',
            'key'       =>'688c31808bfc088f6447cae31595ab66',
        ),
        'ios3'=>array(
            'gid'       =>'9100771509611581',
            'site'      =>'kdyg_ios2',
            'key'       =>'6651005f87355957720e2159ada21e82',
        ),
        'ios4'=>array(
            'gid'       =>'9100771509581581',
            'site'      =>'kdyg_ios3',
            'key'       =>'0443e7f7e05fce77f54cf437ea52de6f',
        ),
        'ios5'=>array(
            'gid'       =>'9100771509591581',
            'site'      =>'kdyg_ios4',
            'key'       =>'52e9bdc285e4e06ac13b28701c52f6ce',
        ),
        'ios6'=>array(
            'gid'       =>'9100771809551581',
            'site'      =>'kdyg_ios5',
            'key'       =>'db58ba8aea02d93cd139b185ccae4cde',
        ),
        'ios7'=>array(
            'gid'       =>'9100771808971581',
            'site'      =>'kdyg_ios6',
            'key'       =>'1a15d56f417c1920afda546699827205',
        ),
        'ios8'=>array(
            'gid'       =>'9100771509601581',
            'site'      =>'kdyg_ios7',
            'key'       =>'f0ecc5c15782f0edd89f996e9f10e7a1',
        ),
        'ios9'=>array(
            'gid'       =>'9100771509821581',
            'site'      =>'kdyg_ios8',
            'key'       =>'ffcd18c64ad47014103d83a036ef589b',
        ),
        'ios10'=>array(
            'gid'       =>'9100771509891581',
            'site'      =>'kdyg_ios9',
            'key'       =>'8ffffd00db5280b9b83ccfca4288594d',
        ),
        'ios11'=>array(
            'gid'       =>'9100771509601581',
            'site'      =>'kdyg_ios10',
            'key'       =>'63d351a2f834000cb93c074bd2ca7bb6',
        ),

        'ios12'=>array(
            'gid'       =>'9100771509601581',
            'site'      =>'kdyg_ios11',
            'key'       =>'aa9f2eee15d7b8a49b420f7c364254c1',
        ),
        'ios13'=>array(
            'gid'       =>'9100771509601581',
            'site'      =>'kdyg_ios12',
            'key'       =>'f031cb86ff8bb207e7806f8fc0b52d98',
        ),
        'ios14'=>array(
            'gid'       =>'9100771509601581',
            'site'      =>'kdyg_ios13',
            'key'       =>'6003a86cca110055c45572e37758aba8',
        ),
        'ios15'=>array(
            'gid'       =>'9100771509601581',
            'site'      =>'kdyg_ios14',
            'key'       =>'200f6ebda76a0fe8db80823957a154cc',
        ),
        'ios16'=>array(
            'gid'       =>'9100771509601581',
            'site'      =>'kdyg_ios15',
            'key'       =>'eec2dfd8a385b644302200033e82dfb3',
        ),
        'ios17'=>array(
            'gid'       =>'9100771511491581',
            'site'      =>'kdyg_ios16',
            'key'       =>'910740ecae55979e6dd5b741b504c1a1',
        ),
        'ios18'=>array(
            'gid'       =>'9100771509601581',
            'site'      =>'kdyg_ios17',
            'key'       =>'00c0e1f1b13dc3655bb888a642ee4fdd',
        ),
        'ios19'=>array(
            'gid'       =>'9100771509601581',
            'site'      =>'kdyg_ios18',
            'key'       =>'d75a46dc20269acadd820578668e813c',
        ),
        'ios20'=>array(
            'gid'       =>'9100771509601581',
            'site'      =>'kdyg_ios19',
            'key'       =>'55e0d3187fa172d789332d0984c5dc22',
        ),
        'ios21'=>array(
            'gid'       =>'9100771509551581',
            'site'      =>'kdyg_ios20',
            'key'       =>'cb99c171f72b76b441921738a3823c21',
        ),

        'ios22'=>array(
            'gid'       =>'9100771509551581',
            'site'      =>'kdyg_ios21',
            'key'       =>'9a72193b78ac2840e5251ddd1edb7f73',
        ),
        'ios23'=>array(
            'gid'       =>'9100771509551581',
            'site'      =>'kdyg_ios22',
            'key'       =>'1e92a66e34b1f3e8a006c9d16bd2af81',
        ),
        'ios24'=>array(
            'gid'       =>'9100771509551581',
            'site'      =>'kdyg_ios23',
            'key'       =>'80815ab55cde6998c3a955e1eae6e320',
        ),
        'ios25'=>array(
            'gid'       =>'9100771509551581',
            'site'      =>'kdyg_ios24',
            'key'       =>'7fd61da4bd4a6a4c4da1fe7d7a2de2a9',
        ),
        'ios26'=>array(
            'gid'       =>'9100771509551581',
            'site'      =>'kdyg_ios25',
            'key'       =>'394b2a709824488da9ea1b566289fac9',
        ),
        'ios27'=>array(
            'gid'       =>'9100771509551581',
            'site'      =>'kdyg_ios26',
            'key'       =>'ba15e54465144e3680357107be90951d',
        ),
        'ios28'=>array(
            'gid'       =>'9100771509551581',
            'site'      =>'kdyg_ios27',
            'key'       =>'e9377cda32d424cc5b951016d2f88dc4',
        ),
        'ios29'=>array(
            'gid'       =>'9100771509551581',
            'site'      =>'kdyg_ios28',
            'key'       =>'e0c5952ccacc86f159c4f9ae0a2b54cd',
        ),
        'ios30'=>array(
            'gid'       =>'9100771509551581',
            'site'      =>'kdyg_ios29',
            'key'       =>'aada732311556ca2548723e51b306e86',
        ),
        'ios31'=>array(
            'gid'       =>'9100771509551581',
            'site'      =>'kdyg_ios30',
            'key'       =>'1075d13fc248da4e00c02f5b12de2a46',
        ),
        'ios46'=>array(
            'gid'       =>'9100771517561581',
            'site'      =>'kdyg_ios45',
            'key'       =>'585713d294ff41e83617db70a39f1d67',
        ),
        'ios47'=>array(
            'gid'       =>'9100771517571581',
            'site'      =>'kdyg_ios46',
            'key'       =>'ffcf2f6df9d2e1f1b4de30f6b2172394',
        ),
        //
        'ios35'=>array(
            'gid'       =>'9100771515201581',
            'site'      =>'kdyg_ios34',
            'key'       =>'41c1fe33ae22b781bcc399d0fc1113f2',
        ),
        'ios36'=>array(
            'gid'       =>'9100771515211581',
            'site'      =>'kdyg_ios35',
            'key'       =>'f825432de4ca29377433227c5932a4c3',
        ),
        'ios37'=>array(
            'gid'       =>'9100771515211581',
            'site'      =>'kdyg_ios36',
            'key'       =>'6a147709494071c528ea9e1bfba220f7',
        ),
        'ios38'=>array(
            'gid'       =>'9100771515211581',
            'site'      =>'kdyg_ios37',
            'key'       =>'8f7d4f34b859b55f2a47772ab5c3afce',
        ),
        'ios39'=>array(
            'gid'       =>'9100771515211581',
            'site'      =>'kdyg_ios38',
            'key'       =>'a43ad5e0b99d4e9ca43f169b81c6c15b',
        ),
        'ios40'=>array(
            'gid'       =>'9100771515211581',
            'site'      =>'kdyg_ios39',
            'key'       =>'7b7a14b8d8f1a80327e9b493f7086300',
        ),
        'ios41'=>array(
            'gid'       =>'9100771515211581',
            'site'      =>'kdyg_ios40',
            'key'       =>'ce6024cd4fa442b60f66740181092c14',
        ),
        'ios42'=>array(
            'gid'       =>'9100771515211581',
            'site'      =>'kdyg_ios41',
            'key'       =>'188ab43fdbe47bba7571d0d9638ffbb2',
        ),
        'ios43'=>array(
            'gid'       =>'9100771517531581',
            'site'      =>'kdyg_ios42',
            'key'       =>'a6169d15fee36f220f148d0bbca1205a',
        ),
        'ios44'=>array(
            'gid'       =>'9100771517541581',
            'site'      =>'kdyg_ios43',
            'key'       =>'ffc6a513176d6b05a9863189b47b5947',
        ),
        'ios45'=>array(
            'gid'       =>'9100771517551581',
            'site'      =>'kdyg_ios44',
            'key'       =>'b3feb9857fce34b836034fe50f77a0af',
        ),
        'ios48'=>array(
            'gid'       =>'9100771517691581',
            'site'      =>'kdyg_ios47',
            'key'       =>'d9ace79ceda0936afa3085ea8c6080ed',
        ),
        'ios49'=>array(
            'gid'       =>'9100771517701581',
            'site'      =>'kdyg_ios48',
            'key'       =>'1b0da0f66edf5b175fc8837478298cd1',
        ),
        'ios59'=>array(
            'gid'       =>'9100771518231581',
            'site'      =>'kdyg_ios58',
            'key'       =>'971598fa7fc47320a1c4e094d7867381',
        ),
        'ios60'=>array(
            'gid'       =>'9100771518241581',
            'site'      =>'kdyg_ios59',
            'key'       =>'02f16eba603193b6a72938fa5abaf3f8',
        ),
        'ios61'=>array(
            'gid'       =>'9100771518251581',
            'site'      =>'kdyg_ios60',
            'key'       =>'235cbbb168c6c871d4e621930cbb1984',
        ),
        'ios64'=>array(
            'gid'       =>'9100771519391581',
            'site'      =>'kdyg_ios63',
            'key'       =>'aa60136b9dc9acbd644a31a830510e9b',
        ),
        'ios65'=>array(
            'gid'       =>'9100771519401581',
            'site'      =>'kdyg_ios64',
            'key'       =>'9ac4c8f3d59c6f8326a86fbd636cc953',
        ),
        'ios66'=>array(
            'gid'       =>'9100771519411581',
            'site'      =>'kdyg_ios65',
            'key'       =>'ced53c32a0efa0d2db227a01d8c81bfd',
        ),
        'ios67'=>array(
            'gid'       =>'9100771519421581',
            'site'      =>'kdyg_ios66',
            'key'       =>'df41120dcb464964dbe58c66b915c521',
        ),
        'ios68'=>array(
            'gid'       =>'9100771519431581',
            'site'      =>'kdyg_ios67',
            'key'       =>'5c77f93f41c807b5371310b1851f4c1d',
        ),
        'ios69'=>array(
            'gid'       =>'9100771519441581',
            'site'      =>'kdyg_ios68',
            'key'       =>'8e9fe3875420918772688c49922f30d5',
        ),
        'ios70'=>array(
            'gid'       =>'9100771519451581',
            'site'      =>'kdyg_ios69',
            'key'       =>'503c50fe313b2b887d2bfba9bd84935e',
        ),
        'ios71'=>array(
            'gid'       =>'9100771519461581',
            'site'      =>'kdyg_ios70',
            'key'       =>'accf095c0f44ecf04e31d73867e2180a',
        ),
        'ios72'=>array(
            'gid'       =>'9100771520721581',
            'site'      =>'kdyg_ios71',
            'key'       =>'1f18e9c26226f39f343fd27b8dd9f6c0',
        ),
        'ios73'=>array(
            'gid'       =>'9100771520531581',
            'site'      =>'kdyg_ios72',
            'key'       =>'3f9a68a6a436d26b5c8cb734973cf513',
        ),
        'ios74'=>array(
            'gid'       =>'9100771520741581',
            'site'      =>'kdyg_ios73',
            'key'       =>'207be07daf00eaf6970d871ca4d0a30d',
        ),
        'ios75'=>array(
            'gid'       =>'9100771520751581',
            'site'      =>'kdyg_ios74',
            'key'       =>'db6788340616b2929853d6542fb87a9c',
        ),
        'ios76'=>array(
            'gid'       =>'9100771520761581',
            'site'      =>'kdyg_ios75',
            'key'       =>'9a5f6fab4b498eaa4fa4d9a9d0c5967b',
        ),
        'ios77'=>array(
            'gid'       =>'9100771520771581',
            'site'      =>'kdyg_ios76',
            'key'       =>'06f3b4ba7b18f5e86017dc1ab1441784',
        ),
        'ios78'=>array(
            'gid'       =>'9100771520781581',
            'site'      =>'kdyg_ios77',
            'key'       =>'a61d49875c19c6610f840e3d59c19edb',
        ),
        'ios79'=>array(
            'gid'       =>'9100771520791581',
            'site'      =>'kdyg_ios78',
            'key'       =>'7fa582e55d5e33e3a5b7c169506297c8',
        ),
        'ios80'=>array(
            'gid'       =>'9100771520801581',
            'site'      =>'kdyg_ios79',
            'key'       =>'10c361ba9d19f5eccd5278faf7c2d632',
        ),
        'ios81'=>array(
            'gid'       =>'9100771520811581',
            'site'      =>'kdyg_ios80',
            'key'       =>'e4a613ac08bd667a0a976668ae446d83',
        ),
        'ios82'=>array(
            'gid'       =>'9101451324801581',
            'site'      =>'kdyg_ios81',
            'key'       =>'2e2865b5d2dcd5b6da559268c13b9004',
        ),
        'ios83'=>array(
            'gid'       =>'9101451324811581',
            'site'      =>'kdyg_ios82',
            'key'       =>'406fa9db3c43b4d873e78aba3a6eda64',
        ),
        'ios84'=>array(
            'gid'       =>'9101451324821581',
            'site'      =>'kdyg_ios83',
            'key'       =>'eae80b6584bd0c8ed9daba74936c2a7e',
        ),

        'ios85'=>array(
            'gid'       =>'9100771525091581',
            'site'      =>'kdyg_ios84',
            'key'       =>'a085843dbdb5ab260eab99b30adc6767',
        ),
        'ios86'=>array(
            'gid'       =>'9100771525081581',
            'site'      =>'kdyg_ios85',
            'key'       =>'1ac7fbc9206381687031554de35ae79e',
        ),
        'ios87'=>array(
            'gid'       =>'9100771525071581',
            'site'      =>'kdyg_ios86',
            'key'       =>'9123e549eaa8b37dd0f482e0dcfe8890',
        ),
    	'ios88'=>array(
    			'gid'       =>'9100771525061581',
    			'site'      =>'kdyg_ios87',
    			'key'       =>'a569404a09da8c975cb39aa464cd5b80',
    	),
    	'ios89'=>array(
    			'gid'       =>'9100771525051581',
    			'site'      =>'kdyg_ios88',
    			'key'       =>'67db8fdff55f061e07c07e97b0683734',
    	),
    	'ios90'=>array(
    				'gid'       =>'9100771525041581',
    				'site'      =>'kdyg_ios89',
    				'key'       =>'fcc94be6f9b0d5707e485d3b400eb0a8',
    	),
    		'ios91'=>array(
    				'gid'       =>'9100771525121581',
    				'site'      =>'kdyg_ios90',
    				'key'       =>'535289897b36111832cb273af0eb60b6',
    		),
    	'ios92'=>array(
    			'gid'       =>'9100771527961581',
    			'site'      =>'kdyg_ios91',
    			'key'       =>'4c00f905288daccb0ec5a9d3e3a2577c',
    	),
    		'ios93'=>array(
    				'gid'       =>'9100771527971581',
    				'site'      =>'kdyg_ios92',
    				'key'       =>'f34a44fba320b6ba543fbff71eae515b',
    		),
    		'ios94'=>array(
    				'gid'       =>'9100771527981581',
    				'site'      =>'kdyg_ios93',
    				'key'       =>'c6485a3a5ddac008d33963b01935aa75',
    		),
    		'ios95'=>array(
    				'gid'       =>'9100771527991581',
    				'site'      =>'kdyg_ios94',
    				'key'       =>'fa44f7be1fcd28e4dc3c4949a8dafdae',
    		),
    		'ios97'=>array(
    				'gid'       =>'9100771528011581',
    				'site'      =>'kdyg_ios96',
    				'key'       =>'a27e606230c50a78d15e432f122274de',
    		),
    		'ios98'=>array(
    				'gid'       =>'9100771528021581',
    				'site'      =>'kdyg_ios97',
    				'key'       =>'80a08e8d3fe34ee205602b902fcfbe17',
    		),
    		'ios100'=>array(
    				'gid'       =>'9100771528041581',
    				'site'      =>'kdyg_ios99',
    				'key'       =>'0c2f3bebbf3602f1c5521277fcb090a3',
    		),
    		'ios101'=>array(
    				'gid'       =>'9100771528051581',
    				'site'      =>'kdyg_ios100',
    				'key'       =>'8ec20c9ffd26d6ac88e53fc507350ca1',
    		),
    		'ios104'=>array(
    				'gid'       =>'9100771536511581',
    				'site'      =>'kdyg_ios103',
    				'key'       =>'a75119beca2e289ea13cd43d284e9be6',
    		),
    		'ios105'=>array(
    				'gid'       =>'9100771536521581',
    				'site'      =>'kdyg_ios104',
    				'key'       =>'485145801ca6ab887ff5d72f2306d04c',
    		),
    		'ios106'=>array(
    				'gid'       =>'9100771536531581',
    				'site'      =>'kdyg_ios105',
    				'key'       =>'063b0633abc3eda6338255e33e5d8b47',
    		),
    		'ios107'=>array(
    				'gid'       =>'9100771542481581',
    				'site'      =>'kdyg_ios106',
    				'key'       =>'111cbd27d4653c4acb387a7b06590761',
    		),
    		'ios110'=>array(
    				'gid'       =>'9100771542511581',
    				'site'      =>'kdyg_ios109',
    				'key'       =>'4abd537e5c0989d0bc32d01bb16cb981',
    		),
    		'ios112'=>array(
    				'gid'       =>'9100771550121581',
    				'site'      =>'kdyg_ios111',
    				'key'       =>'c5757f7e1da4df4226e34d400b0752a3',
    		),
    		'ios113'=>array(
    				'gid'       =>'9100771550241581',
    				'site'      =>'kdyg_ios112',
    				'key'       =>'44b9948a75d7f6ddaf5b7187f2375297',
    		),
    		'ios114'=>array(
    				'gid'       =>'9100771552511581',
    				'site'      =>'kdyg_ios113',
    				'key'       =>'dd7a1a9ec6c111ea81ae5bdd1d0f85ff',
    		),
    		'ios115'=>array(
    				'gid'       =>'9100771554101581',
    				'site'      =>'kdyg_ios114',
    				'key'       =>'0d4255eef9635848019c68f852c71a99',
    		),
    		'ios116'=>array(
    				'gid'       =>'9100771556751581',
    				'site'      =>'kdyg_ios115',
    				'key'       =>'adee23d872902fba6523056bf44c4ec5',
    		),
    		'ios117'=>array(
    				'gid'       =>'9100771556761581',
    				'site'      =>'kdyg_ios116',
    				'key'       =>'de9f348f2a10346a46e5e365a1dc260d',
    		),
    		'ios118'=>array(
    				'gid'       =>'9100771557121581',
    				'site'      =>'kdyg_ios117',
    				'key'       =>'086fb4a08fe7b373cbb14281a073ecb4',
    		),
    		'ios119'=>array(
    				'gid'       =>'9100771559901581',
    				'site'      =>'kdyg_ios118',
    				'key'       =>'7cee0b5549040cfba7d3e5db4469232f',
    		),
    		'ios120'=>array(
    				'gid'       =>'9100771563541581',
    				'site'      =>'kdyg_ios119',
    				'key'       =>'9a0350df6495144779f7eb54d89036d9',
    		),
    		'ios121'=>array(
    				'gid'       =>'9100771563551581',
    				'site'      =>'kdyg_ios120',
    				'key'       =>'cbc6e4a8e5bb95f7fe1e2be9f323f9a4',
    		),
    ),
);