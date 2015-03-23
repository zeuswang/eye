<?php
function orz_view_datatable($file_name){
	echo "<link type='text/css' rel='stylesheet' href='http://cdn.datatables.net/1.10.4/css/jquery.dataTables.css'>\n";
	echo "<script type='text/javascript' src='http://code.jquery.com/jquery-1.11.1.min.js'></script>\n";
	echo "<script type='text/javascript' src='http://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js'></script>\n";
	echo "<link type='text/css' rel='stylesheet' href='/css/datatable.css'>\n";

	$file=fopen($file_name,"r");
	$count=0;
	print "<table id='example' class='display' cellspacing='0' width='100%'>\n";
	while(!feof($file))
	{
		$count+=1;
		//echo $count."<br>";
		$tmp=fgets($file);
		$line=(explode("\t",trim($tmp)));
		if(count($line)==1)continue;
		if($count==1){
			print "\t<thead>\n\t\t<tr>\n";
			$col=0;
			while($col<count($line)){
				print "\t\t\t<td>".$line[$col]."</td>\n";
				$col+=1;
			}
			print "\t\t</tr>\n\t</thead>\n";

			print "\t<tfoot>\n\t\t<tr>\n";
			$col=0;
			while($col<count($line)){
				print "\t\t\t<td>".$line[$col]."</td>\n";
				$col+=1;
			}
			print "\t\t</tr>\n\t</tfoot>\n";

			print "\t<tbody>\n";
		}else{
			print "\t\t<tr>\n";
			$col=0;
			while($col<count($line)){
				print "\t\t\t<td>".$line[$col]."</td>\n";
				$col+=1;
			}
			print "\t\t</tr>\n";
		}

	}
	print "\t</tbody>\n";
	echo	"</table>\n";
	fclose($file);
	//print "<script>alert(1);</script>"
	echo "
		<script>
		$(document).ready(function() {
				$('#example').dataTable();

				$('#example tbody').on('click', 'tr', function () {
					var name = $('td', this).eq(0).text();
					//alert( 'You clicked on '+name+'\'s row' );
					} );
				} );
	</script>";
}
//USE:EG
//orz_datatable("data.txt");
?>
