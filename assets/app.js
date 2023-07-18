// import { Draggable } from '@shopify/draggable';


// const draggable = new Draggable(document.querySelectorAll('ul'), {
//     draggable: 'li'
    
// });

// console.log('coucou', draggable);
function dragStart(event) {
    event.dataTransfer.setData("Text", event.target.id);
    document.getElementById("demo").innerHTML = "Dragging started";
  }
  
  function dragEnd(event) {
    document.getElementById("demo").innerHTML = "Dragging ended.";
  }
  
  function allowDrop(event) {
    event.preventDefault();
  }
  
  function drop(event) {
    event.preventDefault();
    const data = event.dataTransfer.getData("Text");
    event.target.appendChild(document.getElementById(data));
  }
