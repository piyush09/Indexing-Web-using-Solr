<?php
header('Content-Type:text/html; charset=utf-8');
$limit = 10;
$query= isset($_REQUEST['q'])?$_REQUEST['q']:false;
$final_result = false;
$linksFromFile = array();

if($query){
        require_once('solr-php-client/Apache/Solr/Service.php');
        $solr = new Apache_Solr_Service('localhost', 8983, '/solr/homework/');   // Homework core created
        if(get_magic_quotes_gpc() == 1){
                $query = stripslashes($query);
        }
        try{
		if(!isset($_GET['algorithm']))$_GET['algorithm']="lucene";
		if($_GET['algorithm'] == "lucene"){

			$param = array("q.op" => 'AND');  
			$final_result = $solr->search($query, 0, $limit,$param);

		}else{

			$param = array('sort'=>'pageRankFile desc', "q.op" => 'AND');
			$final_result = $solr->search($query, 0, $limit, $param);

		}

	 }
        catch(Exception $e){
                die("<html><head><title>SEARCH EXCEPTION</title></head><body><pre>{$e->__toString()}</pre></body></html>");
        }
}
?>


<html>
<head>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">

        <title> PHP Solr Client Example </title>
        
<style>

	body {
		background-color: #ADD8E6;
	}

	.searchDiv{
		text-align: center;
	}
	.resultDiv{
		padding-bottom: 20px;
		padding-left: 15px;
	}
	.ResultLineDiv{
		padding-left: 10px;
		padding-bottom: 20px;
	}
	th{
		padding-right: 10px;
	}

	a {
		text-decoration: none;
	}

	a:hover {
		text-decoration: underline;
	}

	.table-result {
		width: 1100px;
		margin:auto;
	}


	
</style>
</head>
<body>
<div class= "searchDiv">
<h1> Search Engine</h1><br/>
<div class = "formDiv">
<form accept-charset="utf-8" method="get">

    <input id="q" name="q" type="text" value="<?php echo htmlspecialchars($query, ENT_QUOTES, 'utf-8');?>"/><br/><br/> 
	<input type="radio" name="algorithm" value="lucene" /> Solr's Lucene Search
	<input type="radio" name="algorithm" value="pagerank" /> PageRank Google <br/><br/> 
	<input type="submit" class="btn-primary" />
</form>
</div>
</div>
<?php
if($final_result){
        $final_sum = (int)$final_result->response->numFound; 
        $start = min(1,$final_sum);
        $end = min($limit, $final_sum);
        $file = fopen("UrlToHtml_NBCNews.csv","r"); 
        while(!feof($file))
 		{
  			$a = fgetcsv($file);
  			#print($a[0] ."-" . $a[1]);
  			$linksFromFile[$a[0]] = $a[1];
  		}

fclose($file);
?>
<div class="ResultLineDiv"> Results <?php echo $start; ?> - <?php echo $end;?> of <?php echo $final_sum;?>:</div> 
	
<?php
	foreach ($final_result->response->docs as $document) {?>
		
		<tr class="Row_Navigation" id=" Lucene Search">

		<?php 
		foreach ($document as $key => $value) {
			
			if($key == "id"){
				$URLID = $value;
			}
			elseif($key == 'description'){
				$URLDescription = $value;
			}
			elseif($key == 'og_url'){
				$currentURL = $value;
			}
			elseif($key == 'title'){
				$URLTitle = $value;
			}
		}
		if(empty($currentURL)){
			$idFetch = explode("/", $URLID);
			$currentURL = $linksFromFile[$idFetch[6]];
		}
		if(empty($URLDescription)){
			$URLDescription = "N/A";
		}
		if(empty($URLTitle)){
			$URLTitle = "N/A";
		}?>
		<div class="resultDiv">
		<table style ="border: 1px solid black; text-align: left; border-radius:10px; " class="table table-sm table-result">
			
			<tr>
				<th>ID</th>
				<td><?php echo $URLID ?></td>
			</tr>
			<tr>
				<th>URL Title</th>
				<td><a href=<?php echo $currentURL ?>><?php echo $URLTitle ?></a></td>
			</tr>
			<tr>
				<th>Description</th>
				<td><?php echo $URLDescription ?></td>
			</tr>
			<tr>
				<th>URL Link</th>
				<td><a href=<?php echo $currentURL ?>><?php echo $currentURL ?></a></td>
			</tr>
		</table>
	</div>

<?php  
		$URLTitle = $URLDescription = $currentURL = $URLID = "";?>
		</tr>

	<?php }?>


	


	<?php } ?>
</body>
</html>

