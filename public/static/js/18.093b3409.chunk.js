(this["webpackJsonpmember-backend"]=this["webpackJsonpmember-backend"]||[]).push([[18],{1e3:function(e,t,n){"use strict";var a=n(22),r=n.n(a),c=n(40),u=n(182),s=n(14),i=new function e(){Object(u.a)(this,e),this.list=Object(c.a)(r.a.mark((function e(){var t;return r.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,s.a.get("/api/v1/branches");case 2:return t=e.sent,e.abrupt("return",t);case 4:case"end":return e.stop()}}),e)}))),this.create=function(){var e=Object(c.a)(r.a.mark((function e(t){var n;return r.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,s.a.post("/api/v1/branches",t);case 2:return n=e.sent,e.abrupt("return",n);case 4:case"end":return e.stop()}}),e)})));return function(t){return e.apply(this,arguments)}}(),this.get=function(){var e=Object(c.a)(r.a.mark((function e(t){var n;return r.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,s.a.get("/api/v1/branches/".concat(t));case 2:return n=e.sent,e.abrupt("return",n);case 4:case"end":return e.stop()}}),e)})));return function(t){return e.apply(this,arguments)}}(),this.update=function(){var e=Object(c.a)(r.a.mark((function e(t,n){var a;return r.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,s.a.patch("/api/v1/branches/".concat(t),n);case 2:return a=e.sent,e.abrupt("return",a);case 4:case"end":return e.stop()}}),e)})));return function(t,n){return e.apply(this,arguments)}}(),this.delete=function(){var e=Object(c.a)(r.a.mark((function e(t){var n;return r.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,s.a.delete("/api/v1/branches/".concat(t));case 2:return n=e.sent,e.abrupt("return",n);case 4:case"end":return e.stop()}}),e)})));return function(t){return e.apply(this,arguments)}}()};t.a=i},1018:function(e,t,n){"use strict";n.d(t,"a",(function(){return p}));var a=n(22),r=n.n(a),c=n(40),u=n(57),s=n(0),i=n(972),o=n(975),l=n(997);function p(){var e=Object(s.useState)([]),t=Object(u.a)(e,2),n=t[0],a=t[1],p=Object(o.a)(),b=Object(i.a)().i18n.language,f=Object(s.useCallback)(Object(c.a)(r.a.mark((function e(){var t;return r.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,l.a.list();case 2:t=e.sent,a(t.data.data);case 4:case"end":return e.stop()}}),e)}))),[p]),m=Object(s.useCallback)(function(){var e=Object(c.a)(r.a.mark((function e(t){return r.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,l.a.delete(t);case 2:f();case 3:case"end":return e.stop()}}),e)})));return function(t){return e.apply(this,arguments)}}(),[f]),d=Object(s.useCallback)((function(e){switch(b){case"zh-TW":case"zh-ZN":return"".concat(e.last_name).concat(e.first_name);default:return"".concat(e.first_name," ").concat(e.last_name)}}),[b]);return Object(s.useEffect)((function(){f()}),[f]),{members:n,deleteMember:m,getMemberFullName:d}}},1055:function(e,t,n){"use strict";n.d(t,"a",(function(){return l}));var a=n(22),r=n.n(a),c=n(40),u=n(57),s=n(0),i=n(975),o=n(1e3);function l(){var e=Object(s.useState)([]),t=Object(u.a)(e,2),n=t[0],a=t[1],l=Object(i.a)(),p=Object(s.useCallback)(Object(c.a)(r.a.mark((function e(){var t;return r.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,o.a.list();case 2:t=e.sent,a(t.data.data);case 4:case"end":return e.stop()}}),e)}))),[l]),b=Object(s.useCallback)(function(){var e=Object(c.a)(r.a.mark((function e(t){return r.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,o.a.delete(t);case 2:p();case 3:case"end":return e.stop()}}),e)})));return function(t){return e.apply(this,arguments)}}(),[p]);return Object(s.useEffect)((function(){p()}),[p]),{branches:n,deleteBranch:b}}},1109:function(e,t,n){"use strict";var a=n(126),r=n(0),c=n.n(r),u=n(1289),s=n(924);t.a=function(e){var t=e.textFieldProps,n=Object(a.a)(e,["textFieldProps"]);n.label,n.required,n.variant,n.name,n.getOptionLabel;return c.a.createElement(u.a,Object.assign({},n,{renderInput:function(e){return c.a.createElement(s.a,Object.assign({},e,t))}}))}},1271:function(e,t,n){"use strict";n.r(t);var a=n(0),r=n.n(a),c=n(485),u=n(943),s=n(301),i=n(972),o=n(976),l=n(126),p=n(74),b=n(2),f=n(969),m=n(951),d=n(73),h=n(978),v=n.n(h),O=Object(c.a)((function(){return{root:{}}}));var j=function(e){var t=e.className,n=Object(l.a)(e,["className"]),a=O(),c=Object(i.a)().t;return r.a.createElement("div",Object.assign({className:Object(b.a)(a.root,t)},n),r.a.createElement(f.a,{separator:r.a.createElement(v.a,{fontSize:"small"}),"aria-label":"breadcrumb"},r.a.createElement(m.a,{variant:"body1",color:"inherit",to:"/",component:p.a},c("Dashboard")),r.a.createElement(d.a,{variant:"body1",color:"textPrimary"},c("Add Chops"))),r.a.createElement(d.a,{variant:"h3",color:"textPrimary"},c("Add Chops")))},g=n(22),w=n.n(g),x=n(84),E=n(40),k=n(985),S=n(986),y=n(939),C=n(940),_=n(950),N=n(924),P=n(910),T=n(1017),q=n(979),B=n(1109),A=n(182),F=n(14),D=new function e(){Object(A.a)(this,e),this.add=function(){var e=Object(E.a)(w.a.mark((function e(t){var n;return w.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,F.a.post("/api/v1/chops/add",t);case 2:return n=e.sent,e.abrupt("return",n);case 4:case"end":return e.stop()}}),e)})));return function(t){return e.apply(this,arguments)}}()},M=n(1018),R=n(1055),W=Object(c.a)((function(){return{root:{}}}));var L=function(e){var t=e.className,n=Object(l.a)(e,["className"]),a=W(),c=Object(q.a)().successSnackbar,u=Object(i.a)().t,o=Object(M.a)(),p=o.members,f=o.getMemberFullName,m=Object(R.a)().branches;return p.length&&m.length?r.a.createElement(S.a,{initialValues:{member:p[0],branch:m[0],chops:0},validationSchema:k.d().shape({member:k.d().required(),branch:k.d().required(),chops:k.c().min(1).required(u("is required",{name:u("Chops")}))}),onSubmit:function(){var e=Object(E.a)(w.a.mark((function e(t,n){var a,r,s;return w.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return a=n.setErrors,r=n.setStatus,s=n.setSubmitting,e.prev=1,e.next=4,D.add(Object(x.a)({},t,{phone:t.member.phone,branch_id:t.branch.code}));case 4:r({success:!0}),s(!1),c(u("Chops added")),e.next=14;break;case 9:e.prev=9,e.t0=e.catch(1),r({success:!1}),a({submit:e.t0.response.data.message}),s(!1);case 14:case"end":return e.stop()}}),e,null,[[1,9]])})));return function(t,n){return e.apply(this,arguments)}}()},(function(e){var c=e.errors,i=e.handleBlur,o=e.handleChange,l=e.handleSubmit,d=e.isSubmitting,h=e.touched,v=e.values,O=e.setFieldValue;return r.a.createElement("form",Object.assign({className:Object(b.a)(a.root,t),onSubmit:l},n),r.a.createElement(y.a,null,r.a.createElement(C.a,null,c.submit&&r.a.createElement(s.a,{mb:3},r.a.createElement(T.a,{severity:"error"},c.submit)),r.a.createElement(_.a,{container:!0,spacing:3},r.a.createElement(_.a,{item:!0,md:6,xs:12},r.a.createElement(B.a,{options:p,getOptionLabel:function(e){return f(e)},value:v.member,onBlur:i,name:"member",onChange:function(e,t){return O("member",t)},textFieldProps:{required:!0,variant:"outlined",label:u("Member")}})),r.a.createElement(_.a,{item:!0,md:6,xs:12},r.a.createElement(B.a,{options:m,getOptionLabel:function(e){return"".concat(e.name)},value:v.branch,onBlur:i,name:"branch",onChange:function(e,t){return O("branch",t)},textFieldProps:{required:!0,variant:"outlined",label:u("Branch")}})),r.a.createElement(_.a,{item:!0,md:6,xs:12},r.a.createElement(N.a,{error:Boolean(h.chops&&c.chops),fullWidth:!0,helperText:h.chops&&c.chops,label:u("Chops"),name:"chops",type:"number",onBlur:i,onChange:o,required:!0,value:v.chops,variant:"outlined"}))),r.a.createElement(s.a,{mt:2},r.a.createElement(P.a,{variant:"contained",color:"secondary",type:"submit",disabled:d},u("Submit"))))))})):null},z=Object(c.a)((function(e){return{root:{backgroundColor:e.palette.background.dark,minHeight:"100%",paddingTop:e.spacing(3),paddingBottom:e.spacing(3)}}}));t.default=function(){var e=z(),t=Object(i.a)().t;return r.a.createElement(o.a,{className:e.root,title:t("Add Chops")},r.a.createElement(u.a,{maxWidth:"lg"},r.a.createElement(j,null),r.a.createElement(s.a,{mt:3},r.a.createElement(L,null))))}},975:function(e,t,n){"use strict";n.d(t,"a",(function(){return r}));var a=n(0);function r(){var e=Object(a.useRef)(!0);return Object(a.useEffect)((function(){return function(){e.current=!1}}),[]),e}},976:function(e,t,n){"use strict";var a=n(126),r=n(0),c=n.n(r),u=n(318),s=n(49);function i(){var e;window.gtag&&(e=window).gtag.apply(e,arguments)}var o={pageview:function(e){i("config",Object({NODE_ENV:"production",PUBLIC_URL:"",WDS_SOCKET_HOST:void 0,WDS_SOCKET_PATH:void 0,WDS_SOCKET_PORT:void 0}).REACT_APP_GA_MEASUREMENT_ID,e)},event:function(e,t){i("event",e,t)}},l=Object(r.forwardRef)((function(e,t){var n=e.title,i=e.children,l=Object(a.a)(e,["title","children"]),p=Object(s.h)(),b=Object(r.useCallback)((function(){o.pageview({page_path:p.pathname})}),[p]);return Object(r.useEffect)((function(){b()}),[b]),c.a.createElement("div",Object.assign({ref:t},l),c.a.createElement(u.Helmet,null,c.a.createElement("title",null,n)),i)}));t.a=l},979:function(e,t,n){"use strict";n.d(t,"a",(function(){return r}));n(0);var a=n(184);function r(){var e=Object(a.useSnackbar)().enqueueSnackbar;return{successSnackbar:function(t){e(t,{variant:"success",anchorOrigin:{vertical:"bottom",horizontal:"right"}})}}}},997:function(e,t,n){"use strict";var a=n(22),r=n.n(a),c=n(40),u=n(182),s=n(14),i=new function e(){Object(u.a)(this,e),this.list=Object(c.a)(r.a.mark((function e(){var t;return r.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,s.a.get("/api/v1/members");case 2:return t=e.sent,e.abrupt("return",t);case 4:case"end":return e.stop()}}),e)}))),this.create=function(){var e=Object(c.a)(r.a.mark((function e(t){var n;return r.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,s.a.post("/api/v1/members",t);case 2:return n=e.sent,e.abrupt("return",n);case 4:case"end":return e.stop()}}),e)})));return function(t){return e.apply(this,arguments)}}(),this.get=function(){var e=Object(c.a)(r.a.mark((function e(t){var n;return r.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,s.a.get("/api/v1/members/".concat(t));case 2:return n=e.sent,e.abrupt("return",n);case 4:case"end":return e.stop()}}),e)})));return function(t){return e.apply(this,arguments)}}(),this.update=function(){var e=Object(c.a)(r.a.mark((function e(t,n){var a;return r.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,s.a.patch("/api/v1/members/".concat(t),n);case 2:return a=e.sent,e.abrupt("return",a);case 4:case"end":return e.stop()}}),e)})));return function(t,n){return e.apply(this,arguments)}}(),this.delete=function(){var e=Object(c.a)(r.a.mark((function e(t){var n;return r.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,s.a.delete("/api/v1/members/".concat(t));case 2:return n=e.sent,e.abrupt("return",n);case 4:case"end":return e.stop()}}),e)})));return function(t){return e.apply(this,arguments)}}(),this.detail=function(){var e=Object(c.a)(r.a.mark((function e(t){var n;return r.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,s.a.get("/api/v1/members/".concat(t,"/detail"));case 2:return n=e.sent,e.abrupt("return",n);case 4:case"end":return e.stop()}}),e)})));return function(t){return e.apply(this,arguments)}}()};t.a=i}}]);
//# sourceMappingURL=18.093b3409.chunk.js.map