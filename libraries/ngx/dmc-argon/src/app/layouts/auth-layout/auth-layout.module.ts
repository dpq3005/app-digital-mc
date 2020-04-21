import {NgModule} from '@angular/core';
import {RouterModule} from '@angular/router';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {AuthLayoutRoutes} from './auth-layout.routing';
import {NgbModule} from '@ng-bootstrap/ng-bootstrap';

import {LoginComponent} from '../../pages/login/login.component';
import {RegisterComponent} from '../../pages/register/register.component';
import {SupervisorLoginComponent} from '../../pages/supervisor/supervisor-login/supervisor-login.component';
import {InterimComponent} from '../../pages/interim/interim.component';
import {MerchantLoginComponent} from '../../pages/merchant/merchant-login/merchant-login.component';
import {PinDigitDirective} from '../../directives/pin-digit/pin-digit.directive';
import {SharedModule} from "../../shared/shared.module";
import {JwtHelperService, JwtModule, JwtModuleOptions} from "@auth0/angular-jwt";
import {HttpClientModule} from "@angular/common/http";

@NgModule({
  imports: [
    CommonModule,
    RouterModule.forChild(AuthLayoutRoutes),
    FormsModule,
    SharedModule,
    HttpClientModule
    // NgbModule
  ],
  declarations: [
    LoginComponent,
    RegisterComponent,
    SupervisorLoginComponent,
    InterimComponent,
    MerchantLoginComponent
  ]
})
export class AuthLayoutModule {
}
