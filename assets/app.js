import { Draggable } from '@shopify/draggable';


const draggable = new Draggable(document.querySelectorAll('ul'), {
    draggable: 'li'
});

//console.log('coucou', draggable);