import {Injectable} from '@angular/core';
import {
  CanLoad,
  Route,
  UrlSegment,
  ActivatedRouteSnapshot,
  RouterStateSnapshot,
  UrlTree,
  CanActivate, Router
} from '@angular/router';
import {Observable, ObservableInput} from 'rxjs';
import {AuthService} from "../auth.service";
import {catchError} from "rxjs/operators";

@Injectable({
  providedIn: 'any'
})
export class SupervisorAuthGuard implements CanLoad, CanActivate {

  constructor(private authService: AuthService, private router: Router) {
  }

  canLoad(
    route: Route,
    segments: UrlSegment[]): Observable<boolean> | Promise<boolean> | boolean {

    console.log('route', route);
    console.log('segments', segments);
    return true;
  }

  canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<boolean | UrlTree> | Promise<boolean | UrlTree> | boolean | UrlTree {
    if (!this.authService.isAuthenticated()) {
      if (localStorage.getItem('credentials') === null) {
        this.router.navigate(['supervisor', 'login']);
      }

      try {
        this.authService.reAuthenticate().pipe(catchError((err, caught): ObservableInput<any> => {
          this.router.navigate(['supervisor', 'login']);
          return new Observable();
        })).subscribe(jwt => {
          localStorage.setItem('token', jwt.token);
          localStorage.setItem('benefitProviderUuid', jwt.benefitProviderUuid);
          document.location.reload();
          return true;
        });
      } catch (exception) {
        this.router.navigate(['supervisor', 'login']);
      }
    }
    return true;
  }
}
