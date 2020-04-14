import { Routes } from '@angular/router';

import { LoginComponent } from '../../pages/login/login.component';
import { RegisterComponent } from '../../pages/register/register.component';
import { SupervisorLoginComponent } from '../../pages/supervisor/supervisor-login/supervisor-login.component';

export const AuthLayoutRoutes: Routes = [
    { path: 'login',          component: LoginComponent },
    { path: 'supervisor/login',          component: SupervisorLoginComponent },
    { path: 'register',       component: RegisterComponent }
];
