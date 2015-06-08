<?php
$bu=Url::base();
?>
<form class='pure-form dataSelector'>
	<?php
	$allUsers=User::getAll();
	echo '<label>Użytkownik: </label>';
	echo "<select class='userId'>";
	foreach($allUsers as $u){
		$selected='';
		$userDisplay=$u['login'];
		if($u['id']==$userId)
			$selected='selected="selected"';
		if($u['id']==User::cur()['id'])
			$userDisplay='Ja - '.$userDisplay;
		echo "<option value='{$u['id']}' $selected>{$userDisplay}</option>";
	}
	echo "</select> ";
	$curYear=date('Y',strtotime($from));
	$curMonth=date('m',strtotime($from));
	echo '<label>Rok: </label>';
	$to=(int)date('Y');
	echo "<select class='year'>";
	for($year=2015;$year<=$to+10;$year++){
		$selected='';
		if($year==$curYear)
			$selected='selected="selected"';
		echo "<option value='{$year}' $selected>$year</option>";
	};
	echo "</select>";
	echo ' <label>Miesiąc: </label>';
	echo "<select class='month'>";
	$months=['styczeń','luty','marzec','kwiecień','maj','czerwiec','lipiec','sierpień','wrzesień','listopad','grudzień'];
	$nmonth=1;
	foreach($months as $month){
		$selected='';
		if($nmonth==$curMonth)
			$selected='selected="selected"';		
		echo "<option value='{$nmonth}' $selected>{$month}</option>";
		$nmonth++;
	}
	echo "</select>";
	?>
	<!--<button class='pure-button pure-button-primary submit'>Pokaż</button>-->
	<span class='info'></span>
</form>
<div class="calendar">
	<?php
	
	for($d=1;$d<=32;$d++){
		if($d<10){
			$d='0'.$d;
		}
		$date=date("Y-m-{$d}",strtotime($from));
		if(!checkdate(date('m',strtotime($date)),$d,date('Y',strtotime($date))))
			break;
		echo "<div class='dayWrap' data-date='$date'>";
		$dayOfWeek=[
			   "Pn", "Wt", "Śr", "Czw", 
			   "Pia", "So", "Nie",
		];
		$dayOfWeek=$dayOfWeek[date('N',strtotime($date))-1];
		$header="$date ({$dayOfWeek})";
		echo "<div class='dayHeader'>$header</div>";
		echo "<div class='day'>";
		echo "</div>";
		//echo "<div class='dayFooter'><a href='javascript:;' class='add' title='Dodaj nowe zdarzenie'>+</a></div>";
		echo "<div class='dayFooter'>";
		echo "<a href='javascript:;' class='add' title='Dodaj nowe zdarzenie'><img src='{$bu}assets/images/icons/round75.png'></a>";
		echo '</div>';
		echo "</div>";//enf of dayWrap
		if($d%7==0){
			echo "<div class='clear'></div>";
		}
	}
	?>	
	<div class='clear'></div>
</div>

<div class="editEventDlg">
	<form class="pure-form pure-form-aligned cform">
		<fieldset>
			<input type="hidden" name="id" value="">
			<div class="row">
				<label>Tytuł:</label>
				<input name="title" value="" type='text'>
			</div>
			<div class="row">
				<label>Opis:</label>
				<textarea name="description"></textarea>
			</div>
			<div class="row">
				<label>Start:</label>
				<input name="from" value="" type='text'>
			</div>
			<div class="row">
				<label>Czas trwania:</label>
				<select name='periodHours'>
					<?php
					for($n=0;$n<=24;$n++)
						echo "<option value='$n'>$n</option>";
					?>
				</select> Godzin.
			</div>

			<button class="pure-button save">Zapisz</button>
			<button class="pure-button cancel">Anuluj</button>
		</fieldset>
	</form>
</div>

<script>
	var userId=<?=$userId?>;
	var calendar=new Calendar('<?=$from?>',userId);
	
</script>