import { Injectable } from '@angular/core';
import {
  CanLoad,
  Route,
  UrlSegment,
  ActivatedRouteSnapshot,
  RouterStateSnapshot,
  UrlTree,
  CanActivate, Router
} from '@angular/router';
import { Observable } from 'rxjs';
import {AuthService} from "../auth.service";

@Injectable({
  providedIn: 'any'
})
export class SupervisorAuthGuard implements CanLoad, CanActivate {

  constructor(private authService: AuthService, private router: Router) {
  }

  canLoad(
    route: Route,
    segments: UrlSegment[]): Observable<boolean> | Promise<boolean> | boolean {
    console.log('SupervisorAuthGuardSupervisorAuthGuardSupervisorAuthGuardSupervisorAuthGuardSupervisorAuthGuardSupervisorAuthGuardSupervisorAuthGuardSupervisorAuthGuardSupervisorAuthGuardSupervisorAuthGuard');
    console.log('route', route);
    console.log('segments', segments);
    return true;
  }

  canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<boolean | UrlTree> | Promise<boolean | UrlTree> | boolean | UrlTree {
    if (!this.authService.isAuthenticated()) {
      this.router.navigate(['supervisor', 'login']);
    }
    return true;
  }
}
