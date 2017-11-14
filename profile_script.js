(function() {

	var page    = document.querySelector('#page'),
        p_max   = document.querySelector('#p_max'),
		last	= document.querySelector('#last'),
		next	= document.querySelector('#next');
	
	if (page.value >= p_max.value)
		next.setAttribute('hidden', 'hidden');
	if (page.value == 1)
		last.setAttribute('hidden', 'hidden');


})();

function ft_del() {
	var conf    = document.querySelector('#conf');

	ret = confirm("Etes-vous sur de vouloir supprimer la photo?");
	conf.setAttribute('value', ret);
}