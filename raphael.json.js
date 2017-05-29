
(function() {
	Raphael.fn.toJSON = function(callback) {
		var
			data,
			elements = new Array,
			paper    = this
			;

		for ( var el = paper.bottom; el != null; el = el.next ) {
			data = callback ? callback(el, new Object) : new Object;
			
			var temp = JSON.stringify(el.attrs);
			if(temp.indexOf('stroke-dasharray') + 1) continue;
			
			if ( data ) elements.push({
				data:      data,
				type:      el.type,
				attrs:     el.attrs,
				transform: el.matrix.toTransformString(),
				});
		}

		return JSON.stringify(elements);
	}

	Raphael.fn.fromJSON = function(json, callback) {
		var
			el,
			paper = this
			;

		if ( typeof json === 'string' ) json = JSON.parse(json);

		for ( var i in json ) {
			if ( json.hasOwnProperty(i) ) {
				el = paper[json[i].type]()
					.attr(json[i].attrs)
					.transform(json[i].transform)
					;
				
				drawChooseRect(el.getBBox()).element = el;
				
				if ( callback ) el = callback(el, json[i].data);

				if ( el ) paper.set().push(el);
			}
		}
	}
})();
