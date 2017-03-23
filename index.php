<?php 
header("Access-Control-Allow-Origin: *");

if(isset($_GET['compName']))   {                    

$stockName = $_GET["compName"];
echo $stockName; 
$jsonObj = file_get_contents("http://dev.markitondemand.com/MODApis/Api/v2/Lookup/json?input=".$stockName);
 $objJsonDecoded = json_decode($jsonObj,true);   
 foreach($objJsonDecoded as $val){
        $sym = $val["Symbol"];
        $nam = $val["Name"];
        $exg = $val["Exchange"];
        $symArr[] = $sym;
        $namArr[] = $nam;
        $exgArr[] = $exg;
        $totArr[] = (string)($sym . " - " . $nam . " ( " . $exg . " ) ");
    }
        $lookupValues = array();
        $lookupValues["symbolcmp"] = $symArr;
        $lookupValues["namecmp"] = $namArr;
        $lookupValues["exchangecmp"] = $exgArr;         
        $lookupValues["total"] = $totArr;        


echo json_encode($lookupValues);
}
else if(isset($_GET['newsFeed'])){
    $symbol = $_GET['newsFeed'];
$uName = 'dA3kB5Hi7bxaBx6+QFlNHPY3oCa1KBcr/nHUoeG0yFs';
$password ='dA3kB5Hi7bxaBx6+QFlNHPY3oCa1KBcr/nHUoeG0yFs';
$jsonBingNewsUrl = 'http://api.datamarket.azure.com/Bing/Search/v1/News?Query=%27'.$symbol.'%27&$format=json';    
$context = stream_context_create(array('http' => array(
  'request_fulluri' => true,
'header' => "Authorization: Basic " . base64_encode("$uName:$password"))));
$jsonBingNews = file_get_contents($jsonBingNewsUrl, false, $context);                 
$jsonBingDecoded = json_decode($jsonBingNews,true);                    
echo json_encode($jsonBingDecoded);
}


