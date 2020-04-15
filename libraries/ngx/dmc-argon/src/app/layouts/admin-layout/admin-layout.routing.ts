import {Routes} from '@angular/router';

import {DashboardComponent} from '../../pages/dashboard/dashboard.component';
import {IconsComponent} from '../../pages/icons/icons.component';
import {MapsComponent} from '../../pages/maps/maps.component';
import {UserProfileComponent} from '../../pages/user-profile/user-profile.component';
import {TablesComponent} from '../../pages/tables/tables.component';

import {DmcListComponent} from '../../pages/supervisor/dmc-list/dmc-list.component';
import {DmcSingleComponent} from '../../pages/supervisor/dmc-single/dmc-single.component';
import {SupervisorAuthGuard} from "../../security/supervisor/supervisor-auth.guard";
import { NotFoundComponent } from '../../pages/not-found/not-found.component';

export const AdminLayoutRoutes: Routes = [
  {path: 'dashboard', component: DashboardComponent},
  {path: 'user-profile', component: UserProfileComponent},
  {path: 'tables', component: TablesComponent},
  {path: 'icons', component: IconsComponent},
  {path: 'maps', component: MapsComponent},
  {path: 'not-found', component: NotFoundComponent},
  {path: 'supervisor/dmc/list', component: DmcListComponent, canLoad: [SupervisorAuthGuard], canActivate: [SupervisorAuthGuard]},
  {path: 'supervisor/dmc/create', component: DmcSingleComponent},
  {path: 'supervisor/dmc/edit/:id', component: DmcSingleComponent},
];
