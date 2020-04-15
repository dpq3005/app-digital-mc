import { Injectable } from '@angular/core';
import {
  CanLoad,
  Route,
  UrlSegment,
  ActivatedRouteSnapshot,
  RouterStateSnapshot,
  UrlTree,
  CanActivate
} from '@angular/router';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class SupervisorAuthGuard implements CanLoad, CanActivate {
  canLoad(
    route: Route,
    segments: UrlSegment[]): Observable<boolean> | Promise<boolean> | boolean {
    console.log('SupervisorAuthGuardSupervisorAuthGuardSupervisorAuthGuardSupervisorAuthGuardSupervisorAuthGuardSupervisorAuthGuardSupervisorAuthGuardSupervisorAuthGuardSupervisorAuthGuardSupervisorAuthGuard');
    console.log('route', route);
    console.log('segments', segments);
    return true;
  }

  canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<boolean | UrlTree> | Promise<boolean | UrlTree> | boolean | UrlTree {
    console.log('routeeeeeeeeee ', route);
    return true;
  }
}
