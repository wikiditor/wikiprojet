(self["webpackChunk"] = self["webpackChunk"] || []).push([["app"],{

/***/ "./assets/app.js":
/*!***********************!*\
  !*** ./assets/app.js ***!
  \***********************/
/***/ (() => {

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
  var data = event.dataTransfer.getData("Text");
  event.target.appendChild(document.getElementById(data));
}

/***/ })

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ var __webpack_exports__ = (__webpack_exec__("./assets/app.js"));
/******/ }
]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiYXBwLmpzIiwibWFwcGluZ3MiOiI7Ozs7Ozs7O0FBQUE7O0FBR0E7QUFDQTs7QUFFQTs7QUFFQTtBQUNBLFNBQVNBLFNBQVNBLENBQUNDLEtBQUssRUFBRTtFQUN0QkEsS0FBSyxDQUFDQyxZQUFZLENBQUNDLE9BQU8sQ0FBQyxNQUFNLEVBQUVGLEtBQUssQ0FBQ0csTUFBTSxDQUFDQyxFQUFFLENBQUM7RUFDbkRDLFFBQVEsQ0FBQ0MsY0FBYyxDQUFDLE1BQU0sQ0FBQyxDQUFDQyxTQUFTLEdBQUcsa0JBQWtCO0FBQ2hFO0FBRUEsU0FBU0MsT0FBT0EsQ0FBQ1IsS0FBSyxFQUFFO0VBQ3RCSyxRQUFRLENBQUNDLGNBQWMsQ0FBQyxNQUFNLENBQUMsQ0FBQ0MsU0FBUyxHQUFHLGlCQUFpQjtBQUMvRDtBQUVBLFNBQVNFLFNBQVNBLENBQUNULEtBQUssRUFBRTtFQUN4QkEsS0FBSyxDQUFDVSxjQUFjLENBQUMsQ0FBQztBQUN4QjtBQUVBLFNBQVNDLElBQUlBLENBQUNYLEtBQUssRUFBRTtFQUNuQkEsS0FBSyxDQUFDVSxjQUFjLENBQUMsQ0FBQztFQUN0QixJQUFNRSxJQUFJLEdBQUdaLEtBQUssQ0FBQ0MsWUFBWSxDQUFDWSxPQUFPLENBQUMsTUFBTSxDQUFDO0VBQy9DYixLQUFLLENBQUNHLE1BQU0sQ0FBQ1csV0FBVyxDQUFDVCxRQUFRLENBQUNDLGNBQWMsQ0FBQ00sSUFBSSxDQUFDLENBQUM7QUFDekQiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvYXBwLmpzIl0sInNvdXJjZXNDb250ZW50IjpbIi8vIGltcG9ydCB7IERyYWdnYWJsZSB9IGZyb20gJ0BzaG9waWZ5L2RyYWdnYWJsZSc7XG5cblxuLy8gY29uc3QgZHJhZ2dhYmxlID0gbmV3IERyYWdnYWJsZShkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKCd1bCcpLCB7XG4vLyAgICAgZHJhZ2dhYmxlOiAnbGknXG4gICAgXG4vLyB9KTtcblxuLy8gY29uc29sZS5sb2coJ2NvdWNvdScsIGRyYWdnYWJsZSk7XG5mdW5jdGlvbiBkcmFnU3RhcnQoZXZlbnQpIHtcbiAgICBldmVudC5kYXRhVHJhbnNmZXIuc2V0RGF0YShcIlRleHRcIiwgZXZlbnQudGFyZ2V0LmlkKTtcbiAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZChcImRlbW9cIikuaW5uZXJIVE1MID0gXCJEcmFnZ2luZyBzdGFydGVkXCI7XG4gIH1cbiAgXG4gIGZ1bmN0aW9uIGRyYWdFbmQoZXZlbnQpIHtcbiAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZChcImRlbW9cIikuaW5uZXJIVE1MID0gXCJEcmFnZ2luZyBlbmRlZC5cIjtcbiAgfVxuICBcbiAgZnVuY3Rpb24gYWxsb3dEcm9wKGV2ZW50KSB7XG4gICAgZXZlbnQucHJldmVudERlZmF1bHQoKTtcbiAgfVxuICBcbiAgZnVuY3Rpb24gZHJvcChldmVudCkge1xuICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG4gICAgY29uc3QgZGF0YSA9IGV2ZW50LmRhdGFUcmFuc2Zlci5nZXREYXRhKFwiVGV4dFwiKTtcbiAgICBldmVudC50YXJnZXQuYXBwZW5kQ2hpbGQoZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoZGF0YSkpO1xuICB9XG4iXSwibmFtZXMiOlsiZHJhZ1N0YXJ0IiwiZXZlbnQiLCJkYXRhVHJhbnNmZXIiLCJzZXREYXRhIiwidGFyZ2V0IiwiaWQiLCJkb2N1bWVudCIsImdldEVsZW1lbnRCeUlkIiwiaW5uZXJIVE1MIiwiZHJhZ0VuZCIsImFsbG93RHJvcCIsInByZXZlbnREZWZhdWx0IiwiZHJvcCIsImRhdGEiLCJnZXREYXRhIiwiYXBwZW5kQ2hpbGQiXSwic291cmNlUm9vdCI6IiJ9