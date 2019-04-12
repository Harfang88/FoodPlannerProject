var app = {

    init : function() {
        app.createAddStepButton();
        app.createAddIngredientButton();
    },

    createAddStepButton: function() {

        let $collectionHolder;

        // setup an "add a step" button
        let $addStepButton = $('<button type="button" class="add_step_link btn btn-primary btn-sm"><i class="fas fa-plus-circle"></i> Ajouter une étape</button>');

        let $newLinkLi = $('<li></li>').append($addStepButton);

         // Get the ul that holds the collection of steps
        $collectionHolder = $('ul.steps');

        // add a delete link to all of the existing step form li elements
        $collectionHolder.find('li').each(function() {
            app.addStepFormDeleteLink($(this));
        });

        // add the "add a step" anchor and li to the steps ul
        $collectionHolder.append($newLinkLi);

        // count the current form inputs we have, use that as the new index when inserting a new item
        $collectionHolder.data('index', $collectionHolder.find(':input').length);

        $addStepButton.on('click', function(e) {
            // add a new step form
            app.addStepForm($collectionHolder, $newLinkLi);
        });
    },

    addStepForm: function($collectionHolder, $newLinkLi) {

        // Get the data-prototype
        let prototype = $collectionHolder.data('prototype');
    
        // get the new index
        let index = $collectionHolder.data('index');
    
        let newForm = prototype;
        // Replace '__name__' in the prototype's HTML to instead be a number based on how many items we have
        newForm = newForm.replace(/__name__/g, index);
    
        // increase the index with one for the next item
        $collectionHolder.data('index', index + 1);
    
        // Display the form in the page in an li, before the "Add a step" link li
        let $newDiv = $('<div />', { 'class':'border rounded bg-secondary p-2'}).append(newForm);
        let $newFormLi = $('<li />', { 'class':'col-12 col-xl-6 p-2'}).append($newDiv);
        $newLinkLi.before($newFormLi);
    
        // add a delete link to the new form
        app.addStepFormDeleteLink($newFormLi);
    },

    addStepFormDeleteLink: function($stepFormLi) {

        let $removeFormButton = $('<a href="#" class="text-danger float-right"><i class="fas fa-trash-alt"></i> Supprimer</a>' );
        
        $stepFormLi.append($removeFormButton);
    
        $removeFormButton.on('click', function(e) {
            e.preventDefault();
            // remove the li for the step form
            $stepFormLi.remove();
        });
    },
    
    createAddIngredientButton: function() {

        let $collectionHolder;

        // setup an "add an ingredient" link
        let $addIngredientButton = $('<button type="button" class="add_ingredient_link btn btn-primary btn-sm"><i class="fas fa-plus-circle"></i> Ajouter un ingrédient</button>');
        let $newLinkLi = $('<li></li>').append($addIngredientButton);

        // Get the ul that holds the collection of ingredients
        $collectionHolder = $('ul.ingredients');

        // add a delete link to all of the existing ingredient form li elements
        $collectionHolder.find('li').each(function() {
            app.addIngredientFormDeleteLink($(this));
        });

        // add the "add a ingredient" anchor and li to the ingredients ul
        $collectionHolder.append($newLinkLi);

        // count the current form inputs we have, use that as the new index when inserting a new item
        $collectionHolder.data('index', $collectionHolder.find(':input').length);

        $addIngredientButton.on('click', function(e) {
            // add a new ingredient form
            app.addIngredientForm($collectionHolder, $newLinkLi);
        });
    },

    addIngredientForm: function($collectionHolder, $newLinkLi) {

        // Get the data-prototype
        let prototype = $collectionHolder.data('prototype');
    
        // get the new index
        let index = $collectionHolder.data('index');
    
        let newForm = prototype;
        // Replace '__name__' in the prototype's HTML to instead be a number based on how many items we have
        newForm = newForm.replace(/__name__/g, index);
    
        // increase the index with one for the next item
        $collectionHolder.data('index', index + 1);
    
        // Display the form in the page in an li, before the "Add a ingredient" link li
        let $newDiv = $('<div />', { 'class':'border rounded bg-secondary p-2'}).append(newForm);
        let $newFormLi = $('<li />', { 'class':'col-12 col-xl-6'}).append($newDiv);
        $newLinkLi.before($newFormLi);
    
        // add a delete link to the new form
        app.addIngredientFormDeleteLink($newFormLi);
    },
    
    
    addIngredientFormDeleteLink: function($stepFormLi) {
        let $removeFormButton2 = $('<a href="#" class="text-danger float-right"><i class="fas fa-trash-alt"></i> Supprimer</a>' );

        $stepFormLi.append($removeFormButton2);
    
        $removeFormButton2.on('click', function(e) {
            e.preventDefault();
            // remove the li for the step form
            $stepFormLi.remove();
        });
    }

}

$(app.init);

