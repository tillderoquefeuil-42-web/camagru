(function() {

	var page    = document.querySelector('#page'),
        p_max   = document.querySelector('#p_max'),
		last	= document.querySelector('#last'),
		next	= document.querySelector('#next'),
		fpage    = document.querySelector('#fpage'),
        fp_max   = document.querySelector('#fp_max'),
        flast    = document.querySelector('#flast'),
        fnext    = document.querySelector('#fnext');
	
	if (fpage.value >= fp_max.value)
        fnext.setAttribute('hidden', 'hidden');
    if (fpage.value == 1)
        flast.setAttribute('hidden', 'hidden');

	if (page.value >= p_max.value)
		next.setAttribute('hidden', 'hidden');
	if (page.value == 1)
		last.setAttribute('hidden', 'hidden');


})();
