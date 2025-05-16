# php-html-generator
 Generate html from php arrays and associative arrays
 Includes the css manager which allows for css rules to be added to the html
 
 This php version was converted from the javascript version
```
$html=new DOMDocument('1.0','UTF-8');

$tmp=['type'=>'div','settings'=>['className'=>'container'],'ext'=>['type'=>'table','settings'=>['style'=>['width'=>'100%'],'className'=>'tbx'],'rows'=>['settings'=>['style'=>['background-color'=>'#082440']]],'tnodes'=>[
            ['r'=>1,'c'=>1,'settings'=>['style'=>['border'=>'1px solid black','display'=>'none']],'ext'=>['type'=>'table','settings'=>['className'=>'tbx'],'tnodes'=>[
                ['r'=>1,'c'=>1,'settings'=>['style'=>['text-align'=>'left']],'ext'=>['type'=>'div','settings'=>['className'=>'nav_top_left','style'=>['display'=>'inline-block']],'ext'=>['type'=>'span','ext'=>['type'=>'textnode','text'=>'test1']]]],
                ['r'=>1,'c'=>2,'settings'=>['style'=>['text-align'=>'right']],'ext'=>['type'=>'div','settings'=>['className'=>'nav_top_right','style'=>['display'=>'inline-block']],'ext'=>['type'=>'span','ext'=>['type'=>'textnode','text'=>'test2']]]]
            ]]],
            ['r'=>2,'c'=>1,'settings'=>['className'=>'nav_logo'],'ext'=>['type'=>'div','ext'=>['type'=>'span','settings'=>['className'=>'maintitle'],'ext'=>['type'=>'textnode','text'=>'test3']]]],
            ['r'=>3,'c'=>1,'settings'=>['style'=>['border'=>'1px solid black','background-color'=>'dodgerblue']],'ext'=>['type'=>'table','settings'=>['style'=>['box-sizing'=>'border-box','height'=>'20px'],'className'=>'tbx mcenter'],'cols'=>['settings'=>['className'=>"nav_bottom_area_cell"]],'tnodes'=>[
                ['r'=>1,'c'=>1,'ext'=>['type'=>'div','settings'=>['className'=>'nav_sections'],'ext'=>['type'=>'span','ext'=>['type'=>'textnode','text'=>'test4']]]],
                ['r'=>1,'c'=>2,'ext'=>['type'=>'div','settings'=>['className'=>'nav_sections'],'ext'=>['type'=>'span','ext'=>['type'=>'textnode','text'=>'test5']]]],
                ['r'=>1,'c'=>3,'ext'=>['type'=>'div','settings'=>['className'=>'nav_sections'],'ext'=>['type'=>'span','ext'=>['type'=>'textnode','text'=>'test6']]]],
                ['r'=>1,'c'=>4,'ext'=>['type'=>'div','settings'=>['className'=>'nav_sections'],'ext'=>['type'=>'span','ext'=>['type'=>'textnode','text'=>'test7']]]],
                ['r'=>1,'c'=>5,'ext'=>['type'=>'div','settings'=>['className'=>'nav_sections'],'ext'=>['type'=>'span','ext'=>['type'=>'textnode','text'=>'test8']]]],
                ['r'=>1,'c'=>6,'ext'=>['type'=>'div','settings'=>['className'=>'nav_sections'],'ext'=>['type'=>'span','ext'=>['type'=>'textnode','text'=>'test9']]]],
                ['r'=>1,'c'=>7,'ext'=>['type'=>'div','settings'=>['className'=>'nav_sections'],'ext'=>['type'=>'span','ext'=>['type'=>'textnode','text'=>'test10']]]],
                ['r'=>1,'c'=>8,'ext'=>['type'=>'div','settings'=>['className'=>'nav_sections'],'ext'=>['type'=>'span','ext'=>['type'=>'textnode','text'=>'test11']]]],
            ]]]
        ]]];
$html->appendChild(htmlgen::generatorhtml($html,$tmp));
print $html->saveHTML();
```
