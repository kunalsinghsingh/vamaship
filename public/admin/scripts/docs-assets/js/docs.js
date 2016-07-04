jQuery(function(){
  // Track downloads
 

  $('#tokenfield-1').tokenfield({
    autocomplete: {
      source: ['red','blue','green','yellow','violet','brown','purple','black','white'],
      delay: 100
    },
    showAutocompleteOnFocus: true,
    delimiter: [',',' ', '-', '_']
  });

  
});