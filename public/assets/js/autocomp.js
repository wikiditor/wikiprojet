document.addEventListener('DOMContentLoaded', (e) => {
    
    // ajout evenement keyup sur la barre de recherche
    document.getElementById('searchTerm').addEventListener('keyup', (e) =>{

        // récupération du contenu de la barre de recherche
        let searchTerm = e.target.value;

        // lancementr de la requête à l'api de recherche de suggestions (pour l'autocompletion)
        fetch('/API/article/' + searchTerm)
        .then(function(httpResponse) {
            return httpResponse.text();
        })
        .then(function(json) {

            // récupération des suggestions
            const suggestions = JSON.parse(json).suggestions;

            // récupération de l'element HTML dans lequel il faut injecter les suggestions (ul sous la barre de recherche)
            const targetUl = document.getElementById('suggestionsTarget');


            // si la ul de destination existe bel et bien
            if (targetUl) {

                // vidage de la ul si recherche précédente
                targetUl.innerHTML = '';

                // pour chaque suggestion
                suggestions.forEach(suggestion => {
    
                    // on crée un élément de type li (vide)
                    let li = document.createElement('li');

                    // on lui rajoute un paramètre "data-searchTerm" avec la suggestion
                    li.dataset['searchTerm'] = suggestion;

                    // ajoute dans la li la suggestion (pour affichage)
                    li.innerHTML = suggestion;

                    // ajoute un evenement click sur la li
                    li.addEventListener('click', (e) => {

                        // récupération du terme a rechercher
                        searchTerm = e.target.dataset['searchTerm'];

                        document.location.replace('\\article\\' + searchTerm + '\\fr' );
                    });

                    // ajoute la li finale à la ul de destination
                    targetUl.appendChild(li);
                });
            }
        });
    });


});