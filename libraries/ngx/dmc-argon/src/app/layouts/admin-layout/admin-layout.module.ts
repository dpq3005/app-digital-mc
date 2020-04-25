import {NgModule} from '@angular/core';
import {HttpClientModule} from '@angular/common/http';
import {RouterModule} from '@angular/router';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';

import {ClipboardModule} from 'ngx-clipboard';

import {AdminLayoutRoutes} from './admin-layout.routing';

import {NgbModule} from '@ng-bootstrap/ng-bootstrap';

// import { ToastrModule } from 'ngx-toastr';
import {NgSelectModule} from '@ng-select/ng-select';

import {DmcListComponent} from '../../pages/supervisor/dmc-list/dmc-list.component';
import {DmcSingleComponent} from '../../pages/supervisor/dmc-single/dmc-single.component';
import {NotFoundComponent} from '../../pages/not-found/not-found.component';
import {DmcListComponent as MerchantDmcListComponent} from '../../pages/merchant/dmc-list/dmc-list.component';
import {InfiniteScrollModule} from "ngx-infinite-scroll";
import { LogoutComponent } from '../../pages/logout/logout.component';
import {JwtHelperService, JwtModule, JwtModuleOptions} from "@auth0/angular-jwt";
import { ConfirmDmcRedemptionComponent } from '../../pages/merchant/dmc-redeem/confirm-dmc-redemption/confirm-dmc-redemption.component';

@NgModule({
  imports: [
    CommonModule,
    RouterModule.forChild(AdminLayoutRoutes),
    FormsModule,
    HttpClientModule,
    NgbModule,
    ClipboardModule,
    NgSelectModule,
    InfiniteScrollModule
  ],
  declarations: [
    DmcListComponent,
    DmcSingleComponent,
    NotFoundComponent,
    MerchantDmcListComponent,
    LogoutComponent,
    ConfirmDmcRedemptionComponent
  ]
})

export class AdminLayoutModule {
}
