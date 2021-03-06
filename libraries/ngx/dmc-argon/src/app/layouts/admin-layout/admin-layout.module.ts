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
import {SharedModule} from "../../shared/shared.module";
import {TelemedListComponent} from "../../pages/supervisor/telemed-list/telemed-list.component";
import {TelemedSingleComponent} from "../../pages/supervisor/telemed-single/telemed-single.component";
import { TelemedCreateSuccessfulComponent } from '../../pages/supervisor/telemed-single/telemed-create-successful/telemed-create-successful.component';

@NgModule({
  imports: [
    CommonModule,
    RouterModule.forChild(AdminLayoutRoutes),
    FormsModule,
    HttpClientModule,
    NgbModule,
    ClipboardModule,
    NgSelectModule,
    InfiniteScrollModule,
    SharedModule
  ],
  declarations: [
    DmcListComponent,
    DmcSingleComponent,
    TelemedListComponent,
    TelemedSingleComponent,
    TelemedSingleComponent,
    NotFoundComponent,
    MerchantDmcListComponent,
    LogoutComponent,
    ConfirmDmcRedemptionComponent,
    TelemedCreateSuccessfulComponent
  ]
})

export class AdminLayoutModule {
}
