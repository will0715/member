(this["webpackJsonpmember-backend"]=this["webpackJsonpmember-backend"]||[]).push([[15],{1e3:function(e,t,n){"use strict";var r=n(22),a=n.n(r),c=n(40),o=n(182),i=n(14),l=new function e(){Object(o.a)(this,e),this.list=Object(c.a)(a.a.mark((function e(){var t;return a.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,i.a.get("/api/v1/branches");case 2:return t=e.sent,e.abrupt("return",t);case 4:case"end":return e.stop()}}),e)}))),this.create=function(){var e=Object(c.a)(a.a.mark((function e(t){var n;return a.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,i.a.post("/api/v1/branches",t);case 2:return n=e.sent,e.abrupt("return",n);case 4:case"end":return e.stop()}}),e)})));return function(t){return e.apply(this,arguments)}}(),this.get=function(){var e=Object(c.a)(a.a.mark((function e(t){var n;return a.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,i.a.get("/api/v1/branches/".concat(t));case 2:return n=e.sent,e.abrupt("return",n);case 4:case"end":return e.stop()}}),e)})));return function(t){return e.apply(this,arguments)}}(),this.update=function(){var e=Object(c.a)(a.a.mark((function e(t,n){var r;return a.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,i.a.patch("/api/v1/branches/".concat(t),n);case 2:return r=e.sent,e.abrupt("return",r);case 4:case"end":return e.stop()}}),e)})));return function(t,n){return e.apply(this,arguments)}}(),this.delete=function(){var e=Object(c.a)(a.a.mark((function e(t){var n;return a.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,i.a.delete("/api/v1/branches/".concat(t));case 2:return n=e.sent,e.abrupt("return",n);case 4:case"end":return e.stop()}}),e)})));return function(t){return e.apply(this,arguments)}}()};t.a=l},1006:function(e,t,n){"use strict";var r=n(0),a=n.n(r),c=n(910),o=n(944),i=n(945),l=n(946),s=n(947),u=n(948),p=n(972);t.a=function(e){var t=e.open,n=e.handleClose,r=e.handleDelete,f=e.checkText,m=Object(p.a)().t;return a.a.createElement(o.a,{open:t,onClose:n,"aria-labelledby":"alert-dialog-title","aria-describedby":"alert-dialog-description"},a.a.createElement(u.a,{id:"alert-dialog-title"},m("Delete Check")),a.a.createElement(l.a,null,a.a.createElement(s.a,{id:"alert-dialog-description"},m("Delete")," : ".concat(f," ?"))),a.a.createElement(i.a,null,a.a.createElement(c.a,{onClick:n,color:"primary"},m("Cancel")),a.a.createElement(c.a,{onClick:r,color:"primary",autoFocus:!0},m("Submit"))))}},1052:function(e,t,n){"use strict";var r=n(0),a=n.n(r),c=n(4),o=n.n(c);function i(){return(i=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e}).apply(this,arguments)}function l(e,t){if(null==e)return{};var n,r,a=function(e,t){if(null==e)return{};var n,r,a={},c=Object.keys(e);for(r=0;r<c.length;r++)n=c[r],t.indexOf(n)>=0||(a[n]=e[n]);return a}(e,t);if(Object.getOwnPropertySymbols){var c=Object.getOwnPropertySymbols(e);for(r=0;r<c.length;r++)n=c[r],t.indexOf(n)>=0||Object.prototype.propertyIsEnumerable.call(e,n)&&(a[n]=e[n])}return a}var s=function(e){var t=e.color,n=e.size,r=l(e,["color","size"]);return a.a.createElement("svg",i({xmlns:"http://www.w3.org/2000/svg",width:n,height:n,viewBox:"0 0 24 24",fill:"none",stroke:t,strokeWidth:"2",strokeLinecap:"round",strokeLinejoin:"round"},r),a.a.createElement("circle",{cx:"12",cy:"12",r:"10"}),a.a.createElement("line",{x1:"12",y1:"8",x2:"12",y2:"16"}),a.a.createElement("line",{x1:"8",y1:"12",x2:"16",y2:"12"}))};s.propTypes={color:o.a.string,size:o.a.oneOfType([o.a.string,o.a.number])},s.defaultProps={color:"currentColor",size:"24"},t.a=s},1053:function(e,t,n){"use strict";var r=n(0),a=n.n(r),c=n(4),o=n.n(c);function i(){return(i=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e}).apply(this,arguments)}function l(e,t){if(null==e)return{};var n,r,a=function(e,t){if(null==e)return{};var n,r,a={},c=Object.keys(e);for(r=0;r<c.length;r++)n=c[r],t.indexOf(n)>=0||(a[n]=e[n]);return a}(e,t);if(Object.getOwnPropertySymbols){var c=Object.getOwnPropertySymbols(e);for(r=0;r<c.length;r++)n=c[r],t.indexOf(n)>=0||Object.prototype.propertyIsEnumerable.call(e,n)&&(a[n]=e[n])}return a}var s=function(e){var t=e.color,n=e.size,r=l(e,["color","size"]);return a.a.createElement("svg",i({xmlns:"http://www.w3.org/2000/svg",width:n,height:n,viewBox:"0 0 24 24",fill:"none",stroke:t,strokeWidth:"2",strokeLinecap:"round",strokeLinejoin:"round"},r),a.a.createElement("path",{d:"M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"}),a.a.createElement("path",{d:"M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"}))};s.propTypes={color:o.a.string,size:o.a.oneOfType([o.a.string,o.a.number])},s.defaultProps={color:"currentColor",size:"24"},t.a=s},1054:function(e,t,n){"use strict";var r=n(0),a=n.n(r),c=n(4),o=n.n(c);function i(){return(i=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e}).apply(this,arguments)}function l(e,t){if(null==e)return{};var n,r,a=function(e,t){if(null==e)return{};var n,r,a={},c=Object.keys(e);for(r=0;r<c.length;r++)n=c[r],t.indexOf(n)>=0||(a[n]=e[n]);return a}(e,t);if(Object.getOwnPropertySymbols){var c=Object.getOwnPropertySymbols(e);for(r=0;r<c.length;r++)n=c[r],t.indexOf(n)>=0||Object.prototype.propertyIsEnumerable.call(e,n)&&(a[n]=e[n])}return a}var s=function(e){var t=e.color,n=e.size,r=l(e,["color","size"]);return a.a.createElement("svg",i({xmlns:"http://www.w3.org/2000/svg",width:n,height:n,viewBox:"0 0 24 24",fill:"none",stroke:t,strokeWidth:"2",strokeLinecap:"round",strokeLinejoin:"round"},r),a.a.createElement("polyline",{points:"3 6 5 6 21 6"}),a.a.createElement("path",{d:"M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"}))};s.propTypes={color:o.a.string,size:o.a.oneOfType([o.a.string,o.a.number])},s.defaultProps={color:"currentColor",size:"24"},t.a=s},1055:function(e,t,n){"use strict";n.d(t,"a",(function(){return u}));var r=n(22),a=n.n(r),c=n(40),o=n(57),i=n(0),l=n(975),s=n(1e3);function u(){var e=Object(i.useState)([]),t=Object(o.a)(e,2),n=t[0],r=t[1],u=Object(l.a)(),p=Object(i.useCallback)(Object(c.a)(a.a.mark((function e(){var t;return a.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,s.a.list();case 2:t=e.sent,r(t.data.data);case 4:case"end":return e.stop()}}),e)}))),[u]),f=Object(i.useCallback)(function(){var e=Object(c.a)(a.a.mark((function e(t){return a.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,s.a.delete(t);case 2:p();case 3:case"end":return e.stop()}}),e)})));return function(t){return e.apply(this,arguments)}}(),[p]);return Object(i.useEffect)((function(){p()}),[p]),{branches:n,deleteBranch:f}}},1293:function(e,t,n){"use strict";n.r(t),n.d(t,"default",(function(){return I}));var r=n(22),a=n.n(r),c=n(40),o=n(57),i=n(0),l=n.n(i),s=n(74),u=n(984),p=n.n(u),f=n(485),m=n(538),b=n(231),d=n(943),v=n(301),h=n(1053),O=n(1054),g=n(972),E=(n(1e3),n(1006)),y=n(1055),j=(n(975),n(976)),w=n(126),k=n(2),x=n(950),S=n(969),C=n(951),P=n(73),z=n(910),T=n(978),L=n.n(T),_=n(1052),B=Object(f.a)((function(e){return{root:{},action:{marginBottom:e.spacing(1),"& + &":{marginLeft:e.spacing(1)}},actionIcon:{marginRight:e.spacing(1)}}}));var N=function(e){var t=e.className,n=Object(w.a)(e,["className"]),r=B(),a=Object(g.a)().t;return l.a.createElement(x.a,Object.assign({className:Object(k.a)(r.root,t),container:!0,justify:"space-between",spacing:3},n),l.a.createElement(x.a,{item:!0},l.a.createElement(S.a,{separator:l.a.createElement(L.a,{fontSize:"small"}),"aria-label":"breadcrumb"},l.a.createElement(C.a,{variant:"body1",color:"inherit",to:"/",component:s.a},a("Dashboard")),l.a.createElement(P.a,{variant:"body1",color:"textPrimary"},a("Branch List"))),l.a.createElement(P.a,{variant:"h3",color:"textPrimary"},a("Branch List")),l.a.createElement(v.a,{mt:2})),l.a.createElement(x.a,{item:!0},l.a.createElement(z.a,{color:"secondary",variant:"contained",className:r.action,to:"/branches/create",component:s.a},l.a.createElement(b.a,{fontSize:"small",className:r.actionIcon},l.a.createElement(_.a,null)),a("Create Branch"))))},D=Object(f.a)((function(e){return{root:{backgroundColor:e.palette.background.dark,minHeight:"100%",paddingTop:e.spacing(3),paddingBottom:e.spacing(3)}}}));function I(){var e=Object(g.a)().t,t=Object(y.a)(),n=t.branches,r=t.deleteBranch,i=D(),u=l.a.useState(!1),f=Object(o.a)(u,2),w=f[0],k=f[1],x=l.a.useState({}),S=Object(o.a)(x,2),C=S[0],P=S[1],z=function(){var e=Object(c.a)(a.a.mark((function e(){return a.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,r(C.id);case 2:k(!1),P({});case 4:case"end":return e.stop()}}),e)})));return function(){return e.apply(this,arguments)}}(),T=[{title:e("Name"),field:"name"},{title:e("Code"),field:"code"},{title:e("Store Name"),field:"store_name"},{title:e("Actions"),field:"actions",render:function(e){return l.a.createElement(l.a.Fragment,null,l.a.createElement(m.a,{component:s.a,to:"/branches/".concat(e.id,"/edit")},l.a.createElement(b.a,{fontSize:"small"},l.a.createElement(h.a,null))),l.a.createElement(m.a,{onClick:function(){return t=e,console.log(t),P(t),void k(!0);var t}},l.a.createElement(b.a,{fontSize:"small"},l.a.createElement(O.a,null))))}}];return n?l.a.createElement(j.a,{className:i.root,title:e("Branch List")},l.a.createElement(d.a,{maxWidth:!1},l.a.createElement(N,null),n&&l.a.createElement(v.a,{mt:3},l.a.createElement(p.a,{title:e("Branch List"),columns:T,data:n}))),l.a.createElement(E.a,{open:w,handleClose:function(){k(!1)},handleDelete:z,checkText:"".concat(C.name," (").concat(C.code,")")})):null}},975:function(e,t,n){"use strict";n.d(t,"a",(function(){return a}));var r=n(0);function a(){var e=Object(r.useRef)(!0);return Object(r.useEffect)((function(){return function(){e.current=!1}}),[]),e}},976:function(e,t,n){"use strict";var r=n(126),a=n(0),c=n.n(a),o=n(318),i=n(49);function l(){var e;window.gtag&&(e=window).gtag.apply(e,arguments)}var s={pageview:function(e){l("config",Object({NODE_ENV:"production",PUBLIC_URL:"",WDS_SOCKET_HOST:void 0,WDS_SOCKET_PATH:void 0,WDS_SOCKET_PORT:void 0}).REACT_APP_GA_MEASUREMENT_ID,e)},event:function(e,t){l("event",e,t)}},u=Object(a.forwardRef)((function(e,t){var n=e.title,l=e.children,u=Object(r.a)(e,["title","children"]),p=Object(i.h)(),f=Object(a.useCallback)((function(){s.pageview({page_path:p.pathname})}),[p]);return Object(a.useEffect)((function(){f()}),[f]),c.a.createElement("div",Object.assign({ref:t},u),c.a.createElement(o.Helmet,null,c.a.createElement("title",null,n)),l)}));t.a=u}}]);
//# sourceMappingURL=15.81c748ec.chunk.js.map