else if(isset($_GET['jsonInput']))
{
    $interactiveChInput = $_GET["jsonInput"];    
     $objJson = file_get_contents('http://dev.markitondemand.com/MODApis/Api/v2/InteractiveChart/json?parameters={"Normalized":false,"NumberOfDays":1095,"DataPeriod":"Day","Elements":[{"Symbol":"'.$interactiveChInput.'","Type":"price","Params":["ohlc"]}]}');   
     $objJsonDecoded = json_decode($objJson,true);   
    echo json_encode($objJsonDecoded);
}
else if(isset($_POST['storageSymbol']))
{
    $symbol = $_POST["storageSymbol"];  
    $quoteDetails = array(); 
   
    foreach($symbol as $s)
    {
               $objJson = file_get_contents("http://dev.markitondemand.com/MODApis/Api/v2/Quote/json?symbol=".$s);     $objJsonDecoded = json_decode($objJson,true);                    

                if($objJsonDecoded['Status'] != "SUCCESS")
                    {
                    $quoteDetails["Error"] = "<div style='border:1px solid;text-align:center;width:700px;background-color:rgb(250,250,250);margin:auto;padding-top:5px;padding-bottom:5px;font-family:Arial'>There is no stock information available</div></body></html>";
                   echo json_encode($quoteDetails);
                    return;
                   }                     
                        $lastPrice;               
                        $html = "<tr><td><a href='#' id='symbolId' key='".$s."'>".$objJsonDecoded['Symbol']."</a></td>";
                        $html = $html."<td>".$objJsonDecoded['Name']."</td>";
                        $lastPrice = $objJsonDecoded['LastPrice'];
                        $lastPrice = round($lastPrice,2);                        
                        $html = $html ."<td id='".$s."lastPrice'>$".$lastPrice."</td>";  
                        $change = round($objJsonDecoded['Change'],2);
                       $changepercent =round($objJsonDecoded['ChangePercent'],2);                        
                        if($changepercent == 0)
                            $html = $html ."<td id='".$s."change'>".$change."( ".$changepercent."% )</td>";
                       else if($changepercent < 0)
                         $html = $html ."<td id='".$s."change' class='changePercentNeg'>".$change."( ".$changepercent."% ) <img style ='height:20px;width:20px;' src='down.png'></td>";
                        else
                         $html = $html ."<td id='".$s."change' class='changePercentPos'>".$change."( ".$changepercent."% ) <img style ='height:20px;width:20px;' src='up.png'></td>";
                         $marketCap = $objJsonDecoded['MarketCap'];
                        if($marketCap >= 1000000000)
                        {
                        $marketcap = round(($marketCap/1000000000),2);
                             $html = $html ."<td>".$marketcap." Billion</td>";
                        }
                        else if($marketCap < 1000000000  && $marketCap >= 1000000)
                        {
                            $marketcap = round(($marketCap/1000000),2);
                             $html = $html ."<td>".$marketcap." Million</td>";
                        }       
                         
        $quoteDetails[$s] =$html."<td><button key=".$s." id='deleteRow' class='btn btn-xs'><span class='glyphicon glyphicon-trash'></span></button></td></tr>";
              
    }
    echo json_encode($quoteDetails);
}
else if(isset($_GET['refreshSymbol']))
{
    $symbol = $_GET["refreshSymbol"];  
    $quoteDetails = array(); 
   
    foreach($symbol as $s)
    {
               $objJson = file_get_contents("http://dev.markitondemand.com/MODApis/Api/v2/Quote/json?symbol=".$s);     $objJsonDecoded = json_decode($objJson,true);                    

                if($objJsonDecoded['Status'] != "SUCCESS")
                    {
                    $quoteDetails["Error"] = "<div style='border:1px solid;text-align:center;width:700px;background-color:rgb(250,250,250);margin:auto;padding-top:5px;padding-bottom:5px;font-family:Arial'>There is no stock information available</div></body></html>";
                   echo json_encode($quoteDetails);
                    return;
                   }                     
                        $lastPrice; 
                        $lastPrice = $objJsonDecoded['LastPrice'];
                        $lastPrice = round($lastPrice,2);                       
                       
                        $change = round($objJsonDecoded['Change'],2);
                       $changepercent =round($objJsonDecoded['ChangePercent'],2);            
                         
        $quoteDetails[$s] =array($lastPrice,$change,$changepercent);
              
    }
    echo json_encode($quoteDetails);
}
else if(isset($_GET['compNameNew']) && !empty($_GET['compNameNew']))
{

$symbol = $_GET["compNameNew"];  
$quoteDetails = array();   

               $objJson = file_get_contents("http://dev.markitondemand.com/MODApis/Api/v2/Quote/json?symbol=".$symbol);                     

                $objJsonDecoded = json_decode($objJson,true);                    

                if($objJsonDecoded['Status'] != "SUCCESS")
                    {
                    $quoteDetails["Error"] = "<div style='border:1px solid;text-align:center;width:700px;background-color:rgb(250,250,250);margin:auto;padding-top:5px;padding-bottom:5px;font-family:Arial'>There is no stock information available</div></body></html>";
                   echo json_encode($quoteDetails);
                    return;
                   }                     
                $lastPrice;                    


                        $html = "<tr><th>Name</th><td>".$objJsonDecoded['Name']."</td></tr>";
                        $quoteDetails["Name"]= $objJsonDecoded['Name'];                        

                        $html = $html ."<tr><th>Symbol</th><td>".$objJsonDecoded['Symbol']."</td></tr>";
                         $quoteDetails["Symbol"]=$objJsonDecoded['Symbol'];

                        $lastPrice = $objJsonDecoded['LastPrice'];
                        $lastPrice = round($lastPrice,2);
                        $quoteDetails["LastPrice"]=$lastPrice;
                        $html = $html ."<tr><th>Last Price</th><td>$".$lastPrice."</td></tr>";  

                      $change = round($objJsonDecoded['Change'],2);
                       $changepercent =round($objJsonDecoded['ChangePercent'],2);
                       $quoteDetails["changePercent"] = $changepercent;
                         $quoteDetails["ChangeandPercent"] = $change."( ".$changepercent."%)";
                        if($changepercent == 0)
                            $html = $html ."<tr><th>Change(Change Percent)</th><td>".$change."( ".$changepercent."% )</td></tr>";
                       else if($changepercent < 0)
                         $html = $html ."<tr><th>Change(Change Percent)</th><td class='changePercentNeg'>".$change."( ".$changepercent."% ) <img style ='height:20px;width:20px;' src='down.png'></td></tr>";
                        else
                         $html = $html ."<tr><th>Change(Change Percent)</th><td class ='changePercentPos'>".$change."( ".$changepercent."% ) <img style ='height:20px;width:20px;' src='up.png'></td></tr>";




                        date_default_timezone_set('America/Los_Angeles');
                        $time = date('d F Y, g:i:s A',strtotime($objJsonDecoded['Timestamp']));
                        $quoteDetails["TimeStamp"] = $time;
                        $html = $html ."<tr><th>Time and Date</th><td>".$time."</td></tr>";       

                        $marketCap = $objJsonDecoded['MarketCap'];
                        $quoteDetails["MarketCap"] =$marketCap;
                        if($marketCap >= 1000000000)
                        {
                        $marketcap = round(($marketCap/1000000000),2);
                             $html = $html ."<tr><th>Market Cap</th><td>".$marketcap." Billion</td></tr>";
                        }
                        else if($marketCap < 1000000000  && $marketCap >= 1000000)
                        {
                        $marketcap = round(($marketCap/1000000),2);
                             $html = $html ."<tr><th>Market Cap</th><td>".$marketcap." Million</td></tr>";
                        }

                    
                        $quoteDetails["Volume"] = $objJsonDecoded['Volume'];
                        $html = $html ."<tr><th>Volume</th><td>".$objJsonDecoded['Volume']."</td></tr>";



                        $changeYTD = round($objJsonDecoded['ChangeYTD'],2);
                        $changepercentNew = round($objJsonDecoded['ChangePercentYTD'],2);
                        $quoteDetails["ChangeYTD"] = $changeYTD."( ".$changepercentNew."% )";
                       if($changepercentNew == 0)
                        $html = $html ."<tr><th>Change YTD(Change Percent YTD)</th><td>".$changeYTD."( ".$changepercentNew."% )</td></tr>";
                        else if($changepercentNew < 0)
                            $html = $html ."<tr><th>Change YTD</th><td class='changePercentNeg'>".$changeYTD."( ".$changepercentNew."% ) <img style ='height:20px;width:20px;' src='down.png'></td></tr>";
                        else
                            $html = $html ."<tr><th>Change YTD(Change Percent YTD)</th><td class='changePercentPos'>".$changeYTD."( ".$changepercentNew."% ) <img style ='height:20px;width:20px;' src='up.png'></td></tr>";
                        
                        $quoteDetails["High"] = $objJsonDecoded['High'];
                        $html = $html ."<tr><th>High Price</th><td>$".round($objJsonDecoded['High'],2)."</td></tr>";
                         $quoteDetails["Low"] = $objJsonDecoded['Low'];
                        $html = $html ."<tr><th>Low Price</th><td>$".round($objJsonDecoded['Low'],2)."</td></tr>";
                         $quoteDetails["Open"] = $objJsonDecoded['Open'];
                        $html = $html ."<tr><th>Opening Price</th><td>$".round($objJsonDecoded['Open'],2)."</td></tr>"; 
                       
                    $quoteDetails["quoteDetails"] =$html;
                    $jsonYahooChart = "http://chart.finance.yahoo.com/t?s=".$symbol."&lang=en-US&width=400&height=300";
                   $quoteDetails["YahooChart"] = $jsonYahooChart; 
    
    
    
                  /*$uName = 'dA3kB5Hi7bxaBx6+QFlNHPY3oCa1KBcr/nHUoeG0yFs';
                  $password ='dA3kB5Hi7bxaBx6+QFlNHPY3oCa1KBcr/nHUoeG0yFs';
                    $jsonBingNewsUrl = 'http://api.datamarket.azure.com/Bing/Search/v1/News?Query=%27'.$symbol.'%27&$format=json';    
                  $context = stream_context_create(array('http' => array(
                      'request_fulluri' => true,
                  'header' => "Authorization: Basic " . base64_encode("$uName:$password"))));
                    $jsonBingNews = file_get_contents($jsonBingNewsUrl, false, $context);                 
                    $jsonBingDecoded = json_decode($jsonBingNews,true); 
                    $quoteDetails["BingNews"] = $jsonBingDecoded;*/
                    echo json_encode($quoteDetails);
}

/*  $name,$symbol,$exchange;
foreach($objJsonDecoded as $key => $value)
{       
   if($key == "Name")
       $name = $value;
   else if($key == "Symbol")
       $symbol = $value;
   else if($key == "Exchange")
   $exchange = $value;
   $concat = $symbol.'-'.$name.'('.$exchange.')';
   $lookupFinal["name"] = $name;
   $lookupFinal["symbol"]=$symbol;
   $lookupFinal["exchange"]=$exchange;
   $lookupFinal["display"]=$concat;
}
echo json_encode($lookupFinal);   */                                          
?>



