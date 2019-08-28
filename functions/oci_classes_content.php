<?php
class Content extends Queries{
    private static function getVariables($arg){
        $link = "";
        (isset($_GET['pid'])) ? $link = "?pid=" . $_GET['pid'] : $link = "?pid=1";
        (isset($_GET['rok'])) ? $link .= "&rok=" . $_GET['rok'] : null;
        if($arg == "generateSortLinks"){
            (isset($_GET['page'])) ? $link .= "&page=" . $_GET['page'] : $link .= "&page=1";
        }else{
            (isset($_GET['sort'])) ? $link .= "&sort=" . $_GET['sort'] : null ;
            (isset($_GET['order'])) ? $link .= "&order=" . $_GET['order'] : null ;
        }
        return htmlentities($link);
    }
    private static function convertVariablesToInputs($arg){
        foreach ($_GET as $key => $value)
        {
            if($arg !== $key){
                echo '<input type="hidden" name="'.$key.'" value="'.$value.'"></input>';
            }
        }
    }
    private static function tableTitles($stid, $ncols){
        ?>
        <div class="container">
         <div class="row">
        <?php
         for ($i = 1; $i <= $ncols; $i++) {
        ?>
            <div class="col">
        <?php     
            echo oci_field_name($stid, $i);
            Content::generateSortLinks(oci_field_name($stid, $i));
        ?>
            </div>
        <?php
         }
        ?>
         </div>
         </div>
         <?php
    }
    private static function fetchPage($stid, $page, $color){
        if(isset($_GET['page'])){
            $begin=(($_GET['page']-1) * $page);
            $end=$begin+$page;
        }
        else {
            $begin=0;
            $end=$page;
        }
        $n=0;
        while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
            if($page==0){
                ?>
                <div class="container transparent-<?php echo $color?>">
                 <div class="row">
                <?php 
                foreach ($row as $item) {
                    ?>
                    <div class="col">
                    <?php
                    echo  ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;");
                    ?>
                    </div>
                    <?php
             }
            ?>
                 </div> 
                 </div>    
                 <?php
            }else{
            if( $n>=$begin && $n<=$end){
                ?>  
                <div class="container transparent-<?php echo $color?>">
                 <div class="row">
                <?php 
                foreach ($row as $item) {
                    ?>
                    <div class="col">
                    <?php
                    echo  ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;");
                    ?>
                    </div>
                    <?php
             }
            ?>
                 </div> 
                 </div>    
            <?php
                }
                $n++;   
            }
        }
    }
    private static function paginationLinks($rows, $page, $color){
        $rowCount = ceil($rows/$page);
        $limit = 10;
        $offset = 0;
        if(isset($_GET['page']))
            $currentPage = $_GET['page'];
        else
            $currentPage = 1;
        $counter = 1;
        if ($rowCount>1){
            ?>
            <div class="container">
            <nav aria-label="Page navigation example">
            <ul class="pagination">
            <?php
            if ($currentPage > ($limit/2)){
                ?>
                   
                    <li class="page-item">    
                    <a class="page-link" href="?pid=<?php echo $_GET['pid'];?>&page=1">1</a></li>
                    <li class="page-item">    
                    <a class="page-link" href="?pid=<?php echo $_GET['pid'];?>&page=<?php echo ceil($currentPage/2);?>
                    ">...</a></li>   
                <?php
                 $offset = 3;
                 $limit-=2;
            }else{
                for ($i=1;$i<=$currentPage-1;++$i){
                    ?>
                    <li class="page-item">    
                    <a class="page-link <?php
                    if($i==$_GET['page'] || (!(isset($_GET['page']))&&$i==1)) echo $color;
                    ?>" href="?pid=<?php echo $_GET['pid'];?>&page=<?php echo $i;?>"><?php echo $i;?></a></li>
                    <?php
                }
                $limit-=($currentPage-1);
            }
            if ($rowCount>8){
                if ($currentPage > $rowCount-8){ $offset = 8-($rowCount - $currentPage);
                 $limit+=2;
                                           
                }
            }
                for ($i=$currentPage-$offset;$i<=$rowCount;++$i){
                    if($counter<$limit){
                    ?>
                    <li class="page-item">    
                    <a class="page-link <?php
                    if($i==$_GET['page'] || (!(isset($_GET['page']))&&$i==1)) echo $color;
                    ?>" href="?pid=<?php echo $_GET['pid'];?>&page=<?php echo $i;?>"><?php echo $i;?></a></li>
                    <?php
                    $counter++;
                    }
                }
                if ($currentPage <= $rowCount-8){
                    ?>
                    <li class="page-item">    
                    <a class="page-link" href="?pid=<?php echo $_GET['pid'];?>&page=<?php echo ceil(($currentPage+$rowCount)/2);?>
                    ">...</a></li>  
                    <li class="page-item">    
                    <a class="page-link" href="?pid=<?php echo $_GET['pid'];?>&page=<?php echo $rowCount; ?>"><?php echo $rowCount; ?>
                        </a></li>

                    <?php
                }
        }
        ?>
            </ul>
</nav>
  </div>
   <?php
    }
    private static function addSort($argument){
        if (isset($_GET['sort'])){
            if (preg_match('/\s/',$_GET['sort']) ){
         $argument .= " order by '"; 
         $argument .= $_GET['sort'];
         $argument .= "'";
            }else{
         $argument .= " order by "; 
         $argument .= $_GET['sort'];
         $argument .= "";
            }
        }
        if (isset($_GET['order']))
        {
            $argument .= " ";
            $argument .= $_GET['order'];
        } 
        return $argument;
    }
    private static function generateSortLinks($argument){
        if (!isset($_GET['sort'])){
        ?>
            <a href="<?php echo Content::getVariables("generateSortLinks");?>&sort=<?php echo strtolower($argument);?>&order=asc"><i class="fa fa-sort" aria-hidden="true"></i></a>
        <?php
            }else{
                if ($_GET['sort']!=strtolower($argument)){
                    ?>
                    <a href="<?php echo Content::getVariables("generateSortLinks");?>&sort=<?php echo strtolower($argument);?>&order=asc"><i class="fa fa-sort" aria-hidden="true"></i></a>
                    <?php
                }else{
                if(($_GET['order'])== 'desc'){
                    ?>
                    <a href="<?php echo Content::getVariables("generateSortLinks");?>&sort=<?php echo strtolower($argument);?>&order=asc"><i class="fa fa-sort-desc" aria-hidden="true"></i></a>
                    <?php
                }else{
                    ?>
                    <a href="<?php echo Content::getVariables("generateSortLinks");?>&sort=<?php echo strtolower($argument);?>&order=desc"><i class="fa fa-sort-asc" aria-hidden="true"></i></a>
                    <?php
                    }
                }
            }
    }
    protected static function writeTable($argument, $color, $rpp){
         $connect = $_SESSION['connection'];
         $argument = Content::addSort($argument);
         $stid = oci_parse($connect, $argument);  
         oci_execute($stid);
         $ncols = oci_num_fields($stid);
        ?>
        <div class="widget-table-title
         <?php echo $color ?>
         ">
         <?php
        Content::tableTitles($stid, $ncols);
        ?>
        </div>
         <div class="widget-table bg-white">
         <?php
        Content::fetchPage($stid, $rpp, $color);
        ?>
        </div>
        <?php
        Content::paginationLinks(oci_num_rows($stid),$rpp, $color);
    }
    protected static function writeContent($argument){
        $connect = $_SESSION['connection'];
        $stid = oci_parse($connect, $argument);
        oci_execute($stid);
        while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
            foreach($row as $item){
                echo $item;
            }
        }
    }
    public static function getNumRows($argument){
        $connect = $_SESSION['connection'];
        $stid = oci_parse($connect, $argument);
        oci_execute($stid);
        while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {}
        return oci_num_rows($stid);
    }
    protected static function writeList($argument){
        $connect = $_SESSION['connection'];
        $stid = oci_parse($connect, $argument);
        oci_execute($stid);
        ?><ul><?php
        while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {  
                ?>
                <a href="?pid=<?php echo $row[0];?>">
                <li>
                    <span><?php echo $row[1];?></span>
                </li>
                </a><?php
        } 
        ?></ul><?php
    }
    private static function writeSelectMenu($stid, $name, $title){
         ?>
        <label><?php echo $title;?>: </label> 
        <select class="custom-select" name="<?php echo $name;?>">
         <?php
        while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
            foreach($row as $item){
                ?>
                    <option value="<?php echo $item;?>"
                    <?php if(isset($_GET[$name])&&$_GET[$name]==$item)echo "selected";?>><?php echo $item;?></option>
                <?php
            }
        } 
    ?> </select> <?php
    }
    protected static function submitFormSelectMenu($argument, $argument2){
        $connect = $_SESSION['connection'];
        $stid = oci_parse($connect, $argument);
        oci_execute($stid);
        ?>
        <form action="<?php echo Content::getVariables("form");?>" method="GET" class="form-inline">
              <?php
               Content::convertVariablesToInputs("rok");
               Content::writeSelectMenu($stid, "rok", "Podaj rok z listy");
               ?>
              <button type="submit" class="btn bg-junglegreen">Prześlij</button>
        </form>
        <?php
        if (isset($_GET['rok'])){
			    $Queries = new Queries($_GET['rok']);  
				if ($argument2=0){
					Content::writeTable($Queries->funkcjaRankingowa1,"bg-junglegreen", 100); 
				}
				if ($argument2=1){
					Content::writeTable($Queries->partycjaObliczeniowa2,"bg-junglegreen", 100);					
				}
        }
    }
}
?>