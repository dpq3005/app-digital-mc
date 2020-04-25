import {Routes} from '@angular/router';
import {DmcListComponent} from '../../pages/supervisor/dmc-list/dmc-list.component';
import {DmcSingleComponent} from '../../pages/supervisor/dmc-single/dmc-single.component';
import {SupervisorAuthGuard} from "../../security/supervisor/supervisor-auth.guard";
import { NotFoundComponent } from '../../pages/not-found/not-found.component';
import {DmcListComponent as MerchantDmcListComponent} from '../../pages/merchant/dmc-list/dmc-list.component';
import {LogoutComponent} from "../../pages/logout/logout.component";
import {DmcRedeemComponent} from "../../pages/merchant/dmc-redeem/dmc-redeem.component";

export const AdminLayoutRoutes: Routes = [
  {path: 'not-found', component: NotFoundComponent},
  {path: 'supervisor/dmc/list', component: DmcListComponent, canLoad: [SupervisorAuthGuard], canActivate: [SupervisorAuthGuard]},
  {path: 'supervisor/dmc/create', component: DmcSingleComponent, canLoad: [SupervisorAuthGuard], canActivate: [SupervisorAuthGuard]},
  {path: 'supervisor/dmc/edit/:id', component: DmcSingleComponent, canLoad: [SupervisorAuthGuard], canActivate: [SupervisorAuthGuard]},

  {path: 'merchant/dmc/list', component: MerchantDmcListComponent, canLoad: [SupervisorAuthGuard], canActivate: [SupervisorAuthGuard]},
  {path: 'merchant/dmc/:id/redeem', component: DmcRedeemComponent, canLoad: [SupervisorAuthGuard], canActivate: [SupervisorAuthGuard]},
  {path: 'logout', component: LogoutComponent},

];
