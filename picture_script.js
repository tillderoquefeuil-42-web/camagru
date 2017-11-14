function ft_picture(name) {
	var select	= document.querySelector('#select'),
		all		= document.getElementById("all").style,
		pic		= document.querySelector('#sel');

	pic.setAttribute("src", name);
	all.opacity = "0.3";
	select.removeAttribute("hidden");
}

function ft_close() {
	var select  = document.querySelector('#select'),
		all     = document.getElementById("all").style;

	select.setAttribute('hidden', 'hidden');
	all.opacity = "1";
}