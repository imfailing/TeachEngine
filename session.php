<?php
include 'auth.php';
$id=$_GET['id'];
$userdata = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE id = '".intval($_COOKIE['id'])."' LIMIT 1"));
$sessiondata = mysql_fetch_assoc(mysql_query("SELECT * FROM session WHERE id = '".$id."' LIMIT 1"));
?>

<html>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<head>
    <title>Семинар</title>
	<link type="text/css" rel="stylesheet" href="session.css">	
	<link type="text/css" rel="stylesheet" href="jquery-ui.css">
	
	<script src="jquery.js"></script>
	<script type="text/javascript" src="jquery-ui-1.8.13.custom.min.js"></script>
	<script type="text/javascript" src="raphael-min.js"></script>
	<script type="text/javascript" src="swfobject.js"></script>
	<script src="http://192.168.1.200:8082/socket.io/socket.io.js"></script>

	<script type="text/javascript" src="icons.js"></script>
	<script type="text/javascript" src="raphael.json.js"></script>
	<script src="client.js"></script>	
</head>

<body>
<header class="cf">
<nav>
	<ul>
		<li id="task">
			<a id="task-trigger" href="#">
				Задание <span>&#x25BC;</span>
			</a>
			<div id="task-content">
				<div id="tasks">
				Задания пока отсутствуют.
				</div>
			</div>                     
		</li>
		<li id="chat">
			<a href="">Чат &#x25BC;</a>
		</li>
		<li id="sound">
			<a id="sound-trigger" href="#">
				Видео<span>&#x25BC;</span>
				<div id="task-content">
			</div>  
			</a>
		</li>		
	</ul>
</nav>
</header>

	<div class="info message">
		 <h3></h3>
		 <p>Можете продолжить работу.</p>
	</div>
	<div class="error message">
		 <h3>Вам закрыт доступ к семинару!</h3>
		 <p>Можете закрыть окно.</p>
	</div>

	<div id="tools" style="margin-left: 20px;">
		<input type="button" value="Указка" onclick="switchToMode('clearMode', 'default'); restoreWhiteboard();">
		<input type="button" value="Маркер" onclick="switchToMode('paintMode', 'pointer');">
		<input type="button" value="Линия" onclick="switchToMode('lineMode', 'pointer');">
		<input type="button" value="Вставить элемент" onclick="switchToMode('objectMode', 'crosshair');">
		<input type="button" value="Вставить сетевое устройство" onclick="switchToMode('netobjectMode', 'crosshair');">
		<input type="button" value="Вставить текст" onclick="switchToMode('textMode', 'text');">
		<input type="button" value="Перенос элементов" onclick="switchToMode('moveMode', 'default');">
		<input type="button" value="Удаление элементов" onclick="switchToMode('deleteMode', 'crosshair');">
		<input type="button" value="Очистить доску" onclick="switchToMode('clearMode', 'default'); clearWhiteboard();">
	</div>

	<div id="whiteboard"></div>

	<div id="dialogNetobjects" title="Выберите сетевое устройство">
		<div id="NetobjectsArea"></div>
	</div>
	<div id="dialogIcons" title="Добавить векторный объект">
		<div id="iconsArea"></div>
	</div>
	<div id="dialogInputText" title="Добавить текст">
		<textarea id="textArea"></textarea>
	</div>
	<div id="dialogVideo" title="Видео">
		<p id="container">Please install the Flash Plugin</p>
	</div>
	<div id="dialogChat" title="Окно чата">
	<div id="log"></div>
   <input type="text" id="input" autofocus>
   </div>

