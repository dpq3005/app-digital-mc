(window.webpackJsonp=window.webpackJsonp||[]).push([[7],{PTPi:function(t,e,n){"use strict";n.r(e);var i=n("DUip"),o=n("Valr"),r=n("QJY3"),a=n("sNNf"),d=n("JZt4"),l=n("9Z1F"),c=n("6blF"),s=n("TYT/");function b(t,e){if(1&t&&(s.Sb(0,"div",17),s.Hc(1),s.Rb()),2&t){var n=s.ic();s.zb(1),s.Jc(" ",n.errorMessage," ")}}function g(t,e){1&t&&(s.Sb(0,"div",18),s.Sb(1,"h3"),s.Hc(2,"Please wait just a heart beat!"),s.Rb(),s.Sb(3,"div",19),s.Nb(4,"div"),s.Rb(),s.Rb())}function p(t,e){if(1&t){var n=s.Tb();s.Sb(0,"form",20),s.Sb(1,"div",21),s.Sb(2,"div",22),s.Sb(3,"div",23),s.Sb(4,"span",24),s.Nb(5,"i",25),s.Rb(),s.Rb(),s.Sb(6,"input",26,27),s.ec("ngModelChange",(function(t){return s.yc(n),s.ic().credentials.companyCode=t})),s.Rb(),s.Rb(),s.Rb(),s.Sb(8,"div",21),s.Sb(9,"div",22),s.Sb(10,"div",23),s.Sb(11,"span",24),s.Nb(12,"i",28),s.Rb(),s.Rb(),s.Sb(13,"input",29,30),s.ec("ngModelChange",(function(t){return s.yc(n),s.ic().credentials.username=t})),s.Rb(),s.Rb(),s.Rb(),s.Sb(15,"div",31),s.Sb(16,"div",22),s.Sb(17,"div",23),s.Sb(18,"span",24),s.Nb(19,"i",32),s.Rb(),s.Rb(),s.Sb(20,"input",33,34),s.ec("ngModelChange",(function(t){return s.yc(n),s.ic().credentials.password=t})),s.Rb(),s.Rb(),s.Rb(),s.Sb(22,"div",18),s.Sb(23,"button",35),s.ec("click",(function(){return s.yc(n),s.ic().login()})),s.Hc(24,"Sign in"),s.Rb(),s.Rb(),s.Rb()}if(2&t){var i=s.ic();s.zb(6),s.oc("ngModel",i.credentials.companyCode),s.zb(7),s.oc("ngModel",i.credentials.username),s.zb(7),s.oc("ngModel",i.credentials.password)}}var u=function(){function t(t,e){this.authService=t,this.router=e,this.loading=!1,this.errorMessage="",this.credentials=new a.b}return t.prototype.ngOnInit=function(){var t=document.getElementsByTagName("body")[0];t.classList.remove("bg-default"),t.classList.add("bg-wellness"),this.authService.isAuthenticated()&&this.router.navigate(["supervisor","dmc","list"])},t.prototype.ngOnDestroy=function(){document.getElementsByTagName("body")[0].classList.remove("bg-wellness")},t.prototype.login=function(){var t=this;this.loading=!0,this.errorMessage="",this.authService.authenticate(this.credentials).pipe(Object(l.a)((function(e,n){return t.loading=!1,t.errorMessage=e.message,new c.a}))).subscribe((function(e){t.loading=!1,localStorage.setItem("token",e.token),localStorage.setItem("benefitProviderUuid",e.benefitProviderUuid),localStorage.setItem("credentials",JSON.stringify(t.credentials));var n=document.getElementsByTagName("body")[0];n.classList.add("bg-default"),n.classList.remove("bg-wellness"),t.router.navigate(["supervisor","dmc","list"])}))},t.\u0275fac=function(e){return new(e||t)(s.Mb(d.a),s.Mb(i.b))},t.\u0275cmp=s.Gb({type:t,selectors:[["app-supervisor-login"]],decls:21,vars:3,consts:[[1,"header","bg-transparent","py-5","py-lg-6"],[1,"container"],[1,"header-body","text-center","mb-5"],[1,"row","justify-content-center"],[1,"col-lg-5","col-md-6"],[1,"container","mt--8","pb-5"],[1,"col-lg-5","col-md-7"],[1,"card","bg-secondary","shadow","border-0"],[1,"card-header","bg-transparent"],[1,"text-center","text-primary"],["href","https://www.magenta-wellness.com",1,"bg-transparent","border-radius-5"],["height","100","src","assets/img/brand/purple.png"],[1,"text-muted","text-center","mt-2"],[1,"card-body","px-lg-5","py-lg-5"],["class","alert alert-danger","role","alert",4,"ngIf"],["class","text-center",4,"ngIf"],["role","form",4,"ngIf"],["role","alert",1,"alert","alert-danger"],[1,"text-center"],[1,"lds-heart"],["role","form"],[1,"form-group","mb-3"],[1,"input-group","input-group-alternative"],[1,"input-group-prepend"],[1,"input-group-text"],[1,"ni","ni-atom"],["placeholder","User Code","type","text","name","company-code",1,"form-control",3,"ngModel","ngModelChange"],["companyCode","ngModel"],[1,"ni","ni-single-02"],["placeholder","Username","type","text","name","username",1,"form-control",3,"ngModel","ngModelChange"],["username","ngModel"],[1,"form-group"],[1,"ni","ni-lock-circle-open"],["placeholder","Password","type","password","name","password",1,"form-control",3,"ngModel","ngModelChange"],["password","ngModel"],["type","button",1,"btn","btn-primary","my-4",3,"click"]],template:function(t,e){1&t&&(s.Sb(0,"div",0),s.Sb(1,"div",1),s.Sb(2,"div",2),s.Sb(3,"div",3),s.Nb(4,"div",4),s.Rb(),s.Rb(),s.Rb(),s.Rb(),s.Sb(5,"div",5),s.Sb(6,"div",3),s.Sb(7,"div",6),s.Sb(8,"div",7),s.Sb(9,"div",8),s.Sb(10,"div",9),s.Sb(11,"a",10),s.Nb(12,"img",11),s.Rb(),s.Sb(13,"h1"),s.Hc(14,"Welcome Supervisor!"),s.Rb(),s.Rb(),s.Sb(15,"div",12),s.Hc(16,"Sign in using your created credentials"),s.Rb(),s.Rb(),s.Sb(17,"div",13),s.Fc(18,b,2,1,"div",14),s.Fc(19,g,5,0,"div",15),s.Fc(20,p,25,3,"form",16),s.Rb(),s.Rb(),s.Rb(),s.Rb(),s.Rb()),2&t&&(s.zb(18),s.oc("ngIf",!e.loading&&e.errorMessage.length>0),s.zb(1),s.oc("ngIf",e.loading),s.zb(1),s.oc("ngIf",!e.loading))},directives:[o.m,r.o,r.g,r.h,r.a,r.f,r.i],styles:["a.bg-white[_ngcontent-%COMP%]:focus, a.bg-white[_ngcontent-%COMP%]:hover, button.bg-white[_ngcontent-%COMP%]:focus, button.bg-white[_ngcontent-%COMP%]:hover{background-color:transparent!important}"]}),t}(),m=function(){function t(t){this.router=t}return t.prototype.ngOnInit=function(){this.router.navigate(["supervisor","dmc","list"])},t.\u0275fac=function(e){return new(e||t)(s.Mb(i.b))},t.\u0275cmp=s.Gb({type:t,selectors:[["app-interim"]],decls:19,vars:0,consts:[[1,"header","bg-gradient-magenta","py-7","py-lg-8"],[1,"container"],[1,"header-body","text-center","mb-5"],[1,"row","justify-content-center"],[1,"col-lg-5","col-md-6"],[1,"separator","separator-bottom","separator-skew","zindex-100"],["x","0","y","0","viewBox","0 0 2560 100","preserveAspectRatio","none","version","1.1","xmlns","http://www.w3.org/2000/svg"],["points","2560 0 2560 100 0 100",1,"fill-default"],[1,"container","mt--10","pb-5"],[1,"col-lg-5","col-md-7"],[1,"card","bg-secondary","shadow","border-0"],[1,"card-header","bg-transparent"],[1,"text-muted","text-center","mt-2"],[1,"card-body","px-lg-5","py-lg-5","text-center"],[1,"lds-heart"]],template:function(t,e){1&t&&(s.Sb(0,"div",0),s.Sb(1,"div",1),s.Sb(2,"div",2),s.Sb(3,"div",3),s.Nb(4,"div",4),s.Rb(),s.Rb(),s.Rb(),s.Sb(5,"div",5),s.hc(),s.Sb(6,"svg",6),s.Nb(7,"polygon",7),s.Rb(),s.Rb(),s.Rb(),s.gc(),s.Sb(8,"div",8),s.Sb(9,"div",3),s.Sb(10,"div",9),s.Sb(11,"div",10),s.Sb(12,"div",11),s.Sb(13,"div",12),s.Sb(14,"h2"),s.Hc(15,"Please wait while the page is being loaded..."),s.Rb(),s.Rb(),s.Rb(),s.Sb(16,"div",13),s.Sb(17,"div",14),s.Nb(18,"div"),s.Rb(),s.Rb(),s.Rb(),s.Rb(),s.Rb(),s.Rb())},styles:['.mt--10[_ngcontent-%COMP%]{margin-top:-10rem!important}.lds-heart[_ngcontent-%COMP%]{display:inline-block;position:relative;width:80px;height:80px;-webkit-transform:rotate(45deg);transform:rotate(45deg);-webkit-transform-origin:40px 40px;transform-origin:40px 40px}.lds-heart[_ngcontent-%COMP%]   div[_ngcontent-%COMP%]{top:32px;left:32px;position:absolute;width:32px;height:32px;background:#fcf;-webkit-animation:lds-heart 1.2s cubic-bezier(.215,.61,.355,1) infinite;animation:lds-heart 1.2s cubic-bezier(.215,.61,.355,1) infinite}.lds-heart[_ngcontent-%COMP%]   div[_ngcontent-%COMP%]:after, .lds-heart[_ngcontent-%COMP%]   div[_ngcontent-%COMP%]:before{content:" ";position:absolute;display:block;width:32px;height:32px;background:#fcf}.lds-heart[_ngcontent-%COMP%]   div[_ngcontent-%COMP%]:before{left:-24px;border-radius:50% 0 0 50%}.lds-heart[_ngcontent-%COMP%]   div[_ngcontent-%COMP%]:after{top:-24px;border-radius:50% 50% 0 0}@-webkit-keyframes lds-heart{0%{-webkit-transform:scale(.95);transform:scale(.95)}5%{-webkit-transform:scale(1.1);transform:scale(1.1)}39%{-webkit-transform:scale(.85);transform:scale(.85)}45%{-webkit-transform:scale(1);transform:scale(1)}60%{-webkit-transform:scale(.95);transform:scale(.95)}to{-webkit-transform:scale(.9);transform:scale(.9)}}@keyframes lds-heart{0%{-webkit-transform:scale(.95);transform:scale(.95)}5%{-webkit-transform:scale(1.1);transform:scale(1.1)}39%{-webkit-transform:scale(.85);transform:scale(.85)}45%{-webkit-transform:scale(1);transform:scale(1)}60%{-webkit-transform:scale(.95);transform:scale(.95)}to{-webkit-transform:scale(.9);transform:scale(.9)}}']}),t}(),f=n("MnXN"),h=n("2MRv");function v(t,e){1&t&&(s.Sb(0,"div",34),s.Sb(1,"h4"),s.Hc(2,"Checking pin code..."),s.Rb(),s.Sb(3,"div",37),s.Nb(4,"div"),s.Nb(5,"div"),s.Nb(6,"div"),s.Nb(7,"div"),s.Nb(8,"div"),s.Nb(9,"div"),s.Nb(10,"div"),s.Nb(11,"div"),s.Rb(),s.Rb())}function M(t,e){if(1&t){var n=s.Tb();s.Sb(0,"button",38),s.ec("click",(function(){return s.yc(n),s.ic().$implicit.close("Dismissed")})),s.Hc(1,"OK "),s.Rb()}}function y(t,e){if(1&t&&(s.Sb(0,"div",28),s.Sb(1,"h4",29),s.Hc(2,"PIN Verification"),s.Rb(),s.Sb(3,"button",30),s.ec("click",(function(){return e.$implicit.dismiss("Cross click")})),s.Sb(4,"span",31),s.Hc(5,"\xd7"),s.Rb(),s.Rb(),s.Rb(),s.Sb(6,"div",32),s.Fc(7,v,12,0,"div",33),s.Sb(8,"div",34),s.Hc(9),s.Rb(),s.Rb(),s.Sb(10,"div",35),s.Fc(11,M,2,0,"button",36),s.Rb()),2&t){var n=s.ic();s.zb(7),s.oc("ngIf",!n.loginStatus),s.zb(2),s.Ic(n.loginStatus),s.zb(2),s.oc("ngIf",n.loginStatus)}}var S=[{path:"supervisor/login",component:u},{path:"merchant/login",component:function(){function t(t,e,n,i){this.modalService=t,this.route=e,this.authService=n,this.router=i,this.closeResult="",this.isLoading=!1,this.errorMessage="",this.credentials=new a.a}return t.prototype.ngOnInit=function(){},t.prototype.verify=function(){var t=this;this.loginStatus=null;var e=this.route.snapshot.queryParamMap.get("uuid");this.credentials.uuid=e,this.credentials.pin=this.digit1+""+this.digit2+this.digit3+this.digit4+this.digit5+this.digit6,this.isLoading=!0,this.errorMessage="",this.authService.authenticate(this.credentials).pipe(Object(l.a)((function(e,n){return t.isLoading=!1,t.loginStatus=t.errorMessage="Invalid PIN enterred",console.log("errrrrrrrrrrrrr ",e),new c.a}))).subscribe((function(e){t.isLoading=!1,localStorage.setItem("token",e.token),localStorage.setItem("merchantUuid",t.credentials.uuid),localStorage.setItem("credentials",JSON.stringify(t.credentials)),t.modalService.dismissAll(),t.router.navigate(["merchant","dmc","list"])}))},t.prototype.openModal=function(t){var e=this;this.modalService.open(t,{ariaLabelledBy:"modal-basic-title"}).result.then((function(t){e.closeResult="Closed with: "+t}),(function(t){e.closeResult="Dismissed "+e.getDismissReason(t)}))},t.prototype.getDismissReason=function(t){return t===f.a.ESC?"by pressing ESC":t===f.a.BACKDROP_CLICK?"by clicking on a backdrop":"with: "+t},t.\u0275fac=function(e){return new(e||t)(s.Mb(f.e),s.Mb(i.a),s.Mb(d.a),s.Mb(i.b))},t.\u0275cmp=s.Gb({type:t,selectors:[["app-merchant-login"]],decls:35,vars:6,consts:[[1,"header","bg-gradient-magenta","py-7","py-lg-8"],[1,"container"],[1,"header-body","text-center","mb-5"],[1,"row","justify-content-center"],[1,"col-12","text-center","mb-3"],["href","https://www.magenta-wellness.com",1,"navbar-brand","bg-white","border-radius-5"],["height","80","src","assets/img/brand/purple.png"],[1,"col-lg-5","col-md-6"],[1,"text-white"],[1,"separator","separator-bottom","separator-skew","zindex-100"],["x","0","y","0","viewBox","0 0 2560 100","preserveAspectRatio","none","version","1.1","xmlns","http://www.w3.org/2000/svg"],["points","2560 0 2560 100 0 100",1,"fill-default"],[1,"container","mt--8","pb-5"],[1,"col-lg-5","col-md-7"],[1,"card","bg-secondary","shadow","border-0"],[1,"card-body","px-lg-5","py-lg-5"],[1,"text-left"],[1,"section","msg-border"],[1,"section","text-center"],["appPinDigit","","type","number","pattern","[0-9]*","id","digit1","name","digit1","data-indx","0","data-next-id","digit2","value","","size","1","maxlength","1","autocomplete","off","placeholder","",1,"ipt_pin","form-control","input-xlarge","digit-input",3,"ngModel","ngModelChange"],["appPinDigit","","type","number","pattern","[0-9]*","id","digit2","name","digit2","data-indx","0","data-next-id","digit2","value","","size","1","maxlength","1","autocomplete","off","placeholder","",1,"ipt_pin","form-control","input-xlarge","digit-input",3,"ngModel","ngModelChange"],["appPinDigit","","type","number","pattern","[0-9]*","id","digit3","name","digit3","data-indx","0","data-next-id","digit2","value","","size","1","maxlength","1","autocomplete","off","placeholder","",1,"ipt_pin","form-control","input-xlarge","digit-input",3,"ngModel","ngModelChange"],["appPinDigit","","type","number","pattern","[0-9]*","id","digit4","name","digit4","data-indx","0","data-next-id","digit2","value","","size","1","maxlength","1","autocomplete","off","placeholder","",1,"ipt_pin","form-control","input-xlarge","digit-input",3,"ngModel","ngModelChange"],["appPinDigit","","type","number","pattern","[0-9]*","id","digit5","name","digit5","data-indx","0","data-next-id","digit2","value","","size","1","maxlength","1","autocomplete","off","placeholder","",1,"ipt_pin","form-control","input-xlarge","digit-input",3,"ngModel","ngModelChange"],["appPinDigit","","type","number","pattern","[0-9]*","id","digit6","name","digit6","data-indx","0","data-next-id","digit2","value","","size","1","maxlength","1","autocomplete","off","placeholder","",1,"ipt_pin","form-control","input-xlarge","digit-input",3,"ngModel","ngModelChange"],[1,"mt-1"],["type","button",1,"form-control","btn","btn-outline-success",3,"click"],["content",""],[1,"modal-header"],["id","modal-basic-title",1,"modal-title"],["type","button","aria-label","Close",1,"close",3,"click"],["aria-hidden","true"],[1,"modal-body"],["class","text-center",4,"ngIf"],[1,"text-center"],[1,"modal-footer"],["type","button","class","btn btn-primary",3,"click",4,"ngIf"],[1,"lds-roller"],["type","button",1,"btn","btn-primary",3,"click"]],template:function(t,e){if(1&t){var n=s.Tb();s.Sb(0,"div",0),s.Sb(1,"div",1),s.Sb(2,"div",2),s.Sb(3,"div",3),s.Sb(4,"div",4),s.Sb(5,"a",5),s.Nb(6,"img",6),s.Rb(),s.Rb(),s.Sb(7,"div",7),s.Sb(8,"h1",8),s.Hc(9,"Please enter your PIN"),s.Rb(),s.Rb(),s.Rb(),s.Rb(),s.Rb(),s.Sb(10,"div",9),s.hc(),s.Sb(11,"svg",10),s.Nb(12,"polygon",11),s.Rb(),s.Rb(),s.Rb(),s.gc(),s.Sb(13,"div",12),s.Sb(14,"div",3),s.Sb(15,"div",13),s.Sb(16,"div",14),s.Sb(17,"div",15),s.Sb(18,"div",16),s.Sb(19,"div",17),s.Hc(20," This screen is meant for a centre staff."),s.Nb(21,"br"),s.Hc(22," Please enter the assigned 6-digit centre pin for verification. "),s.Rb(),s.Sb(23,"div",18),s.Sb(24,"input",19),s.ec("ngModelChange",(function(t){return e.digit1=t})),s.Rb(),s.Sb(25,"input",20),s.ec("ngModelChange",(function(t){return e.digit2=t})),s.Rb(),s.Sb(26,"input",21),s.ec("ngModelChange",(function(t){return e.digit3=t})),s.Rb(),s.Sb(27,"input",22),s.ec("ngModelChange",(function(t){return e.digit4=t})),s.Rb(),s.Sb(28,"input",23),s.ec("ngModelChange",(function(t){return e.digit5=t})),s.Rb(),s.Sb(29,"input",24),s.ec("ngModelChange",(function(t){return e.digit6=t})),s.Rb(),s.Rb(),s.Sb(30,"div",25),s.Sb(31,"button",26),s.ec("click",(function(){s.yc(n);var t=s.xc(34);return e.openModal(t),e.verify()})),s.Hc(32," VERIFY "),s.Rb(),s.Rb(),s.Rb(),s.Fc(33,y,12,3,"ng-template",null,27,s.Gc),s.Rb(),s.Rb(),s.Rb(),s.Rb(),s.Rb()}2&t&&(s.zb(24),s.oc("ngModel",e.digit1),s.zb(1),s.oc("ngModel",e.digit2),s.zb(1),s.oc("ngModel",e.digit3),s.zb(1),s.oc("ngModel",e.digit4),s.zb(1),s.oc("ngModel",e.digit5),s.zb(1),s.oc("ngModel",e.digit6))},directives:[r.k,r.a,h.a,r.l,r.c,r.f,r.i,o.m],styles:['.input-xlarge[_ngcontent-%COMP%]{height:56px;font-size:x-large;border-radius:6px}.ipt_pin[_ngcontent-%COMP%]{width:35px;text-align:center;display:inline-block;padding:1px;margin:2px}.lds-roller[_ngcontent-%COMP%]{display:inline-block;position:relative;width:80px;height:80px}.lds-roller[_ngcontent-%COMP%]   div[_ngcontent-%COMP%]{-webkit-animation:lds-roller 1.2s cubic-bezier(.5,0,.5,1) infinite;animation:lds-roller 1.2s cubic-bezier(.5,0,.5,1) infinite;-webkit-transform-origin:40px 40px;transform-origin:40px 40px}.lds-roller[_ngcontent-%COMP%]   div[_ngcontent-%COMP%]:after{content:" ";display:block;position:absolute;width:7px;height:7px;border-radius:50%;background:#8f00ff;margin:-4px 0 0 -4px}.lds-roller[_ngcontent-%COMP%]   div[_ngcontent-%COMP%]:first-child{-webkit-animation-delay:-36ms;animation-delay:-36ms}.lds-roller[_ngcontent-%COMP%]   div[_ngcontent-%COMP%]:first-child:after{top:63px;left:63px}.lds-roller[_ngcontent-%COMP%]   div[_ngcontent-%COMP%]:nth-child(2){-webkit-animation-delay:-72ms;animation-delay:-72ms}.lds-roller[_ngcontent-%COMP%]   div[_ngcontent-%COMP%]:nth-child(2):after{top:68px;left:56px}.lds-roller[_ngcontent-%COMP%]   div[_ngcontent-%COMP%]:nth-child(3){-webkit-animation-delay:-.108s;animation-delay:-.108s}.lds-roller[_ngcontent-%COMP%]   div[_ngcontent-%COMP%]:nth-child(3):after{top:71px;left:48px}.lds-roller[_ngcontent-%COMP%]   div[_ngcontent-%COMP%]:nth-child(4){-webkit-animation-delay:-.144s;animation-delay:-.144s}.lds-roller[_ngcontent-%COMP%]   div[_ngcontent-%COMP%]:nth-child(4):after{top:72px;left:40px}.lds-roller[_ngcontent-%COMP%]   div[_ngcontent-%COMP%]:nth-child(5){-webkit-animation-delay:-.18s;animation-delay:-.18s}.lds-roller[_ngcontent-%COMP%]   div[_ngcontent-%COMP%]:nth-child(5):after{top:71px;left:32px}.lds-roller[_ngcontent-%COMP%]   div[_ngcontent-%COMP%]:nth-child(6){-webkit-animation-delay:-.216s;animation-delay:-.216s}.lds-roller[_ngcontent-%COMP%]   div[_ngcontent-%COMP%]:nth-child(6):after{top:68px;left:24px}.lds-roller[_ngcontent-%COMP%]   div[_ngcontent-%COMP%]:nth-child(7){-webkit-animation-delay:-.252s;animation-delay:-.252s}.lds-roller[_ngcontent-%COMP%]   div[_ngcontent-%COMP%]:nth-child(7):after{top:63px;left:17px}.lds-roller[_ngcontent-%COMP%]   div[_ngcontent-%COMP%]:nth-child(8){-webkit-animation-delay:-.288s;animation-delay:-.288s}.lds-roller[_ngcontent-%COMP%]   div[_ngcontent-%COMP%]:nth-child(8):after{top:56px;left:12px}@-webkit-keyframes lds-roller{0%{-webkit-transform:rotate(0deg);transform:rotate(0deg)}to{-webkit-transform:rotate(1turn);transform:rotate(1turn)}}@keyframes lds-roller{0%{-webkit-transform:rotate(0deg);transform:rotate(0deg)}to{-webkit-transform:rotate(1turn);transform:rotate(1turn)}}']}),t}()},{path:"interim",component:m}],x=n("PCNd"),R=n("cUzu");n.d(e,"AuthLayoutModule",(function(){return w}));var w=function(){function t(){}return t.\u0275mod=s.Kb({type:t}),t.\u0275inj=s.Jb({factory:function(e){return new(e||t)},imports:[[o.c,i.e.forChild(S),r.b,x.a,R.c]]}),t}()}}]);