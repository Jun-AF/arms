$(document).ready(function() {
	let url = window.location.pathname;
	let sidebar_lsit = $('#accordionSidebar li');
	if (url.match(/dashboard/)) {
		sidebar_lsit.eq(0).addClass("active");
		sidebar_lsit.eq(1).removeClass("active");
		sidebar_lsit.eq(2).removeClass("active");
		sidebar_lsit.eq(3).removeClass("active");
	} else if (url.match(/office.*/)) {
		sidebar_lsit.eq(0).removeClass("active");
		sidebar_lsit.eq(1).addClass("active");
		sidebar_lsit.eq(2).removeClass("active");
		sidebar_lsit.eq(3).removeClass("active");
	} else if (url.match(/admin.*|person.*/)) {
		sidebar_lsit.eq(0).removeClass("active");
		sidebar_lsit.eq(1).removeClass("active");
		sidebar_lsit.eq(2).addClass("active");
		sidebar_lsit.eq(3).removeClass("active");
	} else if (url.match(/asset.*|transaction.*|validation.*/)) {
		sidebar_lsit.eq(0).removeClass("active");
		sidebar_lsit.eq(1).removeClass("active");
		sidebar_lsit.eq(2).removeClass("active");
		sidebar_lsit.eq(3).addClass("active");
	} else if (url.match(/settings.*/)) {
		sidebar_lsit.eq(0).removeClass("active");
		sidebar_lsit.eq(1).removeClass("active");
		sidebar_lsit.eq(2).removeClass("active");
		sidebar_lsit.eq(3).removeClass("active");
		sidebar_lsit.eq(4).addClass("active");
	}
});