Validation.addAllThese([
	['validate-rand_len', 'String length must be between 5 and 10.', {
		min : 5,
		max : 10,
		include : ['validate-digits']
	}],
	['validate-quantity', 'Cannot be more than 400.', {
		max : 400,
		include : ['validate-digits']
	}]
]);