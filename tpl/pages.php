<div id="pages">

<table style="font-size: 40px; float: left;">
	<tr>
	<td><img src="./img/arrow_left.gif"></td>
	<td class="page_links"><span>1</span></td>
	<td class="page_links"><span>2</span></td>
	<td class="page_links"><span>3</span></td> 
	<td class="page_links"><span>4</span></td>
	<td class="page_links"><span>5</span></td>
	<td class="page_links"><span>6</span></td>
	<td class="page_links"><span>7</span></td>
	<td><img src="./img/arrow_right.gif"></td>	
	</tr>
</table> 

</div>

<script>

$(".page_links").click(function() {
	$("#page").data('page', $(this).text());
	LoadPage($(this).text());
});

</script>