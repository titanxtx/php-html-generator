<?php
class htmlgen{

    private static function isAssoc(array $arr)
    {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
    private static function extmodes($dm)
    {
        /*if()
        {

        }
        if()
        {

        }
        if()
        {

        }
        if()
        {

        }*/
    }
    public static function generatorhtml($dom,$dx,$parent=NULL)
    {
        for($c=0,$e=is_array($dx)&&!self::isAssoc($dx),$d=($e)?$dx:array($dx);$c<count($d);$c++)
        {
            if(is_null($d[$c])) continue;
            else if(is_array($d[$c])&&!self::isAssoc($d[$c]))
            {
                self::generatorhtml($dom,$d[$c],$parent);
            }
            else if($d[$c]['type']=='dom')
            {
                $dm;
                if(array_key_exists('element',$d[$c]))
                {
                    $dm=$d[$c]['element'];
                }
                else if(array_key_exists('html',$d[$c]))
                {
                    $fx=$dom->createDocumentFragment();
                    $fx->appendXML($d[$c]['html']);
                    $dm=$fx;
                }
                if(isset($dm))
                {
                    if($e)
                    {
                        $parent->appendChild($dm);
                    }
                    else{
                        return $dm;
                    } 
                }
            }
            else if($d[$c]['type']!=='table')
            {
                $dm=NULL;
                if($d[$c]['type']==='textnode')
                {
                    $dm=$dom->createTextNode($d[$c]['text']);
                }
                else if($d[$c]['type']==='commentnode')
                {
                    $dm=$dom->createComment($d[$c]['text']);
                }
                else
                {
                    $dm=$dom->createElement($d[$c]['type']);
                    self::paramcheck($dm,$d[$c]);
                    if(array_key_exists('ext',$d[$c]))
                    {
                        if(is_array($d[$c]['ext'])&&!self::isAssoc($d[$c]['ext']))
                        {
                            self::generatorhtml($dom,$d[$c]['ext'],$dm);
                        }
                        else{
                            $dm->appendChild(self::generatorhtml($dom,$d[$c]['ext'],$dm));
                        }
                    }
                }
                if($e)
                {
                    $parent->appendChild($dm);
                }
                else{
                    return $dm;
                }
            }
            else{
                $mz=array($dom->createElement((array_key_exists('ttype',$d[$c]))?$d[$c]['ttype']:'div'));
                self::paramcheck($mz[0],$d[$c]);
                if(array_key_exists('tnodes',$d[$c]))
                {
                    $rows=array();
                    $rtype=(array_key_exists('rtype',$d[$c]))?$d[$c]['rtype']:'div';
                    $ctype=(array_key_exists('ctype',$d[$c]))?$d[$c]['ctype']:'div';
                    $r_all=array_key_exists('allrows',$d[$c]);
                    $rrows=array_key_exists('rows',$d[$c]);
                    $ccols=array_key_exists('cols',$d[$c]);
                    $tablemaster=function($ax) use($dom,&$tablemaster,$rows,$rtype,$ctype,$mz,$d,$c,$r_all,$rrows,$ccols){
                        for($x=0,$last_col,$tnodes=(self::isAssoc($ax))?array($ax):$ax;$x<count($tnodes);$x++)
                        {
                            if(is_null($tnodes[$x])) continue;
                            else if(is_array($tnodes[$x])&&!self::isAssoc($tnodes[$x])) $tablemaster($tnodes[$x]);
                            else{
                                $cx=$tnodes[$x];
                                for($y=1;count($mz)<=$cx['r'];$y++)
                                {
                                    $zx=$dom->createElement($rtype);
                                    $mz[0]->appendChild($zx);
                                    array_push($mz,$zx);
                                    if($rrows) self::paramcheck($zx,$d[$c]['rows']);
                                    if(array_key_exists('rows',$cx)) self::paramcheck($zx,$cx['rows']);
                                    if($r_all) self::paramcheck($zx,$d[$c]['allrows']);
                                }
                                if(!array_key_exists($cx['r'],$rows)) $rows[$cx['r']]=0;//=array('len'=>0,'col'=>array());    //col count

                                if($rows[$cx['r']]<$cx['c'])
                                {
                                    for($y=$rows[$cx['r']]+1;$y<=$cx['c'];$y++)
                                    {
                                        $dz=$dom->createElement($ctype);
                                        $mz[$cx['r']]->appendChild($dz);
                                        if($ccols) self::paramcheck($dz,$d[$c]['cols']);
                                        if(array_key_exists('cols',$cx))
                                        {
                                            self::paramcheck($dz,$cx['cols']);
                                        }
                                        if($y+1>$cx['c']) $last_col=$dz;
                                    }
                                    $rows[$cx['r']]=$cx['c'];
                                }
                                self::paramcheck($last_col,$cx);//$rows[$cx['r']]['col'][$cx['c']]
                                if(array_key_exists('ext',$cx))
                                {
                                    if(is_array($cx['ext'])&&!self::isAssoc($cx['ext']))
                                    {
                                        self::generatorhtml($dom,$cx['ext'],$last_col);
                                    }
                                    else{
                                        $last_col->appendChild(self::generatorhtml($dom,$cx['ext'],$last_col));
                                    }
                                }
                            }
                        }
                    };
                    $tablemaster($d[$c]['tnodes']);
                    if(array_key_exists('rsel',$d[$c]))
                    {
                        for($y=0,$h=array_keys($d[$c]['rsel']);$y<count($h);$y++)
                        {
                            if($h[$y]>0&&$h[$y]<count($mz))
                            {
                                self::paramcheck($mz[$h[$y]],$d[$c]['rsel'][$h[$y]]);
                            }
                        }
                    }
                }
                if(array_key_exists('ext',$d[$c]))
                {
                    if(is_array($d[$c]['ext'])&&!self::isAssoc($d[$c]['ext']))
                    {
                        self::generatorhtml($dom,$d[$c]['ext'],$mz[0]);
                    }
                    else{
                        $mz[0]->appendChild(self::generatorhtml($dom,$d[$c]['ext'],$mz[0]));
                    }
                }
                if($e)
                {
                    $parent->appendChild($mz[0]);
                }
                else{
                    return $mz[0];
                }
            }
        }
    }

    public static function generator($dom,$dx,$mx=NULL)
    {
        //echo 'TESTING 321',"\n";
        for($c=0,$last=NULL,$ix=is_array($dx),$e=$ix&&!self::isAssoc($dx),$d=($e)?$dx:array($dx);$c<count($d);$c++)
        {
            //var_dump($dx);
            //echo "ix:${ix} e:${e}<br>";
            //echo "c:".$c,"<br>"; 
            echo 'type:',gettype($d[$c])," kind:",self::isAssoc($d[$c]),"<br>";

           // echo "kind:".$d[$c]['type']." c:".$c."<br>";//."  d[c]:".var_dump($d[$c])."\n";
           //var_dump($d[$c]);
           //echo '<br>';
            if(is_null($d[$c])) continue;
            else if(is_array($d[$c])&&self::isAssoc($d[$c])==false)
            {
                //echo 'recursive begin<br>';
                self::generator($dom,$d[$c],(!is_null($mx)&&self::isAssoc($mx))?$mx:array('parent'=>NULL,'child'=>NULL,'prev'=>NULL,'next'=>NULL,'dom'=>NULL));
            }
            else if($d[$c]['type']=='dom')
            {
                //echo 'inside here now1<br>';
                $zc=(!is_null($mx)&&self::isAssoc($mx))?$mx:array('parent'=>NULL,'child'=>NULL,'prev'=>NULL,'next'=>NULL,'dom'=>NULL);
                $insideout=function($nd,$jx,$index) use($last,&$insideout){
                    $jx['dom']=$nd;
                    if($index>0&&!is_null($last))
                    {
                        $last['next']=$jx;
                        $jx['prev']=$last;
                    }
                    if(!is_null($jx['parent']))
                    {
                        if(is_null($jx['parent']['child']))
                        {
                            $jx['parent']['child']=$jx;
                        }
                        else if(!($kx=is_array($jx['parent']['child'])))//seq
                        {
                            $jx['parent']['child']=array_merge(array($jx['parent']['child']),array($jx));
                        }
                        else if($kx)
                        {
                            array_push($jx['parent']['child'],$jx);
                        }
                    }
                    $last=$jx;
                    for($x=0,$y=$nd['children'];$x<count($y);$x++)
                    {
                        $insideout($y[$x],array('parent'=>$jx,'child'=>NULL,'prev'=>NULL,'next'=>NULL,'dom'=>NULL),$x);
                    }
                };
                $insideout($y[$x],$datatemp,$x);
                $last=$zc;
                if($e)
                {
                    $zc['parent']['dom']->appendChild($zc['dom']);
                }
                else{
                    return $zc['dom'];
                }
            }
            else if($d[$c]['type']!=='table')
            {
                //echo "kind:".$d[$c]['type']." c:".$c."<br>";//."  d[c]:".var_dump($d[$c])."\n";
                $zc=(is_array($mx)&&self::isAssoc($mx))?$mx:array('parent'=>NULL,'child'=>NULL,'prev'=>NULL,'next'=>NULL,'dom'=>NULL);
                if($d[$c]['type']==='textnode')
                {
                    $zc['dom']=$dom->createTextNode($d[$c]['text']);
                }
                else if($d[$c]['type']==='commentnode')
                {
                    $zc['dom']=$dom->createComment($d[$c]['text']);
                }
                else
                {
                    $zc['dom']=$dom->createElement($d[$c]['type']);
                    if($c>0&&!is_null($last))
                    {
                        $last['next']=$zc;
                        $zc['prev']=$last;
                    }
                    if(!is_null($zc['parent']))
                    {
                        if(!is_null($zc['parent']))
                        {
                            $zc['parent']['child']=$zc;
                        }
                        else if(!($kx=is_array($zc['parent']['child'])))//seq
                        {
                            $zc['parent']['child']=array_merge(array($zc['parent']['child']),array($zc));
                        }
                        else if($kx)
                        {
                            array_push($zc['parent']['child'],$zc);
                        }
                    }
                    self::paramcheck($zc['dom'],$d[$c]);
                    if(array_key_exists('ext',$d[$c]))
                    {
                        $zc['child']=array('parent'=>$zc,'child'=>NULL,'prev'=>NULL,'next'=>NULL,'dom'=>NULL);
                        if(is_array($d[$c]['ext'])&&!self::isAssoc($d[$c]['ext']))
                        {
                            self::generator($dom,$d[$c]['ext'],$zc['child']);
                        }
                        else{
                           // echo 'appending type:',$d[$c]['type'],' <br>';
                            $zc['dom']->appendChild(self::generator($dom,$d[$c]['ext'],$zc['child']));
                        }
                    }
                }
                $last=$zc;
                if($e)
                {
                    $zc['parent']['dom']->appendChild($zc['dom']);
                }
                else{
                    return $zc['dom'];
                }
            }
            else{
                $rows=array();
                $mz;
                if(isset($mx)&&!is_null($mx))
                {
                    //echo'first section<br>';
                    $mz=array($mx);
                    $mz[0]['dom']=$dom->createElement((array_key_exists('ttype',$d[$c]))?$d[$c]['ttype']:'div');
                    $mz[0]['child']=array();
                }
                else{
                    echo'second section<br>';
                    $mz=array(array('parent'=>NULL,'child'=>array(),'prev'=>NULL,'next'=>NULL,'dom'=>$dom->createElement((array_key_exists('ttype',$d[$c]))?$d[$c]['ttype']:'div')));
                }
                //echo'THIS IS A TEST OF EVERYTHING<br>';
                //var_dump($mz);
               // echo '<br>';
                self::paramcheck($mz[0]['dom'],$d[$c]);
                $rtype=(array_key_exists('rtype',$d[$c]))?$d[$c]['rtype']:'div';
                $ctype=(array_key_exists('ctype',$d[$c]))?$d[$c]['ctype']:'div';
                $tablemaster=function($ax) use($dom,&$tablemaster,$rows,$rtype,$ctype,$mz,$d,$c){
                    for($x=0,$tnodes=(self::isAssoc($ax))?array($ax):$ax;$x<count($tnodes);$x++)
                    {
                        if(is_null($tnodes[$x])) continue;
                        else if(is_array($tnodes[$x])&&!self::isAssoc($tnodes[$x])) $tablemaster($tnodes[$x]);
                        else{
                            $cx=$tnodes[$x];
                            for($y=1;count($mz)<=$cx['r'];$y++)
                            {
                                $zx=$dom->createElement($rtype);
                                $mz[0]['dom']->appendChild($zx);
                                $tmp=array('parent'=>$mz[0],'child'=>array(),'prev'=>NULL,'next'=>NULL,'dom'=>$zx);
                                array_push($mz,$tmp);
                                if($y>1)
                                {
                                    $mz[$y-1]['next']=$tmp;
                                    $mz[$y]['prev']=$mz[$y-1];
                                }
                                if(array_key_exists('rows',$d[$c]))
                                {
                                    self::paramcheck($zx,$d[$c]['rows']);
                                }
                                if(array_key_exists('rows',$cx))
                                {
                                    self::paramcheck($zx,$cx['rows']);
                                }
                            }
                            if(!array_key_exists($cx['r'],$rows)) $rows[$cx['r']]=array('len'=>0,'col'=>array());
                            if(array_key_exists($cx['r'],$rows)&&$rows[$cx['r']]['len']<$cx['c'])
                            {
                                for($y=$rows[$cx['r']]['len']+1;$y<=$cx['c'];$y++)
                                {
                                    $dz=$dom->createElement($ctype);
                                    $tmp=array('parent'=>$mz[$cx['r']],'child'=>array(),'prev'=>NULL,'next'=>NULL,'dom'=>$dz);
                                    $rows[$cx['r']]['col'][$y]=array('rows'=>NULL,'cell'=>$tmp,'dom'=>$dz);
                                    array_push($mz[$cx['r']]['child'],$tmp);
                                    if($y>1)
                                    {
                                        $mz[$cx['r']]['child'][$y-2][$next]=$tmp;
                                        $tmp[$prev]=$mz[$cx['r']]['child'][$y-2];
                                    }
                                    $mz[$cx['r']]['dom']->appendChild($dz);
                                    if(array_key_exists('cols',$d[$c]))
                                    {
                                        self::paramcheck($dz,$d[$c]['cols']);
                                    }
                                    if(array_key_exists('cols',$cx))
                                    {
                                        self::paramcheck($dz,$cx['cols']);
                                    }
                                }
                                $rows[$cx['r']]['len']=$cx['c'];
                            }
                            self::paramcheck($rows[$cx['r']]['col'][$cx['c']]['dom'],$cx);
                            if(array_key_exists('ext',$cx))
                            {
                                $ax=array('parent'=>$rows[$cx['r']]['col'][$cx['c']]['cell'],'child'=>NULL,'prev'=>NULL,'next'=>NULL,'dom'=>NULL);
                                if(is_array($cx['ext'])&&!self::isAssoc($cx['ext']))
                                {
                                    self::generator($dom,$cx['ext'],$ax);
                                }
                                else{
                                    $rows[$cx['r']]['col'][$cx['c']]['dom']->appendChild(self::generator($dom,$cx['ext'],$ax));
                                }
                            }
                        }
                    }
                };
                $tablemaster($d[$c]['tnodes']);
                if(array_key_exists('rows',$d[$c]))
                {
                    for($y=0,$h=array_keys($d[$c]['rows']);$y<count($h);$y++)
                    {
                        if($h[$y]<count($mz))
                        {
                            self::paramcheck($mz[$h[$y]]['dom'],$d[$c]['rows'][$h[$y]]);
                        }
                    }
                }
                if(array_key_exists('allrows',$d[$c]))
                {
                    if(array_key_exists('functions',$d[$c]['allrows']))
                    {
                        for($y=1;$y<count($mz);$y++) self::dmset($mz[$y]['dom'],$d[$c]['allrows']['functions']);
                    }
                    if(array_key_exists('settings',$d[$c]['allrows']))
                    {
                        for($y=1;$y<count($mz);$y++) self::dmset($mz[$y]['dom'],$d[$c]['allrows']['settings']);
                    }
                }
                $mz[0]['data']=$rows;
                if($c>0&&!is_null($last))
                {
                    $last['next']=$mz[0];
                    $mz[0]['prev']=$last;
                }
                if(!is_null($mz[0]['parent']))
                {
                    if(is_null($mz[0]['parent']['child']))
                    {
                        $mz[0]['parent']['child']=$mz[0];
                    }
                    else if(!($tm=self::isAssoc($mz[0]['parent']['child'])))
                    {
                        $mz[0]['parent']['child']=array_merge(array($mz[0]['parent']['child']),array($mz[0]));
                    }
                    else if($tm)
                    {
                        array_push($mz[0]['parent']['child'],$mz[0]);
                    }
                }
                $last=$mz[0];
                if($e)
                {
                    $mz[0]['parent']['dom']->appendChild($mz[0]['dom']);
                }
                else{
                    return $mz[0]['dom'];
                }
            }
        }
    }
    private static function paramcheck($a,$b)
    {
        if(array_key_exists('functions',$b))
        {
            self::dmset($a,$b['functions']);
        }
        else if(array_key_exists('settings',$b))
        {
            //echo 'SETTING SETTINGS<br>';
            self::dmset($a,$b['settings']);
        }
        else if(array_key_exists('save',$b))
        {
            if(is_array($b['save'])&&!(self::isAssoc($b['save']))||array_key_exists('set',$b['save'])&&array_key_exists('loc',$b['save']))
            {
                self::setval($a,$b['save'],NULL,true);
            }
            else{
                for($x=0,$y=array_keys($b['save']);$x<count($y);$x++)
                {
                    $tmp=$b['save'][$y[$x]];
                    $tmp[$y[$x]]=$a;
                    //$tmp[$y[$x]]=$a;
                }
            }
        }
        else if(array_key_exists('push',$b))
        {
            for($x=0,$y=array_keys($b['save']);$x<count($y);$x++)
            {
                array_push($b['save'][$y[$x]],$a);
            }
        }
        else if(array_key_exists('sub',$b))
        {
            for($x=0,$y=array_keys($b['save']);$x<count($y);$x++)
            {
                $b['save'][$y[$x]]($a);
            }
        }
    }

    private static function dmset($a,$b,$c=array(),$d=0)
    {
        //if(!isset($c)||is_null($c)) $c=array();
        //if(!isset($d)||is_null($d)) $d=0;
        if($d==0&&is_array($b))
        {
            if(!($mx=self::isAssoc($b)))
            {
                for($x=0;$x<count($b);$x++) self::dmset($a,$b[$x],$c,$d);
            }
            else if($mx)
            {
                //echo "BEFORE ARRAY<br>";
                for($x=0,$z=array_keys($b),$v;$x<count($z);$x++)
                {
                    if($z[$x]=='id')
                    {
                        $a->setAttribute('id',$b[$z[$x]]);
                    }
                    elseif($z[$x]=='style')
                    {
                        $tmp=array();
                        for($u=0,$w=array_keys($b[$z[$x]]);$u<count($w);$u++) array_push($tmp,$w[$u].':'.$b[$z[$x]][$w[$u]]);
                        $a->setAttribute('style',implode(';',$tmp).';');
                    }
                    else if($z[$x]=='className'||$z[$x]=='class')
                    {
                        $a->setAttribute('class',(is_array($b[$z[$x]]))?implode(' ',$b[$z[$x]]):$b[$z[$x]]);
                    }
                    else if($z[$x]=='function'&&($v=1)||$z[$x]=='nfunction'&&($v=2))
                    {
                        if(self::isAssoc($b[$z[$x]]))//if associated is detected we do each key and value as each separate executionn  function=>[1=>2,3=>2]
                        {
                            for($y=0,$w=array_keys($b[$z[$x]]);$y<count($w);$y++)
                            {
                                self::dmset($a,array($w[$y],$b[$z[$x]][$w[$y]]),$c,$v);
                            }
                        }
                        else{//function=>[1,2]
                            self::dmset($a,$b[$z[$x]],$c,$v);
                        }
                    }
                    else if($z[$x]=='functions'&&($v=1)||$z[$x]=='nfunctions'&&($v=2))
                    {
                        if(self::isAssoc($b[$z[$x]]))//if associated is detected we do each key and value as each separate execution  functions=>[1=>2,3=>2]
                        {
                            for($y=0,$w=array_keys($b[$z[$x]]);$y<count($w);$y++)
                            {
                                self::dmset($a,array($w[$y],$b[$z[$x]][$w[$y]]),$c,$v);
                            }
                        }
                        else{//[[1,2],[1,2],[3,2]]
                            for($y=0,$w=$b[$z[$x]];$y<count($w);$y++)
                            {
                                self::dmset($a,$w[$y],$c,$v);
                            }
                        }
                        
                    }
                    else{
                        self::dmset($a,$b[$z[$x]],array_merge($c,array($z[$x])));
                    }
                }
            }
        }
        else if(isset($b)&&!is_null($b))
        {
            $tmp=$a;
            for($x=0,$y=count($c)-1;$x<$y;$x++)
            {
                $t=$c[$x];
                $tmp=$tmp->$t;
            }
            if($d!=0)
            {
                call_user_func_array(array($tmp,end($c)),$b);
            }
            else{
                $mx=end($c);
                $tmp->$mx=$b;
            }
        }
    }
    private static function setval($a,$b,$c=NULL,$d=false)
    {
       // if(!isset($c)) $c=NULL;
       // if(!isset($d)) $d=false;
        if($d==true&&!($tm=self::isAssoc($b)))
        {
            for($x=0;$x<count($b);$x++) self::setval($a,$b[$x],NULL,true);
        }
        else if($tm)
        {
            if($d==true&&array_key_exists('set',$b)&&array_key_exists('loc',$b))
            {
                if(is_array($b['set']))
                {
                    for($x=0;$x<count($b['set']);$x++)
                    {
                        self::setval($a,$b['loc'],$b['set'][$x]);
                    }
                }
                else{
                    self::setval($a,$b['loc'],$b['set']);
                }
            }
        }
        else{
            for($x=0,$y=array_keys($b);$x<count($y);$x++)
            {
                if(!is_null($b[$y[$x]]))
                {
                    if(!array_key_exists($y[$x])||!(self::isAssoc($c[$y[$x]]))) $c[$y[$x]]=array();
                    self::setval($a,$b[$y[$x]],$c[$y[$x]]);
                }
                else{
                    $tmp=$c[$y[$x]];
                    $tmp[$y[$x]]=$a;
                }
            }
        }
    }
}



class idgen{
    private $uids=array();
    private $real_id=array();
    public function setid($a)
    {
        if(is_array($a))//gettype($a)=='array'
        {
            $id=$this->genid(count($a));
            for($x=0;$x<count($a);$x++) $this->real_id[$a[$x]]=$id[$x];
        }
        else{
            $this->real_id[$a]=$this->genid(1);
        }
    }
    public function deleteid($a)
    {
        unset($this->real_id[$a]);
    }
    public function getid($a){
        return (array_key_exists($a,$this->real_id))?$this->real_id[$a]:NULL;
    }
    public function clearall(){
        $this->real_id=array();
    }
    public function genid($amt)
    {
        $result=array();
        for($x=0;$x<$amt;)
        {
            $zc=base_convert((round(microtime(true) * 1000))*(mt_rand() / mt_getrandmax()),12,16);
            $tmp=preg_replace('/(.+?)(?=\.).*|(.+)/','$1$2',$zc);
            if(!array_key_exists($tmp,$this->uids))
            {
                $this->uids[$tmp]=1;
                array_push($result,$tmp);
                $x++;
            }
        }
        return $result;
    }
}

//dynamically add and remove css when we have to
class css_manager2{
    private $stylesheets;
    private $default_sheet;
    function __construct($default_sheetname='main'){
        $this->default_sheet=$default_sheetname;
        $this->stylesheets=array($default_sheetname=>array('id'=>0,'style_id'=>array(),'rules'=>array()));
    }
    public function add($a,$b=NULL,$mkdefaultname=false)
    {
        if(is_null($b)) $b=$this->default_sheet;
        if(!array_key_exists($b,$this->stylesheets)) $this->stylesheets[$b]=array('id'=>0,'style_id'=>array(),'rules'=>array());
        if($mkdefaultname) $this->default_sheet=$b;
        if(is_array($a))
        {
            for($x=0;$x<count($a);$x++)
            {
                $id=$this->stylesheets[$b]['id'];
                $this->stylesheets[$b]['style_id'][$a[$x]]=$id;
                $this->stylesheets[$b]['rules'][$id]=$a[$x];
            }
            
        }
        else{
            $id=$this->stylesheets[$b]['id'];
            $this->stylesheets[$b]['style_id'][$a]=$id;
            $this->stylesheets[$b]['rules'][$id]=$a;
        }
        return $this->stylesheets[$b]['id']++;
    }
    public function remove($a,$b=NULL)
    {
        if(is_null($b)) $b=$this->default_sheet;
        if(is_array($a))
        {
            for($x=0;$x<count($a);$x++)
            {
                if(array_key_exists($this->stylesheets[$b]['style_id'][$a[$x]]))
                {
                    $id=$this->stylesheets[$b]['style_id'][$a[$x]];
                    unset($this->stylesheets[$b]['rules'][$id]);
                    unset($this->stylesheets[$b]['style_id'][$a[$x]]);
                }
            }
        }
        else if(array_key_exists($this->stylesheets[$b]['style_id'][$a]))
        {
            $id=$this->stylesheets[$b]['style_id'][$a];
            unset($this->stylesheets[$b]['rules'][$id]);
            unset($this->stylesheets[$b]['style_id'][$a]);    
        }
    }
    public function remove_id($a,$b=NULL)
    {
        if(is_null($b)) $b=$this->default_sheet;
        if(is_array($a))
        {
            for($x=0;$x<count($a);$x++)
            {
                if(array_key_exists($this->stylesheets[$b]['rules'][$a[$x]]))
                {
                    $rule=$this->stylesheets[$b]['rules'][$a[$x]];
                    unset($this->stylesheets[$b]['style_id'][$rule]);
                    unset($this->stylesheets[$b]['rules'][$a[$x]]);
                }
            }
        }
        else if(array_key_exists($this->stylesheets[$b]['rules'][$a]))
        {
            $rule=$this->stylesheets[$b]['rules'][$a];
            unset($this->stylesheets[$b]['style_id'][$rule]);
            unset($this->stylesheets[$b]['rules'][$a]);
        }
    }
    public function delete_sheet($a,$b=NULL)
    {
        if(is_null($b)) $b=$this->default_sheet;
        if(is_array($a))
        {
            for($x=0;$x<count($a);$x++)
            {
                if(array_key_exists($this->stylesheets[$b])) unset($this->stylesheets[$b]);
            }
        }
        else if(array_key_exists($this->stylesheets[$b]))
        {
            unset($this->stylesheets[$b]);
        }
    }
    public function tree($dom,$parent=NULL,$b=NULL)
    {
        if(is_null($b)) $b=$this->default_sheet;
        $mx=implode("\r\n",array_keys($this->stylesheets[$b]['style_id']));
        $dm=$dom->createElement('style',implode('',$matches));
        if(is_null($parent))
        {
            return $dm;
        }
        else{
            $parent->appendChild($dm);
        }
    }
    public function tree_all($dom,$parent=NULL)
    {
        $kx=array_keys($this->stylesheets);
        $mx=implode("\r\n",array_map(function($a){return implode("\r\n",array_keys($this->stylesheets[$a]['style_id']));},$kx));
        $dm=$dom->createElement('style',$mx);
        $dm->setAttribute("type","text/css");
        if(is_null($parent))
        {
            return $dm;
        }
        else{
            $parent->appendChild($dm);
        }
    }
}



?>