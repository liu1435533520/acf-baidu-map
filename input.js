(function($){
	
	
	/**
	*  initialize_field
	*
	*  This function will initialize the $field.
	*
	*  @date	06/6/19
	*  @since	1.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/
	
	function initialize_field($field) {
		//$field.doStuff();
		let mapOption = {};
		mapOption.zoom = $field.find('.acf-baidu-map').attr('data-zoom');
		mapOption.latp = $field.find('.acf-baidu-map').attr('data-lat');
		mapOption.lngp = $field.find('.acf-baidu-map').attr('data-lng');
		mapOption.lat = $field.find('input[data-name="lat"]')[0];
		mapOption.lng = $field.find('input[data-name="lng"]')[0];
		mapOption.targetID = $field.find('.baidu-map-container').attr('id');
		function newBaiduMap(opt) {
			let mp = new BMap.Map(opt.targetID);
			let point = !(opt.lat.value || opt.lng.value) ? new BMap.Point(opt.lngp, opt.latp) : new BMap.Point(opt.lng.value, opt.lat.value);
			let marker = new BMap.Marker(point);
			let mapBox = document.getElementById(opt.targetID);
			mp.centerAndZoom(point, opt.zoom || 5);
			mp.addOverlay(marker);
			mp.addControl(new BMap.MapTypeControl({
				mapTypes: [
					BMAP_NORMAL_MAP,
					BMAP_HYBRID_MAP
				]
			}));
			mp.enableScrollWheelZoom(true);

			mp.setDefaultCursor("crosshair");   //设置地图默认的鼠标指针样式

			let labelOpts = {
				position: point,    // 指定文本标注所在的地理位置
				offset: new BMap.Size(30, -30)    //设置文本偏移量
			}
			let label = new BMap.Label(point, labelOpts);
			label.setStyle({
				color: "red",
				fontSize: "12px",
				height: "20px",
				lineHeight: "20px",
				fontFamily: "微软雅黑"
			});
			//组件 control-tools
			let top_left_control = new BMap.ScaleControl({ anchor: BMAP_ANCHOR_TOP_LEFT });
			let top_left_navigation = new BMap.NavigationControl();
			// let size = new BMap.Size(10, 10);
			mp.addControl(top_left_control);
			mp.addControl(top_left_navigation);
			// mp.addControl(new BMap.CityListControl({
			// 	anchor: BMAP_ANCHOR_BOTTOM_RIGHT,
			// 	offset: size,
			// }));
			//单击取坐标 click   get lng|lat
			mp.addEventListener("click", function (e) {
				opt.lat.value = e.point.lat;
				opt.lng.value = e.point.lng;
				mp.removeOverlay(marker);
				point = new BMap.Point(e.point.lng, e.point.lat);
				marker = new BMap.Marker(point);
				mp.addOverlay(marker);
			});
			mp.addEventListener("mousemove", function (e) {
				let mlat = e.point.lat;
				let mlng = e.point.lng;
				let opts = {
					position: new BMap.Point(mlng, mlat),    // 指定文本标注所在的地理位置
					offset: new BMap.Size(30, -30)    //设置文本偏移量
				}
				mp.removeOverlay(label);
				label = new BMap.Label('经:' + mlng + '<br>纬:' + mlat, opts);  // 创建文本标注对象
				label.setStyle({
					color: "red",
					fontSize: "12px",
					height: "40px",
					lineHeight: "20px",
					fontFamily: "微软雅黑"
				});
				mp.addOverlay(label);
			});
			mp.addEventListener("mouseout", function (e) {
				mp.removeOverlay(label);
			})
			//鼠标移入阻止滚轮默认事件
			mapBox.onmousewheel = scrollFunc;//IE/Opera/Chrome
			if (mapBox.addEventListener) {
				mapBox.addEventListener('DOMMouseScroll', scrollFunc, false);
			}
			function scrollFunc(evt) {
				let ev = evt || window.event;
				if (ev.preventDefault) {
					ev.preventDefault();
					ev.stopPropagation();
				}
				return false;
			}
		}
		newBaiduMap(mapOption);
	}

		/*
		*  ready & append (ACF5)
		*  These two events are called when a field element is ready for initizliation.
		*  - ready: on page load similar to $(document).ready()
		*  - append: on new DOM elements appended via repeater field or other AJAX calls
		*  @param	n/a
		*  @return	n/a
		*/
		acf.add_action('ready_field/type=baidu_map', initialize_field);
		acf.add_action('append_field/type=baidu_map', initialize_field);

})(jQuery);
