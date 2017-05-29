

var POINT_SIZE = 1;

var POINT_COLOR = '#f00';


var whiteBoard = null;

Event.observe(window, 'load', function( ) {
	whiteBoard = new jsGraphics('whiteBoard');
	whiteBoard.setColor(POINT_COLOR);
	whiteBoard.setStroke(POINT_SIZE);
	Event.observe('whiteBoard', 'mousedown', StartDrawing);
	Event.observe('whiteBoard', 'mouseup', StopDrawing);
});


function StartDrawing(e) {
	if (Event.element(e).id == 'whiteBoard')
		DrawPoint(Event.pointerX(e), Event.pointerY(e));
	Event.observe('whiteBoard', 'mousemove', ContinueDrawing);
}


function StopDrawing(e) {

	Event.stopObserving('whiteBoard', 'mousemove', ContinueDrawing);
	lastPointX = -1;
	lastPointY = -1;
}


function ContinueDrawing(e) {
	if (Event.element(e).id == 'whiteBoard')
		DrawPoint(Event.pointerX(e), Event.pointerY(e));
}