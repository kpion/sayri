/**
 * Wszystko co dotyczy obsługi kalendarza, używane przez widok calendar/calendar.php
 * @param {type} from
 * 
 * @param {type} userId
 * @returns {Calendar}
 */
function Calendar(from,userId){
	/**
	 * Pobranie eventów, w callbacku wyląduje JSON
	 */
	this.getEvents=function(userId,from,callback){
		$.post(baseUrl+"homepage/ajax",{'action':'getEvents','userId':userId,'from':from},function(d){
			var data = $.parseJSON(d);
			if(data.error != '') {
				alert(data.error);
				return;
			}
			//gdy ok:
			if(typeof data.debugLog!='undefined')
				console.log('Debug Log z ajaxa :',data.debugLog);
			callback(data.events);
		});
	}
	/**
	 * Czyszczenie wszystkich eventów
	 */
	this.clearEvents=function(){
		$('.calendar .day .event').remove();
	}

	/**
	 * Wyświetlenie podanych eventów, tzn. wypełnienie divów .day
	 */
	this.displayEvents=function(events){
		$.each(events,function(){
			var dateFrom=this.fromDate;
			var details=JSON.stringify(this);
			var htmlTitle='Od '+this.from+' - '+this.periodHours+" godzin\n"+this.description;
			var data={title:this.title,'details':details,'htmlTitle':htmlTitle,'elementId':'event'+this.id};
			var html='<div class="event" id="^elementId^" data-details=\'^details^\' title=\'^htmlTitle^\'><div class="titleWrap"><span class="delete" title="Usuń"><img src="'+baseUrl+'assets/images/icons/delete85.png"></span><span class="title">^title^</span></div></div>';
			html=Utils.template(html,data);
			var selector=".calendar .dayWrap[data-date='"+dateFrom+"'] .day";
			$(selector).append(html);
			//$('#'+'event'+this.id).draggable();
		});
	};

	/**
	 * Wszystkie operacje w jednym - pobranie i wyświetlenie eventów
	 */
	this.getAndDisplayEvents=function(userId,from,callback){
		var calendar=this;
		this.getEvents(userId,from,function(events){
			calendar.clearEvents();
			calendar.displayEvents(events);
			callback(events);
		});
	}
	
	/**
	 * Dodanie lub edycja istniejącego eventu
	 */
	this.addOrUpdate=function(data,callback){
		$.post(baseUrl+"homepage/ajax",{'action':'addOrUpdate','data':data},function(d){
			var data = $.parseJSON(d);
			if(data.error != '') {
				alert(data.error);
				return;
			}
			//gdy ok:
			if(typeof data.debugLog!='undefined')
				console.log('Debug Log z ajaxa :',data.debugLog);
			callback();
		});
	};

	/**
	 * Usunięcie eventu
	 */
	this.delete=function(id,callback){
		$.post(baseUrl+"homepage/ajax",{'action':'delete','id':id},function(d){
			var data = $.parseJSON(d);
			if(data.error != '') {
				alert(data.error);
				return;
			}
			//gdy ok:
			if(typeof data.debugLog!='undefined')
				console.log('Debug Log z ajaxa :',data.debugLog);
			callback();
		});
	}
	/**
	 * Inicjalizacja
	 */
	this.init=function(){
		var calendar=this;
		calendar.getAndDisplayEvents(userId,from,function(events){
		});
		$(function(){
			$(".editEventDlg form input[name=from]").datetimepicker({
				lang:'pl',

				i18n:{
				 pl:{
				  months:[
				   'Styczeń','Luty','Marzec','Kwiecień',
				   'Maj','Czerwiec','Lipiec','Sierpień',
				   'Wrzesień','Październik','Listopad','Grudzień',
				  ],
				  dayOfWeek:[
				   "Po", "Wt", "Śr", "Czw", 
				   "Pia", "So", "Nie",
				  ]
				 }
				},
				timepicker:true,
				format:'Y-m-d h:i:s'
			   });
			//edycja istniejącego zdarzenia
			$( ".calendar .dayWrap .day" ).on( "click", ".event", function() {
				var $form=$(".editEventDlg form");
				var eventData=JSON.parse($(this).closest('.event').attr('data-details'));
				$form.find('[name=id]').val(eventData.id),
				$form.find('[name=title]').val(eventData.title),
				$form.find('[name=description]').val(eventData.description),
				$form.find('[name=from]').val(eventData.from),
				$form.find('[name=periodHours]').val(eventData.periodHours),
				$(".editEventDlg").dialog({title:'Edycja zdarzenia',width:500,show: 'fade'});
				console.log('eventData:',eventData);
			});

			//dodanie nowego zdarzenia
			$(' .calendar .dayWrap .add').click(function(){
				var $form=$(".editEventDlg form");
				var date=$(this).closest('.dayWrap').data('date');
				$form.find('input,textarea,select').val(''),
				$form.find('select[name=periodHours]').val('1');
				$form.find('[name=from]').val(date),
				//console.log(date);
				$(".editEventDlg").dialog({title:'Dodanie zdarzenia',width:500,show: 'fade'});
			});


			$(".editEventDlg .save").click(function(){
				var $form=$(".editEventDlg form");
				var data={
					userId:userId,
					id:$form.find('[name=id]').val(),
					title:$form.find('[name=title]').val(),
					description:$form.find('[name=description]').val(),
					from:$form.find('[name=from]').val(),
					periodHours:$form.find('[name=periodHours]').val(),
				};
				if(data.title==''){
					alert('Proszę uzupełnić przynajmniej tytuł');
					return false;
				}
				calendar.addOrUpdate(data,function(){
					calendar.getAndDisplayEvents(userId,from,function(events){
						$(".editEventDlg").dialog('close');
					});
				});
				return false;
			});


			$(".editEventDlg .cancel").click(function(){
				$(".editEventDlg").dialog('close');
				return false;
			});


			$('.dataSelector select').change(function(){
				$(this).closest('.dataSelector').find('.info').text('Czekaj...');
				var year=$(this).closest('.dataSelector').find('.year').val();
				var month=$(this).closest('.dataSelector').find('.month').val();
				if(month<10)
					month='0'+month;
				var date=year+'-'+month+'-01';
				var userId=$(this).closest('.dataSelector').find('.userId').val();
				window.location.replace(baseUrl+'homepage/index/'+date+'/'+userId);
				return false;
			});


			//usunięcie zdarzenia
			$('.calendar .day').on( "click", ".delete", function() {
				if(!confirm('Na pewno usunąć to zdarzenie?'))
					return false;
				var $form=$(".editEventDlg form");
				var eventData=JSON.parse($(this).closest('.event').attr('data-details'));
				console.log('delete, eventData:',eventData);
				calendar.delete(eventData.id,function(){
					calendar.getAndDisplayEvents(userId,from,function(events){
					});
				});			
				return false;
			});
		});
	}
	this.init();
}
