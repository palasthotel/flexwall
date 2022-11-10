(function($, fw){

	fw._request = function(method, params, success){
		return $.ajax({
			url: fw.api.url,
			data: Object.assign(fw.api.params, {method: method}, params),
			method: 'POST',
			xhrFields: {
				withCredentials: true
			},
		}).done(function(data){
			if(typeof success === "function") success(data);
		});
	};

	fw._get = function(method, params){
		return $.ajax({
			url: fw.api.url,
			data: Object.assign(fw.api.params, {method: method}, params),
			method: 'POST',
			async: false,
			xhrFields: {
				withCredentials: true
			},
		}).responseJSON;
	};

	fw.login = function(res){
		fw._request("login",{}, res);
	};

	fw.logout = function(res){
		fw._request("logout",{}, res);
	};

	fw.isLoggedIn = function(res){
		fw._request("isLoggedIn",{}, res);
	}

})(jQuery, FlexWall);