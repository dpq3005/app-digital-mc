import {Routes} from '@angular/router';
import {DmcListComponent} from '../../pages/supervisor/dmc-list/dmc-list.component';
import {DmcSingleComponent} from '../../pages/supervisor/dmc-single/dmc-single.component';
import {SupervisorAuthGuard} from "../../security/supervisor/supervisor-auth.guard";
import { NotFoundComponent } from '../../pages/not-found/not-found.component';
import {DmcListComponent as MerchantDmcListComponent} from '../../pages/merchant/dmc-list/dmc-list.component';
import {LogoutComponent} from "../../pages/logout/logout.component";
import {DmcRedeemComponent} from "../../pages/merchant/dmc-redeem/dmc-redeem.component";
import {ConfirmDmcRedemptionComponent} from "../../pages/merchant/dmc-redeem/confirm-dmc-redemption/confirm-dmc-redemption.component";
import {MerchantAuthGuard} from "../../security/merchant/merchant-auth.guard";
import {TelemedListComponent} from "../../pages/supervisor/telemed-list/telemed-list.component";
import {TelemedSingleComponent} from "../../pages/supervisor/telemed-single/telemed-single.component";
import {TelemedCreateSuccessfulComponent} from "../../pages/supervisor/telemed-single/telemed-create-successful/telemed-create-successful.component";

export const AdminLayoutRoutes: Routes = [
  {path: 'not-found', component: NotFoundComponent},
  {path: 'supervisor/dmc/list', component: DmcListComponent, canLoad: [SupervisorAuthGuard], canActivate: [SupervisorAuthGuard]},
  {path: 'supervisor/dmc/create', component: DmcSingleComponent, canLoad: [SupervisorAuthGuard], canActivate: [SupervisorAuthGuard]},
  {path: 'supervisor/dmc/edit/:id', component: DmcSingleComponent, canLoad: [SupervisorAuthGuard], canActivate: [SupervisorAuthGuard]},

  {path: 'supervisor/telemed/list', component: TelemedListComponent, canLoad: [SupervisorAuthGuard], canActivate: [SupervisorAuthGuard]},
  {path: 'supervisor/telemed/create', component: TelemedSingleComponent, canLoad: [SupervisorAuthGuard], canActivate: [SupervisorAuthGuard]},
  {path: 'supervisor/telemed/create/successful', component: TelemedCreateSuccessfulComponent, canLoad: [SupervisorAuthGuard], canActivate: [SupervisorAuthGuard]},

  {path: 'merchant/dmc/list', component: MerchantDmcListComponent, canActivate: [MerchantAuthGuard]},
  {path: 'merchant/dmc/:id/redeem', component: DmcRedeemComponent, canActivate: [MerchantAuthGuard]},

  {path: 'merchant/dmc/:id/redeem/validate-pin', component: ConfirmDmcRedemptionComponent,  canActivate: [MerchantAuthGuard]},

  {path: 'logout', component: LogoutComponent},

];
