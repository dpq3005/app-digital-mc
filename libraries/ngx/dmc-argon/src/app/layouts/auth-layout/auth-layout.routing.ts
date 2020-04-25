import { Routes } from '@angular/router';

import { SupervisorLoginComponent } from '../../pages/supervisor/supervisor-login/supervisor-login.component';
import { InterimComponent } from '../../pages/interim/interim.component';
import {DmcListComponent} from "../../pages/supervisor/dmc-list/dmc-list.component";
import {SupervisorAuthGuard} from "../../security/supervisor/supervisor-auth.guard";
import { MerchantLoginComponent } from '../../pages/merchant/merchant-login/merchant-login.component';

export const AuthLayoutRoutes: Routes = [
  { path: 'supervisor/login',          component: SupervisorLoginComponent },
  { path: 'merchant/login',          component: MerchantLoginComponent },
  { path: 'interim',          component: InterimComponent}
];
