<?php
class Forms extends Content{
     public static function navbarLeft(){
         ?>
         <ul class="sidebar-nav">
            <li class="sidebar-brand">
                <i class="fa fa-plane" aria-hidden="true" style=""></i>
                <span>Baza lotów</span>
                 <i class="fa fa-bars menu-toggle" aria-hidden="true"></i> 
            </li>
            <a href="?pid=1">
            <li><i class="fa fa-home" aria-hidden="true" style="display: inline;"></i>
                <span>Podsumowanie</span>
            </li>
            </a>
            <li>
               <li><i class="fa fa-table" aria-hidden="true" style="display: inline;"></i>
                <span>Archiwum</span>
                <?php 
                    $Queries = new Queries("pid, ptitle", "baza_lotow");
                    Content::writeList($Queries->findInTable);
                ?>
            </li>
        </ul>    
        <?php
     }
    public static function pageContent(){
        if(isset($_GET['pid'])){
            $colors = array("bg-yellowplane", "bg-sky", "bg-greeny", "bg-softred", "bg-junglegreen");
            switch($_GET['pid']){
                case 1:
                include("./themes/".$_SESSION['theme']."/frontpage.php");
                break;
                case 2: 
                Content::writeTable(Queries::$zapytanieRollup1,$colors[0], 30); 
                break;
                case 3:
                Content::writeTable(Queries::$zapytanieCube1,$colors[1], 50); 
                break;
                case 4: 
                Content::writeTable(Queries::$zapytanieGroupingSets1,$colors[2], 50); 
                break;
                case 5:
                Content::writeTable(Queries::$partycjaObliczeniowa1,$colors[3], 50); 
                break;
                case 6: 
                Content::writeTable(Queries::$oknoObliczeniowe1,$colors[4], 100); 
                break;
                case 7: 
                $Queries = new Queries("Rok", "Rok");
                Content::submitFormSelectMenu($Queries->findInTable, 0);
                break;
                case 8: 
                Content::writeTable(Queries::$zapytanieRollup2,$colors[0], 50); 
                break;
                case 9:
                Content::writeTable(Queries::$zapytanieCube2,$colors[1], 50); 
                break;
                case 10: 
                Content::writeTable(Queries::$zapytanieGroupingSets2,$colors[2], 50); 
                break;
                case 11:
                $Queries = new Queries("Rok", "Rok");
                Content::submitFormSelectMenu($Queries->findInTable, 1);
                break;
                case 12: 
                Content::writeTable(Queries::$oknoObliczeniowe2,$colors[4], 100); 
                break;
                case 13: 
                Content::writeTable(Queries::$funkcjaRankingowa2,$colors[4], 100); 
                break;
                default: 
                if(isset($_GET['pid'])){               
                    $Queries = new Queries("pcontent","baza_lotow","pid",$_GET['pid']);
                }else{
                    $Queries = new Queries("pcontent","baza_lotow","pid",1);  
                }
                Content::writeContent($Queries->findInTable);
            }
        }else{
            include("./themes/".$_SESSION['theme']."/frontpage.php");
        }
    }
    public static function pageTitle(){
        if(isset($_GET['pid'])){
            switch($_GET['pid']){
                case 1: echo "Strona główna";
                    break;
                default: 
                    if(isset($_GET['pid'])){                $Queries = new Queries("ptitle","baza_lotow","pid",$_GET['pid']);
                    }else{
                    $Queries = new Queries("ptitle","baza_lotow","pid",1);  
                        echo "Strona główna";
                    }
                Content::writeContent($Queries->findInTable);
            }
        }     
    }
}
?>