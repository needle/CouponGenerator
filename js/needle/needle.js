Validation.add('validate-rand_len', 'String length must be between 3 and 10.', {
       min : 3,
       max : 10,
       include : ['validate-digits']
    });