<script type="text/javascript">
	var myMessages = ['info','error','success'];
	var room=<?php echo $id?>;
	var userid=<?php echo $_COOKIE['id']?>;
	var mystate= {canpaint:0, canchat:0, state:0, peer:0};
	var whiteboard = jQuery("#whiteboard");
	var list = Raphael("whiteboard", whiteboard.width(), whiteboard.height());
	var json;
	
	var offsetLeft = whiteboard.offset().left;
	var offsetTop = whiteboard.offset().top;
	jQuery.extend(whiteboard, {
		"lineEl": {"path": null, "pathArray": null},
		"iconEl": {"cx": 0, "cy": 0},
		"textEl": {"cx": 0, "cy": 0},
		"netobjectEl": {"cx": 0, "cy": 0},
	});
	
	function showMessage(type)
	{
	$('.'+ type +'-trigger').click(function(){
		  hideAllMessages();				  
		  $('.'+type).animate({top:"0"}, 500);
	});
	}
		
	function hideAllMessages()
	{
		 var messagesHeights = new Array();
	 
		 for (i=0; i<myMessages.length; i++)
		 {
				  messagesHeights[i] = $('.' + myMessages[i]).outerHeight(); 
				  $('.' + myMessages[i]).css('top', -messagesHeights[i]); 	  
		 }
	}
	

	var dialogIcons = jQuery("#dialogIcons");
	var dialogNetobjects = jQuery("#dialogNetobjects");
	var dialogInputText = jQuery("#dialogInputText");
	var dialogVideo = jQuery("#dialogVideo");
	var dialogChat = jQuery("#dialogChat");	
	var modeNow = {
		"paintMode": false,
		"lineMode": false,
		"rectangleMode": false,
		"circleMode": false,
		"netobjectMode": false,
		"objectMode": false,
		"textMode": false,
		"moveMode": false,
		"deleteMode": false,
		"clearMode": false,
		"noneMode": false
	};

	var setstate = function() {
		if(mystate['state']==-1)
		{
			switchToMode('noneMode', 'default')
			$('.error h3').text("У Вас больше нет доступа к этому семинару!");
			$('.error').animate({top:"0"}, 500);
			$('.cf').hide();
			$('#whiteboard').hide();
			dialogChat.dialog( "close" );
			dialogVideo.dialog( "close" );
		} 
		if(mystate['canpaint']==0)
		{
			switchToMode('noneMode', 'default')			
			$('#tools').hide();
		} else
		{
			$('#tools').show();
			$('.info h3').text("Вам передана возможность пользоваться интерактивной доской!");
			$('.info').animate({top:"0"}, 500);
		}
		if(mystate['canchat']==0)
		{
			$('.error h3').text("У Вас больше нет доступа к чату!");
			$('.error').animate({top:"0"}, 500);
			$('#input').hide();
		} else
		{
			$('#input').show();
		}
	};
	
	var sendChanges = function() {
		json = list.toJSON();
		socket.emit('whiteboard', json);
	};
	
	
	// Перетасиквание
	var startElement = function () {
		if (!modeNow.moveMode) {
			return false;
		}

		this.odx = 0;
		this.ody = 0;
		this.attr("cursor", "move");
		this.element.toFront();
		this.toFront();
	},
	moveElement = function (dx, dy) {
		if (!modeNow.moveMode) {
			return false;
		}

		this.element.translate(dx - this.odx, dy - this.ody);
		this.translate(dx - this.odx, dy - this.ody);
		this.odx = dx;
		this.ody = dy;
	},
	stopElement = function () {
		if (!modeNow.moveMode) {
			return false;
		}
		this.attr("cursor", "default");
		sendChanges();
	};
	
	// Обводка элементов
	var chooseRectElement = function (event) {
		if (!modeNow.moveMode && !modeNow.deleteMode) {
			this.attr("cursor", whiteboard.css("cursor"));
			return true;
		}

		if (modeNow.moveMode) {
			this.attr({"stroke": "#0D0BF5", "stroke-opacity": 0.8, "fill": "#0276FD", "fill-opacity": 0.2});
			this.attr("cursor", "move");
		} else {
			this.attr({"stroke": "#FF0000", "stroke-opacity": 0.8, "fill": "#FF0000", "fill-opacity": 0.2});
			this.attr("cursor", "crosshair");
		}
	},
	chooseRectOutElement = function (event) {
		if (!modeNow.moveMode && !modeNow.deleteMode) {
			return true;

		}

		this.attr({"stroke-opacity": 0, "fill-opacity": 0});
		if (modeNow.moveMode) {
			this.attr("cursor", "default");
		}
	};

	// регистр хенд клику
	var clickElement = function(event) {
		if (!modeNow.deleteMode) {
			return false;
		}

		this.element.remove();
		this.remove();
		sendChanges();
	};
	
	// ап даун муве
	var mousedownHandler = function (event) {
		if (modeNow.paintMode) {
			whiteboard.lineEl.path = list.path("M" + (event.pageX - offsetLeft) + "," + (event.pageY - offsetTop));
			whiteboard.lineEl.path.attr({stroke: "#000000", "stroke-width": 3});
			whiteboard.bind("mousemove.mmu", mousemoveHandler);
			whiteboard.one("mouseup.mmu", mouseupHandler);
		} else if (modeNow.lineMode) {
			whiteboard.lineEl.pathArray = [];
			whiteboard.lineEl.pathArray[0] = ["M", event.pageX - offsetLeft, event.pageY - offsetTop];
			whiteboard.lineEl.path = list.path(whiteboard.lineEl.pathArray);
			whiteboard.lineEl.path.attr({stroke: "#000000", "stroke-width": 3});
			whiteboard.bind("mousemove.mmu", mousemoveHandler);
			whiteboard.one("mouseup.mmu", mouseupHandler);
		}

		return false;
	},
	mousemoveHandler = function (event) {
		if (modeNow.paintMode) {
			whiteboard.lineEl.path.attr("path", whiteboard.lineEl.path.attr("path") + "L" + (event.pageX - offsetLeft) + "," + (event.pageY - offsetTop));
		} else if (modeNow.lineMode) {
			whiteboard.lineEl.pathArray[1] = ["L", event.pageX - offsetLeft, event.pageY - offsetTop];
			whiteboard.lineEl.path.attr("path", whiteboard.lineEl.pathArray);
		}
	},
	mouseupHandler = function () {
		whiteboard.unbind(".mmu");
		if (whiteboard.lineEl.path) {
			drawChooseRect(whiteboard.lineEl.path.getBBox()).element = whiteboard.lineEl.path;
			sendChanges();
			whiteboard.lineEl.path = null;
			whiteboard.lineEl.pathArray = null;
		}
	};

	// клик
	var clickHandler = function(event) {
		if (modeNow.objectMode) {
			whiteboard.iconEl.cx = event.pageX - offsetLeft;
			whiteboard.iconEl.cy = event.pageY - offsetTop;
			dialogIcons.dialog("open");
		} else if (modeNow.textMode) {
			whiteboard.textEl.cx = event.pageX - offsetLeft;
			whiteboard.textEl.cy = event.pageY - offsetTop;
			dialogInputText.dialog("open");
		} else if (modeNow.netobjectMode) {
			whiteboard.netobjectEl.cx = event.pageX - offsetLeft;
			whiteboard.netobjectEl.cy = event.pageY - offsetTop;
			dialogNetobjects.dialog("open");
		
		} else {
			return false;
		}
	};
	
	// лив 
	var mouseleaveHandler = function(event) {
		if (modeNow.paintMode || modeNow.lineMode) {
			mouseupHandler();
		}
		
		return false;
	};

	
	whiteboard.bind("click", clickHandler);
	whiteboard.bind("mousedown", mousedownHandler);
	whiteboard.bind("mouseleave", mouseleaveHandler);
	
	// жикверикнопки
	jQuery(":button").button();

	// прорисовка меню объектов иконок
	var x = 0, y = 0;
	var fillStroke = {fill: "#000", stroke: "none"};
	var fiilNone = {fill: "#000", opacity: 0};
	var fillHover = {fill: "90-#0050af-#002c62", stroke: "#FF0000"};
	var iconlist = Raphael("iconsArea", 600, 360);

	for (var name in whiteboardIcons) {
		var currentIcon = iconlist.path(whiteboardIcons[name]).attr(fillStroke).translate(x, y);
		currentIcon.offsetX = x + 20;
		currentIcon.offsetY = y + 20;
		var overlayIcon = iconlist.rect(x, y, 40, 40).attr(fiilNone);
		overlayIcon.icon = currentIcon;
		overlayIcon.click(function () {
			dialogIcons.dialog("close");
			var iconElement = list.path(this.icon.attr("path")).attr(fillStroke).translate(whiteboard.iconEl.cx , whiteboard.iconEl.cy );
			drawChooseRect(iconElement.getBBox()).element = iconElement;
			sendChanges();

		}).hover(function () {
			this.icon.attr(fillHover);
		}, function () {
			this.icon.attr(fillStroke);
		});
		x += 40;
		if (x > 560) {
			x = 0;
			y += 40;
		}
	}
	
	//Сетевые объекты
	x = 0; y = 0;
	var netobjectlist = Raphael("NetobjectsArea", 640, 60);
	
	for (var name in NetObjects) {
		var currentNetobject = netobjectlist.image(NetObjects[name], x, y, 60, 60);
		currentNetobject.offsetX = x + 20;
		currentNetobject.offsetY = y + 20;
		//var overlayNetobject = netobjectlist.rect(x, y, 40, 40);
		//overlayNetObject.icon = currentNetobject;
		currentNetobject.click(function () {
			dialogNetobjects.dialog("close");
			var NetObjectElement = list.image(this.attr("src"), whiteboard.netobjectEl.cx , whiteboard.netobjectEl.cy , this.attr("width") , this.attr("height"));
			drawChooseRect(NetObjectElement.getBBox()).element = NetObjectElement;
			sendChanges();

		});
		x += 60+10;
		if (x > 640) {
			x = 0;
			y += 60+10;
		}
	}

	// жиквери сетевые объекты диалог	
	dialogNetobjects.dialog({
		width: 100,
		height: 100,
		modal: false,
		autoOpen: false,
		resizable: false,
		buttons: {
			//Close: function() {
				//dialogIcons.dialog("close");
			//}
		},
		// fix for scrollbars in IE
		open: function(event, ui){
			//jQuery('body').css('overflow','hidden');
			//jQuery('.ui-widget-overlay').css('width','100%');
		},
		close: function(event, ui){
			//jQuery('body').css('overflow','auto');
		}
	});
	
	// жиквери иконки диалог
	dialogIcons.dialog({
		width: 100,
		modal: true,
		autoOpen: false,
		resizable: false,
		buttons: {
			//Close: function() {
				//dialogIcons.dialog("close");
			//}
		},
		// fix for scrollbars in IE
		open: function(event, ui){
			jQuery('body').css('overflow','hidden');
			jQuery('.ui-widget-overlay').css('width','100%');
		},
		close: function(event, ui){
			jQuery('body').css('overflow','auto');
		}
	});
	
	
	
	// ввод текста
	dialogInputText.dialog({
		modal: false,
		autoOpen: false,
		resizable: false,
		buttons: {
			Добавить: function() {
				var inputText = dialogInputText.find("#textArea").val();
				dialogInputText.dialog("close");
				if (inputText !== "") {
					var textElement = list.text(whiteboard.textEl.cx, whiteboard.textEl.cy, inputText);
					textElement.attr("font-size", "18");
					drawChooseRect(textElement.getBBox()).element = textElement;
					sendChanges();
				}
			}
		},
		open: function(event, ui){
			jQuery('body').css('overflow','hidden');
			jQuery('.ui-widget-overlay').css('width','100%');
		},
		close: function(event, ui){
			jQuery('body').css('overflow','auto');
			dialogInputText.find("#textArea").val('');
		}
	});
	
	dialogVideo.dialog({
		width: 320,
		height: 290,
		modal: false,
		autoOpen: true,
		resizable: false,
		buttons: {

		},
		open: function(event, ui){
//			jQuery('body').css('overflow','hidden');
			//jQuery('.ui-widget-overlay').css('width','100%');
		},
		close: function(event, ui){
			//jQuery('body').css('overflow','auto');
			//dialogInputText.find("#textArea").val('');
		}
	});
	
	dialogChat.dialog({
		width: 1000,
		height: 320,
		modal: false,
		autoOpen: true,
		resizable: false,
		buttons: {

		},
		open: function(event, ui){
//			jQuery('body').css('overflow','hidden');
			//jQuery('.ui-widget-overlay').css('width','100%');
		},
		close: function(event, ui){
			//jQuery('body').css('overflow','auto');
			//dialogInputText.find("#textArea").val('');
		}
	});
	
	
	function drawChooseRect(bbox) {
		// обводка элемента
		var helperRect = list.rect(bbox.x - 1, bbox.y - 1, (bbox.width !== 0 ? bbox.width + 2 : 3), (bbox.height !== 0 ? bbox.height + 2 : 3));
		helperRect.attr({"stroke": "#0D0BF5", "stroke-width": "2", "stroke-dasharray": "-", "stroke-opacity": 0, "fill": "#0276FD", "fill-opacity": 0});
		helperRect.hover(chooseRectElement, chooseRectOutElement);
		helperRect.click(clickElement);
		helperRect.drag(moveElement, startElement, stopElement);
		return helperRect;
	}
	
	function clearWhiteboard() {
		list.clear();
		sendChanges();
		
	}
	
	function restoreWhiteboard() {
		list.fromJSON(json);
	}
	
	function switchToMode(mode, cursor) {
		for (var name in modeNow) {
			modeNow[name] = false;
		}
		modeNow[mode] = true;
		whiteboard.css("cursor", cursor);
		
	}
	
	function check(form)
	{
		socket.emit('checktask', {id: form.taskid.value, key: form.keyword.value , 'userid': userid});
		return false;
	}
	
	$(document).ready(function(){
				$('#task-trigger').click(function(){
					$.get('task.php', { sessionid: room, 'userid': userid } , function(data){$("#task-content").html(data);});
					$(this).next('#task-content').slideToggle();
					$(this).toggleClass('active');					
					
					if ($(this).hasClass('active')) $(this).find('span').html('&#x25B2;')
						else $(this).find('span').html('&#x25BC;')
					})
				$('#sound-trigger').click(function(){
					$(this).next('#sound-content').slideToggle();
					$(this).toggleClass('active');					
					
					if ($(this).hasClass('active')) $(this).find('span').html('&#x25B2;')
						else $(this).find('span').html('&#x25BC;')
					})	

					var flashvars = { streamer:'rtmp://192.168.1.200/flvplayback/',file:'<?php echo $id?>',autostart:'false',bufferlength:'0' };  var params = { allowfullscreen:'true', allowscriptaccess:'always' };
					var attributes = { id:'player1', name:'player1' };
					swfobject.embedSWF('player.swf','container','320','240','9.0.115','false',flashvars, params, attributes);					
					hideAllMessages();

					for(var i=0;i<myMessages.length;i++)
					{
						showMessage(myMessages[i]);
					}

					$('.message').click(function(){			  
					$(this).animate({top: -$(this).outerHeight()}, 500);
					
		  });
	});
</script>

</body>
</html>
