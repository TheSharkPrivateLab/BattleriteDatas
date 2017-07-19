<?php

function getDatas($userID) {
    $doc = new DOMDocument;
    
    // We don't want to bother with white spaces
    $internalErrors = libxml_use_internal_errors(true);
    $doc->preserveWhiteSpace = false;
    
    // Most HTML Developers are chimps and produce invalid markup...
    $doc->strictErrorChecking = false;
    $doc->recover = true;
    
    if ($doc->loadHTMLFile('https://battlerite-stats.ru/profile/'.strval($userID))) {
    
        $xpath = new DOMXPath($doc);
        
        $query = "//div[@class='block-info-league']";
        
        $entries = $xpath->query($query);
        $str_rm = "";
        $ret = "Unranked";
        if (isset($entries->item(0)->textContent))
            $ret = str_replace($str_rm,"",$entries->item(0)->textContent);
        
        $query2 = "//div[@class='col-md-9 character-block']"; 
        $entries2 = $xpath->query($query2);
        $str_rm2 = "All roles
                                                Melee
                                                Ranged
                                                Support
                                            
                                            
                                                
                                                
                                        
                                        The search has not given any results
                                        
                                            
                                                
                                                    
                                                    
                                            ";
        if (isset($entries->item(0)->textContent)) {
            $ret2 = str_replace($str_rm2,"",$entries2->item(0)->textContent);
            $ret2 = str_replace("\n","",$ret2);
            $ret2 = preg_replace('/\s+/', ' ', $ret2);
            $re = '/\w* \d+ lvl \d+ - \d+/';
            preg_match_all($re, $ret2, $matches, PREG_SET_ORDER, 0);
            $ret3 = "";
            foreach ($matches as $champion)
            {
                $ret3 = $ret3 . "<p>" . $champion[0] ."</p>"; 
            }
            return("<div style=\"border:1px solid black\">".$ret . " ". $ret3."</div>");
            //return $matches;
        }
    }
    else return "";
}
?>
<html>
    <head>
    	<title>Battlerite Datas</title>
    </head>
    <body>
    <?php
    $x = 1;
    while ($x <= 10) {
        if ($datas = getDatas($x))
            echo($datas);
        $x++;
    }
    ?>
    </body>
</html>